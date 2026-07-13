# Testimonial Carousel

A dedicated WordPress testimonial slider plugin with testimonial content management, ratings, images/logos, controls, schema, shortcode output, and Gutenberg block output.

## Features

- Custom post type: **Testimonials** (`tc_testimonial`).
- Star rating meta box.
- Client role and company/logo-name fields.
- Featured image support for client images or logos.
- Autoplay controls with configurable interval.
- Previous/next arrows and dot navigation.
- Schema.org `Review` JSON-LD markup.
- Dynamic Gutenberg block output.
- Shortcode output for classic editor, widgets, templates, and page builders.

## Shortcodes

```text
[testimonial_carousel]
[tc_testimonial_carousel title="Customer stories" limit="6" autoplay="true" interval="5000" schema="true" schema_item_name="Example Company"]
```

## Block Usage

1. Add testimonial posts from **Testimonials** in the WordPress admin.
2. Add the testimonial quote in the editor body.
3. Set a star rating, client role, company/logo name, and optional featured image.
4. Insert the **Testimonial Carousel** block and configure title, limit, autoplay, interval, and schema output.

## Compatibility Notes

- The plugin uses WordPress custom post types, post meta, shortcodes, dynamic block registration, and enqueued assets only.
- Styles and scripts are scoped with `tc-*` selectors to reduce conflicts with themes, Elementor, Avada, WooCommerce, and WP Rocket.
- Rewrite rules are flushed only on plugin activation/deactivation.
- No third-party dependencies are bundled or required.
