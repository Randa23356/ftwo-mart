<?php

namespace App\Console\Commands;

use App\Models\Conversation;
use Illuminate\Console\Command;

class CreateTestGuestConversation extends Command
{
    protected $signature = 'chat:create-test-guest';
    protected $description = 'Buat conversation test dari guest user';

    public function handle()
    {
        // Buat conversation guest
        $conversation = Conversation::create([
            'user_id' => null, // Guest user
            'subject' => 'Test Guest Message (dari: John Doe)',
            'visibility' => 'admin_only',
            'status' => 'open'
        ]);

        // Buat pesan guest dengan format yang benar
        $guestMessage = "Nama: John Doe\n";
        $guestMessage .= "Email: john.doe@example.com\n\n";
        $guestMessage .= "Halo, saya ingin bertanya tentang produk batik yang tersedia. Apakah ada diskon untuk pembelian dalam jumlah besar?";

        $conversation->messages()->create([
            'user_id' => null, // Guest message
            'body' => $guestMessage,
        ]);

        $this->info("Test guest conversation berhasil dibuat:");
        $this->info("ID: {$conversation->id}");
        $this->info("Subject: {$conversation->subject}");
        $this->info("URL: /chat/{$conversation->id}");
        
        return 0;
    }
}