/// <reference types="datatables.net" />
/// <reference types="select2" />

import type jQuery from 'jquery';

declare global {
    interface Window {
        jQuery: typeof jQuery;
        $: typeof jQuery;
        axios: typeof import('axios').default;
        t: (key: string) => string;
        l: Record<string, string>;
        BASE_URL: string;
        BASE_URL_ADMIN: string;
        DATATABLE_ELEMENTS_PER_PAGE?: number;
        DICTIONARIES_DATA_URL?: string;

        // Legacy globals (will be deprecated)
        AppUtils: {
            dataTableDom: () => string;
        };
        applyTableFilters: (
            tableApi: DataTables.Api,
            customSelectOptions?: Record<number, Record<string, string>>
        ) => void;
        ModalHelper: ModalHelperInterface;
    }
}

export interface ModalHelperInterface {
    closeModal($form: JQuery): void;
    closeModalByFormId(formId: string): void;
    resetForm(formSelector: string): void;
    fadeOutMessage(containerId: string, timeout?: number): void;
    updateFields(data: { updated_fields?: Array<{ field: string; value: string }> }): void;
    redrawDataTables(data: { datatable_id?: string; datatable_id_2?: string }): void;
    reloadPageIfNeeded(data: { reload_internal_page?: number }): void;
    redirectIfNeeded(data: { redirect_url?: string }, delay?: number): void;
    disableSubmitButton($button: JQuery): string;
    enableSubmitButton($button: JQuery, originalText: string): void;
    showMessage(data: { message?: string }, containerId: string, defaultMessage?: string): void;
    showError(data: { message?: string }, containerId: string, defaultMessage?: string): void;
    showValidationError(
        data: string | string[] | { errors?: string[]; message?: string },
        containerId: string,
        defaultMessage?: string
    ): void;
    getSubmitButton($form: JQuery): JQuery;
    showLoader(containerId: string): void;
    validateForm(formId: string): { valid: boolean; errors: string[] };
    confirmDelete(options: {
        title?: string;
        message?: string;
        url: string;
        itemId: string | number;
        datatableId?: string;
        reloadPage?: boolean;
    }): void;
    downloadFile(url: string, filename: string): void;
    deleteImagesHandler(): void;
}

export interface AjaxResponse {
    status: 'success' | 'error' | 'validation_error';
    message?: string;
    datatable_id?: string;
    datatable_id_2?: string;
    redirect_url?: string;
    reload_internal_page?: number;
    updated_fields?: Array<{ field: string; value: string }>;
    errors?: string[];
}

export {};
