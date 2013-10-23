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
define("ATHENE2", ['jquery', 'common', 'side_navigation', 'translator', 'layout', 'search', 'system_notification', 'modals', 'sortable_list', 'timeago'],
    function ($, Common, SideNavigation, t, Layout, Search, SystemNotification) {
        "use strict";

        function init($context) {
            // configure Translator to current language
            t.config({
                language: document.getElementsByTagName('html')[0].attributes.lang.value || 'de'
            });

            // create an system notifiction whenever Common.genericError is called
            Common.addEventListener('generic error', function () {
                SystemNotification.error();
            });
            // initialize contextuals whenever a new context is added
            Common.addEventListener('new context', function ($context) {
                initContextuals($context);
            });

            // initialize the side navigation
            new SideNavigation();
            // initialize the search
            new Search();

            // trigger new contextual
            Common.trigger('new context', $context);

            Layout.init();
        }

        function initContextuals($context) {
            // init sortable lists in context
            $('.sortable', $context).SortableList();
            // init timeago fields in context
            $('.timeago', $context).TimeAgo();
            // init dialogues in context
            $('.dialog', $context).SerloModals();
        }

        return {
            initialize: function ($context) {
                init($context);
            }
        };
    });

require(['jquery', 'ATHENE2', 'support'], function ($, App, Supporter) {
    "use strict";
    $(function () {
        Supporter.check();
        App.initialize($('body'));
    });
});