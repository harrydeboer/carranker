jQuery('#selectMakes').on('change', function()
{
    jQuery('#makesForm').submit();
});

jQuery('#deleteMake').on('click', function() {
    jQuery('#realyDeleteMake').modal('show');
});