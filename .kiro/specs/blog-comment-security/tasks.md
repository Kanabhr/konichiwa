# Implementation Plan: Blog Comment Security

## Overview

Implement five cooperating security layers on the blog comment form: Cloudflare Turnstile bot detection, plain-text content filtering (`SafeCommentText`), field/length validation (`StoreCommentRequest`), spam/flood defences (rate limit + honeypot), and a Content-Security-Policy middleware. No new Composer packages are required — all work uses existing Laravel facilities. Tasks are ordered so each step produces immediately testable code that wires into the previous step.

---

## Tasks

- [x] 1. Bootstrap configuration and environment
  - [x] 1.1 Add Turnstile block to `config/services.php`
    - Append the `'turnstile'` array with keys `site_key`, `secret_key`, `expected_hostname`, `skip_hostname_check` (defaults `false`), each reading exclusively from `env()`
    - _Requirements: 1.9, 9.1, 9.2, 9.6_

  - [x] 1.2 Update `.env.example` with Turnstile placeholder entries
    - Add `TURNSTILE_SITE_KEY=`, `TURNSTILE_SECRET_KEY=`, `TURNSTILE_EXPECTED_HOSTNAME=` with **empty values only** — no real or dummy keys
    - _Requirements: 1.10, 9.3_

- [x] 2. Implement custom Rule classes
  - [x] 2.1 Create `App\Rules\MaxWords`
    - File: `app/Rules/MaxWords.php`
    - Constructor accepts `int $max = 50`; `validate()` splits on `/\s+/` via `preg_split` with `PREG_SPLIT_NO_EMPTY`, counts tokens, calls `$fail` if count exceeds `$max`
    - _Requirements: 3.2_

  - [x] 2.2 Create `App\Rules\SafeCommentText`
    - File: `app/Rules/SafeCommentText.php`
    - Implement `private function normalise(string $input): string` with the full pipeline: trim → collapse whitespace → loop up to 5× `html_entity_decode` + `rawurldecode` until stable → `Normalizer::normalize(FORM_KC)` (or `mb_convert_encoding` fallback) → `mb_strtolower` → strip zero-width chars `\x{200B}–\x{200D}\x{FEFF}` → strip control chars
    - Implement all rejection checks on the normalised copy: HTML tags/angle brackets + fullwidth equivalents, `on*=` event handlers, `javascript:`/`vbscript:`/`data:` URI schemes, URL patterns, image file extensions + Markdown `![...]()` + BBCode `[img]` + CSS `url(`
    - Implement `intl` fallback: when `extension_loaded('intl')` is `false`, skip `Normalizer::normalize`, use `mb_convert_encoding`, apply strict rejection (`\p{L}\p{N}\p{Z}\p{P}` only), and log once at `warning` (+ `critical` in production) using a static flag
    - _Requirements: 2.1–2.9, 8.2–8.4_

  - [ ]* 2.3 Write unit tests for `MaxWords`
    - Test: exactly 50 words passes; 51 words fails; empty string passes; single word passes
    - File: `tests/Unit/MaxWordsTest.php`
    - _Requirements: 3.2_

  - [x] 2.4 Create `App\Rules\Turnstile`
    - File: `app/Rules/Turnstile.php`
    - `validate()` POSTs `{secret, response, remoteip}` to `https://challenges.cloudflare.com/turnstile/v0/siteverify` via `Http::asForm()->timeout(5)`
    - Handle all failure modes: HTTP error / `ConnectionException` / timeout → generic `$fail`; `success === false` → challenge fail message; `error-codes` contains `timeout-or-duplicate` → expired/duplicate message; hostname mismatch when `skip_hostname_check` is `false` → domain mismatch message
    - All config read from `config('services.turnstile.*')`, `remoteip` from `request()->ip()`
    - _Requirements: 1.3–1.8, 9.1_

  - [ ]* 2.5 Write unit tests for `Turnstile` rule
    - Use `Http::fake()` to simulate: pass, `success=false`, `timeout-or-duplicate`, connection exception, HTTP 500, hostname mismatch, `skip_hostname_check=true` bypasses hostname check
    - File: `tests/Unit/TurnstileRuleTest.php`
    - _Requirements: 1.4–1.8_

- [x] 3. Implement `StoreCommentRequest` FormRequest
  - [x] 3.1 Create `App\Http\Requests\StoreCommentRequest`
    - File: `app/Http/Requests/StoreCommentRequest.php`
    - `authorize()` returns `true`
    - `rules()` returns: `name` → `[required, string, max:50, new SafeCommentText]`; `email` → `[required, string, email:rfc,dns]`; `message` → `[required, string, new MaxWords(50), max:500, new SafeCommentText]`; `cf-turnstile-response` → `[required, string, new Turnstile]`; `website` not listed (silently discarded)
    - `messages()` with custom messages for `cf-turnstile-response.required`, `message.max`, `message.*`
    - _Requirements: 1.3, 3.1–3.3, 4.1–4.5_

- [x] 4. Update `BlogCommentController`
  - [x] 4.1 Refactor `BlogCommentController::store()` to use `StoreCommentRequest`
    - File: `app/Http/Controllers/BlogCommentController.php`
    - Replace `Request $request` with `StoreCommentRequest $request`
    - Remove the inline `$request->validate([...])` call
    - Add honeypot short-circuit: if `$request->filled('website_url')` return the success redirect immediately without calling `BlogComment::create()`
    - Store via `BlogComment::create(array_merge($request->validated(), ['blog_id' => $blog->id, 'approved' => false]))`
    - _Requirements: 4.6, 5.4_

- [x] 5. Database migration
  - [x] 5.1 Create migration `alter_blog_comments_message_to_varchar_500`
    - File: `database/migrations/{timestamp}_alter_blog_comments_message_to_varchar_500.php`
    - `up()`: query `blog_comments` for rows where `CHAR_LENGTH(message) > 500`; if any exist, output error with count + IDs and throw `\RuntimeException` (aborts before schema change); otherwise `$table->string('message', 500)->change()`
    - `down()`: `$table->text('message')->change()`
    - _Requirements: 3.4, 3.5_

- [x] 6. Verify `BlogComment` model `$fillable`
  - [x] 6.1 Confirm `BlogComment` has correct `$fillable` and no `$guarded = []`
    - File: `app/Models/BlogComment.php`
    - The existing `$fillable = ['blog_id', 'name', 'email', 'message', 'approved']` is already correct — add inline comment confirming `cf-turnstile-response`, `website`, and `website_url` are intentionally absent from `$fillable`
    - _Requirements: 4.6_

- [x] 7. Implement `ContentSecurityPolicy` middleware
  - [x] 7.1 Create `App\Http\Middleware\ContentSecurityPolicy`
    - File: `app/Http/Middleware/ContentSecurityPolicy.php`
    - `handle()` calls `$next($request)`, then sets the CSP header on the response and returns it — covers all status codes
    - `buildHeader()` assembles directives: `default-src 'self'`; `script-src 'self' https://challenges.cloudflare.com` + any CDN script origins found in Blade layouts; `style-src 'self' 'unsafe-inline' https://fonts.googleapis.com` + CDN style origins; `font-src 'self' https://fonts.gstatic.com` + Font Awesome CDN; `img-src 'self' data:`; `frame-src 'self' https://challenges.cloudflare.com`; `connect-src 'self' https://challenges.cloudflare.com`; `object-src 'none'`; `base-uri 'self'`; `form-action 'self'`
    - Audit `resources/views/layouts/` Blade files to enumerate all third-party script/style/font origins; include each in the appropriate directive
    - MUST NOT emit both `Content-Security-Policy` and `Content-Security-Policy-Report-Only` on the same response; if full enforcement is not feasible, switch entirely to `Content-Security-Policy-Report-Only` and document in `TURNSTILE_CHANGES.md`
    - _Requirements: 7.1–7.8_

  - [x] 7.2 Register `ContentSecurityPolicy` in `bootstrap/app.php`
    - Append `\App\Http\Middleware\ContentSecurityPolicy::class` to the `web` middleware group `append` array (after `AddLinkHeadersForPreloadedAssets::class`)
    - Leave the `api` middleware group untouched
    - _Requirements: 7.7_

- [x] 8. Update route with rate limiting
  - [x] 8.1 Add `throttle:5,1` middleware to the comment store route
    - File: `routes/web.php`
    - Chain `->middleware('throttle:5,1')` onto the existing `Route::post('/blog/{blog}/comments', ...)` definition
    - _Requirements: 5.1, 5.2_

- [x] 9. Update Blade view
  - [x] 9.1 Add Turnstile widget, honeypot field, and error display to `blog-details.blade.php`
    - File: `resources/views/blog-details.blade.php`
    - Inside `<form>`, before the submit button: add `<script src="https://challenges.cloudflare.com/turnstile/v0/api.js" defer></script>`, the `<div class="cf-turnstile" data-sitekey="{{ config('services.turnstile.site_key') }}">`, `@error('cf-turnstile-response')` display block, and the hidden honeypot `<input type="text" name="website_url" value="" style="display:none;" tabindex="-1" autocomplete="off">`
    - Audit all `{!! !!}` expressions referencing comment fields — confirm `$comment->name` and `$comment->message` are output only via `{{ }}` (they already are based on the current view); add inline comment noting the safe output pattern
    - _Requirements: 1.1, 1.2, 5.3, 6.1, 6.3_

- [x] 10. Implement `ScanComments` Artisan command
  - [x] 10.1 Create `App\Console\Commands\ScanComments`
    - File: `app/Console/Commands/ScanComments.php`
    - Signature: `comments:scan`; description as per design
    - Counts total rows; counts rows matching HTML tag pattern; counts rows matching URL patterns; counts rows matching image-extension patterns — all via DB queries, no data modification
    - Outputs the four-line report table; exits `0` on success, `1` on DB failure
    - _Requirements: 12.7, 12.8_

- [x] 11. Checkpoint — verify rules, request, controller, and route work end-to-end
  - Ensure all tests pass, ask the user if questions arise.

- [x] 12. Write automated test suite
  - [x] 12.1 Create `tests/Feature/BlogCommentSecurityTest.php`
    - Use `RefreshDatabase`, `Http::fake()` for all siteverify calls
    - Include reject tests (422) for every input listed in Requirement 11.2: `<script>alert(1)</script>`, `<img src=x onerror=alert(1)>`, `https://site.com/a.svg`, `www.evil.com`, `evil.com/x.png`, `![x](a.png)`, `[img]a.jpg[/img]`, `javascript:alert(1)`, `&lt;script&gt;`, `%3Cscript%3E`, `&amp;lt;script&amp;gt;`, `data:image/svg+xml;base64,PHN2Zz4=`, fullwidth `<script>` (`\xFF1C`/`\xFF1E`), zero-width obfuscated `http://x.com`, 51 words, 501 characters, missing Turnstile token, failed Turnstile, `timeout-or-duplicate` Turnstile, Cloudflare timeout, wrong hostname
    - Include accept test: `'; DROP TABLE comments;--` asserts **200/redirect** (not 422) — confirms rule is not over-broad
    - Include honeypot test: filled `website_url` → same redirect as success, zero DB rows written
    - Include rate-limit test: sixth POST from same IP within 1 minute → 429
    - Include accept test: 20-word plain-text comment, passing Turnstile `Http::fake()` → 200/redirect + row stored in DB
    - Include CSRF test: POST without token → 419
    - Include CSP header test: assert response has `Content-Security-Policy` header containing `challenges.cloudflare.com`
    - Include `extension_loaded('intl')` assertion (Requirement 8.5 / 11.4)
    - Include Blade audit test: scan all `.blade.php` under `resources/views/` for `{!! $comment->` not wrapped in `e()` — assert zero matches (Requirement 11.5)
    - Include `config('services.turnstile')` keys test: assert all four keys exist and are non-null in non-production env with dummy keys set
    - _Requirements: 11.1–11.5_

  - [ ]* 12.2 Write property test — Property 1: normaliser idempotence
    - File: `tests/Unit/SafeCommentTextNormaliserTest.php`
    - Dataset: 100 generated strings (alphanumeric, unicode, encoded variants, mixed); assert `normalise(normalise($s)) === normalise($s)` via a test-visible wrapper or protected-to-public reflection
    - Tag: `// Feature: blog-comment-security, Property 1: normaliser idempotence`
    - **Property 1: Normaliser Idempotence**
    - **Validates: Requirements 2.1**

  - [ ]* 12.3 Write property test — Property 2: HTML/script rejection
    - File: `tests/Feature/BlogCommentSecurityTest.php`
    - Dataset: 100 strings each containing a randomly chosen banned HTML pattern with varying encoding layers; assert `SafeCommentText` rejects each
    - Tag: `// Feature: blog-comment-security, Property 2: HTML and script patterns rejected`
    - **Property 2: HTML and Script Patterns Always Rejected After Normalisation**
    - **Validates: Requirements 2.2, 2.5**

  - [ ]* 12.4 Write property test — Property 3: URL rejection
    - File: `tests/Feature/BlogCommentSecurityTest.php`
    - Dataset: 100 strings containing randomly chosen URL patterns (upper/lower/mixed case); assert `SafeCommentText` rejects each
    - Tag: `// Feature: blog-comment-security, Property 3: URL patterns rejected`
    - **Property 3: URL Patterns Always Rejected After Normalisation**
    - **Validates: Requirements 2.3**

  - [ ]* 12.5 Write property test — Property 4: image reference rejection
    - File: `tests/Feature/BlogCommentSecurityTest.php`
    - Dataset: 100 strings containing randomly chosen image reference patterns; assert `SafeCommentText` rejects each
    - Tag: `// Feature: blog-comment-security, Property 4: image references rejected`
    - **Property 4: Image References Always Rejected After Normalisation**
    - **Validates: Requirements 2.4**

  - [ ]* 12.6 Write property test — Property 5: zero-width character stripping
    - File: `tests/Unit/SafeCommentTextNormaliserTest.php`
    - Dataset: 100 strings with `\u200B`–`\u200D` / `\uFEFF` injected at random positions in both benign and banned content; assert normalised string contains none of those characters and banned content is still detected
    - Tag: `// Feature: blog-comment-security, Property 5: zero-width characters stripped`
    - **Property 5: Zero-Width Characters Stripped Before Any Check Runs**
    - **Validates: Requirements 2.6, 2.7**

  - [ ]* 12.7 Write property test — Property 6: valid plain text accepted
    - File: `tests/Feature/BlogCommentSecurityTest.php`
    - Dataset: 100 strings of plain Unicode text (`\p{L}`, `\p{N}`, `\p{Z}`, `\p{P}`) with no banned patterns; assert `SafeCommentText` accepts each
    - Tag: `// Feature: blog-comment-security, Property 6: valid plain text accepted`
    - **Property 6: Valid Plain Text Always Accepted**
    - **Validates: Requirements 2.8**

  - [ ]* 12.8 Write unit tests for `SafeCommentText` fallback mode
    - Use a static flag or subclass to simulate `extension_loaded('intl') === false`; assert fallback normalisation runs, stricter rejection applies, warning is logged
    - File: `tests/Unit/SafeCommentTextFallbackTest.php`
    - _Requirements: 8.2–8.4_

  - [ ]* 12.9 Write migration rollback test
    - Seed a `blog_comments` row with a 501-character message; run `up()` migration; assert `RuntimeException` is thrown and schema is unchanged
    - File: `tests/Unit/BlogCommentMigrationTest.php`
    - _Requirements: 3.5_

  - [ ]* 12.10 Write `comments:scan` command tests
    - Test normal output format and exit code 0; test DB failure returns exit code 1; test no data is written
    - File: `tests/Unit/ScanCommentsCommandTest.php`
    - _Requirements: 12.7, 12.8_

- [x] 13. Create `TURNSTILE_CHANGES.md` handover file
  - [x] 13.1 Write `TURNSTILE_CHANGES.md` in project root
    - File: `TURNSTILE_CHANGES.md`
    - Sections: (a) changed/new files table with one-line reason each; (b) production `.env` keys to set (`TURNSTILE_SITE_KEY`, `TURNSTILE_SECRET_KEY`, `TURNSTILE_EXPECTED_HOSTNAME=konichiwachemicals.com`) with explicit warning that Dummy Keys must not be used in production; (c) deployment steps in order: copy files → set `.env` keys → `php artisan migrate` → `php artisan config:clear` → confirm `intl` extension loaded → confirm outbound HTTPS to `challenges.cloudflare.com` not blocked; (d) manual verification checklist covering Turnstile widget visibility, always-pass dummy key test, always-fail dummy key test each with observable pass/fail condition; (e) placeholder for `comments:scan` findings (total rows, HTML count, URL count, image ref count)
    - If CSP is in report-only mode, document this decision and the steps to transition to enforcing mode
    - _Requirements: 12.1–12.6_

- [x] 14. Final checkpoint — full test suite green
  - Run `php artisan test` (or `./vendor/bin/pest`); ensure all tests pass. Ask the user if questions arise.

---

## Notes

- Tasks marked with `*` are optional and can be skipped for a faster MVP
- Each task references specific requirements for traceability
- The `BlogComment` model (task 6.1) requires no code changes — only a confirmatory comment; it is included so the implementer explicitly verifies `$fillable` before the controller task depends on it
- Property tests (12.2–12.7) use data-driven Pest datasets (`it()->with()`) since no PBT library is currently in `composer.json`; a `generateStrings(callable $factory, int $count = 100): array` helper should be added to `tests/Pest.php`
- The CSP middleware (task 7.1) requires reading layout Blade files to enumerate third-party origins before writing the directive string; if any origin is uncertain, emit `Content-Security-Policy-Report-Only` and document in `TURNSTILE_CHANGES.md`
- The `.env.example` entries must have **empty values** (`TURNSTILE_SITE_KEY=`) — not the dummy keys — to satisfy Requirement 9.3; the `.env` file (not committed) holds the dummy keys for local development

## Task Dependency Graph

```json
{
  "waves": [
    { "id": 0, "tasks": ["1.1", "1.2", "2.1", "6.1"] },
    { "id": 1, "tasks": ["2.2", "5.1", "8.1"] },
    { "id": 2, "tasks": ["2.3", "2.4", "3.1"] },
    { "id": 3, "tasks": ["2.5", "4.1", "7.1", "10.1"] },
    { "id": 4, "tasks": ["7.2", "9.1"] },
    { "id": 5, "tasks": ["12.1", "12.2", "12.8", "12.9", "12.10"] },
    { "id": 6, "tasks": ["12.3", "12.4", "12.5", "12.6", "12.7"] },
    { "id": 7, "tasks": ["13.1"] }
  ]
}
```
