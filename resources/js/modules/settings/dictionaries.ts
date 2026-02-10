export default function initDictionaries(): void {
    const $table = $('#datatable_dictionaries');
    if (!$table.length || !window.DICTIONARIES_DATA_URL) return;

    if ($.fn.dataTable && $.fn.dataTable.isDataTable($table[0])) {
        $table.DataTable().ajax.reload(undefined, false);
        return;
    }

    let filtersApplied = false;

    $table.DataTable({
        dom: window.AppUtils.dataTableDom(),
        responsive: true,
        pagingType: 'first_last_numbers',
        pageLength: window.DATATABLE_ELEMENTS_PER_PAGE || 25,
        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']],
        autoWidth: false,
        order: [],
        columnDefs: [
            { orderable: false, targets: 'no-sort' },
            { searchable: false, targets: 'no-search' }
        ],
        serverSide: true,
        processing: true,
        ajax: {
            url: window.DICTIONARIES_DATA_URL,
            type: 'GET'
        },
        initComplete: function () {
            if (filtersApplied) return;
            filtersApplied = true;

            const api = this.api();
            requestAnimationFrame(() => {
                requestAnimationFrame(() => {
                    window.applyTableFilters(api);
                });
            });
        }
    });
}

