<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\RefundRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class RefundController extends Controller
{
    public function request(Request $request, Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        if (!$order->isAwaitingBuyerConfirmation()) {
            return back()->with('error', 'Pesanan tidak memenuhi syarat untuk pengembalian.');
        }

        if ($order->refund_status) {
            return back()->with('error', 'Pengembalian sudah pernah diajukan.');
        }

        $validated = $request->validate([
            'reason' => 'required|in:changed_mind,wrong_item,damaged,not_as_described,late_delivery,other',
            'notes' => 'nullable|string|max:500',
            'evidence_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $evidencePath = null;
        if ($request->hasFile('evidence_image')) {
            $evidencePath = $request->file('evidence_image')->store('refund-evidence', 'public');
        }

        $order->update(['refund_status' => 'pending']);

        $refund = RefundRequest::create([
            'order_id' => $order->id,
            'user_id' => Auth::id(),
            'reason' => $validated['reason'],
            'notes' => $validated['notes'] ?? null,
            'evidence_image' => $evidencePath,
            'status' => 'pending',
        ]);

        $order->logStatusChange(
            'shipped',
            'shipped',
            Auth::id(),
            'user',
            'Pengembalian diajukan: ' . $refund->reason_label
        );

        return back()->with('success', 'Pengembalian berhasil diajukan. Menunggu review dari admin.');
    }

    public function submitReturn(Request $request, Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        $refund = $order->refundRequest;

        if (!$refund || $refund->status !== 'approved') {
            return back()->with('error', 'Pengembalian belum disetujui.');
        }

        if ($refund->buyer_returned_at) {
            return back()->with('error', 'Bukti pengembalian sudah dikirim sebelumnya.');
        }

        $validated = $request->validate([
            'return_tracking_number' => 'required|string|max:255',
            'return_evidence_image' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $evidencePath = $request->file('return_evidence_image')->store('refund-evidence', 'public');

        $refund->update([
            'return_tracking_number' => $validated['return_tracking_number'],
            'return_evidence_image' => $evidencePath,
            'buyer_returned_at' => now(),
            'status' => 'return_shipped',
        ]);

        $order->update(['refund_status' => 'return_shipped']);

        $order->logStatusChange(
            'shipped',
            'shipped',
            Auth::id(),
            'user',
            'Barang dikirim balik. Resi: ' . $validated['return_tracking_number']
        );

        return back()->with('success', 'Bukti pengembalian berhasil dikirim. Menunggu seller mengkonfirmasi penerimaan.');
    }
}
