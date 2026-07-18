# Reading Progress TOC

Reading Progress TOC is a lightweight WordPress content enhancement plugin for blogs, documentation, and long-form pages.

## Features

- Fixed reading progress bar on enabled singular content.
- Auto-generated table of contents from page headings.
- Smooth anchor navigation.
- Active heading highlight while scrolling.
- Per-post enable/disable checkbox in the editor sidebar.
- Dynamic Gutenberg block: `reading-progress-toc/table-of-contents`.
- Shortcodes: `[reading_progress_toc]` and `[content_toc]`.
- Theme-safe CSS scoped with the `rptoc-` prefix and no third-party dependencies.

## Shortcode examples

```text
[reading_progress_toc]
[reading_progress_toc title="Article sections" min_level="2" max_level="4"]
[content_toc selector=".entry-content" placeholder="Add headings to generate a table of contents."]
```

## Compatibility notes

- The frontend script adds missing heading IDs only inside the configured content selector.
- Per-post disabling is useful for landing pages, full-page builders, or custom layouts where generated anchors are not desired.
- The plugin uses WordPress hooks, shortcodes, post meta, dynamic blocks, and enqueued assets; it does not modify themes or core files.
