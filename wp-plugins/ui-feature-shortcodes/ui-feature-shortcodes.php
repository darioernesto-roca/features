<?php
/**
 * Plugin Name: UI Feature Shortcodes
 * Description: Shortcode-based UI components adapted from the UI Feature Gallery prototypes.
 * Version: 0.1.0
 * Author: UI Feature Gallery
 * License: GPL-2.0-or-later
 * Text Domain: ui-feature-shortcodes
 * Requires at least: 6.0
 * Requires PHP: 7.4
 *
 * @package UIFeatureShortcodes
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'UI_FEATURE_SHORTCODES_VERSION', '0.1.0' );
define( 'UI_FEATURE_SHORTCODES_FILE', __FILE__ );
define( 'UI_FEATURE_SHORTCODES_DIR', plugin_dir_path( __FILE__ ) );
define( 'UI_FEATURE_SHORTCODES_URL', plugin_dir_url( __FILE__ ) );

require_once UI_FEATURE_SHORTCODES_DIR . 'includes/class-ui-feature-shortcodes.php';

UI_Feature_Shortcodes::init();
