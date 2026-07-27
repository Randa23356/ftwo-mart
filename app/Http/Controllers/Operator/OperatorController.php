<?php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OperatorController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:operator']);
    }

    /**
     * Scope: only orders that contain NO seller products (staff-uploaded only).
     */
    private function scopeStaffOrders($query)
    {
        return $query->whereDoesntHave('orderItems.product', function ($q) {
            $q->whereNotNull('seller_id');
        });
    }

    /**
     * Guard: abort 404 if order contains any seller product.
     */
    private function ensureStaffOrder(Order $order)
    {
        if ($order->orderItems->contains(function ($item) {
            return $item->product && $item->product->seller_id !== null;
        })) {
            abort(404);
        }
    }

    public function dashboard()
    {
        $menus = Product::with('category')->whereNull('seller_id')->get();

        $base = fn ($q) => $this->scopeStaffOrders($q);
        $pendingOrders = $base(Order::where('order_status', 'pending'))->count();
        $processingOrders = $base(Order::where('order_status', 'processing'))->count();
        $shippedOrders = $base(Order::where('order_status', 'shipped'))->count();
        $readyOrders = $base(Order::where('order_status', 'ready'))->count();
        $deliveredOrders = $base(Order::where('order_status', 'delivered'))->count();

        $recentOrders = $base(Order::with('user'))->latest()->take(10)->get();

        return view('operator.dashboard', compact(
            'menus',
            'pendingOrders',
            'processingOrders',
            'shippedOrders',
            'readyOrders',
            'deliveredOrders',
            'recentOrders'
        ));
    }

    public function orders()
    {
        $orders = $this->scopeStaffOrders(
            Order::with(['user', 'orderItems.product' => fn ($q) => $q->withTrashed()])
        )->latest()->paginate(15);

        return view('operator.orders.index', compact('orders'));
    }

    public function orderDetail(Order $order)
    {
        $order->load(['user', 'orderItems.product' => fn ($q) => $q->withTrashed(), 'paymentTransaction']);
        $this->ensureStaffOrder($order);
        return view('operator.orders.show', compact('order'));
    }

    public function updateOrderStatus(Request $request, Order $order)
    {
        $this->ensureStaffOrder($order);

        $oldStatus = $order->order_status;

        if ($oldStatus === 'cancelled') {
            return back()->with('error', 'Pesanan telah dibatalkan dan tidak dapat diubah.');
        }

        if ($order->payment_method !== 'cod' && $order->payment_status !== 'paid') {
            return back()->with('error', 'Pesanan belum dibayar. Tidak dapat memproses pesanan sebelum pembayaran lunas.');
        }

        $allowed = $this->getAllowedStatuses($oldStatus);

        $request->validate([
            'order_status' => 'required|in:' . implode(',', $allowed),
            'tracking_number' => 'required_if:order_status,shipped|nullable|string|max:255',
            'notes' => 'nullable|string|max:500',
        ]);

        if (!in_array($request->order_status, $allowed)) {
            return back()->with('error', 'Status tidak valid untuk pesanan ini. Hanya boleh: ' . implode(', ', $allowed));
        }

        $updates = ['order_status' => $request->order_status];

        if ($request->order_status === 'cancelled') {
            $order->cancelOrder('Dibatalkan oleh operator: ' . ($request->notes ?? 'Tidak ada keterangan'));
            $order->logStatusChange($oldStatus, 'cancelled', Auth::id(), 'operator', 'Dibatalkan oleh operator');
            return back()->with('success', 'Pesanan berhasil dibatalkan dan stok dikembalikan.');
        }

        if ($request->order_status === 'shipped' && $request->tracking_number) {
            $updates['tracking_number'] = $request->tracking_number;
            $updates['shipped_at'] = now();
        }

        $order->update($updates);
        $order->logStatusChange($oldStatus, $request->order_status, Auth::id(), 'operator', $request->notes ?? null);

        return back()->with('success', 'Status pesanan berhasil diupdate');
    }

    private function getAllowedStatuses(string $current): array
    {
        return match ($current) {
            'pending' => ['processing', 'cancelled'],
            'processing' => ['ready', 'cancelled'],
            'ready' => ['shipped', 'cancelled'],
            'shipped' => [],
            'delivered' => [],
            default => [],
        };
    }

    public function confirmReturn(Order $order)
    {
        $refund = $order->refundRequest;

        if (!$refund || $refund->status !== 'return_shipped') {
            return back()->with('error', 'Pengembalian belum dikirim oleh pembeli.');
        }

        $refund->update([
            'status' => 'completed',
            'seller_returned_at' => now(),
        ]);

        $order->update(['refund_status' => 'completed']);

        $order->cancelOrder('Pengembalian barang dikonfirmasi diterima oleh operator');

        $order->logStatusChange(
            'shipped',
            'cancelled',
            Auth::id(),
            'operator',
            'Barang retur diterima. Pesanan dibatalkan dan stok dikembalikan.'
        );

        return back()->with('success', 'Barang retur dikonfirmasi diterima. Pesanan dibatalkan dan stok dikembalikan.');
    }

    public function pendingOrders()
    {
        $orders = $this->scopeStaffOrders(
            Order::where('order_status', 'pending')
                ->with(['user', 'orderItems.product' => fn ($q) => $q->withTrashed()])
        )->latest()->paginate(15);

        return view('operator.orders.index', compact('orders'));
    }

    public function processingOrders()
    {
        $orders = $this->scopeStaffOrders(
            Order::where('order_status', 'processing')
                ->with(['user', 'orderItems.product' => fn ($q) => $q->withTrashed()])
        )->latest()->paginate(15);

        return view('operator.orders.index', compact('orders'));
    }

    public function readyOrders()
    {
        $orders = $this->scopeStaffOrders(
            Order::where('order_status', 'ready')
                ->with(['user', 'orderItems.product' => fn ($q) => $q->withTrashed()])
        )->latest()->paginate(15);

        return view('operator.orders.index', compact('orders'));
    }

    public function shippedOrders()
    {
        $orders = $this->scopeStaffOrders(
            Order::where('order_status', 'shipped')
                ->with(['user', 'orderItems.product' => fn ($q) => $q->withTrashed()])
        )->latest()->paginate(15);

        return view('operator.orders.index', compact('orders'));
    }

    public function deliveredOrders()
    {
        $orders = $this->scopeStaffOrders(
            Order::where('order_status', 'delivered')
                ->with(['user', 'orderItems.product' => fn ($q) => $q->withTrashed()])
        )->latest()->paginate(15);

        return view('operator.orders.index', compact('orders'));
    }

    public function cancelledOrders()
    {
        $orders = $this->scopeStaffOrders(
            Order::where('order_status', 'cancelled')
                ->with(['user', 'orderItems.product' => fn ($q) => $q->withTrashed()])
        )->latest()->paginate(15);

        return view('operator.orders.index', compact('orders'));
    }

    public function updateTrackingNumber(Request $request, Order $order)
    {
        $this->ensureStaffOrder($order);

        $request->validate([
            'tracking_number' => 'required|string|max:255'
        ]);

        try {
            $order->update([
                'tracking_number' => $request->tracking_number,
                'shipped_at' => $order->shipped_at ?? now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Nomor resi berhasil disimpan.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan nomor resi: ' . $e->getMessage()
            ], 500);
        }
    }

    public function printOrder(Order $order)
    {
        $this->ensureStaffOrder($order);

        $order->load(['user', 'orderItems.product' => fn ($q) => $q->withTrashed()]);
        return view('operator.orders.print', compact('order'));
    }

    public function destroy(Order $order)
    {
        $this->ensureStaffOrder($order);

        if (!auth()->user()->can('order-delete')) {
            abort(403, 'Anda tidak memiliki izin untuk menghapus pesanan.');
        }

        try {
            $order->delete();
            return redirect()->route('operator.orders')->with('success', 'Pesanan berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus pesanan.');
        }
    }

    public function trashOrders()
    {
        $orders = $this->scopeStaffOrders(
            Order::onlyTrashed()
                ->with(['user', 'orderItems.product' => fn ($q) => $q->withTrashed()])
        )->latest()->paginate(15);

        return view('operator.orders.trash', compact('orders'));
    }

    public function restoreOrder($id)
    {
        $order = Order::onlyTrashed()->findOrFail($id);
        $this->ensureStaffOrder($order);
        $order->restore();

        return back()->with('success', 'Pesanan berhasil dipulihkan.');
    }

    public function forceDeleteOrder($id)
    {
        $order = Order::onlyTrashed()->findOrFail($id);
        $this->ensureStaffOrder($order);
        $order->forceDelete();

        return back()->with('success', 'Pesanan berhasil dihapus permanen.');
    }
}
