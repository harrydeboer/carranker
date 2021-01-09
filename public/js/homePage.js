$(document).ready(function ()
{
    sessionStorage.lazyLoad = false;
    let checkAll = $('.checkAll');

    /** Activate the slider */
    $('#sliderTop').carousel();

    /** Via the checkall checkbox all checks of this car spec are toggled. */
    checkAll.on('click', function () {
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
        checkAll.each(function()
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

    let numShowMoreLess = parseInt($('#numShowMoreLess').val());

    if (typeof sessionStorage.numberOfRows === 'undefined') {
        sessionStorage.numberOfRows = $('#tableTop tr').length;
    }

    if (typeof sessionStorage.topTable !== 'undefined') {
        $('#fillableTable').html(sessionStorage.topTable);
        $('#slideshow').html(sessionStorage.slideshow);
        $("#atLeastVotes").html('<em>with at least ' + sessionStorage.minNumVotes + ' votes</em>');
        $(".checkAll").click();

        $.each(sessionStorage.filterTopForm.split('&'), function (index, elem)
        {
            let elemArray = elem.split('=');
            let element = $("[name='" + decodeURIComponent(elemArray[0]) + "']");

            element.val(decodeURIComponent(elemArray[1]));
            element.prop('checked', true);
        });
    }

    showPartTopTable(sessionStorage.numberOfRows);

    /** When more or less trims are shown in the top table the scrolling makes that the button remains in the same place of the window. */
    $('#showMore').on('click', function (event)
    {
        let height = $(document).height();
        let y = $(window).scrollTop();
        let topRows = $('.topRow');
        let topRowsVisible = $('.topRow:visible');

        /** More trims are loaded only when there are not enough trims hidden. Otherwise the hidden trims are shown. */
        if (topRowsVisible.length + numShowMoreLess > topRows.length) {

            /** The extra number of trims is numShowMoreLess except for the case that the current number of trims is not a ten fold.
             * Showing more trims is always the upper ten fold. */
            if (topRowsVisible.length % 10 === 0) {
                sessionStorage.numberOfRows = topRowsVisible.length + numShowMoreLess;
            } else {
                sessionStorage.numberOfRows = topRowsVisible.length + numShowMoreLess - topRowsVisible.length % 10;
            }

            let dataRequest = 'numberOfRows=' + sessionStorage.numberOfRows + '&offset=' + topRows.length + '&' +
                $('#filterTopForm').serialize();
            $.get($(this).attr('href'), dataRequest, function (data)
            {
                let tableTop = $('#tableTop');
                tableTop.append(data);

                sessionStorage.numberOfRows = $('#tableTop tr').length;
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

    $('#showLess').on('click', function (event)
    {
        if (sessionStorage.numberOfRows > numShowMoreLess) {
            let height = $(document).height();
            let y = $(window).scrollTop();

            /** Showing less trims is always the lower ten fold when the current number of trims is not a ten fold.
             * Otherwise numShorMoreLess is substracted for the number of visible trims. */
            if ($('#tableTop tr').length % 10 === 0) {
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

    $("#filterTopForm").on('submit', function (event)
    {
        /** When there are less trims visible than numShowMoreLess the filtering tries to find numShowMoreLess trims.
         * Otherwise the number of visible trims is asked from the server. */
        let rows;
        let topRowsVisible = $('.topRow:visible');
        if (topRowsVisible.length < numShowMoreLess) {
            rows = numShowMoreLess;
        } else {
            rows = topRowsVisible.length;
        }
        sessionStorage.filterTopForm = $(this).serialize();

        /** Three pieces of html, the slideshow, the top table and the least number of votes, are filled with the
         * ajax callback data. The data has a splitpoint to split at the right point for the three pieces of html.
         * The number of rows that has to be shown is set with showPartTopTable, the loader is hidden and
         * the slider is activated. Then the window scrolls to the top of the table. */
        $.get($(this).attr('action'), $(this).serialize() + "&numberOfRows=" + rows, function (data)
        {
            let array = data.split(/splitPoint/);
            let tableTopTrs = $('#tableTop tr');
            $('#fillableTable').html(array[0]);
            $('#slideshow').html(array[1]);
            $("#atLeastVotes").html('<em>with at least ' + array[2] + ' votes</em>');

            sessionStorage.topTable = array[0];
            sessionStorage.slideshow = array[1];
            sessionStorage.minNumVotes = array[2];

            /** Activate the slider */
            $('#carousel').carousel();

            showPartTopTable(tableTopTrs.length);
            sessionStorage.numberOfRows = tableTopTrs.length;

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