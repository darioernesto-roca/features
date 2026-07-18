# Pricing Table Builder

A focused WordPress plugin for pricing tables with billing toggles, featured plan ribbons, comparison rows, CTA buttons, currency settings, and optional WooCommerce product links.

## Features

- Monthly/yearly price toggle.
- Featured plan ribbon using a zero-based plan index.
- Feature comparison rows.
- CTA buttons with manual URLs.
- Currency and billing-period label settings.
- Optional WooCommerce product IDs for add-to-cart links when WooCommerce is active.
- Dynamic Gutenberg block output.
- Shortcode output for classic editor, widgets, templates, and page builders.

## Shortcodes

```text
[pricing_table_builder]
[ptb_pricing_table currency="$" plans="Starter|Growth|Scale" monthly_prices="19|49|99" yearly_prices="190|490|990" featured="1"]
```

Feature rows use this syntax:

```text
features="Projects:5|25|Unlimited;Support:Email|Priority|Dedicated"
```

Optional WooCommerce product IDs use pipe-separated values that line up with plan order:

```text
product_ids="123|456|789"
```

## Block Usage

1. Insert the **Pricing Table Builder** block.
2. Configure plan names, prices, currency, CTAs, featured plan, and comparison rows.
3. Optionally add WooCommerce product IDs for product-linked CTAs.

## Compatibility Notes

- The plugin uses WordPress shortcodes, dynamic block registration, and enqueued assets only.
- WooCommerce integration is optional and only runs when `wc_get_product()` is available.
- Styles and scripts are scoped with `ptb-*` selectors to reduce conflicts with themes, Elementor, Avada, WooCommerce, and WP Rocket.
- No third-party dependencies are bundled or required.
