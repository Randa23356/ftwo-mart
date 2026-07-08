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
                $status = Transaction::status($order->order_number);

                // Virtual Account
                if (!empty($status->va_numbers)) {
                    foreach ($status->va_numbers as $va) {
                        $vaNumbers[] =
                            strtoupper($va->bank) . ": " . $va->va_number;
                    }
                }

                if (!empty($status->permata_va_number)) {
                    $vaNumbers[] = "PERMATA: " . $status->permata_va_number;
                }

                if (!empty($status->biller_code) && !empty($status->bill_key)) {
                    $vaNumbers[] =
                        "MANDIRI: " .
                        $status->bill_key .
                        " (Kode: " .
                        $status->biller_code .
                        ")";
                }

                // QRIS & actions
                if (!empty($status->actions)) {
                    foreach ($status->actions as $action) {
                        $url = $action->url ?? ($action->redirect_url ?? null);
                        if (!$url) {
                            continue;
                        }
                        $name = strtolower($action->name ?? "");
                        if (
                            str_contains($name, "qris") ||
                            str_contains($name, "scan")
                        ) {
                            $qrisUrls[] = $url;
                        } else {
                            $otherPayments[strtoupper($action->name)] = $url;
                        }
                    }
                }

                // QRIS PDF
                if (
                    !empty($status->payment_type) &&
                    strtolower($status->payment_type) === "qris"
                ) {
                    if (
                        !empty($status->pdf_url) &&
                        !in_array($status->pdf_url, $qrisUrls)
                    ) {
                        $qrisUrls[] = $status->pdf_url;
                    }
                }

                $paymentType = $status->payment_type ?? null;
            } catch (\Exception $e) {
            }
        }

        // Fallback to local PaymentTransaction data if API didn't yield payment_type
        if (empty($paymentType)) {
            $transaction = $order->paymentTransaction;
            if ($transaction && !empty($transaction->gateway_response)) {
                $response = json_decode($transaction->gateway_response);
                $paymentType = $response->payment_type ?? null;
            }
        }

        return view(
            "orders.show",
            compact(
                "order",
                "vaNumbers",
                "qrisUrls",
                "otherPayments",
                "paymentType",
            ),
        );
    }

    public function checkout()
    {
        if (session()->has("buy_now_item")) {
            // Buy Now flow
            $item = session("buy_now_item");
            $product = \App\Models\Product::findOrFail($item["product_id"]);
            $subtotal = $product->price * $item["quantity"];
            $cartItems = collect([
                (object) [
                    "product" => $product,
                    "quantity" => $item["quantity"],
                    "subtotal" => $subtotal,
                    "formatted_subtotal" =>
                        "Rp " . number_format($subtotal, 0, ",", "."),
                ],
            ]);
        } elseif (session()->has("selected_cart_items")) {
            // Selected cart items flow
            $selectedItems = session("selected_cart_items");
            $cartItems = collect();

            foreach ($selectedItems as $item) {
                $product = \App\Models\Product::findOrFail($item["product_id"]);
                $cartItems->push(
                    (object) [
                        "product" => $product,
                        "quantity" => $item["quantity"],
                        "subtotal" => $item["subtotal"],
                        "formatted_subtotal" =>
                            "Rp " .
                            number_format($item["subtotal"], 0, ",", "."),
                    ],
                );
            }
        } elseif (session()->has("payment_in_progress")) {
            $orderId = session("payment_in_progress");
            $order = Order::find($orderId);

            if ($order && $order->user_id === Auth::id()) {
                // Redirect to payment page if payment is not completed
                if (
                    $order->payment_status === "pending" &&
                    $order->payment_method === "midtrans"
                ) {
                    return redirect()->route("orders.pay", $order);
                }
                // Redirect to order details page if payment is completed or not midtrans
                return redirect()->route("orders.show", $order);
            }

            // If order not found or doesn't belong to user, clear the session
            session()->forget("payment_in_progress");
        } elseif (session()->has("checkout_cart_backup")) {
            // Use backup cart items from session if available
            $backupItems = session("checkout_cart_backup");
            $cartItems = collect();

            foreach ($backupItems as $item) {
                $product = \App\Models\Product::find($item["product_id"]);
                if ($product) {
                    $cartItems->push(
                        (object) [
                            "id" => $item["id"] ?? null,
                            "product_id" => $product->id,
                            "product" => $product,
                            "quantity" => $item["quantity"],
                            "subtotal" => $item["subtotal"],
                            "formatted_subtotal" =>
                                "Rp " .
                                number_format($item["subtotal"], 0, ",", "."),
                        ],
                    );
                }
            }
        } else {
            // All cart items flow (default)
            $cartItems = Auth::user()->cartItems()->with("product")->get();

            // Store cart items in session as backup
            if ($cartItems->isNotEmpty()) {
                session()->put(
                    "checkout_cart_backup",
                    $cartItems
                        ->map(function ($item) {
                            return [
                                "id" => $item->id,
                                "product_id" => $item->product_id,
                                "quantity" => $item->quantity,
                                "subtotal" => $item->subtotal,
                            ];
                        })
                        ->toArray(),
                );
            }
        }

        // Handle empty cart - redirect to appropriate page
        if ($cartItems->isEmpty()) {
            // Check if user has recent orders
            $recentOrder = Auth::user()->orders()->latest()->first();

            if ($recentOrder) {
                // Redirect to most recent order if exists
                return redirect()
                    ->route("orders.show", $recentOrder)
                    ->with(
                        "info",
                        "Checkout dibatalkan. Menampilkan pesanan terakhir Anda.",
                    );
            } else {
                // Redirect to products if no orders
                return redirect()
                    ->route("products")
                    ->with(
                        "info",
                        "Keranjang belanja kosong. Silakan pilih produk terlebih dahulu.",
                    );
            }
        }

        $total = $cartItems->sum("subtotal");

        // Debug: Log total calculation
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

        // Calculate total weight properly
        $totalWeight = 0;
        foreach ($cartItems as $item) {
            // Use product weight or default to 500g if not set
            $productWeight = $item->product->weight ?? 500;
            $totalWeight += $productWeight * $item->quantity;
        }

        // Ensure minimum weight of 100g
        if ($totalWeight < 100) {
            $totalWeight = 500;
        }

        \Midtrans\Config::$serverKey = config("midtrans.server_key");
        \Midtrans\Config::$isProduction = config("midtrans.is_production");
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;

        // Persist cart data in session as backup if not already present
        if (
            !session()->has("checkout_cart_backup") &&
            !session()->has("buy_now_item") &&
            !session()->has("selected_cart_items")
        ) {
            // Only store in session if not already present
            session()->put(
                "checkout_cart_backup",
                $cartItems
                    ->map(function ($item) {
                        return [
                            "id" => $item->id ?? null,
                            "product_id" => $item->product_id,
                            "quantity" => $item->quantity,
                            "subtotal" => $item->subtotal,
                        ];
                    })
                    ->toArray(),
            );
        }

        return view(
            "orders.checkout",
            compact("cartItems", "total", "totalWeight"),
        );
    }

    public function buyNow(Request $request)
    {
        $request->validate([
            "product_id" => "required|exists:products,id",
            "quantity" => "required|integer|min:1",
        ]);

        $product = \App\Models\Product::findOrFail($request->product_id);

        if ($product->stock < $request->quantity) {
            $errorMessage = "Stok produk tidak mencukupi.";
            if ($request->expectsJson()) {
                return response()->json(["message" => $errorMessage], 422);
            }
            return back()->with("error", $errorMessage);
        }

        // Hapus session payment_in_progress agar tidak terjebak di order lama
        session()->forget("payment_in_progress");

        // simpan di session
        session([
            "buy_now_item" => [
                "product_id" => $product->id,
                "quantity" => $request->quantity,
            ],
        ]);

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
        // Hapus session buy now item
        session()->forget("buy_now_item");

        return redirect()
            ->route("cart.index")
            ->with("success", "Checkout berhasil dibatalkan");
    }

    public function store(Request $request)
    {
        // Debug: Log that method is called
        \Log::info("=== ORDER STORE METHOD CALLED ===");

        // Debug: Log session data
        \Log::info("Order Store Debug", [
            "has_buy_now" => session()->has("buy_now_item"),
            "has_selected" => session()->has("selected_cart_items"),
            "buy_now_data" => session("buy_now_item"),
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
                // Shipping validation
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

        // ✅ Ambil item berdasarkan flow: Buy Now, Selected Items, atau All Cart
        if (session()->has("buy_now_item")) {
            // Buy Now flow
            $item = session("buy_now_item");
            $product = \App\Models\Product::findOrFail($item["product_id"]);

            $subtotal = $product->price * $item["quantity"];
            $cartItems = collect([
                (object) [
                    "id" => null,
                    "product_id" => $product->id,
                    "product" => $product,
                    "quantity" => $item["quantity"],
                    "subtotal" => $subtotal,
                    "formatted_subtotal" =>
                        "Rp " . number_format($subtotal, 0, ",", "."),
                    "is_buy_now" => true,
                ],
            ]);
        } elseif (session()->has("selected_cart_items")) {
            // Selected cart items flow
            $selectedItems = session("selected_cart_items");
            $cartItems = collect();

            foreach ($selectedItems as $item) {
                $product = \App\Models\Product::findOrFail($item["product_id"]);
                $cartItems->push(
                    (object) [
                        "id" => $item["id"],
                        "product_id" => $product->id,
                        "product" => $product,
                        "quantity" => $item["quantity"],
                        "subtotal" => $item["subtotal"],
                        "formatted_subtotal" =>
                            "Rp " .
                            number_format($item["subtotal"], 0, ",", "."),
                        "is_selected" => true,
                    ],
                );
            }
        } elseif (session()->has("checkout_cart_backup")) {
            // Use backup cart data
            \Log::info("Using checkout cart backup");
            $backupItems = session("checkout_cart_backup");
            $cartItems = collect();

            foreach ($backupItems as $item) {
                $product = \App\Models\Product::findOrFail($item["product_id"]);
                $cartItems->push(
                    (object) [
                        "id" => $item["id"],
                        "product_id" => $product->id,
                        "product" => $product,
                        "quantity" => $item["quantity"],
                        "subtotal" => $item["subtotal"],
                        "formatted_subtotal" =>
                            "Rp " .
                            number_format($item["subtotal"], 0, ",", "."),
                        "is_backup" => true,
                    ],
                );
            }
        } else {
            // All cart items flow (default)
            $cartItems = Auth::user()->cartItems()->with("product")->get();
        }

        if ($cartItems->isEmpty()) {
            return redirect()
                ->route("cart.index")
                ->with("error", "Keranjang belanja kosong.");
        }

        // ✅ Cek stok
        foreach ($cartItems as $cartItem) {
            if ($cartItem->product->stock < $cartItem->quantity) {
                return back()->with(
                    "error",
                    "Stok produk {$cartItem->product->name} tidak mencukupi.",
                );
            }
        }

        // Calculate subtotal and shipping separately
        $subtotal = $cartItems->sum("subtotal");
        $shippingCost = $request->shipping_cost;

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
                "expires_at" => now()->addMinutes(30), // expired 30 menit
                // Shipping data
                "shipping_courier" => $request->shipping_courier,
                "shipping_service" => $request->shipping_service,
                "shipping_cost" => $request->shipping_cost,
                "shipping_etd" => $request->shipping_etd,
                "destination_city_id" => $request->destination_city_id,
                "destination_province" => $request->destination_province,
                "destination_city" => $request->destination_city,
                "total_weight" => $request->total_weight,
                "origin_city_id" => config("app.origin_city_id", 501),
            ]);

            foreach ($cartItems as $cartItem) {
                $order->orderItems()->create([
                    "product_id" => $cartItem->product_id,
                    "quantity" => $cartItem->quantity,
                    "price" => $cartItem->product->price,
                    "subtotal" => $cartItem->subtotal,
                ]);

                // update stok
                $cartItem->product->decrement("stock", $cartItem->quantity);
            }

            // Remove cart items that were used in this order
            if (session()->has('selected_cart_items')) {
                // If using selected cart items, remove only those
                $selectedIds = collect(session('selected_cart_items'))->pluck('id')->filter()->toArray();
                if (!empty($selectedIds)) {
                    Auth::user()->cartItems()->whereIn('id', $selectedIds)->delete();
                }
            } elseif (!session()->has('buy_now_item')) {
                // If not using selected items or buy now, remove all cart items
                Auth::user()->cartItems()->delete();
            }

            // Commit transaction
            DB::commit();

            // Set payment in progress in session and clear checkout data
            session()->put("payment_in_progress", $order->id);
            session()->forget([
                "selected_cart_items",
                "buy_now_item",
                "checkout_cart_backup",
                "checkout_data",
            ]);

            if ($request->payment_method === "midtrans") {
                return redirect()->route("orders.pay", $order);
            }

            return redirect()
                ->route("orders.show", $order)
                ->with("success", "Pesanan berhasil dibuat!");
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error("Order Store Error: " . $e->getMessage());
            \Log::error($e->getTraceAsString());
            return back()->with(
                "error",
                "Terjadi kesalahan: " . $e->getMessage(),
            );
        }
    }

    public function pay(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        // Redirect if order is already paid
        if ($order->payment_status === "paid") {
            return redirect()
                ->route("orders.show", $order)
                ->with(
                    "success",
                    "Pesanan sudah dibayar. Menampilkan detail pesanan.",
                );
        }

        // Redirect if order is failed or cancelled
        if (
            $order->payment_status === "failed" ||
            $order->order_status === "cancelled"
        ) {
            return redirect()
                ->route("orders.index")
                ->with(
                    "error",
                    "Pesanan sudah dibatalkan atau gagal. Silakan buat pesanan baru.",
                );
        }

        // Check if there's an existing valid token (less than 1 hour old)
        if (
            $order->snap_token &&
            $order->snap_token_created_at &&
            $order->snap_token_created_at->diffInHours(now()) < 1
        ) {
            // Get the existing token from payment transaction
            $payment = PaymentTransaction::where(
                "order_id",
                $order->id,
            )->first();

            if ($payment && !empty($payment->gateway_response)) {
                $response = json_decode($payment->gateway_response, true);
                $snapToken = $response["snap_token"] ?? null;

                if ($snapToken) {
                    return view(
                        "orders.payment",
                        compact("order", "snapToken"),
                    );
                }
            }
        }

        // Initialize Midtrans config
        Config::$serverKey = config("services.midtrans.server_key");
        Config::$isProduction = config("services.midtrans.is_production");
        Config::$isSanitized = true;
        Config::$is3ds = true;

        // Prepare transaction details
        $params = [
            "transaction_details" => [
                "order_id" => $order->order_number,
                "gross_amount" => $order->total_amount + $order->shipping_cost,
            ],
            "customer_details" => [
                "first_name" => $order->user->name,
                "email" => $order->user->email,
                "phone" => $order->delivery_phone,
            ],
        ];

        try {
            // Get new snap token from Midtrans
            $snapToken = Snap::getSnapToken($params);

            // Save payment transaction
            $payment = PaymentTransaction::updateOrCreate(
                ["order_id" => $order->id],
                [
                    "transaction_id" => $order->order_number,
                    "payment_gateway" => "midtrans",
                    "amount" => $order->total_amount + $order->shipping_cost,
                    "status" => "pending",
                    "gateway_response" => json_encode([
                        "snap_token" => $snapToken,
                    ]),
                ],
            );

            // Update order with token
            $order->update([
                "snap_token" => $snapToken,
                "snap_token_created_at" => now(),
            ]);

            return view("orders.payment", compact("order", "snapToken"));
        } catch (\Exception $e) {
            \Log::error("Midtrans token generation failed", [
                "order_id" => $order->id,
                "error" => $e->getMessage(),
            ]);

            return redirect()
                ->back()
                ->with(
                    "error",
                    "Gagal memproses pembayaran. Silakan coba lagi.",
                );
        }
    }

    public function paymentCallback(Request $request)
    {
        // Log semua payload dari Midtrans
        \Log::info("Midtrans callback payload", $request->all());

        // Validasi signature
        $serverKey = config("services.midtrans.server_key");
        $computedSignature = hash(
            "sha512",
            $request->order_id .
                $request->status_code .
                $request->gross_amount .
                $serverKey,
        );

        if ($computedSignature !== $request->signature_key) {
            \Log::warning("Midtrans signature mismatch", [
                "computed" => $computedSignature,
                "received" => $request->signature_key,
            ]);
            return response()->json(["status" => "invalid_signature"], 403);
        }

        // Cari order berdasarkan order_number
        $order = Order::where("order_number", $request->order_id)->first();
        if (!$order) {
            \Log::warning("Order not found", [
                "order_id" => $request->order_id,
            ]);
            return response()->json(["status" => "order_not_found"], 404);
        }

        // Cari atau buat PaymentTransaction terkait
        $payment = PaymentTransaction::firstOrNew(["order_id" => $order->id]);
        $payment->transaction_id =
            $request->transaction_id ?? $order->order_number;
        $payment->payment_gateway = "midtrans";
        $payment->amount =
            $request->gross_amount ?? $order->total_with_shipping;
        $payment->gateway_response = json_encode($request->all());

        // Update status order dan payment
        $newStatus = "pending"; // default

        switch ($request->transaction_status) {
            case "capture":
                if (
                    isset($request->fraud_status) &&
                    $request->fraud_status === "challenge"
                ) {
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

        \Log::info(
            "Order #{$order->order_number} updated status to {$newStatus}",
        );

        return response()->json(["status" => "ok"]);
    }

    /**
     * Cancel the specified order.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function cancel(Order $order)
    {
        // Check if the order belongs to the authenticated user
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        // Check if the order can be cancelled
        if (!$order->canBeCancelled()) {
            return back()->with('error', 'Pesanan tidak dapat dibatalkan.');
        }

        // Cancel the order
        $order->cancelOrder('Dibatalkan oleh pengguna');

        return back()->with('success', 'Pesanan berhasil dibatalkan.');
    }
}
