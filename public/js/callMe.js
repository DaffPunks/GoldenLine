var is_calling_for_procedures = true;
var call_me_number;

$('#call-me-modal').on('show.bs.modal', function (e) {

    showCallLoading();
    hideCallMeMessage();

    var data = {
        '_token' : $("input[name=_token]").val()
    };

    $.ajax({
        type: "POST",
        url: "/get_phone",
        data: data,
        success: function (number) {

            hideCallLoading();

            if(number){

                call_me_number = number;
                $('#call-me-phone-input').val(number);
            }
        },
        error:function(error){

            console.log(error);
        }
    }, "json");

});

$('#btn-call-me-procedures').click(function () {

    is_calling_for_procedures = true;

    $('#btn-call-me-procedures').addClass('active');
    $('#btn-call-me-sport').removeClass('active');

});

$('#btn-call-me-sport').click(function () {

    is_calling_for_procedures = false;

    $('#btn-call-me-sport').addClass('active');
    $('#btn-call-me-procedures').removeClass('active');

});

$('#btn-call-me').click(function(){

    showCallLoading();

    var data = {
        '_token' : $("input[name=_token]").val(),
        'is_procedures' : is_calling_for_procedures,
        'number' : call_me_number,
        'client_name' : $('#dropdown-username').html()
    };

    $.ajax({
        type: "POST",
        url: "/call_me",
        data: data,
        success: function (response) {

            hideCallLoading();

            if(response && response.status == 'success'){
                $('#modal-call-me-message').html('Благодарим вас! В скором времени Вам перезвонят.')
            }
            else{
                $('#modal-call-me-message').html('Извините, кажется, произошла ошибка. ' + response.msg)
            }

            showCallMeMessage();
        },
        error:function(error){
            console.log(error);

            hideCallLoading();
        }
    }, "json");

});

function showCallLoading(){
    $('#modal-call-me-loading').slideDown(300);
    $('#btn-call-me').slideUp(300);
}

function hideCallLoading(){
    $('#modal-call-me-loading').slideUp(300);
    $('#btn-call-me').slideDown(300);
}

function showCallMeMessage(){
    $('#modal-call-me-message').show(300);
    $('#btn-call-me').hide(300);
}

function hideCallMeMessage(){
    $('#modal-call-me-message').hide(300);
}