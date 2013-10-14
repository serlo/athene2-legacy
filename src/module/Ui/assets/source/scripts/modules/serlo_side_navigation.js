/**
 * 
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author  Julian Kempff (julian.kempff@serlo.org)
 * @license LGPL-3.0
 * @license http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link        https://github.com/serlo-org/athene2 for the canonical source repository
 * @copyright Copyright (c) 2013 Gesellschaft für freie Bildung e.V. (http://www.open-education.eu/)
 * 
 * The Main Navigation
 * 
 */

/*global define*/
define("side_navigation", ["jquery", "underscore", "referrer_history", "events"], function ($, _, ReferrerHistory, eventScope, undefined) {
    "use strict";
    var defaults,
        instance,
        Hierarchy,
        MenuItem,
        SubNavigation,
        SideNavigation;

    defaults = {
        // main wrapper selector
        mainId: '#main-nav',
        // active class, given to <li> elements
        activeClass: 'is-active'
    };

    /**
     * @class MenuItem
     * @param {Object} data All informations about the MenuItem (url, title, position)
     */
    MenuItem = function (data) {
        if (!data.url || !data.title || !data.position) {
            throw new Error("Not enough arguments");
        }

        this.data = data;
        this.$el = $('<li>');

        this.render();

        this.$el.click(this.onClick);
    };

    /**
     * @method render
     * 
     * Renders the a <li> and <a> tag on MenuItem.$el
     **/
    MenuItem.prototype.render = function () {
        var self = this;
        $('<li>').append($('<a>')
                    .text(self.data.title)
                    .attr('href', self.data.url))
                .appendTo(self.$el);
        return self;
    };

    /**
     * @method onClick
     * @param {jQuery Click Event} e
     *
     * OnClick handler for MenuItem
     **/
    MenuItem.prototype.onClick = function (e) {
        e.preventDefault();
        this.trigger('click', {
            originalEvent: e,
            menuItem: this
        });
        return;
    };

    /**
     * @method getChildren
     * @return {Array} Returns children or false
     **/
    MenuItem.prototype.getChildren = function () {
        return this.children || false;
    };

    eventScope(MenuItem);

    /**
     * @class SubNavigation
     * @param {Array} menuItems An array of MenuItems, to be rendered in an <ul>
     *
     * Renders the given menuItems in an <ul>
     **/

    SubNavigation = function (menuItems) {
        this.menuItems = menuItems;
        this.$el = $('<ul>');
        this.render();
    };

    /**
     * @method render
     *
     * Creates the <li> and <a> elements
     **/
    SubNavigation.prototype.render = function () {
        var self = this;
        self.$el.empty();
        _.each(self.menuItems, function (menuItem) {
            self.$el.append(menuItem.$el);
        });
        return this;
    };

    /**
     * @class Hierarchy
     **/
    Hierarchy = function () {
        this.data = [];
    };

    /**
     * @method fetchFromDom
     * @param {jQueryObject} $root
     * 
     * Loops through $root and creates an hierarchial array of objects
     **/
    Hierarchy.prototype.fetchFromDom = function ($root) {
        var self = this,
            deepness = [];

        self.data = [];
        self.$root = $root;

        /**
         * @function loop
         * @param {jQueryObject} $element The element containing the children to loop through
         * @param {Array} dataHierarchy The current hierarchy array
         * @param {Number} level The current level of hierarchy
         *
         * Creates a recursive reflection of the <li> tags in the given $element
         * on the Hierarchy (hierarchy.data)
         *
         * Also creates MenuItem instances for every link and adds event handlers
         **/
        function loop($element, dataHierarchy, level) {
            var subNavigation;
            $('> li', $element).each(function (i) {
                deepness.splice(level);
                deepness.push(i);

                var $listItem = $(this),
                    $link = $listItem.children().filter('a').first();

                dataHierarchy[i] = new MenuItem({
                    url: $link.attr('href'),
                    title: $link.text().trim(),
                    $li: $listItem,
                    $a: $link,
                    position: [].concat(deepness)
                });

                if ($listItem.children().filter('ul').length) {
                    dataHierarchy[i].children = [];
                    loop($listItem.children().filter('ul').first(), dataHierarchy[i].children, level + 1);
                }
            });
            subNavigation = new SubNavigation(dataHierarchy);
            $('body').append(subNavigation.$el);
        }

        loop(self.$root, self.data, 0);
        return this;
    };

    /**
     * @method findByUrl
     * @param {String} url
     * @return {Object} The first found menu item
     * 
     * Searches for a menu item by URL
     **/
    Hierarchy.prototype.findByUrl = function (url) {
        var self = this;

        /**
         * @function deepFlatten
         * @param {Object}
         * @return {Object} the original item OR {Array} an array with the item itself and its children
         * 
         * Recursive helper function to flatten the hierarchy to a one-level array
         **/
        function deepFlatten(item) {
            if (item.children) {
                return [].concat(item, deepFlatten(item.children));
            }
            return item;
        }

        return _.first(_.chain(self.data).map(deepFlatten).flatten().filter(function (item) {
            if (item.url === url) {
                return item;
            }
            return false;
        }).value());
    };

    /**
     * @method findLastAvailableUrl
     * @param {String} url
     * @return {Object} The first found menu item matching on the last ReferrerHistory entries.
     * 
     * Searches menu items by URL
     **/
    Hierarchy.prototype.findLastAvailableUrl = function () {
        var self = this,
            foundItem,
            lastUrls = ReferrerHistory.getAll();

        _.each(lastUrls, function (lastUrl) {
            var result = self.findByUrl(lastUrl);
            if (result) {
                foundItem = result;
                return;
            }
        });

        return foundItem;
    };

    /**
     * @method findByPosition
     * @param {Array} position An array of indexes
     * @return {MenuItem} or false
     *
     **/
    Hierarchy.prototype.findByPosition = function (position) {
        var cursor = this.data;
        _.each(position, function (index) {
            cursor = cursor[index] || (cursor.children && cursor.children[index]);
        });
        return cursor || false;
    };

    /**
     * @method getRaw
     * @return {Array} Returns the raw data hierarchy array
     **/
    Hierarchy.prototype.getRaw = function () {
        return this.data;
    };

    /**
     * @class SideNavigation
     * @param {Object} options See defaults
     * 
     * Main constructor
     **/
    SideNavigation = function (options) {
        if (!(this instanceof SideNavigation)) {
            return new SideNavigation(options);
        }

        this.options = options ? $.extend({}, defaults, options) : $.extend({}, defaults);

        this.$el = $(this.options.mainId);

        this.hierarchy = new Hierarchy();
        this.hierarchy.fetchFromDom(this.$el);

        this.active = this.hierarchy.findLastAvailableUrl();

        this.setActiveBranch();

        this.attachEventHandler();
    };

    /**
     * @method setActiveBranch
     * 
     * Sets options.activeClass for active menu item and its parents
     **/
    SideNavigation.prototype.setActiveBranch = function () {
        if (this.active) {
            this.active.$li
                .addClass(this.options.activeClass)
                .parents('li')
                .addClass(this.options.activeClass);
        }
        return this;
    };

    /**
     * @method attachEventHandler
     *
     * Attaches all needed event handlers
     **/
    SideNavigation.prototype.attachEventHandler = function () {
        var self = this,
            menuItems = this.hierarchy.getRaw();
        // add 'open' click event to first-level items
        _.each(menuItems, function (menuItem) {
            menuItem.addEventListener('click', function (e) {
                self.open(e);
            });

            if (menuItem.getChildren()) {
                _.each(menuItem.getChildren(), function (subItem) {
                    subItem.addEventListener('click', function (e) {
                        self.jumpTo(e);
                    });
                });
            }
        });
    };

    /**
     * SideNavigation constructor wrapper
     * for creating a singleton
     */
    return function (options) {
        // singleton
        return instance || (function () {
            instance = new SideNavigation(options);
            return instance;
        }());
    };
});