<?php
/**
 * Plugin Name: Accessibility Utilities
 * Description: Lightweight accessibility helpers: skip links, focus outlines, reduced motion, external link indicators, live regions, focus trap utility, and audit checklist.
 * Version: 0.1.0
 * Author: UI Feature Gallery
 * License: GPL-2.0-or-later
 * Text Domain: accessibility-utilities
 * Requires at least: 6.0
 * Requires PHP: 7.4
 *
 * @package AccessibilityUtilities
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'ACCESSIBILITY_UTILITIES_VERSION', '0.1.0' );
define( 'ACCESSIBILITY_UTILITIES_FILE', __FILE__ );
define( 'ACCESSIBILITY_UTILITIES_DIR', plugin_dir_path( __FILE__ ) );
define( 'ACCESSIBILITY_UTILITIES_URL', plugin_dir_url( __FILE__ ) );

require_once ACCESSIBILITY_UTILITIES_DIR . 'includes/class-accessibility-utilities.php';

Accessibility_Utilities::init();

if ( ! function_exists( 'accessibility_utilities_announce' ) ) {
	/**
	 * Queue a frontend ARIA live announcement for the current request.
	 *
	 * @param string $message Announcement message.
	 * @param string $politeness Live region politeness: polite or assertive.
	 * @return void
	 */
	function accessibility_utilities_announce( $message, $politeness = 'polite' ) {
		Accessibility_Utilities::queue_announcement( $message, $politeness );
	}
}
