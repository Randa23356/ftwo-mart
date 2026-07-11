<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class UpdateUserLastSeenAt
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()) {
            // Update the last_seen_at timestamp for the authenticated user
            try {
                Auth::user()->update(["last_seen_at" => now()]);
            } catch (\Exception $e) {
                // Log error but don't break the request
                \Log::error('Failed to update last_seen_at', [
                    'user_id' => Auth::id(),
                    'error' => $e->getMessage()
                ]);
            }
        }

        return $next($request);
    }
}
