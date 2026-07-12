@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Modern Hero Header -->
    <div class="relative bg-gradient-to-br from-green-800 via-green-700 to-emerald-600 rounded-3xl p-10 md:p-14 mb-16 overflow-hidden shadow-xl text-center">
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-0 right-0 -mt-10 -mr-10 w-40 h-40 bg-white opacity-5 rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 left-0 -mb-10 -ml-10 w-40 h-40 bg-green-500 opacity-10 rounded-full blur-3xl"></div>
        </div>
        <div class="relative z-10">
            <div class="inline-flex items-center gap-2 bg-white/10 backdrop-blur-sm border border-white/20 rounded-full px-5 py-2 mb-8">
                <i class="fas fa-headset text-green-200 text-xs"></i>
                <span class="text-green-100 text-sm font-medium tracking-wide">Hubungi Kami</span>
            </div>
            <h1 class="text-3xl md:text-5xl font-extrabold text-white mb-4 tracking-tight">
                {{ $settings['contact_title']->value ?? 'Hubungi Kami' }}
            </h1>
            <p class="text-green-100/90 text-lg md:text-xl max-w-2xl mx-auto font-medium leading-relaxed">
                {{ $settings['contact_description']->value ?? 'Ada pertanyaan atau butuh bantuan? Jangan ragu untuk menghubungi kami. Tim customer service kami siap membantu Anda.' }}
            </p>
        </div>
    </div> 

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12 mb-16 items-start">
        <!-- Contact Form -->
        <div class="bg-white rounded-3xl shadow-xl border border-gray-100 p-8 md:p-10 order-2 lg:order-1">
            <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                <span class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center mr-3 text-green-600">
                    <i class="fas fa-paper-plane"></i>
                </span>
                Kirim Pesan
            </h2>

            <!-- Session Status -->
            @if(session('success'))
                <div class="mb-6 bg-green-50 border border-green-200 rounded-xl p-4 flex items-start gap-3">
                    <i class="fas fa-check-circle text-green-500 mt-1"></i>
                    <div>
                        <h3 class="text-sm font-bold text-green-800">Pesan Terkirim!</h3>
                        <p class="text-sm text-green-700 mt-1">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            <form action="{{ route('contact.store') }}" method="POST" class="space-y-5">
                @csrf

                @guest
                    <!-- Guest Warning -->
                    <div class="bg-blue-50 border border-blue-100 rounded-xl p-4 mb-6">
                        <div class="flex gap-3">
                            <i class="fas fa-info-circle text-blue-500 mt-1"></i>
                            <div class="text-sm text-blue-800">
                                <p class="font-bold mb-1">Mode Tamu</p>
                                <p>Anda belum login. Silakan isi nama dan email, atau <a href="{{ route('login') }}" class="underline font-bold hover:text-blue-600">Login</a> untuk menikmati fitur chat langsung.</p>
                            </div>
                        </div>
                    </div>
                @else
                    @if(!Auth::user()->hasVerifiedEmail())
                        <div class="bg-amber-50 border border-amber-100 rounded-xl p-4 mb-6 flex gap-3">
                            <i class="fas fa-exclamation-triangle text-amber-500 mt-1"></i>
                            <div class="text-sm text-amber-800">
                                <p class="font-bold mb-1">Email Belum Diverifikasi</p>
                                <p>Pesan akan dikirim sebagai tamu. <a href="{{ route('verification.notice') }}" class="underline font-bold">Verifikasi Email</a> untuk fitur chat penuh.</p>
                            </div>
                        </div>
                    @else
                        <div class="bg-green-50 border border-green-100 rounded-xl p-4 mb-6 flex gap-3">
                            <i class="fas fa-user-check text-green-500 mt-1"></i>
                            <div class="text-sm text-green-800">
                                <p class="font-bold mb-1">Login sebagai {{ Auth::user()->name }}</p>
                                <p>Pesan Anda akan masuk ke sistem chat prioritas.</p>
                            </div>
                        </div>
                    @endif
                @endguest

                @if(!Auth::check() || (Auth::check() && !Auth::user()->hasVerifiedEmail()))
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="space-y-2">
                            <label for="name" class="text-sm font-bold text-gray-700">Nama Lengkap</label>
                            <input type="text" id="name" name="name" required
                                   class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all"
                                   placeholder="Nama Anda"
                                   value="{{ old('name', Auth::check() ? Auth::user()->name : '') }}"
                                   @auth @if(!Auth::user()->hasVerifiedEmail()) readonly @endif @endauth>
                        </div>

                        <div class="space-y-2">
                            <label for="email" class="text-sm font-bold text-gray-700">Email</label>
                            <input type="email" id="email" name="email" required
                                   class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all"
                                   placeholder="email@contoh.com"
                                   value="{{ old('email', Auth::check() ? Auth::user()->email : '') }}"
                                   @auth @if(!Auth::user()->hasVerifiedEmail()) readonly @endif @endauth>
                        </div>
                    </div>
                @endif

                <div class="space-y-2">
                    <label for="subject" class="text-sm font-bold text-gray-700">Subjek</label>
                    <input type="text" id="subject" name="subject" required
                           class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all"
                           placeholder="Apa yang ingin Anda tanyakan?"
                           value="{{ old('subject') }}">
                </div>

                <div class="space-y-2">
                    <label for="message" class="text-sm font-bold text-gray-700">Pesan</label>
                    <textarea id="message" name="message" rows="5" required
                              class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all"
                              placeholder="Tulis pesan Anda di sini...">{{ old('message') }}</textarea>
                </div>

                <button type="submit"
                        class="w-full bg-green-700 hover:bg-green-800 text-white py-4 px-6 rounded-xl font-bold shadow-lg shadow-green-200 transition-all duration-300 transform hover:-translate-y-1 flex items-center justify-center gap-2 !mt-8">
                    <i class="fas fa-paper-plane text-white"></i>
                    <span>Kirim Pesan</span>
                </button>
            </form>
        </div>

        <!-- Contact Information -->
        <div class="space-y-8 order-1 lg:order-2">
            <!-- Info Card -->
            <div class="bg-white rounded-3xl shadow-xl border border-gray-100 p-8 md:p-10 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-green-50 rounded-bl-full -mr-10 -mt-10 opacity-50"></div>

                <h2 class="text-2xl font-bold text-gray-900 mb-8 flex items-center relative z-10">
                    <span class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center mr-3 text-green-600">
                        <i class="fas fa-info"></i>
                    </span>
                    Informasi Kontak
                </h2>

                <div class="space-y-6 relative z-10">
                    <div class="flex items-start group">
                        <div class="flex-shrink-0 w-12 h-12 bg-gray-50 rounded-2xl flex items-center justify-center group-hover:bg-green-600 group-hover:text-white transition-colors duration-300">
                            <i class="fas fa-phone text-gray-600 group-hover:text-white text-lg"></i>
                        </div>
                        <div class="ml-5">
                            <h3 class="text-sm font-bold text-gray-400 uppercase tracking-wider mb-1">Telepon</h3>
                            <p class="text-gray-900 font-semibold text-lg">{{ $settings['phone']->value ?? '-' }}</p>
                        </div>
                    </div>

                    <div class="flex items-start group">
                        <div class="flex-shrink-0 w-12 h-12 bg-gray-50 rounded-2xl flex items-center justify-center group-hover:bg-green-500 group-hover:text-white transition-colors duration-300">
                            <i class="fas fa-envelope text-gray-600 group-hover:text-white text-lg"></i>
                        </div>
                        <div class="ml-5">
                            <h3 class="text-sm font-bold text-gray-400 uppercase tracking-wider mb-1">Email</h3>
                            <p class="text-gray-900 font-semibold text-lg break-all">{{ $settings['email']->value ?? '-' }}</p>
                        </div>
                    </div>

                    <div class="flex items-start group">
                        <div class="flex-shrink-0 w-12 h-12 bg-gray-50 rounded-2xl flex items-center justify-center group-hover:bg-green-700 group-hover:text-white transition-colors duration-300">
                            <i class="fas fa-map-marker-alt text-gray-600 group-hover:text-white text-lg"></i>
                        </div>
                        <div class="ml-5">
                            <h3 class="text-sm font-bold text-gray-400 uppercase tracking-wider mb-1">Alamat</h3>
                            <p class="text-gray-900 font-medium leading-relaxed">{{ $settings['address']->value ?? '-' }}</p>
                        </div>
                    </div>

                    <div class="border-t border-gray-100 pt-6 mt-6">
                        <h3 class="text-sm font-bold text-gray-400 uppercase tracking-wider mb-4">Jam Operasional</h3>
                        <div class="flex items-center gap-3 text-gray-700 bg-gray-50 p-4 rounded-xl">
                            <i class="fas fa-clock text-green-600"></i>
                            <span class="font-medium">{{ $settings['opening_hours']->value ?? 'Setiap Hari: 09:00 - 21:00' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Social Media -->
            <div class="bg-white rounded-3xl shadow-xl border border-gray-100 p-8">
                <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                    <span class="w-10 h-10 rounded-full bg-pink-100 flex items-center justify-center mr-3 text-pink-600">
                        <i class="fas fa-hashtag"></i>
                    </span>
                    Sosial Media
                </h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <a href="https://wa.me/{{ $settings['contact_whatsapp']->value ?? '' }}" target="_blank"
                       class="flex items-center p-4 bg-green-50 text-green-700 rounded-xl hover:bg-green-100 transition-colors group">
                        <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center shadow-sm text-green-600 mr-3 group-hover:scale-110 transition-transform">
                            <i class="fab fa-whatsapp text-xl"></i>
                        </div>
                        <span class="font-bold">WhatsApp</span>
                    </a>

                    @if(isset($settings['instagram']) && $settings['instagram']->value)
                    <a href="{{ $settings['instagram']->value }}" target="_blank"
                       class="flex items-center p-4 bg-pink-50 text-pink-700 rounded-xl hover:bg-pink-100 transition-colors group">
                        <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center shadow-sm text-pink-600 mr-3 group-hover:scale-110 transition-transform">
                            <i class="fab fa-instagram text-xl"></i>
                        </div>
                        <span class="font-bold">Instagram</span>
                    </a>
                    @endif

                    @if(isset($settings['facebook']) && $settings['facebook']->value)
                    <a href="{{ $settings['facebook']->value }}" target="_blank"
                       class="flex items-center p-4 bg-blue-50 text-blue-700 rounded-xl hover:bg-blue-100 transition-colors group">
                        <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center shadow-sm text-blue-600 mr-3 group-hover:scale-110 transition-transform">
                            <i class="fab fa-facebook-f text-xl"></i>
                        </div>
                        <span class="font-bold">Facebook</span>
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Map Section -->
    <div class="mb-20">
        <div class="bg-white rounded-3xl shadow-2xl overflow-hidden border border-gray-200">
             @if(isset($settings['contact_maps_embed']) && $settings['contact_maps_embed']->value)
                <div class="aspect-w-16 aspect-h-9 w-full h-[400px]">
                    {!! $settings['contact_maps_embed']->value !!}
                </div>
            @else
                <div class="w-full h-[400px] bg-gray-100 flex flex-col items-center justify-center text-center p-8">
                    <i class="fas fa-map-marked-alt text-6xl text-gray-300 mb-4"></i>
                    <h3 class="text-xl font-bold text-gray-500">Peta Lokasi Belum Diatur</h3>
                    <p class="text-gray-400">Silakan atur embed map di panel admin.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
