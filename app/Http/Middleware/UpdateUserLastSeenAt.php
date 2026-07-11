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
        // Only update last_seen_at for authenticated users on safe routes
        // Skip for email verification routes to avoid errors
        if (Auth::check() && !$request->is('email/verify/*')) {
            try {
                $user = Auth::user();
                if ($user && $user->exists && $user->id) {
                    // Update last_seen_at without triggering model events
                    $updated = \DB::table('users')
                        ->where('id', $user->id)
                        ->update(['last_seen_at' => now()]);
                    
                    // Log for debugging
                    if ($updated) {
                        \Log::info('Updated last_seen_at', ['user_id' => $user->id]);
                    }
                }
            } catch (\Exception $e) {
                // Log error for debugging
                \Log::error('Failed to update last_seen_at', [
                    'user_id' => Auth::id(),
                    'error' => $e->getMessage()
                ]);
            }
        }

        return $next($request);
    }
}
