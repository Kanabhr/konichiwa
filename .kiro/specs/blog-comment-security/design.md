# Design Document: Blog Comment Security

## Overview

This document describes the technical design for securing the "Leave a Reply" comment form at `/blog/{slug}` in the Konichiwa Laravel application. The current `BlogCommentController` accepts any submission that passes basic length checks with no bot protection or content filtering.

The solution introduces five cooperating layers:

1. **Cloudflare Turnstile** — server-side bot detection via `Turnstile` Rule class
2. **Content filtering** — `SafeCommentText` Rule class with a normalisation pipeline that rejects HTML, scripts, URLs, and image references
3. **Field & length validation** — `StoreCommentRequest` FormRequest enforcing character limits, word limits, and field rules
4. **Spam/flood defences** — `throttle:5,1` rate limiting and a honeypot field
5. **Content-Security-Policy middleware** — permits the Turnstile widget while preserving all existing external resources

No new Composer packages are introduced. All HTTP calls use Laravel's `Http` facade. All sensitive values are read exclusively from `config/services.php` which reads from `.env`.

---

## Architecture

```
Browser                         Laravel (web middleware group)
  │                                       │
  │  POST /blog/{blog}/comments           │
  │ ──────────────────────────────────────►
  │                                       │
  │                          ┌────────────▼────────────┐
  │                          │  throttle:5,1            │  Rate limit (per IP)
  │                          └────────────┬────────────┘
  │                          ┌────────────▼────────────┐
  │                          │  VerifyCsrfToken         │  CSRF check (419 on fail)
  │                          └────────────┬────────────┘
  │                          ┌────────────▼────────────┐
  │                          │  StoreCommentRequest     │  FormRequest validation
  │                          │  ├─ honeypot check       │
  │                          │  ├─ required/max rules   │
  │                          │  ├─ SafeCommentText Rule │  ──► normalise + reject patterns
  │                          │  └─ Turnstile Rule       │  ──► POST to Cloudflare siteverify
  │                          └────────────┬────────────┘
  │                          ┌────────────▼────────────┐
  │                          │  BlogCommentController   │
  │                          │  BlogComment::create()   │  Only $request->validated()
  │                          └─────────────────────────┘
  │
  │  Every response (all status codes)
  │ ◄──────────────────────────────────── ContentSecurityPolicyMiddleware
```

The normaliser pipeline in `SafeCommentText` is a pure function applied to a copy of the input before any rejection check, ensuring encoded/obfuscated variants are always evaluated in their decoded, canonical form.

---

## Components and Interfaces

### 1. `App\Rules\Turnstile`

Implements `Illuminate\Contracts\Validation\ValidationRule`.

```php
class Turnstile implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
```

**Responsibilities:**
- POST `{secret, response, remoteip}` to `https://challenges.cloudflare.com/turnstile/v0/siteverify` using `Http::asForm()->timeout(5)`
- On HTTP error, connection failure, or timeout → call `$fail` with a generic "unable to verify" message; do **not** throw
- On `success === false` → call `$fail` with "please complete the challenge" message
- On `error-codes` containing `timeout-or-duplicate` → call `$fail` with "challenge expired or already used" message
- On hostname mismatch (when `config('services.turnstile.skip_hostname_check')` is `false`) → call `$fail` with "domain mismatch" message
- Config keys consumed: `services.turnstile.secret_key`, `services.turnstile.expected_hostname`, `services.turnstile.skip_hostname_check`
- `remoteip` sourced from `request()->ip()`

### 2. `App\Rules\SafeCommentText`

Implements `Illuminate\Contracts\Validation\ValidationRule`.

```php
class SafeCommentText implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
```

**Normaliser pipeline** (`private function normalise(string $input): string`):

```
trim($input)
→ preg_replace('/\s+/', ' ', $s)                   // collapse whitespace
→ loop up to 5 passes: html_entity_decode + rawurldecode until stable
→ if extension_loaded('intl'): Normalizer::normalize($s, Normalizer::FORM_KC)
   else: mb_convert_encoding($s, 'UTF-8', 'UTF-8')  // fallback
→ mb_strtolower($s)                                  // for case-insensitive URL checks
→ preg_replace('/[\x{200B}-\x{200D}\x{FEFF}]/u', '', $s)  // strip zero-width chars
→ preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/u', '', $s)  // strip control chars
```

**Rejection checks** (applied to normalised copy):

| Pattern group | Detection |
|---|---|
| HTML tags / angle brackets | `/<\s*[a-z]` or bare `<`/`>` or fullwidth `\xFF1C`/`\xFF1E` |
| Event handlers | `/\bon\w+\s*=/i` |
| Dangerous URI schemes | `javascript:`, `vbscript:`, `data:` |
| URLs | `http://`, `https://`, `ftp://`, `//` followed by hostname, `www\.`, `[a-z0-9-]+\.[a-z]{2,}/` |
| Image references | file extensions `.svg .png .jpg .jpeg .gif .webp .bmp .ico .avif .tiff`, Markdown `![...]()`, BBCode `[img]`, CSS `url(` |

**`intl`-fallback mode** (when `extension_loaded('intl')` is `false`):
- Skip `Normalizer::normalize`; use `mb_convert_encoding` instead
- Apply additional strict rejection: any character outside `\p{L}\p{N}\p{Z}\p{P}` triggers failure
- Log warning via `Log::warning('SafeCommentText: intl extension unavailable, operating in fallback mode')` — logged once per process via a static flag
- If `app()->environment('production')`: additionally log `Log::critical('...')`

### 3. `App\Http\Requests\StoreCommentRequest`

Extends `Illuminate\Foundation\Http\FormRequest`.

```php
class StoreCommentRequest extends FormRequest
{
    public function authorize(): bool   // always true (auth handled by route/middleware)
    public function rules(): array
    public function withValidator(Validator $validator): void  // honeypot check
    public function messages(): array   // custom error messages
```

**`rules()` output:**
```php
[
    'name'                 => ['required', 'string', 'max:50', new SafeCommentText],
    'email'                => ['required', 'string', 'email:rfc,dns'],
    'message'              => ['required', 'string', new MaxWords(50), 'max:500', new SafeCommentText],
    'cf-turnstile-response'=> ['required', 'string', new Turnstile],
    'website'              => []   // not listed → silently discarded by validated()
]
```

`max:500` maps to `mb_strlen` via Laravel's built-in rule. The `MaxWords` rule is a simple inline check or a dedicated Rule class that splits on `/\s+/` and counts tokens.

**`withValidator()` — honeypot:**
```php
$validator->after(function ($v) {
    if ($this->filled('website_url')) {
        // Silently mark as passed but flag for controller to short-circuit
        $this->merge(['_honeypot_triggered' => true]);
    }
});
```

Actually, the honeypot must return the same response as success without storing. The cleanest pattern is to check in the controller **before** `BlogComment::create()`:

```php
public function store(StoreCommentRequest $request, Blog $blog): RedirectResponse
{
    if ($request->filled('website_url')) {
        return redirect()->route('blog-details', $blog->slug)
            ->with('comment_success', 'Thank you! Your comment has been submitted and is awaiting approval.');
    }

    BlogComment::create(array_merge(
        $request->validated(),
        ['blog_id' => $blog->id, 'approved' => false]
    ));

    return redirect()->route('blog-details', $blog->slug)
        ->with('comment_success', 'Thank you! Your comment has been submitted and is awaiting approval.');
}
```

`$request->validated()` never includes `website`, `website_url`, `cf-turnstile-response`, or any other unlisted field.

### 4. `App\Http\Controllers\BlogCommentController` (modified)

- Replace `Request $request` parameter with `StoreCommentRequest $request`
- Remove inline `$request->validate([...])` call
- Store via `BlogComment::create(array_merge($request->validated(), ['blog_id' => $blog->id, 'approved' => false]))`
- Honeypot short-circuit as shown above

### 5. `App\Http\Middleware\ContentSecurityPolicy`

Implements standard Laravel middleware interface.

```php
class ContentSecurityPolicy
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);
        $response->headers->set('Content-Security-Policy', $this->buildHeader());
        return $response;
    }

    private function buildHeader(): string
```

**Directives to include:**

| Directive | Allowed origins |
|---|---|
| `default-src` | `'self'` |
| `script-src` | `'self'` `https://challenges.cloudflare.com` + CDN origins in use |
| `style-src` | `'self'` `'unsafe-inline'` `https://fonts.googleapis.com` + CDN origins |
| `font-src` | `'self'` `https://fonts.gstatic.com` + Font Awesome CDN |
| `img-src` | `'self'` `data:` |
| `frame-src` | `'self'` `https://challenges.cloudflare.com` |
| `connect-src` | `'self'` `https://challenges.cloudflare.com` |
| `object-src` | `'none'` |
| `base-uri` | `'self'` |
| `form-action` | `'self'` |

The exact set of CDN origins must be enumerated by inspecting the project's layout and partial Blade files. If any origin is missed and resources break, the middleware switches to `Content-Security-Policy-Report-Only` mode (per Req 7.6), and this is documented in `TURNSTILE_CHANGES.md`.

The middleware MUST NOT emit both `Content-Security-Policy` and `Content-Security-Policy-Report-Only` on the same response.

**Registration** in `bootstrap/app.php`:
```php
$middleware->web(append: [
    HandleAppearance::class,
    HandleInertiaRequests::class,
    AddLinkHeadersForPreloadedAssets::class,
    \App\Http\Middleware\ContentSecurityPolicy::class,  // ← add here
]);
```

### 6. `App\Rules\MaxWords`

A small Rule class to enforce the 50-word limit.

```php
class MaxWords implements ValidationRule
{
    public function __construct(private int $max = 50) {}

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $count = count(preg_split('/\s+/', trim($value), -1, PREG_SPLIT_NO_EMPTY));
        if ($count > $this->max) {
            $fail("The :attribute may not exceed {$this->max} words.");
        }
    }
}
```

### 7. Migration: `alter_blog_comments_message_to_varchar_500`

```php
public function up(): void
{
    // Safety check: refuse to run if oversized rows exist
    $oversized = DB::table('blog_comments')
        ->whereRaw('CHAR_LENGTH(message) > 500')
        ->get(['id']);

    if ($oversized->isNotEmpty()) {
        $ids = $oversized->pluck('id')->implode(', ');
        $this->command->error(
            "Migration aborted: {$oversized->count()} row(s) exceed 500 characters. IDs: {$ids}"
        );
        throw new \RuntimeException('Migration halted: oversized message rows detected.');
    }

    Schema::table('blog_comments', function (Blueprint $table) {
        $table->string('message', 500)->change();
    });
}

public function down(): void
{
    Schema::table('blog_comments', function (Blueprint $table) {
        $table->text('message')->change();
    });
}
```

### 8. `App\Console\Commands\ScanComments`

Read-only Artisan command: `comments:scan`

```php
protected $signature   = 'comments:scan';
protected $description = 'Scan blog_comments for HTML, URLs, and image references without modifying data.';
```

**Output:**
```
Total rows:              42
Rows with HTML tags:      3
Rows with URLs:           2
Rows with image refs:     0
```

Exits with code `0` on success. On DB failure, outputs an error and exits with code `1`. Never modifies, deletes, or writes any data.

### 9. `config/services.php` — Turnstile block

```php
'turnstile' => [
    'site_key'             => env('TURNSTILE_SITE_KEY'),
    'secret_key'           => env('TURNSTILE_SECRET_KEY'),
    'expected_hostname'    => env('TURNSTILE_EXPECTED_HOSTNAME'),
    'skip_hostname_check'  => env('TURNSTILE_SKIP_HOSTNAME_CHECK', false),
],
```

### 10. `resources/views/blog-details.blade.php` — form changes

Additions to the existing `<form>`:

```blade
{{-- Turnstile widget --}}
<script src="https://challenges.cloudflare.com/turnstile/v0/api.js" defer></script>
<div class="cf-turnstile" data-sitekey="{{ config('services.turnstile.site_key') }}"></div>
@error('cf-turnstile-response')
    <div class="invalid-feedback d-block">{{ $message }}</div>
@enderror

{{-- Honeypot (hidden from real users; bots fill it in) --}}
<input type="text" name="website_url" value="" style="display:none;" tabindex="-1" autocomplete="off">
```

Comment display (currently safe with `{{ }}` — no change needed for `name` and `message`). If line-break preservation is ever added: `{!! nl2br(e($comment->message)) !!}`.

### 11. Route change — rate limiting

```php
// routes/web.php
Route::post('/blog/{blog}/comments', [BlogCommentController::class, 'store'])
    ->name('blog.comments.store')
    ->middleware('throttle:5,1');
```

### 12. `TURNSTILE_CHANGES.md`

Root-level Markdown file documenting all changed files, production env keys, deployment steps, manual verification checklist, and optional `comments:scan` findings. See Requirement 12 for full content specification.

---

## Data Models

### `blog_comments` table (after migration)

| Column | Type | Notes |
|---|---|---|
| `id` | BIGINT UNSIGNED | PK, auto-increment |
| `blog_id` | BIGINT UNSIGNED | FK → `blogs.id` ON DELETE CASCADE |
| `name` | VARCHAR(255) | Unchanged |
| `email` | VARCHAR(255) | Unchanged |
| `message` | **VARCHAR(500)** | Changed from `TEXT` via new migration |
| `approved` | BOOLEAN | Default `false` |
| `created_at` | TIMESTAMP | |
| `updated_at` | TIMESTAMP | |

### `BlogComment` Eloquent model (existing, no change needed)

```php
protected $fillable = ['blog_id', 'name', 'email', 'message', 'approved'];
```

Already correct. The `$guarded = []` pattern is not used. No changes needed to the model itself.

### Data flow through `StoreCommentRequest::validated()`

Only these keys are returned:

| Key | Rule | Stored |
|---|---|---|
| `name` | required, string, max:50, SafeCommentText | ✓ |
| `email` | required, string, email:rfc,dns | ✓ |
| `message` | required, string, MaxWords(50), max:500, SafeCommentText | ✓ |
| `cf-turnstile-response` | required, string, Turnstile | ✗ (not in $fillable) |
| `website` | not listed | ✗ silently discarded |
| `website_url` | not listed | ✗ silently discarded |

---

## Correctness Properties

*A property is a characteristic or behavior that should hold true across all valid executions of a system — essentially, a formal statement about what the system should do. Properties serve as the bridge between human-readable specifications and machine-verifiable correctness guarantees.*

### Property 1: Normaliser idempotence

*For any* string input, applying the `SafeCommentText` normaliser pipeline twice must produce the same result as applying it once. The normalised form is stable.

**Validates: Requirements 2.1**

---

### Property 2: HTML and script patterns are always rejected after normalisation

*For any* string that, after normalisation, contains an HTML tag opener (`<` followed by a tag name), an `on*=` event handler attribute, a `javascript:`, `vbscript:`, or `data:` URI scheme, or fullwidth angle bracket equivalents — `SafeCommentText::passes()` must return `false`, regardless of how the input was originally encoded or cased.

**Validates: Requirements 2.2, 2.5**

---

### Property 3: URL patterns are always rejected after normalisation

*For any* string that, after normalisation and lowercasing, contains `http://`, `https://`, `ftp://`, a `//`-prefixed hostname, `www.`, or a domain-like pattern matching `[a-z0-9-]+\.[a-z]{2,}/` — `SafeCommentText::passes()` must return `false`.

**Validates: Requirements 2.3**

---

### Property 4: Image references are always rejected after normalisation

*For any* string that, after normalisation, contains an image file extension (`.png`, `.jpg`, `.jpeg`, `.gif`, `.webp`, `.svg`, `.bmp`, `.ico`, `.avif`, `.tiff`), a Markdown image syntax `![...]()`, a BBCode `[img]...[/img]`, or a CSS `url(` pattern — `SafeCommentText::passes()` must return `false`.

**Validates: Requirements 2.4**

---

### Property 5: Zero-width characters are stripped before any check runs

*For any* string with zero-width characters (`\u200B`–`\u200D`, `\uFEFF`) inserted at arbitrary positions within otherwise valid or banned content, the normaliser must produce a string containing none of those characters. Consequently, a banned pattern obfuscated solely by zero-width interleaving must still be detected by the subsequent rejection checks.

**Validates: Requirements 2.6, 2.7**

---

### Property 6: Valid plain text is always accepted

*For any* string composed entirely of Unicode letters (`\p{L}`), digits (`\p{N}`), Unicode separators (`\p{Z}`), and punctuation (`\p{P}`) — with no banned HTML, URL, or image patterns — `SafeCommentText::passes()` must return `true`.

**Validates: Requirements 2.8**

---

## Error Handling

### Validation failures (422)

`StoreCommentRequest` returns a 422 Unprocessable Entity with a JSON `errors` object (or a redirect with `$errors` bag for non-AJAX requests) on any validation failure. Laravel's default FormRequest error handling is used.

Custom messages are defined in `StoreCommentRequest::messages()`:

```php
'cf-turnstile-response.required' => 'Please complete the security challenge.',
'message.max'                    => 'Your comment may not exceed 500 characters.',
'message.*'                      => 'Your comment contains content that is not allowed.',  // covers MaxWords + SafeCommentText
```

### Turnstile network failure

If `Http::asForm()->timeout(5)->post(...)` throws a `ConnectionException` or returns a non-2xx status, `Turnstile::validate()` calls `$fail('We were unable to verify your response. Please try again.')`. The exception is caught internally — it does not propagate to the global exception handler.

### `intl` extension missing

- On first call to `SafeCommentText::validate()` when `extension_loaded('intl')` is `false`, log once at `warning` level (and `critical` if `app()->environment('production')`)
- Continue execution using fallback normalisation (see Components section)
- The application never halts; it degrades to stricter rejection

### Migration rollback on oversized rows

The `up()` method throws a `\RuntimeException` before any schema change when oversized rows are detected. Laravel rolls back the transaction (if using a transactional migration driver). On SQLite (the dev driver), the check occurs before the DDL statement, so no partial change is made.

### Rate limit exceeded (429)

The `throttle:5,1` middleware returns HTTP 429 with a `Retry-After` header containing the remaining seconds in the current window. This is Laravel's default throttle behaviour.

### CSRF token missing (419)

Laravel's `VerifyCsrfToken` middleware handles this automatically and returns HTTP 419.

### Honeypot triggered

The controller checks `$request->filled('website_url')` before `BlogComment::create()`. If filled, it returns the same success redirect without writing any database row. From the submitter's perspective this is indistinguishable from a real success.

---

## Testing Strategy

### Test framework

Pest 3 with `pestphp/pest-plugin-laravel`, `RefreshDatabase` trait, and `Http::fake()` for all Cloudflare siteverify calls.

Test file location: `tests/Feature/BlogCommentSecurityTest.php`

### Acceptance test matrix (from Requirement 11.2)

Each row is a `test()` case:

| Input / Scenario | Expected status |
|---|---|
| `<script>alert(1)</script>` in message | 422 |
| `<img src=x onerror=alert(1)>` in message | 422 |
| `https://site.com/a.svg` in message | 422 |
| `www.evil.com` in message | 422 |
| `evil.com/x.png` in message | 422 |
| `![x](a.png)` in message | 422 |
| `[img]a.jpg[/img]` in message | 422 |
| `javascript:alert(1)` in message | 422 |
| `&lt;script&gt;` in message | 422 |
| `%3Cscript%3E` in message | 422 |
| `&amp;lt;script&amp;gt;` in message | 422 |
| `data:image/svg+xml;base64,PHN2Zz4=` in message | 422 |
| Fullwidth `<script>` (using \xFF1C, \xFF1E) | 422 |
| Zero-width obfuscated `http://x.com` | 422 |
| 51-word message | 422 |
| 501-character message | 422 |
| `'; DROP TABLE comments;--` in message | 422 (SafeCommentText passes this — plain SQL is allowed; it is safe text. This test verifies the rejection logic is not over-broad. **Accepted, not rejected.**) |
| Missing Turnstile token | 422 |
| Failed Turnstile token (success=false) | 422 |
| Reused Turnstile token (timeout-or-duplicate error-code) | 422 |
| Cloudflare timeout | 422 |
| Wrong hostname in siteverify response | 422 |
| Filled honeypot field | Same as 200/redirect, zero DB rows |
| Sixth request same IP within one minute | 429 |
| Normal 20-word plain-text comment, passing Turnstile | 200 or redirect + row in DB |

> Note on SQL injection input: `'; DROP TABLE comments;--` is plain text with no HTML, URLs, or image references. It passes `SafeCommentText` deliberately — stored content is never executed as SQL because `BlogComment::create()` uses Eloquent's parameterised bindings. The test for this input should assert **acceptance** (status 200/redirect), not rejection. This confirms the rule is not over-broad.

### Property-based tests

Use [**RoachPHP/fast-check** is not a PHP library]. PHP's leading PBT library is [**eris**](https://github.com/giorgiosironi/eris) or manual generator helpers within Pest. Given the project uses no PBT library currently and the properties are all pure-function checks on `SafeCommentText`, implement them as **data-driven Pest datasets** covering a wide range of generated inputs. Each "property" test uses `it()->with()` with a dataset of 50–100 generated inputs.

Alternatively, a simple custom generator helper can be placed in `tests/Pest.php`:

```php
function generateStrings(callable $factory, int $count = 100): array {
    return array_map(fn($i) => [$factory($i)], range(1, $count));
}
```

Each property test is tagged with a comment: `// Feature: blog-comment-security, Property N: <property text>`

**Property 1 — Normaliser idempotence** (`tests/Unit/SafeCommentTextNormaliserTest.php`):
- Dataset: 100 random strings (alphanumeric, unicode, encoded, mixed)
- Assert: `normalise(normalise($s)) === normalise($s)` (access via a test-visible method or a thin wrapper)
- Tag: `// Feature: blog-comment-security, Property 1: normaliser idempotence`

**Property 2 — HTML/script rejection** (`tests/Feature/BlogCommentSecurityTest.php`):
- Dataset: 100 strings each containing a randomly chosen banned HTML pattern (with varying encoding layers)
- Assert: `SafeCommentText::passes()` returns `false`
- Tag: `// Feature: blog-comment-security, Property 2: HTML and script patterns rejected`

**Property 3 — URL rejection** (`tests/Feature/BlogCommentSecurityTest.php`):
- Dataset: 100 strings containing randomly chosen URL patterns (upper/lowercase variants)
- Assert: `SafeCommentText::passes()` returns `false`
- Tag: `// Feature: blog-comment-security, Property 3: URL patterns rejected`

**Property 4 — Image reference rejection** (`tests/Feature/BlogCommentSecurityTest.php`):
- Dataset: 100 strings containing randomly chosen image reference patterns
- Assert: `SafeCommentText::passes()` returns `false`
- Tag: `// Feature: blog-comment-security, Property 4: image references rejected`

**Property 5 — Zero-width stripping** (`tests/Unit/SafeCommentTextNormaliserTest.php`):
- Dataset: 100 strings with zero-width characters injected at random positions in both benign and banned content
- Assert: normalised string contains no zero-width chars; banned content is still detected
- Tag: `// Feature: blog-comment-security, Property 5: zero-width characters stripped`

**Property 6 — Valid text accepted** (`tests/Feature/BlogCommentSecurityTest.php`):
- Dataset: 100 strings of plain Unicode text (letters, digits, punctuation) with no banned patterns
- Assert: `SafeCommentText::passes()` returns `true`
- Tag: `// Feature: blog-comment-security, Property 6: valid plain text accepted`

### Additional tests

- `extension_loaded('intl')` returns `true` — `tests/Feature/BlogCommentSecurityTest.php`
- Blade scan for unescaped `{!! $comment->` — `tests/Feature/BlogCommentSecurityTest.php`
- CSRF: POST without token → 419
- CSP header present and contains all required directives
- `config('services.turnstile')` has all required keys

### Unit Tests

- `SafeCommentText` fallback mode (mock `extension_loaded` or a static flag mechanism)
- `Turnstile` rule with each siteverify failure scenario (`Http::fake()`)
- `MaxWords` with boundary values (50 words pass, 51 fail)
- Migration rollback test with seeded oversized rows
- `comments:scan` Artisan command output format and exit codes
