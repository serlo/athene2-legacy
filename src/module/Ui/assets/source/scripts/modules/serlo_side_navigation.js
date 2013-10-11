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
define("side_navigation", ["jquery", "referrer_history"], function ($, ReferrerHistory) {
    "use strict";
    var defaults,
        instance,
        Plugin;

    defaults = {
        mainId: '#serlo-nav'
    };

    Plugin = function (options) {
        if (!(this instanceof Plugin)) {
            return new Plugin(options);
        }

        this.options = options ? $.extend({}, defaults, options) : $.extend({}, defaults);

        this.$el = $(this.options.mainId);
        this.$allLinks = $('li > a', this.$el);

        this.$activeLink = this.findActiveByUrl(ReferrerHistory.getOne());
    };

    Plugin.prototype.findActiveByUrl = function (url) {
        return this.$allLinks.filter('[href=\"' + url + '\"]').first();
    };

    return function (options) {
        // singleton
        return instance || (function () {
            instance = new Plugin(options);
            return instance;
        }());
    };
});