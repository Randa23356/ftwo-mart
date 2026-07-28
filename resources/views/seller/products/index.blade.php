@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 lg:py-8">
        <div class="bg-white/80 backdrop-blur-xl rounded-2xl lg:rounded-3xl shadow-xl border border-white/20 p-6 lg:p-8 mb-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                <h1 class="text-2xl font-bold text-gray-900"><i class="fas fa-box mr-2 text-green-600"></i>Produk Saya</h1>
                <div class="flex flex-col sm:flex-row sm:items-center gap-3 w-full sm:w-auto">
                    <form method="GET" class="flex flex-1 sm:flex-none gap-2">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari produk..."
                               class="flex-1 px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:ring-2 focus:ring-green-500">
                        <button class="bg-green-600 text-white px-4 py-2.5 rounded-xl text-sm hover:bg-green-700 transition-all flex-shrink-0">
                            <i class="fas fa-search"></i>
                        </button>
                    </form>
                    <a href="{{ route('seller.products.create') }}"
                       class="w-full sm:w-auto bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white font-semibold px-5 py-2.5 rounded-xl transition-all shadow-lg whitespace-nowrap text-sm">
                        <i class="fas fa-plus mr-1"></i> Tambah
                    </a>
                </div>
            </div>

            @if($products->isEmpty())
                <div class="text-center py-12">
                    <i class="fas fa-box-open text-5xl text-gray-300 mb-4"></i>
                    <p class="text-gray-500 text-lg">Belum ada produk</p>
                    <a href="{{ route('seller.products.create') }}" class="text-green-600 font-semibold hover:underline mt-2 inline-block">Upload Produk Pertama →</a>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                    @foreach($products as $product)
                    <div class="bg-white rounded-xl border border-gray-100 overflow-hidden hover:shadow-lg transition-all group">
                        <div class="relative aspect-square bg-gray-100">
                            <img src="{{ $product->image_url }}" alt="{{ $product->name }}"
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            <div class="absolute top-2 right-2">
                                <span class="px-2 py-1 rounded-full text-xs font-bold {{ $product->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $product->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </div>
                        </div>
                        <div class="p-4">
                            <h3 class="font-semibold text-gray-900 truncate">{{ $product->name }}</h3>
                            <p class="text-green-600 font-bold text-lg mt-1">{{ $product->formatted_price }}</p>
                            <p class="text-gray-500 text-xs">Stok: {{ $product->total_stock }} | Terjual: {{ $product->sold_count }}</p>
                            <div class="flex gap-2 mt-3">
                                <a href="{{ route('seller.products.edit', $product) }}"
                                   class="flex-1 text-center bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-semibold py-2 rounded-lg transition-all">
                                    <i class="fas fa-edit mr-1"></i> Edit
                                </a>
                                <form method="POST" action="{{ route('seller.products.toggle-status', $product) }}" class="flex-1">
                                    @csrf @method('PUT')
                                    <button class="w-full text-center {{ $product->is_active ? 'bg-yellow-100 hover:bg-yellow-200 text-yellow-700' : 'bg-green-100 hover:bg-green-200 text-green-700' }} text-xs font-semibold py-2 rounded-lg transition-all">
                                        <i class="fas fa-{{ $product->is_active ? 'pause' : 'play' }} mr-1"></i>
                                        {{ $product->is_active ? 'Nonaktif' : 'Aktif' }}
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('seller.products.destroy', $product) }}" class="flex-1"
                                      onsubmit="return confirm('Yakin hapus produk ini?')">
                                    @csrf @method('DELETE')
                                    <button class="w-full text-center bg-red-100 hover:bg-red-200 text-red-700 text-xs font-semibold py-2 rounded-lg transition-all">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="mt-6">
                    {{ $products->withQueryString()->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
