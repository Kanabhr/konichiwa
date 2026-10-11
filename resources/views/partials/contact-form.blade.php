{{--
    contact-form.blade.php
    Shared contact form partial for the redesigned public pages.

    Parameters (pass via @include):
        $showPhone   (bool, default false) renders input[name="phone"]
        $showSubject (bool, default false) renders input[name="subject"]
        $showGst     (bool, default false) renders input[name="gst_number"] (optional)

    Always renders: name, email, message (max 100 words), Turnstile, honeypot.
    Posts to: route('contact.store')  ->  POST /contact
    JS: contact-counter.js (word counter, GST uppercase) always;
        contact-form.js (phone check) only when showPhone = true.
--}}
@php
    $showPhone   = $showPhone   ?? false;
    $showSubject = $showSubject ?? false;
    $showGst     = $showGst     ?? false;
@endphp

@if(session('success'))
    <div class="alert alert-success" role="alert">
        {{ session('success') }}
    </div>
@endif

<form id="rd-contact-form"
      action="{{ route('contact.store') }}"
      method="POST"
      novalidate>
    @csrf

    <div class="rts-contact-form-wrapper">

        <div class="name-email">
            <div class="form-group">
                <label for="cf-name">Full Name / Company Name</label>
                <input type="text"
                       id="cf-name"
                       name="name"
                       value="{{ old('name') }}"
                       placeholder="Full Name / Company Name"
                       required
                       aria-required="true"
                       class="{{ $errors->has('name') ? 'is-error' : '' }}">
                @error('name')
                    <span class="field-error" role="alert">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="cf-email">Email Address</label>
                <input type="email"
                       id="cf-email"
                       name="email"
                       value="{{ old('email') }}"
                       placeholder="Email Address"
                       required
                       aria-required="true"
                       class="{{ $errors->has('email') ? 'is-error' : '' }}">
                @error('email')
                    <span class="field-error" role="alert">{{ $message }}</span>
                @enderror
            </div>
        </div>

        @if($showPhone)
        <div class="form-group phone-field-wrapper">
            <label for="cf-phone">Phone Number</label>
            <input type="text"
                   id="cf-phone"
                   name="phone"
                   value="{{ old('phone') }}"
                   placeholder="Phone Number *"
                   aria-required="true"
                   class="{{ $errors->has('phone') ? 'is-error' : '' }}">
            <span id="cf-phone-error"
                  class="field-error"
                  role="alert"
                  style="display: none;">&#9679; This field is required</span>
            @error('phone')
                <span class="field-error" role="alert">{{ $message }}</span>
            @enderror
        </div>
        @endif

        @if($showSubject)
        <div class="form-group">
            <label for="cf-subject">Inquiry Type</label>
            <input type="text"
                   id="cf-subject"
                   name="subject"
                   value="{{ old('subject') }}"
                   placeholder="Inquiry Type (e.g., Wholesale, Retail, Field Visit)"
                   class="{{ $errors->has('subject') ? 'is-error' : '' }}">
            @error('subject')
                <span class="field-error" role="alert">{{ $message }}</span>
            @enderror
        </div>
        @endif

        @if($showGst)
        <div class="form-group">
            <label for="cf-gst">GST Number <span class="rd-optional">(optional)</span></label>
            <input type="text"
                   id="cf-gst"
                   name="gst_number"
                   value="{{ old('gst_number') }}"
                   placeholder="e.g. 22AAAAA0000A1Z5"
                   maxlength="15"
                   autocomplete="off"
                   class="rd-gst {{ $errors->has('gst_number') ? 'is-error' : '' }}">
            @error('gst_number')
                <span class="field-error" role="alert">{{ $message }}</span>
            @enderror
        </div>
        @endif

        <div class="form-group">
            <label for="cf-message">Your Message</label>
            <textarea id="cf-message"
                      name="message"
                      placeholder="Tell us about your bulk requirements or request a visit..."
                      maxlength="1000"
                      data-max-words="100"
                      required
                      aria-required="true"
                      class="{{ $errors->has('message') ? 'is-error' : '' }}">{{ old('message') }}</textarea>
            <div class="rd-word-count" id="cf-message-count" aria-live="polite">0 / 100 words</div>
            @error('message')
                <span class="field-error" role="alert">{{ $message }}</span>
            @enderror
        </div>

        {{-- Honeypot: hidden from people, bots fill it. Checked in ContactController. --}}
        <div class="rd-hp" aria-hidden="true">
            <label for="cf-website">Leave this field empty</label>
            <input type="text" id="cf-website" name="website_url" tabindex="-1" autocomplete="off">
        </div>

        {{-- Cloudflare Turnstile --}}
        <div class="form-group">
            <div class="cf-turnstile" data-sitekey="{{ config('services.turnstile.site_key') }}"></div>
            @error('cf-turnstile-response')
                <span class="field-error" role="alert">{{ $message }}</span>
            @enderror
        </div>

        <button type="submit" class="rts-btn btn-primary">Send Message</button>

    </div>
</form>

@push('scripts')
<script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
<script src="{{ asset('assets/redesign/js/contact-counter.js') }}"></script>
@endpush

@if($showPhone)
@push('scripts')
<script src="{{ asset('assets/redesign/js/contact-form.js') }}"></script>
@endpush
@endif