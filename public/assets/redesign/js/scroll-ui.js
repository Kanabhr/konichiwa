(function () {
    var btn = document.querySelector('.progress-wrap');
    if (!btn) return;

    btn.setAttribute('role', 'button');
    btn.setAttribute('tabindex', '0');
    btn.setAttribute('aria-label', 'Back to top');
    btn.addEventListener('keydown', function (e) {
        if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); btn.click(); }
    });

    var lastY = window.pageYOffset || 0;
    var ticking = false;

    function update() {
        var y = window.pageYOffset || 0;
        if (Math.abs(y - lastY) > 4) {
            btn.classList.toggle('scrolling-down', y > lastY);
            btn.classList.toggle('scrolling-up', y < lastY);
            lastY = y;
        }
        ticking = false;
    }

    window.addEventListener('scroll', function () {
        if (!ticking) { ticking = true; window.requestAnimationFrame(update); }
    }, { passive: true });
})();