$(document).ready(function ()
{
    sessionStorage.lazyLoad = false;
    let checkAll = $('.check-all');

    /** Activate the slider */
    $('#slider-top').carousel();

    /** Via the check all checkbox all checks of this car spec are toggled. */
    checkAll.on('click', function () {
        $("." + $(this).data('spec-name')).prop('checked', $(this).prop('checked'));
    });

    /** All hidden top filters are toggled. */
    $('#filter-top-form-show-all').on('click', function(event)
    {
        /** When all specs are shown the button group must be aligned vertically
         * so that the aspect ranges fit next to the buttons. */
        if ($('#aspects-table:visible').length) {
            $('#choices').addClass('col-xl-12').removeClass('col-xl-4').removeClass('vertical');
        } else {
            $('#choices').addClass('col-xl-4').addClass('vertical').removeClass('col-xl-12');
        }

        if ($('.collapse-choice:visible').length) {
            $('.collapse-choice').toggle();
        } else {
            $('.collapse-choice').toggle().css('display', 'inline');
        }

        $('.collapse-aspects').toggle();
        if ($('.collapse-range:visible').length) {
            $('.collapse-range').toggle();
        } else {
            $('.collapse-range').toggle().css('display', 'flex');
        }

        $('#preferences-dialog').show();
        $('html, body').animate({
            scrollTop: $("#choose-preferences").offset().top
        }, 1000);

        event.preventDefault();
    });

    /** When a user wants the default settings of all filters this function resets to default filtering. */
    $('#filter-top-form-reset').on('click', function (event)
    {
        checkAll.each(function()
        {
            $(this).prop('checked', true);
            $('.' + $(this).data('spec-name')).each(function ()
            {
                $(this).prop('checked', true);
            });
        });

        $('#min-num-votes').val($('#min-num-votes-default').val());

        $('.aspect-element').each(function ()
        {
            $(this).val(1);
        });

        $('.specs-range').each(function ()
        {
            $(this).val("");
        });

        event.preventDefault();
    });

    /** When the user wants to filter the top the filter preferences are shown and scrolled to. */
    $('#choose-preferences').on('click', function ()
    {
        $('#preferences-dialog').toggle();
        $('html, body').animate({
            scrollTop: $("#choose-preferences").offset().top
        }, 1000);
    });

    let numShowMoreLess = parseInt($('#num-show-more-less').val());

    if (typeof sessionStorage.numberOfRows === 'undefined') {
        sessionStorage.numberOfRows = $('#table-top tr').length;
    }

    if (typeof sessionStorage.topTable !== 'undefined') {
        $('#fillable-table').html(sessionStorage.topTable);
        $('#slideshow').html(sessionStorage.slideshow);
        $("#at-least-votes").html('<em>with at least ' + sessionStorage.minNumVotes + ' votes</em>');
        $(".check-all").click();

        $.each(sessionStorage.filterFormSerializedValues.split('&'), function (index, value)
        {
            let valueArray = value.split('=');
            let formElement = $("[name='" + decodeURIComponent(valueArray[0]) + "']");

            formElement.val(decodeURIComponent(valueArray[1]));
            formElement.prop('checked', true);
        });
    }

    showPartTopTable(sessionStorage.numberOfRows);

    /** When more or less trims are shown in the top table the scrolling makes that the button remains in the same place of the window. */
    $('#show-more').on('click', function (event)
    {
        let height = $(document).height();
        let y = $(window).scrollTop();
        let topRows = $('.top-row');
        let topRowsVisible = $('.top-row:visible');

        /** More trims are loaded only when there are not enough trims hidden. Otherwise the hidden trims are shown. */
        if (topRowsVisible.length + numShowMoreLess > topRows.length) {

            /** The extra number of trims is numShowMoreLess except for the case that the current number of trims is not a ten fold.
             * Showing more trims is always the upper ten fold. */
            if (topRowsVisible.length % 10 === 0) {
                sessionStorage.numberOfRows = topRowsVisible.length + numShowMoreLess;
            } else {
                sessionStorage.numberOfRows = topRowsVisible.length + numShowMoreLess - topRowsVisible.length % 10;
            }

            let dataRequest = 'numberOfRows=' + sessionStorage.numberOfRows + '&offset=' +
                topRows.length + '&' + $('#filter-top-form').serialize();

            $.get($(this).attr('href'), dataRequest, function (data)
            {
                let tableTop = $('#table-top');
                tableTop.append(data);

                sessionStorage.numberOfRows = $('#table-top tr').length;
                sessionStorage.topTable = tableTop[0].outerHTML;
                showPartTopTable(sessionStorage.numberOfRows);

                let heightNew = $(document).height();
                $(window).scrollTop(y + heightNew - height);
            });

        } else {
            sessionStorage.numberOfRows = parseInt(sessionStorage.numberOfRows) + numShowMoreLess;
            showPartTopTable(sessionStorage.numberOfRows);

            let heightNew = $(document).height();
            $(window).scrollTop(y + heightNew - height);
        }

        event.preventDefault();
    });

    $('#show-less').on('click', function (event)
    {
        if (sessionStorage.numberOfRows > numShowMoreLess) {
            let height = $(document).height();
            let y = $(window).scrollTop();

            /** Showing less trims is always the lower ten fold when the current number of trims is not a ten fold.
             * Otherwise numShorMoreLess is subtracted for the number of visible trims. */
            if ($('#table-top tr').length % 10 === 0) {
                sessionStorage.numberOfRows = parseInt(sessionStorage.numberOfRows) - numShowMoreLess;
            } else {
                sessionStorage.numberOfRows = parseInt(sessionStorage.numberOfRows) - parseInt(sessionStorage.numberOfRows) % 10;
            }
            showPartTopTable(sessionStorage.numberOfRows);

            let heightNew = $(document).height();
            $(window).scrollTop(y + heightNew - height);
        }

        event.preventDefault();
    });

    $("#filter-top-form").on('submit', function (event)
    {
        /** When there are less trims visible than numShowMoreLess the filtering tries to find numShowMoreLess trims.
         * Otherwise the number of visible trims is asked from the server. */
        let rows;
        let topRowsVisible = $('.top-row:visible');
        if (topRowsVisible.length < numShowMoreLess) {
            rows = numShowMoreLess;
        } else {
            rows = topRowsVisible.length;
        }
        sessionStorage.filterFormSerializedValues = $(this).serialize();

        /** Three pieces of html, the slideshow, the top table and the least number of votes, are filled with the
         * ajax callback data. The data has a splitpoint to split at the right point for the three pieces of html.
         * The number of rows that has to be shown is set with showPartTopTable, the loader is hidden and
         * the slider is activated. Then the window scrolls to the top of the table. */
        $.get($(this).attr('action'), $(this).serialize() + "&numberOfRows=" + rows, function (data)
        {
            let array = data.split(/splitPoint/);
            let tableTopTrs = $('#table-top tr');
            $('#fillable-table').html(array[0]);
            $('#slideshow').html(array[1]);
            $("#at-least-votes").html('<em>with at least ' + array[2] + ' votes</em>');

            sessionStorage.topTable = array[0];
            sessionStorage.slideshow = array[1];
            sessionStorage.minNumVotes = array[2];

            /** Activate the slider */
            $('#carousel').carousel();

            showPartTopTable(tableTopTrs.length);
            sessionStorage.numberOfRows = tableTopTrs.length;

            $('html, body').animate({
                scrollTop: $("#top-cars").offset().top
            }, 1000);
        });

        event.preventDefault();
    });

    /** Only a part of the total table is shown. The minimum number of votes is set on top of the table. */
    function showPartTopTable(numberOfRows)
    {
        $('#table-top tr').hide();
        $('.top-row').slice(0, numberOfRows).show().css('display', 'flex');
        $('#top-or-less-number').html(numberOfRows);
    }
});