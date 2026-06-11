<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Console\Scheduling\Schedule;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'auth' => \App\Http\Middleware\EnsureAuthenticated::class,
            'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
            'admin' => \App\Http\Middleware\IsAdmin::class,
            'client' => \App\Http\Middleware\IsClient::class,
        ]);

        // Exclude API routes from CSRF verification
        $middleware->validateCsrfTokens(except: [
            '/api/license/validate',
            '/api/license/info',
            '/api/device/activate',
            '/api/device/heartbeat',
            '/api/device/info',
            '/api/device/deactivate',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })
    ->withSchedule(function (Schedule $schedule): void {
        // Check for expired licenses daily at midnight
        $schedule->command('licenses:check-expired')->daily();

        // Send expiration warnings daily at 9 AM
        $schedule->command('licenses:send-expiration-warnings')->dailyAt('09:00');
    })
    ->create();
