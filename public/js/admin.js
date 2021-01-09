$('.deleteButton').on('click', function(event)
{
    $('#deleteId').val($(this).data('id'));
    $('#deleteForm').attr('action', $(this).data('action'));
    $('#reallyDelete').show();
});