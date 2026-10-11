/**
 * home.js
 * Homepage-specific JS for the redesigned Konichiwa public site.
 *
 * Responsibilities:
 *   1. After a contact form submission that returns a success flash,
 *      scroll the alert into view smoothly.
 *   2. If Laravel validation errors are present after a reload,
 *      scroll the form into view so the user sees them.
 *
 * CSP: script-src 'self' — loaded as external file, no inline scripts.
 * No jQuery. Vanilla IIFE.
 */
(function () {
    'use strict';

    /**
     * Scroll an element into view with a small top offset.
     * Falls back to scrollIntoView if scrollTo is not supported.
     */
    function scrollToElement(el) {
        if (!el) { return; }
        var offset = 100; // px from top of viewport
        var top = el.getBoundingClientRect().top + window.pageYOffset - offset;
        if (window.scrollTo) {
            window.scrollTo({ top: top, behavior: 'smooth' });
        } else {
            el.scrollIntoView({ behavior: 'smooth' });
        }
    }

    document.addEventListener('DOMContentLoaded', function () {

        // 1. Success alert: scroll into view after form submission
        //    The partial renders .alert.alert-success when session('success') is set.
        //    This fires on the page reload after a successful POST.
        var successAlert = document.querySelector('#home-contact-section .alert.alert-success');
        if (successAlert) {
            scrollToElement(successAlert);
            return; // success takes priority over error scroll
        }

        // 2. Validation errors: scroll the form into view
        //    Laravel renders .is-invalid or .field-error spans next to fields.
        var firstError = document.querySelector('#home-contact-section .field-error:not([style*="display: none"])');
        if (!firstError) {
            // Also check for Bootstrap is-invalid class
            firstError = document.querySelector('#home-contact-section .is-error');
        }
        if (firstError) {
            var form = document.getElementById('rd-contact-form');
            scrollToElement(form || firstError);
        }

    });

}());
/**
 * Hero banner: rotate every 5 seconds.
 * main.js creates the Swiper with a 4s delay; this adjusts it once the page has loaded.
 */
(function () {
    'use strict';

    window.addEventListener('load', function () {
        var hero = document.querySelector('.mySwiper');
        var swiper = hero && hero.swiper;
        if (!swiper || !swiper.params.autoplay || !swiper.autoplay) { return; }

        // Respect users who ask their system for reduced motion.
        if (window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
            swiper.autoplay.stop();
            return;
        }

        swiper.params.autoplay.delay = 5000;
        swiper.autoplay.stop();
        swiper.autoplay.start();
    });
}());
/* Hero rotation: 3.5 s per slide, loops back to slide 1 */
(function () {
    window.addEventListener('load', function () {
        var el = document.querySelector('.rts-banner-area .mySwiper');
        var sw = el && el.swiper;
        if (!sw) { return; }

        // Turn off main.js's own autoplay so the two timers don't fight
        if (sw.autoplay && sw.autoplay.running) { sw.autoplay.stop(); }

        // Respect visitors who ask their device for less motion
        if (window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches) { return; }

        setInterval(function () {
            if (document.hidden) { return; }
            if (sw.isEnd) { sw.slideTo(0); } else { sw.slideNext(); }
        }, 3500);
    });
})();

/**
 * Farmer Success Stories: Row-by-row Load More & View Less
 * 4 cards per row. Starts with 4 visible.
 */
(function () {
    var ITEMS_PER_ROW = 4;
    var currentlyVisible = 4;

    window.loadMoreFarmerStories = function () {
        var items = document.querySelectorAll('.farmer-story-col');
        var btnLoad = document.getElementById('btnLoadMoreStories');
        var btnLess = document.getElementById('btnViewLessStories');
        if (!items.length) { return; }

        var nextCount = currentlyVisible + ITEMS_PER_ROW;
        items.forEach(function (el, idx) {
            if (idx < nextCount) {
                el.style.display = '';
            }
        });

        currentlyVisible = nextCount;

        // Show View Less button once we've expanded
        if (btnLess) {
            btnLess.style.display = 'inline-flex';
        }

        // If all items are now visible, hide the Load More button
        if (currentlyVisible >= items.length && btnLoad) {
            btnLoad.style.display = 'none';
        }
    };

    window.viewLessFarmerStories = function () {
        var items = document.querySelectorAll('.farmer-story-col');
        var btnLoad = document.getElementById('btnLoadMoreStories');
        var btnLess = document.getElementById('btnViewLessStories');
        if (!items.length) { return; }

        // Hide anything beyond the first row (first 4 items)
        items.forEach(function (el, idx) {
            if (idx >= ITEMS_PER_ROW) {
                el.style.display = 'none';
            }
        });

        currentlyVisible = ITEMS_PER_ROW;

        // Reset button states
        if (btnLoad) {
            btnLoad.style.display = 'inline-flex';
        }
        if (btnLess) {
            btnLess.style.display = 'none';
        }

        // Smooth scroll back to section top
        var sec = document.getElementById('farmer-stories-section');
        if (sec) {
            var top = sec.getBoundingClientRect().top + window.pageYOffset - 90;
            window.scrollTo({ top: top, behavior: 'smooth' });
        }
    };
})();