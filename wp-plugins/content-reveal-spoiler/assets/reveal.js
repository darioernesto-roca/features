(function () {
  'use strict';

  document.addEventListener('click', function (event) {
    const trigger = event.target.closest('[data-crs-reveal] .crs-reveal__trigger');

    if (!trigger) {
      return;
    }

    const reveal = trigger.closest('[data-crs-reveal]');
    const content = document.getElementById(trigger.getAttribute('aria-controls'));
    const isOpen = trigger.getAttribute('aria-expanded') === 'true';

    if (!content || (isOpen && reveal.hasAttribute('data-crs-one-way'))) {
      return;
    }

    trigger.setAttribute('aria-expanded', String(!isOpen));
    trigger.querySelector('.crs-reveal__trigger-text').textContent = isOpen
      ? trigger.dataset.crsShowLabel
      : trigger.dataset.crsHideLabel;
    content.hidden = isOpen;
    reveal.classList.toggle('is-open', !isOpen);

    if (!isOpen && reveal.hasAttribute('data-crs-one-way')) {
      trigger.hidden = true;
    }
  });
})();
