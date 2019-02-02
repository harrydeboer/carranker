if (typeof($.fn.modal) === 'undefined') {
    document.write('<script src="/js/popper.min.js"></script>');
    document.write('<script src="/js/bootstrap.min.js"></script>');
}

$(document).ready(function ()
{
    if ($('#hideAll').is(':visible') === true) {
        $('head').prepend('<link href="/bootstrap-4.2.1/css/bootstrap.min.css" rel="stylesheet" type="text/css" />');
    }

    var $span = $('<span class="fa hidden"></span>').appendTo('body');
    if ($span.css('fontFamily') !== 'FontAwesome' ) {
        // Fallback Link
        $('head').prepend('<link href="/font-awesome-4.7.0/css/font-awesome.min.css" rel="stylesheet">');
    }
    $span.remove();

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