<?php

namespace App\Http\Controllers;

use App\Models\Rating;
use App\Models\RatingReply;
use Illuminate\Http\Request;

class RatingReplyController extends Controller
{
    public function store(Request $request, Rating $rating)
    {
        // Validasi: hanya admin atau operator yang bisa reply
        if (!auth()->user()->isAdmin() && !auth()->user()->isOperator()) {
            abort(403, 'Unauthorized');
        }

        $request->validate([
            'reply_text' => 'required|string|max:1000',
        ]);

        RatingReply::create([
            'rating_id' => $rating->id,
            'user_id' => auth()->id(),
            'reply_text' => $request->reply_text,
        ]);

        return back()->with('success', 'Balasan rating berhasil ditambahkan');
    }

    public function destroy(RatingReply $reply)
    {
        // Validasi: hanya admin, operator, atau pemilik reply yang bisa hapus
        if (!auth()->user()->isAdmin() && !auth()->user()->isOperator() && $reply->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        $reply->delete();

        return back()->with('success', 'Balasan rating berhasil dihapus');
    }
}
