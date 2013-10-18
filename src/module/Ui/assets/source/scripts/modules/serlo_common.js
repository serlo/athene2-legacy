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

/*global define, window, console*/
define(['underscore', 'system_notification'], function (_, SystemNotification) {
    "use strict";
    var Common = {},
        slice = Array.prototype.slice;

    Common.log = (function () {
        var history = [];
        return function () {
            history.push(arguments);
            if (window.console) {
                console.log(Array.prototype.slice.call(arguments));
            }
        };
    }());

    Common.CarbonCopy = function (element) {
        if (!(element instanceof Array) && !(element instanceof Object)) {
            return element;
        }

        var copy = (function () {
            if (element instanceof Array) {
                return slice.call(element, 0);
            }

            if (element instanceof Object) {
                return _.extend({}, element);
            }

            throw new Error('Cant copy element');
        }());

        _.each(copy, function (item) {
            item = Common.CarbonCopy(item);
        });

        return copy;
    };

    Common.sortArrayByObjectKey = function (key, array, ascending) {
        ascending = ascending || false;
        return array.sort(function (a, b) {
            return ((a[key] < b[key]) ? -1 : ((a[key] > b[key]) ? 1 : 0)) * (ascending ? 1 : -1);
        });
    };

    Common.findObjectByKey = function (key, value, object) {
        var item;
        _.each(object, function (val) {
            if (val[key] === value) {
                item = val;
                return;
            }
        });
        return item;
    };

    Common.genericError = function () {
        if (console) {
            console.trace();
        }
        Common.log(arguments);
        SystemNotification.notify('An error occured, please reload.', 'danger');
        throw new Error('An error occured');
    };

    /*
    * memoize.js
    * by @philogb and @addyosmani
    * with further optimizations by @mathias
    * and @DmitryBaranovsk
    * perf tests: http://bit.ly/q3zpG3
    * Released under an MIT license.
    */
    Common.memoize = function (fn) {
        return function () {
            var args = Array.prototype.slice.call(arguments),
                hash = "",
                i = args.length,
                currentArg = null;
            while (i--) {
                currentArg = args[i];
                hash += (currentArg === Object(currentArg)) ? JSON.stringify(currentArg) : currentArg;
                if (!fn.memoize) {
                    fn.memoize = {};
                }
            }
            return (hash in fn.memoize) ? fn.memoize[hash] : (fn.memoize[hash] = fn.apply(this, args));
        };
    };

    Common.expr = function (statement) {
        return statement;
    };

    return Common;
});