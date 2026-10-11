(function () {
    'use strict';

    var btn = document.querySelector('[data-copy-link]');
    var status = document.querySelector('.rd-share-status');
    if (!btn) { return; }

    function say(msg) {
        if (!status) { return; }
        status.textContent = msg;
        setTimeout(function () { status.textContent = ''; }, 2500);
    }

    function fallbackCopy(text) {
        var ta = document.createElement('textarea');
        ta.value = text;
        ta.setAttribute('readonly', '');
        ta.style.position = 'fixed';
        ta.style.opacity = '0';
        document.body.appendChild(ta);
        ta.select();
        var ok = false;
        try { ok = document.execCommand('copy'); } catch (e) { ok = false; }
        document.body.removeChild(ta);
        say(ok ? 'Link copied' : 'Could not copy. Copy the address bar link instead.');
    }

    btn.addEventListener('click', function () {
        var url = btn.getAttribute('data-copy-link');
        if (navigator.clipboard && window.isSecureContext) {
            navigator.clipboard.writeText(url).then(
                function () { say('Link copied'); },
                function () { fallbackCopy(url); }
            );
        } else {
            fallbackCopy(url);
        }
    });
}());