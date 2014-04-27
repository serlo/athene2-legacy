/*global define, MathJax, requestAnimationFrame*/
define(['jquery'], function ($) {
    "use strict";
    var MathjaxTrigger;

    MathjaxTrigger = function () {
        return $(this).on('shown.bs.collapse show.after shown.bs.tab shown.bs.popover shown.bs.modal', function () {
            var that = this;
            requestAnimationFrame(function () {
                var elements = $('.math, .mathInline', that).filter(':visible').toArray();
                if (elements.length) {
                    MathJax.Hub.Queue(["Typeset", MathJax.Hub, elements]);
                }
            });
        });
    };

    $.fn.MathjaxTrigger = MathjaxTrigger;
});