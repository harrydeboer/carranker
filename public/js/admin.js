$('.deleteButton').on('click', function()
{
    $('#deleteId').val($(this).data('id'));
    $('#deleteForm').attr('action', $(this).data('action'));
    $('#reallyDelete').show();
});
