<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckoutProtection
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Allow if user has buy_now_item or selected_cart_items in session
        if (session()->has('buy_now_item') || session()->has('selected_cart_items')) {
            return $next($request);
        }

        // Allow if user has items in cart
        if (Auth::check() && Auth::user()->cartItems()->count() > 0) {
            return $next($request);
        }

        // If no valid checkout data, redirect appropriately
        if (Auth::check()) {
            $recentOrder = Auth::user()->orders()->latest()->first();
            
            if ($recentOrder) {
                return redirect()
                    ->route('orders.show', $recentOrder)
                    ->with('info', 'Tidak ada item untuk checkout. Menampilkan pesanan terakhir Anda.');
            }
        }

        return redirect()
            ->route('products')
            ->with('info', 'Silakan pilih produk terlebih dahulu untuk melakukan checkout.');
    }
}
