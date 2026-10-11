<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;

class Turnstile implements ValidationRule
{
    /**
     * The Cloudflare Turnstile siteverify endpoint.
     */
    private const SITEVERIFY_URL = 'https://challenges.cloudflare.com/turnstile/v0/siteverify';

    /**
     * Validate the Cloudflare Turnstile response token.
     *
     * Checks (in order):
     *  1. Connection / HTTP errors  → generic failure message
     *  2. timeout-or-duplicate error-codes → expired/duplicate message
     *  3. success === false          → challenge failed message
     *  4. Hostname mismatch          → domain mismatch message
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $secret           = config('services.turnstile.secret_key');
        $expectedHostname = config('services.turnstile.expected_hostname');
        $skipHostnameCheck = config('services.turnstile.skip_hostname_check', false);

        // --- Step 1: POST to siteverify; catch connection / network errors ---
        try {
            $response = Http::asForm()
                ->timeout(5)
                ->post(self::SITEVERIFY_URL, [
                    'secret'   => $secret,
                    'response' => $value,
                    'remoteip' => request()->ip(),
                ]);
        } catch (ConnectionException $e) {
            $fail('We were unable to verify your response. Please try again.');
            return;
        }

        // Non-2xx HTTP status
        if ($response->failed()) {
            $fail('We were unable to verify your response. Please try again.');
            return;
        }

        $body = $response->json();

        // --- Step 2: timeout-or-duplicate error-code check ---
        $errorCodes = $body['error-codes'] ?? [];
        if (in_array('timeout-or-duplicate', $errorCodes, true)) {
            $fail('The challenge has expired or was already used. Please try again.');
            return;
        }

        // --- Step 3: success flag ---
        if (($body['success'] ?? false) === false) {
            $fail('Please complete the challenge and try again.');
            return;
        }

        // --- Step 4: hostname mismatch ---
        if (! $skipHostnameCheck) {
            $returnedHostname = $body['hostname'] ?? null;
            if ($returnedHostname !== $expectedHostname) {
                $fail('Domain mismatch. Please try again.');
                return;
            }
        }
    }
}
