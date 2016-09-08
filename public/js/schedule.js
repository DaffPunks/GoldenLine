$(document).ready(function () {

    makeTHeadStatic();
});

$(window).scroll(function() {
    makeTHeadStatic();
});

function makeTHeadStatic(){
    if ($(window).scrollTop() >= $('#schedule-static-thead').offset().top) {
        $('#schedule-dynamic-thead').show();
        $('#schedule-dynamic-thead').find('tr').addClass('tr-static');
    }
    else{
        $('#schedule-dynamic-thead').hide();
        $('#schedule-dynamic-thead').find('tr').removeClass('tr-static');
    }
}

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

    $('#headerDatePicker').datetimepicker({
        pickTime: false,
        format: "YYYY/MM/DD",
        defaultDate: addDays(now, daysOffset),
        maxDate: addDays(now, 365*2),
        minDate : new Date('2010-01-01'),
        language : 'ru'
    });

    function days_between(firstDate, secondDate) {

        var oneDay = 24*60*60*1000;
        return Math.round((firstDate.getTime() - secondDate.getTime())/oneDay);

    }

    function formatDifference(num)
    {
        if(num > 0){
            return "+" + num;
        }
        return num.toString();
    }

    $('#headerDatePicker').change(function(e){

        var daysDiff = -1 * days_between(todayDate(), new Date($('input[name=chosenDate]').val()));
        var pathComponents = window.location.pathname.split('/');

        window.location.href = 'http://' +  window.location.host + '/' + pathComponents[1] + '/' + pathComponents[2] + '/' + formatDifference(daysDiff);
    });
});