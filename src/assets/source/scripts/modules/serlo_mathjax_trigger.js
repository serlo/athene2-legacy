/*global define, MathJax*/
define(['jquery'], function ($) {
    "use strict";
    var MathjaxTrigger;

    MathjaxTrigger = function () {
        return $(this).each(function () {
            var $that = $(this);
            $that.click(function () {
                MathJax.Hub.Queue(["Typeset", MathJax.Hub, $('.math, .mathInline').filter(':visible').toArray()]);
            });
        });
    };

    $.fn.MathjaxTrigger = MathjaxTrigger;
});