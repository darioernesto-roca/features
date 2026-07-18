# Micro Survey Feedback

Micro Survey Feedback is a lightweight WordPress plugin for collecting page-level reactions without an external analytics service.

## Features

- Emoji reaction buttons and optional 1–5 rating capture.
- Optional comment field with front-end validation and AJAX submission.
- Automatic page/post association using the current singular post ID and URL.
- Admin results table under **Tools → Micro Survey Feedback**.
- Dynamic Gutenberg block: `micro-survey-feedback/widget`.
- Shortcodes: `[micro_survey_feedback]` and `[feedback_widget]`.
- Optional thank-you toast integration when the Notification Toast plugin is active.
- Stores responses in a private WordPress post type instead of adding custom database tables.

## Shortcode examples

```text
[micro_survey_feedback]
[micro_survey_feedback title="Was this article useful?" comment="false"]
[feedback_widget toast="true" thankYou="Thanks for helping us improve."]
```

## Compatibility notes

- No third-party scripts or external analytics are loaded.
- Assets are scoped with the `msfw-` prefix to reduce theme and page-builder conflicts.
- The thank-you toast is optional and gracefully falls back to an inline status message.
- The plugin uses WordPress AJAX, shortcodes, post meta, and a dynamic block; it does not modify themes or core files.
