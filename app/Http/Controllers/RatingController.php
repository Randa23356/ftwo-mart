<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\Rating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RatingController extends Controller
{
    public function create(Order $order, Product $product)
    {
        // Validasi: hanya user yang memesan produk ini bisa review
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        // Validasi: order harus sudah dibayar
        if ($order->payment_status !== 'paid') {
            abort(403, 'Order must be paid first');
        }

        // Validasi: order harus sudah delivered (selesai)
        if ($order->order_status !== 'delivered') {
            abort(403, 'Order must be delivered before you can rate this product');
        }

        // Validasi: produk harus ada dalam order
        $hasProduct = $order->orderItems()->where('product_id', $product->id)->exists();
        if (!$hasProduct) {
            abort(404, 'Product not found in this order');
        }

        // Validasi: user belum pernah rating produk ini dari order ini
        $existingRating = Rating::where('user_id', Auth::id())
            ->where('order_id', $order->id)
            ->where('product_id', $product->id)
            ->first();

        if ($existingRating) {
            return redirect()->route('products.detail', $product->slug)
                ->with('info', 'You have already rated this product');
        }

        return view('ratings.create', compact('order', 'product'));
    }

    public function store(Request $request, Order $order, Product $product)
    {
        // Validasi yang sama seperti create
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        if ($order->payment_status !== 'paid') {
            abort(403, 'Order must be paid first');
        }

        if ($order->order_status !== 'delivered') {
            abort(403, 'Order must be delivered before you can rate this product');
        }

        $hasProduct = $order->orderItems()->where('product_id', $product->id)->exists();
        if (!$hasProduct) {
            abort(404, 'Product not found in this order');
        }

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review_text' => 'nullable|string|max:1000',
        ]);

        Rating::create([
            'user_id' => Auth::id(),
            'product_id' => $product->id,
            'order_id' => $order->id,
            'rating' => $request->rating,
            'review_text' => $request->review_text,
        ]);

        return redirect()->route('products.detail', $product->slug)
            ->with('success', 'Thank you for your review!');
    }

    public function edit(Order $order, Product $product)
    {
        // Validasi: hanya user yang memesan produk ini bisa edit
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        // Validasi: order harus sudah delivered
        if ($order->order_status !== 'delivered') {
            abort(403, 'Order must be delivered before you can edit rating');
        }

        // Validasi: produk harus ada dalam order
        $hasProduct = $order->orderItems()->where('product_id', $product->id)->exists();
        if (!$hasProduct) {
            abort(404, 'Product not found in this order');
        }

        // Cari rating yang sudah ada
        $rating = Rating::where('user_id', Auth::id())
            ->where('order_id', $order->id)
            ->where('product_id', $product->id)
            ->firstOrFail();

        return view('ratings.edit', compact('order', 'product', 'rating'));
    }

    public function update(Request $request, Order $order, Product $product)
    {
        // Validasi yang sama seperti edit
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        if ($order->order_status !== 'delivered') {
            abort(403, 'Order must be delivered before you can edit rating');
        }

        $hasProduct = $order->orderItems()->where('product_id', $product->id)->exists();
        if (!$hasProduct) {
            abort(404, 'Product not found in this order');
        }

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review_text' => 'nullable|string|max:1000',
        ]);

        // Cari dan update rating yang sudah ada
        $rating = Rating::where('user_id', Auth::id())
            ->where('order_id', $order->id)
            ->where('product_id', $product->id)
            ->firstOrFail();

        $rating->update([
            'rating' => $request->rating,
            'review_text' => $request->review_text,
        ]);

        return redirect()->route('products.detail', $product->slug)
            ->with('success', 'Rating updated successfully!');
    }
}
