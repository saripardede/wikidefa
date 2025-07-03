<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array
     */
    protected $middleware = [
        // Middleware bawaan Laravel
        \Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \App\Http\Middleware\TrimStrings::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [
                \App\Http\Middleware\EncryptCookies::class,
                \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
                \Illuminate\Session\Middleware\StartSession::class,
                \Illuminate\View\Middleware\ShareErrorsFromSession::class,
                \App\Http\Middleware\VerifyCsrfToken::class, 
                \Illuminate\Routing\Middleware\SubstituteBindings::class,
            ],


        'api' => [
                \App\Http\Middleware\EncryptCookies::class,
                \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
                \Illuminate\Session\Middleware\StartSession::class,
                \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class, // Tambahkan ini
                'throttle:api',
                \Illuminate\Routing\Middleware\SubstituteBindings::class,
                \App\Http\Middleware\Cors::class,  // Pastikan CORS Middleware ada di sini
            ],
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array
     */
    // app/Http/Kernel.php

    protected $routeMiddleware = [
        'auth' => \App\Http\Middleware\AuthenticateMiddleware::class,
        'admin' => \App\Http\Middleware\AdminMiddleware::class,
        'user' => \App\Http\Middleware\UserMiddleware::class,
        'check.status' => \App\Http\Middleware\CheckStatus::class,
        'sanctum' => \App\Http\Middleware\SanctumMiddleware::class,
    ];
}