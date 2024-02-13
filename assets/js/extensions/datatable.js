export class WIDECONNECTDatatable {
    constructor(el, options = {}) {
        this.el = el;
        this.options = {...this.getDefaultOptions(), ...options};
        this.datatable = $(this.el);
        this.DATATABLE = this.init();
    }

    init() {
        return this.datatable.DataTable({
            language: this.languageFR(),
            fixedHeader: this.fixedHeader(),
            lengthMenu: this.lengthMenu(),
            pageLength: this.pageLength(),
            info: this.info(),
            scrollX: this.scrollX(),
            scrollY: this.scrollY(),
            search: this.search(),
            stateSave: this.stateSave(),
            searching: this.searching(),
            dom: this.dom(),
            paging: this.paging(),
            pagingType: this.pagingType(),
            autoWidth: this.autoWidth(),
            select: this.select(),
            order: this.order(),
            ordering: this.ordering(),
            responsive: this.responsive(),
            ...this.serverSide(),
            drawCallback: () => {
                this.onDrawCallback();
            },
            initComplete: () => {
                this.onInitComplete()
            }
        })
    }

    pagingType() {
        if (this.datatable.data('paging-type')) return this.datatable.data('paging-type');
        return 'simple_numbers';
    }

    scrollX() {
        if (this.datatable.data('scroll-x')) return this.datatable.data('scroll-x');
        return true;
    }

    scrollY() {
        if (this.datatable.data('scroll-y')) return this.datatable.data('scroll-y');
        return '400px';
    }

    languageFR() {
        return {
            processing: "Traitement en cours...",
            search: "Rechercher&nbsp;:",
            lengthMenu: "Afficher _MENU_ &eacute;l&eacute;ments",
            info: "Affichage de l'&eacute;lement _START_ &agrave; _END_ sur _TOTAL_ &eacute;l&eacute;ments",
            infoEmpty: "Affichage de l'&eacute;lement 0 &agrave; 0 sur 0 &eacute;l&eacute;ments",
            infoFiltered: "(filtr&eacute; de _MAX_ &eacute;l&eacute;ments au total)",
            infoPostFix: "",
            loadingRecords: "Chargement en cours...",
            zeroRecords: "Aucun &eacute;l&eacute;ment &agrave; afficher",
            emptyTable: "Aucune donnée disponible dans le tableau",
            paginate: {
                first: "Premier",
                previous: "<i class='fa-solid fa-chevron-left'></i>",
                next: "<i class='fa-solid fa-chevron-right'></i>",
                last: "Dernier"
            },
            aria: {
                sortAscending: ": activer pour trier la colonne par ordre croissant",
                sortDescending: ": activer pour trier la colonne par ordre décroissant"
            },
            select: {
                rows: ''
            }
        };
    }

    dom() {
        return "" +
            "<'row'<'col-md-6 pb-2 col-sm-12'l><'col-md-6 pb-2 col-sm-12'f>>" +
            "<'row'<'col-12'tr>>" +
            "<'row'<'col-md-6 col-sm-12 pb-2'i><'col-md-6 col-sm-12 pb-2'p>>";
    }

    fixedHeader() {
        if (this.datatable.data('fixedHeader')) return this.datatable.data('fixed-header');
        return true;
    }

    info() {
        if (this.datatable.data('info')) return this.datatable.data('info');
        return true;
    }

    search() {
        if (this.datatable.data('search')) return this.datatable.data('search');
        return true;
    }

    searching() {
        if (this.datatable.data('searching')) return this.datatable.data('searching');
        return true;
    }

    paging() {
        if (this.datatable.data('paging')) return this.datatable.data('paging');
        return true;
    }

    autoWidth() {
        if (this.datatable.data('auto-width')) return this.datatable.data('auto-width');
        return false;
    }

    select() {
        if (this.datatable.data('select')) return this.datatable.data('select');
        return false;
    }

    order() {
        if (this.datatable.data('order')) return this.datatable.data('order');
        return [[0, 'asc']];
    }

    ordering() {
        if (this.datatable.data('ordering')) return this.datatable.data('ordering');
        return true;
    }

    pageLength() {
        if (this.datatable.data('page-length')) return this.datatable.data('page-length');
        return 25;
    }

    lengthMenu() {
        if (this.datatable.data('length-menu')) return this.datatable.data('length-menu');
        return [[10, 25, 50, 100], [10, 25, 50, 100]];
    }

    stateSave() {
        if (this.datatable.data('state-save')) return this.datatable.data('state-save');
        return true;
    }

    serverSide() {
        if (this.datatable.data('server-side-url')) {
            return {
                ajax: {
                    url: this.el.dataset['serverSideUrl'],
                    type: 'POST',
                    dataSrc: 'data',
                    data: this.options.data
                },
                serverSide: true,
                processing: true
            }
        }

        return {};
    }

    responsive() {
        if (this.datatable.data('responsive')) this.datatable.data('responsive');
        return true;
    }

    getDefaultOptions() {
        return {
            data: {}
        }
    }

    setOptions(options = {}) {
        this.options = {...this.options, ...options};
    }

    reload() {
        this.DATATABLE.ajax.reload();
    }

    onDrawCallback() {
        if (typeof this.options.onDrawCallback === "function")
            setTimeout(() => this.options.onDrawCallback(this), 100);
    }

    onInitComplete() {
        if (typeof this.options.onInitComplete === "function")
            setTimeout(() => this.options.onInitComplete(this), 100);
    }
}