import {WIDECONNECTCollection} from "./collection";
import {WIDECONNECTReactive} from "./reactive";
import {WIDECONNECTPhoneNumber} from "./phone_number";
import {WIDECONNECTSelect2} from "./select2";

export class WIDECONNECTForm {
    constructor(el, options = {}) {
        this.el = $(el);

        this.options = {...this.getDefaultOptions(), ...options};

        this.setup();
    }

    async setup() {
        await this.handle();
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
        this.initBody(html);
        this.initCallbacks();
    }

    initCallbacks() {
        if (this.options.autoInit === true) {
            // new File(); new Textarea(); new Select2()
            new WIDECONNECTCollection({
                onAdd: (prototype) => {
                    this.onHandle({'collection': prototype, 'action': 'add'});
                    WIDECONNECTPhoneNumber();
                    WIDECONNECTSelect2();
                },
                onRemove: (prototype) => {
                    this.onHandle({'collection': prototype, 'action': 'remove'})
                },
                autoAdd: this.options.collectionAutoAdd,
                autoRemove: this.options.collectionAutoRemove
            });
            WIDECONNECTPhoneNumber();
            WIDECONNECTSelect2();
        }

        this.getForm().off('submit').on('submit', async (e) => {
            e.preventDefault();
            await this.submit();
        })

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
            this.success();
            await this.onSuccess(responseData)
        } else {
            this.init(responseData.html)
        }
    }

    getBody() {
        return this.el;
    }

    initBody(html) {
        this.getBody().html(html);
        this.getBody().find('.is-invalid').removeClass('is-invalid')
    }

    getForm() {
        return this.el.find('form');
    }

    getDefaultOptions() {
        return {
            url: this.el.data('url'),
            body: this.el.data('body'),
            autoInit: true,
            FormData: null,
            collectionAutoAdd: true,
            collectionAutoRemove: true
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

    }

    async onPreSubmit() {
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
            this.initCallbacks();
        } else {
            this.init(responseData.html)
        }
    }

    onFormData() {
        this.options.FormData = new FormData(this.getForm()[0]);
        if (typeof this.options.onFormData === "function") this.options.onFormData(this);
    }

    onHandle(data) {
        if (typeof this.options.onHandle === "function") this.options.onHandle(this, data);
        this.getBody().find('[data-formevent]').off().on('change', async () => {
            await this.onPreSubmit();
        });
    }

    async onSuccess(response) {
        await WIDECONNECTReactive();
        if (typeof this.options.onSuccess === "function") await this.options.onSuccess(this, response);
    }
}