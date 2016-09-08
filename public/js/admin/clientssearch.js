var lastClientUpdate = Date.now()-2*1000;
var offset = 0;
var clientsPerUpdate = 20;
var isFetchingClients = false;
var canFetchMoreClients = true;
var searchInput = $('input[name = search]');

$(document).ready(function(){
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
        }
    });
    getClients(function(clients){
                updateClientsTable(clients);
                fetchMoreClientsIfNeeded();
            });
});

function getClients(callback) {

    lastClientUpdate = Date.now();
    var data = {
        'search' : searchInput.val(),
        'offset' : offset,
        'amount' : clientsPerUpdate
    };

    $.ajax({
        type: "POST",
        url: "/admin/clients/search",
        data: data,
        success: function (clients) {

            if(clients){
                console.log(clients);
                callback(clients);
            }
        },
        error:function(error){

            console.log(error);
        }
    }, "json");
}

function clearClientsTable(){

    $('#clientsTable tbody').empty();
    offset = 0;
}

function updateClientsTable(clients){

    if(clients.length == 0){
        canFetchMoreClients = false;
        hideFetchLoading();
        return;
    }
    else{
        showFetchLoading();
    }

    var table = '';

    clients.forEach(function(client){

        table += '<tr> \
<td id="clientid">' + client.id + '</td> \
<td id="name">' + client.name + '</td> \
<td id="cellphone">' + client.phone + '</td> \
<td>' + client.lastDate + '</td> \
<td>' + client.status + '</td> \
<td> \
    <button onclick="checkIfClientExists($(this))" class="btn-no-bg"> \
        <span class="glyphicon glyphicon-edit"></span> \
    </button> \
</td> \
</tr>';

    });

    $('#clientsTable tbody').append(table);
    offset+=clientsPerUpdate;
}

$('#form-clients-search').on('submit', function (e) {
    e.preventDefault();
    submitSearchForm();
});

$('#btn-clients-search').click(function(){
    submitSearchForm();
});

function submitSearchForm(){
    canFetchMoreClients = true;
    clearClientsTable();
    getClients(function(clients){
        updateClientsTable(clients);
        fetchMoreClientsIfNeeded();
    });
}

function needMoreClients(){
    if($(window).scrollTop() + $(window).height() > $(document).height() - 100) {
        return true;
    }else{
        return false;
    }
}

function fetchMoreClientsIfNeeded(){
    console.log('Need : '+needMoreClients()+" "+isFetchingClients);
    if(needMoreClients() && canFetchMoreClients){
        if(!isFetchingClients){
            isFetchingClients = true;
            getClients(function(clients){
                updateClientsTable(clients);
                isFetchingClients = false;
            });
        }
    }
}

$(window).bind('scroll', function() {
    fetchMoreClientsIfNeeded();
});

function hideFetchLoading(){
    $('#clients-loading').hide(400);
}

function showFetchLoading(){
    $('#clients-loading').show(300);
}