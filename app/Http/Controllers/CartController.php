<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        // Clear selected session when returning to cart
        session()->forget("selected_cart_items");

        $cartItems = Auth::user()->cartItems()->with("product.category")->get();

        // Filter out cart items with deleted products and remove them
        $orphaned = $cartItems->filter(fn($item) => $item->product === null);
        if ($orphaned->isNotEmpty()) {
            Cart::whereIn('id', $orphaned->pluck('id'))->delete();
        }

        $cartItems = $cartItems->filter(fn($item) => $item->product !== null)->values();
        $total = $cartItems->sum("subtotal");

        return view("cart.index", compact("cartItems", "total"));
    }

    public function add(Request $request)
    {
        $request->validate([
            "product_id" => "required|exists:products,id",
            "quantity" => "required|integer|min:1",
        ]);

        $product = Product::findOrFail($request->product_id);

        // Check stock
        if ($product->stock < $request->quantity) {
            $errorMessage =
                "Stok produk tidak mencukupi. Stok tersedia: " .
                $product->stock;
            if ($request->expectsJson()) {
                return response()->json(["message" => $errorMessage], 422);
            }
            return back()->with("error", $errorMessage);
        }

        // 🔑 Add to Cart biasa
        $existingCart = Auth::user()
            ->cartItems()
            ->where("product_id", $request->product_id)
            ->first();

        if ($existingCart) {
            $newQuantity = $existingCart->quantity + $request->quantity;

            if ($product->stock < $newQuantity) {
                $errorMessage =
                    "Stok produk tidak mencukupi untuk jumlah yang diminta.";
                if ($request->expectsJson()) {
                    return response()->json(["message" => $errorMessage], 422);
                }
                return back()->with("error", $errorMessage);
            }

            $existingCart->update(["quantity" => $newQuantity]);
            $message = "Jumlah produk berhasil diperbarui di keranjang.";
        } else {
            Auth::user()
                ->cartItems()
                ->create([
                    "product_id" => $request->product_id,
                    "quantity" => $request->quantity,
                ]);
            $message = "Produk berhasil ditambahkan ke keranjang.";
        }

        $cartCount = Auth::user()->cartItems()->sum("quantity");

        // Return JSON response for AJAX requests
        if ($request->expectsJson()) {
            return response()->json([
                "success" => true,
                "message" => $message,
                "cart_count" => $cartCount,
                "product" => [
                    "id" => $product->id,
                    "name" => $product->name,
                    "image_url" => $product->image_url,
                    "formatted_price" => $product->formatted_price,
                    "quantity" => $request->quantity,
                ],
            ]);
        }

        return back()->with("success", $message);
    }

    public function update(Request $request, Cart $cart)
    {
        \Log::info("Cart update", [
            "cart_id" => $cart->id,
            "request" => $request->all(),
        ]);

        $request->validate([
            "quantity" => "required|integer|min:1",
        ]);

        if ($cart->user_id !== Auth::id()) {
            abort(403);
        }

        if ($cart->product->stock < $request->quantity) {
            $msg =
                "Stok produk tidak mencukupi. Stok tersedia: " .
                $cart->product->stock;
            if ($request->expectsJson()) {
                return response()->json(["error" => $msg], 422);
            }
            return back()->with("error", $msg);
        }

        $cart->update(["quantity" => $request->quantity]);

        if ($request->expectsJson()) {
            return response()->json(["success" => true]);
        }

        return back()->with("success", "Jumlah produk berhasil diperbarui.");
    }

    public function remove(Cart $cart)
    {
        // Check if cart item belongs to authenticated user
        if ($cart->user_id !== Auth::id()) {
            abort(403);
        }

        $cart->delete();

        return back()->with(
            "success",
            "Produk berhasil dihapus dari keranjang.",
        );
    }

    public function clear()
    {
        Auth::user()->cartItems()->delete();

        return back()->with(
            "success",
            "Keranjang belanja berhasil dikosongkan.",
        );
    }

    public function checkoutSelected(Request $request)
    {
        $request->validate([
            "selected_items" => "required|array|min:1",
            "selected_items.*" => "integer|exists:cart,id",
        ]);

        // Verify all selected items belong to authenticated user
        $selectedCartItems = Auth::user()
            ->cartItems()
            ->whereIn("id", $request->selected_items)
            ->with("product.category")
            ->get();

        if ($selectedCartItems->count() !== count($request->selected_items)) {
            return back()->with(
                "error",
                "Beberapa item tidak valid atau tidak ditemukan.",
            );
        }

        // Store selected items in session for checkout
        session([
            "selected_cart_items" => $selectedCartItems
                ->map(function ($item) {
                    return [
                        "id" => $item->id,
                        "product_id" => $item->product_id,
                        "quantity" => $item->quantity,
                        "price" => $item->product->price,
                        "subtotal" => $item->product->price * $item->quantity,
                        "product_name" => $item->product->name,
                        "product_image" => $item->product->image_url,
                    ];
                })
                ->toArray(),
        ]);

        return redirect()->route("orders.checkout");
    }

    public function clearSelectedSession(Request $request)
    {
        session()->forget("selected_cart_items");
        return response()->json(["success" => true]);
    }
}
