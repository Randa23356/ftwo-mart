<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    public function redirect()
    {
        if (!config('services.google.client_id') || !config('services.google.client_secret')) {
            return redirect()->route('login')->with('error', 'Google OAuth belum dikonfigurasi.');
        }

        try {
            return Socialite::driver('google')->redirect();
        } catch (\Exception $e) {
            Log::error('Google OAuth redirect error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return redirect()->route('login')->with('error', 'Google OAuth error: ' . $e->getMessage());
        }
    }

    public function callback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            $user = User::where('google_id', $googleUser->id)->first();

            if ($user) {
                Auth::login($user, true);
                return redirect()->intended(route('home'));
            }

            // Check if email already exists (registered normally))
            $existingUser = User::where('email', $googleUser->email)->first();

            if ($existingUser) {
                // Link Google account to existing user
                $existingUser->update(['google_id' => $googleUser->id]);
                Auth::login($existingUser, true);
                return redirect()->intended(route('home'));
            }

            // Create new userr
            $name = $googleUser->name ?? explode('@', $googleUser->email)[0];
            $slug = Str::slug($name) . '-' . Str::random(5);

            $user = User::create([
                'name' => $name,
                'email' => $googleUser->email,
                'google_id' => $googleUser->id,
                'email_verified_at' => now(),
                'slug' => $slug,
                'is_active' => true,
            ]);

            // Assign default role
            $user->assignRole('user');

            Auth::login($user, true);

            return redirect()->intended(route('home'));

        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Gagal login dengan Google. Silakan coba lagi.');
        }
    }
}
