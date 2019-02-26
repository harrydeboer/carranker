$(document).ready(function ()
{
    sessionStorage.lazyLoad = false;

    /** Activate the slider */
    $('#sliderTop').carousel();

    /** Via the checkall checkbox all checks of this car spec are toggled. */
    $(".checkAll").on('click', function () {
        $("." + $(this).data('specname')).prop('checked', $(this).prop('checked'));
    });

    /** All hidden top filters are toggled. */
    $('#filterTopFormShowAll').on('click', function(event)
    {
        /** When all specs are shown the button group must be aligned vertically
         * so that the aspect ranges fit next to the buttons. */
        if ($('#aspectsTable:visible').length) {
            $('#choices').addClass('col-xl-12').removeClass('col-xl-4').removeClass('vertical');
        } else {
            $('#choices').addClass('col-xl-4').addClass('vertical').removeClass('col-xl-12');
        }

        if ($('.collapseChoice:visible').length) {
            $('.collapseChoice').toggle();
        } else {
            $('.collapseChoice').toggle().css('display', 'inline');
        }

        $('.collapseAspects').toggle();
        if ($('.collapseRange:visible').length) {
            $('.collapseRange').toggle();
        } else {
            $('.collapseRange').toggle().css('display', 'flex');
        }

        $('#preferencesDialog').show();
        $('html, body').animate({
            scrollTop: $("#choosePreferences").offset().top
        }, 1000);

        event.preventDefault();
    });

    /** When a user wants the default settings of all filters this function resets to default filtering. */
    $('#filterTopFormReset').on('click', function (event)
    {
        $('.checkAll').each(function()
        {
            $(this).prop('checked', true);
            $('.' + $(this).data('specname')).each(function ()
            {
                $(this).prop('checked', true);
            });
        });

        $('#minNumVotes').val($('#minNumVotesDefault').val());

        $('.aspectElement').each(function ()
        {
            $(this).val(1);
        });

        $('.specsRange').each(function ()
        {
            $(this).val("");
        });

        event.preventDefault();
    });

    /** When the user wants to filter the top the filter preferences are shown and scrolled to. */
    $('#choosePreferences').on('click', function ()
    {
        $('#preferencesDialog').toggle();
        $('html, body').animate({
            scrollTop: $("#choosePreferences").offset().top
        }, 1000);
    });

    numShowMoreLess = parseInt($('#numShowMoreLess').val());
    if (typeof sessionStorage.numberOfRows === 'undefined') {
        sessionStorage.numberOfRows = $('#tableTop tr').length;
    }
    showPartTopTable(sessionStorage.numberOfRows);

    /** When more or less trims are shown in the top table the scrolling makes that the button remains in the same place of the window. */
    $('#showMore').on('click', function ()
    {
        var height = $(document).height();
        var y = $(window).scrollTop();

        /** More trims are loaded only when there are not enough trims hidden. Otherwise the hidden trims are shown. */
        if ($('.topRow:visible').length + numShowMoreLess > $('.topRow').length) {

            /** The extra number of trims is numShowMoreLess except for the case that the current number of trims is not a ten fold.
             * Showing more trims is always the upper ten fold. */
            if ($('.topRow:visible').length % 10 === 0) {
                sessionStorage.numberOfRows = $('.topRow:visible').length + numShowMoreLess;
            } else {
                sessionStorage.numberOfRows = $('.topRow:visible').length + numShowMoreLess - $('.topRow:visible').length % 10;
            }

            $.get('showMoreTopTable/' + sessionStorage.numberOfRows + '/' + $('.topRow').length, "", function (data)
            {
                $('#tableTop').append(data);

                sessionStorage.numberOfRows = $('#tableTop tr').length;
                showPartTopTable(sessionStorage.numberOfRows);

                var heightNew = $(document).height();
                $(window).scrollTop(y + heightNew - height);
            });
        } else {
            sessionStorage.numberOfRows = parseInt(sessionStorage.numberOfRows) + numShowMoreLess;
            showPartTopTable(sessionStorage.numberOfRows);

            var heightNew = $(document).height();
            $(window).scrollTop(y + heightNew - height);
        }
    });

    $('#showLess').on('click', function ()
    {
        if (sessionStorage.numberOfRows > numShowMoreLess) {
            var height = $(document).height();
            var y = $(window).scrollTop();

            /** Showing less trims is always the lower ten fold when the current number of trims is not a ten fold.
             * Otherwise numShorMoreLess is substracted for the number of visible trims. */
            if ($('#tableTop tr').length % 10 === 0) {
                sessionStorage.numberOfRows = parseInt(sessionStorage.numberOfRows) - parseInt(numShowMoreLess);
            } else {
                sessionStorage.numberOfRows = parseInt(sessionStorage.numberOfRows) - parseInt(sessionStorage.numberOfRows) % 10;
            }
            showPartTopTable(sessionStorage.numberOfRows);

            var heightNew = $(document).height();
            $(window).scrollTop(y + heightNew - height);
        }
    });

    $("#filterTopForm").on('submit', function (event)
    {
        /** When there are less trims visible than numShowMoreLess the filtering tries to find numShowMoreLess trims.
         * Otherwise the number of visible trims is asked from the server. */
        var rows;
        if ($('.topRow:visible').length < numShowMoreLess) {
            rows = numShowMoreLess;
        } else {
            rows = $('.topRow:visible').length;
        }

        /** Three pieces of html, the slideshow, the top table and the least number of votes, are filled with the
         * ajax callback data. The data has a splitpoint to split at the right point for the three pieces of html.
         * The number of rows that has to be shown is set with showPartTopTable, the loader is hidden and
         * the slider is activated. Then the window scrolls to the top of the table. */
        $.get('filterTop', $(this).serialize() + "&numberOfRows=" + rows, function (data)
        {
            var array = data.split(/splitPoint/);
            $('#fillableTable').html(array[0]);
            $('#slideshow').html(array[1]);
            $("#atLeastVotes").html('<em>with at least ' + array[2] + ' votes</em>');

            /** Activate the slider */
            $('#carousel').carousel();

            showPartTopTable($('#tableTop tr').length);
            sessionStorage.numberOfRows = $('#tableTop tr').length;

            $('html, body').animate({
                scrollTop: $("#topCars").offset().top
            }, 1000);
        });

        event.preventDefault();
    });

    /** Only a part of the total table is shown. The minimum number of votes is set on top of the table. */
    function showPartTopTable(numberOfRows)
    {
        $('#tableTop tr').hide();
        $('.topRow').slice(0, numberOfRows).show().css('display', 'flex');
        $('#topOrLessNumber').html(numberOfRows);
    }
});
