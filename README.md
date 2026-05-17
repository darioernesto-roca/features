# UI Feature Gallery

A collection of standalone HTML/CSS prototypes showcasing modern UI patterns, animation techniques, and layout experiments. Each folder is a self-contained demo that you can open directly in a browser.

## Contents

| Feature | Description | Entry Point |
| --- | --- | --- |
| **Accessible Modal Variants** | Three accessible overlay patterns (center modal, slide-up drawer, side panel) with keyboard focus trap, ESC close, and backdrop close behavior. | [`accessible-modal-variants/index.html`](accessible-modal-variants/index.html) |
| **Button Rotating Border Glow Effect** | A call-to-action button with a conic-gradient border that animates via a custom CSS `@property` and keyframes, creating a rotating glow around the button. | [`button-rotating-border-glow-effect/index.html`](button-rotating-border-glow-effect/index.html) |
| **Cards with Inverted Border Radius** | A three-card layout with image covers, inset icon corner treatment, and tag chips; showcases a stylized “inverted” border-radius effect for the corner badge. | [`cards-with-inverted-border-radius/index.html`](cards-with-inverted-border-radius/index.html) |
| **Fluid Grid Gallery** | A multi-column image gallery where hovering an item expands its row height within a column using `:has()` to drive grid row resizing. | [`fluid-grid-gallery/index.html`](fluid-grid-gallery/index.html) |
| **Fluid Grid Gallery v2** | An enhanced gallery that expands both columns and rows on hover for a more dynamic masonry-like effect, with hover transitions and desaturated images that colorize on hover. | [`fluid-grid-gallery-2/index.html`](fluid-grid-gallery-2/index.html) |
| **Image Carousel 3D** | A 3D rotating carousel using CSS transforms, perspective, and keyframe animation, with left/right controls to switch rotation direction. | [`image-carousel-3d/index.html`](image-carousel-3d/index.html) |
| **Scroll-driven Animation** | Scroll-linked progress bar and card entrance animations using `animation-timeline: scroll()` and `animation-timeline: view()` for scroll-driven effects. | [`scroll-driven-animation/index.html`](scroll-driven-animation/index.html) |
| **Table Column Hover** | A data table that highlights the active column and row on hover using the CSS `:has()` selector for column targeting. | [`table-column/table-column-hover.html`](table-column/table-column-hover.html) |

## How to View the Demos

These are static files—no build step required.

1. Choose any feature folder.
2. Open the HTML file in a browser (double-click or use a local server).
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
