<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // આ લાઇન Render ના લોડ બેલેન્સરને ઓળખવામાં અને સાચો IP મેળવવામાં મદદ કરશે
        $middleware->trustProxies(at: '*'); 
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
