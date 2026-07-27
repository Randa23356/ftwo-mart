<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use Illuminate\Support\Facades\Log;

class AutoCompleteOrders extends Command
{
    protected $signature = 'orders:auto-complete';

    protected $description = 'Selesaikan pesanan otomatis 3 hari setelah kurir konfirmasi (buyer belum konfirmasi)';

    public function handle()
    {
        $this->info('Mencari pesanan yang perlu diselesaikan otomatis...');

        $orders = Order::awaitingBuyerConfirmation()->get();
        $count = 0;

        if ($orders->isEmpty()) {
            $this->info('Tidak ada pesanan yang perlu diselesaikan.');
            return;
        }

        foreach ($orders as $order) {
            $this->info("Memproses Order #{$order->order_number}...");

            $oldStatus = $order->order_status;
            $updates = ['order_status' => 'delivered'];

            if ($order->payment_method === 'cod' && $order->payment_status !== 'paid') {
                $updates['payment_status'] = 'paid';
                $updates['paid_at'] = now();
            }

            $order->update($updates);
            $order->logStatusChange(
                $oldStatus,
                'delivered',
                null,
                'system',
                'Pesanan otomatis diselesaikan (3 hari setelah konfirmasi kurir)'
            );

            if ($order->fresh()->payment_status === 'paid') {
                $order->createSellerTransactions();
            }

            $this->info("Order {$order->order_number} berhasil diselesaikan.");
            $count++;
        }

        $this->info("Selesai. Total {$count} pesanan diselesaikan otomatis.");
    }
}
