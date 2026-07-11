<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class ClearInvalidSession
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // If user is logged in but user data is invalid, clear session
        if (Auth::check()) {
            try {
                $user = Auth::user();
                if (!$user || !$user->exists) {
                    Auth::logout();
                    Session::flush();
                }
            } catch (\Exception $e) {
                // If any error occurs with auth, clear session
                Auth::logout();
                Session::flush();
            }
        }

        return $next($request);
    }
}
