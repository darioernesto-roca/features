# Mega Menu Enhancer

A theme-safe WordPress nav menu enhancer that renders rich dropdown layouts without modifying core or theme menu APIs.

## Features

- Multi-column dropdown layouts.
- Icon support via menu item CSS classes like `mme-icon-star`, `mme-icon-cart`, or `icon-home`.
- Promo blocks through shortcode attributes.
- Mobile collapse behavior with accessible submenu toggles.
- Menu item descriptions rendered inside dropdown items.
- Scoped `mme-*` CSS to reduce theme and page-builder conflicts.

## Shortcodes

```text
[mega_menu_enhancer theme_location="primary" columns="3"]
[mme_menu menu="Main Menu" columns="4" promo_title="New launch" promo_text="Explore the latest resources." promo_url="/resources" promo_label="View resources"]
```

## Theme Helper

```php
echo mega_menu_enhancer_render_menu(
    array(
        'theme_location' => 'primary',
        'columns'        => '3',
        'promo_title'    => 'Need help?',
        'promo_text'     => 'Browse support resources and guides.',
        'promo_url'      => '/support',
    )
);
```

## Menu Authoring Notes

- Enable **Description** and **CSS Classes** in the WordPress menu screen options.
- Add descriptions to child menu items for richer dropdown summaries.
- Add icon classes such as `mme-icon-star`, `mme-icon-bolt`, `mme-icon-cart`, `mme-icon-book`, `mme-icon-mail`, or `mme-icon-support`.
- Use shortcode-level promo attributes for a dropdown promo card.

## Compatibility Notes

- The plugin renders menus with `wp_nav_menu()` and a custom walker only when the shortcode/helper is used.
- Styles and scripts are scoped with `mme-*` selectors.
- No third-party dependencies are bundled or required.
