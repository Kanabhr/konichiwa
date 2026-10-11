(function () {
    'use strict';

    var form = document.getElementById('rd-contact-form');
    var area = document.getElementById('cf-message');
    var out  = document.getElementById('cf-message-count');
    var gst  = document.getElementById('cf-gst');
    if (!form || !area || !out) { return; }

    var max = parseInt(area.getAttribute('data-max-words'), 10) || 100;

    function words() {
        var t = area.value.trim();
        return t ? t.split(/\s+/).length : 0;
    }

    function update() {
        var n = words();
        out.textContent = n + ' / ' + max + ' words';
        out.classList.toggle('is-over', n > max);
        return n <= max;
    }

    area.addEventListener('input', update);
    form.addEventListener('submit', function (e) {
        if (!update()) { e.preventDefault(); area.focus(); }
    });

    if (gst) {
        gst.addEventListener('input', function () {
            gst.value = gst.value.toUpperCase();
        });
    }

    update();
}());