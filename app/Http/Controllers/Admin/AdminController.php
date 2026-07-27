<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules;
use App\Models\Category;
use App\Models\Conversation;
use Illuminate\Support\Facades\Mail;
use App\Mail\GuestReplyMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
        view()->share('orderCounts', $this->getOrderCounts());
    }

    private function getOrderCounts(): array
    {
        return [
            'pending'    => Order::where('order_status', 'pending')->count(),
            'processing' => Order::where('order_status', 'processing')->count(),
            'ready'      => Order::where('order_status', 'ready')->count(),
            'shipped'    => Order::where('order_status', 'shipped')->count(),
            'delivered'  => Order::where('order_status', 'delivered')->count(),
            'cancelled'  => Order::where('order_status', 'cancelled')->count(),
        ];
    }

    public function dashboard()
    {
        $totalOrders = Order::count();
        $totalProducts = Product::count();
        $totalUsers = User::count();
        $totalCategories = Category::count();
        $totalConversations = Conversation::count();
        $guestMessages = Conversation::whereNull('user_id')->count();

        $recentOrders = Order::with('user')->latest()->take(5)->get();
        
        $monthlyRevenue = Order::where('payment_status', 'paid')
            ->whereYear('created_at', date('Y'))
            ->selectRaw('MONTH(created_at) as month, SUM(total_amount) as revenue')
            ->groupBy('month')
            ->pluck('revenue', 'month')
            ->toArray();

        $topProducts = Product::withTrashed()
            ->withCount('orderItems')
            ->orderBy('order_items_count', 'desc')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalOrders',
            'totalProducts', 
            'totalUsers',
            'totalCategories',
            'totalConversations',
            'guestMessages',
            'recentOrders',
            'monthlyRevenue',
            'topProducts'
        ));
    }

    public function orders()
    {
        $orders = Order::with(['user', 'orderItems.product' => function($query) {
                $query->withTrashed();
            }])
            ->latest()
            ->paginate(15);

        return view('admin.orders.index', compact('orders'));
    }

    public function pendingOrders()
    {
        $orders = Order::where('order_status', 'pending')
            ->with(['user', 'orderItems.product' => function($query) {
                $query->withTrashed();
            }])
            ->latest()
            ->paginate(15);

        return view('admin.orders.index', compact('orders'));
    }

    public function processingOrders()
    {
        $orders = Order::where('order_status', 'processing')
            ->with(['user', 'orderItems.product' => function($query) {
                $query->withTrashed();
            }])
            ->latest()
            ->paginate(15);

        return view('admin.orders.index', compact('orders'));
    }

    public function readyOrders()
    {
        $orders = Order::where('order_status', 'ready')
            ->with(['user', 'orderItems.product' => function($query) {
                $query->withTrashed();
            }])
            ->latest()
            ->paginate(15);

        return view('admin.orders.index', compact('orders'));
    }

    public function shippedOrders()
    {
        $orders = Order::where('order_status', 'shipped')
            ->with(['user', 'orderItems.product' => function($query) {
                $query->withTrashed();
            }])
            ->latest()
            ->paginate(15);

        return view('admin.orders.index', compact('orders'));
    }

    public function deliveredOrders()
    {
        $orders = Order::where('order_status', 'delivered')
            ->with(['user', 'orderItems.product' => function($query) {
                $query->withTrashed();
            }])
            ->latest()
            ->paginate(15);

        return view('admin.orders.index', compact('orders'));
    }

    public function cancelledOrders()
    {
        $orders = Order::where('order_status', 'cancelled')
            ->with(['user', 'orderItems.product' => function($query) {
                $query->withTrashed();
            }])
            ->latest()
            ->paginate(15);

        return view('admin.orders.index', compact('orders'));
    }

    public function trashOrders()
    {
        $orders = Order::onlyTrashed()
            ->with(['user', 'orderItems.product' => function($query) {
                $query->withTrashed();
            }])
            ->latest()
            ->paginate(15);

        return view('admin.orders.trash', compact('orders'));
    }

    public function destroyOrder(Order $order)
    {
        try {
            $order->delete();
            return redirect()->route('admin.orders')->with('success', 'Pesanan berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus pesanan.');
        }
    }

    public function restoreOrder($id)
    {
        $order = Order::onlyTrashed()->findOrFail($id);
        $order->restore();

        return back()->with('success', 'Pesanan berhasil dipulihkan.');
    }

    public function forceDeleteOrder($id)
    {
        $order = Order::onlyTrashed()->findOrFail($id);
        $order->forceDelete();

        return back()->with('success', 'Pesanan berhasil dihapus permanen.');
    }

    public function orderDetail(Order $order)
    {
        $order->load(['user', 'orderItems.product' => function($query) {
            $query->withTrashed();
        }, 'paymentTransaction', 'histories.user']);
        return view('admin.orders.show', compact('order'));
    }

    public function updateOrderStatus(Request $request, Order $order)
    {
        $oldStatus = $order->order_status;

        // Guard: prevent changes to already cancelled orders
        if ($oldStatus === 'cancelled') {
            return back()->with('error', 'Pesanan telah dibatalkan dan tidak dapat diubah.');
        }

        // Guard: require payment for non-COD orders
        if ($order->payment_method !== 'cod' && $order->payment_status !== 'paid') {
            return back()->with('error', 'Pesanan belum dibayar. Tidak dapat memproses pesanan sebelum pembayaran lunas.');
        }

        // Strict status transitions matching seller flow exactly
        $allowed = $this->getAllowedStatuses($oldStatus);

        $request->validate([
            'order_status' => 'required|in:' . implode(',', $allowed),
            'tracking_number' => 'required_if:order_status,shipped|nullable|string|max:255',
            'notes' => 'nullable|string|max:500',
        ]);

        if (!in_array($request->order_status, $allowed)) {
            return back()->with('error', 'Status tidak valid untuk pesanan ini. Hanya boleh: ' . implode(', ', $allowed));
        }

        $updates = ['order_status' => $request->order_status];

        if ($request->order_status === 'cancelled') {
            // Cancel order and restore stock
            $order->cancelOrder('Dibatalkan oleh admin: ' . ($request->notes ?? 'Tidak ada keterangan'));
            $order->logStatusChange($oldStatus, 'cancelled', Auth::id(), 'admin', 'Dibatalkan oleh admin');
            return back()->with('success', 'Pesanan berhasil dibatalkan dan stok dikembalikan.');
        }

        if ($request->order_status === 'shipped' && $request->tracking_number) {
            $updates['tracking_number'] = $request->tracking_number;
            $updates['shipped_at'] = now();
        }

        $order->update($updates);
        $order->logStatusChange($oldStatus, $request->order_status, Auth::id(), 'admin', $request->notes ?? null);

        return back()->with('success', 'Status pesanan berhasil diupdate');
    }

    /**
     * Get allowed status transitions - SAME as seller flow (no direct delivered,
     * delivered only via COD buyer confirmation or system auto-complete 3 days after courier scan)
     */
    private function getAllowedStatuses(string $current): array
    {
        return match ($current) {
            'pending' => ['processing', 'cancelled'],
            'processing' => ['ready', 'cancelled'],
            'ready' => ['shipped', 'cancelled'],
            'shipped' => [],
            'delivered' => [],
            default => [],
        };
    }

    /**
     * Confirm return received (matching seller confirmReturn)
     */
    public function confirmReturn(Order $order)
    {
        $refund = $order->refundRequest;

        if (!$refund || $refund->status !== 'return_shipped') {
            return back()->with('error', 'Pengembalian belum dikirim oleh pembeli.');
        }

        $refund->update([
            'status' => 'completed',
            'seller_returned_at' => now(),
        ]);

        $order->update(['refund_status' => 'completed']);

        $order->cancelOrder('Pengembalian barang dikonfirmasi diterima oleh admin');

        $order->logStatusChange(
            'shipped',
            'cancelled',
            Auth::id(),
            'admin',
            'Barang retur diterima. Pesanan dibatalkan dan stok dikembalikan.'
        );

        return back()->with('success', 'Barang retur dikonfirmasi diterima. Pesanan dibatalkan dan stok dikembalikan.');
    }

    public function updateTrackingNumber(Request $request, Order $order)
    {
        $request->validate([
            'tracking_number' => 'required|string|max:255'
        ]);

        try {
            $order->update([
                'tracking_number' => $request->tracking_number,
                'shipped_at' => $order->shipped_at ?? now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Nomor resi berhasil disimpan.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan nomor resi: ' . $e->getMessage()
            ], 500);
        }
    }

    public function users(Request $request)
    {
        $query = User::with('roles');
        
        // Filter by role if provided
        if ($request->has('role') && $request->role) {
            $query->whereHas('roles', function($q) use ($request) {
                $q->where('name', $request->role);
            });
        }
        
        $users = $query->paginate(15)->withQueryString();

        $userStats = [
            'total' => User::count(),
            'customers' => User::role('user')->count(),
            'operators' => User::role('operator')->count(),
            'admins' => User::role('admin')->count(),
        ];

        return view('admin.users.index', compact('users', 'userStats'));
    }

    public function userDetail(User $user)
    {
        $user->load(['orders', 'roles']);
        return view('admin.users.show', compact('user'));
    }

    public function toggleUserStatus(User $user)
    {
        $user->update(['is_active' => !$user->is_active]);
        
        $status = $user->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('success', "User berhasil {$status}");
    }

    public function create()
    {
        $roles = Role::all();
        return view('admin.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'exists:roles,name'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $user->assignRole($request->role);

        return redirect()->route('admin.users')->with('success', 'Pengguna baru berhasil ditambahkan.');
    }

    public function reports()
    {
        $monthlyOrders = Order::selectRaw('MONTH(created_at) as month, COUNT(*) as total')
            ->whereYear('created_at', date('Y'))
            ->groupBy('month')
            ->pluck('total', 'month')
            ->toArray();

        $monthlyRevenue = Order::where('payment_status', 'paid')
            ->selectRaw('MONTH(created_at) as month, SUM(total_amount) as revenue')
            ->whereYear('created_at', date('Y'))
            ->groupBy('month')
            ->pluck('revenue', 'month')
            ->toArray();

        $topSellingProducts = Product::withTrashed()
            ->withCount('orderItems')
            ->orderBy('order_items_count', 'desc')
            ->take(10)
            ->get();

        $paymentMethodStats = Order::selectRaw('payment_method, COUNT(*) as total')
            ->groupBy('payment_method')
            ->pluck('total', 'payment_method')
            ->toArray();

        return view('admin.reports', compact(
            'monthlyOrders',
            'monthlyRevenue',
            'topSellingProducts',
            'paymentMethodStats'
        ));
    }

    public function emailReply(Request $request, Conversation $conversation)
    {
        // Validate that this is a guest conversation
        if ($conversation->user_id !== null) {
            return response()->json(['success' => false, 'message' => 'This is not a guest conversation'], 400);
        }

        $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:5000',
            'guest_email' => 'required|email',
            'guest_name' => 'nullable|string|max:255'
        ]);

        try {
            // Extract guest name if provided
            $guestName = $request->guest_name ?: null;

            // Send email using Mailable class
            Mail::to($request->guest_email)->send(
                new GuestReplyMail($request->subject, $request->message, $guestName)
            );

            // Log the email reply in the conversation
            $conversation->messages()->create([
                'user_id' => auth()->id(),
                'body' => "[EMAIL SENT]\nTo: {$request->guest_email}\nSubject: {$request->subject}\n\n{$request->message}"
            ]);

            // Mark conversation as replied
            $conversation->update(['replied_at' => now()]);

            return response()->json([
                'success' => true, 
                'message' => 'Email berhasil dikirim ke ' . $request->guest_email
            ]);

        } catch (\Exception $e) {
            \Log::error('Failed to send email reply: ' . $e->getMessage());
            
            return response()->json([
                'success' => false, 
                'message' => 'Gagal mengirim email: ' . $e->getMessage()
            ], 500);
        }
    }
}
