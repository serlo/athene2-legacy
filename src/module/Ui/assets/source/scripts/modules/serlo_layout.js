/*global define*/
define(['jquery'], function ($) {
    "use strict";
    var Layout,
        defaults = {
            leftToggle: '#navigation-toggle',
            leftToggleClass: 'slide-right',
            rightToggle: '#context-toggle',
            rightToggleClass: 'slide-left'
        };

    Layout = function (options) {
        this.options = options ? $.extend({}, defaults, options) : $.extend({}, defaults);

        this.$window = $(window);
        this.$body = $('body');
        this.$leftToggle = $(this.options.leftToggle);
        this.attachHandler();
    };

    Layout.prototype.attachHandler = function () {
        var self = this;
        self.$leftToggle.click(function () {
            self.onLeftTogglerClick();
        });

        self.$window.resize(function () {
            // do stuff
        });
    };

    Layout.prototype.onLeftTogglerClick = function () {
        this.$body.toggleClass(this.options.leftToggleClass);
    };

    return {
        init: function (options) {
            return new Layout(options);
        }
    }; 
});