<?php
/**
 * Plugin Name: Pricing Table Builder
 * Description: Pricing tables with monthly/yearly toggle, feature rows, CTA buttons, and optional WooCommerce product links.
 * Version: 0.1.0
 * Author: UI Feature Gallery
 * License: GPL-2.0-or-later
 * Text Domain: pricing-table-builder
 * Requires at least: 6.0
 * Requires PHP: 7.4
 *
 * @package PricingTableBuilder
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'PRICING_TABLE_BUILDER_VERSION', '0.1.0' );
define( 'PRICING_TABLE_BUILDER_FILE', __FILE__ );
define( 'PRICING_TABLE_BUILDER_DIR', plugin_dir_path( __FILE__ ) );
define( 'PRICING_TABLE_BUILDER_URL', plugin_dir_url( __FILE__ ) );

require_once PRICING_TABLE_BUILDER_DIR . 'includes/class-pricing-table-builder.php';

Pricing_Table_Builder::init();
