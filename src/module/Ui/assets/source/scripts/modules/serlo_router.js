/*global define, window*/
define(function () {
    "use strict";
    var Router;

    function navigate(url) {
        window.location.href = url;
    }

    Router = {
        navigate: function (url) {
            navigate(url);
        }
    };

    return Router;
});