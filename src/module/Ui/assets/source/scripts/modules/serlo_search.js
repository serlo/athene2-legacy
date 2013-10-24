/*global define*/
define(['jquery', 'underscore', 'common'], function ($, _, Common) {
    "use strict";
    var Search,
        defaults = {
            url: 'search/ajax',
            wrapperSelector: '#search-content',
            inputSelector: '#search-input',
            inFocusClass: 'is-in-focus',
            ajaxThrottling: 360,
            maxQueryLength: 3,
            ignoreKeys: [
                Common.KeyCode.shift,
                Common.KeyCode.backspace,
                Common.KeyCode.entf,
                Common.KeyCode.cmd
            ]
        };

    Search = function (options) {
        var self = this;

        self.options = $.extend({}, defaults, options || {});
        self.$el = $(self.options.wrapperSelector);
        self.$input = $(self.options.inputSelector);

        self.origPerformSearch = self.performSearch;
        self.performSearch = _.throttle(function (string) {
            self.origPerformSearch(string);
        }, self.options.ajaxThrottling);

        self.attachHandler();
    };

    Search.prototype.attachHandler = function () {
        var self = this;
        this.$input
            .focus(function () {
                self.$el.addClass(self.options.inFocusClass);
            })
            .bind('focusout', function () {
                self.$el.removeClass(self.options.inFocusClass);
            })
            .keydown(function (e) {
                var value = Common.trim($(this).val() || "");
                if (_.indexOf(self.options.ignoreKeys, e.keyCode) >= 0) {
                    return true;
                }

                switch (e.keyCode) {
                case Common.KeyCode.esc:
                    self.$input.blur();
                    break;
                default:
                    Common.expr(value.length < self.options.maxQueryLength || self.search(value));
                    break;
                }
            });
    };

    Search.prototype.search = function (string) {
        this.performSearch(string);
    };

    Search.prototype.performSearch = function (string) {
        var self = this;

        self.ajax = $.ajax({
            url: self.options.url,
            data: {
                q: string
            },
            method: 'post'
        });

        self.ajax.success(this.onResult).fail(function () {
            Common.genericError();
        });
    };

    Search.prototype.onResult = function (result) {
        Common.log(result);
    };

    return Search;
});