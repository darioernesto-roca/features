(function () {
  document.addEventListener('click', (event) => {
    const toggle = event.target.closest('.mme-menu__toggle');

    if (!toggle) {
      return;
    }

    const item = toggle.closest('.mme-menu__item--has-children');
    const submenu = item ? item.querySelector(':scope > .mme-submenu') : null;
    const expanded = toggle.getAttribute('aria-expanded') === 'true';

    toggle.setAttribute('aria-expanded', expanded ? 'false' : 'true');

    if (submenu) {
      submenu.classList.toggle('is-open', !expanded);
    }
  });
})();
