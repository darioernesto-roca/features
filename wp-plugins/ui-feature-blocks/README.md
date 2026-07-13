# UI Feature Blocks

A lightweight WordPress plugin that adapts selected UI Feature Gallery patterns into native Gutenberg blocks.

## Included Blocks

- **UI FAQ Accordion**: accessible disclosure-based FAQ content.
- **UI Pricing Card**: a scoped pricing card inspired by the pricing table prototype.
- **UI Testimonial**: a styled testimonial card inspired by the testimonial carousel prototype.

## Installation

1. Copy the `ui-feature-blocks` folder into `wp-content/plugins/`.
2. Activate **UI Feature Blocks** in the WordPress admin.
3. Insert the blocks from the block editor's **Design** category.

## Compatibility Notes

- The plugin uses native WordPress block registration and dynamic PHP rendering.
- Styles are scoped with `wp-block-ui-feature-*` class names to reduce theme and page-builder conflicts.
- No third-party runtime dependencies are required.
- The initial block set is intentionally small so it can grow alongside the static prototypes without changing the existing project architecture.
