<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class TestPhotoUpload extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:photo-upload {email : User email to test photo upload}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test photo upload functionality for a user';

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
        
        $this->info("Testing photo upload for user: {$user->name} ({$email})");
        
        // Check storage setup
        $this->info("📁 Storage checks:");
        $this->line("   - Avatars folder exists: " . (Storage::disk('public')->exists('avatars') ? '✅ Yes' : '❌ No'));
        $this->line("   - Storage linked: " . (is_link(public_path('storage')) ? '✅ Yes' : '❌ No'));
        
        // Check current photo
        $this->info("📸 Current photo status:");
        if ($user->profile_photo_path) {
            $this->line("   - Has custom photo: ✅ Yes ({$user->profile_photo_path})");
            $this->line("   - File exists: " . (Storage::disk('public')->exists($user->profile_photo_path) ? '✅ Yes' : '❌ No'));
        } else {
            $this->line("   - Has custom photo: ❌ No (using default avatar)");
        }
        
        $this->line("   - Photo URL: {$user->profile_photo_url}");
        
        // Check permissions
        $avatarsPath = storage_path('app/public/avatars');
        $this->info("🔐 Permissions:");
        $this->line("   - Avatars folder writable: " . (is_writable($avatarsPath) ? '✅ Yes' : '❌ No'));
        $this->line("   - Folder permissions: " . substr(sprintf('%o', fileperms($avatarsPath)), -4));
        
        $this->info("✅ Photo upload test completed!");
        
        return 0;
    }
}