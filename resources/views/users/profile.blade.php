@extends('layouts.app')

@section('title', 'Profile ' . $user->name . ' - FtwoMart')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 via-white to-green-50 py-8">
    <div class="max-w-6xl mx-auto px-4">
        <!-- Profile Header with Glassmorphism -->
        <div class="bg-white/90 backdrop-blur-xl rounded-2xl shadow-xl border border-green-100/30 overflow-hidden mb-6 md:mb-8">
            <!-- Header top gradient -->
            <div class="h-24 sm:h-32 bg-gradient-to-r from-green-600 to-emerald-700 relative">
                <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"%3E%3Cg fill="none" fill-rule="evenodd"%3E%3Cg fill="%23ffffff" fill-opacity="0.08"%3E%3Ccircle cx="30" cy="30" r="4"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')]"></div>
            </div>
            <div class="relative px-4 sm:px-6 pb-6 sm:pb-8">
                <div class="flex flex-col items-center space-y-4 md:flex-row md:items-end md:space-y-0 md:space-x-6 lg:space-x-8 -mt-12 sm:-mt-16">
                    <div class="relative group">
                        <div class="absolute inset-0 bg-gradient-to-r from-green-400 to-emerald-400 rounded-2xl blur-xl opacity-50 group-hover:opacity-75 transition-opacity duration-300"></div>
                        <img src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}"
                             class="relative w-24 h-24 md:w-32 md:h-32 rounded-2xl object-cover border-4 border-white shadow-2xl transition-all duration-300 group-hover:scale-105">
                        <div class="absolute -bottom-1 -right-1 md:-bottom-2 md:-right-2 w-7 h-7 md:w-9 md:h-9 bg-gradient-to-r from-green-500 to-emerald-600 rounded-full flex items-center justify-center border-2 border-white shadow-lg">
                            <i class="fas fa-check text-white text-xs"></i>
                        </div>
                        @if($user->presence_status == 'Online')
                            <div class="absolute top-0 right-0 w-5 h-5 bg-green-500 border-2 border-white rounded-full animate-pulse shadow-lg"></div>
                        @endif
                    </div>
                    <div class="flex-1 text-center md:text-left">
                        <h1 class="text-2xl md:text-4xl font-bold text-gray-900 mb-1">
                            {{ $user->name }}
                            @if($user->email_verified_at)
                                <i class="fas fa-check-circle text-green-500 ml-1 text-lg"></i>
                            @endif
                        </h1>
                        @if($user->bio)
                            <p class="text-gray-600 leading-relaxed text-sm md:text-base max-w-full md:max-w-2xl">{{ $user->bio }}</p>
                        @endif

                        <!-- Status and Info -->
                        <div class="flex flex-wrap gap-2 mt-3 justify-center md:justify-start">
                            <span class="px-3 py-1.5 rounded-full text-xs font-bold {{ $user->presence_status == 'Online' ? 'bg-green-100 text-green-800 border border-green-200' : 'bg-gray-100 text-gray-800 border border-gray-200' }} shadow-sm">
                                <i class="fas fa-circle text-xs mr-1 {{ $user->presence_status == 'Online' ? 'text-green-500 animate-pulse' : 'text-gray-400' }}"></i>
                                {{ $user->presence_status }}
                            </span>
                            <span class="px-3 py-1.5 rounded-full text-xs font-bold {{ $user->isAdmin() ? 'bg-purple-100 text-purple-800 border border-purple-200' : ($user->isOperator() ? 'bg-blue-100 text-blue-800 border border-blue-200' : 'bg-green-100 text-green-800 border border-green-200') }} shadow-sm">
                                <i class="fas fa-{{ $user->isAdmin() ? 'user-shield' : ($user->isOperator() ? 'user-tie' : 'user') }} mr-1"></i>
                                {{ $user->isAdmin() ? 'Admin' : ($user->isOperator() ? 'Operator' : 'Pelanggan') }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modern Stats Cards with Green Theme -->
        <div class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6 mb-6 md:mb-8">
            <div class="bg-gradient-to-br from-green-600 to-emerald-700 rounded-2xl p-4 md:p-6 text-white shadow-xl transform transition-all duration-300 hover:scale-105 hover:shadow-2xl">
                <div class="flex items-center justify-between mb-3 md:mb-4">
                    <i class="fas fa-star text-2xl md:text-3xl opacity-80"></i>
                    <div class="bg-white/20 rounded-full px-2 py-0.5 md:px-3 md:py-1 text-xs font-semibold">TOTAL</div>
                </div>
                <div class="text-2xl md:text-4xl font-bold mb-1 md:mb-2">{{ $user->ratings->count() }}</div>
                <div class="text-green-100 text-xs md:text-sm font-medium">Rating Diberikan</div>
            </div>

            <div class="bg-gradient-to-br from-emerald-600 to-green-700 rounded-2xl p-4 md:p-6 text-white shadow-xl transform transition-all duration-300 hover:scale-105 hover:shadow-2xl">
                <div class="flex items-center justify-between mb-3 md:mb-4">
                    <i class="fas fa-chart-line text-2xl md:text-3xl opacity-80"></i>
                    <div class="bg-white/20 rounded-full px-2 py-0.5 md:px-3 md:py-1 text-xs font-semibold">AVG</div>
                </div>
                <div class="text-2xl md:text-4xl font-bold mb-1 md:mb-2">{{ $user->ratings->avg('rating') ? number_format($user->ratings->avg('rating'), 1) : '0.0' }}</div>
                <div class="text-emerald-100 text-xs md:text-sm font-medium">Rata-rata Rating</div>
            </div>

            <div class="bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl p-4 md:p-6 text-white shadow-xl transform transition-all duration-300 hover:scale-105 hover:shadow-2xl">
                <div class="flex items-center justify-between mb-3 md:mb-4">
                    <i class="fas fa-pen text-2xl md:text-3xl opacity-80"></i>
                    <div class="bg-white/20 rounded-full px-2 py-0.5 md:px-3 md:py-1 text-xs font-semibold">REVIEWS</div>
                </div>
                <div class="text-2xl md:text-4xl font-bold mb-1 md:mb-2">{{ $user->ratings->where('review_text', '!=', '')->count() }}</div>
                <div class="text-green-100 text-xs md:text-sm font-medium">Ulasan Ditulis</div>
            </div>

            <div class="bg-gradient-to-br from-teal-600 to-green-600 rounded-2xl p-4 md:p-6 text-white shadow-xl transform transition-all duration-300 hover:scale-105 hover:shadow-2xl">
                <div class="flex items-center justify-between mb-3 md:mb-4">
                    <i class="fas fa-shopping-bag text-2xl md:text-3xl opacity-80"></i>
                    <div class="bg-white/20 rounded-full px-2 py-0.5 md:px-3 md:py-1 text-xs font-semibold">ORDERS</div>
                </div>
                <div class="text-2xl md:text-4xl font-bold mb-1 md:mb-2">{{ $user->orders()->count() }}</div>
                <div class="text-teal-100 text-xs md:text-sm font-medium">Total Pesanan</div>
            </div>
        </div>

        <!-- User Ratings with Modern Design -->
        <div class="bg-white/90 backdrop-blur-xl rounded-2xl shadow-xl border border-green-100/30 p-4 md:p-8">
            <div class="flex flex-col items-center justify-between mb-4 md:mb-8 md:flex-row">
                <h2 class="text-xl md:text-2xl font-bold text-gray-900 flex items-center">
                    <div class="w-10 h-10 bg-green-100 rounded-xl flex items-center justify-center mr-3">
                        <i class="fas fa-star text-yellow-500"></i>
                    </div>
                    <span>Rating & Ulasan {{ $user->name }}</span>
                </h2>
                <div class="bg-gradient-to-r from-green-600 to-emerald-600 text-white px-4 py-2 rounded-xl text-sm font-bold shadow-lg mt-3 md:mt-0">
                    <i class="fas fa-star mr-1"></i>
                    {{ $user->ratings->count() }} Review{{ $user->ratings->count() > 1 ? 's' : '' }}
                </div>
            </div>

            @if($user->ratings->count() > 0)
                <div class="space-y-4 md:space-y-6">
                    @foreach($user->ratings as $rating)
                        <div class="bg-white rounded-2xl p-4 md:p-6 border border-gray-100 shadow-lg hover:shadow-xl transition-all duration-300">
                            <div class="flex flex-col md:flex-row md:items-start justify-between mb-3 md:mb-4">
                                <div class="flex items-start space-x-3 md:space-x-4 mb-3 md:mb-0">
                                    <img src="{{ $rating->user->profile_photo_url }}" alt="{{ $rating->user->name }}"
                                         class="w-10 h-10 md:w-12 md:h-12 rounded-xl object-cover border-2 border-white shadow-md flex-shrink-0">
                                    <div class="flex-1 min-w-0">
                                        <div class="flex flex-col md:flex-row md:items-center space-y-1 md:space-y-0 md:space-x-3">
                                            <h4 class="font-bold text-gray-800 text-sm md:text-base">
                                                <a href="{{ route('users.profile', $rating->user->name) }}" class="hover:text-green-600 transition-colors">
                                                    {{ $rating->user->name }}
                                                </a>
                                            </h4>
                                            <div class="flex flex-col md:flex-row md:items-center space-y-1 md:space-y-0 md:space-x-3 text-sm text-gray-500">
                                                <div class="flex items-center">
                                                    @for ($i = 1; $i <= 5; $i++)
                                                        <span class="text-base md:text-lg {{ $i <= $rating->rating ? 'text-yellow-400' : 'text-gray-300' }}">★</span>
                                                    @endfor
                                                </div>
                                                <div class="flex flex-col md:flex-row md:items-center space-y-1 md:space-y-0 md:space-x-3">
                                                    <span>{{ $rating->created_at->format('d M Y') }}</span>
                                                    <span class="hidden md:inline">•</span>
                                                    <a href="{{ route('products.detail', $rating->product->slug) }}"
                                                       class="text-green-600 hover:text-green-700 font-medium block md:inline">
                                                        {{ $rating->product->name }}
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @if($rating->review_text)
                                <p class="text-gray-700 leading-relaxed mb-3 md:mb-4 text-base">{{ $rating->review_text }}</p>
                            @endif

                            <!-- Rating Replies -->
                            @if($rating->replies->count() > 0)
                                <div class="mt-3 md:mt-4 space-y-2 md:space-y-3">
                                    @foreach($rating->replies as $reply)
                                        <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-2xl p-3 md:p-4 border border-green-200 shadow-md">
                                            <div class="flex items-start space-x-2 md:space-x-3">
                                                <img src="{{ $reply->user->profile_photo_url }}" alt="{{ $reply->user->name }}"
                                                     class="w-8 h-8 md:w-10 md:h-10 rounded-xl object-cover border-2 border-green-300 shadow-sm flex-shrink-0">
                                                <div class="flex-1 min-w-0">
                                                    <div class="flex flex-col md:flex-row md:items-center space-y-1 md:space-y-0 md:space-x-3 mb-2">
                                                        <div class="flex items-center space-x-2 md:space-x-3">
                                                            <h5 class="font-bold text-green-800 text-sm md:text-base">
                                                                <a href="{{ route('users.profile', $reply->user->name) }}" class="hover:text-green-600 transition-colors">
                                                                    {{ $reply->user->name }}
                                                                </a>
                                                            </h5>
                                                            <span class="bg-gradient-to-r from-green-600 to-emerald-600 text-white px-3 py-1 rounded-full text-xs font-bold shadow-sm">
                                                                @if($reply->user->isAdmin())
                                                                    Admin
                                                                @elseif($reply->user->isOperator())
                                                                    Operator
                                                                @endif
                                                            </span>
                                                            <span class="text-gray-500 text-xs md:text-sm">{{ $reply->created_at->format('d M Y, H:i') }}</span>
                                                        </div>
                                                    </div>
                                                    <p class="text-gray-700 leading-relaxed text-sm md:text-base">{{ $reply->reply_text }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12 md:py-16">
                    <div class="w-20 h-20 mx-auto bg-gray-100 rounded-full flex items-center justify-center mb-4 md:mb-6">
                        <i class="fas fa-star text-gray-300 text-4xl"></i>
                    </div>
                    <p class="text-gray-600 text-lg md:text-xl font-semibold mb-2">{{ $user->name }} belum memberikan rating</p>
                    <p class="text-gray-500 text-base">Jadilah yang pertama untuk memberikan ulasan!</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
