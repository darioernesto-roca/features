# Accessible Tabs and Accordion Blocks

A dependency-free WordPress plugin providing accessible tabs and accordion blocks for the block editor.

## Features

- Horizontal and vertical tabs with responsive mobile behavior.
- Arrow key, Home, and End navigation following the WAI-ARIA tabs pattern.
- Shareable tab links using URL fragments such as `#atab-shipping`.
- Single-open and multiple-open accordion modes.
- Configurable accordion heading levels for a logical document outline.
- Optional FAQPage structured data with an editor reminder to use it only for genuine FAQs.
- Editable, reorderable sections with rich-text content.
- Server-rendered, sanitized markup with unique ARIA relationships.
- Scoped CSS, vanilla JavaScript, and reduced-motion support.

## Installation

1. Copy `accessible-tabs-accordion-blocks` into `wp-content/plugins/`.
2. Activate **Accessible Tabs and Accordion Blocks** in WordPress.
3. Add an **Accessible Tabs** or **Accessible Accordion** block in the editor.

## Usage

Use the block toolbar and sidebar to add, remove, reorder, and configure sections. Tab labels determine their shareable URL fragment. For example, a tab named **Shipping Details** can be opened with `#atab-shipping-details`.

The FAQ structured-data option is disabled by default. Enable it only when each accordion heading is a question with a directly visible answer and the content complies with current search-engine guidelines.

## Compatibility

The plugin does not modify WordPress core or theme templates and has no third-party dependencies. Frontend selectors use an `atab-` prefix to reduce conflicts with Gutenberg, Elementor, Avada, WooCommerce, and caching or optimization plugins such as WP Rocket. Assets are registered through WordPress APIs and are only enqueued on pages where a block is rendered.

## Accessibility Notes

Tabs expose `tablist`, `tab`, and `tabpanel` roles with managed focus and selection state. Accordions use native buttons with `aria-expanded`, `aria-controls`, labelled regions, and optional arrow-key navigation. Authors should keep labels concise and choose the accordion heading level that preserves the page's logical document outline.
