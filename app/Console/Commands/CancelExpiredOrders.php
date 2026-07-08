<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CancelExpiredOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:cancel-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Batalkan pesanan yang sudah melewati batas waktu pembayaran';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Mencari pesanan yang expired...');

        // Ambil pesanan yang unpaid dan expired
        $expiredOrders = Order::expiredUnpaid()->get();
        $count = 0;

        if ($expiredOrders->isEmpty()) {
            $this->info('Tidak ada pesanan expired yang ditemukan.');
            return;
        }

        foreach ($expiredOrders as $order) {
            $this->info("Memproses Order #{$order->order_number}...");
            
            // Gunakan method cancelOrder dari model untuk konsistensi (restore stock dll)
            if ($order->cancelOrder('Otomatis dibatalkan oleh sistem (Expired)')) {
                 $this->info("Order {$order->order_number} berhasil dibatalkan.");
                 $count++;
            } else {
                 $this->error("Gagal membatalkan order {$order->order_number}.");
                 Log::error("Command: Gagal membatalkan order expired {$order->order_number} (ID: {$order->id})");
            }
        }

        $this->info("Selesai. Total {$count} pesanan dibatalkan.");
    }
}
