<?php

namespace App\Services;

use App\Models\Order;
use Carbon\Carbon;

class OrderExpirationService
{
    /**
     * Set waktu expired untuk pesanan
     * 
     * @param Order $order
     * @param int $minutes Waktu expired dalam menit (default 30 menit)
     * @return void
     */
    public function setExpiration(Order $order, int $minutes = 30): void
    {
        $order->update([
            'expires_at' => now()->addMinutes($minutes)
        ]);
    }

    /**
     * Cek apakah pesanan sudah expired
     * 
     * @param Order $order
     * @return bool
     */
    public function isExpired(Order $order): bool
    {
        return $order->expires_at && now()->isAfter($order->expires_at);
    }

    /**
     * Batalkan pesanan yang expired
     * 
     * @param Order $order
     * @return bool
     */
    public function cancelExpiredOrder(Order $order): bool
    {
        if (!$this->isExpired($order)) {
            return false;
        }

        if ($order->payment_status === 'paid' || $order->order_status === 'cancelled') {
            return false;
        }

        $order->update([
            'order_status' => 'cancelled',
            'payment_status' => 'failed'
        ]);

        // Kembalikan stok produk
        foreach ($order->orderItems as $item) {
            $item->product->increment('stock', $item->quantity);
        }

        return true;
    }

    /**
     * Perpanjang waktu expired pesanan
     * 
     * @param Order $order
     * @param int $additionalMinutes
     * @return void
     */
    public function extendExpiration(Order $order, int $additionalMinutes = 30): void
    {
        if ($order->expires_at) {
            $order->update([
                'expires_at' => $order->expires_at->addMinutes($additionalMinutes)
            ]);
        } else {
            $this->setExpiration($order, $additionalMinutes);
        }
    }

    /**
     * Dapatkan waktu tersisa sebelum expired
     * 
     * @param Order $order
     * @return array
     */
    public function getTimeRemaining(Order $order): array
    {
        if (!$order->expires_at) {
            return [
                'expired' => false,
                'minutes' => null,
                'seconds' => null,
                'formatted' => 'Tidak ada batas waktu'
            ];
        }

        $now = now();
        $expiresAt = $order->expires_at;

        if ($now->isAfter($expiresAt)) {
            return [
                'expired' => true,
                'minutes' => 0,
                'seconds' => 0,
                'formatted' => 'Sudah expired'
            ];
        }

        $diff = $now->diffInSeconds($expiresAt);
        $minutes = floor($diff / 60);
        $seconds = $diff % 60;

        return [
            'expired' => false,
            'minutes' => $minutes,
            'seconds' => $seconds,
            'formatted' => sprintf('%02d:%02d', $minutes, $seconds)
        ];
    }
}