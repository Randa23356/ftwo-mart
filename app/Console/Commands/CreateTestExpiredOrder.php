<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Models\User;
use App\Models\Product;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class CreateTestExpiredOrder extends Command
{
    protected $signature = 'orders:create-test-expired {--minutes=1 : Minutes until expiration}';
    protected $description = 'Buat pesanan test yang akan expired dalam waktu tertentu';

    public function handle()
    {
        $minutes = $this->option('minutes');
        
        // Ambil user pertama
        $user = User::first();
        if (!$user) {
            $this->error('Tidak ada user di database. Buat user terlebih dahulu.');
            return 1;
        }

        // Ambil produk pertama
        $product = Product::first();
        if (!$product) {
            $this->error('Tidak ada produk di database. Buat produk terlebih dahulu.');
            return 1;
        }

        // Buat pesanan test
        $order = Order::create([
            'order_number' => 'TEST-' . date('Ymd') . '-' . Str::random(6),
            'user_id' => $user->id,
            'total_amount' => 50000,
            'payment_method' => 'midtrans',
            'payment_status' => 'pending',
            'order_status' => 'pending',
            'delivery_address' => 'Alamat Test',
            'delivery_phone' => '081234567890',
            'notes' => 'Pesanan test untuk expired order',
            'expires_at' => now()->addMinutes($minutes),
        ]);

        // Buat order item
        $order->orderItems()->create([
            'product_id' => $product->id,
            'quantity' => 1,
            'price' => $product->price,
            'subtotal' => $product->price,
        ]);

        $this->info("Pesanan test berhasil dibuat:");
        $this->info("Order Number: {$order->order_number}");
        $this->info("User: {$user->name}");
        $this->info("Expires At: {$order->expires_at}");
        $this->info("Akan expired dalam {$minutes} menit");
        
        return 0;
    }
}