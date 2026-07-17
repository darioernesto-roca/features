(function () {
  const focusableSelector = [
    'a[href]',
    'button:not([disabled])',
    'textarea:not([disabled])',
    'input:not([disabled])',
    'select:not([disabled])',
    '[tabindex]:not([tabindex="-1"])',
  ].join(',');

  let activeDialog = null;
  let lastFocusedElement = null;

  const getFocusableElements = (dialog) =>
    Array.from(dialog.querySelectorAll(focusableSelector)).filter((element) =>
      Boolean(element.offsetWidth || element.offsetHeight || element.getClientRects().length)
    );

  const closeDialog = (dialog) => {
    if (!dialog) {
      return;
    }

    dialog.hidden = true;
    document.documentElement.classList.remove('amd-dialog-is-open');
    activeDialog = null;

    if (lastFocusedElement && typeof lastFocusedElement.focus === 'function') {
      lastFocusedElement.focus();
    }
  };

  const openDialog = (dialog) => {
    if (!dialog) {
      return;
    }

    lastFocusedElement = document.activeElement;
    activeDialog = dialog;
    dialog.hidden = false;
    document.documentElement.classList.add('amd-dialog-is-open');

    const focusableElements = getFocusableElements(dialog);
    const firstFocusable = focusableElements[0] || dialog.querySelector('.amd-dialog__panel');

    if (firstFocusable && typeof firstFocusable.focus === 'function') {
      firstFocusable.focus();
    }
  };

  const trapFocus = (event) => {
    if (!activeDialog || event.key !== 'Tab') {
      return;
    }

    const focusableElements = getFocusableElements(activeDialog);

    if (!focusableElements.length) {
      event.preventDefault();
      activeDialog.querySelector('.amd-dialog__panel').focus();
      return;
    }

    const firstFocusable = focusableElements[0];
    const lastFocusable = focusableElements[focusableElements.length - 1];

    if (event.shiftKey && document.activeElement === firstFocusable) {
      event.preventDefault();
      lastFocusable.focus();
    }

    if (!event.shiftKey && document.activeElement === lastFocusable) {
      event.preventDefault();
      firstFocusable.focus();
    }
  };

  document.addEventListener('click', (event) => {
    const opener = event.target.closest('[data-amd-open]');
    const closer = event.target.closest('[data-amd-close]');

    if (opener) {
      openDialog(document.getElementById(opener.getAttribute('data-amd-open')));
    }

    if (closer) {
      closeDialog(closer.closest('.amd-dialog'));
    }
  });

  document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape') {
      closeDialog(activeDialog);
      return;
    }

    trapFocus(event);
  });
})();
