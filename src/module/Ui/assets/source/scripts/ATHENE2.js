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
/*global define, require*/
define("ATHENE2", ['jquery', 'common', 'side_navigation', 'translator', 'layout', 'search', 'system_notification', 'sortable_list'],
    function ($, Common, SideNavigation, t, Layout, Search, SystemNotification) {
        "use strict";

        function init() {
            t.config({
                language: 'de_DE'
            });

            Common.addEventListener('generic error', function () {
                SystemNotification.notify(t('An error occured, please reload.'), 'danger');
            });

            new SideNavigation();
            new Search();

            $('.sortable').SortableList();


            Layout.init();
        }

        return {
            initialize: function () {
                init();
            }
        };
    });

require(['jquery', 'ATHENE2', 'support'], function ($, App, Supporter) {
    "use strict";
    $(function () {
        Supporter.check();
        App.initialize();
    });
});