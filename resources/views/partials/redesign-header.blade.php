{{-- redesign-header.blade.php
     Requirements: 2.12–2.16, 10.2, 10.3, 11.7, 11.8, 12.6, 12.10
     IMPORTANT: request()->routeIs() MUST remain verbatim — no substitution permitted.
     (Requirement 11.7)
--}}
@php
    $social = [
        ['label' => 'Facebook',    'icon' => 'fab fa-facebook-f', 'url' => $site->facebook ?? ''],
        ['label' => 'Instagram',   'icon' => 'fab fa-instagram',  'url' => $site->instagram ?? ''],
        ['label' => 'X (Twitter)', 'icon' => 'fab fa-twitter',    'url' => $site->twitter ?? ''],
        ['label' => 'LinkedIn',    'icon' => 'fab fa-linkedin-in','url' => $site->linkedin ?? ''],
        ['label' => 'WhatsApp',    'icon' => 'fab fa-whatsapp',   'url' => ($site->whatsapp_number ?? '') ? 'https://wa.me/' . $site->whatsapp_number : ''],
    ];
    $mapUrl = 'https://www.google.com/maps/search/?api=1&query=' . urlencode('Konichiwa Chemicals Indore');
@endphp
<header class="redesign-header" role="banner">

    {{-- Top bar — hidden below 575px via CSS; xl-only via Bootstrap --}}
    <div class="rh-top-bar d-xl-block d-none">
        <div class="container">
            <div class="rh-top-inner">
                <a href="mailto:{{ $site->email }}">
                    <i class="fal fa-envelope me-1"></i> {{ $site->email }}
                </a>
                <span>
                    <i class="fas fa-certificate me-1"></i> ISO No.- INQ/AN-25712/129662/1225
                </span>
            </div>
        </div>
    </div>

    {{-- Main nav bar --}}
    <div class="rh-main-bar bg-white">
        <div class="container">
            <div class="row align-items-center">

                {{-- Logo --}}
                <div class="col-xl-3 col-lg-4 col-md-4 col-sm-4 col-4">
                    <div class="rh-logo">
                        <a href="{{ route('home') }}" aria-label="Konichiwa Home">
                            <img src="{{ asset('assets/images/logo/logo10.png') }}"
                                 alt="Konichiwa Chemicals"
                                 style="max-height: 60px;">
                        </a>
                    </div>
                </div>

                {{-- Nav + action buttons --}}
                <div class="col-xl-9 col-lg-8 col-md-8 col-sm-8 col-8">
                    <div class="main-header">

                        {{-- Desktop nav — hidden below xl breakpoint --}}
                        <nav class="rh-nav d-none d-xl-block"
                             role="navigation"
                             aria-label="Main navigation">
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

                        {{-- Action buttons: search + hamburger --}}
                        <div class="rh-actions">
                            <button id="search"
                                    class="rts-btn btn-primary-alta"
                                    aria-label="Open search"
                                    style="padding: 10px 14px;">
                                <i class="far fa-search"></i>
                            </button>
                            <button id="menu-btn"
                                    class="rts-btn btn-primary-alta ml--20 ml_sm--5"
                                    aria-label="Open menu"
                                    aria-expanded="false"
                                    aria-controls="side-bar"
                                    style="padding: 10px 14px;">
                                <div class="icon-slide-container">
                                    <img id="icon-dark"
                                         class="menu-icon"
                                         src="{{ asset('assets/images/icon/menu.png') }}"
                                         alt="">
                                    <div id="icon-light" class="menu-icon logo-wrapper">
                                       <img class="logo-main"
                                            src="{{ asset('assets/images/icon/logo(1).svg') }}"
                                            alt=""
                                            width="26">
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

{{-- Mobile / desktop slide-in sidebar --}}
<div id="side-bar"
     class="side-bar"
     role="dialog"
     aria-label="Navigation menu"
     aria-modal="true">

    <button class="close-icon-menu" aria-label="Close menu">
        <i class="far fa-times"></i>
    </button>

    <div class="rts-sidebar-menu-desktop">

{{-- Logo in sidebar --}}
<div class="rd-side-logo">
    <a href="{{ route('home') }}" aria-label="Konichiwa Home">
        <img src="{{ asset('assets/images/logo/logo10.png') }}" alt="Konichiwa">
    </a>
</div>

{{-- Contact info panel: xl desktop sidebar only --}}
<div class="body d-none d-xl-block">
    <h2 class="rd-side-title">Get In Touch</h2>
    <ul class="rd-contact-list">
        <li>
            <a href="tel:{{ $site->phone_primary_tel }}">
                <span class="rd-contact-icon"><i class="fas fa-phone-alt" aria-hidden="true"></i></span>
                <span>{{ $site->phone_primary }}</span>
            </a>
        </li>
        <li>
            <a href="mailto:{{ $site->email }}">
                <span class="rd-contact-icon"><i class="fas fa-envelope" aria-hidden="true"></i></span>
                <span>{{ $site->email }}</span>
            </a>
        </li>
        <li>
            <a href="{{ $mapUrl }}" target="_blank" rel="noopener noreferrer">
                <span class="rd-contact-icon"><i class="fas fa-map-marker-alt" aria-hidden="true"></i></span>
                <span>Indore, M.P.</span>
            </a>
        </li>
    </ul>
    <div class="rd-social">
        @foreach($social as $s)
            @if($s['url'])
                <a href="{{ $s['url'] }}" target="_blank" rel="noopener noreferrer" aria-label="{{ $s['label'] }}">
                    <i class="{{ $s['icon'] }}" aria-hidden="true"></i>
                </a>
            @endif
        @endforeach
    </div>
</div>
        {{-- Mobile nav links — visible on viewports below xl --}}
        <div class="body-mobile mainmenu-nav d-block d-xl-none">
            <nav role="navigation" aria-label="Mobile navigation">
                <ul class="mainmenu">
                    <li class="menu-item"><a class="menu-link" href="{{ route('home') }}">Home</a></li>
                    <li class="menu-item"><a class="menu-link" href="{{ route('products.index') }}">Products</a></li>
                    <li class="menu-item"><a class="menu-link" href="{{ route('about-us') }}">About Us</a></li>
                    <li class="menu-item"><a class="menu-link" href="{{ route('blog-list') }}">Blogs</a></li>
                    <li class="menu-item"><a class="menu-link" href="{{ route('contact') }}">Contact</a></li>
                </ul>
            </nav>
          <div class="rd-social">
    @foreach($social as $s)
        @if($s['url'])
            <a href="{{ $s['url'] }}" target="_blank" rel="noopener noreferrer" aria-label="{{ $s['label'] }}">
                <i class="{{ $s['icon'] }}" aria-hidden="true"></i>
            </a>
        @endif
    @endforeach
</div>
            {{-- Get Quote CTA — links to contact route (Requirement 10.3) --}}
            <a href="{{ route('contact') }}" class="rts-btn btn-primary mt--30 text-center w-100">Get Quote</a>
        </div>

    </div>
</div>

{{-- Search overlay — GET /blogs?search= (Requirement 11.8)
     Submits GET to route('blog-list') with name="search"
     Toggled open by #search button; closed by #close and #anywhere-home --}}
<div class="search-input-area" role="search" aria-label="Blog search">
    <div class="container">
        <div class="search-input-inner">
            <form action="{{ route('blog-list') }}" method="GET" class="w-100">
                <div class="input-div">
                    <input id="searchInput1"
                           class="search-input"
                           type="text"
                           name="search"
                           value="{{ request('search') }}"
                           placeholder="Search blogs..."
                           autocomplete="off"
                           aria-label="Search blogs">
                    <button type="submit" aria-label="Submit search">
                        <i class="far fa-search"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
    <button id="close" class="search-close-icon" aria-label="Close search">
        <i class="far fa-times"></i>
    </button>
</div>

{{-- Backdrop overlay — main.js uses #anywhere-home to close sidebar and search overlay --}}
<div id="anywhere-home"></div>
