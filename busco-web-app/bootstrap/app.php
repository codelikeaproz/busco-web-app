<?php

use App\Http\Middleware\EnsureUserIsAdmin;
use App\Http\Middleware\AdminInactivityTimeout;
use Illuminate\Http\Request;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->trustProxies(at: '*');
        $middleware->redirectGuestsTo(fn (Request $request) => $request->is('admin') || $request->is('admin/*')
            ? route('admin.login')
            : route('home'));
        $middleware->alias([
            'admin' => EnsureUserIsAdmin::class,
            'admin.inactivity' => AdminInactivityTimeout::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
