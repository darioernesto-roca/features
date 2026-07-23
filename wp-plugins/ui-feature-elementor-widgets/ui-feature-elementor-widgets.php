<?php
/**
 * Plugin Name: UI Feature Elementor Widgets
 * Description: Lightweight Elementor widgets adapted from the UI Feature Gallery prototypes.
 * Version: 0.1.0
 * Author: UI Feature Gallery
 * License: GPL-2.0-or-later
 * Text Domain: ui-feature-elementor-widgets
 * Requires at least: 6.0
 * Requires PHP: 7.4
 * Elementor tested up to: 3.29
 *
 * @package UIFeatureElementorWidgets
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'UI_FEATURE_ELEMENTOR_WIDGETS_VERSION', '0.1.0' );
define( 'UI_FEATURE_ELEMENTOR_WIDGETS_FILE', __FILE__ );
define( 'UI_FEATURE_ELEMENTOR_WIDGETS_DIR', plugin_dir_path( __FILE__ ) );
define( 'UI_FEATURE_ELEMENTOR_WIDGETS_URL', plugin_dir_url( __FILE__ ) );

require_once UI_FEATURE_ELEMENTOR_WIDGETS_DIR . 'includes/class-ui-feature-elementor-widgets.php';

UI_Feature_Elementor_Widgets::init();
