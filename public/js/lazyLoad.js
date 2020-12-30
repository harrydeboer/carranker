document.addEventListener("DOMContentLoaded", function ()
{
    var lazyloadImages = document.querySelectorAll("img.lazy");

    function lazyload()
    {
        if (typeof hasLazyLoaded === 'undefined') {
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

            var hasLazyLoaded = true;
        }
    }

    if ( lazyloadImages.length > 0 && typeof sessionStorage.lazyLoad === 'undefined' ) {
        setTimeout(lazyload, 3000);
        document.addEventListener("scroll", lazyload);
        window.addEventListener("resize", lazyload);
        window.addEventListener("orientationChange", lazyload);
        document.addEventListener("visibilitychange", lazyload);
    } else {
        lazyload();
    }
});