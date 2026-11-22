<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\CheckRole;

// Namespace middleware SweetAlert yang benar untuk versi terbaru.
use RealRashid\SweetAlert\Http\Middleware\ToSweetAlert;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {

        // Middleware untuk membatasi akses berdasarkan role pengguna.
        $middleware->alias([
            'role' => CheckRole::class,
        ]);

        // Middleware SweetAlert untuk mengubah flash message
        // Laravel menjadi notifikasi SweetAlert.
        $middleware->web(append: [
            ToSweetAlert::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })
    ->create();
