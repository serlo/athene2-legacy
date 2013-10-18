/*global define*/
define(['jquery', 'translator'], function ($, t) {
    "use strict";
    var rootSelector = '#content-container',
        $wrapper,
        SystemNotification,
        errorMessage = t('An error occured, please reload.'),
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
        var self = this,
            $close = $('<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>')
                .click(function () {
                    self.$el.remove();
                });

        status = status || 'info';
        self.$el = $('<div class="alert">');

        if (status) {
            self.$el.addClass('alert-' + status);
        }

        if (html) {
            self.$el.html(message);
        } else {
            self.$el.text(message);
        }

        self.$el.append($close);
    };

    return {
        notify: function (message, status, html) {
            showNotification(message, status, html);
        },
        error: function (message) {
            this.notify(message || errorMessage, 'danger');
        }
    };
});