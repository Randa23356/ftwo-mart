@extends('layouts.app')

@push('styles')
<style>
    * { box-sizing: border-box; }

    .chat-show-page {
        background: linear-gradient(135deg, #15803d 0%, #065f46 100%);
        min-height: 100dvh;
        width: 100%;
        overflow-x: hidden;
    }

    .chat-page-inner {
        display: flex;
        flex-direction: column;
        height: 100dvh;
        padding: 1rem;
        gap: 0.75rem;
    }

    .glass-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
    }

    .chat-header-card {
        flex-shrink: 0;
    }

    .chat-header-card h3,
    .chat-header-card p,
    .chat-header-card span {
        color: #111827 !important;
    }

    .chat-header-card p {
        color: #6b7280 !important;
    }

    .chat-main-card {
        flex: 1;
        min-height: 0;
        display: flex;
        flex-direction: column;
        overflow: hidden;
    }

    .chat-subject-bar {
        flex-shrink: 0;
        background: linear-gradient(135deg, #15803d 0%, #065f46 100%);
        color: white;
        padding: 0.75rem 1rem;
    }

    .chat-messages {
        flex: 1;
        min-height: 0;
        overflow-y: auto;
        scroll-behavior: smooth;
        background: rgba(248, 250, 252, 0.8);
        padding: 0.75rem;
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
        animation: slideUp 0.3s ease-out;
    }

    @keyframes slideUp {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
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
    }

    .chat-input-bar {
        flex-shrink: 0;
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-top: 1px solid rgba(255, 255, 255, 0.3);
        padding: 0.75rem;
    }

    .chat-input-bar textarea {
        border: 2px solid #e5e7eb;
        border-radius: 1rem;
        padding: 0.625rem 0.75rem;
        padding-right: 2.5rem;
        resize: none;
        font-size: 0.875rem;
        line-height: 1.5;
        min-height: 42px;
        max-height: 120px;
        outline: none;
        transition: border-color 0.2s;
        width: 100%;
    }

    .chat-input-bar textarea:focus {
        border-color: #22c55e;
        box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.1);
    }

    .chat-send-btn {
        background: linear-gradient(135deg, #15803d, #065f46);
        color: white;
        width: 42px;
        height: 42px;
        border-radius: 0.75rem;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        transition: all 0.2s;
        box-shadow: 0 4px 12px rgba(21, 128, 61, 0.3);
    }

    .chat-send-btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 16px rgba(21, 128, 61, 0.4);
    }

    .chat-send-btn:disabled {
        background: #9ca3af;
        box-shadow: none;
        cursor: not-allowed;
        transform: none;
    }

    .chat-quick-actions {
        flex-shrink: 0;
    }

    /* Responsive */
    @media (max-width: 640px) {
        .chat-page-inner {
            padding: 0.5rem;
            gap: 0.5rem;
        }

        .chat-messages {
            padding: 0.5rem;
        }

        .chat-subject-bar {
            padding: 0.5rem 0.75rem;
        }
    }
</style>
@endpush

@section('content')
<style>
    .chat-header-card h3,
    .chat-header-card p {
        color: #111827 !important;
    }
    .chat-header-card p {
        color: #6b7280 !important;
    }
</style>
<div class="chat-show-page">
    <div class="chat-page-inner">
        <!-- Header -->
        <div class="glass-card chat-header-card rounded-xl p-3 flex items-center gap-3">
            <a href="{{ route('chat.index') }}"
               class="p-2 rounded-lg action-button hover:bg-white transition-all flex-shrink-0">
                <i class="fas fa-arrow-left text-gray-600 text-sm"></i>
            </a>

            <div class="relative flex-shrink-0">
                @if($conversation->visibility === 'seller_buyer')
                    @if(Auth::user()->seller && Auth::user()->seller->id === $conversation->seller_id)
                        {{-- Seller viewing: show buyer --}}
                        @if($conversation->user)
                            <img src="{{ $conversation->user->profile_photo_url }}"
                                 alt="{{ $conversation->user->name }}"
                                 class="w-10 h-10 rounded-xl object-cover border-2 border-white shadow-lg">
                        @else
                            <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl flex items-center justify-center border-2 border-white shadow-lg">
                                <i class="fas fa-user text-white text-sm"></i>
                            </div>
                        @endif
                    @else
                        {{-- Buyer viewing: show seller --}}
                        @if($conversation->seller && $conversation->seller->logo)
                            <img src="{{ $conversation->seller->logo_url }}"
                                 alt="{{ $conversation->seller->shop_name }}"
                                 class="w-10 h-10 rounded-xl object-cover border-2 border-white shadow-lg">
                        @else
                            <div class="w-10 h-10 bg-gradient-to-r from-green-500 to-emerald-600 rounded-xl flex items-center justify-center border-2 border-white shadow-lg">
                                <i class="fas fa-store text-white text-sm"></i>
                            </div>
                        @endif
                    @endif
                @elseif(Auth::user()->isAdmin() || Auth::user()->isOperator())
                    @if($conversation->user)
                        <img src="{{ $conversation->user->profile_photo_url }}"
                             alt="{{ $conversation->user->name }}"
                             class="w-10 h-10 rounded-xl object-cover border-2 border-white shadow-lg">
                        @if($conversation->user->presence_status == 'Online')
                            <div class="online-indicator" id="presence-indicator"></div>
                        @else
                            <div class="absolute -bottom-0.5 -right-0.5 w-3 h-3 bg-gray-400 border-2 border-white rounded-full" id="presence-indicator"></div>
                        @endif
                    @else
                        <div class="w-10 h-10 bg-gradient-to-r from-orange-500 to-orange-600 rounded-xl flex items-center justify-center border-2 border-white shadow-lg">
                            <i class="fas fa-user-secret text-white text-sm"></i>
                        </div>
                    @endif
                @else
                    <div class="w-10 h-10 bg-gradient-to-r from-green-500 to-green-600 rounded-xl flex items-center justify-center border-2 border-white shadow-lg">
                        <i class="fas fa-headset text-white text-sm"></i>
                    </div>
                    <div class="online-indicator"></div>
                @endif
            </div>

            <div class="flex-1 min-w-0">
                @if($conversation->visibility === 'seller_buyer')
                    @if(Auth::user()->seller && Auth::user()->seller->id === $conversation->seller_id)
                        {{-- Seller viewing: show buyer name --}}
                        <h3 style="font-size:0.875rem;font-weight:700;color:#111827;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $conversation->user->name ?? 'Pembeli' }}</h3>
                        <p style="font-size:0.75rem;color:#6b7280;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">Pembeli</p>
                    @else
                        {{-- Buyer viewing: show seller shop name --}}
                        <h3 style="font-size:0.875rem;font-weight:700;color:#111827;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $conversation->seller->shop_name ?? 'Penjual' }}</h3>
                        <p style="font-size:0.75rem;color:#6b7280;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">Penjual</p>
                    @endif
                @elseif(Auth::user()->isAdmin() || Auth::user()->isOperator())
                    @if($conversation->user)
                        <h3 style="font-size:0.875rem;font-weight:700;color:#111827;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $conversation->user->name }}</h3>
                        <p style="font-size:0.75rem;color:#6b7280;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;" id="presence-status-text">{{ $conversation->user->presence_status }}</p>
                    @else
                        <h3 style="font-size:0.875rem;font-weight:700;color:#ea580c;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;"><i class="fas fa-user-secret mr-1"></i>Guest</h3>
                        <p style="font-size:0.75rem;color:#6b7280;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">Pesan dari pengunjung</p>
                    @endif
                @else
                    @php
                        $csName = 'Customer Service';
                        $csRole = 'Online';
                        foreach ($conversation->messages as $message) {
                            if ($message->user_id !== Auth::id() && $message->user_id !== null && $message->user) {
                                $csUser = $message->user;
                                $csName = $csUser->name;
                                $csRole = $csUser->hasRole('admin') ? 'Admin Support' : 'Customer Support';
                                break;
                            }
                        }
                    @endphp
                    <h3 style="font-size:0.875rem;font-weight:700;color:#111827;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $csName }}</h3>
                    <p style="font-size:0.75rem;color:#6b7280;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $csRole }}</p>
                @endif
            </div>

            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold {{ $conversation->status == 'open' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                <span class="w-1.5 h-1.5 {{ $conversation->status == 'open' ? 'bg-green-500' : 'bg-gray-500' }} rounded-full mr-1.5"></span>
                {{ $conversation->status == 'open' ? 'Aktif' : 'Selesai' }}
            </span>
        </div>

        <!-- Chat Main Card -->
        <div class="glass-card chat-main-card rounded-xl overflow-hidden">
            <!-- Subject Bar -->
            <div class="chat-subject-bar flex items-center justify-between">
                <div class="min-w-0">
                    <h4 class="font-bold text-sm truncate">{{ $conversation->subject }}</h4>
                    <p class="text-green-100 text-xs">
                        <i class="fas fa-clock mr-1"></i>
                        {{ $conversation->created_at->diffForHumans() }}
                        <span class="ml-2 bg-white/20 px-2 py-0.5 rounded-full text-xs">
                            <i class="fas fa-comments mr-1"></i>{{ $conversation->messages()->count() }}
                        </span>
                    </p>
                </div>
            </div>

            <!-- Messages Area -->
            <div id="message-container" class="chat-messages space-y-3">
                <div id="loading-messages" class="flex justify-center items-center py-8">
                    <div class="flex space-x-2">
                        <div class="w-2 h-2 bg-green-500 rounded-full animate-bounce"></div>
                        <div class="w-2 h-2 bg-green-500 rounded-full animate-bounce" style="animation-delay: 0.1s;"></div>
                        <div class="w-2 h-2 bg-green-500 rounded-full animate-bounce" style="animation-delay: 0.2s;"></div>
                    </div>
                </div>
            </div>

            <!-- Typing Indicator -->
            <div id="typing-indicator" class="px-3 py-2 hidden">
                <div class="typing-indicator">
                    <div class="typing-dot"></div>
                    <div class="typing-dot"></div>
                    <div class="typing-dot"></div>
                </div>
            </div>

            <!-- Message Input -->
            <div class="chat-input-bar">
                @if($conversation->status === 'open')
                    @if($conversation->user_id === null)
                        <div class="bg-orange-50 border border-orange-200 rounded-xl p-3 text-center">
                            <p class="text-xs text-orange-700 mb-2">Guest tidak dapat melihat balasan di chat ini.</p>
                            <button onclick="openEmailReplyModal()"
                                    class="inline-flex items-center px-4 py-2 bg-orange-600 text-white text-xs font-semibold rounded-lg hover:bg-orange-700 transition-all">
                                <i class="fas fa-envelope mr-1.5"></i>Balas via Email
                            </button>
                        </div>
                    @else
                        <form id="message-form" class="flex items-end gap-2">
                            <div class="flex-1 relative min-w-0">
                                <textarea id="message-body"
                                          placeholder="Ketik pesan..."
                                          rows="1"
                                          maxlength="5000"></textarea>
                                <span id="char-count" class="absolute bottom-1.5 right-2 text-[10px] text-gray-400">0/5000</span>
                            </div>
                            <button type="submit" class="chat-send-btn" title="Kirim">
                                <i class="fas fa-paper-plane text-sm"></i>
                            </button>
                        </form>
                    @endif
                @else
                    <div class="text-center text-gray-400 text-xs py-2">
                        <i class="fas fa-lock mr-1"></i>Percakapan ditutup
                    </div>
                @endif
            </div>
        </div>

        <!-- Quick Actions (for admin/operator) -->
        @if(Auth::user()->isAdmin() || Auth::user()->isOperator())
        <div class="glass-card chat-quick-actions rounded-xl p-3">
            <div class="flex flex-wrap gap-1.5">
                @if($conversation->user_id === null)
                    <button onclick="openEmailReplyModal()" class="px-2.5 py-1.5 bg-orange-100 text-orange-700 rounded-lg text-xs font-medium hover:bg-orange-200 transition-all">
                        <i class="fas fa-envelope mr-1"></i>Email
                    </button>
                @endif

                @if($conversation->status == 'open')
                    <form action="{{ route('chat.close', $conversation) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="px-2.5 py-1.5 bg-red-100 text-red-700 rounded-lg text-xs font-medium hover:bg-red-200 transition-all">
                            <i class="fas fa-times mr-1"></i>Tutup
                        </button>
                    </form>
                @else
                    <form action="{{ route('chat.reopen', $conversation) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="px-2.5 py-1.5 bg-green-100 text-green-700 rounded-lg text-xs font-medium hover:bg-green-200 transition-all">
                            <i class="fas fa-play mr-1"></i>Buka
                        </button>
                    </form>
                @endif

                <form action="{{ route('chat.important.toggle', $conversation) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="px-2.5 py-1.5 {{ $conversation->is_important ? 'bg-yellow-200 text-yellow-800' : 'bg-green-100 text-green-700' }} rounded-lg text-xs font-medium hover:opacity-80 transition-all">
                        <i class="fas fa-flag mr-1"></i>{{ $conversation->is_important ? 'Hapus' : 'Penting' }}
                    </button>
                </form>

                <button onclick="openDeleteModal()" class="px-2.5 py-1.5 bg-red-100 text-red-700 rounded-lg text-xs font-medium hover:bg-red-200 transition-all">
                    <i class="fas fa-trash mr-1"></i>Hapus
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
    let renderedMessageIds = new Set();
    let isInitialLoad = true;

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
        setTimeout(() => {
            messageContainer.scrollTo({
                top: messageContainer.scrollHeight,
                behavior: smooth ? 'smooth' : 'auto'
            });
        }, 50);
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
            const userNameStyle = 'color:#374151';
            const timeStyle = 'color:#9ca3af';
            const bubbleClass = isCurrentUser ? 'chat-bubble-user' : 'chat-bubble-other';
            const checkIcon = isCurrentUser ? '<i class="fas fa-check text-green-500 ml-1"></i>' : '';

            bubbleDiv.innerHTML = '<div class="flex items-center justify-between mb-1">' +
                '<span class="text-sm font-semibold" style="' + userNameStyle + '">' + userName + '</span>' +
                '<span class="text-xs ml-2" style="' + timeStyle + '">' + formatTime(message.created_at) + checkIcon + '</span>' +
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

    // New function: Append only new messages (without slideUp animation)
    const appendMessages = (container, messages) => {
        let lastDate = null;

        // Get the last date separator if exists
        const existingSeparators = container.querySelectorAll('.flex.justify-center.my-4');
        if (existingSeparators.length > 0) {
            const lastSeparator = existingSeparators[existingSeparators.length - 1];
            const dateText = lastSeparator.textContent.trim();
            // Parse date from separator
            lastDate = new Date(dateText).toDateString();
        }

        messages.forEach((message, index) => {
            const messageDate = new Date(message.created_at).toDateString();

            // Add date separator if new day
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

            // No animation class for appended messages
            messageWrapper.classList.add(
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
            const userNameStyle = 'color:#374151';
            const timeStyle = 'color:#9ca3af';
            const bubbleClass = isCurrentUser ? 'chat-bubble-user' : 'chat-bubble-other';
            const checkIcon = isCurrentUser ? '<i class="fas fa-check text-green-500 ml-1"></i>' : '';

            bubbleDiv.innerHTML = '<div class="flex items-center justify-between mb-1">' +
                '<span class="text-sm font-semibold" style="' + userNameStyle + '">' + userName + '</span>' +
                '<span class="text-xs ml-2" style="' + timeStyle + '">' + formatTime(message.created_at) + checkIcon + '</span>' +
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
            const response = await fetch(getMessagesUrl);

            if (!response.ok) {
                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            }

            const messages = await response.json();

            if (isInitialLoad) {
                renderMessages(messageContainer, messages);
                messages.forEach(m => renderedMessageIds.add(m.id));
                scrollToBottom(false);
                isInitialLoad = false;
            } else if (messages.length > 0) {
                const newMessages = messages.filter(m => !renderedMessageIds.has(m.id));
                if (newMessages.length > 0) {
                    appendMessages(messageContainer, newMessages);
                    newMessages.forEach(m => renderedMessageIds.add(m.id));
                    scrollToBottom();
                }
            }

        } catch (error) {
            console.error('Failed to fetch messages', error);
            if (showLoading && loadingIndicator) {
                loadingIndicator.innerHTML = '<p class="text-red-500 text-center">Gagal memuat pesan: ' + error.message + '<br><button onclick="location.reload()" class="text-green-600 underline">Coba lagi</button></p>';
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
