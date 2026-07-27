<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    public function redirect()
    {
        if (!env('GOOGLE_CLIENT_ID') || !env('GOOGLE_CLIENT_SECRET')) {
            return redirect()->route('login')->with('error', 'Google OAuth belum dikonfigurasi.');
        }

        return Socialite::driver('google')->redirect();
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

            // Check if email already exists (registered normally)
            $existingUser = User::where('email', $googleUser->email)->first();

            if ($existingUser) {
                // Link Google account to existing user
                $existingUser->update(['google_id' => $googleUser->id]);
                Auth::login($existingUser, true);
                return redirect()->intended(route('home'));
            }

            // Create new user
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
