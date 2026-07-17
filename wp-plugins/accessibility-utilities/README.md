# Accessibility Utilities

A lightweight WordPress accessibility helper plugin for sites using the UI Feature Gallery components.

## Features

- Skip link injector through `wp_body_open`.
- Focus outline helper using `:focus-visible`.
- Reduced-motion stylesheet for users who prefer less motion.
- External link indicators with accessible labels.
- ARIA live region helper for dynamic announcements.
- Keyboard focus trap utility for developers.
- Admin audit checklist under **Settings → Accessibility Utilities**.

## PHP API

```php
accessibility_utilities_announce( 'Cart updated.', 'polite' );
```

## JavaScript API

```js
window.AccessibilityUtilities.announce('Saved successfully.', 'polite');

const cleanupTrap = window.AccessibilityUtilities.trapFocus(dialogElement);
cleanupTrap();
```

## Admin Checklist

Use the checklist to track manual accessibility checks such as keyboard navigation, visible focus, heading order, image alt text, contrast, reduced motion, form labels, and live regions.

## Compatibility Notes

- The plugin uses WordPress hooks, settings, enqueued assets, and small helper APIs only.
- Styles and scripts are scoped with `au-*` selectors where possible.
- No third-party dependencies are bundled or required.
