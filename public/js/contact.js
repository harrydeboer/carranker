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
        } else {
            $('#hideAll').show();

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            serializedForm = $(this).serialize();

            /** The reCaptchaScript element is loaded when not present.*/
            if (!$('#reCaptchaScript').length) {
                var head_ID = document.getElementsByTagName("head")[0];
                var script_element = document.createElement('script');
                script_element.type = 'text/javascript';
                script_element.id = "reCaptchaScript";
                script_element.src = "https://www.google.com/recaptcha/api.js?render=" + $('#reCaptchaKey').val();
                head_ID.appendChild(script_element);
            } else {
                sendMail();
            }

            $('#reCaptchaScript').on('load', function()
            {
                grecaptcha.ready(function ()
                {
                    sendMail();
                });
            });
        }
        event.preventDefault();
    });

    function sendMail()
    {
        /** The recaptcha element is executed and a token is added to the form which is by ajax to the server. */
        grecaptcha.execute($('#reCaptchaKey').val(), {action: 'sendMail'}).then(function (reCaptchaToken)
        {
            serializedForm += "&reCaptchaToken=" + reCaptchaToken;
            $.post('sendMail', serializedForm, function (data)
            {
                $('#hideAll').hide();
                if (data === "1") {
                    $('#success').html("Thank you for your mail!");
                    $('#error').html("");
                } else {
                    $('#success').html("");
                    $('#error').html("Could not deliver mail. Try again later.");
                }
                $('html, body').animate({
                    scrollTop: $("#contactsArticle").offset().top
                }, 1000);
            });
        });
    }
});