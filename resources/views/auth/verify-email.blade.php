@extends('layouts.app')

@push('styles')
<style>
    .animate-fade-in {
        animation: fadeIn 0.3s ease-in-out;
        transition: opacity 0.3s ease, transform 0.3s ease;
    }
    
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .progress-container {
        box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.1);
        background: #e5e7eb;
    }
    
    .progress-bar {
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
        min-width: 0;
    }
</style>
@endpush

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        @if(auth()->user() && auth()->user()->hasVerifiedEmail())
            <div class="bg-white shadow rounded-lg p-6">
                <div class="text-center">
                    <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-green-100 mb-4">
                        <i class="fas fa-check-circle text-green-600 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">
                        Email Sudah Diverifikasi!
                    </h3>
                    <p class="text-sm text-gray-600 mb-6">
                        Email Anda sudah terverifikasi. Anda akan diarahkan ke halaman yang sesuai...
                    </p>
                    <div class="mt-6">
                        <a href="{{ route('home') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-700 hover:bg-green-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            Kembali ke Beranda
                        </a>
                    </div>
                </div>
            </div>
            <script>
                setTimeout(function() {
                    window.location.href = '{{ route("home") }}';
                }, 3000);
            </script>
        @else
            <div>
                <div class="mx-auto h-12 w-12 flex items-center justify-center rounded-full bg-green-100">
                    <i class="fas fa-envelope-open text-green-600 text-xl"></i>
                </div>
                <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                    Verifikasi Email Anda
                </h2>
                <p class="mt-2 text-center text-sm text-gray-600">
                    Kami telah mengirimkan link verifikasi ke email Anda
                </p>
            </div>

            <div class="bg-white shadow rounded-lg p-6">
                <div class="text-center">
                    <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-yellow-100 mb-4">
                        <i class="fas fa-exclamation-triangle text-yellow-600"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">
                        Email Belum Diverifikasi
                    </h3>
                <p class="text-sm text-gray-600 mb-4">
                    Sebelum melanjutkan, silakan cek email Anda untuk link verifikasi. 
                    Jika Anda tidak menerima email tersebut, klik tombol di bawah untuk mengirim ulang.
                </p>
                
                <div class="bg-green-50 border border-green-200 rounded-md p-3 mb-6">
                    <h4 class="text-sm font-medium text-green-800 mb-2">Fitur yang Memerlukan Verifikasi Email:</h4>
                    <ul class="text-xs text-green-700 space-y-1">
                        <li>• Pemesanan produk dan checkout</li>
                        <li>• Chat dengan customer service</li>
                        <li>• Menambah produk ke keranjang</li>
                        <li>• Menerima notifikasi pesanan</li>
                    </ul>
                    <p class="text-xs text-green-600 mt-2 font-medium">
                        Anda masih bisa browsing produk dan mengirim pesan contact sebagai guest.
                    </p>
                </div>

                @if (session('status') == 'verification-link-sent')
                    <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-md">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-check-circle text-green-400"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-green-800">
                                    Link verifikasi baru telah dikirim ke alamat email yang Anda daftarkan.
                                </p>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="flex flex-col space-y-3">
                    <form method="POST" action="{{ route('verification.send') }}" id="verification-form">
                        @csrf
                        <button type="submit" id="send-verification-btn"
                                class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-700 hover:bg-green-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors">
                            <i class="fas fa-paper-plane mr-2" id="btn-icon"></i>
                            <span id="btn-text">Kirim Ulang Email Verifikasi</span>
                        </button>
                    </form>

                    <a href="{{ route('home') }}" 
                       class="w-full flex justify-center py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors">
                        <i class="fas fa-home mr-2"></i>
                        Lanjutkan Tanpa Verifikasi
                    </a>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" 
                                class="w-full flex justify-center py-2 px-4 border border-red-300 rounded-md shadow-sm text-sm font-medium text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors">
                            <i class="fas fa-sign-out-alt mr-2"></i>
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="text-center">
            <p class="text-xs text-gray-500">
                Pastikan untuk memeriksa folder spam/junk jika email tidak ditemukan di inbox Anda.
            </p>
                </div>
            </div>
        @endif
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('verification-form');
    const button = document.getElementById('send-verification-btn');
    const buttonText = document.getElementById('btn-text');
    const buttonIcon = document.getElementById('btn-icon');
    
    // Check if email was just sent (from session)
    @if (session('status') == 'verification-link-sent')
        startCooldown();
    @endif
    
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Change button state immediately
        button.disabled = true;
        button.classList.remove('bg-green-700', 'hover:bg-green-800');
        button.classList.add('bg-gray-400', 'cursor-not-allowed');
        buttonIcon.className = 'fas fa-spinner fa-spin mr-2';
        buttonText.textContent = 'Mengirim Email...';
        
        // Submit form
        fetch(form.action, {
            method: 'POST',
            body: new FormData(form),
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Email sent successfully
                buttonIcon.className = 'fas fa-check mr-2';
                buttonText.textContent = 'Email Terkirim!';
                
                // Show success message
                showSuccessMessage(data.message);
                
                // Start cooldown
                setTimeout(() => {
                    startCooldown();
                }, 1000);
            } else {
                // Error occurred
                resetButton();
                showErrorMessage(data.message || 'Terjadi kesalahan');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            resetButton();
            showErrorMessage('Gagal mengirim email verifikasi. Silakan coba lagi.');
        });
    });
    
    function startCooldown() {
        let countdown = 60; // 60 seconds cooldown
        
        button.disabled = true;
        button.classList.remove('bg-green-700', 'hover:bg-green-800');
        button.classList.add('bg-gray-400', 'cursor-not-allowed');
        buttonIcon.className = 'fas fa-clock mr-2';
        
        // Create progress bar container
        const progressContainer = document.createElement('div');
        progressContainer.className = 'progress-container w-full bg-gray-200 rounded-full h-2 mt-2 overflow-hidden';
        
        // Create progress bar
        const progressBar = document.createElement('div');
        progressBar.className = 'progress-bar h-full rounded-full bg-green-500 transition-all duration-1000 ease-linear';
        progressBar.style.width = '100%';
        
        progressContainer.appendChild(progressBar);
        button.parentNode.appendChild(progressContainer);
        
        // Start countdown
        const interval = setInterval(() => {
            buttonText.textContent = `Kirim Ulang dalam ${countdown}s`;
            
            // Calculate and update progress
            const progress = Math.max(0, (countdown / 60) * 100);
            progressBar.style.width = progress + '%';
            
            // Add color transition based on remaining time
            if (countdown <= 10) {
                progressBar.className = 'progress-bar h-full rounded-full bg-gradient-to-r from-red-400 to-red-600 transition-all duration-1000 ease-linear';
            } else if (countdown <= 30) {
                progressBar.className = 'progress-bar h-full rounded-full bg-gradient-to-r from-yellow-400 to-yellow-600 transition-all duration-1000 ease-linear';
            }
            
            countdown--;
            
            if (countdown < 0) {
                clearInterval(interval);
                progressContainer.remove();
                resetButton();
            }
        }, 1000);
    }
    
    function resetButton() {
        button.disabled = false;
        button.classList.remove('bg-gray-400', 'cursor-not-allowed');
        button.classList.add('bg-green-700', 'hover:bg-green-800');
        buttonIcon.className = 'fas fa-paper-plane mr-2';
        buttonText.textContent = 'Kirim Ulang Email Verifikasi';
    }
    
    function showSuccessMessage(message = 'Link verifikasi baru telah dikirim ke alamat email yang Anda daftarkan.') {
        // Remove existing messages
        const existingMessages = document.querySelectorAll('.success-message, .error-message');
        existingMessages.forEach(msg => msg.remove());
        
        const successDiv = document.createElement('div');
        successDiv.className = 'success-message mb-4 p-4 bg-green-50 border border-green-200 rounded-md animate-fade-in';
        successDiv.innerHTML = `
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-check-circle text-green-400"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-green-800">
                        ${message}
                    </p>
                </div>
            </div>
        `;
        
        // Insert before the form
        form.parentNode.insertBefore(successDiv, form);
        
        // Auto remove after 5 seconds
        setTimeout(() => {
            successDiv.style.opacity = '0';
            successDiv.style.transform = 'translateY(-10px)';
            setTimeout(() => successDiv.remove(), 300);
        }, 5000);
    }
    
    function showErrorMessage(message = 'Gagal mengirim email verifikasi. Silakan coba lagi.') {
        // Remove existing messages
        const existingMessages = document.querySelectorAll('.success-message, .error-message');
        existingMessages.forEach(msg => msg.remove());
        
        const errorDiv = document.createElement('div');
        errorDiv.className = 'error-message mb-4 p-4 bg-red-50 border border-red-200 rounded-md animate-fade-in';
        errorDiv.innerHTML = `
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-circle text-red-400"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-red-800">
                        ${message}
                    </p>
                </div>
            </div>
        `;
        
        // Insert before the form
        form.parentNode.insertBefore(errorDiv, form);
        
        // Auto remove after 5 seconds
        setTimeout(() => {
            errorDiv.style.opacity = '0';
            errorDiv.style.transform = 'translateY(-10px)';
            setTimeout(() => errorDiv.remove(), 300);
        }, 5000);
    }
});
</script>

@endsection
