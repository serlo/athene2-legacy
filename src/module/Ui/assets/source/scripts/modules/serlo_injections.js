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

/*global define, require, window, web*/
define(['jquery', 'common', 'translator'], function ($, Common, t) {
    "use strict";
    var Injections,
        cache = {},
        ggbApplets = [],
        geogebraScriptSource = 'http://www.geogebra.org/web/4.4/web/web.nocache.js',
        $geogebraTemplate = $('<article class="geogebraweb" data-param-width="500" data-param-height="500" data-param-usebrowserforjs="true" data-param-enableRightClick="true"></article>');

    // terrible geogebra oninit handler..
    window.ggbOnInit = function (id) {
        if (ggbApplets[id]) {
            ggbApplets[id]();
        }
    };

    Injections = function () {
        return $(this).each(function () {
            var $that = $(this),
                $a = $('> a', $that),
                title = $a.text(),
                href = $a.attr('href');

            if (!href) {
                return true;
            }

            function initGeogebraApplet(xml) {
                var ggbAppletID = ggbApplets.length,
                    $clone = $geogebraTemplate.clone();

                $clone.attr("data-param-id", ggbAppletID);
                $clone.attr("data-param-ggbbase64", btoa(xml));

                ggbApplets[ggbAppletID] = function () {
                    console.log('hier', this, arguments, xml);
                };

                $that.html($clone);

                // web();
            }

            function notSupportedYet($context) {
                Common.log('Illegal injection found: ' + href);
                $context.html('<div class="alert alert-info">' + t('Illegal injection found') + '</div>');
            }

            function handleResponse(data, contentType) {
                cache[href] = {
                    data: data,
                    contentType: contentType
                };

                // check if it is geogebra xml
                if (data.documentElement && data.documentElement.nodeName === 'geogebra') {
                    if (typeof web === "undefined") {
                        require([geogebraScriptSource], function () {
                            console.log('geogebra loaded');

                            initGeogebraApplet(data.documentElement.outerHTML);
                        });
                    } else {
                        initGeogebraApplet(data.documentElement.outerHTML);
                    }
                } else if (contentType === 'image/jpeg' || contentType === 'image/png') {
                    $that.html('<img src="' + href + '" title="' + title + '" />');
                } else {
                    try {
                        data = JSON.parse(data);
                        if (data.response) {
                            $that.html(data.response);
                        } else {
                            notSupportedYet($that);
                        }
                    } catch (e) {
                        notSupportedYet($that);
                    }
                }
                // /// if response contains html,
                // /// use that.
                // if (data.response) {
                //     $that.html(data.response);
                // } else {
                //     /// if we have a geogebra injection
                //     if (data.type === 'geogebra') {
                //         /// check if there is only one file
                //         if (data.files && data.files.length > 1) {
                //             /// wich means the applet needs to
                //             /// be loaded.
                //             if (typeof "web" === "undefined") {
                //                 require([geogebraScriptSource], function () {
                //                     initGeogebraApplet(xml);
                //                 });
                //             }
                //             initGeogebraApplet(xml);
                //         } else {
                //             /// or simply insert the second
                //             /// file as an image
                //             $that.html($('<img>').attr({
                //                 title: title,
                //                 src: data.files[1].location
                //             }));
                //         }
                //     } else if (data.files) {
                //         $that.html($('<a>').attr({
                //             href: data.files[0].location
                //         }));
                //     } else {
                //         Common.genericError('Unknown injection: ' + href);
                //     }
                // }
            }

            if (cache[href]) {
                handleResponse(cache[href].data, cache[href].contentType);
            }

            $.ajax(href)
                .success(function () {
                    handleResponse(arguments[0], arguments[2].getResponseHeader('Content-Type'));
                })
                .error(function () {
                    $that.html(t('Could not load injection'));
                });
        });
    };

    $.fn.Injections = Injections;
});