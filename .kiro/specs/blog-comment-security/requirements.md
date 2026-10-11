# Requirements Document

## Introduction

This feature secures the "Leave a Reply" comment form on blog post pages (`/blog/{slug}`) in the Konichiwa Laravel application. Currently the `BlogCommentController` accepts any submission that passes basic length validation, with no bot protection and no content filtering. The goal is to ensure that only short, plain-text comments from verified humans reach the database. No HTML, markup, scripts, URLs, or image references may ever be stored. Protection is enforced exclusively server-side; client-side checks are UX enhancements only.

The solution uses Cloudflare Turnstile (Managed mode) for bot detection, a dedicated `StoreCommentRequest` FormRequest for validation, and custom `Rule` classes (`Turnstile`, `SafeCommentText`) for the two domain-specific checks. A Content-Security-Policy middleware is added to allow the Turnstile widget. Rate limiting and a honeypot field defend against automated floods.

## Glossary

- **Comment_Form**: The "Leave a Reply" HTML form embedded in `blog-details.blade.php` at the bottom of every blog post page.
- **StoreCommentRequest**: A Laravel `FormRequest` class that replaces the inline `validate()` call in `BlogCommentController`. It holds all validation rules including custom rules.
- **SafeCommentText**: A Laravel `Rule` class that normalises input and rejects any content that is not plain text (no HTML, tags, event handlers, URLs, image references, scripts, or link-like patterns).
- **Normalizer**: The normalisation pipeline applied by `SafeCommentText` before any check: trim → collapse whitespace → loop `html_entity_decode` + `rawurldecode` until stable (max 5 passes) → `Normalizer::normalize($s, Normalizer::FORM_KC)` → strip zero-width and control characters. Requires PHP `intl` extension.
- **Turnstile_Rule**: A Laravel `Rule` class that POSTs the `cf-turnstile-response` token to Cloudflare's siteverify endpoint and passes only if `success` is `true` and the returned hostname matches `TURNSTILE_EXPECTED_HOSTNAME`.
- **CSP_Middleware**: A Laravel middleware that attaches a `Content-Security-Policy` response header allowing Cloudflare Turnstile domains (`https://challenges.cloudflare.com`) while preserving all existing external resources.
- **Honeypot_Field**: A hidden `<input>` field in the Comment_Form whose name is not visible to real users. Any submission with this field filled in is treated as a bot.
- **BlogComment**: The existing Eloquent model (`App\Models\BlogComment`) with `$fillable` set to `blog_id`, `name`, `email`, `message`, `approved`.
- **intl_extension**: The PHP `intl` extension required by `Normalizer::normalize`. Its absence must be detected at startup and cause the application to either halt or fall back to an explicit safe alternative.
- **Dummy_Keys**: Cloudflare-provided test key pairs for local development. Always-pass pair: site key `1x00000000000000000000AA` / secret key `1x0000000000000000000000000000000AA`. Always-fail pair: site key `2x00000000000000000000AB` / secret key `2x0000000000000000000000000000000AA`. These must never be used in production.
- **TURNSTILE_SKIP_HOSTNAME_CHECK**: An optional boolean `.env` variable that, when set to `true`, disables the hostname comparison in Turnstile_Rule. This exists solely for local development with Dummy_Keys and must default to `false`.

---

## Requirements

### Requirement 1: Cloudflare Turnstile Bot Detection

**User Story:** As a site owner, I want every comment submission to be verified by Cloudflare Turnstile, so that automated bots cannot store comments in the database.

#### Acceptance Criteria

1. THE Comment_Form SHALL include the Turnstile JavaScript from `https://challenges.cloudflare.com/turnstile/v0/api.js` loaded with `defer`.
2. THE Comment_Form SHALL include a `<div class="cf-turnstile">` element with `data-sitekey` set to `{{ config('services.turnstile.site_key') }}`.
3. WHEN a comment is submitted, THE StoreCommentRequest SHALL validate that the `cf-turnstile-response` field is present and non-empty; IF the field is absent or empty, THEN THE StoreCommentRequest SHALL reject the submission before contacting Cloudflare with an error indicating the challenge widget was not completed.
4. WHEN a comment is submitted, THE Turnstile_Rule SHALL POST to `https://challenges.cloudflare.com/turnstile/v0/siteverify` using `Http::asForm()->timeout(5)` with the fields `secret`, `response`, and `remoteip`.
5. IF the siteverify response body contains `success` equal to `false`, THEN THE Turnstile_Rule SHALL reject the submission with an error indicating the user must complete the challenge and try again.
6. WHEN the siteverify response contains a `hostname` value that does not match `config('services.turnstile.expected_hostname')` AND `config('services.turnstile.skip_hostname_check')` is `false`, THE Turnstile_Rule SHALL reject the submission with an error indicating a domain mismatch.
7. IF Cloudflare's siteverify endpoint is unreachable, times out after 5 seconds, or returns an HTTP error status, THEN THE Turnstile_Rule SHALL reject the submission with a generic error and SHALL NOT store the comment in the database.
8. IF the siteverify response body contains an `error-codes` value of `timeout-or-duplicate`, THEN THE Turnstile_Rule SHALL reject the submission with an error indicating the challenge has expired or was already used and the user must retry.
9. THE `config/services.php` file SHALL expose a `turnstile` array with keys `site_key`, `secret_key`, `expected_hostname`, and `skip_hostname_check`, each reading exclusively from environment variables via `env()`, where `skip_hostname_check` defaults to `false` when the environment variable is absent.
10. THE `.env.example` file SHALL contain `TURNSTILE_SITE_KEY=`, `TURNSTILE_SECRET_KEY=`, and `TURNSTILE_EXPECTED_HOSTNAME=` with empty values. Real keys SHALL NOT appear in any committed file.

---

### Requirement 2: Plain-Text Content Enforcement

**User Story:** As a site owner, I want all stored comments to be strictly plain text, so that no HTML, scripts, URLs, or image references can be injected or displayed.

#### Acceptance Criteria

1. WHEN a comment message is submitted, THE SafeCommentText Rule SHALL first apply the Normalizer pipeline to a copy of the input before running any content checks.
2. WHEN the normalised comment message contains `<` or `>`, HTML tags, `on*=` event handler attributes, `javascript:`, `vbscript:`, or `data:` URI patterns, THE SafeCommentText Rule SHALL reject the submission.
3. WHEN the normalised comment message contains URL patterns (`http://`, `https://`, `ftp://`, `//` followed by a hostname, `www.`, or domain-like strings matching `[a-z0-9-]+\.[a-z]{2,}/`), THE SafeCommentText Rule SHALL reject the submission; this check applies after normalisation including lowercasing so uppercase variants such as `HTTP://` and `WWW.` are also caught.
4. WHEN the normalised comment message contains image references (file extensions `.svg`, `.png`, `.jpg`, `.jpeg`, `.gif`, `.webp`, `.bmp`, `.ico`, `.avif`, `.tiff`; Markdown image syntax `![...](...)`; BBCode `[img]...[/img]`; or CSS `url()`), THE SafeCommentText Rule SHALL reject the submission.
5. WHEN the normalised comment message contains fullwidth Unicode equivalents of `<` (`\x{FF1C}`) or `>` (`\x{FF1E}`), THE SafeCommentText Rule SHALL reject the submission.
6. WHEN the normalised comment message contains zero-width characters (`\x{200B}`-`\x{200D}`, `\x{FEFF}`), THE Normalizer SHALL strip those characters before any content check runs.
7. WHEN the Normalizer has stripped zero-width characters, THE SafeCommentText Rule SHALL evaluate the stripped form of the input against all content checks in criteria 2-5.
8. WHEN a comment message passes all content checks, THE SafeCommentText Rule SHALL accept it as valid plain text.
9. WHEN the `name` field is submitted and fails any content check applied by the SafeCommentText Rule (HTML, URLs, image references, or other blocked patterns), THE entire submission SHALL be rejected with a validation error on the `name` field.

---

### Requirement 3: Length and Word Limits

**User Story:** As a site owner, I want comments capped at 500 characters and 50 words, so that submissions remain concise and database storage is bounded.

#### Acceptance Criteria

1. WHEN a comment message exceeds 500 characters as measured by `mb_strlen`, THE StoreCommentRequest SHALL reject it with a validation error on the `message` field stating the character limit was exceeded.
2. WHEN a comment message exceeds 50 words (where words are counted by splitting on one or more whitespace characters), THE StoreCommentRequest SHALL reject it with a validation error on the `message` field stating the word limit was exceeded.
3. WHEN a comment message exceeds both 500 characters and 50 words simultaneously, THE StoreCommentRequest SHALL return validation errors for both limits.
4. THE `blog_comments` table `message` column SHALL be altered to `string` (VARCHAR 500) via a new migration.
5. WHEN the migration in Requirement 3.4 runs on a database containing comments whose `message` exceeds 500 characters, THE migration SHALL roll back without modifying the schema, and SHALL output an explicit error listing the affected row count and IDs rather than silently truncating data.

---

### Requirement 4: Field Validation

**User Story:** As a site owner, I want the name and email fields validated server-side with strict rules, so that only legitimate contact details are accepted.

#### Acceptance Criteria

1. WHEN a `name` field value is absent or empty, THE StoreCommentRequest SHALL reject the request with a validation error on the `name` field.
2. IF a `name` field value exceeds 50 characters, THEN THE StoreCommentRequest SHALL reject the request with a validation error indicating the `name` field failed validation.
3. IF a `name` field value contains content rejected by SafeCommentText (HTML, URLs, image references), THEN THE StoreCommentRequest SHALL reject the request with a validation error indicating the `name` field failed validation.
4. IF an `email` field value fails RFC-compliant email format validation (`email:rfc,dns`), THEN THE StoreCommentRequest SHALL reject the request with a validation error indicating the `email` field failed validation.
5. THE Comment_Form SHALL NOT include a `website` field. IF a `website` value is present in the request payload, THE StoreCommentRequest SHALL silently discard it without returning a validation error, and the value SHALL NOT appear in `$request->validated()`.
6. WHEN all fields pass validation, THE BlogCommentController SHALL store only `$request->validated()` via `BlogComment::create()`, setting `approved` to `false`.

---

### Requirement 5: Spam and Flood Defences

**User Story:** As a site owner, I want rate limiting and a honeypot to block automated flooding, so that the database and server resources are protected.

#### Acceptance Criteria

1. THE `POST /blog/{blog}/comments` route SHALL enforce a rate limit of 5 requests per minute per IP address for all incoming submissions.
2. WHEN a sixth or subsequent comment is submitted from the same IP address within the same one-minute window, THE System SHALL return an HTTP 429 response with a `Retry-After` header specifying the number of seconds remaining in the current window.
3. THE Comment_Form SHALL include a hidden `<input>` field named exactly `website_url` (the Honeypot_Field) with no visible label and no visible rendering in the browser.
4. WHEN a submission arrives with the Honeypot_Field containing any non-empty value, THE System SHALL discard the submission without storing it and SHALL return the same HTTP status code and redirect destination as a successful submission.

---

### Requirement 6: Safe Output and Display

**User Story:** As a site owner, I want all comment data displayed safely escaped, so that stored content can never cause XSS when rendered in a browser.

#### Acceptance Criteria

1. THE `blog-details.blade.php` view SHALL render all comment fields (including `name` and `message`) using `{{ }}` Blade escaping, which escapes HTML entities before output.
2. WHEN a comment message is rendered and line break preservation is required, THE view SHALL escape the content with `e()` before passing it to `nl2br()`, and may output the result using `{!! !!}` only in that form.
3. THE System SHALL contain no instances of any comment field (including `name`, `message`, or any other field from the `BlogComment` model) output via `{!! !!}` without first being wrapped in `e()`.

---

### Requirement 7: Content-Security-Policy

**User Story:** As a site owner, I want a Content-Security-Policy header that allows the Turnstile widget while not breaking existing site resources, so that the browser enforces an additional layer of protection.

#### Acceptance Criteria

1. THE CSP_Middleware SHALL attach a `Content-Security-Policy` header to every HTTP response delivered through the `web` middleware group, including responses with 2xx, 3xx, 4xx, and 5xx status codes.
2. THE CSP_Middleware `script-src` directive SHALL include `'self'` and `https://challenges.cloudflare.com` as allowed script origins.
3. THE CSP_Middleware `frame-src` directive SHALL include `'self'` and `https://challenges.cloudflare.com` as allowed frame origins.
4. THE CSP_Middleware `connect-src` directive SHALL include `'self'` and `https://challenges.cloudflare.com` as allowed connection origins.
5. WHEN the application loads external scripts, fonts, or styles from third-party origins (including but not limited to CDNs, Google Fonts at `https://fonts.googleapis.com` and `https://fonts.gstatic.com`, and Font Awesome), THE CSP_Middleware SHALL include each such origin in the corresponding directive (`script-src`, `style-src`, or `font-src`) so that no previously functioning resource is blocked.
6. IF enumerating all required external origins without causing resource-blocking violations is not feasible, THEN THE CSP_Middleware SHALL emit a `Content-Security-Policy-Report-Only` header instead of `Content-Security-Policy`, and the handover document SHALL record this decision and list the concrete steps required to transition to enforcing mode.
7. THE CSP_Middleware SHALL be registered in `bootstrap/app.php` and applied exclusively to the `web` middleware group, leaving the `api` middleware group unaffected.
8. WHEN the CSP_Middleware is active, THE CSP_Middleware SHALL NOT emit both a `Content-Security-Policy` header and a `Content-Security-Policy-Report-Only` header on the same response.

---

### Requirement 8: PHP intl Extension Check

**User Story:** As a developer, I want the application to detect whether the PHP intl extension is available at startup, so that normalisation is never silently skipped.

#### Acceptance Criteria

1. WHEN the application boots, THE System SHALL verify that `extension_loaded('intl')` returns `true`.
2. IF the `intl` extension is not loaded, THEN THE SafeCommentText Rule SHALL apply a fallback normalisation strategy that converts input to UTF-8 encoding and lowercases it, and SHALL apply a stricter rejection policy that rejects any comment containing characters outside the Unicode categories for letters, numbers, separators, and punctuation (`\p{L}`, `\p{N}`, `\p{Z}`, `\p{P}`).
3. IF the `intl` extension is not loaded, THEN THE System SHALL log a warning-level message indicating that the `intl` extension is unavailable and that SafeCommentText is operating in fallback mode; the application SHALL continue to function rather than halt.
4. IF the application environment is `production` and the `intl` extension is not loaded, THEN THE System SHALL additionally log a critical-level message indicating that full Unicode normalisation is unavailable in production.
5. IF the `intl` extension is loaded in the test environment, THEN THE automated test suite SHALL include a test that asserts `extension_loaded('intl')` returns `true`.

---

### Requirement 9: Configuration and Environment

**User Story:** As a developer, I want all sensitive keys managed exclusively through environment variables and config files, so that secrets are never hardcoded or leaked in source control.

#### Acceptance Criteria

1. THE System SHALL never call `env()` outside of a `config/` file.
2. THE `config/services.php` file SHALL be the single source of truth for `site_key`, `secret_key`, `expected_hostname`, and `skip_hostname_check` for Turnstile, where each value is read exclusively via `env()` from the corresponding `TURNSTILE_*` environment variable.
3. THE `.env.example` file SHALL contain `TURNSTILE_SITE_KEY=`, `TURNSTILE_SECRET_KEY=`, and `TURNSTILE_EXPECTED_HOSTNAME=` entries with empty values only, and SHALL NOT contain `TURNSTILE_SECRET_KEY` with any non-empty value.
4. IF the application environment is set to `production`, THEN THE System SHALL reject any request where `TURNSTILE_SITE_KEY` or `TURNSTILE_SECRET_KEY` matches the Cloudflare-documented dummy/test key values, and SHALL log an error indicating invalid production key configuration.
5. THE local development `.env` SHALL set `TURNSTILE_SITE_KEY` and `TURNSTILE_SECRET_KEY` to the Cloudflare-documented dummy/test key values, `TURNSTILE_EXPECTED_HOSTNAME=localhost`, and `TURNSTILE_SKIP_HOSTNAME_CHECK=true`.
6. IF `TURNSTILE_SKIP_HOSTNAME_CHECK` is absent or set to any value other than `true`, THEN THE System SHALL default `skip_hostname_check` to `false` when reading the value from `config/services.php`.

---

### Requirement 10: CSRF Protection

**User Story:** As a site owner, I want CSRF protection to remain active on the comment route, so that cross-site request forgery attacks are prevented.

#### Acceptance Criteria

1. THE Comment_Form SHALL include a `@csrf` directive as a direct child element of the form tag that submits to `POST /blog/{blog}/comments`.
2. WHEN a `POST` request is submitted to `/blog/{blog}/comments` without a valid CSRF token, THEN THE System SHALL reject the request with an HTTP 419 response and SHALL NOT store the comment.
3. THE `POST /blog/{blog}/comments` route SHALL NOT appear in any CSRF token verification exclusion list within the application.
4. THE BlogCommentController SHALL NOT apply any middleware exemption or `withoutMiddleware` call that removes CSRF token verification from the comment store action.

---

### Requirement 11: Automated Test Coverage

**User Story:** As a developer, I want comprehensive automated tests covering every security criterion, so that regressions are caught before deployment.

#### Acceptance Criteria

1. THE test suite SHALL use Pest with `Http::fake()` to simulate Cloudflare siteverify responses without making real network calls.
2. THE test suite SHALL include a reject test for each of the following inputs, asserting the HTTP response status code indicated: `<script>alert(1)</script>` (422), `<img src=x onerror=alert(1)>` (422), `https://site.com/a.svg` (422), `www.evil.com` (422), `evil.com/x.png` (422), `![x](a.png)` (422), `[img]a.jpg[/img]` (422), `javascript:alert(1)` (422), `&lt;script&gt;` (422), `%3Cscript%3E` (422), `&amp;lt;script&amp;gt;` (422), `data:image/svg+xml;base64,PHN2Zz4=` (422), fullwidth `<script>` (422), zero-width obfuscated `http://x.com` (422), 51 words (422), 501 characters (422), `'; DROP TABLE comments;--` (422), missing Turnstile token (422), failed Turnstile token (422), reused Turnstile token where the same token string has already been submitted and accepted in a prior request within the same test (422), Cloudflare timeout (422), wrong hostname in siteverify response (422), filled honeypot field (422), and a sixth request from the same IP within one minute (429).
3. THE test suite SHALL include an accept test for a normal 20-word plain-text comment with a simulated passing Turnstile response, asserting the HTTP response status is 200 or a redirect and the comment text is stored in the database.
4. THE test suite SHALL include a test asserting `extension_loaded('intl')` returns `true`.
5. THE test suite SHALL include a test that scans all `.blade.php` files under `resources/views/` and asserts that no file contains an unescaped output expression matching `{!! $comment->` without being wrapped in `e()`, asserting zero matches are found.

---

### Requirement 12: Handover Documentation

**User Story:** As a developer, I want a complete handover file listing every changed file and deployment step, so that changes can be applied to the server without missing anything.

#### Acceptance Criteria

1. THE System SHALL include a `TURNSTILE_CHANGES.md` file in the project root directory of the deployed codebase.
2. THE `TURNSTILE_CHANGES.md` SHALL list every new and modified file with a one-line reason for each change, where the file list covers all files changed as part of the Turnstile integration.
3. THE `TURNSTILE_CHANGES.md` SHALL document the production `.env` keys to set (`TURNSTILE_SITE_KEY`, `TURNSTILE_SECRET_KEY`, `TURNSTILE_EXPECTED_HOSTNAME=konichiwachemicals.com`) and SHALL include a warning that Dummy Keys must not be used in production.
4. THE `TURNSTILE_CHANGES.md` SHALL document deployment steps in the following order: (a) copy changed files to the server, (b) set the required `.env` keys, (c) run `php artisan migrate`, (d) run `php artisan config:clear`, (e) confirm the `intl` PHP extension is loaded, (f) confirm outbound HTTPS to `challenges.cloudflare.com` is not blocked.
5. WHEN the optional `comments:scan` artisan command has been executed prior to generating the handover documentation, THE `TURNSTILE_CHANGES.md` SHALL document the findings from that scan, including the total number of rows scanned, the distribution of comment character lengths, and a count of rows flagged for containing HTML tags, URLs, or image references.
6. THE `TURNSTILE_CHANGES.md` SHALL include a manual verification checklist containing: (a) steps to confirm the Turnstile widget loads and is visible under the active Content Security Policy, (b) steps to test locally using the Cloudflare-designated always-pass Dummy Key pair, and (c) steps to test locally using the Cloudflare-designated always-fail Dummy Key pair, where each checklist item specifies the observable pass or fail condition.
7. WHEN a developer runs the `comments:scan` artisan command, THE System SHALL scan all rows in the `blog_comments` table and output a report listing the total row count, the count of rows containing HTML tags, the count of rows containing URLs, and the count of rows containing image references, without modifying, deleting, or writing any data to the database.
8. IF the `comments:scan` artisan command encounters a database connection failure or is unable to read from the `blog_comments` table, THEN THE System SHALL output an error message indicating the failure and exit with a non-zero status code without modifying any data.
