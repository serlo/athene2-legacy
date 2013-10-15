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
        activeClass: 'is-active',
        // class given to menu items the user is navigating through
        activeNavigatorClass: 'is-nav-active',
        // width of the subnavigation
        subNavigationWidth: 260,
        // duration of slide animation
        animationDuration: 150
    };

    /**
     * @function deepFlatten
     * @param {Array} the array
     * @return {UnderscoreChain}
     * 
     * Helper function
     **/
    function deepFlatten(array) {
        function dm(item) {
            if (item.children) {
                return [].concat(item, _.chain(item.children).map(dm).flatten().value());
            }
            return item;
        }
        return _.chain(array).map(dm).flatten();
    }

    /**
     * @class MenuItem
     * @param {Object} data All informations about the MenuItem (url, title, position, level)
     */
    MenuItem = function (data) {
        if (data.url === undefined || !data.title || !data.position || data.level === undefined) {
            throw new Error("Not enough arguments");
        }

        this.data = data;
        this.$el = $('<li>');

        this.render();
    };

    /**
     * @method render
     * 
     * Renders the a <li> and <a> tag on MenuItem.$el
     **/
    MenuItem.prototype.render = function () {
        var self = this;
        this.$el.empty();
        if (self.data.level === 0) {
            self.data.$a.click(function (e) {
                self.onClick(e);
            });
        } else {
            $('<a>')
                .text(self.data.title)
                .click(function (e) {
                    self.onClick(e);
                })
                .attr('href', self.data.url)
                .appendTo(self.$el);
        }
        return self;
    };

    /**
     * @method onClick
     * @param {jQuery Click Event} e
     *
     * OnClick handler for MenuItem
     **/
    MenuItem.prototype.onClick = function (e) {
        if (this.children || this.alwaysPrevent) {
            e.preventDefault();
            this.trigger('click', {
                originalEvent: e,
                menuItem: this
            });
        } else {
            this.trigger('reload', {
                originalEvent: e
            });
        }
    };

    /**
     * @method getChildren
     * @return {Array} Returns children or false
     **/
    MenuItem.prototype.getChildren = function () {
        return this.children || false;
    };

    /**
     * @class SubNavigation
     * @param {Array} levels An array of levels, containing MenuItems, to be rendered in an <ul>
     *
     * Creates <ul>s for each level and renders them
     **/

    SubNavigation = function (levels) {
        this.$el = $('<div>');
        this.reset(levels);
    };

    /**
     * @method reset
     * @param {Array} levels An array of levels, containing MenuItems, to be rendered in an <ul>
     *
     **/
    SubNavigation.prototype.reset = function (levels) {
        this.levels = levels;
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

        _.each(self.levels, function (level) {
            var $ul = $('<ul>');
            _.each(level, function (menuItem) {
                $ul.append(menuItem.render().$el);
            });
            self.$el.append($ul);
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
                    position: [].concat(deepness),
                    level: level
                });

                eventScope(dataHierarchy[i]);

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

        return _.first(deepFlatten(self.data).filter(function (menuItem) {
            if (menuItem.data.url === url) {
                return menuItem;
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
        if (position.length === 1) {
            return this.data[position[0]];
        }

        var cursor = this.data[position[0]];

        position = position.slice();
        position.shift();

        _.each(position, function (index) {
            cursor = cursor.children[index];
        });

        return cursor || false;
    };

    /**
     * @method getFlattened
     * @return {Array} Returns an array of all MenuItems without hierarchy
     **/
    Hierarchy.prototype.getFlattened = function () {
        return deepFlatten(this.data).value();
    };

    /**
     * @method getLevels
     * @param {Array} position An array of indexes
     * @return {Array} an Array of Levels
     *
     **/
    Hierarchy.prototype.getLevels = function (position) {
        var self = this,
            cursor = self.data,
            result = [];
        _.each(position, function (index) {
            result.push(cursor[index].children);
            cursor = cursor[index].children;
        });
        return result;
    };

    /**
     * @method getParents
     * @param {Array} position An array of indexes
     * @return {Array} an Array of menuItems
     *
     **/
    Hierarchy.prototype.getParents = function (position) {
        var result = [],
            usePosition = position.slice();

        while (usePosition.length) {
            result.push(this.findByPosition(usePosition));
            usePosition.pop();
        }

        return result;
    };

    /**
     * @method getParent
     * @param {MenuItem} menuItem A menuItem
     * @return {MenuItem} The direct parent of the given MenuItem
     **/
    Hierarchy.prototype.getParent = function (menuItem) {
        var parents = this.getParents(menuItem.data.position).reverse();
        parents.pop();
        return parents[parents.length - 1];
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

        // when force gets true, a click on $el will propagate
        this.force = false;

        this.$el = $(this.options.mainId);
        this.$nav = $('<nav id="serlo-side-sub-navigation">');
        this.$mover = $('<div id="serlo-side-sub-navigation-mover">');
        this.$nav.append(this.$mover);
        this.$mover.css('left', 0);
        this.$breadcrumbs = $('<ul id="serlo-side-navigation-breadcrumbs" class="nav">');

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
        var self = this,
            position,
            parents,
            $rootMenuItem;

        if (self.active) {
            position = self.active.data.position;

            self.active.$el
                .addClass(self.options.activeClass)
                .parents('li')
                .addClass(self.options.activeClass);

            // set the original root elements activeClass
            $rootMenuItem = $('> li', self.$el).eq(position[0]).addClass(self.options.activeClass);

            // Create 'breadcrumbs'
            parents = self.hierarchy.getParents(position).reverse();
            parents.shift();

            if (parents.length) {
                _.each(parents, function (menuItem) {
                    var breadcrumb = new MenuItem(menuItem.data);

                    breadcrumb.alwaysPrevent = true;

                    eventScope(breadcrumb);

                    breadcrumb.addEventListener('click', function (e) {
                        var parentMenuItem = self.hierarchy.getParent(e.menuItem);
                        e.originalEvent.preventDefault();
                        self.navigatedMenuItem = parentMenuItem;
                        self.jumpTo(parentMenuItem);
                    });

                    self.$breadcrumbs.append(breadcrumb.$el);
                });

                self.$breadcrumbs.insertAfter($rootMenuItem);
            }
        }
        return self;
    };

    /**
     * @method setActiveNavigator
     * 
     * Sets the options.activeNavigatorClass for menu items the user is navigating with
     **/
    SideNavigation.prototype.setActiveNavigator = function () {
        $('.' + this.options.activeNavigatorClass, this.$el)
            .removeClass(this.options.activeNavigatorClass);

        if (this.navigatedMenuItem) {
            this.navigatedMenuItem.$el
                .addClass(this.options.activeNavigatorClass)
                .parents('li')
                .addClass(this.options.activeNavigatorClass);
            // set the original root elements activeClass
            $('> li', this.$el).eq(this.navigatedMenuItem.data.position[0]).addClass(this.options.activeNavigatorClass);
        }
    };

    /**
     * @method attachEventHandler
     *
     * Attaches all needed event handlers
     **/
    SideNavigation.prototype.attachEventHandler = function () {
        var self = this,
            menuItems = this.hierarchy.getFlattened();

        // add 'open' click event to first-level items
        _.each(menuItems, function (menuItem) {
            menuItem.addEventListener('click', function (e) {
                e.originalEvent.preventDefault();
                self.navigatedMenuItem = e.menuItem;
                self.jumpTo(e.menuItem);
            });

            menuItem.addEventListener('reload', function () {
                self.force = true;
                self.close();
            });
        });

        $('body').on('click', function () {
            self.close();
        });

        self.$el.click(function (e) {
            if (!self.force) {
                e.preventDefault();
                e.stopPropagation();
                return;
            }
        });
    };

    /**
     * @method open
     * @param {Object} menuItem The clicked MenuItem instance
     *
     * Shows the generated Subnavigation
     **/
    SideNavigation.prototype.open = function (menuItem) {
        this.isOpen = true;
        this.$nav.appendTo(this.$el);
        this.routeAnimation(menuItem);
    };

    /**
     * @method close
     *
     * Hides the generated Subnavigations
     **/
    SideNavigation.prototype.close = function () {
        this.isOpen = false;
        this.$nav.remove();
    };

    /**
     * @method jumpTo
     * @param {Object} menuItem The clicked MenuItem instance
     *
     * Starts animation to the clicked MenuItem
     **/
    SideNavigation.prototype.jumpTo = function (menuItem) {
        if (!this.isOpen) {
            this.open(menuItem);
        } else {
            this.routeAnimation(menuItem);
        }
    };

    /**
     * @method routeAnimation
     * @param {Object} menuItem The target MenuItem instance
     *
     * Animations!!!!
     **/
    SideNavigation.prototype.routeAnimation = function (menuItem) {
        var self = this,
            startLevels,
            breakpoint;

        if (!self.activeLevels) {
            self.activeLevels = self.hierarchy.getLevels(menuItem.data.position);

            self.subNavigation = new SubNavigation(self.activeLevels);
            self.subNavigation.$el.appendTo(self.$mover);
        }

        startLevels = self.activeLevels;

        self.activeLevels = self.hierarchy.getLevels(menuItem.data.position);
        // determine position crossing
        breakpoint = self.determineBreakpoint(startLevels, self.activeLevels);
        // start and end level is 1, we dont need any animation
        if (startLevels.length === 1 && self.activeLevels.length === 1) {
            self.subNavigation.reset(self.activeLevels);
            self.setActiveNavigator();
        } else {
            if (breakpoint === startLevels.length) {
                // breakpoint is current level,
                // so we only need one animation
                self.subNavigation.reset(self.activeLevels);
                self.setActiveNavigator();
                self.animateTo(self.activeLevels.length);
            } else {
                // we need two animations,
                // first to our breakpoint
                // then to our target
                self.animateTo(breakpoint, function () {
                    self.subNavigation.reset(self.activeLevels);
                    self.setActiveNavigator();
                    self.animateTo(self.activeLevels.length);
                });
            }
        }
    };

    /**
     * @method animateTo
     * @param {Number} level
     * @param {Function} callback
     **/
    SideNavigation.prototype.animateTo = function (level, callback) {
        var self = this,
            targetLeft = ((level - 1) * -1 * self.options.subNavigationWidth) + 'px';

        if (self.$mover.css('left') === targetLeft && callback) {
            callback();
            return;
        }

        self.$mover.animate({
            left: targetLeft
        }, {
            complete: function () {
                if (callback !== undefined) {
                    callback();
                }
            },
            duration: self.options.animationDuration,
            easing: 'easeOutExpo'
        });
    };

    /**
     * @method determineBreakpoint
     * @param {Array} start
     * @param {Array} end
     * @return {Object} MenuItem
     **/
    SideNavigation.prototype.determineBreakpoint = function (start, end) {
        var result = 1,
            startReverse = start.slice(),
            endReverse = end.slice().splice(0, start.length);

        if (startReverse.length > endReverse.length) {
            startReverse = startReverse.splice(0, endReverse.length);
        }

        _.each(startReverse, function (level, index) {
            if (endReverse[index] !== undefined) {
                if (_.isEqual(level[0].data.position, endReverse[index][0].data.position)) {
                    result = index + 1;
                    return;
                }
            }
        });

        return result;
    };

    /**
     * SideNavigation constructor wrapper
     * for creating a singleton
     */
    return function (options) {
        // singleton
        return instance || (function () {
            instance = new SideNavigation(options);
            return instance;
        }());
    };
});