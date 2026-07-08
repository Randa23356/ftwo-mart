<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\WebsiteSetting;
use App\Models\Category;
use App\Models\Service;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Blade;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Share website settings with all views if table exists
        if (Schema::hasTable("website_settings")) {
            $settings = WebsiteSetting::all()->keyBy("key");
            View::share("settings", $settings);
        }

        // Share categories with all views
        if (Schema::hasTable("categories")) {
            $categories = Category::where("is_active", true)->get();
            View::share("categories", $categories);
        }

        // Share services with all views
        if (Schema::hasTable("services")) {
            $services = Service::where("is_active", true)->get();
            View::share("services", $services);
        }

        // Blade directive for image URLs
        Blade::directive('imageUrl', function ($expression) {
            return "<?php echo \\App\\Helpers\\ImageHelper::getImageUrl($expression); ?>";
        });
    }
}