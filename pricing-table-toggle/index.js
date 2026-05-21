const billingToggle = document.getElementById('billing-toggle');
const prices = document.querySelectorAll('.price');
const periods = document.querySelectorAll('.period');

function updateBilling(isYearly) {
    prices.forEach((price) => {
        price.textContent = isYearly ? price.dataset.yearly : price.dataset.monthly;
    });

    periods.forEach((period) => {
        period.textContent = isYearly ? 'per month, billed yearly' : 'per month';
    });
}

billingToggle.addEventListener('change', (event) => {
    updateBilling(event.target.checked);
});

updateBilling(false);
