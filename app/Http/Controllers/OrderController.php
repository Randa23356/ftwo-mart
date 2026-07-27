<?php

namespace App\Http\Controllers;

use App\Models\Order;

use App\Models\PaymentTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Midtrans\Snap;
use Midtrans\Config;
use Midtrans\Transaction;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Auth::user()
            ->orders()
            ->with(['orderItems.product' => function($query) {
                $query->withTrashed();
            }])
            ->latest()
            ->paginate(10);
        return view("orders.index", compact("orders"));
    }

    public function show(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        $vaNumbers = [];
        $qrisUrls = [];
        $otherPayments = [];
        $paymentType = null;

        if ($order->payment_method === "midtrans") {
            Config::$serverKey = config("services.midtrans.server_key");
            Config::$isProduction = config("services.midtrans.is_production");

            try {
                $paymentTx = $order->paymentTransaction;
                $midtransId = $paymentTx ? $paymentTx->transaction_id : $order->order_number;
                $status = Transaction::status($midtransId);

                if (!empty($status->va_numbers)) {
                    foreach ($status->va_numbers as $va) {
                        $vaNumbers[] = strtoupper($va->bank) . ": " . $va->va_number;
                    }
                }

                if (!empty($status->permata_va_number)) {
                    $vaNumbers[] = "PERMATA: " . $status->permata_va_number;
                }

                if (!empty($status->biller_code) && !empty($status->bill_key)) {
                    $vaNumbers[] = "MANDIRI: " . $status->bill_key . " (Kode: " . $status->biller_code . ")";
                }

                if (!empty($status->actions)) {
                    foreach ($status->actions as $action) {
                        $url = $action->url ?? ($action->redirect_url ?? null);
                        if (!$url) continue;
                        $name = strtolower($action->name ?? "");
                        if (str_contains($name, "qris") || str_contains($name, "scan")) {
                            $qrisUrls[] = $url;
                        } else {
                            $otherPayments[strtoupper($action->name)] = $url;
                        }
                    }
                }

                if (!empty($status->payment_type) && strtolower($status->payment_type) === "qris") {
                    if (!empty($status->pdf_url) && !in_array($status->pdf_url, $qrisUrls)) {
                        $qrisUrls[] = $status->pdf_url;
                    }
                }

                $paymentType = $status->payment_type ?? null;
            } catch (\Exception $e) {
            }
        }

        if (empty($paymentType)) {
            $transaction = $order->paymentTransaction;
            if ($transaction && !empty($transaction->gateway_response)) {
                $response = json_decode($transaction->gateway_response);
                $paymentType = $response->payment_type ?? null;
            }
        }

        return view("orders.show", compact("order", "vaNumbers", "qrisUrls", "otherPayments", "paymentType"));
    }

    public function checkout()
    {
        // Buy Now flow - highest priority, clear any conflicting sessions
        if (session()->has("buy_now_items")) {
            session()->forget("checkout_cart_backup");
            $buyNowItems = session("buy_now_items");
            $cartItems = collect();

            foreach ($buyNowItems as $item) {
                $product = \App\Models\Product::findOrFail($item["product_id"]);
                $subtotal = ($item["unit_price"] ?? $product->price) * $item["quantity"];
                $cartItems->push(
                    (object) [
                        "product" => $product,
                        "quantity" => $item["quantity"],
                        "selected_variants" => $item["selected_variants"] ?? null,
                        "subtotal" => $subtotal,
                        "unit_price" => $item["unit_price"] ?? $product->price,
                        "formatted_subtotal" => "Rp " . number_format($subtotal, 0, ",", "."),
                        "is_buy_now" => true,
                    ],
                );
            }
        } elseif (session()->has("buy_now_item")) {
            session()->forget("checkout_cart_backup");
            $item = session("buy_now_item");
            $product = \App\Models\Product::findOrFail($item["product_id"]);
            $resolvedPrice = $item["unit_price"] ?? ($item["selected_variants"] ? $product->getPriceForVariants($item["selected_variants"]) : $product->price);
            $subtotal = $resolvedPrice * $item["quantity"];
            $cartItems = collect([
                (object) [
                    "product" => $product,
                    "quantity" => $item["quantity"],
                    "selected_variants" => $item["selected_variants"] ?? null,
                    "subtotal" => $subtotal,
                    "unit_price" => $resolvedPrice,
                    "formatted_subtotal" => "Rp " . number_format($subtotal, 0, ",", "."),
                    "is_buy_now" => true,
                ],
            ]);
        } elseif (session()->has("selected_cart_items")) {
            session()->forget("checkout_cart_backup");
            $selectedItems = session("selected_cart_items");
            $cartItems = collect();

            foreach ($selectedItems as $item) {
                $product = \App\Models\Product::findOrFail($item["product_id"]);
                $resolvedPrice = $item["unit_price"] ?? ($item["selected_variants"] ? $product->getPriceForVariants($item["selected_variants"]) : $product->price);
                $cartItems->push(
                    (object) [
                        "product" => $product,
                        "quantity" => $item["quantity"],
                        "selected_variants" => $item["selected_variants"] ?? null,
                        "subtotal" => $item["subtotal"],
                        "unit_price" => $resolvedPrice,
                        "formatted_subtotal" => "Rp " . number_format($item["subtotal"], 0, ",", "."),
                    ],
                );
            }
        } elseif (session()->has("payment_in_progress")) {
            $orderId = session("payment_in_progress");
            $order = Order::find($orderId);

            if ($order && $order->user_id === Auth::id()) {
                if ($order->payment_status === "pending" && $order->payment_method === "midtrans") {
                    return redirect()->route("orders.pay", $order);
                }
                return redirect()->route("orders.show", $order);
            }

            session()->forget("payment_in_progress");
        } elseif (session()->has("checkout_cart_backup")) {
            $backupItems = session("checkout_cart_backup");
            $cartItems = collect();

            foreach ($backupItems as $item) {
                $product = \App\Models\Product::find($item["product_id"]);
                if ($product) {
                    $resolvedPrice = $item["unit_price"] ?? ($item["selected_variants"] ? $product->getPriceForVariants($item["selected_variants"]) : $product->price);
                    $cartItems->push(
                        (object) [
                            "id" => $item["id"] ?? null,
                            "product_id" => $product->id,
                            "product" => $product,
                            "quantity" => $item["quantity"],
                            "selected_variants" => $item["selected_variants"] ?? null,
                            "subtotal" => $item["subtotal"],
                            "unit_price" => $resolvedPrice,
                            "formatted_subtotal" => "Rp " . number_format($item["subtotal"], 0, ",", "."),
                        ],
                    );
                }
            }
        } else {
            $cartItems = Auth::user()->cartItems()->with("product")->get();

            if ($cartItems->isNotEmpty()) {
                    session()->put(
                        "checkout_cart_backup",
                        $cartItems->map(function ($item) {
                            return [
                                "id" => $item->id,
                                "product_id" => $item->product_id,
                                "quantity" => $item->quantity,
                                "subtotal" => $item->subtotal,
                                "selected_variants" => $item->selected_variants,
                                "unit_price" => $item->unit_price,
                            ];
                        })->toArray(),
                    );
            }
        }

        // Eager load seller origin for each product
        foreach ($cartItems as $item) {
            if (isset($item->product) && $item->product) {
                $item->product->loadMissing('seller.user.originCity');
            }
        }

        // Filter out blocked products (staff can't buy staff products, seller can't buy own)
        $userId = Auth::id();
        $cartItems->each(function ($item) {
            if (isset($item->product) && $item->product) {
                $item->product->load('uploadedBy');
            }
        });
        $cartItems = $cartItems->filter(function ($item) use ($userId) {
            return !(isset($item->product) && $item->product && $item->product->isBlockedForUser($userId));
        })->values();

        if ($cartItems->isEmpty()) {
            $recentOrder = Auth::user()->orders()->latest()->first();

            if ($recentOrder) {
                return redirect()->route("orders.show", $recentOrder)
                    ->with("info", "Checkout dibatalkan. Menampilkan pesanan terakhir Anda.");
            } else {
                return redirect()->route("products")
                    ->with("info", "Keranjang belanja kosong. Silakan pilih produk terlebih dahulu.");
            }
        }

        $total = $cartItems->sum("subtotal");

        \Log::info("Checkout Debug", [
            "cart_items_count" => $cartItems->count(),
            "subtotal" => $total,
            "cart_items" => $cartItems->map(function ($item) {
                return [
                    "product_name" => $item->product->name,
                    "price" => $item->product->price,
                    "quantity" => $item->quantity,
                    "subtotal" => $item->subtotal ?? 0,
                ];
            }),
        ]);

        $totalWeight = 0;
        foreach ($cartItems as $item) {
            $productWeight = $item->product->weight ?? 500;
            $totalWeight += $productWeight * $item->quantity;
        }

        if ($totalWeight < 100) {
            $totalWeight = 500;
        }

        \Midtrans\Config::$serverKey = config("midtrans.server_key");
        \Midtrans\Config::$isProduction = config("midtrans.is_production");
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;

        if (!session()->has("checkout_cart_backup") && !session()->has("buy_now_items") && !session()->has("buy_now_item") && !session()->has("selected_cart_items")) {
            session()->put(
                "checkout_cart_backup",
                $cartItems->map(function ($item) {
                    return [
                        "id" => $item->id ?? null,
                        "product_id" => $item->product_id,
                        "quantity" => $item->quantity,
                        "subtotal" => $item->subtotal,
                    ];
                })->toArray(),
            );
        }

        $shippingOrigin = \App\Models\ShippingSetting::getActiveOrigin();

        // Resolve origin from seller's user profile, fallback to shipping setting
        $resolvedOrigin = null;
        foreach ($cartItems as $item) {
            if ($item->product && $item->product->seller && $item->product->seller->user && $item->product->seller->user->origin_city_id) {
                $resolvedOrigin = $item->product->seller->user;
                break;
            }
        }

        if ($resolvedOrigin) {
            $shippingOrigin = (object) [
                'origin_city_id' => $resolvedOrigin->origin_city_id,
                'origin_city_name' => $resolvedOrigin->originCity->type . ' ' . $resolvedOrigin->originCity->city_name,
                'origin_province' => $resolvedOrigin->originCity->province ?? '',
            ];
        }

        return view("orders.checkout", compact("cartItems", "total", "totalWeight", "shippingOrigin"));
    }

    public function buyNow(Request $request)
    {
        $request->validate([
            "product_id" => "required|exists:products,id",
            "quantity" => "required|integer|min:1",
            "selected_variants" => "nullable",
        ]);

        $product = \App\Models\Product::findOrFail($request->product_id);
        $rawVariants = $request->input('selected_variants');

        // Handle both JSON string (from form) and array (from AJAX)
        if (is_string($rawVariants)) {
            $selectedVariants = json_decode($rawVariants, true) ?? [];
        } elseif (is_array($rawVariants)) {
            $selectedVariants = $rawVariants;
        } else {
            $selectedVariants = [];
        }

        // Validate variant selection
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
            if ($combination->stock < $request->quantity) {
                $errorMessage = "Stok varian tidak mencukupi.";
                if ($request->expectsJson()) {
                    return response()->json(["message" => $errorMessage], 422);
                }
                return back()->with("error", $errorMessage);
            }
        } else {
            $unitPrice = (float) $product->price;
            if ($product->stock < $request->quantity) {
                $errorMessage = "Stok produk tidak mencukupi.";
                if ($request->expectsJson()) {
                    return response()->json(["message" => $errorMessage], 422);
                }
                return back()->with("error", $errorMessage);
            }
        }

        // RESET SEMUA session checkout terlebih dahulu — Buy Now = HANYA item saat ini,
        // jangan bawa sisa item buy now / checkout sebelumnya yang tidak jadi diproses.
        session()->forget([
            "buy_now_item",
            "buy_now_items",
            "selected_cart_items",
            "checkout_cart_backup",
            "payment_in_progress",
        ]);

        $buyNowItems = [];

        $buyNowItems[] = [
            "product_id" => $product->id,
            "quantity" => $request->quantity,
            "selected_variants" => $selectedVariants,
            "unit_price" => $unitPrice,
        ];

        session(["buy_now_items" => $buyNowItems]);

        if ($request->expectsJson()) {
            return response()->json([
                "success" => true,
                "message" => "Produk berhasil ditambahkan untuk Buy Now",
                "redirect" => route("orders.checkout"),
            ]);
        }

        return redirect()->route("orders.checkout");
    }

    public function cancelBuyNow()
    {
        session()->forget([
            "buy_now_item",
            "buy_now_items",
            "selected_cart_items",
            "checkout_cart_backup",
            "payment_in_progress",
        ]);

        return redirect()->route("cart.index")->with("success", "Checkout berhasil dibatalkan");
    }

    public function store(Request $request)
    {
        \Log::info("=== ORDER STORE METHOD CALLED ===");

        \Log::info("Order Store Debug", [
            "has_buy_now" => session()->has("buy_now_items"),
            "has_selected" => session()->has("selected_cart_items"),
            "buy_now_data" => session("buy_now_items"),
            "selected_data" => session("selected_cart_items"),
            "user_cart_count" => Auth::user()->cartItems()->count(),
            "request_data" => $request->all(),
        ]);

        try {
            $request->validate([
                "delivery_name" => "required|string|max:255",
                "delivery_phone" => "required|string|max:20",
                "delivery_address" => "required|string",
                "notes" => "nullable|string",
                "payment_method" => "required|in:midtrans,cod",
                "shipping_courier" => "required|string",
                "shipping_service" => "required|string",
                "shipping_cost" => "required|numeric|min:0",
                "shipping_etd" => "nullable|integer",
                "destination_city_id" => "required|integer",
                "destination_province" => "required|string",
                "destination_city" => "required|string",
                "total_weight" => "required|integer|min:1",
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error("Validation Error in Order Store", [
                "errors" => $e->errors(),
                "request_data" => $request->all(),
            ]);
            throw $e;
        }

        // ✅ Ambil item berdasarkan flow
        if (session()->has("buy_now_items")) {
            session()->forget("checkout_cart_backup");
            $buyNowItems = session("buy_now_items");
            $cartItems = collect();

            foreach ($buyNowItems as $item) {
                $product = \App\Models\Product::findOrFail($item["product_id"]);
                $resolvedPrice = $item["unit_price"] ?? ($item["selected_variants"] ? $product->getPriceForVariants($item["selected_variants"]) : $product->price);
                $subtotal = $resolvedPrice * $item["quantity"];
                $cartItems->push(
                    (object) [
                        "id" => null,
                        "product_id" => $product->id,
                        "product" => $product,
                        "quantity" => $item["quantity"],
                        "selected_variants" => $item["selected_variants"] ?? null,
                        "subtotal" => $subtotal,
                        "formatted_subtotal" => "Rp " . number_format($subtotal, 0, ",", "."),
                        "unit_price" => $resolvedPrice,
                        "is_buy_now" => true,
                    ],
                );
            }
        } elseif (session()->has("buy_now_item")) {
            session()->forget("checkout_cart_backup");
            $item = session("buy_now_item");
            $product = \App\Models\Product::findOrFail($item["product_id"]);
            $resolvedPrice = $item["unit_price"] ?? ($item["selected_variants"] ? $product->getPriceForVariants($item["selected_variants"]) : $product->price);
            $subtotal = $resolvedPrice * $item["quantity"];
            $cartItems = collect([
                (object) [
                    "id" => null,
                    "product_id" => $product->id,
                    "product" => $product,
                    "quantity" => $item["quantity"],
                    "selected_variants" => $item["selected_variants"] ?? null,
                    "subtotal" => $subtotal,
                    "formatted_subtotal" => "Rp " . number_format($subtotal, 0, ",", "."),
                    "unit_price" => $resolvedPrice,
                    "is_buy_now" => true,
                ],
            ]);
        } elseif (session()->has("selected_cart_items")) {
            session()->forget("checkout_cart_backup");
            $selectedItems = session("selected_cart_items");
            $cartItems = collect();

            foreach ($selectedItems as $item) {
                $product = \App\Models\Product::findOrFail($item["product_id"]);
                $resolvedPrice = $item["unit_price"] ?? ($item["selected_variants"] ? $product->getPriceForVariants($item["selected_variants"]) : $product->price);
                $cartItems->push(
                    (object) [
                        "id" => $item["id"],
                        "product_id" => $product->id,
                        "product" => $product,
                        "quantity" => $item["quantity"],
                        "selected_variants" => $item["selected_variants"] ?? null,
                        "subtotal" => $item["subtotal"],
                        "formatted_subtotal" => "Rp " . number_format($item["subtotal"], 0, ",", "."),
                        "unit_price" => $resolvedPrice,
                        "is_selected" => true,
                    ],
                );
            }
        } elseif (session()->has("checkout_cart_backup")) {
            \Log::info("Using checkout cart backup");
            $backupItems = session("checkout_cart_backup");
            $cartItems = collect();

            foreach ($backupItems as $item) {
                $product = \App\Models\Product::findOrFail($item["product_id"]);
                $resolvedPrice = $item["unit_price"] ?? ($item["selected_variants"] ? $product->getPriceForVariants($item["selected_variants"]) : $product->price);
                $cartItems->push(
                    (object) [
                        "id" => $item["id"],
                        "product_id" => $product->id,
                        "product" => $product,
                        "quantity" => $item["quantity"],
                        "selected_variants" => $item["selected_variants"] ?? null,
                        "subtotal" => $item["subtotal"],
                        "formatted_subtotal" => "Rp " . number_format($item["subtotal"], 0, ",", "."),
                        "unit_price" => $resolvedPrice,
                        "is_backup" => true,
                    ],
                );
            }
        } else {
            $cartItems = Auth::user()->cartItems()->with("product")->get();
        }

        // Eager load seller origin for each product
        foreach ($cartItems as $item) {
            if (isset($item->product) && $item->product) {
                $item->product->loadMissing('seller.user.originCity');
            }
        }

        // Filter out blocked products (staff can't buy staff products, seller can't buy own)
        $userId = Auth::id();
        $cartItems->each(function ($item) {
            if (isset($item->product) && $item->product) {
                $item->product->load('uploadedBy');
            }
        });
        $cartItems = $cartItems->filter(function ($item) use ($userId) {
            return !(isset($item->product) && $item->product && $item->product->isBlockedForUser($userId));
        })->values();

        if ($cartItems->isEmpty()) {
            return redirect()->route("cart.index")->with("error", "Keranjang belanja kosong.");
        }

        foreach ($cartItems as $cartItem) {
            $availableStock = $cartItem->product->getStockForVariants($cartItem->selected_variants ?? []);
            if ($availableStock < $cartItem->quantity) {
                return back()->with("error", "Stok produk {$cartItem->product->name} tidak mencukupi.");
            }
        }

        $subtotal = $cartItems->sum("subtotal");
        $shippingCost = $request->shipping_cost;
        $totalAmount = $subtotal + $shippingCost;

        // Validate COD price limits
        if ($request->payment_method === 'cod') {
            $codMin = (float) (\App\Models\WebsiteSetting::getValue('cod_min_price', '10000') ?: '0');
            $codMax = (float) (\App\Models\WebsiteSetting::getValue('cod_max_price', '500000') ?: '0');
            if ($codMin > 0 && $totalAmount < $codMin) {
                return back()->with("error", "COD tidak tersedia untuk pesanan di bawah Rp " . number_format($codMin, 0, ',', '.') . ".");
            }
            if ($codMax > 0 && $totalAmount > $codMax) {
                return back()->with("error", "COD tidak tersedia untuk pesanan di atas Rp " . number_format($codMax, 0, ',', '.') . ".");
            }
        }

        $shippingOrigin = \App\Models\ShippingSetting::getActiveOrigin();

        // Resolve origin from seller's user profile, fallback to shipping setting
        $resolvedOrigin = null;
        foreach ($cartItems as $item) {
            if ($item->product && $item->product->seller && $item->product->seller->user && $item->product->seller->user->origin_city_id) {
                $resolvedOrigin = $item->product->seller->user;
                break;
            }
        }

        $originCityId = $resolvedOrigin ? $resolvedOrigin->origin_city_id : ($shippingOrigin->origin_city_id ?? config("app.origin_city_id", 501));

        DB::beginTransaction();
        try {
            $order = Order::create([
                "order_number" => "ORD-" . date("Ymd") . "-" . Str::random(6),
                "user_id" => Auth::id(),
                "total_amount" => $subtotal,
                "payment_method" => $request->payment_method,
                "payment_status" => "pending",
                "order_status" => "pending",
                "delivery_address" => $request->delivery_address,
                "delivery_phone" => $request->delivery_phone,
                "notes" => $request->notes,
                "expires_at" => now()->addMinutes(30),
                "courier_token" => Order::generateToken(),
                "shipping_courier" => $request->shipping_courier,
                "shipping_service" => $request->shipping_service,
                "shipping_cost" => $request->shipping_cost,
                "shipping_etd" => $request->shipping_etd,
                "destination_city_id" => $request->destination_city_id,
                "destination_province" => $request->destination_province,
                "destination_city" => $request->destination_city,
                "total_weight" => $request->total_weight,
                "origin_city_id" => $originCityId,
            ]);

            foreach ($cartItems as $cartItem) {
                $unitPrice = $cartItem->unit_price ?? (isset($cartItem->product) ? $cartItem->product->price : 0);
                $order->orderItems()->create([
                    "product_id" => $cartItem->product_id,
                    "quantity" => $cartItem->quantity,
                    "selected_variants" => $cartItem->selected_variants ?? null,
                    "price" => $unitPrice,
                    "subtotal" => $cartItem->subtotal,
                    "product_name" => $cartItem->product->name,
                    "product_image" => $cartItem->product->image,
                    "product_code" => $cartItem->product->product_code,
                ]);

                // Decrement stock — use combination stock if variants exist
                $selectedVariants = $cartItem->selected_variants ?? [];
                if (!empty($selectedVariants) && $cartItem->product->has_variants) {
                    $combination = $cartItem->product->findCombination($selectedVariants);
                    if ($combination) {
                        $combination->decrement('stock', $cartItem->quantity);
                    } else {
                        $cartItem->product->decrement("stock", $cartItem->quantity);
                    }
                } else {
                    $cartItem->product->decrement("stock", $cartItem->quantity);
                }
            }

            if (session()->has('selected_cart_items')) {
                $selectedIds = collect(session('selected_cart_items'))->pluck('id')->filter()->toArray();
                if (!empty($selectedIds)) {
                    Auth::user()->cartItems()->whereIn('id', $selectedIds)->delete();
                }
            } elseif (!session()->has('buy_now_items') && !session()->has('buy_now_item')) {
                Auth::user()->cartItems()->delete();
            }

            DB::commit();

            $order->logStatusChange(null, 'pending', Auth::id(), 'user', 'Pesanan dibuat');

            session()->put("payment_in_progress", $order->id);
            session()->forget([
                "selected_cart_items",
                "buy_now_items",
                "buy_now_item",
                "checkout_cart_backup",
                "checkout_data",
            ]);

            if ($request->payment_method === "midtrans") {
                return redirect()->route("orders.pay", $order);
            }

            return redirect()->route("orders.show", $order)->with("success", "Pesanan berhasil dibuat!");
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error("Order Store Error: " . $e->getMessage());
            \Log::error($e->getTraceAsString());
            return back()->with("error", "Terjadi kesalahan: " . $e->getMessage());
        }
    }

    public function pay(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        if ($order->payment_status === "paid") {
            return redirect()->route("orders.show", $order)
                ->with("success", "Pesanan sudah dibayar. Menampilkan detail pesanan.");
        }

        if ($order->payment_status === "failed" || $order->order_status === "cancelled") {
            return redirect()->route("orders.index")
                ->with("error", "Pesanan sudah dibatalkan atau gagal. Silakan buat pesanan baru.");
        }

        if ($order->snap_token_created_at && $order->snap_token_created_at->diffInHours(now()) < 1) {
            $payment = PaymentTransaction::where("order_id", $order->id)->first();

            if ($payment && !empty($payment->gateway_response)) {
                $response = json_decode($payment->gateway_response, true);
                $snapToken = $response["snap_token"] ?? null;

                if ($snapToken) {
                    return view("orders.payment", compact("order", "snapToken"));
                }
            }
        }

        Config::$serverKey = config("services.midtrans.server_key");
        Config::$isProduction = config("services.midtrans.is_production");
        Config::$isSanitized = true;
        Config::$is3ds = true;

        $midtransOrderId = $order->order_number . '-' . time();

        $params = [
            "transaction_details" => [
                "order_id" => $midtransOrderId,
                "gross_amount" => (int) ($order->total_amount + $order->shipping_cost),
            ],
            "customer_details" => [
                "first_name" => $order->user->name,
                "email" => $order->user->email,
                "phone" => $order->delivery_phone,
            ],
        ];

        try {
            $snapToken = Snap::getSnapToken($params);

            $payment = PaymentTransaction::updateOrCreate(
                ["order_id" => $order->id],
                [
                    "transaction_id" => $midtransOrderId,
                    "payment_gateway" => "midtrans",
                    "amount" => $order->total_amount + $order->shipping_cost,
                    "status" => "pending",
                    "gateway_response" => json_encode([
                        "snap_token" => $snapToken,
                    ]),
                ],
            );

            $order->update(["snap_token_created_at" => now()]);

            return view("orders.payment", compact("order", "snapToken"));
        } catch (\Exception $e) {
            \Log::error("Midtrans token generation failed", [
                "order_id" => $order->id,
                "order_number" => $order->order_number,
                "params" => $params ?? null,
                "error" => $e->getMessage(),
                "trace" => $e->getTraceAsString(),
            ]);

            return redirect()->back()->with("error", "Gagal memproses pembayaran: " . $e->getMessage());
        }
    }

    public function paymentCallback(Request $request)
    {
        \Log::info("Midtrans callback payload", $request->all());

        $serverKey = config("services.midtrans.server_key");
        $computedSignature = hash("sha512", $request->order_id . $request->status_code . $request->gross_amount . $serverKey);

        if ($computedSignature !== $request->signature_key) {
            \Log::warning("Midtrans signature mismatch", [
                "computed" => $computedSignature,
                "received" => $request->signature_key,
            ]);
            return response()->json(["status" => "invalid_signature"], 403);
        }

        $midtransOrderId = $request->order_id;
        $orderNumber = preg_replace('/-\d+$/', '', $midtransOrderId);
        $order = Order::where("order_number", $orderNumber)->first();
        if (!$order) {
            \Log::warning("Order not found", ["order_id" => $request->order_id]);
            return response()->json(["status" => "order_not_found"], 404);
        }

        $payment = PaymentTransaction::firstOrNew(["order_id" => $order->id]);
        if (!$payment->exists) {
            $payment->transaction_id = $request->transaction_id ?? $midtransOrderId;
        }
        $payment->payment_gateway = "midtrans";
        $payment->amount = $request->gross_amount ?? $order->total_with_shipping;
        $payment->gateway_response = json_encode($request->all());

        $newStatus = "pending";

        switch ($request->transaction_status) {
            case "capture":
                if (isset($request->fraud_status) && $request->fraud_status === "challenge") {
                    $newStatus = "pending";
                    $payment->status = "pending";
                } else {
                    $newStatus = "paid";
                    $payment->status = "success";
                }
                break;
            case "settlement":
                $newStatus = "paid";
                $payment->status = "success";
                break;
            case "pending":
                $newStatus = "pending";
                $payment->status = "pending";
                break;
            case "deny":
            case "expire":
            case "cancel":
                $newStatus = "failed";
                $payment->status = "failed";
                break;
        }

        $order->update(["payment_status" => $newStatus]);
        $payment->save();

        \Log::info("Order #{$order->order_number} updated status to {$newStatus}");

        return response()->json(["status" => "ok"]);
    }

    public function cancel(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        if (!$order->canBeCancelled()) {
            return back()->with('error', 'Pesanan tidak dapat dibatalkan.');
        }

        $order->cancelOrder('Dibatalkan oleh pengguna');

        return back()->with('success', 'Pesanan berhasil dibatalkan.');
    }

    public function confirmDelivery(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        if ($order->order_status !== 'shipped') {
            return back()->with('error', 'Pesanan belum dalam status dikirim.');
        }

        if (!$order->courier_confirmed_at) {
            return back()->with('error', 'Pesanan belum dikonfirmasi oleh kurir.');
        }

        if (in_array($order->refund_status, ['pending', 'return_pending', 'return_shipped'])) {
            return back()->with('error', 'Pesanan sedang dalam proses pengembalian.');
        }

        $oldStatus = $order->order_status;

        $updates = [
            'order_status' => 'delivered',
        ];

        if ($order->payment_method === 'cod' && $order->payment_status !== 'paid') {
            $updates['payment_status'] = 'paid';
            $updates['paid_at'] = now();
        }

        $order->update($updates);
        $order->logStatusChange($oldStatus, 'delivered', Auth::id(), 'user', 'Konfirmasi penerimaan oleh pembeli');

        if ($order->fresh()->payment_status === 'paid') {
            $order->createSellerTransactions();
        }

        return back()->with('success', 'Terima kasih! Pesanan telah dikonfirmasi diterima.');
    }
}
