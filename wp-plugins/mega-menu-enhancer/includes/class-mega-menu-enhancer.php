<?php
/**
 * Main plugin bootstrap.
 *
 * @package MegaMenuEnhancer
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Registers shortcode, assets, and render helpers for enhanced menus.
 */
final class Mega_Menu_Enhancer {
	/**
	 * Initialize plugin hooks.
	 *
	 * @return void
	 */
	public static function init() {
		add_action( 'plugins_loaded', array( __CLASS__, 'load_textdomain' ) );
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'register_assets' ) );
		add_shortcode( 'mega_menu_enhancer', array( __CLASS__, 'render_shortcode' ) );
		add_shortcode( 'mme_menu', array( __CLASS__, 'render_shortcode' ) );
	}

	/**
	 * Load translations.
	 *
	 * @return void
	 */
	public static function load_textdomain() {
		load_plugin_textdomain(
			'mega-menu-enhancer',
			false,
			dirname( plugin_basename( MEGA_MENU_ENHANCER_FILE ) ) . '/languages'
		);
	}

	/**
	 * Register scoped frontend assets.
	 *
	 * @return void
	 */
	public static function register_assets() {
		$style_asset_path  = MEGA_MENU_ENHANCER_DIR . 'assets/mega-menu.css';
		$script_asset_path = MEGA_MENU_ENHANCER_DIR . 'assets/mega-menu.js';

		wp_register_style(
			'mega-menu-enhancer',
			MEGA_MENU_ENHANCER_URL . 'assets/mega-menu.css',
			array(),
			file_exists( $style_asset_path ) ? filemtime( $style_asset_path ) : MEGA_MENU_ENHANCER_VERSION
		);

		wp_register_script(
			'mega-menu-enhancer',
			MEGA_MENU_ENHANCER_URL . 'assets/mega-menu.js',
			array(),
			file_exists( $script_asset_path ) ? filemtime( $script_asset_path ) : MEGA_MENU_ENHANCER_VERSION,
			true
		);
	}

	/**
	 * Render shortcode output.
	 *
	 * @param array $atts Shortcode attributes.
	 * @return string
	 */
	public static function render_shortcode( $atts ) {
		$attributes = shortcode_atts(
			array(
				'menu'           => '',
				'theme_location' => '',
				'columns'        => '3',
				'promo_title'    => '',
				'promo_text'     => '',
				'promo_url'      => '',
				'promo_label'    => __( 'Learn more', 'mega-menu-enhancer' ),
			),
			(array) $atts,
			'mega_menu_enhancer'
		);

		return self::render_menu( $attributes );
	}

	/**
	 * Render an enhanced WordPress menu.
	 *
	 * @param array $attributes Render attributes.
	 * @return string
	 */
	public static function render_menu( $attributes = array() ) {
		wp_enqueue_style( 'mega-menu-enhancer' );
		wp_enqueue_script( 'mega-menu-enhancer' );

		$attributes = wp_parse_args(
			(array) $attributes,
			array(
				'menu'           => '',
				'theme_location' => '',
				'columns'        => '3',
				'promo_title'    => '',
				'promo_text'     => '',
				'promo_url'      => '',
				'promo_label'    => __( 'Learn more', 'mega-menu-enhancer' ),
			)
		);

		$columns = min( 4, max( 2, absint( $attributes['columns'] ) ) );
		$promo   = array(
			'title' => sanitize_text_field( $attributes['promo_title'] ),
			'text'  => sanitize_text_field( $attributes['promo_text'] ),
			'url'   => esc_url_raw( $attributes['promo_url'] ),
			'label' => sanitize_text_field( $attributes['promo_label'] ),
		);

		$args = array(
			'container'      => 'nav',
			'container_class' => 'mme-nav',
			'container_id'   => 'mme-' . wp_generate_uuid4(),
			'menu_class'     => 'mme-menu mme-menu--columns-' . $columns,
			'fallback_cb'    => false,
			'walker'         => new Mega_Menu_Enhancer_Walker( $columns, $promo ),
			'echo'           => false,
			'depth'          => 3,
		);

		if ( '' !== $attributes['menu'] ) {
			$args['menu'] = $attributes['menu'];
		}

		if ( '' !== $attributes['theme_location'] ) {
			$args['theme_location'] = $attributes['theme_location'];
		}

		$menu = wp_nav_menu( $args );

		return false !== $menu ? $menu : '';
	}
}

if ( ! function_exists( 'mega_menu_enhancer_render_menu' ) ) {
	/**
	 * Theme helper for rendering an enhanced menu in templates.
	 *
	 * @param array $attributes Render attributes.
	 * @return string
	 */
	function mega_menu_enhancer_render_menu( $attributes = array() ) {
		return Mega_Menu_Enhancer::render_menu( $attributes );
	}
}
