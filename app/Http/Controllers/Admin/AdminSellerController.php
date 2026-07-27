<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Seller;
use App\Models\SellerWithdrawal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminSellerController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    public function index(Request $request)
    {
        $query = Seller::with('user');

        if ($request->filled('search')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%');
            });
            $query->orWhere('shop_name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('status')) {
            $query->where('approval_status', $request->status);
        }

        $sellers = $query->latest()->paginate(15);
        $pendingCount = Seller::where('approval_status', 'pending')->count();

        return view('admin.sellers.index', compact('sellers', 'pendingCount'));
    }

    public function show(Seller $seller)
    {
        $seller->load(['user', 'products', 'transactions.order', 'withdrawals', 'approvedBy']);

        $stats = [
            'total_products' => $seller->products()->count(),
            'total_orders' => $seller->transactions()->count(),
            'total_earnings' => $seller->total_earnings,
            'balance' => $seller->balance,
            'total_withdrawn' => $seller->total_withdrawn,
        ];

        return view('admin.sellers.show', compact('seller', 'stats'));
    }

    public function approve(Seller $seller)
    {
        $seller->update([
            'approval_status' => 'approved',
            'is_active' => true,
            'approved_at' => now(),
            'approved_by' => Auth::id(),
        ]);

        return back()->with('success', 'Seller berhasil disetujui! Seller sudah bisa mulai berjualan.');
    }

    public function reject(Request $request, Seller $seller)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        $seller->update([
            'approval_status' => 'rejected',
            'is_active' => false,
            'rejection_reason' => $request->rejection_reason,
        ]);

        return back()->with('success', 'Registrasi seller ditolak.');
    }

    public function toggleVerification(Seller $seller)
    {
        $seller->update(['is_verified' => !$seller->is_verified]);

        return back()->with('success', 'Status verifikasi seller berhasil diubah!');
    }

    public function toggleStatus(Seller $seller)
    {
        $seller->update(['is_active' => !$seller->is_active]);

        return back()->with('success', 'Status seller berhasil diubah!');
    }

    public function withdrawals(Request $request)
    {
        $query = SellerWithdrawal::with(['seller.user', 'processedBy']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $withdrawals = $query->latest()->paginate(15);

        return view('admin.sellers.withdrawals', compact('withdrawals'));
    }

    public function showWithdrawal(SellerWithdrawal $withdrawal)
    {
        $withdrawal->load(['seller.user', 'processedBy']);

        return view('admin.sellers.withdrawal-detail', compact('withdrawal'));
    }

    public function processWithdrawal(Request $request, SellerWithdrawal $withdrawal)
    {
        $request->validate([
            'action' => 'required|in:complete,reject',
            'admin_notes' => 'nullable|string|max:500',
        ]);

        if ($withdrawal->status !== 'pending') {
            return back()->with('error', 'Penarikan ini sudah diproses sebelumnya.');
        }

        DB::beginTransaction();

        try {
            $seller = $withdrawal->seller;

            if ($request->action === 'complete') {
                if ($seller->balance < $withdrawal->amount) {
                    DB::rollBack();
                    return back()->with('error', 'Saldo seller tidak mencukupi.');
                }

                $seller->decrement('balance', $withdrawal->amount);
                $seller->increment('total_withdrawn', $withdrawal->amount);

                $withdrawal->update([
                    'status' => 'completed',
                    'admin_notes' => $request->admin_notes,
                    'processed_at' => now(),
                    'processed_by' => Auth::id(),
                ]);

                DB::commit();

                return back()->with('success', 'Penarikan berhasil diproses!');
            } else {
                $withdrawal->update([
                    'status' => 'rejected',
                    'admin_notes' => $request->admin_notes,
                    'processed_at' => now(),
                    'processed_by' => Auth::id(),
                ]);

                DB::commit();

                return back()->with('success', 'Penarikan ditolak.');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memproses penarikan: ' . $e->getMessage());
        }
    }
}
