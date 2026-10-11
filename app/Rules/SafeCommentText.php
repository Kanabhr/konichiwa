<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Log;
use Normalizer;

class SafeCommentText implements ValidationRule
{
    /**
     * Static flag to ensure the intl-unavailable warning is logged at most
     * once per PHP process, regardless of how many times this rule is invoked.
     */
    private static bool $intlWarningLogged = false;

    /**
     * Run the validation rule.
     *
     * Applies the full normalisation pipeline to a copy of the input, then
     * checks the normalised copy against every banned pattern.  The original
     * value is never mutated.
     *
     * Requirements: 2.1–2.9, 8.2–8.4
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $normalised = $this->normalise((string) $value);

        // intl-fallback strict rejection: characters outside L/N/Z/P categories
        if (! extension_loaded('intl')) {
            if (preg_match('/[^\p{L}\p{N}\p{Z}\p{P}]/u', $normalised)) {
                $fail("The :attribute contains characters that are not allowed.");
                return;
            }
        }

        // HTML tags / angle brackets (including fullwidth equivalents)
        // Req 2.2, 2.5
        if (preg_match('/<\s*[a-z]/iu', $normalised)
            || str_contains($normalised, '<')
            || str_contains($normalised, '>')
            || preg_match('/[\x{FF1C}\x{FF1E}]/u', $normalised)
        ) {
            $fail("The :attribute contains HTML or markup that is not allowed.");
            return;
        }

        // on* event handler attributes — Req 2.2
        if (preg_match('/\bon\w+\s*=/i', $normalised)) {
            $fail("The :attribute contains event handler attributes that are not allowed.");
            return;
        }

        // Dangerous URI schemes — Req 2.2
        if (str_contains($normalised, 'javascript:')
            || str_contains($normalised, 'vbscript:')
            || str_contains($normalised, 'data:')
        ) {
            $fail("The :attribute contains a URI scheme that is not allowed.");
            return;
        }

        // URL patterns — Req 2.3
        // Normaliser already lowercased the input, so HTTP:// etc. are covered.
        if (str_contains($normalised, 'http://')
            || str_contains($normalised, 'https://')
            || str_contains($normalised, 'ftp://')
            || preg_match('#//[a-z0-9]#i', $normalised)
            || preg_match('/\bwww\./i', $normalised)
            || preg_match('/[a-z0-9\-]+\.[a-z]{2,}\//i', $normalised)
        ) {
            $fail("The :attribute contains URLs or links that are not allowed.");
            return;
        }

        // Image references — Req 2.4
        if (preg_match('/\.(svg|png|jpe?g|gif|webp|bmp|ico|avif|tiff)\b/i', $normalised)
            || preg_match('/!\[.*?\]\(.*?\)/', $normalised)
            || preg_match('/\[img\]/i', $normalised)
            || str_contains($normalised, 'url(')
        ) {
            $fail("The :attribute contains image references that are not allowed.");
            return;
        }
    }

    /**
     * Normalise the input string through a multi-step pipeline so that encoded
     * or obfuscated variants are always evaluated in their canonical form.
     *
     * Pipeline (Requirements 2.1, 2.6, 2.7):
     *   1. trim
     *   2. collapse whitespace to single spaces
     *   3. decode HTML entities + percent-encoding up to 5 passes until stable
     *   4. Unicode normalisation (NFKC) via intl; fallback to mb_convert_encoding
     *   5. lowercase (for case-insensitive URL/scheme detection)
     *   6. strip zero-width characters (\u200B–\u200D, \uFEFF)
     *   7. strip ASCII control characters
     */
    private function normalise(string $input): string
    {
        // Step 1 — trim
        $s = trim($input);

        // Step 2 — collapse internal whitespace
        $s = preg_replace('/\s+/', ' ', $s);

        // Step 3 — iterative decoding (max 5 passes)
        for ($i = 0; $i < 5; $i++) {
            $decoded = rawurldecode(html_entity_decode($s, ENT_QUOTES | ENT_HTML5, 'UTF-8'));
            if ($decoded === $s) {
                break;
            }
            $s = $decoded;
        }

        // Step 4 — Unicode normalisation
        if (extension_loaded('intl')) {
            $result = Normalizer::normalize($s, Normalizer::FORM_KC);
            // normalize() returns false on malformed input; keep $s in that case
            if ($result !== false) {
                $s = $result;
            }
        } else {
            $this->logIntlUnavailable();
            $s = mb_convert_encoding($s, 'UTF-8', 'UTF-8');
        }

        // Step 5 — lowercase for case-insensitive pattern matching
        $s = mb_strtolower($s, 'UTF-8');

        // Step 6 — strip zero-width characters (Req 2.6)
        $s = preg_replace('/[\x{200B}-\x{200D}\x{FEFF}]/u', '', $s);

        // Step 7 — strip ASCII control characters (except TAB \x09, LF \x0A, CR \x0D)
        $s = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/u', '', $s);

        return $s;
    }

    /**
     * Log the intl-unavailable warning at most once per process (Req 8.3, 8.4).
     */
    private function logIntlUnavailable(): void
    {
        if (self::$intlWarningLogged) {
            return;
        }

        self::$intlWarningLogged = true;

        Log::warning('SafeCommentText: intl extension unavailable, operating in fallback mode');

        if (app()->environment('production')) {
            Log::critical('SafeCommentText: full Unicode normalisation unavailable in production');
        }
    }
}
