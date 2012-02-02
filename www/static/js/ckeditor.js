$(function() {
    var textarea = $("#text");
    
    var config = {
        toolbar: [
            ['Source','-','Bold','Italic','-','NumberedList','BulletedList','-','Outdent','Indent','Blockquote'],
            ['Link','Unlink','Anchor'],
            ['Format','FontSize'],
            ['SpecialChar']
        ],
        skin: 'v2'
    };
    textarea.ckeditor(config);
    
    CKEDITOR.instances.text.on('blur', function() {
        var text = textarea.val();
        var pattern = /http:\/\//
        
        var matches = text.match(pattern);
        
        if (matches == null) {
            showNoLinkWarning();
        } else {
            hideNoLinkWarning();
        }
    });
});

function showNoLinkWarning() {
    if ($(".flashMessenger").length == 0) {
        var div = $("<div class='flashMessenger fm-bad'>Nisam našao linkove u tekstu!</div>");
        div.prependTo("#right");
    }
}

function hideNoLinkWarning() {
    $(".flashMessenger").remove();
}