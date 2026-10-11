<div class="rts-footer-area footer-one rts-section-gapTop bg-footer-one">
    <div class="container bg-shape-f1">
        <div class="row pt--120 pt_sm--80 pb--80 pb_sm--40">
            <!-- Footer Column 1: Quick Links -->
            <div class="col-xl-4 col-md-6 col-sm-12 col-12">
                <div class="footer-one-single-wized">
                    <div class="wized-title">
                        <h5 class="title">Quick Links</h5>
                        <img src="{{ asset('assets/images/footer/under-title.png') }}" alt="finbiz_footer">
                    </div>
                    <div class="quick-link-inner">
                        <ul class="links">

                            <li><a href="{{ route('contact') }}"><i class="far fa-arrow-right"></i>Contact Us</a></li>
                            <li><a href="{{ route('about-us') }}"><i class="far fa-arrow-right"></i>About Us</a></li>
                            <li><a href="{{ route('products.index') }}"><i class="far fa-arrow-right"></i>Products</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Footer Column 2: Recent Posts -->
            <div class="col-xl-4 col-md-6 col-sm-12 col-12">
                <div class="footer-one-single-wized margin-left-65">
                    <div class="wized-title">
                        <h5 class="title">Popular Updates</h5>
                        <img src="{{ asset('assets/images/footer/under-title.png') }}" alt="finbiz_footer">
                    </div>
                    <div class="post-wrapper">
                        @foreach($footerPosts as $post)
                            <div class="single-footer-post {{ !$loop->last ? 'mb--30' : '' }}">
                                <div class="left-thumbnail">
                                    <img src="{{ $post->image ? asset($post->image) : asset('assets/images/blog/blog6.webp') }}"
                                        alt="blog" style="width: 80px; height: 80px; object-fit: cover;">
                                </div>
                                <div class="post-right">
                                    <p> <i class="fal fa-clock"></i> {{ $post->created_at->format('d M, Y') }}</p>
                                    <a href="{{ route('blog-details', $post->slug) }}">
                                        <h6 class="title">{{ Str::limit($post->title, 40) }}</h6>
                                    </a>
                                    <a class="red-more" href="{{ route('blog-details', $post->slug) }}">Read More<i
                                            class="far fa-arrow-right"></i></a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Footer Column 3: ISO Certificate -->
            <div class="col-xl-4 col-md-6 col-sm-12 col-12">
                <div class="footer-one-single-wized margin-left-65">
                    <div class="wized-title footer-img">
                        <h5 class="title">ISO Certificate</h5>
                        <img src="{{ asset('assets/images/footer/under-title.png') }}" alt="finbiz_footer">
                    </div>
                    <div class="post-wrapper">
                        <img src="{{ asset('assets/images/footer/iso.jpg') }}" alt="ISO Certificate"
                            class="img-fluid rounded border shadow-sm">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Copyright Area -->
    <div class="rts-copyright-area">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center">
                    <p class="m-0">KONICHIWA - Copyright {{ date('Y') }}. All rights reserved.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Back to top -->
<div class="progress-wrap">
    <svg class="progress-circle svg-content" width="100%" height="100%" viewBox="-1 -1 102 102">
        <path d="M50,1 a49,49 0 0,1 0,98 a49,49 0 0,1 0,-98" />
    </svg>
</div>

<!-- Scripts -->
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

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const dark = document.getElementById('icon-dark');
        const lightWrapper = document.getElementById('icon-light');
        if (!dark || !lightWrapper) return;

        let isDarkVisible = true;
        dark.classList.add('slide-active');
        lightWrapper.classList.add('slide-out-right');

        setInterval(() => {
            if (isDarkVisible) {
                dark.classList.replace('slide-active', 'slide-out-right');
                lightWrapper.style.transition = 'none';
                lightWrapper.classList.replace('slide-out-right', 'slide-out-left');
                setTimeout(() => {
                    lightWrapper.style.transition = 'transform 0.8s, opacity 0.8s';
                    lightWrapper.classList.replace('slide-out-left', 'slide-active');
                }, 50);
            } else {
                lightWrapper.classList.replace('slide-active', 'slide-out-right');
                dark.style.transition = 'none';
                dark.classList.replace('slide-out-right', 'slide-out-left');
                setTimeout(() => {
                    dark.style.transition = 'transform 0.8s, opacity 0.8s';
                    dark.classList.replace('slide-out-left', 'slide-active');
                }, 50);
            }
            isDarkVisible = !isDarkVisible;
        }, 3000);
    });
</script>
