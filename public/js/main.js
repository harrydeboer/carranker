/** Lazy loading for images below the fold. The loading happens after 3 seconds, scrolling, resizing,
    orientation change and visibility change */
var lazyloadThrottleTimeout;

function lazyload()
{
    if (lazyloadThrottleTimeout) {
        clearTimeout(lazyloadThrottleTimeout);
    }

    lazyloadThrottleTimeout = setTimeout(function()
    {
        var scrollTop = window.pageYOffset;
        lazyloadImages.forEach(function(img)
        {
            if (img.offsetTop < (window.innerHeight + scrollTop)) {
                img.src = img.dataset.src;
                img.classList.remove('lazy');
            }
        });
        if (lazyloadImages.length === 0) {
            document.removeEventListener("scroll", lazyload);
            window.removeEventListener("resize", lazyload);
            window.removeEventListener("orientationChange", lazyload);
            document.removeEventListener("visibilitychange", lazyload);
        }
    }, 20);
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

$(document).ready(function ()
{
    var menuMake = $('#nav_select_make');
    var menuModel = $('#nav_select_model');

    /* The selected options are set to the session on change of the selected make or model. */
    menuMake.on('change', function ()
    {
        fillModelMenu();
    });

    menuModel.on('change', function ()
    {
        navigate();
    });

    $('#search_form_submit').on('click', function(event)
    {
        if ($('#search_form_text').val() === "") {
            event.preventDefault();
            if (menuMake.val() !== "") {
                navigate();
            }
        }
    });

    /* Determines the car models related to the chosen make and fills the modelselect accordingly. */
    function fillModelMenu()
    {
        var selectedMake = $('#nav_select_make').val();
        menuModel.empty();
        menuModel.append('<option value="">Model</option>');

        if (selectedMake === '') {
            return;
        }

        $.get("/api/getModelNames/" + selectedMake, null, function (modelnames)
        {
            for (var key in modelnames) {
                menuModel.append('<option value="' + modelnames[key] + '">' + modelnames[key] + '</option>');
            }
        });
    }

    function navigate()
    {
        if (menuModel.val() === "") {
            window.location.href = "/make/" + encodeURIComponent(menuMake.val());
        } else {
            window.location.href = "/model/" + encodeURIComponent(menuMake.val()) + "/" + encodeURIComponent(menuModel.val());
        }
    }
});