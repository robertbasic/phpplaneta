$(function(){
    var config = {
        toolbar: [
            ['Source','-','Bold','Italic','-','NumberedList','BulletedList','-','Outdent','Indent','Blockquote'],
            ['Link','Unlink','Anchor'],
            ['Format','FontSize'],
            ['SpecialChar']
        ],
        skin: 'v2'
    };
    $('#text').ckeditor(config);
});