/*global define, window*/
define(function () {
    "use strict";
    var Router;

    function navigate(url) {
        window.location.href = url;
    }

    function post(path, params, method) {
        var form,
            key,
            hiddenField;

        method = method || "post"; // Set method to post by default if not specified.

        // The rest of this code assumes you are not using a library.
        // It can be made less wordy if you use one.
        form = document.createElement("form");
        form.setAttribute("method", method);
        form.setAttribute("action", path);

        for (key in params) {
            if (params.hasOwnProperty(key)) {
                hiddenField = document.createElement("input");
                hiddenField.setAttribute("type", "hidden");
                hiddenField.setAttribute("name", key);
                hiddenField.setAttribute("value", params[key]);
                form.appendChild(hiddenField);
            }
        }

        document.body.appendChild(form);
        form.submit();
    }

    function reload() {
        if (typeof window.location.reload === "function") {
            window.location.reload();
            return;
        }
        var href = window.location.href;
        window.location.href = href;
    }

    Router = {
        navigate: function (url) {
            navigate(url);
        },
        post: function (url, params, method) {
            post(url, params, method);
        },
        reload: function () {
            reload();
        }
    };

    return Router;
});