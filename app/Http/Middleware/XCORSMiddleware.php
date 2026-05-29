<?php

namespace App\Http\Middleware;

use App\Models\Frame;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class XCORSMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $frame = Frame::where('token', $request->token)->first();
        if ($frame === null)
            abort(404);

        if ($frame->division?->url === null)
            abort(404);


        return $next($request);
    }
}
