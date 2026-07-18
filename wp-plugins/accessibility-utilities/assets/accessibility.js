(function () {
  const settings = window.AccessibilityUtilitiesSettings || {};
  if (settings.focus_outline === '1') {
    document.documentElement.classList.add('au-focus-outline-enabled');
  }

  if (settings.reduced_motion === '1') {
    document.documentElement.classList.add('au-reduced-motion-enabled');
  }

  const focusableSelector = [
    'a[href]',
    'button:not([disabled])',
    'textarea:not([disabled])',
    'input:not([disabled])',
    'select:not([disabled])',
    '[tabindex]:not([tabindex="-1"])',
  ].join(',');

  const announce = (message, politeness = 'polite') => {
    const region = document.querySelector(`[data-au-live-region="${politeness === 'assertive' ? 'assertive' : 'polite'}"]`);

    if (!region) {
      return;
    }

    region.textContent = '';
    window.setTimeout(() => {
      region.textContent = message;
    }, 50);
  };

  const trapFocus = (container) => {
    if (!container) {
      return () => {};
    }

    const handleKeydown = (event) => {
      if (event.key !== 'Tab') {
        return;
      }

      const focusable = Array.from(container.querySelectorAll(focusableSelector)).filter((element) =>
        Boolean(element.offsetWidth || element.offsetHeight || element.getClientRects().length)
      );

      if (!focusable.length) {
        event.preventDefault();
        return;
      }

      const first = focusable[0];
      const last = focusable[focusable.length - 1];

      if (event.shiftKey && document.activeElement === first) {
        event.preventDefault();
        last.focus();
      }

      if (!event.shiftKey && document.activeElement === last) {
        event.preventDefault();
        first.focus();
      }
    };

    container.addEventListener('keydown', handleKeydown);

    return () => container.removeEventListener('keydown', handleKeydown);
  };

  const markExternalLinks = () => {
    if (settings.external_links !== '1') {
      return;
    }

    const currentHost = window.location.hostname;

    document.querySelectorAll('a[href^="http"]').forEach((link) => {
      let url;

      try {
        url = new URL(link.href);
      } catch (error) {
        return;
      }

      if (url.hostname === currentHost || link.closest('[data-au-ignore-external]')) {
        return;
      }

      link.setAttribute('data-au-external-link', '');

      if (!link.getAttribute('aria-label')) {
        link.setAttribute('aria-label', `${link.textContent.trim()} (opens external site)`);
      }
    });
  };

  const replayInitialAnnouncements = () => {
    const script = document.getElementById('au-initial-announcements');

    if (!script) {
      return;
    }

    try {
      const announcements = JSON.parse(script.textContent || '[]');

      if (Array.isArray(announcements)) {
        announcements.forEach((item) => announce(item.message, item.politeness));
      }
    } catch (error) {
      // Ignore malformed inline data.
    }
  };

  window.AccessibilityUtilities = {
    announce,
    trapFocus,
  };

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
      markExternalLinks();
      replayInitialAnnouncements();
    });
  } else {
    markExternalLinks();
    replayInitialAnnouncements();
  }
})();
