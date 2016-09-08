//On document load
$(document).ready(function() {

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
        }
    });

    var now = new Date();

    var daysOffset = 0;

    var locations = window.location.pathname.split('/');
    if (!isNaN(parseInt(locations[locations.length - 1]))) {
        daysOffset = parseInt(locations[locations.length - 1]);
    }

    $('#create-datetimepicker-date').datetimepicker({
        pickTime: false,
        format: "YYYY.MM.DD",
        defaultDate: addDays(now, daysOffset),
        maxDate: addDays(now, 365 * 4),
        minDate: new Date('2010-01-01'),
        language: 'ru'
    });

    $('#create-datetimepicker-start').datetimepicker({
        pickDate: false,
        language: 'ru',
        minuteStepping: 15
    });

    $('#create-datetimepicker-end').datetimepicker({
        pickDate: false,
        minuteStepping : 15,
        language: 'ru'
    });

    requestCoachesForCreateModal();
});

$('#create-datetimepicker-start').change(function(e){

    var minHour = 9, maxHour = 21;

    var startInput = $('#create-datetimepicker-start input');
    var hours = parseInt(startInput.val().split(':')[0]);
    var minutes = startInput.val().split(':')[1];

    if(hours < minHour){
        hours = minHour;
        startInput.val('0' + hours + ':' + minutes);
    }
    else if(hours > maxHour){
        hours = maxHour;
        startInput.val(hours + ':' + minutes);
    }

    var endInput = $('#create-datetimepicker-end input');
    var endMinutes = endInput.val().split(':')[1];
    var endHours = hours + 1;
    endInput.val(endHours + ':' + endMinutes);
});

$('#create-datetimepicker-end').change(function (e) {

    var minHour = 10, maxHour = 22;
    var endInput = $('#create-datetimepicker-end input');
    var hours = parseInt(endInput.val().split(':')[0]);
    var minutes = endInput.val().split(':')[1];

    if(hours < minHour){
        hours = minHour;
        endInput.val(hours + ':' + minutes);
    }
    else if(hours > maxHour){
        hours = maxHour;
        endInput.val(hours + ':' + minutes);
    }
});

//Create entry
$('#createEntryModal').on('show.bs.modal', function (e) {

    $('#create-loading').hide();
    $('#btn-create-entry').show();
});

function showCoachCreateLoading(){
    $('#modal-create-coach-loading').slideDown(300);
    $('#modal-create-coach-dropdown').slideUp(300);
}

function hideCoachCreateLoading(){
    $('#modal-create-coach-loading').slideUp(300);
    $('#modal-create-coach-dropdown').slideDown(300);
}

var coach_id;

function requestCoachesForCreateModal(){

    showCoachCreateLoading();

    var data = {
        'search' : ''
    };

    $.ajax({
        type: "POST",
        url: "/admin/coaches/search",
        data: data,
        success: function (coaches) {

            hideCoachCreateLoading();

            if(coaches){
                console.log(coaches);
                insertCoachesInCreateList(coaches);
            }
        },
        error:function(error){
            console.log(error);
        }
    }, "json");
}

function insertCoachesInCreateList(coaches){

    var new_coaches = [];

    $(coaches).each(function(i){

        var name = coaches[i]['name'];
        var id = coaches[i]['id'];
        new_coaches[i] = {content : name, id : id};

        var li = '<li id="' + id + '"><a href="#">' + name + '</a></li>';

        $('#modal-create-coach-dropdown-list').append(li);
    });

    $('#modal-create-coach-dropdown-list li').on('click', function(){
        coach_id = this.id;

        var child = $(this).children()[0];
        var coach_name = $(child).html();

        $('#modal-create-coach-dropdown button').html(coach_name);
        console.log(this.id);
    });
}

var workout_nameId;
var create_workout_name_IDD = new InputDropDown('create-workout-name-input', 'create-workout-name-input-ul');
create_workout_name_IDD.liTemplate = '<li style="padding: 5px 10px 5px 10px;"><div class="row"><div class="col-xs-10 text-left" style="font-size: 17px;">%@</div><div class="col-xs-2"><button style="font-size: 14px;border: 0; background: 0;">X</button></div></div></li>';

create_workout_name_IDD.input.bind("change paste keyup", function() {

    var data = {
        'search' : create_workout_name_IDD.input.val()
    };

    $.ajax({
        type: "POST",
        url: "/admin/workout/search/name",
        data: data,
        success: function (workout_names) {

            if(workout_names){
                var new_workout_names =[];

                $(workout_names).each(function(i){

                    var name = workout_names[i]['name'];
                    var id = workout_names[i]['id'];
                    new_workout_names[i] = {content : name, id : id};

                });

                create_workout_name_IDD.setData(new_workout_names);
            }
        },
        error:function(error){
            console.log(error);
        }
    }, "json");
});

create_workout_name_IDD.listItemClicked = function(li_index, e){

    var info = this.getData(li_index);

    if($(e.target).is('button')){
        deleteWorkoutName(info.content, li_index);
    }
    else{
        this.listItemClickedAction(li_index);
        workout_nameId = info.id;
    }
};

//Finish creating

function checkInput(input){

    if(!input.val()){
        input.parent().addClass('has-error');
        return false;
    }
    else{
        input.parent().removeClass('has-error');
        return true;
    }
}

$('#btn-create-entry').click(function(){

    createEntry();
});

function createEntry(){
    var success  = checkInput($('#create-datetimepicker-date input')) &&
        checkInput($('#create-datetimepicker-start input')) &&
        checkInput($('#create-datetimepicker-end input')) &&
        checkInput($('#create-workout-name-input')) &&
        checkInput($('#create-places-count'));

    if(success && coach_id && coach_id != 0){

        $('#create-loading').slideDown(300);
        $('#btn-create-entry').slideUp(300);

        var date, time_start, time_end, places_count, gym_type, weeks_amount;

        date = $('#create-datetimepicker-date input').val();
        time_start = $('#create-datetimepicker-start input').val();
        time_end = $('#create-datetimepicker-end input').val();
        places_count = $('#create-places-count').val();
        gym_type = window.location.pathname.split('/')[2];
        weeks_amount = $("#weeks_amount").val();
        var is_personal = $('#create-checkbox-personal').is(":checked");

        var data = {
            'workout_name' : $('#create-workout-name-input').val(),
            'coach_id' : coach_id,
            'gym_type' : gym_type,
            'date_start' : date+' '+time_start,
            'date_end' : date+' '+time_end,
            'places_count' : places_count,
            'weeks_amount' : weeks_amount,
            'is_personal' : is_personal
        };

        $.ajax({
            type: "POST",
            url: "/admin/workout/create",
            data: data,
            success: function (response) {

                if(response){
                    if(response['status'] == 'success')
                        window.location.href = 'http://' +  window.location.host + window.location.pathname;
                    else {
                        $('#create-loading').slideUp(300);
                        $('#btn-create-entry').slideDown(300);
                        alert(response['msg']);
                    }
                }
                else {
                    $('#create-loading').slideUp(300);
                    $('#btn-create-entry').slideDown(300);
                    alert('Произошла ошибка');
                }
            },
            error:function(error){
                console.log(error);
            }
        }, "json");
    }
}

//Edit-Entry
var client_id = -1;
var subscription_id;
var workout_id;
var this_workout_start_event;
var clients_capacity, free_places_count;

var subscription_objects = {}, unconfirmed_clients_objects = {};
//Subscriptions statuses: 0 - delete, 1 - stay, 2 - add

function showEditLoading(){
    $('#modal-edit-loading').slideDown(300);
    $('#modal-edit-buttons').slideUp(300);
}

function hideEditLoading(){
    $('#modal-edit-loading').slideUp(300);
    $('#modal-edit-buttons').slideDown(300);
}

function loadEntryDataForWorkout(workout_id){

    $('#edit-client-list').hide();
    $('#edit-loading').show();

    var data = {
        'workout_id' : workout_id
    };

    $.ajax({
        type: "POST",
        url: "/admin/workout/get_entry_data",
        data: data,
        success: function (entry_data) {

            hideEditLoading();

            if(entry_data){

                var clients_and_subscriptions = $(entry_data['clients_and_subscriptions']);
                clients_and_subscriptions.each(function(i, data_element){

                    subscription_objects[data_element['id']] = 1;

                    addRowToSubscriptionList(data_element['id'], data_element['client_name'], data_element['number'], data_element['subscription_name']);
                });

                var unconfirmed_clients = $(entry_data['unconfirmed_clients']);
                unconfirmed_clients.each(function(i, data_element){

                    unconfirmed_clients_objects[data_element['client_id']] = 1;
                    addRowToUnconfirmedList(data_element['client_id'], data_element['client_name'], data_element['client_phone']);
                });

                if(parseInt(entry_data['is_coach_replaced'])){
                    $("#checkbox-is-coach-replaced").prop('checked', true);
                }

                countFreePlaces();

                $('#edit-loading').slideUp(300);
                $('#edit-client-list').fadeIn(300);
            }
        },
        error:function(error){
            hideEditLoading();
            console.log(error);
        }
    }, "json");

}

$('#modal-edit-entry').on('show.bs.modal', function (e) {

    var btn = e.relatedTarget;
    $('#modal-edit-entry').find("#edit-workout-name-input").val(btn.dataset.name);
    $('#modal-edit-entry').find('#modal-edit-coach-dropdown button').html(btn.dataset.coachname)
    coach_id = btn.dataset.coachid;
    workout_id = btn.dataset.id;
    this_workout_start_event = btn.dataset.startevent;
    clients_capacity = btn.dataset.clientscapacity;
    free_places_count = btn.dataset.freeplaces;
    $('#free-places-label').html('Осталось мест: ' + free_places_count);

    //Empty Both objects
    subscription_objects = {};
    unconfirmed_clients_objects = {};

    //Empty both tables
    $('#edit-client-list').empty();
    $('#unconfirmed-clients-list').empty();

    loadEntryDataForWorkout(btn.dataset.id);

    requestCoachesForEditModal();

    showEditLoading();
});

function showCoachEditLoading(){
    $('#modal-edit-coach-loading').slideDown(300);
    $('#modal-edit-coach-dropdown').slideUp(300);
    $('#modal-edit-checkbox-coach-replaced').slideUp(300);
}

function hideCoachEditLoading(){
    $('#modal-edit-coach-loading').slideUp(300);
    $('#modal-edit-coach-dropdown').slideDown(300);
    $('#modal-edit-checkbox-coach-replaced').slideDown(300);
}

function requestCoachesForEditModal(){

    showCoachEditLoading();

    var data = {
        'search' : ''
    };

    $.ajax({
        type: "POST",
        url: "/admin/coaches/search",
        data: data,
        success: function (coaches) {

            hideCoachEditLoading();

            if(coaches){
                insertCoachesInEditList(coaches);
            }
        },
        error:function(error){
            console.log(error);
        }
    }, "json");
}

function insertCoachesInEditList(coaches){

    var new_coaches = [];

    //Clear list
    $('#modal-edit-coach-dropdown-list').html('');

    $(coaches).each(function(i){

        var name = coaches[i]['name'];
        var id = coaches[i]['id'];
        new_coaches[i] = {content : name, id : id};

        var li = '<li id="' + id + '"><a href="#">' + name + '</a></li>';

        $('#modal-edit-coach-dropdown-list').append(li);
    });

    $('#modal-edit-coach-dropdown-list li').on('click', function(){
        coach_id = this.id;

        var child = $(this).children()[0];
        var coach_name = $(child).html();

        $('#modal-edit-coach-dropdown button').html(coach_name);
    });
}

function addRowToSubscriptionList(subscription_id, client_name, subscription_number, subscription_name){

    var li = '<li class="list-group-item" style="min-height: 46px; font-size: 18px" data-id="' + subscription_id + '"> \
                <div class="form-inline row"> \
                <div class="col-xs-12 col-sm-6 col-lg-5"><p>' + client_name + '</p></div> \
                <div class="col-xs-12 col-sm-6 col-lg-7"><p>' + subscription_name + '</p></div> \
                <div class="col-xs-12 col-sm-6 col-lg-5"><p><button onclick="setSubscriptionIdAsDeleted($(this))" class="btn btn-default pull-left" type="button" style="float: right; margin-top: -4px"><span class="glyphicon glyphicon-minus"></span></button></p></div> \
                <div class="col-xs-12 col-sm-6 col-lg-7"><p>' + subscription_number + '</p></div> \
                </div> \
              </li>';

    $('#edit-client-list').prepend(li);
    $('#edit-client-list').children().first().hide().slideDown(350);

    countFreePlaces();
}

function addRowToUnconfirmedList(client_id, client_name, client_phone){

    var li = '<li class="list-group-item" style="height: 46px; font-size: 18px" data-id="' + client_id + '"> \
                <div class="form-inline row" style="margin-bottom: 8px;"> \
                <div class="col-sm-6">' + client_name + '</div> \
                <div class="col-sm-4">' + client_phone +  '</div> \
                <div class="col-sm-2 text-center"> \
                    <button onclick="setUnconfirmedClientIdAsDeleted($(this))" class="btn btn-default" type="button" style="float: right; margin-top: -4px"><span class="glyphicon glyphicon-minus"></span></button> \
                </div> \
                </div> \
              </li>';

    $('#unconfirmed-clients-list').prepend(li);
    $('#unconfirmed-clients-list').children().first().hide().slideDown(350);
    countFreePlaces();
}

function countFreePlaces(){

    var subscriptionsLis = $('#edit-client-list').children();
    var unconfirmed_lis = $('#unconfirmed-clients-list').children();

    free_places_count = clients_capacity - subscriptionsLis.length - unconfirmed_lis.length;

    if(free_places_count <= 0){
        $('#btn-add-subscription').slideUp(300);
    }
    else{
        $('#btn-add-subscription').slideDown(300);
    }

    $('#free-places-label').html('Осталось мест: ' + free_places_count);

    if(unconfirmed_lis.length == 0){
        $('#unconfirmed-clients-label').html('Заявок от клиентов нет');
    }
    else{
        $('#unconfirmed-clients-label').html('Заявки на тренировку:');
    }

    if(subscriptionsLis.length == 0){
        $('#edit-client-label').html('Утвержденных клиентов нет');
    }
    else{
        $('#edit-client-label').html('Утвержденные клиенты:');
    }
}

//Edit-Entry workout_name
var edit_workout_name_IDD = new InputDropDown('edit-workout-name-input', 'edit-workout-name-input-ul');
edit_workout_name_IDD.liTemplate = '<li style="padding: 5px 10px 5px 10px;"><div class="row"><div class="col-xs-10 text-left" style="font-size: 17px;">%@</div><div class="col-xs-2 pull-right"><button style="font-size: 14px;border: 0; background: 0;">X</button></div></div></li>';

edit_workout_name_IDD.input.bind("change paste keyup", function() {

    var data = {
        'search' : edit_workout_name_IDD.input.val()
    };

    $.ajax({
        type: "POST",
        url: "/admin/workout/search/name",
        data: data,
        success: function (workout_names) {

            if(workout_names){
                var new_workout_names =[];

                $(workout_names).each(function(i){

                    var name = workout_names[i]['name'];
                    var id = workout_names[i]['id'];
                    new_workout_names[i] = {content : name, id : id};

                });

                edit_workout_name_IDD.setData(new_workout_names);
            }
        },
        error:function(error){
            console.log(error);
        }
    }, "json");
});

edit_workout_name_IDD.listItemClicked = function(li_index, e){

    var info = this.getData(li_index);

    if($(e.target).is('button')){
        deleteWorkoutName(info.content, li_index);
    }
    else{
        this.listItemClickedAction(li_index);
        workout_nameId = info.id;
    }
};

//Edit-Entry client
var edit_find_client_IDD = new InputDropDown('edit-client-input', 'edit-client-ul');
var edit_find_client_timer;
edit_find_client_IDD.input.bind("change paste keyup", function() {

    edit_find_client_IDD.showLoading();
    clearTimeout(edit_find_client_timer);
    edit_find_client_timer = setTimeout(editFindClients, 800);
});

function editFindClients(){

    var data = {
        'search' : edit_find_client_IDD.input.val()
    };

    $.ajax({
        type: "POST",
        url: "/admin/clients/search",
        data: data,
        success: function (clients) {

            if(clients){

                edit_find_client_IDD.hideLoading();
                var new_clients =[];

                $(clients).each(function(i){

                    var name = clients[i]['name'];
                    var phone = clients[i]['phone'];
                    var id = clients[i]['id'];
                    new_clients[i] = {content : name + ' ' + phone, id : id};

                });

                edit_find_client_IDD.setData(new_clients);

            }
        },
        error:function(error){
            console.log(error);
        }
    }, "json");
}

edit_find_client_IDD.listItemClicked = function(li_index){

    var client_info = this.getData(li_index);
    client_id = client_info.id;
    this.listItemClickedAction(li_index);
};

//Edit-Entry subscription
var edit_find_subscription_IDD = new InputDropDown('edit-subscription-input', 'edit-subscription-ul');
var edit_find_subscription_timer;
var subscription_name, subscription_number;

edit_find_subscription_IDD.input.bind("change paste keyup", function() {

    edit_find_subscription_IDD.showLoading();
    clearTimeout(edit_find_subscription_timer);
    edit_find_subscription_timer = setTimeout(findSubscription, 800);
});

edit_find_subscription_IDD.input.focus(function(){

    findSubscription();
    edit_find_subscription_IDD.showLoading();

});

function findSubscription(){

    var data = {
        'search' : edit_find_subscription_IDD.input.val(),
        'client_id' : client_id,
        'workout_id' : workout_id
    };

    $.ajax({
        type: "POST",
        url: "/admin/subscriptions/search",
        data: data,
        success: function (subscriptions) {

            if(subscriptions){

                edit_find_subscription_IDD.hideLoading();

                var new_subscriptions =[];

                $(subscriptions).each(function(i){

                    var name = subscriptions[i]['name'];
                    var number = subscriptions[i]['number'];
                    var id = subscriptions[i]['id'];
                    new_subscriptions[i] = {content : name + ' ' + number, name : name, number : number, id : id};

                });

                edit_find_subscription_IDD.setData(new_subscriptions);

            }
        },
        error:function(xhr, status, error){
            var err = eval("(" + xhr.responseText + ")");
            console.log(err);
        }
    }, "json");
}

edit_find_subscription_IDD.listItemClicked = function(li_index){

    $('#btn-add-subscription').prop('disabled', false);
    var subscription_info = this.getData(li_index);
    subscription_id = subscription_info.id;
    subscription_name = subscription_info.name;
    subscription_number = subscription_info.number;
    this.listItemClickedAction(li_index);
};

$('#btn-add-subscription').click(function(){

    var client_input_value_segments = edit_find_client_IDD.input.val().split(' ');
    var client_name = edit_find_client_IDD.input.val() ? client_input_value_segments[0] + ' ' + client_input_value_segments[1] : 'Имя будет определено';

    subscription_objects[subscription_id] = 2;
    addRowToSubscriptionList(subscription_id, client_name, subscription_number, subscription_name);

    edit_find_client_IDD.input.val('');
    edit_find_subscription_IDD.input.val('');

    $('#btn-add-subscription').prop('disabled', true);
});

function setSubscriptionIdAsDeleted(btnSender){

    var li = btnSender.closest('li');
    var subscription_id = li.data('id');

    subscription_objects[subscription_id.toString()] = 0;

    removeElementFromList(li);
}

function setUnconfirmedClientIdAsDeleted(btnSender){
    var li = btnSender.closest('li');
    var client_id = li.data('id');

    unconfirmed_clients_objects[client_id.toString()] = 0;

    removeElementFromList(li);
}

function removeElementFromList(li){

    li.hide(350, function(){
        li.remove();
        countFreePlaces();
    });
    countFreePlaces();
}

$('#btn-edit-entry').click(function(){

    var success = checkInput($('#edit-workout-name-input'));

    if(success) updateEntry();
});

$('#btn-delete-entry').click(function(){
    deleteEntry();
});

function updateEntry(){

    showEditLoading();

    var data = {
        'workout_id' : workout_id,
        'start_event' : this_workout_start_event,
        'workout_name' : $('#edit-workout-name-input').val(),
        'coach_id' : coach_id,
        'realcoach_id' : realcoach_id,
        'subscriptions' : subscription_objects,
        'unconfirmed_clients' : unconfirmed_clients_objects,
        'update_subsequent' : $("#checkbox-update-subsequent").is(":checked"),
        'is_coach_replaced' : $("#checkbox-is-coach-replaced").is(":checked")
    };

    $.ajax({
        type: "POST",
        url: "/admin/workout/update",
        data: data,
        success: function (response) {

            if(response){
                if(response['status'] == 'success')
                    window.location.href = 'http://' +  window.location.host + window.location.pathname;
                else{
                    hideEditLoading();
                    alert(response['msg']);
                }
            }
            else
                alert('Произошла ошибка');
        },
        error:function(error){
            hideEditLoading();
            console.log(error);
        }
    }, "json");
}

function deleteEntry(){

    showEditLoading();

    var data = {
        'workout_id': workout_id,
        'start_event' : this_workout_start_event,
        'update_subsequent' : $("#checkbox-update-subsequent").is(":checked")
    };

    $.ajax({
        type: "POST",
        url: "/admin/workout/delete",
        data: data,
        success: function (response) {

            if(response){
                if(response['status'] == 'success') {
                    window.location.href = 'http://' + window.location.host + window.location.pathname;
                }else {
                    hideEditLoading();
                    alert(response['msg']);
                }
            }
            else
                alert('Произошла ошибка');
        },
        error:function(error){
            hideEditLoading();
            console.log(error);
        }
    }, "json");
}

function deleteWorkoutName(workout_name, li_index){

    var data = {
        'workout_name': workout_name
    };

    $.ajax({
        type: "POST",
        url: "/admin/workout/name/delete",
        data: data,
        success: function (response) {

            if(response){
                if(response['status'] == 'success'){
                    create_workout_name_IDD.deleteLiAtIndex(li_index);
                    edit_workout_name_IDD.deleteLiAtIndex(li_index);
                }
                else
                    alert(response['msg']);
            }
            else
                alert('Произошла ошибка');
        },
        error:function(error){
            console.log(error);
        }
    }, "json");

}