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
 * Saves the browser pathname on the client side.
 */

/*global define*/
define("side_navigation", ["jquery", "underscore", "referrer_history"], function ($, _, ReferrerHistory, undefined) {
    "use strict";
    var defaults,
        instance,
        Hierarchy,
        Plugin;

    defaults = {
        mainId: '#main-nav',
        activeClass: 'is-active'
    };

    Hierarchy = function () {
        this.data = [];
    };

    Hierarchy.prototype.fetchFromDom = function ($root) {
        var self = this,
            deepness = [];

        self.data = [];
        self.$root = $root;

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

    Hierarchy.prototype.findByUrl = function (url) {
        var self = this;

        function deepFlatten(item) {
            if (item.children) {
                return [].concat(item, deepFlatten(item.children));
            }
            return item;
        }

        function find(dataHierarchy) {
            return _.chain(dataHierarchy).map(deepFlatten).flatten().filter(function (item) {
                if (item.url === url) {
                    return item;
                }
                return false;
            }).value();
        }

        return _.first(find(self.data));
    };

    Plugin = function (options) {
        if (!(this instanceof Plugin)) {
            return new Plugin(options);
        }

        this.options = options ? $.extend({}, defaults, options) : $.extend({}, defaults);

        this.$el = $(this.options.mainId);
        this.$allLinks = $('li > a', this.$el);

        this.hierarchy = new Hierarchy();
        this.hierarchy.fetchFromDom(this.$el);

        this.active = this.hierarchy.findByUrl(ReferrerHistory.getOne());

        this.setActiveBranch();
    };

    Plugin.prototype.findActiveByUrl = function (url) {
        return this.$allLinks.filter('[href=\"' + url + '\"]').first();
    };

    Plugin.prototype.setActiveBranch = function () {
        if (this.active) {
            this.active.$li
                .addClass(this.options.activeClass)
                .parents('li')
                .addClass(this.options.activeClass);
        }
        return this;
    };

    return function (options) {
        // singleton
        return instance || (function () {
            instance = new Plugin(options);
            return instance;
        }());
    };
});