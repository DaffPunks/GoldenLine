var lastSpecialistUpdate = Date.now()-2*1000;
var offset = 0;
var specialistsPerUpdate = 20;
var isFetchingSpecialists = false;
var canFetchMoreSpecialists = true;
var searchInput = $('input[name = search]');

$(document).ready(function(){
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
        }
    });
    getSpecialists(function(specialists){
                updateSpecialistsTable(specialists);
                fetchMoreSpecialistsIfNeeded();
            });
});

function getSpecialists(callback) {

    lastSpecialistUpdate = Date.now();
    var data = {
        'search' : searchInput.val(),
        'offset' : offset,
        'amount' : specialistsPerUpdate
    };

    $.ajax({
        type: "POST",
        url: "/admin/specialists/search",
        data: data,
        success: function (specialists) {

            if(specialists){
                console.log(specialists);
                callback(specialists);
            }
        },
        error:function(error){
            console.log(error);
        }
    }, "json");
//}
}

function clearSpecialistsTable(){
    $('#specialistsTable tbody').empty();
    offset = 0;
}

function updateSpecialistsTable(specialists){

    if(specialists.length == 0){
        canFetchMoreSpecialists = false;
        hideFetchLoading();
        return;
    }
    else{
        showFetchLoading();
    }

    var table = '';

    //<td id="position">' + specialist.position + '</td> \
    specialists.forEach(function(specialist){

        table += '<tr> \
<td id="specialistid">' + specialist.id + '</td> \
<td id="name">' + specialist.name + '</td> \
<td id="cellphone">' + specialist.phone + '</td> \
<td>' + specialist.lastDate + '</td> \
<td> \
    <button onclick="checkIfSpecialistExists($(this))" class="btn-no-bg"> \
        <span class="glyphicon glyphicon-edit"></span> \
    </button> \
</td> \
</tr>';

    });

    $('#specialistsTable tbody').append(table);
    offset+=specialistsPerUpdate;
}

$('#form-specialists-search').on('submit', function (e) {
    e.preventDefault();
    submitSearchForm();
});

$('#btn-specialists-search').click(function(){
    submitSearchForm();
});

function submitSearchForm(){
    canFetchMoreSpecialists = true;
    clearSpecialistsTable();
    getSpecialists(function(specialists){
        updateSpecialistsTable(specialists);
        fetchMoreSpecialistsIfNeeded();
    });
}

function needMoreSpecialists(){
    if($(window).scrollTop() + $(window).height() > $(document).height() - 100) {
        return true;
    }else{
        return false;
    }
}

function fetchMoreSpecialistsIfNeeded(){
    if(needMoreSpecialists() && canFetchMoreSpecialists){
        if(!isFetchingSpecialists){
            isFetchingSpecialists = true;
            getSpecialists(function(specialists){
                updateSpecialistsTable(specialists);
                isFetchingSpecialists = false;
            });
        }
    }
}

$(window).bind('scroll', function() {
    fetchMoreSpecialistsIfNeeded();
});

function hideFetchLoading(){
    $('#doctors-loading').hide(400);
}

function showFetchLoading(){
    $('#doctors-loading').show(300);
}