<?php
/**
 * Plugin Name: Mega Menu Enhancer
 * Description: Theme-safe rich dropdown navigation with multi-column menus, icons, promo blocks, descriptions, and mobile collapse behavior.
 * Version: 0.1.0
 * Author: UI Feature Gallery
 * License: GPL-2.0-or-later
 * Text Domain: mega-menu-enhancer
 * Requires at least: 6.0
 * Requires PHP: 7.4
 *
 * @package MegaMenuEnhancer
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'MEGA_MENU_ENHANCER_VERSION', '0.1.0' );
define( 'MEGA_MENU_ENHANCER_FILE', __FILE__ );
define( 'MEGA_MENU_ENHANCER_DIR', plugin_dir_path( __FILE__ ) );
define( 'MEGA_MENU_ENHANCER_URL', plugin_dir_url( __FILE__ ) );

require_once MEGA_MENU_ENHANCER_DIR . 'includes/class-mega-menu-enhancer-walker.php';
require_once MEGA_MENU_ENHANCER_DIR . 'includes/class-mega-menu-enhancer.php';

Mega_Menu_Enhancer::init();
