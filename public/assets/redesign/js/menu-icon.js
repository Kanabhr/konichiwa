document.addEventListener('DOMContentLoaded', function () {
    var dark = document.getElementById('icon-dark');
    var lightWrapper = document.getElementById('icon-light');
    if (!dark || !lightWrapper) return;
    var isDarkVisible = true;
    dark.classList.add('slide-active');
    lightWrapper.classList.add('slide-out-right');
    setInterval(function () {
        if (isDarkVisible) {
            dark.classList.replace('slide-active', 'slide-out-right');
            lightWrapper.style.transition = 'none';
            lightWrapper.classList.replace('slide-out-right', 'slide-out-left');
            setTimeout(function () {
                lightWrapper.style.transition = 'transform 0.8s, opacity 0.8s';
                lightWrapper.classList.replace('slide-out-left', 'slide-active');
            }, 50);
        } else {
            lightWrapper.classList.replace('slide-active', 'slide-out-right');
            dark.style.transition = 'none';
            dark.classList.replace('slide-out-right', 'slide-out-left');
            setTimeout(function () {
                dark.style.transition = 'transform 0.8s, opacity 0.8s';
                dark.classList.replace('slide-out-left', 'slide-active');
            }, 50);
        }
        isDarkVisible = !isDarkVisible;
    }, 3000);
});
