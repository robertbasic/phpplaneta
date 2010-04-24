function fileUpload(form, action_url, div_id)
{
// Create the iframe...
var iframe = document.createElement("iframe");
iframe.setAttribute("id","upload_iframe");
iframe.setAttribute("name","upload_iframe");
iframe.setAttribute("width","0");
iframe.setAttribute("height","0");
iframe.setAttribute("border","0");
iframe.setAttribute("style","width: 0; height: 0; border: none;");

// Add to document...
form.parentNode.appendChild(iframe);
window.frames['upload_iframe'].name="upload_iframe";

iframeId = document.getElementById("upload_iframe");

// Add event...
var eventHandler = function()  {

if (iframeId.detachEvent)
iframeId.detachEvent("onload", eventHandler);
else
iframeId.removeEventListener("load", eventHandler, false);

// Message from server...
if (iframeId.contentDocument) {
content = iframeId.contentDocument.body.innerHTML;
} else if (iframeId.contentWindow) {
content = iframeId.contentWindow.document.body.innerHTML;
} else if (iframeId.document) {
content = iframeId.document.body.innerHTML;
}

document.getElementById(div_id).innerHTML = content;

// Del the iframe...
setTimeout('iframeId.parentNode.removeChild(iframeId)', 250);
}

if (iframeId.addEventListener)
iframeId.addEventListener("load", eventHandler, true);
if (iframeId.attachEvent)
iframeId.attachEvent("onload", eventHandler);

// Set properties of form...
form.setAttribute("target","upload_iframe");
form.setAttribute("action", action_url);
form.setAttribute("method","post");
form.setAttribute("enctype","multipart/form-data");
form.setAttribute("encoding","multipart/form-data");

// Submit the form...
//form.submit();

document.getElementById(div_id).innerHTML = "Uploading...";
}

$(document).ready(function(){

    $("#default").live('submit', function(){

        fileUpload($(this), '/', "blah");
        return false;

//        var firstname = $("#firstname").val();
//        var lastname = $("#lastname").val();
//        var city = $("#city").val();
//        var phone = $("#phone").val();
//        var email = $("#email").val();
//        var drop_down = $("#drop_down").val();
//        var sd = $("#sd").val();
//
//        $(".form").html('Loading...');
//
//        $.post('/',
//            {
//                'firstname': firstname,
//                'lastname': lastname,
//                'city': city,
//                'phone': phone,
//                'email': email,
//                'drop_down': drop_down,
//                'sd': sd
//            },
//            function(data){
//                $(".form").html(data);
//            }
//        );
//
//        return false;
    });
});