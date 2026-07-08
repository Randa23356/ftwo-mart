<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Mail\GuestReplyMail;

class TestEmailCommand extends Command
{
    protected $signature = 'email:test {email}';
    protected $description = 'Test email configuration dengan mengirim email test';

    public function handle()
    {
        $email = $this->argument('email');
        
        try {
            $this->info("Mengirim test email ke: {$email}");
            
            Mail::to($email)->send(
                new GuestReplyMail(
                    'Test Email dari FtwoMart',
                    'Ini adalah test email untuk memastikan konfigurasi email berfungsi dengan baik.',
                    'Test User'
                )
            );
            
            $this->info("✅ Email berhasil dikirim!");
            $this->info("Cek inbox email: {$email}");
            
        } catch (\Exception $e) {
            $this->error("❌ Gagal mengirim email:");
            $this->error($e->getMessage());
            
            $this->info("\n🔧 Troubleshooting:");
            $this->info("1. Cek konfigurasi MAIL_* di .env");
            $this->info("2. Pastikan SMTP credentials benar");
            $this->info("3. Cek firewall/port 587");
        }
        
        return 0;
    }
}