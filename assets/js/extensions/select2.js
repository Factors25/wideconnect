export async function WIDECONNECTSelect2() {
    let selects = document.querySelectorAll('select[data-select]') || [];

    selects.forEach((select) => {
        init(select)
    });
}

function init(select) {
    if ($(select).hasClass("select2-hidden-accessible")) return;

    $(select)
        .addClass('form-select')
        .attr('data-control', 'select2')
        .attr('data-toggle', 'select2');

    let multiple = $(select).data('multiple');
    if (multiple) {
        $(select)
            .addClass('select2-multiple')
            .attr('multiple', 'multiple')
    }

    let placeholder = $(select).data('placeholder');
    if (placeholder) {
        $(select).attr('data-placeholder', placeholder)
    }

    let url = $(select).data('url');
    if (url && url.includes('api-adresse.data.gouv.fr')) {
        $(select).select2(getAddressOptions(select))
    } else {
        $(select).select2(getDefaultOptions(select));
    }
}

function getParent(select) {
    if ($(select).parents('#modal').length > 0) return document.querySelector('#modal');
    return document.body;
}

function getDefaultOptions(select) {
    return {
        width: '100%',
        language: {
            noResults: function () {
                return "Aucun résultat trouvé";
            }
        },
        escapeMarkup: function (m) {
            return m;
        },
        templateResult: function (data) {
            return data.title !== "" ? data.title : data.text;
        },
        templateSelection: function (data) {
            return data.title !== "" ? data.title : data.text;
        },
        dropdownParent: getParent(select)
    }
}

function getAddressOptions(select) {
    return {
        ajax: {
            url: $(select).data('url'),
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {q: params.term};
            },
            processResults: function (data, params) {
                params.page = params.page || 1;

                return {
                    results: data.features.map((feature) => {
                        return feature.properties;
                    }),
                    pagination: {
                        more: (params.page * 30) < data.total_count
                    }
                };
            },
            cache: true
        },
        placeholder: 'Chercher',
        width: '100%',
        language: {
            noResults: function () {
                return "Aucun résultat trouvé.";
            },
            searching: function () {
                return "Recherche en cours ...";
            },
            inputTooShort: function () {
                return "Veuillez entrer 3 caractères ou plus.";
            }
        },
        minimumInputLength: 3,
        templateResult: function (data) {
            if (data.loading) {
                return data.text;
            }

            return $(`<div class='select2-result-repository clearfix'>
                    <div class="select2-result-repository__avatar">${data.label}</div>
                </div>`);
        },
        templateSelection: function (data) {
            if (data.label !== undefined) {
                $(select)
                    .parents('[data-collection-id]')
                    .find('input')
                    .each((i, e) => {
                        if (i === 1) $(e).val(getAddressStreet(data));
                        if (i === 3) $(e).val(getAddressZipCode(data));
                        if (i === 4) $(e).val(getAddressCity(data));
                    })
            }

            return data.label || data.text;
        },
        dropdownParent: getParent(select),
    }
}

function getAddressStreet(data) {
    let str = '';

    let streetNumber = data.housenumber;
    let streetName = data.street;

    if (streetNumber !== undefined) str += streetNumber;
    if (streetName !== undefined) str += ' ' + streetName;

    return str.trim()
}

function getAddressZipCode(data) {
    if (data.postcode !== undefined) return data.postcode;
    return '';
}

function getAddressCity(data) {
    if (data.city !== undefined) return data.city;
    return '';
}