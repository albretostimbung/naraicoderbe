<?php

namespace App\Http\Middleware;

use App\Helpers\ResponseFormatter;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthTokenMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $authHeader = $request->header('Authorization');

        // Cek apakah ada header Authorization dengan format Bearer <token>
        if (!$authHeader || !preg_match('/^Bearer\s+\S+$/', $authHeader)) {
            return ResponseFormatter::error('Unauthorized', 401);
        }

        // TODO: verifikasi token di sini kalau mau (misal cocokkan ke database)

        return $next($request);
    }
}
