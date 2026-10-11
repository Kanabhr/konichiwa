# Implementation Plan: Konichiwa Chemicals Public Site Redesign

## Overview

Nine sequential tasks (Task 0 through Task 8) deliver the redesign one scope unit at a time. Each task ends with a mandatory review gate — no subsequent task may begin until the user provides explicit written approval. Task 0 and Task 8 are read-only (analysis/audit only); no code changes occur in either. All backend files (controllers, routes, models, middleware) are frozen. The only permitted PHP edit is the single `View::composer` array addition in `AppServiceProvider`.

---

## Tasks

- [ ] 0. Audit and baseline capture (read-only — no code changes)
  - [x] 0.1 Run tasteskill v2 Section 11.B audit on `DESIGN-CURRENT.md`
    - Cover exactly six domains: typography, color, spacing, motion, components, layout
    - Each domain must have at least one "preserve" entry and one "retire" entry, each sourced from observable evidence in `DESIGN-CURRENT.md`
    - Include a "Locked tokens" section declaring `--color-primary: #DF0A0A`, `font-primary: DM Sans`, `font-secondary: Red Hat Display` as locked
    - Mode declaration: Redesign — Preserve brand
    - _Requirements: 1.1, 1.2_
  - [x] 0.2 Propose dial values with B2B justifications
    - Propose integer values: `DESIGN_VARIANCE` (1–5), `MOTION_INTENSITY` (1–4), `VISUAL_DENSITY`
    - Each value accompanied by a one-line justification referencing at least one observable characteristic from `DESIGN-CURRENT.md`
    - Confirm `DESIGN_VARIANCE ≤ 5` and `MOTION_INTENSITY ≤ 4`; note explicitly if tasteskill defaults would exceed these caps
    - _Requirements: 1.3, 1.4_
  - [x] 0.3 Capture route baseline
    - Run `php artisan route:list --columns=method,uri,name` from project root
    - Record full output verbatim in `.kiro/specs/public-site-redesign/baseline.md` under a "Routes" section
    - Any value that requires server execution and cannot be read from static source: mark `[requires artisan — run manually]`
    - _Requirements: 1.6, 1.7_
  - [x] 0.4 Capture nav label baseline
    - Read `resources/views/layouts/header.blade.php`
    - Record all five nav link labels and their `route()` or `href` values in `baseline.md` under "Nav labels" section as `[Label] → [route/href]`
    - _Requirements: 1.6, 1.7_
  - [x] 0.5 Capture anchor and form field baseline for all six public pages
    - Read each of: `index.blade.php`, `about-us.blade.php`, `contactus.blade.php`, `blog-list.blade.php`, `blog-details.blade.php`, `product-catalog.blade.php`
    - For each page: record a table of every visible `<a>` element's link text and `href` attribute under "Anchor inventory" section
    - For each page: record a table of every `<input>`, `<select>`, `<textarea>` element's `name` attribute and the form's `action` attribute under "Form field inventory" section
    - All values taken verbatim from source — no inference or fabrication
    - _Requirements: 1.6, 1.7_
  - [x] 0.6 Evaluate tasteskill Pre-Flight Check
    - Produce a table: Item | Result (PASS / FAIL / N/A) | Reason
    - Mark as N/A: Next.js, Tailwind, Motion, GSAP, next/font, dark mode items
    - All other items: PASS or FAIL with one-line reason
    - _Requirements: 1.9_
  - [ ] 0.7 Stop for review — present baseline.md and dial values, await approval before Task 1
    - Output "STOP — awaiting approval" block as final element
    - No file under `public/assets/redesign/`, `resources/views/layouts/`, or `resources/views/partials/` may be created or modified until explicit written approval is received
    - _Requirements: 1.5, 15.1, 15.2_

- [ ] 1. Foundation files (layout, CSS, fonts, header, footer, breadcrumb)
  - [ ] 1.1 Download and place font files
    - Download DM Sans (weights 400, 500, 700) woff2 files from Google Fonts `Download family` option (Latin subset)
    - Download Red Hat Display (weights 300, 400, 500, 600, 700, 800, 900) woff2 files from Google Fonts
    - Place all files in `public/assets/redesign/fonts/`
    - File names: `DMSans-Regular.woff2`, `DMSans-Medium.woff2`, `DMSans-Bold.woff2`, `RedHatDisplay-Light.woff2`, `RedHatDisplay-Regular.woff2`, `RedHatDisplay-Medium.woff2`, `RedHatDisplay-SemiBold.woff2`, `RedHatDisplay-Bold.woff2`, `RedHatDisplay-ExtraBold.woff2`, `RedHatDisplay-Black.woff2`
    - _Requirements: 2.3_
  - [ ] 1.2 Create `public/assets/redesign/css/redesign.css`
    - **Section A**: `@font-face` declarations for all DM Sans and Red Hat Display weights — `font-display: swap` on every rule, no Google Fonts `@import` URL
    - **Section B**: `:root` token block — at minimum: `--color-primary: #DF0A0A`, `--color-tint: #FFECEC`, `--color-heading: #1C2539`, `--color-body: #5D666F`, `--color-footer-bg: #181C25`, `--font-body`, `--font-heading`, `--transition`, `--section-gap`; add comment noting `#5D666F on #FFFFFF = 4.65:1 WCAG AA PASS`
    - **Section C**: WOW.js @keyframes + `.animated` class — `fadeIn`, `fadeInUp`, `fadeInDown`, `fadeInLeft`, `fadeInRight` matching original Animate.css 3.7.x transform/opacity values; `@media (prefers-reduced-motion: reduce)` block; comment explaining animate.min.css is banned and this section replaces it
    - **Section D**: base resets and typography — `body`, `h1`–`h6` with desktop type scale (h1:60px, h2:48px, h3:36px, h4:30px, h5:24px, h6:20px), `p`, `a`, `img`
    - **Section E**: utility classes — `.rts-section-gap`, `.rts-section-gapBottom`, `.rts-section-gapTop`, `.pre-title`
    - **Section F**: button system — `.rts-btn`, `.btn-primary`, `.btn-primary-alta`, `.btn-white`, `.rts-btn:focus-visible` (3px outline, WCAG 3:1), `.rts-read-more`
    - **Section G**: loader — `.loader-wrapper`, `.loader`, `.loader-section`, `@keyframes loaderPulse`
    - **Section H**: header CSS — `.redesign-header`, `.rh-top-bar`, `.rh-main-bar`, `.rh-logo`, `.rh-nav`, `.rh-actions`, `.search-input-area`, `.rh-sidebar`, `#anywhere-home`
    - **Section I**: footer CSS — `.redesign-footer`, `.rf-col-title`, `.rf-links`, `.rf-post-single`, `.rf-iso`, `.rf-copyright`, `.progress-wrap` (with `::after` using Font Awesome `\f062`, `svg.progress-circle path`)
    - **Section J**: breadcrumb CSS — `.rts-breadcrumb-area`, `::before` overlay, `.bread-tag`
    - **Section K**: contact form CSS — `.rts-contact-form-wrapper`, `.name-email` grid, `.field-error`
    - **Section L**: contact info card CSS — `.single-contact-one-inner`, `.content` tint background, `.info span` red uppercase label — navy text on tint (NOT red text on tint)
    - **Section M**: product card CSS — `.product-item-unique`, `.product-image-container` (height:280px, padding:20px, background:#f8fafc), `.product-image-container img` with `object-fit: contain` and comment "HARD CONSTRAINT: never change to cover"
    - **Section N**: blog card CSS — `.blog-single-post-listing` (.thumbnail height:350px), `.single-blog-one-wrapper`
    - **Section O**: sidebar widget CSS — `.rts-single-wized`, `.rts-search-wrapper`, `.single-categories`, `.recent-post-single`
    - **Section P**: pagination CSS — `.page-item-btn`, `.page-item-btn.active` (background: var(--color-primary)), `.page-item-btn.prev`, `.page-item-btn.next`
    - **Section Q**: responsive media queries — `@media (max-width: 991px)` tablet, `@media (max-width: 767px)` mobile (h1:34px, h2:28px … h6:18px; `.name-email` collapses to 1 col; `.rts-breadcrumb-area h1` 32px), `@media (max-width: 575px)` (`.rh-top-bar { display: none }`)
    - _Requirements: 2.1, 2.2, 2.3, 2.4, 2.7, 2.8, 9.1–9.11, 10.7, 10.8_
  - [ ] 1.3 Create `resources/views/layouts/redesign.blade.php`
    - `<html lang="en">` (Requirement 12.8)
    - CSS load order in `<head>`: `swiper.min.css` → `fontawesome-5.css` → `bootstrap.min.css` → `redesign.css` → `@stack('styles')` (Requirement 2.9)
    - `loader-wrapper` with logo `assets/images/logo/logo10.png` and `.loader-section.section-left` / `.loader-section.section-right` (Requirement 10.1)
    - `@include('partials.redesign-header')`
    - `<main id="main-content">@yield('content')</main>` (Requirement 12.10)
    - `@include('partials.redesign-footer')`
    - NO `<script>` tags in this file — all JS is owned by redesign-footer (Requirement 2.11)
    - `style.css`, `animate.min.css`, Bootstrap 3, Owl, Rev Slider, Ionicons must NOT appear (Requirement 2.7)
    - SEO meta tags: `description`, `keywords`, `author`, `robots`, favicon
    - _Requirements: 2.5, 2.6, 2.9, 2.11, 10.1, 11.5, 11.6, 12.8, 12.10_
  - [ ] 1.4 Create `resources/views/partials/redesign-header.blade.php`
    - Top bar (`.rh-top-bar`) with phone, email, address contact info
    - Main nav bar (`.rh-main-bar`) with logo `assets/images/logo/logo10.png` (max-height 60px desktop, 40px mobile — NOT 28px, use design.md value)
    - Five nav links with exact labels and routes verbatim: `Home` → `route('home')`, `Products` → `route('products.index')`, `About Us` → `route('about-us')`, `Blogs` → `route('blog-list')`, `Contact` → `route('contact')`
    - `request()->routeIs()` verbatim for active class detection (Requirement 11.7) — no other mechanism
    - Search overlay: `<form method="GET" action="{{ route('blog-list') }}">` with `<input name="search">`, toggle open/close (Requirement 11.8)
    - Hamburger button with `aria-expanded="false"` attribute (Requirement 12.6)
    - Mobile sidebar (`#side-bar` / `.rh-sidebar`): nav links, phone/email/address, "Get Quote" CTA to `route('contact')`
    - `#anywhere-home` backdrop div (for main.js sidebar close) (Requirement 10.3)
    - `<header>` semantic element wrapping the component (Requirement 12.10)
    - `<nav>` element wrapping the navigation links (Requirement 12.10)
    - _Requirements: 2.12, 2.13, 2.14, 2.15, 2.16, 10.2, 10.3, 11.7, 11.8, 12.6, 12.10_
  - [ ] 1.5 Create `resources/views/partials/redesign-footer.blade.php`
    - Three-column layout (Quick Links, Popular Updates, ISO Certificate) on `--color-footer-bg: #181C25`
    - `$footerPosts` consumed via `@foreach` — no raw DB query in template (Requirement 2.18)
    - Quick Links column with Font Awesome chevron icons
    - Popular Updates column: `@foreach($footerPosts as $post)` with thumbnail, date, title link
    - ISO column with certificate image
    - Copyright: `"KONICHIWA - Copyright {{ date('Y') }}. All rights reserved."` (verbatim per Requirement 2.19)
    - `.progress-wrap` SVG back-to-top circle element (Requirement 10.6)
    - Dark/light icon animation script block from original `footer.blade.php` preserved
    - All 10 scripts in exact order (Requirement 2.10): `jquery.min.js` → `jqueryui.js` → `waypoint.js` → `swiper.js` → `counterup.js` → `sal.min.js` → `bootstrap.min.js` → `waw.js` → `contact.form.js` → `main.js`
    - `@stack('scripts')` after the last `<script>` tag (Requirement 2.6, 11.6)
    - `<footer>` semantic element wrapping the component (Requirement 12.10)
    - _Requirements: 2.10, 2.11, 2.17, 2.18, 2.19, 2.20, 10.4, 10.5, 10.6, 11.6, 12.10_
  - [ ] 1.6 Create `resources/views/partials/redesign-breadcrumb.blade.php`
    - Accepts `$pageTitle` (string) and `$breadcrumbActive` (string) via `@include`
    - Background: `assets/images/breadcrumb/01.webp` (Requirement 2.22)
    - `::before` overlay at `rgba(28,37,57,0.65)`
    - `<h1 class="title">{{ $pageTitle }}</h1>` — white text
    - Separator `" / "` rendered in `var(--color-primary)` (red)
    - Active breadcrumb item text in `#1C2539` (navy), not red (per design.md Section J note)
    - _Requirements: 2.21, 2.22, 10.9_
  - [ ] 1.7 Update `app/Providers/AppServiceProvider.php`
    - Change `View::composer('layouts.footer', ...)` to `View::composer(['layouts.footer', 'partials.redesign-footer'], ...)`
    - This is the ONLY permitted PHP file change — no other PHP file may be touched
    - If `partials.redesign-footer` is already in the array, leave the file unchanged
    - _Requirements: 2.24, 2.25, 11.1_
  - [ ] 1.8 Cutover test: update `resources/views/about-us.blade.php`
    - Change `@extends('layouts.app')` to `@extends('layouts.redesign')` — the `@extends` line only
    - All existing content, sections, Blade directives, and variables remain completely unchanged
    - Verify page loads at `/about-us` without PHP errors
    - _Requirements: 2.23_
  - [ ] 1.9 Stop for review — present changed files list, await approval before Task 2
    - List all files created or modified: `public/assets/redesign/css/redesign.css`, `public/assets/redesign/fonts/` (10 files), `resources/views/layouts/redesign.blade.php`, `resources/views/partials/redesign-header.blade.php`, `resources/views/partials/redesign-footer.blade.php`, `resources/views/partials/redesign-breadcrumb.blade.php`, `app/Providers/AppServiceProvider.php`, `resources/views/about-us.blade.php`
    - _Requirements: 2.26, 15.1, 15.2_

- [ ] 2. Shared contact form partial
  - [ ] 2.1 Create `resources/views/partials/contact-form.blade.php` — shell and success alert
    - `@php $showPhone = $showPhone ?? false; $showSubject = $showSubject ?? false; @endphp`
    - `@if(session('success'))` Bootstrap `alert alert-success` block
    - `<form id="contact-form" action="{{ route('contact.store') }}" method="POST" novalidate>` with `@csrf`
    - _Requirements: 3.1, 3.2, 3.8_
  - [ ] 2.2 Add base fields to contact-form.blade.php
    - Name: `<label for="cf-name">`, `<input type="text" id="cf-name" name="name" required aria-required="true">`, `value="{{ old('name') }}"`, `@error('name')` span with `role="alert"` and `class="field-error"`
    - Email: same pattern with `type="email"`, `id="cf-email"`, `name="email"`, `value="{{ old('email') }}"`
    - Message: `<textarea id="cf-message" name="message" required>{{ old('message') }}</textarea>`, `@error('message')`
    - Name and email wrapped in `.name-email` grid div
    - Submit: `<button type="submit" class="rts-btn btn-primary">Send Message</button>`
    - _Requirements: 3.3, 3.4, 3.9, 12.4, 12.5_
  - [ ] 2.3 Add conditional phone field
    - `@if($showPhone)` wraps: `<label for="cf-phone">`, `<input type="text" id="cf-phone" name="phone" required aria-required="true">`, `<span id="phone-error" class="field-error" role="alert" style="display:none;">&#9679; This field is required</span>`, `@error('phone')` span
    - _Requirements: 3.5, 3.11_
  - [ ] 2.4 Add conditional subject field
    - `@if($showSubject)` wraps: `<label for="cf-subject">`, `<input type="text" id="cf-subject" name="subject" placeholder="Inquiry Type (e.g., Wholesale, Retail, Field Visit)" value="{{ old('subject') }}">`, `@error('subject')` span
    - No Turnstile widget. No honeypot field (`website_url`). ContactController does not validate them for this route.
    - _Requirements: 3.6, 3.7_
  - [ ] 2.5 Add phone validation script (conditional)
    - `@if($showPhone) @push('scripts')` wrapping a vanilla JS IIFE (no jQuery dependency)
    - IIFE implements: `blur` on `#cf-phone` → `validate()` (show `#phone-error`, add `.is-error` if empty; clear if not empty) (Requirement 3.11)
    - `input` on `#cf-phone` → clear error and `.is-error` when value is non-empty (Requirement 3.12)
    - `submit` on `#contact-form` → call `validate()`, `e.preventDefault()` and `phone.focus()` if invalid
    - `@endpush @endif`
    - _Requirements: 3.10, 3.11, 3.12_
  - [ ] 2.6 Stop for review — present changed files list, await approval before Task 3
    - List changed files: `resources/views/partials/contact-form.blade.php`
    - _Requirements: 3.13, 15.1, 15.2_

- [ ] 3. Contact page redesign
  - [ ] 3.1 Switch `resources/views/contactus.blade.php` to New_Layout
    - Change `@extends('layouts.app')` to `@extends('layouts.redesign')`
    - _Requirements: 4.1_
  - [ ] 3.2 Replace breadcrumb markup in contactus.blade.php
    - Replace existing breadcrumb HTML with `@include('partials.redesign-breadcrumb', ['pageTitle' => 'Contact Us', 'breadcrumbActive' => 'Contact Us'])`
    - _Requirements: 4.4_
  - [ ] 3.3 Preserve all three contact info cards verbatim
    - Verify images: `contact/22.webp`, `contact/23.webp`, `contact/03.png` — same `src` paths
    - Verify icon SVGs, label text ("Call Us 24/7", "Make A Quote", "Registered Office"), phone number, email address, office address — all text content verbatim
    - _Requirements: 4.1_
  - [ ] 3.4 Replace standalone form with contact-form partial
    - Replace the standalone `<form>` with `@include('partials.contact-form', ['showPhone' => true, 'showSubject' => false])`
    - Preserve surrounding section heading ("Get In Touch" / "Needs Help? Let's Get in Touch") and wrapper divs exactly
    - Remove the `@push('scripts')` phone validation block from `contactus.blade.php` — this logic is now owned by the partial when `showPhone` is true
    - _Requirements: 4.2_
  - [ ] 3.5 Preserve Google Maps iframe
    - Verify the Google Maps `<iframe>` `src` URL is completely unchanged
    - _Requirements: 4.3_
  - [ ] 3.6 Stop for review — present changed files list, await approval before Task 4
    - List changed files: `resources/views/contactus.blade.php`
    - _Requirements: 4.5, 15.1, 15.2_

- [ ] 4. Homepage redesign
  - [ ] 4.1 Switch `resources/views/index.blade.php` to New_Layout
    - Change `@extends('layouts.app')` to `@extends('layouts.redesign')`
    - _Requirements: 5.1_
  - [ ] 4.2 Verify all eight sections are preserved in order
    - Confirm document order: `.rts-banner-area`, `.rts-about-area`, `.rts-business-goal`, `.rts-gallery-area`, `.rts-feature-area`, `@include('farmer-stories.section')`, `.rts-blog-area`, `.rts-map-area`
    - No section added between, removed from, or reordered within this sequence
    - _Requirements: 5.2_
  - [ ] 4.3 Verify all Swiper container classes are preserved verbatim
    - Confirm: `mySwiper banner-one`, `mySwipers`, `mySwiperh1_blog` class names unchanged
    - Verify pagination, navigation, and wrapper elements for each Swiper are unchanged
    - _Requirements: 5.3_
  - [ ] 4.4 Verify farmer stories empty-state logic
    - Confirm `@if($farmerStories->isNotEmpty())` wrapping the farmer stories section — no fallback content or placeholder substituted
    - _Requirements: 5.4_
  - [ ] 4.5 Replace mailer.php form with contact-form partial (BLOCKED: do not start until the user confirms the form action in writing; see baseline.md Section 5)
    - Replace `<form id="contact-form" action="mailer.php" ...>` section with `@include('partials.contact-form', ['showPhone' => false, 'showSubject' => true])`
    - This renders: `name`, `email`, `subject`, `message` — matching homepage's current field set; omits `phone`
    - _Requirements: 5.5_
  - [ ] 4.6 Verify commented-out blocks remain untouched
    - Confirm Trusted Clients, Team, Counter-up, Testimonials blocks remain inside `{{-- --}}` Blade comment delimiters
    - No block uncommented, removed, or restructured
    - _Requirements: 5.6_
  - [ ] 4.7 Verify hero banner image paths
    - Confirm all slide background image paths and shape image paths (`banner/shape/01.png`, `banner/shape/02.png`, `banner/shape/03.png`) are unchanged
    - If any referenced image does not exist at its stated path, insert `{{-- TODO: missing image: [path] --}}` comment — no Picsum or external URL
    - _Requirements: 5.7, 11.11, 11.12_
  - [ ] 4.8 Stop for review — present changed files list, await explicit approval before Task 5
    - List changed files: `resources/views/index.blade.php`
    - _Requirements: 5.8, 15.1, 15.2_

- [ ] 5. About page full redesign (building on Task 1 cutover)
  - [ ] 5.1 Replace breadcrumb markup in about-us.blade.php
    - Replace existing breadcrumb HTML with `@include('partials.redesign-breadcrumb', ['pageTitle' => 'About Us', 'breadcrumbActive' => 'About Us'])`
    - _Requirements: 6.7_
  - [ ] 5.2 Verify all text content is verbatim
    - Confirm all headings, body paragraphs, accordion question text, and accordion answer text are unchanged
    - No copy rewritten or paraphrased
    - _Requirements: 6.1_
  - [ ] 5.3 Verify progress bars are preserved
    - Confirm `data-progress="70"` with labels "Crop Protection Accuracy" / "95%"
    - Confirm `data-progress="93"` with labels "Company Strength" / "93%"
    - Verify the `.meter > span` CSS class selector and `data-progress` attribute are present so `main.js` continues to animate them without modification (Requirement 11.9)
    - _Requirements: 6.2, 6.3, 11.9_
  - [ ] 5.4 Verify Bootstrap 5 accordion is preserved
    - Confirm exactly 3 accordion items
    - Confirm `data-bs-toggle="collapse"` and `data-bs-parent="#accordionExample"` on each toggle control
    - Confirm `aria-expanded`, `aria-controls`, `aria-labelledby` attributes are untouched (Requirement 12.7)
    - _Requirements: 6.4, 12.7_
  - [ ] 5.5 Verify images are preserved
    - Confirm `about/main/88.png`, `about/main/about4.png`, `faq/03.webp` — same `src` and `alt` attributes
    - _Requirements: 6.5_
  - [ ] 5.6 Verify commented-out sections remain as comments
    - Confirm Services cards and Customer feedback sections remain as `{{-- --}}` Blade comment delimiters
    - No structural changes to or removal of the commented-out markup
    - _Requirements: 6.6_
  - [ ] 5.7 Stop for review — present changed files list, await approval before Task 6
    - List changed files: `resources/views/about-us.blade.php`
    - _Requirements: 6.8, 15.1, 15.2_

- [ ] 6. Blog pages redesign (blog-list and blog-details)
  - [ ] 6.1 Switch blog-list.blade.php to New_Layout and replace breadcrumb
    - Change `@extends` to `@extends('layouts.redesign')`
    - Replace breadcrumb HTML with `@include('partials.redesign-breadcrumb', ...)`
    - Preserve `col-xl-8` main content / `col-xl-4` sidebar two-column layout at ≥ 1200px
    - _Requirements: 7.1_
  - [ ] 6.2 Preserve search form and pagination in blog-list
    - Verify GET search form: `method="GET"` `action="{{ route('blog-list') }}"` `<input name="search">` — unchanged
    - Verify search results banner shows "Showing results for [term]" and Clear anchor to `route('blog-list')` when `$search` is set
    - Verify `{{ $blogs->links() }}` Bootstrap_Pagination rendered without a custom wrapper component (Requirement 7.4)
    - _Requirements: 7.2, 7.3, 7.4_
  - [ ] 6.3 Preserve sidebar widgets in blog-list
    - Verify all four sidebar widgets in order: Search, Categories, Recent Posts, Contact CTA
    - Verify active category highlight on link matching `request('category')`
    - Verify "Clear filter" anchor to `route('blog-list')` when category filter is active
    - _Requirements: 7.7, 7.8_
  - [ ] 6.4 Preserve blog-list empty states
    - Verify: empty + no search → `fa-folder-open` icon + "No blog posts found."
    - Verify: empty + search set → "No results found for [term]" + "Clear Search" anchor to `route('blog-list')`
    - _Requirements: 7.5, 7.6_
  - [ ] 6.5 Switch blog-details.blade.php to New_Layout and replace breadcrumb
    - Change `@extends` to `@extends('layouts.redesign')`
    - Replace breadcrumb HTML with `@include('partials.redesign-breadcrumb', ...)`
    - Preserve `col-xl-8` post content / `col-xl-4` sidebar two-column layout at ≥ 1200px
    - _Requirements: 7.9_
  - [ ] 6.6 Preserve share buttons in blog-details
    - Verify all four share buttons (Facebook, Twitter, Instagram clipboard copy, LinkedIn) with their exact `onclick` JavaScript handlers — no handler modified
    - _Requirements: 7.10_
  - [ ] 6.7 Verify comment display in blog-details
    - Confirm comment output uses `{{ }}` auto-escaped (never `{!! !!}`) for commenter name and message
    - Confirm name initial avatar circle is preserved
    - _Requirements: 7.11_
  - [ ] 6.8 Preserve blog-details comment form
    - Verify `@csrf` present
    - Verify Turnstile widget: `.cf-turnstile` div with `data-sitekey="{{ config(...) }}"` and Turnstile JS `<script>` tag
    - Verify Honeypot: `<input name="website_url" style="display:none;">` (or equivalent hidden style)
    - Verify `@error('cf-turnstile-response')` displayed adjacent to the Turnstile widget
    - Verify all other `$errors` bag messages displayed as immediately-following sibling of their associated field
    - _Requirements: 7.12_
  - [ ] 6.9 Preserve blog-details session success alert and sidebar
    - Verify `session('comment_success')` Bootstrap `alert alert-success` block
    - Verify sidebar: Categories widget and Related Posts widget present; Search widget stays in `{{-- --}}` comment
    - _Requirements: 7.13, 7.14_
  - [ ] 6.10 Stop for review — present changed files list, await approval before Task 7
    - List changed files: `resources/views/blog-list.blade.php`, `resources/views/blog-details.blade.php`
    - _Requirements: 7.15, 15.1, 15.2_

- [ ] 7. Products page redesign
  - [ ] 7.1 Switch product-catalog.blade.php to New_Layout and replace breadcrumb
    - Change `@extends` to `@extends('layouts.redesign')`
    - Replace breadcrumb markup with `@include('partials.redesign-breadcrumb', ['pageTitle' => 'Product Catalog', 'breadcrumbActive' => 'Products'])`
    - _Requirements: 8.1, 8.6_
  - [ ] 7.2 Verify product grid and image constraint
    - Confirm `$products` variable consumed unchanged — no controller modification (Requirement 8.2)
    - Confirm product loop uses `product['full_image_url']`, `product['product_name']`, `href="#"` verbatim
    - Verify `object-fit: contain` applied to product image — HARD CONSTRAINT, never `cover` (Requirement 8.3)
    - Verify `.product-image-container` height 280px, padding 20px, background `#f8fafc` (Requirement 10.7)
    - _Requirements: 8.2, 8.3, 10.7_
  - [ ] 7.3 Preserve custom pagination
    - Verify `.page-item-btn` pagination component with `$currentPage`, `$totalPages` variables consumed unchanged (Requirement 8.2)
    - Verify `str_pad` zero-padded page numbers
    - Verify `fal fa-angle-double-left` / `fal fa-angle-double-right` chevrons for previous/next
    - Verify active page: `background-color: var(--color-primary)` and white text (Requirement 8.7)
    - Confirm inline `<style>` block for `.page-item-btn` in the original view is removed (absorbed into redesign.css pagination section)
    - _Requirements: 8.4, 8.7_
  - [ ] 7.4 Preserve products empty state
    - Verify: basket icon (`fal fa-shopping-basket`), text "No products are currently available in the catalog.", link to `route('contact')` with text "Contact us for inquiries"
    - _Requirements: 8.5_
  - [ ] 7.5 Stop for review — present changed files list, await approval before Task 8
    - List changed files: `resources/views/product-catalog.blade.php`
    - _Requirements: 8.8, 15.1, 15.2_

- [ ] 8. Final audit (read-only — no code changes)
  - [ ] 8.1 Responsiveness check
    - For each redesigned page (`/`, `/about-us`, `/contact`, `/blogs`, `/blogs/[slug]`, `/products`) at 375px, 768px, 1280px:
      - Check horizontal overflow (`scrollWidth > clientWidth`)
      - Check collapsed layouts (two-column → single-column at breakpoints)
      - Check clipped elements (sidebar, carousel overflow)
    - Label each check: `browser-devtools`, `static-analysis`, or `needs-manual-check`
    - _Requirements: 14.1, 13.1_
  - [ ] 8.2 WCAG contrast audit
    - For each foreground/background pair in `redesign.css`:
      - Record hex values, compute ratio using WCAG relative luminance formula
      - PASS/FAIL: 4.5:1 for normal text, 3:1 for large text (≥ 18pt or ≥ 14pt bold) and UI components
      - Label: `computed` or `needs-manual-check` (gradient/image/dynamic backgrounds)
    - Confirm `#DF0A0A` on `#FFFFFF` is restricted to large/bold text contexts only (buttons at 15px/600 weight, heading decorators, icon accents)
    - Include the note required by Requirement 12.9 about manual testing
    - _Requirements: 14.2, 12.1, 12.2_
  - [ ] 8.3 Asset path audit
    - Grep all `asset()` calls in all redesigned Blade files
    - Confirm each path resolves to a file existing under `public/`
    - Report any `<!-- TODO: image not found -->` comments and the paths they replace
    - _Requirements: 14.3, 11.11_
  - [ ] 8.4 Placeholder text audit
    - Confirm no Lorem Ipsum, "dummy", "placeholder", or "TODO text" appears in rendered HTML of any redesigned page
    - Excludes labeled TODO_Slot HTML comments
    - _Requirements: 14.4_
  - [ ] 8.5 Banned asset audit
    - Confirm these are NOT loaded on any redesigned page: `style.css`, `animate.min.css` (from `assets/css/plugins/`), Bootstrap 3 CSS, Owl Carousel CSS, Rev Slider CSS, Ionicons
    - Confirm `waw.js` IS present in footer script order — this is expected and NOT a finding
    - _Requirements: 14.5, 2.7_
  - [ ] 8.6 Brand Fidelity audit
    - Per redesigned page: report measured `DESIGN_VARIANCE` and `MOTION_INTENSITY` scores
    - PASS if `DESIGN_VARIANCE ≤ 5`; PASS if `MOTION_INTENSITY ≤ 4`; FAIL otherwise
    - _Requirements: 14.6_
  - [ ] 8.7 Baseline diff
    - Compare every URL path, nav label, form field `name` attribute, and visible anchor text in redesigned pages against `baseline.md` values recorded in Task 0
    - Produce a change list — must be empty unless the changed item appears in an explicitly approved-change record
    - If `baseline.md` does not exist: halt and report the missing baseline (Requirement 14.10)
    - _Requirements: 14.7, 14.10_
  - [ ] 8.8 Dead file list
    - List any files under `public/assets/redesign/` whose path does not appear in any `href`, `src`, `action`, `url()`, or `asset()` call across all redesigned Blade files and `redesign.css`
    - Do NOT delete any listed file
    - _Requirements: 14.8_
  - [ ] 8.9 Present consolidated audit report and await sign-off
    - Present a single consolidated report containing: responsive overflow results (8.1), WCAG contrast table (8.2), asset-path verification (8.3), placeholder-text result (8.4), banned-asset result (8.5), Brand Fidelity scores (8.6), URL/label change list (8.7), dead-file list (8.8)
    - Await explicit sign-off before any further changes are made
    - _Requirements: 14.9, 15.1, 15.2_

---

## Notes

- Tasks are strictly sequential. No task begins until the preceding review gate is explicitly approved in writing.
- Tasks 0 and 8 are read-only — no file under `public/assets/`, `resources/views/`, or `app/` may be created or modified.
- The only permitted PHP edit across the entire project is the `View::composer` array addition in `AppServiceProvider` (Task 1.7).
- `object-fit: contain` on product images is a hard constraint — it must never become `cover`.
- `request()->routeIs()` in Redesign_Header must remain verbatim — no substitute mechanism is acceptable.
- Nav labels (Home, Products, About Us, Blogs, Contact), form field `name` attributes, URLs, and legal copy are frozen.
- `waw.js` (WOW.js) stays loaded and its presence in the footer script order is expected — its `@keyframes` are provided by `redesign.css` in place of the banned `animate.min.css`.
- No Tailwind, GSAP, Motion, Phosphor, Ionicons, Bootstrap 3, Owl, Rev Slider, or Animate.css may be loaded on any redesigned page.
- All images must reference confirmed `public/assets/` paths; use `<!-- TODO: image not found: [path] -->` for any missing file — never Picsum or external URLs.

## Task Dependency Graph

```json
{
  "waves": [
    { "id": 0, "tasks": ["0.1", "0.2"] },
    { "id": 1, "tasks": ["0.3", "0.4", "0.5"] },
    { "id": 2, "tasks": ["0.6"] },
    { "id": 3, "tasks": ["0.7"] },
    { "id": 4, "tasks": ["1.1"] },
    { "id": 5, "tasks": ["1.2", "1.3"] },
    { "id": 6, "tasks": ["1.4", "1.5", "1.6"] },
    { "id": 7, "tasks": ["1.7", "1.8"] },
    { "id": 8, "tasks": ["1.9"] },
    { "id": 9, "tasks": ["2.1"] },
    { "id": 10, "tasks": ["2.2"] },
    { "id": 11, "tasks": ["2.3", "2.4"] },
    { "id": 12, "tasks": ["2.5"] },
    { "id": 13, "tasks": ["2.6"] },
    { "id": 14, "tasks": ["3.1", "3.2"] },
    { "id": 15, "tasks": ["3.3", "3.4", "3.5"] },
    { "id": 16, "tasks": ["3.6"] },
    { "id": 17, "tasks": ["4.1"] },
    { "id": 18, "tasks": ["4.2", "4.3", "4.4", "4.6", "4.7"] },
    { "id": 19, "tasks": ["4.5"] },
    { "id": 20, "tasks": ["4.8"] },
    { "id": 21, "tasks": ["5.1"] },
    { "id": 22, "tasks": ["5.2", "5.3", "5.4", "5.5", "5.6"] },
    { "id": 23, "tasks": ["5.7"] },
    { "id": 24, "tasks": ["6.1"] },
    { "id": 25, "tasks": ["6.2", "6.3", "6.4"] },
    { "id": 26, "tasks": ["6.5"] },
    { "id": 27, "tasks": ["6.6", "6.7", "6.8", "6.9"] },
    { "id": 28, "tasks": ["6.10"] },
    { "id": 29, "tasks": ["7.1"] },
    { "id": 30, "tasks": ["7.2", "7.3", "7.4"] },
    { "id": 31, "tasks": ["7.5"] },
    { "id": 32, "tasks": ["8.1", "8.2", "8.3", "8.4", "8.5", "8.6"] },
    { "id": 33, "tasks": ["8.7", "8.8"] },
    { "id": 34, "tasks": ["8.9"] }
  ]
}
```