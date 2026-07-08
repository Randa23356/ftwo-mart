<?php

use Illuminate\Support\Facades\Route;
use App\Models\User;
use App\Models\Product;

Route::get('/test-checkout', function () {
    $user = User::where('email', 'test@example.com')->first();
    
    if (!$user) {
        return 'Test user not found';
    }
    
    // Login user
    auth()->login($user);
    
    $cartItems = $user->cartItems()->with('product')->get();
    
    $debug = [
        'user' => $user->name,
        'cart_count' => $cartItems->count(),
        'cart_items' => $cartItems->map(function($item) {
            return [
                'product' => $item->product->name,
                'price' => $item->product->price,
                'quantity' => $item->quantity,
                'subtotal' => $item->subtotal
            ];
        }),
        'total' => $cartItems->sum('subtotal')
    ];
    
    return response()->json($debug);
});

Route::get('/test-checkout-page', function () {
    $user = User::where('email', 'test@example.com')->first();
    
    if (!$user) {
        return 'Test user not found';
    }
    
    // Login user
    auth()->login($user);
    
    // Redirect to actual checkout
    return redirect()->route('orders.checkout');
});