/*global define*/
define(['jquery'], function ($) {
    "use strict";
    var rootSelector = '#page',
        $wrapper,
        SystemNotification,
        /**
         * allowed status:
         *   success, info, warning, danger
         **/
        showNotification = function (message, status, html) {
            var notification;

            if (!$wrapper) {
                $wrapper = $('<div id="system-notification">');
                $(rootSelector).prepend($wrapper);
            }

            notification = new SystemNotification(message, status, html);
            $wrapper.append(notification.$el);
        };

    SystemNotification = function (message, status, html) {
        status = status || 'info';
        this.$el = $('<div class="alert">');
        if (status) {
            this.$el.addClass('alert-' + status);
        }
        if (html) {
            this.$el.html(message);
        } else {
            this.$el.text(message);
        }
    };

    return {
        notify: function (message, status, html) {
            showNotification(message, status, html);
        }
    };
});