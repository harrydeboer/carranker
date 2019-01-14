$(document).ready(function ()
{
    var menuMake = $('#nav_form_make');
    var menuModel = $('#nav_form_model');

    /** Fill the makeselect in the navigation with all the makenames. */
    for (var index = 0; index <makenames.length; index++) {
        menuMake.append('<option value="' + makenames[index] + '">' + makenames[index] + '</option>')
    }

    function reCaptchaNavigate()
    {
        if (!$('#reCaptchaScript').length) {
            var head_ID = document.getElementsByTagName("head")[0];
            var script_element = document.createElement('script');
            script_element.type = 'text/javascript';
            script_element.id = "reCaptchaScript";
            script_element.src = "https://www.google.com/recaptcha/api.js?render=" + reCaptchaKey;
            head_ID.appendChild(script_element);

            $('#reCaptchaScript').on('load', function() {
                grecaptcha.ready(function() {
                    grecaptcha.execute(reCaptchaKey, {action: 'navigate'}).then(function (reCaptchaToken) {
                        $('#reCaptchaTokenNavbar').val(reCaptchaToken);
                        $('#nav-form').submit();
                    });
                });
            });
        } else {
            grecaptcha.execute(reCaptchaKey, {action: 'navigate'}).then(function (reCaptchaToken) {
                $('#reCaptchaTokenNavbar').val(reCaptchaToken);
                $('#nav-form').submit();
            });
        }
    }

    /* The selected options are set to the session on change of the selected make or model. */
    menuMake.on('change', function ()
    {
        sessionStorage.selectedMake = menuMake.val();
        fillModelMenu("");
    });
    menuModel.on('change', function ()
    {
        $("#hideAll").show();
        sessionStorage.selectedModel = menuModel.val();
        reCaptchaNavigate();
    });
    $('#nav_form_submit').on('click', function(event)
    {
        $("#hideAll").show();
        event.preventDefault();
        reCaptchaNavigate();
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
        var selectedMake = $('#nav_form_make').val();
        menuModel.empty();
        menuModel.append('<option value="">Model</option>');

        if (selectedMake === '') {
            return;
        }

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            type: "get", url: "/api/getModelNames/" + selectedMake,
            success: function (modelnames, text)
            {
                for (var index = 0; index < modelnames.length; index++) {
                    var modelnamesArray = modelnames[index].split(';');
                    if (modelnamesArray[0] === selectedMake) {
                        menuModel.append('<option value="' + modelnames[index] + '">' + modelnamesArray[1] + '</option>');
                    }
                }
                menuModel.val(model);
                sessionStorage.selectedModel = model;
            },
            error: function (request, status, error)
            {
            }
        });
    }
});