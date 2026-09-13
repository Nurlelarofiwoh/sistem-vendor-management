<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Spatie\Permission\Middleware\RoleMiddleware;
use Spatie\Permission\Middleware\RoleOrPermissionMiddleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Percayai reverse proxy Render agar HTTPS dan header X-Forwarded-Proto terbaca dengan benar
        $middleware->trustProxies(at: '*');

        // Abaikan verifikasi CSRF untuk rute publik pendaftaran vendor agar tidak terjadi error 419 jika sesi expired
        $middleware->validateCsrfTokens(except: [
            'daftar-vendor',
            'daftar-vendor/*',
        ]);

        // Mendaftarkan alias middleware Spatie
        $middleware->alias([
            'role' => RoleMiddleware::class,
            'permission' => PermissionMiddleware::class,
            'role_or_permission' => RoleOrPermissionMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Tangani error 419 (CSRF Token Mismatch) agar tidak muncul halaman blank 419
        $exceptions->render(function (\Illuminate\Session\TokenMismatchException $e, \Illuminate\Http\Request $request) {
            return redirect()->back()->withInput($request->except('_token'))->with('error', 'Sesi formulir Anda telah berakhir atau kedaluwarsa. Silakan coba kirim ulang.');
        });
        
        // Tangani error Payload Too Large (misal file proposal kebesaran melebihi limit PHP)
        $exceptions->render(function (\Illuminate\Http\Exceptions\PostTooLargeException $e, \Illuminate\Http\Request $request) {
            return redirect()->back()->withInput($request->except('_token'))->with('error', 'Ukuran file terlalu besar melebihi batas maksimal server. Pastikan ukuran file proposal tidak melebihi 5MB.');
        });
    })->create();
