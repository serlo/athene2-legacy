/*global define, MathJax*/
define(['jquery'], function ($) {
    "use strict";
    var MathjaxTrigger;

    MathjaxTrigger = function () {
        return $(this).on('shown.bs.collapse slideToggle.after toggle.after show.after fadeIn.after slideDown.after animate.after shown.bs.tab shown.bs.popover shown.bs.modal', function () {
            MathJax.Hub.Queue(["Typeset", MathJax.Hub, $('.math, .mathInline', this).filter(':visible').toArray()]);
        });
    };

    $.fn.MathjaxTrigger = MathjaxTrigger;
});