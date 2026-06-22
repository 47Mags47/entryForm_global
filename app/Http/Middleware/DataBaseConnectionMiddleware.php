<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class DataBaseConnectionMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        try {
            DB::connection()->getPdo();
        } catch (\Throwable $e) {
            return response()
                ->view('dbDisconnected', [], 503);
        }

        return $next($request);
    }
}
