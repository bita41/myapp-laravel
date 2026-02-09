window.AppUtils = window.AppUtils || {};

window.AppUtils.dataTableDom = function () {
    return "<'row mb-3'<'col-sm-12 col-md-6 d-flex align-items-center justify-content-start'f><'col-sm-12 col-md-6 d-flex align-items-center justify-content-end'l>>" +
        "<'row'<'col-sm-12'tr>>" +
        "<'row mt-3'<'col-sm-12 col-md-7 d-flex align-items-center justify-content-start'i><'col-sm-12 col-md-5 d-flex align-items-center justify-content-end'p>>";
};

window.applyTableFilters = function (tableApi, customSelectOptions) {
    customSelectOptions = customSelectOptions || {};

    tableApi.columns().every(function () {
        const column = this;
        const index = column.index();
        const header = $(column.header());
        const columnTitle = header.text().trim();

        if (header.hasClass('select_search')) {
            const select = $('<select class="form-select form-select-sm"><option value="">Toate</option></select>')
                .appendTo(header.empty())
                .on('change', function () {
                    column.search($(this).val() ? 'exact:' + $(this).val() : '').draw();
                });

            if (customSelectOptions[index]) {
                $.each(customSelectOptions[index], function (val, label) {
                    select.append('<option value="' + val + '">' + label + '</option>');
                });
            } else {
                column.data().unique().sort().each(function (d) {
                    if (d) select.append('<option value="' + d + '">' + d + '</option>');
                });
            }
            return;
        }

        if (!header.hasClass('no-search')) {
            $('<input type="text" class="form-control form-control-sm" placeholder="Cauta ' + columnTitle + '..." />')
                .appendTo(header.empty())
                .on('keyup change clear', function () {
                    column.search($(this).val()).draw();
                });
        }
    });
};
