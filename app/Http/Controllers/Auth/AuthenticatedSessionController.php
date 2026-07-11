<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class AuthenticatedSessionController extends Controller
{
    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        // Update last_seen_at immediately on login
        \DB::table('users')
            ->where('id', Auth::id())
            ->update(['last_seen_at' => now()]);

        // Check if user's email is verified
        if (!Auth::user()->hasVerifiedEmail()) {
            return redirect()->route('verification.notice')
                ->with('info', 'Selamat datang! Silakan verifikasi email Anda untuk mengakses semua fitur.');
        }

        return redirect()->intended(route('home'))->with('success', 'Selamat datang kembali!');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'Anda telah berhasil logout.');
    }
}
