<?php
/**
 * Main plugin bootstrap.
 *
 * @package UIFeatureElementorWidgets
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Registers Elementor widgets and assets.
 */
final class UI_Feature_Elementor_Widgets {
	/**
	 * Elementor category slug.
	 */
	const CATEGORY = 'ui-feature-gallery';

	/**
	 * Initialize plugin hooks.
	 *
	 * @return void
	 */
	public static function init() {
		add_action( 'plugins_loaded', array( __CLASS__, 'load_textdomain' ) );
		add_action( 'admin_notices', array( __CLASS__, 'render_missing_elementor_notice' ) );
		add_action( 'elementor/elements/categories_registered', array( __CLASS__, 'register_category' ) );
		add_action( 'init', array( __CLASS__, 'register_styles' ) );
		add_action( 'elementor/widgets/register', array( __CLASS__, 'register_widgets' ) );
	}

	/**
	 * Load plugin translations.
	 *
	 * @return void
	 */
	public static function load_textdomain() {
		load_plugin_textdomain(
			'ui-feature-elementor-widgets',
			false,
			dirname( plugin_basename( UI_FEATURE_ELEMENTOR_WIDGETS_FILE ) ) . '/languages'
		);
	}

	/**
	 * Show a notice when Elementor is not active.
	 *
	 * @return void
	 */
	public static function render_missing_elementor_notice() {
		if ( did_action( 'elementor/loaded' ) || ! current_user_can( 'activate_plugins' ) ) {
			return;
		}

		printf(
			'<div class="notice notice-warning"><p>%s</p></div>',
			esc_html__( 'UI Feature Elementor Widgets requires Elementor to be installed and activated.', 'ui-feature-elementor-widgets' )
		);
	}

	/**
	 * Add a dedicated Elementor panel category.
	 *
	 * @param \Elementor\Elements_Manager $elements_manager Elementor elements manager.
	 * @return void
	 */
	public static function register_category( $elements_manager ) {
		$elements_manager->add_category(
			self::CATEGORY,
			array(
				'title' => esc_html__( 'UI Feature Gallery', 'ui-feature-elementor-widgets' ),
				'icon'  => 'fa fa-plug',
			)
		);
	}

	/**
	 * Register shared widget styles.
	 *
	 * @return void
	 */
	public static function register_styles() {
		$style_asset_path = UI_FEATURE_ELEMENTOR_WIDGETS_DIR . 'assets/widgets.css';

		wp_register_style(
			'ui-feature-elementor-widgets',
			UI_FEATURE_ELEMENTOR_WIDGETS_URL . 'assets/widgets.css',
			array(),
			file_exists( $style_asset_path ) ? filemtime( $style_asset_path ) : UI_FEATURE_ELEMENTOR_WIDGETS_VERSION
		);
	}

	/**
	 * Register Elementor widgets.
	 *
	 * @param \Elementor\Widgets_Manager $widgets_manager Elementor widgets manager.
	 * @return void
	 */
	public static function register_widgets( $widgets_manager ) {
		if ( ! did_action( 'elementor/loaded' ) ) {
			return;
		}

		require_once UI_FEATURE_ELEMENTOR_WIDGETS_DIR . 'widgets/class-ui-feature-faq-widget.php';
		require_once UI_FEATURE_ELEMENTOR_WIDGETS_DIR . 'widgets/class-ui-feature-pricing-widget.php';
		require_once UI_FEATURE_ELEMENTOR_WIDGETS_DIR . 'widgets/class-ui-feature-testimonial-widget.php';

		$widgets_manager->register( new UI_Feature_Faq_Widget() );
		$widgets_manager->register( new UI_Feature_Pricing_Widget() );
		$widgets_manager->register( new UI_Feature_Testimonial_Widget() );
	}
}
