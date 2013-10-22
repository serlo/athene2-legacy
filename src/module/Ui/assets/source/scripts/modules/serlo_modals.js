/*global define*/
define(['jquery', 'router'], function ($, Router) {
    "use strict";
    var SerloModals,
        Modal,
        modalTemplate = '#modalTemplate';

    Modal = function ($modal, $trigger) {
        this.$trigger = $trigger;
        this.$el = $modal;

        this.type = $trigger.attr('data-type');
        this.content = $trigger.attr('data-content');
        this.href = $trigger.attr('href');

        this.render().show();
    };

    Modal.prototype.render = function () {
        var self = this;
        $('.modal-body', self.$el).text(self.content);
        $('body').append(self.$el);

        $('.btn-primary', self.$el).click(function () {
            Router.navigate(self.href);
        });
        return self;
    };

    Modal.prototype.show = function () {
        this.$el.modal('show');
    };

    SerloModals = function () {
        var $modalTemplate = $(modalTemplate).clone();

        return $(this).each(function () {
            var $self = $(this);

            $self.click(function (e) {
                e.preventDefault();
                new Modal($modalTemplate, $self);
                return;
            });
        });
    };

    $.fn.SerloModals = SerloModals;
});