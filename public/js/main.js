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
        menuModel.append('<option>Model</option>');

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
        if (menuModel.val() === "Model") {
            window.location.href = "/make/" + menuMake.val();
        } else {
            window.location.href = "/model/" + menuMake.val() + "/" + menuModel.val();
        }
    }
});