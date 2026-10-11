@extends('layouts.redesign')

@section('title', 'About Us | Konichiwa')

@section('content')
    @include('partials.redesign-breadcrumb', ['pageTitle' => 'About Us', 'breadcrumbActive' => 'About Us'])

    {{-- About intro --}}
    <section class="rd-about rts-section-gap wow fadeInUp" data-wow-delay="0.1s">
        <div class="container">
            <div class="row g-5 align-items-center">
                <div class="col-lg-6">
                    <div class="rd-about-images">
                        <img class="rd-about-main" src="{{ asset('assets/images') }}/about/main/88.png" alt="Konichiwa agricultural chemicals">
                        <img class="rd-about-over" src="{{ asset('assets/images') }}/about/main/about4.png" alt="Konichiwa crop protection products">
                    </div>
                </div>
                <div class="col-lg-6">
                    <span class="rd-label">Rooted in quality</span>
                    <h2 class="rd-about-title">Empowering Indian Agriculture Through Innovation</h2>
                    <p class="rd-about-text">"Konichiwa Agricultural Chemicals India is dedicated to revolutionizing crop health across the nation. By combining advanced scientific innovation with a deep-rooted understanding of Indian soil, we empower our farmers with high-performance solutions for a bountiful, resilient, and sustainable harvest."</p>

                    <div class="rd-progress-list">
                        <div class="single-progress">
                            <div class="progress-top">
                                <p class="progress-title">Crop Protection Accuracy</p>
                                <span class="persectage">95%</span>
                            </div>
                            <div class="meter cadetblue">
                                <span data-progress="70" style="width:0;"></span>
                            </div>
                        </div>
                        <div class="single-progress">
                            <div class="progress-top">
                                <p class="progress-title">Company Strength</p>
                                <span class="persectage">93%</span>
                            </div>
                            <div class="meter">
                                <span data-progress="93" style="width:0;"></span>
                            </div>
                        </div>
                    </div>

                    <a href="{{ route('contact') }}" class="rts-btn btn-primary">Partner With Us</a>
                </div>
            </div>
        </div>
    </section>

    {{-- Why choose us --}}
    <section class="rd-faq rts-section-gap wow fadeInUp" data-wow-delay="0.1s">
        <div class="container">
            <div class="row g-5 align-items-center">
                <div class="col-lg-6">
                    <span class="rd-label rd-label-dark">Why choose us</span>
                    <h2 class="rd-about-title">Cultivating Trust With Quality Agro-Solutions</h2>

                    <div class="accordion rd-accordion" id="accordionExample">
                        <div class="accordion-item">
                            <h3 class="accordion-header" id="headingOne">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                    <span>01.&nbsp;</span>How do Konichiwa products improve my crop yield?
                                </button>
                            </h3>
                            <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    Our formulations are engineered using high-precision chemical standards that target specific pests and diseases without harming the plant. By optimizing nutrient uptake and providing superior protection, we ensure your crops reach their full genetic potential for a heavier, healthier harvest.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h3 class="accordion-header" id="headingTwo">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                    <span>02.&nbsp;</span>Are your chemicals safe for the soil in the long run?
                                </button>
                            </h3>
                            <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    Yes. At Konichiwa, we prioritize sustainable agriculture. Our R&D focuses on biodegradable components that minimize soil toxicity. We provide balanced solutions that protect your current crop while maintaining the microbial health of your land for future generations.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h3 class="accordion-header" id="headingThree">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                    <span>03.&nbsp;</span>How can I verify the authenticity of Konichiwa products?
                                </button>
                            </h3>
                            <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    To protect farmers from counterfeit products, every Konichiwa package comes with a unique QR code. You can scan this code using your smartphone to instantly verify the batch details, manufacturing date, and chemical composition directly from our secure database.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="rd-collage">
    <figure class="rd-collage-item rd-collage-a">
        <img src="{{ asset('assets/redesign/images/collage-field.jpg') }}" alt="Two farm workers planting in a green crop field" width="1000" height="667" loading="lazy" decoding="async">
    </figure>
    <figure class="rd-collage-item rd-collage-b">
        <img src="{{ asset('assets/redesign/images/collage-smile.jpg') }}" alt="Smiling farmer with young crop seedlings" width="550" height="500" loading="lazy" decoding="async">
    </figure>
    <figure class="rd-collage-item rd-collage-c">
        <img src="{{ asset('assets/redesign/images/collage-top.jpg') }}" alt="Farmer harvesting in a green field, seen from above" width="550" height="500" loading="lazy" decoding="async">
    </figure>
</div>
            </div>
        </div>
    </section>
@endsection