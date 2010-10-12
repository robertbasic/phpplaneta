/* Serbian i18n for the jQuery UI date picker plugin. */
/* Written by Dejan Dimić. */
jQuery(function($){
	$.datepicker.regional['sr-SR'] = {
		closeText: 'Zatvori',
		prevText: '&#x3c;',
		nextText: '&#x3e;',
		currentText: 'Danas',
		monthNames: ['Januar','Februar','Mart','April','Maj','Jun',
		'Jul','Avgust','Septembar','Oktobar','Novembar','Decembar'],
		monthNamesShort: ['Jan','Feb','Mar','Apr','Maj','Jun',
		'Jul','Avg','Sep','Okt','Nov','Dec'],
		dayNames: ['Nedelja','Ponedeljak','Utorak','Sreda','Četvrtak','Petak','Subota'],
		dayNamesShort: ['Ned','Pon','Uto','Sre','Čet','Pet','Sub'],
		dayNamesMin: ['Ne','Po','Ut','Sr','Če','Pe','Su'],
		weekHeader: 'Sed',
		dateFormat: 'dd/mm/yy',
		firstDay: 1,
		isRTL: false,
		showMonthAfterYear: false,
		yearSuffix: ''};
	$.datepicker.setDefaults($.datepicker.regional['sr-SR']);
});

$(document).ready(function(){
    $.datepicker.setDefaults($.datepicker.regional['sr-SR']);

    var dateShown = null;

    $("#calendar").datepicker(
    {
        onChangeMonthYear: function(year, month, inst){
                                dateShown = [year, month];
                                highlite(dateShown);
                                return true;
                            },
        beforeShowDay: function(date){
            return [false, ''];
        }
    }
    );

    if(typeof(setCalendarDate) != 'undefined') {
        $("#calendar").datepicker("setDate", setCalendarDate);
    }

    var currentDate = $("#calendar").datepicker("getDate");
    var currentYear = currentDate.getFullYear();
    var currentMonth = currentDate.getMonth()+1;

    dateShown = [currentYear, currentMonth];

    highlite(dateShown);

    $(".has-news").live('click', function(){
        var clickedDay = parseInt($(this).text());
        var date = dateShown[0]+'-'+dateShown[1]+'-'+clickedDay;

        $("body").append('<form name="date" method="get" action="/datum/'+date+'/strana/1"></form>');
        $("form[name=date]").submit();
    });
});

function highlite(date) {

    $(".has-news").removeClass('has-news');

    $.get(
        '/news/ajax-load-dates/',
        {
            year: date[0],
            month: date[1]
        },
        function(responseData) {
            if(responseData.length > 0) {
                for(key in responseData) {
                    var day = responseData[key].day;
                    var aTag = $(".ui-datepicker-calendar td span:contains('"+day+"')").filter(function(){
                        return $(this).text() == day;
                    });
                    if(!aTag.hasClass('has-news')) {
                        aTag.addClass('has-news');
                    }
                }
            }
        },
        "json"
    );

}