<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Middleware\TrustProxies; // આ ઉમેરવાની જરૂર પડી શકે છે

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // ૧. Render અને Cloudflare જેવા પ્રોક્સી સર્વર માટે ટ્રસ્ટ સેટિંગ
        // આ લાઇન ખાતરી કરશે કે $request->ip() હંમેશા સાચો યુઝર IP આપે.
        $middleware->trustProxies(at: '*');

        // ૨. વૈકલ્પિક: જો હજી પણ પ્રોબ્લેમ આવે, તો સ્પેસિફિક હેડર્સ સેટ કરી શકાય
        $middleware->trustProxies(headers: TrustProxies::HEADER_X_FORWARDED_FOR | 
                                           TrustProxies::HEADER_X_FORWARDED_HOST | 
                                           TrustProxies::HEADER_X_FORWARDED_PORT | 
                                           TrustProxies::HEADER_X_FORWARDED_PROTO);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
