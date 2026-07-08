<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\OrderExpirationService;
use Illuminate\Http\Request;

class OrderStatusController extends Controller
{
    protected $expirationService;

    public function __construct(OrderExpirationService $expirationService)
    {
        $this->expirationService = $expirationService;
    }

    /**
     * Cek status pesanan dan waktu tersisa
     */
    public function checkStatus(Order $order)
    {
        // Pastikan user hanya bisa mengecek pesanan miliknya
        if ($order->user_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $timeRemaining = $this->expirationService->getTimeRemaining($order);

        return response()->json([
            'order_id' => $order->id,
            'order_number' => $order->order_number,
            'payment_status' => $order->payment_status,
            'order_status' => $order->order_status,
            'expires_at' => $order->expires_at,
            'time_remaining' => $timeRemaining,
            'is_expired' => $order->isExpired(),
        ]);
    }

    /**
     * Perpanjang waktu expired pesanan (jika diizinkan)
     */
    public function extendExpiration(Request $request, Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($order->payment_status !== 'pending' || $order->order_status === 'cancelled') {
            return response()->json(['message' => 'Cannot extend expired time for this order'], 422);
        }

        $additionalMinutes = $request->input('minutes', 30);
        
        // Batasi perpanjangan maksimal 2 jam
        if ($additionalMinutes > 120) {
            $additionalMinutes = 120;
        }

        $this->expirationService->extendExpiration($order, $additionalMinutes);

        return response()->json([
            'message' => 'Expiration time extended successfully',
            'new_expires_at' => $order->fresh()->expires_at,
            'extended_minutes' => $additionalMinutes
        ]);
    }
}