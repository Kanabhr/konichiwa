<?php

namespace App\Http\Requests;

use App\Rules\MaxWords;
use App\Rules\SafeCommentText;
use App\Rules\Turnstile;
use Illuminate\Foundation\Http\FormRequest;

class StoreCommentRequest extends FormRequest
{
    /**
     * Determine if the user is authorised to make this request.
     *
     * Authorization is handled at the route/middleware level; the FormRequest
     * always permits the request to proceed to validation.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * Rules:
     *  - name:                  required, ≤ 50 chars, plain-text only (SafeCommentText)
     *  - email:                 required, RFC + DNS validated email address
     *  - message:               required, ≤ 50 words (MaxWords), ≤ 500 chars, plain-text only (SafeCommentText)
     *  - cf-turnstile-response: required, verified against Cloudflare siteverify (Turnstile)
     *  - website:               intentionally omitted — silently discarded from validated()
     *  - website_url:           intentionally omitted — honeypot, checked in controller, never stored
     *
     * Requirements: 1.3, 3.1–3.3, 4.1–4.5
     */
    public function rules(): array
    {
        return [
            'name'                  => ['required', 'string', 'max:50', new SafeCommentText],
            'email'                 => ['required', 'string', 'email:rfc,dns'],
            'message'               => ['required', 'string', new MaxWords(50), 'max:500', new SafeCommentText],
            'cf-turnstile-response' => ['required', 'string', new Turnstile],
            // 'website'    intentionally omitted → silently discarded from validated()
            // 'website_url' intentionally omitted → honeypot field checked in controller
        ];
    }

    /**
     * Get custom validation error messages.
     *
     * Requirements: 1.3, 3.1, 3.2
     */
    public function messages(): array
    {
        return [
            'cf-turnstile-response.required' => 'Please complete the security challenge.',
            'message.max'                    => 'Your comment may not exceed 500 characters.',
            'message.*'                      => 'Your comment contains content that is not allowed.',
        ];
    }
}
