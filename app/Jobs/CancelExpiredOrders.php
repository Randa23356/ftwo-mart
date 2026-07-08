<?php

namespace App\Jobs;

use App\Models\Order;
use App\Services\OrderExpirationService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class CancelExpiredOrders implements ShouldQueue
{
    use Queueable;

    protected $expirationService;

    /**
     * Create a new job instance.
     */
    public function __construct(OrderExpirationService $expirationService)
    {
        $this->expirationService = $expirationService;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Get all expired and unpaid orders
        $expiredOrders = Order::expiredUnpaid()->get();
        
        $cancelledCount = 0;

        foreach ($expiredOrders as $order) {
            try {
                if ($this->expirationService->cancelExpiredOrder($order)) {
                    Log::info("Order {$order->order_number} has been cancelled due to expiration", [
                        'order_id' => $order->id,
                        'user_id' => $order->user_id,
                        'expired_at' => $order->expires_at,
                        'cancelled_at' => now()
                    ]);
                    $cancelledCount++;
                }
            } catch (\Exception $e) {
                Log::error("Failed to cancel order {$order->order_number}: " . $e->getMessage(), [
                    'order_id' => $order->id,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        }

        Log::info("Expired order processing completed", [
            'total_cancelled' => $cancelledCount,
            'processed_at' => now()
        ]);
    }

    /**
     * Kirim notifikasi pembatalan ke user (optional)
     */
    private function sendCancellationNotification(Order $order)
    {
        // Implementasi notifikasi bisa via email, SMS, atau push notification
        // Contoh: Mail::to($order->user->email)->send(new OrderCancelledMail($order));
    }
}
