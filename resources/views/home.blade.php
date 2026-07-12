@extends('layouts.app')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
<style>
    .material-symbols-outlined {
        font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        vertical-align: middle;
    }
    .glass-card {
        background: rgba(255, 255, 255, 0.7);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
    }
    .hero-gradient {
        background: linear-gradient(135deg, #004426 0%, #006e2f 100%);
    }
    .ambient-shadow {
        box-shadow: 0 4px 20px rgba(15, 23, 42, 0.05);
        transition: box-shadow 0.3s ease, transform 0.3s ease;
    }
    .ambient-shadow:hover {
        box-shadow: 0 12px 40px rgba(15, 23, 42, 0.08);
        transform: translateY(-4px) scale(1.02);
    }
    .bento-img {
        transition: transform 0.7s ease;
    }
    .bento-img:hover {
        transform: scale(1.1);
    }
    @keyframes float-slow {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-12px); }
    }
    .animate-float-slow {
        animation: float-slow 5s ease-in-out infinite;
    }
</style>
@endpush

@section('content')
<!-- Hero Section -->
<section class="relative min-h-[85vh] flex items-center overflow-hidden hero-gradient py-12">
    <!-- Decorative Blur -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-[10%] -left-[10%] w-[60%] lg:w-[40%] h-[40%] bg-green-500/30 blur-[100px] rounded-full animate-pulse"></div>
        <div class="absolute top-[20%] -right-[5%] w-[40%] lg:w-[30%] h-[50%] bg-green-400/20 blur-[80px] rounded-full"></div>
    </div>

    @if(isset($settings['hero_image']) && $settings['hero_image']->value)
        <div class="absolute inset-0">
            <img src="{{ asset('storage/' . $settings['hero_image']->value) }}" alt="Hero Image" class="w-full h-full object-cover opacity-10 mix-blend-overlay">
        </div>
    @endif

    <div class="max-w-7xl mx-auto px-5 md:px-16 grid md:grid-cols-2 gap-8 items-center relative z-10 w-full">
        <!-- Left Content -->
        <div class="text-white space-y-8"
             x-data="{ show: false }"
             x-init="setTimeout(() => show = true, 100)"
             x-show="show"
             x-transition:enter="transition ease-out duration-1000"
             x-transition:enter-start="opacity-0 -translate-x-12"
             x-transition:enter-end="opacity-100 translate-x-0">

            <div class="inline-flex items-center gap-2 bg-white/10 border border-white/20 px-4 py-1.5 rounded-full">
                <span class="w-2 h-2 rounded-full bg-green-300 animate-pulse"></span>
                <span class="text-xs font-semibold uppercase tracking-wider text-green-200">Koleksi Terbaru 2025</span>
            </div>

            <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold leading-tight tracking-tight">
                {{ $settings['website_name']->value ?? 'FtwoMart' }}
                <br/>
                <span class="text-green-200">Marketplace Terpercaya</span>
            </h1>

            <p class="text-base md:text-lg text-green-100/80 max-w-lg leading-relaxed">
                {{ $settings['website_description']->value ?? 'Marketplace terpercaya untuk berbagai produk berkualitas dengan pelayanan terbaik. Bergabunglah dengan ribuan pelanggan yang puas.' }}
            </p>

            <div class="flex flex-wrap gap-4 pt-2">
                <a href="{{ route('products') }}" class="bg-green-600 text-white font-semibold text-sm px-8 py-4 rounded-xl flex items-center gap-2 shadow-lg hover:bg-green-500 transition-all group">
                    <i class="fas fa-shopping-bag group-hover:rotate-12 transition-transform"></i>
                    Lihat Koleksi
                </a>
                <a href="{{ route('about') }}" class="border-2 border-green-300 text-green-200 font-semibold text-sm px-8 py-4 rounded-xl flex items-center gap-2 hover:bg-white/10 transition-all">
                    <i class="fas fa-info-circle"></i>
                    Tentang Kami
                </a>
            </div>

            <div class="flex gap-6 pt-2 text-green-200/70 text-sm font-medium">
                <div class="flex items-center gap-2">
                    <i class="fas fa-check-circle text-green-300"></i> Produk Asli
                </div>
                <div class="flex items-center gap-2">
                    <i class="fas fa-check-circle text-green-300"></i> Kualitas Premium
                </div>
            </div>
        </div>

        <!-- Right Content -->
        <div class="relative hidden md:block"
             x-data="{ show: false }"
             x-init="setTimeout(() => show = true, 300)"
             x-show="show"
             x-transition:enter="transition ease-out duration-1000"
             x-transition:enter-start="opacity-0 scale-90 translate-y-12"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0">

            <div class="relative rounded-[2.5rem] overflow-hidden aspect-square ambient-shadow border-4 border-white/10">
                @if(isset($settings['hero_image']) && $settings['hero_image']->value)
                    <img src="{{ asset('storage/' . $settings['hero_image']->value) }}" alt="Hero Image" class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full bg-green-800/50 flex items-center justify-center">
                        <i class="fas fa-shopping-bag text-[8rem] text-white/20"></i>
                    </div>
                @endif

                <!-- Floating Rating Card -->
                <div class="absolute top-8 right-8 glass-card p-4 rounded-2xl shadow-xl border border-white/30 animate-float-slow">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center text-green-600">
                            <i class="fas fa-star text-xl"></i>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 font-medium">Rating Pelanggan</p>
                            <p class="font-bold text-lg text-gray-900">
                                @if(isset($totalRatings) && $totalRatings > 0)
                                    {{ number_format($overallRating, 1) }}/5.0
                                @else
                                    4.9/5.0
                                @endif
                            </p>
                            @if(isset($totalRatings) && $totalRatings > 0)
                                <p class="text-[10px] text-gray-400">{{ number_format($totalRatings) }} ulasan</p>
                            @else
                                <p class="text-[10px] text-gray-400 italic">Belum ada ulasan</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Floating Shipping Card -->
            <div class="absolute -bottom-6 -left-6 glass-card p-5 rounded-2xl hidden md:block border border-white/40 shadow-2xl max-w-[200px]">
                <div class="flex flex-col gap-2">
                    <i class="fas fa-truck text-green-600 text-3xl"></i>
                    <p class="font-bold text-gray-900 text-sm">Cepat & Aman</p>
                    <p class="text-xs text-gray-500">Layanan pengiriman prioritas ke seluruh Indonesia.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Scroll indicator -->
    <div class="absolute bottom-8 left-1/2 -translate-x-1/2 text-green-200 flex flex-col items-center gap-2 opacity-50 animate-pulse">
        <span class="text-[10px] uppercase tracking-widest font-bold">Scroll</span>
        <i class="fas fa-chevron-down"></i>
    </div>
</section>

<!-- Keunggulan Kami -->
<section class="py-24 bg-white">
    <div class="max-w-7xl mx-auto px-5 md:px-16">
        <div class="text-center max-w-2xl mx-auto mb-16">
            <p class="text-green-600 font-semibold text-sm uppercase tracking-[0.2em] mb-4">Keunggulan Kami</p>
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-6">Mengapa Memilih {{ $settings['website_name']->value ?? 'FtwoMart' }}?</h2>
            <p class="text-gray-500 leading-relaxed">Kami menghadirkan kualitas terbaik dengan berbagai pilihan produk untuk memenuhi kebutuhan Anda.</p>
        </div>

        <div class="grid md:grid-cols-3 gap-8">
            <!-- Feature 1 -->
            <div class="bg-white p-8 rounded-2xl ambient-shadow flex flex-col items-center text-center border border-gray-50">
                <div class="w-16 h-16 bg-green-50 text-green-600 rounded-2xl flex items-center justify-center mb-6">
                    <i class="fas fa-certificate text-3xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Produk Terpercaya</h3>
                <p class="text-gray-500 text-sm leading-relaxed">Semua produk kami telah melalui proses seleksi ketat untuk memastikan kualitas terbaik.</p>
            </div>

            <!-- Feature 2 -->
            <div class="bg-white p-8 rounded-2xl ambient-shadow flex flex-col items-center text-center border border-gray-50">
                <div class="w-16 h-16 bg-green-50 text-green-600 rounded-2xl flex items-center justify-center mb-6">
                    <i class="fas fa-tags text-3xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Harga Terjangkau</h3>
                <p class="text-gray-500 text-sm leading-relaxed">Dapatkan produk berkualitas dengan harga yang kompetitif dan penawaran menarik setiap hari.</p>
            </div>

            <!-- Feature 3 -->
            <div class="bg-white p-8 rounded-2xl ambient-shadow flex flex-col items-center text-center border border-gray-50">
                <div class="w-16 h-16 bg-green-50 text-green-600 rounded-2xl flex items-center justify-center mb-6">
                    <i class="fas fa-truck text-3xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Pengiriman Cepat</h3>
                <p class="text-gray-500 text-sm leading-relaxed">Kemasan premium yang aman untuk memastikan produk sampai ke tangan Anda dalam kondisi sempurna.</p>
            </div>
        </div>
    </div>
</section>

<!-- Koleksi Terpopuler -->
<section class="py-24 bg-gray-50">
    <div class="max-w-7xl mx-auto px-5 md:px-16">
        <div class="flex flex-col md:flex-row justify-between items-end mb-12 gap-6">
            <div class="max-w-lg">
                <p class="text-green-600 font-semibold text-sm uppercase tracking-[0.2em] mb-4">Koleksi Terpopuler</p>
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900">Produk Unggulan Kami</h2>
            </div>
            <a href="{{ route('products') }}" class="group flex items-center gap-2 text-green-600 font-semibold text-sm hover:underline decoration-2 underline-offset-4">
                Lihat Semua
                <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
            </a>
        </div>

        @if($featuredProducts->count() > 0)
            <x-product-grid :products="$featuredProducts" />
        @else
            <div class="bg-white rounded-[2rem] p-16 flex flex-col items-center justify-center text-center border-2 border-dashed border-gray-200">
                <div class="w-24 h-24 bg-gray-50 rounded-full flex items-center justify-center mb-8">
                    <i class="fas fa-box-open text-5xl text-gray-300"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Belum ada produk unggulan</h3>
                <p class="text-gray-500 max-w-sm text-sm">Saat ini belum ada produk yang ditampilkan sebagai unggulan. Kami sedang menyiapkan koleksi spesial untuk Anda.</p>
                <div class="mt-8 flex gap-2">
                    <div class="w-2 h-2 rounded-full bg-green-200"></div>
                    <div class="w-2 h-2 rounded-full bg-green-200"></div>
                    <div class="w-2 h-2 rounded-full bg-green-200"></div>
                </div>
            </div>
        @endif
    </div>
</section>

<!-- Kategori Produk (Bento Layout) -->
<section class="py-24 bg-white">
    <div class="max-w-7xl mx-auto px-5 md:px-16">
        <div class="text-center max-w-2xl mx-auto mb-16">
            <p class="text-green-600 font-semibold text-sm uppercase tracking-[0.2em] mb-4">Jelajahi Koleksi</p>
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-6">Kategori Produk</h2>
            <p class="text-gray-500 leading-relaxed">Temukan berbagai produk dan gaya yang sesuai dengan kepribadian dan kebutuhan Anda.</p>
        </div>

        @if($categories->count() > 0)
            <div class="grid md:grid-cols-3 gap-6 h-[500px]">
                <!-- First Category (Large) -->
                <a href="{{ route('products', ['category' => $categories->first()->slug]) }}" class="group md:col-span-2 relative rounded-[2rem] overflow-hidden ambient-shadow h-full">
                    @if($categories->first()->image)
                        <img src="{{ asset('storage/' . $categories->first()->image) }}" alt="{{ $categories->first()->name }}" class="absolute inset-0 w-full h-full object-cover bento-img">
                    @else
                        <div class="absolute inset-0 bg-green-700"></div>
                    @endif
                    <div class="absolute inset-0 bg-gradient-to-t from-green-900/90 via-green-900/30 to-transparent"></div>
                    <div class="absolute bottom-0 left-0 p-10 w-full">
                        <div class="flex items-center gap-4 mb-4">
                            <div class="w-14 h-14 glass-card rounded-2xl flex items-center justify-center text-green-700">
                                <i class="fas fa-box text-3xl"></i>
                            </div>
                        </div>
                        <h3 class="text-white text-2xl font-bold mb-2">{{ $categories->first()->name }}</h3>
                        <div class="flex items-center gap-2 text-green-200 text-sm font-semibold">
                            Lihat Produk <i class="fas fa-arrow-right"></i>
                        </div>
                    </div>
                </a>

                <!-- Secondary Categories -->
                <div class="flex flex-col gap-6">
                    @if($categories->count() > 1)
                        <a href="{{ route('products', ['category' => $categories[1]->slug]) }}" class="relative flex-1 rounded-[2rem] overflow-hidden bg-gray-100 border border-gray-200 ambient-shadow group">
                            @if($categories[1]->image)
                                <img src="{{ asset('storage/' . $categories[1]->image) }}" alt="{{ $categories[1]->name }}" class="absolute inset-0 w-full h-full object-cover bento-img opacity-50">
                            @endif
                            <div class="relative p-8 h-full flex flex-col justify-end">
                                <h4 class="text-xl font-bold text-gray-900 mb-2">{{ $categories[1]->name }}</h4>
                                <p class="text-gray-500 text-sm">Lihat Koleksi</p>
                            </div>
                        </a>
                    @endif

                    <div class="relative flex-1 rounded-[2rem] overflow-hidden bg-green-50 border border-green-100 ambient-shadow group">
                        <div class="relative p-8 h-full flex flex-col justify-center items-center text-center">
                            @if($categories->count() > 2)
                                <a href="{{ route('products') }}" class="flex flex-col items-center">
                                    <i class="fas fa-ellipsis-h text-4xl text-green-600 mb-4"></i>
                                    <h4 class="text-xl font-bold text-gray-900">Lainnya ({{ $categories->count() - 2 }})</h4>
                                    <p class="text-gray-500 text-sm mt-1">Lihat Semua Kategori</p>
                                </a>
                            @else
                                <i class="fas fa-ellipsis-h text-4xl text-green-300 mb-4"></i>
                                <h4 class="text-xl font-bold text-gray-900">Kategori Lainnya</h4>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="bg-gray-50 rounded-[2rem] p-16 flex flex-col items-center justify-center text-center border-2 border-dashed border-gray-200">
                <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-8">
                    <i class="fas fa-th-large text-5xl text-gray-300"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Belum ada kategori</h3>
                <p class="text-gray-500 max-w-sm text-sm">Kategori produk sedang dalam persiapan.</p>
            </div>
        @endif
    </div>
</section>

<!-- Layanan Kami -->
<section class="py-24 bg-gray-50">
    <div class="max-w-7xl mx-auto px-5 md:px-16">
        <div class="text-center max-w-2xl mx-auto mb-16">
            <p class="text-green-600 font-semibold text-sm uppercase tracking-[0.2em] mb-4">Layanan Spesial</p>
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-6">Layanan Kami</h2>
            <p class="text-gray-500 leading-relaxed">Kami berdedikasi untuk memberikan pengalaman terbaik bukan hanya melalui produk, tapi juga layanan unggulan.</p>
        </div>

        @if($services->count() > 0)
            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
                @foreach($services as $service)
                    <a href="{{ $service->url ?? '#' }}" class="group h-full">
                        <div class="bg-white p-8 rounded-2xl ambient-shadow border border-gray-50 hover:border-green-200 transition-colors h-full">
                            <div class="w-14 h-14 bg-green-50 text-green-600 rounded-full flex items-center justify-center mb-6">
                                <i class="fas fa-{{ $service->icon ?? 'concierge-bell' }} text-xl"></i>
                            </div>
                            <h4 class="text-lg font-bold text-gray-900 mb-3 group-hover:text-green-600 transition-colors">{{ $service->name }}</h4>
                            <p class="text-gray-500 text-sm leading-relaxed">Nikmati layanan profesional kami untuk memenuhi kebutuhan Anda dengan mudah dan cepat.</p>
                        </div>
                    </a>
                @endforeach
            </div>
        @else
            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
                @foreach(['Pemesanan Online', 'Pengiriman', 'Pengiriman Luar Pulau', 'Konsultasi Produk'] as $index => $name)
                    <div class="bg-white p-8 rounded-2xl ambient-shadow border border-gray-50">
                        <div class="w-14 h-14 bg-green-50 text-green-600 rounded-full flex items-center justify-center mb-6">
                            <i class="fas fa-{{ ['shopping-cart', 'truck', 'globe', 'headset'][$index] }} text-xl"></i>
                        </div>
                        <h4 class="text-lg font-bold text-gray-900 mb-3">{{ $name }}</h4>
                        <p class="text-gray-500 text-sm leading-relaxed">Nikmati layanan profesional kami untuk memenuhi kebutuhan Anda dengan mudah dan cepat.</p>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</section>

<!-- Call to Action -->
<section class="px-5 md:px-16 mb-24">
    <div class="relative rounded-[3rem] overflow-hidden hero-gradient py-20 px-8 text-center">
        <div class="relative z-10 max-w-3xl mx-auto space-y-8">
            <h2 class="text-3xl md:text-4xl lg:text-5xl font-bold text-white leading-tight">
                Siap Menemukan Produk Terbaik?
                <br/>
                {{ $settings['website_name']->value ?? 'FtwoMart' }}
            </h2>
            <p class="text-green-200/80 text-lg">Bergabunglah dengan ribuan pelanggan lainnya dan temukan produk berkualitas dengan harga terbaik.</p>
            <div class="flex flex-wrap justify-center gap-4 pt-4">
                @auth
                    <a href="{{ route('products') }}" class="bg-white text-green-700 font-bold px-10 py-4 rounded-xl flex items-center gap-2 hover:bg-green-50 transition-all shadow-xl active:scale-95">
                        <i class="fas fa-shopping-bag"></i>
                        Mulai Belanja
                    </a>
                @else
                    <a href="{{ route('register') }}" class="bg-white text-green-700 font-bold px-10 py-4 rounded-xl flex items-center gap-2 hover:bg-green-50 transition-all shadow-xl active:scale-95">
                        <i class="fas fa-user-plus"></i>
                        Daftar Sekarang
                    </a>
                    <a href="{{ route('login') }}" class="border-2 border-green-300 text-green-200 font-bold px-10 py-4 rounded-xl flex items-center gap-2 hover:bg-white/10 transition-all active:scale-95">
                        <i class="fas fa-sign-in-alt"></i>
                        Masuk
                    </a>
                @endauth
            </div>
        </div>
    </div>
</section>

@endsection
