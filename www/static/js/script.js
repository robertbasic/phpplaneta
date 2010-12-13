$(document).ready(function(){
    $(".navigation li").hover(function(){
        $(this).children("ul").show();
    }, function(){
        $(this).children("ul").hide();
    });

    var left = $("#left");
    var right = $("#right");
    if(left.innerHeight() > right.innerHeight()) {
        right.height(left.innerHeight());
    }

    var jsFill = $("#js_fill");
    if(jsFill.length > 0) {
        jsFill.val('honeypot!');
    }
});