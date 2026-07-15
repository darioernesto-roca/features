# Notification Toast

A site-wide WordPress toast notification plugin with admin-built notices, shortcodes, JavaScript/PHP API triggers, display rules, and WooCommerce event integrations.

## Features

- Success, error, info, and warning toast styles.
- Timed dismissal with configurable duration.
- Display rules: always, once per session, or once per cookie period.
- Admin notice builder under **Settings → Notification Toast**.
- WooCommerce integration for add-to-cart and checkout error events.
- Shortcode output for auto-triggered or button-triggered toasts.
- PHP helper API: `notification_toast_trigger()`.
- JavaScript API: `window.NotificationToast.show()` and `ntp:show` custom events.

## Shortcodes

```text
[notification_toast message="Saved successfully." type="success" rule="session" duration="5000"]
[toast_notice trigger="button" label="Show warning" message="Please review this notice." type="warning" rule="always"]
```

## PHP API

```php
notification_toast_trigger(
    'Product added to cart.',
    'success',
    array(
        'rule'     => 'session',
        'duration' => 5000,
    )
);
```

## JavaScript API

```js
window.NotificationToast.show({
  id: 'example-notice',
  message: 'This was triggered by JavaScript.',
  type: 'info',
  rule: 'always',
  duration: 6000,
});
```

## Compatibility Notes

- The plugin uses WordPress settings, shortcodes, hooks, and enqueued assets only.
- WooCommerce integrations are optional and only run when WooCommerce fires compatible hooks/events.
- Styles and scripts are scoped with `ntp-*` selectors to reduce conflicts with themes, Elementor, Avada, WooCommerce, and WP Rocket.
- No third-party dependencies are bundled or required.
