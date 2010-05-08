$(function(){
    var tagsform = $('#tagsform');
    if(tagsform.length > 0) {
        tagsform.remove();
        $('#right').append(tagsform);
        tagsform = $('#tagsform');

        loadTags();

        tagsform.submit(function(){
            var tags = $('#tags');
            var tagsVal = tags.val();
            $.post(
                '/admin/public/news-tags/ajax-add',
                {
                    tags: tagsVal
                },
                function(data) {
                    if('tags' in data) {
                        appendTags(data);
                    } else if('errors' in data) {
                        console.log(data);
                    }
                    tags.val('');
                },
                'json'
                );
            return false;
        });
    }

    $('.ul_tags li a').live('click', function(){
        var liTag = $(this).parent();
        var tagId = liTag.attr('rel').replace(/tag_id_/,'');
        removeTag(tagId);
        liTag.remove();
        return false;
    });

});

function loadTags() {
    var newsIdTag = $('#id');
    if(newsIdTag.length != 1) {
        return false;
    }

    var newsId = newsIdTag.val();

    if(newsId == '' || newsId == 0) {
        return false;
    }

    $.post(
        '/admin/public/news-tags/ajax-load',
        {
            newsId: newsId
        },
        function(data) {
            if('tags' in data) {
                appendTags(data);
            } else if('errors' in data) {
                console.log(data);
            }
        },
        'json'
        );
}

function appendTags(tags) {
    tags = tags['tags'];

    if(tags.length == 0) {
        return false;
    }

    if($('.ul_tags').length == 0) {
        createUlTags();
    }
    var ulTags = $('.ul_tags');
    var liTags = '';
    var newsTag = $('#related_tags');

    for(key in tags) {
        var tagId = tags[key]['id'];
        var rel = 'tag_id_' + tagId;
        if($('li[rel='+rel+']').length == 0) {

            var newsTagVal = newsTag.val();
            newsTag.val(newsTagVal + '#' + tagId + '#');

            liTags += '<li rel=\'' + rel + '\'>';
            liTags += tags[key]['title'];
            liTags += ' <a href=\'#\'>&otimes;</a>';
            liTags += '</li>';
        }
    }
    ulTags.append(liTags);
}

function removeTag(tagId) {
    var newsTag = $('#related_tags');
    var newsTagVal = newsTag.val();
    newsTagVal = newsTagVal.replace('#'+tagId+'#','');
    newsTag.val(newsTagVal);
}

function createUlTags()
{
    $('#right').append('<ul class=\'ul_tags\'></ul>');
}