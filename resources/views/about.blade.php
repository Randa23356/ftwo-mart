@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Modern Hero Header -->
    <div class="relative bg-green-700 rounded-3xl p-10 md:p-12 mb-16 overflow-hidden shadow-xl text-center">
        <div class="absolute inset-0 opacity-10 pattern-dots"></div>
        <div class="relative z-10">
            <h1 id="about-title-editable" class="text-3xl md:text-5xl font-extrabold text-white mb-4 tracking-tight flex items-center justify-center gap-3">
                <i class="fas fa-info-circle text-green-200"></i>
                <span>{{ $settings['about_title']->value ?? 'Tentang Kami' }}</span>
            </h1>
            <p class="text-green-100 text-lg md:text-xl font-medium max-w-2xl mx-auto">
                Mengenal lebih dekat warisan dan dedikasi di balik setiap produk FtwoMart.
            </p>
            @auth
                @if(auth()->user()->isAdmin())
                    <button id="save-about-title" class="mt-4 bg-white text-green-600 hover:bg-gray-50 font-bold py-2 px-6 rounded-full shadow-lg transition-transform hover:scale-105 hidden">
                        <i class="fas fa-save mr-2"></i> Simpan Judul
                    </button>
                @endif
            @endauth
        </div>
    </div>

    <!-- Main Content -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-20 mb-20 items-center">
        <!-- Image Section (Left on Desktop) -->
        <div class="relative order-2 lg:order-1">
             <div class="absolute inset-0 bg-green-200 rounded-3xl transform rotate-3 scale-105 opacity-30"></div>
             @if(isset($settings['about_image']) && $settings['about_image']->value)
                <div class="relative rounded-3xl overflow-hidden shadow-2xl transform transition-transform hover:-translate-y-2 duration-500">
                    <img src="{{ Storage::url($settings['about_image']->value) }}" alt="About Us Image" class="w-full h-auto object-cover">
                </div>
            @else
                <div class="relative bg-white rounded-3xl p-12 text-center shadow-xl border border-gray-100 flex flex-col items-center justify-center min-h-[400px]">
                    <div class="w-24 h-24 bg-green-50 rounded-full flex items-center justify-center mb-6">
                        <i class="fas fa-store text-5xl text-green-600"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-3">Terpercaya & Berkualitas</h3>
                    <p class="text-gray-500 max-w-xs mx-auto">Setiap produk dipilih dengan teliti untuk memastikan kualitas terbaik</p>
                </div>
            @endif
        </div>

        <!-- Story Section -->
        <div class="order-1 lg:order-2">
            <h2 class="text-3xl font-bold text-gray-900 mb-6">Cerita Kami</h2>
            <div class="prose prose-lg text-gray-600 leading-relaxed space-y-4">
                {!! $settings['about_content']->value ?? 'Konten tentang kami belum diatur.' !!}
            </div>
            
            <!-- Features Grid -->
            <div class="grid grid-cols-2 gap-6 mt-8">
                <div class="bg-gray-50 p-4 rounded-xl border border-gray-100">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center text-green-600">
                            <i class="fas fa-gem text-xs"></i>
                        </div>
                        <span class="font-bold text-gray-900">Kualitas Premium</span>
                    </div>
                    <p class="text-xs text-gray-500">Produk terbaik untuk kepuasan Anda.</p>
                </div>
                <div class="bg-gray-50 p-4 rounded-xl border border-gray-100">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-8 h-8 rounded-full bg-green-50 flex items-center justify-center text-green-700">
                            <i class="fas fa-star text-xs"></i>
                        </div>
                        <span class="font-bold text-gray-900">Pilihan Terlengkap</span>
                    </div>
                    <p class="text-xs text-gray-500">Beragam produk untuk semua kebutuhan.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Contact CTA -->
    <div class="relative bg-green-700 rounded-3xl p-8 md:p-12 text-center text-white overflow-hidden shadow-2xl mb-12">
        <div class="absolute top-0 right-0 -mt-10 -mr-10 w-40 h-40 bg-white opacity-5 rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 left-0 -mb-10 -ml-10 w-40 h-40 bg-green-500 opacity-10 rounded-full blur-3xl"></div>
        
        <div class="relative z-10">
            <h2 class="text-3xl font-bold mb-4">Butuh Bantuan?</h2>
            <p class="text-green-100 mb-8 max-w-xl mx-auto text-lg">
                Ada pertanyaan atau butuh informasi lebih lanjut? Tim customer service kami siap membantu Anda kapan saja.
            </p>
            <a href="{{ route('contact') }}"
               class="inline-flex items-center px-8 py-3.5 bg-white hover:bg-gray-50 text-green-700 font-bold rounded-xl transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                <i class="fas fa-comment-dots mr-2"></i>
                <span class="tracking-wide">Hubungi Kami Sekarang</span>
            </a>
        </div>
    </div>
</div>

@auth
    @if(auth()->user()->isAdmin())
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const titleElement = document.getElementById('about-title-editable');
                const saveButton = document.getElementById('save-about-title');

                titleElement.addEventListener('click', function() {
                    this.setAttribute('contenteditable', 'true');
                    this.focus();
                    saveButton.classList.remove('hidden');
                });

                saveButton.addEventListener('click', function() {
                    const newValue = titleElement.innerText;

                    fetch('{{ route("admin.settings.inline-update") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            key: 'about_title',
                            value: newValue
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            notify.success('Title updated successfully!', {
                                title: 'Success!',
                                duration: 4000
                            });
                            titleElement.setAttribute('contenteditable', 'false');
                            saveButton.classList.add('hidden');
                        } else {
                            notify.error('Failed to update title.', {
                                title: 'Update Failed',
                                duration: 6000
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        notify.error('An error occurred while updating the title.', {
                            title: 'Connection Error',
                            duration: 6000
                        });
                    });
                });
            });
        </script>
    @endif
@endauth
@endsection
