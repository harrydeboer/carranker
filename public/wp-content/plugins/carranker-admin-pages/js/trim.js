jQuery('#selectGeneration').on('change', function()
{
    fillSeries();
});

jQuery('#selectSerie').on('change', function()
{
    if (jQuery('#selectTrimType').length) {
        fillTrims();
    } else {
        jQuery('#trimsForm').submit();
    }
});

jQuery('#selectTrimType').on('change', function()
{
    jQuery('#trimsForm').submit();
});

jQuery('#deleteTrim').on('click', function() {
    jQuery('#realyDeleteTrim').modal('show');
});

fillSeries();

function fillSeries()
{
    var selectedGeneration = jQuery('#selectGeneration').val();

    if (selectedGeneration === '') {
        return;
    }

    jQuery('#selectSerie').empty();
    if (typeof hasTrimTypes === 'undefined') {
        jQuery('#selectSerie').append('<option value="">New Serie</option>');
    } else {
        jQuery('#selectSerie').append('<option value="">Serie</option>');
    }

    if (typeof generationsSeriesTrims !== 'undefined') {
        for (var index in generationsSeriesTrims[selectedGeneration]) {
            var trimId = generationsSeriesTrims[selectedGeneration][index];
            if (hasTrimTypes === 0) {
                if (trimId == trimIdSelect) {
                    jQuery('#selectSerie').append('<option value="' + trimId + '" selected>' + index + '</option>');
                } else {
                    jQuery('#selectSerie').append('<option value="' + trimId + '">' + index + '</option>');
                }
            } else {
                jQuery('#selectSerie').append('<option value="' + index + '">' + index + '</option>');
            }
        }
    }
}

function fillTrims()
{
    var selectedGeneration = jQuery('#selectGeneration').val();
    var selectedSerie = jQuery('#selectSerie').val();

    if (selectedSerie === '') {
        return;
    }

    jQuery('#selectTrimType').empty();
    jQuery('#selectTrimType').append('<option value="">New Trim Type</option>');

    if (typeof generationsSeriesTrims !== 'undefined') {
        for (var index in generationsSeriesTrims[selectedGeneration][selectedSerie]) {
            var trimId = generationsSeriesTrims[selectedGeneration][selectedSerie][index];
            jQuery('#selectTrimType').append('<option value="' + trimId + '">' + index + '</option>');
        }
    }
}
