<?php

namespace App\Providers;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\WebsiteSetting;
use App\Models\Category;
use App\Models\Service;
use App\Models\Order;
use App\Observers\OrderObserver;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Gate;
use Spatie\Permission\Models\Permission;

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
        ResetPassword::createUrlUsing(function (
            object $notifiable,
            string $token,
        ) {
            return config("app.frontend_url") .
                "/password-reset/$token?email={$notifiable->getEmailForPasswordReset()}";
        });

        if (Schema::hasTable("website_settings")) {
            $settings = WebsiteSetting::all()->keyBy("key");
            View::share("settings", $settings);
        }

        if (Schema::hasTable("categories")) {
            try {
                $categories = Category::where("is_active", true)->take(4)->get();
                View::share("footer_categories", $categories);
            } catch (\Exception $e) {
                // Fails if soft deletes column missing
            }
        }

        if (Schema::hasTable("services")) {
            try {
                $services = Service::where("is_active", true)->take(4)->get();
                View::share("footer_services", $services);
            } catch (\Exception $e) {
                // Fails if soft deletes column missing
            }
        }

        // Register Order Observer
        Order::observe(OrderObserver::class);

        // Dynamically register gates for all permissions
        if (Schema::hasTable('permissions')) {
            try {
                Permission::all()->map(function ($permission) {
                    Gate::define($permission->name, function ($user) use ($permission) {
                        return $user->hasPermissionTo($permission);
                    });
                });
            } catch (\Exception $e) {
                // Fails during initial migration, safe to ignore
            }
        }
    }
}
