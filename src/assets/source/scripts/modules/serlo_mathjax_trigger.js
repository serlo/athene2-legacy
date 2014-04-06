/*global define, MathJax*/
define(['jquery'], function ($) {
    "use strict";
    var MathjaxTrigger;

    MathjaxTrigger = function () {
        return $(this).on('shown.bs.collapse show.after shown.bs.tab shown.bs.popover shown.bs.modal', function () {
            var elements = $('.math, .mathInline', this).filter(':visible').toArray();
            if (elements.length) {
                MathJax.Hub.Queue(["Typeset", MathJax.Hub, elements]);
            }
        });
    };

    $.fn.MathjaxTrigger = MathjaxTrigger;
});