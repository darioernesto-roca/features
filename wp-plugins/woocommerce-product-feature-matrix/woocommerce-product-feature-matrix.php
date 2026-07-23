<?php
/**
 * Plugin Name: WooCommerce Product Feature Matrix
 * Description: WooCommerce-aware product comparison matrix with product data, attribute filters, sticky headers, shortcode, and block output.
 * Version: 0.1.0
 * Author: UI Feature Gallery
 * License: GPL-2.0-or-later
 * Text Domain: woocommerce-product-feature-matrix
 * Requires at least: 6.0
 * Requires PHP: 7.4
 * Requires Plugins: woocommerce
 *
 * @package WooCommerceProductFeatureMatrix
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'WPFM_VERSION', '0.1.0' );
define( 'WPFM_FILE', __FILE__ );
define( 'WPFM_DIR', plugin_dir_path( __FILE__ ) );
define( 'WPFM_URL', plugin_dir_url( __FILE__ ) );

require_once WPFM_DIR . 'includes/class-woocommerce-product-feature-matrix.php';

WooCommerce_Product_Feature_Matrix::init();
