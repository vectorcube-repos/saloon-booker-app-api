<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminReferrerPolicy
{
    /**
     * Set Referrer-Policy so storage image requests from the admin UI
     * do not send a Referer header (avoids 403 when server blocks referrers).
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $response->headers->set('Referrer-Policy', 'no-referrer');

        return $response;
    }
}
