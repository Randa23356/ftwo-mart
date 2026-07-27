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
        session()->forget([
            "selected_cart_items",
            "buy_now_item",
            "buy_now_items",
        ]);

        $cartItems = Auth::user()->cartItems()->with("product.category")->get();

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
            "selected_variants" => "nullable",
        ]);

        $product = Product::findOrFail($request->product_id);
        $rawVariants = $request->input('selected_variants');

        // Handle both JSON string (from form) and array (from AJAX)
        if (is_string($rawVariants)) {
            $selectedVariants = json_decode($rawVariants, true) ?? [];
        } elseif (is_array($rawVariants)) {
            $selectedVariants = $rawVariants;
        } else {
            $selectedVariants = [];
        }

        // Validate variant selection against product's variant_options
        if ($product->has_variants) {
            foreach ($product->variant_options as $label => $options) {
                $selected = $selectedVariants[$label] ?? null;

                if (empty($selected)) {
                    $errorMessage = "Anda harus memilih {$label} terlebih dahulu.";
                    if ($request->expectsJson()) {
                        return response()->json(["message" => $errorMessage], 422);
                    }
                    return back()->with("error", $errorMessage);
                }

                if (!in_array($selected, $options)) {
                    $errorMessage = "Pilihan {$label} tidak valid.";
                    if ($request->expectsJson()) {
                        return response()->json(["message" => $errorMessage], 422);
                    }
                    return back()->with("error", $errorMessage);
                }
            }
        }

        // Check self-buy
        $user = Auth::user();
        $product->load('uploadedBy');
        if ($product->isBlockedForUser($user->id)) {
            $errorMessage = "Anda tidak dapat membeli produk ini.";
            if ($request->expectsJson()) {
                return response()->json(["message" => $errorMessage], 422);
            }
            return back()->with("error", $errorMessage);
        }

        // Determine unit price from variant combination or product price
        if (!empty($selectedVariants)) {
            $combination = $product->findCombination($selectedVariants);
            if (!$combination) {
                $errorMessage = "Kombinasi varian tidak ditemukan.";
                if ($request->expectsJson()) {
                    return response()->json(["message" => $errorMessage], 422);
                }
                return back()->with("error", $errorMessage);
            }
            $unitPrice = (float) $combination->price;
            // Check combination stock
            if ($combination->stock < $request->quantity) {
                $errorMessage = "Stok varian tidak mencukupi. Stok tersedia: " . $combination->stock;
                if ($request->expectsJson()) {
                    return response()->json(["message" => $errorMessage], 422);
                }
                return back()->with("error", $errorMessage);
            }
        } else {
            $unitPrice = (float) $product->price;
            if ($product->stock < $request->quantity) {
                $errorMessage = "Stok produk tidak mencukupi. Stok tersedia: " . $product->stock;
                if ($request->expectsJson()) {
                    return response()->json(["message" => $errorMessage], 422);
                }
                return back()->with("error", $errorMessage);
            }
        }

        // Check if same product + same variants already in cart
        $existingCart = Auth::user()
            ->cartItems()
            ->where("product_id", $request->product_id)
            ->where("selected_variants", json_encode($selectedVariants))
            ->first();

        if ($existingCart) {
            $newQuantity = $existingCart->quantity + $request->quantity;

            if ($product->stock < $newQuantity) {
                $errorMessage = "Stok produk tidak mencukupi untuk jumlah yang diminta.";
                if ($request->expectsJson()) {
                    return response()->json(["message" => $errorMessage], 422);
                }
                return back()->with("error", $errorMessage);
            }

            $existingCart->update(["quantity" => $newQuantity, "unit_price" => $unitPrice]);
            $message = "Jumlah produk berhasil diperbarui di keranjang.";
        } else {
            Auth::user()
                ->cartItems()
                ->create([
                    "product_id" => $request->product_id,
                    "quantity" => $request->quantity,
                    "selected_variants" => $selectedVariants,
                    "unit_price" => $unitPrice,
                ]);
            $message = "Produk berhasil ditambahkan ke keranjang.";
        }

        $cartCount = Auth::user()->cartItems()->sum("quantity");

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
        if ($cart->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            "quantity" => "required|integer|min:1",
            "selected_variants" => "nullable",
        ]);

        $rawVariants = $request->input('selected_variants');
        if (is_string($rawVariants)) {
            $newVariants = json_decode($rawVariants, true) ?? [];
        } elseif (is_array($rawVariants)) {
            $newVariants = $rawVariants;
        } else {
            $newVariants = $cart->selected_variants ?? [];
        }

        // Check if same product + same variants already exists in another cart item
        $duplicate = Auth::user()
            ->cartItems()
            ->where("id", "!=", $cart->id)
            ->where("product_id", $cart->product_id)
            ->where("selected_variants", json_encode($newVariants))
            ->first();

        if ($duplicate) {
            $newQty = $duplicate->quantity + $request->quantity;
            if ($cart->product->stock < $newQty) {
                return back()->with("error", "Stok produk tidak mencukupi untuk jumlah yang diminta.");
            }
            $duplicate->update(["quantity" => $newQty]);
            $cart->delete();
            return back();
        }

        // Determine unit price and validate stock
        if (!empty($newVariants)) {
            $combination = $cart->product->findCombination($newVariants);
            if (!$combination) {
                $msg = "Kombinasi varian tidak ditemukan.";
                if ($request->expectsJson()) return response()->json(["error" => $msg], 422);
                return back()->with("error", $msg);
            }
            $unitPrice = (float) $combination->price;
            if ($combination->stock < $request->quantity) {
                $msg = "Stok varian tidak mencukupi. Stok tersedia: " . $combination->stock;
                if ($request->expectsJson()) return response()->json(["error" => $msg], 422);
                return back()->with("error", $msg);
            }
        } else {
            $unitPrice = (float) $cart->product->price;
            if ($cart->product->stock < $request->quantity) {
                $msg = "Stok produk tidak mencukupi. Stok tersedia: " . $cart->product->stock;
                if ($request->expectsJson()) return response()->json(["error" => $msg], 422);
                return back()->with("error", $msg);
            }
        }

        $cart->update([
            "quantity" => $request->quantity,
            "selected_variants" => $newVariants,
            "unit_price" => $unitPrice,
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                "success" => true,
                "unit_price" => (float) $unitPrice,
                "subtotal" => (float) $unitPrice * $request->quantity,
            ]);
        }

        return back();
    }

    public function remove(Cart $cart)
    {
        if ($cart->user_id !== Auth::id()) {
            abort(403);
        }

        $cart->delete();

        return back()->with("success", "Produk berhasil dihapus dari keranjang.");
    }

    public function clear()
    {
        Auth::user()->cartItems()->delete();

        return back()->with("success", "Keranjang belanja berhasil dikosongkan.");
    }

    public function checkoutSelected(Request $request)
    {
        $request->validate([
            "selected_items" => "required|array|min:1",
            "selected_items.*" => "integer|exists:cart,id",
        ]);

        $selectedCartItems = Auth::user()
            ->cartItems()
            ->whereIn("id", $request->selected_items)
            ->with("product.category")
            ->get();

        if ($selectedCartItems->count() !== count($request->selected_items)) {
            return back()->with("error", "Beberapa item tidak valid atau tidak ditemukan.");
        }

        // Filter out blocked products (staff can't buy staff products, seller can't buy own)
        $userId = Auth::id();
        $selectedCartItems->each(function ($item) {
            $item->product->load('uploadedBy');
        });
        $selectedCartItems = $selectedCartItems->filter(function ($item) use ($userId) {
            return !($item->product && $item->product->isBlockedForUser($userId));
        })->values();
        if ($selectedCartItems->isEmpty()) {
            return back()->with("error", "Tidak ada produk yang bisa diproses.");
        }

        session()->forget(["buy_now_item", "buy_now_items"]);

        session([
            "selected_cart_items" => $selectedCartItems
                ->map(function ($item) {
                    $price = $item->unit_price ?? $item->product->price;
                    return [
                        "id" => $item->id,
                        "product_id" => $item->product_id,
                        "quantity" => $item->quantity,
                        "price" => $price,
                        "subtotal" => $price * $item->quantity,
                        "unit_price" => $price,
                        "product_name" => $item->product->name,
                        "product_image" => $item->product->image_url,
                        "selected_variants" => $item->selected_variants,
                    ];
                })
                ->toArray(),
        ]);

        return redirect()->route("orders.checkout");
    }

    public function clearSelectedSession(Request $request)
    {
        session()->forget([
            "selected_cart_items",
            "buy_now_item",
            "buy_now_items",
        ]);
        return response()->json(["success" => true]);
    }
}
