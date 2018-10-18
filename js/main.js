$(document).ready(function() {
    
    getAmountOfRecords();
    showBrowseButtons();

    $('#show-next-ten').on('click', function(){
        currentStart = parseInt($('#paso-paper-table').attr("start"));
        total = parseInt($('#paso-paper-table').attr("amount"));
        if (currentStart + 10 <= total) {
            resetTable();
            setRows(10, currentStart + 10);
            currentStart = $('#paso-paper-table').attr("start", currentStart + 10);
        }
        showBrowseButtons();
    });

    $('#show-previous-ten').on('click', function(){
        currentStart = parseInt( $('#paso-paper-table').attr("start"));
        if (currentStart > 0) {
            resetTable();
            setRows(10 , currentStart - 10);
            currentStart = $('#paso-paper-table').attr("start", currentStart - 10);
        }
        showBrowseButtons();

    });

    $('.search-button').on('click', function(){
        search($('#searchform'), $(this).attr("id"));
    });

});

function showBrowseButtons()
{
    rows = parseInt($('#paso-paper-table').attr("amount"));
    currentStart = parseInt($('#paso-paper-table').attr("start"));

    if (currentStart > 0) {
        $('#show-previous-ten').removeClass('paso-disabled');
        $('#show-previous-ten').addClass('paso-enabled');
    } else {
        $('#show-previous-ten').addClass('paso-disabled');
        $('#show-previous-ten').removeClass('paso-enabled');
    }
    if (rows > 10 && rows - currentStart > 10) {
        $('#show-next-ten').removeClass('paso-disabled');
        $('#show-next-ten').addClass('paso-enabled');
    } else {
        $('#show-next-ten').addClass('paso-disabled');
        $('#show-next-ten').removeClass('paso-enabled');
    }
}

function resetTable()
{
    $('#paso-paper-table tbody').html('');
}

