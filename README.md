# UI Feature Gallery

A collection of standalone HTML/CSS prototypes showcasing modern UI patterns, animation techniques, and layout experiments. Each folder is a self-contained demo that you can open directly in a browser, and `index.html` provides a modern entry page that links all demos.

## Contents

| Feature | Description | Entry Point |
| --- | --- | --- |
| **Accessible Modal Variants** | Three accessible overlay patterns (center modal, slide-up drawer, side panel) with keyboard focus trap, ESC close, and backdrop close behavior. | [`accessible-modal-variants/index.html`](accessible-modal-variants/index.html) |
| **Button Rotating Border Glow Effect** | A call-to-action button with a conic-gradient border that animates via a custom CSS `@property` and keyframes, creating a rotating glow around the button. | [`button-rotating-border-glow-effect/index.html`](button-rotating-border-glow-effect/index.html) |
| **Command Palette UI** | A spotlight-style quick action/search modal with keyboard-first interactions: Ctrl/⌘+K toggle, arrow navigation, Enter execute, ESC close, and backdrop close. | [`command-palette-ui/index.html`](command-palette-ui/index.html) |
| **Comparison Table (Sticky Header/First Column)** | A SaaS-style feature matrix with sticky table header and first column, row/column hover highlight, and responsive card fallback on small screens. | [`comparison-table-sticky/index.html`](comparison-table-sticky/index.html) |
| **Cards with Inverted Border Radius** | A three-card layout with image covers, inset icon corner treatment, and tag chips; showcases a stylized “inverted” border-radius effect for the corner badge. | [`cards-with-inverted-border-radius/index.html`](cards-with-inverted-border-radius/index.html) |
| **FAQ Accordion Variants** | Clean FAQ accordions with animated plus/minus icon transitions and a nested, sectioned FAQ layout for grouped content. | [`faq-accordion-variants/index.html`](faq-accordion-variants/index.html) |
| **Fluid Grid Gallery** | A multi-column image gallery where hovering an item expands its row height within a column using `:has()` to drive grid row resizing. | [`fluid-grid-gallery/index.html`](fluid-grid-gallery/index.html) |
| **Fluid Grid Gallery v2** | An enhanced gallery that expands both columns and rows on hover for a more dynamic masonry-like effect, with hover transitions and desaturated images that colorize on hover. | [`fluid-grid-gallery-2/index.html`](fluid-grid-gallery-2/index.html) |
| **Image Carousel 3D** | A 3D rotating carousel using CSS transforms, perspective, and keyframe animation, with left/right controls to switch rotation direction. | [`image-carousel-3d/index.html`](image-carousel-3d/index.html) |
| **Mega Menu / Dropdown Navigation** | Multi-column dropdown navigation with icons, promo block, and mobile collapse behavior. | [`mega-menu-dropdown-navigation/index.html`](mega-menu-dropdown-navigation/index.html) |
| **Pricing Table with Toggle** | A pricing section with monthly/yearly switch, a “Most Popular” plan ribbon, and feature comparison rows. | [`pricing-table-toggle/index.html`](pricing-table-toggle/index.html) |
| **Scroll-driven Animation** | Scroll-linked progress bar and card entrance animations using `animation-timeline: scroll()` and `animation-timeline: view()` for scroll-driven effects. | [`scroll-driven-animation/index.html`](scroll-driven-animation/index.html) |
| **Skeleton Loading Patterns** | Reusable skeleton modules (card, profile, table row, dashboard) with shimmer animation styling for loading states. | [`skeleton-loading-patterns/index.html`](skeleton-loading-patterns/index.html) |
| **Timeline / Stepper Components** | Vertical timeline plus horizontal checkout stepper with progress and completed states. | [`timeline-stepper-components/index.html`](timeline-stepper-components/index.html) |
| **Toast Notification Stack** | Success, error, and info toasts with timed dismissal plus animated entrance/exit transitions. | [`toast-notification-stack/index.html`](toast-notification-stack/index.html) |
| **Testimonial Carousel** | A responsive testimonial slider with auto-play, previous/next controls, dot navigation, and pointer swipe-ready interactions. | [`testimonial-carousel/index.html`](testimonial-carousel/index.html) |
| **Table Column Hover** | A data table that highlights the active column and row on hover using the CSS `:has()` selector for column targeting. | [`table-column/table-column-hover.html`](table-column/table-column-hover.html) |

## WordPress Plugins

This repository can also include WordPress plugins that adapt selected static UI prototypes into reusable WordPress features. Plugin projects live in `wp-plugins/` and should preserve compatibility with themes, Gutenberg, Elementor, Avada, WooCommerce, and performance plugins by using scoped assets and WordPress hooks instead of modifying core files.

| Plugin | Description | Entry Point |
| --- | --- | --- |
| **UI Feature Blocks** | A lightweight Gutenberg block plugin with FAQ Accordion, Pricing Card, and Testimonial blocks adapted from the UI Feature Gallery patterns. | [`wp-plugins/ui-feature-blocks/ui-feature-blocks.php`](wp-plugins/ui-feature-blocks/ui-feature-blocks.php) |
| **UI Feature Elementor Widgets** | A lightweight Elementor widget pack with FAQ Accordion, Pricing Card, and Testimonial widgets adapted from the UI Feature Gallery patterns. | [`wp-plugins/ui-feature-elementor-widgets/ui-feature-elementor-widgets.php`](wp-plugins/ui-feature-elementor-widgets/ui-feature-elementor-widgets.php) |
| **UI Feature Shortcodes** | A simple shortcode plugin exposing pricing, FAQ, testimonial, timeline, modal, and toast UI components for classic editor, widgets, templates, and page builders. | [`wp-plugins/ui-feature-shortcodes/ui-feature-shortcodes.php`](wp-plugins/ui-feature-shortcodes/ui-feature-shortcodes.php) |
| **Accessible Modal Drawer** | An accessibility-first modal, drawer, and side-panel plugin with focus trap, ESC/backdrop close, ARIA labels, and shortcode/block triggers. | [`wp-plugins/accessible-modal-drawer/accessible-modal-drawer.php`](wp-plugins/accessible-modal-drawer/accessible-modal-drawer.php) |
| **Advanced FAQ Help Center** | A full FAQ plugin with a custom FAQ post type, categories, accordion layouts, search/filter UI, FAQPage schema, shortcode output, and a Gutenberg block. | [`wp-plugins/advanced-faq-help-center/advanced-faq-help-center.php`](wp-plugins/advanced-faq-help-center/advanced-faq-help-center.php) |

### WordPress Plugin Guidelines

- Keep plugins self-contained under `wp-plugins/<plugin-name>/`.
- Scope CSS and JavaScript to plugin-specific classes to avoid theme and page-builder conflicts.
- Prefer WordPress hooks, filters, block registration APIs, and enqueued assets over core or theme modifications.
- Avoid unnecessary dependencies; document any dependency before adding it.
- Run PHP syntax checks before committing plugin changes.

## How to View the Demos

These are static files—no build step required.

1. Open `index.html` in the repository root to browse all features from one modern hub page.
2. Click any feature card to navigate to its standalone module.
3. Interact with the page (hover, scroll, or click) to see the effect.

### Optional: Local Server
If you prefer running a local server (e.g., for relative assets), you can use:

```bash
python3 -m http.server
```

Then navigate to `http://localhost:8000/<feature-folder>/index.html`.

## Notes

- Each demo is intentionally minimal and self-contained.
- Many demos reference external fonts or images from public CDNs.

## Credits

These demos are inspired by or adapted from public CodePen examples, which are linked within each demo’s HTML.
