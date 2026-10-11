(function () {
    'use strict';

    // Hide the page loader (replaces the old inline script).
    function markLoaded() { document.body.classList.add('loaded'); }
    if (document.readyState === 'complete') {
        markLoaded();
    } else {
        window.addEventListener('load', markLoaded);
    }

    document.addEventListener('click', function (e) {
        // Buttons and links with data-confirm="message" ask before acting.
        var confirmEl = e.target.closest('[data-confirm]');
        if (confirmEl && !window.confirm(confirmEl.getAttribute('data-confirm'))) {
            e.preventDefault();
            return;
        }

        // Table rows with data-href="url" open that URL when clicked,
        // unless the click was on a link, button or form control inside the row.
        var row = e.target.closest('[data-href]');
        if (row && !e.target.closest('a, button, input, select, textarea, label, form')) {
            window.location.href = row.getAttribute('data-href');
        }
    });
}());