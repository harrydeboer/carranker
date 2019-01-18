$(document).ready(function ()
{
    $(document).on('click', '.choice-menu', function (event) {
        event.stopPropagation();
    });

    /** Activate the slider */
    if ($('#sliderTop').length) {
        $('#sliderTop').carousel();
    }

    /** Via the checkall checkbox all choices checks of this spec are toggled. */
    $(".checkAll").on('click', function () {
        $("." + $(this).data('specname')).prop('checked', $(this).prop('checked'));
    });

    /** All spec filtering options are toggled and the window scrolls to the filtered top. */
    $('#filterTopFormShowAll').on('click', function(event)
    {
        if ($('#choices.btn-group-vertical').length) {
            $('#choices').removeClass('btn-group-vertical');
            $('#choices').addClass('col-md-12').removeClass('col-md-4 col-xs-12');
        } else {
            $('#choices').addClass('btn-group-vertical');
            $('#choices').addClass('col-md-4 col-xs-12').removeClass('col-md-12');
        }
        $('.collapseChoice').toggle();
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

    /** When a user wants the default settings of all specs this function resets to default. */
    $('#filterTopFormReset').on('click', function (event)
    {
        for (var index in specsChoice) {
            $('.' + index).each(function () {
                $(this).prop('checked', true);
            });
        }
        $('#minNumVotes').val(minNumVotes);
        $('.aspectElement').each(function () {
            $(this).val(1);
        });
        $('.checkAll').each(function() {
            $(this).prop('checked', true);
        });
        $('.specsRange').each(function () {
            $(this).prop('selectedIndex', 0);
        });

        event.preventDefault();
    });

    /** The number of rows of the top table are placed in the session when the user want to show more
     * trims or less trims of the top. Default is the parameter top_number. */
    if (typeof sessionStorage.numberOfRows === 'undefined') {
        sessionStorage.numberOfRows = topLength;
    }

    /** When the user wants to filter the top the preferences are shown and scrolled to. */
    $('#choosePreferences').on('click', function ()
    {
        $('#preferencesDialog').toggle();
        $('html, body').animate({
            scrollTop: $("#choosePreferences").offset().top
        }, 1000);
    });

    sessionStorage.numberOfRows = $('#tableTop tr').length;
    showPartTopTable();

    /** When more or less cars are shown in the top table the scrolling makes that the button remains in the same place of the window. */
    $('#showMore').on('click', function ()
    {
        var height = $(document).height();
        var y = $(window).scrollTop();
        if ($('#tableTop tr').length % 10 === 0) {
            sessionStorage.numberOfRows = parseInt(sessionStorage.numberOfRows) + parseInt(numShowMoreLess);
        } else {
            sessionStorage.numberOfRows = parseInt(sessionStorage.numberOfRows) + parseInt(numShowMoreLess) - $('#tableTop tr').length % 10;
        }

        if (sessionStorage.numberOfRows > $('.topRow').length) {

            /** Show the loader img */
            $('#hideAll').show();

            $.get('showMoreTopTable/' + sessionStorage.numberOfRows + '/' + $('.topRow').length, "", function (data) {
                $('#tableTop').append(data);
                $('#hideAll').hide();
                sessionStorage.numberOfRows = $('#tableTop tr').length;
                showPartTopTable();
                var heightNew = $(document).height();
                $(window).scrollTop(y + heightNew - height);
            });
        } else {
            showPartTopTable();
            var heightNew = $(document).height();
            $(window).scrollTop(y + heightNew - height);
        }
    });

    $('#showLess').on('click', function ()
    {
        if (sessionStorage.numberOfRows > 10) {
            var height = $(document).height();
            var y = $(window).scrollTop();
            if ($('#tableTop tr').length % 10 === 0) {
                sessionStorage.numberOfRows = parseInt(sessionStorage.numberOfRows) - parseInt(numShowMoreLess);
            } else {
                sessionStorage.numberOfRows = parseInt(sessionStorage.numberOfRows) - parseInt(sessionStorage.numberOfRows) % 10;
            }
            showPartTopTable();
            var heightNew = $(document).height();
            $(window).scrollTop(y + heightNew - height);
        }
    });

    $("#filterTopForm").on('submit', function (event)
    {
        /** Show the loader img */
        $('#hideAll').show();

        $.get('filterTop', $(this).serialize() + "&numberOfRows=" + sessionStorage.numberOfRows, function (data) {
            /** Three pieces of html, the slideshow, the top table and the least number of votes, are filled with the data.
             * The data has a splitpoint to split at the right point for the three pieces of html.
             * The number of rows that has to be shown is set with showParTopTable, the loader is hidden and
             * the slider is activated. Then the window scrolls to the top of the table. */
            var array = data.split(/splitPoint/);
            $('#fillableTable').html(array[0]);
            $('#slideshow').html(array[1]);
            $("#atLeastVotes").html('<em>with at least ' + array[2] + ' votes</em>');

            /** Hide the loader img */
            $("#hideAll").hide();

            sessionStorage.numberOfRows = $('#tableTop tr').length;

            showPartTopTable();

            /** Activate the slider */
            $('#carousel').carousel();

            $("#preferencesDialog").css('margin-top', '0');
            $('html, body').animate({
                scrollTop: $("#topCars").offset().top
            }, 1000);
            lazyloadImages = document.querySelectorAll("img.lazy");
            setTimeout(lazyload, 3000);
        });

        event.preventDefault();
    });

    /** Only a part of the total table is shown. The minimum number of votes is added on top of the table. */
    function showPartTopTable()
    {
        $('#tableTop tr').hide();
        $('.topRow').slice(0, sessionStorage.numberOfRows).show().css('display', 'flex');
        $('#topOrLessNumber').html(Math.min(sessionStorage.numberOfRows, $('.topRow').length));
    }
});
