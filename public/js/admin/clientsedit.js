
var clientExists = false;
var clientId;
var is_phone_valid = true;

function checkIfClientExists(btnSender){

    $('#modal-generate-password').modal('show');
    $('#modal-generate-password-dialog').hide(0);
    $('#modal-generate-password-loading').show(0);

    var tr = btnSender.closest('tr');
    clientId = tr.find('#clientid').html();
    $('#modal-name').html(tr.find('#name').html());

    //Get cellphone
    var cellphone = tr.find('#cellphone').html();

    //Clear from hyphen(-)
    var cellphone = cellphone.split(' ')[0].replace(/\-/g, "");

    $('input[name=genPassPhone]').val(cellphone);
    checkPhoneinput();

    var data = {};
    var postUrl = "/admin/clients/getid/"+clientId;
    $.ajax({
        type: "POST",
        url: postUrl,
        data: data,
        success: function (msg) {

            if(msg){
                if(msg != 0)
                    $('#modalInfo').html('Изменить Пользователя');
                else
                    $('#modalInfo').html('Добавить Пользователя');

                $('#newpass').hide(0);
                $('#modal-generate-password-dialog').show(300);
                $('#modal-generate-password-loading').hide(300);
                hideGenPassLoading();
            }
        },
        error:function(error){
            console.log(error);
        }
    }, "json");
}

var phoneInput = $('input[name=genPassPhone]');

function checkPhoneinput(){
    if(phoneInput.val().match(/ +|,/g) || phoneInput.val() == ''){
        phoneInput.parent().addClass('has-error');
        phoneInput.parent().removeClass('has-success');
        $('#btnGenPass').prop('disabled', true);
        is_phone_valid = false;
    }else{
        phoneInput.parent().removeClass('has-error');
        phoneInput.parent().addClass('has-success');
        $('#btnGenPass').prop('disabled', false);
        is_phone_valid = true;
    }
}

phoneInput.popover({
    placement: 'top',
    trigger: 'focus'
});

phoneInput.bind("focus change paste keyup", function() {
    checkPhoneinput();
});

$('#btnGenPass').click(function(){

    showGenPassLoading();

    if(is_phone_valid) {
        var data = {
            'cellphone': $('input[name=genPassPhone]').val(),
            'clientId' : clientId
        };

        var postUrl = "/admin/clients/genpassword";
        $.ajax({
            type: "POST",
            url: postUrl,
            data: data,
            success: function (password) {

                if (password) {
                    $('#modal-genpass-loading').slideUp(300);
                    $('#newpassinput').html(password);
                    $('#newpassinput').show();
                    $('#newpass').slideDown(300);
                    selectTextInside("newpassinput");
                }
            },
            error:function(error){
                hideGenPassLoading();
                console.log(error);
            }
        }, "json");
    }
});

function selectTextInside(objId) {
    if (document.selection)
        document.selection.empty();
    else if (window.getSelection)
            window.getSelection().removeAllRanges();

    if (document.selection) {
        var range = document.body.createTextRange();
            range.moveToElementText(document.getElementById(objId));
        range.select();
    }
    else if (window.getSelection) {
        var range = document.createRange();
        range.selectNode(document.getElementById(objId));
        window.getSelection().addRange(range);
    }
}

function showGenPassLoading(){
    $('#modal-genpass-loading').slideDown(300);
    $('#btnGenPass').slideUp(300);
    $('#newpass').slideUp(300);
}

function hideGenPassLoading(){
    $('#modal-genpass-loading').slideUp(300);
    $('#btnGenPass').slideDown(300);
}

////Delete Client
//function deleteClient(btnSender){
//
//    var tr = btnSender.closest('tr');
//    var clientName = tr.find('#name').html();
//
//    $('#modal-delete-name').html(clientName);
//    $('#deletePersonModal').modal('show');
//}