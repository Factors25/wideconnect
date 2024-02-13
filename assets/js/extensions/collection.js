export class WIDECONNECTCollection {
    constructor(options = {}) {
        this.options = options;
        this.options = {...this.getDefaultOptions(), ...options};

        this.initAddListener();
        this.initRemoveListener();
    }

    getDefaultOptions() {
        return {
            autoAdd: true,
            autoRemove: true
        }
    }

    initAddListener() {
        $('[data-collection-add]').each((index, el) => {
            $(el).off('click').on('click', (e) => {
                e.preventDefault();
                this.addElement(e);

                this.initRemoveListener()
            })
        })
    }

    initRemoveListener() {
        $('[data-collection-remove]').each((index, el) => {
            $(el).off('click').on('click', (e) => {
                e.preventDefault();
                this.removeElement(e);
            })
        })
    }

    addElement(e) {
        const collectionHolder = $(e.currentTarget).parents('[data-collection]').first().find('[data-collection-handler]');
        let id = collectionHolder.children().last();
        id = id.data('collection-id') ? id.data('collection-id') : 0;
        const new_id = (
            $(id).find('.dataTables_empty').length === 0
                ? parseInt(id) + 1
                : 0);
        let prototype = $(e.currentTarget).data('collection-prototype').replace(/__name__/g, new_id);

        if (this.options.autoAdd) {
            prototype = $(prototype).hide();

            collectionHolder.append(prototype);

            prototype.fadeIn('slow');
        }

        this.onAdd(prototype);
    }

    removeElement(e) {
        let el = $(e.currentTarget).parents('[data-collection-id]').first();

        $(el).fadeOut('slow', () => {
            if (this.options.autoRemove) {
                $(el).remove();
            }
            this.onRemove(el);
        });
    }

    onAdd(prototype) {
        if (typeof this.options.onAdd === 'function') this.options.onAdd(prototype);
    }

    onRemove(prototype) {
        if (typeof this.options.onRemove === 'function') this.options.onRemove(prototype);
    }
}