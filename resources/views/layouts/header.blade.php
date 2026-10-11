<header class="header--sticky header-one ">
    <div class="header-top header-top-one bg-1">
        <div class="container">
            <div class="row">
                <div class="col-12 d-xl-block d-none">
                    <div class="left">
                        <div class="mail">
                            <a href="mailto:{{ $site->email }}"><i class="fal fa-envelope"></i> {{ $site->email }}</a>
                        </div>
                        <div class="mail">
                            <a href="#"><i class="fas fa-certificate"></i> ISO No.- INQ/AN-25712/129662/1225</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="header-main-one bg-white">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-xl-3 col-lg-4 col-md-4 col-sm-4 col-4">
                    <div class="thumbnail">
                        <a href="{{ route('home') }}">
                            <img src="{{ asset('assets/images/logo/logo10.png') }}" alt="Konichiwa Logo" class="logo-img" style="max-height: 60px;">
                        </a>
                    </div>
                </div>
                <div class="col-xl-9 col-lg-8 col-md-8 col-sm-8 col-8">
                    <div class="main-header">
                        <nav class="nav-main mainmenu-nav d-none d-xl-block">
                            <ul class="mainmenu">
                                <li class="{{ request()->routeIs('home') ? 'active' : '' }}">
                                    <a class="nav-link" href="{{ route('home') }}">Home</a>
                                </li>
                                <li class="{{ request()->routeIs('products.*') ? 'active' : '' }}">
                                    <a class="nav-link" href="{{ route('products.index') }}">Products</a>
                                </li>
                                <li class="{{ request()->routeIs('about-us') ? 'active' : '' }}">
                                    <a class="nav-link" href="{{ route('about-us') }}">About Us</a>
                                </li>
                                <li class="{{ request()->routeIs('blog-*') ? 'active' : '' }}">
                                    <a class="nav-link" href="{{ route('blog-list') }}">Blogs</a>
                                </li>
                                <li class="{{ request()->routeIs('contact') ? 'active' : '' }}">
                                    <a class="nav-link" href="{{ route('contact') }}">Contact</a>
                                </li>
                            </ul>
                        </nav>
                        <div class="button-area">
                            <button id="search" class="rts-btn btn-primary-alta"><i class="far fa-search"></i></button>
                            <button id="menu-btn" class="menu rts-btn btn-primary-alta ml--20 ml_sm--5">
                                <div class="icon-slide-container">
                                    <img id="icon-dark" class="menu-icon" src="{{ asset('assets/images/icon/menu.png') }}" alt="Menu">
                                    <div id="icon-light" class="menu-icon logo-wrapper">
                                        <img class="logo-main" src="{{ asset('assets/images/icon/logo(1).svg') }}" alt="Logo" width="50px">
                                    </div>
                                </div>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>

<!-- Mobile Menu Sidebar -->
<div id="side-bar" class="side-bar">
    <button class="close-icon-menu"><i class="far fa-times"></i></button>
    <div class="rts-sidebar-menu-desktop">
        <div class="mb--30">
            <a href="{{ route('home') }}">
                <img src="{{ asset('assets/images/logo/logo10.png') }}" alt="Logo" style="height: 50px;">
            </a>
        </div>
        <div class="body d-none d-xl-block">
            <div class="get-in-touch">
                <div class="h6 title">Get In Touch</div>
                <div class="wrapper">
                    <div class="single">
                        <i class="fas fa-phone-alt"></i>
                        <a href="tel:{{ $site->phone_primary_tel }}">{{ $site->phone_primary }}</a>
                    </div>
                    <div class="single">
                        <i class="fas fa-envelope"></i>
                        <a href="mailto:{{ $site->email }}">{{ $site->email }}</a>
                    </div>
                    <div class="single">
                        <i class="fas fa-map-marker-alt"></i>
                        <a href="#">Indore, M.P.</a>
                    </div>
                </div>
                <div class="social-wrapper-two menu mt--30">
                    @if($site->facebook)<a href="{{ $site->facebook }}" target="_blank" rel="noopener noreferrer"><i class="fab fa-facebook-f"></i></a>@endif
                    @if($site->twitter)<a href="{{ $site->twitter }}" target="_blank" rel="noopener noreferrer"><i class="fab fa-twitter"></i></a>@endif
                    @if($site->instagram)<a href="{{ $site->instagram }}" target="_blank" rel="noopener noreferrer"><i class="fab fa-instagram"></i></a>@endif
                    @if($site->whatsapp_number)<a href="https://wa.me/{{ $site->whatsapp_number }}" target="_blank" rel="noopener noreferrer"><i class="fab fa-whatsapp"></i></a>@endif
                </div>
            </div>
        </div>
        <div class="body-mobile d-block d-xl-none">
            <nav class="nav-main mainmenu-nav">
                <ul class="mainmenu">
                    <li class="menu-item"><a class="menu-link" href="{{ route('home') }}">Home</a></li>
                    <li class="menu-item"><a class="menu-link" href="{{ route('products.index') }}">Products</a></li>
                    <li class="menu-item"><a class="menu-link" href="{{ route('about-us') }}">About Us</a></li>
                    <li class="menu-item"><a class="menu-link" href="{{ route('blog-list') }}">Blog</a></li>
                    <li class="menu-item"><a class="menu-link" href="{{ route('contact') }}">Contact</a></li>
                </ul>
            </nav>
            <div class="social-wrapper-two menu mobile-menu mt--30">
                @if($site->facebook)<a href="{{ $site->facebook }}" target="_blank" rel="noopener noreferrer"><i class="fab fa-facebook-f"></i></a>@endif
                @if($site->twitter)<a href="{{ $site->twitter }}" target="_blank" rel="noopener noreferrer"><i class="fab fa-twitter"></i></a>@endif
                @if($site->instagram)<a href="{{ $site->instagram }}" target="_blank" rel="noopener noreferrer"><i class="fab fa-instagram"></i></a>@endif
                @if($site->whatsapp_number)<a href="https://wa.me/{{ $site->whatsapp_number }}" target="_blank" rel="noopener noreferrer"><i class="fab fa-whatsapp"></i></a>@endif
            </div>
            <a href="{{ route('contact') }}" class="rts-btn btn-primary mt--30 text-center w-100">Get Quote</a>
        </div>
    </div>
</div>

<div class="search-input-area">
    <div class="container">
        <div class="search-input-inner">
            <form action="{{ route('blog-list') }}" method="GET" class="w-100" role="search">
                <div class="input-div">
                    <input id="searchInput1"
                           class="search-input"
                           type="text"
                           name="search"
                           value="{{ request('search') }}"
                           placeholder="Search blogs..."
                           autocomplete="off"
                           aria-label="Search blogs">
                    <button type="submit" aria-label="Submit search"><i class="far fa-search"></i></button>
                </div>
            </form>
        </div>
    </div>
    <div id="close" class="search-close-icon"><i class="far fa-times"></i></div>
</div>

<div id="anywhere-home"></div>

