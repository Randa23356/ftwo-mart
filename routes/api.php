<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\Api\OrderStatusController;

Route::post('/payment/callback', [OrderController::class, 'paymentCallback']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    
    // Order status API
    Route::get('/orders/{order}/status', [OrderStatusController::class, 'checkStatus']);
    Route::post('/orders/{order}/extend-expiration', [OrderStatusController::class, 'extendExpiration']);
});
