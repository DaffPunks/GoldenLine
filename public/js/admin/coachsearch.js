var lastCoachUpdate = Date.now()-2*1000;
var offset = 0;
var coachesPerUpdate = 10;
var isFetchingCoaches = false;
var canFetchMoreCoaches = true;
var searchInput = $('input[name = search]');

$(document).ready(function(){
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
        }
    });
    getCoaches(function(coaches){
                updateCoachesTable(coaches);
                fetchMoreCoachesIfNeeded();
            });
});

function getCoaches(callback) {
    lastCoachUpdate = Date.now();
    var data = {
        'search' : searchInput.val(),
        'offset' : offset,
        'amount' : coachesPerUpdate
    };

    $.ajax({
        type: "POST",
        url: "/admin/coaches/search",
        data: data,
        success: function (coaches) {

            if(coaches){
                callback(coaches);
            }
        },
        error:function(error){
            console.log(error);
        }
    }, "json");
}

function clearCoachesTable(){
    $('#coachesTable tbody').empty();
    offset = 0;
}

function updateCoachesTable(coaches){

    if(coaches.length == 0){
        canFetchMoreCoaches = false;
        hideFetchLoading();
        return;
    }
    else{
        showFetchLoading();
    }

    var table = '';

    coaches.forEach(function(coach){

        table += '<tr> \
<td id="coachid">' + coach.id + '</td> \
<td id="name">' + coach.name + '</td> \
<td id="cellphone">' + coach.phone + '</td> \
<td id="percent">' + coach.percent + '</td> \
<td id="salary">' + coach.salary + '</td> \
<td> \
    <button onclick="btnEditCoachAction($(this))" class="btn-no-bg" data-id="' + coach.id +'" data-name="'+ coach.name +'" data-percent="'+ coach.percent +'" data-salary="'+ coach.salary +'" data-phone="'+ coach.phone +'"> \
        <span class="glyphicon glyphicon-edit"></span> \
    </button> \
</td> \
<td> \
    <button onclick="btnDeleteCoachAction($(this))" class="btn-no-bg" data-id="' + coach.id +'" data-name="'+ coach.name +'"> \
        <span class="glyphicon glyphicon-remove"></span> \
    </button> \
</td> \
</tr>';

    });

    $('#coachesTable tbody').append(table);
    offset+=coachesPerUpdate;
}

function needMoreCoaches(){
    if($(window).scrollTop() + $(window).height() > $(document).height() - 100) {
        return true;
    }else{
        return false;
    }
}

function fetchMoreCoachesIfNeeded(){
    console.log('Need : '+needMoreCoaches()+" "+isFetchingCoaches);
    if(needMoreCoaches() && canFetchMoreCoaches){
        if(!isFetchingCoaches){
            isFetchingCoaches = true;
            getCoaches(function(coaches){
                updateCoachesTable(coaches);
                isFetchingCoaches = false;
            });
        }
    }
}

$(window).bind('scroll', function() {
    fetchMoreCoachesIfNeeded();
});


//Search
$('#form-coaches-search').on('submit', function (e) {
    e.preventDefault();
    submitSearchForm();
});

$('#btn-coaches-search').click(function(){
    submitSearchForm();
});

function submitSearchForm(){
    canFetchMoreCoaches = true;
    clearCoachesTable();
    getCoaches(function(coaches){
        updateCoachesTable(coaches);
        fetchMoreCoachesIfNeeded();
    });
}

function hideFetchLoading(){
    $('#coaches-loading').hide(400);
}

function showFetchLoading(){
    $('#coaches-loading').show(300);
}