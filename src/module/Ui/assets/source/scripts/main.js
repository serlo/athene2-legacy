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
        "jquery": "../bower_components/jquery/jquery.min",
        "jquery-ui" : "../bower_components/jquery-ui/ui/jquery-ui",
        "underscore": "../bower_components/underscore/underscore",
        "bootstrap": "../bower_components/sass-bootstrap/dist/js/bootstrap",
        "common" : "modules/serlo_common",
        "events": "libs/eventscope",
        "cache": "libs/cache",
        "referrer_history" : "modules/serlo_referrer_history",
        "side_navigation" : "modules/serlo_side_navigation"
    },
    shim: {
        underscore: {
            exports: '_'
        },
        bootstrap: {
            deps: ['jquery']
        },
        ATHENE2: {
            deps: ['bootstrap']
        }
    },
    waitSeconds: 2
});