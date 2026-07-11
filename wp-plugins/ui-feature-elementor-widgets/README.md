# UI Feature Elementor Widgets

A lightweight WordPress plugin that adapts selected UI Feature Gallery patterns into custom Elementor widgets.

## Included Widgets

- **UI FAQ Accordion**: accessible disclosure-style FAQ content.
- **UI Pricing Card**: a scoped pricing card with optional CTA link.
- **UI Testimonial**: a styled testimonial card for social proof sections.

## Installation

1. Copy the `ui-feature-elementor-widgets` folder into `wp-content/plugins/`.
2. Install and activate Elementor if it is not already active.
3. Activate **UI Feature Elementor Widgets** in the WordPress admin.
4. Add widgets from the Elementor panel's **UI Feature Gallery** category.

## Compatibility Notes

- The plugin registers widgets only through Elementor hooks and does not modify Elementor, WordPress core, or theme files.
- Styles are enqueued as an Elementor widget dependency and scoped with `ufe-widget-*` class names.
- The plugin shows an admin notice instead of causing a fatal error when Elementor is inactive.
- No third-party dependencies are bundled or required beyond Elementor.
