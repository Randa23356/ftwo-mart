<?php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;

class OperatorController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:operator']);
    }

    public function dashboard()
    {
        $menus = Product::with('category')->get();
        $pendingOrders = Order::where('order_status', 'pending')->count();
        $processingOrders = Order::where('order_status', 'processing')->count();
        $shippedOrders = Order::where('order_status', 'shipped')->count();
        $readyOrders = Order::where('order_status', 'ready')->count();
        $deliveredOrders = Order::where('order_status', 'delivered')->count();

        $recentOrders = Order::with('user')
            ->latest()
            ->take(10)
            ->get();

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
        $orders = Order::with(['user', 'orderItems.product' => function($query) {
                $query->withTrashed();
            }])
            ->latest()
            ->paginate(15);

        return view('operator.orders.index', compact('orders'));
    }

    public function orderDetail(Order $order)
    {
        $order->load(['user', 'orderItems.product' => function($query) {
            $query->withTrashed();
        }, 'paymentTransaction']);
        return view('operator.orders.show', compact('order'));
    }

    public function updateOrderStatus(Request $request, Order $order)
    {
        $request->validate([
            'order_status' => 'required|in:pending,processing,ready,shipped,delivered,cancelled'
        ]);

        // Prevent any changes if order already cancelled
        if ($order->order_status === 'cancelled') {
            return back()->with('error', 'Pesanan telah dibatalkan dan tidak dapat diubah.');
        }

        // Handle cancellation with stock restoration
        if ($request->order_status === 'cancelled') {
            if ($order->cancelOrder('Dibatalkan oleh operator')) {
                return back()->with('success', 'Pesanan berhasil dibatalkan dan stok produk telah dikembalikan.');
            } else {
                return back()->with('error', 'Gagal membatalkan pesanan. Pesanan mungkin sudah dibayar atau tidak dapat dibatalkan.');
            }
        }

        $updates = ['order_status' => $request->order_status];

        // If COD and order delivered, mark payment as paid automatically
        if ($request->order_status === 'delivered' && $order->payment_method === 'cod') {
            $updates['payment_status'] = 'paid';
            $updates['paid_at'] = now();
        }

        $order->update($updates);

        return back()->with('success', 'Status pesanan berhasil diupdate');
    }

    public function pendingOrders()
    {
        $orders = Order::where('order_status', 'pending')
            ->with(['user', 'orderItems.product' => function($query) {
                $query->withTrashed();
            }])
            ->latest()
            ->paginate(15);

        return view('operator.orders.index', compact('orders'));
    }

    public function processingOrders()
    {
        $orders = Order::where('order_status', 'processing')
            ->with(['user', 'orderItems.product' => function($query) {
                $query->withTrashed();
            }])
            ->latest()
            ->paginate(15);

        return view('operator.orders.index', compact('orders'));
    }

    public function readyOrders()
    {
        $orders = Order::where('order_status', 'ready')
            ->with(['user', 'orderItems.product' => function($query) {
                $query->withTrashed();
            }])
            ->latest()
            ->paginate(15);

        return view('operator.orders.index', compact('orders'));
    }

    public function shippedOrders()
    {
        $orders = Order::where('order_status', 'shipped')
            ->with(['user', 'orderItems.product' => function($query) {
                $query->withTrashed();
            }])
            ->latest()
            ->paginate(15);

        return view('operator.orders.index', compact('orders'));
    }

    public function deliveredOrders()
    {
        $orders = Order::where('order_status', 'delivered')
            ->with(['user', 'orderItems.product' => function($query) {
                $query->withTrashed();
            }])
            ->latest()
            ->paginate(15);

        return view('operator.orders.index', compact('orders'));
    }

    public function cancelledOrders()
    {
        $orders = Order::where('order_status', 'cancelled')
            ->with(['user', 'orderItems.product' => function($query) {
                $query->withTrashed();
            }])
            ->latest()
            ->paginate(15);

        return view('operator.orders.index', compact('orders'));
    }

    public function updateTrackingNumber(Request $request, Order $order)
    {
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
        $order->load(['user', 'orderItems.product' => function($query) {
            $query->withTrashed();
        }]);
        return view('operator.orders.print', compact('order'));
    }
    public function destroy(Order $order)
{
    // Check permission
    if (!auth()->user()->can('order-delete')) {
        abort(403, 'Anda tidak memiliki izin untuk menghapus pesanan.');
    }
    
    try {
        $order->delete(); // atau soft delete kalau mau bisa restore
        return redirect()->route('operator.orders')->with('success', 'Pesanan berhasil dihapus.');
    } catch (\Exception $e) {
        return back()->with('error', 'Gagal menghapus pesanan.');
    }
}

// Lihat pesanan yang dihapus (Trash)
public function trashOrders()
{
    $orders = Order::onlyTrashed()
        ->with(['user', 'orderItems.product' => function($query) {
            $query->withTrashed();
        }])
        ->latest()
        ->paginate(15);

    return view('operator.orders.trash', compact('orders'));
}

// Restore pesanan
public function restoreOrder($id)
{
    $order = Order::onlyTrashed()->findOrFail($id);
    $order->restore();

    return back()->with('success', 'Pesanan berhasil dipulihkan.');
}

// Hapus permanen
public function forceDeleteOrder($id)
{
    $order = Order::onlyTrashed()->findOrFail($id);
    $order->forceDelete();

    return back()->with('success', 'Pesanan berhasil dihapus permanen.');
}



}
