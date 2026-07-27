<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class CourierController extends Controller
{
    public function scan(string $token)
    {
        $order = Order::where('courier_token', $token)->firstOrFail();

        return view('courier.scan', compact('order', 'token'));
    }

    public function confirm(Request $request, string $token)
    {
        $order = Order::where('courier_token', $token)->firstOrFail();

        if ($order->courier_confirmed_at) {
            return back()->with('error', 'Pengiriman sudah dikonfirmasi sebelumnya.');
        }

        if ($order->order_status !== 'shipped') {
            return back()->with('error', 'Status pesanan tidak valid untuk dikonfirmasi.');
        }

        $order->update([
            'courier_confirmed_at' => now(),
        ]);

        $order->logStatusChange(
            'shipped',
            'shipped',
            null,
            'courier',
            'Kurir mengkonfirmasi pengiriman paket diterima penerima'
        );

        return redirect()->route('courier.scan', $token)->with('success', 'Pengiriman berhasil dikonfirmasi diterima oleh kurir. Menunggu konfirmasi dari pembeli.');
    }
}
