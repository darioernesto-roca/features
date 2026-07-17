(function () {
  const updateTable = (table, period) => {
    const yearly = period === 'yearly';

    table.querySelectorAll('[data-ptb-period]').forEach((button) => {
      button.classList.toggle('is-active', button.getAttribute('data-ptb-period') === period);
    });

    table.querySelectorAll('.ptb-plan__price').forEach((price) => {
      const priceValue = yearly ? price.getAttribute('data-ptb-yearly') : price.getAttribute('data-ptb-monthly');
      const label = yearly ? table.getAttribute('data-ptb-yearly-label') : table.getAttribute('data-ptb-monthly-label');
      const priceTarget = price.querySelector('[data-ptb-price]');
      const labelTarget = price.querySelector('[data-ptb-period-label]');

      if (priceTarget) {
        priceTarget.textContent = priceValue;
      }

      if (labelTarget) {
        labelTarget.textContent = label;
      }
    });
  };

  document.addEventListener('click', (event) => {
    const button = event.target.closest('[data-ptb-period]');

    if (!button) {
      return;
    }

    updateTable(button.closest('[data-ptb-pricing]'), button.getAttribute('data-ptb-period'));
  });
})();
