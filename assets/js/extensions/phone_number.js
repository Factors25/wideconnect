export async function WIDECONNECTPhoneNumber() {
    let phone_numbers = document.querySelectorAll('input[data-phone-number]') || [];

    phone_numbers.forEach((phone_number) => {
        phone_number.removeEventListener('input', format);
        phone_number.addEventListener('input', format)
    });
}

function format(e) {
    let phoneNumber = e.target.value;

    phoneNumber = phoneNumber.replace(/\D/g, '');

    if (phoneNumber.length > 10) {
        phoneNumber = phoneNumber.slice(0, 10);
    }

    e.target.value = phoneNumber.replace(/(\d{2})(?=\d)/g, '$1 ');
}