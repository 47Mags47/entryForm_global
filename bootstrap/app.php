<?php

use App\Http\Middleware\XCORSMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware
        ->web(append: [
            XCORSMiddleware::class,
        ])->validateCsrfTokens(except: [
            'frames/*'
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
