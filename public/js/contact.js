$(document).ready(function ()
{
    /** When there is no swearing then a request is send to the server, which sends the email. */
    $('#contact-form').on('submit', function (event)
    {
        var testProfanities = true;
        var mailWords = $('#contactform-message').val().split(' ');
        var subjectWords = $('#contactform-subject').val().split(' ');
        var nameWords = $('#contactform-name').val().split(' ');

        for (var index = 0; index < profanities.length; index++) {
            for (word = 0; word < subjectWords.length; word++) {
                if ((profanities[index]) === subjectWords[word]) {
                    testProfanities = false;
                    break;
                }
            }
            for (word = 0; word < mailWords.length; word++) {
                if ((profanities[index]) === mailWords[word]) {
                    testProfanities = false;
                    break;
                }
            }
            for (word = 0; word < nameWords.length; word++) {
                if ((profanities[index]) === nameWords[word]) {
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
            var head_ID = document.getElementsByTagName("head")[0];
            var script_element = document.createElement('script');
            script_element.type = 'text/javascript';
            script_element.id = "reCaptchaScript";
            script_element.src = "https://www.google.com/recaptcha/api.js?render=" + reCaptchaKey;
            head_ID.appendChild(script_element);

            $('#reCaptchaScript').on('load', function() {
                grecaptcha.ready(function () {
                    grecaptcha.execute(reCaptchaKey, {action: 'sendMail'}).then(function (reCaptchaToken) {
                        serializedForm += "&reCaptchaToken=" + reCaptchaToken;
                        $.post('sendMail', serializedForm, function (data) {
                            $('#hideAll').hide();
                            if (data) {
                                $('#success').html(data);
                            } else {
                                $('#error').html(data);
                            }
                            $('html, body').animate({
                                scrollTop: $("#contactsArticle").offset().top
                            }, 1000);
                        });
                    });
                });
            });
        }
        event.preventDefault();
    });
});