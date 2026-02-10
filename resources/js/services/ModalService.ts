import type { AjaxResponse, ModalHelperInterface } from '@/types/global';

export class ModalService implements ModalHelperInterface {
    private lastModalTrigger: HTMLElement | null = null;

    /**
     * Initialize modal system - sets up all event handlers
     */
    public init(): void {
        this.setupModalLoadHandlers();
        this.setupFormSubmitHandler();
        this.setupModalCloseHandler();
    }

    /**
     * Setup click handlers for remote modal loading
     */
    private setupModalLoadHandlers(): void {
        const modalTypes = [
            { selector: '.btn-modal-remote', modalId: '#modal-remote' },
            { selector: '.btn-modal-remote-middle', modalId: '#modal-remote-middle' },
            { selector: '.btn-modal-remote-middle-current', modalId: '#modal-remote-middle-current' },
            { selector: '.btn-modal-remote-big', modalId: '#modal-remote-big' }
        ];

        modalTypes.forEach(({ selector, modalId }) => {
            $(document).on('click', selector, (e) => {
                this.handleModalLoad(e, modalId);
            });
        });
    }

    /**
     * Handle modal loading from remote URL
     */
    private handleModalLoad(e: JQuery.ClickEvent, modalId: string): void {
        this.lastModalTrigger = e.currentTarget;
        e.preventDefault();

        const remoteUrl = $(e.currentTarget).data('remote') as string;
        if (!remoteUrl) return;

        const $modal = $(modalId);
        $modal.modal('show');
        $modal.find('.modal-content').load(remoteUrl, () => {
            this.initSelect2InModal($modal, '.load-select2');
        });
    }

    /**
     * Initialize Select2 in modal
     */
    private initSelect2InModal($modal: JQuery, selector: string): void {
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

    /**
     * Setup AJAX form submission handler
     */
    private setupFormSubmitHandler(): void {
        $(document).on('submit', 'form[data-async-modal]', (event) => {
            this.handleFormSubmit(event);
        });
    }

    /**
     * Handle AJAX form submission
     */
    private async handleFormSubmit(event: JQuery.SubmitEvent): Promise<void> {
        event.preventDefault();

        const $form = $(event.currentTarget);
        const formId = $form.attr('id');
        if (!formId) {
            console.error('Form must have an id attribute');
            return;
        }

        const formData = new FormData(event.currentTarget as HTMLFormElement);
        const $submitBtn = this.getSubmitButton($form);

        // Validate form
        const result = this.validateForm(formId);
        if (!result.valid) {
            this.showError(
                { message: result.errors.join('<br>') },
                `msg_${formId}`,
                window.l['a-required-fields']
            );
            return;
        }

        const submitText = this.disableSubmitButton($submitBtn);
        this.showLoader(`msg_${formId}`);

        try {
            const response = await $.ajax({
                type: $form.attr('method') || 'POST',
                dataType: 'json',
                url: $form.attr('action') || '',
                data: formData,
                cache: false,
                processData: false,
                contentType: false
            });

            this.handleFormResponse(response as AjaxResponse, formId, $submitBtn, submitText);
        } catch (error) {
            this.handleFormError(error, formId, $submitBtn, submitText);
        }
    }

    /**
     * Handle successful form response
     */
    private handleFormResponse(
        data: AjaxResponse,
        formId: string,
        $submitBtn: JQuery,
        submitText: string
    ): void {
        if (data.status === 'success') {
            this.showMessage(data, `msg_${formId}`, window.l['a-information-updated']);
            this.fadeOutMessage(`msg_${formId}`, 800);
            this.redrawDataTables(data);
            this.updateFields(data);

            setTimeout(() => {
                this.closeModalByFormId(formId);
                this.reloadPageIfNeeded(data);
                this.redirectIfNeeded(data, 500);
            }, 1500);
        } else if (data.status === 'validation_error') {
            this.showValidationError(data, `msg_${formId}`, window.l['a-required-fields']);
            this.enableSubmitButton($submitBtn, submitText);
        } else if (data.status === 'error') {
            this.showError(data, `msg_${formId}`, window.l['a-required-fields']);
            this.enableSubmitButton($submitBtn, submitText);
        }
    }

    /**
     * Handle form submission error
     */
    private handleFormError(
        error: any,
        formId: string,
        $submitBtn: JQuery,
        submitText: string
    ): void {
        console.error('Form submission error:', error);
        this.showError(
            { message: 'A server error occurred. Please try again.' },
            `msg_${formId}`
        );
        this.enableSubmitButton($submitBtn, submitText);
    }

    /**
     * Setup modal close handler to restore focus
     */
    private setupModalCloseHandler(): void {
        $(document).on('hidden.bs.modal', '.modal', () => {
            if ($('.modal.show').length) {
                $('body').addClass('modal-open');
            }
            if (this.lastModalTrigger) {
                try {
                    this.lastModalTrigger.focus();
                } catch (e) {
                    // Focus failed, ignore
                }
                this.lastModalTrigger = null;
            }
        });
    }

    // ===== Public API Methods (from ModalHelper) =====

    public closeModal($form: JQuery): void {
        $form.closest('.modal').modal('hide');
    }

    public closeModalByFormId(formId: string): void {
        $(`#${formId}`).closest('.modal').modal('hide');
    }

    public resetForm(formSelector: string): void {
        const $form = $(formSelector);
        const formElement = $form[0] as HTMLFormElement | undefined;
        formElement?.reset();
        $form.find('.is-invalid, .is-valid').removeClass('is-invalid is-valid');
        $form.find('.form-control').removeClass('is-invalid is-valid');
    }

    public fadeOutMessage(containerId: string, timeout: number = 3000): void {
        setTimeout(() => {
            $(`#${containerId} .alert`).fadeOut('slow', function () {
                $(this).remove();
            });
        }, timeout);
    }

    public updateFields(data: { updated_fields?: Array<{ field: string; value: string }> }): void {
        if (data.updated_fields) {
            data.updated_fields.forEach((update_field) => {
                if (update_field.field) {
                    $(`#${update_field.field}`).html(update_field.value);
                }
            });
        }
    }

    public redrawDataTables(data: { datatable_id?: string; datatable_id_2?: string }): void {
        if (data.datatable_id) {
            $(`#${data.datatable_id}`).DataTable().draw(false);
        }
        if (data.datatable_id_2) {
            $(`#${data.datatable_id_2}`).DataTable().draw(false);
        }
    }

    public reloadPageIfNeeded(data: { reload_internal_page?: number }): void {
        if (data.reload_internal_page === 1) {
            location.reload();
        }
    }

    public redirectIfNeeded(data: { redirect_url?: string }, delay: number = 500): void {
        if (data.redirect_url) {
            setTimeout(() => {
                window.location.href = data.redirect_url!;
            }, delay);
        }
    }

    public disableSubmitButton($button: JQuery): string {
        const originalText = $button.text();
        $button.attr('disabled', 'disabled').html(window.l['a-please-wait']);
        return originalText;
    }

    public enableSubmitButton($button: JQuery, originalText: string): void {
        $button.removeAttr('disabled').html(originalText);
    }

    public showMessage(data: { message?: string }, containerId: string, defaultMessage?: string): void {
        const message = data.message && data.message.trim() !== ''
            ? data.message
            : (defaultMessage || window.l['a-information-updated']);

        $(`#${containerId}`).html(`<div class="alert alert-success">${message}</div>`);
    }

    public showError(data: { message?: string }, containerId: string, defaultMessage?: string): void {
        const message = data.message && data.message.trim() !== ''
            ? data.message
            : (defaultMessage || window.l['a-required-fields']);

        $(`#${containerId}`).html(`<div class="alert alert-danger">${message}</div>`);
    }

    public showValidationError(
        data: string | string[] | { errors?: string[]; message?: string },
        containerId: string,
        defaultMessage?: string
    ): void {
        let message = '';

        if (typeof data === 'string') {
            message = data;
        } else if (Array.isArray(data)) {
            message = data.join('<br>');
        } else if (data && Array.isArray(data.errors)) {
            message = data.errors.join('<br>');
        } else if (data && typeof data.message === 'string') {
            message = data.message;
        }

        if (!message || message.trim() === '') {
            message = defaultMessage || window.l['a-required-fields'];
        }

        $(`#${containerId}`).html(
            `<div class="alert alert-warning">
                <i class="fal fa-exclamation-triangle me-1"></i> ${message}
            </div>`
        );
    }

    public getSubmitButton($form: JQuery): JQuery {
        return $form.find('button[type=submit]');
    }

    public showLoader(containerId: string): void {
        $(`#${containerId}`).html(
            '<div class="text-center py-2"><i class="fal fa-spinner fa-spin fa-2x"></i></div>'
        );
    }

    public validateForm(formId: string): { valid: boolean; errors: string[] } {
        let isFormValid = true;
        const messages: string[] = [];
        const $form = $(`#${formId}`);

        // Required fields
        $form.find('.required').each(function () {
            const $field = $(this);
            if ($.trim($field.val() as string).length === 0) {
                $field.addClass('form-error');
                isFormValid = false;
                const label = $field.attr('data-label');
                messages.push(`${window.l['a-required-fields']}: ${label}`);
            } else {
                $field.removeClass('form-error');
            }
        });

        // Required select2
        $form.find('.required-select2').each(function () {
            const $field = $(this);
            if ($.trim($field.val() as string).length === 0) {
                $field.parent().find('.select2-container').addClass('form-error');
                isFormValid = false;
                const label = $field.attr('data-label');
                messages.push(`${window.l['a-required-fields']}: ${label}`);
            } else {
                $field.parent().find('.select2-container').removeClass('form-error');
            }
        });

        // Email validation
        $form.find('.valid-email').each(function () {
            const $field = $(this);
            const value = $.trim($field.val() as string);
            if (value.length > 0 && !isValidEmailAddress(value)) {
                $field.addClass('form-error');
                isFormValid = false;
                messages.push(window.l['a-invalid-email-address']);
            } else if (value.length > 0) {
                $field.removeClass('form-error');
            }
        });

        return { valid: isFormValid, errors: messages };
    }

    public confirmDelete(options: {
        title?: string;
        message?: string;
        url: string;
        itemId: string | number;
        datatableId?: string;
        reloadPage?: boolean;
    }): void {
        // @ts-ignore - bootbox is loaded globally
        const dialog = bootbox.confirm({
            title: options.title || window.l['a-confirm-delete'],
            message: options.message || window.l['a-message-delete'],
            size: '',
            backdrop: true,
            buttons: {
                confirm: {
                    label: `<i class="fal fa-check"></i> ${window.l['a-delete']}`,
                    className: 'btn-danger'
                },
                cancel: {
                    label: `<i class="fal fa-times"></i> ${window.l['a-cancel']}`,
                    className: 'btn-default'
                }
            },
            callback: (result: boolean) => {
                if (result) {
                    $.ajax({
                        url: options.url,
                        type: 'POST',
                        dataType: 'json',
                        data: $.param({ item_id: options.itemId }),
                        success: (data: AjaxResponse) => {
                            if (data.status === 'success') {
                                $('.bootbox.modal').modal('hide');

                                if (options.datatableId && $.fn.dataTable.isDataTable(`#${options.datatableId}`)) {
                                    $(`#${options.datatableId}`).DataTable().draw(false);
                                }

                                if (options.reloadPage) {
                                    location.reload();
                                }

                                this.updateFields(data);
                            } else if (data.status === 'error') {
                                // @ts-ignore
                                bootbox.alert(data.message);
                            }
                        },
                        error: (xhr, status, error) => {
                            console.error('AJAX Error:', status, error);
                            // @ts-ignore
                            bootbox.alert('Process Error');
                        }
                    });
                }
            }
        });

        dialog.on('shown.bs.modal', function (this: HTMLElement) {
            $(this).removeAttr('aria-hidden');
            dialog.find('button.bootbox-accept, button.btn-danger').focus();
        });

        dialog.on('hidden.bs.modal', function () {
            (document.activeElement as HTMLElement)?.blur();
        });
    }

    public downloadFile(url: string, filename: string): void {
        $.ajax({
            url: url,
            method: 'GET',
            xhrFields: {
                responseType: 'blob'
            },
            success: (data: Blob) => {
                const a = document.createElement('a');
                const blobUrl = URL.createObjectURL(data);
                a.href = blobUrl;
                a.setAttribute('download', filename);
                document.body.appendChild(a);
                a.click();

                setTimeout(() => {
                    document.body.removeChild(a);
                    URL.revokeObjectURL(blobUrl);
                }, 100);
            },
            error: (xhr, status, error) => {
                console.error('Download failed:', error);
                this.showError({}, 'some-container-id', 'Download failed');
            }
        });
    }

    public deleteImagesHandler(): void {
        $(document).off('click', '.deleteimages');

        $(document).on('click', '.deleteimages', (e) => {
            e.preventDefault();

            const $btn = $(e.currentTarget);
            const url = $btn.data('url') as string;
            const itemId = $btn.data('item_id');
            const selector = ($btn.data('remove_selector') as string) || `#item-${itemId}`;

            if (!url || !itemId) return;

            $.ajax({
                url: url,
                type: 'POST',
                dataType: 'json',
                data: { image_id: itemId },
                success: (data: AjaxResponse | number) => {
                    if ((typeof data === 'object' && data.status === 'success') || data === 1) {
                        $(selector).remove();
                    } else {
                        const msg = typeof data === 'object' && data.message
                            ? data.message
                            : window.l['a-required-fields'];
                        alert(msg);
                    }
                },
                error: () => {
                    alert('Error deleting image');
                }
            });
        });
    }
}

/**
 * Email validation helper
 */
function isValidEmailAddress(emailAddress: string): boolean {
    const pattern = /^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i;
    return pattern.test(emailAddress);
}

export default ModalService;
