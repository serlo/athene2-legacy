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
/*global define, require, MathJax*/
define("ATHENE2", ['jquery', 'common', 'side_navigation', 'translator', 'layout', 'content', 'search', 'system_notification',
                    'moment', 'ajax_overlay', 'modals', 'sortable_list', 'timeago', 'spoiler', 'injections', 'moment_de'],
    function ($, Common, SideNavigation, t, Layout, Content, Search, SystemNotification, moment, AjaxOverlay) {
        "use strict";
        var languageFromDOM,
            ajaxOverlay;

        function init($context) {
            languageFromDOM = $('html').attr('lang') || 'de';
            // configure Translator to current language
            t.config({
                language: languageFromDOM
            });

            moment.lang(languageFromDOM);

            // create an system notifiction whenever Common.genericError is called
            Common.addEventListener('generic error', function () {
                SystemNotification.error();
            });
            // initialize contextuals whenever a new context is added

            Content.add(function ($context) {
                // init sortable lists in context
                $('.sortable', $context).SortableList();
                // init timeago fields in context
                $('.timeago', $context).TimeAgo();
                // init dialogues in context
                $('.dialog', $context).SerloModals();
                // init datepicker
                $('.datepicker', $context).datepicker({
                    format: 'dd.mm.yyyy'
                });
                // init datepicker for dateranges
                $('.input-daterange', $context).datepicker({
                    format: 'dd.mm.yyyy'
                });
                // init spoilers
                $('.spoiler').Spoiler();
                // init injections
                $('.injection').Injections();
            });

            Common.addEventListener('new context', function ($context) {
                Content.init($context);
            });

            // initialize the side navigation
            new SideNavigation();
            // initialize the search
            new Search();
            // initialize ajax overlay
            ajaxOverlay = new AjaxOverlay({
                on: {
                    contentOpened: function () {
                        Content.init(this.$el);
                    },
                    error: function (err) {
                        ajaxOverlay.shutDownAjaxContent();
                        SystemNotification.error(t("When asynchronously trying to load a ressource, I came across an error: %s", err.status + ' ' + err.statusText));
                    }
                }
            });

            // trigger new contextual
            Common.trigger('new context', $context);

            Layout.init();
        }

        return {
            initialize: function ($context) {
                init($context);
            }
        };
    });

require(['jquery', 'ATHENE2', 'support'], function ($, App, Supporter) {
    "use strict";

    if (typeof MathJax !== undefined) {
        MathJax.Hub.Config({
            displayAlign: 'left',
            extensions: ["tex2jax.js"],
            jax: ["input/TeX", "output/HTML-CSS"],
            tex2jax: {
                inlineMath: [["%%", "%%"]]
            },
            "HTML-CSS": {
                scale: 100
            }
        });
    }

    $(function () {
        Supporter.check();
        App.initialize($('body'));
    });
});