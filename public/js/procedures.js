var offset = 0;
var proceduresPerUpdate = 6;
var isFetchingProcedures = false;
var canFetchMoreProcedures = true;
var isFuture;
var didGetAnyProcedure = false;

$(document).ready(function(){

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
        }
    });

    if($(document).width() > 568){
        proceduresPerUpdate = 10;
    }

    var path = window.location.pathname.split('/')[2];
    isFuture = path == 'future';

    getProcedures(function(procedures){
        updateProceduresDiv(procedures);
        fetchMoreProceduresIfNeeded();
    });
});

function getProcedures(callback) {

    var data = {
        'time' : window.location.pathname.split('/')[2],
        'offset' : offset,
        'amount' : proceduresPerUpdate
    };

    $.ajax({
        type: "POST",
        url: "/get_procedures",
        data: data,
        success: function (procedures) {

            if(procedures){
                console.log(procedures);
                callback(procedures);
            }
        },
        error:function(error){
            console.log(error);
        }
    }, "json");
}

function updateProceduresDiv(procedures){

    var max_comment_length = 80;

    var proceduresHTML = '';

    if(procedures.length > 0){

        didGetAnyProcedure = true;

        procedures.forEach(function(procedure){

            if($(document).width() > 768 && $(document).width() <= 1200 && procedure.comment.length > max_comment_length){
                procedure.shortComment = procedure.comment.substr(0, max_comment_length) + '...';
            }
            else{
                procedure.shortComment = procedure.comment;
            }

            var procedureHTML =  '<div class="col-sm-4 col-md-3 cell-container"> \
                                <div class="col-sm-12 col-cell"> \
                                    <div class="header"> \
                                        <div class="col-xs-6 col-sm-5 col-lg-3 text-left"> \
                                            <div class="time">'  + procedure.time + '</div> \
                                        </div> \
                                        <div class="col-xs-6 col-sm-7 col-lg-9 text-right">';

            if(procedure.day != 'Сегодня' || !isFuture) {
                procedureHTML += '<h4>' + procedure.day + '</h4><h5>' + procedure.month + '</h5>';
            }
            else {
                procedureHTML += '<h3>' + procedure.day + '</h3>';
            }

            procedureHTML += '</div></div><div class="content ';
            if((procedure.day == 'Завтра' || procedure.day == 'Сегодня') && isFuture)
                procedureHTML += 'active';

            procedureHTML += '"><p1>' + procedure.shortComment + '</p1> \
        <h5>' +procedure.doctorName + '</h5>' +
                '</div></div></div>';

            console.log(procedureHTML);

            proceduresHTML += procedureHTML;
        });
    }
    else if(procedures.length == 0 && !didGetAnyProcedure){
        proceduresHTML += '<div class="col-xs-12 text-center" style="margin-top: 20vh"><h4>Нет Процедур</h4></div>'
    }

    $('#procedures-container').append(proceduresHTML);

    offset+=proceduresPerUpdate;

    if(procedures.length == 0){
        canFetchMoreProcedures = false;
        hideFetchLoading();
        return;
    }
}

function needMoreProcedures(){
    if($(window).scrollTop() + $(window).height() > $(document).height() - 100) {
        return true;
    }else{
        return false;
    }
}

function fetchMoreProceduresIfNeeded(){
    console.log('Need : '+needMoreProcedures()+" "+isFetchingProcedures);
    if(needMoreProcedures() && canFetchMoreProcedures){
        if(!isFetchingProcedures){
            isFetchingProcedures = true;
            getProcedures(function(procedures){
                updateProceduresDiv(procedures);
                isFetchingProcedures = false;
            });
        }
    }
}

$(window).bind('scroll', function() {
    fetchMoreProceduresIfNeeded();
});

function hideFetchLoading(){
    $('#procedures-loading').hide(400);
}

function showFetchLoading(){
    $('#procedures-loading').hide(300);
}