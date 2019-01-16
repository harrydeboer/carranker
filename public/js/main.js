$(document).ready(function ()
{
    var menuMake = $('#nav_select_make');
    var menuModel = $('#nav_select_model');

    /* The selected options are set to the session on change of the selected make or model. */
    menuMake.on('change', function ()
    {
        sessionStorage.selectedMake = menuMake.val();
        fillModelMenu("");
    });
    menuModel.on('change', function ()
    {
        sessionStorage.selectedModel = menuModel.val();
        navigate();
    });

    $('#search_form_submit').on('click', function(event)
    {
        if ($('#search_form_text').val() === "") {
            event.preventDefault();
            if (menuMake.val() === "") {
            } else {
                navigate();
            }
        }
    });

    /** Initialize the make and model selects and set the selected option when there is one in the session. */
    if (typeof sessionStorage.selectedMake !== 'undefined') {
        menuMake.val(sessionStorage.selectedMake);
    }

    /** The current model is selected in the navigation model select and stored in the session. */
    if (controller === 'modelpage') {
        menuMake.val(makename);
        sessionStorage.selectedMake = menuMake.val();
        fillModelMenu(makename + ";" + modelname);
    } else if (typeof sessionStorage.selectedModel !== 'undefined') {
        fillModelMenu(sessionStorage.selectedModel);
    }

    /* Determines the car models related to the chosen make and fills the modelselect accordingly. */
    function fillModelMenu(model)
    {
        var selectedMake = $('#nav_select_make').val();
        menuModel.empty();
        menuModel.append('<option value="">Model</option>');

        if (selectedMake === '') {
            return;
        }

        $.get("/api/getModelNames/" + selectedMake, null, function (modelnames)
        {
            for (var index = 0; index < modelnames.length; index++) {
                var modelnamesArray = modelnames[index].split(';');
                if (modelnamesArray[0] === selectedMake) {
                    menuModel.append('<option value="' + modelnames[index] + '">' + modelnamesArray[1] + '</option>');
                }
            }
            menuModel.val(model);
            sessionStorage.selectedModel = model;
        });
    }

    function navigate()
    {
        var menuArray = menuModel.val().split(';');
        if (menuModel.val() === "") {
            window.location.href = "/make/" + menuMake.val();
        } else {
            window.location.href = "/model/" + menuArray[0] + "/" + menuArray[1];
        }
    }
});