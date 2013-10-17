/*global define, window*/
define(['jquery'], function ($) {
    "use strict";
    var Layout,
        defaults = {
            leftToggle: '#navigation-toggle',
            leftToggleClass: 'slide-right',
            rightToggle: '#context-toggle',
            rightToggleClass: 'slide-left',
            // Full Stack Breakpoint Grid
            fullStackBreakPoint: 1350,
            // Sidebar Breakpoint Grid
            sidebarBreakPoint: 980,
            // Navigation Breakpoint Grid
            navigationBreakPoint: 1140
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
            var windowWidth = self.$window.width();

            if (windowWidth > self.options.sidebarBreakPoint) {
                self.$body.removeClass(self.options.rightToggleClass);
            }

            if (windowWidth > self.options.navigationBreakPoint) {
                self.$body.removeClass(self.options.leftToggleClass);
            }

            // if (windowWidth > self.options.fullStackBreakPoint) {
            //    
            // }
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