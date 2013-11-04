/*global define*/
define(['jquery', 'router'], function ($, Router) {
    "use strict";
    var SerloModals,
        Modal,
        modalTemplate = '#modalTemplate';

    Modal = function ($modal, $trigger) {
        this.$trigger = $trigger;
        this.$el = $modal;

        this.type = $trigger.attr('data-type') || false;
        this.title = $trigger.attr('data-title') || false;
        this.content = $trigger.attr('data-content');
        this.href = $trigger.attr('href');

        this.render().show();
    };

    Modal.prototype.render = function () {
        var self = this,
            $btn = $('.btn-primary', self.$el);

        $('.modal-body', self.$el).text(self.content);
        $('body').append(self.$el);

        $btn.click(function () {
            Router.navigate(self.href);
        });

        if (self.type) {
            $btn.removeClass('btn-primary').addClass('btn-' + this.type);
        }

        if (self.title) {
            $('.modal-title', self.$el).text(self.title);
        }

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