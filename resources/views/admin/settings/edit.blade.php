@extends('layouts.admin')

@section('page-title', 'Contact Details')

@section('content')

<div class="row g-4">

    <div class="col-12">
        <p class="text-muted small mb-0">
            Update the phone, email, WhatsApp, address, map embed and social links shown on the public site.
            Changes take effect immediately on save.
        </p>
    </div>

    <div class="col-12">
        <form action="{{ route('admin.settings.update') }}" method="POST" novalidate>
            @csrf

            {{-- ── Contact Info ──────────────────────────────────────── --}}
            <div class="premium-card p-4 mb-4">
                <h6 class="fw-bold text-uppercase small text-muted mb-4" style="letter-spacing:.05em;">
                    Contact Information
                </h6>

                <div class="row g-3">

                    <div class="col-md-6">
                        <label class="form-label fw-bold text-uppercase small text-muted" for="phone_primary">
                            Primary Phone
                        </label>
                        <input type="text"
                               id="phone_primary"
                               name="phone_primary"
                               value="{{ old('phone_primary', $site->phone_primary) }}"
                               class="form-control border-0 bg-light p-3 @error('phone_primary') is-invalid @enderror"
                               placeholder="+919993955051"
                               style="border-radius:12px;">
                        <div class="form-text text-muted" style="font-size:12px;">
                            Used in tel: links — digits, +, spaces and hyphens only.
                        </div>
                        @error('phone_primary')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold text-uppercase small text-muted" for="phone_secondary">
                            Secondary Phone <span class="text-muted fw-normal">(optional)</span>
                        </label>
                        <input type="text"
                               id="phone_secondary"
                               name="phone_secondary"
                               value="{{ old('phone_secondary', $site->phone_secondary) }}"
                               class="form-control border-0 bg-light p-3 @error('phone_secondary') is-invalid @enderror"
                               placeholder=""
                               style="border-radius:12px;">
                        @error('phone_secondary')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold text-uppercase small text-muted" for="email">
                            Email Address
                        </label>
                        <input type="email"
                               id="email"
                               name="email"
                               value="{{ old('email', $site->email) }}"
                               class="form-control border-0 bg-light p-3 @error('email') is-invalid @enderror"
                               placeholder="info@konichiwachemicals.com"
                               style="border-radius:12px;">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold text-uppercase small text-muted" for="whatsapp_number">
                            WhatsApp Number
                        </label>
                        <input type="text"
                               id="whatsapp_number"
                               name="whatsapp_number"
                               value="{{ old('whatsapp_number', $site->whatsapp_number) }}"
                               class="form-control border-0 bg-light p-3 @error('whatsapp_number') is-invalid @enderror"
                               placeholder="919993955051"
                               style="border-radius:12px;">
                        <div class="form-text text-muted" style="font-size:12px;">
                            Digits with country code, no spaces or + (e.g. 919993955051). Saved automatically stripped of spaces/+.
                        </div>
                        @error('whatsapp_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                </div>
            </div>

            {{-- ── Addresses ──────────────────────────────────────────── --}}
            <div class="premium-card p-4 mb-4">
                <h6 class="fw-bold text-uppercase small text-muted mb-4" style="letter-spacing:.05em;">
                    Addresses
                </h6>

                <div class="row g-3">

                    <div class="col-12">
                        <label class="form-label fw-bold text-uppercase small text-muted" for="address_reg">
                            Registered Office
                        </label>
                        <textarea id="address_reg"
                                  name="address_reg"
                                  rows="2"
                                  maxlength="500"
                                  class="form-control border-0 bg-light p-3 @error('address_reg') is-invalid @enderror"
                                  style="border-radius:12px; resize:vertical;">{{ old('address_reg', $site->address_reg) }}</textarea>
                        @error('address_reg')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-bold text-uppercase small text-muted" for="address_region">
                            Regional Office
                        </label>
                        <textarea id="address_region"
                                  name="address_region"
                                  rows="2"
                                  maxlength="500"
                                  class="form-control border-0 bg-light p-3 @error('address_region') is-invalid @enderror"
                                  style="border-radius:12px; resize:vertical;">{{ old('address_region', $site->address_region) }}</textarea>
                        @error('address_region')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                </div>
            </div>

            {{-- ── Map Embed ───────────────────────────────────────────── --}}
            <div class="premium-card p-4 mb-4">
                <h6 class="fw-bold text-uppercase small text-muted mb-4" style="letter-spacing:.05em;">
                    Google Maps Embed
                </h6>

                <div class="mb-3">
                    <label class="form-label fw-bold text-uppercase small text-muted" for="map_embed_url">
                        Map Embed URL (src from &lt;iframe&gt;)
                    </label>
                    <input type="url"
                           id="map_embed_url"
                           name="map_embed_url"
                           value="{{ old('map_embed_url', $site->map_embed_url) }}"
                           class="form-control border-0 bg-light p-3 @error('map_embed_url') is-invalid @enderror"
                           placeholder="https://www.google.com/maps/embed?pb=..."
                           style="border-radius:12px;">
                    <div class="form-text text-muted" style="font-size:12px;">
                        Open Google Maps &rarr; Share &rarr; Embed a map &rarr; copy only the URL inside <code>src="…"</code>.
                    </div>
                    @error('map_embed_url')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                @if($site->map_embed_url)
                <div class="rounded overflow-hidden" style="border-radius:12px; height:220px;">
                    <iframe src="{{ $site->map_embed_url }}"
                            width="100%"
                            height="220"
                            style="border:0;"
                            allowfullscreen=""
                            loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade"
                            title="Map preview"></iframe>
                </div>
                @endif

            </div>

            {{-- ── Social Links ────────────────────────────────────────── --}}
            <div class="premium-card p-4 mb-4">
                <h6 class="fw-bold text-uppercase small text-muted mb-4" style="letter-spacing:.05em;">
                    Social Links <span class="text-muted fw-normal">(leave blank to hide)</span>
                </h6>

                <div class="row g-3">

                    @foreach([
                        'facebook'  => ['label' => 'Facebook',  'icon' => 'fab fa-facebook-f',  'placeholder' => 'https://facebook.com/yourpage'],
                        'instagram' => ['label' => 'Instagram', 'icon' => 'fab fa-instagram',   'placeholder' => 'https://instagram.com/yourhandle'],
                        'youtube'   => ['label' => 'YouTube',   'icon' => 'fab fa-youtube',     'placeholder' => 'https://youtube.com/yourchannel'],
                        'linkedin'  => ['label' => 'LinkedIn',  'icon' => 'fab fa-linkedin-in', 'placeholder' => 'https://linkedin.com/company/yourcompany'],
                        'twitter'   => ['label' => 'X (Twitter)', 'icon' => 'fab fa-twitter',  'placeholder' => 'https://x.com/yourhandle'],
                    ] as $field => $meta)
                    <div class="col-md-6">
                        <label class="form-label fw-bold text-uppercase small text-muted" for="{{ $field }}">
                            <i class="{{ $meta['icon'] }} me-1" aria-hidden="true"></i> {{ $meta['label'] }}
                        </label>
                        <input type="url"
                               id="{{ $field }}"
                               name="{{ $field }}"
                               value="{{ old($field, $site->{$field}) }}"
                               class="form-control border-0 bg-light p-3 @error($field) is-invalid @enderror"
                               placeholder="{{ $meta['placeholder'] }}"
                               style="border-radius:12px;">
                        @error($field)
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    @endforeach

                </div>
            </div>

            {{-- ── Submit ──────────────────────────────────────────────── --}}
            <div class="d-flex gap-3">
                <button type="submit" class="btn btn-premium btn-premium-primary px-5">
                    <i class="fas fa-save me-2" aria-hidden="true"></i> Save Changes
                </button>
                <a href="{{ route('admin.dashboard') }}" class="btn btn-light px-4" style="border-radius:10px; font-weight:600;">
                    Cancel
                </a>
            </div>

        </form>
    </div>

</div>

@endsection
