/**
 * 
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author  Julian Kempff (julian.kempff@serlo.org)
 * @license LGPL-3.0
 * @license http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link        https://github.com/serlo-org/athene2 for the canonical source repository
 * @copyright Copyright (c) 2013 Gesellschaft für freie Bildung e.V. (http://www.open-education.eu/)
 * 
 */

/** maybe use http://dbushell.github.io/Nestable/ instead of jqueryui */

/*global define*/
define("sortable_list", ["jquery", "underscore", "common"], function ($) {
    var SortableList;

    SortableList = function () {
        return $(this).each(function () {
            var $instance = $(this),
                url, originalData;

            url = $instance.attr('data-action');

            if (!url) {
                throw new Error('No sort action given.');
            }

            $instance.nestable({
                handleClass: 'sort-handle',
                rootClass: 'sortable',
                expandBtnHTML: '',
                collapseBtnHTML: '',
                maxDepth: 12
            });

            originalData = $instance.nestable('serialize');

            $instance.on('change', function () {
                var data = $instance.nestable('serialize');
                console.log(JSON.stringify(data));
                // if (!_.isEqual(data, originalData)) {
                //     $.ajax({
                //         url: url,
                //         data: JSON.stringify(data),
                //         method: 'post'
                //     })
                //         .success(function (result) {
                //             console.log(result);
                //         })
                //         .fail(Common.genericError);
                // }
            });
        });
    };

    $.fn.SortableList = SortableList;
});