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
/*global require*/
require.config({
    name: 'ATHENE2',
    baseUrl: "/assets/build/scripts",
    paths: {
        "jquery": "../bower_components/jquery/jquery",
        "jquery-ui" : "../bower_components/jquery-ui/ui/jquery-ui",
        "underscore": "../bower_components/underscore/underscore",
        "bootstrap": "../bower_components/sass-bootstrap/dist/js/bootstrap",
        "moment" : "../bower_components/momentjs/min/moment.min",
        "moment_de": "../bower_components/momentjs/lang/de",
        "common" : "modules/serlo_common",
        "easing" : "libs/easing",
        "events": "libs/eventscope",
        "cache": "libs/cache",
        "polyfills": "libs/polyfills",
        "referrer_history" : "modules/serlo_referrer_history",
        "side_navigation" : "modules/serlo_side_navigation",
        "ajax_overlay": "modules/serlo_ajax_overlay",
        "sortable_list" : "modules/serlo_sortable_list",
        "timeago" : "modules/serlo_timeago",
        "system_notification" : "modules/serlo_system_notification",
        "nestable" : "thirdparty/jquery.nestable",
        "translator" : "modules/serlo_translator",
        "i18n" : "modules/serlo_i18n",
        "layout" : "modules/serlo_layout",
        "search" : "modules/serlo_search",
        "support" : "modules/serlo_supporter",
        "modals" : "modules/serlo_modals",
        "router" : "modules/serlo_router"
    },
    shim: {
        underscore: {
            exports: '_'
        },
        bootstrap: {
            deps: ['jquery']
        },
        easing: {
            deps: ['jquery']
        },
        nestable: {
            deps: ['jquery']
        },
        ATHENE2: {
            deps: ['bootstrap', 'easing', 'nestable', 'polyfills']
        }
    },
    waitSeconds: 2
});