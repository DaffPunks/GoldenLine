var workoutId;
var IsEntered;

function openEntryModal(btnSender, isEntered){

    IsEntered = isEntered;

    workoutId = btnSender.parent().find('input[name=id]').val();

    var time = btnSender.parent().find('.cell-time-interval').html();
    var name = btnSender.parent().find('.cell-workout-name').html();
    var coach = btnSender.parent().find('.cell-coach').html();
    var freePlace = btnSender.parent().find('input[name = freePlace]').val();

    $('#enter-modal time').html(time);
    $('#enter-modal name').html(name);
    $('#enter-modal coach').html(coach);
    $('#enter-modal free').html(isEntered ? 'Вы записаны' : 'Осталось мест: ' + freePlace);


    $('#btn-main-enter-modal').html( isEntered ? 'Отказаться' : 'Записаться') ;

    $('#enter-modal').modal('show');
}

$('#btn-main-enter-modal').click(function () {

    var data = {
        '_token' : $("input[name=_token]").val(),
        'workoutId' : workoutId
    };

    var operation =IsEntered? 'quit' : 'enter';

    $.ajax({
        type: "POST",
        url: '/sport/' + operation + 'Workout',
        data: data,
        success: function (response) {

            responseCame();
            if(response) {

                IsEntered ? quitSuccess(response) : enterSuccess(response);
            }

        }
    }, "json");

    waitForResponse();
});

function waitForResponse(){

    $('#enter-modal').modal({backdrop: 'static', keyboard: false});
    $('#btn-main-enter-modal').prop('disabled', true);
    $('#btn-main-enter-modal').fadeOut(300);
    $('#edit-loading').fadeIn(300);
}

function responseCame(){

    $('#btn-main-enter-modal').prop('disabled', false);
    $('#btn-main-enter-modal').show(300);
    $('#edit-loading').fadeOut(300);

}

function enterSuccess(response){

    if(response['status'] == 'success'){

        $('#enter-modal free').html('Вы записаны');

    }
    else{
        $('#enter-modal free').html(response['msg']);
    }

    //$('#btn-main-enter-modal').prop('disabled', false);
    window.location.href = 'http://' +  window.location.host + window.location.pathname;

}

function quitSuccess(response){

    if(response['status'] == 'success'){

        $('#enter-modal free').html('Вы отказались');

    }
    else{
        $('#enter-modal free').html(response['msg']);
    }

    //$('#btn-main-enter-modal').prop('disabled', false);
    window.location.href = 'http://' +  window.location.host + window.location.pathname;
}