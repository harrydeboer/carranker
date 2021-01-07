$(document).ready(function ()
{
    $('#generationSelect').on('change', function()
    {
        showSelectedGeneration();
    });
    showSelectedGeneration();

    /** Show all or part of the specs of a car trim in the car trim modal. */
    $('.showAllSpecs').on('click', function()
    {
        if ($('.collapseSpecs:visible').length) {
            $('.collapseSpecs').hide();
            $('.showAllSpecs').html('Hide all specs');
        } else {
            $('.collapseSpecs').show().css('display', 'flex');
            $('.showAllSpecs').html('Show all specs');
        }
    });

    if (sessionStorage.isThankYou === 'true') {
        sessionStorage.removeItem('isThankYou');
        $('#thankYou').modal('show');
    }

    if ($('.trimType').length > 0) {
        hasTrimTypes = true;
    } else {
        hasTrimTypes = false;
    }

    /** When a user wants to rate a trim then the generation, series and id of the trim are filled in in the rating form. */
    $(".toRateTrim").on('click', function()
    {
        $('.typeInfo').modal('hide');
        showDialog('trim');
        var generation = $(this).data('generation');
        var series = $(this).data('series');
        var IDTrim = $(this).data('idtrim');
        $('#ratingFormGeneration').val(generation);
        $('#ratingFormSeries').val(generation + ';' + series);
        $('#ratingFormTrim').val(generation + ';' + series + ';' + IDTrim);
    });

    $("#showModelDialog").on('click', function()
    {
        showDialog('model');
    });

    $("#showReviewDialog").on('click', function()
    {
        showDialog('review');
    });

    /** A rating can be send to the server when there is no swearing in a review
     * or when the submit is not a review. The required attributes in the html validate the form. */
    $('#ratingForm').on('submit', function(event)
    {
        var testProfanities = true;
        var profanities = $('#profanities').val().split(' ');

        if ($('#ratingFormContent:visible').length) {
            var content = $('#ratingFormContent').val();
            isReview = true;

            var contentWords = content.split(' ');

            for (index = 0; index < profanities.length; index++) {
                for (word = 0; word < contentWords.length; word++) {
                    if ((profanities[index]) === contentWords[word].toLowerCase()) {
                        testProfanities = false;
                        break;
                    }
                }
            }
        }

        if (!testProfanities) {
            $('#reviewWarning').html('No swearing please.<BR>');
            event.preventDefault();
        } else if (!$('#reCaptchaScript').length) {

            /** Show the loader img */
            $('#hideAll').show();

            /** The recaptcha element is loaded and waits for execution. Meanwhile the events default is prevented,
             * because the token is not passed to the form yet. */
            var head_ID = document.getElementsByTagName("head")[0];
            var script_element = document.createElement('script');
            script_element.type = 'text/javascript';
            script_element.id = "reCaptchaScript";
            script_element.src = "https://www.google.com/recaptcha/api.js?render=" + $('#reCaptchaKey').val();
            head_ID.appendChild(script_element);

            $('#reCaptchaScript').on('load', function ()
            {
                grecaptcha.ready(function () {
                    grecaptcha.execute($('#reCaptchaKey').val()).then(
                        function (reCaptchaToken)
                    {
                        $('#reCaptchaToken').val(reCaptchaToken);

                        /** The form is submitted which triggers the current function again but now the recaptcha element
                         * is loaded and the events default is not prevented so that the form will actually submit. */
                        $.post($('#ratingForm').attr('action'), $('#ratingForm').serialize(), function(data)
                        {
                            if (data === 'true') {
                                sessionStorage.isThankYou = "true";
                            }
                            location.reload();
                        });
                    });
                });
            });

            event.preventDefault();
        }
    });

    /** The generations have series and when a generation is selected the right options for the series must be shown.
     * The series have trims and when a series is selected the right options for the trims must be shown. */
    var menuGenerations = $('#ratingFormGeneration');
    var menuSeries = $('#ratingFormSeries');
    var menuSeriesOptions = $('#ratingFormSeries option');
    var menuTrims = $('#ratingFormTrim');
    var menuTrimsOptions = $('#ratingFormTrim option');
    menuSeriesOptions.hide();
    menuTrimsOptions.hide();

    menuGenerations.on('change', function()
    {
        var selectedGeneration = $(this).val();
        menuSeriesOptions.hide();
        menuTrimsOptions.hide();
        menuSeries.val('');
        menuTrims.val('');
        menuSeriesOptions.each(function()
        {
            if ($(this).val() !== '') {
                var seriesArray = $(this).val().split(';');
                if (seriesArray[0] === selectedGeneration) {
                    $(this).show();
                }
            } else {
                $(this).show();
            }
        });
    });

    menuSeries.on('change', function()
    {
        var selectedGeneration = $(this).val();
        var selectedSeries = $(this).val();
        menuTrimsOptions.hide();
        menuTrims.val('');
        menuTrimsOptions.each(function()
        {
            if ($(this).val() !== '') {
                var trimArray = $(this).val().split(';');
                if (trimArray[0] + ';' + trimArray[1] === selectedSeries) {
                    $(this).show();
                    if (hasTrimTypes === false) {
                        $(this).prop('selected', true);
                    } else {
                        $(this).show();
                    }
                }
            } else {
                $(this).show();
            }
        });
    });

    /** The dialog with the rating form can have three shapes. When a trim is viewed and the user wants to rate
     * this trim then the user does not need to specify the right generation, series or trim. When the form is selected
     * from the modelpage the user needs to specify the generation, series and/or trim and these are then required.
     * Finally when the user wants to write a review the textarea is displayed in the form and made required. */
    function showDialog(typeShow)
    {
        var winW = $(window).width();
        var winH = $(window).height();
        $('#ratingFormGeneration').show();
        $('#ratingFormSeries').show();
        $('#ratingFormTrim').show();
        $("#divArea").show();
        if (hasTrimTypes === false) {
            $('#ratingFormTrim').hide();
        }
        if (typeShow === 'review') {
            $("#ratingFormContent").prop('required',true);
        } else {
            $("#divArea").hide();
            $("#ratingFormContent").prop('required',false);

            if (typeShow === 'trim') {
                $('#ratingFormGeneration').hide();
                $('#ratingFormSeries').hide();
                $('#ratingFormTrim').hide();
            }
        }
    }

    function showSelectedGeneration()
    {
        $('.generations').hide();
        $('#generation' + $("#generationSelect option:selected").val()).show();
    }
});
