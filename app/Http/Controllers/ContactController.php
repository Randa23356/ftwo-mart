<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Conversation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ContactController extends Controller
{
    /**
     * Show the contact form.
     */
    public function show()
    {
        return view("contact");
    }

    /**
     * Store a new message from the contact form and create a conversation.
     */
    public function store(Request $request)
    {
        // Jika user belum login, tambahkan validasi untuk nama dan email
        $validationRules = [
            "subject" => "required|string|max:255",
            "message" => "required|string|max:5000",
        ];

        // Jika user belum login ATAU belum verifikasi email, minta nama dan email
        if (!Auth::check() || (Auth::check() && !Auth::user()->hasVerifiedEmail())) {
            $validationRules["name"] = "required|string|max:255";
            $validationRules["email"] = "required|email|max:255";
        }

        $request->validate($validationRules);

        try {
            DB::beginTransaction();

            if (Auth::check() && Auth::user()->hasVerifiedEmail()) {
                // User sudah login DAN email terverifikasi - buat conversation normal
                $conversation = Conversation::create([
                    "user_id" => Auth::id(),
                    "subject" => $request->subject,
                    "visibility" => "admin_only",
                ]);

                $conversation->messages()->create([
                    "user_id" => Auth::id(),
                    "body" => $request->message,
                ]);

                DB::commit();

                return redirect()
                    ->route("chat.show", $conversation)
                    ->with(
                        "success",
                        "Pesan Anda telah dikirim dan percakapan baru telah dimulai!",
                    );
            } else {
                // User belum login ATAU belum verifikasi email - simpan sebagai guest message
                $senderInfo = Auth::check() ? 
                    " (dari user: " . Auth::user()->name . " - " . Auth::user()->email . " - BELUM VERIFIKASI)" :
                    " (dari: " . $request->name . ")";
                
                $conversation = Conversation::create([
                    "user_id" => null, // Guest user
                    "subject" => $request->subject . $senderInfo,
                    "visibility" => "admin_only",
                ]);

                // Simpan pesan dengan informasi guest
                $guestMessage = "Nama: " . $request->name . "\n";
                $guestMessage .= "Email: " . $request->email . "\n";
                
                if (Auth::check()) {
                    $guestMessage .= "Status: User terdaftar tapi email belum diverifikasi\n";
                    $guestMessage .= "User ID: " . Auth::id() . "\n";
                }
                
                $guestMessage .= "\n" . $request->message;

                $conversation->messages()->create([
                    "user_id" => null, // Guest message
                    "body" => $guestMessage,
                ]);

                DB::commit();

                return redirect()
                    ->route("contact")
                    ->with(
                        "success",
                        "Pesan Anda telah dikirim! Admin akan menghubungi Anda melalui email yang Anda berikan.",
                    );
            }
        } catch (\Exception $e) {
            DB::rollBack();
            logger()->error(
                "Failed to create conversation from contact form: " .
                    $e->getMessage(),
            );
            return back()->with(
                "error",
                "Gagal mengirim pesan. Silakan coba lagi.",
            );
        }
    }
}
