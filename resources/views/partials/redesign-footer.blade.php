{{-- redesign-footer.blade.php
     Requirements: 2.10, 2.11, 2.17–2.20, 10.4–10.6, 11.6, 12.10
     $footerPosts is injected by View::composer in AppServiceProvider — no raw DB query here.
     Scripts MUST be in the exact order below — no duplicates, no reordering.
     (Requirements 2.10, 2.11)
--}}
<footer class="redesign-footer" role="contentinfo">
    <div class="rf-top">
        <div class="container">
            <div class="row g-4 justify-content-between">

                {{-- Column 1: Company Profile & Mission --}}
                <div class="col-xl-3 col-lg-6 col-md-6 col-12 mb-4 mb-xl-0">
                    <div class="footer-widget-brand">
                        <a href="{{ route('home') }}" class="footer-brand-logo mb-3 d-inline-block">
                            <img src="{{ asset('assets/images/logo/logo10.png') }}" alt="Konichiwa Chemicals" style="max-height: 52px; filter: brightness(0) invert(1);">
                        </a>
                        <p class="footer-brand-desc">
                            Delivering lab-tested, high-performance agricultural chemical solutions, fertilizers, and plant protection formulas empowering farmers and agribusinesses across India.
                        </p>
                        <div class="footer-social-links mt-3">
                            @if(!empty($site->facebook))
                                <a href="{{ $site->facebook }}" target="_blank" rel="noopener noreferrer" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                            @endif
                            @if(!empty($site->instagram))
                                <a href="{{ $site->instagram }}" target="_blank" rel="noopener noreferrer" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                            @endif
                            @if(!empty($site->twitter))
                                <a href="{{ $site->twitter }}" target="_blank" rel="noopener noreferrer" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
                            @endif
                            @if(!empty($site->linkedin))
                                <a href="{{ $site->linkedin }}" target="_blank" rel="noopener noreferrer" aria-label="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                            @endif
                            @if(!empty($site->whatsapp_number))
                                <a href="https://wa.me/{{ $site->whatsapp_number }}" target="_blank" rel="noopener noreferrer" aria-label="WhatsApp"><i class="fab fa-whatsapp"></i></a>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Column 2: Quick Links --}}
                <div class="col-xl-2 col-lg-6 col-md-6 col-12 rf-links mb-4 mb-xl-0">
                    <div class="footer-widget-inner">
                        <h5 class="rf-col-title">Navigation</h5>
                        <span class="rf-title-underline" aria-hidden="true"></span>
                        <ul class="footer-nav-list">
                            <li>
                                <a href="{{ route('home') }}">
                                    <i class="far fa-chevron-right" aria-hidden="true"></i> Home
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('about-us') }}">
                                    <i class="far fa-chevron-right" aria-hidden="true"></i> About Us
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('products.index') }}">
                                    <i class="far fa-chevron-right" aria-hidden="true"></i> Our Products
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('contact') }}">
                                    <i class="far fa-chevron-right" aria-hidden="true"></i> Contact Us
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>

                {{-- Column 3: Popular Updates ($footerPosts from View::composer) --}}
                <div class="col-xl-4 col-lg-6 col-md-6 col-12 mb-4 mb-xl-0">
                    <div class="footer-widget-inner">
                        <h5 class="rf-col-title">Popular Updates</h5>
                        <span class="rf-title-underline" aria-hidden="true"></span>
                        <div class="post-wrapper">
                            @if(isset($footerPosts) && $footerPosts->isNotEmpty())
                                @foreach($footerPosts as $post)
                                <div class="rf-post-single">
                                    <div class="rf-post-thumb">
                                        <img src="{{ $post->image ? asset($post->image) : asset('assets/images/blog/blog6.webp') }}"
                                             alt="{{ $post->title }}">
                                    </div>
                                    <div class="rf-post-content">
                                        <span class="rf-post-date">
                                            <i class="fal fa-clock me-1" aria-hidden="true"></i>
                                            {{ $post->created_at->format('d M, Y') }}
                                        </span>
                                        <a href="{{ route('blog-details', $post->slug) }}" class="rf-post-title">
                                            {{ Str::limit($post->title, 42) }}
                                        </a>
                                        <a class="rf-read-more" href="{{ route('blog-details', $post->slug) }}">
                                            Read More <i class="far fa-arrow-right" aria-hidden="true"></i>
                                        </a>
                                    </div>
                                </div>
                                @endforeach
                            @else
                                <p style="color: rgba(255,255,255,0.4); font-size: 14px;">No updates yet.</p>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Column 4: ISO Certificate & Certification --}}
                <div class="col-xl-3 col-lg-6 col-md-6 col-12">
                    <div class="footer-widget-inner">
                        <h5 class="rf-col-title">ISO Certified</h5>
                        <span class="rf-title-underline" aria-hidden="true"></span>
                        <div class="rf-iso-wrapper">
                            <div class="rf-iso-card">
                                <img src="{{ asset('assets/images/footer/iso.jpg') }}"
                                     alt="Konichiwa ISO 9001 Certificate"
                                     class="img-fluid rounded">
                            </div>
                            <div class="rf-cert-meta mt-2">
                                <span class="cert-code"><i class="fas fa-shield-alt text-danger me-1"></i> ISO No.- INQ/AN-25712/129662/1225</span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- Copyright bar --}}
    <div class="rf-copyright">
        <div class="container">
            <div class="row align-items-center justify-content-between">
                <div class="col-md-6 col-12 text-center text-md-start mb-2 mb-md-0">
                    <p class="m-0">KONICHIWA - Copyright &copy; {{ date('Y') }}. All rights reserved.</p>
                </div>
                <div class="col-md-6 col-12 text-center text-md-end">
                    <span class="rf-quality-tag">
                        <i class="fas fa-seedling text-success me-1"></i> Empowering Modern Agriculture
                    </span>
                </div>
            </div>
        </div>
    </div>
</footer>

{{-- Back to top — SVG progress circle. Class names MUST stay for main.js (Requirement 10.6) --}}
<a href="https://wa.me/{{ $site->whatsapp_number }}?text={{ rawurlencode('Hello Konichiwa Chemicals, I would like to enquire about your products.') }}"
   class="rd-whatsapp"
   target="_blank"
   rel="noopener noreferrer"
   aria-label="Chat with us on WhatsApp">
    <svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
</a>
<div class="progress-wrap" title="Back to top" aria-label="Back to top">
    <svg class="progress-circle svg-content" width="100%" height="100%" viewBox="-1 -1 102 102" aria-hidden="true">
        <path d="M50,1 a49,49 0 0,1 0,98 a49,49 0 0,1 0,-98"/>
    </svg>
</div>

<script src="{{ asset('assets/redesign/js/menu-icon.js') }}"></script>
{{--
    JS load order — MUST match layouts/footer.blade.php EXACTLY.
    Each file appears ONCE in the entire page.
    No jQuery or Bootstrap tags appear anywhere else in New_Layout.
    (Requirements 2.10, 2.11)
--}}
<script src="{{ asset('assets/js/vendor/jquery.min.js') }}"></script>
<script src="{{ asset('assets/js/vendor/jqueryui.js') }}"></script>
<script src="{{ asset('assets/js/vendor/waypoint.js') }}"></script>
<script src="{{ asset('assets/js/plugins/swiper.js') }}"></script>
<script src="{{ asset('assets/js/plugins/counterup.js') }}"></script>
<script src="{{ asset('assets/js/plugins/sal.min.js') }}"></script>
<script src="{{ asset('assets/js/vendor/bootstrap.min.js') }}"></script>
<script src="{{ asset('assets/js/vendor/waw.js') }}"></script>
<script src="{{ asset('assets/js/plugins/contact.form.js') }}"></script>
<script src="{{ asset('assets/js/main.js') }}"></script>
<script src="{{ asset('assets/redesign/js/scroll-ui.js') }}"></script>
@stack('scripts')
