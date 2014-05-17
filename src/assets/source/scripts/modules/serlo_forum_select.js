/*global define*/
define(['jquery', 'translator'], function ($, t) {
    "use strict";
    var ForumSelect;

    /* jshint validthis:true  */
    function selectForum(e) {
        e.preventDefault();
        var $that = $(this),
            url = $that.data('select-forum-href');
        $.get(url, function (data) {
            var $modal = $('<div class="modal fade"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button><h4 class="modal-title">' + t('You\'re almost done!') + '</h4></div><div class="modal-body"></div><div class="modal-footer"><button type="button" class="btn btn-default" data-dismiss="modal">' + t('Abort') + '</button></div></div></div></div>');
            $('body').append($modal);
            $('.modal-body', $modal).html(data);
            $modal.modal('show');

            $('button.select').click(function () {
                $that.unbind('submit', selectForum);
                var $this = $(this),
                    href = $this.data('action');
                $this.button('loading');
                $.ajax({
                    url: href,
                    type: 'POST',
                    data: $that.serialize()
                }).success(function () {
                    location.reload();
                });
            });
        });
        return false;
    }

    ForumSelect = function () {
        return $(this).each(function () {
            // Edit mode toggle
            if ($(this).data('select-forum-href')) {
                $(this).submit(selectForum);
            }
        });
    };

    $.fn.ForumSelect = ForumSelect;
});