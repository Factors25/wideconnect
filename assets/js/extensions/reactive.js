export async function WIDECONNECTReactive() {
    let reactives = document.querySelectorAll('[data-reactive-url]') || [];

    for (const reactive of reactives) {
        let urls = JSON.parse(reactive.dataset['reactiveUrl']);
        for (const url of urls) {
            await beReactive(url);
        }
    }
}

async function beReactive(url) {
    let response = await fetch(url, {
        method: 'GET',
        headers: new Headers()
    })

    if (!response.ok) {
        errors(`Erreur HTTP ${response.status}`);
    }

    const responseData = await response.json();

    for (const [key, value] of Object.entries(responseData)) {
        for (const [action, data] of Object.entries(value)) {
            let selectors = document.querySelectorAll(`[data-reactive-${key}="${action}"]`);
            selectors.forEach((selector) => {
                selector.innerHTML = data;
            })
        }
    }
}

function errors(message) {
    console.log(message);
}