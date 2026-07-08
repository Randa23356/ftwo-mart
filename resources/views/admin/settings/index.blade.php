@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50" x-data="{ tab: 'general' }">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">

        <!-- PAGE HEADER -->
        <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-xl border border-white/20 p-6 sm:p-8 mb-6">
            <div class="flex items-center space-x-4">
                <div class="bg-gradient-to-br from-green-600 to-emerald-700 p-3 sm:p-4 rounded-2xl shadow-lg flex-shrink-0">
                    <i class="fas fa-cog text-white text-xl sm:text-2xl"></i>
                </div>
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Pengaturan Website</h1>
                    <p class="text-sm text-gray-500 mt-0.5">Kelola konfigurasi dan konten website</p>
                </div>
            </div>
        </div>

        <!-- SUCCESS ALERT -->
        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-800 px-5 py-4 rounded-2xl mb-6 flex items-center gap-3 shadow-sm">
                <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-check-circle text-green-600 text-sm"></i>
                </div>
                <span class="text-sm font-medium">{{ session('success') }}</span>
            </div>
        @endif

        <!-- TAB NAVIGATION -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 mb-6 overflow-x-auto">
            <nav class="flex min-w-max sm:min-w-0">
                @foreach([
                    ['key' => 'general',  'icon' => 'fa-sliders-h',   'label' => 'General'],
                    ['key' => 'images',   'icon' => 'fa-images',      'label' => 'Images'],
                    ['key' => 'about',    'icon' => 'fa-info-circle',  'label' => 'About Page'],
                    ['key' => 'contact',  'icon' => 'fa-address-book', 'label' => 'Contact Page'],
                ] as $t)
                <button type="button"
                        @click="tab = '{{ $t['key'] }}'"
                        :class="tab === '{{ $t['key'] }}'
                            ? 'text-green-700 border-b-2 border-green-600 bg-green-50 font-semibold'
                            : 'text-gray-500 border-b-2 border-transparent hover:text-gray-700 hover:bg-gray-50'"
                        class="flex items-center gap-2 px-5 py-4 text-sm transition-all duration-200 whitespace-nowrap">
                    <i class="fas {{ $t['icon'] }} text-xs"></i>
                    {{ $t['label'] }}
                </button>
                @endforeach
            </nav>
        </div>

        <!-- GENERAL TAB -->
        <div x-show="tab === 'general'" x-transition>
            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                <div class="bg-gradient-to-r from-green-50 to-emerald-50 px-6 py-4 border-b border-green-100 flex items-center gap-3">
                    <div class="w-9 h-9 bg-gradient-to-br from-green-600 to-emerald-700 rounded-xl flex items-center justify-center shadow-md">
                        <i class="fas fa-sliders-h text-white text-sm"></i>
                    </div>
                    <div>
                        <h2 class="text-base font-bold text-gray-900">Pengaturan Umum</h2>
                        <p class="text-xs text-gray-500">Informasi dasar website</p>
                    </div>
                </div>
                <form action="{{ route('admin.settings.update') }}" method="POST">
                    @csrf @method('PUT')
                    <div class="p-6 sm:p-8 grid grid-cols-1 md:grid-cols-2 gap-5">
                        @php $inp = 'w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all text-sm bg-gray-50 focus:bg-white'; @endphp

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Website</label>
                            <input type="text" name="website_name" value="{{ old('website_name', $settings['website_name']->value ?? '') }}" class="{{ $inp }}" placeholder="Batik Sasambo">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
                            <input type="email" name="email" value="{{ old('email', $settings['email']->value ?? '') }}" class="{{ $inp }}" placeholder="info@batiksamasbo.com">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Telepon</label>
                            <input type="text" name="phone" value="{{ old('phone', $settings['phone']->value ?? '') }}" class="{{ $inp }}" placeholder="08xxxxxxxxxx">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Jam Operasional</label>
                            <input type="text" name="opening_hours" value="{{ old('opening_hours', $settings['opening_hours']->value ?? '') }}" class="{{ $inp }}" placeholder="Senin–Sabtu, 08.00–17.00">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">URL Instagram</label>
                            <div class="relative">
                                <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-sm"><i class="fab fa-instagram"></i></span>
                                <input type="text" name="instagram" value="{{ old('instagram', $settings['instagram']->value ?? '') }}" class="{{ $inp }} pl-9" placeholder="https://instagram.com/...">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">URL Facebook</label>
                            <div class="relative">
                                <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-sm"><i class="fab fa-facebook"></i></span>
                                <input type="text" name="facebook" value="{{ old('facebook', $settings['facebook']->value ?? '') }}" class="{{ $inp }} pl-9" placeholder="https://facebook.com/...">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Biaya Pengiriman (Rp)</label>
                            <div class="relative">
                                <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-sm font-semibold text-gray-400">Rp</span>
                                <input type="number" name="delivery_fee" value="{{ old('delivery_fee', $settings['delivery_fee']->value ?? '0') }}" class="{{ $inp }} pl-10">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Minimum Order (Rp)</label>
                            <div class="relative">
                                <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-sm font-semibold text-gray-400">Rp</span>
                                <input type="number" name="min_order" value="{{ old('min_order', $settings['min_order']->value ?? '0') }}" class="{{ $inp }} pl-10">
                            </div>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Alamat</label>
                            <textarea name="address" rows="3" class="{{ $inp }} resize-none" placeholder="Alamat lengkap toko...">{{ old('address', $settings['address']->value ?? '') }}</textarea>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Deskripsi Website</label>
                            <textarea name="website_description" rows="4" class="{{ $inp }} resize-none" placeholder="Deskripsi singkat tentang website...">{{ old('website_description', $settings['website_description']->value ?? '') }}</textarea>
                        </div>
                    </div>
                    <div class="px-6 sm:px-8 py-4 bg-gray-50 border-t border-gray-100 flex justify-end">
                        <button type="submit" class="inline-flex items-center px-6 py-2.5 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white rounded-xl text-sm font-semibold shadow-md hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200">
                            <i class="fas fa-save mr-2"></i> Simpan Pengaturan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- IMAGES TAB -->
        <div x-show="tab === 'images'" x-transition>
            <!-- Logo -->
            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden mb-6">
                <div class="bg-gradient-to-r from-green-50 to-emerald-50 px-6 py-4 border-b border-green-100 flex items-center gap-3">
                    <div class="w-9 h-9 bg-gradient-to-br from-green-600 to-emerald-700 rounded-xl flex items-center justify-center shadow-md">
                        <i class="fas fa-image text-white text-sm"></i>
                    </div>
                    <div>
                        <h2 class="text-base font-bold text-gray-900">Logo Website</h2>
                        <p class="text-xs text-gray-500">Logo yang tampil di navbar</p>
                    </div>
                </div>
                <form action="{{ route('admin.settings.logo') }}" method="POST" enctype="multipart/form-data">
                    @csrf @method('PUT')
                    <div class="p-6 sm:p-8">
                        <div class="flex flex-col sm:flex-row items-start sm:items-center gap-6">
                            <div class="flex-shrink-0">
                                <div class="w-24 h-24 rounded-2xl border-2 border-gray-200 overflow-hidden bg-gray-50 flex items-center justify-center shadow-inner">
                                    @if(isset($settings['logo']) && $settings['logo']->value)
                                        <img src="{{ asset('storage/' . $settings['logo']->value) }}" alt="Logo" class="w-full h-full object-contain p-2">
                                    @else
                                        <i class="fas fa-image text-gray-300 text-3xl"></i>
                                    @endif
                                </div>
                                <p class="text-xs text-gray-400 text-center mt-2">Logo saat ini</p>
                            </div>
                            <div class="flex-1">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Upload Logo Baru</label>
                                <input type="file" name="logo" accept="image/*"
                                       class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100 transition-all">
                                <p class="text-xs text-gray-400 mt-2"><i class="fas fa-info-circle mr-1 text-green-400"></i> Rekomendasi: PNG transparan, ukuran 200×60px</p>
                                @error('logo') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>
                    <div class="px-6 sm:px-8 py-4 bg-gray-50 border-t border-gray-100 flex justify-end">
                        <button type="submit" class="inline-flex items-center px-6 py-2.5 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white rounded-xl text-sm font-semibold shadow-md hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200">
                            <i class="fas fa-upload mr-2"></i> Update Logo
                        </button>
                    </div>
                </form>
            </div>

            <!-- Hero Image -->
            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                <div class="bg-gradient-to-r from-green-50 to-emerald-50 px-6 py-4 border-b border-green-100 flex items-center gap-3">
                    <div class="w-9 h-9 bg-gradient-to-br from-green-600 to-emerald-700 rounded-xl flex items-center justify-center shadow-md">
                        <i class="fas fa-panorama text-white text-sm"></i>
                    </div>
                    <div>
                        <h2 class="text-base font-bold text-gray-900">Hero Image</h2>
                        <p class="text-xs text-gray-500">Gambar banner utama halaman beranda</p>
                    </div>
                </div>
                <form action="{{ route('admin.settings.hero-image') }}" method="POST" enctype="multipart/form-data">
                    @csrf @method('PUT')
                    <div class="p-6 sm:p-8">
                        <div class="rounded-2xl overflow-hidden border-2 border-gray-200 mb-4 bg-gray-50 max-h-56 flex items-center justify-center">
                            @if(isset($settings['hero_image']) && $settings['hero_image']->value)
                                <img src="{{ asset('storage/' . $settings['hero_image']->value) }}" alt="Hero" class="w-full h-56 object-cover">
                            @else
                                <div class="flex flex-col items-center py-12 text-gray-300">
                                    <i class="fas fa-panorama text-5xl mb-2"></i>
                                    <p class="text-sm">Belum ada hero image</p>
                                </div>
                            @endif
                        </div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Upload Hero Image Baru</label>
                        <input type="file" name="hero_image" accept="image/*"
                               class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100 transition-all">
                        <p class="text-xs text-gray-400 mt-2"><i class="fas fa-info-circle mr-1 text-green-400"></i> Rekomendasi: JPG/PNG, rasio 16:6, min 1200px lebar</p>
                        @error('hero_image') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="px-6 sm:px-8 py-4 bg-gray-50 border-t border-gray-100 flex justify-end">
                        <button type="submit" class="inline-flex items-center px-6 py-2.5 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white rounded-xl text-sm font-semibold shadow-md hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200">
                            <i class="fas fa-upload mr-2"></i> Update Hero Image
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- ABOUT TAB -->
        <div x-show="tab === 'about'" x-transition>
            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                <div class="bg-gradient-to-r from-green-50 to-emerald-50 px-6 py-4 border-b border-green-100 flex items-center gap-3">
                    <div class="w-9 h-9 bg-gradient-to-br from-green-600 to-emerald-700 rounded-xl flex items-center justify-center shadow-md">
                        <i class="fas fa-info-circle text-white text-sm"></i>
                    </div>
                    <div>
                        <h2 class="text-base font-bold text-gray-900">Halaman Tentang Kami</h2>
                        <p class="text-xs text-gray-500">Konten halaman About</p>
                    </div>
                </div>
                <form action="{{ route('admin.settings.about') }}" method="POST" enctype="multipart/form-data">
                    @csrf @method('PUT')
                    <div class="p-6 sm:p-8 space-y-5">
                        @php $inp = 'w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all text-sm bg-gray-50 focus:bg-white'; @endphp
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Judul About</label>
                            <input type="text" name="about_title" value="{{ old('about_title', $settings['about_title']->value ?? '') }}" class="{{ $inp }}" placeholder="Tentang Batik Sasambo">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Konten About</label>
                            <textarea name="about_content" rows="8" class="{{ $inp }} resize-none" placeholder="Ceritakan tentang toko Anda...">{{ old('about_content', $settings['about_content']->value ?? '') }}</textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Gambar About</label>
                            @if(isset($settings['about_image']) && $settings['about_image']->value)
                                <div class="mb-3 rounded-xl overflow-hidden border-2 border-gray-200 max-w-xs">
                                    <img src="{{ asset('storage/' . $settings['about_image']->value) }}" alt="About" class="w-full h-40 object-cover">
                                </div>
                            @endif
                            <input type="file" name="about_image" accept="image/*"
                                   class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100 transition-all">
                        </div>
                    </div>
                    <div class="px-6 sm:px-8 py-4 bg-gray-50 border-t border-gray-100 flex justify-end">
                        <button type="submit" class="inline-flex items-center px-6 py-2.5 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white rounded-xl text-sm font-semibold shadow-md hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200">
                            <i class="fas fa-save mr-2"></i> Simpan Halaman About
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- CONTACT TAB -->
        <div x-show="tab === 'contact'" x-transition>
            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                <div class="bg-gradient-to-r from-green-50 to-emerald-50 px-6 py-4 border-b border-green-100 flex items-center gap-3">
                    <div class="w-9 h-9 bg-gradient-to-br from-green-600 to-emerald-700 rounded-xl flex items-center justify-center shadow-md">
                        <i class="fas fa-address-book text-white text-sm"></i>
                    </div>
                    <div>
                        <h2 class="text-base font-bold text-gray-900">Halaman Kontak</h2>
                        <p class="text-xs text-gray-500">Konten spesifik halaman Kontak</p>
                    </div>
                </div>
                <form action="{{ route('admin.settings.contact') }}" method="POST">
                    @csrf @method('PUT')
                    <div class="p-6 sm:p-8">
                        <div class="bg-green-50 border border-green-200 rounded-xl p-4 mb-5 text-sm text-green-700 flex items-start gap-3">
                            <i class="fas fa-info-circle mt-0.5 flex-shrink-0"></i>
                            <span>Informasi kontak utama (Telepon, Email, Alamat) diatur di tab <strong>General</strong>.</span>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            @php $inp = 'w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all text-sm bg-gray-50 focus:bg-white'; @endphp
                            <div class="md:col-span-2">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Judul Halaman Kontak</label>
                                <input type="text" name="contact_title" value="{{ old('contact_title', $settings['contact_title']->value ?? '') }}" class="{{ $inp }}" placeholder="Hubungi Kami">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Deskripsi Kontak</label>
                                <textarea name="contact_description" rows="3" class="{{ $inp }} resize-none" placeholder="Deskripsi singkat...">{{ old('contact_description', $settings['contact_description']->value ?? '') }}</textarea>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Nomor WhatsApp</label>
                                <div class="relative">
                                    <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-green-500 text-sm"><i class="fab fa-whatsapp"></i></span>
                                    <input type="text" name="contact_whatsapp" value="{{ old('contact_whatsapp', $settings['contact_whatsapp']->value ?? '') }}" class="{{ $inp }} pl-9" placeholder="628xxxxxxxxxx">
                                </div>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Google Maps Embed URL</label>
                                <textarea name="contact_maps_embed" rows="4" class="{{ $inp }} resize-none text-xs font-mono" placeholder="https://maps.google.com/maps?q=...">{{ old('contact_maps_embed', $settings['contact_maps_embed']->value ?? '') }}</textarea>
                                <p class="text-xs text-gray-400 mt-1.5"><i class="fas fa-info-circle mr-1 text-green-400"></i> Salin URL dari Google Maps → Share → Embed a map</p>
                            </div>
                        </div>
                    </div>
                    <div class="px-6 sm:px-8 py-4 bg-gray-50 border-t border-gray-100 flex justify-end">
                        <button type="submit" class="inline-flex items-center px-6 py-2.5 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white rounded-xl text-sm font-semibold shadow-md hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200">
                            <i class="fas fa-save mr-2"></i> Simpan Halaman Kontak
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
@endsection
