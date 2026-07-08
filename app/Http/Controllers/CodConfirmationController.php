<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class CodConfirmationController extends Controller
{
    public function show(Request $request, Order $order)
    {
        // Guards
        if ($order->payment_method !== 'cod') {
            abort(404);
        }
        if ($order->payment_status === 'paid') {
            return view('orders.cod-confirmed', compact('order'));
        }

        // Render confirmation page (mobile friendly)
        return view('orders.cod-confirm', compact('order'));
    }

    public function confirm(Request $request, Order $order)
    {
        if ($order->payment_method !== 'cod') {
            abort(404);
        }
        if ($order->payment_status === 'paid') {
            return redirect()->route('cod.confirm.show', ['order' => $order->id, 'signature' => $request->query('signature')])
                ->with('success', 'Pembayaran sudah dikonfirmasi.');
        }

        $request->validate([
            'phone_last4' => 'required|digits:4'
        ]);

        $phone = preg_replace('/\D+/', '', $order->delivery_phone ?? '');
        $last4 = substr($phone, -4);

        if ($last4 !== $request->phone_last4) {
            return back()->with('error', 'Verifikasi nomor HP tidak cocok.');
        }

        $order->update([
            'order_status' => 'delivered',
            'payment_status' => 'paid',
            'paid_at' => now(),
        ]);

        return redirect()->route('cod.confirm.show', ['order' => $order->id, 'signature' => $request->query('signature')])
            ->with('success', 'Pembayaran COD berhasil dikonfirmasi.');
    }
}


