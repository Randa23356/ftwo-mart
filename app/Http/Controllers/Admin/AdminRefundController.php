<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\RefundRequest;
use Illuminate\Http\Request;

class AdminRefundController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    public function index()
    {
        $refunds = RefundRequest::with(['order', 'user'])
            ->latest()
            ->paginate(20);

        return view('admin.refunds.index', compact('refunds'));
    }

    public function show(RefundRequest $refund)
    {
        $refund->load(['order.orderItems.product', 'user']);

        return view('admin.refunds.show', compact('refund'));
    }

    public function approve(RefundRequest $refund)
    {
        if ($refund->status !== 'pending') {
            return back()->with('error', 'Refund sudah diproses.');
        }

        $refund->update([
            'status' => 'approved',
            'admin_notes' => request('admin_notes'),
            'reviewed_at' => now(),
        ]);

        $order = $refund->order;
        $order->update(['refund_status' => 'return_pending']);

        $order->logStatusChange(
            'shipped',
            'shipped',
            auth()->id(),
            'admin',
            'Pengembalian disetujui: ' . $refund->reason_label . '. Menunggu pengembalian barang dari pembeli.'
        );

        return back()->with('success', 'Pengembalian disetujui. Pembeli akan diminta mengirim barang kembali.');
    }

    public function reject(RefundRequest $refund)
    {
        if ($refund->status !== 'pending') {
            return back()->with('error', 'Refund sudah diproses.');
        }

        $refund->update([
            'status' => 'rejected',
            'admin_notes' => request('admin_notes'),
            'reviewed_at' => now(),
        ]);

        $order = $refund->order;
        $order->update(['refund_status' => 'rejected']);

        $order->logStatusChange(
            'shipped',
            'shipped',
            auth()->id(),
            'admin',
            'Pengembalian ditolak: ' . $refund->reason_label
        );

        return back()->with('success', 'Pengembalian ditolak.');
    }
}
