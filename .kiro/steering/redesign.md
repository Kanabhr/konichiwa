---
inclusion: always
---
# Konichiwa Chemicals public site redesign

Design taste: use the installed design-taste-frontend skill (tasteskill v2) as the only source of design rules. Mode: Redesign - Preserve brand (client wants the red theme kept).
Priority when sources disagree: these project rules, then tasteskill, then the template. The GainGrower template (design-reference/gaingrower-package/gaingrower/, Bootstrap 3.3.7; ignore documentation/ and psdfile/) is layout and mood inspiration only.

Stack (overrides the skill's React/Next/Tailwind/Motion defaults):
- Public site is Blade + Bootstrap 5.0.2 + jQuery + Swiper + SAL + Font Awesome 5 from public/assets/. Not React.
- Install no new npm or composer packages. No Tailwind, Motion, GSAP or Phosphor. Font Awesome 5 is the one icon family.
- Never copy template CSS/JS. Never load Bootstrap 3, Owl, Animate.css, Rev Slider, Ionicons, or old style.css on redesigned pages.
- waw.js is WOW.js and main.js calls it, so it stays loaded. Do not remove or edit it.
- Scripts: load each JS file exactly once, in the order used by layouts/footer.blade.php (jquery.min, jqueryui, waypoint, swiper, counterup, sal.min, bootstrap.min, waw, contact.form, main), then @stack('scripts'). Do not copy the duplicate jQuery and Bootstrap loads from app.blade.php.
- Own CSS: public/assets/redesign/css/redesign.css using CSS variables. Self-host DM Sans and Red Hat Display in public/assets/redesign/fonts with font-display: swap.
- New layout: resources/views/layouts/redesign.blade.php. Old pages keep the old layout until cutover.

Design decisions:
- Palette: primary #DF0A0A, tint #FFECEC, heading navy #1C2539, body #5D666F, dark footer #181C25. Not the template's green or yellow. Use navy text on the tint background, not red.
- Fonts: keep DM Sans (body) and Red Hat Display (headings). Do not change the type stack.
- Light theme only. No dark mode (explicit instruction).
- Dials: infer them in the audit, but this is a B2B manufacturer, so keep DESIGN_VARIANCE <= 5 and MOTION_INTENSITY <= 4.
- Images: reuse existing site images and template images. No Picsum, no generated stand-ins, no fake screenshots. If an image is missing, leave a labeled TODO slot.
- Do not rewrite existing client copy or blog content. The em-dash ban applies only to strings you write.
- Pre-Flight Check: mark items about Next.js, Tailwind, Motion, GSAP, next/font and dark mode as N/A. All other items apply.

Backend and preservation:
- Do not modify controllers, routes, models, middleware, or variables passed to views. Only allowed backend edit: add the new footer view name to the existing View::composer binding in AppServiceProvider.
- Do not touch admin, auth, settings, resources/js, or /backup.
- Never delete anything in public/assets/ (admin depends on it).
- Never change silently: URLs, nav labels, form field names, logo, legal copy.
- Do not add Turnstile or a honeypot to the contact or homepage forms. They do not use them today and ContactController does not check them.
- The contact form partial takes showPhone and showSubject flags so each page keeps exactly its current fields.
- Keep @stack('styles'), @stack('scripts'), request()->routeIs() active nav, and the GET /blogs?search= overlay.
- Preserve: product object-fit: contain, farmer-stories empty state, blog comment form (Turnstile, honeypot, errors), share buttons, Bootstrap pagination markup, contact phone validation.
- Use only real existing content. No placeholder text, no fake team or testimonials.

Process: Task 0 captures a baseline (routes, nav labels, links, form fields) in .kiro/specs/public-site-redesign/baseline.md before any code changes. One page at a time, stop for review after each, show a list of changed files.