<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Daftarkan alias middleware di sini
        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
        ]);
        // Pengecualian CSRF ✨
        $middleware->validateCsrfTokens(except: [
            'midtrans/callback', // Hapus garis miring di depan jika sebelumnya gagal
            'http://*/midtrans/callback', // Menjaga jika diakses via domain/ngrok
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
