<?php
/**
 * Plugin Name: Advanced FAQ Help Center
 * Description: FAQ custom post type, categories, searchable accordion output, schema, shortcode, and Gutenberg block.
 * Version: 0.1.0
 * Author: UI Feature Gallery
 * License: GPL-2.0-or-later
 * Text Domain: advanced-faq-help-center
 * Requires at least: 6.0
 * Requires PHP: 7.4
 *
 * @package AdvancedFAQHelpCenter
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'ADVANCED_FAQ_HELP_CENTER_VERSION', '0.1.0' );
define( 'ADVANCED_FAQ_HELP_CENTER_FILE', __FILE__ );
define( 'ADVANCED_FAQ_HELP_CENTER_DIR', plugin_dir_path( __FILE__ ) );
define( 'ADVANCED_FAQ_HELP_CENTER_URL', plugin_dir_url( __FILE__ ) );

require_once ADVANCED_FAQ_HELP_CENTER_DIR . 'includes/class-advanced-faq-help-center.php';

Advanced_FAQ_Help_Center::init();
register_activation_hook( __FILE__, array( 'Advanced_FAQ_Help_Center', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'Advanced_FAQ_Help_Center', 'deactivate' ) );
