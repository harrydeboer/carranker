$(document).ready(function () {
  $('.delete-button').on('click', function () {
    $('#delete-id').val($(this).data('id'));
    $('#delete-form').attr('action', $(this).data('action'));
    $('#really-delete').show();
  });
});
