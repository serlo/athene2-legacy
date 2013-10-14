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
define("side_navigation", ["jquery", "underscore", "referrer_history"], function ($, _, ReferrerHistory, undefined) {
    "use strict";
    var defaults,
        instance,
        Hierarchy,
        SideNavigation;

    defaults = {
        // main wrapper selector
        mainId: '#main-nav',
        // active class, given to <li> elements
        activeClass: 'is-active'
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
         **/
        function loop($element, dataHierarchy, level) {

            $('> li', $element).each(function (i) {
                deepness.splice(level);
                deepness.push(i);

                var $listItem = $(this),
                    $link = $listItem.children().filter('a').first();

                dataHierarchy[i] = {
                    url: $link.attr('href'),
                    title: $link.text().trim(),
                    $li: $listItem,
                    $a: $link,
                    position: [].concat(deepness)
                };

                if ($listItem.children().filter('ul').length) {
                    dataHierarchy[i].children = [];
                    loop($listItem.children().filter('ul').first(), dataHierarchy[i].children, level + 1);
                }
            });
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
        this.$allLinks = $('li > a', this.$el);

        this.hierarchy = new Hierarchy();
        this.hierarchy.fetchFromDom(this.$el);

        this.active = this.hierarchy.findLastAvailableUrl();

        this.setActiveBranch();
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