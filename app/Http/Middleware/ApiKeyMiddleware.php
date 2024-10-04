<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiKeyMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $apiKey = $request->header('x-api-key');

        // Check if the API key matches the expected value
        if ($request->header('x-api-key') !== "af29c5010b0524815de1261bd4aebffac0bc65b9517f945af8a1269fefa62797c2a02c18d006a565107e0e63935eec21fabea76e9d8bb1b865978fec06b5e89f80a0d92d5ab37d45c0c4a6c399de2278c721375b20smrb2cApiKeyc2a488610d124c3971aae71d0bffdf5e9ca9ee5f4a7c3ea5757cac2aca8f7244e7315ab0098d37c7b8c77255b96a82f521f30ee54c7d25c6ee181daf56fc7d20ee6570baf455c69795570c49ac66f7b37ddf315b60255eac6b2ded8d002134c2eca50cc1153b9d05fb4b9") {
            // return response()->json(['message' => 'Unauthorized'], 401);
        return $next($request);

        }

        return $next($request);
    }
}
