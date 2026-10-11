@extends('layouts.redesign')

@section('title', 'Konichiwa Chemicals Private Limited')

@section('content')

{{-- ═══════════════════════════════════════════════════
     1. Hero Swiper
     .mySwiper.banner-one — preserved for main.js (effect: fade)
     Background images set by style.css (.banner-one .swiper-wrapper .swiper-slide)
     ═══════════════════════════════════════════════════ --}}
<div class="rts-banner-area rts-banner-one">
    <div class="swiper mySwiper banner-one">
        <div class="swiper-wrapper">
            {{-- Slide 1: h1 heading --}}
            <div class="swiper-slide">
                <div class="banner-one-inner text-start">
                    <p class="pre-title"><span>WELCOME!</span> STEP INTO THE FUTURE OF FARMING</p>
                    <h1 class="title">Agricultural Chemical <br>Solutions for Your Farm</h1>
                    <p class="disc banner-para">
                        Lab-tested formulas, supplied in bulk to wholesalers and retailers.
                    </p>
                    <a href="{{ route('products.index')}}" class="rts-btn btn-primary">View Products</a>
                    <img class="shape-img one" src="{{ asset('assets/images') }}/banner/shape/01.png" alt="">
                </div>
            </div>
            {{-- Slide 2: p heading (not h1) --}}
            <div class="swiper-slide two">
                <div class="banner-one-inner text-start">
                    <p class="pre-title"><span>INNOVATION!</span> ADVANCING AGRICULTURAL SCIENCE</p>
                    <p class="title banner-slide-title">Strategic Supply, Expert Sales Support</p>
                    <p class="disc banner-para">
                        On-field salesmen and an inside sales team behind every order.
                    </p>
                    <a href="{{ route('contact')}}" class="rts-btn btn-primary">Contact Us</a>
                    <img class="shape-img one" src="{{ asset('assets/images') }}/banner/shape/01.png" alt="">
                </div>
            </div>
            {{-- Slide 3: p heading (not h1) --}}
            <div class="swiper-slide three">
                <div class="banner-one-inner text-start">
                    <p class="pre-title"><span>QUALITY!</span> HIGH-YIELD FORMULAS</p>
                    <p class="title banner-slide-title">High-Yield Formulas You Can Trust</p>
                    <p class="disc banner-para">
                        Quality-checked products with dedicated bulk pricing.
                    </p>
                    <a href="{{ route('products.index')}}" class="rts-btn btn-primary">View Products</a>
                    <img class="shape-img one" src="{{ asset('assets/images') }}/banner/shape/01.png" alt="">
                </div>
            </div>
        </div>
        <div class="swiper-pagination"></div>
    </div>
    <div class="animation-img">
        <img class="shape-img two" src="{{ asset('assets/images') }}/banner/shape/02.png" alt="">
        <img class="shape-img three" src="{{ asset('assets/images') }}/banner/shape/03.png" alt="">
    </div>
</div>

{{-- ═══════════════════════════════════════════════════
     2. About
     ═══════════════════════════════════════════════════ --}}
<div class="rts-about-area rts-section-gap bg-about-sm-shape wow fadeInUp" data-wow-delay="0.1s">
    <div class="container">
        <div class="row g-5 align-items-center">
            <!-- about left -->
            <div class="col-lg-6 col-md-12 col-sm-12 col-12 order-lg-1 order-md-1 order-sm-1 order-1 mt_md--50 mt_sm--50 ">
                <div class="rts-title-area">
                    <p class="pre-title">
                        MORE ABOUT KONICHIWA
                    </p>
                    <h2 class="title">We Provide Best Agricultural Chemical Solutions for Your Farm</h2>
                </div>
                <div class="about-inner">
                    <p class="disc">
                        At KONICHIWA, we are dedicated to transforming agriculture through scientific innovation. We provide high-quality fertilizers, pesticides, and growth enhancers that help farmers maximize their yield while ensuring long-term soil health. Our mission is to support the global food supply by protecting crops with precision-engineered chemistry.
                    </p>
                    <!-- start about success area -->
                    <div class="row about-success-wrapper">
                        <!-- left wrapper start -->
                        <div class="col-lg-6 col-md-6">
                            <div class="single">
                                <i class="far fa-check"></i>
                                <p class="details">High-Yield Chemical Formulas</p>
                            </div>
                            <div class="single">
                                <i class="far fa-check"></i>
                                <p class="details">Lab-Tested Quality Assurance</p>
                            </div>
                            <div class="single">
                                <i class="far fa-check"></i>
                                <p class="details">Advanced Crop Protection</p>
                            </div>
                        </div>
                        <!-- left wrapper end -->
                        <div class="col-lg-6 col-md-6">
                            <div class="single">
                                <i class="far fa-check"></i>
                                <p class="details">Bulk Order Fulfillment
                                </p>
                            </div>
                            <div class="single">
                                <i class="far fa-check"></i>
                                <p class="details">Dedicated Wholesaler Pricing</p>
                            </div>
                            <div class="single">
                                <i class="far fa-check"></i>
                                <p class="details">Fast & Secure Bulk Delivery</p>
                            </div>
                        </div>
                    </div>
                    <!-- start about success area -->

                    <!-- about founder & get in touch start -->
                    <div class="row about-founder-wrapper align-items-center mt--40">
                        <!-- left area start -->
                        {{-- <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                            <div class="author-inner">
                                <a class="thumbnail" href="#"><img src="{{ asset('assets/images') }}/about/sm-01.png" alt="finbiz_founder"></a>
                                <div class="founder-details">
                                    <a href="team-details.html">
                                        <h6 class="title">Adrew David</h6>
                                    </a>
                                    <span>CEO & Founder</span>
                                </div>
                            </div>
                        </div> --}}
                        <!-- left area end -->
                        <!-- right founder area -->
                        <div class="col-lg-6 col-md-6 col-sm-6 col-12 mt_sm--20">
                            <div class="author-call-option">
                                <img class="authore-call" src="{{ asset('assets/images') }}/about/call.svg" alt="call_founder">
                                <div class="call-details">
                                    <span>Call us anytime</span>
                                    <a href="tel:{{ $site->phone_primary_tel }}">
                                        <h6 class="title">{{ $site->phone_primary }}</h6>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <!-- left founder area -->
                    </div>
                    <!-- about founder & get in touch end -->
                </div>
            </div>
            <!-- about right -->

            <!-- about-right Start-->
            <div class="col-lg-6 col-md-12 col-sm-12 col-12 order-lg-2 order-md-2 order-sm-2 order-2 mt--40">
                <div class="about-one-thumbnail">
                   <img src="{{ asset('assets/redesign/images/about-farmer.webp') }}" alt="Farmer inspecting a young soybean plant in the field" width="1600" height="1065" loading="lazy">
                    {{-- <img class="small-img" src="{{ asset('assets/images') }}/about/main/about-02.png" alt="finbiz-small"> --}}
                    {{-- <div class="experience">
                        <div class="left single">
                            <h2 class="title">25+</h2>
                            <p class="time">Years</p>
                        </div>
                        <div class="right single">
                            <p class="disc">
                                Of experience
                                in consulting
                                service
                            </p>
                        </div>
                    </div> --}}
                </div>
            </div>
            <!-- about-right end -->
        </div>
    </div>
</div>
<!-- rts about us section end -->

<!-- rts service post area  Start-->
{{-- <div class="rts-service-area rts-section-gapBottom">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="rts-title-area service text-center">
                    <p class="pre-title">
                        Our Services
                    </p>
                    <h2 class="title">High Quality Services</h2>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid service-main plr--120-service mt--50 plr_md--0 pl_sm--0 pr_sm--0">
        <div class="background-service row">
            <!-- start single Service -->
            <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 col-12">
                <div class="service-one-inner one">
                    <div class="thumbnail">
                        <img src="{{ asset('assets/images') }}/service/icon/01.svg" alt="finbiz_service">
                    </div>
                    <div class="service-details">
                        <a href="#">
                            <h5 class="title">Business Planning</h5>
                        </a>
                        <p class="disc">
                            Sagitis himos pulvinar morb socis laoreet posuere enim non auctor etiam pretium libero
                        </p>
                        <a class="rts-read-more btn-primary" href="#"><i
                                class="far fa-arrow-right"></i>Read
                            More</a>
                    </div>
                </div>
            </div>
            <!-- end single Services -->
            <!-- start single Service -->
            <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 col-12">
                <div class="service-one-inner two">
                    <div class="thumbnail">
                        <img src="{{ asset('assets/images') }}/service/icon/02.svg" alt="finbiz_service">
                    </div>
                    <div class="service-details">
                        <a href="#">
                            <h5 class="title">Develop Process</h5>
                        </a>
                        <p class="disc">
                            Sagitis himos pulvinar morb socis laoreet posuere enim non auctor etiam pretium libero
                        </p>
                        <a class="rts-read-more btn-primary" href="#"><i
                                class="far fa-arrow-right"></i>Read
                            More</a>
                    </div>
                </div>
            </div>
            <!-- end single Services -->
            <!-- start single Service -->
            <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 col-12">
                <div class="service-one-inner three">
                    <div class="thumbnail">
                        <img src="{{ asset('assets/images') }}/service/icon/03.svg" alt="finbiz_service">
                    </div>
                    <div class="service-details">
                        <a href="#">
                            <h5 class="title">Strategy & Planning</h5>
                        </a>
                        <p class="disc">
                            Sagitis himos pulvinar morb socis laoreet posuere enim non auctor etiam pretium libero
                        </p>
                        <a class="rts-read-more btn-primary" href="#"><i
                                class="far fa-arrow-right"></i>Read
                            More</a>
                    </div>
                </div>
            </div>
            <!-- end single Services -->
            <!-- start single Service -->
            <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 col-12">
                <div class="service-one-inner four">
                    <div class="thumbnail">
                        <img src="{{ asset('assets/images') }}/service/icon/04.svg" alt="finbiz_service">
                    </div>
                    <div class="service-details">
                        <a href="#">
                            <h5 class="title">Business Support</h5>
                        </a>
                        <p class="disc">
                            Sagitis himos pulvinar morb socis laoreet posuere enim non auctor etiam pretium libero
                        </p>
                        <a class="rts-read-more btn-primary" href="#"><i
                                class="far fa-arrow-right"></i>Read
                            More</a>
                    </div>
                </div>
            </div>
            <!-- end single Services -->
            <!-- start single Service -->
            <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 col-12">
                <div class="service-one-inner five">
                    <div class="thumbnail">
                        <img src="{{ asset('assets/images') }}/service/icon/05.svg" alt="finbiz_service">
                    </div>
                    <div class="service-details">
                        <a href="#">
                            <h5 class="title">Audit & Evaluation</h5>
                        </a>
                        <p class="disc">
                            Sagitis himos pulvinar morb socis laoreet posuere enim non auctor etiam pretium libero
                        </p>
                        <a class="rts-read-more btn-primary" href="#"><i
                                class="far fa-arrow-right"></i>Read
                            More</a>
                    </div>
                </div>
            </div>
            <!-- end single Services -->
            <!-- start single Service -->
            <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 col-12">
                <div class="service-one-inner six">
                    <div class="thumbnail">
                        <img src="{{ asset('assets/images') }}/service/icon/06.svg" alt="finbiz_service">
                    </div>
                    <div class="service-details">
                        <a href="#">
                            <h5 class="title">Consultancy & Advice</h5>
                        </a>
                        <p class="disc">
                            Sagitis himos pulvinar morb socis laoreet posuere enim non auctor etiam pretium libero
                        </p>
                        <a class="rts-read-more btn-primary" href="#"><i
                                class="far fa-arrow-right"></i>Read
                            More</a>
                    </div>
                </div>
            </div>
            <!-- end single Services -->
        </div>
        <div class="row">
            <div class="cta-one-bg col-12">
                <div class="cta-one-inner">
                    <div class="cta-left">
                        <h3 class="title">Let's discuss how we can help make your <br>
               agriculture business more profitable</h3>
                    </div>
                    <div class="cta-right">
                        <a class="rts-btn btn-white" href="{{ route('contact')}}">Lets Work Together</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> --}}
<!-- rts service post area ENd -->

<!-- ── OUR SALES & LOGISTICS POWER section ── -->
<section id="sales-logistics-section" class="rts-sales-logistics-area rts-section-gap wow fadeInUp" data-wow-delay="0.1s">
    <div class="container">
        <div class="row g-5 align-items-center">
            <!-- Left: Interactive Photo with Floating Stats Badge -->
            <div class="col-lg-6 col-md-12 order-lg-1 order-2">
                <div class="sl-visual-wrapper">
                    <div class="sl-image-wrap">
                        <img src="{{ asset('assets/redesign/images/business-goal-handshake.jpg') }}"
                             alt="Konichiwa sales representative shaking hands with a farmer in the field"
                             loading="lazy"
                             class="sl-main-img">
                        
                        <!-- Floating Glassmorphism Stat Badge -->
                        <div class="sl-floating-badge top-right">
                            <div class="sl-badge-icon">
                                <i class="fas fa-shipping-fast" aria-hidden="true"></i>
                            </div>
                            <div class="sl-badge-text">
                                <strong class="sl-badge-number">100%</strong>
                                <span class="sl-badge-label">On-Time Supply</span>
                            </div>
                        </div>

                        <!-- Floating Verified Network Badge -->
                        <div class="sl-floating-badge bottom-left">
                            <div class="sl-badge-icon pulse-red">
                                <i class="fas fa-users" aria-hidden="true"></i>
                            </div>
                            <div class="sl-badge-text">
                                <strong class="sl-badge-number">500+</strong>
                                <span class="sl-badge-label">Trusted Partners</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right: Content & Interactive Feature Cards -->
            <div class="col-lg-6 col-md-12 order-lg-2 order-1">
                <div class="sl-content-wrap">
                    <div class="rts-title-area text-start">
                        <span class="sl-pill-tag">OUR SALES &amp; LOGISTICS POWER</span>
                        <h2 class="title">Strategic Supply &amp; Expert<br>Sales Support for Your Business</h2>
                        <p class="sl-lead-desc">
                            Empowering agricultural retailers, distributors, and farmers with an agile supply network, on-demand field guidance, and guaranteed stock availability.
                        </p>
                    </div>

                    <div class="sl-goals-cards mt--25">
                        <!-- Card 1 -->
                        <div class="sl-card-item">
                            <div class="sl-card-icon-box">
                                <img src="{{ asset('assets/images/business-goal/icon/01.svg') }}" alt="On-Field Sales" width="54" height="54">
                            </div>
                            <div class="sl-card-body">
                                <h5 class="sl-card-title">On-Field &amp; Inside Sales Support</h5>
                                <p class="sl-card-text">
                                    Our dedicated agronomists and on-field representatives visit <strong class="text-primary">your location</strong> with expert crop guidance, while inside sales executives <strong class="text-primary">process orders</strong> and resolve inquiries instantly.
                                </p>
                            </div>
                        </div>

                        <!-- Card 2 -->
                        <div class="sl-card-item">
                            <div class="sl-card-icon-box">
                                <img src="{{ asset('assets/images/business-goal/icon/02.svg') }}" alt="Wholesale Distribution" width="54" height="54">
                            </div>
                            <div class="sl-card-body">
                                <h5 class="sl-card-title">Seamless Wholesale &amp; Bulk Logistics</h5>
                                <p class="sl-card-text">
                                    We coordinate end-to-end transport for bulk agricultural chemicals, ensuring state-compliant carriage, secure delivery, and guaranteed uninterrupted inventories for dealers.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="sl-btn-wrap mt--35 d-flex align-items-center gap-3 flex-wrap">
                        <a href="{{ route('contact') }}" class="rts-btn btn-primary" id="sl-contact-btn">
                            Get in Touch <i class="fas fa-arrow-right ms-2" aria-hidden="true"></i>
                        </a>
                        <a href="tel:{{ $site->phone_primary_tel ?? '+919999999999' }}" class="sl-phone-cta">
                            <span class="phone-icon-wrap"><i class="fas fa-phone-alt" aria-hidden="true"></i></span>
                            <span class="phone-cta-text">
                                <small>Call Support</small>
                                <strong>{{ $site->phone_primary ?? '+91 99999 99999' }}</strong>
                            </span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- ── end OUR SALES & LOGISTICS POWER section ── -->
<!-- business goal area End -->

{{-- <!-- rts-counter up area start -->
<div class="rts-counter-up-area rts-section-gap counter-bg">
    <div class="container">
        <div class="row">
            <!-- counter up area -->
            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                <div class="single-counter">
                    <img src="{{ asset('assets/images') }}/counterup/icon/01.svg" alt="Business_counter">
                    <div class="counter-details">
                        <h2 class="title"><span class="counter animated fadeInDownBig">100+</span></h2>
                        <p class="disc">Products</p>
                    </div>
                </div>
            </div>
            <!-- counter up area -->
            <!-- counter up area -->
            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                <div class="single-counter pl--10 justify-content-center two pl--30">
                    <img src="{{ asset('assets/images') }}/counterup/icon/02.svg" alt="Business_counter">
                    <div class="counter-details">
                        <h2 class="title"><span class="counter animated fadeInDownBig">50+</span></h2>
                        <p class="disc">Media Activities</p>
                    </div>
                </div>
            </div>
            <!-- counter up area -->
            <!-- counter up area -->
            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                <div class="single-counter pl--10 justify-content-center three pl--50">
                    <img src="{{ asset('assets/images') }}/counterup/icon/03.svg" alt="Business_counter">
                    <div class="counter-details">
                        <h2 class="title"><span class="counter animated fadeInDownBig">50+</span></h2>
                        <p class="disc">Sales Experts</p>
                    </div>
                </div>
            </div>
            <!-- counter up area -->
            <!-- counter up area -->
            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                <div class="single-counter pl--10 justify-content-end four">
                    <img src="{{ asset('assets/images') }}/counterup/icon/04.svg" alt="Business_counter">
                    <div class="counter-details">
                        <h2 class="title happy"><span class="counter animated fadeInDownBig">500+</span></h2>
                        <p class="disc">Happy Clients</p>
                    </div>
                </div>
            </div>
            <!-- counter up area -->
        </div>
    </div>
</div>
<!-- rts-counter up area end --> --}}

<!-- start gallery/popular products section -->
<section class="rts-gallery-area rts-section-gap popular-products-redesign wow fadeInUp" data-wow-delay="0.1s" id="popular-products">
    <div class="container">
        <div class="row mb--35">
            <div class="col-12">
                <div class="rts-title-area gallery text-start">
                    <p class="pre-title">POPULAR PRODUCTS</p>
                    <h2 class="title">Our Popular Products</h2>
                </div>
            </div>
        </div>
        <div class="row g-4 align-items-stretch">
            <!-- Left Card: Image Placed -->
            <div class="col-lg-6 col-12 d-flex">
                <div class="popular-product-card card-image-left w-100">
                    <div class="popular-img-wrapper">
                        <img src="{{ asset('assets/redesign/images/products-hero.png') }}" 
                             alt="Konichiwa Popular Agricultural Chemical Products" 
                             class="popular-hero-img">
                    </div>
                </div>
            </div>

            <!-- Right Card: All Data Present in Konichiwa Brand Red Theme -->
            <div class="col-lg-6 col-12 d-flex">
                <div class="popular-product-card card-data-right w-100">
                    <div class="card-data-header">
                        <div class="data-icon-box">
                            <img src="{{ asset('assets/images/gallery/icon/01.svg') }}" alt="Pro Business Icon" class="data-icon">
                        </div>
                        <div class="data-header-text">
                            <h3 class="data-title">Pro Business</h3>
                            <span class="data-subtitle">Case Study, Growth</span>
                        </div>
                    </div>

                    <p class="data-desc">
                        In the literal sense, the term "Business" means the state of being busy. But it is a very wide connotation of business — delivering high-grade chemical efficacy, superior crop health, and unmatched return on investment for agricultural enterprises.
                    </p>

                    <div class="data-features-list">
                        <div class="data-feature-item">
                            <div class="feature-badge">
                                <i class="far fa-check"></i>
                            </div>
                            <div class="feature-info">
                                <h5 class="feature-title">High-Efficacy Agro-Chemicals</h5>
                                <p class="feature-text">Formulated with lab-tested purity to protect against weeds, pests, and fungal diseases.</p>
                            </div>
                        </div>

                        <div class="data-feature-item">
                            <div class="feature-badge">
                                <i class="far fa-check"></i>
                            </div>
                            <div class="feature-info">
                                <h5 class="feature-title">Wholesale &amp; Retail Supply</h5>
                                <p class="feature-text">Consistent inventory availability and compliant logistics for distributors and retailers.</p>
                            </div>
                        </div>

                        <div class="data-feature-item">
                            <div class="feature-badge">
                                <i class="far fa-check"></i>
                            </div>
                            <div class="feature-info">
                                <h5 class="feature-title">Complete Crop Yield Boost</h5>
                                <p class="feature-text">Scientifically balanced nutrients to ensure stronger roots and heavier harvest yields.</p>
                            </div>
                        </div>
                    </div>

                    <div class="data-action-footer">
                        <a href="{{ route('products.index') }}" class="rts-btn btn-primary btn-popular-view">
                            View Products <i class="fas fa-arrow-right ms-2" aria-hidden="true"></i>
                        </a>
                        <a href="{{ route('contact') }}" class="btn-popular-inquire">
                            Inquire Bulk Supply &rarr;
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- end gallery/popular products section -->

<!-- start trusted client section -->
<!-- <div class="rts-trusted-client rts-section-gapBottom">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="title-area-client text-center">
                    <p class="client-title">Our Trusted Clients</p>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="client-wrapper-one">
                <a href="#"><img src="{{ asset('assets/images') }}/client/01.png" alt="business_finbiz"></a>
                <a href="#"><img src="{{ asset('assets/images') }}/client/02.png" alt="business_finbiz"></a>
                <a href="#"><img src="{{ asset('assets/images') }}/client/03.png" alt="business_finbiz"></a>
                <a href="#"><img src="{{ asset('assets/images') }}/client/04.png" alt="business_finbiz"></a>
                <a href="#"><img src="{{ asset('assets/images') }}/client/05.png" alt="business_finbiz"></a>
                <a href="#"><img src="{{ asset('assets/images') }}/client/06.png" alt="business_finbiz"></a>
            </div>
        </div>
    </div>
</div> -->
<!-- end trusted client section -->

<!-- start team section -->
<!-- <div class="rts-team-area rts-section-gap bg-team">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="rts-title-area team text-center">
                    <p class="pre-title">
                        Professionals Team
                    </p>
                    <h2 class="title">Professionals Team</h2>
                </div>
            </div>
        </div>
        <div class="row g-5 mt--0">

            <div class="swiper mySwiperh1_team">
                <div class="swiper-wrapper">
                    <div class="swiper-slide">
                        <div class="team-single-one-start">
                            <div class="team-image-area">
                                <a href="team-details.html">
                                    <img src="{{ asset('assets/images') }}/team/tm/01.jpg" alt="Business_Team_single">
                                    <div class="team-social">
                                        <div class="main">
                                            <i class="fal fa-plus"></i>
                                        </div>
                                        <div class="team-social-one">
                                            <i class="fab fa-youtube"></i>
                                            <i class="fab fa-twitter"></i>
                                            <i class="fab fa-instagram"></i>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="single-details">
                                <a href="team-details.html">
                                    <h5 class="title">Archer Graham</h5>
                                </a>
                                <p>Founder</p>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="team-single-one-start">
                            <div class="team-image-area">
                                <a href="team-details.html">
                                    <img src="{{ asset('assets/images') }}/team/tm/02.jpg" alt="Business_Team_single">
                                    <div class="team-social">
                                        <div class="main">
                                            <i class="fal fa-plus"></i>
                                        </div>
                                        <div class="team-social-one">
                                            <i class="fab fa-youtube"></i>
                                            <i class="fab fa-twitter"></i>
                                            <i class="fab fa-instagram"></i>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="single-details">
                                <a href="team-details.html">
                                    <h5 class="title">Amelia Clover</h5>
                                </a>
                                <p>Co-Founder</p>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="team-single-one-start">
                            <div class="team-image-area">
                                <a href="team-details.html">
                                    <img src="{{ asset('assets/images') }}/team/tm/03.jpg" alt="Business_Team_single">
                                    <div class="team-social">
                                        <div class="main">
                                            <i class="fal fa-plus"></i>
                                        </div>
                                        <div class="team-social-one">
                                            <i class="fab fa-youtube"></i>
                                            <i class="fab fa-twitter"></i>
                                            <i class="fab fa-instagram"></i>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="single-details">
                                <a href="team-details.html">
                                    <h5 class="title">Beckett Hayden</h5>
                                </a>
                                <p>Deputy Manager</p>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="team-single-one-start">
                            <div class="team-image-area">
                                <a href="team-details.html">
                                    <img src="{{ asset('assets/images') }}/team/tm/04.jpg" alt="Business_Team_single">
                                    <div class="team-social">
                                        <div class="main">
                                            <i class="fal fa-plus"></i>
                                        </div>
                                        <div class="team-social-one">
                                            <i class="fab fa-youtube"></i>
                                            <i class="fab fa-twitter"></i>
                                            <i class="fab fa-instagram"></i>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="single-details">
                                <a href="team-details.html">
                                    <h5 class="title">Julian Wyat</h5>
                                </a>
                                <p>Finance Manager</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> -->
<!-- end team section -->

<!-- ── BOOST YOUR AGRI-BUSINESS TODAY section ── -->
<section class="rts-feature-area rts-section-gap agri-boost-section wow fadeInUp" data-wow-delay="0.1s" id="agri-business-boost">
    <div class="container">
        <div class="agri-boost-card">
            <div class="row g-5 align-items-center">
                <!-- Left: Warehouse & Logistics Image with floating stat badge -->
                <div class="col-lg-6 col-12">
                    <div class="agri-boost-image-wrap">
                        <img src="{{ asset('assets/redesign/images/features-warehouse.jpg') }}" 
                             alt="Warehouse worker and farmer with agricultural chemical supplies"
                             class="agri-boost-img"
                             loading="lazy">
                        
                        <!-- Floating Inventory Guarantee Badge -->
                        <div class="agri-floating-guarantee">
                            <div class="guarantee-icon">
                                <i class="fas fa-boxes-stacked" aria-hidden="true"></i>
                            </div>
                            <div class="guarantee-info">
                                <span class="guarantee-val">99.8%</span>
                                <span class="guarantee-txt">Ready Stock Fulfillment</span>
                            </div>
                        </div>

                        <!-- Floating Certification Tag -->
                        <div class="agri-floating-tag">
                            <i class="fas fa-certificate text-danger me-2" aria-hidden="true"></i>
                            <span>Lab-Tested &amp; Certified</span>
                        </div>
                    </div>
                </div>

                <!-- Right: Content, Feature Highlights & Action -->
                <div class="col-lg-6 col-12">
                    <div class="agri-boost-content">
                        <span class="agri-badge-pill">BOOST YOUR AGRI-BUSINESS TODAY</span>
                        <h2 class="agri-title">Most Reliable Partner for <br><span class="text-primary-gradient">Bulk Chemical Supply</span></h2>
                        <p class="agri-desc">
                            Konichiwa empowers wholesalers and retailers with a high-performance product portfolio and a dedicated sales network. Whether through our expert on-field salesmen or responsive inside sales team, we ensure your inventory is always stocked with lab-tested, high-yield solutions that farmers trust.
                        </p>

                        <!-- Feature Badges Grid -->
                        <div class="agri-features-grid mt-4">
                            <div class="agri-feature-item">
                                <div class="agri-feat-icon">
                                    <i class="fas fa-chart-line" aria-hidden="true"></i>
                                </div>
                                <div class="agri-feat-text">
                                    <strong>High-Margin Wholesale Rates</strong>
                                    <p>Maximized profitability for bulk dealers and distributors.</p>
                                </div>
                            </div>

                            <div class="agri-feature-item">
                                <div class="agri-feat-icon">
                                    <i class="fas fa-headset" aria-hidden="true"></i>
                                </div>
                                <div class="agri-feat-text">
                                    <strong>Inside Sales Assistance</strong>
                                    <p>Fast dispatch handling and instant quotation support.</p>
                                </div>
                            </div>

                            <div class="agri-feature-item">
                                <div class="agri-feat-icon">
                                    <i class="fas fa-user-tie" aria-hidden="true"></i>
                                </div>
                                <div class="agri-feat-text">
                                    <strong>Dedicated On-Field Support</strong>
                                    <p>Agronomists visiting your stores and farms directly.</p>
                                </div>
                            </div>

                            <div class="agri-feature-item">
                                <div class="agri-feat-icon">
                                    <i class="fas fa-truck-ramp-box" aria-hidden="true"></i>
                                </div>
                                <div class="agri-feat-text">
                                    <strong>Verified Bulk Distribution</strong>
                                    <p>Safe transport compliance and uninterrupted supply.</p>
                                </div>
                            </div>
                        </div>

                        <!-- Action Bar -->
                        <div class="agri-action-bar mt-4 pt-2 d-flex align-items-center gap-3 flex-wrap">
                            <a href="{{ route('contact') }}" class="rts-btn btn-primary btn-agri-action">
                                Become a Distributor <i class="fas fa-arrow-right ms-2" aria-hidden="true"></i>
                            </a>
                            <a href="{{ route('products.index') }}" class="btn-agri-catalog">
                                Explore Chemical Portfolio &rarr;
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- ── end BOOST YOUR AGRI-BUSINESS TODAY section ── -->

<!-- start client feed back section -->
<!-- <div class="rts-client-feedback">
    <div class="container">
        <div class="row">
           
            <div class="col-lg-7">
                <div class="rts-title-area reviews text-start pl--30 pt--70">
                    <p class="pre-title">
                        Our Testimonials
                    </p>
                    <h2 class="title">Client's Feedbacks</h2>

                   
                    <div class="swiper mySwipertestimonial">
                        <div class="swiper-wrapper">
                            <div class="swiper-slide">
                                <div class="testimonial-inner">
                                    <p class="disc text-start">
                                        "Consectetur adipiscing elit velit porta sapien purus erat nec, a ornare
                                        laoreet sem gravida accumsan in commodo aliquet nascetur maecenas. Sem
                                        tempus hendrerit diam mauris leo magna sociosqu viverra, accumsan massa
                                        tristique egestas cum sodales hac lacinia feugiat scelerisque porttitor"
                                    </p>
                                    <div class="testimonial-bottom-one">
                                        <div class="thumbnail">
                                            <img src="{{ asset('assets/images') }}/testimonials/02.png" alt="business-testimonials">
                                        </div>
                                        <div class="details">
                                            <a href="#">
                                                <h5 class="title">David Smith</h5>
                                            </a>
                                            <span>JHKL Owner</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="swiper-slide">
                                <div class="testimonial-inner">
                                    <p class="disc text-start">
                                        "Consectetur adipiscing elit velit porta sapien purus erat nec, a ornare
                                        laoreet sem gravida accumsan in commodo aliquet nascetur maecenas. Sem
                                        tempus hendrerit diam mauris leo magna sociosqu viverra, accumsan massa
                                        tristique egestas cum sodales hac lacinia feugiat scelerisque porttitor"
                                    </p>
                                    <div class="testimonial-bottom-one">
                                        <div class="thumbnail">
                                            <img src="{{ asset('assets/images') }}/testimonials/02.png" alt="business-testimonials">
                                        </div>
                                        <div class="details">
                                            <a href="#">
                                                <h5 class="title">David Smith</h5>
                                            </a>
                                            <span>JHKL Owner</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="swiper-slide">
                                <div class="testimonial-inner">
                                    <p class="disc text-start">
                                        "Consectetur adipiscing elit velit porta sapien purus erat nec, a ornare
                                        laoreet sem gravida accumsan in commodo aliquet nascetur maecenas. Sem
                                        tempus hendrerit diam mauris leo magna sociosqu viverra, accumsan massa
                                        tristique egestas cum sodales hac lacinia feugiat scelerisque porttitor"
                                    </p>
                                    <div class="testimonial-bottom-one">
                                        <div class="thumbnail">
                                            <img src="{{ asset('assets/images') }}/testimonials/02.png" alt="business-testimonials">
                                        </div>
                                        <div class="details">
                                            <a href="#">
                                                <h5 class="title">David Smith</h5>
                                            </a>
                                            <span>JHKL Owner</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="swiper-button-next"></div>
                        <div class="swiper-button-prev"></div>
                    </div>
                   
                </div>
            </div>
        
            <div class="col-lg-5">
                <div class="rts-test-one-image-inner">
                    <img src="{{ asset('assets/images') }}/testimonials/01.png" alt="business_testimobials">
                </div>
            </div>
          
        </div>
    </div>
</div> -->
<!-- start client feed back section End -->

{{-- Farmer Stories Section --}}
@include('farmer-stories.section')
{{-- Farmer Stories Section End --}}

<!-- blog area start -->
<div class="rts-blog-area rts-section-gap bg-secondary wow fadeInUp" data-wow-delay="0.1s">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="title-area text-center">
                    <span>Blog Posts</span>
                    <h2 class="title">News & Updates</h2>
                </div>
            </div>
        </div>
        <div class="g-5 mt--20">
            <div class="swiper mySwiperh1_blog">
                <div class="swiper-wrapper">
                    @forelse($blogs as $blog)
                    <div class="swiper-slide">
                        <div class="single-blog-one-wrapper">
                            <div class="thumbnail">
                                <img src="{{ $blog->image ? asset($blog->image) : asset('assets/images/blog/blog6.webp') }}" alt="{{ $blog->title }}">
                                <div class="blog-badge">
                                    <span>{{ $blog->created_at->format('d.m.Y') }}</span>
                                </div>
                            </div>
                            <div class="blog-content">
                                <p><span>{{ $blog->category ?? 'Business' }} </span>/ by {{ $blog->author ?? 'Admin' }}</p>
                                <a href="{{ route('blog-details', $blog->slug) }}">
                                    <h5 class="title">{{ $blog->title }}</h5>
                                </a>
                                <a class="rts-read-more btn-primary" href="{{ route('blog-details', $blog->slug) }}"><i
                                        class="far fa-arrow-right"></i>Read More</a>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="swiper-slide text-center">
                        <p>No blog posts found.</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
<!-- blog area end -->

{{-- ═══════════════════════════════════════════════════
     Contact CTA — inline form replaced with shared partial
     showPhone=false, showSubject=true (name/email/subject/message)
     Posts to route('contact.store') — confirmed by user (Req 5.5)
     ═══════════════════════════════════════════════════ --}}
<div class="rts-contact-area contact-one wow fadeInUp" data-wow-delay="0.1s" id="home-contact-section">
    <div class="container">
        <div class="row align-items-center g-0">
            <div class="col-lg-4 col-md-12 col-sm-12 col-12 mt--40">
                <div class="contact-image-one">
                    <img src="{{ asset('assets/images') }}/contact/25.webp" alt="Konichiwa Support Team">
                </div>
            </div>
            <div class="col-lg-8 col-md-12 col-sm-12 col-12">
                <div class="contact-form-area-one">
                    <div class="rts-title-area contact text-start">
                        <p class="pre-title">
                            GET IN TOUCH WITH KONICHIWA
                        </p>
                        <h2 class="title">Partner with us for bulk supply</h2>
                    </div>
                    @include('partials.contact-form', [
                        'showPhone'   => false,
                        'showSubject' => true,
                        'showGst' => true
                    ])
                </div>
            </div>
        </div>
    </div>
</div>
<!-- contact area end -->

<!-- ── Modern Contact & Location Map Section ── -->
<section class="rts-map-area modern-location-section wow fadeInUp" data-wow-delay="0.1s" id="company-location">
    <div class="container">
        <div class="location-main-card">
            <div class="row g-5 align-items-center">
                <!-- Left: Structured Contact & Office Details -->
                <div class="col-lg-6 col-12">
                    <div class="location-details-wrapper">
                        <span class="location-pill-badge">OUR PRESENCE</span>
                        <h2 class="location-heading">Visit Our Corporate &amp; Regional Offices</h2>
                        <p class="location-desc">
                            Connect directly with our sales, technical, and distribution teams across India.
                        </p>

                        <div class="office-cards-list mt-4">
                            <!-- Direct Inquiries Card -->
                            <div class="office-info-card">
                                <div class="office-card-icon">
                                    <i class="fas fa-headset" aria-hidden="true"></i>
                                </div>
                                <div class="office-card-body">
                                    <span class="office-type-tag">Work &amp; General Inquiries</span>
                                    <div class="office-contact-links mt-1">
                                        <a href="tel:{{ $site->phone_primary_tel }}" class="contact-highlight-link">
                                            <i class="fas fa-phone-alt me-2" aria-hidden="true"></i>{{ $site->phone_primary }}
                                        </a>
                                        <a href="mailto:{{ $site->email }}" class="contact-highlight-link">
                                            <i class="fas fa-envelope me-2" aria-hidden="true"></i>{{ $site->email }}
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <!-- Registered Office Card -->
                            <div class="office-info-card">
                                <div class="office-card-icon">
                                    <i class="fas fa-building" aria-hidden="true"></i>
                                </div>
                                <div class="office-card-body">
                                    <span class="office-type-tag">Registered Corporate Office</span>
                                    <p class="office-address-text">
                                        {{ $site->address_reg }}
                                    </p>
                                </div>
                            </div>

                            <!-- Regional Office Card -->
                            <div class="office-info-card">
                                <div class="office-card-icon">
                                    <i class="fas fa-map-marker-alt" aria-hidden="true"></i>
                                </div>
                                <div class="office-card-body">
                                    <span class="office-type-tag">Regional Office</span>
                                    <p class="office-address-text">
                                        {{ $site->address_region }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right: Sleek Interactive Google Maps Frame -->
                <div class="col-lg-6 col-12">
                    <div class="location-map-wrapper">
                        <div class="map-inner-frame">
                            <iframe src="{{ $site->map_embed_url }}" 
                                    width="100%" 
                                    height="460" 
                                    style="border:0;" 
                                    allowfullscreen="" 
                                    loading="lazy" 
                                    referrerpolicy="no-referrer-when-downgrade"
                                    title="Konichiwa Office Location Map"></iframe>
                        </div>
                        <div class="map-floating-overlay">
                            <div class="overlay-pin">
                                <i class="fas fa-map-pin text-danger me-2" aria-hidden="true"></i>
                                <span>Innov8 DLF Cyber Green &bull; Gurugram</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- ── end Modern Contact & Location Map Section ── -->

@endsection

@push('scripts')
<script src="{{ asset('assets/redesign/js/home.js') }}"></script>
@endpush
