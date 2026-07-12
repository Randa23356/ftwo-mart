@extends('layouts.app')

@section('content')

<!-- Hero Section -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-16">
    <div class="relative bg-gradient-to-br from-green-800 via-green-700 to-emerald-600 rounded-3xl p-10 md:p-14 overflow-hidden shadow-xl text-center">
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-0 right-0 -mt-10 -mr-10 w-40 h-40 bg-white opacity-5 rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 left-0 -mb-10 -ml-10 w-40 h-40 bg-green-500 opacity-10 rounded-full blur-3xl"></div>
        </div>
        <div class="relative z-10">
            <div class="inline-flex items-center gap-2 bg-white/10 backdrop-blur-sm border border-white/20 rounded-full px-5 py-2 mb-8">
                <i class="fas fa-leaf text-green-200 text-xs"></i>
                <span class="text-green-100 text-sm font-medium tracking-wide">Tentang Kami</span>
            </div>
            <h1 id="about-title-editable" class="text-3xl md:text-5xl font-extrabold text-white mb-4 tracking-tight">
                {{ $settings['about_title']->value ?? 'Tentang Kami' }}
            </h1>
            <p class="text-green-100/90 text-lg md:text-xl max-w-2xl mx-auto leading-relaxed">
                Mengenal lebih dekat warisan dan dedikasi di balik setiap produk berkualitas dari FtwoMart.
            </p>
            @auth
                @if(auth()->user()->isAdmin())
                    <button id="save-about-title" class="mt-6 bg-white text-green-700 hover:bg-gray-50 font-bold py-2.5 px-6 rounded-full shadow-lg transition-all hover:scale-105 hover:shadow-xl hidden">
                        <i class="fas fa-save mr-2"></i> Simpan Judul
                    </button>
                @endif
            @endauth
        </div>
    </div>
</div>

<!-- Cerita Kami -->
<section class="py-20 md:py-28 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-20 items-center">
            <!-- Image -->
            <div class="relative group">
                <div class="absolute -inset-4 bg-gradient-to-br from-green-200 to-emerald-100 rounded-[2rem] opacity-0 group-hover:opacity-60 transition-opacity duration-500 blur-xl"></div>
                @if(isset($settings['about_image']) && $settings['about_image']->value)
                    <div class="relative rounded-[2rem] overflow-hidden shadow-2xl group-hover:shadow-3xl transition-shadow duration-500">
                        <img src="{{ Storage::url($settings['about_image']->value) }}" alt="About Us" class="w-full h-auto object-cover transform group-hover:scale-105 transition-transform duration-700">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent"></div>
                    </div>
                @else
                    <div class="relative bg-gradient-to-br from-gray-50 to-green-50 rounded-[2rem] p-12 text-center border border-gray-100 min-h-[420px] flex flex-col items-center justify-center">
                        <div class="w-20 h-20 bg-green-100 rounded-2xl flex items-center justify-center mb-6 shadow-lg shadow-green-100 group-hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-store text-4xl text-green-600"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Terpercaya & Berkualitas</h3>
                        <p class="text-gray-400 text-sm max-w-xs">Setiap produk dipilih dengan teliti untuk memastikan kualitas terbaik</p>
                    </div>
                @endif
            </div>

            <!-- Story -->
            <div>
                <div class="inline-flex items-center gap-2 bg-green-50 border border-green-100 rounded-full px-4 py-1.5 mb-6">
                    <div class="w-1.5 h-1.5 bg-green-500 rounded-full"></div>
                    <span class="text-green-700 text-xs font-bold uppercase tracking-wider">Cerita Kami</span>
                </div>
                <h2 class="text-3xl md:text-4xl font-extrabold text-gray-900 mb-6 leading-tight">Membangun Warisan<br>Kualitas Indonesia</h2>
                <div class="prose prose-lg text-gray-500 leading-relaxed space-y-4">
                    {!! $settings['about_content']->value ?? 'Konten tentang kami belum diatur.' !!}
                </div>

                <!-- Features -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-8">
                    <div class="flex items-start gap-4 p-4 rounded-2xl bg-gray-50 hover:bg-green-50 border border-transparent hover:border-green-100 transition-all duration-300 group">
                        <div class="w-11 h-11 rounded-xl bg-green-100 group-hover:bg-green-200 flex items-center justify-center flex-shrink-0 transition-colors">
                            <i class="fas fa-gem text-green-600 text-sm"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-900 text-sm mb-0.5">Kualitas Premium</h4>
                            <p class="text-xs text-gray-400 leading-relaxed">Produk terbaik untuk kepuasan Anda.</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-4 p-4 rounded-2xl bg-gray-50 hover:bg-green-50 border border-transparent hover:border-green-100 transition-all duration-300 group">
                        <div class="w-11 h-11 rounded-xl bg-green-100 group-hover:bg-green-200 flex items-center justify-center flex-shrink-0 transition-colors">
                            <i class="fas fa-star text-green-600 text-sm"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-900 text-sm mb-0.5">Pilihan Terlengkap</h4>
                            <p class="text-xs text-gray-400 leading-relaxed">Beragam produk untuk semua kebutuhan.</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-4 p-4 rounded-2xl bg-gray-50 hover:bg-green-50 border border-transparent hover:border-green-100 transition-all duration-300 group">
                        <div class="w-11 h-11 rounded-xl bg-green-100 group-hover:bg-green-200 flex items-center justify-center flex-shrink-0 transition-colors">
                            <i class="fas fa-shield-alt text-green-600 text-sm"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-900 text-sm mb-0.5">Garansi Resmi</h4>
                            <p class="text-xs text-gray-400 leading-relaxed">Jaminan keaslian setiap produk.</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-4 p-4 rounded-2xl bg-gray-50 hover:bg-green-50 border border-transparent hover:border-green-100 transition-all duration-300 group">
                        <div class="w-11 h-11 rounded-xl bg-green-100 group-hover:bg-green-200 flex items-center justify-center flex-shrink-0 transition-colors">
                            <i class="fas fa-truck text-green-600 text-sm"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-900 text-sm mb-0.5">Pengiriman Cepat</h4>
                            <p class="text-xs text-gray-400 leading-relaxed">Layanan antar cepat & aman.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="py-16 bg-gradient-to-br from-green-700 via-green-600 to-emerald-600 relative overflow-hidden">
    <div class="absolute inset-0 opacity-10">
        <div class="absolute top-0 left-1/4 w-64 h-64 bg-white rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 right-1/4 w-80 h-80 bg-emerald-300 rounded-full blur-3xl"></div>
    </div>
    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
            <div>
                <div class="text-4xl md:text-5xl font-extrabold text-white mb-2">{{ number_format($totalProducts) }}+</div>
                <p class="text-green-200 text-sm font-medium">Produk Tersedia</p>
            </div>
            <div>
                <div class="text-4xl md:text-5xl font-extrabold text-white mb-2">{{ number_format($totalCompletedOrders) }}</div>
                <p class="text-green-200 text-sm font-medium">Pesanan Selesai</p>
            </div>
            <div>
                <div class="text-4xl md:text-5xl font-extrabold text-white mb-2">{{ number_format($totalCustomers) }}+</div>
                <p class="text-green-200 text-sm font-medium">Pelanggan Terdaftar</p>
            </div>
            <div>
                <div class="text-4xl md:text-5xl font-extrabold text-white mb-2">{{ number_format($avgRating, 1) }}</div>
                <p class="text-green-200 text-sm font-medium">Rating Pelanggan</p>
            </div>
        </div>
    </div>
</section>

<!-- Visi & Misi -->
<section class="py-20 md:py-28 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-2xl mx-auto mb-16">
            <div class="inline-flex items-center gap-2 bg-green-50 border border-green-100 rounded-full px-4 py-1.5 mb-6">
                <div class="w-1.5 h-1.5 bg-green-500 rounded-full"></div>
                <span class="text-green-700 text-xs font-bold uppercase tracking-wider">Visi & Misi</span>
            </div>
            <h2 class="text-3xl md:text-4xl font-extrabold text-gray-900 mb-4">Mendorong Perubahan Positif</h2>
            <p class="text-gray-500 leading-relaxed">Kami percaya setiap produk punya cerita dan setiap pembelian adalah bentuk dukungan terhadap lokal.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Visi -->
            <div class="group bg-white rounded-[2rem] p-8 border border-gray-100 hover:border-green-200 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                <div class="w-14 h-14 bg-green-100 group-hover:bg-green-200 rounded-2xl flex items-center justify-center mb-6 transition-colors">
                    <i class="fas fa-eye text-green-600 text-xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Visi Kami</h3>
                <p class="text-gray-500 text-sm leading-relaxed">Menjadi marketplace terdepan yang menghubungkan produk lokal berkualitas dengan masyarakat Indonesia.</p>
            </div>

            <!-- Misi -->
            <div class="group bg-white rounded-[2rem] p-8 border border-gray-100 hover:border-green-200 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                <div class="w-14 h-14 bg-green-100 group-hover:bg-green-200 rounded-2xl flex items-center justify-center mb-6 transition-colors">
                    <i class="fas fa-bullseye text-green-600 text-xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Misi Kami</h3>
                <p class="text-gray-500 text-sm leading-relaxed">Memberdayakan UMKM lokal dengan teknologi digital dan menyediakan pengalaman belanja terbaik.</p>
            </div>

            <!-- Nilai -->
            <div class="group bg-white rounded-[2rem] p-8 border border-gray-100 hover:border-green-200 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                <div class="w-14 h-14 bg-green-100 group-hover:bg-green-200 rounded-2xl flex items-center justify-center mb-6 transition-colors">
                    <i class="fas fa-heart text-green-600 text-xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Nilai Kami</h3>
                <p class="text-gray-500 text-sm leading-relaxed">Kejujuran, kualitas, dan pelayanan adalah fondasi utama yang selalu kami junjung tinggi.</p>
            </div>
        </div>
    </div>
</section>

<!-- CTA -->
<section class="py-20 md:py-28 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="relative bg-gradient-to-br from-green-700 via-green-600 to-emerald-600 rounded-[2rem] p-10 md:p-16 text-center text-white overflow-hidden shadow-2xl">
            <div class="absolute top-0 right-0 -mt-20 -mr-20 w-64 h-64 bg-white/5 rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 left-0 -mb-20 -ml-20 w-80 h-80 bg-emerald-400/10 rounded-full blur-3xl"></div>
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[500px] h-[500px] bg-white/5 rounded-full blur-3xl"></div>

            <div class="relative z-10">
                <div class="w-16 h-16 bg-white/10 backdrop-blur-sm rounded-2xl flex items-center justify-center mx-auto mb-6 border border-white/20">
                    <i class="fas fa-headset text-white text-2xl"></i>
                </div>
                <h2 class="text-3xl md:text-4xl font-extrabold mb-4">Butuh Bantuan?</h2>
                <p class="text-green-100/90 mb-10 max-w-xl mx-auto text-lg leading-relaxed">
                    Ada pertanyaan atau butuh informasi lebih lanjut? Tim customer service kami siap membantu Anda kapan saja.
                </p>
                <a href="{{ route('contact') }}"
                   class="inline-flex items-center gap-2 px-8 py-4 bg-white hover:bg-gray-50 text-green-700 font-bold rounded-2xl transition-all duration-300 shadow-lg hover:shadow-xl hover:-translate-y-1">
                    <i class="fas fa-comment-dots"></i>
                    <span>Hubungi Kami Sekarang</span>
                    <i class="fas fa-arrow-right text-sm"></i>
                </a>
            </div>
        </div>
    </div>
</section>

@endsection

@auth
    @if(auth()->user()->isAdmin())
        @push('scripts')
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
        @endpush
    @endif
@endauth
