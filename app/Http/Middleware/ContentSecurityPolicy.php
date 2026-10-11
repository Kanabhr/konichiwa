<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Attaches a Content-Security-Policy header to every response in the web
 * middleware group.
 *
 * External origins audited from resources/views/layouts/ (header, footer, app):
 * - All CSS/JS assets are served locally via asset() from /assets/
 * - Font Awesome is loaded locally (assets/css/plugins/fontawesome-5.css)
 * - Bootstrap, jQuery, Swiper, etc. are all local
 * - The ONLY external origin is https://challenges.cloudflare.com (Turnstile)
 *
 * This middleware emits ONLY Content-Security-Policy (enforcing mode).
 * Per Req 7.8, it MUST NOT emit both CSP and CSP-Report-Only on the same response.
 */
class ContentSecurityPolicy
{
    public function handle(Request $request, Closure $next): Response
    {
        /** @var Response $response */
        $response = $next($request);

        // Set header after $next() so it is applied to all status codes
        // (2xx, 3xx, 4xx, 5xx) — Req 7.1
        $response->headers->set('Content-Security-Policy', $this->buildHeader());

        return $response;
    }

    private function buildHeader(): string
    {
        $directives = [
            // All resources default to same-origin
            "default-src 'self'",

            // Scripts: self + Cloudflare Turnstile (Req 7.2)
            "script-src 'self' https://challenges.cloudflare.com",

            // Styles: self + unsafe-inline (needed for inline styles used by
            // the theme's JS animations and third-party widgets)
            "style-src 'self' 'unsafe-inline'",

            // Fonts: self only (Font Awesome is served locally)
            "font-src 'self' data: ",

            // Images: self + data: URIs (used by some inline SVG/spinner patterns)
            "img-src 'self' data: https://qrcode.konichiwachemicals.com",

            // Frames: Turnstile renders in an iframe (Req 7.3)
            "frame-src 'self' https://challenges.cloudflare.com https://www.google.com/maps/",

            // Fetch/XHR: Turnstile makes connections to Cloudflare (Req 7.4)
            "connect-src 'self' https://challenges.cloudflare.com",

            // Block plugins/Flash
            "object-src 'none'",

            // Restrict base tag to same origin
            "base-uri 'self'",

            // Only allow forms to submit to same origin
            "form-action 'self'",
            // Allow product images 
            
        ];

        return implode('; ', $directives);
    }
}
