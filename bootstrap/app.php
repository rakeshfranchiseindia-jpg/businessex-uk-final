<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
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
