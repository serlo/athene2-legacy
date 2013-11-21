/*global define, window*/
define(['jquery'], function ($) {
    "use strict";
    var Layout,
        defaults = {
            leftToggle: '#navigation-toggle',
            leftToggleClass: 'slide-right',
            rightToggle: '#sidebar-toggle',
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
        this.$rightToggle = $(this.options.rightToggle);
        this.attachHandler();
    };

    Layout.prototype.attachHandler = function () {
        var self = this;
        self.$leftToggle.click(function () {
            self.onLeftTogglerClick();
        });

        self.$rightToggle.click(function () {
            self.onRightTogglerClick();
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
        this.$body.removeClass(this.options.rightToggleClass);
        this.$body.toggleClass(this.options.leftToggleClass);
    };

    Layout.prototype.onRightTogglerClick = function () {
        this.$body.removeClass(this.options.leftToggleClass);
        this.$body.toggleClass(this.options.rightToggleClass);
    };

    return {
        init: function (options) {
            return new Layout(options);
        }
    };
});