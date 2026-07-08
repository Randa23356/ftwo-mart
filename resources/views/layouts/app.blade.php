<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $settings['website_name']->value ?? config('app.name', 'FtwoMart') }}</title>

    <!-- Favicon / Shortcut Icon -->
    @if(isset($settings['logo']) && $settings['logo']->value)
        <link rel="shortcut icon" href="{{ asset('storage/' . $settings['logo']->value) }}" type="image/x-icon">
    @else
        <link rel="shortcut icon" href="{{ asset('images/logo.png') }}" type="image/x-icon">
    @endif

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Tailwind CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- SweetAlert2 untuk notifikasi -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Custom CSS -->
    <style>
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .transition-all {
            transition: all 0.3s ease;
        }

        /* Hide elements until Alpine.js is ready */
        [x-cloak] {
            display: none !important;
        }

        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        ::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }
    </style>
</head>
<body class="font-sans antialiased bg-gray-50" x-data="{ mobileOpen: false, scrolled: false }" @scroll.window="scrolled = (window.pageYOffset > 20)">

    <!-- Modern Mobile Menu Overlay -->
    <div class="md:hidden fixed inset-0 z-[9999]"
         x-show="mobileOpen"
         @keydown.escape.window="mobileOpen = false">
        <!-- Backdrop -->
        <div class="absolute inset-0 bg-gray-900/60 backdrop-blur-md transition-opacity duration-300"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @click="mobileOpen = false"></div>

        <!-- Menu Content -->
        <div class="absolute right-0 top-0 h-screen w-[280px] bg-white shadow-2xl transition-transform duration-300 ease-out"
             x-transition:enter="transform transition ease-out duration-300"
             x-transition:enter-start="translate-x-full"
             x-transition:enter-end="translate-x-0"
             x-transition:leave="transform transition ease-in duration-200"
             x-transition:leave-start="translate-x-0"
             x-transition:leave-end="translate-x-full">

            <div class="flex flex-col h-full">
                <div class="p-6 flex items-center justify-between border-b border-gray-100">
                    <span class="text-xl font-black tracking-tighter text-gray-900">
                        Menu <span class="text-green-600">Navigasi</span>
                    </span>
                    <button @click="mobileOpen = false" class="h-10 w-10 flex items-center justify-center rounded-xl bg-gray-50 text-gray-400 hover:text-red-500 transition-colors">
                        <i class="fas fa-times text-lg"></i>
                    </button>
                </div>

                <div class="flex-1 overflow-y-auto py-8 px-6 space-y-3">
                    <a href="{{ route('home') }}"
                       class="flex items-center gap-4 px-6 py-3.5 rounded-2xl text-base font-bold transition-all border border-transparent {{ request()->routeIs('home') ? 'bg-green-50 text-green-700 border-green-100 shadow-sm' : 'text-gray-600 hover:bg-gray-50' }}">
                        <i class="fas fa-home w-5 text-green-400"></i> Home
                    </a>

                    <a href="{{ route('products') }}"
                       class="flex items-center gap-4 px-6 py-3.5 rounded-2xl text-base font-bold transition-all border border-transparent {{ request()->routeIs('products*') ? 'bg-green-50 text-green-700 border-green-100 shadow-sm' : 'text-gray-600 hover:bg-gray-50' }}">
                        <i class="fas fa-box w-5 text-green-400"></i> Koleksi
                    </a>

                    <a href="{{ route('about') }}"
                       class="flex items-center gap-4 px-6 py-3.5 rounded-2xl text-base font-bold transition-all border border-transparent {{ request()->routeIs('about') ? 'bg-green-50 text-green-700 border-green-100 shadow-sm' : 'text-gray-600 hover:bg-gray-50' }}">
                        <i class="fas fa-info-circle w-5 text-green-400"></i> Tentang
                    </a>

                    <a href="{{ route('contact') }}"
                       class="flex items-center gap-4 px-6 py-3.5 rounded-2xl text-base font-bold transition-all border border-transparent {{ request()->routeIs('contact') ? 'bg-green-50 text-green-700 border-green-100 shadow-sm' : 'text-gray-600 hover:bg-gray-50' }}">
                        <i class="fas fa-envelope w-5 text-green-400"></i> Kontak Kami
                    </a>

                    <div class="pt-6 border-t border-gray-100 mt-6 pb-2">
                        <p class="px-4 text-[11px] font-black uppercase tracking-[0.2em] text-gray-400 mb-5">Akun Saya</p>
                        <div class="space-y-3">
                            @auth
                                @if(auth()->user()->isAdmin())
                                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-4 px-6 py-3.5 rounded-2xl text-base font-bold text-red-600 hover:bg-red-50 transition-all">
                                        <i class="fas fa-tachometer-alt w-5 text-red-400"></i> Dashboard Admin
                                    </a>
                                @endif

                                @if(auth()->user()->isOperator())
                                    <a href="{{ route('operator.dashboard') }}" class="flex items-center gap-4 px-6 py-3.5 rounded-2xl text-base font-bold text-green-600 hover:bg-green-50 transition-all">
                                        <i class="fas fa-tools w-5 text-green-400"></i> Dashboard Operator
                                    </a>
                                @endif

                                @php $cartCount = auth()->user()->cartItems->sum('quantity'); @endphp
                                <a href="{{ route('cart.index') }}" class="flex items-center justify-between px-6 py-3.5 rounded-2xl text-base font-bold text-gray-600 hover:bg-gray-50 transition-all">
                                    <div class="flex items-center gap-4">
                                        <i class="fas fa-shopping-cart w-5 text-green-400"></i> Keranjang
                                    </div>
                                    @if($cartCount > 0)
                                        <span class="bg-red-500 text-white text-[10px] rounded-lg px-2 py-0.5 font-black">{{ $cartCount }}</span>
                                    @endif
                                </a>

                                <a href="{{ route('orders.index') }}" class="flex items-center gap-4 px-6 py-3.5 rounded-2xl text-base font-bold text-gray-600 hover:bg-gray-50 transition-all">
                                    <i class="fas fa-shopping-bag w-5 text-green-400"></i> Pesanan
                                </a>

                                <a href="{{ route('chat.index') }}" class="flex items-center gap-4 px-8 py-3.5 rounded-2xl text-base font-bold text-gray-600 hover:bg-gray-50 transition-all">
                                    <i class="fas fa-comments w-5 text-green-400"></i> Pesan
                                </a>

                                <a href="{{ route('profile.show', Auth::user()->slug ?? 'user-' . Auth::user()->id) }}" class="flex items-center gap-4 px-8 py-3.5 rounded-2xl text-base font-bold text-gray-600 hover:bg-gray-50 transition-all">
                                    <i class="fas fa-user w-5 text-green-400"></i> Profil
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="flex items-center gap-4 px-8 py-3.5 rounded-2xl text-base font-bold text-gray-600 hover:bg-gray-50 transition-all">
                                    <i class="fas fa-sign-in-alt w-5 text-green-400"></i> Login
                                </a>

                                <a href="{{ route('register') }}" class="flex items-center gap-4 px-8 py-3.5 rounded-2xl text-base font-bold text-gray-600 hover:bg-gray-50 transition-all">
                                    <i class="fas fa-user-plus w-5 text-green-400"></i> Daftar Sekarang
                                </a>
                            @endauth
                        </div>
                    </div>
                </div>

                @auth
                    <div class="p-6 border-t border-gray-100 bg-gray-50/50">
                        <div class="flex items-center gap-4 mb-6">
                            <img src="{{ Auth::user()->profile_photo_url }}" class="w-12 h-12 rounded-2xl shadow-md border-2 border-white object-cover" alt="{{ Auth::user()->name }}">
                            <div>
                                <p class="text-sm font-black text-gray-900 leading-none mb-1">{{ Auth::user()->name }}</p>
                                <p class="text-xs font-bold text-gray-500">{{ Auth::user()->email }}</p>
                            </div>
                        </div>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full flex items-center justify-center gap-3 py-3.5 rounded-2xl bg-white border border-red-100 text-red-600 text-sm font-black uppercase tracking-widest hover:bg-red-50 transition-all shadow-sm">
                                <i class="fas fa-power-off"></i> Logout
                            </button>
                        </form>
                    </div>
                @endauth
            </div>
        </div>
    </div>

    <div class="min-h-screen flex flex-col">
        <!-- Navigation -->
        <nav class="sticky top-0 z-50 bg-white/80 backdrop-blur-xl border-b border-gray-100 transition-all duration-300 py-2 md:py-0"
             :class="scrolled ? 'shadow-lg shadow-gray-200/50' : ''">
            <div class="max-w-7xl mx-auto px-6 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16 lg:h-20">
                    <div class="flex items-center gap-8 lg:gap-12">
                        <!-- Logo -->
                        <div class="flex-shrink-0 flex items-center">
                            <a href="{{ route('home') }}" class="flex items-center group transition-transform hover:scale-105">
                                @if(isset($settings['logo']) && $settings['logo']->value)
                                    <img class="h-8 lg:h-12 w-auto mr-2 md:mr-3 drop-shadow-sm" src="{{ asset('storage/' . $settings['logo']->value) }}" alt="Logo">
                                @else
                                    <img class="h-8 lg:h-12 w-auto mr-2 md:mr-3 drop-shadow-sm" src="{{ asset('images/logo.png') }}" alt="FtwoMart Logo">
                                @endif
                                <span class="text-lg lg:text-2xl font-black tracking-tighter text-gray-900 group-hover:text-green-600 transition-colors">
                                    {{ $settings['website_name']->value ?? 'FtwoMart' }}
                                </span>
                            </a>
                        </div>

                        <!-- Navigation Links -->
                        <div class="hidden md:flex items-center space-x-1 lg:space-x-2">
                            <a href="{{ route('home') }}"
                               class="px-4 py-2 rounded-xl text-sm font-bold tracking-tight transition-all duration-200 {{ request()->routeIs('home') ? 'bg-green-50 text-green-700' : 'text-gray-500 hover:text-green-600 hover:bg-gray-50' }}">
                                Home
                            </a>
                            <a href="{{ route('products') }}"
                               class="px-4 py-2 rounded-xl text-sm font-bold tracking-tight transition-all duration-200 {{ request()->routeIs('products*') ? 'bg-green-50 text-green-700' : 'text-gray-500 hover:text-green-600 hover:bg-gray-50' }}">
                                Koleksi
                            </a>
                            <a href="{{ route('about') }}"
                               class="px-4 py-2 rounded-xl text-sm font-bold tracking-tight transition-all duration-200 {{ request()->routeIs('about') ? 'bg-green-50 text-green-700' : 'text-gray-500 hover:text-green-600 hover:bg-gray-50' }}">
                                Tentang
                            </a>
                            <a href="{{ route('contact') }}"
                               class="px-4 py-2 rounded-xl text-sm font-bold tracking-tight transition-all duration-200 {{ request()->routeIs('contact') ? 'bg-green-50 text-green-700' : 'text-gray-500 hover:text-green-600 hover:bg-gray-50' }}">
                                Kontak
                            </a>
                        </div>
                    </div>

                    <div class="flex items-center gap-2 lg:gap-4">
                        <!-- Desktop Actions -->
                        @auth
                            <a href="{{ route('cart.index') }}"
                               class="hidden md:flex relative h-11 w-11 items-center justify-center rounded-xl bg-gray-50 text-gray-700 hover:bg-green-50 hover:text-green-600 transition-all duration-300 hover:scale-105 cart-badge group">
                                <i class="fas fa-shopping-cart text-lg"></i>
                                @php $cartCount = auth()->user()->cartItems->sum('quantity'); @endphp
                                <span class="cart-counter absolute -top-1.5 -right-1.5 bg-red-500 text-white text-[10px] rounded-lg min-w-[20px] h-5 flex items-center justify-center px-1 font-bold border-2 border-white shadow-lg transition-transform group-hover:scale-110 {{ $cartCount > 0 ? '' : 'hidden' }}">
                                    {{ $cartCount }}
                                </span>
                            </a>
                        @endauth

                        <!-- User Menu -->
                        @auth
                            <div class="hidden md:block relative" x-data="{ open: false }">
                                <button @click="open = !open"
                                        class="flex items-center gap-3 p-1.5 pr-4 rounded-2xl bg-gray-50 hover:bg-green-50 border border-gray-100 transition-all duration-300 group">
                                    <img src="{{ Auth::user()->profile_photo_url }}"
                                         alt="{{ Auth::user()->name }}"
                                         class="w-8 h-8 rounded-xl border border-white shadow-sm object-cover group-hover:scale-105 transition-transform">
                                    <div class="flex items-center gap-2">
                                        <span class="text-sm font-bold text-gray-700 group-hover:text-green-700">{{ Auth::user()->name }}</span>
                                        <i class="fas fa-chevron-down text-[10px] text-gray-400 group-hover:text-green-400 transition-transform" :class="open ? 'rotate-180' : ''"></i>
                                    </div>
                                </button>

                                <div x-show="open"
                                     x-cloak
                                     @click.away="open = false"
                                     x-transition:enter="transition ease-out duration-200"
                                     x-transition:enter-start="opacity-0 scale-95 -translate-y-2 transform origin-top-right"
                                     x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                                     x-transition:leave="transition ease-in duration-150"
                                     x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                                     x-transition:leave-end="opacity-0 scale-95 -translate-y-2 transform origin-top-right"
                                     class="absolute right-0 mt-3 w-56 bg-white rounded-2xl shadow-2xl shadow-green-900/10 py-2 z-50 border border-green-50">

                                    <div class="px-4 py-3 border-b border-gray-100 mb-1">
                                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Signed in as</p>
                                        <p class="text-sm font-bold text-gray-900 truncate">{{ Auth::user()->email }}</p>
                                    </div>

                                    @if(auth()->user()->isAdmin())
                                        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-green-50 hover:text-green-700 transition-colors">
                                            <div class="w-8 h-8 rounded-lg bg-green-100 flex items-center justify-center text-green-600">
                                                <i class="fas fa-tachometer-alt"></i>
                                            </div>
                                            <span class="font-semibold">Dashboard Admin</span>
                                        </a>
                                    @endif

                                    @if(auth()->user()->isOperator())
                                        <a href="{{ route('operator.dashboard') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-green-50 hover:text-green-700 transition-colors">
                                            <div class="w-8 h-8 rounded-lg bg-green-50 flex items-center justify-center text-green-700">
                                                <i class="fas fa-tools"></i>
                                            </div>
                                            <span class="font-semibold">Dashboard Ops</span>
                                        </a>
                                    @endif

                                    <a href="{{ route('orders.index') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-green-50 hover:text-green-700 transition-colors">
                                        <div class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center text-gray-600 group-hover:bg-green-100 group-hover:text-green-600">
                                            <i class="fas fa-shopping-bag"></i>
                                        </div>
                                        <span class="font-semibold">Pemesanan Saya</span>
                                    </a>

                                    <a href="{{ route('chat.index') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-green-50 hover:text-green-700 transition-colors">
                                        <div class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center text-gray-600">
                                            <i class="fas fa-comments"></i>
                                        </div>
                                        <span class="font-semibold">Pesan Chat</span>
                                    </a>

                                    <a href="{{ route('profile.show', Auth::user()->slug ?? 'user-' . Auth::user()->id) }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-green-50 hover:text-green-700 transition-colors">
                                        <div class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center text-gray-600">
                                            <i class="fas fa-user-circle"></i>
                                        </div>
                                        <span class="font-semibold">Lihat Profil</span>
                                    </a>

                                    <div class="border-t border-gray-100 mt-1 pt-1">
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="flex w-full items-center gap-3 px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition-colors font-bold uppercase tracking-wider">
                                                <div class="w-8 h-8 rounded-lg bg-red-100 flex items-center justify-center">
                                                    <i class="fas fa-power-off"></i>
                                                </div>
                                                Logout
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="hidden md:flex items-center gap-3">
                                <a href="{{ route('login') }}" class="px-5 py-2.5 rounded-xl text-sm font-bold text-gray-600 hover:text-green-600 transition-colors tracking-tight">
                                    Login
                                </a>
                                <a href="{{ route('register') }}" class="px-6 py-2.5 rounded-xl text-sm font-bold text-white bg-green-700 hover:bg-green-800 shadow-lg shadow-green-600/20 transition-all hover:-translate-y-0.5 tracking-tight uppercase">
                                    Gabung
                                </a>
                            </div>
                        @endauth

                        <!-- Mobile menu button -->
                        <div class="flex items-center h-full">
                            <button type="button"
                                    class="md:hidden flex items-center justify-center rounded-xl text-gray-500 hover:text-green-600 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-green-500 transition-all duration-200 h-10 w-10"
                                    @click="mobileOpen = !mobileOpen"
                                    :aria-expanded="mobileOpen.toString()"
                                    aria-label="Toggle navigation">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M4 6h16M4 12h16M4 18h16"
                                          :class="mobileOpen ? 'opacity-0' : 'opacity-100'"
                                          style="transition: opacity 0.3s ease" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M6 18L18 6M6 6l12 12"
                                          :class="mobileOpen ? 'opacity-100' : 'opacity-0'"
                                          style="transition: opacity 0.3s ease; position: absolute; left: 0; right: 0;" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Email Verification Notice -->
        @auth
            @if(!Auth::user()->hasVerifiedEmail())
                <div id="email-verification-notice" class="bg-yellow-50 border-b border-yellow-200" style="display: none;">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        <div class="py-3">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-exclamation-triangle text-yellow-400"></i>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm text-yellow-800">
                                            <span class="font-medium">Email Anda belum diverifikasi.</span>
                                            Beberapa fitur seperti pemesanan dan chat memerlukan verifikasi email.
                                        </p>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-3">
                                    <a href="{{ route('verification.notice') }}"
                                       class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-yellow-800 bg-yellow-100 hover:bg-yellow-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 transition-colors">
                                        <i class="fas fa-envelope-open mr-1"></i>
                                        Verifikasi Sekarang
                                    </a>
                                    <button onclick="closeEmailNotification()"
                                            class="text-yellow-600 hover:text-yellow-800 focus:outline-none">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <script>
                    // Show notification if not dismissed
                    document.addEventListener('DOMContentLoaded', function() {
                        const notice = document.getElementById('email-verification-notice');
                        const dismissed = sessionStorage.getItem('email-verification-dismissed');

                        if (!dismissed && notice) {
                            notice.style.display = 'block';
                        }
                    });

                    function closeEmailNotification() {
                        const notice = document.getElementById('email-verification-notice');
                        if (notice) {
                            notice.style.display = 'none';
                            sessionStorage.setItem('email-verification-dismissed', 'true');
                        }
                    }
                </script>
            @endif
        @endauth

        <!-- Page Content -->
        <main class="{{ request()->routeIs('home') ? 'pb-8' : 'py-8' }} flex-grow">
            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="bg-gray-800 text-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                    <div>
                        <h3 class="text-lg font-semibold mb-4 text-green-400">
                            <i class="fas fa-store mr-2"></i>
                            {{ $settings['website_name']->value ?? 'FtwoMart' }}
                        </h3>
                        <p class="text-gray-300">
                            {{ $settings['website_description']->value ?? 'Marketplace terpercaya untuk berbagai produk berkualitas dengan pelayanan terbaik.' }}
                        </p>
                    </div>

                    <div>
                        <h4 class="text-lg font-semibold mb-4 text-green-400">Kategori</h4>
                        <ul class="space-y-2 text-gray-300">
                            @foreach($footer_categories as $category)
                                <li><a href="{{ route('products', ['category' => $category->slug]) }}" class="hover:text-green-400 transition-colors">{{ $category->name }}</a></li>
                            @endforeach
                        </ul>
                    </div>

                    <div>
                        <h4 class="text-lg font-semibold mb-4 text-green-400">Layanan</h4>
                        <ul class="space-y-2 text-gray-300">
                            @foreach($footer_services as $service)
                                <li><a href="{{ $service->url ?? '#' }}" class="hover:text-green-400 transition-colors">{{ $service->name }}</a></li>
                            @endforeach
                        </ul>
                    </div>

                    <div>
                        <h4 class="text-lg font-semibold mb-4 text-green-400">Kontak</h4>
                        <ul class="space-y-2 text-gray-300">
                            @if(isset($settings['phone']) && $settings['phone']->value)
                                <li><i class="fas fa-phone mr-2"></i> {{ $settings['phone']->value }}</li>
                            @endif
                            @if(isset($settings['email']) && $settings['email']->value)
                                <li><i class="fas fa-envelope mr-2"></i> {{ $settings['email']->value }}</li>
                            @endif
                            @if(isset($settings['address']) && $settings['address']->value)
                                <li><i class="fas fa-map-marker-alt mr-2"></i> {{ $settings['address']->value }}</li>
                            @endif
                            @if(isset($settings['opening_hours']) && $settings['opening_hours']->value)
                                <li><i class="fas fa-clock mr-2"></i> {{ $settings['opening_hours']->value }}</li>
                            @endif
                        </ul>
                    </div>
                </div>

                <div class="border-t border-gray-700 mt-8 pt-8 text-center text-gray-300">
                    <p>&copy; {{ date('Y') }} {{ $settings['website_name']->value ?? 'FtwoMart' }}. All rights reserved.</p>
                </div>
            </div>
        </footer>
    </div>

    <!-- Scripts Section -->
    @stack('scripts')

    <!-- Show session notifications -->
<script>
    // Global loading overlay utility
    const loadingOverlay = {
        show: function(message = 'Loading...') {
            // Remove existing overlay if any
            this.hide();

            const overlay = document.createElement('div');
            overlay.id = 'loading-overlay';
            overlay.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-[9999]';
            overlay.innerHTML = `
                <div class="bg-white rounded-2xl p-6 shadow-2xl flex items-center space-x-4">
                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                    <span class="text-gray-700 font-medium">${message}</span>
                </div>
            `;
            document.body.appendChild(overlay);
        },
        hide: function() {
            const overlay = document.getElementById('loading-overlay');
            if (overlay) {
                overlay.remove();
            }
        }
    };

    document.addEventListener('DOMContentLoaded', function() {
        // Prevent duplicate notifications by checking if already shown
        if (window.sessionNotificationsShown) return;
        window.sessionNotificationsShown = true;

        // Add small delay to ensure notification system is loaded
        setTimeout(() => {
            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: '{{ session('success') }}',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 4000,
                    timerProgressBar: true
                });
            @endif

            @if(session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: '{{ session('error') }}',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 6000,
                    timerProgressBar: true
                });
            @endif

            @if(session('warning'))
                Swal.fire({
                    icon: 'warning',
                    title: 'Peringatan!',
                    text: '{{ session('warning') }}',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 5000,
                    timerProgressBar: true
                });
            @endif

            @if(session('info'))
                Swal.fire({
                    icon: 'info',
                    title: 'Informasi',
                    text: '{{ session('info') }}',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 4000,
                    timerProgressBar: true
                });
            @endif
        }, 100);
    });

    // Handle profile photo errors
    document.addEventListener('DOMContentLoaded', function() {
        // Handle all images that might be profile photos
        const profileImages = document.querySelectorAll('img[class*="avatar"], img[class*="profile"], img[src*="profile_photo_url"], img[alt*="name"]');

        profileImages.forEach(function(img) {
            img.onerror = function() {
                // Replace with random default avatar on error
                const avatars = [
                    '/images/default-avatar.svg',
                    '/images/default-avatar-yellow.svg',
                    '/images/default-avatar-green.svg'
                ];
                const randomAvatar = avatars[Math.floor(Math.random() * avatars.length)];
                this.src = randomAvatar;
                this.onerror = null; // Prevent infinite loop
            };
        });

        // Also handle dynamically loaded images
        const observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                mutation.addedNodes.forEach(function(node) {
                    if (node.tagName === 'IMG') {
                        if (node.className.includes('avatar') || node.className.includes('profile') || node.src.includes('profile_photo_url')) {
                            node.onerror = function() {
                                const avatars = [
                                    '/images/default-avatar.svg',
                                    '/images/default-avatar-yellow.svg',
                                    '/images/default-avatar-green.svg'
                                ];
                                const randomAvatar = avatars[Math.floor(Math.random() * avatars.length)];
                                this.src = randomAvatar;
                                this.onerror = null;
                            };
                        }
                    }
                });
            });
        });

        observer.observe(document.body, {
            childList: true,
            subtree: true
        });
    });
    </script>
</body>
</html>
