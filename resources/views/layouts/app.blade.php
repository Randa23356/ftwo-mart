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
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet" />

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

    @stack('styles')
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
        <div class="absolute right-0 top-0 h-screen w-[min(300px,85vw)] bg-white shadow-2xl transition-transform duration-300 ease-out flex flex-col"
             x-transition:enter="transform transition ease-out duration-300"
             x-transition:enter-start="translate-x-full"
             x-transition:enter-end="translate-x-0"
             x-transition:leave="transform transition ease-in duration-200"
             x-transition:leave-start="translate-x-0"
             x-transition:leave-end="translate-x-full">

            <!-- Header -->
            <div class="px-4 py-3.5 flex items-center justify-between border-b border-gray-100 flex-shrink-0">
                <span class="text-base font-black tracking-tight text-gray-900">
                    Menu <span class="text-green-600">Navigasi</span>
                </span>
                <button @click="mobileOpen = false" class="h-8 w-8 flex items-center justify-center rounded-lg bg-gray-50 text-gray-400 hover:text-red-500 transition-colors">
                    <i class="fas fa-times text-sm"></i>
                </button>
            </div>

            <!-- Scrollable Menu -->
            <div class="flex-1 overflow-y-auto min-h-0 relative">
                <div class="px-3 py-3 space-y-0.5">
                    <!-- Mobile Search -->
                    <div class="px-1 pb-3" x-data="smartSearch()">
                        <div class="relative">
                            <form action="{{ route('products') }}" method="GET" @submit="saveHistory()">
                                <input type="hidden" name="min_price" :value="filterMin">
                                <input type="hidden" name="max_price" :value="filterMax">
                                <div class="flex items-center bg-gray-100 border border-gray-200 rounded-xl focus-within:border-green-400 focus-within:bg-white focus-within:ring-4 focus-within:ring-green-50 transition-all duration-300">
                                    <i class="fas fa-search text-gray-400 text-xs ml-3.5"></i>
                                    <input type="text"
                                           name="search"
                                           x-model="query"
                                           @input.debounce.300ms="fetchSuggestions()"
                                           @focus="open = true"
                                           @keydown.escape="close()"
                                           value="{{ request('search') }}"
                                           placeholder="Cari produk..."
                                           class="flex-1 bg-transparent border-none outline-none text-sm py-2.5 px-3 text-gray-700 placeholder-gray-400">
                                    <button type="button" @click.stop="showFilter = !showFilter; open = false"
                                            class="flex items-center justify-center w-8 h-8 mr-1 rounded-lg transition-all duration-200"
                                            :class="showFilter ? 'bg-green-100 text-green-600' : 'text-gray-400 hover:text-green-600'">
                                        <i class="fas fa-sliders-h text-[10px]"></i>
                                    </button>
                                </div>
                            </form>

                            <!-- Mobile Filter -->
                            <div x-show="showFilter" x-cloak @click.away="showFilter = false"
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 -translate-y-2"
                                 x-transition:enter-end="opacity-100 translate-y-0"
                                 class="mt-3 p-4 bg-white rounded-xl shadow-lg border border-gray-100">
                                <div class="flex items-center gap-2 mb-3">
                                    <div class="w-7 h-7 bg-green-100 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-sliders-h text-green-600 text-[10px]"></i>
                                    </div>
                                    <p class="text-xs font-bold text-gray-700">Filter Harga</p>
                                </div>
                                <div class="flex gap-2 mb-3">
                                    <div class="flex-1">
                                        <label class="text-[11px] font-semibold text-gray-500 mb-1 block">Minimum</label>
                                        <div class="relative">
                                            <span class="absolute left-2.5 top-1/2 -translate-y-1/2 text-gray-400 text-[11px]">Rp</span>
                                            <input type="number" x-model="filterMin" placeholder="0"
                                                   class="w-full pl-8 pr-2 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none">
                                        </div>
                                    </div>
                                    <div class="flex-1">
                                        <label class="text-[11px] font-semibold text-gray-500 mb-1 block">Maksimum</label>
                                        <div class="relative">
                                            <span class="absolute left-2.5 top-1/2 -translate-y-1/2 text-gray-400 text-[11px]">Rp</span>
                                            <input type="number" x-model="filterMax" placeholder="..."
                                                   class="w-full pl-8 pr-2 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none">
                                        </div>
                                    </div>
                                </div>
                                <button type="button" @click="applyFilter()" class="w-full py-2.5 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white text-sm font-semibold rounded-xl transition-all shadow-lg shadow-green-600/20">
                                    <i class="fas fa-search mr-1.5"></i> Terapkan
                                </button>
                            </div>

                            <!-- Mobile Autocomplete -->
                            <div x-show="open && (query.length >= 2 || history.length > 0)"
                                 x-cloak
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 -translate-y-2"
                                 x-transition:enter-end="opacity-100 translate-y-0"
                                 class="absolute left-0 right-0 mt-2 bg-white rounded-2xl shadow-2xl shadow-gray-900/10 z-50 border border-gray-100 overflow-hidden max-h-80 overflow-y-auto">

                                <!-- History -->
                                <template x-if="query.length < 2 && history.length > 0">
                                    <div class="p-2">
                                        <div class="flex items-center justify-between px-3 py-2">
                                            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Terakhir</p>
                                            <button @click="clearHistory()" class="text-xs text-red-400 hover:text-red-600 font-medium">Hapus</button>
                                        </div>
                                        <template x-for="(item, i) in history" :key="i">
                                            <a :href="'{{ route('products') }}?search=' + encodeURIComponent(item)"
                                               class="flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-green-50 transition-all group">
                                                <div class="w-8 h-8 rounded-lg bg-gray-100 group-hover:bg-green-100 flex items-center justify-center flex-shrink-0">
                                                    <i class="fas fa-clock text-gray-400 group-hover:text-green-500 text-xs"></i>
                                                </div>
                                                <span class="text-sm text-gray-600 group-hover:text-gray-900" x-text="item"></span>
                                            </a>
                                        </template>
                                    </div>
                                </template>

                                <!-- Suggestions -->
                                <template x-if="query.length >= 2">
                                    <div class="p-2">
                                        <template x-if="categories.length > 0">
                                            <div>
                                                <div class="px-3 py-2"><p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Kategori</p></div>
                                                <template x-for="(cat, i) in categories" :key="'c'+i">
                                                    <a :href="cat.url" class="flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-green-50 transition-all group">
                                                        <div class="w-9 h-9 rounded-xl bg-green-100 group-hover:bg-green-200 flex items-center justify-center flex-shrink-0">
                                                            <i class="fas fa-tag text-green-500 text-xs"></i>
                                                        </div>
                                                        <div class="min-w-0">
                                                            <span class="text-sm font-semibold text-gray-700 group-hover:text-green-700" x-text="cat.name"></span>
                                                            <p class="text-[11px] text-gray-400">Kategori</p>
                                                        </div>
                                                    </a>
                                                </template>
                                            </div>
                                        </template>
                                        <template x-if="products.length > 0">
                                            <div>
                                                <div class="px-3 py-2" :class="categories.length > 0 ? 'border-t border-gray-100 mt-1 pt-3' : ''">
                                                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Produk</p>
                                                </div>
                                                <template x-for="(item, i) in products" :key="'p'+i">
                                                    <a :href="item.url" class="flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-green-50 transition-all group">
                                                        <img :src="item.image" :alt="item.name"
                                                             class="w-11 h-11 rounded-xl object-cover bg-gray-100 flex-shrink-0 border border-gray-100">
                                                        <div class="min-w-0 flex-1">
                                                            <p class="text-sm font-semibold text-gray-800 group-hover:text-green-700 truncate" x-text="item.name"></p>
                                                            <p class="text-[11px] text-gray-400" x-text="item.category"></p>
                                                        </div>
                                                        <span class="text-xs font-bold text-green-600 bg-green-50 px-2 py-1 rounded-lg whitespace-nowrap" x-text="item.price"></span>
                                                    </a>
                                                </template>
                                            </div>
                                        </template>
                                        <template x-if="products.length === 0 && categories.length === 0">
                                            <div class="px-4 py-8 text-center">
                                                <div class="w-14 h-14 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                                    <i class="fas fa-search text-gray-300 text-lg"></i>
                                                </div>
                                                <p class="text-sm text-gray-500 font-medium">Tidak ada hasil</p>
                                            </div>
                                        </template>
                                        <div class="border-t border-gray-100 mt-1 px-2 py-2">
                                            <button @click="saveHistory(); $el.closest('div[x-data]').querySelector('form').submit()"
                                                    class="w-full flex items-center justify-center gap-2 py-2.5 rounded-xl text-sm font-semibold text-green-600 hover:bg-green-50 transition-all">
                                                <span>Lihat semua hasil</span>
                                                <i class="fas fa-arrow-right text-xs"></i>
                                            </button>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('home') }}"
                       class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-semibold transition-all border border-transparent {{ request()->routeIs('home') ? 'bg-green-50 text-green-700 border-green-100' : 'text-gray-600 hover:bg-gray-50' }}">
                        <i class="fas fa-home w-4 text-center text-green-400"></i> Home
                    </a>

                    <a href="{{ route('products') }}"
                       class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-semibold transition-all border border-transparent {{ request()->routeIs('products*') ? 'bg-green-50 text-green-700 border-green-100' : 'text-gray-600 hover:bg-gray-50' }}">
                        <i class="fas fa-box w-4 text-center text-green-400"></i> Koleksi
                    </a>

                    <a href="{{ route('about') }}"
                       class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-semibold transition-all border border-transparent {{ request()->routeIs('about') ? 'bg-green-50 text-green-700 border-green-100' : 'text-gray-600 hover:bg-gray-50' }}">
                        <i class="fas fa-info-circle w-4 text-center text-green-400"></i> Tentang
                    </a>

                    <a href="{{ route('contact') }}"
                       class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-semibold transition-all border border-transparent {{ request()->routeIs('contact') ? 'bg-green-50 text-green-700 border-green-100' : 'text-gray-600 hover:bg-gray-50' }}">
                        <i class="fas fa-envelope w-4 text-center text-green-400"></i> Kontak Kami
                    </a>

                    <div class="pt-3 border-t border-gray-100 mt-2">
                        <p class="px-2 text-[10px] font-black uppercase tracking-[0.15em] text-gray-400 mb-2">Akun Saya</p>
                        @auth
                            @if(auth()->user()->isAdmin())
                                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-semibold text-red-600 hover:bg-red-50 transition-all">
                                    <i class="fas fa-tachometer-alt w-4 text-center text-red-400"></i> Dashboard Admin
                                </a>
                            @endif

                            @if(auth()->user()->isOperator())
                                <a href="{{ route('operator.dashboard') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-semibold text-green-600 hover:bg-green-50 transition-all">
                                    <i class="fas fa-tools w-4 text-center text-green-400"></i> Dashboard Operator
                                </a>
                            @endif

                            @php $cartCount = auth()->user()->cartItems->sum('quantity'); @endphp
                            <a href="{{ route('cart.index') }}" class="flex items-center justify-between px-4 py-2.5 rounded-xl text-sm font-semibold text-gray-600 hover:bg-gray-50 transition-all">
                                <div class="flex items-center gap-3">
                                    <i class="fas fa-shopping-cart w-4 text-center text-green-400"></i> Keranjang
                                </div>
                                @if($cartCount > 0)
                                    <span class="bg-red-500 text-white text-[9px] rounded-md px-1.5 py-0.5 font-bold">{{ $cartCount }}</span>
                                @endif
                            </a>

                            <a href="{{ route('orders.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-semibold text-gray-600 hover:bg-gray-50 transition-all">
                                <i class="fas fa-shopping-bag w-4 text-center text-green-400"></i> Pesanan
                            </a>

                            <a href="{{ route('chat.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-semibold text-gray-600 hover:bg-gray-50 transition-all">
                                <i class="fas fa-comments w-4 text-center text-green-400"></i> Pesan
                            </a>

                            <a href="{{ route('profile.show', Auth::user()->slug ?? 'user-' . Auth::user()->id) }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-semibold text-gray-600 hover:bg-gray-50 transition-all">
                                <i class="fas fa-user w-4 text-center text-green-400"></i> Profil
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-semibold text-gray-600 hover:bg-gray-50 transition-all">
                                <i class="fas fa-sign-in-alt w-4 text-center text-green-400"></i> Login
                            </a>

                            <a href="{{ route('register') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-semibold text-gray-600 hover:bg-gray-50 transition-all">
                                <i class="fas fa-user-plus w-4 text-center text-green-400"></i> Daftar Sekarang
                            </a>
                        @endauth
                    </div>
                </div>
                <!-- Scroll fade -->
                <div class="sticky bottom-0 h-6 bg-gradient-to-t from-white to-transparent pointer-events-none"></div>
            </div>

            <!-- Footer: User Info + Logout -->
            @auth
                <div class="px-4 py-3 border-t border-gray-100 bg-gray-50/50 flex-shrink-0">
                    <div class="flex items-center gap-3 mb-3">
                        <img src="{{ Auth::user()->profile_photo_url }}" class="w-10 h-10 rounded-xl shadow-sm border-2 border-white object-cover" alt="{{ Auth::user()->name }}">
                        <div class="min-w-0 flex-1">
                            <p class="text-xs font-bold text-gray-900 truncate leading-none mb-0.5">{{ Auth::user()->name }}</p>
                            <p class="text-[10px] text-gray-500 truncate">{{ Auth::user()->email }}</p>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full flex items-center justify-center gap-2 py-2.5 rounded-xl bg-white border border-red-100 text-red-600 text-xs font-bold uppercase tracking-wider hover:bg-red-50 transition-all shadow-sm">
                            <i class="fas fa-power-off text-[10px]"></i> Logout
                        </button>
                    </form>
                </div>
            @endauth
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
                        <!-- Smart Search Bar -->
                        <div class="hidden md:flex items-center"
                             x-data="smartSearch()"
                             @click.away="close()">
                            <div class="relative">
                                <form action="{{ route('products') }}" method="GET" @submit="saveHistory()">
                                    <input type="hidden" name="min_price" :value="filterMin">
                                    <input type="hidden" name="max_price" :value="filterMax">
                                    <div class="flex items-center bg-gray-100/80 border border-gray-200/80 rounded-full hover:border-green-300 focus-within:border-green-400 focus-within:bg-white focus-within:ring-4 focus-within:ring-green-50 transition-all duration-300">
                                        <i class="fas fa-search text-gray-400 text-xs ml-4"></i>
                                        <input type="text"
                                               name="search"
                                               x-model="query"
                                               @input.debounce.300ms="fetchSuggestions()"
                                               @focus="open = true"
                                               @keydown.escape="close()"
                                               value="{{ request('search') }}"
                                               placeholder="Cari produk..."
                                               class="flex-1 bg-transparent border-none outline-none text-sm py-2.5 px-3 text-gray-700 placeholder-gray-400">
                                        <button type="button" @click.stop="showFilter = !showFilter; open = false"
                                                class="flex items-center justify-center w-8 h-8 mr-1 rounded-full transition-all duration-200"
                                                :class="showFilter ? 'bg-green-100 text-green-600' : 'text-gray-400 hover:text-green-600 hover:bg-green-50'">
                                            <i class="fas fa-sliders-h text-[10px]"></i>
                                        </button>
                                    </div>
                                </form>

                                <!-- Filter Dropdown -->
                                <div x-show="showFilter" x-cloak @click.away="showFilter = false"
                                     x-transition:enter="transition ease-out duration-200"
                                     x-transition:enter-start="opacity-0 scale-95 -translate-y-1"
                                     x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                                     class="absolute right-0 mt-3 w-72 bg-white rounded-2xl shadow-2xl shadow-gray-900/10 p-5 z-50 border border-gray-100">
                                    <div class="flex items-center gap-2 mb-4">
                                        <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                                            <i class="fas fa-sliders-h text-green-600 text-xs"></i>
                                        </div>
                                        <p class="text-sm font-bold text-gray-800">Filter Harga</p>
                                    </div>
                                    <div class="flex gap-3 mb-4">
                                        <div class="flex-1">
                                            <label class="text-xs font-semibold text-gray-500 mb-1.5 block">Minimum</label>
                                            <div class="relative">
                                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs">Rp</span>
                                                <input type="number" x-model="filterMin" placeholder="0"
                                                       class="w-full pl-9 pr-3 py-2.5 text-sm border border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition-all">
                                            </div>
                                        </div>
                                        <div class="flex-1">
                                            <label class="text-xs font-semibold text-gray-500 mb-1.5 block">Maksimum</label>
                                            <div class="relative">
                                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs">Rp</span>
                                                <input type="number" x-model="filterMax" placeholder="..."
                                                       class="w-full pl-9 pr-3 py-2.5 text-sm border border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition-all">
                                            </div>
                                        </div>
                                    </div>
                                    <button type="button" @click="applyFilter()" class="w-full py-2.5 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white text-sm font-semibold rounded-xl transition-all duration-200 shadow-lg shadow-green-600/20 hover:shadow-xl hover:-translate-y-0.5">
                                        <i class="fas fa-search mr-1.5"></i> Terapkan Filter
                                    </button>
                                </div>

                                <!-- Autocomplete Dropdown -->
                                <div x-show="open && (query.length >= 2 || history.length > 0)"
                                     x-cloak
                                     x-transition:enter="transition ease-out duration-200"
                                     x-transition:enter-start="opacity-0 scale-95 -translate-y-1"
                                     x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                                     class="absolute left-0 right-0 mt-3 w-96 bg-white rounded-2xl shadow-2xl shadow-gray-900/10 z-50 border border-gray-100 overflow-hidden">

                                    <!-- Search History -->
                                    <template x-if="query.length < 2 && history.length > 0">
                                        <div class="p-2">
                                            <div class="flex items-center justify-between px-3 py-2">
                                                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Pencarian Terakhir</p>
                                                <button @click="clearHistory()" class="text-xs text-red-400 hover:text-red-600 font-medium transition-colors">Hapus</button>
                                            </div>
                                            <template x-for="(item, i) in history" :key="i">
                                                <a :href="'{{ route('products') }}?search=' + encodeURIComponent(item)"
                                                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-green-50 transition-all group">
                                                    <div class="w-8 h-8 rounded-lg bg-gray-100 group-hover:bg-green-100 flex items-center justify-center flex-shrink-0 transition-colors">
                                                        <i class="fas fa-clock text-gray-400 group-hover:text-green-500 text-xs transition-colors"></i>
                                                    </div>
                                                    <span class="text-sm text-gray-600 group-hover:text-gray-900 transition-colors" x-text="item"></span>
                                                </a>
                                            </template>
                                        </div>
                                    </template>

                                    <!-- Suggestions -->
                                    <template x-if="query.length >= 2">
                                        <div class="p-2">
                                            <!-- Category Matches -->
                                            <template x-if="categories.length > 0">
                                                <div>
                                                    <div class="px-3 py-2">
                                                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Kategori</p>
                                                    </div>
                                                    <template x-for="(cat, i) in categories" :key="'c'+i">
                                                        <a :href="cat.url"
                                                           class="flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-green-50 transition-all group">
                                                            <div class="w-9 h-9 rounded-xl bg-green-100 group-hover:bg-green-200 flex items-center justify-center flex-shrink-0 transition-colors">
                                                                <i class="fas fa-tag text-green-500 text-xs"></i>
                                                            </div>
                                                            <div class="min-w-0">
                                                                <span class="text-sm font-semibold text-gray-700 group-hover:text-green-700 transition-colors" x-text="cat.name"></span>
                                                                <p class="text-[11px] text-gray-400">Kategori</p>
                                                            </div>
                                                            <i class="fas fa-arrow-right text-gray-300 group-hover:text-green-400 text-xs ml-auto transition-colors"></i>
                                                        </a>
                                                    </template>
                                                </div>
                                            </template>

                                            <!-- Product Matches -->
                                            <template x-if="products.length > 0">
                                                <div>
                                                    <div class="px-3 py-2" :class="categories.length > 0 ? 'border-t border-gray-100 mt-1 pt-3' : ''">
                                                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Produk</p>
                                                    </div>
                                                    <template x-for="(item, i) in products" :key="'p'+i">
                                                        <a :href="item.url"
                                                           class="flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-green-50 transition-all group">
                                                            <img :src="item.image" :alt="item.name"
                                                                 class="w-11 h-11 rounded-xl object-cover bg-gray-100 flex-shrink-0 border border-gray-100 group-hover:border-green-200 transition-colors">
                                                            <div class="min-w-0 flex-1">
                                                                <p class="text-sm font-semibold text-gray-800 group-hover:text-green-700 truncate transition-colors" x-text="item.name"></p>
                                                                <p class="text-[11px] text-gray-400" x-text="item.category"></p>
                                                            </div>
                                                            <span class="text-xs font-bold text-green-600 bg-green-50 px-2.5 py-1 rounded-lg whitespace-nowrap" x-text="item.price"></span>
                                                        </a>
                                                    </template>
                                                </div>
                                            </template>

                                            <!-- No Results -->
                                            <template x-if="products.length === 0 && categories.length === 0">
                                                <div class="px-4 py-8 text-center">
                                                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                                        <i class="fas fa-search text-gray-300 text-xl"></i>
                                                    </div>
                                                    <p class="text-sm text-gray-500 font-medium">Tidak ditemukan hasil</p>
                                                    <p class="text-xs text-gray-400 mt-1">Coba kata kunci lain</p>
                                                </div>
                                            </template>

                                            <!-- View All -->
                                            <div class="border-t border-gray-100 mt-1 px-2 py-2">
                                                <button @click="saveHistory(); $el.closest('div[x-data]').querySelector('form').submit()"
                                                        class="w-full flex items-center justify-center gap-2 py-2.5 rounded-xl text-sm font-semibold text-green-600 hover:bg-green-50 transition-all">
                                                    <span>Lihat semua hasil</span>
                                                    <i class="fas fa-arrow-right text-xs"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>

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
                                        class="flex items-center gap-2.5 p-1.5 pr-3 rounded-xl bg-gray-50 hover:bg-green-50 border border-gray-100 transition-all duration-300 group">
                                    <img src="{{ Auth::user()->profile_photo_url }}"
                                         alt="{{ Auth::user()->name }}"
                                         class="w-8 h-8 rounded-lg border border-white shadow-sm object-cover group-hover:scale-105 transition-transform">
                                    <div class="flex items-center gap-1.5">
                                        <span class="text-sm font-bold text-gray-700 group-hover:text-green-700 hidden lg:inline max-w-[100px] truncate">{{ Auth::user()->name }}</span>
                                        <i class="fas fa-chevron-down text-[9px] text-gray-400 group-hover:text-green-400 transition-transform" :class="open ? 'rotate-180' : ''"></i>
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
                                     class="absolute right-0 mt-2 w-52 bg-white rounded-xl shadow-2xl shadow-green-900/10 py-1.5 z-50 border border-green-50">

                                    <div class="px-3 py-2.5 border-b border-gray-100 mb-1">
                                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-0.5">Signed in as</p>
                                        <p class="text-xs font-bold text-gray-900 truncate">{{ Auth::user()->email }}</p>
                                    </div>

                                    @if(auth()->user()->isAdmin())
                                        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2.5 px-3 py-2 text-sm text-gray-700 hover:bg-green-50 hover:text-green-700 transition-colors">
                                            <i class="fas fa-tachometer-alt w-4 text-center text-green-500 text-xs"></i>
                                            <span class="font-semibold text-xs">Dashboard Admin</span>
                                        </a>
                                    @endif

                                    @if(auth()->user()->isOperator())
                                        <a href="{{ route('operator.dashboard') }}" class="flex items-center gap-2.5 px-3 py-2 text-sm text-gray-700 hover:bg-green-50 hover:text-green-700 transition-colors">
                                            <i class="fas fa-tools w-4 text-center text-green-600 text-xs"></i>
                                            <span class="font-semibold text-xs">Dashboard Operator</span>
                                        </a>
                                    @endif

                                    <div class="border-t border-gray-100 my-1"></div>

                                    <a href="{{ route('orders.index') }}" class="flex items-center gap-2.5 px-3 py-2 text-sm text-gray-700 hover:bg-green-50 hover:text-green-700 transition-colors">
                                        <i class="fas fa-shopping-bag w-4 text-center text-gray-400 text-xs"></i>
                                        <span class="font-semibold text-xs">Pemesanan Saya</span>
                                    </a>

                                    <a href="{{ route('chat.index') }}" class="flex items-center gap-2.5 px-3 py-2 text-sm text-gray-700 hover:bg-green-50 hover:text-green-700 transition-colors">
                                        <i class="fas fa-comments w-4 text-center text-gray-400 text-xs"></i>
                                        <span class="font-semibold text-xs">Pesan Chat</span>
                                    </a>

                                    <a href="{{ route('profile.show', Auth::user()->slug ?? 'user-' . Auth::user()->id) }}" class="flex items-center gap-2.5 px-3 py-2 text-sm text-gray-700 hover:bg-green-50 hover:text-green-700 transition-colors">
                                        <i class="fas fa-user-circle w-4 text-center text-gray-400 text-xs"></i>
                                        <span class="font-semibold text-xs">Lihat Profil</span>
                                    </a>

                                    <div class="border-t border-gray-100 mt-1 pt-1">
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="flex w-full items-center gap-2.5 px-3 py-2 text-xs text-red-600 hover:bg-red-50 transition-colors font-bold uppercase tracking-wider">
                                                <i class="fas fa-power-off w-4 text-center text-red-400"></i>
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
                        <h3 class="text-lg font-semibold mb-4 text-white flex items-center gap-3">
                            @if(isset($settings['logo']) && $settings['logo']->value)
                                <img src="{{ asset('storage/' . $settings['logo']->value) }}" alt="Logo" class="h-10 w-auto rounded-lg bg-white/10 p-1">
                            @else
                                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-10 w-auto rounded-lg bg-white/10 p-1">
                            @endif
                            <span class="text-green-400">{{ $settings['website_name']->value ?? 'FtwoMart' }}</span>
                        </h3>
                        <p class="text-gray-300 text-sm leading-relaxed">
                            {{ $settings['website_description']->value ?? 'Marketplace terpercaya untuk berbagai produk berkualitas dengan pelayanan terbaik.' }}
                        </p>
                    </div>

                    <div>
                        <h4 class="text-lg font-semibold mb-4 text-green-400">Kategori</h4>
                        <ul class="space-y-2 text-gray-300">
                            @if(isset($categories) && $categories->count() > 0)
                                @foreach($categories as $category)
                                    <li><a href="{{ route('products', ['category' => $category->slug]) }}" class="hover:text-green-400 transition-colors">{{ $category->name }}</a></li>
                                @endforeach
                            @else
                                <li class="text-gray-500">Tidak ada kategori</li>
                            @endif
                        </ul>
                    </div>

                    <div>
                        <h4 class="text-lg font-semibold mb-4 text-green-400">Layanan</h4>
                        <ul class="space-y-2 text-gray-300">
                            @if(isset($services) && $services->count() > 0)
                                @foreach($services as $service)
                                    <li><a href="{{ $service->url ?? '#' }}" class="hover:text-green-400 transition-colors">{{ $service->name }}</a></li>
                                @endforeach
                            @else
                                <li class="text-gray-500">Tidak ada layanan</li>
                            @endif
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

    <script>
    function smartSearch() {
        return {
            query: '',
            open: false,
            showFilter: false,
            filterMin: '',
            filterMax: '',
            products: [],
            categories: [],
            history: JSON.parse(localStorage.getItem('search_history') || '[]'),
            fetchSuggestions() {
                if (this.query.length < 2) {
                    this.products = [];
                    this.categories = [];
                    return;
                }
                fetch('{{ route("search.suggestions") }}?q=' + encodeURIComponent(this.query))
                    .then(r => r.json())
                    .then(data => {
                        this.products = data.products;
                        this.categories = data.categories;
                    });
            },
            saveHistory() {
                if (this.query.length < 2) return;
                let history = JSON.parse(localStorage.getItem('search_history') || '[]');
                history = history.filter(h => h.toLowerCase() !== this.query.toLowerCase());
                history.unshift(this.query);
                history = history.slice(0, 8);
                localStorage.setItem('search_history', JSON.stringify(history));
                this.history = history;
            },
            clearHistory() {
                localStorage.removeItem('search_history');
                this.history = [];
            },
            applyFilter() {
                this.showFilter = false;
                const url = new URL('{{ route("products") }}', window.location.origin);
                if (this.query) url.searchParams.set('search', this.query);
                if (this.filterMin) url.searchParams.set('min_price', this.filterMin);
                if (this.filterMax) url.searchParams.set('max_price', this.filterMax);
                window.location.href = url.toString();
            },
            close() {
                this.open = false;
                this.showFilter = false;
            }
        };
    }
    </script>
</body>
</html>
