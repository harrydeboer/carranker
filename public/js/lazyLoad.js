document.addEventListener("DOMContentLoaded", function ()
{
    let lazyLoadImages = document.querySelectorAll("img.lazy");
    let hasLazyLoaded;

    function lazyLoad()
    {
        if (typeof hasLazyLoaded === 'undefined') {
            lazyLoadImages.forEach(function (img) {
                img.src = img.dataset.src;
                img.classList.remove('lazy');
            });
            if (lazyLoadImages.length === 0) {
                document.removeEventListener("scroll", lazyLoad);
                window.removeEventListener("resize", lazyLoad);
                window.removeEventListener("orientationChange", lazyLoad);
                document.removeEventListener("visibilitychange", lazyLoad);
            }

            hasLazyLoaded = true;
        }
    }

    if ( lazyLoadImages.length > 0 && typeof sessionStorage.lazyLoad === 'undefined' ) {
        setTimeout(lazyLoad, 3000);
        document.addEventListener("scroll", lazyLoad);
        window.addEventListener("resize", lazyLoad);
        window.addEventListener("orientationChange", lazyLoad);
        document.addEventListener("visibilitychange", lazyLoad);
    } else {
        lazyLoad();
    }
});
