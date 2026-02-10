/**
 * @deprecated This file is deprecated and will be removed in a future version.
 * All functionality has been migrated to TypeScript services in resources/js/services/
 * - AppUtils.dataTableDom → resources/js/services/DataTableService.ts
 * - applyTableFilters → resources/js/services/DataTableService.ts
 * - Modal form handlers → resources/js/services/ModalService.ts
 * Please use the new TypeScript services via window.AppUtils and window.applyTableFilters (which now point to the TypeScript versions).
 */
console.warn('[DEPRECATED] Legacy app.js is loaded. Functionality migrated to TypeScript services.');

window.AppUtils = window.AppUtils || {};

AppUtils.dataTableDom = function() {
    return "<'row mb-3'<'col-sm-12 col-md-6 d-flex align-items-center justify-content-start'f><'col-sm-12 col-md-6 d-flex align-items-center justify-content-end'l>>" +
        "<'row'<'col-sm-12'tr>>" +
        "<'row mt-3'<'col-sm-12 col-md-7 d-flex align-items-center justify-content-start'i><'col-sm-12 col-md-5 d-flex align-items-center justify-content-end'p>>";
};

function applyTableFilters(tableApi, customSelectOptions = {}) {
    tableApi.columns().every(function() {
        var column = this;
        var index = column.index();
        var header = $(column.header());
        var columnTitle = header.text().trim();

        if (header.hasClass('select_search')) {
            var select = $('<select class="form-select form-select-sm"><option value="">Toate</option></select>')
                .appendTo(header.empty())
                .on('change', function() {
                    column.search($(this).val() ? 'exact:' + $(this).val() : '').draw();
                });

            if (customSelectOptions[index]) {
                $.each(customSelectOptions[index], function(val, label) {
                    select.append('<option value="' + val + '">' + label + '</option>');
                });
            } else {
                column.data().unique().sort().each(function(d) {
                    if (d) {
                        select.append('<option value="' + d + '">' + d + '</option>');
                    }
                });
            }
        } else if (!header.hasClass('no-search')) {
            var input = $('<input type="text" class="form-control form-control-sm" placeholder="Cauta ' + columnTitle + '..." />')
                .appendTo(header.empty())
                .on('keyup change clear', function() {
                    column.search($(this).val()).draw();
                });
        }
    });
}

function getSelectedValues($modal) {
    const selectedValues = [];
    $modal.find('.attribute_select').each(function () {
        const value = $(this).val();
        if (value) {
            selectedValues.push(value);
        }
    });
    return selectedValues.join(',');
}

function initializeSelect2InModal($modal, selector, options = {}) {
    $modal.find(selector).each(function () {
        const $select = $(this);
        if (!$select.hasClass("select2-hidden-accessible")) {
            $select.select2($.extend({
                dropdownParent: $modal,
                minimumResultsForSearch: 1
            }, options));
        }
    });
}

function initializeAttributeSelectSimple($context) {
    const $attributeSelects = $context.find('.attribute_select');

    if ($attributeSelects.length > 0) {
        $attributeSelects.each(function() {
            const $select = $(this);
            
            // Distruge Select2 existent (pentru selecturi din EDIT care au deja Select2 static)
            if ($select.hasClass("select2-hidden-accessible")) {
                $select.select2('destroy');
            }
            
            $select.select2({
                placeholder: l['select-attribute'],
                dropdownParent: $context,
                minimumResultsForSearch: 1,
                ajax: {
                    url: BASE_URL_ADMIN + '/products/getattributesjson',
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            q: params.term,
                            selectedvalues: getSelectedValues($context)
                        };
                    },
                    processResults: function (data) {
                        return {
                            results: (data || []).map(attribute => ({
                                id: attribute.id,
                                text: attribute.text,
                                type: attribute.type
                            }))
                        };
                    },
                    cache: true
                }
            });
            
            // Handler pentru actualizare variante când se schimbă atributul (EDIT mode)
            $select.on('select2:select', function (e) {
                const attributeType = e.params.data.type;
                const $row = $(this).closest('.row');
                const $variantSelect = $row.find('.variant_select');
                const attributeId = $(this).val();

                if ($variantSelect.hasClass("select2-hidden-accessible")) {
                    $variantSelect.select2('destroy');
                }

                $variantSelect.empty();

                setTimeout(() => {
                    $variantSelect.prop('multiple', attributeType === '3');

                    $.ajax({
                        url: BASE_URL_ADMIN + '/products/getvariants',
                        type: 'POST',
                        data: { attribute_id: attributeId },
                        success: function(data) {
                            if (Array.isArray(data)) {
                                data.forEach(variant => {
                                    const option = new Option(variant.text, variant.id, false, false);
                                    $variantSelect.append(option);
                                });
                            }

                            $variantSelect.select2({
                                placeholder: l['a-select-variant'],
                                dropdownParent: $context,
                                escapeMarkup: markup => markup
                            });
                        },
                        error: function(xhr, status, error) {
                            console.error('Error loading variants:', error);
                            $variantSelect.append('<option value="">Eroare la încărcare</option>');
                            $variantSelect.select2({
                                placeholder: 'Eroare',
                                dropdownParent: $context
                            });
                        }
                    });
                }, 100);
            });
        });
    }
}


function initializeAttributeSelect($modal) {
    // Găsește doar selecturi care NU au Select2 deja
    const $newSelects = $modal.find('.attribute_select').not('.select2-hidden-accessible');
    
    if ($newSelects.length === 0) return;
    
    $newSelects.each(function() {
        const $select = $(this);
        
        $select.select2({
            placeholder: l['a-select-attribute'],
            dropdownParent: $modal,
            minimumResultsForSearch: 1,
            ajax: {
                url: BASE_URL_ADMIN + '/products/getattributesjson',
                dataType: 'json',
                delay: 250,
                data: params => ({
                    q: params.term,
                    selectedvalues: getSelectedValues($modal)
                }),
                processResults: data => ({
                    results: (data || []).map(attribute => ({
                        id: attribute.id,
                        text: attribute.text,
                        type: attribute.type
                    }))
                }),
                cache: true
            }
        });
        
        // Event handler DOAR pe selectul nou
        $select.on('select2:select', function (e) {
            const attributeType = e.params.data.type;
            const $row = $(this).closest('.row');
            const $variantSelect = $row.find('.variant_select');
            const attributeId = $(this).val();

            if ($variantSelect.hasClass("select2-hidden-accessible")) {
                $variantSelect.select2('destroy');
            }

            $variantSelect.empty();

            // Previne race condition
            setTimeout(() => {
                $variantSelect.prop('multiple', attributeType === '3');

                $.ajax({
                    url: BASE_URL_ADMIN + '/products/getvariants',
                    type: 'POST',
                    data: { attribute_id: attributeId },
                    success: function(data) {
                        if (Array.isArray(data)) {
                            data.forEach(variant => {
                                const option = new Option(variant.text, variant.id, false, false);
                                $variantSelect.append(option);
                            });
                        }

                        $variantSelect.select2({
                            placeholder: l['a-select-variant'],
                            dropdownParent: $modal,
                            escapeMarkup: markup => markup
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error('Error loading variants:', error);
                        $variantSelect.append('<option value="">Eroare la încărcare</option>');
                        $variantSelect.select2({
                            placeholder: 'Eroare',
                            dropdownParent: $modal
                        });
                    }
                });
            }, 100); // Mărește delay-ul pentru stabilitate
        });
    });
}

// Fix z-index pentru dropdown-uri Select2 în tabs
$(document).on('select2:open', function(e) {
    const $dropdown = $('.select2-dropdown');
    const $modal = $(e.target).closest('.modal');
    
    if ($modal.length) {
        const modalZIndex = parseInt($modal.css('z-index'));
        $dropdown.css('z-index', modalZIndex + 10);
    }
});


function initializeVariantSelect($modal) {
    if ($('.variant_select').length > 0) {
        $('.variant_select').select2({
            placeholder: l['a-select-variant'],
            dropdownParent: $($modal),
            ajax: {
                url: BASE_URL_ADMIN + '/products/getvariantsjson',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        q: params.term,
                        attribute_id: $(this).data('attribute-id')
                    };
                },
                processResults: function (data) {
                    return {
                        results: data
                    };
                },
                cache: true
            }
        });
    }
}

// Function loadVariants() removed - logic moved to initializeAttributeSelect() select2:select handler
// This eliminates race conditions and duplicate AJAX calls


function initializeCkeditor($container) {
    $container.find('.ckeditor').each(function(index, item) {
        var ckeditor_id = $(item).attr('id');
        if (ckeditor_id && typeof CKEDITOR !== 'undefined') {
            CKEDITOR.replace(ckeditor_id);
        }
    });
}

function initializeFlatpickrSingle(container = document) {
    const elements = container.querySelectorAll('.datetimepicker');
    elements.forEach(el => {
        flatpickr(el, {
            enableTime: true,
            dateFormat: 'Y-m-d H:i',
            time_24hr: true,
            allowInput: true,
            clickOpens: true,
            minDate: null,
            maxDate: null,
            defaultDate: el.value || null
        });
    });
}

function bindFlatpickrRange(container = document) {
    const rangeFields = container.querySelectorAll('.date-range-picker');

    rangeFields.forEach(el => {
        if (el._flatpickr) {
            el._flatpickr.destroy();
        }

        const now = new Date();
        const startOfToday = new Date(now.getFullYear(), now.getMonth(), now.getDate(), 0, 0);
        const endOfToday = new Date(now.getFullYear(), now.getMonth(), now.getDate(), 23, 59);

        const defaultDates = (el.value && el.value.includes(' to '))
            ? el.value.split(' to ')
            : [startOfToday, endOfToday];

        flatpickr(el, {
            mode: "range",
            enableTime: true,
            dateFormat: "Y-m-d H:i",
            time_24hr: true,
            allowInput: true,
            defaultDate: defaultDates,
            minDate: startOfToday,
            locale: {
                rangeSeparator: " to "
            }
        });
    });
}




$(document).ready(function() {

    var controls = { //datepicker
        leftArrow: '<i class="fal fa-angle-left" style="font-size: 1.25rem"></i>',
        rightArrow: '<i class="fal fa-angle-right" style="font-size: 1.25rem"></i>'
    }

    $(document).on( "click", '.inputs-modal-clean',function(e) {
        var form_id = $(this).data('form_id');
        $("#"+form_id).trigger( "reset" );
        $("#"+form_id+" .required").removeClass('border-red');
        $("#msg_"+form_id).html(''); 
    });

    $(document).on( "click", '.btn-modal-remote',function(e) {
        e.preventDefault();
        const $modal = $('.modal-remote');
        $modal.modal('show');
        var remote =  $(this).data('remote');
        var title =  $(this).data('title');
        var reload_internal_page =  $(this).data('reload_internal_page');

        $modal.find('.modal-content').load(remote, function () {
            var $modalContent = $('.modal-remote .modal-content');
            if(typeof reload_internal_page != 'undefined') $("#reload_internal_page").val('1');

            initializeSelect2InModal($modal, '.load-select2');

            initializeCkeditor($modal);

            if ($('.startdate').length>0) {
                $(".startdate").datepicker({
                    changeMonth: true,
                    changeYear: true,
                    format:'yyyy-mm-dd',
                    autoclose: true,
                }).on('changeDate', function (selected) {
                    var minDate = new Date(selected.date.valueOf());
                    $('.enddate').datepicker('setStartDate', minDate);
                });
            }

            if ($('.enddate').length>0) {
                $(".enddate").datepicker({
                    changeMonth: true,
                    changeYear: true,
                    format:'yyyy-mm-dd',
                    autoclose: true,
                })
                    .on('changeDate', function (selected) {
                        var maxDate = new Date(selected.date.valueOf());
                        $('.startdate').datepicker('setEndDate', maxDate);
                    });
            }

            $('.sortable-list').sortable({
                axis: 'y',
                stop: function (event, ui) {
                    var $this = $(this);
                    var data = $this.sortable('serialize');
                    var datatable_id = $this.data('datatable_id');
                    var url = BASE_URL + $this.data('url');
                    //var categoryId = $this.data('category_id');

                    $.ajax({
                        type: 'POST',
                        url: url,
                        data: data,
                        dataType: 'json',
                        success: function (response) {
                            if (response.status === 'success') {

                                $this.find('li').each(function(index) {
                                    $(this).find('.badge').text(index + 1); // index pornește de la 0
                                });
                                
                                if (datatable_id && $.fn.DataTable.isDataTable('#' + datatable_id)) {
                                    $('#' + datatable_id).DataTable().order([]).draw(false);
                                }
                            } else if (response.status === 'error') {
                                alert(response.message);
                            }
                        },
                        error: function (xhr, status, error) {
                            console.error('AJAX Error:', status, error);
                            alert('A server error occurred.');
                        }
                    });
                }
            });

            if (typeof ModalHelper.deleteImagesHandler === 'function') {
                ModalHelper.deleteImagesHandler();
            }

            initializeCategoriesSelect($modal);
  
        });
    });

    $(document).on( "click", '.btn-modal-remote-middle',function(e) {
        e.preventDefault();

        const $modal = $('.modal-remote-middle');
        const $this = $(this);
        const remoteUrl = $this.data('remote');
        const modalTitle = $this.data('title');
        const $modalContent = $modal.find('.modal-content');

        $modal.modal('show');

        $modalContent.load(remoteUrl, function() {

            if(typeof reload_internal_page != 'undefined') $("#reload_internal_page").val('1');

            initializeSelect2InModal($modal, '.load-select2');

            initializeCkeditor($modal);

            bindFlatpickrRange($modal[0]);

            if (typeof ModalHelper.deleteImagesHandler === 'function') {
                ModalHelper.deleteImagesHandler();
            }

            initializeAttributeSelectSimple($modal);

        });

    });


    // Butonul care deschide modalul 2
    $(document).on("click", ".btn-modal-remote-middle-current", function (e) {
        e.preventDefault();

        const $modal = $("#modal-remote-middle-current"); // use ID, not class
        const remote = $(this).data("remote");

        $modal.modal("show");

        $modal.find(".modal-content").load(remote, function () {
            if (typeof reload_internal_page != "undefined") {
                $("#reload_internal_page").val("1");
            }

            initializeSelect2InModal($modal, ".load-select2");
            initializeCkeditor($modal);
            initializeCategoriesSelect($modal);
        });
    });

    /* Mark the new backdrop as 'current' (so it gets the higher z-index) */
    $(document).on('shown.bs.modal', '#modal-remote-middle-current', function () {
        $('.modal-backdrop').last().addClass('modal-backdrop-current');
    });

    /* Keep body scroll locked if another modal is still open */
    $(document).on("hidden.bs.modal", ".modal", function () {
        if ($(".modal.show").length) {
            $("body").addClass("modal-open");
        }
    });




    function initializeDatepicker($container) {
        if ($container.find('.editstartdate').length) {
            $container.find('.editstartdate').datepicker({
                todayHighlight: true,
                format: 'yyyy-mm-dd',
                orientation: 'top left',
                templates: controls
            });
        }
    }

    function initializeTagsInput() {
        if ($('.tagsinput').length) {
            $('.tagsinput').tagsinput('items');
        }
    }

    function initializeBrandsSelect($modal) {
        if ($('.brands_select').length>0) {
            $('.brands_select').select2({
                placeholder: l['select-brands'],
                dropdownParent: $($modal),
                minimumResultsForSearch: 1,
                ajax: {
                    url: BASE_URL_ADMIN + '/products/brands/getbrandsjson',
                    dataType: 'json',
                    delay: 250,
                    processResults: function (data) {
                        return {
                            results: data
                        };
                    },
                    cache: true
                }
            });
        }
    }

    function initializeCategoriesSelect($modal) {
        if ($('.categories_select').length > 0) {
            $('.categories_select').select2({
                placeholder: l['select-brands'],
                dropdownParent: $($modal),
                minimumResultsForSearch: 1,
                ajax: {
                    url: BASE_URL_ADMIN + '/products/getcategoriesjson',
                    dataType: 'json',
                    delay: 250,
                    processResults: function (data) {
                        return {
                            results: data
                        };
                    },
                    cache: true
                }
            });
        }
    }

    function initializeCategoriesMultipleSelect($modal) {
        if ($('.categories_multiple_select').length > 0) {
            $('.categories_multiple_select').select2({
                placeholder: l['select-brands'],
                dropdownParent: $($modal),
                minimumInputLength: 0,
                ajax: {
                    url: BASE_URL_ADMIN + '/products/getcategoriesmultiplejson',
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        const selectedIds = $('.categories_multiple_select').val() || [];
                        const exclude = selectedIds.length > 0 ? selectedIds : ['0'];
                        const term = (typeof params.term === 'undefined') ? '' : params.term;

                        //console.log('select2 -> term:', term);
                        //console.log('select2 -> selected:', selectedIds);

                        return {
                            term: params.term || '',
                            exclude: exclude
                        };
                    },
                    processResults: function (data) {
                        return { results: data };
                    },
                    cache: true
                }
            });
        }
    }

    function initializeFeaturedIdsSelect($modal) {
        const $select = $modal.find('.featured_ids_select');

        if ($select.length > 0) {
            $select.select2({
                placeholder: l['a-select-featured'] || 'Selectează recomandări',
                dropdownParent: $modal,
                minimumInputLength: 0,
                ajax: {
                    url: BASE_URL_ADMIN + '/products/getfeaturedjson',
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        const selectedIds = $select.val() || [];
                        const exclude = selectedIds.length > 0 ? selectedIds : ['0'];

                        return {
                            term: params.term || '',
                            exclude: exclude
                        };
                    },
                    processResults: function (data) {
                        return { results: data };
                    },
                    cache: true
                }
            });
        }
    }

    function initializeRelatedProductsSelect($modal) {
        if ($('.related_products_select').length > 0) {
            $('.related_products_select').select2({
                placeholder: l['a-related-products'],
                dropdownParent: $($modal),
                ajax: {
                    url: BASE_URL_ADMIN + '/products/get_products_ajax',
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            term: params.term
                        };
                    },
                    processResults: function (data) {
                        return {
                            results: data.results
                        };
                    },
                    cache: true
                }
            });
        }
    }


    $(document).on('click', '.btn-modal-remote-big', function(e) {
        e.preventDefault();

        const $modal = $('.modal-remote-big');
        const $this = $(this);
        const remoteUrl = $this.data('remote');
        const modalTitle = $this.data('title');
        const $modalContent = $modal.find('.modal-content');

        $modal.modal('show');

        $modalContent.load(remoteUrl, function() {

            if (typeof reload_internal_page !== 'undefined') {
                $('#reload_internal_page').val('1');
            }

            initializeSelect2InModal($modal, '.load-select2');

            initializeFlatpickrSingle($modal[0]);

            initializeCkeditor($modal);

            initializeDatepicker($modal);

            initializeTagsInput();

            initializeBrandsSelect($modal);

            initializeCategoriesSelect($modal);

            initializeCategoriesMultipleSelect($modal);

            initializeFeaturedIdsSelect($modal);

            initializeAttributeSelect($modal);

            initializeVariantSelect($modal);

            initializeRelatedProductsSelect($modal);

            initializeAttributeSelectSimple($modal);

            $(document).on( "click", '.detailproduct',function(e) {
                var product_id = $(this).data('product_id');
                //console.log(product_id);

            });

            // Handler removed - conflict cu select2:select din initializeAttributeSelect()

            if (typeof ModalHelper.deleteImagesHandler === 'function') {
                ModalHelper.deleteImagesHandler();
            }

        });   
        
    });


    $(document).on("keyup", ".slugify", function (e) {
        var url = $(this).data('url');
        var id = $(this).attr('id');

        // Preluare text
        var text = $(this).val();

        // Eliminare diacritice
        text = text.normalize("NFD").replace(/[\u0300-\u036f]/g, "");

        // Punem textul fără diacritice în input și slugify îl procesează corect
        $(this).val(text);

        $("." + url).slugify("#" + id);
    });


    $(document).on('submit', 'form[data-async-modal]', function(event) {

        event.preventDefault();

        var formData = new FormData(this);
        var $form = $(this);
        var form_id = $form.attr('id');
        var $submitBtn = ModalHelper.getSubmitButton($form);

        //tst
        //if ($form.data('inflight') === true) return;
        //$form.data('inflight', true);

        var isFormValid = true;

        var result = ModalHelper.validateForm(form_id);
        if (!result.valid) {
            ModalHelper.showError({ message: result.errors.join("<br>") }, 'msg_' + form_id, l['a-required-fields']);
            return;
        }

        var submitText = ModalHelper.disableSubmitButton($submitBtn);

        $('#msg_'+form_id).html('<img src="'+BASE_URL+'assets/admin/img/loading.gif">');

        $.ajax({
            type: $form.attr('method'),
            dataType: "JSON",
            url: $form.attr('action'),
            data: formData,
            cache: false,
            async: true,
            processData: false,
            contentType: false,

            success: function(data){

                if (data.status == 'success') {
                    ModalHelper.showMessage(data, 'msg_' + form_id, l['a-information-updated']);
                    ModalHelper.fadeOutMessage('msg_' + form_id, 800);

                    ModalHelper.redrawDataTables(data);
                    ModalHelper.updateFields(data);

                    setTimeout(function() {
                        ModalHelper.closeModalByFormId(form_id);
                        ModalHelper.reloadPageIfNeeded(data);
                        ModalHelper.redirectIfNeeded(data, 500);
                    }, 1500);

                } else if (data.status == 'validation_error') {
                    ModalHelper.showValidationError(data, 'msg_' + form_id, l['a-required-fields']);
                    ModalHelper.enableSubmitButton($submitBtn, submitText);
                    //$form.data('inflight', false);
                } else if (data.status == 'error') {
                    ModalHelper.showError(data, 'msg_' + form_id, l['a-required-fields']);
                }

                // Re-enable submit button
                //ModalHelper.enableSubmitButton($submitBtn, submitText);
                //$form.data('inflight', false);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                ModalHelper.showError({ message: 'A server error occurred. Please try again.' }, 'msg_' + form_id);
                //ModalHelper.enableSubmitButton($submitBtn, submitText);
                //$form.data('inflight', false);
            }
        });
    });

    $(document).on("click", ".delete_item_datatable", function(e) {
        e.preventDefault();

        ModalHelper.confirmDelete({
            url: $(this).data("url"),
            itemId: $(this).data("item_id"),
            datatableId: $(this).data("datatable_id"),
            //reloadPage: true,
            //title: "Stergere definitiva!",
            //message: "Sigur vrei sa stergi acest element?"
        });
    });

    $(document).on("click", ".delete_item", function(e) {
        e.preventDefault();
        ModalHelper.confirmDelete({
            url: $(this).data("url"),
            itemId: $(this).data("item_id"),
            reloadPage: true
        });
    });


    $(document).on('click', '.change-status', function(e) {
        e.preventDefault();

        const $this = $(this);
        const url = $this.data('url');
        const itemId = $this.data('item_id');
        const datatableId = $this.data('datatable_id');

        if (!url || !itemId) {
            console.warn('Missing url or item_id data attribute');
            return;
        }

        $this.prop('disabled', true);

        $.ajax({
            type: 'POST',
            url: url,
            data: { item_id: itemId },
            cache: false,
            dataType: 'json',
            success: function(response) {
                if (response && response.status === 'success') {
                    if (response.active === 0) {
                        $this.removeClass('active-box').addClass('inactive-box').html(response.message);
                    } else {
                        $this.removeClass('inactive-box').addClass('active-box').html(response.message);
                    }

                    if (datatableId && $.fn.DataTable.isDataTable('#' + datatableId)) {
                        $('#' + datatableId).DataTable().draw(false);
                    }
                } else {
                    console.warn('Unexpected response:', response);
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', status, error);
                alert('An error occurred. Please try again.');
            },
            complete: function() {
                $this.prop('disabled', false);
            }
        });
    });





});

