@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="max-w-2xl mx-auto px-4 py-8">
        <div class="bg-white/80 backdrop-blur-xl rounded-2xl lg:rounded-3xl shadow-xl border border-white/20 p-6 lg:p-8 mb-8">
            <div class="text-center mb-8">
                <div class="bg-gradient-to-r from-green-500 to-emerald-600 w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg">
                    <i class="fas fa-store text-white text-2xl"></i>
                </div>
                <h1 class="text-2xl lg:text-3xl font-bold text-gray-900">Jadi Seller</h1>
                <p class="text-gray-600 mt-2">Mulai berjualan di FtwoMart dan hasilkan uang dari produkmu!</p>
            </div>

            <div class="bg-green-50 border border-green-200 rounded-xl p-4 mb-6">
                <h3 class="text-green-800 font-semibold mb-2"><i class="fas fa-info-circle mr-1"></i> Keuntungan Jadi Seller:</h3>
                <ul class="text-green-700 text-sm space-y-1">
                    <li><i class="fas fa-check mr-1"></i> Upload produk sendiri sesukamu</li>
                    <li><i class="fas fa-check mr-1"></i> Kelola stok dan harga secara mandiri</li>
                    <li><i class="fas fa-check mr-1"></i> Tarik uang kapan saja</li>
                    <li><i class="fas fa-check mr-1"></i> Komisi platform hanya 5%</li>
                </ul>
            </div>

            @if ($errors->any())
            <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-6">
                <ul class="text-red-600 text-sm space-y-1">
                    @foreach ($errors->all() as $error)
                        <li><i class="fas fa-exclamation-circle mr-1"></i> {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form method="POST" action="{{ route('seller.register.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="space-y-5">
                    <!-- Toko Info -->
                    <div class="border-b border-gray-100 pb-4">
                        <h3 class="text-sm font-bold text-gray-700 uppercase tracking-wider mb-3"><i class="fas fa-store mr-1 text-green-600"></i> Informasi Toko</h3>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Toko *</label>
                        <input type="text" name="shop_name" value="{{ old('shop_name') }}" required
                               class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all"
                               placeholder="Contoh: Batik Lombok Shop">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Deskripsi Toko</label>
                        <textarea name="shop_description" rows="3"
                                  class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all"
                                  placeholder="Ceritakan tentang toko kamu...">{{ old('shop_description') }}</textarea>
                    </div>

                    <!-- Bank Info -->
                    <div class="border-t border-gray-100 pt-4">
                        <h3 class="text-sm font-bold text-gray-700 uppercase tracking-wider mb-3"><i class="fas fa-university mr-1 text-blue-600"></i> Info Rekening Bank</h3>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Bank *</label>
                        <select name="bank_name" required
                                class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all">
                            <option value="">Pilih Bank</option>
                            @foreach(['BCA','BRI','BNI','Mandiri','CIMB Niaga','BSI','BTN','Danamon','Permata','OCBC NISP','Maybank'] as $bank)
                                <option value="{{ $bank }}" {{ old('bank_name') === $bank ? 'selected' : '' }}>{{ $bank }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Nomor Rekening *</label>
                            <input type="text" name="bank_account_number" value="{{ old('bank_account_number') }}" required
                                   class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                   placeholder="1234567890">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Atas Nama *</label>
                            <input type="text" name="bank_account_name" value="{{ old('bank_account_name') }}" required
                                   class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                   placeholder="Nama Pemilik Rekening">
                        </div>
                    </div>

                    <!-- Documents -->
                    <div class="border-t border-gray-100 pt-4">
                        <h3 class="text-sm font-bold text-gray-700 uppercase tracking-wider mb-1"><i class="fas fa-file-alt mr-1 text-orange-600"></i> Dokumen Verifikasi</h3>
                        <p class="text-xs text-gray-500 mb-3">Upload foto dokumen yang jelas. Format: JPG/PNG, maks 5MB.</p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">KTP *</label>
                            <div class="relative">
                                <input type="file" name="ktp" accept="image/*" required
                                       class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-green-500 focus:border-transparent text-sm file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:bg-green-50 file:text-green-700 file:font-semibold file:text-xs hover:file:bg-green-100">
                            </div>
                            <p class="text-xs text-gray-400 mt-1">Foto KTP yang masih berlaku</p>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">NIB (Nomor Induk Berusaha) *</label>
                            <input type="file" name="nib" accept="image/*" required
                                   class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-green-500 focus:border-transparent text-sm file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:bg-blue-50 file:text-blue-700 file:font-semibold file:text-xs hover:file:bg-blue-100">
                            <p class="text-xs text-gray-400 mt-1">NIB atau NIK (untuk perorangan)</p>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">NPWP (Opsional)</label>
                            <input type="file" name="npwp" accept="image/*"
                                   class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-green-500 focus:border-transparent text-sm file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:bg-purple-50 file:text-purple-700 file:font-semibold file:text-xs hover:file:bg-purple-100">
                            <p class="text-xs text-gray-400 mt-1">NPWP jika ada</p>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Foto Rekening Tabungan *</label>
                            <input type="file" name="rekening_tabungan" accept="image/*" required
                                   class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-green-500 focus:border-transparent text-sm file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:bg-orange-50 file:text-orange-700 file:font-semibold file:text-xs hover:file:bg-orange-100">
                            <p class="text-xs text-gray-400 mt-1">Foto/scan buku tabungan atau mutasi rekening</p>
                        </div>
                    </div>
                </div>

                <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-3 mt-4">
                    <p class="text-xs text-yellow-700"><i class="fas fa-exclamation-triangle mr-1"></i> Dokumen akan diverifikasi oleh admin. Proses verifikasi biasanya memakan waktu 1-2 hari kerja.</p>
                </div>

                <div class="mt-6">
                    <button type="submit"
                            class="w-full bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white font-bold py-3 rounded-xl transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                        <i class="fas fa-rocket mr-2"></i> Daftar Sebagai Seller
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
