import {WIDECONNECTCollection} from "./collection";
import {WIDECONNECTReactive} from "./reactive";
import {WIDECONNECTPhoneNumber} from "./phone_number";
import {WIDECONNECTSelect2} from "./select2";

export class WIDECONNECTModal {
    constructor(el, options = {}) {
        this.el = $(el);

        this.options = {...this.getDefaultOptions(), ...options};

        this.modal = $('#modal');
        this.MODAL = new bootstrap.Modal(this.modal);

        this.setup();
    }

    setup() {
        this.el.off('click').on('click', async (e) => {
            e.preventDefault();
            await this.handle();
        })
    }

    async handle() {
        try {
            let response = await fetch(this.options.url, {
                method: 'GET',
                headers: new Headers(this.headers())
            })

            if (!response.ok) {
                this.errors(`Erreur HTTP ${response.status}`);
            }

            const responseData = await response.json();

            if (responseData.html) {
                await this.init(responseData.html);
            }
        } catch (e) {
            this.errors(e.message);
        }
    }

    init(html) {
        this.initTitle();
        this.initBody(html);
        this.initHeader();
        this.initDialog();

        this.MODAL.show();

        this.initCallbacks();
    }

    initCallbacks() {
        if (this.options.autoInit === true) {
            // new File(); new Textarea(); new Select2()
            new WIDECONNECTCollection({
                onAdd: (prototype) => {
                    this.onHandle({'collection': prototype});
                    WIDECONNECTPhoneNumber();
                    WIDECONNECTSelect2();
                },
                onRemove: () => this.onHandle({})
            });
            WIDECONNECTPhoneNumber();
            WIDECONNECTSelect2();
        }

        this.getForm().off('submit').on('submit', async (e) => {
            e.preventDefault();
            await this.submit();
        })

        this.modal.find('[data-bs-dismiss="modal"]').each((index, el) => {
            $(el).off('click').on('click', () => {
                this.reset();
                this.MODAL.hide();
            });
        });

        this.onHandle({});
    }

    async submit() {
        this.onFormData();

        let response = await fetch(this.options.url, {
            method: 'POST',
            headers: new Headers(this.headers()),
            body: this.options.FormData
        })

        if (!response.ok) {
            this.errors(`Erreur HTTP ${response.status}`);
        }

        const responseData = await response.json();

        if (responseData.success === true) {
            if (this.el.data('redirect')) {
                $(window).unbind();
                document.location.href = this.el.data('redirect');
            } else if (responseData.redirect) {
                $(window).unbind();
                document.location.href = responseData.redirect;
            } else {
                this.MODAL.hide();
                this.success();

                await this.onSuccess(responseData)
            }
        } else {
            this.init(responseData.html)
        }
    }

    getTitle() {
        return this.modal.find('.modal-title');
    }

    initTitle() {
        this.getTitle().html(this.options.title)
    }

    resetTitle() {
        this.getTitle().html('')
    }

    getBody() {
        return this.modal.find('.modal-body');
    }

    initBody(html) {
        this.getBody().html(html);
    }

    resetBody() {
        this.getBody().html('')
    }

    getHeader() {
        return this.modal.find('.modal-header');
    }

    initHeader() {
        this.getHeader().addClass(this.options.header.class);
    }

    resetHeader() {
        this.getHeader().removeClass(this.options.header.class);
    }

    getDialog() {
        return this.modal.find('.modal-dialog');
    }

    initDialog() {
        this.getDialog().addClass(this.options.dialog.class);
    }

    resetDialog() {
        this.getDialog().removeClass(this.options.dialog.class)
    }

    getForm() {
        return this.modal.find('form');
    }

    reset() {
        this.resetTitle();
        this.resetBody();
        this.resetHeader();
        this.resetDialog();
    }

    getDefaultOptions() {
        return {
            title: this.el.data('title'),
            url: this.el.data('url'),
            message: this.el.data('message'),
            header: {
                class: 'bg-primary'
            },
            dialog: {
                class: 'modal-lg'
            },
            autoInit: true,
            FormData: null,
            reactiveUrl: this.el.data('reactive-url')
        }
    }

    setOptions(options = {}) {
        this.options = {...this.options, ...options};
    }

    headers() {
        return {
            'current-uri': document.location.pathname
        }
    }

    errors(message) {
        console.log(message)
    }

    success() {
        this.reset();
    }

    async onPreSubmit(trigger) {
        this.onFormData();

        this.options.FormData.forEach((value, key) => {
            if (key.includes('_token')) this.options.FormData.delete(key);
        })

        let response = await fetch(this.options.url, {
            method: 'POST',
            headers: new Headers(this.headers()),
            body: this.options.FormData
        })

        if (!response.ok) {
            this.errors(`Erreur HTTP ${response.status}`);
        }

        const responseData = await response.json();

        if (responseData.success === true) {
            if (responseData.redirect) {
                $(window).unbind();
                document.location.href = responseData.redirect;
            } else {
                this.MODAL.hide();
                this.success();

                await this.onSuccess(responseData)
            }
        } else {
            this.onPostSubmit(trigger, responseData.html);
        }
    }

    onPostSubmit(trigger, html) {
        let container = null;
        let data = $(trigger.currentTarget).data();
        Object.keys(data).forEach((element) => {
            let data_ = 'data-' + (element.replace(/([A-Z])/g, '-$1')).toLowerCase();
            if (data_.startsWith('data-dom-')) {
                container = data_.replace('-dom-', '-container-');

                $(`[${container}]`).replaceWith($(html).find(`[${container}]`));

                $(`[${container}]`).find('input, select').each((i, e) => {
                    $(e).removeClass('is-invalid');
                })

                $(`[${container}]`).find('.invalid-feedback').each((i, e) => {
                    $(e).remove();
                })
            }
        })
    }

    onFormData() {
        this.options.FormData = new FormData(this.getForm()[0]);
        if (typeof this.options.onFormData === "function") this.options.onFormData(this);
    }

    onHandle(data) {
        this.modal.find('[data-formevent]').off().on('change', async (e) => {
            await this.onPreSubmit(e);
        });
        if (typeof this.options.onHandle === "function") this.options.onHandle(this, data);
    }

    async onSuccess(response) {
        await WIDECONNECTReactive();
        if (typeof this.options.onSuccess === "function") await this.options.onSuccess(this, response);
    }
}