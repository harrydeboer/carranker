$(document).ready(function ()
{
    /** When there is no swearing in the form then a request is send to the server, which sends the email. */
    $('#contact-form').on('submit', function (event)
    {
        let testProfanities = true;
        let mailWords = $('#contactFormMessage').val().split(' ');
        let subjectWords = $('#contactFormSubject').val().split(' ');
        let nameWords = $('#contactFormName').val().split(' ');
        let profanities = $('#profanities').val().split(' ');

        for (let index = 0; index < profanities.length; index++) {
            for (let word = 0; word < subjectWords.length; word++) {
                if ((profanities[index]) === subjectWords[word].toLowerCase()) {
                    testProfanities = false;
                    break;
                }
            }
            for (let word = 0; word < mailWords.length; word++) {
                if ((profanities[index]) === mailWords[word].toLowerCase()) {
                    testProfanities = false;
                    break;
                }
            }
            for (let word = 0; word < nameWords.length; word++) {
                if ((profanities[index]) === nameWords[word].toLowerCase()) {
                    testProfanities = false;
                    break;
                }
            }
        }

        if (!testProfanities) {
            $('#error').html('No swearing please.<BR>');
        } else if (!$('#reCAPTCHAScript').length) {

            reCAPTCHA($(this), 'contactPage');

            event.preventDefault();
        }
    });
});
