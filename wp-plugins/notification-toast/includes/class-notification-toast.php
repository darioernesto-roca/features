<?php
/**
 * Main plugin bootstrap.
 *
 * @package NotificationToast
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Registers toast assets, admin builder, display rules, shortcodes, and integrations.
 */
final class Notification_Toast {
	/** Option name for admin-built notice. */
	const OPTION = 'notification_toast_notice';

	/** Runtime notice queue. */
	private static $queued_notices = array();

	/**
	 * Initialize plugin hooks.
	 *
	 * @return void
	 */
	public static function init() {
		add_action( 'plugins_loaded', array( __CLASS__, 'load_textdomain' ) );
		add_action( 'init', array( __CLASS__, 'register_assets' ) );
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'enqueue_assets' ) );
		add_action( 'wp_footer', array( __CLASS__, 'render_container' ) );
		add_action( 'admin_menu', array( __CLASS__, 'add_admin_page' ) );
		add_action( 'admin_init', array( __CLASS__, 'register_settings' ) );
		add_shortcode( 'notification_toast', array( __CLASS__, 'render_shortcode' ) );
		add_shortcode( 'toast_notice', array( __CLASS__, 'render_shortcode' ) );
		add_action( 'woocommerce_thankyou', array( __CLASS__, 'queue_order_toast' ) );
		add_action( 'woocommerce_add_to_cart', array( __CLASS__, 'queue_add_to_cart_toast' ) );
	}

	/**
	 * Load translations.
	 *
	 * @return void
	 */
	public static function load_textdomain() {
		load_plugin_textdomain(
			'notification-toast',
			false,
			dirname( plugin_basename( NOTIFICATION_TOAST_FILE ) ) . '/languages'
		);
	}

	/**
	 * Register frontend assets.
	 *
	 * @return void
	 */
	public static function register_assets() {
		$style_asset_path  = NOTIFICATION_TOAST_DIR . 'assets/toast.css';
		$script_asset_path = NOTIFICATION_TOAST_DIR . 'assets/toast.js';

		wp_register_style(
			'notification-toast',
			NOTIFICATION_TOAST_URL . 'assets/toast.css',
			array(),
			file_exists( $style_asset_path ) ? filemtime( $style_asset_path ) : NOTIFICATION_TOAST_VERSION
		);

		wp_register_script(
			'notification-toast',
			NOTIFICATION_TOAST_URL . 'assets/toast.js',
			array(),
			file_exists( $script_asset_path ) ? filemtime( $script_asset_path ) : NOTIFICATION_TOAST_VERSION,
			true
		);
	}

	/**
	 * Enqueue frontend assets.
	 *
	 * @return void
	 */
	public static function enqueue_assets() {
		wp_enqueue_style( 'notification-toast' );
		wp_enqueue_script( 'notification-toast' );
	}

	/**
	 * Add settings page for the admin notice builder.
	 *
	 * @return void
	 */
	public static function add_admin_page() {
		add_options_page(
			esc_html__( 'Notification Toast', 'notification-toast' ),
			esc_html__( 'Notification Toast', 'notification-toast' ),
			'manage_options',
			'notification-toast',
			array( __CLASS__, 'render_admin_page' )
		);
	}

	/**
	 * Register settings for the admin notice builder.
	 *
	 * @return void
	 */
	public static function register_settings() {
		register_setting(
			'notification_toast_settings',
			self::OPTION,
			array(
				'type'              => 'array',
				'sanitize_callback' => array( __CLASS__, 'sanitize_notice_option' ),
				'default'           => self::get_default_notice(),
			)
		);
	}

	/**
	 * Render admin settings page.
	 *
	 * @return void
	 */
	public static function render_admin_page() {
		$notice = wp_parse_args( get_option( self::OPTION, array() ), self::get_default_notice() );
		?>
		<div class="wrap">
			<h1><?php echo esc_html__( 'Notification Toast Builder', 'notification-toast' ); ?></h1>
			<form method="post" action="options.php">
				<?php settings_fields( 'notification_toast_settings' ); ?>
				<table class="form-table" role="presentation">
					<tr>
						<th scope="row"><label for="nt-enabled"><?php echo esc_html__( 'Enable site notice', 'notification-toast' ); ?></label></th>
						<td><input id="nt-enabled" type="checkbox" name="<?php echo esc_attr( self::OPTION ); ?>[enabled]" value="1" <?php checked( self::truthy( $notice['enabled'] ) ); ?> /></td>
					</tr>
					<tr>
						<th scope="row"><label for="nt-message"><?php echo esc_html__( 'Message', 'notification-toast' ); ?></label></th>
						<td><textarea id="nt-message" class="large-text" rows="3" name="<?php echo esc_attr( self::OPTION ); ?>[message]"><?php echo esc_textarea( $notice['message'] ); ?></textarea></td>
					</tr>
					<tr>
						<th scope="row"><label for="nt-type"><?php echo esc_html__( 'Type', 'notification-toast' ); ?></label></th>
						<td><?php self::render_select( 'type', $notice['type'], array( 'success', 'error', 'info', 'warning' ) ); ?></td>
					</tr>
					<tr>
						<th scope="row"><label for="nt-rule"><?php echo esc_html__( 'Display rule', 'notification-toast' ); ?></label></th>
						<td><?php self::render_select( 'rule', $notice['rule'], array( 'always', 'session', 'cookie' ) ); ?></td>
					</tr>
					<tr>
						<th scope="row"><label for="nt-duration"><?php echo esc_html__( 'Dismiss after', 'notification-toast' ); ?></label></th>
						<td><input id="nt-duration" type="number" min="0" step="500" name="<?php echo esc_attr( self::OPTION ); ?>[duration]" value="<?php echo esc_attr( $notice['duration'] ); ?>" /> <span><?php echo esc_html__( 'milliseconds. Use 0 to disable timed dismissal.', 'notification-toast' ); ?></span></td>
					</tr>
				</table>
				<?php submit_button(); ?>
			</form>
		</div>
		<?php
	}

	/**
	 * Render a select field for admin settings.
	 *
	 * @param string $key Current setting key.
	 * @param string $value Current setting value.
	 * @param array  $choices Allowed choices.
	 * @return void
	 */
	private static function render_select( $key, $value, $choices ) {
		printf( '<select id="nt-%1$s" name="%2$s[%1$s]">', esc_attr( $key ), esc_attr( self::OPTION ) );

		foreach ( $choices as $choice ) {
			printf( '<option value="%1$s" %2$s>%3$s</option>', esc_attr( $choice ), selected( $value, $choice, false ), esc_html( ucfirst( $choice ) ) );
		}

		echo '</select>';
	}

	/**
	 * Sanitize admin notice option.
	 *
	 * @param array $value Raw option value.
	 * @return array
	 */
	public static function sanitize_notice_option( $value ) {
		$value = is_array( $value ) ? $value : array();

		return array(
			'enabled'  => ! empty( $value['enabled'] ) ? '1' : '0',
			'message'  => isset( $value['message'] ) ? sanitize_text_field( $value['message'] ) : '',
			'type'     => self::normalize_choice( isset( $value['type'] ) ? $value['type'] : 'info', array( 'success', 'error', 'info', 'warning' ), 'info' ),
			'rule'     => self::normalize_choice( isset( $value['rule'] ) ? $value['rule'] : 'session', array( 'always', 'session', 'cookie' ), 'session' ),
			'duration' => isset( $value['duration'] ) ? (string) absint( $value['duration'] ) : '6000',
		);
	}

	/**
	 * Render shortcode trigger or immediate toast data.
	 *
	 * @param array $atts Shortcode attributes.
	 * @return string
	 */
	public static function render_shortcode( $atts ) {
		$atts = shortcode_atts(
			array(
				'message'  => __( 'This is a reusable toast notification.', 'notification-toast' ),
				'type'     => 'info',
				'rule'     => 'always',
				'duration' => '6000',
				'trigger'  => 'auto',
				'label'    => __( 'Show notification', 'notification-toast' ),
			),
			(array) $atts,
			'notification_toast'
		);

		$notice = self::normalize_notice( $atts );

		if ( 'button' === $atts['trigger'] ) {
			return sprintf(
				'<button type="button" class="ntp-trigger" data-ntp-trigger="%1$s">%2$s</button><script type="application/json" id="%1$s">%3$s</script>',
				esc_attr( 'ntp-shortcode-' . wp_generate_uuid4() ),
				esc_html( $atts['label'] ),
				wp_json_encode( $notice )
			);
		}

		return sprintf( '<script type="application/json" class="ntp-shortcode-notice">%s</script>', wp_json_encode( $notice ) );
	}

	/**
	 * Queue a PHP/API notice for the current request.
	 *
	 * @param string $message Notice message.
	 * @param string $type Notice type.
	 * @param array  $args Optional notice args.
	 * @return void
	 */
	public static function queue_notice( $message, $type = 'info', $args = array() ) {
		self::$queued_notices[] = self::normalize_notice(
			wp_parse_args(
				$args,
				array(
					'message'  => $message,
					'type'     => $type,
					'rule'     => 'session',
					'duration' => '6000',
				)
			)
		);
	}

	/**
	 * Queue WooCommerce order toast.
	 *
	 * @return void
	 */
	public static function queue_order_toast() {
		self::queue_notice( __( 'Thanks! Your order was received.', 'notification-toast' ), 'success', array( 'rule' => 'session' ) );
	}

	/**
	 * Queue WooCommerce add-to-cart toast for non-AJAX requests.
	 *
	 * @return void
	 */
	public static function queue_add_to_cart_toast() {
		self::queue_notice( __( 'Product added to cart.', 'notification-toast' ), 'success', array( 'rule' => 'session' ) );
	}

	/**
	 * Render frontend toast container and initial notice payload.
	 *
	 * @return void
	 */
	public static function render_container() {
		$notices = self::get_initial_notices();
		?>
		<div class="ntp-toast-region" data-ntp-region aria-live="polite" aria-atomic="true"></div>
		<script type="application/json" id="ntp-initial-notices"><?php echo wp_json_encode( $notices ); ?></script>
		<?php
	}

	/**
	 * Get admin-built and queued notices.
	 *
	 * @return array
	 */
	private static function get_initial_notices() {
		$notices = self::$queued_notices;
		$notice  = wp_parse_args( get_option( self::OPTION, array() ), self::get_default_notice() );

		if ( self::truthy( $notice['enabled'] ) && '' !== $notice['message'] ) {
			array_unshift( $notices, self::normalize_notice( $notice ) );
		}

		return $notices;
	}

	/**
	 * Normalize a notice payload.
	 *
	 * @param array $notice Raw notice.
	 * @return array
	 */
	private static function normalize_notice( $notice ) {
		$type    = self::normalize_choice( isset( $notice['type'] ) ? $notice['type'] : 'info', array( 'success', 'error', 'info', 'warning' ), 'info' );
		$rule    = self::normalize_choice( isset( $notice['rule'] ) ? $notice['rule'] : 'always', array( 'always', 'session', 'cookie' ), 'always' );
		$message = isset( $notice['message'] ) ? sanitize_text_field( $notice['message'] ) : '';
		$id      = isset( $notice['id'] ) ? sanitize_key( $notice['id'] ) : 'ntp-' . md5( $type . '|' . $rule . '|' . $message );

		return array(
			'id'       => $id,
			'message'  => $message,
			'type'     => $type,
			'rule'     => $rule,
			'duration' => isset( $notice['duration'] ) ? absint( $notice['duration'] ) : 6000,
		);
	}

	/**
	 * Get default admin notice values.
	 *
	 * @return array
	 */
	private static function get_default_notice() {
		return array(
			'enabled'  => '0',
			'message'  => __( 'Welcome! This is a site-wide toast notification.', 'notification-toast' ),
			'type'     => 'info',
			'rule'     => 'session',
			'duration' => '6000',
		);
	}

	/**
	 * Normalize a choice value.
	 *
	 * @param string $value Raw value.
	 * @param array  $allowed Allowed values.
	 * @param string $fallback Fallback value.
	 * @return string
	 */
	private static function normalize_choice( $value, $allowed, $fallback ) {
		$value = sanitize_key( (string) $value );

		return in_array( $value, $allowed, true ) ? $value : $fallback;
	}

	/**
	 * Determine whether a value is truthy.
	 *
	 * @param string $value Raw value.
	 * @return bool
	 */
	private static function truthy( $value ) {
		return in_array( strtolower( (string) $value ), array( '1', 'true', 'yes', 'on' ), true );
	}
}
