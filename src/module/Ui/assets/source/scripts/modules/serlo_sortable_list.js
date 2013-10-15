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
define("sortable_list", ["jquery", "underscore", "common"], function ($, _, Common) {
    "use strict";
    var SortableList;

    SortableList = function () {
        return $(this).each(function () {
            var $instance = $(this),
                $saveBtn = $('.save-order', this),
                url,
                originalData,
                updatedData;

            url = $instance.attr('data-action');

            if (!url) {
                throw new Error('No sort action given.');
            }


            /**
             * @function cleanEmptyChildren
             * @param {Array}
             *
             * Removes empty children arrays from serialized nestable,
             * to be able to hide the $saveBtn
             **/
            function cleanEmptyChildren(array) {
                _.each(array, function (child) {
                    if (child.children) {
                        if (child.children.length) {
                            cleanEmptyChildren(child.children);
                        } else {
                            delete child.children;
                        }
                    }
                });
                return array;
            }

            $instance.nestable({
                handleClass: 'sort-handle',
                rootClass: 'sortable',
                expandBtnHTML: '',
                collapseBtnHTML: '',
                maxDepth: 12
            });

            originalData = cleanEmptyChildren($instance.nestable('serialize'));

            $instance.on('change', function () {
                updatedData = cleanEmptyChildren($instance.nestable('serialize'));
                if (!_.isEqual(updatedData, originalData)) {
                    $saveBtn.show();
                } else {
                    $saveBtn.hide();
                }
            });

            $saveBtn.click(function (e) {
                e.preventDefault();
                $.ajax({
                    url: url,
                    data: {
                        sortable: updatedData
                    },
                    method: 'post'
                })
                    .success(function (result) {
                        console.log(result);
                    })
                    .fail(function () {
                        Common.genericError(arguments);
                    });
                return;
            });

        });
    };

    $.fn.SortableList = SortableList;
});