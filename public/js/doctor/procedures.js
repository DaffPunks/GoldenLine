var pathComponents = window.location.pathname.split('/');
var time = pathComponents[pathComponents.length-1];

$('#btnDateValue').html(time == 'today' ? 'Сегодня' : time);

//DatePicker
function todayDate(){
    var today=new Date();
    today.setHours(0);
    today.setMinutes(0);
    today.setSeconds(0);
    today.setMilliseconds(0);
    return today;
}

function addDays(date, days)
{
    var dat = new Date(date);
    dat.setDate(dat.getDate() + days);
    return dat;
}

$(document).ready(function(){

    var now = new Date();

    var daysOffset = 0;

    var locations = window.location.pathname.split('/');
    if(!isNaN(parseInt(locations[locations.length-1]))){
        daysOffset = parseInt(locations[locations.length-1]);
    }

    $('#datePicker').datetimepicker({
        pickTime: false,
        format: "DD.MM.YYYY",
        defaultDate: addDays(now, daysOffset),
        maxDate: addDays(now, 365*2),
        minDate : new Date('2010-01-01'),
        language : 'ru'
    });

    $('#datePicker').change(function(e){

        var date = $('input[name=chosenDate]').val();

        //var daysDiff = -1 * days_between(todayDate(), new Date($('input[name=chosenDate]').val()));
        //var pathComponents = window.location.pathname.split('/');
        //
        window.location.href = 'http://' +  window.location.host + '/' + pathComponents[1] + '/' + pathComponents[2] + '/' + date;
    });
});