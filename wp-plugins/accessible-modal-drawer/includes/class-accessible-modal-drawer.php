<?php
/**
 * Main plugin bootstrap.
 *
 * @package AccessibleModalDrawer
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Registers accessible dialog assets, shortcodes, and blocks.
 */
final class Accessible_Modal_Drawer {
	/**
	 * Initialize plugin hooks.
	 *
	 * @return void
	 */
	public static function init() {
		add_action( 'plugins_loaded', array( __CLASS__, 'load_textdomain' ) );
		add_action( 'init', array( __CLASS__, 'register_assets' ) );
		add_action( 'init', array( __CLASS__, 'register_block' ) );
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'enqueue_assets' ) );
		add_shortcode( 'amd_dialog', array( __CLASS__, 'render_shortcode' ) );
		add_shortcode( 'accessible_modal', array( __CLASS__, 'render_shortcode' ) );
	}

	/**
	 * Load plugin translations.
	 *
	 * @return void
	 */
	public static function load_textdomain() {
		load_plugin_textdomain(
			'accessible-modal-drawer',
			false,
			dirname( plugin_basename( ACCESSIBLE_MODAL_DRAWER_FILE ) ) . '/languages'
		);
	}

	/**
	 * Register shared block, shortcode, and frontend assets.
	 *
	 * @return void
	 */
	public static function register_assets() {
		$editor_asset_path = ACCESSIBLE_MODAL_DRAWER_DIR . 'assets/editor.js';
		$style_asset_path  = ACCESSIBLE_MODAL_DRAWER_DIR . 'assets/dialog.css';
		$script_asset_path = ACCESSIBLE_MODAL_DRAWER_DIR . 'assets/dialog.js';

		wp_register_script(
			'accessible-modal-drawer-editor',
			ACCESSIBLE_MODAL_DRAWER_URL . 'assets/editor.js',
			array( 'wp-blocks', 'wp-components', 'wp-element', 'wp-i18n', 'wp-block-editor' ),
			file_exists( $editor_asset_path ) ? filemtime( $editor_asset_path ) : ACCESSIBLE_MODAL_DRAWER_VERSION,
			true
		);

		wp_register_style(
			'accessible-modal-drawer',
			ACCESSIBLE_MODAL_DRAWER_URL . 'assets/dialog.css',
			array(),
			file_exists( $style_asset_path ) ? filemtime( $style_asset_path ) : ACCESSIBLE_MODAL_DRAWER_VERSION
		);

		wp_register_script(
			'accessible-modal-drawer',
			ACCESSIBLE_MODAL_DRAWER_URL . 'assets/dialog.js',
			array(),
			file_exists( $script_asset_path ) ? filemtime( $script_asset_path ) : ACCESSIBLE_MODAL_DRAWER_VERSION,
			true
		);
	}


	/**
	 * Enqueue public assets for shortcode and template usage.
	 *
	 * @return void
	 */
	public static function enqueue_assets() {
		wp_enqueue_style( 'accessible-modal-drawer' );
		wp_enqueue_script( 'accessible-modal-drawer' );
	}

	/**
	 * Register the dynamic trigger block.
	 *
	 * @return void
	 */
	public static function register_block() {
		if ( ! function_exists( 'register_block_type' ) ) {
			return;
		}

		register_block_type(
			'accessible-modal-drawer/trigger',
			array(
				'api_version'     => 2,
				'editor_script'   => 'accessible-modal-drawer-editor',
				'style'           => 'accessible-modal-drawer',
				'script'          => 'accessible-modal-drawer',
				'render_callback' => array( __CLASS__, 'render_block' ),
				'attributes'      => self::get_attribute_schema(),
			)
		);
	}

	/**
	 * Render shortcode output.
	 *
	 * @param array       $atts Shortcode attributes.
	 * @param string|null $content Optional enclosed content.
	 * @return string
	 */
	public static function render_shortcode( $atts, $content = null ) {
		$atts       = (array) $atts;
		$attributes = shortcode_atts(
			array_merge(
				self::get_default_attributes(),
				array(
					'button_text' => '',
				)
			),
			$atts,
			'amd_dialog'
		);

		if ( '' !== $attributes['button_text'] ) {
			$attributes['buttonText'] = $attributes['button_text'];
		}

		if ( null !== $content && '' !== trim( $content ) ) {
			$attributes['content'] = do_shortcode( $content );
		}

		return self::render_dialog( $attributes );
	}

	/**
	 * Render dynamic block output.
	 *
	 * @param array $attributes Block attributes.
	 * @return string
	 */
	public static function render_block( $attributes ) {
		return self::render_dialog( wp_parse_args( (array) $attributes, self::get_default_attributes() ) );
	}

	/**
	 * Render the trigger and dialog markup.
	 *
	 * @param array $attributes Dialog attributes.
	 * @return string
	 */
	private static function render_dialog( $attributes ) {
		$attributes  = wp_parse_args( (array) $attributes, self::get_default_attributes() );
		$dialog_id   = sanitize_html_class( $attributes['id'] );
		$dialog_id   = '' !== $dialog_id ? $dialog_id : 'accessible-dialog';
		$dialog_type = self::normalize_choice( $attributes['type'], array( 'modal', 'drawer', 'side-panel' ), 'modal' );
		$template    = self::normalize_choice( $attributes['template'], array( 'newsletter', 'cta', 'announcement', 'custom' ), 'newsletter' );
		$title       = self::clean_text( $attributes['title'] );
		$button_text = self::clean_text( $attributes['buttonText'] );
		$content     = '' !== trim( (string) $attributes['content'] ) ? $attributes['content'] : self::get_template_content( $template );

		ob_start();
		?>
		<div class="amd-dialog-wrap amd-dialog-wrap--<?php echo esc_attr( $dialog_type ); ?>">
			<button class="amd-dialog__trigger" type="button" data-amd-open="<?php echo esc_attr( $dialog_id ); ?>" aria-haspopup="dialog">
				<?php echo esc_html( $button_text ); ?>
			</button>

			<div class="amd-dialog amd-dialog--<?php echo esc_attr( $dialog_type ); ?>" id="<?php echo esc_attr( $dialog_id ); ?>" role="dialog" aria-modal="true" aria-labelledby="<?php echo esc_attr( $dialog_id ); ?>-title" hidden>
				<div class="amd-dialog__backdrop" data-amd-close></div>
				<section class="amd-dialog__panel" role="document" tabindex="-1">
					<button class="amd-dialog__close" type="button" data-amd-close aria-label="<?php echo esc_attr__( 'Close dialog', 'accessible-modal-drawer' ); ?>">&times;</button>
					<h2 class="amd-dialog__title" id="<?php echo esc_attr( $dialog_id ); ?>-title"><?php echo esc_html( $title ); ?></h2>
					<div class="amd-dialog__content amd-dialog__content--<?php echo esc_attr( $template ); ?>">
						<?php echo wp_kses_post( wpautop( $content ) ); ?>
					</div>
				</section>
			</div>
		</div>
		<?php
		return ob_get_clean();
	}

	/**
	 * Get default shortcode and block attributes.
	 *
	 * @return array
	 */
	private static function get_default_attributes() {
		return array(
			'id'         => 'accessible-dialog',
			'type'       => 'modal',
			'template'   => 'newsletter',
			'title'      => __( 'Stay in the loop', 'accessible-modal-drawer' ),
			'buttonText' => __( 'Open dialog', 'accessible-modal-drawer' ),
			'content'    => '',
		);
	}

	/**
	 * Get dynamic block attribute schema.
	 *
	 * @return array
	 */
	private static function get_attribute_schema() {
		$defaults = self::get_default_attributes();
		$schema   = array();

		foreach ( $defaults as $key => $default ) {
			$schema[ $key ] = array(
				'type'    => 'string',
				'default' => $default,
			);
		}

		return $schema;
	}

	/**
	 * Return starter content for built-in templates.
	 *
	 * @param string $template Template slug.
	 * @return string
	 */
	private static function get_template_content( $template ) {
		$templates = array(
			'newsletter'   => __( 'Subscribe for updates, product notes, and helpful resources delivered to your inbox.', 'accessible-modal-drawer' ),
			'cta'          => __( 'Ready to get started? Use this dialog for a focused call to action without changing your theme.', 'accessible-modal-drawer' ),
			'announcement' => __( 'Share an important announcement with a keyboard-friendly modal, drawer, or side panel.', 'accessible-modal-drawer' ),
			'custom'       => __( 'Add custom content in the block settings or inside the shortcode body.', 'accessible-modal-drawer' ),
		);

		return isset( $templates[ $template ] ) ? $templates[ $template ] : $templates['newsletter'];
	}

	/**
	 * Normalize a string to an allowed value.
	 *
	 * @param string $value Value to normalize.
	 * @param array  $allowed Allowed values.
	 * @param string $fallback Fallback value.
	 * @return string
	 */
	private static function normalize_choice( $value, $allowed, $fallback ) {
		$value = sanitize_key( (string) $value );

		return in_array( $value, $allowed, true ) ? $value : $fallback;
	}

	/**
	 * Clean a plain text value.
	 *
	 * @param string $value Raw value.
	 * @return string
	 */
	private static function clean_text( $value ) {
		return wp_strip_all_tags( (string) $value );
	}
}
