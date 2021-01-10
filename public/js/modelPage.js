$(document).ready(function ()
{
    let ratingForm = $('#ratingForm');
    let menuGenerations = $('#ratingFormGeneration');
    let menuSeries = $('#ratingFormSeries');
    let menuSeriesOptions = $('#ratingFormSeries option');
    let menuTrims = $('#ratingFormTrim');
    let menuTrimsOptions = $('#ratingFormTrim option');

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

    /** When a user wants to rate a trim then the generation, series and id of the trim are filled in in the rating form. */
    $(".toRateTrim").on('click', function()
    {
        $('.typeInfo').modal('hide');
        showDialog('trim');
        let generation = $(this).data('generation');
        let series = $(this).data('series');
        let idTrim = $(this).data('id-trim');
        menuGenerations.val(generation);
        menuSeries.val(generation + ';' + series);
        menuTrims.val(generation + ';' + series + ';' + idTrim);
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
    ratingForm.on('submit', function(event)
    {
        let trimNameArray = menuTrims.val().split(';');
        $('#ratingFormTrimId').val(trimNameArray[2]);

        let testProfanities = true;
        let profanities = $('#profanities').val().split(' ');

        if ($('#ratingFormContent:visible').length) {
            let content = $('#ratingFormContent').val();

            let contentWords = content.split(' ');

            for (let index = 0; index < profanities.length; index++) {
                for (let word = 0; word < contentWords.length; word++) {
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
        } else if (!$('#reCAPTCHAScript').length) {

            reCAPTCHA(ratingForm, 'modelPage')

            event.preventDefault();
        }
    });

    /** The generations have series and when a generation is selected the right options for the series must be shown.
     * The series have trims and when a series is selected the right options for the trims must be shown. */
    menuSeriesOptions.hide();
    menuTrimsOptions.hide();
    menuGenerations.on('change', function()
    {
        let selectedGeneration = $(this).val();
        menuSeriesOptions.hide();
        menuTrimsOptions.hide();
        menuSeries.val('');
        menuTrims.val('');
        menuSeriesOptions.each(function()
        {
            if ($(this).val() !== '') {
                let seriesArray = $(this).val().split(';');
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
        let selectedSeries = $(this).val();
        menuTrimsOptions.hide();
        menuTrims.val('');
        menuTrimsOptions.each(function()
        {
            if ($(this).val() !== '') {
                let trimArray = $(this).val().split(';');
                if (trimArray[0] + ';' + trimArray[1] === selectedSeries) {
                    $(this).show();
                    if ($('.trimType').length === 0) {
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
        menuGenerations.show();
        menuSeries.show();
        menuTrims.show();
        let divTextarea = $('#divArea');
        divTextarea.show();
        if ($('.trimType').length === 0) {
            menuTrims.hide();
        }
        if (typeShow === 'review') {
            $("#ratingFormContent").prop('required',true);
        } else {
            divTextarea.hide();
            $("#ratingFormContent").prop('required',false);

            if (typeShow === 'trim') {
                menuGenerations.hide();
                menuSeries.hide();
                menuTrims.hide();
            }
        }
    }

    function showSelectedGeneration()
    {
        $('.generations').hide();
        $('#generation' + $("#generationSelect option:selected").val()).show();
    }
});
