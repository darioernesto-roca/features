const focusableSelectors = [
    'a[href]',
    'button:not([disabled])',
    'textarea:not([disabled])',
    'input:not([disabled])',
    'select:not([disabled])',
    '[tabindex]:not([tabindex="-1"])'
].join(',');

let activeOverlay = null;
let restoreFocusTo = null;

const overlays = Array.from(document.querySelectorAll('.overlay'));
const openButtons = Array.from(document.querySelectorAll('[data-open]'));

function getFocusable(overlay) {
    return Array.from(overlay.querySelectorAll(focusableSelectors));
}

function openOverlay(id, trigger) {
    const overlay = document.getElementById(id);
    if (!overlay) {
        return;
    }

    if (activeOverlay) {
        closeOverlay(activeOverlay);
    }

    activeOverlay = overlay;
    restoreFocusTo = trigger || document.activeElement;

    overlay.classList.add('is-open');
    overlay.setAttribute('aria-hidden', 'false');
    document.body.classList.add('modal-open');

    const focusable = getFocusable(overlay);
    (focusable[0] || overlay.querySelector('.surface')).focus();
}

function closeOverlay(overlay) {
    overlay.classList.remove('is-open');
    overlay.setAttribute('aria-hidden', 'true');

    if (activeOverlay === overlay) {
        activeOverlay = null;
        document.body.classList.remove('modal-open');
    }

    if (restoreFocusTo) {
        restoreFocusTo.focus();
    }
}

openButtons.forEach((button) => {
    button.addEventListener('click', () => {
        openOverlay(button.dataset.open, button);
    });
});

overlays.forEach((overlay) => {
    overlay.addEventListener('click', (event) => {
        if (event.target === overlay) {
            closeOverlay(overlay);
        }
    });

    overlay.querySelectorAll('[data-close]').forEach((closeButton) => {
        closeButton.addEventListener('click', () => closeOverlay(overlay));
    });
});

document.addEventListener('keydown', (event) => {
    if (!activeOverlay) {
        return;
    }

    if (event.key === 'Escape') {
        event.preventDefault();
        closeOverlay(activeOverlay);
        return;
    }

    if (event.key !== 'Tab') {
        return;
    }

    const focusable = getFocusable(activeOverlay);
    if (!focusable.length) {
        return;
    }

    const first = focusable[0];
    const last = focusable[focusable.length - 1];

    if (event.shiftKey && document.activeElement === first) {
        event.preventDefault();
        last.focus();
    } else if (!event.shiftKey && document.activeElement === last) {
        event.preventDefault();
        first.focus();
    }
});
