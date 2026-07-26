# Content Reveal Spoiler

An accessible, dependency-free WordPress plugin for hiding optional details, answers, hints, and spoilers until a visitor chooses to reveal them.

## Features

- Dynamic Gutenberg block and enclosing shortcodes.
- Hidden-panel and blurred-spoiler presentations.
- Accessible buttons with `aria-expanded` and `aria-controls` state.
- Custom reveal, hide, and assistive-context labels.
- Initially-open and one-way reveal options.
- Nested shortcode support and WordPress-safe content sanitization.
- Scoped CSS and vanilla JavaScript with reduced-motion support.

## Shortcodes

```text
[content_reveal label="Show answer" hide_label="Hide answer"]The answer is 42.[/content_reveal]

[spoiler label="Reveal spoiler" style="blur" one_way="true"]The hidden ending.[/spoiler]
```

Supported attributes are `label`, `hide_label`, `screen_label`, `style` (`panel` or `blur`), `open`, and `one_way`.

## Installation

1. Copy `content-reveal-spoiler` into `wp-content/plugins/`.
2. Activate **Content Reveal Spoiler** in WordPress.
3. Add the **Content Reveal / Spoiler** block or either shortcode to content.

## Compatibility

The plugin does not modify themes or WordPress core, has no third-party dependencies, and only enqueues frontend assets when a reveal is rendered. Its `crs-` selectors are scoped to reduce conflicts with Gutenberg, Elementor, Avada, WooCommerce, and caching plugins such as WP Rocket.
