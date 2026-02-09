'use strict';

let lastModalTrigger = null;

function initSelect2InModal($modal, selector) {
    const $items = $modal.find(selector);
    if (!$items.length || !$.fn.select2) return;

    $items.each(function () {
        const $el = $(this);
        if ($el.hasClass('select2-hidden-accessible')) return;

        $el.select2({
            dropdownParent: $modal,
            minimumResultsForSearch: 1
        });
    });
}

export function initRemoteModals() {
    $(document).on('click', '.btn-modal-remote', function (e) {
        lastModalTrigger = this;
        e.preventDefault();
        const remoteUrl = $(this).data('remote');
        const $modal = $('#modal-remote');
        if (!remoteUrl) return;

        $modal.modal('show');
        $modal.find('.modal-content').load(remoteUrl, function () {
            initSelect2InModal($modal, '.load-select2');
        });
    });

    $(document).on('click', '.btn-modal-remote-middle', function (e) {
        lastModalTrigger = this;
        e.preventDefault();
        const remoteUrl = $(this).data('remote');
        const $modal = $('#modal-remote-middle');
        if (!remoteUrl) return;

        $modal.modal('show');
        $modal.find('.modal-content').load(remoteUrl, function () {
            initSelect2InModal($modal, '.load-select2');
        });
    });

    $(document).on('click', '.btn-modal-remote-middle-current', function (e) {
        lastModalTrigger = this;
        e.preventDefault();
        const remoteUrl = $(this).data('remote');
        const $modal = $('#modal-remote-middle-current');
        if (!remoteUrl) return;

        $modal.modal('show');
        $modal.find('.modal-content').load(remoteUrl, function () {
            initSelect2InModal($modal, '.load-select2');
        });
    });

    $(document).on('click', '.btn-modal-remote-big', function (e) {
        lastModalTrigger = this;
        e.preventDefault();
        const remoteUrl = $(this).data('remote');
        const $modal = $('#modal-remote-big');
        if (!remoteUrl) return;

        $modal.modal('show');
        $modal.find('.modal-content').load(remoteUrl, function () {
            initSelect2InModal($modal, '.load-select2');
        });
    });

    $(document).on('hidden.bs.modal', '.modal', function () {
        if ($('.modal.show').length) $('body').addClass('modal-open');
        if (lastModalTrigger) {
            try { lastModalTrigger.focus(); } catch (e) {}
            lastModalTrigger = null;
        }
    });
}
