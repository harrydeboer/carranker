$(document).ready(function ()
{
    /** When there is no swearing in the form then a request is send to the server, which sends the email. */
    $('#contact-form').on('submit', function (event)
    {
        var testProfanities = true;
        var mailWords = $('#contactform-message').val().split(' ');
        var subjectWords = $('#contactform-subject').val().split(' ');
        var nameWords = $('#contactform-name').val().split(' ');
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
        } else if (!$('#reCaptchaScript').length) {

            /** Show the loader img */
            $('#hideAll').show();

            /** The reCaptchaScript element is loaded when not present.*/
            var head_ID = document.getElementsByTagName("head")[0];
            var script_element = document.createElement('script');
            script_element.type = 'text/javascript';
            script_element.id = "reCaptchaScript";
            script_element.src = "https://www.google.com/recaptcha/api.js?render=" + $('#reCaptchaKey').val();
            head_ID.appendChild(script_element);

            $('#reCaptchaScript').on('load', function () {
                grecaptcha.ready(function () {
                    grecaptcha.execute($('#reCaptchaKey').val(), {action: 'rate'}).then(function (reCaptchaToken)
                    {
                        $('#reCaptchaToken').val(reCaptchaToken);

                        /** The form is submitted which triggers the current function again but now the recaptcha element
                         * is loaded and the events default is not prevented so that the form will actually submit. */
                        $('#contact-form').submit();
                    });
                });
            });
            event.preventDefault();
        }
    });
});