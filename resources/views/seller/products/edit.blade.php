@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="fixed inset-0 opacity-20 pointer-events-none">
        <div class="absolute inset-0" style="background-image: url(\"data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%2316a34a' fill-opacity='0.06'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E\");"></div>
    </div>

    <div class="relative z-10 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">

        <!-- ===== PAGE HEADER ===== -->
        <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-xl border border-white/20 p-6 sm:p-8 mb-6 sm:mb-8">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div class="flex items-center space-x-4">
                    <div class="bg-gradient-to-br from-green-600 to-emerald-700 p-3 sm:p-4 rounded-2xl shadow-lg flex-shrink-0">
                        <i class="fas fa-edit text-white text-xl sm:text-2xl"></i>
                    </div>
                    <div class="min-w-0">
                        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 truncate">Edit Produk</h1>
                        <p class="text-sm sm:text-base text-gray-500 mt-0.5 truncate">{{ $product->name }}</p>
                    </div>
                </div>
                <nav class="flex items-center space-x-2 text-sm text-gray-500">
                    <a href="{{ route('seller.dashboard') }}" class="hover:text-green-600 transition-colors font-medium">
                        <i class="fas fa-home mr-1"></i> Seller
                    </a>
                    <i class="fas fa-chevron-right text-gray-300 text-xs"></i>
                    <a href="{{ route('seller.products.index') }}" class="hover:text-green-600 transition-colors font-medium">Produk</a>
                    <i class="fas fa-chevron-right text-gray-300 text-xs"></i>
                    <span class="text-green-600 font-semibold">Edit</span>
                </nav>
            </div>
        </div>

        <!-- ===== FORM CARD ===== -->
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
            <div class="bg-gradient-to-r from-green-50 to-emerald-50 px-6 py-4 border-b border-green-100">
                <div class="flex items-center space-x-3">
                    <div class="w-9 h-9 bg-gradient-to-br from-green-600 to-emerald-700 rounded-xl flex items-center justify-center shadow-md">
                        <i class="fas fa-tshirt text-white text-sm"></i>
                    </div>
                    <div>
                        <h2 class="text-base font-bold text-gray-900">Informasi Produk</h2>
                        <p class="text-xs text-gray-500">Edit data produk #{{ $product->id }}</p>
                    </div>
                </div>
            </div>

            <div class="p-6 sm:p-8">
                <form method="POST"
                      action="{{ route('seller.products.update', $product) }}"
                      enctype="multipart/form-data"
                      class="space-y-8"
                      x-data="productForm()">
                    @csrf
                    @method('PUT')

                    @php $inp = 'w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all text-sm bg-gray-50 focus:bg-white'; @endphp

                    <!-- SECTION 1 -->
                    <div>
                        <h3 class="text-sm font-bold text-gray-700 uppercase tracking-wider mb-4 flex items-center gap-2">
                            <span class="w-5 h-5 bg-green-100 rounded-full flex items-center justify-center text-green-700 text-xs font-bold">1</span>
                            Informasi Dasar
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Nama Produk <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="name" value="{{ old('name', $product->name) }}"
                                       placeholder="Contoh: Batik Motif Parang"
                                       class="{{ $inp }}">
                                @error('name')
                                    <p class="text-xs text-red-600 mt-1.5 flex items-center gap-1"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Kategori <span class="text-red-500">*</span>
                                </label>
                                <select name="category_id" class="{{ $inp }}">
                                    <option value="">— Pilih Kategori —</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" @selected(old('category_id', $product->category_id) == $category->id)>{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <p class="text-xs text-red-600 mt-1.5 flex items-center gap-1"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="mt-5">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Deskripsi Produk</label>
                            <textarea name="description" rows="4"
                                      placeholder="Jelaskan detail produk..."
                                      class="{{ $inp }} resize-none">{{ old('description', $product->description) }}</textarea>
                            @error('description')
                                <p class="text-xs text-red-600 mt-1.5 flex items-center gap-1"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <hr class="border-gray-100">

                    <!-- SECTION 2 -->
                    <div>
                        <h3 class="text-sm font-bold text-gray-700 uppercase tracking-wider mb-4 flex items-center gap-2">
                            <span class="w-5 h-5 bg-green-100 rounded-full flex items-center justify-center text-green-700 text-xs font-bold">2</span>
                            Harga, Stok & Varian
                        </h3>

                        <div class="mb-5">
                            <div class="flex gap-6 mb-5">
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="pricing_type" value="fixed"
                                           x-model="pricingType"
                                           class="w-4 h-4 text-green-600 focus:ring-green-500">
                                    <span class="text-sm font-semibold text-gray-700">Harga Tetap</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="pricing_type" value="variant"
                                           x-model="pricingType"
                                           class="w-4 h-4 text-green-600 focus:ring-green-500">
                                    <span class="text-sm font-semibold text-gray-700">Harga per Varian</span>
                                </label>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-5" x-show="pricingType === 'fixed'">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Harga <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-sm font-semibold text-gray-500">Rp</span>
                                    <input type="number" name="price" value="{{ old('price', $product->price) }}" min="0" step="1"
                                           class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all text-sm bg-gray-50 focus:bg-white">
                                </div>
                                @error('price')
                                    <p class="text-xs text-red-600 mt-1.5 flex items-center gap-1"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Stok <span class="text-red-500">*</span></label>
                                <input type="number" name="stock" value="{{ old('stock', $product->stock) }}" min="0" class="{{ $inp }}">
                                @error('stock')
                                    <p class="text-xs text-red-600 mt-1.5 flex items-center gap-1"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Berat (gram)</label>
                                <div class="relative">
                                    <input type="number" name="weight" value="{{ old('weight', $product->weight) }}" min="1" max="50000" class="w-full px-4 py-3 pr-14 border border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all text-sm bg-gray-50 focus:bg-white">
                                    <span class="absolute right-3.5 top-1/2 -translate-y-1/2 text-xs text-gray-400 font-medium">gram</span>
                                </div>
                                <p class="text-xs text-gray-400 mt-1.5 flex items-center gap-1"><i class="fas fa-info-circle text-green-400"></i> Untuk perhitungan ongkir</p>
                                @error('weight')
                                    <p class="text-xs text-red-600 mt-1 flex items-center gap-1"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div x-show="pricingType === 'variant'" class="mb-5 text-sm text-gray-500 bg-gray-50 rounded-xl p-3 border border-gray-200">
                            <i class="fas fa-info-circle mr-1"></i> Harga dan stok diatur per kombinasi varian di bawah.
                        </div>
                        <div x-show="pricingType === 'variant'" class="grid grid-cols-1 sm:grid-cols-3 gap-5 mb-5">
                            <div class="sm:col-start-3">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Berat (gram)</label>
                                <div class="relative">
                                    <input type="number" name="weight" value="{{ old('weight', $product->weight) }}" min="1" max="50000" class="w-full px-4 py-3 pr-14 border border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all text-sm bg-gray-50 focus:bg-white">
                                    <span class="absolute right-3.5 top-1/2 -translate-y-1/2 text-xs text-gray-400 font-medium">gram</span>
                                </div>
                                <p class="text-xs text-gray-400 mt-1.5 flex items-center gap-1"><i class="fas fa-info-circle text-green-400"></i> Untuk perhitungan ongkir</p>
                                @error('weight')
                                    <p class="text-xs text-red-600 mt-1 flex items-center gap-1"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <hr class="border-gray-100">

                    <!-- SECTION 2B: VARIANTS -->
                    <div x-data="{
                        variants: [],
                        newLabel: '',
                        newOption: '',
                        combos: {},
                        init() {
                            const d = document.getElementById('product-variants-data');
                            if (d) { try { this.variants = JSON.parse(d.textContent); } catch(e) {} }
                            const cd = document.getElementById('product-combinations-data');
                            if (cd) {
                                try {
                                    const existing = JSON.parse(cd.textContent);
                                    existing.forEach(c => { this.combos[JSON.stringify(c.key)] = { price: c.price, stock: c.stock }; });
                                } catch(e) {}
                            }
                            this.syncToInput();
                            this.$watch('variants', () => this.syncToInput(), { deep: true });
                        },
                        addVariant() {
                            const label = this.newLabel.trim();
                            if (!label) return;
                            if (this.variants.some(v => v.label === label)) {
                                Swal.fire({ icon: 'warning', title: 'Varian sudah ada', text: 'Gunakan nama varian yang berbeda.', confirmButtonColor: '#10b981' });
                                return;
                            }
                            this.variants.push({ label: label, options: [] });
                            this.newLabel = '';
                        },
                        removeVariant(idx) {
                            this.variants.splice(idx, 1);
                        },
                        addOption(idx) {
                            const opt = this.newOption.trim();
                            if (!opt) return;
                            if (this.variants[idx].options.some(o => o === opt)) {
                                Swal.fire({ icon: 'warning', title: 'Opsi sudah ada', confirmButtonColor: '#10b981' });
                                return;
                            }
                            this.variants[idx].options.push(opt);
                            this.newOption = '';
                        },
                        removeOption(idx, optIdx) {
                            this.variants[idx].options.splice(optIdx, 1);
                        },
                        generateCombinations() {
                            if (this.variants.length === 0) return [];
                            const labels = this.variants.map(v => v.label);
                            const opts = this.variants.map(v => v.options.filter(o => o.trim()));
                            if (opts.some(o => o.length === 0)) return [];
                            let result = [[]];
                            for (let i = 0; i < opts.length; i++) {
                                const tmp = [];
                                for (const combo of result) {
                                    for (const opt of opts[i]) {
                                        const next = { ...combo, [labels[i]]: opt };
                                        tmp.push(next);
                                    }
                                }
                                result = tmp;
                            }
                            return result;
                        },
                        getComboKey(combo) {
                            const sorted = {};
                            Object.keys(combo).sort().forEach(k => { sorted[k] = combo[k]; });
                            return JSON.stringify(sorted);
                        },
                        comboPrice(combo) {
                            const k = this.getComboKey(combo);
                            return this.combos[k]?.price ?? '';
                        },
                        comboStock(combo) {
                            const k = this.getComboKey(combo);
                            return this.combos[k]?.stock ?? '';
                        },
                        setComboPrice(combo, val) {
                            const k = this.getComboKey(combo);
                            if (!this.combos[k]) this.combos[k] = { price: '', stock: '' };
                            this.combos[k].price = val ? parseFloat(val) : '';
                            this.syncCombosInput();
                        },
                        setComboStock(combo, val) {
                            const k = this.getComboKey(combo);
                            if (!this.combos[k]) this.combos[k] = { price: '', stock: '' };
                            this.combos[k].stock = val ? parseInt(val) : '';
                            this.syncCombosInput();
                        },
                        syncToInput() {
                            const el = document.getElementById('variant_options_input');
                            if (el) {
                                const obj = {};
                                this.variants.forEach(v => { obj[v.label] = v.options; });
                                el.value = JSON.stringify(obj);
                            }
                            this.syncCombosInput();
                        },
                        syncCombosInput() {
                            const el = document.getElementById('variant_combinations_input');
                            if (!el) return;
                            const combos = this.generateCombinations();
                            const arr = [];
                            for (const combo of combos) {
                                const k = this.getComboKey(combo);
                                const data = this.combos[k];
                                arr.push({ key: combo, price: data?.price ?? '', stock: data?.stock ?? '' });
                            }
                            el.value = JSON.stringify(arr);
                        }
                    }">
                        <h3 class="text-sm font-bold text-gray-700 uppercase tracking-wider mb-4 flex items-center gap-2">
                            <span class="w-5 h-5 bg-green-100 rounded-full flex items-center justify-center text-green-700 text-xs font-bold">5</span>
                            Varian Produk
                        </h3>
                        <p class="text-xs text-gray-500 mb-4">Tambahkan varian seperti Ukuran, Warna, Tipe, Material, dll. (opsional)</p>

                        <script type="application/json" id="product-variants-data">
                            @json(collect($product->variant_options ?? [])->map(fn($opts, $label) => ['label' => $label, 'options' => $opts])->values()->toArray())
                        </script>
                        <script type="application/json" id="product-combinations-data">
                            @json($product->variantCombinations->map(fn($c) => ['key' => $c->variant_key, 'price' => (float) $c->price, 'stock' => $c->stock])->values()->toArray())
                        </script>

                        <template x-for="(variant, idx) in variants" :key="idx">
                            <div class="bg-gray-50 rounded-xl p-4 mb-4 border border-gray-200">
                                <div class="flex items-center justify-between mb-3">
                                    <span class="text-sm font-bold text-gray-800" x-text="variant.label"></span>
                                    <button type="button" @click="removeVariant(idx)" class="text-red-400 hover:text-red-600 text-xs font-semibold">
                                        <i class="fas fa-trash mr-1"></i> Hapus
                                    </button>
                                </div>
                                <div class="flex flex-wrap gap-2 mb-3">
                                    <template x-for="(opt, optIdx) in variant.options" :key="optIdx">
                                        <span class="inline-flex items-center gap-1 bg-white border border-gray-200 rounded-full px-3 py-1.5 text-xs font-medium text-gray-700 shadow-sm">
                                            <span x-text="opt"></span>
                                            <button type="button" @click="removeOption(idx, optIdx)" class="text-gray-400 hover:text-red-500 ml-1 leading-none">&times;</button>
                                        </span>
                                    </template>
                                    <span x-show="variant.options.length === 0" class="text-xs text-gray-400 italic">Belum ada pilihan</span>
                                </div>
                                <div class="flex gap-2">
                                    <input type="text" x-model="newOption" @keydown.enter.prevent="addOption(idx)"
                                           class="flex-1 px-3 py-2 rounded-lg border border-gray-200 text-sm focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                           placeholder="Tambah pilihan...">
                                    <button type="button" @click="addOption(idx)"
                                            class="px-4 py-2 bg-green-100 text-green-700 rounded-lg text-sm font-semibold hover:bg-green-200 transition-colors">
                                        Tambah
                                    </button>
                                </div>
                            </div>
                        </template>

                        <div class="flex gap-2 mb-4">
                            <input type="text" x-model="newLabel" @keydown.enter.prevent="addVariant()"
                                   class="flex-1 px-4 py-3 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-green-500 focus:border-transparent bg-gray-50"
                                   placeholder="Nama varian (contoh: Ukuran, Warna, Tipe)">
                            <button type="button" @click="addVariant()"
                                    class="px-5 py-3 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-xl text-sm font-semibold hover:from-green-700 hover:to-emerald-700 transition-all shadow-md whitespace-nowrap">
                                <i class="fas fa-plus mr-1"></i> Tambah Varian
                            </button>
                        </div>

                        <template x-if="generateCombinations().length > 0">
                            <div class="mt-6 border-t pt-5">
                                <h4 class="text-sm font-bold text-gray-700 mb-2">Harga & Stok per Kombinasi</h4>
                                <p class="text-xs text-gray-500 mb-4">Atur harga dan stok untuk setiap kombinasi varian.</p>
                                <div class="overflow-x-auto rounded-xl border border-gray-200">
                                    <table class="w-full text-sm">
                                        <thead class="bg-gray-50 border-b">
                                            <tr>
                                                <template x-for="v in variants" :key="'h-'+v.label">
                                                    <th class="text-left px-4 py-3 text-xs font-bold text-gray-600 uppercase tracking-wider" x-text="v.label"></th>
                                                </template>
                                                <th class="text-left px-4 py-3 text-xs font-bold text-gray-600 uppercase tracking-wider">Harga (Rp)</th>
                                                <th class="text-left px-4 py-3 text-xs font-bold text-gray-600 uppercase tracking-wider">Stok</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-100">
                                            <template x-for="(combo, ci) in generateCombinations()" :key="ci">
                                                <tr class="hover:bg-gray-50 transition-colors">
                                                    <template x-for="v in variants" :key="'c-'+v.label+'-'+ci">
                                                        <td class="px-4 py-3 text-xs text-gray-700 font-medium" x-text="combo[v.label]"></td>
                                                    </template>
                                                    <td class="px-4 py-3">
                                                        <input type="number" :value="comboPrice(combo)" @input="setComboPrice(combo, $event.target.value)" min="0"
                                                               class="w-full px-3 py-2 border border-gray-200 rounded-lg text-xs focus:ring-2 focus:ring-green-500 focus:border-transparent" placeholder="0">
                                                    </td>
                                                    <td class="px-4 py-3">
                                                        <input type="number" :value="comboStock(combo)" @input="setComboStock(combo, $event.target.value)" min="0"
                                                               class="w-full px-3 py-2 border border-gray-200 rounded-lg text-xs focus:ring-2 focus:ring-green-500 focus:border-transparent" placeholder="0">
                                                    </td>
                                                </tr>
                                            </template>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </template>

                        <input type="hidden" name="variant_options" id="variant_options_input" value="">
                        <input type="hidden" name="variant_combinations" id="variant_combinations_input" value="">
                    </div>

                    <hr class="border-gray-100">

                    <!-- SECTION 2C: DETAILS -->
                    <div>
                        <h3 class="text-sm font-bold text-gray-700 uppercase tracking-wider mb-4 flex items-center gap-2">
                            <span class="w-5 h-5 bg-green-100 rounded-full flex items-center justify-center text-green-700 text-xs font-bold">6</span>
                            Detail Tambahan
                        </h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Motif</label>
                                <input type="text" name="motif_name" value="{{ old('motif_name', $product->motif_name) }}"
                                       placeholder="Contoh: Parang, Mega Mendung"
                                       class="{{ $inp }}">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Asal Daerah</label>
                                <input type="text" name="origin_region" value="{{ old('origin_region', $product->origin_region) }}"
                                       placeholder="Contoh: Solo, Yogyakarta, Pekalongan"
                                       class="{{ $inp }}">
                            </div>
                        </div>
                    </div>

                    <hr class="border-gray-100">

                    <!-- SECTION 3: IMAGES -->
                    <div>
                        <h3 class="text-sm font-bold text-gray-700 uppercase tracking-wider mb-4 flex items-center gap-2">
                            <span class="w-5 h-5 bg-green-100 rounded-full flex items-center justify-center text-green-700 text-xs font-bold">3</span>
                            Foto Produk
                        </h3>

                        @if($product->images->count() > 0)
                            <div class="mb-5 p-4 bg-gray-50 rounded-xl border border-gray-100">
                                <div class="flex items-center justify-between mb-3">
                                    <p class="text-sm font-semibold text-gray-700">
                                        <i class="fas fa-images mr-1.5 text-green-500"></i>
                                        Gambar Saat Ini ({{ $product->images->count() }})
                                    </p>
                                    <button type="button" onclick="deleteAllImages()"
                                            class="text-xs font-semibold text-red-500 hover:text-red-700">
                                        <i class="fas fa-trash mr-0.5"></i> Hapus Semua
                                    </button>
                                </div>
                                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                                    @foreach($product->images as $image)
                                        <div class="relative group">
                                            <div class="relative overflow-hidden rounded-xl border-2 {{ $image->is_primary ? 'border-green-400' : 'border-gray-200' }}">
                                                <img src="{{ $image->image_url }}" alt="{{ $image->formatted_alt_text }}"
                                                     class="w-full h-24 object-cover">
                                                @if($image->is_primary)
                                                    <div class="absolute top-1.5 left-1.5 bg-green-600 text-white text-xs px-2 py-0.5 rounded-full font-semibold shadow">Utama</div>
                                                @endif
                                            </div>
                                            <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity rounded-xl flex items-center justify-center">
                                                <button type="button" onclick="deleteImage({{ $image->id }})"
                                                        class="bg-red-600 text-white p-2 rounded-full shadow hover:bg-red-700 transition-colors">
                                                    <i class="fas fa-trash text-xs"></i>
                                                </button>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <p class="text-sm font-semibold text-gray-700 mb-3">Tambah Gambar Baru</p>
                        <div class="border-2 border-dashed border-green-200 rounded-2xl p-6 text-center bg-green-50/30 hover:bg-green-50 hover:border-green-400 transition-all duration-200">
                            <div class="text-center">
                                <div class="w-14 h-14 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                    <i class="fas fa-cloud-upload-alt text-green-500 text-2xl"></i>
                                </div>
                                <label for="images" class="cursor-pointer text-sm font-semibold text-green-700 hover:text-green-800">
                                    Klik untuk upload gambar baru
                                </label>
                                <span class="text-sm text-gray-500"> atau drag & drop</span>
                                <input type="file" id="images" name="images[]" multiple accept="image/*"
                                       @change="handleImageUpload($event)" class="hidden">
                                <p class="text-xs text-gray-500 mt-2">PNG, JPG, GIF — max 5MB. Maks. 10 gambar total.</p>
                            </div>
                        </div>

                        <div x-show="images.length > 0" x-cloak class="mt-4">
                            <p class="text-sm font-semibold text-gray-700 mb-3">
                                <i class="fas fa-images mr-1.5 text-green-500"></i>
                                Preview Gambar Baru (<span x-text="images.length"></span>)
                            </p>
                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                                <template x-for="(image, index) in images" :key="index">
                                    <div class="relative group">
                                        <div class="relative overflow-hidden rounded-xl border-2"
                                             :class="index === primaryImageIndex ? 'border-green-400' : 'border-gray-200'">
                                            <img :src="image.url" :alt="'Preview ' + (index + 1)" class="w-full h-24 object-cover">
                                            <div x-show="index === primaryImageIndex"
                                                 class="absolute top-1.5 left-1.5 bg-green-600 text-white text-xs px-2 py-0.5 rounded-full font-semibold shadow">
                                                Utama Baru
                                            </div>
                                        </div>
                                        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity rounded-xl flex items-center justify-center gap-2">
                                            <button type="button" @click="setPrimaryImage(index)"
                                                    class="text-white p-2 rounded-full shadow transition-colors"
                                                    :class="index === primaryImageIndex ? 'bg-green-600 hover:bg-green-700' : 'bg-gray-600 hover:bg-gray-700'">
                                                <i class="fas fa-star text-xs"></i>
                                            </button>
                                            <button type="button" @click="removeImage(index)"
                                                    class="bg-red-600 text-white p-2 rounded-full hover:bg-red-700 transition-colors shadow">
                                                <i class="fas fa-trash text-xs"></i>
                                            </button>
                                        </div>
                                        <input type="text" :name="'image_alt_texts[' + index + ']'" x-model="image.altText"
                                               placeholder="Deskripsi (opsional)"
                                               class="w-full mt-2 text-xs px-2 py-1.5 border border-gray-200 rounded-lg focus:ring-green-500 focus:border-green-500">
                                    </div>
                                </template>
                            </div>
                            <input type="hidden" name="primary_image_index" :value="primaryImageIndex">
                        </div>

                        @error('images')
                            <p class="text-xs text-red-600 mt-2 flex items-center gap-1"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>
                        @enderror
                        @error('images.*')
                            <p class="text-xs text-red-600 mt-2 flex items-center gap-1"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>
                        @enderror
                    </div>

                    <hr class="border-gray-100">

                    <!-- SECTION 4 -->
                    <div>
                        <h3 class="text-sm font-bold text-gray-700 uppercase tracking-wider mb-4 flex items-center gap-2">
                            <span class="w-5 h-5 bg-green-100 rounded-full flex items-center justify-center text-green-700 text-xs font-bold">4</span>
                            Visibilitas
                        </h3>
                        <div class="grid grid-cols-1 gap-4">
                            <label class="flex items-start gap-4 p-4 border-2 rounded-xl cursor-pointer hover:border-green-400 transition-all duration-200
                                {{ old('is_active', $product->is_active) ? 'border-green-300 bg-green-50' : 'border-gray-200 bg-white' }}"
                                   id="active-label">
                                <input type="checkbox" name="is_active" value="1"
                                       @checked(old('is_active', $product->is_active))
                                       class="mt-0.5 w-4 h-4 rounded border-gray-300 text-green-600 focus:ring-green-500"
                                       onchange="toggleLabelStyle('active-label', this)">
                                <div>
                                    <p class="text-sm font-semibold text-gray-800">Produk Aktif</p>
                                    <p class="text-xs text-gray-500 mt-0.5">Tampil di katalog dan bisa dibeli</p>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- SUBMIT -->
                    <div class="flex flex-col sm:flex-row items-center justify-end gap-3 pt-6 border-t border-gray-100">
                        <a href="{{ route('seller.products.index') }}"
                           class="w-full sm:w-auto text-center px-6 py-3 border-2 border-gray-200 rounded-xl text-gray-600 hover:bg-gray-50 hover:border-gray-300 transition-all duration-200 text-sm font-semibold">
                            <i class="fas fa-times mr-2"></i> Batal
                        </a>
                        <button type="submit"
                                class="w-full sm:w-auto px-8 py-3 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white rounded-xl transition-all duration-300 hover:shadow-lg hover:-translate-y-0.5 text-sm font-semibold shadow-md">
                            <i class="fas fa-save mr-2"></i> Update Produk
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 max-w-sm mx-4">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Konfirmasi Hapus</h3>
        <p id="deleteMessage" class="text-sm text-gray-600 mb-6"></p>
        <div class="flex justify-end space-x-3">
            <button onclick="closeDeleteModal()" class="px-4 py-2 text-gray-600 hover:text-gray-800">Batal</button>
            <button id="confirmDeleteBtn" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">Hapus</button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function productForm() {
    return {
        pricingType: '{{ old('pricing_type', $product->pricing_type ?? 'fixed') }}',
        images: [],
        primaryImageIndex: null,

        init() {
            if (this.pricingType !== 'variant') this.pricingType = 'fixed';
        },

        handleImageUpload(event) {
            const files = Array.from(event.target.files);
            files.forEach((file) => {
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        this.images.push({ file, url: e.target.result, altText: '' });
                        if (this.primaryImageIndex === null && this.images.length === 1) {
                            this.primaryImageIndex = 0;
                        }
                    };
                    reader.readAsDataURL(file);
                }
            });
        },

        removeImage(index) {
            this.images.splice(index, 1);
            if (this.primaryImageIndex >= this.images.length) {
                this.primaryImageIndex = Math.max(0, this.images.length - 1);
            }
            if (this.images.length === 0) {
                this.primaryImageIndex = null;
            }
        },

        setPrimaryImage(index) {
            this.primaryImageIndex = index;
        }
    };
}

function toggleLabelStyle(labelId, checkbox, color = 'green') {
    const label = document.getElementById(labelId);
    if (checkbox.checked) {
        label.classList.remove('border-gray-200', 'bg-white');
        label.classList.add(`border-${color}-300`, `bg-${color}-50`);
    } else {
        label.classList.remove(`border-${color}-300`, `bg-${color}-50`);
        label.classList.add('border-gray-200', 'bg-white');
    }
}

let currentDeleteAction = null;

function deleteImage(imageId) {
    document.getElementById('deleteMessage').textContent = 'Apakah Anda yakin ingin menghapus gambar ini?';
    currentDeleteAction = () => executeDeleteImage(imageId);
    document.getElementById('deleteModal').classList.remove('hidden');
    document.getElementById('deleteModal').classList.add('flex');
}

function deleteAllImages() {
    document.getElementById('deleteMessage').textContent = 'Apakah Anda yakin ingin menghapus SEMUA gambar? Tindakan ini tidak dapat dibatalkan.';
    currentDeleteAction = () => executeDeleteAllImages();
    document.getElementById('deleteModal').classList.remove('hidden');
    document.getElementById('deleteModal').classList.add('flex');
}

function closeDeleteModal() {
    currentDeleteAction = null;
    document.getElementById('deleteModal').classList.add('hidden');
    document.getElementById('deleteModal').classList.remove('flex');
}

document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
    if (currentDeleteAction) {
        currentDeleteAction();
    }
    closeDeleteModal();
});

function executeDeleteImage(imageId) {
    fetch('{{ route('seller.products.delete-image', [$product, '__ID__']) }}'.replace('__ID__', imageId), {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({ icon: 'success', title: 'Berhasil', text: data.message, confirmButtonColor: '#10b981' })
                .then(() => window.location.reload());
        } else {
            Swal.fire({ icon: 'error', title: 'Gagal', text: data.message || 'Terjadi kesalahan', confirmButtonColor: '#10b981' });
        }
    })
    .catch(() => {
        Swal.fire({ icon: 'error', title: 'Gagal', text: 'Terjadi kesalahan saat menghapus gambar', confirmButtonColor: '#10b981' });
    });
}

function executeDeleteAllImages() {
    fetch(`{{ route('seller.products.delete-all-images', $product) }}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({ icon: 'success', title: 'Berhasil', text: data.message, confirmButtonColor: '#10b981' })
                .then(() => window.location.reload());
        } else {
            Swal.fire({ icon: 'error', title: 'Gagal', text: data.message || 'Terjadi kesalahan', confirmButtonColor: '#10b981' });
        }
    })
    .catch(() => {
        Swal.fire({ icon: 'error', title: 'Gagal', text: 'Terjadi kesalahan saat menghapus gambar', confirmButtonColor: '#10b981' });
    });
}
</script>
@endpush
