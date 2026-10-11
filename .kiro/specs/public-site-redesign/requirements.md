# Requirements Document

## Introduction

Visual redesign of the public Blade pages of Konichiwa Chemicals' Laravel 12 site. The project upgrades the aesthetic quality to a cohesive, premium B2B look while preserving every piece of existing behavior exactly as-is. No new features, no new npm packages, and no changes to any backend code other than one allowed View Composer binding update. The redesign is delivered one page at a time with a mandatory stop-for-review after each task (Tasks 0 through 8).

---

## Glossary

- **Redesign_System**: The complete set of new front-end files introduced by this project: `redesign.css`, `redesign.blade.php`, partials under `resources/views/partials/`, and self-hosted fonts in `public/assets/redesign/`.
- **Old_Layout**: `resources/views/layouts/app.blade.php` and its included partials (`layouts.header`, `layouts.footer`). Must not be modified.
- **New_Layout**: `resources/views/layouts/redesign.blade.php`. The new Blade layout used by all redesigned pages.
- **Redesign_CSS**: `public/assets/redesign/css/redesign.css`. The single new stylesheet for redesigned pages.
- **Redesign_Header**: `resources/views/partials/redesign-header.blade.php`.
- **Redesign_Footer**: `resources/views/partials/redesign-footer.blade.php`.
- **Breadcrumb_Partial**: `resources/views/partials/redesign-breadcrumb.blade.php`.
- **Contact_Form_Partial**: `resources/views/partials/contact-form.blade.php`.
- **View_Composer**: The `View::composer` call in `AppServiceProvider::boot()` that injects `$footerPosts` into footer views.
- **Blade_Page**: Any of the eight public Blade views: `index.blade.php`, `product-catalog.blade.php`, `blog-list.blade.php`, `blog-details.blade.php`, `contactus.blade.php`, `about-us.blade.php`, and any shared partials.
- **Bootstrap_Pagination**: Laravel's `$model->links()` markup rendered by `Paginator::useBootstrap()`. Produces Bootstrap 5 `.pagination` HTML. Must not be replaced.
- **Turnstile**: Cloudflare Turnstile widget embedded via `.cf-turnstile` div on the blog comment form.
- **Honeypot**: Hidden `<input>` field on the blog comment form used to catch automated submissions.
- **SAL**: Scroll Animation Library (`sal.min.js`) used for scroll-reveal animations.
- **Swiper**: `swiper.min.js` / `swiper.min.css` used for all carousels.
- **Font_Awesome_5**: The icon library loaded from `public/assets/css/plugins/fontawesome-5.css`. The only permitted icon family.
- **WCAG_AA**: Web Content Accessibility Guidelines 2.1 Level AA. Minimum contrast ratio 4.5:1 for normal text, 3:1 for large text.
- **Task**: One of the nine phased work items (Task 0 through Task 8). Each Task must stop for review before the next begins.
- **Dial_Values**: Three numeric design-control variables — `DESIGN_VARIANCE` (≤ 5), `MOTION_INTENSITY` (≤ 4), `VISUAL_DENSITY` — that govern how far the redesign departs from the current site.
- **TODO_Slot**: A clearly labeled HTML comment `<!-- TODO: missing image — [description] -->` used when a required image file cannot be found in `public/assets/`.

---

## Requirements

---

### Requirement 1: Design Audit (Task 0)

**User Story:** As a designer reviewing the project, I want a structured audit of the current site's design quality so that the redesign dials are calibrated before any code is written.

#### Acceptance Criteria

1. WHEN Task 0 is executed, THE Redesign_System SHALL produce a written audit using the tasteskill v2 Section 11.B audit methodology applied to `DESIGN-CURRENT.md` as input. The audit SHALL cover exactly six domains: typography, color, spacing, motion, components, and layout. For each domain, the audit output SHALL include at least one "preserve" entry and at least one "retire" entry, each entry sourced from observable evidence in `DESIGN-CURRENT.md`.
2. WHEN the audit is produced, THE Redesign_System SHALL include a section headed "Locked tokens" that lists the exact values `--color-primary: #DF0A0A`, `font-primary: DM Sans`, and `font-secondary: Red Hat Display` as locked, and SHALL NOT propose any change to these values.
3. WHEN the audit is complete, THE Redesign_System SHALL propose integer values for `DESIGN_VARIANCE`, `MOTION_INTENSITY`, and `VISUAL_DENSITY`. Each value SHALL be accompanied by a one-line justification that references at least one observable characteristic from `DESIGN-CURRENT.md` and confirms fitness for a B2B manufacturer context.
4. THE Redesign_System SHALL set `DESIGN_VARIANCE` to an integer in the range 1–5 (inclusive) and `MOTION_INTENSITY` to an integer in the range 1–4 (inclusive). IF the tasteskill skill's default for either dial would exceed these bounds, THEN the cap SHALL be noted explicitly in the audit output.
5. WHEN Task 0 output is delivered, THE Redesign_System SHALL include a clearly labeled "STOP — awaiting approval" block as the final element of the output. No file under `public/assets/redesign/`, `resources/views/layouts/`, or `resources/views/partials/` SHALL be created or modified until the user provides explicit written approval of the dial values.
6. WHEN Task 0 is executed, THE Redesign_System SHALL create the file `.kiro/specs/public-site-redesign/baseline.md` containing the following sections, each derived from the current Blade source files (not from a running server):
   - **Routes**: the full output of `php artisan route:list --columns=method,uri,name` run from the project root.
   - **Nav labels**: every navigation link label and its `route()` or `href` value from `layouts/header.blade.php`, listed as `[Label] → [route/href]`.
   - **Anchor inventory**: for each public Blade page (`index`, `about-us`, `contactus`, `blog-list`, `blog-details`, `product-catalog`), a table of every visible `<a>` element's link text and `href` attribute.
   - **Form field inventory**: for each public Blade page, a table of every `<input>`, `<select>`, and `<textarea>` element's `name` attribute and the form's `action` attribute.
7. WHEN `baseline.md` is written, THE Redesign_System SHALL NOT infer or fabricate data. Every value recorded in `baseline.md` SHALL be taken verbatim from the source Blade files. If a value cannot be read from the static source (e.g., a route name that requires server execution), it SHALL be marked as `[requires artisan — run manually]`.
8. WHEN `redesign.css` is created, THE Redesign_System SHALL define `@keyframes` rules for every WOW.js animation class that the current Blade templates apply via `data-wow-*` attributes or hardcoded `.wow` class names. Because `animate.min.css` is banned from New_Layout (criterion 7), `redesign.css` is the sole source of these keyframe definitions. At minimum, THE Redesign_System SHALL include `@keyframes` for `fadeIn`, `fadeInUp`, `fadeInDown`, `fadeInLeft`, and `fadeInRight`, matching the timing and transform values from the original Animate.css definitions. IF the Blade templates reference additional animation names, those keyframes SHALL also be defined in `redesign.css`. Without these definitions, `waw.js` (WOW.js) will initialise without error but `.wow` elements will snap to visible with no animation.
9. THE Redesign_System SHALL evaluate every item in the tasteskill Pre-Flight Check and produce a table with columns: Item | Result (PASS / FAIL / N/A) | Reason. Items relating to Next.js, Tailwind, Motion, GSAP, next/font, or dark mode SHALL be marked N/A. All other items SHALL be marked PASS or FAIL with a one-line reason.

---

### Requirement 2: Foundation Files (Task 1)

**User Story:** As a developer, I want the shared foundation files (layout, CSS, fonts, header, footer, breadcrumb) created first so that all subsequent page tasks can extend the same base.

#### Acceptance Criteria

1. THE Redesign_System SHALL create `public/assets/redesign/css/redesign.css` as the only new CSS file for redesigned pages.
2. WHEN `redesign.css` is created, THE Redesign_System SHALL define all design tokens as CSS custom properties in a `:root {}` block, using at minimum: `--color-primary: #DF0A0A`, `--color-tint: #FFECEC`, `--color-heading: #1C2539`, `--color-body: #5D666F`, `--color-footer-bg: #181C25`, `--font-body`, `--font-heading`, `--transition`, `--section-gap`.
3. THE Redesign_System SHALL self-host DM Sans (weights 400, 500, 700) and Red Hat Display (weights 300, 400, 500, 600, 700, 800, 900) font files in `public/assets/redesign/fonts/` and SHALL load them in `redesign.css` using `@font-face` declarations with `font-display: swap`.
4. THE Redesign_System SHALL NOT load any Google Fonts `@import` URL in `redesign.css` or in New_Layout.
5. THE Redesign_System SHALL create `resources/views/layouts/redesign.blade.php` as New_Layout.
6. WHEN New_Layout is created, THE Redesign_System SHALL include `@stack('styles')` in the `<head>` and `@stack('scripts')` before `</body>`.
7. WHEN New_Layout is created, THE Redesign_System SHALL NOT load `style.css`, Bootstrap 3 CSS, Owl Carousel CSS, Animate.css (the theme's `animate.min.css` from `assets/css/plugins/`), Rev Slider CSS, or Ionicons on any redesigned page. `waw.js` (WOW.js v1.1.2) SHALL be loaded as part of the footer script order per criterion 10, because `main.js` calls `new WOW().init()` directly.
8. WHEN `redesign.css` is created, THE Redesign_System SHALL define `@keyframes` rules for every animation class name that WOW.js (`waw.js`) applies via its `animateClass` default (`"animated"`). Because `animate.min.css` is banned from New_Layout (criterion 7), `redesign.css` is the sole CSS source for these animation definitions. THE Redesign_System SHALL include at minimum the following `@keyframes`: `fadeIn`, `fadeInUp`, `fadeInDown`, `fadeInLeft`, `fadeInRight`. Each keyframe definition SHALL match the transform and opacity values from the original Animate.css specification so that WOW.js reveal animations play correctly on scroll. IF any `.wow` element in the redesigned Blade templates specifies an additional `data-wow-animation` class name not in this list, that keyframe SHALL also be defined in `redesign.css`. Without these definitions, WOW.js will initialise without error but `.wow` elements will snap to visible with no animation.
9. WHEN New_Layout is created, THE Redesign_System SHALL load, in this order: Swiper CSS, Font_Awesome_5 CSS, Bootstrap 5.0.2 CSS (vendor), then `redesign.css`.
10. WHEN New_Layout is created, THE Redesign_System SHALL load JS files in New_Layout's Redesign_Footer in the exact same order as `layouts/footer.blade.php`, keeping every script that `main.js` depends on: `assets/js/vendor/jquery.min.js`, `assets/js/vendor/jqueryui.js`, `assets/js/vendor/waypoint.js`, `assets/js/plugins/swiper.js`, `assets/js/plugins/counterup.js`, `assets/js/plugins/sal.min.js`, `assets/js/vendor/bootstrap.min.js`, `assets/js/vendor/waw.js`, `assets/js/plugins/contact.form.js`, `assets/js/main.js`, then `@stack('scripts')`. No script from this list SHALL be omitted or reordered.
11. WHEN New_Layout is rendered for any redesigned page, each JavaScript file in the footer script list (criterion 10) SHALL appear in the rendered HTML `<body>` exactly once. IF the old `layouts/app.blade.php` head or body also loads `jquery.min.js` or `bootstrap.min.js` separately (e.g. via a leftover `<script>` tag), THE Redesign_System SHALL ensure New_Layout does NOT repeat those tags — New_Layout's Redesign_Footer script block is the sole source for all JS on redesigned pages. No `<script src="...jquery.min.js">` or `<script src="...bootstrap.min.js">` tag SHALL appear anywhere in New_Layout's `<head>` or outside the Redesign_Footer script block.
12. THE Redesign_System SHALL create `resources/views/partials/redesign-header.blade.php` as Redesign_Header.
13. WHEN Redesign_Header is created, THE Redesign_System SHALL reproduce all five nav links (Home, Products, About Us, Blogs, Contact) with the exact same route names and nav labels as in `layouts.header`.
14. WHEN Redesign_Header is created, THE Redesign_System SHALL use `request()->routeIs()` to apply the active state class on the current page's nav link.
15. WHEN Redesign_Header is created, THE Redesign_System SHALL include the search overlay that submits a GET request to `route('blog-list')` with `name="search"`, matching the existing overlay behavior.
16. WHEN Redesign_Header is created, THE Redesign_System SHALL display the existing logo (`assets/images/logo/logo10.png`) at its current dimensions (max-height 60px desktop, 28px mobile). The logo SHALL NOT be replaced or modified.
17. THE Redesign_System SHALL create `resources/views/partials/redesign-footer.blade.php` as Redesign_Footer.
18. WHEN Redesign_Footer is created, THE Redesign_System SHALL render the `$footerPosts` variable injected by View_Composer without any raw database query in the template.
19. WHEN Redesign_Footer is created, THE Redesign_System SHALL preserve the copyright text exactly as it appears in `layouts.footer`: "KONICHIWA - Copyright {year}. All rights reserved."
20. WHEN Redesign_Footer is created, THE Redesign_System SHALL use the dark footer background `#181C25` (`--color-footer-bg`).
21. THE Redesign_System SHALL create `resources/views/partials/redesign-breadcrumb.blade.php` as Breadcrumb_Partial, accepting Blade slot variables `$pageTitle` and `$breadcrumbActive` for the page title and active breadcrumb label.
22. WHEN Breadcrumb_Partial is rendered, THE Redesign_System SHALL use `assets/images/breadcrumb/01.webp` as the background image, matching the current breadcrumb behavior.
23. WHEN Task 1 is complete, THE Redesign_System SHALL switch `about-us.blade.php` to extend New_Layout as a cutover test, with all existing content and sections unchanged.
24. THE Redesign_System SHALL add `'partials.redesign-footer'` to the array of view names in the existing `View::composer` call in `AppServiceProvider::boot()`. This is the only permitted modification to any PHP file in `app/`. The `View::composer` call SHALL remain in its existing array form; a second separate `View::composer` call SHALL NOT be added.
25. IF the `View::composer` binding already includes `partials.redesign-footer`, THEN THE Redesign_System SHALL leave `AppServiceProvider.php` unchanged.
26. WHEN Task 1 is complete, THE Redesign_System SHALL stop and wait for review before proceeding to Task 2.

---

### Requirement 3: Shared Contact Form Partial (Task 2)

**User Story:** As a developer, I want a single shared contact form partial so that the same form implementation is used consistently on the Contact page and Homepage, eliminating the duplicate `mailer.php` form.

#### Acceptance Criteria

1. THE Redesign_System SHALL create `resources/views/partials/contact-form.blade.php` as Contact_Form_Partial.
2. WHEN Contact_Form_Partial is rendered, THE Redesign_System SHALL produce a `<form>` that posts to `route('contact.store')` using the HTTP POST method and includes `@csrf`.
3. Contact_Form_Partial SHALL accept two boolean Blade parameters passed via `@include`: `$showPhone` (default `false`) and `$showSubject` (default `false`). These parameters control which conditional fields are rendered. Both default to false so callers that pass neither get a base form with only `name`, `email`, and `message`.
4. WHEN Contact_Form_Partial is rendered, THE Redesign_System SHALL always include three base fields: `name` (text input, required), `email` (email input, required), and `message` (textarea, required). These fields SHALL always be present regardless of parameter values, and their `name` attributes SHALL match these exact strings.
5. WHEN Contact_Form_Partial is rendered with `$showPhone = true`, THE Redesign_System SHALL render a phone text input with `name="phone"`. WHEN `$showPhone = false`, this input SHALL be omitted entirely from the rendered HTML.
6. WHEN Contact_Form_Partial is rendered with `$showSubject = true`, THE Redesign_System SHALL render a text input with `name="subject"` and placeholder "Inquiry Type (e.g., Wholesale, Retail, Field Visit)" taken from the homepage form. WHEN `$showSubject = false`, this input SHALL be omitted entirely from the rendered HTML.
7. THE Redesign_System SHALL NOT add a Turnstile widget or a honeypot field to Contact_Form_Partial. Neither `contactus.blade.php` nor `ContactController` currently use Turnstile or a honeypot for the contact route. These exist only on the blog comment form. Adding them to Contact_Form_Partial would require a corresponding controller change, which is prohibited.
8. WHEN Contact_Form_Partial is rendered, THE Redesign_System SHALL display a Bootstrap `alert alert-success` element containing `session('success')` if it is set, matching the success display in `contactus.blade.php`.
9. IF Laravel validation errors are returned in the `$errors` bag, THEN THE Redesign_System SHALL display each error message as the immediately-following sibling element of its associated field within the same wrapper.
10. WHEN Contact_Form_Partial is rendered with `$showPhone = true` and the user submits the form with an empty `phone` field, THE Redesign_System SHALL display an inline error message with the text "This field is required" adjacent to the phone input. This client-side validation SHALL be rendered inside a `@if($showPhone)` Blade conditional wrapping a `@push('scripts')` block, so the validation script is only emitted when the phone field is present and executes only after all footer scripts (including jQuery) have loaded. The script SHALL be a self-invoking vanilla JS function (no jQuery dependency) matching the pattern in `contactus.blade.php`'s `@push('scripts')` block.
11. WHEN the `phone` field is empty and loses focus (blur event) and `$showPhone` is true, THE Redesign_System SHALL trigger the validation error immediately without requiring form submission.
12. WHEN the user types a non-empty value into the `phone` field, THE Redesign_System SHALL clear the validation error message and remove any error border styling from the phone input immediately.
13. WHEN Task 2 is complete, THE Redesign_System SHALL stop and wait for review before proceeding to Task 3.

---

### Requirement 4: Contact Page (Task 3)

**User Story:** As a site visitor, I want the redesigned Contact page so that I can reach Konichiwa through a visually improved interface that still provides the same contact information and form.

#### Acceptance Criteria

1. WHEN `contactus.blade.php` is updated, THE Redesign_System SHALL extend New_Layout and preserve all three contact info cards (Phone, Email, Address) with their existing images (`contact/22.webp`, `contact/23.webp`, `contact/03.png`), icon SVGs, label text, phone number, email address, and office address exactly as they appear in the current view.
2. WHEN the Contact page is rendered, THE Redesign_System SHALL use Contact_Form_Partial for the enquiry form by replacing the standalone `<form>` with `@include('partials.contact-form', ['showPhone' => true, 'showSubject' => false])`. The section heading ("Get In Touch") and its surrounding wrapper element SHALL be preserved. The `@push('scripts')` block in `contactus.blade.php` that contained the phone validation logic SHALL be removed, as that logic is now owned by Contact_Form_Partial when `showPhone` is true.
3. WHEN the Contact page is rendered, THE Redesign_System SHALL preserve the Google Maps iframe with the exact `src` URL unchanged.
4. WHEN the Contact page is rendered, THE Redesign_System SHALL render the breadcrumb using Breadcrumb_Partial with `$pageTitle = 'Contact Us'` and `$breadcrumbActive = 'Contact Us'`.
5. WHEN Task 3 is complete, THE Redesign_System SHALL stop and wait for review before proceeding to Task 4.

---

### Requirement 5: Homepage (Task 4)

**User Story:** As a site visitor, I want the redesigned homepage so that I receive a cohesive B2B first impression while all existing interactive sections continue to work identically.

#### Acceptance Criteria

1. WHEN `index.blade.php` is updated, THE Redesign_System SHALL extend New_Layout and SHALL NOT extend the old layout.
2. WHEN the homepage is rendered, THE Redesign_System SHALL preserve all eight existing sections in their original document order: Swiper hero (`.rts-banner-area`), About (`.rts-about-area`), Business Goal (`.rts-business-goal`), Gallery/Products Swiper (`.rts-gallery-area`), Features (`.rts-feature-area`), Farmer Stories (`@include('farmer-stories.section')`), Blog Swiper (`.rts-blog-area`), Map (`.rts-map-area`); no section SHALL be added between, removed from, or reordered within this sequence.
3. WHEN the homepage is rendered, THE Redesign_System SHALL preserve all Swiper container classes (`mySwiper banner-one`, `mySwipers`, `mySwiperh1_blog`) and their associated pagination, navigation, and wrapper elements without modification, so that `main.js` Swiper initialization executes without error.
4. WHEN the Farmer Stories section is rendered and `$farmerStories->isNotEmpty()` evaluates to false, THE Redesign_System SHALL keep the section hidden from the rendered output, either by rendering it with `display:none` or by wrapping it in `@if($farmerStories->isNotEmpty())`, matching the behavior defined in `farmer-stories/section.blade.php`; no fallback content or placeholder SHALL be substituted.
5. WHEN the homepage is rendered, THE Redesign_System SHALL replace the inline contact form (currently submitting to `mailer.php`) with `@include('partials.contact-form', ['showPhone' => false, 'showSubject' => true])`. This renders `name`, `email`, `subject`, and `message` — matching the homepage form's current field set — and omits `phone`, which the homepage form does not have. The `showPhone`/`showSubject` parameters resolve the field difference. The form action change from `mailer.php` to `route('contact.store')` is pending user confirmation and SHALL NOT be implemented until the user confirms it in writing. See baseline.md Section 5.
6. WHEN the homepage is rendered, THE Redesign_System SHALL retain the Trusted Clients, Team, Counter-up, and Testimonials blocks in their existing `{{-- --}}` Blade comment delimiters; no block within those comments SHALL be uncommented, removed, or restructured.
7. WHEN the homepage hero Swiper slides are rendered, THE Redesign_System SHALL use each slide's existing background image path and each shape image path (`banner/shape/01.png`, `banner/shape/02.png`, `banner/shape/03.png`) without substitution; IF a referenced image file does not exist at its stated path, THEN THE Redesign_System SHALL insert a labeled `{{-- TODO: missing image: [path] --}}` comment in place of the `src` or `style` value rather than substituting a Picsum or external URL.
8. WHEN Task 4 is complete, THE Redesign_System SHALL stop and await explicit reviewer approval before any file belonging to Task 5 is created or modified.

---

### Requirement 6: About Page (Task 5)

**User Story:** As a site visitor, I want the redesigned About page so that I see a refined presentation of Konichiwa's mission and company information without any content changes.

#### Acceptance Criteria

1. WHEN `about-us.blade.php` is updated for full redesign (building on the cutover test from Task 1), THE Redesign_System SHALL preserve all existing text content verbatim, including headings, body paragraphs, accordion question text, and accordion answer text. No copy SHALL be rewritten or paraphrased.
2. WHEN the About page is rendered, THE Redesign_System SHALL preserve both progress bars (`data-progress="70"` and `data-progress="93"`) with their labels ("Crop Protection Accuracy", "Company Strength") and percentage labels ("95%", "93%") exactly as in the current view, with each bar's filled width matching its `data-progress` value in percentage units.
3. WHEN the About page is rendered, THE Redesign_System SHALL preserve the JavaScript-driven progress bar animation mechanism based on the `data-progress` attribute, triggering when the progress bar element enters the viewport (scroll-triggered) or on page load if already visible, and completing the fill animation within 1000ms.
4. WHEN the About page is rendered, THE Redesign_System SHALL preserve the Bootstrap 5 accordion (exactly 3 items) using `data-bs-toggle="collapse"` and `data-bs-parent="#accordionExample"` attributes on each toggle control, with no structural changes to the accordion markup.
5. WHEN the About page is rendered, THE Redesign_System SHALL preserve both existing about-section images (`about/main/88.png` and `about/main/about4.png`) and the FAQ section image (`faq/03.webp`), each rendered using the same `src` path and `alt` attribute as in the current view. No image SHALL be replaced with a placeholder.
6. WHEN the About page is rendered, THE Redesign_System SHALL keep all currently commented-out sections (Services cards, Customer feedback) as HTML comments, with no structural changes to or removal of the commented-out markup.
7. WHEN the About page is rendered, THE Redesign_System SHALL apply New_Layout, CSS variables from `redesign.css`, and the DM Sans/Red Hat Display font stack, consistent with the standards applied in Tasks 1 through 4.
8. WHEN Task 5 is complete, THE Redesign_System SHALL stop and wait for review before proceeding to Task 6.

---

### Requirement 7: Blog Pages (Task 6)

**User Story:** As a reader, I want the redesigned blog list and blog detail pages so that I can browse and read posts with an improved visual design while all filtering, pagination, commenting, and sharing features work identically.

#### Acceptance Criteria

1. WHEN `blog-list.blade.php` is updated, THE Redesign_System SHALL extend New_Layout and preserve the two-column layout (col-xl-8 main content, col-xl-4 sidebar) as rendered in the browser at viewport widths ≥ 1200px.
2. WHEN the blog list page is rendered, THE Redesign_System SHALL preserve the GET-based search as a `<form>` with `method="GET"` and `action="{{ route('blog-list') }}"` containing an `<input name="search">`, exactly as in the current view.
3. WHEN the blog list page is rendered and `$search` is non-empty, THE Redesign_System SHALL display the search results banner showing "Showing results for [term] — N posts found" and a Clear button that is an anchor linking to `route('blog-list')` with no query parameters.
4. WHEN the blog list page is rendered, THE Redesign_System SHALL preserve Bootstrap_Pagination markup rendered by `{{ $blogs->links() }}` without wrapping it in a custom pagination component.
5. WHEN the blog list is empty and `$search` is set, THE Redesign_System SHALL render an empty state containing the text "No results found for [term]" and a "Clear Search" anchor linking to `route('blog-list')`.
6. WHEN the blog list is empty and `$search` is not set, THE Redesign_System SHALL render the empty state containing a folder-open icon (Font Awesome `fa-folder-open`) and the text "No blog posts found."
7. WHEN the blog list sidebar is rendered, THE Redesign_System SHALL include all four widgets in this order: Search, Categories, Recent Posts, Contact CTA.
8. WHEN the Categories sidebar widget is rendered, THE Redesign_System SHALL apply the active highlight to the link whose `href` parameter matches `request('category')` and include a "Clear filter" anchor linking to `route('blog-list')` when a category filter is active.
9. WHEN `blog-details.blade.php` is updated, THE Redesign_System SHALL extend New_Layout and preserve the two-column layout (col-xl-8 post content, col-xl-4 sidebar) at viewport widths ≥ 1200px.
10. WHEN the blog detail page is rendered, THE Redesign_System SHALL preserve all four share buttons (Facebook, Twitter, Instagram clipboard copy, LinkedIn) with their existing `onclick` JavaScript handlers unchanged and unmodified.
11. WHEN the blog detail page is rendered and `$comments->count() > 0`, THE Redesign_System SHALL render each approved comment showing the commenter's name initial avatar, name, date, and message, all output using `{{ }}` auto-escaped syntax. The Redesign_System SHALL NOT use `{!! !!}` for comment name or message.
12. WHEN the blog detail comment form is rendered, THE Redesign_System SHALL include the Turnstile widget (`.cf-turnstile` div), the Honeypot field, `@csrf`, and display all Laravel validation errors from the `$errors` bag as the immediately-following sibling element of each associated field. IF the `$errors` bag contains a `cf-turnstile-response` error, THE Redesign_System SHALL display that error message adjacent to the Turnstile widget.
13. WHEN the blog detail page is rendered and `session('comment_success')` is set, THE Redesign_System SHALL display a Bootstrap `alert alert-success` element containing the success message.
14. WHEN the blog detail sidebar is rendered, THE Redesign_System SHALL include the Categories widget and Related Posts widget. The Search widget SHALL remain wrapped in an HTML comment (`{{-- ... --}}`) as in the current view.
15. WHEN Task 6 is complete, THE Redesign_System SHALL stop and wait for review before proceeding to Task 7.

---

### Requirement 8: Products Page (Task 7)

**User Story:** As a buyer browsing the catalog, I want the redesigned Products page so that I see a premium product grid while product images render correctly and pagination continues to work.

#### Acceptance Criteria

1. WHEN `product-catalog.blade.php` is updated, THE Redesign_System SHALL extend New_Layout.
2. WHEN the Products page is rendered, THE Redesign_System SHALL NOT call any controller method, modify `ProductController`, or alter any variable (`$products`, `$currentPage`, `$totalPages`) passed from the controller to the view.
3. WHEN a product card image is rendered, THE Redesign_System SHALL apply `object-fit: contain` to the product image (not `cover`). Changing this to `object-fit: cover` is a hard constraint violation.
4. WHEN the Products page is rendered and `$totalPages > 1`, THE Redesign_System SHALL preserve the custom `.page-item-btn` pagination component with active state styling, previous/next chevron buttons, and zero-padded page numbers, as currently implemented in `product-catalog.blade.php`.
5. WHEN `$products` is empty, THE Redesign_System SHALL render the empty state showing the basket icon, the text "No products are currently available in the catalog.", and a "Contact us for inquiries" link to `route('contact')`.
6. WHEN the Products page is rendered, THE Redesign_System SHALL preserve the breadcrumb using Breadcrumb_Partial with `$pageTitle = 'Product Catalog'` and `$breadcrumbActive = 'Products'`.
7. WHEN the Products page is rendered and `$totalPages > 1`, the active page button SHALL have `background-color: var(--color-primary)` and white text. The inactive page buttons SHALL have a neutral background. Previous/Next buttons SHALL be visually distinct from numbered page buttons.
8. WHEN Task 7 is complete, THE Redesign_System SHALL stop and wait for review before proceeding to Task 8.

---

### Requirement 9: Visual Design Standards

**User Story:** As the brand owner, I want all redesigned pages to use the defined palette, typography, and spacing so that the site presents a consistent premium B2B identity.

#### Acceptance Criteria

1. THE Redesign_System SHALL use `#DF0A0A` as the primary action color for CTA buttons, active nav indicators, progress bars, and section accent elements.
2. THE Redesign_System SHALL use `#FFECEC` as the tint color for button hover background states. WHEN a primary button is hovered, its background SHALL transition to `#FFECEC` and its text color SHALL transition to `#1C2539`.
3. THE Redesign_System SHALL use `#1C2539` for all heading text (h1–h6).
4. THE Redesign_System SHALL use `#5D666F` for all body paragraph text. IF this value fails WCAG_AA contrast ratio of 4.5:1 against a white (`#FFFFFF`) background, THEN THE Redesign_System SHALL darken it to the minimum value that passes, and SHALL document the adjusted value in `redesign.css` with a comment.
5. THE Redesign_System SHALL use `#181C25` for the footer background.
6. THE Redesign_System SHALL NOT introduce any color outside the defined palette. IF an additional color is required, THE Redesign_System SHALL document the hex value, the element it is applied to, and the justification in a CSS comment within `redesign.css`.
7. THE Redesign_System SHALL use DM Sans for all body text and Red Hat Display for all heading elements. No other font family SHALL be introduced on any redesigned page.
8. THE Redesign_System SHALL maintain the existing type scale at desktop breakpoints: h1 60px / h2 48px / h3 36px / h4 30px / h5 24px / h6 20px. At mobile breakpoints (≤ 767px): h1 34px / h2 28px / h3 24px / h4 22px / h5 20px / h6 18px.
9. THE Redesign_System SHALL apply section vertical padding of `120px 0` on desktop (≥ 992px), `80px 0` on tablet (768px–991px), and `60px 0` on mobile (< 768px), using a CSS class driven by `--section-gap` or the `.rts-section-gap` utility.
10. THE Redesign_System SHALL apply a light theme only. No dark mode styles, `prefers-color-scheme: dark` media queries, or dark variant classes SHALL be introduced in `redesign.css` or New_Layout.
11. THE Redesign_System SHALL use Font_Awesome_5 exclusively for all icons on redesigned pages. No other icon library (Ionicons, Phosphor, Bootstrap Icons, Heroicons) SHALL be loaded in New_Layout or any redesigned Blade file.

---

### Requirement 10: Layout and Component Behavior

**User Story:** As a developer, I want all shared layout components (header, footer, breadcrumb, cards) to follow consistent structural rules so that each page feels part of the same design system.

#### Acceptance Criteria

1. WHEN New_Layout is rendered on any page, THE Redesign_System SHALL include the page-loader element with class `.loader-wrapper` containing at minimum a logo `<img>` referencing `assets/images/logo/logo10.png` and a spinner element. The structure SHALL match the current loader in `layouts.app.blade.php`.
2. WHEN Redesign_Header is rendered on desktop (≥ 1200px), THE Redesign_System SHALL display the horizontal navigation links. WHEN Redesign_Header is rendered on viewports narrower than 1200px, THE Redesign_System SHALL hide the horizontal navigation and display a hamburger button.
3. WHEN the hamburger button is clicked and the mobile sidebar is closed, THE Redesign_System SHALL open the mobile slide-in sidebar displaying: contact info (phone number, email address, office address), a "Get Quote" CTA button linking to `route('contact')`, and the five nav links. WHEN the hamburger button is clicked again or a backdrop overlay is clicked, THE Redesign_System SHALL close the sidebar.
4. WHEN Redesign_Footer is rendered at viewport widths ≥ 768px, THE Redesign_System SHALL display the three-column layout (Quick Links, Popular Updates, ISO Certificate). WHEN rendered below 768px, the columns SHALL stack vertically in that same order.
5. WHEN Redesign_Footer "Popular Updates" is rendered and `$footerPosts` is null or an empty collection, THE Redesign_System SHALL still render the "Popular Updates" section heading without error; no exception SHALL be thrown and no PHP warning SHALL appear in the HTML output.
6. THE Redesign_System SHALL include the `.progress-wrap` back-to-top SVG circle element in the rendered DOM of Redesign_Footer.
7. WHEN any product card is rendered, THE Redesign_System SHALL maintain the product image container at a height of 280px with `background-color: #f8fafc` and `padding: 20px`.
8. WHEN any blog list card is rendered, THE Redesign_System SHALL maintain the blog thumbnail container at a height of 350px with `object-fit: cover` applied to the thumbnail image.
9. WHEN Breadcrumb_Partial is rendered, THE Redesign_System SHALL display the breadcrumb separator as `" / "` where the separator character is rendered in `var(--color-primary)` and the active breadcrumb item text is rendered in `#1C2539`, matching the current breadcrumb visual style.

---

### Requirement 11: Behavioral Preservation Constraints

**User Story:** As a developer, I want strict constraints on what must not change so that the redesign cannot silently break any existing backend integration or user-facing behavior.

#### Acceptance Criteria

1. THE Redesign_System SHALL NOT modify any file in `app/Http/Controllers/`, `app/Models/`, `app/Http/Middleware/`, `app/Http/Requests/`, `app/Rules/`, or `routes/`.
2. THE Redesign_System SHALL NOT modify any file in `resources/views/admin/`, `resources/views/auth/`, `resources/views/app.blade.php`, `resources/js/`, or `backup/`.
3. THE Redesign_System SHALL NOT delete or overwrite any file in `public/assets/`. New files may only be added under `public/assets/redesign/`.
4. THE Redesign_System SHALL NOT change any URL, route name, nav label, form field `name` attribute, logo file path, or legal copy string. IF such a change is required, THE Redesign_System SHALL add an inline code comment in the modified file recording the original value, the new value, and the reason before making the change.
5. THE Redesign_System SHALL preserve `@stack('styles')` and `@stack('scripts')` in New_Layout so that individual pages can push page-specific CSS and JS.
6. WHEN the `@push('styles')` or `@push('scripts')` stacks are used in any Blade_Page, THE Redesign_System SHALL ensure those stacks are output in New_Layout at the correct positions (`@stack('styles')` in `<head>`, `@stack('scripts')` before `</body>`).
7. THE Redesign_System SHALL preserve the `request()->routeIs()` active navigation detection in Redesign_Header. The PHP expression `request()->routeIs(...)` SHALL remain present verbatim; substituting it with a different mechanism is prohibited.
8. THE Redesign_System SHALL preserve the GET `/blogs?search=` search overlay in Redesign_Header. The overlay form SHALL use `method="GET"` and contain an `<input name="search">`. The overlay toggle and close button SHALL remain functionally identical to those in the current `layouts.header`.
9. WHEN the about-us progress bars are rendered, THE Redesign_System SHALL preserve the `data-progress` attribute, the HTML element tag, and the CSS class selector used by `main.js` to animate the progress bars, so that `main.js` continues to animate them without modification.
10. THE Redesign_System SHALL preserve the `Paginator::useBootstrap()` call in `AppServiceProvider::boot()`. Removing or commenting out this call is prohibited.
11. IF a required image is referenced in a current Blade view but does not exist at its stated path under `public/assets/` at the time of editing, THEN THE Redesign_System SHALL insert a comment `<!-- TODO: image not found: [original path] -->` in place of the `src` or `style` value rather than substituting a placeholder URL.
12. THE Redesign_System SHALL NOT use Picsum, Unsplash, Lorempixel, Lorem Ipsum, AI-generated images, or any external image as a stand-in. All `src` values SHALL either reference `public/assets/` paths confirmed to exist or follow the TODO comment format in criterion 11.

---

### Requirement 12: Accessibility (WCAG AA)

**User Story:** As a user relying on assistive technology, I want all redesigned pages to meet WCAG 2.1 Level AA so that I can navigate and use the site regardless of visual ability.

#### Acceptance Criteria

1. THE Redesign_System SHALL ensure that all text/background color pairs used in `redesign.css` meet a minimum contrast ratio of 4.5:1 for normal text and 3:1 for large text (≥ 18pt or ≥ 14pt bold), as defined by WCAG_AA.
2. THE Redesign_System SHALL ensure `#DF0A0A` on `#FFFFFF` background is verified for contrast compliance. IF this combination fails WCAG_AA for the text size it is applied to, THEN THE Redesign_System SHALL adjust the red value to the nearest darker hue that achieves the required ratio, or restrict its use to large text contexts only (≥ 18pt or ≥ 14pt bold).
3. WHEN interactive elements (buttons, links, form inputs) are rendered, THE Redesign_System SHALL include a visible `:focus-visible` outline of at least 2px solid with a contrast ratio of at least 3:1 against the adjacent background color.
4. WHEN images that convey information are rendered, THE Redesign_System SHALL ensure a non-empty `alt` attribute describing the image content is present. Decorative images SHALL use `alt=""`.
5. WHEN form inputs are rendered in any partial or page, THE Redesign_System SHALL associate each `<input>` and `<textarea>` with a corresponding `<label>` element via a matching `for`/`id` pair, or with an `aria-label` attribute when no visible label is present.
6. WHEN the mobile navigation is toggled open, THE Redesign_System SHALL set the `aria-expanded` attribute on the hamburger button to `"true"`. WHEN the mobile navigation is toggled closed, THE Redesign_System SHALL set the `aria-expanded` attribute on the hamburger button to `"false"`.
7. WHEN the Bootstrap 5 accordion on the About page is rendered, THE Redesign_System SHALL preserve the existing `aria-expanded`, `aria-controls`, and `aria-labelledby` attributes on all accordion elements without modification.
8. THE Redesign_System SHALL ensure the HTML `lang` attribute on the `<html>` element in New_Layout is set to `"en"`.
9. THE Redesign_System SHALL include a note in the Task 8 audit output stating that full WCAG 2.1 AA validation requires manual testing with assistive technologies and expert accessibility review, as automated checks cannot cover all criteria.
10. WHEN a page in New_Layout contains navigational landmark regions, THE Redesign_System SHALL mark each region with the corresponding semantic HTML5 element: `<header>` for the site header, `<main>` for the primary page content, `<footer>` for the site footer, and `<nav>` for navigation menus.
11. WHEN a page in New_Layout is rendered and contains a sequence of headings, THE Redesign_System SHALL ensure heading levels (`<h1>` through `<h6>`) appear in a non-skipping hierarchical order, with exactly one `<h1>` per page.

---

### Requirement 13: Responsiveness

**User Story:** As a mobile user, I want all redesigned pages to display correctly at standard breakpoints so that the site is usable on phones, tablets, and desktops.

#### Acceptance Criteria

1. THE Redesign_System SHALL ensure all redesigned pages render without horizontal overflow (document `scrollWidth` does not exceed `clientWidth`) at viewport widths of 375px, 768px, and 1280px.
2. THE Redesign_System SHALL use Bootstrap 5 grid classes (`col-*`, `col-sm-*`, `col-md-*`, `col-lg-*`, `col-xl-*`) for all responsive column layouts. Bootstrap 3 grid classes (`col-xs-*`) SHALL NOT be introduced.
3. WHEN the page is rendered at 375px width, THE Redesign_System SHALL collapse the three info cards on the Contact page to a single-column stacked layout where each card occupies the full container width.
4. WHEN the page is rendered at 768px width, THE Redesign_System SHALL collapse the blog list two-column layout (main content + sidebar) to a single-column layout with the sidebar rendered in document order after the post list.
5. WHEN the page is rendered at a viewport width below 576px, THE Redesign_System SHALL collapse the product grid to a single-column layout where each product card occupies the full container width.
6. WHILE a Swiper carousel (banner, products gallery, or blog) is displayed, THE Redesign_System SHALL ensure the carousel wrapper does not overflow its parent container and slide navigation (arrow controls or touch swipe, as configured per carousel) advances slides at viewport widths of 375px, 768px, and 1280px.
7. WHEN the page is rendered at 375px width, THE Redesign_System SHALL collapse the main navigation into a hamburger toggle button and display the nav links in a vertically stacked dropdown that is fully contained within the viewport width.

---

### Requirement 14: Final Audit (Task 8)

**User Story:** As a project stakeholder, I want a final audit confirming all design and behavioral constraints were met so that the redesign can be signed off without hidden regressions.

#### Acceptance Criteria

1. WHEN Task 8 is executed, THE Redesign_System SHALL check all redesigned pages for horizontal overflow and layout collapse at 375px, 768px, and 1280px viewports, and SHALL record a verification method for each check using one of three labels: **browser-devtools** (measured using browser DevTools responsive mode or `document.documentElement.scrollWidth > clientWidth` in console), **static-analysis** (inferred from CSS/HTML source without running the page), or **needs-manual-check** (cannot be verified without loading the page in a browser). Any item that cannot be confirmed by static analysis SHALL be labeled `needs-manual-check` rather than PASS in the audit output.
2. WHEN Task 8 is executed, THE Redesign_System SHALL verify WCAG AA contrast ratios for all foreground-on-background color pairs introduced in `redesign.css`. For each pair, the audit output SHALL record: the foreground hex value, the background hex value, the computed contrast ratio (calculated using the WCAG relative luminance formula), a PASS/FAIL result (4.5:1 threshold for normal text, 3:1 for large text and UI components), and the verification method label: **computed** (ratio calculated from the hex values using the luminance formula — this can be done statically) or **needs-manual-check** (if the background is a gradient, image, or dynamically applied). Any item where the background cannot be determined statically SHALL be labeled `needs-manual-check`.
3. WHEN Task 8 is executed, THE Redesign_System SHALL verify that every `asset()` path referenced in all redesigned Blade files resolves to a file that exists under `public/`.
4. WHEN Task 8 is executed, THE Redesign_System SHALL confirm that no placeholder text (Lorem Ipsum, "dummy", "placeholder", "TODO text") appears in the rendered HTML of any redesigned page, excluding labeled TODO_Slot comments.
5. WHEN Task 8 is executed, THE Redesign_System SHALL confirm that `style.css`, Bootstrap 3 CSS, Owl Carousel CSS, the theme's `animate.min.css` (from `assets/css/plugins/animate.min.css`), Rev Slider CSS, and Ionicons are not loaded in New_Layout or any redesigned page. `waw.js` (WOW.js) SHALL be present in the footer script order and is therefore expected — its presence is NOT a finding.
6. WHEN Task 8 is executed, THE Redesign_System SHALL produce a Brand Fidelity audit report that lists the measured `DESIGN_VARIANCE` score and `MOTION_INTENSITY` score for each redesigned page, and marks each score as PASS if `DESIGN_VARIANCE` is 5 or below and `MOTION_INTENSITY` is 4 or below, or FAIL otherwise.
7. WHEN Task 8 is executed, THE Redesign_System SHALL compare every URL path, nav label, form field `name` attribute, and `<a>` visible link text in redesigned pages against the values recorded in the Pre-Redesign Baseline captured during Task 0, and SHALL produce a change list that MUST be empty unless the changed item appears in an explicitly approved-change record.
8. WHEN Task 8 is executed, THE Redesign_System SHALL produce a list of any files under `public/assets/redesign/` whose path does not appear in any `href`, `src`, `action`, `url()`, or `asset()` call across all redesigned Blade files and `redesign.css`, and SHALL NOT delete any listed file.
9. WHEN Task 8 is complete, THE Redesign_System SHALL stop and present a single consolidated audit report containing, at minimum: the responsive overflow results from criterion 1, the WCAG contrast table from criterion 2, the asset-path verification result from criterion 3, the placeholder-text result from criterion 4, the banned-asset result from criterion 5, the Brand Fidelity scores from criterion 6, the URL/label change list from criterion 7, and the dead-file list from criterion 8, and SHALL await explicit sign-off before any further changes are made.
10. IF the Pre-Redesign Baseline referenced in criterion 7 does not exist at the start of Task 8, THEN THE Redesign_System SHALL halt execution and report that the baseline is missing, specifying which pages lack a recorded baseline.

---

### Requirement 15: Phased Delivery and Review Gates

**User Story:** As a project manager, I want each task to deliver only its defined scope and stop for review so that problems are caught incrementally rather than accumulating across all eight pages.

#### Acceptance Criteria

1. THE Redesign_System SHALL complete Tasks 0 through 8 in sequential order. No Task SHALL begin implementation until the preceding Task's review gate has been passed, where "review gate passed" means the user has provided explicit written approval in the session (e.g., "approved", "looks good", "proceed").
2. WHEN any Task is complete, THE Redesign_System SHALL stop and present: (a) a list of all files created or modified during that Task, (b) a summary of the changes made, and (c) the explicit statement "Waiting for your approval before proceeding to Task [N+1]."
3. THE Redesign_System SHALL NOT combine two or more Tasks into a single delivery step unless the user provides explicit written instruction in the current session to do so.
4. WHEN implementing any Task, THE Redesign_System SHALL limit changes to only the files described in that Task's scope. Files belonging to future Tasks SHALL NOT be created or modified.
5. WHEN a Task scope says "content unchanged", THE Redesign_System SHALL not alter any text string, `value` attribute, `name` attribute, image `src` attribute, route reference, `data-*` attribute, or Blade directive in that page's content sections.