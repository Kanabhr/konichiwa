<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ContentSecurityPolicy
{
    public function handle(Request $request, Closure $next): Response
    {
        /** @var Response $response */
        $response = $next($request);

        $response->headers->set(
            'Content-Security-Policy',
            $this->isInertiaPage($response) ? $this->adminHeader() : $this->buildHeader()
        );

        return $response;
    }

    // Inertia (React admin/login) pages carry a data-page attribute or the X-Inertia header
    private function isInertiaPage(Response $response): bool
    {
        if ($response->headers->has('X-Inertia')) {
            return true;
        }
        $content = $response->getContent();
        return is_string($content) && str_contains($content, 'data-page=');
    }

    // Looser policy ONLY for the logged-in admin app / auth screens
    private function adminHeader(): string
    {
        $dev = file_exists(public_path('hot'))
            ? ' http://localhost:5173 http://[::1]:5173 http://127.0.0.1:5173'
            : '';
        $devWs = $dev ? ' ws://localhost:5173 ws://[::1]:5173 ws://127.0.0.1:5173' : '';

        return implode('; ', [
            "default-src 'self'",
            "script-src 'self' 'unsafe-inline' https://challenges.cloudflare.com{$dev}",
            "style-src 'self' 'unsafe-inline' https://fonts.bunny.net{$dev}",
            "font-src 'self' data: https://fonts.bunny.net",
            "img-src 'self' data: blob:",
            "frame-src 'self' https://challenges.cloudflare.com",
            "connect-src 'self' https://challenges.cloudflare.com{$dev}{$devWs}",
            "object-src 'none'",
            "base-uri 'self'",
            "form-action 'self'",
        ]);
    }

    // Strict policy for the public website (unchanged)
    private function buildHeader(): string
    {
        return implode('; ', [
            "default-src 'self'",
            "script-src 'self' https://challenges.cloudflare.com",
            "style-src 'self' 'unsafe-inline'",
            "font-src 'self' data:",
            "img-src 'self' data: https://qrcode.konichiwachemicals.com",
            "frame-src 'self' https://challenges.cloudflare.com https://www.google.com/maps/",
            "connect-src 'self' https://challenges.cloudflare.com",
            "object-src 'none'",
            "base-uri 'self'",
            "form-action 'self'",
        ]);
    }
}
