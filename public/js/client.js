$(document).ready(function(){
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
        }
    });

    getAccountData();
});

function getAccountData(){

    $.ajax({
        type: "POST",
        url: "/get_account_data",
        data: {},
        success: function (data) {

            if(data){
                console.log(data);
                fillAccountInfo(data);
            }
        },
        error:function(error){

            console.log(error);
        }
    }, "json");
}

function fillAccountInfo(data){

    var deposit = data['deposit'];
    var discount = data['discount'];
    var bonus = data['bonus'];

    $('#deposit').html(deposit);
    if(discount > 0){
        $('#discount').html(discount);
    }
    else{
        $('#discount-label').hide();
    }
    $('#bonus').html(bonus);

    $('#account-info-container').slideDown(400);
}