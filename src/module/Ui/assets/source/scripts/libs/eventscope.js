/**
 * 
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author  Julian Kempff (julian.kempff@serlo.org)
 * @license LGPL-3.0
 * @license http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link        https://github.com/serlo-org/athene2 for the canonical source repository
 * @copyright Copyright (c) 2013 Gesellschaft für freie Bildung e.V. (http://www.open-education.eu/)
 */

/*global define*/
define(['underscore'], function (_) {
    "use strict";
    return function (element) {
        var Events = {},
            fn = {};

        fn.addEventListener = function (type, fn) {
            Events[type] = Events[type] || [];
            Events[type].push(fn);
            return true;
        };

        fn.removeEventListener = function (type, fn) {
            return !Events[type] || (function () {
                if (fn === undefined) {
                    delete Events[type];
                    return true;
                }

                _.each(Events[type], function (i) {
                    if (Events[type][i] === fn) {
                        Events[type].splice(i, 1);
                        return false;
                    }
                });
            }());
        };

        fn.trigger = function (type) {
            var self = this,
                slice = Array.prototype.slice.bind(arguments);

            if (!Events.hasOwnProperty(type)) {
                return true;
            }

            _.each(Events[type], function (fn) {
                fn.apply(self, slice(1));
            });
        };

        switch (element.constructor.name) {
        case 'Object':
            _.extend(element, fn);
            break;
        case 'Function':
            _.extend(element.prototype, fn);
            break;
        default:
            throw new Error('Unable to create eventScope for element of type ' + element.constructor.name);
        }
    };
});