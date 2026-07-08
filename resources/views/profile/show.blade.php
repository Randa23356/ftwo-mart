@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="fixed inset-0 opacity-20 pointer-events-none">
        <div class="absolute inset-0" style="background-image: url(\"data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%2316a34a' fill-opacity='0.06'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E\");"></div>
    </div>
    <div class="relative z-10 container mx-auto px-4 sm:px-6 lg:px-8 py-4 sm:py-6 lg:py-8">
        <div class="max-w-7xl mx-auto">
            <!-- Enhanced Breadcrumb -->
            <nav class="flex mb-6 sm:mb-8" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-2 sm:space-x-3">
                    <li class="inline-flex items-center">
                        @if(Auth::user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center text-xs sm:text-sm font-medium text-gray-600 hover:text-green-600 transition-all duration-200 hover:scale-105">
                                <i class="fas fa-home mr-1.5 sm:mr-2 text-green-500"></i>
                                <span class="hidden sm:inline">Admin</span>
                                <span class="sm:hidden">Home</span>
                            </a>
                        @elseif(Auth::user()->isOperator())
                            <a href="{{ route('operator.dashboard') }}" class="inline-flex items-center text-xs sm:text-sm font-medium text-gray-600 hover:text-green-600 transition-all duration-200 hover:scale-105">
                                <i class="fas fa-home mr-1.5 sm:mr-2 text-green-500"></i>
                                <span class="hidden sm:inline">Operator</span>
                                <span class="sm:hidden">Home</span>
                            </a>
                        @else
                            <a href="{{ route('home') }}" class="inline-flex items-center text-xs sm:text-sm font-medium text-gray-600 hover:text-green-600 transition-all duration-200 hover:scale-105">
                                <i class="fas fa-home mr-1.5 sm:mr-2 text-green-500"></i>
                                <span class="hidden sm:inline">Beranda</span>
                                <span class="sm:hidden">Home</span>
                            </a>
                        @endif
                    </li>
                    <li>
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-gray-400 mx-2 text-xs sm:text-sm animate-pulse"></i>
                            <span class="ml-1 text-xs sm:text-sm font-medium text-gray-700 md:ml-2 bg-green-50 px-3 py-1 rounded-full">Profil {{ $user->name }}</span>
                        </div>
                    </li>
                </ol>
            </nav>

            <!-- Enhanced Profile Header -->
            <div class="bg-white/90 backdrop-blur-xl rounded-2xl shadow-xl border border-white/20 overflow-hidden mb-6 sm:mb-8">
                <!-- Hero Section with Pattern -->
                <div class="relative">
                    <div class="absolute inset-0 bg-gradient-to-r from-green-600 to-emerald-700 opacity-95"></div>
                    <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"%3E%3Cg fill="none" fill-rule="evenodd"%3E%3Cg fill="%23ffffff" fill-opacity="0.1"%3E%3Ccircle cx="30" cy="30" r="4"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')] opacity-20"></div>
                    <div class="relative h-32 sm:h-40 lg:h-48"></div>
                </div>
                
                <div class="relative px-4 sm:px-6 lg:px-8 pb-6 sm:pb-8">
                    <!-- Profile Content -->
                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6 lg:gap-8 -mt-16 sm:-mt-20">
                        <!-- Profile Photo Section -->
                        <div class="flex flex-col sm:flex-row items-center sm:items-end gap-4 sm:gap-6">
                            <div class="relative group">
                                <!-- Glow Effect -->
                                <div class="absolute inset-0 bg-gradient-to-r from-green-400 to-emerald-400 rounded-full blur-xl opacity-50 group-hover:opacity-75 transition-opacity duration-300"></div>
                                
                                <img src="{{ $user->profile_photo_url }}"
                                     alt="{{ $user->name }}"
                                     class="profile-avatar relative w-28 h-28 sm:w-36 sm:h-36 lg:w-44 lg:h-44 rounded-full border-4 border-white shadow-2xl object-cover transition-all duration-300 group-hover:scale-105 group-hover:rotate-1">

                                <!-- Online Status -->
                                @if($user->presence_status == 'Online')
                                    <div class="absolute bottom-3 right-3 w-6 h-6 sm:w-7 sm:h-7 bg-green-500 border-4 border-white rounded-full animate-pulse shadow-lg">
                                        <div class="absolute inset-0 bg-green-400 rounded-full animate-ping"></div>
                                    </div>
                                @endif
                            </div>
                            
                            <!-- Quick Stats Mobile -->
                            <div class="flex gap-3 sm:hidden">
                                <div class="bg-white/90 backdrop-blur px-3 py-2 rounded-xl shadow-lg border border-gray-100">
                                    <div class="text-xs text-gray-500">Orders</div>
                                    <div class="text-lg font-bold text-green-600">{{ $user->orders()->count() }}</div>
                                </div>
                                <div class="bg-white/90 backdrop-blur px-3 py-2 rounded-xl shadow-lg border border-gray-100">
                                    <div class="text-xs text-gray-500">Chat</div>
                                    <div class="text-lg font-bold text-green-600">{{ $user->conversations()->count() }}</div>
                                </div>
                            </div>
                        </div>

                        <!-- User Info -->
                        <div class="flex-1 text-center lg:text-left">
                            <div class="space-y-3">
                                <!-- Name and Title -->
                                <div>
                                    <div class="flex items-center justify-center lg:justify-start gap-2 mb-2">
                                        <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-900 bg-gradient-to-r from-gray-900 to-gray-700 bg-clip-text text-transparent">
                                            {{ $user->name }}
                                        </h1>
                                        <!-- Verified Badge -->
                                        @if($user->email_verified_at)
                                            <div class="flex items-center justify-center w-6 h-6 sm:w-8 sm:h-8 bg-green-600 rounded-full shadow-lg border-2 border-white">
                                                <i class="fas fa-check text-white text-xs sm:text-sm"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-2 sm:gap-4">
                                        <p class="text-sm sm:text-base text-gray-600 flex items-center">
                                            <i class="fas fa-envelope mr-2 text-green-500"></i>
                                            {{ $user->email }}
                                        </p>
                                        @if($user->phone)
                                            <p class="text-sm sm:text-base text-gray-600 flex items-center">
                                                <i class="fas fa-phone mr-2 text-green-500"></i>
                                                {{ $user->phone }}
                                            </p>
                                        @endif
                                    </div>
                                </div>

                                <!-- Bio -->
                                @if($user->bio)
                                    <p class="text-sm sm:text-base text-gray-700 leading-relaxed max-w-2xl bg-gray-50 p-3 rounded-xl border border-gray-100">
                                        <i class="fas fa-quote-left text-green-400 mr-2"></i>
                                        {{ $user->bio }}
                                    </p>
                                @endif

                                <!-- Status and Actions -->
                                <div class="flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-3 sm:gap-4">
                                    <!-- Status Badge -->
                                    <div class="flex items-center gap-2">
                                        <span class="px-4 py-2 rounded-full text-sm font-bold {{ $user->presence_status == 'Online' ? 'bg-gradient-to-r from-green-100 to-emerald-100 text-green-800 border border-green-200' : 'bg-gradient-to-r from-gray-100 to-slate-100 text-gray-800 border border-gray-200' }} shadow-sm">
                                            <i class="fas fa-circle text-xs mr-2 {{ $user->presence_status == 'Online' ? 'text-green-500 animate-pulse' : 'text-gray-400' }}"></i>
                                            {{ $user->presence_status }}
                                        </span>
                                        
                                        <!-- Role Badge -->
                                        <span class="px-3 py-1.5 rounded-full text-xs font-bold
                                            {{ $user->isAdmin() ? 'bg-purple-100 text-purple-800 border border-purple-200' :
                                               ($user->isOperator() ? 'bg-blue-100 text-blue-800 border border-blue-200' : 'bg-green-100 text-green-800 border border-green-200') }} shadow-sm">
                                            <i class="fas fa-{{ $user->isAdmin() ? 'user-shield' : ($user->isOperator() ? 'user-tie' : 'user') }} mr-1"></i>
                                            {{ $user->isAdmin() ? 'Admin' : ($user->isOperator() ? 'Operator' : 'Pelanggan') }}
                                        </span>
                                        
                                        <!-- Operator Badge (Only for operators) -->
                                        @if($user->isOperator())
                                        <span class="px-3 py-1.5 rounded-full text-xs font-bold bg-gradient-to-r from-cyan-100 to-blue-100 text-cyan-800 border border-cyan-200 shadow-sm flex items-center">
                                            <i class="fas fa-headset mr-1 text-cyan-600"></i>
                                            Support Team
                                        </span>
                                        @endif
                                    </div>

                                    <!-- Action Buttons -->
                                    <div class="flex gap-2">
                                        @if(Auth::id() !== $user->id)
                                            <a href="{{ route('contact') }}"
                                               class="inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all duration-300 hover:shadow-lg hover:-translate-y-0.5 text-sm font-medium shadow-lg">
                                                <i class="fas fa-comment mr-2"></i>
                                                <span class="hidden sm:inline">Kirim Pesan</span>
                                                <span class="sm:hidden">Chat</span>
                                            </a>
                                        @else
                                            <a href="{{ route('profile.edit') }}"
                                               class="inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all duration-300 hover:shadow-lg hover:-translate-y-0.5 text-sm font-medium shadow-lg">
                                                <i class="fas fa-edit mr-2"></i>
                                                <span class="hidden sm:inline">Edit Profil</span>
                                                <span class="sm:hidden">Edit</span>
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">
                <!-- Main Content Area -->
                <div class="lg:col-span-2 space-y-6 lg:space-y-8">
                    <!-- Personal Information Card -->
                    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                        <!-- Card Header -->
                        <div class="bg-gradient-to-r from-green-50 to-emerald-50 px-6 sm:px-8 py-4 border-b border-green-100">
                            <div class="flex items-center">
                                <div class="w-12 h-12 bg-gradient-to-br from-green-600 to-emerald-700 rounded-2xl flex items-center justify-center mr-4 shadow-lg">
                                    <i class="fas fa-user text-white text-lg"></i>
                                </div>
                                <div>
                                    <h2 class="text-xl sm:text-2xl font-bold text-gray-900">Informasi Pribadi</h2>
                                    <p class="text-sm text-gray-600">Data diri dan kontak</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Card Content -->
                        <div class="p-6 sm:p-8">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                                <!-- Name -->
                                <div class="group p-4 bg-gradient-to-r from-gray-50 to-slate-50 rounded-2xl hover:from-green-50 hover:to-emerald-50 transition-all duration-300 border border-gray-100 hover:border-green-200">
                                    <div class="flex items-start space-x-3">
                                        <div class="bg-white p-3 rounded-xl shadow-sm group-hover:shadow-md transition-shadow">
                                            <i class="fas fa-signature text-green-600 text-lg"></i>
                                        </div>
                                        <div class="flex-1">
                                            <p class="text-xs sm:text-sm text-gray-500 mb-1 font-medium">Nama Lengkap</p>
                                            <p class="font-bold text-gray-900 text-sm sm:text-base">{{ $user->name }}</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Email -->
                                <div class="group p-4 bg-gradient-to-r from-gray-50 to-slate-50 rounded-2xl hover:from-green-50 hover:to-emerald-50 transition-all duration-300 border border-gray-100 hover:border-green-200">
                                    <div class="flex items-start space-x-3">
                                        <div class="bg-white p-3 rounded-xl shadow-sm group-hover:shadow-md transition-shadow">
                                            <i class="fas fa-envelope text-green-600 text-lg"></i>
                                        </div>
                                        <div class="flex-1">
                                            <p class="text-xs sm:text-sm text-gray-500 mb-1 font-medium">Email</p>
                                            <p class="font-bold text-gray-900 text-sm sm:text-base break-all">{{ $user->email }}</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Phone -->
                                @if($user->phone)
                                <div class="group p-4 bg-gradient-to-r from-gray-50 to-slate-50 rounded-2xl hover:from-green-50 hover:to-emerald-50 transition-all duration-300 border border-gray-100 hover:border-green-200">
                                    <div class="flex items-start space-x-3">
                                        <div class="bg-white p-3 rounded-xl shadow-sm group-hover:shadow-md transition-shadow">
                                            <i class="fas fa-phone text-green-600 text-lg"></i>
                                        </div>
                                        <div class="flex-1">
                                            <p class="text-xs sm:text-sm text-gray-500 mb-1 font-medium">Telepon</p>
                                            <p class="font-bold text-gray-900 text-sm sm:text-base">{{ $user->phone }}</p>
                                        </div>
                                    </div>
                                </div>
                                @endif

                                <!-- Gender -->
                                @if($user->gender)
                                <div class="group p-4 bg-gradient-to-r from-gray-50 to-slate-50 rounded-2xl hover:from-green-50 hover:to-emerald-50 transition-all duration-300 border border-gray-100 hover:border-green-200">
                                    <div class="flex items-start space-x-3">
                                        <div class="bg-white p-3 rounded-xl shadow-sm group-hover:shadow-md transition-shadow">
                                            <i class="fas fa-{{ $user->gender == 'male' ? 'mars' : ($user->gender == 'female' ? 'venus' : 'genderless') }} text-green-600 text-lg"></i>
                                        </div>
                                        <div class="flex-1">
                                            <p class="text-xs sm:text-sm text-gray-500 mb-1 font-medium">Jenis Kelamin</p>
                                            <p class="font-bold text-gray-900 text-sm sm:text-base">
                                                {{ $user->gender == 'male' ? 'Laki-laki' : ($user->gender == 'female' ? 'Perempuan' : 'Lainnya') }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                @endif

                                <!-- Birth Date -->
                                @if($user->birth_date)
                                <div class="group p-4 bg-gradient-to-r from-gray-50 to-slate-50 rounded-2xl hover:from-green-50 hover:to-emerald-50 transition-all duration-300 border border-gray-100 hover:border-green-200">
                                    <div class="flex items-start space-x-3">
                                        <div class="bg-white p-3 rounded-xl shadow-sm group-hover:shadow-md transition-shadow">
                                            <i class="fas fa-birthday-cake text-green-600 text-lg"></i>
                                        </div>
                                        <div class="flex-1">
                                            <p class="text-xs sm:text-sm text-gray-500 mb-1 font-medium">Tanggal Lahir</p>
                                            <p class="font-bold text-gray-900 text-sm sm:text-base">
                                                {{ $user->birth_date->format('d F Y') }}
                                                <span class="text-gray-500 text-xs sm:text-sm ml-2">({{ $user->birth_date->age }} tahun)</span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                @endif

                                <!-- Operator Information (Only for operators) -->
                                @if($user->isOperator())
                                <div class="group p-4 bg-gradient-to-r from-blue-50 to-cyan-50 rounded-2xl hover:from-blue-100 hover:to-cyan-100 transition-all duration-300 border border-teal-200 hover:border-teal-300 sm:col-span-2">
                                    <div class="flex items-start space-x-3">
                                        <div class="bg-blue-100 p-3 rounded-xl shadow-sm group-hover:shadow-md transition-shadow">
                                            <i class="fas fa-headset text-green-600 text-lg"></i>
                                        </div>
                                        <div class="flex-1">
                                            <p class="text-xs sm:text-sm text-gray-500 mb-1 font-medium">Informasi Operator</p>
                                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 mt-2">
                                                <div class="flex items-center space-x-2">
                                                    <i class="fas fa-id-badge text-green-500 text-sm"></i>
                                                    <span class="font-bold text-gray-900 text-sm">ID: #{{ str_pad($user->id, 4, '0', STR_PAD_LEFT) }}</span>
                                                </div>
                                                @if($user->phone)
                                                <div class="flex items-center space-x-2">
                                                    <i class="fas fa-phone-alt text-green-500 text-sm"></i>
                                                    <span class="font-bold text-gray-900 text-sm">{{ $user->phone }}</span>
                                                </div>
                                                @endif
                                                <div class="flex items-center space-x-2">
                                                    <i class="fas fa-envelope text-green-500 text-sm"></i>
                                                    <span class="font-bold text-gray-900 text-sm truncate">{{ $user->email }}</span>
                                                </div>
                                                <div class="flex items-center space-x-2">
                                                    <i class="fas fa-calendar-check text-emerald-500 text-sm"></i>
                                                    <span class="font-bold text-gray-900 text-sm">{{ $user->created_at->format('d M Y') }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif

                                <!-- Address -->
                                @if($user->address)
                                <div class="group p-4 bg-gradient-to-r from-gray-50 to-slate-50 rounded-2xl hover:from-green-50 hover:to-emerald-50 transition-all duration-300 border border-gray-100 hover:border-green-200 sm:col-span-2">
                                    <div class="flex items-start space-x-3">
                                        <div class="bg-white p-3 rounded-xl shadow-sm group-hover:shadow-md transition-shadow">
                                            <i class="fas fa-map-marker-alt text-green-600 text-lg"></i>
                                        </div>
                                        <div class="flex-1">
                                            <p class="text-xs sm:text-sm text-gray-500 mb-1 font-medium">Alamat</p>
                                            <p class="font-bold text-gray-900 text-sm sm:text-base leading-relaxed">{{ $user->address }}</p>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Recent Activity (Only for admin/operator viewing) -->
                    @if(Auth::user()->isAdmin() || Auth::user()->isOperator())
                    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                        <!-- Card Header -->
                        <div class="bg-gradient-to-r from-green-50 to-emerald-50 px-6 sm:px-8 py-4 border-b border-green-100">
                            <div class="flex items-center">
                                <div class="w-12 h-12 bg-gradient-to-br from-green-600 to-emerald-700 rounded-2xl flex items-center justify-center mr-4 shadow-lg">
                                    <i class="fas fa-clock text-white text-lg"></i>
                                </div>
                                <div>
                                    <h2 class="text-xl sm:text-2xl font-bold text-gray-900">Aktivitas Terkini</h2>
                                    <p class="text-sm text-gray-600">Riwayat pesanan dan pesan</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Card Content -->
                        <div class="p-6 sm:p-8">
                            <div class="space-y-6">
                                <!-- Recent Orders -->
                                @if($user->orders()->latest()->limit(3)->get()->isNotEmpty())
                                    <div class="bg-green-50 border-l-4 border-green-500 p-4 sm:p-6 rounded-r-2xl">
                                        <h4 class="font-bold text-gray-900 mb-4 text-sm sm:text-base flex items-center">
                                            <i class="fas fa-shopping-cart mr-2 text-green-600"></i>
                                            Pesanan Terbaru
                                        </h4>
                                        <div class="space-y-3">
                                            @foreach($user->orders()->latest()->limit(3)->get() as $order)
                                                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center p-3 bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow">
                                                    <div class="flex-1">
                                                        <p class="text-sm sm:text-base font-bold text-gray-900">Order #{{ $order->id }}</p>
                                                        <p class="text-xs sm:text-sm text-gray-500">{{ $order->created_at->diffForHumans() }}</p>
                                                    </div>
                                                    <span class="px-3 py-1.5 text-xs rounded-full bg-{{ $order->status_color }}-100 text-{{ $order->status_color }}-800 font-bold shadow-sm">
                                                        {{ ucfirst($order->status) }}
                                                    </span>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                <!-- Recent Messages -->
                                @if($user->messages()->latest()->limit(3)->get()->isNotEmpty())
                                    <div class="bg-green-50 border-l-4 border-green-400 p-4 sm:p-6 rounded-r-2xl">
                                        <h4 class="font-bold text-gray-900 mb-4 text-sm sm:text-base flex items-center">
                                            <i class="fas fa-comments mr-2 text-green-600"></i>
                                            Pesan Terbaru
                                        </h4>
                                        <div class="space-y-3">
                                            @foreach($user->messages()->latest()->limit(3)->get() as $message)
                                                <div class="p-3 bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow">
                                                    <p class="text-sm sm:text-base text-gray-700 leading-relaxed">{{ Str::limit($message->body, 80) }}</p>
                                                    <p class="text-xs sm:text-sm text-gray-500 mt-2">{{ $message->created_at->diffForHumans() }}</p>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Enhanced Sidebar -->
                <div class="lg:col-span-1 space-y-6">
                    <!-- Stats Card -->
                    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                        <!-- Card Header -->
                        <div class="bg-gradient-to-r from-green-50 to-emerald-50 px-6 py-4 border-b border-green-100">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-gradient-to-br from-green-600 to-emerald-700 rounded-2xl flex items-center justify-center mr-3 shadow-lg">
                                    <i class="fas fa-chart-bar text-white text-sm"></i>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-gray-900">Statistik</h3>
                                    <p class="text-xs text-gray-600">Data aktivitas</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Card Content -->
                        <div class="p-6">
                            <div class="space-y-3">
                                <!-- Total Orders -->
                                <div class="group p-3 bg-green-50 rounded-xl hover:bg-green-100 transition-all duration-300 border border-green-100 hover:border-green-200">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-2">
                                            <div class="bg-green-100 p-2 rounded-full group-hover:scale-110 transition-transform shadow-sm">
                                                <i class="fas fa-shopping-cart text-green-600 text-sm"></i>
                                            </div>
                                            <div>
                                                <p class="text-xs text-gray-600 font-medium">Total Pesanan</p>
                                                <p class="text-lg font-bold text-green-600">{{ $user->orders()->count() }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Total Conversations -->
                                <div class="group p-3 bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl hover:from-green-100 hover:to-emerald-100 transition-all duration-300 border border-green-200 hover:border-green-300">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-2">
                                            <div class="bg-green-100 p-2 rounded-full group-hover:scale-110 transition-transform shadow-sm">
                                                <i class="fas fa-comments text-green-600 text-sm"></i>
                                            </div>
                                            <div>
                                                <p class="text-xs text-gray-600 font-medium">Percakapan</p>
                                                <p class="text-lg font-bold text-green-600">{{ $user->conversations()->count() }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Member Since -->
                                <div class="group p-3 bg-emerald-50 rounded-xl hover:bg-emerald-100 transition-all duration-300 border border-emerald-100 hover:border-emerald-200">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-2">
                                            <div class="bg-emerald-100 p-2 rounded-full group-hover:scale-110 transition-transform shadow-sm">
                                                <i class="fas fa-calendar-alt text-emerald-600 text-sm"></i>
                                            </div>
                                            <div>
                                                <p class="text-xs text-gray-600 font-medium">Member Sejak</p>
                                                <p class="text-sm font-bold text-emerald-600">{{ $user->created_at->format('M Y') }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Last Seen -->
                                @if($user->last_seen_at)
                                <div class="group p-3 bg-gradient-to-r from-gray-50 to-slate-50 rounded-xl hover:from-gray-100 hover:to-slate-100 transition-all duration-300 border border-gray-200 hover:border-gray-300">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-2">
                                            <div class="bg-gray-100 p-2 rounded-full group-hover:scale-110 transition-transform shadow-sm">
                                                <i class="fas fa-eye text-gray-600 text-sm"></i>
                                            </div>
                                            <div>
                                                <p class="text-xs text-gray-600 font-medium">Terakhir Dilihat</p>
                                                <p class="text-sm font-bold text-gray-600">{{ $user->last_seen_at->diffForHumans() }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Operator Performance Stats (Only for operators) -->
                    @if($user->isOperator())
                    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                        <!-- Card Header -->
                        <div class="bg-gradient-to-r from-cyan-50 to-blue-50 px-6 py-4 border-b border-gray-100">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-gradient-to-r from-cyan-500 to-blue-600 rounded-2xl flex items-center justify-center mr-3 shadow-lg">
                                    <i class="fas fa-tachometer-alt text-white text-sm"></i>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-gray-900">Performa Kerja</h3>
                                    <p class="text-xs text-gray-600">Statistik operator</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Card Content -->
                        <div class="p-6">
                            <div class="space-y-3">
                                <!-- Orders Processed -->
                                <div class="group p-3 bg-gradient-to-r from-emerald-50 to-green-50 rounded-xl hover:from-emerald-100 hover:to-green-100 transition-all duration-300 border border-emerald-200 hover:border-emerald-300">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-2">
                                            <div class="bg-emerald-100 p-2 rounded-full group-hover:scale-110 transition-transform shadow-sm">
                                                <i class="fas fa-check-circle text-emerald-600 text-sm"></i>
                                            </div>
                                            <div>
                                                <p class="text-xs text-gray-600 font-medium">Pesanan Selesai</p>
                                                <p class="text-lg font-bold text-emerald-600">
                                                    {{ $user->orders()->where('order_status', 'delivered')->count() }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Pending Orders -->
                                <div class="group p-3 bg-gradient-to-r from-amber-50 to-orange-50 rounded-xl hover:from-amber-100 hover:to-orange-100 transition-all duration-300 border border-amber-200 hover:border-orange-300">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-2">
                                            <div class="bg-amber-100 p-2 rounded-full group-hover:scale-110 transition-transform shadow-sm">
                                                <i class="fas fa-clock text-amber-600 text-sm"></i>
                                            </div>
                                            <div>
                                                <p class="text-xs text-gray-600 font-medium">Menunggu Proses</p>
                                                <p class="text-lg font-bold text-amber-600">
                                                    {{ $user->orders()->where('order_status', 'pending')->count() }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Cancelled Orders -->
                                <div class="group p-3 bg-gradient-to-r from-red-50 to-pink-50 rounded-xl hover:from-red-100 hover:to-pink-100 transition-all duration-300 border border-red-200 hover:border-red-300">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-2">
                                            <div class="bg-red-100 p-2 rounded-full group-hover:scale-110 transition-transform shadow-sm">
                                                <i class="fas fa-times-circle text-red-600 text-sm"></i>
                                            </div>
                                            <div>
                                                <p class="text-xs text-gray-600 font-medium">Dibatalkan</p>
                                                <p class="text-lg font-bold text-red-600">
                                                    {{ $user->orders()->where('order_status', 'cancelled')->count() }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Response Rate -->
                                <div class="group p-3 bg-gradient-to-r from-purple-50 to-green-50 rounded-xl hover:from-purple-100 hover:to-green-100 transition-all duration-300 border border-emerald-200 hover:border-emerald-300">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-2">
                                            <div class="bg-purple-100 p-2 rounded-full group-hover:scale-110 transition-transform shadow-sm">
                                                <i class="fas fa-reply text-emerald-600 text-sm"></i>
                                            </div>
                                            <div>
                                                <p class="text-xs text-gray-600 font-medium">Response Rate</p>
                                                <p class="text-lg font-bold text-emerald-600">
                                                    @php
                                                        $totalMessages = $user->messages()->count();
                                                        $totalConversations = $user->conversations()->count();
                                                        $rate = $totalConversations > 0 ? round(($totalMessages / $totalConversations) * 100, 1) : 0;
                                                    @endphp
                                                    {{ $rate }}%
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Enhanced Role & Status Card -->
                    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                        <!-- Card Header -->
                        <div class="bg-gradient-to-r from-green-50 to-emerald-50 px-6 py-4 border-b border-green-100">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-gradient-to-br from-green-600 to-emerald-700 rounded-2xl flex items-center justify-center mr-3 shadow-lg">
                                    <i class="fas fa-shield-alt text-white text-sm"></i>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-gray-900">Role & Status</h3>
                                    <p class="text-xs text-gray-600">Hak akses dan status</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Card Content -->
                        <div class="p-6">
                            <div class="space-y-3">
                                <!-- User Role -->
                                <div class="group p-3 bg-gradient-to-r from-gray-50 to-slate-50 rounded-xl hover:from-green-50 hover:to-emerald-50 transition-all duration-300 border border-gray-100 hover:border-green-200">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-2">
                                            <div class="bg-white p-2 rounded-xl shadow-sm group-hover:shadow-md transition-shadow">
                                                <i class="fas fa-user-tag text-green-600 text-sm"></i>
                                            </div>
                                            <div>
                                                <p class="text-xs text-gray-500 mb-1 font-medium">Role</p>
                                                <p class="text-sm font-bold text-gray-900">User Level</p>
                                            </div>
                                        </div>
                                        <span class="px-3 py-1.5 rounded-full text-xs font-bold
                                            {{ $user->isAdmin() ? 'bg-purple-100 text-purple-800 border border-purple-200' :
                                               ($user->isOperator() ? 'bg-blue-100 text-blue-800 border border-blue-200' : 'bg-green-100 text-green-800 border border-green-200') }} shadow-sm">
                                            <i class="fas fa-{{ $user->isAdmin() ? 'user-shield' : ($user->isOperator() ? 'user-tie' : 'user') }} mr-1"></i>
                                            {{ $user->isAdmin() ? 'Admin' : ($user->isOperator() ? 'Operator' : 'Pelanggan') }}
                                        </span>
                                    </div>
                                </div>

                                <!-- Account Status -->
                                <div class="group p-3 bg-gradient-to-r from-gray-50 to-slate-50 rounded-xl hover:from-green-50 hover:to-emerald-50 transition-all duration-300 border border-gray-100 hover:border-green-200">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-2">
                                            <div class="bg-white p-2 rounded-xl shadow-sm group-hover:shadow-md transition-shadow">
                                                <i class="fas fa-toggle-on text-green-600 text-sm"></i>
                                            </div>
                                            <div>
                                                <p class="text-xs text-gray-500 mb-1 font-medium">Status Akun</p>
                                                <p class="text-sm font-bold text-gray-900">Account Status</p>
                                            </div>
                                        </div>
                                        <span class="px-3 py-1.5 rounded-full text-xs font-bold {{ $user->is_active ? 'bg-gradient-to-r from-green-100 to-emerald-100 text-green-800 border border-green-200' : 'bg-gradient-to-r from-red-100 to-pink-100 text-red-800 border border-red-200' }} shadow-sm">
                                            {{ $user->is_active ? 'Aktif' : 'Non-aktif' }}
                                        </span>
                                    </div>
                                </div>

                                <!-- Email Verification -->
                                <div class="group p-3 bg-gradient-to-r from-gray-50 to-slate-50 rounded-xl hover:from-green-50 hover:to-emerald-50 transition-all duration-300 border border-gray-100 hover:border-green-200">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-2">
                                            <div class="bg-white p-2 rounded-xl shadow-sm group-hover:shadow-md transition-shadow">
                                                <i class="fas fa-envelope-circle-check text-green-600 text-sm"></i>
                                            </div>
                                            <div>
                                                <p class="text-xs text-gray-500 mb-1 font-medium">Email Verification</p>
                                                <p class="text-sm font-bold text-gray-900">Email Status</p>
                                            </div>
                                        </div>
                                        <span class="px-3 py-1.5 rounded-full text-xs font-bold {{ $user->email_verified_at ? 'bg-gradient-to-r from-green-100 to-emerald-100 text-green-800 border border-green-200' : 'bg-gradient-to-r from-amber-100 to-yellow-100 text-yellow-800 border border-yellow-200' }} shadow-sm">
                                            {{ $user->email_verified_at ? 'Verified' : 'Pending' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
