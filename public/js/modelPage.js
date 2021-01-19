$(document).ready(function () {
  let ratingForm = $('#rating-form');
  let menuGenerations = $('#rating-form-generation');
  let menuSeries = $('#rating-form-series');
  let menuSeriesOptions = $('#rating-form-series option');
  let menuTrims = $('#rating-form-trim');
  let menuTrimsOptions = $('#rating-form-trim option');
  let ratingFormContent = $('#rating-form-content');

  $('#generation-select').on('change', function() {
    showSelectedGeneration();
  });
  showSelectedGeneration();

  /**
   * Show all or part of the specs of a car trim in the car trim modal.
   */
  $('.show-all-specs').on('click', function() {
    if ($('.collapse-specs:visible').length) {
      $('.collapse-specs').hide();
      $('.show-all-specs').html('Show all specs');
    } else {
      $('.collapse-specs').show().css('display', 'flex');
      $('.show-all-specs').html('Hide all specs');
    }
  });

  if (sessionStorage.isThankYou === 'true') {
    sessionStorage.removeItem('isThankYou');
    $('#thank-you').modal('show');
  }

  /**
   * When a user wants to rate a trim then the generation, series and id of the trim are filled in in the rating form.
   */
  $(".to-rate-trim").on('click', function() {
    $('.type-info').modal('hide');
    showDialog('trim');
    let generation = $(this).data('generation');
    let series = $(this).data('series');
    let idTrim = $(this).data('id-trim');
    menuGenerations.val(generation);
    menuSeries.val(generation + ';' + series);
    menuTrims.val(generation + ';' + series + ';' + idTrim);
  });

  $("#show-model-dialog").on('click', function() {
    showDialog('model');
  });

  $("#show-review-dialog").on('click', function() {
    showDialog('review');
  });

  /**
   * A rating can be send to the server when there is no swearing in a review
   * or when the submit is not a review. The required attributes in the html validate the form.
   */
  ratingForm.on('submit', function(event) {
    let trimNameArray = menuTrims.val().split(';');
    $('#rating-form-trim-id').val(trimNameArray[2]);

    let testProfanities = true;
    let profanities = $('#profanities').val().split(' ');

    if ($('#rating-form-content:visible').length) {
      let content = $('#rating-form-content').val();

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
      $('#review-warning').html('No swearing please.<BR>');
      event.preventDefault();
    } else if (!$('#re-captcha-script').length) {

      reCAPTCHA(ratingForm, 'modelPage')

      event.preventDefault();
    }
  });

  /**
   * The generations have series and when a generation is selected the right options for the series must be shown.
   * The series have trims and when a series is selected the right options for the trims must be shown.
   */
  menuSeriesOptions.hide();
  menuTrimsOptions.hide();
  menuGenerations.on('change', function() {
    let selectedGeneration = $(this).val();
    menuSeriesOptions.hide();
    menuTrimsOptions.hide();
    menuSeries.val('');
    menuTrims.val('');
    menuSeriesOptions.each(function() {
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
  menuSeries.on('change', function() {
    let selectedSeries = $(this).val();
    menuTrimsOptions.hide();
    menuTrims.val('');
    menuTrimsOptions.each(function() {
      if ($(this).val() !== '') {
        let trimArray = $(this).val().split(';');
        if (trimArray[0] + ';' + trimArray[1] === selectedSeries) {
          $(this).show();
          if ($('.trim-type').length === 0) {
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

  let maxCharactersInReview = ratingFormContent.attr('maxlength');
  ratingFormContent.on('keyup', function() {
    $('#characters-left').text(maxCharactersInReview - $(this).val().length);
  });

  /**
   * The dialog with the rating form can have three shapes. When a trim is viewed and the user wants to rate
   * this trim then the user does not need to specify the right generation, series or trim. When the form is selected
   * from the model page the user needs to specify the generation, series and/or trim and these are then required.
   * Finally when the user wants to write a review the textarea is displayed in the form and made required.
   */
  function showDialog(typeShow) {
    menuGenerations.show();
    menuSeries.show();
    menuTrims.show();
    let divTextarea = $('#div-area');
    divTextarea.show();
    if ($('.trimType').length === 0) {
      menuTrims.hide();
    }
    if (typeShow === 'review') {
      $("#rating-form-content").prop('required',true);
    } else {
      divTextarea.hide();
      $("#rating-form-content").prop('required',false);

      if (typeShow === 'trim') {
        menuGenerations.hide();
        menuSeries.hide();
        menuTrims.hide();
      }
    }
  }

  function showSelectedGeneration() {
    $('.generations').hide();
    $('#generation' + $("#generation-select option:selected").val()).show();
  }

  /**
   * No html (<>[]) allowed from keypress or copy paste in the review.
   */
  ratingFormContent.keypress(function(event) {
    let errorMessage = $('#no-html-allowed');
    errorMessage.text('');

    /** No <>[] characters allowed. */
    if (
      event.which === 60
      || event.which === 62
      || event.which === 91
      || event.which === 93
    ) {
      errorMessage.text('No html allowed');

      return false;
    }
  });
  ratingFormContent.bind('paste', function(event) {
    let errorMessage = $('#no-html-allowed');
    let pastedData = event.originalEvent.clipboardData.getData('text');
    errorMessage.text('');

    if (
      pastedData.indexOf('<') !== -1
      || pastedData.indexOf('>') !== -1
      || pastedData.indexOf('[') !== -1
      || pastedData.indexOf(']') !== -1
    ) {
      errorMessage.text('No html allowed');

      return false;
    }
  });
});
