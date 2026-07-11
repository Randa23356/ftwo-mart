<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . "/../routes/web.php",
        api: __DIR__ . "/../routes/api.php",
        commands: __DIR__ . "/../routes/console.php",
        health: "/up",
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->api(
            prepend: [
                \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
            ],
        );

        $middleware->alias([
            "verified" => \App\Http\Middleware\EnsureEmailIsVerified::class,
        ]);

        $middleware->alias([
            // Spatie Permission Middleware
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,

            // Custom Middleware
            "checkout.protection" => \App\Http\Middleware\CheckoutProtection::class,
            "clear.invalid.session" => \App\Http\Middleware\ClearInvalidSession::class,
        ]);

        $middleware->web(
            // append: [\App\Http\Middleware\UpdateUserLastSeenAt::class],
        );
    })
    ->withSchedule(function ($schedule) {
        // Jalankan pembatalan pesanan expired setiap 30 menit
        $schedule->command('orders:cancel-expired')
                ->everyThirtyMinutes()
                ->withoutOverlapping()
                ->runInBackground();
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })
    ->create();
