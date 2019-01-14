jQuery('#selectMakes').on('change', function()
{
    fillModels(jQuery('#selectMakes').val(), '', '#selectModels');
    jQuery('#selectGeneration').empty();
    jQuery('#selectGeneration').append('<option value="">Generation</option>');
    jQuery('#selectSerie').empty();
    if (typeof hasTrimTypes === 'undefined') {
        jQuery('#selectSerie').append('<option value="">New Serie</option>');
    } else {
        jQuery('#selectSerie').append('<option value="">Serie</option>');
    }
    jQuery('#selectTrimType').empty();
    jQuery('#selectTrimType').append('<option value="">New Trim Type</option>');
});

jQuery('#selectModels').on('change', function()
{
    if (jQuery('#modelsForm').length) {
        jQuery('#modelsForm').submit();
    } else {
        jQuery('#trimsForm').submit();
    }
});

if (typeof model === 'undefined') {
    fillModels(jQuery('#selectMakes').val(), '', '#selectModels');
    fillModels(jQuery('#makeSelect').val(), '', '#modelSelect');
} else {
    fillModels(make, model, '#selectModels');
    fillModels(make, model, '#modelSelect');
}

jQuery('#makeSelect').on('change', function()
{
    fillModels(jQuery(this).val(), '', '#modelSelect')
});

function fillModels(make, model, selectId)
{
    if (make === '') {
        return;
    }

    jQuery(selectId).empty();
    if (selectId === '#selectModels') {
        jQuery(selectId).append('<option value="">New Model</option>');
    }
    for (var index = 0; index < modelnames.length; index++) {
        var modelnamesArray = modelnames[index].split(';');
        if (modelnamesArray[0] === make) {
            if (model === modelnamesArray[1]) {
                jQuery(selectId).append('<option value="' + modelnames[index] + '" selected>' + modelnamesArray[1] + '</option>');
            } else {
                jQuery(selectId).append('<option value="' + modelnames[index] + '">' + modelnamesArray[1] + '</option>');
            }
        }
    }
}
