/*global define*/
define(['jquery', 'underscore', 'common', 'router'], function ($, _, Common, Router) {
    "use strict";
    var Search,
        SearchResults,
        defaults = {
            url: 'search/ajax',
            wrapperSelector: '#search-content',
            inputSelector: '#search-input',
            resultWrapper: '#search-results',
            inFocusClass: 'is-in-focus',
            hasResultsClass: 'has-results',
            ajaxThrottling: 360,
            maxQueryLength: 3,
            ignoreKeys: [
                Common.KeyCode.shift,
                Common.KeyCode.backspace,
                Common.KeyCode.entf,
                Common.KeyCode.cmd,
                Common.KeyCode.up,
                Common.KeyCode.down
            ]
        };

    SearchResults = function (resultWrapperClass, $input) {
        this.$el = $(resultWrapperClass);
        this.$input = $input;
        this.clear();
    };

    SearchResults.prototype.clear = function () {
        this.activeFocus = 0;
        this.$el.empty();
    };

    SearchResults.prototype.show = function (groups) {
        var self = this;
        self.clear();
        self.count = 0;

        _.each(groups, function (group) {
            var $li = $('<li class="header">').append(group.title);
            self.$el.append($li);
            _.each(group.items, function (item) {
                var $li = $('<li>').append($('<a>').text(item.title).attr('href', item.url));
                self.$el.append($li);
                self.count += 1;
            });
        });

        self.$links = self.$el.find('li').filter(':not(.header)');

        this.setActiveItem();
    };

    SearchResults.prototype.onKey = function (e) {
        switch (e.keyCode) {
        case Common.KeyCode.up:
            e.preventDefault();
            this.focusPrev();
            return;
        case Common.KeyCode.down:
            e.preventDefault();
            this.focusNext();
            return;
        case Common.KeyCode.enter:
            Router.navigate(this.$el.find('.active').children().first().attr('href'));
            this.$input.blur();
            break;
        }
    };

    SearchResults.prototype.focusNext = function () {
        this.activeFocus += 1;
        if (this.activeFocus >= this.count) {
            this.activeFocus = 0;
        }
        this.setActiveItem();
    };

    SearchResults.prototype.focusPrev = function () {
        this.activeFocus -= 1;
        if (this.activeFocus < 0) {
            this.activeFocus = this.count - 1;
        }
        this.setActiveItem();
    };

    SearchResults.prototype.setActiveItem = function () {
        this.$el.find('.active').removeClass('active');
        var $next = this.$links.eq(this.activeFocus);
        $next.addClass('active');
    };

    Search = function (options) {
        var self = this;

        self.options = $.extend({}, defaults, options || {});
        self.$el = $(self.options.wrapperSelector);
        self.$input = $(self.options.inputSelector);
        self.results = new SearchResults(self.options.resultWrapper, self.$input);

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

                // Keep track on the users mouse actions
                // to prevent too fast result clearing
                self.$el.mousedown(function () {
                    self.onMouseDown();
                });
                self.$el.mouseup(function () {
                    self.onMouseUp();
                });
            })
            .bind('focusout', function () {
                function clearAndHide() {
                    self.results.clear();
                    self.$el.removeClass(self.options.inFocusClass).removeClass(self.options.hasResultsClass);
                    self.$el.unbind('mousedown').unbind('mouseup');
                }

                // If the user currently has the mouse down
                // on self.$el, he probably wants to click
                // on a search result. So we set a timeout
                // to make sure the results dont disappear
                // before the user can click on them.
                if (self.mouseIsDown) {
                    setTimeout(function () {
                        clearAndHide();
                    }, 400);
                } else {
                    clearAndHide();
                }
            })
            .keydown(function (e) {
                var value = Common.trim($(this).val() || "");
                self.results.onKey(e);
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

    Search.prototype.onMouseDown = function () {
        this.mouseIsDown = true;
    };

    Search.prototype.onMouseUp = function () {
        this.mouseIsDown = false;
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

        self.ajax.success(function (data) {
            self.onResult(data);
        }).fail(function () {
            self.$input.blur();
            Common.genericError();
        });
    };

    Search.prototype.onResult = function (result) {
        var self = this;
        if (self.$el.hasClass(self.options.inFocusClass)) {
            self.results.clear();
            if (result.length) {
                self.$el.addClass(self.options.hasResultsClass);
                self.results.show(result);
            } else {
                self.$el.removeClass(self.options.hasResultsClass);
            }
        }
    };

    return Search;
});