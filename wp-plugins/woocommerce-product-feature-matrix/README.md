# WooCommerce Product Feature Matrix

WooCommerce Product Feature Matrix is a product-aware comparison plugin for stores that need richer comparisons than a generic table.

## Features

- Select specific WooCommerce products to compare.
- Pull product image, price, rating, stock, and attributes from WooCommerce.
- Sticky comparison header and sticky feature-name column.
- Product add-to-cart buttons with WooCommerce AJAX class support.
- Attribute-based filtering for large matrices.
- Dynamic Gutenberg block: `woocommerce-product-feature-matrix/matrix`.
- Shortcodes: `[wc_product_feature_matrix]` and `[product_feature_matrix]`.
- Scoped `wpfm-` styles and no third-party frontend dependencies.

## Shortcode examples

```text
[wc_product_feature_matrix products="12,34,56"]
[wc_product_feature_matrix title="Compare laptops" products="12,34,56" attributes="screen-size,memory,pa_color"]
[product_feature_matrix product_ids="12,34" show_filters="false"]
```

## Compatibility notes

- WooCommerce must be active for product data and add-to-cart links to render.
- The plugin reads product data through WooCommerce APIs and does not duplicate product information.
- Attribute filters are progressively enhanced with a small scoped JavaScript file.
- Styles are scoped with the `wpfm-` prefix to reduce theme, Elementor, Avada, and WP Rocket conflicts.
