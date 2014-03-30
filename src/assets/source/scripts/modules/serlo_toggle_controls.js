/*global define*/
define(['jquery'], function ($) {
    "use strict";
    var ToggleControls;

    ToggleControls = function () {
        return $(this).each(function () {
            // Edit mode toggle
            if ($(this).data('toggle') === 'edit-controls') {
                $(this)
                    .unbind('click')
                    .click(function (e) {
                        e.preventDefault();
                        var $that = $(this);
                        $that.toggleClass('active');
                        $('.edit-control').toggleClass('hidden');
                        return;
                    });
            }
        });
    };

    $.fn.ToggleControls = ToggleControls;
});