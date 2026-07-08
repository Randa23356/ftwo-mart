@extends('layouts.app')

@section('content')
<!-- Hero Section -->
<div class="relative h-auto lg:min-h-[600px] flex items-center overflow-hidden bg-green-700 text-white">
    <!-- Decorative Mesh/Blur Background -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-[10%] -left-[10%] w-[60%] lg:w-[40%] h-[40%] bg-green-500/30 blur-[100px] lg:blur-[120px] rounded-full animate-pulse"></div>
        <div class="absolute top-[20%] -right-[5%] w-[40%] lg:w-[30%] h-[50%] bg-green-400/20 blur-[80px] lg:blur-[100px] rounded-full"></div>
        <div class="absolute -bottom-[20%] left-[20%] w-[60%] lg:w-[50%] h-[40%] bg-green-600/20 blur-[110px] lg:blur-[130px] rounded-full"></div>
    </div>

    @if(isset($settings['hero_image']) && $settings['hero_image']->value)
        <div class="absolute inset-0">
            <!-- Debug: Show the path -->
            <!-- hero_image value: {{ $settings['hero_image']->value }} -->
            <img src="/{{ $settings['hero_image']->value }}" alt="Hero Image" class="w-full h-full object-cover opacity-15 mix-blend-overlay" onerror="console.log('Image failed to load: /{{ $settings['hero_image']->value }}')">
            <div class="absolute inset-0 bg-green-900/80"></div>
        </div>
    @endif

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-32">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16 items-center">
            <div class="space-y-6 lg:space-y-8"
                 x-data="{ show: false }"
                 x-init="setTimeout(() => show = true, 100)"
                 x-show="show"
                 x-transition:enter="transition ease-out duration-1000"
                 x-transition:enter-start="opacity-0 -translate-x-12"
                 x-transition:enter-end="opacity-100 translate-x-0">

                <div class="inline-flex items-center px-4 py-2 rounded-full bg-white/10 backdrop-blur-md border border-white/20 text-green-100 text-xs sm:text-sm font-medium mb-2">
                    <span class="flex h-2 w-2 rounded-full bg-green-400 mr-2 animate-ping"></span>
                    Koleksi Terbaru 2025
                </div>

                <h1 class="text-4xl sm:text-5xl md:text-7xl font-extrabold tracking-tight leading-[1.1] sm:leading-tight">
                    {{ $settings['website_name']->value ?? 'FtwoMart' }}
                    <span class="block text-green-200">
                        Marketplace Terpercaya
                    </span>
                </h1>

                <p class="text-base sm:text-lg md:text-xl text-green-100/90 leading-relaxed max-w-xl">
                    {{ $settings['website_description']->value ?? 'Temukan berbagai produk berkualitas dengan harga terjangkau. Marketplace terpercaya untuk kebutuhan Anda.' }}
                </p>

                <div class="flex flex-col sm:flex-row gap-4 sm:gap-5">
                    <x-button href="{{ route('products') }}" variant="secondary" size="xl" class="w-full sm:w-auto shadow-xl shadow-green-500/20 group">
                        <i class="fas fa-shopping-bag mr-2 group-hover:rotate-12 transition-transform"></i>
                        Lihat Koleksi
                    </x-button>
                    <x-button href="{{ route('about') }}" variant="outline" size="xl" class="w-full sm:w-auto backdrop-blur-sm border-white/30 text-white hover:bg-white/10 hover:border-white/50">
                        <i class="fas fa-info-circle mr-2"></i>
                        Tentang Kami
                    </x-button>
                </div>

                <div class="flex flex-wrap items-center gap-4 sm:gap-6 pt-4 text-green-200/80">
                    <div class="flex items-center gap-2">
                        <i class="fas fa-check-circle text-green-400 text-sm sm:text-base"></i>
                        <span class="text-xs sm:text-sm font-medium uppercase tracking-wider">Produk Asli</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <i class="fas fa-check-circle text-green-400 text-sm sm:text-base"></i>
                        <span class="text-xs sm:text-sm font-medium uppercase tracking-wider">Kualitas Premium</span>
                    </div>
                </div>
            </div>

            <div class="hidden lg:block relative"
                 x-data="{ show: false }"
                 x-init="setTimeout(() => show = true, 300)"
                 x-show="show"
                 x-transition:enter="transition ease-out duration-1000"
                 x-transition:enter-start="opacity-0 scale-90 translate-y-12"
                 x-transition:enter-end="opacity-100 scale-100 translate-y-0">

                <div class="relative">
                    <!-- Decorative shapes -->
                    <div class="absolute -top-10 -right-10 w-32 h-32 bg-green-500/20 rounded-full blur-2xl animate-pulse"></div>
                    <div class="absolute -bottom-10 -left-10 w-32 h-32 bg-green-600/20 rounded-full blur-2xl animate-pulse" style="animation-delay: 1s"></div>

                    @if(isset($settings['hero_image']) && $settings['hero_image']->value)
                        <div class="relative rounded-2xl overflow-hidden shadow-2xl shadow-green-900/50 border border-white/10 animate-float">
                            <img src="/{{ $settings['hero_image']->value }}" alt="Hero Image" class="w-full h-auto">
                            <div class="absolute inset-0 bg-green-900/40"></div>
                        </div>
                    @else
                        <div class="relative animate-float">
                            <div class="absolute inset-0 bg-green-600 rounded-3xl transform rotate-6 scale-95 opacity-20"></div>
                            <div class="relative bg-white/10 backdrop-blur-xl rounded-3xl p-2 border border-white/20">
                                <div class="bg-green-700 rounded-2xl p-16 flex items-center justify-center shadow-inner">
                                    <i class="fas fa-shopping-bag text-[10rem] text-white/90 drop-shadow-2xl"></i>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Stats Card Floating -->
                    <div class="absolute -bottom-6 -right-6 bg-white/90 backdrop-blur-md p-4 rounded-xl shadow-xl border border-white text-gray-900 animate-float" style="animation-delay: 0.5s">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-green-100 rounded-lg">
                                <i class="fas fa-star text-green-600"></i>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 font-medium">Rating Pelanggan</p>
                                <p class="text-lg font-bold">
                                    @if(isset($totalRatings) && $totalRatings > 0)
                                        {{ number_format($overallRating, 1) }}/5.0
                                    @else
                                        4.9/5.0
                                    @endif
                                </p>
                                @if(isset($totalRatings) && $totalRatings > 0)
                                    <p class="text-[10px] text-gray-400 mt-0.5">{{ number_format($totalRatings) }} ulasan</p>
                                @else
                                    <p class="text-[10px] text-gray-400 mt-0.5">Belum ada ulasan</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scroll Down Indicator -->
    <div class="absolute bottom-6 left-1/2 -translate-x-1/2 flex flex-col items-center gap-2 opacity-30 hover:opacity-100 transition-opacity">
        <span class="text-[10px] uppercase tracking-widest font-bold">Scroll</span>
        <div class="w-[1px] h-12 bg-gradient-to-b from-white to-transparent"></div>
    </div>
</div>

<style>
    @keyframes float {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-20px); }
    }
    .animate-float {
        animation: float 6s ease-in-out infinite;
    }
</style>

<!-- Features Section -->
<div class="py-24 bg-white relative overflow-hidden">
    <!-- Subtle Background Element -->
    <div class="absolute top-0 right-0 w-64 h-64 bg-indigo-50/50 rounded-full blur-3xl -mr-32 -mt-32"></div>
    <div class="absolute bottom-0 left-0 w-64 h-64 bg-purple-50/50 rounded-full blur-3xl -ml-32 -mb-32"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
        <div class="text-center mb-16">
            <h2 class="text-green-600 font-bold tracking-widest uppercase text-sm mb-3">Keunggulan Kami</h2>
            <h3 class="text-4xl font-extrabold text-gray-900 mb-4 tracking-tight">Mengapa Memilih {{ $settings['website_name']->value ?? 'FtwoMart' }}?</h3>
            <p class="text-lg text-gray-500 max-w-2xl mx-auto leading-relaxed">Kami menghadirkan kualitas terbaik dengan berbagai pilihan produk untuk memenuhi kebutuhan Anda.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
            <div class="group bg-white p-10 rounded-3xl border border-green-50 shadow-sm hover:shadow-2xl hover:shadow-green-500/10 transition-all duration-500 hover:-translate-y-2">
                <div class="bg-green-600 w-16 h-16 rounded-2xl flex items-center justify-center mb-8 rotate-3 group-hover:rotate-6 transition-transform shadow-lg">
                    <i class="fas fa-certificate text-2xl text-white"></i>
                </div>
                <h4 class="text-xl font-bold text-gray-900 mb-3 tracking-tight">Produk Terpercaya</h4>
                <p class="text-gray-600 leading-relaxed">Semua produk kami telah melalui proses seleksi ketat untuk memastikan kualitas terbaik.</p>
            </div>

            <div class="group bg-white p-10 rounded-3xl border border-green-50 shadow-sm hover:shadow-2xl hover:shadow-green-500/10 transition-all duration-500 hover:-translate-y-2">
                <div class="bg-green-500 w-16 h-16 rounded-2xl flex items-center justify-center mb-8 -rotate-3 group-hover:-rotate-6 transition-transform shadow-lg">
                    <i class="fas fa-tags text-2xl text-white"></i>
                </div>
                <h4 class="text-xl font-bold text-gray-900 mb-3 tracking-tight">Harga Terjangkau</h4>
                <p class="text-gray-600 leading-relaxed">Dapatkan produk berkualitas dengan harga yang kompetitif dan penawaran menarik setiap hari.</p>
            </div>

            <div class="group bg-white p-10 rounded-3xl border border-green-50 shadow-sm hover:shadow-2xl hover:shadow-green-500/10 transition-all duration-500 hover:-translate-y-2">
                <div class="bg-green-700 w-16 h-16 rounded-2xl flex items-center justify-center mb-8 rotate-3 group-hover:rotate-12 transition-transform shadow-lg">
                    <i class="fas fa-truck text-2xl text-white"></i>
                </div>
                <h4 class="text-xl font-bold text-gray-900 mb-3 tracking-tight">Pengiriman Cepat</h4>
                <p class="text-gray-600 leading-relaxed">Kemasan premium yang aman untuk memastikan produk sampai ke tangan Anda dalam kondisi sempurna.</p>
            </div>
        </div>
    </div>
</div>

<!-- Featured Products Section -->
<div class="py-24 bg-gray-50 relative overflow-hidden">
    <!-- Decorative Radial Gradient -->
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[800px] h-[800px] bg-green-100/30 rounded-full blur-[120px] pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h2 class="text-green-600 font-bold tracking-widest uppercase text-sm mb-2">Koleksi Terpopuler</h2>
                <h3 class="text-2xl font-bold text-gray-900">Produk Unggulan Kami</h3>
            </div>
            <a href="{{ route('products') }}" class="text-sm font-medium text-green-600 hover:text-green-800 flex items-center">
                Lihat Semua
                <i class="fas fa-arrow-right ml-1 text-xs"></i>
            </a>
        </div>

        @if($featuredProducts->count() > 0)
            <x-product-grid :products="$featuredProducts" />
        @else
            <div class="text-center py-16 bg-white rounded-3xl shadow-sm border border-gray-100">
                <div class="w-24 h-24 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-search text-4xl text-gray-300"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Belum ada produk unggulan</h3>
                <p class="text-gray-500 mb-8 max-w-md mx-auto">
                    Saat ini belum ada produk yang ditampilkan sebagai unggulan.
                </p>
            </div>
        @endif
    </div>
</div>

<!-- Categories Section -->
<div class="py-24 bg-white relative">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-green-600 font-bold tracking-widest uppercase text-sm mb-3">Jelajahi Koleksi</h2>
            <h3 class="text-4xl font-extrabold text-gray-900 tracking-tight leading-tight">Kategori Produk</h3>
            <p class="mt-4 text-lg text-gray-600 max-w-2xl mx-auto">Temukan berbagai produk dan gaya yang sesuai dengan kepribadian dan kebutuhan Anda.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            @foreach($categories as $category)
            <a href="{{ route('products', ['category' => $category->slug]) }}" class="group">
                <div class="relative overflow-hidden rounded-3xl p-8 h-full bg-green-700 text-white transition-all duration-500 group-hover:shadow-2xl group-hover:shadow-green-500/30 group-hover:-translate-y-2">
                    <!-- Pattern Overlay (Subtle) -->
                    <div class="absolute inset-0 opacity-10 group-hover:opacity-20 transition-opacity pointer-events-none mix-blend-overlay scale-150">
                        <i class="fas fa-th-large text-[15rem] absolute -top-20 -right-20"></i>
                    </div>

                    <div class="relative z-10 flex flex-col h-full">
                        <div class="w-14 h-14 bg-white/20 backdrop-blur-md rounded-2xl flex items-center justify-center mb-6 border border-white/30 group-hover:scale-110 transition-transform">
                            <i class="fas fa-box text-xl text-white"></i>
                        </div>
                        <h4 class="text-2xl font-bold mb-3 tracking-tight">{{ $category->name }}</h4>
                        <p class="text-green-50/80 text-sm leading-relaxed mb-6">{{ $category->description }}</p>
                        <div class="mt-auto flex items-center font-bold text-sm">
                            Lihat Produk
                            <i class="fas fa-chevron-right ml-2 group-hover:translate-x-1 transition-transform"></i>
                        </div>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</div>

<!-- Services Section -->
<div class="py-24 bg-gray-50 border-t border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-teal-600 font-bold tracking-widest uppercase text-sm mb-3">Layanan Spesial</h2>
            <h3 class="text-4xl font-extrabold text-gray-900 tracking-tight leading-tight">Layanan Kami</h3>
            <p class="mt-4 text-lg text-gray-600 max-w-2xl mx-auto">Kami berdedikasi untuk memberikan pengalaman terbaik bukan hanya melalui produk, tapi juga layanan unggulan.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            @foreach($services as $service)
            <a href="{{ $service->url ?? '#' }}" class="group h-full">
                <div class="bg-white rounded-3xl p-8 h-full border border-teal-50 shadow-sm hover:shadow-2xl hover:shadow-teal-500/10 transition-all duration-500 hover:-translate-y-2 flex flex-col items-center text-center">
                    <div class="w-20 h-20 bg-gradient-to-br from-teal-400 to-emerald-500 rounded-3xl flex items-center justify-center mb-6 shadow-lg shadow-teal-100 rotate-3 group-hover:rotate-6 transition-transform">
                        <i class="fas fa-concierge-bell text-3xl text-white"></i>
                    </div>
                    <h4 class="text-xl font-bold text-gray-900 mb-2 tracking-tight group-hover:text-teal-600 transition-colors">{{ $service->name }}</h4>
                    <p class="text-gray-500 text-sm leading-relaxed">Nikmati layanan profesional kami untuk memenuhi kebutuhan Anda dengan mudah dan cepat.</p>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</div>

<!-- CTA Section -->
<div class="py-24 relative overflow-hidden bg-green-700">
    <!-- Decorative Circle Blur -->
    <div class="absolute -top-[50%] -left-[20%] w-[100%] h-[200%] bg-green-500/20 blur-[150px] rounded-full"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
        <div class="max-w-3xl mx-auto">
            <h2 class="text-4xl md:text-5xl font-extrabold text-white mb-6 tracking-tight leading-tight">Siap Menemukan Produk Terbaik? <span class="text-green-200 text-glow">FtwoMart</span></h2>
            <p class="text-xl text-green-100/80 mb-10 leading-relaxed">Bergabunglah dengan ribuan pelanggan lainnya dan temukan produk berkualitas dengan harga terbaik.</p>

            <div class="flex flex-col sm:flex-row gap-5 justify-center">
                @auth
                    <x-button href="{{ route('products') }}" variant="secondary" size="xl" class="shadow-2xl shadow-green-500/30 min-w-[200px]">
                        <i class="fas fa-shopping-bag mr-2"></i>
                        Mulai Belanja
                    </x-button>
                @else
                    <x-button href="{{ route('register') }}" variant="secondary" size="xl" class="shadow-2xl shadow-green-500/30 min-w-[200px]">
                        <i class="fas fa-user-plus mr-2"></i>
                        Daftar Sekarang
                    </x-button>
                    <x-button href="{{ route('login') }}" variant="outline" size="xl" class="backdrop-blur-sm border-white/30 text-white hover:bg-white/10 hover:border-white/50 min-w-[200px]">
                        <i class="fas fa-sign-in-alt mr-2"></i>
                        Masuk
                    </x-button>
                @endauth
            </div>
        </div>
    </div>
</div>

<style>
    .text-glow {
        text-shadow: 0 0 20px rgba(187, 247, 208, 0.4);
    }
</style>


@endsection
