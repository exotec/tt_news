define(['jquery'], function ($) {

    var ajaxUrl = TYPO3.settings.ajaxUrls['tt_news_catmenu'];

    var NewsCatmenu = {};


    NewsCatmenu.expandCollapse = function ($element) {
        var isExpand = $element.data('isexpand');
        var parent = $element.closest('li');
        var img = parent.find('a.pmiconatag img');


        console.log([$element.data('params')]);

        parent.find('ul').remove();

        $element.data('isexpand', 1);

        if (!isExpand) {
            var src = img.attr('src');
            if (!!src) {
                var newsrc = src.replace('minus', 'plus');
                img.attr('src', newsrc);
            }
        } else {
            $.ajax({
                url: ajaxUrl,
                type: 'get',
                dataType: 'html',
                cache: false,
                data: {
                    'PM': $element.data('params'),
                    'id': $element.data('pid'),
                    'action': 'expandTree',
                    'cObjUid': $element.data('cobjuid'),
                    'L': $element.data('l')
                }
            }).done(function (response) {
                $element.closest('li').html(response);
            });
        }
    };




    NewsCatmenu.initializeEvents = function () {
        var tree = $('#ttnews-cat-tree');

        tree.on('click', '.pmiconatag', function (evt) {
            evt.preventDefault();
            NewsCatmenu.expandCollapse($(this));
        });
    };

    $(NewsCatmenu.initializeEvents);
});

