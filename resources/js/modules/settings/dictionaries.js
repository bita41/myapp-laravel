$(document).ready(function () {
    var $table = $('#datatable_dictionaries');
    if (!$table.length || !window.DICTIONARIES_DATA_URL) return;

    $table.DataTable({
        dom: AppUtils.dataTableDom(),
        responsive: true,
        pagingType: 'first_last_numbers',
        pageLength: 25,
        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']],
        autoWidth: false,
        order: [],
        columnDefs: [{
            orderable: false,
            targets: ['no-sort']
        }],
        serverSide: true,
        processing: true,
        ajax: {
            url: window.DICTIONARIES_DATA_URL,
            type: 'GET'
        },
        initComplete: function () {
            applyTableFilters(this.api());
        }
    });
});
