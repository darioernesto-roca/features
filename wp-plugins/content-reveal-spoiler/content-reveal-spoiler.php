<?php
/**
 * Plugin Name: Content Reveal Spoiler
 * Description: Accessible content reveals and spoiler panels for shortcodes and the block editor.
 * Version: 0.1.0
 * Author: UI Feature Gallery
 * License: GPL-2.0-or-later
 * Text Domain: content-reveal-spoiler
 * Requires at least: 6.0
 * Requires PHP: 7.4
 *
 * @package ContentRevealSpoiler
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'CONTENT_REVEAL_SPOILER_VERSION', '0.1.0' );
define( 'CONTENT_REVEAL_SPOILER_FILE', __FILE__ );
define( 'CONTENT_REVEAL_SPOILER_DIR', plugin_dir_path( __FILE__ ) );
define( 'CONTENT_REVEAL_SPOILER_URL', plugin_dir_url( __FILE__ ) );

require_once CONTENT_REVEAL_SPOILER_DIR . 'includes/class-content-reveal-spoiler.php';

Content_Reveal_Spoiler::init();
