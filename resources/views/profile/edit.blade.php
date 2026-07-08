@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 via-white to-green-50">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8 lg:py-12">
        <div class="max-w-6xl mx-auto">
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
                            <a href="{{ route('profile.show', Auth::user()->slug) }}" class="inline-flex items-center text-xs sm:text-sm font-medium text-gray-600 hover:text-green-600 transition-all duration-200 hover:scale-105">
                                <i class="fas fa-user mr-1.5 sm:mr-2 text-green-500"></i>
                                <span class="hidden sm:inline">Profile {{ Auth::user()->name }}</span>
                                <span class="sm:hidden">Profile {{ Str::limit(Auth::user()->name, 8) }}</span>
                            </a>
                        </div>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-gray-400 mx-2 text-xs sm:text-sm animate-pulse"></i>
                            <span class="ml-1 text-xs sm:text-sm font-medium text-gray-700 md:ml-2 bg-green-50 px-3 py-1 rounded-full">Edit Profile</span>
                        </div>
                    </li>
                </ol>
            </nav>

            <!-- Header -->
            <div class="mb-8 sm:mb-12">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-900 mb-2">Profil Saya</h1>
                        <p class="text-sm sm:text-base text-gray-600">Kelola informasi profil dan pengaturan akun Anda</p>
                    </div>
                    <div class="mt-4 sm:mt-0">
                        <a href="{{ route('profile.show', Auth::user()->slug) }}"
                           class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition-all duration-200 text-sm font-medium">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Kembali ke Profil
                        </a>
                    </div>
                </div>
            </div>

        @if (session('success'))
            <div class="bg-green-50 border border-green-200 text-green-800 px-4 sm:px-6 py-4 rounded-2xl mb-6 flex items-center animate-fade-in shadow-lg">
                <div class="bg-green-100 p-2 rounded-full mr-3">
                    <i class="fas fa-check-circle text-green-600"></i>
                </div>
                <span class="text-sm sm:text-base font-medium">{{ session('success') }}</span>
                <button onclick="this.parentElement.style.display='none'" class="ml-auto text-green-600 hover:text-green-800 transition-colors">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-50 border border-red-200 text-red-800 px-4 sm:px-6 py-4 rounded-2xl mb-6 flex items-center animate-fade-in shadow-lg">
                <div class="bg-red-100 p-2 rounded-full mr-3">
                    <i class="fas fa-exclamation-circle text-red-600"></i>
                </div>
                <span class="text-sm sm:text-base font-medium">{{ session('error') }}</span>
                <button onclick="this.parentElement.style.display='none'" class="ml-auto text-red-600 hover:text-red-800 transition-colors">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        @endif

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6 lg:gap-8">
            <!-- Profile Photo Section -->
            <div class="xl:col-span-1 space-y-6 lg:space-y-8">
                <div class="bg-white p-6 sm:p-8 rounded-2xl shadow-lg border border-gray-100 profile-card-hover">
                    <h2 class="text-lg sm:text-xl font-bold text-gray-900 mb-6 flex items-center">
                        <div class="w-10 h-10 bg-green-100 rounded-xl flex items-center justify-center mr-3">
                            <i class="fas fa-camera text-green-600"></i>
                        </div>
                        Foto Profil
                    </h2>

                    <div class="text-center">
                        <div class="mb-6">
                            <img id="profile-preview"
                                 src="{{ $user->profile_photo_url }}"
                                 alt="{{ $user->name }}"
                                 class="profile-avatar w-32 h-32 sm:w-40 sm:h-40 rounded-full mx-auto object-cover border-4 border-white shadow-xl">
                        </div>

                        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" id="photo-form">
                            @csrf
                            @method('PATCH')

                            <div class="mb-4">
                                <input type="file"
                                       id="photo"
                                       name="photo"
                                       accept="image/jpeg,image/jpg,image/png"
                                       class="hidden"
                                       onchange="handlePhotoChange(this)"
                                       required>
                                <label for="photo"
                                       class="cursor-pointer bg-green-600 text-white px-4 py-2.5 rounded-xl hover:bg-green-700 transition-all duration-200 hover:shadow-lg hover:-translate-y-0.5 inline-flex items-center text-sm font-medium">
                                    <i class="fas fa-camera mr-2"></i>
                                    Pilih Foto
                                </label>
                                <div id="file-info" class="mt-2 text-sm text-gray-600 hidden"></div>
                            </div>

                            @error('photo')
                                <div class="bg-red-50 border border-red-200 text-red-700 px-3 py-2 rounded-lg text-sm mb-4">
                                    <i class="fas fa-exclamation-triangle mr-1"></i>
                                    {{ $message }}
                                </div>
                            @enderror

                            <button type="submit"
                                    id="upload-btn"
                                    class="w-full bg-green-600 text-white px-4 py-2.5 rounded-xl hover:bg-green-700 transition-all duration-200 hover:shadow-lg hover:-translate-y-0.5 disabled:bg-gray-400 disabled:cursor-not-allowed disabled:shadow-none disabled:translate-y-0 text-sm font-medium"
                                    disabled>
                                <i class="fas fa-save mr-2"></i>
                                <span id="btn-text">Pilih Foto Dulu</span>
                            </button>
                        </form>

                        <div class="mt-4 p-3 bg-gray-50 rounded-lg">
                            <p class="text-xs text-gray-600">
                                <i class="fas fa-info-circle mr-1"></i>
                                File maksimal 1MB. Format: JPG, JPEG, PNG
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Quick Stats -->
                <div class="bg-white p-6 sm:p-8 rounded-2xl shadow-lg border border-gray-100 profile-card-hover">
                    <h3 class="text-lg sm:text-xl font-bold text-gray-900 mb-6 flex items-center">
                        <div class="w-10 h-10 bg-green-100 rounded-xl flex items-center justify-center mr-3">
                            <i class="fas fa-chart-bar text-green-600"></i>
                        </div>
                        Statistik
                    </h3>
                    <div class="space-y-4">
                        <div class="flex justify-between items-center p-3 bg-green-50 rounded-xl hover:bg-green-100 transition-colors">
                            <span class="text-sm sm:text-base font-medium text-gray-700">Total Pesanan</span>
                            <span class="font-bold text-green-600 text-lg">{{ $user->orders()->count() }}</span>
                        </div>
                        <div class="flex justify-between items-center p-3 bg-green-50 rounded-xl hover:bg-green-100 transition-colors">
                            <span class="text-sm sm:text-base font-medium text-gray-700">Percakapan</span>
                            <span class="font-bold text-green-600 text-lg">{{ $user->conversations()->count() }}</span>
                        </div>
                        <div class="flex justify-between items-center p-3 bg-purple-50 rounded-xl hover:bg-purple-100 transition-colors">
                            <span class="text-sm sm:text-base font-medium text-gray-700">Status</span>
                            <span class="px-3 py-1 text-xs rounded-full font-bold {{ $user->presence_status == 'Online' ? 'bg-green-100 text-green-800 border border-green-200' : 'bg-gray-100 text-gray-800 border border-gray-200' }}">
                                {{ $user->presence_status }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Profile Information Section -->
            <div class="xl:col-span-2 space-y-6 lg:space-y-8">
                <div class="bg-white p-6 sm:p-8 rounded-2xl shadow-lg border border-gray-100 profile-card-hover">
                    <h2 class="text-lg sm:text-xl font-bold text-gray-900 mb-6 flex items-center">
                        <div class="w-10 h-10 bg-green-100 rounded-xl flex items-center justify-center mr-3">
                            <i class="fas fa-user text-green-600"></i>
                        </div>
                        Informasi Pribadi
                    </h2>

                    <form action="{{ route('profile.update-info') }}" method="POST">
                        @csrf
                        @method('PATCH')

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                            <!-- Name -->
                            <div>
                                <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Nama Lengkap <span class="text-red-500">*</span>
                                </label>
                                <input type="text"
                                       id="name"
                                       name="name"
                                       value="{{ old('name', $user->name) }}"
                                       class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200 hover:border-gray-400"
                                       required>
                                @error('name')
                                    <div class="bg-red-50 border border-red-200 text-red-700 px-3 py-2 rounded-lg text-sm mt-2">
                                        <i class="fas fa-exclamation-triangle mr-1"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div>
                                <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Email <span class="text-red-500">*</span>
                                </label>
                                <input type="email"
                                       id="email"
                                       name="email"
                                       value="{{ old('email', $user->email) }}"
                                       class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200 hover:border-gray-400"
                                       required>
                                @error('email')
                                    <div class="bg-red-50 border border-red-200 text-red-700 px-3 py-2 rounded-lg text-sm mt-2">
                                        <i class="fas fa-exclamation-triangle mr-1"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Phone -->
                            <div>
                                <label for="phone" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Nomor Telepon
                                </label>
                                <input type="tel"
                                       id="phone"
                                       name="phone"
                                       value="{{ old('phone', $user->phone) }}"
                                       class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200 hover:border-gray-400"
                                       placeholder="08xxxxxxxxxx">
                                @error('phone')
                                    <div class="bg-red-50 border border-red-200 text-red-700 px-3 py-2 rounded-lg text-sm mt-2">
                                        <i class="fas fa-exclamation-triangle mr-1"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Birth Date -->
                            <div>
                                <label for="birth_date" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Tanggal Lahir
                                </label>
                                <input type="date"
                                       id="birth_date"
                                       name="birth_date"
                                       value="{{ old('birth_date', $user->birth_date?->format('Y-m-d')) }}"
                                       class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200 hover:border-gray-400">
                                @error('birth_date')
                                    <div class="bg-red-50 border border-red-200 text-red-700 px-3 py-2 rounded-lg text-sm mt-2">
                                        <i class="fas fa-exclamation-triangle mr-1"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Gender -->
                            <div>
                                <label for="gender" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Jenis Kelamin
                                </label>
                                <select id="gender"
                                        name="gender"
                                        class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200 hover:border-gray-400">
                                    <option value="">Pilih Jenis Kelamin</option>
                                    <option value="male" {{ old('gender', $user->gender) == 'male' ? 'selected' : '' }}>
                                        Laki-laki
                                    </option>
                                    <option value="female" {{ old('gender', $user->gender) == 'female' ? 'selected' : '' }}>
                                        Perempuan
                                    </option>
                                    <option value="other" {{ old('gender', $user->gender) == 'other' ? 'selected' : '' }}>
                                        Lainnya
                                    </option>
                                </select>
                                @error('gender')
                                    <div class="bg-red-50 border border-red-200 text-red-700 px-3 py-2 rounded-lg text-sm mt-2">
                                        <i class="fas fa-exclamation-triangle mr-1"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <!-- Address -->
                        <div class="mt-6">
                            <label for="address" class="block text-sm font-semibold text-gray-700 mb-2">
                                Alamat Lengkap
                            </label>
                            <textarea id="address"
                                      name="address"
                                      rows="3"
                                      class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200 hover:border-gray-400 resize-none"
                                      placeholder="Masukkan alamat lengkap Anda">{{ old('address', $user->address) }}</textarea>
                            @error('address')
                                <div class="bg-red-50 border border-red-200 text-red-700 px-3 py-2 rounded-lg text-sm mt-2">
                                    <i class="fas fa-exclamation-triangle mr-1"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Bio -->
                        <div class="mt-6">
                            <label for="bio" class="block text-sm font-semibold text-gray-700 mb-2">
                                Bio
                            </label>
                            <textarea id="bio"
                                      name="bio"
                                      rows="4"
                                      class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200 hover:border-gray-400 resize-none"
                                      placeholder="Ceritakan sedikit tentang diri Anda..."
                                      maxlength="500">{{ old('bio', $user->bio) }}</textarea>
                            <div class="flex justify-between items-center mt-2">
                                @error('bio')
                                    <div class="bg-red-50 border border-red-200 text-red-700 px-3 py-2 rounded-lg text-sm">
                                        <i class="fas fa-exclamation-triangle mr-1"></i>
                                        {{ $message }}
                                    </div>
                                @else
                                    <span></span>
                                @enderror
                                <span id="bio-count" class="text-sm text-gray-500 font-medium">
                                    {{ strlen(old('bio', $user->bio ?? '')) }}/500
                                </span>
                            </div>
                        </div>

                        <div class="mt-8 flex flex-col sm:flex-row justify-end space-y-3 sm:space-y-0 sm:space-x-3">
                            @if(Auth::user()->isAdmin())
                                <a href="{{ route('admin.dashboard') }}"
                                   class="px-6 py-3 border border-gray-300 rounded-xl text-gray-700 hover:bg-gray-50 transition-all duration-200 text-sm font-medium text-center">
                                    <i class="fas fa-times mr-2"></i>
                                    Batal
                                </a>
                            @elseif(Auth::user()->isOperator())
                                <a href="{{ route('operator.dashboard') }}"
                                   class="px-6 py-3 border border-gray-300 rounded-xl text-gray-700 hover:bg-gray-50 transition-all duration-200 text-sm font-medium text-center">
                                    <i class="fas fa-times mr-2"></i>
                                    Batal
                                </a>
                            @else
                                <a href="{{ route('home') }}"
                                   class="px-6 py-3 border border-gray-300 rounded-xl text-gray-700 hover:bg-gray-50 transition-all duration-200 text-sm font-medium text-center">
                                    <i class="fas fa-times mr-2"></i>
                                    Batal
                                </a>
                            @endif
                            <button type="submit"
                                    class="px-6 py-3 bg-green-600 text-white rounded-xl hover:bg-green-700 transition-all duration-200 hover:shadow-lg hover:-translate-y-0.5 text-sm font-medium">
                                <i class="fas fa-save mr-2"></i>
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Change Password Section -->
                <div class="bg-white p-6 sm:p-8 rounded-2xl shadow-lg border border-gray-100 profile-card-hover">
                    <h2 class="text-lg sm:text-xl font-bold text-gray-900 mb-6 flex items-center">
                        <div class="w-10 h-10 bg-green-100 rounded-xl flex items-center justify-center mr-3">
                            <i class="fas fa-key text-green-600"></i>
                        </div>
                        Ubah Kata Sandi
                    </h2>

                    <form action="{{ route('profile.update-password') }}" method="POST">
                        @csrf
                        @method('PATCH')

                        <div class="space-y-4 sm:space-y-6">
                            <div>
                                <label for="current_password" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Kata Sandi Saat Ini <span class="text-red-500">*</span>
                                </label>
                                <input type="password"
                                       id="current_password"
                                       name="current_password"
                                       class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200 hover:border-gray-400"
                                       required>
                                @error('current_password')
                                    <div class="bg-red-50 border border-red-200 text-red-700 px-3 py-2 rounded-lg text-sm mt-2">
                                        <i class="fas fa-exclamation-triangle mr-1"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div>
                                <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Kata Sandi Baru <span class="text-red-500">*</span>
                                </label>
                                <input type="password"
                                       id="password"
                                       name="password"
                                       class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200 hover:border-gray-400"
                                       required>
                                @error('password')
                                    <div class="bg-red-50 border border-red-200 text-red-700 px-3 py-2 rounded-lg text-sm mt-2">
                                        <i class="fas fa-exclamation-triangle mr-1"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div>
                                <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Konfirmasi Kata Sandi Baru <span class="text-red-500">*</span>
                                </label>
                                <input type="password"
                                       id="password_confirmation"
                                       name="password_confirmation"
                                       class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200 hover:border-gray-400"
                                       required>
                            </div>

                            <div class="pt-4">
                                <button type="submit"
                                        class="bg-amber-600 text-white px-6 py-3 rounded-xl hover:bg-amber-700 transition-all duration-200 hover:shadow-lg hover:-translate-y-0.5 text-sm font-medium">
                                    <i class="fas fa-key mr-2"></i>
                                    Ubah Kata Sandi
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

<script>
function handlePhotoChange(input) {
    const fileInfo = document.getElementById('file-info');
    const uploadBtn = document.getElementById('upload-btn');
    const btnText = document.getElementById('btn-text');

    if (input.files && input.files[0]) {
        const file = input.files[0];

        // Validate file type
        const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
        if (!allowedTypes.includes(file.type)) {
            showNotification('Format file tidak didukung. Gunakan JPG, JPEG, atau PNG.', 'error');
            input.value = '';
            return;
        }

        // Validate file size (1MB = 1024 * 1024 bytes)
        if (file.size > 1024 * 1024) {
            showNotification('Ukuran file terlalu besar. Maksimal 1MB.', 'error');
            input.value = '';
            return;
        }

        // Show file info
        const fileSize = (file.size / 1024).toFixed(1);
        fileInfo.innerHTML = `<i class="fas fa-file-image mr-1"></i>File: ${file.name} (${fileSize} KB)`;
        fileInfo.classList.remove('hidden');

        // Enable upload button
        uploadBtn.disabled = false;
        uploadBtn.classList.remove('bg-gray-400', 'cursor-not-allowed');
        uploadBtn.classList.add('bg-green-600', 'hover:bg-green-700');
        btnText.textContent = 'Upload Foto';

        // Preview image
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('profile-preview').src = e.target.result;
        }
        reader.readAsDataURL(file);
    } else {
        // Reset if no file selected
        fileInfo.classList.add('hidden');
        uploadBtn.disabled = true;
        uploadBtn.classList.add('bg-gray-400', 'cursor-not-allowed');
        uploadBtn.classList.remove('bg-green-600', 'hover:bg-green-700');
        btnText.textContent = 'Pilih Foto Dulu';
    }
}

// Handle form submission
document.getElementById('photo-form').addEventListener('submit', function(e) {
    const uploadBtn = document.getElementById('upload-btn');
    const btnText = document.getElementById('btn-text');

    // Disable button and show loading state
    uploadBtn.disabled = true;
    uploadBtn.classList.add('bg-gray-400', 'cursor-not-allowed');
    uploadBtn.classList.remove('bg-green-600', 'hover:bg-green-700');
    btnText.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Mengupload...';
});

// Bio character counter
document.getElementById('bio').addEventListener('input', function() {
    const bioCount = document.getElementById('bio-count');
    const currentLength = this.value.length;
    bioCount.textContent = `${currentLength}/500`;

    // Change color based on length
    bioCount.classList.remove('text-amber-600', 'text-red-600');

    if (currentLength > 450) {
        bioCount.classList.add('text-amber-600');
    }

    if (currentLength >= 500) {
        bioCount.classList.remove('text-amber-600');
        bioCount.classList.add('text-red-600');
    }
});

// Show notification function
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    const bgColor = type === 'error' ? 'bg-red-50 border-red-200 text-red-800' :
                    type === 'success' ? 'bg-green-50 border-green-200 text-green-800' :
                    'bg-green-50 border-green-200 text-green-800';
    const icon = type === 'error' ? 'fa-exclamation-circle' :
                 type === 'success' ? 'fa-check-circle' :
                 'fa-info-circle';
    const iconColor = type === 'error' ? 'text-red-600' :
                      type === 'success' ? 'text-green-600' :
                      'text-green-600';

    notification.className = `${bgColor} border px-4 sm:px-6 py-4 rounded-2xl mb-6 flex items-center animate-fade-in shadow-lg`;
    notification.innerHTML = `
        <div class="bg-white p-2 rounded-full mr-3">
            <i class="fas ${icon} ${iconColor}"></i>
        </div>
        <span class="text-sm sm:text-base font-medium">${message}</span>
        <button onclick="this.parentElement.style.display='none'" class="ml-auto ${type === 'error' ? 'text-red-600 hover:text-red-800' : type === 'success' ? 'text-green-600 hover:text-green-800' : 'text-green-600 hover:text-green-800'} transition-colors">
            <i class="fas fa-times"></i>
        </button>
    `;

    // Insert at the top of the container
    const container = document.querySelector('.max-w-6xl');
    const firstChild = container.firstChild;
    container.insertBefore(notification, firstChild);

    // Auto-hide after 5 seconds
    setTimeout(() => {
        notification.style.transition = 'opacity 0.5s ease-out';
        notification.style.opacity = '0';
        setTimeout(() => notification.remove(), 500);
    }, 5000);
}

// Auto-hide success and error messages after 5 seconds
document.addEventListener('DOMContentLoaded', function() {
    const alerts = document.querySelectorAll('.bg-green-50, .bg-red-50');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.transition = 'opacity 0.5s ease-out';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500);
        }, 5000);
    });
});
</script>

<style>
@keyframes fade-in {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
}

.animate-fade-in {
    animation: fade-in 0.3s ease-out;
}

/* Missing profile-card-hover class */
.profile-card-hover {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.profile-card-hover:hover {
    transform: translateY(-2px);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.08);
}

/* Custom form enhancements */
.form-input:focus {
    transform: translateY(-1px);
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
}

.form-button:active {
    transform: translateY(0);
}

/* Mobile optimizations */
@media (max-width: 640px) {
    .profile-mobile-full {
        width: 100%;
    }
}
</style>

@endsection
