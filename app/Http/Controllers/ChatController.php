<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Message;


class ChatController extends Controller
{
    /**
     * Display a list of conversations.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $filter = $request->get('filter', 'all');

        // Base query with additional CS user info
        $query = Conversation::with([
            "user", 
            "latestMessage.user",
            "messages" => function($query) use ($user) {
                // Load one CS message per conversation for name display
                $query->where('user_id', '!=', $user->id)
                      ->whereNotNull('user_id')
                      ->with('user')
                      ->latest()
                      ->limit(1);
            }
        ])->latest('last_activity_at');

        if ($user->isAdmin()) {
            // Admins see all conversations
            $allConversations = $query->get();
        } elseif ($user->isOperator()) {
            // Operators see staff and internal conversations
            $allConversations = $query->whereIn("visibility", ["staff", "internal"])->get();
        } else {
            // Regular users can only see their own conversations
            $allConversations = $user->conversations()->with([
                "latestMessage.user",
                "messages" => function($query) use ($user) {
                    // Load one CS message per conversation for name display
                    $query->where('user_id', '!=', $user->id)
                          ->whereNotNull('user_id')
                          ->with('user')
                          ->latest()
                          ->limit(1);
                }
            ])->latest('last_activity_at')->get();
        }

        // Calculate statistics from ALL conversations (before filtering)
        $userRole = $user->isAdmin() ? 'admin' : ($user->isOperator() ? 'operator' : 'user');
        
        $stats = [
            'total' => $allConversations->count(),
            'unread' => Conversation::getUnreadCount($userRole),
            'guest' => $allConversations->where('type', 'guest')->count(),
            'admin_user' => $allConversations->where('type', 'admin_user')->count(),
            'operator_user' => $allConversations->where('type', 'operator_user')->count(),
            'internal' => $allConversations->where('type', 'internal')->count(),
        ];

        // Filter conversations by type for display
        if ($filter !== 'all') {
            $conversations = $allConversations->filter(function ($conversation) use ($filter) {
                return $conversation->type === $filter;
            });
        } else {
            $conversations = $allConversations;
        }

        // Users for creating conversations (exclude current user)
        $admins = User::role('admin')->where('id', '!=', $user->id)->get();
        $operators = User::role('operator')->where('id', '!=', $user->id)->get();
        $users = User::role('user')->where('id', '!=', $user->id)->get();

        // Limit recipient options based on user role
        if ($user->isUser()) {
            // Regular users can only message admin and operators
            $usersByRole = [
                'admin' => $admins,
                'operator' => $operators,
            ];
        } else {
            // Admin and operators can message everyone
            $usersByRole = [
                'admin' => $admins,
                'operator' => $operators,
                'user' => $users,
            ];
        }

        return view("chat.index", compact("conversations", "usersByRole", "stats", "filter"));
    }



    /**
     * Get users for conversation creation (AJAX).
     */
    public function getUsers(Request $request)
    {
        $user = Auth::user();

        // Only admin and operators can access this
        if (!$user->isAdmin() && !$user->isOperator()) {
            return response()->json(["error" => "Unauthorized"], 403);
        }

        $search = $request->get("search", "");
        $role = $request->get("role", "user"); // Default to 'user' role

        $users = User::whereHas("roles", function ($query) use ($role) {
            $query->where("name", $role);
        })
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where("name", "like", "%{$search}%")->orWhere(
                        "email",
                        "like",
                        "%{$search}%",
                    );
                });
            })
            ->select("id", "name", "email", "profile_photo_path")
            ->limit(20)
            ->get()
            ->map(function ($user) {
                return [
                    "id" => $user->id,
                    "name" => $user->name,
                    "email" => $user->email,
                    "profile_photo_path" => $user->profile_photo_path,
                    "profile_photo_url" => $user->profile_photo_url,
                ];
            });

        return response()->json($users);
    }

    /**
     * Store a new conversation.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        // All authenticated users can create conversations
        // But we'll validate recipient based on user role below

        // Validate input
        $validated = $request->validate([
            "subject" => "required|string|max:255",
            "message" => "required|string|max:5000",
            "recipient_id" => "required|integer|exists:users,id",
        ]);

        // Additional check: prevent user from messaging themselves
        if ($validated['recipient_id'] == $user->id) {
            return back()->withErrors(['recipient_id' => 'Anda tidak dapat mengirim pesan kepada diri sendiri.'])->withInput();
        }

        $recipient = User::findOrFail($validated['recipient_id']);
        
        // Validate recipient based on sender role
        if ($user->isUser()) {
            // Regular users can only message admin and operators
            if (!$recipient->isAdmin() && !$recipient->isOperator()) {
                return back()->withErrors(['recipient_id' => 'Anda hanya dapat mengirim pesan kepada Admin atau Operator.'])->withInput();
            }
        }
        
        // Determine conversation visibility based on sender and recipient roles
        $visibility = $this->determineVisibility($user, $recipient);
        
        // Set user_id based on conversation type
        // For conversations involving regular users, user_id should be the regular user
        // For internal conversations (admin-operator), user_id is the sender
        $conversationUserId = $user->id;
        if ($recipient->isUser() && !$user->isUser()) {
            // Admin/Operator sending to User - user_id should be the user
            $conversationUserId = $recipient->id;
        }
        
        // Create conversation
        $conversation = Conversation::create([
            "user_id" => $conversationUserId,
            "subject" => $validated['subject'],
            "status" => "open",
            "visibility" => $visibility,
            "last_activity_at" => now(),
        ]);

        // Create first message
        $conversation->messages()->create([
            "user_id" => $user->id,
            "body" => $validated['message'],
        ]);

        // Set unread notifications for recipient
        $senderRole = $user->isAdmin() ? 'admin' : ($user->isOperator() ? 'operator' : 'user');
        $conversation->markAsUnreadForOthers($senderRole);

        return redirect()->route("chat.show", $conversation)
            ->with("success", "Percakapan berhasil dibuat dengan {$recipient->name}.");
    }

    /**
     * Determine conversation visibility based on participants
     */
    private function determineVisibility($sender, $recipient)
    {
        $senderRole = $sender->isAdmin() ? 'admin' : ($sender->isOperator() ? 'operator' : 'user');
        $recipientRole = $recipient->isAdmin() ? 'admin' : ($recipient->isOperator() ? 'operator' : 'user');

        // Admin ↔ Operator = internal
        if (($senderRole === 'admin' && $recipientRole === 'operator') || 
            ($senderRole === 'operator' && $recipientRole === 'admin')) {
            return 'internal';
        }

        // Admin ↔ User = admin_only
        if (($senderRole === 'admin' && $recipientRole === 'user') || 
            ($senderRole === 'user' && $recipientRole === 'admin')) {
            return 'admin_only';
        }

        // Operator ↔ User = staff
        if (($senderRole === 'operator' && $recipientRole === 'user') || 
            ($senderRole === 'user' && $recipientRole === 'operator')) {
            return 'staff';
        }

        // Default fallback
        return 'staff';
    }


    /**
     * Display a specific conversation.
     */
    public function show(Conversation $conversation)
    {
        // Authorize that the user can view this conversation
        $this->authorizeUser("view", $conversation);

        // Mark as read for current user
        $user = Auth::user();
        $userRole = $user->isAdmin() ? 'admin' : ($user->isOperator() ? 'operator' : 'user');
        $conversation->markAsRead($userRole);

        // Eager load messages with the sender information
        $conversation->load("messages.user");

        return view("chat.show", compact("conversation"));
    }

    /**
     * Get all messages for a conversation (for AJAX polling).
     */
    public function getMessages(Conversation $conversation)
    {
        $this->authorizeUser("view", $conversation);

        $messages = $conversation->messages()->with("user")->get();

        return response()->json($messages);
    }

    /**
     * Store a new message in a conversation.
     */
    public function storeMessage(Request $request, Conversation $conversation)
    {
        $this->authorizeUser("reply", $conversation);

        $request->validate([
            "body" => "required|string|max:5000",
        ]);

        $message = $conversation->messages()->create([
            "user_id" => Auth::id(),
            "body" => $request->body,
        ]);

        // Update conversation notifications
        $user = Auth::user();
        $senderRole = $user->isAdmin() ? 'admin' : ($user->isOperator() ? 'operator' : 'user');
        $conversation->markAsUnreadForOthers($senderRole);

        // Load the user relationship for the response
        $message->load("user");

        return response()->json($message, 201);
    }

    /**
     * Helper function for authorization.
     * In a larger application, this would be a FormRequest or a Policy.
     */
    private function authorizeUser(string $action, Conversation $conversation)
{
    $user = Auth::user();

    // Deny operators from accessing admin_only conversations
    if ($user->isOperator() && $conversation->visibility === "admin_only") {
        abort(403, "This action is unauthorized.");
    }

    $isParticipant = $conversation->user_id === $user->id;
    $isStaff = $user->isAdmin() || $user->isOperator();
    $isGuestConversation = $conversation->user_id === null;

    // Untuk aksi close/reopen/delete
    if (in_array($action, ["close", "reopen", "delete", "restore", "force_delete"])) {
        if ($isStaff || $isParticipant) {
            return; // authorized
        }
        abort(403, "This action is unauthorized.");
    }

    // Default rule (view/reply)
    // Staff can view all conversations including guest conversations
    // Users can only view their own conversations
    if ($isStaff) {
        return; // Staff can view everything
    }
    
    if ($isGuestConversation) {
        // Guest conversations can only be viewed by staff
        abort(403, "This action is unauthorized.");
    }
    
    if (!$isParticipant) {
        abort(403, "This action is unauthorized.");
    }

    if ($action === "important") {
    if ($isStaff) { // hanya admin/operator
        return;
    }
    abort(403, "This action is unauthorized.");
}

}


    public function close(Conversation $conversation)
{
    $this->authorizeUser("close", $conversation);

    if ($conversation->status === "closed") {
        return redirect()
            ->route("chat.show", $conversation)
            ->with("info", "Percakapan sudah ditutup sebelumnya.");
    }

    $conversation->update([
        "status" => "closed"
    ]);

    return redirect()
        ->route("chat.index")
        ->with("success", "Percakapan berhasil ditutup.");
}


public function reopen(Conversation $conversation)
{
    $this->authorizeUser("reopen", $conversation);

    if ($conversation->status === "open") {
        return redirect()
            ->route("chat.show", $conversation)
            ->with("info", "Percakapan sudah terbuka.");
    }

    $conversation->update([
        "status" => "open"
    ]);

    return redirect()
        ->route("chat.show", $conversation)
        ->with("success", "Percakapan berhasil dibuka kembali.");
}
public function toggleImportant(Conversation $conversation)
{
    if (!auth()->user()->isAdmin() && !auth()->user()->isOperator()) {
        abort(403, 'Unauthorized');
    }

    $conversation->is_important = !$conversation->is_important; // toggle status
    $conversation->save();

    if ($conversation->is_important) {
        return back()->with('success', 'Percakapan ditandai penting.');
    } else {
        return back()->with('success', 'Tanda penting dihapus.');
    }
}




public function destroy(Conversation $conversation)
{
    \Log::info('Delete conversation attempt', [
        'conversation_id' => $conversation->id,
        'user_id' => Auth::id(),
        'user_role' => Auth::user()->isAdmin() ? 'admin' : (Auth::user()->isOperator() ? 'operator' : 'user')
    ]);
    
    try {
        $this->authorizeUser("delete", $conversation);
        \Log::info('Authorization passed for conversation delete', ['conversation_id' => $conversation->id]);
        
        $conversation->delete();
        \Log::info('Conversation deleted successfully', ['conversation_id' => $conversation->id]);
        
        return redirect()->route('chat.index')->with('success', 'Percakapan berhasil dihapus.');
    } catch (\Exception $e) {
        \Log::error('Error deleting conversation', [
            'conversation_id' => $conversation->id,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        
        return redirect()->route('chat.index')->with('error', 'Gagal menghapus percakapan: ' . $e->getMessage());
    }
}

public function restore($id)
{
    $conversation = Conversation::withTrashed()->findOrFail($id);
    $this->authorizeUser("restore", $conversation);
    
    $conversation->restore();
    
    return redirect()->route('chat.index')->with('success', 'Percakapan berhasil dipulihkan.');
}

public function forceDelete($id)
{
    $conversation = Conversation::withTrashed()->findOrFail($id);
    $this->authorizeUser("force_delete", $conversation);
    
    $conversation->forceDelete();
    
    return redirect()->route('chat.index')->with('success', 'Percakapan berhasil dihapus permanen.');
}




}
