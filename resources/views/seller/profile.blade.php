@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6 lg:py-8">
        <!-- Header -->
        <div class="bg-white/80 backdrop-blur-xl rounded-2xl lg:rounded-3xl shadow-xl border border-white/20 p-6 lg:p-8 mb-8">
            <div class="flex items-center space-x-4">
                <div class="bg-gradient-to-r from-green-500 to-emerald-600 p-3 lg:p-4 rounded-2xl shadow-lg">
                    <i class="fas fa-store text-white text-xl lg:text-2xl"></i>
                </div>
                <div>
                    <h1 class="text-2xl lg:text-3xl font-bold text-gray-900">Profil Toko</h1>
                    <p class="text-gray-600 text-sm">Kelola informasi toko Anda</p>
                </div>
            </div>
        </div>

        <form action="{{ route('seller.profile.update') }}" method="POST" enctype="multipart/form-data" x-data="{ logoPreview: '{{ $seller->logo_url }}', bannerPreview: '{{ $seller->banner_url }}' }">
            @csrf
            @method('PUT')

            <!-- Banner -->
            <div class="bg-white/80 backdrop-blur-xl rounded-2xl lg:rounded-3xl shadow-xl border border-white/20 overflow-hidden mb-6">
                <div class="p-6 border-b border-gray-100">
                    <h3 class="font-bold text-gray-900"><i class="fas fa-image mr-2 text-green-600"></i>Banner Toko</h3>
                    <p class="text-sm text-gray-500 mt-1">Gambar banner yang ditampilkan di halaman toko Anda (1200x400px recommended)</p>
                </div>
                <div class="p-6">
                    <div class="relative w-full h-48 bg-gray-100 rounded-xl overflow-hidden border-2 border-dashed border-gray-300 hover:border-green-400 transition-colors cursor-pointer"
                         @click="$refs.bannerInput.click()">
                        <template x-if="bannerPreview">
                            <img :src="bannerPreview" class="w-full h-full object-cover">
                        </template>
                        <template x-if="!bannerPreview">
                            <div class="flex flex-col items-center justify-center h-full text-gray-400">
                                <i class="fas fa-cloud-upload-alt text-4xl mb-2"></i>
                                <p class="text-sm font-medium">Klik untuk upload banner</p>
                            </div>
                        </template>
                    </div>
                    <input type="file" name="banner" x-ref="bannerInput" accept="image/*" class="hidden"
                           @change="bannerPreview = URL.createObjectURL($event.target.files[0])">
                    @error('banner') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <!-- Logo & Shop Name -->
            <div class="bg-white/80 backdrop-blur-xl rounded-2xl lg:rounded-3xl shadow-xl border border-white/20 overflow-hidden mb-6">
                <div class="p-6 border-b border-gray-100">
                    <h3 class="font-bold text-gray-900"><i class="fas fa-store mr-2 text-green-600"></i>Identitas Toko</h3>
                </div>
                <div class="p-6">
                    <div class="flex flex-col sm:flex-row gap-6">
                        <!-- Logo -->
                        <div class="flex-shrink-0">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Logo Toko</label>
                            <div class="relative w-32 h-32 bg-gray-100 rounded-2xl overflow-hidden border-2 border-dashed border-gray-300 hover:border-green-400 transition-colors cursor-pointer shadow-sm"
                                 @click="$refs.logoInput.click()">
                                <template x-if="logoPreview">
                                    <img :src="logoPreview" class="w-full h-full object-cover">
                                </template>
                                <template x-if="!logoPreview">
                                    <div class="flex flex-col items-center justify-center h-full text-gray-400">
                                        <i class="fas fa-camera text-2xl mb-1"></i>
                                        <p class="text-[10px] font-medium">Upload Logo</p>
                                    </div>
                                </template>
                            </div>
                            <input type="file" name="logo" x-ref="logoInput" accept="image/*" class="hidden"
                                   @change="logoPreview = URL.createObjectURL($event.target.files[0])">
                            @error('logo') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Shop Info -->
                        <div class="flex-1 space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Toko <span class="text-red-500">*</span></label>
                                <input type="text" name="shop_name" value="{{ old('shop_name', $seller->shop_name) }}" required
                                       class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all @error('shop_name') border-red-500 @enderror">
                                @error('shop_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi Toko</label>
                                <textarea name="shop_description" rows="4" maxlength="1000"
                                          class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all resize-none @error('shop_description') border-red-500 @enderror"
                                          placeholder="Ceritakan tentang toko Anda...">{{ old('shop_description', $seller->shop_description) }}</textarea>
                                <p class="text-xs text-gray-400 mt-1"><span x-text="document.querySelector('[name=shop_description]').value.length"></span>/1000</p>
                                @error('shop_description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bank Info -->
            <div class="bg-white/80 backdrop-blur-xl rounded-2xl lg:rounded-3xl shadow-xl border border-white/20 overflow-hidden mb-6">
                <div class="p-6 border-b border-gray-100">
                    <h3 class="font-bold text-gray-900"><i class="fas fa-university mr-2 text-green-600"></i>Informasi Bank</h3>
                    <p class="text-sm text-gray-500 mt-1">Digunakan untuk penarikan dana</p>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Bank <span class="text-red-500">*</span></label>
                        <input type="text" name="bank_name" value="{{ old('bank_name', $seller->bank_name) }}" required
                               placeholder="Contoh: BCA, Mandiri, BRI"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all @error('bank_name') border-red-500 @enderror">
                        @error('bank_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Rekening <span class="text-red-500">*</span></label>
                        <input type="text" name="bank_account_number" value="{{ old('bank_account_number', $seller->bank_account_number) }}" required
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all @error('bank_account_number') border-red-500 @enderror">
                        @error('bank_account_number') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Pemilik Rekening <span class="text-red-500">*</span></label>
                        <input type="text" name="bank_account_name" value="{{ old('bank_account_name', $seller->bank_account_name) }}" required
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all @error('bank_account_name') border-red-500 @enderror">
                        @error('bank_account_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <!-- Origin City for Shipping -->
            <div class="bg-white/80 backdrop-blur-xl rounded-2xl lg:rounded-3xl shadow-xl border border-white/20 overflow-hidden mb-6">
                <div class="p-6 border-b border-gray-100">
                    <h3 class="font-bold text-gray-900"><i class="fas fa-map-marker-alt mr-2 text-blue-600"></i>Lokasi Pengiriman Toko</h3>
                    <p class="text-sm text-gray-500 mt-1">Pilih kota asal pengiriman produk Anda</p>
                </div>
                <div class="p-6">
                    <div>
                        <label for="origin_city_id" class="block text-sm font-medium text-gray-700 mb-1">Kota Asal Pengiriman</label>
                        <select name="origin_city_id" id="origin_city_id"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all @error('origin_city_id') border-red-500 @enderror">
                            <option value="">-- Pilih Kota Asal (Kosongkan jika platform pusat) --</option>
                            @foreach(\App\Models\City::orderBy('province')->orderBy('city_name')->get() as $city)
                                <option value="{{ $city->city_id }}"
                                    {{ old('origin_city_id', $seller->user->origin_city_id) == $city->city_id ? 'selected' : '' }}>
                                    {{ $city->type }} {{ $city->city_name }}, {{ $city->province }}
                                </option>
                            @endforeach
                        </select>
                        @error('origin_city_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        <p class="text-xs text-gray-400 mt-1">Kosongkan jika produk dikirim dari platform pusat</p>
                    </div>
                </div>
            </div>

            <!-- Submit -->
            <div class="flex justify-end gap-3">
                <a href="{{ route('seller.dashboard') }}" class="px-6 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-xl transition-all">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white font-semibold rounded-xl transition-all shadow-lg hover:shadow-xl">
                    <i class="fas fa-save mr-2"></i>Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection