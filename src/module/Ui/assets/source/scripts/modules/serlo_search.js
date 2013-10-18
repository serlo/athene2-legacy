/*global define*/
define(['jquery', 'underscore', 'common'], function ($, _, Common) {
    "use strict";
    var Search,
        defaults = {
            url: 'search/ajax',
            wrapperSelector: '#search-content',
            inputSelector: '#search-input',
            inFocusClass: 'is-in-focus',
            ajaxThrottling: 360
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
                switch (e.keyCode) {
                case Common.KeyCode.esc:
                    self.$input.blur();
                    break;
                default:
                    self.search($(this).val());
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