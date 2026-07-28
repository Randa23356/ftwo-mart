<?php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OperatorProductController extends Controller
{
	public function __construct()
	{
		$this->middleware(['auth', 'role:operator']);
	}

	public function index()
	{
		$products = Product::with('category')->whereNull('seller_id')->latest()->paginate(15);
		return view('operator.products.index', compact('products'));
	}

	public function trash()
	{
		$products = Product::onlyTrashed()->with('category')->whereNull('seller_id')->latest()->paginate(15);
		return view('operator.products.trash', compact('products'));
	}

	public function restore($id)
	{
		$product = Product::onlyTrashed()->findOrFail($id);
		$this->ensureNotSellerProduct($product);
		$product->restore();

		return back()->with('success', 'Produk berhasil dipulihkan.');
	}

	public function forceDelete($id)
	{
		$product = Product::onlyTrashed()->findOrFail($id);
		$this->ensureNotSellerProduct($product);

		DB::beginTransaction();
		try {
			// Delete all product images
			foreach ($product->images as $image) {
				Storage::disk('public')->delete($image->image_path);
				$image->delete();
			}

			// Delete legacy image if exists
			if ($product->image) {
				Storage::disk('public')->delete($product->image);
			}

			$product->forceDelete();

			DB::commit();
			return back()->with('success', 'Produk berhasil dihapus permanen.');
		} catch (\Exception $e) {
			DB::rollBack();
			return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
		}
	}

	public function create()
	{
		$categories = Category::where('is_active', true)->get();
		return view('operator.products.create', compact('categories'));
	}

	public function store(Request $request)
	{
		$request->validate([
			'name' => 'required|string|max:255',
			'description' => 'required|string',
			'price' => 'nullable|numeric|min:0',
			'stock' => 'nullable|integer|min:0',
			'weight' => 'required|integer|min:1|max:50000', // Weight in grams, max 50kg
			'category_id' => 'required|exists:categories,id',
			'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
			'images' => 'nullable|array|max:10', // Support multiple imagess
			'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:5120',
			'variant_options' => 'nullable|string',
			'variant_combinations' => 'nullable|string',
			'pricing_type' => 'nullable|in:fixed,variant',
			'motif_name' => 'nullable|string|max:255',
			'material_description' => 'nullable|string',
			'origin_region' => 'nullable|string|max:255',
		]);

		DB::beginTransaction();
		try {
			$data = $request->only(['name','description','price','stock','weight','category_id','motif_name','material_description','origin_region']);
			$data['slug'] = Str::slug($request->name);
			$data['is_active'] = true;
			$data['is_featured'] = false;
			$data['pricing_type'] = $request->input('pricing_type', 'fixed');

			// Parse variant_options JSON string into array
			if (!empty($request->variant_options)) {
				$decoded = json_decode($request->variant_options, true);
				if (is_array($decoded) && count($decoded) > 0) {
					$data['variant_options'] = $decoded;
					$data['has_variants'] = true;
				} else {
					$data['variant_options'] = null;
					$data['has_variants'] = false;
				}
			} else {
				$data['variant_options'] = null;
				$data['has_variants'] = false;
			}

			// If pricing_type is variant, set base price/stock to 0
			if ($data['pricing_type'] === 'variant') {
				$data['price'] = 0;
				$data['stock'] = 0;
			} else {
				// Fixed pricing: ensure price and stock are present
				if (empty($data['price']) && $data['price'] !== '0' && $data['price'] !== 0) {
					return back()->withInput()->with('error', 'Harga wajib diisi untuk harga tetap.');
				}
				if ($data['stock'] === null || $data['stock'] === '') {
					return back()->withInput()->with('error', 'Stok wajib diisi untuk harga tetap.');
				}
			}

			// Handle legacy single image upload
			if ($request->hasFile('image')) {
				$imagePath = $request->file('image')->store('products', 'public');
				$data['image'] = $imagePath;
			}

			$data['uploaded_by'] = Auth::id();
			$product = Product::create($data);

			// Save variant combinations
			$this->saveVariantCombinations($product, $request->input('variant_combinations'));

			// Handle multiple images
			if ($request->hasFile('images')) {
				foreach ($request->file('images') as $index => $image) {
					$imagePath = $image->store('products', 'public');

					ProductImage::create([
						'product_id' => $product->id,
						'image_path' => $imagePath,
						'alt_text' => null,
						'sort_order' => $index,
						'is_primary' => $index === 0 // First image is primary
					]);
				}
			}

			DB::commit();
			return redirect()->route('operator.dashboard')
				->with('success', 'Menu/Produk berhasil ditambahkan');

		} catch (\Exception $e) {
			DB::rollBack();
			return back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
		}
	}

	public function edit(Product $product)
	{
		$this->ensureNotSellerProduct($product);
		$categories = Category::where('is_active', true)->get();
		return view('operator.products.edit', compact('product', 'categories'));
	}

	public function update(Request $request, Product $product)
	{
		$this->ensureNotSellerProduct($product);
		$request->validate([
			'name' => 'required|string|max:255',
			'description' => 'required|string',
			'price' => 'nullable|numeric|min:0',
			'stock' => 'nullable|integer|min:0',
			'weight' => 'required|integer|min:1|max:50000', // Weight in grams, max 50kg
			'category_id' => 'required|exists:categories,id',
			'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
			'images' => 'nullable|array|max:10', // Support multiple images
			'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:5120',
			'variant_options' => 'nullable|string',
			'variant_combinations' => 'nullable|string',
			'pricing_type' => 'nullable|in:fixed,variant',
			'motif_name' => 'nullable|string|max:255',
			'material_description' => 'nullable|string',
			'origin_region' => 'nullable|string|max:255',
		]);

		DB::beginTransaction();
		try {
			$data = $request->only(['name','description','price','stock','weight','category_id','motif_name','material_description','origin_region']);
			$data['slug'] = Str::slug($request->name);
			$data['pricing_type'] = $request->input('pricing_type', 'fixed');

			// Parse variant_options JSON string into array
			if (!empty($request->variant_options)) {
				$decoded = json_decode($request->variant_options, true);
				if (is_array($decoded) && count($decoded) > 0) {
					$data['variant_options'] = $decoded;
					$data['has_variants'] = true;
				} else {
					$data['variant_options'] = null;
					$data['has_variants'] = false;
				}
			} else {
				$data['variant_options'] = null;
				$data['has_variants'] = false;
			}

			// If pricing_type is variant, set base price/stock to 0
			if ($data['pricing_type'] === 'variant') {
				$data['price'] = 0;
				$data['stock'] = 0;
			} else {
				// Fixed pricing: ensure price and stock are present
				if (empty($data['price']) && $data['price'] !== '0' && $data['price'] !== 0) {
					return back()->withInput()->with('error', 'Harga wajib diisi untuk harga tetap.');
				}
				if ($data['stock'] === null || $data['stock'] === '') {
					return back()->withInput()->with('error', 'Stok wajib diisi untuk harga tetap.');
				}
			}

			// Handle legacy single image upload
			if ($request->hasFile('image')) {
				if ($product->image) {
					Storage::disk('public')->delete($product->image);
				}
				$imagePath = $request->file('image')->store('products', 'public');
				$data['image'] = $imagePath;
			}

			$product->update($data);

			// Save variant combinations
			$this->saveVariantCombinations($product, $request->input('variant_combinations'));

			// Handle multiple images
			if ($request->hasFile('images')) {
				$existingImagesCount = $product->images()->count();

				foreach ($request->file('images') as $index => $image) {
					$imagePath = $image->store('products', 'public');

					ProductImage::create([
						'product_id' => $product->id,
						'image_path' => $imagePath,
						'alt_text' => null,
						'sort_order' => $existingImagesCount + $index,
						'is_primary' => $existingImagesCount === 0 && $index === 0 // First image is primary if no existing images
					]);
				}
			}

			DB::commit();
			return redirect()->route('operator.products.index')
				->with('success', 'Menu/Produk berhasil diperbarui');

		} catch (\Exception $e) {
			DB::rollBack();
			return back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
		}
	}

	private function saveVariantCombinations(Product $product, ?string $combinationsJson): void
	{
		$product->variantCombinations()->delete();

		if (empty($combinationsJson)) return;

		$combinations = json_decode($combinationsJson, true);
		if (!is_array($combinations)) return;

		foreach ($combinations as $combo) {
			$key = $combo['key'] ?? null;
			$price = $combo['price'] ?? null;
			$stock = $combo['stock'] ?? 0;

			if (empty($key) || $price === '' || $price === null) continue;

			// Normalize key order for consistent JSON
			$normalizedKey = Product::normalizeVariantKey($key);

			$product->variantCombinations()->create([
				'variant_key' => $normalizedKey,
				'price' => (float) $price,
				'stock' => (int) $stock,
			]);
		}
	}

	private function ensureNotSellerProduct(Product $product): void
	{
		if ($product->seller_id !== null) {
			abort(403, 'Operator tidak dapat mengelola produk milik seller.');
		}
	}

	public function destroy(Product $product)
	{
		$this->ensureNotSellerProduct($product);

		// Check permission
		if (!auth()->user()->can('product-delete')) {
			abort(403, 'Anda tidak memiliki izin untuk menghapus produk.');
		}

		DB::beginTransaction();
		try {
			// Soft delete - product will be hidden but data remains for order history
			$product->delete();

			DB::commit();
			return redirect()->route('operator.products.index')
				->with('success', 'Menu/Produk berhasil dihapus');

		} catch (\Exception $e) {
			DB::rollBack();
			return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
		}
	}

	public function deleteImage(Request $request, Product $product)
	{
		$this->ensureNotSellerProduct($product);

		$request->validate([
			'image_id' => 'required|integer|exists:product_images,id'
		]);

		DB::beginTransaction();
		try {
			$image = ProductImage::where('id', $request->image_id)
				->where('product_id', $product->id)
				->first();

			if (!$image) {
				return response()->json(['success' => false, 'message' => 'Gambar tidak ditemukan']);
			}

			// Delete file from storage
			Storage::disk('public')->delete($image->image_path);

			// If this was the primary image, set another image as primary
			$wasPrimary = $image->is_primary;
			$image->delete();

			if ($wasPrimary) {
				$newPrimary = $product->images()->orderBy('sort_order')->first();
				if ($newPrimary) {
					$newPrimary->update(['is_primary' => true]);
				}
			}

			DB::commit();

			if ($request->expectsJson()) {
				return response()->json(['success' => true, 'message' => 'Gambar berhasil dihapus']);
			}

			return back()->with('success', 'Gambar berhasil dihapus');

		} catch (\Exception $e) {
			DB::rollBack();

			if ($request->expectsJson()) {
				return response()->json(['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()]);
			}

			return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
		}
	}

	public function deleteAllImages(Request $request, Product $product)
	{
		$this->ensureNotSellerProduct($product);

		DB::beginTransaction();
		try {
			$deletedCount = 0;

			// Delete all product images
			foreach ($product->images as $image) {
				Storage::disk('public')->delete($image->image_path);
				$image->delete();
				$deletedCount++;
			}

			// Also delete legacy image if exists
			if ($product->image) {
				Storage::disk('public')->delete($product->image);
				$product->update(['image' => null]);
				$deletedCount++;
			}

			DB::commit();

			if ($request->expectsJson()) {
				return response()->json([
					'success' => true,
					'message' => "Berhasil menghapus {$deletedCount} gambar"
				]);
			}

			return back()->with('success', "Berhasil menghapus {$deletedCount} gambar");

		} catch (\Exception $e) {
			DB::rollBack();

			if ($request->expectsJson()) {
				return response()->json(['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()]);
			}

			return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
		}
	}
}


