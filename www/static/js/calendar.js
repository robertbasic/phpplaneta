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

    $("#calendar").datepicker(
    {
        onChangeMonthYear: function(year, month, inst){
                                highlite(year, month);
                                return true;
                            },
        beforeShowDay: function(date){
            return [false, ''];
        }
    }
    );

    $(".has-news").live('click', function(){
        console.log('a');
    });

    var currentDate = $("#calendar").datepicker("getDate");

    highlite(currentDate.getFullYear(), currentDate.getMonth()+1);
});

function highlite(year, month) {

    $.get(
        '/news/ajax-load-dates/',
        {
            year: year,
            month: month
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