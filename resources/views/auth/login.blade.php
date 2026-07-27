@extends('layouts.guest')

@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400,500,600,700&display=swap');
    .login-card-shadow {
        box-shadow: 0px 20px 40px rgba(15, 23, 42, 0.12);
    }
    .login-input-transition {
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }
</style>

<div class="min-h-screen bg-gray-50 flex items-center justify-center p-4 md:p-6 lg:p-8" style="font-family: 'Inter', sans-serif;">
    <main class="w-full max-w-[1100px] flex flex-col md:flex-row bg-white rounded-xl overflow-hidden login-card-shadow border border-gray-200/60 md:max-h-[90vh]">

        {{-- ====== Left Section: Illustration (hidden on mobile) ====== --}}
        <section class="hidden md:flex md:w-1/2 bg-green-50 relative items-center justify-center p-8 lg:p-10">
            <div class="absolute inset-0 opacity-10 overflow-hidden">
                <div class="absolute -top-10 -left-10 w-64 h-64 bg-green-500 rounded-full blur-3xl"></div>
                <div class="absolute -bottom-20 -right-20 w-80 h-80 bg-emerald-500 rounded-full blur-3xl"></div>
            </div>

            <div class="relative z-10 text-center flex flex-col items-center">
                <div class="mb-8 w-full max-w-[380px] aspect-square rounded-xl overflow-hidden bg-white shadow-lg">
                    @if(isset($settings['login_image']) && $settings['login_image']->value)
                        <img class="w-full h-full object-cover" src="{{ asset('storage/' . $settings['login_image']->value) }}" alt="Login Illustration">
                    @else
                        <img class="w-full h-full object-cover" src="{{ asset('images/logo.png') }}" alt="FtwoMart"
                             onerror="this.onerror=null; this.src='https://placehold.co/500x500/dcfce7/15803d?text=FtwoMart';">
                    @endif
                </div>
                <h1 class="text-[28px] leading-[1.2] tracking-tight font-bold text-green-700 mb-2">
                    {{ $settings['login_title']->value ?? 'Selamat Datang Kembali' }}
                </h1>
                <p class="text-[16px] leading-relaxed text-gray-600 max-w-[340px]">
                    {{ $settings['login_description']->value ?? 'Temukan produk terbaik dari vendor terpercaya di seluruh penjuru negeri hanya di ' . ($settings['website_name']->value ?? 'FtwoMart') . '.' }}
                </p>
            </div>
        </section>

        {{-- ====== Right Section: Login Form ====== --}}
        <section class="w-full md:w-1/2 p-6 md:p-10 bg-white flex flex-col justify-center">

            {{-- Mobile Brand --}}
            <div class="md:hidden flex justify-center mb-8">
                <span class="text-xl font-bold text-green-700">{{ $settings['website_name']->value ?? 'FtwoMart' }}</span>
            </div>

            {{-- Header --}}
            <div class="mb-8">
                <h2 class="text-[24px] leading-[1.3] font-semibold text-gray-900 mb-1">Masuk ke Akun Anda</h2>
                <p class="text-[14px] leading-normal text-gray-500">Silakan masukkan detail akun Anda untuk melanjutkan belanja.</p>
            </div>

            {{-- Form --}}
            <form action="{{ route('login') }}" method="POST" class="space-y-5">
                @csrf

                {{-- Email --}}
                <div class="space-y-1">
                    <label class="text-[14px] font-semibold text-gray-700" for="email">Email</label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-[20px]">mail</span>
                        <input id="email" name="email" type="email" autocomplete="email" required
                               value="{{ old('email') }}"
                               placeholder="nama@email.com"
                               class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-lg text-[16px] text-gray-900 placeholder-gray-400 focus:outline-none focus:border-green-500 focus:ring-4 focus:ring-green-500/10 login-input-transition">
                    </div>
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Password --}}
                <div class="space-y-1">
                    <div class="flex justify-between items-center">
                        <label class="text-[14px] font-semibold text-gray-700" for="password">Kata Sandi</label>
                        @if (Route::has('password.request'))
                            <a class="text-[12px] font-medium text-green-600 hover:underline" href="{{ route('password.request') }}">Lupa Kata Sandi?</a>
                        @endif
                    </div>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-[20px]">lock</span>
                        <input id="password" name="password" type="password" autocomplete="current-password" required
                               placeholder="••••••••"
                               class="w-full pl-10 pr-12 py-3 bg-gray-50 border border-gray-200 rounded-lg text-[16px] text-gray-900 placeholder-gray-400 focus:outline-none focus:border-green-500 focus:ring-4 focus:ring-green-500/10 login-input-transition">
                        <button type="button" onclick="togglePassword()"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-700 transition-colors">
                            <span class="material-symbols-outlined text-[20px]" id="pass-icon">visibility</span>
                        </button>
                    </div>
                    @error('password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Remember Me --}}
                <div class="flex items-center gap-2">
                    <input id="remember_me" name="remember" type="checkbox"
                           class="w-5 h-5 rounded border-gray-300 text-green-600 focus:ring-green-500">
                    <label class="text-[14px] text-gray-500" for="remember_me">Ingat saya untuk sesi berikutnya</label>
                </div>

                {{-- Submit --}}
                <button type="submit"
                        class="w-full py-3 bg-green-700 text-white font-semibold text-[16px] rounded-lg shadow-sm hover:bg-green-800 transition-all active:scale-[0.98]">
                    Masuk Sekarang
                </button>
            </form>

            {{-- Divider --}}
            <div class="relative my-8 text-center">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-gray-200"></div>
                </div>
                <span class="relative px-4 bg-white text-[12px] font-medium text-gray-500">atau masuk dengan</span>
            </div>

            {{-- Google --}}
            <a href="{{ route('google.redirect') }}"
               class="w-full flex items-center justify-center gap-3 py-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors active:scale-[0.98] group">
                <svg class="w-5 h-5" viewBox="0 0 24 24">
                    <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                    <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                    <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                    <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                </svg>
                <span class="font-semibold text-[16px] text-gray-700 group-hover:text-green-700 transition-colors">Masuk dengan Google</span>
            </a>

            {{-- Register --}}
            <p class="mt-8 text-center text-[14px] text-gray-500">
                Belum memiliki akun?
                <a href="{{ route('register') }}" class="text-green-700 font-bold hover:underline">Daftar Sekarang</a>
            </p>
        </section>
    </main>

    {{-- Footer --}}
    <footer class="fixed bottom-0 left-0 w-full p-4 text-center">
        <p class="text-[12px] text-gray-400">&copy; {{ date('Y') }} {{ $settings['website_name']->value ?? 'FtwoMart' }}. All rights reserved.</p>
    </footer>
</div>

<script>
    function togglePassword() {
        const passwordInput = document.getElementById('password');
        const icon = document.getElementById('pass-icon');
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            icon.innerText = 'visibility_off';
        } else {
            passwordInput.type = 'password';
            icon.innerText = 'visibility';
        }
    }
</script>
@endsection