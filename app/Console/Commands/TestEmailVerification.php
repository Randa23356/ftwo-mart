<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class TestEmailVerification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:email-verification {email : The email address to send verification to}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send email verification to a specific user for testing';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        
        $user = User::where('email', $email)->first();
        
        if (!$user) {
            $this->error("User with email '{$email}' not found.");
            return 1;
        }
        
        if ($user->hasVerifiedEmail()) {
            $this->warn("User '{$user->name}' ({$email}) is already verified.");
            
            if ($this->confirm('Do you want to reset verification and send email anyway?')) {
                $user->email_verified_at = null;
                $user->save();
                $this->info("Email verification reset for user '{$user->name}'");
            } else {
                return 0;
            }
        }
        
        try {
            $user->sendEmailVerificationNotification();
            $this->info("✅ Email verification sent successfully to '{$user->name}' ({$email})");
            $this->info("📧 Check your Mailtrap inbox for the verification email.");
            return 0;
        } catch (\Exception $e) {
            $this->error("❌ Failed to send email: " . $e->getMessage());
            return 1;
        }
    }
}