<?php
/**
 * Plugin Name: UI Feature Blocks
 * Description: Lightweight Gutenberg blocks adapted from the UI Feature Gallery prototypes.
 * Version: 0.1.0
 * Author: UI Feature Gallery
 * License: GPL-2.0-or-later
 * Text Domain: ui-feature-blocks
 * Requires at least: 6.0
 * Requires PHP: 7.4
 *
 * @package UIFeatureBlocks
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'UI_FEATURE_BLOCKS_VERSION', '0.1.0' );
define( 'UI_FEATURE_BLOCKS_FILE', __FILE__ );
define( 'UI_FEATURE_BLOCKS_DIR', plugin_dir_path( __FILE__ ) );
define( 'UI_FEATURE_BLOCKS_URL', plugin_dir_url( __FILE__ ) );

require_once UI_FEATURE_BLOCKS_DIR . 'includes/class-ui-feature-blocks.php';

UI_Feature_Blocks::init();
