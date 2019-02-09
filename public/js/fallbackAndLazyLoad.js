window.jQuery || document.write('<script src="/js/jquery-3.3.1.min.js"><\/script>');

/** Lazy loading for images below the fold. The loading happens after 3 seconds, scrolling, resizing,
 orientation change and visibility change */
var lazyloadThrottleTimeout;

function lazyload()
{
    if (typeof hasLazyLoaded === 'undefined') {
        var scrollTop = window.pageYOffset;
        lazyloadImages.forEach(function (img) {
            img.src = img.dataset.src;
            img.classList.remove('lazy');
        });
        if (lazyloadImages.length === 0) {
            document.removeEventListener("scroll", lazyload);
            window.removeEventListener("resize", lazyload);
            window.removeEventListener("orientationChange", lazyload);
            document.removeEventListener("visibilitychange", lazyload);
        }

        hasLazyLoaded = true;
    }
}

document.addEventListener("DOMContentLoaded", function () {
    lazyloadImages = document.querySelectorAll("img.lazy");
    if ( lazyloadImages.length > 0 ) {
        setTimeout(lazyload, 3000);
        document.addEventListener("scroll", lazyload);
        window.addEventListener("resize", lazyload);
        window.addEventListener("orientationChange", lazyload);
        document.addEventListener("visibilitychange", lazyload);
    }
});