import './bootstrap';
import { ModalService } from '@/services/ModalService';
import { DataTableService } from '@/services/DataTableService';

// CI4-style: translate key via window.l (injected by lang.js when $langKeys set)
if (typeof window !== 'undefined') {
    window.t = function (key: string): string {
        return (window.l && window.l[key]) ? window.l[key] : key;
    };
}

// DataTables: uses global jQuery (see vite alias jquery -> jquery-shim.js)
import 'datatables.net-bs5';
import 'datatables.net-responsive-bs5';

// Initialize services
const modalService = new ModalService();
const dataTableService = new DataTableService();

// Expose services globally for backward compatibility with legacy code
window.ModalHelper = modalService;
window.AppUtils = {
    dataTableDom: () => dataTableService.getDataTableDom()
};
window.applyTableFilters = (tableApi, customSelectOptions) => {
    dataTableService.applyTableFilters(tableApi, customSelectOptions);
};

// Static map so Vite can resolve paths at build time (support both .js and .ts)
const moduleLoaders = import.meta.glob<{ default: () => void }>('./modules/**/*.{js,ts}');

async function initPageModules(): Promise<void> {
    const raw = document.body?.getAttribute('data-page-modules');
    if (!raw) return;

    let modules: string[] = [];
    try {
        modules = JSON.parse(raw) || [];
    } catch (e) {
        modules = [];
    }
    if (!Array.isArray(modules) || modules.length === 0) return;

    for (const key of modules) {
        // Try .ts first, then .js for backward compatibility
        const pathTs = `./modules/${key}.ts`;
        const pathJs = `./modules/${key}.js`;

        const load = moduleLoaders[pathTs] || moduleLoaders[pathJs];
        if (!load) {
            console.error('Module not found: ' + key + ' (tried: ' + pathTs + ', ' + pathJs + ')');
            continue;
        }
        try {
            const module = await load();
            if (module.default && typeof module.default === 'function') {
                module.default();
            }
        } catch (e) {
            console.error('Failed to load module: ' + key, e);
        }
    }
}

$(function () {
    modalService.init();
    initPageModules();
});
