<?php
/**
 * Plugin Name: Accessible Tabs and Accordion Blocks
 * Description: Dependency-free, accessible tabs and accordion blocks for the WordPress editor.
 * Version: 0.1.0
 * Author: UI Feature Gallery
 * License: GPL-2.0-or-later
 * Text Domain: accessible-tabs-accordion-blocks
 * Requires at least: 6.0
 * Requires PHP: 7.4
 *
 * @package AccessibleTabsAccordionBlocks
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'ATAB_VERSION', '0.1.0' );
define( 'ATAB_FILE', __FILE__ );
define( 'ATAB_DIR', plugin_dir_path( __FILE__ ) );
define( 'ATAB_URL', plugin_dir_url( __FILE__ ) );

require_once ATAB_DIR . 'includes/class-accessible-tabs-accordion-blocks.php';

Accessible_Tabs_Accordion_Blocks::init();
