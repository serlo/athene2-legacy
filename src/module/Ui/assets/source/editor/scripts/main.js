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
        "jquery": "../../bower_components/jquery/jquery",
        "bootstrap": "../../bower_components/sass-bootstrap/dist/js/bootstrap",
        "underscore": "../../bower_components/underscore/underscore",
        "moment" : "../../bower_components/momentjs/min/moment.min",
        "moment_de": "../../bower_components/momentjs/lang/de",
        "common" : "../../scripts/modules/serlo_common",
        "events": "../../scripts/libs/eventscope",
        "cache": "../../scripts/libs/cache",
        "polyfills": "../../scripts/libs/polyfills",
        "datepicker" : "../../bower_components/bootstrap-datepicker/js/bootstrap-datepicker",
        "translator" : "../../scripts/modules/serlo_translator",
        "i18n" : "../../scripts/modules/serlo_i18n",
        "support" : "../../scripts/modules/serlo_supporter",
        "modals" : "../../scripts/modules/serlo_modals"
    },
    shim: {
        underscore: {
            exports: '_'
        },
        bootstrap: {
            deps: ['jquery']
        },
        datepicker: {
            deps: ['jquery', 'bootstrap']
        },
        "ATHENE2-EDITOR": {
            deps: ['bootstrap', 'polyfills', 'datepicker']
        }
    },
    waitSeconds: 12
});