/**
 * contact-form.js
 * Client-side phone validation for the redesigned contact form partial.
 * Loaded only when showPhone=true via @push('scripts') in contact-form.blade.php.
 *
 * Uses id="rd-contact-form" (NOT "contact-form" — that id is bound by
 * public/assets/js/plugins/contact.form.js for the homepage AJAX form).
 *
 * No jQuery. Vanilla IIFE. CSP script-src 'self' compliant — external file only.
 */
(function () {
    'use strict';

    var phone = document.getElementById('cf-phone');
    var error = document.getElementById('cf-phone-error');
    var form  = document.getElementById('rd-contact-form');

    if (!phone || !error || !form) { return; }

    function validate() {
        if (!phone.value.trim()) {
            error.style.display = 'block';
            phone.style.border  = '1px solid #FF0003';
            return false;
        }
        error.style.display = 'none';
        phone.style.border  = '1px solid transparent';
        return true;
    }

    /* Blur: trigger error immediately on leaving the field */
    phone.addEventListener('blur', validate);

    /* Input: clear error as soon as the user starts typing */
    phone.addEventListener('input', function () {
        if (phone.value.trim()) {
            error.style.display = 'none';
            phone.style.border  = '1px solid transparent';
        }
    });

    /* Submit: block submission if phone is empty */
    form.addEventListener('submit', function (e) {
        if (!validate()) {
            e.preventDefault();
            phone.focus();
        }
    });

}());