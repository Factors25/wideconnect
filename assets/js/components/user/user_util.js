import {WIDECONNECTDatatable} from "../../extensions/datatable";
import {WIDECONNECTModal} from "../../extensions/modal";

const target = {
    table: 'table[data-datatable="User"]',
    create_modal: '[data-modal="User"][data-role="create"]',
    update_modal: '[data-modal="User"][data-role="update"]',
    delete_modal: '[data-modal="User"][data-role="delete"]'
}

export function datatable(options = {}) {
    let tables = document.querySelectorAll(target.table) || [];
    if (tables.length > 0) {
        tables.forEach((table) => {
            if (!$(table).hasClass('dataTable')) {
                let datatable = new WIDECONNECTDatatable(table, {
                    ...options,
                    onDrawCallback: () => {
                        updateModal({datatable: datatable})
                        deleteModal({datatable: datatable})

                        if (!!options.reload) options.reload();
                    }
                });
            }
        })
    }
}

export function createModal(options = {}) {
    let modals = document.querySelectorAll(target.create_modal) || []
    if (modals.length > 0) {
        modals.forEach((modal) => {
            new WIDECONNECTModal(modal, {
                ...options,
                onSuccess: () => {
                    if (!!options.datatable) {
                        $(target.table).each((index, table) => $(table).DataTable().ajax.reload());
                    }
                }
            });
        })
    }
}

export function updateModal(options = {}) {
    let modals = document.querySelectorAll(target.update_modal) || []
    if (modals.length > 0) {
        modals.forEach((modal) => {
            new WIDECONNECTModal(modal, {
                ...options,
                onSuccess: () => {
                    if (!!options.datatable) options.datatable.reload();
                    else {
                        updateModal(options);
                        deleteModal(options);
                    }
                }
            });
        })
    }
}

export function deleteModal(options = {}) {
    let modals = document.querySelectorAll(target.delete_modal) || []
    if (modals.length > 0) {
        modals.forEach((modal) => {
            new WIDECONNECTModal(modal, {
                ...options,
                header: {
                    class: 'bg-danger'
                },
                onSuccess: () => {
                    if (!!options.datatable) options.datatable.reload();
                }
            });
        })
    }
}