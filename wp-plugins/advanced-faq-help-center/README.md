# Advanced FAQ Help Center

A full WordPress FAQ plugin that turns accordion patterns into a searchable, categorized help center with structured data.

## Features

- Custom post type: **FAQs** (`afhc_faq`).
- Hierarchical FAQ categories (`afhc_faq_category`).
- Accordion output with grouped or single-list layouts.
- Search and category filter UI.
- Schema.org `FAQPage` JSON-LD structured data.
- Dynamic Gutenberg block output.
- Shortcode output for classic editor, widgets, templates, and page builders.

## Shortcodes

```text
[advanced_faq_help_center]
[faq_help_center title="Support Center" category="billing,account" limit="8" layout="grouped" search="true" schema="true"]
```

## Block Usage

1. Add FAQ posts from **FAQs** in the WordPress admin.
2. Assign FAQ categories as needed.
3. Insert the **Advanced FAQ Help Center** block.
4. Configure title, description, category slugs, limit, layout, search/filter UI, and schema output.

## Compatibility Notes

- The plugin uses WordPress custom post types, taxonomies, shortcodes, dynamic block registration, and enqueued assets only.
- Styles and scripts are scoped with `afhc-*` selectors to reduce conflicts with themes, Elementor, Avada, WooCommerce, and WP Rocket.
- Rewrite rules are flushed only on plugin activation/deactivation.
- No third-party dependencies are bundled or required.
