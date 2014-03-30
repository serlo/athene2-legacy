/*global jQuery*/
(function ($) {
    $.each(['show', 'hide', 'toggle', 'slideToggle', 'fadeIn', 'slideDown', 'animate'], function (i, ev) {
        var el = $.fn[ev];
        $.fn[ev] = function () {
            var ret = el.apply(this, arguments);
            this.trigger(ev);
            return ret;
        };
    });
})(jQuery);