@extends('layouts.redesign')

@section('title', 'Contact Us | Konichiwa')

@section('content')

    {{-- Breadcrumb --}}
    @include('partials.redesign-breadcrumb', [
        'pageTitle'        => 'Contact Us',
        'breadcrumbActive' => 'Contact Us',
    ])

    {{-- ── Contact info cards ──────────────────────────────────────── --}}
    <div class="rts-contact-area rts-section-gap">
        <div class="container">
            <div class="row g-5">

                {{-- Phone card --}}
                <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">
                    <div class="single-contact-one-inner">
                        <div class="thumbnail">
                            <img class="rd-pos-call" src="{{ asset('assets/redesign/images/contact-call.webp') }}" alt="Smiling man talking on the phone" width="1200" height="800" loading="lazy">
                        </div>
                        <div class="content">
                            <div class="icone">
                                <img src="{{ asset('assets/images/contact/shape/01.svg') }}" alt="">
                            </div>
                            <div class="info">
                                <span>Call Us 24/7</span>
                                <a href="tel:{{ $site->phone_primary_tel }}">
                                    <p>{{ $site->phone_primary }}</p>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Email card --}}
                <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">
                    <div class="single-contact-one-inner">
                        <div class="thumbnail">
                            <img class="rd-pos-quote" src="{{ asset('assets/redesign/images/contact-quote.webp') }}" alt="Woman typing on a laptop in a green field" width="1200" height="1200" loading="lazy">
                        </div>
                        <div class="content">
                            <div class="icone">
                                <img src="{{ asset('assets/images/contact/shape/02.svg') }}" alt="">
                            </div>
                            <div class="info">
                                <span>Make A Quote</span>
                                <a href="mailto:{{ $site->email }}">
                                    <p>{{ $site->email }}</p>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Address card --}}
                <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">
                    <div class="single-contact-one-inner">
                        <div class="thumbnail">
                            <img src="{{ asset('assets/images/contact/03.png') }}" alt="Our office">
                        </div>
                        <div class="content">
                            <div class="icone">
                                <img src="{{ asset('assets/images/contact/shape/03.svg') }}" alt="">
                            </div>
                            <div class="info">
                                <span>Registered Office</span>
                                <a href="#">
                                    <p>{{ $site->address_reg }}</p>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    {{-- ── /Contact info cards ──────────────────────────────────────── --}}

    {{-- ── Contact form ────────────────────────────────────────────── --}}
    <div class="rts-contact-form-area">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="rts-contact-fluid rts-section-gap">
                        <div class="rts-title-area contact text-center mb--50">
                            <p class="pre-title">Get In Touch</p>
                            <h2 class="title">Needs Help? Let's Get in Touch</h2>
                        </div>
                        {{-- Shared partial. Form id = rd-contact-form so contact.form.js
                             (bound to #contact-form) does NOT intercept this form.
                             Normal POST → back()->with('success'). --}}
                        @include('partials.contact-form', [
                            'showPhone'   => true,
                            'showSubject' => false,
                        ])
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- ── /Contact form ──────────────────────────────────────────── --}}

    {{-- ── Google Maps ──────────────────────────────────────────── --}}
    <div class="rts-contact-map-area">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="contact-map-area-fluid">
                        <iframe src="{{ $site->map_embed_url }}"
                                width="600"
                                height="450"
                                style="border:0;"
                                allowfullscreen=""
                                loading="lazy"
                                referrerpolicy="no-referrer-when-downgrade"
                                title="Konichiwa office location"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- ── /Google Maps ──────────────────────────────────────────── --}}

@endsection

@push('styles')
<style>
    /* Equal-height cards with tint background — preserved from original contactus.blade.php */
    .rts-contact-area .row {
        align-items: stretch;
    }
    .single-contact-one-inner {
        background-color: var(--color-tint) !important;
        border-color: #f5d5d5 !important;
        display: flex;
        flex-direction: column;
        height: 100%;
    }
    .single-contact-one-inner .thumbnail {
        flex-shrink: 0;
    }
    .single-contact-one-inner .thumbnail img {
        width: 100%;
        height: 180px;
        object-fit: cover;
    }
    .single-contact-one-inner .content {
        flex: 1;
        background-color: var(--color-tint) !important;
        display: flex;
        align-items: flex-start !important;
        gap: 18px;
        padding: 30px !important;
    }
    .single-contact-one-inner .content .icone {
        flex-shrink: 0;
        margin-right: 0 !important;
        width: 48px;
        height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .single-contact-one-inner .content .icone img {
        width: 48px;
        height: 48px;
        object-fit: contain;
    }
    .single-contact-one-inner .content .info {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }
    .single-contact-one-inner .content .info span {
        display: block;
        color: var(--color-primary) !important;
        font-weight: 600;
        font-size: 15px;
        margin-bottom: 2px;
    }
    .single-contact-one-inner .content .info a p {
        color: var(--color-heading);
        font-size: 15px;
        margin-bottom: 0;
        line-height: 1.6;
    }
    .single-contact-one-inner .content .info a:hover p {
        color: var(--color-primary);
    }
</style>
@endpush