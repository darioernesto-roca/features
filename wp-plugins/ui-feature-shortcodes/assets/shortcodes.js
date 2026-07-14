(function () {
  const closeModal = (modal) => {
    if (!modal) {
      return;
    }

    modal.hidden = true;
    document.documentElement.classList.remove('uisc-modal-open');
  };

  document.addEventListener('click', (event) => {
    const opener = event.target.closest('[data-uisc-modal-open]');
    const closer = event.target.closest('[data-uisc-modal-close]');
    const toastCloser = event.target.closest('[data-uisc-toast-close]');

    if (opener) {
      const modal = document.getElementById(opener.getAttribute('data-uisc-modal-open'));

      if (modal) {
        modal.hidden = false;
        document.documentElement.classList.add('uisc-modal-open');
        const closeButton = modal.querySelector('.uisc-modal__close');

        if (closeButton) {
          closeButton.focus();
        }
      }
    }

    if (closer) {
      closeModal(closer.closest('.uisc-modal'));
    }

    if (toastCloser) {
      const toast = toastCloser.closest('.uisc-toast');

      if (toast) {
        toast.remove();
      }
    }
  });

  document.addEventListener('keydown', (event) => {
    if (event.key !== 'Escape') {
      return;
    }

    document.querySelectorAll('.uisc-modal:not([hidden])').forEach(closeModal);
  });
})();
