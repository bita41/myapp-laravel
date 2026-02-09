/**
 * Shim so that npm packages (e.g. datatables.net) use the page's global jQuery
 * instead of bundling a second copy. jQuery is loaded in head via script tag.
 */
export default typeof window !== 'undefined' && window.jQuery
    ? window.jQuery
    : function () {};
