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
| **Landing Page Mockups** | A card-based gallery of full-page website mockups with stable layouts and scrollable image previews for tall designs. | [`landing-pages-mockups/index.html`](landing-pages-mockups/index.html) |
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
| **Pricing Table Builder** | A focused pricing table plugin with monthly/yearly toggles, featured ribbons, comparison rows, CTA buttons, currency settings, optional WooCommerce product links, shortcode output, and a Gutenberg block. | [`wp-plugins/pricing-table-builder/pricing-table-builder.php`](wp-plugins/pricing-table-builder/pricing-table-builder.php) |
| **Testimonial Carousel** | A dedicated testimonial slider plugin with a testimonial post type, star ratings, client images/logos, autoplay controls, arrows/dots, Review schema, shortcode output, and a Gutenberg block. | [`wp-plugins/testimonial-carousel/testimonial-carousel.php`](wp-plugins/testimonial-carousel/testimonial-carousel.php) |
| **Mega Menu Enhancer** | A theme-safe nav menu enhancer with multi-column dropdowns, icons, promo blocks, mobile collapse behavior, menu item descriptions, scoped CSS, shortcode output, and a theme helper. | [`wp-plugins/mega-menu-enhancer/mega-menu-enhancer.php`](wp-plugins/mega-menu-enhancer/mega-menu-enhancer.php) |
| **Notification Toast** | A site-wide toast notification plugin with success/error/info/warning styles, timed dismissal, cookie/session display rules, WooCommerce event integrations, an admin notice builder, shortcodes, and API triggers. | [`wp-plugins/notification-toast/notification-toast.php`](wp-plugins/notification-toast/notification-toast.php) |
| **Accessibility Utilities** | A lightweight accessibility helper plugin with skip links, focus outlines, reduced-motion CSS, external link indicators, ARIA live regions, keyboard trap utilities, and an admin audit checklist. | [`wp-plugins/accessibility-utilities/accessibility-utilities.php`](wp-plugins/accessibility-utilities/accessibility-utilities.php) |
| **Micro Survey Feedback** | A small page-level feedback plugin with emoji/rating reactions, optional comments, page/post association, admin results, shortcode/block output, optional toast thank-yous, and no external analytics dependency. | [`wp-plugins/micro-survey-feedback/micro-survey-feedback.php`](wp-plugins/micro-survey-feedback/micro-survey-feedback.php) |
| **Reading Progress TOC** | A content enhancement plugin with a reading progress bar, generated table of contents, smooth anchor navigation, active heading highlight, per-post disable controls, shortcode output, and a Gutenberg block. | [`wp-plugins/reading-progress-toc/reading-progress-toc.php`](wp-plugins/reading-progress-toc/reading-progress-toc.php) |
| **WooCommerce Product Feature Matrix** | A WooCommerce-aware comparison plugin that pulls selected product images, prices, ratings, stock, attributes, sticky headers, add-to-cart buttons, attribute filtering, shortcode output, and a Gutenberg block. | [`wp-plugins/woocommerce-product-feature-matrix/woocommerce-product-feature-matrix.php`](wp-plugins/woocommerce-product-feature-matrix/woocommerce-product-feature-matrix.php) |
| **Content Reveal Spoiler** | An accessible content reveal plugin with button and blurred spoiler styles, optional initially-open and one-way behavior, nested shortcode support, and a dynamic Gutenberg block. | [`wp-plugins/content-reveal-spoiler/content-reveal-spoiler.php`](wp-plugins/content-reveal-spoiler/content-reveal-spoiler.php) |
| **Accessible Tabs and Accordion Blocks** | Dependency-free Gutenberg tabs and accordion blocks with keyboard navigation, responsive layouts, deep links, multiple-open controls, and optional FAQ structured data. | [`wp-plugins/accessible-tabs-accordion-blocks/accessible-tabs-accordion-blocks.php`](wp-plugins/accessible-tabs-accordion-blocks/accessible-tabs-accordion-blocks.php) |

### WordPress Plugin Guidelines

- Keep plugins self-contained under `wp-plugins/<plugin-name>/`.
- Scope CSS and JavaScript to plugin-specific classes to avoid theme and page-builder conflicts.
- Prefer WordPress hooks, filters, block registration APIs, and enqueued assets over core or theme modifications.
- Avoid unnecessary dependencies; document any dependency before adding it.
- Run PHP syntax checks before committing plugin changes.

## Test the Static Features Locally

The static demos have no package dependencies or build step. From the repository root, start a local server:

```bash
python3 -m http.server 8000
```

Open <http://localhost:8000/> to use the gallery, or use the direct URLs below. Stop the server with <kbd>Ctrl</kbd>+<kbd>C</kbd>. Opening the files directly also works, but the server more closely matches normal browser hosting and avoids `file://` restrictions.

### Feature-by-feature test checklist

Use a current version of Chrome, Firefox, Safari, or Edge. Also resize the browser to a narrow mobile viewport and use the keyboard where the feature is interactive.

| Feature | Local URL | What to verify |
| --- | --- | --- |
| Accessible Modal Variants | <http://localhost:8000/accessible-modal-variants/> | Open each modal/drawer, press <kbd>Tab</kbd> to confirm focus stays inside, then close with <kbd>Esc</kbd>, the close button, and the backdrop. |
| Button Rotating Border Glow Effect | <http://localhost:8000/button-rotating-border-glow-effect/> | Confirm the border rotates smoothly and the button remains readable and clickable. |
| Command Palette UI | <http://localhost:8000/command-palette-ui/> | Open with <kbd>Ctrl</kbd>/<kbd>⌘</kbd>+<kbd>K</kbd>, navigate with arrow keys, select with <kbd>Enter</kbd>, and close with <kbd>Esc</kbd>. |
| Comparison Table | <http://localhost:8000/comparison-table-sticky/> | Scroll both ways to check the sticky header/first column and hover highlights; verify the mobile card layout. |
| Cards with Inverted Border Radius | <http://localhost:8000/cards-with-inverted-border-radius/> | Check image crops, inset icon corners, and tag wrapping at desktop and mobile widths. |
| FAQ Accordion Variants | <http://localhost:8000/faq-accordion-variants/> | Expand and collapse every question, including nested groups; confirm icon and panel transitions. |
| Fluid Grid Gallery | <http://localhost:8000/fluid-grid-gallery/> | Hover every image and confirm its row expands without overlap or layout overflow. |
| Fluid Grid Gallery v2 | <http://localhost:8000/fluid-grid-gallery-2/> | Hover images and confirm row/column expansion, color transition, and stable surrounding layout. |
| Image Carousel 3D | <http://localhost:8000/image-carousel-3d/> | Use both direction controls and confirm the carousel changes rotation direction without visual clipping. |
| Landing Page Mockups | <http://localhost:8000/landing-pages-mockups/> | Scroll each tall preview independently and confirm cards remain aligned and responsive. |
| Mega Menu / Dropdown Navigation | <http://localhost:8000/mega-menu-dropdown-navigation/> | Open each desktop dropdown, then test the collapsed navigation and submenu controls at a mobile width. |
| Pricing Table with Toggle | <http://localhost:8000/pricing-table-toggle/> | Switch monthly/yearly billing and confirm all prices, the featured ribbon, and comparison rows update correctly. |
| Scroll-driven Animation | <http://localhost:8000/scroll-driven-animation/> | Scroll the full page and confirm the progress indicator and card entrances track the scroll position. |
| Skeleton Loading Patterns | <http://localhost:8000/skeleton-loading-patterns/> | Confirm each skeleton shape renders and its shimmer remains contained at desktop and mobile widths. |
| Timeline / Stepper Components | <http://localhost:8000/timeline-stepper-components/> | Check completed/current states and verify the vertical timeline and horizontal stepper do not overflow. |
| Toast Notification Stack | <http://localhost:8000/toast-notification-stack/> | Trigger every toast type and confirm stacking, timed dismissal, and enter/exit animations. |
| Testimonial Carousel | <http://localhost:8000/testimonial-carousel/> | Test previous/next buttons, dots, autoplay, and pointer swipes; confirm the active slide stays synchronized. |
| Table Column Hover | <http://localhost:8000/table-column/table-column-hover.html> | Hover cells in every column and confirm the correct row and column are highlighted. |

Some demos load public fonts or images from CDNs, so those assets require an internet connection even though the repository itself needs no install step. Modern CSS features such as `:has()`, `@property`, and scroll-driven animations may look different in older browsers.

## Test the WordPress Plugins Locally

Use an existing local WordPress installation so the plugins can be tested against its active theme and normal WordPress APIs. No Composer, Node, or database migration step is required by this repository.

1. Copy or symlink the plugin you want to test into the local site's `wp-content/plugins/` directory. For example:

   ```bash
   ln -s "$(pwd)/wp-plugins/content-reveal-spoiler" /path/to/wordpress/wp-content/plugins/content-reveal-spoiler
   ```

2. In **WordPress Admin → Plugins**, activate that plugin. Activate Elementor or WooCommerce first only when the test below calls for it.
3. Create a draft page, add the listed block or shortcode, publish/preview it, and run the verification steps.
4. Check the browser console for JavaScript errors and test once with a default WordPress theme and once with the project's target theme/page builder when compatibility matters.
5. After testing, deactivate the plugin and remove the symlink (or copied folder). Do not delete the source folder in this repository.

> **Tip:** Each plugin's linked README documents its complete shortcode attributes, APIs, and compatibility notes. The examples below are intentionally the shortest useful smoke tests.

### Plugin-by-plugin test checklist

| Plugin | Minimal local test | What to verify |
| --- | --- | --- |
| UI Feature Blocks | Insert **UI FAQ Accordion**, **UI Pricing Card**, and **UI Testimonial** blocks. | Save and reload the editor, then view the page and confirm all content and scoped styles match the editor settings. |
| UI Feature Elementor Widgets | With Elementor active, add all three widgets from **UI Feature Gallery**. | Confirm controls update the preview and frontend; deactivate Elementor and confirm the plugin shows an admin notice rather than a fatal error. |
| UI Feature Shortcodes | Add `[ui_pricing_table]`, `[ui_faq_accordion question="Test?"]Yes.[/ui_faq_accordion]`, `[ui_testimonial_carousel]`, `[ui_timeline]`, `[ui_modal]`, and `[ui_toast]`. | Exercise each interactive component and confirm multiple shortcodes can coexist without style or script conflicts. |
| Accessible Modal Drawer | Add `[amd_dialog id="local-test" title="Local test"]Dialog content.[/amd_dialog]`. | Open it using mouse and keyboard; check focus trapping, ARIA state, <kbd>Esc</kbd>, backdrop close, and focus return. |
| Advanced FAQ Help Center | Create two **FAQs**, assign categories, and add `[advanced_faq_help_center]`. | Search, filter, and expand answers; if schema is enabled, confirm one valid `FAQPage` JSON-LD script is output. |
| Pricing Table Builder | Add `[ptb_pricing_table plans="Starter|Pro" monthly_prices="10|20" yearly_prices="100|200" featured="1"]`. | Toggle billing, verify both price sets and the featured plan, and test CTA links. |
| Testimonial Carousel | Create at least three **Testimonials**, then add `[testimonial_carousel]`. | Test arrows, dots, autoplay, ratings/images, responsive layout, and pause/focus behavior. |
| Mega Menu Enhancer | Create a menu with parent/child items and add `[mme_menu menu="Main Menu" columns="3"]`. | Test desktop dropdowns, descriptions/icons, keyboard access, and mobile submenu toggles. |
| Notification Toast | Add `[toast_notice trigger="button" label="Show toast" message="Local test" type="success" rule="always"]`. | Trigger and dismiss it; verify timing, focus behavior, and the configured session/cookie display rules. |
| Accessibility Utilities | Activate it and open **Settings → Accessibility Utilities**. | Test the skip link and visible keyboard focus, enable reduced motion at OS level, and exercise the audit checklist. |
| Micro Survey Feedback | Add `[micro_survey_feedback]` to a post. | Submit a reaction, rating, and comment; confirm validation/status feedback and the saved result under **Tools → Micro Survey Feedback**. |
| Reading Progress TOC | Create a long post with H2–H4 headings and add `[reading_progress_toc]`. | Scroll and use TOC links; verify progress, active-heading state, smooth anchors, unique heading IDs, and the per-post disable option. |
| WooCommerce Product Feature Matrix | With WooCommerce active, create products with shared attributes and add `[wc_product_feature_matrix products="12,34"]` using real local IDs. | Verify product data, sticky areas, attribute filters, responsive overflow, and add-to-cart behavior. |
| Content Reveal Spoiler | Add `[content_reveal label="Show answer"]The answer is 42.[/content_reveal]` and a blurred `[spoiler]`. | Toggle with mouse and keyboard; confirm labels/ARIA state, one-way and initially-open options, nesting, and reduced motion. |
| Accessible Tabs and Accordion Blocks | Insert both **Accessible Tabs** and **Accessible Accordion** blocks with several sections. | Test arrow/Home/End tab navigation, accordion modes, responsive layout, focus states, and a direct `#atab-...` URL. |

For focused setup and usage details, open the README inside the relevant `wp-plugins/<plugin-name>/` directory.

## Notes

- Each demo is intentionally minimal and self-contained.
- Many demos reference external fonts or images from public CDNs.

## Credits

These demos are inspired by or adapted from public CodePen examples, which are linked within each demo’s HTML.
