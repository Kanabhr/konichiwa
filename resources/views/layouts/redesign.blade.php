<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Konichiwa | Leading Chemical Solutions')</title>
    <title>...</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}">
    <meta name="description" content="@yield('meta_description', 'Konichiwa Chemicals provides premium agricultural chemical solutions for global markets.')">
    <meta name="keywords" content="@yield('meta_keywords', 'chemicals, agricultural solutions, konichiwa, farming, crop protection')">
    <meta name="author" content="Konichiwa">
    <meta name="robots" content="index, follow">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('assets/images/favicon.png') }}">

    {{--
        CSS load order (fixed — Requirement 2.9):
        1. Swiper CSS
        2. Font Awesome 5
        3. Bootstrap 5.0.2
        4. redesign.css  ← our new stylesheet
        5. @stack('styles') — page-specific additions

        BANNED from New_Layout (never add):
          style.css | animate.min.css | Bootstrap 3 | Owl Carousel | Rev Slider | Ionicons
    --}}
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/swiper.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/fontawesome-5.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/vendor/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/redesign/css/redesign.css') }}">
    @stack('styles')
</head>
<body>

    {{-- Page loader — structure matches existing markup for main.js compatibility --}}
    <div class="loader-wrapper">
        <div class="loader">
            <img src="{{ asset('assets/images/logo/logo10.png') }}" alt="Konichiwa Logo">
        </div>
        <div class="loader-section section-left"></div>
        <div class="loader-section section-right"></div>
    </div>

    @include('partials.redesign-header')

    <main id="main-content" role="main">
        @yield('content')
    </main>

    @include('partials.redesign-footer')
    {{--
        All JS is loaded inside partials/redesign-footer.blade.php in the exact
        footer script order. Do NOT add <script> tags here — the footer partial
        is the sole JS source for all redesigned pages.
        @stack('scripts') is also inside redesign-footer, after main.js.
        (Requirements 2.10, 2.11)
    --}}
</body>
</html>
