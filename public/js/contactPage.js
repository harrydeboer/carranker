$(document).ready(function ()
{
    /** When there is no swearing in the form then a request is send to the server, which sends the email. */
    $('#contact-form').on('submit', function (event)
    {
        var testProfanities = true;
        var mailWords = $('#contactFormMessage').val().split(' ');
        var subjectWords = $('#contactFormSubject').val().split(' ');
        var nameWords = $('#contactFormName').val().split(' ');
        var profanities = $('#profanities').val().split(' ');

        for (var index = 0; index < profanities.length; index++) {
            for (word = 0; word < subjectWords.length; word++) {
                if ((profanities[index]) === subjectWords[word].toLowerCase()) {
                    testProfanities = false;
                    break;
                }
            }
            for (word = 0; word < mailWords.length; word++) {
                if ((profanities[index]) === mailWords[word].toLowerCase()) {
                    testProfanities = false;
                    break;
                }
            }
            for (word = 0; word < nameWords.length; word++) {
                if ((profanities[index]) === nameWords[word].toLowerCase()) {
                    testProfanities = false;
                    break;
                }
            }
        }

        if (!testProfanities) {
            $('#error').html('No swearing please.<BR>');
        } else if (!$('#reCAPTCHAScript').length) {

            /** Show the loader img */
            $('#hideAll').show();

            /** The reCAPTCHAScript element is loaded when not present.*/
            var head_ID = document.getElementsByTagName("head")[0];
            var script_element = document.createElement('script');
            script_element.type = 'text/javascript';
            script_element.id = "reCAPTCHAScript";
            script_element.src = "https://www.google.com/recaptcha/api.js?render=" + $('#reCAPTCHAKey').val();
            head_ID.appendChild(script_element);

            $('#reCAPTCHAScript').on('load', function () {
                grecaptcha.ready(function () {
                    grecaptcha.execute($('#reCAPTCHAKey').val(), {action: 'validateCAPTCHA'}).then(
                        function (reCAPTCHAToken)
                    {
                        $('#reCAPTCHAToken').val(reCAPTCHAToken);

                        /** The form is submitted which triggers the current function again but now the reCAPTCHA element
                         * is loaded and the events default is not prevented so that the form will actually submit. */
                        $('#contact-form').submit();
                    });
                });
            });
            event.preventDefault();
        }
    });
});
