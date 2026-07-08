@extends('layouts.app')

@push('styles')
<style>
    /* Base Styles */
    * {
        box-sizing: border-box;
    }
    
    .chat-page {
        background: linear-gradient(135deg, #15803d 0%, #065f46 100%);
        min-height: 100vh;
        width: 100%;
        overflow-x: hidden;
    }
    
    .glass-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        width: 100%;
        max-width: 100%;
    }
    
    .chat-item {
        transition: all 0.3s ease;
        cursor: pointer;
        width: 100%;
        max-width: 100%;
    }
    
    .chat-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
        background: rgba(255, 255, 255, 1);
    }
    
    .unread-badge {
        animation: pulse 2s infinite;
        box-shadow: 0 0 20px rgba(239, 68, 68, 0.5);
    }
    
    .filter-tab {
        transition: all 0.3s ease;
        white-space: nowrap;
        flex-shrink: 0;
    }
    
    .filter-tab.active {
        background: linear-gradient(135deg, #15803d 0%, #065f46 100%) !important;
        color: white !important;
        box-shadow: 0 4px 15px rgba(21, 128, 61, 0.3);
    }
    
    .filter-tab.active:hover {
        background: linear-gradient(135deg, #15803d 0%, #065f46 100%) !important;
        color: white !important;
    }
    
    .filter-tabs-container {
        overflow-x: auto;
        scrollbar-width: none;
        -ms-overflow-style: none;
        width: 100%;
        max-width: 100%;
    }
    
    .filter-tabs-container::-webkit-scrollbar {
        display: none;
    }
    
    /* Prevent horizontal overflow */
    .container {
        width: 100%;
        max-width: 100%;
        overflow-x: hidden;
    }
    
    /* Mobile Responsive */
    @media (max-width: 768px) {
        .chat-page {
            padding: 0;
            margin: 0;
        }
        
        .container {
            padding-left: 1rem;
            padding-right: 1rem;
        }
        
        /* Ensure all form elements are properly sized */
        input, select, button {
            min-width: 0;
            max-width: 100%;
        }
        
        /* Fix button text wrapping */
        button {
            white-space: nowrap;
        }
        
        /* Ensure proper flex behavior */
        .flex {
            min-width: 0;
        }
        
        .flex-1 {
            min-width: 0;
            flex: 1 1 0%;
        }
        
        /* Header responsive layout */
        .space-y-3 > * + * {
            margin-top: 0.75rem;
        }
        
        /* Ensure proper spacing on mobile */
        @media (min-width: 768px) {
            .space-y-3 > * + * {
                margin-top: 0;
            }
        }
        
        .filter-tab {
            font-size: 0.625rem;
            padding: 0.375rem 0.5rem;
            min-width: auto;
            flex-shrink: 0;
        }
        
        .filter-tab i {
            display: none;
        }
        
        /* Make tabs more compact on mobile */
        .filter-tab span:not(.bg-white) {
            font-size: 0.625rem;
        }
        
        /* Hide count badges on very small screens */
        .filter-tab .bg-white {
            font-size: 0.5rem;
            padding: 0.125rem 0.25rem;
            margin-left: 0.25rem;
        }
        
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 0.75rem;
        }
        
        .chat-item-content {
            padding: 0.75rem;
        }
        
        .chat-avatar {
            width: 2.5rem;
            height: 2.5rem;
        }
        
        .chat-actions {
            display: none;
        }
        
        /* Fix text overflow */
        .chat-item h3,
        .chat-item h4,
        .chat-item p {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            max-width: 100%;
        }
        
        /* Fix flex items */
        .chat-item .flex {
            min-width: 0;
            flex-wrap: wrap;
        }
        
        .chat-item .flex-1 {
            min-width: 0;
            flex: 1 1 0%;
        }
        
        /* Fix badges on mobile */
        .mobile-badges {
            flex-wrap: wrap;
            gap: 0.25rem;
        }
        
        .mobile-badges span {
            font-size: 0.625rem;
            padding: 0.25rem 0.5rem;
        }
    }
    
    /* Extra small screens - ultra compact tabs */
    @media (max-width: 380px) {
        .filter-tab {
            padding: 0.25rem 0.25rem;
            font-size: 0.5rem;
            min-width: auto;
        }
        
        /* Show only count badges on ultra small screens */
        .filter-tab span:not(.bg-white) {
            display: none;
        }
        
        .filter-tab .bg-white {
            margin-left: 0;
            font-size: 0.5rem;
            padding: 0.125rem 0.25rem;
        }
        
        /* If no count, show minimal text */
        .xs\:hidden {
            display: none;
        }
    }

    @media (max-width: 480px) {
        .container {
            padding-left: 0.75rem;
            padding-right: 0.75rem;
        }
        
        .stats-grid {
            grid-template-columns: 1fr;
            gap: 0.5rem;
        }
        
        .filter-tab {
            padding: 0.25rem 0.375rem;
            font-size: 0.5rem;
            min-width: auto;
        }
        
        /* Hide text on ultra small screens, keep only badges */
        .filter-tab span:not(.bg-white) {
            display: none;
        }
        
        /* Make badges even smaller */
        .filter-tab .bg-white {
            font-size: 0.5rem;
            padding: 0.125rem 0.25rem;
            margin-left: 0.125rem;
        }
        
        .glass-card {
            margin-bottom: 0.75rem;
            padding: 0.75rem;
        }
        
        .chat-item-content {
            padding: 0.5rem;
        }
        
        .chat-avatar {
            width: 2rem;
            height: 2rem;
        }
        
        /* Ultra mobile text fixes */
        .chat-item h3 {
            font-size: 0.875rem;
        }
        
        .chat-item h4 {
            font-size: 0.75rem;
        }
        
        .chat-item p {
            font-size: 0.625rem;
        }
        
        /* Extra small screen header adjustments */
        .glass-card {
            padding: 0.75rem;
        }
        
        /* Make sure buttons don't overflow */
        button {
            font-size: 0.75rem;
            padding: 0.5rem 0.75rem;
        }
        
        /* Compact select elements */
        select {
            font-size: 0.75rem;
            padding: 0.5rem;
        }
        
        /* Compact input */
        input {
            font-size: 0.75rem;
            padding: 0.5rem 0.75rem;
            padding-left: 2rem;
        }
    }
    
    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.5; }
    }
</style>
@endpush

@section('content')
<div class="chat-page">
    <div class="container mx-auto px-4 py-4 md:py-8 w-full max-w-full">
        <!-- Flash Messages -->
        <x-flash-messages />

        <!-- Page Header -->
        <div class="glass-card rounded-2xl mb-4 md:mb-8 p-4 md:p-8 w-full">
            <!-- Title Section -->
            <div class="flex items-center space-x-3 md:space-x-6 mb-4 md:mb-6">
                <div class="bg-gradient-to-r from-green-600 to-emerald-600 p-3 md:p-4 rounded-2xl shadow-lg flex-shrink-0">
                    <i class="fas fa-comments text-white text-lg md:text-2xl"></i>
                </div>
                <div class="min-w-0 flex-1">
                    <h1 class="text-xl md:text-3xl font-bold text-gray-900 mb-1 md:mb-2 truncate">
                        @if(Auth::user()->isUser())
                            Customer Service
                        @else
                            Percakapan
                        @endif
                    </h1>
                    <p class="text-gray-600 text-xs md:text-lg truncate">
                        @if(Auth::user()->isUser())
                            Hubungi tim support untuk bantuan pesanan, pembayaran, dan layanan lainnya
                        @else
                            Kelola semua percakapan dan pesan Anda
                        @endif
                    </p>
                </div>
            </div>

            <!-- Controls Section -->
            <div class="space-y-3 md:space-y-0 md:flex md:items-center md:justify-between md:space-x-4">
                <!-- Search -->
                <div class="relative w-full md:w-auto md:flex-1 md:max-w-md">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                    <input type="text" id="search-conversations"
                           class="w-full pl-12 pr-4 py-3 bg-white bg-opacity-80 border border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all shadow-sm"
                           placeholder="Cari percakapan...">
                </div>

                @if(Auth::user()->isAdmin() || Auth::user()->isOperator())
                <!-- Filters and Button Container for Admin/Operator -->
                <div class="flex flex-col space-y-3 md:space-y-0 md:flex-row md:items-center md:space-x-3">
                    <!-- Filters Row -->
                    <div class="flex space-x-2 md:space-x-3">
                        <select id="status-filter"
                                class="flex-1 md:flex-none px-3 md:px-4 py-3 bg-white bg-opacity-80 border border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all shadow-sm text-sm">
                            <option value="">Semua Status</option>
                            <option value="open">Aktif</option>
                            <option value="closed">Selesai</option>
                        </select>

                        <select id="user-type-filter"
                                class="flex-1 md:flex-none px-3 md:px-4 py-3 bg-white bg-opacity-80 border border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all shadow-sm text-sm">
                            <option value="">Semua Pesan</option>
                            <option value="guest">Pesan Guest</option>
                            <option value="user">Pesan User</option>
                        </select>
                    </div>

                    <!-- New Chat Button -->
                    <button onclick="openChatModal()"
                            class="w-full md:w-auto inline-flex items-center justify-center px-4 md:px-6 py-3 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-1 text-sm md:text-base">
                        <i class="fas fa-plus mr-2"></i>
                        <span class="hidden sm:inline">Percakapan Baru</span>
                        <span class="sm:hidden">Baru</span>
                    </button>
                </div>
                @else
                <!-- New Chat Button for Regular Users -->
                <div class="flex justify-end">
                    <button onclick="openChatModal()"
                            class="w-full md:w-auto inline-flex items-center justify-center px-4 md:px-6 py-3 bg-gradient-to-r from-green-600 to-green-700 text-white rounded-xl hover:from-green-700 hover:to-green-800 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-1 text-sm md:text-base">
                        <i class="fas fa-plus mr-2"></i>
                        <span class="hidden sm:inline">Hubungi Customer Service</span>
                        <span class="sm:hidden">CS</span>
                    </button>
                </div>
                @endif
            </div>
        </div>

        <!-- Filter Tabs -->
        @if(Auth::user()->isAdmin() || Auth::user()->isOperator())
        <div class="glass-card rounded-2xl mb-6 md:mb-8 p-2 w-full">
            <div class="filter-tabs-container w-full">
                <nav class="flex space-x-1 md:space-x-2 min-w-max w-full" aria-label="Tabs">
                    <a href="{{ route('chat.index', ['filter' => 'all']) }}" 
                       class="filter-tab px-2 md:px-6 py-2 md:py-3 rounded-xl font-medium text-xs md:text-sm transition-all {{ $filter === 'all' ? 'active' : 'text-gray-600 hover:text-gray-800 hover:bg-white hover:bg-opacity-50' }}"
                       title="Semua Percakapan">
                        <i class="fas fa-list mr-1 md:mr-2 hidden md:inline"></i>
                        <span class="hidden sm:inline">Semua</span>
                        <span class="sm:hidden hidden xs:inline">All</span>
                        @if($stats['total'] > 0)
                            <span class="ml-1 md:ml-2 bg-white bg-opacity-20 text-current py-0.5 px-1 md:px-3 rounded-full text-xs font-semibold">{{ $stats['total'] }}</span>
                        @endif
                    </a>
                    
                    <a href="{{ route('chat.index', ['filter' => 'guest']) }}" 
                       class="filter-tab px-2 md:px-6 py-2 md:py-3 rounded-xl font-medium text-xs md:text-sm transition-all {{ $filter === 'guest' ? 'active' : 'text-gray-600 hover:text-gray-800 hover:bg-white hover:bg-opacity-50' }}"
                       title="Pesan Guest">
                        <i class="fas fa-user-secret mr-1 md:mr-2 hidden md:inline"></i>
                        <span class="hidden sm:inline">Guest</span>
                        <span class="sm:hidden hidden xs:inline">G</span>
                        @if($stats['guest'] > 0)
                            <span class="ml-1 md:ml-2 bg-white bg-opacity-20 text-current py-0.5 px-1 md:px-3 rounded-full text-xs font-semibold">{{ $stats['guest'] }}</span>
                        @endif
                    </a>

                    @if(Auth::user()->isAdmin())
                    <a href="{{ route('chat.index', ['filter' => 'admin_user']) }}" 
                       class="filter-tab px-2 md:px-6 py-2 md:py-3 rounded-xl font-medium text-xs md:text-sm transition-all {{ $filter === 'admin_user' ? 'active' : 'text-gray-600 hover:text-gray-800 hover:bg-white hover:bg-opacity-50' }}"
                       title="Admin ↔ User">
                        <i class="fas fa-user-shield mr-1 md:mr-2 hidden md:inline"></i>
                        <span class="hidden sm:inline">Admin ↔ User</span>
                        <span class="sm:hidden hidden xs:inline">A↔U</span>
                        @if($stats['admin_user'] > 0)
                            <span class="ml-1 md:ml-2 bg-white bg-opacity-20 text-current py-0.5 px-1 md:px-3 rounded-full text-xs font-semibold">{{ $stats['admin_user'] }}</span>
                        @endif
                    </a>
                    @endif

                    <a href="{{ route('chat.index', ['filter' => 'operator_user']) }}" 
                       class="filter-tab px-2 md:px-6 py-2 md:py-3 rounded-xl font-medium text-xs md:text-sm transition-all {{ $filter === 'operator_user' ? 'active' : 'text-gray-600 hover:text-gray-800 hover:bg-white hover:bg-opacity-50' }}"
                       title="Operator ↔ User">
                        <i class="fas fa-headset mr-1 md:mr-2 hidden md:inline"></i>
                        <span class="hidden sm:inline">Operator ↔ User</span>
                        <span class="sm:hidden hidden xs:inline">O↔U</span>
                        @if($stats['operator_user'] > 0)
                            <span class="ml-1 md:ml-2 bg-white bg-opacity-20 text-current py-0.5 px-1 md:px-3 rounded-full text-xs font-semibold">{{ $stats['operator_user'] }}</span>
                        @endif
                    </a>

                    @if(Auth::user()->isAdmin() || Auth::user()->isOperator())
                    <a href="{{ route('chat.index', ['filter' => 'internal']) }}" 
                       class="filter-tab px-2 md:px-6 py-2 md:py-3 rounded-xl font-medium text-xs md:text-sm transition-all {{ $filter === 'internal' ? 'active' : 'text-gray-600 hover:text-gray-800 hover:bg-white hover:bg-opacity-50' }}"
                       title="Internal Chat">
                        <i class="fas fa-users mr-1 md:mr-2 hidden md:inline"></i>
                        <span class="hidden sm:inline">Internal</span>
                        <span class="sm:hidden hidden xs:inline">Int</span>
                        @if($stats['internal'] > 0)
                            <span class="ml-1 md:ml-2 bg-white bg-opacity-20 text-current py-0.5 px-1 md:px-3 rounded-full text-xs font-semibold">{{ $stats['internal'] }}</span>
                        @endif
                    </a>
                    @endif
                </nav>
            </div>
        </div>
        @endif

        <!-- Stats Cards -->
        @if(Auth::user()->isAdmin() || Auth::user()->isOperator())
        <div class="stats-grid grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6 mb-6 md:mb-8">
            <div class="glass-card p-6 rounded-2xl hover:transform hover:-translate-y-1 transition-all">
                <div class="flex items-center">
                    <div class="bg-gradient-to-r from-green-500 to-green-600 p-3 rounded-xl mr-4 shadow-lg">
                        <i class="fas fa-comments text-white text-lg"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-1">Total Percakapan</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['total'] }}</p>
                    </div>
                </div>
            </div>
            <div class="glass-card p-6 rounded-2xl hover:transform hover:-translate-y-1 transition-all">
                <div class="flex items-center">
                    <div class="bg-gradient-to-r from-red-500 to-red-600 p-3 rounded-xl mr-4 shadow-lg">
                        <i class="fas fa-bell text-white text-lg"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-1">Belum Dibaca</p>
                        <p class="text-2xl font-bold text-red-600">{{ $stats['unread'] }}</p>
                    </div>
                </div>
            </div>
            <div class="glass-card p-6 rounded-2xl hover:transform hover:-translate-y-1 transition-all">
                <div class="flex items-center">
                    <div class="bg-gradient-to-r from-green-500 to-green-600 p-3 rounded-xl mr-4 shadow-lg">
                        <i class="fas fa-check-circle text-white text-lg"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-1">Aktif</p>
                        <p class="text-2xl font-bold text-green-600">{{ $conversations->where('status', 'open')->count() }}</p>
                    </div>
                </div>
            </div>
            <div class="glass-card p-6 rounded-2xl hover:transform hover:-translate-y-1 transition-all">
                <div class="flex items-center">
                    <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 p-3 rounded-xl mr-4 shadow-lg">
                        <i class="fas fa-clock text-white text-lg"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-1">Hari Ini</p>
                        <p class="text-2xl font-bold text-yellow-600">{{ $conversations->where('created_at', '>=', today())->count() }}</p>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Chat List -->
        <div class="glass-card rounded-2xl overflow-hidden">
            <!-- Search Results Info -->
            <div id="search-info" class="hidden px-6 py-4 bg-green-50 border-b text-sm text-green-700">
                <i class="fas fa-info-circle mr-2"></i>
                <span id="search-results-text"></span>
                <button id="clear-search" class="ml-2 text-green-600 hover:text-green-800 underline">Hapus filter</button>
            </div>

            @forelse ($conversations as $conversation)
                @php
                    $userRole = Auth::user()->isAdmin() ? 'admin' : (Auth::user()->isOperator() ? 'operator' : 'user');
                    $hasUnread = $conversation->{"has_unread_{$userRole}"};
                @endphp
                <div class="chat-item border-b border-gray-100 last:border-b-0 {{ $hasUnread ? 'bg-green-50' : '' }} w-full max-w-full" 
                     data-user-type="{{ $conversation->user ? 'user' : 'guest' }}"
                     data-status="{{ $conversation->status }}">
                    <div class="flex items-center w-full max-w-full">
                        <a href="{{ route('chat.show', $conversation) }}" class="flex-1 block chat-item-content p-3 md:p-6 hover:bg-gray-50 transition-all duration-200 min-w-0">
                            <div class="flex items-start space-x-2 md:space-x-4 w-full max-w-full">
                                <!-- Avatar -->
                                <div class="flex-shrink-0 relative">
                                    @if(Auth::user()->isAdmin() || Auth::user()->isOperator())
                                        @if($conversation->user)
                                            <img src="{{ $conversation->user->profile_photo_url }}"
                                                 alt="{{ $conversation->user->name }}"
                                                 class="chat-avatar w-12 h-12 md:w-14 md:h-14 rounded-2xl object-cover border-2 border-gray-200 shadow-sm">
                                            @if($conversation->user->presence_status == 'Online')
                                                <div class="absolute -bottom-1 -right-1 w-3 h-3 md:w-4 md:h-4 bg-green-500 border-2 border-white rounded-full"></div>
                                            @endif
                                        @else
                                            <div class="chat-avatar w-12 h-12 md:w-14 md:h-14 bg-gradient-to-r from-orange-500 to-orange-600 rounded-2xl flex items-center justify-center border-2 border-gray-200 shadow-sm">
                                                <i class="fas fa-user-secret text-white text-sm md:text-lg"></i>
                                            </div>
                                            <div class="absolute -bottom-1 -right-1 w-3 h-3 md:w-4 md:h-4 bg-gray-400 border-2 border-white rounded-full"></div>
                                        @endif
                                    @else
                                        <div class="chat-avatar w-12 h-12 md:w-14 md:h-14 bg-gradient-to-r from-green-500 to-green-600 rounded-2xl flex items-center justify-center border-2 border-gray-200 shadow-sm">
                                            <i class="fas fa-headset text-white text-sm md:text-lg"></i>
                                        </div>
                                        <div class="absolute -bottom-1 -right-1 w-3 h-3 md:w-4 md:h-4 bg-green-500 border-2 border-white rounded-full"></div>
                                    @endif
                                </div>

                                <!-- Content -->
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-start justify-between mb-2">
                                        <!-- Name and Status -->
                                        <div class="flex-1 min-w-0">
                                            @if(Auth::user()->isAdmin() || Auth::user()->isOperator())
                                                @if($conversation->user)
                                                    <h3 class="text-base md:text-lg font-semibold text-gray-900 hover:text-green-600 transition-colors truncate">
                                                        {{ $conversation->user->name }}
                                                    </h3>
                                                @else
                                                    <h3 class="text-base md:text-lg font-semibold text-orange-600 truncate">
                                                        <i class="fas fa-user-secret mr-1 md:mr-2"></i>Guest User
                                                    </h3>
                                                @endif
                                            @else
                                                @php
                                                    $csName = 'Customer Service';
                                                    $csRole = '';
                                                    
                                                    // Use conversation type to determine default CS role
                                                    if ($conversation->type === 'admin_user') {
                                                        $csRole = 'Admin Support';
                                                    } elseif ($conversation->type === 'operator_user') {
                                                        $csRole = 'Customer Support';
                                                    }
                                                    
                                                    // Try to get CS name from latest message if it's from CS
                                                    $csUser = null;
                                                    if ($conversation->latestMessage && 
                                                        $conversation->latestMessage->user_id !== Auth::id() && 
                                                        $conversation->latestMessage->user) {
                                                        $csUser = $conversation->latestMessage->user;
                                                    } elseif ($conversation->messages->isNotEmpty()) {
                                                        // Use the CS message we loaded
                                                        $csMessage = $conversation->messages->first();
                                                        if ($csMessage && $csMessage->user) {
                                                            $csUser = $csMessage->user;
                                                        }
                                                    }
                                                    
                                                    if ($csUser) {
                                                        $csName = $csUser->name;
                                                        
                                                        // Update role based on actual user role
                                                        if ($csUser->hasRole('admin')) {
                                                            $csRole = 'Admin Support';
                                                        } elseif ($csUser->hasRole('operator')) {
                                                            $csRole = 'Customer Support';
                                                        }
                                                    } else {
                                                        // Fallback based on conversation type
                                                        if ($conversation->type === 'admin_user') {
                                                            $csName = 'Admin Support';
                                                        } elseif ($conversation->type === 'operator_user') {
                                                            $csName = 'Customer Support';
                                                        }
                                                    }
                                                @endphp
                                                <h3 class="text-base md:text-lg font-semibold text-gray-900 truncate">{{ $csName }}</h3>
                                                @if($csRole)
                                                    <p class="text-xs text-gray-500 truncate">{{ $csRole }}</p>
                                                @endif
                                            @endif

                                            <!-- Mobile badges -->
                                            <div class="mobile-badges flex items-center flex-wrap gap-1 md:gap-2 mt-1">
                                                <!-- Status Badge -->
                                                <span class="inline-flex items-center px-1.5 md:px-3 py-0.5 md:py-1 rounded-full text-xs font-semibold {{ $conversation->status == 'open' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                                    <span class="w-1.5 h-1.5 md:w-2 md:h-2 {{ $conversation->status == 'open' ? 'bg-green-500' : 'bg-gray-500' }} rounded-full mr-1 md:mr-2"></span>
                                                    <span class="hidden sm:inline">{{ $conversation->status == 'open' ? 'Aktif' : 'Selesai' }}</span>
                                                    <span class="sm:hidden">{{ $conversation->status == 'open' ? 'A' : 'S' }}</span>
                                                </span>

                                                @if($conversation->is_important)
                                                <span class="inline-flex items-center px-1.5 md:px-3 py-0.5 md:py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">
                                                    <i class="fas fa-star mr-1 text-red-500"></i>
                                                    <span class="hidden sm:inline">Penting</span>
                                                </span>
                                                @endif
                                            </div>
                                        </div>

                                        <!-- Time -->
                                        <div class="flex items-center space-x-1 md:space-x-2 flex-shrink-0 ml-2">
                                            <span class="text-xs md:text-sm text-gray-500 font-medium">
                                                {{ $conversation->updated_at->diffForHumans() }}
                                            </span>
                                            <i class="fas fa-chevron-right text-gray-400 text-xs md:text-sm"></i>
                                        </div>
                                    </div>

                                    <!-- Subject -->
                                    <h4 class="text-sm md:text-base font-medium text-gray-800 mb-2 truncate">
                                        {{ $conversation->subject }}
                                    </h4>

                                    <!-- Last Message -->
                                    <div class="flex items-center justify-between">
                                        <div class="flex-1 min-w-0">
                                            @if ($conversation->latestMessage)
                                                <div class="flex items-start space-x-2">
                                                    @if($conversation->latestMessage->user_id == Auth::id())
                                                        <span class="text-sm text-gray-500 flex-shrink-0 font-medium">Anda:</span>
                                                    @else
                                                        <span class="text-sm text-gray-500 flex-shrink-0 font-medium">
                                                            {{ $conversation->latestMessage->user ? $conversation->latestMessage->user->name : 'Guest' }}:
                                                        </span>
                                                    @endif
                                                    <p class="text-sm text-gray-600 line-clamp-2">
                                                        {{ $conversation->latestMessage->body }}
                                                    </p>
                                                </div>
                                            @else
                                                <p class="text-sm text-gray-400 italic">Belum ada pesan</p>
                                            @endif
                                        </div>

                                        <!-- Unread indicator -->
                                        @if($hasUnread)
                                            <div class="flex-shrink-0 ml-4">
                                                <span class="inline-flex items-center justify-center w-6 h-6 bg-red-500 text-white text-xs font-bold rounded-full unread-badge">
                                                    !
                                                </span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </a>

                        <!-- Action Buttons -->
                        @if(Auth::user()->isAdmin() || Auth::user()->isOperator())
                        <div class="chat-actions flex items-center space-x-2 px-2 md:px-4">
                            <!-- Delete Button -->
                            <button type="button" 
                                    onclick="deleteConversation({{ $conversation->id }}, '{{ addslashes($conversation->subject) }}')"
                                    class="text-red-500 hover:text-red-700 p-1.5 md:p-2 rounded-full hover:bg-red-50 transition-colors"
                                    title="Hapus Percakapan">
                                <i class="fas fa-trash text-xs md:text-sm"></i>
                            </button>
                            <form id="delete-form-{{ $conversation->id }}" action="{{ route('chat.destroy', $conversation) }}" method="POST" style="display: none;">
                                @csrf
                                @method('DELETE')
                            </form>

                            <!-- Type Badge -->
                            <div class="text-xs">
                                @if($conversation->type === 'guest')
                                    <span class="bg-orange-100 text-orange-800 px-2 md:px-3 py-0.5 md:py-1 rounded-full font-medium">
                                        <span class="hidden sm:inline">Guest</span>
                                        <span class="sm:hidden">G</span>
                                    </span>
                                @elseif($conversation->type === 'admin_user')
                                    <span class="bg-green-100 text-green-800 px-2 md:px-3 py-0.5 md:py-1 rounded-full font-medium">
                                        <span class="hidden sm:inline">Admin</span>
                                        <span class="sm:hidden">A</span>
                                    </span>
                                @elseif($conversation->type === 'operator_user')
                                    <span class="bg-green-100 text-green-800 px-2 md:px-3 py-0.5 md:py-1 rounded-full font-medium">
                                        <span class="hidden sm:inline">Operator</span>
                                        <span class="sm:hidden">O</span>
                                    </span>
                                @elseif($conversation->type === 'internal')
                                    <span class="bg-emerald-100 text-emerald-800 px-2 md:px-3 py-0.5 md:py-1 rounded-full font-medium">
                                        <span class="hidden sm:inline">Internal</span>
                                        <span class="sm:hidden">I</span>
                                    </span>
                                @endif
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            @empty
                <!-- Empty State -->
                <div class="text-center py-16">
                    <div class="mx-auto w-24 h-24 bg-gradient-to-r from-gray-100 to-gray-200 rounded-full flex items-center justify-center mb-6">
                        <i class="fas fa-comments text-gray-400 text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">
                        @if(Auth::user()->isUser())
                            Belum Ada Riwayat Customer Service
                        @else
                            Belum Ada Percakapan
                        @endif
                    </h3>
                    <p class="text-gray-500 mb-6">
                        @if(Auth::user()->isUser())
                            Anda belum pernah menghubungi customer service. Mulai percakapan untuk mendapatkan bantuan dengan pesanan, pembayaran, atau pertanyaan lainnya.
                        @else
                            Anda belum memiliki percakapan apapun.
                        @endif
                    </p>

                    @if(Auth::user()->isUser())
                        <button onclick="openChatModal()"
                           class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-green-600 to-green-700 text-white rounded-xl hover:from-green-700 hover:to-green-800 transition-all shadow-lg hover:shadow-xl">
                            <i class="fas fa-headset mr-2"></i>
                            Hubungi Customer Service
                        </button>
                    @endif
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($conversations instanceof \Illuminate\Pagination\LengthAwarePaginator && $conversations->hasPages())
            <div class="mt-8 w-full">
                {{ $conversations->links() }}
            </div>

        @endif
    </div>
</div>

<!-- New Chat Modal -->
@if(Auth::user())
<div id="newChatModal" class="hidden fixed inset-0 flex items-center justify-center bg-black bg-opacity-60 backdrop-blur-sm z-50 p-4">
    <div class="bg-white bg-opacity-98 backdrop-blur-20 rounded-2xl shadow-2xl w-full max-w-lg transform transition-all">
        <div class="bg-gradient-to-r from-green-600 to-emerald-600 text-white p-6 rounded-t-2xl">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="bg-white bg-opacity-20 p-2 rounded-lg">
                        <i class="fas fa-comments text-xl"></i>
                    </div>
                    <h3 class="text-xl font-bold">
                        @if(Auth::user()->isUser())
                            Hubungi Customer Service
                        @else
                            Buat Percakapan Baru
                        @endif
                    </h3>
                </div>
                <button onclick="closeChatModal()" class="text-white hover:text-gray-200 transition-colors">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
        </div>
        
        <form method="POST" action="{{ route('chat.store') }}" class="p-6">
            @csrf

            @if(!Auth::user()->hasVerifiedEmail())
                <div class="mb-4 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-triangle text-yellow-400"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-yellow-800">
                                Email Belum Diverifikasi
                            </h3>
                            <div class="mt-2 text-sm text-yellow-700">
                                <p>Anda perlu memverifikasi email terlebih dahulu untuk mengirim pesan.</p>
                                <div class="mt-3">
                                    <a href="{{ route('verification.notice') }}" 
                                       class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-yellow-800 bg-yellow-100 hover:bg-yellow-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                                        <i class="fas fa-envelope-open mr-2"></i>
                                        Verifikasi Email Sekarang
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Judul -->
            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-heading text-green-500 mr-2"></i>Judul Percakapan
                </label>
                <input type="text" name="subject" value="{{ old('subject') }}"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all @error('subject') border-red-500 @enderror" 
                       placeholder="Masukkan judul percakapan..." required>
                @error('subject')
                    <p class="text-red-500 text-sm mt-1"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                @enderror
            </div>

            <!-- Pesan -->
            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-message text-green-500 mr-2"></i>Pesan
                </label>
                <textarea name="message" rows="4"
                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all resize-none @error('message') border-red-500 @enderror" 
                          placeholder="Tulis pesan Anda..." required>{{ old('message') }}</textarea>
                @error('message')
                    <p class="text-red-500 text-sm mt-1"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                @enderror
            </div>

            <!-- Pilih Penerima -->
            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-user text-green-500 mr-2"></i>
                    @if(Auth::user()->isUser())
                        Pilih Customer Service
                    @else
                        Pilih Penerima
                    @endif
                </label>
                <select name="recipient_id" id="recipient_id"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all @error('recipient_id') border-red-500 @enderror" 
                        required>
                    <option value="">
                        @if(Auth::user()->isUser())
                            -- Pilih Customer Service --
                        @else
                            -- Pilih Penerima --
                        @endif
                    </option>
                    @if(isset($usersByRole))
                        @foreach($usersByRole as $role => $users)
                            @if(count($users) > 0)
                                <optgroup label="
                                    @if(Auth::user()->isUser())
                                        @if($role === 'admin')
                                            Admin Customer Service ({{ count($users) }} orang)
                                        @elseif($role === 'operator')
                                            Operator Customer Service ({{ count($users) }} orang)
                                        @else
                                            {{ ucfirst($role) }} ({{ count($users) }} orang)
                                        @endif
                                    @else
                                        {{ ucfirst($role) }} ({{ count($users) }} orang)
                                    @endif
                                ">
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" {{ old('recipient_id') == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }} ({{ $user->email }})
                                        </option>
                                    @endforeach
                                </optgroup>
                            @endif
                        @endforeach
                    @else
                        <option value="">Data pengguna tidak tersedia</option>
                    @endif
                </select>
                @error('recipient_id')
                    <p class="text-red-500 text-sm mt-1"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                @enderror
            </div>

            <!-- Buttons -->
            <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
                <button type="button" onclick="closeChatModal()"
                        class="px-6 py-2.5 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors">
                    <i class="fas fa-times mr-2"></i>Batal
                </button>
                <button type="submit"
                        @if(!Auth::user()->hasVerifiedEmail())
                            disabled
                            class="px-6 py-2.5 bg-gray-400 text-white rounded-lg cursor-not-allowed"
                        @else
                            class="px-6 py-2.5 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-lg hover:from-green-700 hover:to-emerald-700 transition-all shadow-lg hover:shadow-xl"
                        @endif>
                    <i class="fas fa-paper-plane mr-2"></i>
                    @if(!Auth::user()->hasVerifiedEmail())
                        Email Belum Diverifikasi
                    @else
                        Kirim Pesan
                    @endif
                </button>
            </div>
        </form>
    </div>
</div>
@endif

@push('scripts')
<script>
    // Simple delete confirmation with single modal
    function deleteConversation(conversationId, conversationSubject) {
        // Update modal content
        document.getElementById('deleteModalSubject').textContent = conversationSubject;
        
        // Set the form action for the modal
        const deleteForm = document.getElementById('modalDeleteForm');
        const originalForm = document.getElementById('delete-form-' + conversationId);
        
        if (originalForm) {
            deleteForm.action = originalForm.action;
        }
        
        // Show modal
        document.getElementById('deleteConfirmModal').classList.remove('hidden');
    }
    
    function closeDeleteModal() {
        document.getElementById('deleteConfirmModal').classList.add('hidden');
    }
    
    // Close modal when clicking outside
    document.getElementById('deleteConfirmModal')?.addEventListener('click', function(e) {
        if (e.target === this) {
            closeDeleteModal();
        }
    });

    // Modal functions
    function openChatModal() {
        document.getElementById('newChatModal').classList.remove('hidden');
    }

    function closeChatModal() {
        document.getElementById('newChatModal').classList.add('hidden');
        document.querySelector('#newChatModal form').reset();
    }

    // Auto-open modal if there are validation errors
    @if($errors->any())
        document.addEventListener('DOMContentLoaded', function() {
            openChatModal();
        });
    @endif

    // Close modal when clicking outside
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('newChatModal');
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                closeChatModal();
            }
        });

        // Search and filter functionality
        const searchInput = document.getElementById('search-conversations');
        const statusFilter = document.getElementById('status-filter');
        const userTypeFilter = document.getElementById('user-type-filter');
        const searchInfo = document.getElementById('search-info');
        const searchResultsText = document.getElementById('search-results-text');
        const clearSearchBtn = document.getElementById('clear-search');
        const chatItems = document.querySelectorAll('.chat-item');

        function filterConversations() {
            const searchTerm = searchInput ? searchInput.value.toLowerCase() : '';
            const statusValue = statusFilter ? statusFilter.value : '';
            const userTypeValue = userTypeFilter ? userTypeFilter.value : '';
            let visibleCount = 0;
            let totalCount = chatItems.length;

            chatItems.forEach(item => {
                const conversationText = item.textContent.toLowerCase();
                const itemStatus = item.dataset.status;
                const itemUserType = item.dataset.userType;
                
                const searchMatch = !searchTerm || conversationText.includes(searchTerm);
                const statusMatch = !statusValue || itemStatus === statusValue;
                const userTypeMatch = !userTypeValue || itemUserType === userTypeValue;

                if (searchMatch && statusMatch && userTypeMatch) {
                    item.style.display = 'block';
                    visibleCount++;
                } else {
                    item.style.display = 'none';
                }
            });

            // Update search info
            if (searchTerm || statusValue || userTypeValue) {
                if (searchInfo) {
                    searchInfo.classList.remove('hidden');
                    if (searchResultsText) {
                        searchResultsText.textContent = `Menampilkan ${visibleCount} dari ${totalCount} percakapan`;
                    }
                }
            } else {
                if (searchInfo) {
                    searchInfo.classList.add('hidden');
                }
            }
        }

        // Event listeners
        if (searchInput) searchInput.addEventListener('input', filterConversations);
        if (statusFilter) statusFilter.addEventListener('change', filterConversations);
        if (userTypeFilter) userTypeFilter.addEventListener('change', filterConversations);
        
        if (clearSearchBtn) {
            clearSearchBtn.addEventListener('click', () => {
                if (searchInput) searchInput.value = '';
                if (statusFilter) statusFilter.value = '';
                if (userTypeFilter) userTypeFilter.value = '';
                filterConversations();
            });
        }

        // Auto refresh every 30 seconds (but not during form submissions)
        let isFormSubmitting = false;
        let refreshInterval;
        
        // Track form submissions to prevent refresh during delete
        document.addEventListener('submit', function(e) {
            isFormSubmitting = true;
            // Clear the refresh interval when form is being submitted
            if (refreshInterval) {
                clearInterval(refreshInterval);
            }
        });
        
        // Prevent page refresh during form submission
        window.addEventListener('beforeunload', function(e) {
            if (isFormSubmitting) {
                // Don't show confirmation dialog, just let the form submit
                return;
            }
        });
        
        refreshInterval = setInterval(function() {
            if (document.visibilityState === 'visible' && !isFormSubmitting) {
                location.reload();
            }
        }, 30000);
    });
</script>
@endpush

<!-- Delete Confirmation Modal -->
<div id="deleteConfirmModal" class="hidden fixed inset-0 bg-black bg-opacity-60 backdrop-blur-sm z-50 flex items-center justify-center p-4">
    <div class="bg-white bg-opacity-98 backdrop-blur-20 rounded-2xl shadow-2xl max-w-md w-full transform transition-all">
        <div class="bg-gradient-to-r from-red-600 to-red-700 text-white p-4 md:p-6 rounded-t-2xl">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="bg-white bg-opacity-20 p-2 rounded-lg">
                        <i class="fas fa-exclamation-triangle text-lg md:text-xl"></i>
                    </div>
                    <h3 class="text-lg md:text-xl font-bold">Hapus Percakapan</h3>
                </div>
                <button onclick="closeDeleteModal()" class="text-white hover:text-gray-200 transition-colors">
                    <i class="fas fa-times text-lg md:text-xl"></i>
                </button>
            </div>
        </div>
        
        <div class="p-4 md:p-6">
            <div class="mb-6">
                <div class="flex items-start space-x-4">
                    <div class="flex-shrink-0">
                        <div class="bg-red-100 p-3 rounded-full">
                            <i class="fas fa-trash text-red-600 text-xl"></i>
                        </div>
                    </div>
                    <div class="flex-1">
                        <h4 class="text-lg font-semibold text-gray-900 mb-2">Konfirmasi Penghapusan</h4>
                        <p class="text-gray-600 mb-4">
                            Apakah Anda yakin ingin menghapus percakapan ini? Tindakan ini tidak dapat dibatalkan dan semua pesan akan hilang permanen.
                        </p>
                        <div class="bg-red-50 border border-red-200 rounded-lg p-3">
                            <p class="text-sm text-red-700">
                                <i class="fas fa-info-circle mr-2"></i>
                                <strong>Percakapan:</strong> <span id="deleteModalSubject"></span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <form id="modalDeleteForm" method="POST" class="flex flex-col md:flex-row justify-end space-y-3 md:space-y-0 md:space-x-3 pt-4 border-t border-gray-200">
                @csrf
                @method('DELETE')
                <button type="button" onclick="closeDeleteModal()"
                        class="w-full md:w-auto px-6 py-2.5 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors">
                    <i class="fas fa-times mr-2"></i>Batal
                </button>
                <button type="submit"
                        class="w-full md:w-auto px-6 py-2.5 bg-gradient-to-r from-red-600 to-red-700 text-white rounded-lg hover:from-red-700 hover:to-red-800 transition-all shadow-lg hover:shadow-xl">
                    <i class="fas fa-trash mr-2"></i>Ya, Hapus Percakapan
                </button>
            </form>
        </div>
    </div>
</div>
@endsection