import {createModal, datatable} from "./user_util";

document.addEventListener('DOMContentLoaded', () => {
    datatable();
    createModal({datatable: true});
})