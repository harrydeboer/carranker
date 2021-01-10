$(document).ready(function ()
{
    let menuMake = $('#nav_select_make');
    let menuModel = $('#nav_select_model');

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
        let selectedMake = menuMake.val();
        menuModel.empty();
        menuModel.append('<option value="">Model</option>');

        if (selectedMake === '') {
            return;
        }

        $.get("/api/getModelNames/" + selectedMake, null, function (modelNames)
        {
            $.each(modelNames, function (index, value) {
                menuModel.append('<option value="' + value + '">' + value + '</option>');
            });
        });
    }

    function navigate()
    {
        if (menuModel.val() === "") {
            window.location.href = "/make/" + encodeURIComponent(menuMake.val());
        } else {
            window.location.href = "/model/" + encodeURIComponent(menuMake.val()) + "/" +
                encodeURIComponent(menuModel.val());
        }
    }
});

function reCAPTCHA(form, page)
{
    /** Show the loader img */
    $('#hideAll').show();

    /** The reCAPTCHAScript element is loaded when not present.*/
    let headId = document.getElementsByTagName("head")[0];
    let scriptElement = document.createElement('script');
    scriptElement.type = 'text/javascript';
    scriptElement.id = "reCAPTCHAScript";
    scriptElement.src = "https://www.google.com/recaptcha/api.js?render=" + $('#reCAPTCHAKey').val();
    headId.appendChild(scriptElement);

    $('#reCAPTCHAScript').on('load',function ()
    {
        grecaptcha.ready(function ()
        {
            grecaptcha.execute(
                $('#reCAPTCHAKey').val(),
                {action: 'validateReCAPTCHA'},
                false)
                .then(
                function (reCAPTCHAToken)
                {
                    $('#reCAPTCHAToken').val(reCAPTCHAToken);

                    if (page === 'contactPage') {
                        /** The form is submitted which triggers the current function again but now the reCAPTCHA element
                         * is loaded and the events default is not prevented so that the form will actually submit. */
                        form.submit();
                    } else if (page === 'modelPage') {

                        /** The form is submitted which triggers the current function again but now the reCAPTCHA element
                         * is loaded and the events default is not prevented so that the form will actually submit. */
                        $.post(form.attr('action'), form.serialize(), function (data) {
                            if (data === 'true') {
                                sessionStorage.isThankYou = "true";
                            }
                            location.reload();
                        });
                    }
                });
        });
    });
}
