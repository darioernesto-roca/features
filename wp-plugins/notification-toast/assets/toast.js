(function () {
  const regionSelector = '[data-ntp-region]';

  const getRegion = () => {
    let region = document.querySelector(regionSelector);

    if (!region) {
      region = document.createElement('div');
      region.className = 'ntp-toast-region';
      region.setAttribute('data-ntp-region', '');
      region.setAttribute('aria-live', 'polite');
      region.setAttribute('aria-atomic', 'true');
      document.body.appendChild(region);
    }

    return region;
  };

  const storageKey = (notice) => `ntp-dismissed-${notice.id}`;

  const hasCookie = (name) => document.cookie.split('; ').some((cookie) => cookie.indexOf(`${name}=`) === 0);

  const setCookie = (name) => {
    document.cookie = `${name}=1; path=/; max-age=2592000; SameSite=Lax`;
  };

  const shouldShow = (notice) => {
    if (!notice || !notice.message) {
      return false;
    }

    const key = storageKey(notice);

    if (notice.rule === 'session') {
      return window.sessionStorage.getItem(key) !== '1';
    }

    if (notice.rule === 'cookie') {
      return !hasCookie(key);
    }

    return true;
  };

  const rememberDismissal = (notice) => {
    const key = storageKey(notice);

    if (notice.rule === 'session') {
      window.sessionStorage.setItem(key, '1');
    }

    if (notice.rule === 'cookie') {
      setCookie(key);
    }
  };

  const dismiss = (toast, notice) => {
    rememberDismissal(notice);
    toast.classList.add('is-leaving');
    window.setTimeout(() => toast.remove(), 180);
  };

  const show = (notice) => {
    if (!shouldShow(notice)) {
      return;
    }

    const region = getRegion();
    const toast = document.createElement('div');
    const close = document.createElement('button');
    const message = document.createElement('span');

    toast.className = `ntp-toast ntp-toast--${notice.type || 'info'}`;
    toast.setAttribute('role', notice.type === 'error' ? 'alert' : 'status');
    message.textContent = notice.message;
    close.type = 'button';
    close.className = 'ntp-toast__close';
    close.setAttribute('aria-label', 'Dismiss notification');
    close.textContent = '×';
    close.addEventListener('click', () => dismiss(toast, notice));

    toast.appendChild(message);
    toast.appendChild(close);
    region.appendChild(toast);

    if (notice.duration && Number(notice.duration) > 0) {
      window.setTimeout(() => dismiss(toast, notice), Number(notice.duration));
    }
  };

  const parseJsonScript = (script) => {
    try {
      return JSON.parse(script.textContent || 'null');
    } catch (error) {
      return null;
    }
  };

  const boot = () => {
    const initialScript = document.getElementById('ntp-initial-notices');
    const initialNotices = initialScript ? parseJsonScript(initialScript) : [];

    if (Array.isArray(initialNotices)) {
      initialNotices.forEach(show);
    }

    document.querySelectorAll('.ntp-shortcode-notice').forEach((script) => {
      show(parseJsonScript(script));
    });

    document.querySelectorAll('[data-ntp-trigger]').forEach((button) => {
      const script = document.getElementById(button.getAttribute('data-ntp-trigger'));
      const notice = script ? parseJsonScript(script) : null;

      button.addEventListener('click', () => show(notice));
    });
  };

  window.NotificationToast = {
    show,
  };

  window.addEventListener('ntp:show', (event) => show(event.detail));

  if (window.jQuery) {
    window.jQuery(document.body).on('added_to_cart', () => {
      show({ id: 'woocommerce-added-to-cart', message: 'Product added to cart.', type: 'success', rule: 'session', duration: 5000 });
    });

    window.jQuery(document.body).on('checkout_error', () => {
      show({ id: 'woocommerce-checkout-error', message: 'Please review the checkout errors.', type: 'error', rule: 'always', duration: 7000 });
    });
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', boot);
  } else {
    boot();
  }
})();
