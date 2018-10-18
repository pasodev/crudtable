function firstTableLoad() 
{
    setRows(10, 0);
}

function populateRow( data )
{

    loc = data.docLocation.split("/");
    pdfFile = loc[loc.length - 1]
    row = '<tr id="' + data.edocId + '">';
    row += '<td class="row-doc-year">' + data.docYear + '</td>';
    row += '<td class="row-journal">' + data.docJournal + '</td>';
    row += '<td class="row-title">' + data.docTitle + '</td>';
    row += '<td class="row-authors">' + data.docAuthors + '</td>';
    row += '<td><button class="get-pdf row-option"> <a href="data/' + pdfFile +'" download>PDF</a></button>'
    row += '<button class="show-abstract row-option">Abstract</button>'
    row += '<button class="edit-row row-option">Edit</button>'
    row += '<button class="delete-row row-option">Delete</button>'
    row += '</td>',
    row += '</tr>';

    return row;
}

function addRowTotableWithId( tableId, data )
{
    table = $('#' + tableId);
    if (table.length = 0) {
        return false;
    }
    $('#' + tableId + ' > tbody:last-child').append(populateRow(data));

    // So we can be sure that the events is atteched to all rows.
    $('.show-abstract').on('click', function(){
        id = $(this).closest('tr').attr('id');
        getAbstract(id);
    });

    $('.delete-row').on('click', function(){
        id = $(this).closest('tr').attr('id');
        deleteRow(id);
    });

    $('.edit-row').on('click', function(){
        id = $(this).closest('tr').attr('id');
        editRow(id);
    });
}

function setRows(limit, offset)
{
    uri = 'api.php';
    params = {
        "method" : "getRows",
        "limit" : limit,
        "offset" : offset
    };

    $.ajax({
        type: "POST",
        url: uri,
        data: params,
        dataType: "json"
    }).done(function( data ){
        for (i=0; i<data.length; i++) {
            addRowTotableWithId('paso-paper-table', data[i]);
        }
        showBrowseButtons();
    });
}

function search( form, searchType )
{
            uri = 'api.php';
            params.form = form.serialize();
            params.method = 'search';
            params.type = searchType;
            $.ajax({
                type: "POST",
                url: uri,
                data: params,
                dataType: "json"
            }).done(function(data){ 
                console.log(data);
                resetTable();
                for (i=0; i<data.length; i++) {
                    addRowTotableWithId('paso-paper-table', data[i]);
                }
                showBrowseButtons();
            }).fail(function(){
                alert("Not matches found!");
            });
   
}

function getAbstract(id)
{
    uri = 'api.php';
    params = {
        "method" : "getField",
        "id" : id,
        "field" : "docAbstract"
    };

    $.ajax({
        type: "POST",
        url: uri,
        data: params,
        dataType: "json"
    }).done(function(data){ 
        showInModal(data);
    });
}

function showInModal( data )
{
    $('#modal-1').html('<div class="paso-modal-close"> <p>x</p> </div><div class="paso-modal-content">' + data + '</div>');
    $('#modal-1').removeClass('paso-modal');
    $('#modal-1').addClass('paso-modal-active');
    $('.paso-modal-close').on('click', function(){
        hideModal();
    });
}

function hideModal()
{
    $('.paso-modal').html();
    $('#modal-1').removeClass('paso-modal-active');
    $('#modal-1').addClass('paso-modal');
}

function deleteRow( id )
{
    uri = 'api.php';
    params = {
        'method' : 'delete',
        'id' : id
    };

    $.ajax({
        type: "POST",
        url: uri,
        data: params,
        dataType: "json"
    }).done(function(data){ 
        $('#paso-paper-table tr[id='+id+']').remove();
        decreaseAmountOfRecords();
    }).fail(function(data){
        alert("Impossible to remove record!");
    });
    
}

function updateTableRow( data )
{
    row = $('#' + data.edocId);
    row.find('.row-doc-year').html(data.docYear);
    row.find('.row-journal').html(data.docJournal);
    row.find('.row-title').html(data.docTitle);
    row.find('.row-authors').html(data.docAuthors);
}

function showEditForm( data )
{
    html = '<form id=edit-form class="paso-edition-form" enctype="application/json">';
    html += '<input type="hidden" name="edocId" value="' + data.edocId + '"></input>';
    html += '<label>Year </label><input type="text" name="docYear" value="' + data.docYear + '"></input>';
    html += '<label>Journal </label><input type="text" name="docJournal" value="' + data.docJournal + '"></input>';
    html += '<label>Title </label><input type="text" name="docTitle" value="' + data.docTitle + '"></input>';
    html += '<label>Authors </label><input type="text" name="docAuthors" value="' + data.docAuthors+ '"></input>';
    html += '<label>Citation </label><input type="text" name="docCitation" value="' + data.docCitation + '"></input>';
    html += '<label>Abstract </label><input type="text" name="docAbstract" value="' + data.docAbstract + '"></input>';
    html += '<label>Tags </label><input type="text" name="docTags" value="' + data.docTags + '"></input>';
    html += '<label>Keywords </label><input type="text" name="docKeywords" value="' + data.docKeywords + '"></input>';
    html += '<label>Notes </label><input type="text" name="docSourceNotes" value="' + data.docSourceNotes + '"></input>';
    html += '<label>Location </label><input type="text" name="docLocation" value="' + data.docLocation + '"></input>';
    html += '<br><div class="paso-submit-edition row-option"> Submit! </div>'
    html += '</form>';

    showInModal(html);
    $('.paso-submit-edition').on('click', function(){
            uri = 'api.php';
            params.form = $('.paso-edition-form').serialize();
            params.method = 'update';
            $.ajax({
                type: "POST",
                url: uri,
                data: params,
                dataType: "json"
            }).done(function(data){ 
                updateTableRow(data); 
                hideModal();
            });
    });
}

function editRow( id )
{
    uri = 'api.php';
    params = {
        "method" : "getById",
        "id" : id,
    };

    $.ajax({
        type: "POST",
        url: uri,
        data: params,
        dataType: "json"
    }).done(function(data){ 
        showEditForm(data);
    });

    $(this).parent()
}

function getAmountOfRecords()
{
    uri = 'api.php';
    params = {
        'method' : 'getAmountOfRecords'
    };

    $.ajax({
        type: "POST",
        url: uri,
        data: params,
        dataType: "json"
    }).done(function(data){ 
        $('#paso-paper-table').attr("amount", data);
    });
}

function decreaseAmountOfRecords()
{
    records = $('#paso-paper-table').attr("amount") - 1;
    $('#paso-paper-table').attr("amount", records);
}
