(function () {
  'use strict';

  const getButtons = (component, selector) => Array.from(component.querySelectorAll(selector));

  const activateTab = (tabs, tab, updateHash) => {
    const buttons = getButtons(tabs, '[role="tab"]');

    buttons.forEach((button) => {
      const selected = button === tab;
      const panel = document.getElementById(button.getAttribute('aria-controls'));

      button.setAttribute('aria-selected', String(selected));
      button.tabIndex = selected ? 0 : -1;
      if (panel) {
        panel.hidden = !selected;
      }
    });

    if (updateHash && tab.dataset.atabSlug && window.history.replaceState) {
      window.history.replaceState(null, '', `#atab-${tab.dataset.atabSlug}`);
    }
  };

  const initializeTabs = (tabs) => {
    const buttons = getButtons(tabs, '[role="tab"]');
    const hashSlug = window.location.hash.replace(/^#atab-/, '');
    const linkedTab = buttons.find((button) => button.dataset.atabSlug === hashSlug);

    if (linkedTab) {
      activateTab(tabs, linkedTab, false);
    }

    tabs.addEventListener('click', (event) => {
      const tab = event.target.closest('[role="tab"]');
      if (tab && tabs.contains(tab)) {
        activateTab(tabs, tab, true);
      }
    });

    tabs.addEventListener('keydown', (event) => {
      const tab = event.target.closest('[role="tab"]');
      if (!tab) {
        return;
      }

      const orientation = tab.parentElement.getAttribute('aria-orientation');
      const previousKey = orientation === 'vertical' ? 'ArrowUp' : 'ArrowLeft';
      const nextKey = orientation === 'vertical' ? 'ArrowDown' : 'ArrowRight';
      const index = buttons.indexOf(tab);
      let nextIndex;

      if (event.key === previousKey) {
        nextIndex = (index - 1 + buttons.length) % buttons.length;
      } else if (event.key === nextKey) {
        nextIndex = (index + 1) % buttons.length;
      } else if (event.key === 'Home') {
        nextIndex = 0;
      } else if (event.key === 'End') {
        nextIndex = buttons.length - 1;
      } else {
        return;
      }

      event.preventDefault();
      activateTab(tabs, buttons[nextIndex], true);
      buttons[nextIndex].focus();
    });
  };

  const initializeAccordion = (accordion) => {
    const buttons = getButtons(accordion, '.atab-accordion__trigger');

    accordion.addEventListener('click', (event) => {
      const trigger = event.target.closest('.atab-accordion__trigger');
      if (!trigger || !accordion.contains(trigger)) {
        return;
      }

      const willOpen = trigger.getAttribute('aria-expanded') !== 'true';

      if (willOpen && !accordion.hasAttribute('data-atab-multiple')) {
        buttons.forEach((button) => {
          const panel = document.getElementById(button.getAttribute('aria-controls'));
          button.setAttribute('aria-expanded', 'false');
          if (panel) {
            panel.hidden = true;
          }
        });
      }

      const panel = document.getElementById(trigger.getAttribute('aria-controls'));
      trigger.setAttribute('aria-expanded', String(willOpen));
      if (panel) {
        panel.hidden = !willOpen;
      }
    });

    accordion.addEventListener('keydown', (event) => {
      const trigger = event.target.closest('.atab-accordion__trigger');
      const index = buttons.indexOf(trigger);
      let nextIndex;

      if (index < 0) {
        return;
      }

      if (event.key === 'ArrowUp') {
        nextIndex = (index - 1 + buttons.length) % buttons.length;
      } else if (event.key === 'ArrowDown') {
        nextIndex = (index + 1) % buttons.length;
      } else if (event.key === 'Home') {
        nextIndex = 0;
      } else if (event.key === 'End') {
        nextIndex = buttons.length - 1;
      } else {
        return;
      }

      event.preventDefault();
      buttons[nextIndex].focus();
    });
  };

  document.querySelectorAll('[data-atab-tabs]').forEach(initializeTabs);
  document.querySelectorAll('[data-atab-accordion]').forEach(initializeAccordion);
})();
