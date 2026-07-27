<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Seller;
use App\Models\SellerTransaction;
use App\Models\SellerWithdrawal;
use App\Models\Product;
use App\Models\Category;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class SellerController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified', 'role:seller']);
        $this->middleware(function ($request, $next) {
            $seller = Auth::user()->seller;
            if (!$seller || !$seller->isApproved()) {
                return redirect()->route('seller.register')
                    ->with('error', 'Akun seller kamu belum disetujui admin. Silakan tunggu atau lengkapi dokumen.');
            }
            return $next($request);
        });
    }

    public function dashboard()
    {
        $seller = Auth::user()->seller;
        $totalProducts = $seller->products()->count();
        $activeProducts = $seller->active_product_count;
        $totalOrders = $seller->transactions()->count();
        $pendingWithdrawals = $seller->withdrawals()->where('status', 'pending')->count();

        $recentTransactions = $seller->transactions()
            ->with(['order', 'product'])
            ->latest()
            ->take(10)
            ->get();

        $monthlyEarnings = $seller->transactions()
            ->where('status', 'settled')
            ->whereYear('created_at', date('Y'))
            ->selectRaw('MONTH(created_at) as month, SUM(net_amount) as earnings')
            ->groupBy('month')
            ->pluck('earnings', 'month')
            ->toArray();

        return view('seller.dashboard', compact(
            'seller',
            'totalProducts',
            'activeProducts',
            'totalOrders',
            'pendingWithdrawals',
            'recentTransactions',
            'monthlyEarnings'
        ));
    }

    public function products(Request $request)
    {
        $seller = Auth::user()->seller;
        $query = $seller->products()->with('category');

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $products = $query->latest()->paginate(12);

        return view('seller.products.index', compact('seller', 'products'));
    }

    public function createProduct()
    {
        $categories = Category::where('is_active', true)->get();
        return view('seller.products.create', compact('categories'));
    }

    public function storeProduct(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'weight' => 'required|integer|min:1',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|max:2048',
            'variant_options' => 'nullable|string',
            'variant_combinations' => 'nullable|string',
            'pricing_type' => 'nullable|in:fixed,variant',
            'motif_name' => 'nullable|string|max:255',
            'material_description' => 'nullable|string',
            'origin_region' => 'nullable|string|max:255',
        ]);

        $seller = Auth::user()->seller;

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        // Parse variant_options JSON string into array
        if (!empty($validated['variant_options'])) {
            $decoded = json_decode($validated['variant_options'], true);
            if (is_array($decoded) && count($decoded) > 0) {
                $validated['variant_options'] = $decoded;
            } else {
                unset($validated['variant_options']);
            }
        } else {
            unset($validated['variant_options']);
        }

        $validated['slug'] = \Illuminate\Support\Str::slug($validated['name']);
        $validated['seller_id'] = $seller->id;
        $validated['is_active'] = true;
        $validated['pricing_type'] = $validated['pricing_type'] ?? 'fixed';

        $validated['uploaded_by'] = Auth::id();
        $product = Product::create($validated);

        // Save variant combinations
        $this->saveVariantCombinations($product, $request->input('variant_combinations'));

        // Handle multiple images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('products', 'public');
                $product->images()->create([
                    'image_path' => $path,
                    'alt_text' => $product->name,
                    'sort_order' => $index,
                    'is_primary' => $index === 0,
                ]);
            }
        }

        return redirect()->route('seller.products.index')
            ->with('success', 'Produk berhasil ditambahkan!');
    }

    public function editProduct(Product $product)
    {
        if ($product->seller_id !== Auth::user()->seller->id) {
            abort(403);
        }

        $categories = Category::where('is_active', true)->get();
        return view('seller.products.edit', compact('product', 'categories'));
    }

    public function updateProduct(Request $request, Product $product)
    {
        if ($product->seller_id !== Auth::user()->seller->id) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'weight' => 'required|integer|min:1',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|max:2048',
            'variant_options' => 'nullable|string',
            'variant_combinations' => 'nullable|string',
            'pricing_type' => 'nullable|in:fixed,variant',
            'motif_name' => 'nullable|string|max:255',
            'material_description' => 'nullable|string',
            'origin_region' => 'nullable|string|max:255',
            'is_active' => 'nullable',
        ]);

        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        // Parse variant_options JSON string into array
        if (!empty($validated['variant_options'])) {
            $decoded = json_decode($validated['variant_options'], true);
            if (is_array($decoded) && count($decoded) > 0) {
                $validated['variant_options'] = $decoded;
            } else {
                $validated['variant_options'] = null;
            }
        } else {
            $validated['variant_options'] = null;
        }

        $validated['slug'] = \Illuminate\Support\Str::slug($validated['name']);
        $validated['is_active'] = isset($validated['is_active']) && $validated['is_active'] !== null;
        $validated['pricing_type'] = $validated['pricing_type'] ?? 'fixed';

        $product->update($validated);

        // Save variant combinations
        $this->saveVariantCombinations($product, $request->input('variant_combinations'));

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('products', 'public');
                $product->images()->create([
                    'image_path' => $path,
                    'alt_text' => $product->name,
                    'sort_order' => $product->images()->count() + $index,
                    'is_primary' => $product->images()->count() === 0 && $index === 0,
                ]);
            }
        }

        return redirect()->route('seller.products.index')
            ->with('success', 'Produk berhasil diupdate!');
    }

    private function saveVariantCombinations(Product $product, ?string $combinationsJson): void
    {
        $product->variantCombinations()->delete();

        if (empty($combinationsJson)) return;

        $combinations = json_decode($combinationsJson, true);
        if (!is_array($combinations)) return;

        foreach ($combinations as $combo) {
            $key = $combo['key'] ?? null;
            $price = $combo['price'] ?? null;
            $stock = $combo['stock'] ?? 0;

            if (empty($key) || $price === '' || $price === null) continue;

            // Normalize key order for consistent JSON
            $normalizedKey = Product::normalizeVariantKey($key);

            $product->variantCombinations()->create([
                'variant_key' => $normalizedKey,
                'price' => (float) $price,
                'stock' => (int) $stock,
            ]);
        }
    }

    public function destroyProduct(Product $product)
    {
        if ($product->seller_id !== Auth::user()->seller->id) {
            abort(403);
        }

        $product->delete();

        return redirect()->route('seller.products.index')
            ->with('success', 'Produk berhasil dihapus!');
    }

    public function toggleProductStatus(Product $product)
    {
        if ($product->seller_id !== Auth::user()->seller->id) {
            abort(403);
        }

        $product->update(['is_active' => !$product->is_active]);

        return back()->with('success', 'Status produk berhasil diubah!');
    }

    public function earnings()
    {
        $seller = Auth::user()->seller;

        $transactions = $seller->transactions()
            ->with(['order', 'product'])
            ->latest()
            ->paginate(20);

        $stats = [
            'total_earnings' => $seller->total_earnings,
            'balance' => $seller->balance,
            'total_withdrawn' => $seller->total_withdrawn,
            'settled_count' => $seller->transactions()->where('status', 'settled')->count(),
            'pending_count' => $seller->transactions()->where('status', 'pending')->count(),
        ];

        return view('seller.earnings', compact('seller', 'transactions', 'stats'));
    }

    public function orders()
    {
        $seller = Auth::user()->seller;

        $orders = Order::whereHas('orderItems.product', function ($q) use ($seller) {
            $q->where('seller_id', $seller->id);
        })
            ->with(['user', 'orderItems.product'])
            ->latest()
            ->paginate(20);

        $commissionRate = (float) (\App\Models\WebsiteSetting::getValue('platform_commission_rate') ?? '5');

        return view('seller.orders', compact('seller', 'orders', 'commissionRate'));
    }

    public function orderDetail(Order $order)
    {
        $seller = Auth::user()->seller;

        $hasMyProducts = $order->orderItems->contains(function ($item) use ($seller) {
            return $item->product && $item->product->seller_id == $seller->id;
        });

        if (!$hasMyProducts) {
            return redirect()->route('seller.orders')->with('error', 'Pesanan ini tidak berisi produk kamu.');
        }

        $order->load(['user', 'orderItems.product', 'histories.user']);

        $myItems = $order->orderItems->filter(fn($item) => $item->product && $item->product->seller_id == $seller->id);
        $commissionRate = (float) (\App\Models\WebsiteSetting::getValue('platform_commission_rate') ?? '5');
        $myShare = $myItems->sum('subtotal') * (1 - $commissionRate / 100);
        $myCommission = $myItems->sum('subtotal') * ($commissionRate / 100);

        $nextStatus = $this->getNextStatus($order->order_status);

        return view('seller.order-detail', compact('seller', 'order', 'myItems', 'nextStatus', 'myShare', 'myCommission', 'commissionRate'));
    }

    public function updateOrderStatus(Request $request, Order $order)
    {
        $seller = Auth::user()->seller;

        $hasMyProducts = $order->orderItems->contains(function ($item) use ($seller) {
            return $item->product && $item->product->seller_id == $seller->id;
        });

        if (!$hasMyProducts) {
            return back()->with('error', 'Pesanan ini tidak berisi produk kamu.');
        }

        if ($order->payment_method !== 'cod' && $order->payment_status !== 'paid') {
            return back()->with('error', 'Pesanan belum dibayar. Tidak dapat memproses pesanan sebelum pembayaran lunas.');
        }

        $allowed = $this->getAllowedStatuses($order->order_status);

        $request->validate([
            'order_status' => 'required|in:' . implode(',', $allowed),
            'tracking_number' => 'required_if:order_status,shipped|nullable|string|max:255',
        ]);

        if (!in_array($request->order_status, $allowed)) {
            return back()->with('error', 'Status tidak valid untuk pesanan ini.');
        }

        $oldStatus = $order->order_status;
        $updates = ['order_status' => $request->order_status];

        if ($request->order_status === 'shipped' && $request->tracking_number) {
            $updates['tracking_number'] = $request->tracking_number;
            $updates['shipped_at'] = now();
        }

        $order->update($updates);
        $order->logStatusChange($oldStatus, $request->order_status, Auth::id(), 'seller', $seller->shop_name);

        return back()->with('success', 'Status pesanan berhasil diupdate!');
    }

    public function confirmReturn(Order $order)
    {
        $seller = Auth::user()->seller;

        $hasMyProducts = $order->orderItems->contains(function ($item) use ($seller) {
            return $item->product && $item->product->seller_id == $seller->id;
        });

        if (!$hasMyProducts) {
            return back()->with('error', 'Pesanan ini tidak berisi produk kamu.');
        }

        $refund = $order->refundRequest;

        if (!$refund || $refund->status !== 'return_shipped') {
            return back()->with('error', 'Pengembalian belum dikirim oleh pembeli.');
        }

        $refund->update([
            'status' => 'completed',
            'seller_returned_at' => now(),
        ]);

        $order->update(['refund_status' => 'completed']);

        $order->cancelOrder('Pengembalian barang dikonfirmasi diterima oleh seller');

        $order->logStatusChange(
            'shipped',
            'cancelled',
            Auth::id(),
            'seller',
            'Barang retur diterima. Pesanan dibatalkan dan stok dikembalikan.'
        );

        return back()->with('success', 'Barang retur dikonfirmasi diterima. Pesanan dibatalkan dan stok dikembalikan.');
    }

    private function getNextStatus(string $current): ?string
    {
        return match($current) {
            'pending' => 'processing',
            'processing' => 'ready',
            'ready' => 'shipped',
            default => null,
        };
    }

    private function getAllowedStatuses(string $current): array
    {
        return match($current) {
            'pending' => ['processing', 'cancelled'],
            'processing' => ['ready', 'cancelled'],
            'ready' => ['shipped', 'cancelled'],
            default => [],
        };
    }

    public function withdrawals()
    {
        $seller = Auth::user()->seller;

        $withdrawals = $seller->withdrawals()
            ->latest()
            ->paginate(20);

        return view('seller.withdrawals', compact('seller', 'withdrawals'));
    }

    public function requestWithdrawal(Request $request)
    {
        $seller = Auth::user()->seller;

        $validated = $request->validate([
            'amount' => 'required|numeric|min:50000|max:' . $seller->balance,
            'bank_name' => 'required|string|max:255',
            'bank_account_number' => 'required|string|max:255',
            'bank_account_name' => 'required|string|max:255',
            'notes' => 'nullable|string|max:500',
        ]);

        $validated['seller_id'] = $seller->id;
        $validated['status'] = 'pending';

        SellerWithdrawal::create($validated);

        return redirect()->route('seller.withdrawals')
            ->with('success', 'Permintaan penarikan berhasil diajukan! Menunggu persetujuan admin.');
    }

    public function destroyImage(Product $product, $imageId)
    {
        if ($product->seller_id !== Auth::user()->seller->id) {
            abort(403);
        }

        $image = $product->images()->findOrFail($imageId);
        Storage::disk('public')->delete($image->image_path);
        $image->delete();

        if (request()->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Gambar berhasil dihapus!']);
        }
        return back()->with('success', 'Gambar berhasil dihapus!');
    }

    public function deleteAllImages(Product $product)
    {
        if ($product->seller_id !== Auth::user()->seller->id) {
            abort(403);
        }

        DB::beginTransaction();
        try {
            $deletedCount = 0;

            foreach ($product->images as $image) {
                Storage::disk('public')->delete($image->image_path);
                $image->delete();
                $deletedCount++;
            }

            if ($product->image) {
                Storage::disk('public')->delete($product->image);
                $product->update(['image' => null]);
                $deletedCount++;
            }

            DB::commit();

            return response()->json(['success' => true, 'message' => "{$deletedCount} gambar berhasil dihapus!"]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Gagal menghapus gambar: ' . $e->getMessage()], 500);
        }
    }

    public function profile()
    {
        $seller = Auth::user()->seller;
        return view('seller.profile', compact('seller'));
    }

    public function updateProfile(Request $request)
    {
        $seller = Auth::user()->seller;

        $validated = $request->validate([
            'shop_name' => 'required|string|max:255',
            'shop_description' => 'nullable|string|max:1000',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'banner' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',
            'bank_name' => 'required|string|max:255',
            'bank_account_number' => 'required|string|max:255',
            'bank_account_name' => 'required|string|max:255',
            'origin_city_id' => 'nullable|integer|exists:cities,city_id',
        ]);

        if ($request->hasFile('logo')) {
            if ($seller->logo) {
                Storage::disk('public')->delete($seller->logo);
            }
            $validated['logo'] = $request->file('logo')->store('seller-logos', 'public');
        }

        if ($request->hasFile('banner')) {
            if ($seller->banner) {
                Storage::disk('public')->delete($seller->banner);
            }
            $validated['banner'] = $request->file('banner')->store('seller-banners', 'public');
        }

        $seller->update($validated);

        if ($request->filled('origin_city_id')) {
            Auth::user()->update(['origin_city_id' => $request->integer('origin_city_id')]);
        }

        return back()->with('success', 'Profil toko berhasil diperbarui!');
    }
}
