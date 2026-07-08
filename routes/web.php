<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Admin\WebsiteSettingController;
use App\Http\Controllers\Operator\OperatorController;
use App\Http\Controllers\Operator\OperatorProductController;
use App\Http\Controllers\CodConfirmationController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ShippingController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\RatingReplyController;

// Fallback route for serving storage files when symlink is not available (shared hosting)
Route::get('storage/{path}', function (string $path) {
    $disk = Storage::disk('public');
    if (!$disk->exists($path)) {
        abort(404);
    }

    $mimeType = $disk->mimeType($path);
    $size = $disk->size($path);
    $lastModified = $disk->lastModified($path);

    return response($disk->get($path))
        ->header('Content-Type', $mimeType)
        ->header('Content-Length', $size)
        ->setLastModified(\Carbon\Carbon::createFromTimestamp($lastModified));
})->where('path', '.*')->name('storage.fallback');

// Public routes
Route::get("/", [HomeController::class, "index"])->name("home");
Route::get("/products", [HomeController::class, "products"])->name("products");
Route::get("/products/{slug}", [HomeController::class, "productDetail"])->name(
    "products.detail",
);
Route::get("/about", [HomeController::class, "about"])->name("about");

// Public Shipping API routes (for AJAX calls)
Route::prefix("shipping")
    ->name("shipping.")
    ->group(function () {
        Route::get("/provinces", [ 
            ShippingController::class,
            "getProvinces",
        ])->name("provinces");
        Route::get("/cities", [ShippingController::class, "getCities"])->name(
            "cities",
        );
        Route::get("/search-cities", [
            ShippingController::class,
            "searchCities",
        ])->name("search.cities");
    });

// Shipping calculate route (with CSRF protection for authenticated users)
Route::post("/shipping/calculate", [ShippingController::class, "calculateCost"])
    ->name("shipping.calculate")
    ->middleware("auth");

// Rating and Review Routes (require authentication)
Route::middleware('auth')->group(function () {
    Route::get('/orders/{order}/products/{product}/rating', [RatingController::class, 'create'])->name('ratings.create');
    Route::post('/orders/{order}/products/{product}/rating', [RatingController::class, 'store'])->name('ratings.store');
    Route::get('/orders/{order}/products/{product}/rating/edit', [RatingController::class, 'edit'])->name('ratings.edit');
    Route::put('/orders/{order}/products/{product}/rating', [RatingController::class, 'update'])->name('ratings.update');

    // Rating Reply Routes (admin/operator only)
    Route::post('/ratings/{rating}/reply', [RatingReplyController::class, 'store'])->name('ratings.reply.store');
    Route::delete('/rating-replies/{reply}', [RatingReplyController::class, 'destroy'])->name('ratings.reply.destroy');
});

Route::get("/contact", [ContactController::class, "show"])->name("contact");
Route::post("/contact", [ContactController::class, "store"])->name(
    "contact.store",
);

// Authentication routes
require __DIR__ . "/auth.php";

// Test routes (remove in production)
if (app()->environment("local")) {
    require __DIR__ . "/test.php";
}

// Public user profile routes
Route::get('/users/{name}', [UserController::class, 'show'])->name('users.profile');

// Protected routes (require authentication and email verification)
Route::middleware(["auth", "verified"])->group(function () {
    // Profile routes
    Route::get("/profile", [ProfileController::class, "edit"])->name(
        "profile.edit",
    );
    Route::patch("/profile", [ProfileController::class, "update"])->name(
        "profile.update",
    );
    Route::patch("/profile/info", [
        ProfileController::class,
        "updateInfo",
    ])->name("profile.update-info");
    Route::patch("/profile/password", [
        ProfileController::class,
        "updatePassword",
    ])->name("profile.update-password");
    Route::get("/profile/{slug}", [ProfileController::class, "show"])->name(
        "profile.show",
    );

    // Chat routes
    Route::prefix("chat")
        ->name("chat.")
        ->group(function () {
            Route::get("/", [ChatController::class, "index"])->name("index");
            Route::post("/", [ChatController::class, "store"])->name("store");
            Route::get("/users", [ChatController::class, "getUsers"])->name(
                "users",
            );
            Route::get("/{conversation}", [
                ChatController::class,
                "show",
            ])->name("show");
            Route::post("/{conversation}/messages", [
                ChatController::class,
                "storeMessage",
            ])->name("messages.store");
            Route::get("/{conversation}/messages", [
                ChatController::class,
                "getMessages",
            ])->name("messages.get");
            // NEW → close conversation
            Route::post("/{conversation}/close", [
                ChatController::class,
                "close",
            ])->name("close");
            Route::post("/{conversation}/reopen", [
                ChatController::class,
                "reopen",
            ])->name("reopen");
            Route::post("/{conversation}/toggle-important", [
                ChatController::class,
                "toggleImportant",
            ])->name("important.toggle");
            Route::delete("/{conversation}", [
                ChatController::class,
                "destroy",
            ])->name("destroy");
            Route::post("/{id}/restore", [
                ChatController::class,
                "restore",
            ])->name("restore");
            Route::delete("/{id}/force-delete", [
                ChatController::class,
                "forceDelete",
            ])->name("force-delete");
            Route::get("/users-for-user", [
                ChatController::class,
                "getUsersForUser",
            ])->name("users.for.user");
        });

    // Cart routes
    Route::get("/cart", [CartController::class, "index"])->name("cart.index");
    Route::post("/cart/add", [CartController::class, "add"])->name("cart.add");
    Route::put("/cart/{cart}/update", [CartController::class, "update"])->name(
        "cart.update",
    );
    Route::delete("/cart/{cart}/remove", [
        CartController::class,
        "remove",
    ])->name("cart.remove");
    Route::delete("/cart/clear", [CartController::class, "clear"])->name(
        "cart.clear",
    );
    Route::post("/cart/checkout-selected", [
        CartController::class,
        "checkoutSelected",
    ])->name("cart.checkout.selected");
    Route::post("/cart/clear-selected-session", [
        CartController::class,
        "clearSelectedSession",
    ])->name("cart.clear.selected.session");

    // Order routes
    Route::get("/orders", [OrderController::class, "index"])->name(
        "orders.index",
    );
    Route::get("/orders/{order}", [OrderController::class, "show"])->name(
        "orders.show",
    );
    Route::get("/checkout", [OrderController::class, "checkout"])
        ->middleware("checkout.protection")
        ->name("orders.checkout");
    Route::post("/buy-now", [OrderController::class, "buyNow"])->name(
        "buy_now",
    );
    Route::post("/buy-now/cancel", [
        OrderController::class,
        "cancelBuyNow",
    ])->name("buy_now.cancel");

    Route::post("/orders", [OrderController::class, "store"])->name(
        "orders.store",
    );
    Route::match(['get', 'post'], "/orders/{order}/pay", [OrderController::class, "pay"])->name(
        "orders.pay",
    );

    // Cancel order route
    Route::patch('/orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
});

// Public routes for COD confirmation (signed)
Route::get("/cod/confirm/{order}", [CodConfirmationController::class, "show"])
    ->name("cod.confirm.show")
    ->middleware("signed");
Route::post("/cod/confirm/{order}", [
    CodConfirmationController::class,
    "confirm",
])
    ->name("cod.confirm.post")
    ->middleware("signed");

// Admin routes
Route::middleware(["auth", "role:admin"])
    ->prefix("admin")
    ->name("admin.")
    ->group(function () {
        Route::get("/dashboard", [AdminController::class, "dashboard"])->name(
            "dashboard",
        );

        // Order routes with filters
        Route::get("/orders", [AdminController::class, "orders"])->name("orders");
        Route::get("/orders/pending", [AdminController::class, "pendingOrders"])->name("orders.pending");
        Route::get("/orders/processing", [AdminController::class, "processingOrders"])->name("orders.processing");
        Route::get("/orders/ready", [AdminController::class, "readyOrders"])->name("orders.ready");
        Route::get("/orders/shipped", [AdminController::class, "shippedOrders"])->name("orders.shipped");
        Route::get("/orders/delivered", [AdminController::class, "deliveredOrders"])->name("orders.delivered");
        Route::get("/orders/cancelled", [AdminController::class, "cancelledOrders"])->name("orders.cancelled");
        Route::get("/orders/trash", [AdminController::class, "trashOrders"])->name("orders.trash");
        Route::post("/orders/{id}/restore", [AdminController::class, "restoreOrder"])->name("orders.restore");
        Route::delete("/orders/{id}/force-delete", [AdminController::class, "forceDeleteOrder"])->name("orders.force-delete");

        Route::get("/orders/{order}", [
            AdminController::class,
            "orderDetail",
        ])->name("orders.detail");
        Route::put("/orders/{order}/status", [
            AdminController::class,
            "updateOrderStatus",
        ])->name("orders.update-status");
        Route::post("/orders/{order}/tracking", [
            AdminController::class,
            "updateTrackingNumber",
        ])->name("orders.tracking");
        Route::delete("/orders/{order}", [AdminController::class, "destroyOrder"])->name("orders.destroy");

        Route::get("/users", [AdminController::class, "users"])->name("users");
        Route::get("/users/{user}", [
            AdminController::class,
            "userDetail",
        ])->name("users.detail");
        Route::put("/users/{user}/toggle-status", [
            AdminController::class,
            "toggleUserStatus",
        ])->name("users.toggle-status");
        Route::get('/users/create', [AdminController::class, 'create'])->name('users.create');
        Route::post('/users', [AdminController::class, 'store'])->name('users.store');
        Route::get("/reports", [AdminController::class, "reports"])->name(
            "reports",
        );

        // Products management
        Route::get('/products/trash', [ProductController::class, 'trash'])->name('products.trash');
        Route::get('/products/weight-management', [ProductController::class, 'weightManagement'])->name('products.weight-management');
        Route::put("/products/{product}/toggle-status", [ProductController::class, "toggleStatus"])->name("products.toggle-status");
        Route::put("/products/{product}/toggle-featured", [ProductController::class, "toggleFeatured"])->name("products.toggle-featured");
        Route::post('/products/{id}/restore', [ProductController::class, 'restore'])->name('products.restore');
        Route::delete('/products/{id}/force-delete', [ProductController::class, 'forceDelete'])->name('products.force-delete');
        Route::post("/products/bulk-update-weight", [ProductController::class, "bulkUpdateWeight"])->name("products.bulk-update-weight");
        Route::resource("products", ProductController::class);

        // Categories management
        Route::get('/categories/trash', [CategoryController::class, 'trash'])->name('categories.trash');
        Route::put("/categories/{category}/toggle-status", [CategoryController::class, "toggleStatus"])->name("categories.toggle-status");
        Route::post('/categories/{id}/restore', [CategoryController::class, 'restore'])->name('categories.restore');
        Route::delete('/categories/{id}/force-delete', [CategoryController::class, 'forceDelete'])->name('categories.force-delete');
        Route::post('/categories/{id}/move-products', [CategoryController::class, 'moveProducts'])->name('categories.move-products');
        Route::resource("categories", CategoryController::class);

        // Services management
        Route::resource("services", ServiceController::class);

        // Website settings
        Route::get("/settings", [
            WebsiteSettingController::class,
            "index",
        ])->name("settings");
        Route::put("/settings", [
            WebsiteSettingController::class,
            "update",
        ])->name("settings.update");
        Route::put("/settings/logo", [
            WebsiteSettingController::class,
            "updateLogo",
        ])->name("settings.logo");
        Route::delete("/settings/logo", [
            WebsiteSettingController::class,
            "deleteLogo",
        ])->name("settings.logo.delete");
        Route::put("/settings/hero-image", [
            WebsiteSettingController::class,
            "updateHeroImage",
        ])->name("settings.hero-image");
        Route::delete("/settings/hero-image", [
            WebsiteSettingController::class,
            "deleteHeroImage",
        ])->name("settings.hero-image.delete");
        Route::put("/settings/about", [
            WebsiteSettingController::class,
            "updateAboutContent",
        ])->name("settings.about");
        Route::delete("/settings/about-image", [
            WebsiteSettingController::class,
            "deleteAboutImage",
        ])->name("settings.about-image.delete");
        Route::put("/settings/contact", [
            WebsiteSettingController::class,
            "updateContactInfo",
        ])->name("settings.contact");
        Route::post("/settings/inline-update", [
            WebsiteSettingController::class,
            "inlineUpdate",
        ])->name("settings.inline-update");

        // Email reply to guest
        Route::post("/email-reply/{conversation}", [
            AdminController::class,
            "emailReply",
        ])->name("email-reply");

        // Shipping settings
        Route::prefix("shipping")
            ->name("shipping.")
            ->group(function () {
                Route::get("/", [
                    App\Http\Controllers\Admin\ShippingSettingController::class,
                    "index",
                ])->name("index");
                Route::put("/update", [
                    App\Http\Controllers\Admin\ShippingSettingController::class,
                    "update",
                ])->name("update");
                Route::post("/test", [
                    App\Http\Controllers\Admin\ShippingSettingController::class,
                    "testShipping",
                ])->name("test");
                Route::get("/origin-info", [
                    App\Http\Controllers\Admin\ShippingSettingController::class,
                    "getOriginInfo",
                ])->name("origin.info");
            });

        // Product image management routes
        Route::get("/products/{product}/manage-images", [
            ProductController::class,
            "manageImages",
        ])->name("products.manage-images");
        Route::post("/products/{product}/update-image-order", [
            ProductController::class,
            "updateImageOrder",
        ])->name("products.update-image-order");
        Route::post("/products/{product}/set-primary-image", [
            ProductController::class,
            "setPrimaryImage",
        ])->name("products.set-primary-image");
        Route::delete("/products/{product}/delete-image", [
            ProductController::class,
            "deleteImage",
        ])->name("products.delete-image");
    });

// Operator routes
Route::middleware(["auth", "role:operator|admin"]) // Allow admin to access operator routes too
    ->prefix("operator")
    ->name("operator.")
    ->group(function () {
        Route::get('/dashboard', [OperatorController::class, 'dashboard'])->name('dashboard')->middleware('permission:dashboard-view');

        // Trash Orders (admin only)
        Route::get("/orders/trash", [
            OperatorController::class,
            "trashOrders",
        ])->name("orders.trash")->middleware('permission:order-delete');
        Route::post("/orders/{order}/restore", [
            OperatorController::class,
            "restoreOrder",
        ])->name("orders.restore")->middleware('permission:order-delete');
        Route::delete("/orders/{order}/force-delete", [
            OperatorController::class,
            "forceDeleteOrder",
        ])->name("orders.force-delete")->middleware('permission:order-delete');

        Route::get('/orders', [OperatorController::class, 'orders'])->name('orders')->middleware('permission:order-view');

        // Put specific status routes BEFORE the dynamic {order} route to avoid conflicts
        Route::get("/orders/pending", [
            OperatorController::class,
            "pendingOrders",
        ])->name("orders.pending");
        Route::get("/orders/processing", [
            OperatorController::class,
            "processingOrders",
        ])->name("orders.processing");
        Route::get("/orders/ready", [
            OperatorController::class,
            "readyOrders",
        ])->name("orders.ready");
        Route::get("/orders/shipped", [
            OperatorController::class,
            "shippedOrders",
        ])->name("orders.shipped");
        Route::get("/orders/delivered", [
            OperatorController::class,
            "deliveredOrders",
        ])->name("orders.delivered");
        Route::get("/orders/cancelled", [
            OperatorController::class,
            "cancelledOrders",
        ])->name("orders.cancelled");
        // Dynamic routes after specific ones
        Route::get("/orders/{order}", [
            OperatorController::class,
            "orderDetail",
        ])->name("orders.detail");
        Route::put('/orders/{order}/status', [OperatorController::class, 'updateOrderStatus'])->name('orders.update-status')->middleware('permission:order-edit');
        Route::post('/orders/{order}/tracking', [OperatorController::class, 'updateTrackingNumber'])->name('orders.tracking')->middleware('permission:order-edit');
        Route::get("/orders/{order}/print", [
            OperatorController::class,
            "printOrder",
        ])->name("orders.print");
        Route::delete("/orders/{order}", [
            OperatorController::class,
            "destroy",
        ])->name("orders.destroy");

        // Operator product management (limited)
        Route::get('/products', [OperatorProductController::class, 'index'])->name('products.index')->middleware('permission:product-view');
        Route::get('/products/trash', [OperatorProductController::class, 'trash'])->name('products.trash')->middleware('permission:product-delete');
        Route::post('/products/{id}/restore', [OperatorProductController::class, 'restore'])->name('products.restore')->middleware('permission:product-delete');
        Route::delete('/products/{id}/force-delete', [OperatorProductController::class, 'forceDelete'])->name('products.force-delete')->middleware('permission:product-delete');
        Route::get('/products/create', [OperatorProductController::class, 'create'])->name('products.create')->middleware('permission:product-create');
        Route::post('/products', [OperatorProductController::class, 'store'])->name('products.store')->middleware('permission:product-create');
        Route::get('/products/{product}/edit', [OperatorProductController::class, 'edit'])->name('products.edit')->middleware('permission:product-edit');
        Route::put('/products/{product}', [OperatorProductController::class, 'update'])->name('products.update')->middleware('permission:product-edit');
        Route::delete('/products/{product}', [OperatorProductController::class, 'destroy'])->name('products.destroy')->middleware('permission:product-delete');

        // Image management routes for operator
        Route::delete("/products/{product}/delete-image", [
            OperatorProductController::class,
            "deleteImage",
        ])->name("products.delete-image");
        Route::delete("/products/{product}/delete-all-images", [
            OperatorProductController::class,
            "deleteAllImages",
        ])->name("products.delete-all-images");
    });

