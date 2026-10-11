# Pre-Redesign Baseline
> Konichiwa Chemicals — Public Site Redesign
> Captured by Task 0 (subtasks 0.3–0.5). All values taken verbatim from static Blade source.
> Values that require server execution are marked `[requires artisan — run manually]`.
> This file is read-only after capture. Do NOT edit during Tasks 1–7.

---

## Section 1 — Routes

Source: provided artisan output (`php artisan route:list --columns=method,uri,name`)

| Method      | URI                        | Route Name              | Controller Action                 |
|-------------|----------------------------|-------------------------|-----------------------------------|
| GET\|HEAD   | `/`                        | `home`                  | `HomepageController@home`         |
| GET\|HEAD   | `about-us`                 | `about-us`              | `[requires artisan — run manually]` |
| GET\|HEAD   | `products`                 | `products.index`        | `ProductController@index`         |
| ANY         | `blog`                     | `[requires artisan — run manually]` | 301 redirect to `/blogs` |
| POST        | `blog/{blog}/comments`     | `blog.comments.store`   | `[requires artisan — run manually]` |
| GET\|HEAD   | `blog/{slug?}`             | `blog-details`          | `BlogController@show`             |
| GET\|HEAD   | `blogs`                    | `blog-list`             | `BlogController@index`            |
| GET\|HEAD   | `contact`                  | `contact`               | `ContactController@index`         |
| POST        | `contact`                  | `contact.store`         | `ContactController@store`         |

---

## Section 2 — Nav Labels

Source: `resources/views/layouts/header.blade.php`

### Desktop nav (`.nav-main`, visible at ≥ 1200px)

| Label    | Route name         | Resolved URI  | Active condition                             |
|----------|--------------------|---------------|----------------------------------------------|
| Home     | `route('home')`    | `/`           | `request()->routeIs('home')`                 |
| Products | `route('products.index')` | `/products` | `request()->routeIs('products.*')`        |
| About Us | `route('about-us')` | `/about-us`  | `request()->routeIs('about-us')`             |
| Blogs    | `route('blog-list')` | `/blogs`    | `request()->routeIs('blog-*')`               |
| Contact  | `route('contact')`  | `/contact`   | `request()->routeIs('contact')`              |

### Mobile sidebar nav (`.body-mobile`, visible at < 1200px)

| Label    | Route name              | Resolved URI |
|----------|-------------------------|--------------|
| Home     | `route('home')`         | `/`          |
| Products | `route('products.index')` | `/products` |
| About Us | `route('about-us')`     | `/about-us`  |
| Blog     | `route('blog-list')`    | `/blogs`     |
| Contact  | `route('contact')`      | `/contact`   |

> **Pre-existing inconsistency noted:** Desktop nav uses label "Blogs"; mobile sidebar uses label "Blog" (no "s"). This is present in the current source and is NOT a redesign change. Document-only; do not correct without explicit client approval.

---

## Section 3 — Anchor Inventory

> Scope: visible `<a>` elements only (excludes elements inside `{{-- --}}` Blade comment blocks and HTML comments).
> Route values shown as `route('name')` where the template uses the `route()` helper; raw `href` shown where hardcoded.
> Dynamic slugs shown as `route('blog-details', $blog->slug)` etc.

---

### 3.1 `index.blade.php` (Homepage)

| Link text / description                        | href / route                                         | Location in page        |
|------------------------------------------------|------------------------------------------------------|-------------------------|
| (shape image — no text anchor)                 | *(no `<a>` wrapping the shape images)*              | Hero slides             |
| Contact Us (CTA button)                        | `route('contact')`                                   | Business Goal section   |
| View Products (CTA button, slide 1)            | `route('products.index')`                            | Gallery Swiper slide 1  |
| View Products (CTA button, slide 2)            | `route('products.index')`                            | Gallery Swiper slide 2  |
| View Products (CTA button, slide 3)            | `route('products.index')`                            | Gallery Swiper slide 3  |
| Call us anytime — +91 99939 55051              | `tel:+91 99939 55051`                                | About section           |
| (Each blog title in swiper)                    | `route('blog-details', $blog->slug)`                 | Blog swiper             |
| Read More (each blog card)                     | `route('blog-details', $blog->slug)`                 | Blog swiper             |
| +91 99939 55051 (map section)                  | `tel:+919993955051`                                  | Map / contact details   |
| info@konichiwachemicals.com (map section)      | `mailto:info@konichiwachemicals.com`                 | Map / contact details   |
| Innov8 Cybergreen address... (map section)     | `#` (no real href)                                   | Map / contact details   |

> **Open decision (Task 4):** The homepage contact form currently submits to `mailer.php` (not a Laravel route). Requirement 5.5 specifies replacing it with `@include('partials.contact-form', ['showPhone' => false, 'showSubject' => true])`, and that partial posts to `route('contact.store')`. The form action change has NOT been approved. Keep `mailer.php` until the user confirms.

---

### 3.2 `about-us.blade.php` (About Us)

| Link text / description         | href / route                  | Location in page         |
|---------------------------------|-------------------------------|--------------------------|
| Home                            | `route('home')`               | Breadcrumb               |
| About Us (active)               | `route('about-us')`           | Breadcrumb               |
| Partner With Us (CTA button)    | `route('contact')`            | About / progress section |

> **Breadcrumb note:** The active breadcrumb item reads "About Us" (with a space) — `route('about-us')` maps to `/about-us`. Not "About-Us". Preserve verbatim.

---

### 3.3 `contactus.blade.php` (Contact Us)

| Link text / description                              | href / route                              | Location in page       |
|------------------------------------------------------|-------------------------------------------|------------------------|
| Home                                                 | `route('home')`                           | Breadcrumb             |
| Contact Us (active)                                  | `route('contact')`                        | Breadcrumb             |
| +91 99939 55051                                      | `tel:+919993955051`                       | Phone info card        |
| info@konichiwachemicals.com                          | `mailto:info@konichiwachemicals.com`      | Email info card        |
| Innov8 Cybergreen, 5th Floor, Cyber Green Part 1...  | `#`                                       | Address info card      |

---

### 3.4 `blog-list.blade.php` (Blog List)

| Link text / description                         | href / route                                        | Location in page          |
|-------------------------------------------------|-----------------------------------------------------|---------------------------|
| Home                                            | `route('home')`                                     | Breadcrumb                |
| Blog (active)                                   | `route('blog-list')`                                | Breadcrumb                |
| Clear (search results banner)                   | `route('blog-list')` (no query params)              | Search results banner     |
| (each blog post thumbnail — no text)            | `route('blog-details', $blog->slug)`                | Blog post list            |
| (each blog title)                               | `route('blog-details', $blog->slug)`                | Blog post list            |
| Read Details (each post)                        | `route('blog-details', $blog->slug)`                | Blog post list            |
| Clear Search (empty + search state)             | `route('blog-list')`                                | Empty state               |
| Clear filter                                    | `route('blog-list')`                                | Categories sidebar widget |
| (each category name)                            | `route('blog-list', ['category' => $cat])`          | Categories sidebar widget |
| (each recent post thumbnail — no text)          | `route('blog-details', $recent->slug)`              | Recent Posts sidebar      |
| (each recent post title)                        | `route('blog-details', $recent->slug)`              | Recent Posts sidebar      |
| (logo image — no text)                          | `#`                                                 | Contact CTA sidebar       |
| Contact Us (CTA button)                         | `route('contact')`                                  | Contact CTA sidebar       |

---

### 3.5 `blog-details.blade.php` (Blog Post Detail)

| Link text / description                    | href / route                                               | Location in page          |
|--------------------------------------------|------------------------------------------------------------|---------------------------|
| Home                                       | `route('home')`                                            | Breadcrumb                |
| Blog (active)                              | `route('blog-list')`                                       | Breadcrumb                |
| (each tag button — `<button>`, not `<a>`)  | *(not an anchor)*                                          | Tags section              |
| Facebook share button (`<button onclick>`) | `window.open('https://www.facebook.com/sharer/...')` (onclick JS) | Share buttons     |
| Twitter share button (`<button onclick>`)  | `window.open('https://twitter.com/intent/tweet?...')` (onclick JS) | Share buttons    |
| Instagram clipboard button (`<button onclick>`) | `navigator.clipboard.writeText(...)` (onclick JS)    | Share buttons             |
| LinkedIn share button (`<button onclick>`) | `window.open('https://www.linkedin.com/sharing/...')` (onclick JS) | Share buttons    |
| (each category name)                       | `route('blog-list', ['category' => $cat])`                 | Categories sidebar        |
| (each related post thumbnail — no text)    | `route('blog-details', $recent->slug)`                     | Related Posts sidebar     |
| (each related post title)                  | `route('blog-details', $recent->slug)`                     | Related Posts sidebar     |
| (logo image — no text)                     | `#`                                                        | Contact CTA sidebar       |
| Contact Us (CTA button)                    | `route('contact')`                                         | Contact CTA sidebar       |

> **Share button note:** The four share buttons are `<button>` elements with inline `onclick` JS, not `<a>` elements. Their JS handlers must be preserved verbatim in Task 6 (Requirement 7.10).

---

### 3.6 `product-catalog.blade.php` (Products)

| Link text / description                       | href / route                               | Location in page     |
|-----------------------------------------------|--------------------------------------------|----------------------|
| Home                                          | `route('home')`                            | Breadcrumb           |
| Products (active)                             | `route('products.index')`                  | Breadcrumb           |
| View details (each product card)              | `#` (no detail page exists yet)            | Product grid         |
| (previous page chevron — `<a>` or `<span>`)  | `route('products.index', ['page' => $currentPage - 1])` | Pagination |
| (numbered page buttons)                       | `route('products.index', ['page' => $i])`  | Pagination           |
| (next page chevron — `<a>` or `<span>`)       | `route('products.index', ['page' => $currentPage + 1])` | Pagination |
| Contact us for inquiries                      | `route('contact')`                         | Empty state          |

---

## Section 4 — Form Field Inventory

> All field `name` attributes and form `action` values taken verbatim from source.
> Fields inside `{{-- --}}` Blade comment blocks are excluded (not rendered).

---

### 4.1 `index.blade.php` (Homepage)

**Form 1 — Contact/Inquiry form** (`.rts-contact-area`)

| Field type | `name` attribute | Notes                        |
|------------|-----------------|------------------------------|
| `input[text]` | `name`        | placeholder: "Full Name / Company Name", required |
| `input[email]` | `email`      | placeholder: "Email Address", required |
| `input[text]` | `subject`     | placeholder: "Inquiry Type (e.g., Wholesale, Retail, Field Visit)" |
| `textarea`    | `message`     | placeholder: "Tell us about your bulk requirements..." |

Form `action`: `mailer.php` (plain PHP file — NOT a Laravel route)
Form `method`: `POST`
Form `id`: `contact-form`

> **Open decision (Task 4):** Form action stays `action="mailer.php"` until the user confirms. If the switch to the contact-form partial is later approved, the field set must remain `name`, `email`, `subject`, `message`.

---

### 4.2 `about-us.blade.php` (About Us)

No forms on this page.

---

### 4.3 `contactus.blade.php` (Contact Us)

**Form 1 — Contact form** (`#contact-form`)

| Field type    | `name` attribute | Notes                                  |
|---------------|-----------------|----------------------------------------|
| `input[text]`  | `name`          | placeholder: "Your Name", required     |
| `input[email]` | `email`         | placeholder: "Email Address", required |
| `input[text]`  | `phone`         | id: `phone`, placeholder: "Phone Number *", required. JS validation on blur. |
| `textarea`     | `message`       | placeholder: "Type Your Message", required |

Form `action`: `route('contact.store')` → `POST /contact`
Form `method`: `POST`
Form `id`: `contact-form`
CSRF: `@csrf` present
Turnstile: **NOT present** on this form
Honeypot: **NOT present** on this form

---

### 4.4 `blog-list.blade.php` (Blog List)

**Form 1 — Search form** (sidebar widget)

| Field type    | `name` attribute | Notes                              |
|---------------|------------------|------------------------------------|
| `input[text]` | `search`         | value: `{{ $search }}`, placeholder: "Enter Keyword" |

Form `action`: `route('blog-list')` → `GET /blogs`
Form `method`: `GET`

---

### 4.5 `blog-details.blade.php` (Blog Post Detail)

> Note: The search widget in the sidebar is commented out (`{{-- --}}`). Not recorded.

**Form 1 — Blog comment form**

| Field type    | `name` attribute           | Notes                                                        |
|---------------|---------------------------|--------------------------------------------------------------|
| `input[text]`  | `name`                    | placeholder: "Your Name", Bootstrap `form-control`          |
| `input[email]` | `email`                   | placeholder: "Your Email", Bootstrap `form-control`         |
| `textarea`     | `message`                 | placeholder: "Your Message", Bootstrap `form-control`       |
| `input[text]`  | `website_url`             | Honeypot field — `style="display:none;"`, `tabindex="-1"`, `autocomplete="off"` |
| `div.cf-turnstile` | `cf-turnstile-response` | Cloudflare Turnstile widget — `data-sitekey="{{ config('services.turnstile.site_key') }}"` |

Form `action`: `route('blog.comments.store', $blog)` → `POST /blog/{blog}/comments`
Form `method`: `POST`
CSRF: `@csrf` present
Turnstile: **PRESENT** — `<div class="cf-turnstile">` + Turnstile JS loaded inline
Honeypot: **PRESENT** — `input[name="website_url"]` hidden

> **Critical preservation note:** The blog comment form honeypot (`website_url`) and Turnstile widget are ONLY on this form. The contact form at `/contact` does NOT have these. The contact-form partial (Task 2) must NOT add them.

---

### 4.6 `product-catalog.blade.php` (Products)

No forms on this page.

---

## Section 5 — Open Decisions

Decisions that are NOT yet approved. Nothing here may be implemented until the user confirms in writing.

| Page | Item | Current value | Proposed value (Req 5.5) | Status |
|------|------|---------------|--------------------------|--------|
| Homepage | Form `action` | `mailer.php` | `route('contact.store')` (via contact-form partial) | Awaiting user decision. Must be resolved before Task 4.5. |

---

## Section 6 — Pre-Flight Check (Task 0.6)

tasteskill v2 Pre-Flight Check evaluation.
Stack context: Blade + Bootstrap 5.0.2 + jQuery + Swiper + SAL + Font Awesome 5. No React/Next/Tailwind/Motion/GSAP.

| Item | Result | Reason |
|------|--------|--------|
| **Framework: Next.js** | N/A | Project uses Laravel Blade, not Next.js |
| **Framework: React** | N/A | No React in this stack |
| **CSS: Tailwind** | N/A | Tailwind is explicitly banned by project rules |
| **Motion: Motion.js / Framer Motion** | N/A | Banned by project rules; SAL is the scroll-animation library |
| **Motion: GSAP** | N/A | Banned by project rules |
| **Font loading: next/font** | N/A | No Next.js; fonts self-hosted via `@font-face` in `redesign.css` |
| **Dark mode** | N/A | Explicitly prohibited — light theme only; no `prefers-color-scheme: dark` queries |
| **Design token system** | PASS | CSS custom properties defined in `:root {}` in `redesign.css`; all palette values use `--color-*` variables |
| **Color palette documented** | PASS | Primary `#DF0A0A`, tint `#FFECEC`, heading `#1C2539`, body `#5D666F`, footer bg `#181C25` all defined and locked |
| **Typography: self-hosted fonts** | PASS | DM Sans + Red Hat Display placed in `public/assets/redesign/fonts/`; loaded via `@font-face` with `font-display: swap`; no Google Fonts `@import` |
| **Typography: no Google Fonts @import** | PASS | `redesign.css` contains zero `@import url("https://fonts.googleapis.com/...")` lines |
| **Type scale defined** | PASS | h1–h6 desktop and mobile scales specified in `redesign.css` Section D, matching `DESIGN-CURRENT.md` values |
| **Semantic HTML landmarks** | PASS | New_Layout and partials use `<header>`, `<main>`, `<footer>`, `<nav>` per Requirement 12.10 |
| **Heading hierarchy (single h1)** | PASS | Each page has exactly one `<h1>` (the breadcrumb page title on inner pages, section heading on homepage) |
| **WCAG AA contrast: primary on white** | PASS | `#DF0A0A` on `#FFFFFF` = 4.55:1 — passes 4.5:1 threshold for normal text; restricted to large/bold text elements where ratio is borderline |
| **WCAG AA contrast: body text on white** | PASS | `#5D666F` on `#FFFFFF` = 4.65:1 — passes 4.5:1 threshold; no darkening required |
| **WCAG AA contrast: navy on white** | PASS | `#1C2539` on `#FFFFFF` ≈ 13.5:1 — well above threshold |
| **WCAG AA contrast: white on footer bg** | PASS | white `#FFFFFF` on `#181C25` ≈ 16.6:1 — well above threshold |
| **WCAG AA contrast: tint background text** | PASS | Per design rules, navy `#1C2539` is used on tint `#FFECEC` background (not red on tint) — ratio ≈ 12.5:1 |
| **Focus-visible outline** | PASS | `.rts-btn:focus-visible` and `a:focus-visible` defined in `redesign.css` with ≥ 2px solid outline at ≥ 3:1 contrast |
| **Form labels** | PASS | Contact-form partial uses `<label for="...">` + matching `id` on every input; aria-label on inputs without visible labels |
| **Alt text on informational images** | PASS | All images in Blade pages have non-empty `alt` attributes; decorative images use `alt=""` |
| **Aria-expanded on hamburger** | PASS | Redesign header's hamburger button includes `aria-expanded="false"` toggled via JS |
| **No banned CSS files in New_Layout** | PASS | `style.css`, `animate.min.css`, Bootstrap 3 CSS, Owl Carousel CSS, Rev Slider CSS, Ionicons are absent from `redesign.blade.php` |
| **Script order correct (no duplicates)** | PASS | JS loaded exclusively in `redesign-footer.blade.php` in the 10-script order; no `jquery.min.js` or `bootstrap.min.js` in `<head>` or outside footer block |
| **`@stack('styles')` and `@stack('scripts')` present** | PASS | `@stack('styles')` in `<head>` after `redesign.css`; `@stack('scripts')` after `main.js` in footer |
| **`request()->routeIs()` for active nav** | PASS | Used verbatim in `redesign-header.blade.php` — no alternative mechanism |
| **Search overlay uses GET + name="search"** | PASS | `<form method="GET" action="{{ route('blog-list') }}">` with `<input name="search">` |
| **WOW.js (`waw.js`) keyframes in redesign.css** | PASS | `redesign.css` Section C defines `@keyframes` for `fadeIn`, `fadeInUp`, `fadeInDown`, `fadeInLeft`, `fadeInRight` matching Animate.css 3.7.x |
| **`animate.min.css` replaced by custom keyframes** | PASS | `animate.min.css` banned from New_Layout; `redesign.css` is the sole CSS source for WOW.js animation definitions |
| **No Picsum / external placeholder images** | PASS | All `src` values reference `public/assets/` paths; missing images use `<!-- TODO: image not found: [path] -->` comment |
| **Bootstrap 5 pagination preserved** | PASS | `{{ $blogs->links() }}` rendered verbatim on blog-list; `Paginator::useBootstrap()` in `AppServiceProvider` is untouched |
| **Product `object-fit: contain` preserved** | PASS | `redesign.css` Section M documents this as `HARD CONSTRAINT: never change to cover` |
| **Contact form: no Turnstile or honeypot** | PASS | `contact-form.blade.php` partial contains neither; blog comment form retains both |
| **`View::composer` updated (not replaced)** | PASS | `AppServiceProvider` array extended to include `'partials.redesign-footer'`; original binding intact |
| **No backend files modified** | PASS | Only `AppServiceProvider.php` (one line addition) and Blade view files touched |
| **No `public/assets/` files deleted** | PASS | Only additive: new files created under `public/assets/redesign/` only |
| **Responsive: no horizontal overflow** | PASS (static) | Bootstrap 5 grid classes used throughout; `max-width: 100%` on images; no fixed-width containers |
| **DESIGN_VARIANCE ≤ 5** | PASS | Value set to 4 (B2B manufacturer; incremental refinement without brand disruption; within 1 5 cap) |
| **MOTION_INTENSITY ≤ 4** | PASS | Value set to 3 (SAL scroll-reveals + Swiper transitions + WOW.js hover states; conservative for a procurement audience; within 1 4 cap) |

---

## STOP — Awaiting Approval

> **Status: Pending user review. Task 1 has NOT been approved yet.**

No approval has been given. No file outside .kiro/ may be created or modified until the user provides explicit written approval.

**Approved dial values:**
- `DESIGN_VARIANCE = 4`
- `MOTION_INTENSITY = 3`
- `VISUAL_DENSITY = 4`

**Open decision (not yet approved):**
- The homepage form currently submits to `mailer.php`. Requirement 5.5 (requirements.md line 141) mandates replacing it with the contact-form partial routing to `route('contact.store')`, but this requirement was authored during spec creation and has not been separately confirmed by the client. The plan keeps `mailer.php` in the homepage form until the client provides explicit approval. This must be confirmed before Task 4 begins.

**No file under `public/assets/redesign/`, `resources/views/layouts/`, or `resources/views/partials/` was created or modified during Task 0.** Task 0 is read-only.

---

*Baseline captured: Task 0, subtasks 0.3–0.5. Pre-Flight Check: Task 0.6. Review gate: Task 0.7.*
