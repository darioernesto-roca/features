# UI Feature Shortcodes

A lightweight WordPress plugin that exposes selected UI Feature Gallery patterns as shortcodes for classic editor content, widgets, page builders, and theme templates.

## Included Shortcodes

- `[ui_pricing_table]`
- `[ui_faq_accordion]`
- `[ui_testimonial_carousel]`
- `[ui_timeline]`
- `[ui_modal id="newsletter"]`
- `[ui_toast type="success"]`

## Example Usage

```text
[ui_pricing_table plan="Starter" price="$19" features="Responsive layout|Scoped styles|No builder lock-in"]
[ui_faq_accordion question="Can I use this in Elementor?"]Yes, shortcodes work in many page-builder shortcode widgets.[/ui_faq_accordion]
[ui_testimonial_carousel quotes="Great support.|Easy to reuse." names="Alex|Jamie" roles="Founder|Editor"]
[ui_timeline items="Discovery|Planning|Launch" text="Review goals.|Map the steps.|Publish the page."]
[ui_modal id="newsletter" button_text="Join the list" title="Newsletter"]Signup form content goes here.[/ui_modal]
[ui_toast type="info" message="This is a reusable notice."]
```

## Compatibility Notes

- Shortcodes are registered through WordPress APIs and do not modify core, themes, Elementor, Avada, WooCommerce, or WP Rocket.
- Styles and scripts are scoped with `uisc-*` selectors to reduce conflicts.
- No third-party dependencies are bundled or required.
- Assets are lightweight and shared across all included shortcode components.
