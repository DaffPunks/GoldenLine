var oldPasswordInput = $('input[name = oldPassword]');
var newPasswordInput = $('input[name = newPassword]');
var newPasswordRepeatedInput = $('input[name = newPasswordRepeated]');

$('#btnChangePass').click(function(){

    showChangePasswordLoading();

    var data = {
        '_token' : $("input[name=_token]").val(),
        'oldPass' : oldPasswordInput.val(),
        'newPass' : newPasswordInput.val(),
        'newPassRepeat' : newPasswordRepeatedInput.val()
    };

    if(data['newPass'] != data['newPassRepeat']){
        showError('Пароли не совпадают');
    }
    else{
        $.ajax({
            type: "POST",
            url: "/change_password",
            data: data,
            success: function (msg) {

                hideChangePasswordLoading();

                if(msg != 'Success'){
                    showError(msg);
                }else{
                    hideError();
                    $('#changePassword').modal('hide');
                }
            },
            error:function(error){
                hideChangePasswordLoading();

                console.log(error);
            }
        }, "json");
    }
});

function showError(msg){
    $('#errorMsg').html(msg);
    $('#error').show(300);
}

function hideError(){
	$('#errorMsg').html();
    $('#error').hide(300);
}

newPasswordInput.popover({
    placement: 'top',
    trigger: 'focus'
});

newPasswordInput.bind("focus change paste keyup", function() {
	if(newPasswordInput.val().length>=6){
		newPasswordInput.parent().removeClass('has-error');
		newPasswordInput.parent().addClass('has-success');
	}else{
		newPasswordInput.parent().addClass('has-error');
		newPasswordInput.parent().removeClass('has-success');
	}
});

newPasswordRepeatedInput.bind("focus change paste keyup", function() {
	if(newPasswordRepeatedInput.val() == newPasswordInput.val()){
		newPasswordRepeatedInput.parent().removeClass('has-error');
		newPasswordRepeatedInput.parent().addClass('has-success');
	}else{
		newPasswordRepeatedInput.parent().addClass('has-error');
		newPasswordRepeatedInput.parent().removeClass('has-success');
	} 
});

function showChangePasswordLoading(){
    $('#modal-change-password-loading').show(300);
    $('#btnChangePass').hide(300);
}

function hideChangePasswordLoading(){
    $('#modal-change-password-loading').hide(300);
    $('#btnChangePass').show(300);
}