# Accessible Modal Drawer

A focused WordPress plugin for accessibility-first modals, drawers, and side panels with shortcode and Gutenberg block triggers.

## Features

- Focus trap while a dialog is open.
- ESC-to-close behavior.
- Backdrop click close behavior.
- ARIA `role="dialog"`, `aria-modal`, and labelled title markup.
- Trigger buttons through shortcode or the **Accessible Modal / Drawer** block.
- Newsletter, CTA, announcement, and custom content templates.

## Shortcode Usage

```text
[amd_dialog id="newsletter" type="modal" template="newsletter" title="Join the newsletter" button_text="Subscribe"]
Get updates and product notes delivered to your inbox.
[/amd_dialog]

[accessible_modal id="promo-panel" type="side-panel" template="cta" title="Book a demo" button_text="Open side panel"]
Use this panel for a focused call to action.
[/accessible_modal]
```

## Block Usage

1. Activate **Accessible Modal Drawer** in WordPress.
2. Insert the **Accessible Modal / Drawer** block from the block editor.
3. Choose a layout type: modal, drawer, or side panel.
4. Pick a template or add custom content.

## Compatibility Notes

- The plugin uses WordPress shortcodes, dynamic block registration, and enqueued assets only.
- Styles and scripts are scoped with `amd-*` selectors to reduce conflicts with themes, Elementor, Avada, WooCommerce, and WP Rocket.
- No third-party dependencies are bundled or required.
