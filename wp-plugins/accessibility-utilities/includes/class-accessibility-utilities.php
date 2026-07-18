<?php
/**
 * Main plugin bootstrap.
 *
 * @package AccessibilityUtilities
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Registers accessibility utilities and admin checklist.
 */
final class Accessibility_Utilities {
	/** Settings option name. */
	const OPTION = 'accessibility_utilities_settings';

	/** Audit checklist option name. */
	const CHECKLIST_OPTION = 'accessibility_utilities_checklist';

	/** Runtime announcements. */
	private static $announcements = array();

	/**
	 * Initialize plugin hooks.
	 *
	 * @return void
	 */
	public static function init() {
		add_action( 'plugins_loaded', array( __CLASS__, 'load_textdomain' ) );
		add_action( 'init', array( __CLASS__, 'register_assets' ) );
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'enqueue_assets' ) );
		add_action( 'wp_body_open', array( __CLASS__, 'render_skip_link' ) );
		add_action( 'wp_footer', array( __CLASS__, 'render_live_region' ) );
		add_action( 'admin_menu', array( __CLASS__, 'add_admin_page' ) );
		add_action( 'admin_init', array( __CLASS__, 'register_settings' ) );
	}

	/**
	 * Load plugin translations.
	 *
	 * @return void
	 */
	public static function load_textdomain() {
		load_plugin_textdomain(
			'accessibility-utilities',
			false,
			dirname( plugin_basename( ACCESSIBILITY_UTILITIES_FILE ) ) . '/languages'
		);
	}

	/**
	 * Register frontend assets.
	 *
	 * @return void
	 */
	public static function register_assets() {
		$style_asset_path  = ACCESSIBILITY_UTILITIES_DIR . 'assets/accessibility.css';
		$script_asset_path = ACCESSIBILITY_UTILITIES_DIR . 'assets/accessibility.js';

		wp_register_style(
			'accessibility-utilities',
			ACCESSIBILITY_UTILITIES_URL . 'assets/accessibility.css',
			array(),
			file_exists( $style_asset_path ) ? filemtime( $style_asset_path ) : ACCESSIBILITY_UTILITIES_VERSION
		);

		wp_register_script(
			'accessibility-utilities',
			ACCESSIBILITY_UTILITIES_URL . 'assets/accessibility.js',
			array(),
			file_exists( $script_asset_path ) ? filemtime( $script_asset_path ) : ACCESSIBILITY_UTILITIES_VERSION,
			true
		);
	}

	/**
	 * Enqueue frontend assets and settings.
	 *
	 * @return void
	 */
	public static function enqueue_assets() {
		$settings = self::get_settings();

		wp_enqueue_style( 'accessibility-utilities' );
		wp_enqueue_script( 'accessibility-utilities' );
		wp_add_inline_script(
			'accessibility-utilities',
			'window.AccessibilityUtilitiesSettings = ' . wp_json_encode( $settings ) . ';',
			'before'
		);
	}

	/**
	 * Render skip link after opening body tag.
	 *
	 * @return void
	 */
	public static function render_skip_link() {
		$settings = self::get_settings();

		if ( ! self::truthy( $settings['skip_link'] ) ) {
			return;
		}

		printf(
			'<a class="au-skip-link" href="%1$s">%2$s</a>',
			esc_url( $settings['skip_target'] ),
			esc_html__( 'Skip to content', 'accessibility-utilities' )
		);
	}

	/**
	 * Render ARIA live regions and queued announcements.
	 *
	 * @return void
	 */
	public static function render_live_region() {
		$settings = self::get_settings();

		if ( ! self::truthy( $settings['live_region'] ) ) {
			return;
		}
		?>
		<div class="au-live-region" data-au-live-region="polite" aria-live="polite" aria-atomic="true"></div>
		<div class="au-live-region" data-au-live-region="assertive" aria-live="assertive" aria-atomic="true"></div>
		<script type="application/json" id="au-initial-announcements"><?php echo wp_json_encode( self::$announcements ); ?></script>
		<?php
	}

	/**
	 * Queue an ARIA live announcement for this request.
	 *
	 * @param string $message Announcement message.
	 * @param string $politeness Live region politeness.
	 * @return void
	 */
	public static function queue_announcement( $message, $politeness = 'polite' ) {
		self::$announcements[] = array(
			'message'    => sanitize_text_field( $message ),
			'politeness' => self::normalize_choice( $politeness, array( 'polite', 'assertive' ), 'polite' ),
		);
	}

	/**
	 * Add admin audit/settings page.
	 *
	 * @return void
	 */
	public static function add_admin_page() {
		add_options_page(
			esc_html__( 'Accessibility Utilities', 'accessibility-utilities' ),
			esc_html__( 'Accessibility Utilities', 'accessibility-utilities' ),
			'manage_options',
			'accessibility-utilities',
			array( __CLASS__, 'render_admin_page' )
		);
	}

	/**
	 * Register settings and checklist options.
	 *
	 * @return void
	 */
	public static function register_settings() {
		register_setting(
			'accessibility_utilities_settings',
			self::OPTION,
			array(
				'type'              => 'array',
				'sanitize_callback' => array( __CLASS__, 'sanitize_settings' ),
				'default'           => self::get_default_settings(),
			)
		);

		register_setting(
			'accessibility_utilities_settings',
			self::CHECKLIST_OPTION,
			array(
				'type'              => 'array',
				'sanitize_callback' => array( __CLASS__, 'sanitize_checklist' ),
				'default'           => array(),
			)
		);
	}

	/**
	 * Render admin settings and audit checklist.
	 *
	 * @return void
	 */
	public static function render_admin_page() {
		$settings  = self::get_settings();
		$checklist = get_option( self::CHECKLIST_OPTION, array() );
		$items     = self::get_audit_items();
		?>
		<div class="wrap">
			<h1><?php echo esc_html__( 'Accessibility Utilities', 'accessibility-utilities' ); ?></h1>
			<form method="post" action="options.php">
				<?php settings_fields( 'accessibility_utilities_settings' ); ?>
				<h2><?php echo esc_html__( 'Frontend Helpers', 'accessibility-utilities' ); ?></h2>
				<table class="form-table" role="presentation">
					<?php self::render_checkbox_row( 'skip_link', __( 'Inject skip link', 'accessibility-utilities' ), $settings['skip_link'] ); ?>
					<tr>
						<th scope="row"><label for="au-skip-target"><?php echo esc_html__( 'Skip target', 'accessibility-utilities' ); ?></label></th>
						<td><input id="au-skip-target" class="regular-text" name="<?php echo esc_attr( self::OPTION ); ?>[skip_target]" type="text" value="<?php echo esc_attr( $settings['skip_target'] ); ?>" /></td>
					</tr>
					<?php self::render_checkbox_row( 'focus_outline', __( 'Enable focus outline helper', 'accessibility-utilities' ), $settings['focus_outline'] ); ?>
					<?php self::render_checkbox_row( 'reduced_motion', __( 'Enable reduced-motion stylesheet', 'accessibility-utilities' ), $settings['reduced_motion'] ); ?>
					<?php self::render_checkbox_row( 'external_links', __( 'Mark external links', 'accessibility-utilities' ), $settings['external_links'] ); ?>
					<?php self::render_checkbox_row( 'live_region', __( 'Enable ARIA live region helper', 'accessibility-utilities' ), $settings['live_region'] ); ?>
				</table>

				<h2><?php echo esc_html__( 'Admin Audit Checklist', 'accessibility-utilities' ); ?></h2>
				<p><?php echo esc_html__( 'Use this lightweight checklist to track manual accessibility checks for the current site.', 'accessibility-utilities' ); ?></p>
				<table class="widefat striped" role="presentation">
					<tbody>
						<?php foreach ( $items as $key => $label ) : ?>
							<tr>
								<td><label><input type="checkbox" name="<?php echo esc_attr( self::CHECKLIST_OPTION ); ?>[<?php echo esc_attr( $key ); ?>]" value="1" <?php checked( ! empty( $checklist[ $key ] ) ); ?> /> <?php echo esc_html( $label ); ?></label></td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
				<?php submit_button(); ?>
			</form>
		</div>
		<?php
	}

	/**
	 * Render a settings checkbox row.
	 *
	 * @param string $key Setting key.
	 * @param string $label Setting label.
	 * @param string $value Setting value.
	 * @return void
	 */
	private static function render_checkbox_row( $key, $label, $value ) {
		?>
		<tr>
			<th scope="row"><?php echo esc_html( $label ); ?></th>
			<td><input type="checkbox" name="<?php echo esc_attr( self::OPTION ); ?>[<?php echo esc_attr( $key ); ?>]" value="1" <?php checked( self::truthy( $value ) ); ?> /></td>
		</tr>
		<?php
	}

	/**
	 * Sanitize settings option.
	 *
	 * @param array $value Raw settings.
	 * @return array
	 */
	public static function sanitize_settings( $value ) {
		$value = is_array( $value ) ? $value : array();

		return array(
			'skip_link'      => ! empty( $value['skip_link'] ) ? '1' : '0',
			'skip_target'    => isset( $value['skip_target'] ) ? sanitize_text_field( $value['skip_target'] ) : '#content',
			'focus_outline'  => ! empty( $value['focus_outline'] ) ? '1' : '0',
			'reduced_motion' => ! empty( $value['reduced_motion'] ) ? '1' : '0',
			'external_links' => ! empty( $value['external_links'] ) ? '1' : '0',
			'live_region'    => ! empty( $value['live_region'] ) ? '1' : '0',
		);
	}

	/**
	 * Sanitize audit checklist option.
	 *
	 * @param array $value Raw checklist values.
	 * @return array
	 */
	public static function sanitize_checklist( $value ) {
		$value = is_array( $value ) ? $value : array();
		$items = self::get_audit_items();
		$out   = array();

		foreach ( array_keys( $items ) as $key ) {
			$out[ $key ] = ! empty( $value[ $key ] ) ? '1' : '0';
		}

		return $out;
	}

	/**
	 * Get merged settings.
	 *
	 * @return array
	 */
	private static function get_settings() {
		return wp_parse_args( get_option( self::OPTION, array() ), self::get_default_settings() );
	}

	/**
	 * Get default settings.
	 *
	 * @return array
	 */
	private static function get_default_settings() {
		return array(
			'skip_link'      => '1',
			'skip_target'    => '#content',
			'focus_outline'  => '1',
			'reduced_motion' => '1',
			'external_links' => '1',
			'live_region'    => '1',
		);
	}

	/**
	 * Get audit checklist items.
	 *
	 * @return array
	 */
	private static function get_audit_items() {
		return array(
			'keyboard'       => __( 'Keyboard navigation reaches all interactive elements.', 'accessibility-utilities' ),
			'focus_visible'  => __( 'Visible focus styles are present and not removed by the theme.', 'accessibility-utilities' ),
			'headings'       => __( 'Headings follow a logical order.', 'accessibility-utilities' ),
			'alt_text'       => __( 'Meaningful images include useful alt text.', 'accessibility-utilities' ),
			'contrast'       => __( 'Text and UI controls meet contrast expectations.', 'accessibility-utilities' ),
			'reduced_motion' => __( 'Motion-heavy UI has reduced-motion alternatives.', 'accessibility-utilities' ),
			'forms'          => __( 'Form fields have labels, errors, and instructions.', 'accessibility-utilities' ),
			'live_regions'   => __( 'Dynamic updates use appropriate live regions.', 'accessibility-utilities' ),
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
