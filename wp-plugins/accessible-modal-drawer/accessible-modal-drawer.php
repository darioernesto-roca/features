<?php
/**
 * Plugin Name: Accessible Modal Drawer
 * Description: Accessibility-first modals, drawers, and side panels with shortcode and block triggers.
 * Version: 0.1.0
 * Author: UI Feature Gallery
 * License: GPL-2.0-or-later
 * Text Domain: accessible-modal-drawer
 * Requires at least: 6.0
 * Requires PHP: 7.4
 *
 * @package AccessibleModalDrawer
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'ACCESSIBLE_MODAL_DRAWER_VERSION', '0.1.0' );
define( 'ACCESSIBLE_MODAL_DRAWER_FILE', __FILE__ );
define( 'ACCESSIBLE_MODAL_DRAWER_DIR', plugin_dir_path( __FILE__ ) );
define( 'ACCESSIBLE_MODAL_DRAWER_URL', plugin_dir_url( __FILE__ ) );

require_once ACCESSIBLE_MODAL_DRAWER_DIR . 'includes/class-accessible-modal-drawer.php';

Accessible_Modal_Drawer::init();
