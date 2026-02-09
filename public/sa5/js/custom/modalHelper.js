var ModalHelper = {

    closeModal: function($form) {
        $form.closest('.modal').modal('hide');
    },

    closeModalByFormId: function(form_id) {
        $("#" + form_id).closest('.modal').modal('hide');
    },

    resetForm: function(formSelector) {
        $(formSelector)[0].reset();
        $(formSelector).find('.is-invalid, .is-valid').removeClass('is-invalid is-valid');
        $(formSelector).find('.form-control').removeClass('is-invalid is-valid');
    },

    fadeOutMessage: function(containerId, timeout) {
        setTimeout(function() {
            $('#' + containerId + ' .alert').fadeOut('slow', function() {
                $(this).remove();
            });
        }, timeout || 3000);
    },

    updateFields: function(data) {
        if (typeof data.updated_fields !== 'undefined') {
            data.updated_fields.forEach(function(update_field) {
                if (typeof update_field.field !== 'undefined') {
                    $('#' + update_field.field).html(update_field.value);
                }
            });
        }
    },

    redrawDataTables: function(data) {
        if (typeof data.datatable_id !== 'undefined') {
            $('#' + data.datatable_id).DataTable().draw(false);
        }
        if (typeof data.datatable_id_2 !== 'undefined') {
            $('#' + data.datatable_id_2).DataTable().draw(false);
        }
    },

    reloadPageIfNeeded: function(data) {
        if (data.reload_internal_page == 1) {
            location.reload();
        }
    },

    redirectIfNeeded: function(data, delay) {
        if (data.redirect_url) {
            setTimeout(function() {
                window.location = data.redirect_url;
            }, delay || 500);
        }
    },

    initSelect2: function(modalSelector) {
        $(modalSelector).find('select').select2({
            dropdownParent: $(modalSelector)
        });
    },

    disableSubmitButton: function($button) {
        var originalText = $button.text();
        $button.attr("disabled", true)
            .html(l['a-please-wait']);
        return originalText;
    },

    enableSubmitButton: function($button, originalText) {
        $button.attr("disabled", false)
            .html(originalText);
    },

    showMessage: function(data, containerId, defaultMessage) {
        var message = (data.message !== undefined && data.message !== null && data.message !== '')
            ? data.message
            : (defaultMessage || l['a-information-updated']);

        $('#' + containerId).html('<div class="alert alert-success">' + message + '</div>');
    },

    showError: function(data, containerId, defaultMessage) {
        var message = (data.message !== undefined && data.message !== null && data.message !== '')
            ? data.message
            : (defaultMessage || l['a-required-fields']);

        $('#' + containerId).html('<div class="alert alert-danger">' + message + '</div>');
    },

    showValidationError: function(data, containerId, defaultMessage) {
        var message = '';

        if (typeof data === 'string') {
            message = data;
        }
        else if (Array.isArray(data)) {
            message = data.join('<br>');
        }
        else if (data && Array.isArray(data.errors)) {
            message = data.errors.join('<br>');
        }
        else if (data && typeof data.message === 'string') {
            message = data.message;
        }

        if (!message || message.trim() === '') {
            message = defaultMessage || l['a-required-fields'];
        }

        $('#' + containerId).html(
            '<div class="alert alert-warning">' +
            '<i class="fal fa-exclamation-triangle me-1"></i> ' +
            message +
            '</div>'
        );
    },

    getSubmitButton: function($form) {
        return $form.find("button[type=submit]");
    },

    showLoader: function(containerId) {
        $('#' + containerId).html('<div class="text-center py-2"><i class="fal fa-spinner fa-spin fa-2x"></i></div>');
    },

    validateForm: function(formId) {
        var isFormValid = true;
        var messages = [];

        // Required fields
        $("#" + formId + " .required").each(function() {
            if ($.trim($(this).val()).length == 0) {
                $(this).addClass("form-error");
                isFormValid = false;

                var label = $(this).attr('data-label');

                messages.push(l['a-required-fields'] + ": " + label);
            } else {
                $(this).removeClass("form-error");
            }
        });

        // Required select2
        $("#" + formId + " .required-select2").each(function() {
            if ($.trim($(this).val()).length == 0) {
                $(this).parent().find(".select2-container").addClass("form-error");
                isFormValid = false;

                var label = $(this).attr('data-label');

                messages.push(l['a-required-fields'] + ": " + label);
            } else {
                $(this).parent().find(".select2-container").removeClass("form-error");
            }
        });

        // Required parent
        $("#" + formId + " .required-parent").each(function() {
            if ($.trim($(this).val()).length == 0) {
                $(this).parent().addClass("form-error");
                isFormValid = false;

                var label = $(this).attr('data-label');
                messages.push(l['a-required-fields'] + ": " + label);
            } else {
                $(this).parent().removeClass("form-error");
            }
        });

        // Email validation
        $("#" + formId + " .valid-email").each(function() {
            var value = $.trim($(this).val());
            if (value.length > 0 && !isValidEmailAddress(value)) {
                $(this).addClass("form-error");
                isFormValid = false;

                messages.push(l['a-invalid-email-address']);
            } else if (value.length > 0) {
                $(this).removeClass("form-error");
            }
        });

        // Email validation on parent
        $("#" + formId + " .valid-email-parent").each(function() {
            var value = $.trim($(this).val());
            if (value.length > 0 && !isValidEmailAddress(value)) {
                $(this).addClass("form-error");
                isFormValid = false;

                messages.push(l['a-invalid-email-address']);
            } else if (value.length > 0) {
                $(this).removeClass("form-error");
            }
        });

        return { valid: isFormValid, errors: messages };
    },

    updateFields: function(data) {
        if (typeof data.updated_fields !== 'undefined') {
            data.updated_fields.forEach(function(update_field) {
                if (typeof update_field.field !== 'undefined') {
                    $('#' + update_field.field).html(update_field.value);
                }
            });
        }
    },

    confirmDelete : function(options) {

        var dialog = bootbox.confirm({
            title: options.title || l['a-confirm-delete'],
            message: options.message || l['a-message-delete'],
            size: '',
            backdrop: true,
            buttons: {
                confirm: {
                    label: '<i class="fal fa-check"></i> ' + l['a-delete'],
                    className: 'btn-danger'
                },
                cancel: {
                    label: '<i class="fal fa-times"></i> ' + l['a-cancel'],
                    className: 'btn-default'
                }
            },
            callback: function(result) {
                if (result) {
                    $.ajax({
                        url: options.url,
                        type: 'POST',
                        dataType: 'json',
                        data: $.param({ item_id: options.itemId }),
                        success: function(data) {
                            if (data.status === 'success') {
                                $('.bootbox.modal').modal('hide');

                                if (options.datatableId && $.fn.DataTable.isDataTable('#' + options.datatableId)) {
                                    $('#' + options.datatableId).DataTable().draw(false);
                                }

                                if (options.reloadPage) {
                                    location.reload();
                                }

                                ModalHelper.updateFields(data);

                            } else if (data.status === 'error') {
                                bootbox.alert(data.message);
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error("AJAX Error: ", status, error);
                            bootbox.alert("Process Error");
                        }
                    });
                }
            }
        });

        // Corectare aria + focus
        dialog.on('shown.bs.modal', function () {
            $(this).removeAttr('aria-hidden');
            dialog.find('button.bootbox-accept, button.btn-danger').focus();
        });

        dialog.on('hidden.bs.modal', function () {
            document.activeElement?.blur(); // prevenire warning aria-hidden
        });
    },

    /**
     * Downloads a file from a URL using jQuery AJAX
     * @param {string} url - The URL to download from
     * @param {string} filename - The name to save the file as
     */
    downloadFile: function(url, filename) {
        $.ajax({
            url: url,
            method: 'GET',
            xhrFields: {
                responseType: 'blob'
            },
            success: function(data) {
                var a = document.createElement("a");
                var url = URL.createObjectURL(data);
                a.href = url;
                a.setAttribute("download", filename);
                document.body.appendChild(a);
                a.click();

                setTimeout(function() {
                    document.body.removeChild(a);
                    URL.revokeObjectURL(url);
                }, 100);
            },
            error: function(xhr, status, error) {
                console.error('Download failed:', error);
                ModalHelper.showError({}, 'some-container-id', 'Download failed');
            }
        });
    },

    deleteImagesHandler: function () {

        $(document).off("click", ".deleteimages");

        $(document).on("click", ".deleteimages", function (e) {
            e.preventDefault();

            const $btn = $(this);
            const url = $btn.data("url");
            const itemId = $btn.data("item_id");
            const selector = $btn.data("remove_selector") || "#item-" + itemId;

            if (!url || !itemId) return;

            $.ajax({
                url: url,
                type: "POST",
                dataType: "json",
                data: { image_id: itemId },
                success: function (data) {
                    if (data.status === "success" || data == 1) {
                        $(selector).remove();
                    } else {
                        alert(data.message || l["a-required-fields"]);
                    }
                },
                error: function () {
                    alert("Error deleting image");
                }
            });
        });
    },
};

function isValidEmailAddress(emailAddress) {
    var pattern = new RegExp(/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i);
    return pattern.test(emailAddress);
};
