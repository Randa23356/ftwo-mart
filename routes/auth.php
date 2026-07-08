<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Route;

// GET routes for auth pages
Route::get('/login', function () {
    return view('auth.login');
})->middleware('guest')->name('login');

Route::get('/register', function () {
    return view('auth.register');
})->middleware('guest')->name('register');

Route::get('/forgot-password', function () {
    return view('auth.forgot-password');
})->middleware('guest')->name('password.request');

Route::get('/reset-password/{token}', function (string $token) {
    return view('auth.reset-password', ['token' => $token]);
})->middleware('guest')->name('password.reset');

Route::get('/email/verify', function () {
    if (auth()->user()->hasVerifiedEmail()) {
        return redirectTo();
    }
    return view('auth.verify-email', ['isVerified' => false]);
})->middleware('auth')->name('verification.notice');

// POST routes for auth actions
Route::post('/register', [RegisteredUserController::class, 'store'])
    ->middleware('guest');

Route::post('/login', [AuthenticatedSessionController::class, 'store'])
    ->middleware('guest');

Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
    ->middleware('guest')
    ->name('password.email');

Route::post('/reset-password', [NewPasswordController::class, 'store'])
    ->middleware('guest')
    ->name('password.store');

Route::post('/email/verification-notification', function () {
    request()->user()->sendEmailVerificationNotification();

    // Handle AJAX requests
    if (request()->expectsJson()) {
        return response()->json([
            'success' => true,
            'message' => 'Link verifikasi telah dikirim!'
        ]);
    }

    return back()->with('status', 'verification-link-sent');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

Route::get('/email/verify/{id}/{hash}', [VerifyEmailController::class, '__invoke'])
    ->middleware(['auth', 'signed'])
    ->name('verification.verify');

if (!function_exists('redirectTo')) {
    function redirectTo() {
        $user = auth()->user();

        if ($user->hasRole('admin')) {
            return redirect()->route('admin.dashboard');
        }

        if ($user->hasRole('operator')) {
            return redirect()->route('operator.dashboard');
        }

        return redirect()->route('home');
    }
}

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');
