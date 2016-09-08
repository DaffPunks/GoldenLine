//Create Coach
$('#btn-modal-add-coach').click(function(){

    var surname = $('#input-add-coach-modal-surname').val();
    var name = $('#input-add-coach-modal-name').val();
    var secondname = $('#input-add-coach-modal-secondname').val();
    var salary =  $('#input-add-coach-modal-salary').val();
    var subscription_percent = $('#input-add-coach-modal-subscription-percent').val();
    var cellphone = $('#input-add-coach-modal-phone').val();

    var data = {
        'surname' : surname,
        'name' : name,
        'secondname' : secondname,
        'salary' : salary,
        'subscription_percent' : subscription_percent,
        'cellphone' : cellphone
    };

    showCreateLoading();

    $.ajax({
        type: "POST",
        url: "/admin/coaches/create",
        data: data,
        success: function (response) {

            if(response){
                if(response.status == 'success')
                    location.reload();
            }
        },
        error:function(error){
            hideCreateLoading();
            console.log(error);
        }
    }, "json");
});

function showCreateLoading(){

    $('#modal-add-coach-loading').slideDown(300);
    $('#btn-modal-add-coach').slideUp(300);
}

function hideCreateLoading(){
    $('#modal-add-coach-loading').slideUp(300);
    $('#btn-modal-add-coach').slideDown(300);
}

//Delete Coach
var delete_coach_id;
function btnDeleteCoachAction(btnSender){

    delete_coach_id = btnSender.data('id');
    var name = btnSender.data('name');

    $('#modal-delete-person-body').html(name);
    $('#deletePersonModal').modal('show');
}

$('#btnDeletePerson').click(function(){

    var data = {
        'id' : delete_coach_id
    };

    showDeleteLoading();

    $.ajax({
        type: "POST",
        url: "/admin/coaches/delete",
        data: data,
        success: function (response) {

            if(response){
                if(response.status == 'success')
                    location.reload();
            }
        },
        error:function(error){
            hideDeleteLoading();
            console.log(error);
        }
    }, "json");

});

function showDeleteLoading(){

    $('#delete-person-loading').show(300);
    $('#btn-delete-person-cancel').hide(300);
    $('#btnDeletePerson').hide(300);
}

function hideDeleteLoading(){
    $('#delete-person-loading').hide(300);
    $('#btn-delete-person-cancel').show(300);
    $('#btnDeletePerson').show(300);
}

//Edit
var edit_coach_id;
function btnEditCoachAction(btnSender){

    edit_coach_id = btnSender.data('id');
    var full_name = btnSender.data('name').split(' ');
    var cellphone = btnSender.data('phone');

    var surname = full_name[0];
    var name = full_name[1];
    var secondname = full_name[2];

    var salary = btnSender.data('salary');
    var subscription_percent = btnSender.data('percent');

    $('#input-edit-coach-modal-surname').val(surname);
    $('#input-edit-coach-modal-name').val(name);
    $('#input-edit-coach-modal-secondname').val(secondname);
    $('#input-edit-coach-modal-salary').val(salary);
    $('#input-edit-coach-modal-subscription-percent').val(subscription_percent);
    $('#input-edit-coach-modal-phone').val(cellphone);

    $('#edit-coach-modal').modal('show');
}

$('#btn-modal-edit-coach').click(function(){

    var surname = $('#input-edit-coach-modal-surname').val();
    var name = $('#input-edit-coach-modal-name').val();
    var secondname = $('#input-edit-coach-modal-secondname').val();
    var salary =  $('#input-edit-coach-modal-salary').val();
    var subscription_percent = $('#input-edit-coach-modal-subscription-percent').val();
    var cellphone = $('#input-edit-coach-modal-phone').val();

    var data = {
        'id' : edit_coach_id,
        'surname' : surname,
        'name' : name,
        'secondname' : secondname,
        'salary' : salary,
        'subscription_percent' : subscription_percent,
        'cellphone' : cellphone
    };

    showEditLoading();

    $.ajax({
        type: "POST",
        url: "/admin/coaches/edit",
        data: data,
        success: function (response) {

            if(response){
                if(response.status == 'success')
                    location.reload();
            }
        },
        error:function(error){
            hideEditLoading();
            console.log(error);
        }
    }, "json");
});

function showEditLoading(){

    $('#modal-edit-coach-loading').slideDown(300);
    $('#btn-modal-edit-coach').slideUp(300);
}

function hideEditLoading(){
    $('#modal-edit-coach-loading').slideUp(300);
    $('#btn-modal-edit-coach').slideDown(300);
}