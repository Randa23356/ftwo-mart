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
            $query
                ->where("name", "like", "%" . $request->search . "%")
                ->orWhere("description", "like", "%" . $request->search . "%");
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
        return view("about", compact("websiteSettings"));
    }

    public function contact()
    {
        $websiteSettings = WebsiteSetting::pluck("value", "key")->toArray();
        return view("contact", compact("websiteSettings"));
    }
}
