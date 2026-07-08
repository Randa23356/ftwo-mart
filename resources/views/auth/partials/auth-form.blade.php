<!-- Auth Form Partial -->
<div class="auth-container">
    <!-- Left Side - Branding & Welcome -->
    <div class="auth-branding {{ $errors->has('name') || $errors->has('phone') || $errors->has('address') ? 'show-register' : '' }}">
        <div class="branding-content">
            <div class="brand-logo">
                <i class="fas fa-tshirt"></i>
            </div>
            <h1 class="brand-name">FtwoMart</h1>
            <p class="brand-tagline">Kearifan Lokal, Kualitas Global</p>
            
            <div class="welcome-message login-welcome">
                <h2>Selamat Datang Kembali!</h2>
                <p>Masuk untuk melanjutkan perjalanan Anda bersama FtwoMart</p>
            </div>
            
            <div class="welcome-message register-welcome">
                <h2>Bergabunglah Dengan Kami!</h2>
                <p>Daftar sekarang dan temukan berbagai produk berkualitas</p>
            </div>
            
            <div class="decorative-pattern"></div>
        </div>
    </div>

    <!-- Right Side - Forms -->
    <div class="auth-forms">
        <!-- Login Form -->
        <div class="form-wrapper login-form {{ $errors->has('name') || $errors->has('phone') || $errors->has('address') ? 'hidden' : 'active' }}">
            <div class="form-header">
                <h2>Masuk</h2>
                <p>Silakan masuk ke akun Anda</p>
            </div>

            <form action="{{ route('login') }}" method="POST">
                @csrf
                
                <div class="input-group">
                    <div class="input-icon">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <input type="email" name="email" value="{{ old('email') }}" required placeholder=" ">
                    <label>Alamat Email</label>
                </div>

                <div class="input-group">
                    <div class="input-icon">
                        <i class="fas fa-lock"></i>
                    </div>
                    <input type="password" name="password" required placeholder=" ">
                    <label>Password</label>
                </div>

                <div class="form-options">
                    <label class="checkbox-label">
                        <input type="checkbox" name="remember">
                        <span>Ingat saya</span>
                    </label>
                    <a href="{{ route('password.request') }}" class="forgot-link">Lupa password?</a>
                </div>

                @if ($errors->any() && !$errors->has('name') && !$errors->has('phone') && !$errors->has('address'))
                    <div class="error-alert">
                        <i class="fas fa-exclamation-circle"></i>
                        <div>
                            @foreach ($errors->all() as $error)
                                <p>{{ $error }}</p>
                            @endforeach
                        </div>
                    </div>
                @endif

                <button type="submit" class="submit-btn">
                    <span>Masuk</span>
                    <i class="fas fa-arrow-right"></i>
                </button>

                <div class="form-footer">
                    <p>Belum punya akun? <a href="{{ route('register') }}" class="toggle-form" data-target="register">Daftar sekarang</a></p>
                </div>
            </form>
        </div>
    </div>
</div>

@push('styles')
<link rel="stylesheet" href="{{ asset('css/auth-style.css') }}">
@endpush

@push('scripts')
<script src="{{ asset('js/auth-script.js') }}"></script>
@endpush
