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
define("sortable_list", ["jquery", "underscore", "common", "translator", "system_notification"], function ($, _, Common, t, SystemNotification) {
    "use strict";
    var SortableList;

    SortableList = function () {
        return $(this).each(function () {
            var $instance = $(this),
                $saveBtn = $('.sortable-save-action', this),
                dataUrl,
                dataDepth,
                originalData,
                updatedData;

            dataUrl = $instance.attr('data-action');

            if (!dataUrl) {
                throw new Error('No sort action given for sortable wrapper.');
            }

            dataDepth = $instance.attr('data-depth') || 0;

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
                rootClass: 'sortable',
                listClass: 'sortable-list',
                itemClass: 'sortable-item',
                dragClass: 'sortable-dragel',
                handleClass: 'sortable-handle',
                collapsedClass: 'sortable-collapsed',
                placeClass: 'sortable-placeholder',
                noDragClass: 'sortable-nodrag',
                emptyClass: 'sortable-empty',

                expandBtnHTML: '',
                collapseBtnHTML: '',
                group: 0,
                maxDepth: dataDepth,
                threshold: 20
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
                    url: dataUrl,
                    data: {
                        sortable: updatedData
                    },
                    method: 'post'
                })
                    .success(function () {
                        SystemNotification.notify(t('Order successfully saved'), 'success');
                        $saveBtn.hide();
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