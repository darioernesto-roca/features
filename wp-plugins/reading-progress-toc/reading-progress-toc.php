<?php
/**
 * Plugin Name: Reading Progress TOC
 * Description: Adds a reading progress bar and auto-generated table of contents for blogs and documentation.
 * Version: 0.1.0
 * Author: UI Feature Gallery
 * License: GPL-2.0-or-later
 * Text Domain: reading-progress-toc
 * Requires at least: 6.0
 * Requires PHP: 7.4
 *
 * @package ReadingProgressToc
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'READING_PROGRESS_TOC_VERSION', '0.1.0' );
define( 'READING_PROGRESS_TOC_FILE', __FILE__ );
define( 'READING_PROGRESS_TOC_DIR', plugin_dir_path( __FILE__ ) );
define( 'READING_PROGRESS_TOC_URL', plugin_dir_url( __FILE__ ) );

require_once READING_PROGRESS_TOC_DIR . 'includes/class-reading-progress-toc.php';

Reading_Progress_Toc::init();
