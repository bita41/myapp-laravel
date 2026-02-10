/// <reference types="datatables.net" />
import type { DataTableColumnMeta } from '@/types/datatables';

export class DataTableService {
    /**
     * Get standard DataTable DOM configuration
     */
    public getDataTableDom(): string {
        return "<'row mb-3'<'col-sm-12 col-md-6 d-flex align-items-center justify-content-start'f><'col-sm-12 col-md-6 d-flex align-items-center justify-content-end'l>>" +
            "<'row'<'col-sm-12'tr>>" +
            "<'row mt-3'<'col-sm-12 col-md-7 d-flex align-items-center justify-content-start'i><'col-sm-12 col-md-5 d-flex align-items-center justify-content-end'p>>";
    }

    /**
     * Apply column filters to DataTable
     */
    public applyTableFilters(
        tableApi: DataTables.Api,
        customSelectOptions: Record<number, Record<string, string>> = {}
    ): void {
        const settings = tableApi.settings()[0] || {};
        const isServerSide = !!settings.oFeatures?.bServerSide;

        // Cache titles BEFORE empty(), avoid re-reading after mutations
        const headersMeta: DataTableColumnMeta[] = [];
        tableApi.columns().every(function (this: DataTables.ColumnMethods) {
            const col = this;
            const headerEl = col.header();
            const $h = $(headerEl);

            headersMeta.push({
                column: col,
                index: col.index(),
                $header: $h,
                title: $h.text().trim(),
                isNoSearch: $h.hasClass('no-search'),
                isSelect: $h.hasClass('select_search')
            });
        });

        // Batch build controls first, then mutate DOM once per header
        for (const meta of headersMeta) {
            if (meta.isNoSearch) continue;

            if (meta.isSelect) {
                this.createSelectFilter(meta, customSelectOptions, isServerSide);
            } else {
                this.createTextFilter(meta);
            }
        }
    }

    /**
     * Create select filter for column
     */
    private createSelectFilter(
        meta: DataTableColumnMeta,
        customSelectOptions: Record<number, Record<string, string>>,
        isServerSide: boolean
    ): void {
        const selectEl = document.createElement('select');
        selectEl.className = 'form-select form-select-sm';

        const optAll = document.createElement('option');
        optAll.value = '';
        optAll.textContent = 'Toate';
        selectEl.appendChild(optAll);

        // Fill options (prefer provided list for server-side)
        const options = customSelectOptions[meta.index];

        if (options && typeof options === 'object') {
            for (const [val, label] of Object.entries(options)) {
                const opt = document.createElement('option');
                opt.value = String(val);
                opt.textContent = String(label);
                selectEl.appendChild(opt);
            }
        } else if (!isServerSide) {
            // Client-side only (server-side: column.data() is just current page anyway)
            meta.column.data().unique().sort().each(function (d: any) {
                const v = (d ?? '').toString().trim();
                if (!v) return;

                const opt = document.createElement('option');
                opt.value = v;
                opt.textContent = v;
                selectEl.appendChild(opt);
            });
        }

        selectEl.addEventListener('change', function () {
            const v = this.value ? 'exact:' + this.value : '';
            meta.column.search(v).draw();
        });

        // Single mutation
        meta.$header.empty().append(selectEl);
    }

    /**
     * Create text input filter for column
     */
    private createTextFilter(meta: DataTableColumnMeta): void {
        const inputEl = document.createElement('input');
        inputEl.type = 'text';
        inputEl.className = 'form-control form-control-sm';
        inputEl.placeholder = 'Cauta ' + meta.title + '...';

        const onChange = this.debounce(function (this: HTMLInputElement) {
            meta.column.search(this.value || '').draw();
        }, 250);

        inputEl.addEventListener('keyup', onChange);
        inputEl.addEventListener('change', onChange);
        inputEl.addEventListener('search', onChange);

        meta.$header.empty().append(inputEl);
    }

    /**
     * Debounce helper
     */
    private debounce<T extends (...args: any[]) => any>(fn: T, wait: number): T {
        let t: ReturnType<typeof setTimeout> | null = null;
        return function (this: any, ...args: Parameters<T>) {
            const ctx = this;
            if (t !== null) {
                clearTimeout(t);
            }
            t = setTimeout(() => {
                fn.apply(ctx, args);
            }, wait);
        } as T;
    }
}

export default DataTableService;
