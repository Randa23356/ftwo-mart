@extends('layouts.app')

@push('styles')
<style>
    /* Base Styles - Consistent with Index */
    * {
        box-sizing: border-box;
    }
    
    .chat-show-page {
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
    
    .chat-container {
        height: calc(100vh - 280px);
        min-height: 500px;
        max-height: 700px;
    }
    
    .chat-messages {
        height: calc(100% - 140px);
        overflow-y: auto;
        scroll-behavior: smooth;
        background: rgba(248, 250, 252, 0.8);
    }
    
    .chat-bubble-user {
        background: linear-gradient(135deg, #15803d 0%, #065f46 100%);
        color: white;
        box-shadow: 0 4px 15px rgba(21, 128, 61, 0.3);
    }
    
    .chat-bubble-other {
        background: rgba(255, 255, 255, 0.95);
        color: #1f2937;
        border: 1px solid rgba(255, 255, 255, 0.3);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }
    
    .chat-bubble-guest {
        background: linear-gradient(135deg, #f97316 0%, #ea580c 100%);
        color: white;
        box-shadow: 0 4px 15px rgba(249, 115, 22, 0.3);
    }
    
    .message-wrapper {
        animation: slideUp 0.4s ease-out;
        transition: all 0.3s ease;
    }
    
    .message-wrapper:hover {
        transform: translateY(-1px);
    }
    
    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .typing-indicator {
        display: flex;
        align-items: center;
        padding: 12px 20px;
        background: rgba(255, 255, 255, 0.9);
        border-radius: 20px;
        max-width: fit-content;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }
    
    .typing-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: linear-gradient(135deg, #15803d 0%, #065f46 100%);
        animation: typing 1.4s infinite;
        margin: 0 2px;
    }
    
    .typing-dot:nth-child(1) { animation-delay: 0s; }
    .typing-dot:nth-child(2) { animation-delay: 0.2s; }
    .typing-dot:nth-child(3) { animation-delay: 0.4s; }

    @keyframes typing {
        0%, 60%, 100% { transform: translateY(0); opacity: 0.4; }
        30% { transform: translateY(-12px); opacity: 1; }
    }
    
    .online-indicator {
        position: absolute;
        bottom: 2px;
        right: 2px;
        width: 12px;
        height: 12px;
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        border: 2px solid white;
        border-radius: 50%;
        box-shadow: 0 0 10px rgba(16, 185, 129, 0.5);
    }
    
    .action-button {
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.3);
        transition: all 0.3s ease;
    }
    
    .action-button:hover {
        background: rgba(255, 255, 255, 1);
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    }
    
    .message-input-area {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.3);
    }
    
    /* Mobile Responsive */
    @media (max-width: 768px) {
        .chat-show-page {
            padding: 0;
            margin: 0;
        }
        
        .container {
            padding-left: 1rem;
            padding-right: 1rem;
        }
        
        .chat-container {
            height: calc(100vh - 200px);
            min-height: 400px;
        }
        
        .chat-header-content {
            flex-direction: column;
            gap: 1rem;
            align-items: stretch;
        }
        
        .chat-user-info {
            flex-direction: column;
            text-align: center;
            gap: 0.75rem;
        }
        
        .chat-avatar {
            width: 3rem;
            height: 3rem;
        }
        
        .online-indicator {
            width: 10px;
            height: 10px;
        }
        
        .chat-messages {
            padding: 0.75rem;
        }
        
        .message-wrapper {
            margin-bottom: 0.75rem;
        }
        
        .chat-bubble-user,
        .chat-bubble-other,
        .chat-bubble-guest {
            max-width: 85%;
            padding: 0.75rem;
            font-size: 0.875rem;
        }
        
        .quick-actions {
            flex-wrap: wrap;
            gap: 0.5rem;
        }
        
        .quick-action-btn {
            font-size: 0.75rem;
            padding: 0.375rem 0.75rem;
        }
    }
    
    @media (max-width: 480px) {
        .container {
            padding-left: 0.75rem;
            padding-right: 0.75rem;
        }
        
        .glass-card {
            margin-bottom: 0.75rem;
            padding: 0.75rem;
        }
        
        .chat-container {
            height: calc(100vh - 180px);
            min-height: 350px;
        }
        
        .chat-avatar {
            width: 2.5rem;
            height: 2.5rem;
        }
        
        .chat-messages {
            padding: 0.5rem;
        }
        
        .chat-bubble-user,
        .chat-bubble-other,
        .chat-bubble-guest {
            max-width: 90%;
            padding: 0.5rem;
            font-size: 0.8rem;
        }
        
        .message-input-area {
            padding: 0.75rem;
        }
        
        .message-input-area textarea {
            font-size: 0.875rem;
            padding: 0.75rem;
        }
        
        .send-button {
            padding: 0.75rem;
        }
    }
    
    /* Prevent horizontal overflow */
    .container {
        width: 100%;
        max-width: 100%;
        overflow-x: hidden;
    }
    
    /* Ensure proper flex behavior */
    .flex {
        min-width: 0;
    }
    
    .flex-1 {
        min-width: 0;
        flex: 1 1 0%;
    }
</style>
@endpush

@section('content')
<div class="chat-show-page">
    <div class="container mx-auto px-4 py-4 md:py-8 w-full max-w-full">
        <!-- Flash Messages -->
        <x-flash-messages />

        <!-- Header -->
        <div class="glass-card rounded-2xl mb-4 md:mb-8 p-4 md:p-6 w-full">
            <div class="chat-header-content flex items-start justify-between">
                <!-- Back Button & User Info -->
                <div class="flex items-center space-x-3 md:space-x-6 flex-1 min-w-0">
                    <a href="{{ route('chat.index') }}"
                       class="p-2 md:p-3 rounded-xl action-button hover:bg-white transition-all flex-shrink-0">
                        <i class="fas fa-arrow-left text-gray-600 text-sm md:text-lg"></i>
                    </a>
                    
                    <div class="chat-user-info flex items-center space-x-3 md:space-x-4 flex-1 min-w-0">
                        <!-- Avatar -->
                        <div class="relative flex-shrink-0">
                            @if(Auth::user()->isAdmin() || Auth::user()->isOperator())
                                @if($conversation->user)
                                    <img src="{{ $conversation->user->profile_photo_url }}"
                                         alt="{{ $conversation->user->name }}"
                                         class="chat-avatar w-12 h-12 md:w-16 md:h-16 rounded-2xl object-cover border-2 border-white shadow-lg">
                                    @if($conversation->user->presence_status == 'Online')
                                        <div class="online-indicator" id="presence-indicator"></div>
                                    @else
                                        <div class="absolute -bottom-1 -right-1 w-3 h-3 md:w-4 md:h-4 bg-gray-400 border-2 border-white rounded-full" id="presence-indicator"></div>
                                    @endif
                                @else
                                    <div class="chat-avatar w-12 h-12 md:w-16 md:h-16 bg-gradient-to-r from-orange-500 to-orange-600 rounded-2xl flex items-center justify-center border-2 border-white shadow-lg">
                                        <i class="fas fa-user-secret text-white text-sm md:text-xl"></i>
                                    </div>
                                    <div class="absolute -bottom-1 -right-1 w-3 h-3 md:w-4 md:h-4 bg-gray-400 border-2 border-white rounded-full"></div>
                                @endif
                            @else
                                <div class="chat-avatar w-12 h-12 md:w-16 md:h-16 bg-gradient-to-r from-green-500 to-green-600 rounded-2xl flex items-center justify-center border-2 border-white shadow-lg">
                                    <i class="fas fa-headset text-white text-sm md:text-xl"></i>
                                </div>
                                <div class="online-indicator"></div>
                            @endif
                        </div>

                        <!-- User Details -->
                        <div class="flex-1 min-w-0">
                            @if(Auth::user()->isAdmin() || Auth::user()->isOperator())
                                @if($conversation->user)
                                    <h3 class="text-lg md:text-xl font-bold text-gray-900 hover:text-green-600 mb-1 truncate">
                                        {{ $conversation->user->name }}
                                    </h3>
                                    <p class="text-xs md:text-sm font-medium text-gray-600 truncate" id="presence-status-text">{{ $conversation->user->presence_status }}</p>
                                @else
                                    <h3 class="text-lg md:text-xl font-bold text-orange-600 mb-1 truncate">
                                        <i class="fas fa-user-secret mr-1 md:mr-2"></i>
                                        Guest User
                                    </h3>
                                    <p class="text-xs md:text-sm font-medium text-gray-600 truncate">Pesan dari pengunjung</p>
                                @endif
                            @else
                                @php
                                    $csName = 'Customer Service';
                                    $csRole = 'Sedang Online';
                                    
                                    // Get CS info from any message (not just latest)
                                    $csMessage = null;
                                    foreach ($conversation->messages as $message) {
                                        if ($message->user_id !== Auth::id() && $message->user_id !== null && $message->user) {
                                            $csMessage = $message;
                                            break;
                                        }
                                    }
                                    
                                    if ($csMessage && $csMessage->user) {
                                        $csUser = $csMessage->user;
                                        $csName = $csUser->name;
                                        
                                        if ($csUser->hasRole('admin')) {
                                            $csRole = 'Admin Support';
                                        } elseif ($csUser->hasRole('operator')) {
                                            $csRole = 'Customer Support';
                                        }
                                    } else {
                                        // Fallback based on conversation type
                                        if ($conversation->type === 'admin_user') {
                                            $csName = 'Admin Support';
                                            $csRole = 'Admin Support';
                                        } elseif ($conversation->type === 'operator_user') {
                                            $csName = 'Customer Support';
                                            $csRole = 'Customer Support';
                                        }
                                    }
                                @endphp
                                <h3 class="text-lg md:text-xl font-bold text-gray-900 mb-1 truncate">{{ $csName }}</h3>
                                <p class="text-xs md:text-sm font-medium text-gray-600 truncate">{{ $csRole }}</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Status Badges -->
                <div class="flex flex-col items-end space-y-2 flex-shrink-0 ml-2">
                    <span class="inline-flex items-center px-2 md:px-4 py-1 md:py-2 rounded-xl text-xs md:text-sm font-semibold {{ $conversation->status == 'open' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }} shadow-sm">
                        <span class="w-1.5 h-1.5 md:w-2 md:h-2 {{ $conversation->status == 'open' ? 'bg-green-500' : 'bg-gray-500' }} rounded-full mr-1 md:mr-2"></span>
                        {{ $conversation->status == 'open' ? 'Aktif' : 'Selesai' }}
                    </span>
                    
                    @if($conversation->is_important)
                    <span class="inline-flex items-center px-2 md:px-4 py-1 md:py-2 rounded-xl text-xs md:text-sm font-semibold bg-red-100 text-red-800 shadow-sm">
                        <i class="fas fa-star mr-1 md:mr-2 text-red-500"></i>
                        Penting
                    </span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Chat Container -->
        <div class="glass-card chat-container rounded-2xl overflow-hidden shadow-2xl w-full">
            <!-- Subject Header -->
            <div class="bg-gradient-to-r from-green-600 to-emerald-600 text-white p-4 md:p-6">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-2 md:space-y-0">
                    <div class="flex-1 min-w-0">
                        <h4 class="font-bold text-lg md:text-xl mb-1 md:mb-2 truncate">{{ $conversation->subject }}</h4>
                        <p class="text-green-100 text-xs md:text-sm font-medium">
                            <i class="fas fa-clock mr-1 md:mr-2"></i>
                            Percakapan dimulai {{ $conversation->created_at->diffForHumans() }}
                        </p>
                    </div>
                    <div class="flex items-center justify-between md:flex-col md:items-end text-green-100 text-xs md:text-sm space-x-4 md:space-x-0 md:space-y-1">
                        <p class="font-semibold">ID: #{{ $conversation->id }}</p>
                        <p class="bg-white bg-opacity-20 px-2 md:px-3 py-1 rounded-full">
                            <i class="fas fa-comments mr-1"></i>
                            {{ $conversation->messages()->count() }} pesan
                        </p>
                    </div>
                </div>
            </div>

            <!-- Messages Area -->
            <div id="message-container" class="chat-messages p-3 md:p-4 space-y-3 md:space-y-4">
                <!-- Messages will be dynamically loaded here -->
                <div id="loading-messages" class="flex justify-center items-center py-8">
                    <div class="flex space-x-2">
                        <div class="w-2 h-2 md:w-3 md:h-3 bg-green-500 rounded-full animate-bounce"></div>
                        <div class="w-2 h-2 md:w-3 md:h-3 bg-green-500 rounded-full animate-bounce" style="animation-delay: 0.1s;"></div>
                        <div class="w-2 h-2 md:w-3 md:h-3 bg-green-500 rounded-full animate-bounce" style="animation-delay: 0.2s;"></div>
                    </div>
                </div>
            </div>

            <!-- Typing Indicator -->
            <div id="typing-indicator" class="px-3 md:px-4 py-2 hidden">
                <div class="flex items-start space-x-2 md:space-x-3">
                    <div class="w-6 h-6 md:w-8 md:h-8 bg-gray-200 rounded-full flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-user text-gray-500 text-xs"></i>
                    </div>
                    <div class="typing-indicator">
                        <div class="typing-dot"></div>
                        <div class="typing-dot"></div>
                        <div class="typing-dot"></div>
                    </div>
                </div>
            </div>

            <!-- Message Input -->
            <div class="message-input-area p-4 md:p-6 border-t border-gray-200">
                @if($conversation->status === 'open')
                    @if($conversation->user_id === null)
                        <!-- Guest conversation - cannot reply in chat -->
                        <div class="bg-gradient-to-r from-orange-50 to-orange-100 border border-orange-200 rounded-2xl p-4 md:p-6">
                            <div class="flex flex-col md:flex-row md:items-start space-y-3 md:space-y-0 md:space-x-4">
                                <div class="flex-shrink-0 self-center md:self-start">
                                    <div class="bg-orange-500 p-2 rounded-xl">
                                        <i class="fas fa-info-circle text-white"></i>
                                    </div>
                                </div>
                                <div class="flex-1 text-center md:text-left">
                                    <h4 class="text-base md:text-lg font-semibold text-orange-800 mb-2">Pesan dari Guest User</h4>
                                    <p class="text-sm text-orange-700 mb-4">
                                        Guest user tidak dapat melihat balasan di chat ini. Untuk merespons, gunakan email langsung.
                                    </p>
                                    <button onclick="openEmailReplyModal()" 
                                            class="w-full md:w-auto inline-flex items-center justify-center px-4 md:px-6 py-2 md:py-3 bg-gradient-to-r from-orange-600 to-orange-700 text-white font-semibold rounded-xl hover:from-orange-700 hover:to-orange-800 transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-1 text-sm md:text-base">
                                        <i class="fas fa-envelope mr-2"></i>
                                        Balas via Email
                                    </button>
                                </div>
                            </div>
                        </div>
                    @else
                        <form id="message-form" class="flex flex-col md:flex-row items-stretch md:items-end space-y-3 md:space-y-0 md:space-x-4">
                            <!-- Textarea -->
                            <div class="flex-1 relative">
                                <textarea id="message-body"
                                          class="w-full message-input-area border-2 border-gray-200 rounded-2xl px-4 md:px-6 py-3 md:py-4 pr-12 md:pr-16 
                                                 focus:ring-2 focus:ring-green-500 focus:border-green-500 resize-none
                                                 leading-relaxed text-gray-900 placeholder-gray-500 text-sm md:text-base"
                                          placeholder="Ketik pesan Anda..."
                                          rows="1"
                                          maxlength="5000"></textarea>
                                <!-- Character count -->
                                <span id="char-count" class="absolute bottom-2 right-3 md:right-4 text-xs text-gray-400 font-medium">0/5000</span>
                            </div>

                            <!-- Send Button -->
                            <button type="submit"
                                    class="send-button bg-gradient-to-r from-green-600 to-emerald-600 text-white p-3 md:p-4 rounded-2xl flex-shrink-0
                                           hover:from-green-700 hover:to-emerald-700 transition-all disabled:from-gray-400 disabled:to-gray-500
                                           disabled:cursor-not-allowed shadow-lg hover:shadow-xl transform hover:-translate-y-1 self-center md:self-auto"
                                    title="Kirim Pesan">
                                <i class="fas fa-paper-plane text-sm md:text-lg"></i>
                            </button>
                        </form>
                    @endif
                @else
                    <div class="text-center text-gray-500 text-sm py-4">
                        <i class="fas fa-lock mr-2"></i> Percakapan ini sudah ditutup. Anda tidak bisa mengirim pesan lagi.
                    </div>
                @endif
            </div>


        </div>

        <!-- Quick Actions (for admin/operator) -->
        @if(Auth::user()->isAdmin() || Auth::user()->isOperator())
        <div class="glass-card rounded-2xl p-4 md:p-6 mt-4 md:mt-6 w-full">
            <h5 class="font-semibold text-gray-900 mb-3 md:mb-4 text-sm md:text-base">Aksi Cepat</h5>
            <div class="quick-actions flex flex-wrap gap-2 md:gap-3">
                @if($conversation->user_id === null)
                    <!-- Guest-specific actions -->
                    <button onclick="openEmailReplyModal()" 
                            class="quick-action-btn px-3 py-2 bg-orange-100 text-orange-700 rounded-xl text-xs md:text-sm hover:bg-orange-200 transition-all shadow-sm hover:shadow-md transform hover:-translate-y-0.5">
                        <i class="fas fa-envelope mr-1"></i> 
                        <span class="hidden sm:inline">Balas via Email</span>
                        <span class="sm:hidden">Email</span>
                    </button>
                @endif
                
                @if($conversation->status == 'open')
                    <form action="{{ route('chat.close', $conversation) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="quick-action-btn px-3 py-2 bg-red-100 text-red-700 rounded-xl text-xs md:text-sm hover:bg-red-200 transition-all shadow-sm hover:shadow-md transform hover:-translate-y-0.5">
                            <i class="fas fa-times mr-1"></i> 
                            <span class="hidden sm:inline">Tutup Percakapan</span>
                            <span class="sm:hidden">Tutup</span>
                        </button>
                    </form>
                @else
                    <form action="{{ route('chat.reopen', $conversation) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="quick-action-btn px-3 py-2 bg-green-100 text-green-700 rounded-xl text-xs md:text-sm hover:bg-green-200 transition-all shadow-sm hover:shadow-md transform hover:-translate-y-0.5">
                            <i class="fas fa-play mr-1"></i> 
                            <span class="hidden sm:inline">Buka Kembali</span>
                            <span class="sm:hidden">Buka</span>
                        </button>
                    </form>
                @endif

                <form action="{{ route('chat.important.toggle', $conversation) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="quick-action-btn px-3 py-2 
                        {{ $conversation->is_important ? 'bg-yellow-200 text-yellow-800 hover:bg-yellow-300' : 'bg-green-100 text-green-700 hover:bg-green-200' }} 
                        rounded-xl text-xs md:text-sm transition-all shadow-sm hover:shadow-md transform hover:-translate-y-0.5">
                        <i class="fas fa-flag mr-1"></i>
                        <span class="hidden sm:inline">{{ $conversation->is_important ? 'Hapus Tanda Penting' : 'Tandai Penting' }}</span>
                        <span class="sm:hidden">{{ $conversation->is_important ? 'Hapus' : 'Penting' }}</span>
                    </button>
                </form>

                <button class="quick-action-btn px-3 py-2 bg-emerald-100 text-emerald-700 rounded-xl text-xs md:text-sm hover:bg-emerald-200 transition-all shadow-sm hover:shadow-md transform hover:-translate-y-0.5">
                    <i class="fas fa-exchange-alt mr-1"></i> 
                    <span class="hidden sm:inline">Transfer</span>
                    <span class="sm:hidden">Transfer</span>
                </button>

                <button onclick="openDeleteModal()" class="quick-action-btn px-3 py-2 bg-red-100 text-red-700 rounded-xl text-xs md:text-sm hover:bg-red-200 transition-all shadow-sm hover:shadow-md transform hover:-translate-y-0.5">
                    <i class="fas fa-trash mr-1"></i> 
                    <span class="hidden sm:inline">Hapus Percakapan</span>
                    <span class="sm:hidden">Hapus</span>
                </button>
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Email Reply Modal -->
@if($conversation->user_id === null)
<div id="emailReplyModal" class="hidden fixed inset-0 bg-black bg-opacity-60 backdrop-blur-sm z-50 flex items-center justify-center p-4">
    <div class="bg-white bg-opacity-98 backdrop-blur-20 rounded-2xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto transform transition-all">
        <div class="bg-gradient-to-r from-orange-600 to-orange-700 text-white p-4 md:p-6 rounded-t-2xl">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="bg-white bg-opacity-20 p-2 rounded-lg">
                        <i class="fas fa-envelope text-lg md:text-xl"></i>
                    </div>
                    <h3 class="text-lg md:text-xl font-bold">Balas via Email</h3>
                </div>
                <button onclick="closeEmailReplyModal()" class="text-white hover:text-gray-200 transition-colors">
                    <i class="fas fa-times text-lg md:text-xl"></i>
                </button>
            </div>
        </div>
        
        <div class="p-4 md:p-6">

            <form id="emailReplyForm" action="{{ route('admin.email-reply', $conversation) }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <!-- Guest Info -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h4 class="font-medium text-gray-900 mb-2">Informasi Guest:</h4>
                        <div id="guestInfo" class="text-sm text-gray-600">
                            <p><strong>Email:</strong> <span id="guestEmail">
                                @php
                                    // Server-side extraction as fallback
                                    $guestEmail = '';
                                    $guestName = '';
                                    if ($conversation->user_id === null && $conversation->messages->count() > 0) {
                                        $firstMessage = $conversation->messages->first();
                                        if (preg_match('/Email:\s*([^\n\r]+)/i', $firstMessage->body, $emailMatch)) {
                                            $guestEmail = trim($emailMatch[1]);
                                        }
                                        if (preg_match('/Nama:\s*([^\n\r]+)/i', $firstMessage->body, $nameMatch)) {
                                            $guestName = trim($nameMatch[1]);
                                        }
                                    }
                                @endphp
                                {{ $guestEmail ?: '-' }}
                            </span></p>
                            <p><strong>Nama:</strong> <span id="guestName">{{ $guestName ?: '-' }}</span></p>
                        </div>
                        @if($guestEmail)
                            <div class="mt-2 text-xs text-green-600">
                                <i class="fas fa-check-circle mr-1"></i>
                                Email berhasil ditemukan dari pesan
                            </div>
                        @else
                            <div class="mt-2">
                                <button type="button" onclick="extractGuestInfo()" 
                                        class="text-xs text-green-600 hover:text-green-800 underline">
                                    <i class="fas fa-refresh mr-1"></i>
                                    Coba ekstrak ulang
                                </button>
                            </div>
                        @endif
                    </div>

                    <!-- Email Subject -->
                    <div>
                        <label for="email_subject" class="block text-sm font-medium text-gray-700 mb-2">Subject</label>
                        <input type="text" id="email_subject" name="subject" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                               value="Re: {{ $conversation->subject }}">
                    </div>

                    <!-- Email Body -->
                    <div>
                        <label for="email_body" class="block text-sm font-medium text-gray-700 mb-2">Pesan</label>
                        <textarea id="email_body" name="message" rows="8" required
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                  placeholder="Tulis balasan Anda di sini..."></textarea>
                    </div>

                    <!-- Actions -->
                    <div class="flex justify-end space-x-3 pt-4">
                        <button type="button" onclick="closeEmailReplyModal()"
                                class="px-4 py-2 text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300 transition-colors">
                            Batal
                        </button>
                        <button type="submit"
                                class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors">
                            <i class="fas fa-paper-plane mr-2"></i>
                            Kirim Email
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="hidden fixed inset-0 bg-black bg-opacity-60 backdrop-blur-sm z-50 flex items-center justify-center p-4">
    <div class="bg-white bg-opacity-98 backdrop-blur-20 rounded-2xl shadow-2xl max-w-md w-full transform transition-all">
        <div class="bg-gradient-to-r from-red-600 to-red-700 text-white p-4 md:p-6 rounded-t-2xl">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="bg-white bg-opacity-20 p-2 rounded-lg">
                        <i class="fas fa-exclamation-triangle text-lg md:text-xl"></i>
                    </div>
                    <h3 class="text-lg md:text-xl font-bold">Konfirmasi Hapus</h3>
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
                        <h4 class="text-lg font-semibold text-gray-900 mb-2">Hapus Percakapan</h4>
                        <p class="text-gray-600 mb-4">
                            Apakah Anda yakin ingin menghapus percakapan ini? Tindakan ini tidak dapat dibatalkan dan semua pesan akan hilang permanen.
                        </p>
                        <div class="bg-red-50 border border-red-200 rounded-lg p-3">
                            <p class="text-sm text-red-700">
                                <i class="fas fa-info-circle mr-2"></i>
                                <strong>Percakapan:</strong> {{ $conversation->subject }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex flex-col md:flex-row justify-end space-y-3 md:space-y-0 md:space-x-3 pt-4 border-t border-gray-200">
                <button onclick="closeDeleteModal()"
                        class="w-full md:w-auto px-6 py-2.5 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors">
                    <i class="fas fa-times mr-2"></i>Batal
                </button>
                <form id="deleteForm" action="{{ route('chat.destroy', $conversation) }}" method="POST" class="w-full md:w-auto">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="w-full px-6 py-2.5 bg-gradient-to-r from-red-600 to-red-700 text-white rounded-lg hover:from-red-700 hover:to-red-800 transition-all shadow-lg hover:shadow-xl">
                        <i class="fas fa-trash mr-2"></i>Ya, Hapus Percakapan
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
const conversationId = {{ $conversation->id }};
const currentUserId = {{ Auth::id() }};
const chatUserId = {{ $conversation->user_id ?? 'null' }};
const getMessagesUrl = `/chat/${conversationId}/messages`;
const storeMessageUrl = `/chat/${conversationId}/messages`;
const presenceUrl = chatUserId ? `/chat/presence/${chatUserId}` : null;

document.addEventListener('DOMContentLoaded', () => {
    const messageContainer = document.getElementById('message-container');
    const messageForm = document.getElementById('message-form');
    const messageBody = document.getElementById('message-body');
    const submitButton = messageForm ? messageForm.querySelector('button[type="submit"]') : null;
    const loadingIndicator = document.getElementById('loading-messages');
    const charCount = document.getElementById('char-count');
    let isLoading = false;
    let lastMessageCount = 0;

    // Auto-resize textarea (only if exists)
    if (messageBody) {
        messageBody.addEventListener('input', function() {
        this.style.height = 'auto';
        this.style.height = Math.min(this.scrollHeight, 120) + 'px';

        // Update character count
        const count = this.value.length;
        charCount.textContent = `${count}/5000`;

            if (count > 4500) {
                charCount.classList.add('text-red-500');
            } else {
                charCount.classList.remove('text-red-500');
            }
        });

        // Handle Enter key (Shift+Enter for new line)
        messageBody.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                if (messageForm) {
                    messageForm.dispatchEvent(new Event('submit'));
                }
            }
        });
    }

    const scrollToBottom = (smooth = true) => {
        messageContainer.scrollTo({
            top: messageContainer.scrollHeight,
            behavior: smooth ? 'smooth' : 'auto'
        });
    };

    const formatTime = (timestamp) => {
        const date = new Date(timestamp);
        const now = new Date();
        const isToday = date.toDateString() === now.toDateString();

        if (isToday) {
            return date.toLocaleTimeString('id-ID', {
                hour: '2-digit',
                minute: '2-digit',
                hour12: false
            });
        } else {
            return date.toLocaleDateString('id-ID', {
                day: '2-digit',
                month: 'short',
                hour: '2-digit',
                minute: '2-digit',
                hour12: false
            });
        }
    };

    const renderMessages = (container, messages) => {
        if (loadingIndicator) {
            loadingIndicator.remove();
        }

        container.innerHTML = '';
        let lastDate = null;
        
        console.log('Rendering messages:', messages.length);
        messages.forEach(msg => {
            console.log('Message:', {
                id: msg.id,
                user_id: msg.user_id,
                body: msg.body.substring(0, 100) + '...',
                user: msg.user
            });
        });

        messages.forEach((message, index) => {
            const messageDate = new Date(message.created_at).toDateString();

            // Add date separator
            if (messageDate !== lastDate) {
                const dateSeparator = document.createElement('div');
                dateSeparator.classList.add('flex', 'justify-center', 'my-4');
                dateSeparator.innerHTML = `
                    <span class="bg-gray-200 text-gray-600 text-xs px-3 py-1 rounded-full">
                        ${new Date(message.created_at).toLocaleDateString('id-ID', {
                            weekday: 'long',
                            year: 'numeric',
                            month: 'long',
                            day: 'numeric'
                        })}
                    </span>
                `;
                container.appendChild(dateSeparator);
                lastDate = messageDate;
            }

            const isCurrentUser = message.user_id === currentUserId;
            const isGuest = message.user_id === null;
            const messageWrapper = document.createElement('div');
            
            messageWrapper.classList.add(
                'message-wrapper', 
                'flex', 
                isCurrentUser ? 'justify-end' : 'justify-start', 
                'items-start',
                'space-x-2'
            );

            // Create avatar
            const avatarDiv = document.createElement('div');
            avatarDiv.classList.add('flex-shrink-0', 'relative');
            
            if (isGuest) {
                avatarDiv.innerHTML = '<div class="rounded-full h-8 w-8 bg-orange-100 flex items-center justify-center border-2 border-white shadow-sm"><i class="fas fa-user-secret text-orange-600 text-xs"></i></div>';
            } else {
                const imgSrc = message.user ? message.user.profile_photo_url : '/images/default-avatar.svg';
                const imgAlt = message.user ? message.user.name : 'User';
                avatarDiv.innerHTML = '<img class="rounded-full h-8 w-8 object-cover border-2 border-white shadow-sm" src="' + imgSrc + '" alt="' + imgAlt + '">';
            }

            // Create message bubble
            const bubbleDiv = document.createElement('div');
            bubbleDiv.classList.add('max-w-xs', 'md:max-w-md');
            
            const userName = isGuest ? 'Guest' : (message.user ? message.user.name : 'User');
            const userNameClass = isCurrentUser ? 'text-white/90' : (isGuest ? 'text-orange-700' : 'text-gray-700');
            const bubbleClass = isCurrentUser ? 'chat-bubble-user' : 'chat-bubble-other';
            const checkIcon = isCurrentUser ? '<i class="fas fa-check text-green-500 ml-1"></i>' : '';
            
            bubbleDiv.innerHTML = '<div class="flex items-center justify-between mb-1">' +
                '<span class="text-sm font-semibold ' + userNameClass + '">' + userName + '</span>' +
                '<span class="text-xs text-gray-400 ml-2">' + formatTime(message.created_at) + checkIcon + '</span>' +
                '</div>' +
                '<div class="rounded-2xl px-4 py-3 ' + bubbleClass + '" style="overflow-wrap: break-word;">' +
                '<p class="text-sm leading-relaxed">' + message.body.replace(/\n/g, '<br>') + '</p>' +
                '</div>';

            // Append in correct order
            if (isCurrentUser) {
                messageWrapper.appendChild(bubbleDiv);
                messageWrapper.appendChild(avatarDiv);
            } else {
                messageWrapper.appendChild(avatarDiv);
                messageWrapper.appendChild(bubbleDiv);
            }

            container.appendChild(messageWrapper);
        });
    };

    const fetchMessages = async (showLoading = false) => {
        if (isLoading) return;

        isLoading = true;
        try {
            console.log('Fetching messages from:', getMessagesUrl);
            const response = await fetch(getMessagesUrl);
            
            if (!response.ok) {
                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            }
            
            const messages = await response.json();
            
            // Debug log
            console.log('Fetched messages:', messages);
            console.log('Messages count:', messages.length);

            // Always render messages (remove the count check that might be causing issues)
            renderMessages(messageContainer, messages);
            scrollToBottom();
            lastMessageCount = messages.length;
            
        } catch (error) {
            console.error('Failed to fetch messages', error);
            // Show error message
            if (loadingIndicator) {
                loadingIndicator.innerHTML = '<p class="text-red-500 text-center">Gagal memuat pesan: ' + error.message + '<br><button onclick="location.reload()" class="text-green-600 underline">Coba lagi</button></p>';
            } else {
                // If no loading indicator, show error in container
                messageContainer.innerHTML = '<p class="text-red-500 text-center p-4">Gagal memuat pesan: ' + error.message + '<br><button onclick="location.reload()" class="text-green-600 underline">Coba lagi</button></p>';
            }
        } finally {
            isLoading = false;
        }
    };

    const sendMessage = async (e) => {
        e.preventDefault();
        const body = messageBody.value.trim();

        if (!body || isLoading) return;

        submitButton.disabled = true;
        const originalButtonHTML = submitButton.innerHTML;
        submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

        try {
            const response = await fetch(storeMessageUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ body })
            });

            if (response.ok) {
                messageBody.value = '';
                messageBody.style.height = 'auto';
                charCount.textContent = '0/5000';
                charCount.classList.remove('text-red-500');
                await fetchMessages();
            } else {
                throw new Error('Failed to send message');
            }
        } catch (error) {
            console.error('Failed to send message', error);
            alert('Gagal mengirim pesan. Silakan coba lagi.');
        } finally {
            submitButton.disabled = false;
            submitButton.innerHTML = originalButtonHTML;
        }
    };

    if (messageForm) {
        messageForm.addEventListener('submit', sendMessage);
    }

    // Auto-refresh messages every 3 seconds
    const messageInterval = setInterval(() => {
        if (document.visibilityState === 'visible') {
            fetchMessages();
        }
    }, 3000);

    // Auto-refresh presence status every 10 seconds
    const presenceIndicator = document.getElementById('presence-indicator');
    const presenceStatusText = document.getElementById('presence-status-text');

    const fetchPresence = async () => {
        if (!presenceUrl || !presenceIndicator || !presenceStatusText) return;
        try {
            const response = await fetch(presenceUrl);
            if (!response.ok) return;
            const data = await response.json();

            presenceStatusText.textContent = data.status;

            if (data.is_online) {
                presenceIndicator.className = 'online-indicator';
            } else {
                presenceIndicator.className = 'absolute -bottom-1 -right-1 w-3 h-3 md:w-4 md:h-4 bg-gray-400 border-2 border-white rounded-full';
            }
        } catch (e) {
            // silent fail
        }
    };

    const presenceInterval = setInterval(() => {
        if (document.visibilityState === 'visible') {
            fetchPresence();
        }
    }, 10000);

    // Clean up interval on page unload
    window.addEventListener('beforeunload', () => {
        clearInterval(messageInterval);
        clearInterval(presenceInterval);
    });

    // Initial load
    fetchMessages(true);

    // Focus on message input
    if (messageBody) {
        messageBody.focus();
    }
});

// Email Reply Modal Functions
function openEmailReplyModal() {
    // Extract guest info from the first message
    extractGuestInfo();
    document.getElementById('emailReplyModal').classList.remove('hidden');
}

function closeEmailReplyModal() {
    document.getElementById('emailReplyModal').classList.add('hidden');
}

function extractGuestInfo() {
    console.log('Starting extractGuestInfo...');
    
    // Try multiple times with increasing delays
    const attempts = [500, 1500, 3000];
    
    function tryExtract(attemptIndex = 0) {
        if (attemptIndex >= attempts.length) {
            console.log('All extraction attempts failed');
            return;
        }
        
        setTimeout(() => {
            console.log(`Extraction attempt ${attemptIndex + 1}...`);
            
            const messageContainer = document.getElementById('message-container');
            if (!messageContainer) {
                console.log('Message container not found');
                return tryExtract(attemptIndex + 1);
            }
            
            const messageWrappers = messageContainer.querySelectorAll('.message-wrapper');
            console.log(`Found ${messageWrappers.length} message wrappers`);
            
            // Look through all messages to find guest message with email info
            for (let i = 0; i < messageWrappers.length; i++) {
                const wrapper = messageWrappers[i];
                const messageText = wrapper.querySelector('p');
                
                if (messageText) {
                    const text = messageText.innerHTML || messageText.textContent || messageText.innerText;
                    console.log(`Message ${i + 1} text:`, text.substring(0, 200));
                    
                    // Try multiple patterns for email extraction
                    const emailPatterns = [
                        /Email:\s*([^\n\r<]+)/i,
                        /email:\s*([^\n\r<]+)/i,
                        /E-mail:\s*([^\n\r<]+)/i,
                        /([a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,})/i // Generic email pattern
                    ];
                    
                    const namePatterns = [
                        /Nama:\s*([^\n\r<]+)/i,
                        /nama:\s*([^\n\r<]+)/i,
                        /Name:\s*([^\n\r<]+)/i,
                        /name:\s*([^\n\r<]+)/i
                    ];
                    
                    // Try to find email
                    for (let pattern of emailPatterns) {
                        const emailMatch = text.match(pattern);
                        if (emailMatch) {
                            const email = emailMatch[1].trim();
                            document.getElementById('guestEmail').textContent = email;
                            console.log('Found email:', email);
                            break;
                        }
                    }
                    
                    // Try to find name
                    for (let pattern of namePatterns) {
                        const nameMatch = text.match(pattern);
                        if (nameMatch) {
                            const name = nameMatch[1].trim();
                            document.getElementById('guestName').textContent = name;
                            console.log('Found name:', name);
                            break;
                        }
                    }
                    
                    // If we found email, we can stop looking
                    if (document.getElementById('guestEmail').textContent !== '-') {
                        console.log('Email found, stopping search');
                        return;
                    }
                }
            }
            
            // If still no email found, try to extract from conversation subject
            if (document.getElementById('guestEmail').textContent === '-') {
                console.log('No email in messages, trying subject...');
                const subjectElement = document.querySelector('h4');
                if (subjectElement) {
                    const subjectText = subjectElement.textContent;
                    console.log('Checking subject:', subjectText);
                    
                    // Extract name from subject like "Subject (dari: Name)"
                    const subjectNameMatch = subjectText.match(/\(dari:\s*([^)]+)\)/i);
                    if (subjectNameMatch && document.getElementById('guestName').textContent === '-') {
                        const nameFromSubject = subjectNameMatch[1].trim();
                        document.getElementById('guestName').textContent = nameFromSubject;
                        console.log('Found name in subject:', nameFromSubject);
                    }
                }
                
                // Try next attempt if no email found
                console.log('No email found, trying next attempt...');
                tryExtract(attemptIndex + 1);
            }
            
        }, attempts[attemptIndex]);
    }
    
    tryExtract();
}

// Handle email form submission
document.getElementById('emailReplyForm')?.addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const guestEmail = document.getElementById('guestEmail').textContent.trim();
    
    if (guestEmail === '-' || guestEmail === '') {
        alert('Email guest tidak ditemukan. Pastikan pesan guest berisi informasi email.');
        return;
    }
    
    console.log('Sending email to:', guestEmail);
    
    // Add guest email and name to form data
    formData.append('guest_email', guestEmail);
    
    const guestName = document.getElementById('guestName').textContent;
    if (guestName !== '-') {
        formData.append('guest_name', guestName);
    }
    
    // Submit form
    fetch(this.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Email berhasil dikirim!');
            closeEmailReplyModal();
        } else {
            alert('Gagal mengirim email: ' + (data.message || 'Unknown error'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat mengirim email.');
    });
});

// Close modal when clicking outside
document.getElementById('emailReplyModal')?.addEventListener('click', function(e) {
    if (e.target === this) {
        closeEmailReplyModal();
    }
});

// Delete Modal Functions
function openDeleteModal() {
    document.getElementById('deleteModal').classList.remove('hidden');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
}

// Close delete modal when clicking outside
document.getElementById('deleteModal')?.addEventListener('click', function(e) {
    if (e.target === this) {
        closeDeleteModal();
    }
});

// Handle delete form submission with additional confirmation
document.getElementById('deleteForm')?.addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Double confirmation for extra safety
    if (confirm('Apakah Anda benar-benar yakin? Tindakan ini tidak dapat dibatalkan!')) {
        this.submit();
    }
});
</script>
@endpush
