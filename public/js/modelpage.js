$(document).ready(function ()
{
    $('#generationSelect').on('change', function()
    {
        showSelectedGeneration();
    });
    showSelectedGeneration();

    /** When a trim is viewed all specs can be shown, but this will toggle the selected generation also so the right
     * selected generation must be shown again. */
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

    if (isThankYou === true) {
        $('#thankyou').modal('show');
    }

    $(".toRateTrim").on('click', function()
    {
        $('.typeInfo').modal('hide');
        showDialog('trim');
        var generation = $(this).data('generation');
        var serie = $(this).data('serie');
        var IDTrim = $(this).data('idtrim');
        $('#rating_form_generation').val(generation);
        showSeriesSelect();
        $('#rating_form_serie').val(generation + ' ' + serie);
        showTrimsSelect();
        $('#rating_form_trim').val(IDTrim);
    });

    $("#showModelDialog").on('click', function()
    {
        showDialog('model');
    });

    $("#showReviewDialog").on('click', function()
    {
        showDialog('review');
    });

    /** A rating can be send to the server when there is no swearing for a review
     * or when the submit is not a review the required attributes in the html validate the form. */
    $('#rating-form').on('submit', function(event)
    {
        var testProfanities = true;

        if ($('#rating_form_content:visible').length) {
            var content = $('#rating_form_content').val();
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

            var head_ID = document.getElementsByTagName("head")[0];
            var script_element = document.createElement('script');
            script_element.type = 'text/javascript';
            script_element.id = "reCaptchaScript";
            script_element.src = "https://www.google.com/recaptcha/api.js?render=" + reCaptchaKey;
            head_ID.appendChild(script_element);

            $('#reCaptchaScript').on('load', function () {
                grecaptcha.ready(function () {
                    grecaptcha.execute(reCaptchaKey, {action: 'rate'}).then(function (reCaptchaToken)
                    {
                        $('#reCaptchaToken').val(reCaptchaToken);
                        $('#rating-form').submit();
                    });
                });
            });
            event.preventDefault();
        }
    });

    /** The generations have series and when a generation is selected the right options for the series must be filled in.
     * The series have trims and when a serie is selected the right options for the trims must be filled in. */
    var menuGenerations = $('#rating_form_generation');
    var menuSeries = $('#rating_form_serie');
    var menuTrims = $('#rating_form_trim');
    for (var gen in generationsSeriesTrims) {
        menuGenerations.append('<option value="' + gen + '">' + gen + '</option>');
    }
    showSeriesSelect();
    showTrimsSelect();
    menuGenerations.on('change', function()
    {
        showSeriesSelect();
    });
    menuSeries.on('change', function()
    {
        showTrimsSelect();
    });

    function showSeriesSelect()
    {
        var selectedGen = $('#rating_form_generation').val();

        menuSeries.empty();
        menuSeries.append('<option value="">Series</option>');
        if ($('#rating_form_generation option:selected').text() === 'Generation') {
            return;
        }
        for (var serie in generationsSeriesTrims[selectedGen]) {
            menuSeries.append('<option value="' + selectedGen + ' ' + serie + '">' + serie + '</option>');
        }
    }

    function showTrimsSelect()
    {
        var selectedGen = menuGenerations.val();
        var selectedSerie = $('#rating_form_serie option:selected').text();

        menuTrims.empty();

        menuTrims.append('<option value="">Trims</option>');
        if ($('#rating_form_serie option:selected').text() === 'Series') {
            return;
        }
        for (var trim in generationsSeriesTrims[selectedGen][selectedSerie]) {
            var trimId = generationsSeriesTrims[selectedGen][selectedSerie][trim];
            menuTrims.append('<option value="' + trimId + '">' + trim + '</option>');
            if (hasTrimTypes === false) {
                $('#rating_form_trim').val(trimId);
            }
        }
    }

    /** The dialog with the rating form can have three forms. When a trim is viewed and the user wants to rate
     * this trim then the user does not need to specify the right generation, serie or trim. When from the modelpage the
     * rate form is shown the user needs to specify the generation, serie and/or trim and these are then required.
     * Finally when the user wants to write a review the textarea is displayed in the form and made required. */
    function showDialog(typeShow)
    {
        var winW = $(window).width();
        var winH = $(window).height();
        $('#rating_form_generation').show();
        $('#rating_form_serie').show();
        $('#rating_form_trim').show();
        $("#divArea").show();
        if (hasTrimTypes === false) {
            $('#rating_form_trim').hide();
        }
        if (typeShow === 'review') {
            $("#rating_form_content").prop('required',true);
        } else {
            $("#divArea").hide();
            $("#rating_form_content").prop('required',false);

            if (typeShow === 'trim') {
                $('#rating_form_generation').hide();
                $('#rating_form_serie').hide();
                $('#rating_form_trim').hide();
            }
        }
    }

    function showSelectedGeneration()
    {
        $('.generations').hide();
        $('#generation' + $("#generationSelect option:selected").val()).show();
    }
});