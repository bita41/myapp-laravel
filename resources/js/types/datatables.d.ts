export interface DataTableColumnMeta {
    column: DataTables.ColumnMethods;
    index: number;
    $header: JQuery;
    title: string;
    isNoSearch: boolean;
    isSelect: boolean;
}

export interface DataTableConfig {
    dom: string;
    responsive: boolean;
    pagingType: string;
    pageLength: number;
    lengthMenu: number[][];
    autoWidth: boolean;
    order: Array<any>;
    columnDefs: Array<{ orderable: boolean; targets: string | number }>;
    serverSide?: boolean;
    processing?: boolean;
    ajax?: {
        url: string;
        type: string;
    };
    initComplete?: () => void;
}
