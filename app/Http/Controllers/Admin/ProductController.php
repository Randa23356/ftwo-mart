<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    public function index()
    {
        $products = Product::with('category')
            ->latest()
            ->paginate(15);

        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::where('is_active', true)->get();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'weight' => 'required|integer|min:1|max:50000', // Weight in grams, max 50kg
            'category_id' => 'required|exists:categories,id',
            'images' => 'nullable|array|max:10', // Max 10 images
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'image_alt_texts' => 'nullable|array',
            'image_alt_texts.*' => 'nullable|string|max:255',
            'primary_image_index' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
            'is_featured' => 'boolean'
        ]);

        DB::beginTransaction();
        try {
            $data = $request->all();
            $data['slug'] = Str::slug($request->name);
            $data['is_active'] = $request->has('is_active');
            $data['is_featured'] = $request->has('is_featured');

            $product = Product::create($data);

            // Handle multiple images
            if ($request->hasFile('images')) {
                $primaryIndex = $request->input('primary_image_index', 0);
                $altTexts = $request->input('image_alt_texts', []);
                
                foreach ($request->file('images') as $index => $image) {
                    $imagePath = $image->store('products', 'public');
                    
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image_path' => $imagePath,
                        'alt_text' => $altTexts[$index] ?? null,
                        'sort_order' => $index,
                        'is_primary' => $index == $primaryIndex
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('admin.products.index')
                ->with('success', 'Produk berhasil ditambahkan');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function edit(Product $product)
    {
        $categories = Category::where('is_active', true)->get();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'weight' => 'required|integer|min:1|max:50000', // Weight in grams, max 50kg
            'category_id' => 'required|exists:categories,id',
            'images' => 'nullable|array|max:10', // Max 10 images
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'image_alt_texts' => 'nullable|array',
            'image_alt_texts.*' => 'nullable|string|max:255',
            'primary_image_index' => 'nullable|integer|min:0',
            'keep_existing_images' => 'nullable|boolean',
            'delete_image_ids' => 'nullable|array',
            'delete_image_ids.*' => 'integer|exists:product_images,id',
            'is_active' => 'boolean',
            'is_featured' => 'boolean'
        ]);

        DB::beginTransaction();
        try {
            $data = $request->all();
            $data['slug'] = Str::slug($request->name);
            $data['is_active'] = $request->has('is_active');
            $data['is_featured'] = $request->has('is_featured');

            $product->update($data);

            // Delete selected images
            if ($request->has('delete_image_ids')) {
                $imagesToDelete = ProductImage::whereIn('id', $request->delete_image_ids)
                    ->where('product_id', $product->id)
                    ->get();
                
                foreach ($imagesToDelete as $imageToDelete) {
                    Storage::disk('public')->delete($imageToDelete->image_path);
                    $imageToDelete->delete();
                }
            }

            // Handle new images
            if ($request->hasFile('images')) {
                $existingImagesCount = $product->images()->count();
                $primaryIndex = $request->input('primary_image_index');
                $altTexts = $request->input('image_alt_texts', []);
                
                // Reset all existing images to non-primary if new primary is being set
                if ($primaryIndex !== null) {
                    $product->images()->update(['is_primary' => false]);
                }
                
                foreach ($request->file('images') as $index => $image) {
                    $imagePath = $image->store('products', 'public');
                    
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image_path' => $imagePath,
                        'alt_text' => $altTexts[$index] ?? null,
                        'sort_order' => $existingImagesCount + $index,
                        'is_primary' => $index == $primaryIndex
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('admin.products.index')
                ->with('success', 'Produk berhasil diupdate');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroy(Product $product)
    {
        DB::beginTransaction();
        try {
            // Soft delete - product will be hidden but data remains for order history
            $product->delete();

            DB::commit();
            return redirect()->route('admin.products.index')
                ->with('success', 'Produk berhasil dihapus');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function trash()
    {
        $products = Product::onlyTrashed()->with('category')->latest()->paginate(15);
        return view('admin.products.trash', compact('products'));
    }

    public function restore($id)
    {
        $product = Product::onlyTrashed()->findOrFail($id);
        $product->restore();

        return back()->with('success', 'Produk berhasil dipulihkan.');
    }

    public function forceDelete($id)
    {
        $product = Product::onlyTrashed()->findOrFail($id);
        
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

    public function toggleStatus(Product $product)
    {
        $product->update(['is_active' => !$product->is_active]);
        
        return response()->json([
            'success' => true,
            'message' => 'Status berhasil diubah',
            'is_active' => $product->is_active
        ]);
    }

    public function toggleFeatured(Product $product)
    {
        $product->update(['is_featured' => !$product->is_featured]);
        
        return response()->json([
            'success' => true,
            'message' => 'Featured berhasil diubah',
            'is_featured' => $product->is_featured
        ]);
    }

    public function bulkUpdateWeight(Request $request)
    {
        $request->validate([
            'products' => 'required|array',
            'products.*.id' => 'required|exists:products,id',
            'products.*.weight' => 'required|integer|min:1|max:50000'
        ]);

        $updated = 0;
        foreach ($request->products as $productData) {
            Product::where('id', $productData['id'])
                ->update(['weight' => $productData['weight']]);
            $updated++;
        }

        return back()->with('success', "Berhasil mengupdate berat {$updated} produk");
    }

    public function weightManagement()
    {
        $products = Product::with('category')
            ->select('id', 'name', 'weight', 'category_id')
            ->paginate(20);

        return view('admin.products.weight-management', compact('products'));
    }

    public function manageImages(Product $product)
    {
        $product->load('images');
        return view('admin.products.manage-images', compact('product'));
    }

    public function updateImageOrder(Request $request, Product $product)
    {
        $request->validate([
            'image_orders' => 'required|array',
            'image_orders.*' => 'integer|exists:product_images,id'
        ]);

        DB::beginTransaction();
        try {
            foreach ($request->image_orders as $order => $imageId) {
                ProductImage::where('id', $imageId)
                    ->where('product_id', $product->id)
                    ->update(['sort_order' => $order]);
            }

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Urutan gambar berhasil diupdate']);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    public function setPrimaryImage(Request $request, Product $product)
    {
        $request->validate([
            'image_id' => 'required|integer|exists:product_images,id'
        ]);

        DB::beginTransaction();
        try {
            // Reset all images to non-primary
            $product->images()->update(['is_primary' => false]);
            
            // Set selected image as primary
            ProductImage::where('id', $request->image_id)
                ->where('product_id', $product->id)
                ->update(['is_primary' => true]);

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Gambar utama berhasil diset']);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    public function deleteImage(Request $request, Product $product)
    {
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
            
            // Delete from database
            $image->delete();

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Gambar berhasil dihapus']);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }
}
