<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
        // Admin panel routes live in their own file, isolated from the
        // public site and the user dashboard.
        then: function (): void {
            Route::middleware('web')->group(base_path('routes/admin.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'admin.auth' => \App\Http\Middleware\Admin\AuthenticateAdmin::class,
            'admin.guest' => \App\Http\Middleware\Admin\RedirectIfAdminAuthenticated::class,
            'admin.role' => \App\Http\Middleware\Admin\EnsureAdminRole::class,
        ]);

        // Razorpay cannot send a CSRF token; the webhook authenticates
        // requests by HMAC signature instead (RazorpayWebhookController).
        $middleware->validateCsrfTokens(except: ['webhooks/razorpay']);
        // Guest "View Profile" links stash their destination in this
        // plain-text cookie (see PageController::show) before redirecting
        // to /login — it's set by JS, not Laravel, so it must be excluded
        // from cookie encryption or EncryptCookies silently nulls it out.
        $middleware->encryptCookies(except: ['intended_profile']);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
