$(document).ready(function(){
    $(".navigation li").hover(function(){
        $(this).children("ul").show();
    }, function(){
        $(this).children("ul").hide();
    });
});