<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureEmailIsVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user() ||
            ($request->user() instanceof MustVerifyEmail &&
            ! $request->user()->hasVerifiedEmail())) {
            
            // For AJAX requests, return JSON response
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Email Anda belum diverifikasi. Silakan verifikasi email terlebih dahulu.',
                    'redirect' => route('verification.notice')
                ], 409);
            }
            
            // For web requests, redirect with flash message
            return redirect()->route('verification.notice')
                ->with('warning', 'Anda perlu memverifikasi email terlebih dahulu untuk mengakses fitur ini.');
        }

        return $next($request);
    }
}
