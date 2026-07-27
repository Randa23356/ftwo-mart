<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Seller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SellerRegistrationController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    public function show()
    {
        $user = Auth::user();
        if ($user->isAdmin() || $user->isOperator()) {
            return redirect()->route('home')->with('error', 'Akun admin/operator tidak bisa mendaftar sebagai seller.');
        }

        if ($seller = $user->seller) {
            if ($seller->approval_status === 'approved') {
                return redirect()->route('seller.dashboard');
            }
            return view('seller.register-pending', compact('seller'));
        }

        return view('seller.register');
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        if ($user->isAdmin() || $user->isOperator()) {
            return redirect()->route('home')->with('error', 'Akun admin/operator tidak bisa mendaftar sebagai seller.');
        }

        if ($user->seller) {
            return redirect()->route('seller.dashboard');
        }

        $validated = $request->validate([
            'shop_name' => 'required|string|max:255',
            'shop_description' => 'nullable|string|max:1000',
            'bank_name' => 'required|string|max:255',
            'bank_account_number' => 'required|string|max:255',
            'bank_account_name' => 'required|string|max:255',
            'ktp' => 'required|image|mimes:jpg,jpeg,png|max:5120',
            'nib' => 'required|image|mimes:jpg,jpeg,png|max:5120',
            'npwp' => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
            'rekening_tabungan' => 'required|image|mimes:jpg,jpeg,png|max:5120',
        ]);

        $paths = [];
        foreach (['ktp', 'nib', 'rekening_tabungan'] as $field) {
            $paths[$field . '_path'] = $request->file($field)->store('seller-documents/' . Auth::id(), 'public');
        }
        if ($request->hasFile('npwp')) {
            $paths['npwp_path'] = $request->file('npwp')->store('seller-documents/' . Auth::id(), 'public');
        }

        $seller = Seller::create([
            'user_id' => Auth::id(),
            'shop_name' => $validated['shop_name'],
            'shop_description' => $validated['shop_description'] ?? null,
            'bank_name' => $validated['bank_name'],
            'bank_account_number' => $validated['bank_account_number'],
            'bank_account_name' => $validated['bank_account_name'],
            'approval_status' => 'pending',
            'is_active' => false,
            ...$paths,
        ]);

        Auth::user()->assignRole('seller');

        return redirect()->route('seller.register')
            ->with('success', 'Registrasi seller berhasil diajukan! Silakan tunggu persetujuan admin.');
    }
}
