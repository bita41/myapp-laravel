import './app-utils';
// DataTables: uses global jQuery (see vite alias jquery -> jquery-shim.js)
import 'datatables.net-bs5';
import 'datatables.net-responsive-bs5';
import { initRemoteModals } from './remote-modal';

// Static map so Vite can resolve paths at build time (no full dynamic import)
const moduleLoaders = import.meta.glob('./modules/**/*.js');

async function initPageModules() {
    const raw = document.body?.getAttribute('data-page-modules');
    if (!raw) return;

    let modules = [];
    try { modules = JSON.parse(raw) || []; } catch (e) { modules = []; }
    if (!Array.isArray(modules) || modules.length === 0) return;

    for (const key of modules) {
        const path = `./modules/${key}.js`;
        const load = moduleLoaders[path];
        if (!load) {
            console.error('Module not found: ' + key + ' (path: ' + path + ')');
            continue;
        }
        try {
            await load();
        } catch (e) {
            console.error('Failed to load module: ' + key, e);
        }
    }
}

$(function () {
    initRemoteModals();
    initPageModules();
});
