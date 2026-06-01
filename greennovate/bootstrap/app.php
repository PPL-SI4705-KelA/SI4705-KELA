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
    ->withMiddleware(function (Middleware $middleware): void {

        $middleware->redirectUsersTo(function () {
            if (auth()->check()) {
                if (auth()->user()->role === 'admin') {
                    return route('admin.dashboard');
                } elseif (auth()->user()->role === 'petugas') {
                    return route('petugas.dashboard');
                }
            }
            return '/';
        });

        $middleware->alias([
            'check.active' => \App\Http\Middleware\CheckActive::class,
            'role' => \App\Http\Middleware\RoleMiddleware::class,
            'is.admin' => \App\Http\Middleware\IsAdmin::class,
            'is.petugas' => \App\Http\Middleware\IsPetugas::class,
            'set.locale' => \App\Http\Middleware\SetLocale::class,
        ]);

        $middleware->appendToGroup('web', [
            \App\Http\Middleware\SetLocale::class,
        ]);

    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();