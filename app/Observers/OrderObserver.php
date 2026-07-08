<?php

namespace App\Observers;

use App\Models\Order;
use App\Services\OrderExpirationService;

class OrderObserver
{
    protected $expirationService;

    public function __construct(OrderExpirationService $expirationService)
    {
        $this->expirationService = $expirationService;
    }

    /**
     * Handle the Order "created" event.
     */
    public function created(Order $order): void
    {
        // Set waktu expired otomatis jika belum diset
        if (!$order->expires_at && $order->payment_status === 'pending') {
            // Tentukan waktu expired berdasarkan payment method
            $expirationMinutes = match($order->payment_method) {
                'cod' => 60 * 24, // COD: 24 jam
                'midtrans' => 30,  // Midtrans: 30 menit
                'bank_transfer' => 60 * 2, // Bank Transfer: 2 jam
                default => 30 // Default: 30 menit
            };

            $this->expirationService->setExpiration($order, $expirationMinutes);
        }
    }

    /**
     * Handle the Order "updated" event.
     */
    public function updated(Order $order): void
    {
        // Jika payment status berubah menjadi paid, hapus expires_at
        if ($order->payment_status === 'paid' && $order->expires_at) {
            $order->update(['expires_at' => null]);
        }
    }

    /**
     * Handle the Order "deleted" event.
     */
    public function deleted(Order $order): void
    {
        //
    }

    /**
     * Handle the Order "restored" event.
     */
    public function restored(Order $order): void
    {
        //
    }

    /**
     * Handle the Order "force deleted" event.
     */
    public function forceDeleted(Order $order): void
    {
        //
    }
}
