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
        if (Auth::check() && !$request->is('email/verify/*')) {
            try {
                $user = Auth::user();
                if ($user && $user->exists && $user->id) {
                    $lastSeen = session('last_seen_at_update');
                    if (!$lastSeen || $lastSeen->diffInSeconds(now()) >= 60) {
                        \DB::table('users')
                            ->where('id', $user->id)
                            ->update(['last_seen_at' => now()]);
                        session(['last_seen_at_update' => now()]);
                    }
                }
            } catch (\Exception $e) {
                \Log::error('Failed to update last_seen_at', [
                    'user_id' => Auth::id(),
                    'error' => $e->getMessage()
                ]);
            }
        }

        return $next($request);
    }
}
