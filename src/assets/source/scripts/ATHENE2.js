/**
 *
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author  Julian Kempff (julian.kempff@serlo.org)
 * @license LGPL-3.0
 * @license http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link        https://github.com/serlo-org/athene2 for the canonical source repository
 */
/*global define, require, MathJax*/
define("ATHENE2", ['jquery', 'common', 'side_navigation', 'translator', 'side_element', 'content', 'search', 'system_notification',
        'moment', 'ajax_overlay', 'toggle_action', 'modals', 'trigger', 'sortable_list', 'timeago', 'spoiler', 'injections', 'moment_de', 'mathjax_trigger', 'affix', 'forum_select'
    ],
    function ($, Common, SideNavigation, t, SideElement, Content, Search, SystemNotification, moment, AjaxOverlay) {
        "use strict";
        var languageFromDOM,
            ajaxOverlay;

        function init($context) {
            languageFromDOM = $('html').attr('lang') ||  'de';
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

            $('.side-element, .page-header').SerloAffix();

            Content.add(function ($context) {
                var elements = $('.math, .mathInline', $context).filter(':visible').toArray();

                // init sortable lists in context
                $('.sortable', $context).SortableList();
                // init timeago fields in context
                $('.timeago', $context).TimeAgo();
                // init dialogues in context
                $('.dialog', $context).SerloModals();
                // init datepicker
                $('.datepicker', $context).datepicker({
                    format: 'yyyy-mm-dd'
                });
                // init datepicker for dateranges
                $('.input-daterange', $context).datepicker({
                    format: 'yyyy-mm-dd'
                });
                // init spoilers
                $('.spoiler').Spoiler();
                // init injections
                $('.injection').Injections();
                // init edit controls
                $('[data-toggle]').ToggleAction();
                // init triggers
                $('[data-trigger]').TriggerAction();
                // forum select
                $('form[name="discussion"]', $context).ForumSelect();

                // NOTE: deactivated for now
                // init AjaxOverlay for /ref links
                // $('a[href^="/ref"]', $context).addClass('ajax-content');

                // init Mathjax
                if (elements.length) {
                    MathJax.Hub.Queue(["Typeset", MathJax.Hub, elements]);
                }
                $context.MathjaxTrigger();
            });

            // Tooltips opt in
            $('[data-toggle="tooltip"]').tooltip({
                container: 'body'
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

            // Initialize the subject nav
            (function () {
                var opened = false,
                    $dropdown = $('#subject-nav .subject-dropdown');

                $dropdown.click(function (e) {
                    // stop bubbling
                    // so outside click
                    // wont close it
                    e.stopPropagation();
                });

                function closeDropdown() {
                    opened = false;
                    $dropdown.removeClass('open');
                    $context.unbind('click', closeDropdown);
                }

                $('.subject-dropdown-toggle', $dropdown).click(function (e) {
                    e.preventDefault();
                    opened = !opened;
                    $dropdown.toggleClass('open', opened);

                    if (opened) {
                        $context.click(closeDropdown);
                    }

                    return;
                });
            }());

            SideElement.init();
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
            skipStartupTypeset: 'true',
            tex2jax: {
                inlineMath: [
                    ["%%", "%%"]
                ]
            },
            "HTML-CSS": {
                scale: 100,
                linebreaks: {
                    automatic: true
                }
            },
            SVG: {
                linebreaks: {
                    automatic: true
                }
            }
        });
    }

    $(function () {
        App.initialize($('body'));
        Supporter.check();
    });
});