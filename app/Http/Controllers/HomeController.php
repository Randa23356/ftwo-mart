<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Service;
use App\Models\WebsiteSetting;
use App\Models\Rating;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $featuredProducts = Product::where("is_featured", true)
            ->where("is_active", true)
            ->with("category")
            ->take(6)
            ->get();

        $categories = Category::where("is_active", true)->get();
        $services = Service::where("is_active", true)->get();

        $websiteSettings = WebsiteSetting::pluck("value", "key")->toArray();

        // Calculate overall average rating from all products
        $overallRating = Rating::avg('rating') ?? 0;
        $totalRatings = Rating::count();

        return view(
            "home",
            compact(
                "featuredProducts",
                "categories",
                "services",
                "websiteSettings",
                "overallRating",
                "totalRatings",
            ),
        );
    }

    public function products(Request $request)
    {
        $query = Product::where("is_active", true)->with("category");

        if ($request->category) {
            $query->whereHas("category", function ($q) use ($request) {
                $q->where("slug", $request->category);
            });
        }

        if ($request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where("name", "like", "%" . $search . "%")
                  ->orWhere("description", "like", "%" . $search . "%")
                  ->orWhereHas("category", function ($cq) use ($search) {
                      $cq->where("name", "like", "%" . $search . "%");
                  });
            });
        }

        if ($request->min_price) {
            $query->where("price", ">=", $request->min_price);
        }

        if ($request->max_price) {
            $query->where("price", "<=", $request->max_price);
        }

        $products = $query->paginate(12);
        $categories = Category::where("is_active", true)->get();

        return view("products", compact("products", "categories"));
    }

    public function productDetail($slug)
    {
        $product = Product::where("slug", $slug)
            ->where("is_active", true)
            ->with("category")
            ->with(['ratings' => function($query) {
                $query->with('user')->with(['replies' => function($replyQuery) {
                    $replyQuery->with('user');
                }])->latest();
            }])
            ->firstOrFail();

        $relatedProducts = Product::where("category_id", $product->category_id)
            ->where("id", "!=", $product->id)
            ->where("is_active", true)
            ->take(4)
            ->get();

        return view("product-detail", compact("product", "relatedProducts"));
    }

    public function about()
    {
        $websiteSettings = WebsiteSetting::pluck("value", "key")->toArray();

        $totalProducts = Product::where("is_active", true)->count();
        $totalCustomers = \App\Models\User::where("role", "user")->count();
        $totalActiveCustomers = \App\Models\User::where("role", "user")->whereHas("orders")->count();
        $avgRating = Rating::avg("rating") ?? 0;

        return view("about", compact("websiteSettings", "totalProducts", "totalCustomers", "totalActiveCustomers", "avgRating"));
    }

    public function contact()
    {
        $websiteSettings = WebsiteSetting::pluck("value", "key")->toArray();
        return view("contact", compact("websiteSettings"));
    }

    public function searchSuggestions(Request $request)
    {
        $query = $request->input('q', '');

        if (strlen($query) < 2) {
            return response()->json(['products' => [], 'categories' => []]);
        }

        $products = Product::where("is_active", true)
            ->where(function ($q) use ($query) {
                $q->where("name", "like", "%" . $query . "%")
                  ->orWhere("description", "like", "%" . $query . "%");
            })
            ->with("category")
            ->take(6)
            ->get()
            ->map(function ($product) {
                return [
                    'name' => $product->name,
                    'slug' => $product->slug,
                    'price' => $product->formatted_price,
                    'category' => $product->category->name ?? '',
                    'image' => $product->image_url,
                    'url' => route('products.detail', $product->slug),
                ];
            });

        $categories = Category::where("is_active", true)
            ->where("name", "like", "%" . $query . "%")
            ->take(3)
            ->get()
            ->map(function ($category) {
                return [
                    'name' => $category->name,
                    'slug' => $category->slug,
                    'url' => route('products', ['category' => $category->slug]),
                ];
            });

        return response()->json(['products' => $products, 'categories' => $categories]);
    }
}
