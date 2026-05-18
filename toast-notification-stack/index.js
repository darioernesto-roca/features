const toastConfig = {
    success: {
        title: 'Success',
        message: 'Your changes have been saved successfully.'
    },
    error: {
        title: 'Error',
        message: 'Something went wrong. Please try again.'
    },
    info: {
        title: 'Info',
        message: 'A new update is available for your workspace.'
    }
};

const TOAST_DURATION = 4200;
const EXIT_ANIMATION_MS = 200;

const stack = document.querySelector('.toast-stack');
const template = document.getElementById('toast-template');
const triggerButtons = document.querySelectorAll('[data-toast-type]');

function scheduleRemoval(toast) {
    const timeoutId = window.setTimeout(() => {
        removeToast(toast);
    }, TOAST_DURATION);

    toast.dataset.timeoutId = String(timeoutId);
}

function removeToast(toast) {
    if (!toast || toast.classList.contains('is-leaving')) {
        return;
    }

    if (toast.dataset.timeoutId) {
        window.clearTimeout(Number(toast.dataset.timeoutId));
    }

    toast.classList.add('is-leaving');

    window.setTimeout(() => {
        toast.remove();
    }, EXIT_ANIMATION_MS);
}

function createToast(type) {
    const config = toastConfig[type];
    if (!config) {
        return;
    }

    const toastFragment = template.content.cloneNode(true);
    const toast = toastFragment.querySelector('.toast');
    const title = toastFragment.querySelector('.toast-title');
    const message = toastFragment.querySelector('.toast-message');
    const closeButton = toastFragment.querySelector('.toast-close');

    toast.classList.add(`toast-${type}`);
    toast.style.setProperty('--toast-duration', `${TOAST_DURATION}ms`);

    title.textContent = config.title;
    message.textContent = config.message;

    closeButton.addEventListener('click', () => removeToast(toast));

    stack.prepend(toastFragment);
    scheduleRemoval(stack.firstElementChild);
}

triggerButtons.forEach((button) => {
    button.addEventListener('click', () => {
        createToast(button.dataset.toastType);
    });
});
