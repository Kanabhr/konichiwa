<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Konichiwa | Leading Chemical Solutions')</title>
    
    <!-- SEO Meta Tags -->
    <meta name="description" content="@yield('meta_description', 'Konichiwa Chemicals provides premium industrial chemical solutions and innovative products for global markets.')">
    <meta name="keywords" content="@yield('meta_keywords', 'chemicals, industrial solutions, konichiwa, chemistry, innovation')">
    <meta name="author" content="Konichiwa">
    <meta name="robots" content="index, follow">

    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('assets/images/fav.png') }}">

    <!-- Stylesheets -->
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/swiper.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/fontawesome-5.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/animate.min.css') }}">
    <!-- <link rel="stylesheet" href="{{ asset('assets/css/plugins/unicons.css') }}"> -->
    <link rel="stylesheet" href="{{ asset('assets/css/vendor/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    
    @stack('styles')
</head>
<body>
    <!-- start loader -->
    <div class="loader-wrapper">
        <div class="loader">
            <img src="{{ asset('assets/images/logo/logo10.png') }}" alt="Logo">
        </div>
        <div class="loader-section section-left"></div>
        <div class="loader-section section-right"></div>
    </div>
    <!-- End loader -->

    @include('layouts.header')

    <main class="content-area">
        @yield('content')
    </main>

    @include('layouts.footer')

    <!-- Scripts -->
    <script src="{{ asset('assets/js/vendor/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/vendor/bootstrap.min.js') }}"></script>
    @stack('scripts')
</body>
</html>

