<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;
use Symfony\Component\HttpFoundation\Cookie;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        //
    ];

    public function addCookieToResponse($request, $response)
    {
        $config = config('session');
        $session_life = $config['csrf_life'];

        $response->headers->setCookie(
            new Cookie(
                'XSRF-TOKEN', $request->session()->token(), $this->availableAt($session_life),
                $config['path'], $config['domain'], $config['secure'], false, false,
                $config['same_site'] ?? null
            )
        );
    }
}
