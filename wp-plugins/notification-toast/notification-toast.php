<?php
/**
 * Plugin Name: Notification Toast
 * Description: Site-wide toast notices with rules, admin builder, WooCommerce hooks, shortcode, and API trigger support.
 * Version: 0.1.0
 * Author: UI Feature Gallery
 * License: GPL-2.0-or-later
 * Text Domain: notification-toast
 * Requires at least: 6.0
 * Requires PHP: 7.4
 *
 * @package NotificationToast
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'NOTIFICATION_TOAST_VERSION', '0.1.0' );
define( 'NOTIFICATION_TOAST_FILE', __FILE__ );
define( 'NOTIFICATION_TOAST_DIR', plugin_dir_path( __FILE__ ) );
define( 'NOTIFICATION_TOAST_URL', plugin_dir_url( __FILE__ ) );

require_once NOTIFICATION_TOAST_DIR . 'includes/class-notification-toast.php';

Notification_Toast::init();

if ( ! function_exists( 'notification_toast_trigger' ) ) {
	/**
	 * Queue a toast from theme/plugin PHP during the current request.
	 *
	 * @param string $message Toast message.
	 * @param string $type Toast type.
	 * @param array  $args Optional notice args.
	 * @return void
	 */
	function notification_toast_trigger( $message, $type = 'info', $args = array() ) {
		Notification_Toast::queue_notice( $message, $type, $args );
	}
}
