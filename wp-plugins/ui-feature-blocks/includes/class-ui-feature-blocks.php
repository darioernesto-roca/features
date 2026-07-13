<?php
/**
 * Main plugin bootstrap.
 *
 * @package UIFeatureBlocks
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Registers assets and dynamic Gutenberg blocks.
 */
final class UI_Feature_Blocks {
	/**
	 * Initialize plugin hooks.
	 *
	 * @return void
	 */
	public static function init() {
		add_action( 'init', array( __CLASS__, 'register_assets' ) );
		add_action( 'init', array( __CLASS__, 'register_blocks' ) );
	}

	/**
	 * Register shared editor and frontend assets.
	 *
	 * @return void
	 */
	public static function register_assets() {
		$editor_asset_path = UI_FEATURE_BLOCKS_DIR . 'assets/editor.js';
		$style_asset_path  = UI_FEATURE_BLOCKS_DIR . 'assets/blocks.css';

		wp_register_script(
			'ui-feature-blocks-editor',
			UI_FEATURE_BLOCKS_URL . 'assets/editor.js',
			array( 'wp-blocks', 'wp-components', 'wp-element', 'wp-i18n', 'wp-block-editor' ),
			file_exists( $editor_asset_path ) ? filemtime( $editor_asset_path ) : UI_FEATURE_BLOCKS_VERSION,
			true
		);

		wp_register_style(
			'ui-feature-blocks-style',
			UI_FEATURE_BLOCKS_URL . 'assets/blocks.css',
			array(),
			file_exists( $style_asset_path ) ? filemtime( $style_asset_path ) : UI_FEATURE_BLOCKS_VERSION
		);
	}

	/**
	 * Register the initial block collection.
	 *
	 * @return void
	 */
	public static function register_blocks() {
		if ( ! function_exists( 'register_block_type' ) ) {
			return;
		}

		register_block_type(
			'ui-feature/faq-accordion',
			array(
				'api_version'     => 2,
				'editor_script'   => 'ui-feature-blocks-editor',
				'style'           => 'ui-feature-blocks-style',
				'render_callback' => array( __CLASS__, 'render_faq_accordion' ),
				'attributes'      => array(
					'question' => array(
						'type'    => 'string',
						'default' => 'What makes this component reusable?',
					),
					'answer'   => array(
						'type'    => 'string',
						'default' => 'It is rendered as a scoped WordPress block so it can be reused without modifying a theme.',
					),
				),
			)
		);

		register_block_type(
			'ui-feature/pricing-card',
			array(
				'api_version'     => 2,
				'editor_script'   => 'ui-feature-blocks-editor',
				'style'           => 'ui-feature-blocks-style',
				'render_callback' => array( __CLASS__, 'render_pricing_card' ),
				'attributes'      => array(
					'planName'    => array(
						'type'    => 'string',
						'default' => 'Starter',
					),
					'price'       => array(
						'type'    => 'string',
						'default' => '$19',
					),
					'description' => array(
						'type'    => 'string',
						'default' => 'A focused plan for small teams launching quickly.',
					),
					'buttonText'  => array(
						'type'    => 'string',
						'default' => 'Get started',
					),
				),
			)
		);

		register_block_type(
			'ui-feature/testimonial',
			array(
				'api_version'     => 2,
				'editor_script'   => 'ui-feature-blocks-editor',
				'style'           => 'ui-feature-blocks-style',
				'render_callback' => array( __CLASS__, 'render_testimonial' ),
				'attributes'      => array(
					'quote' => array(
						'type'    => 'string',
						'default' => 'This block helped us add a polished section without custom theme work.',
					),
					'name'  => array(
						'type'    => 'string',
						'default' => 'Alex Morgan',
					),
					'role'  => array(
						'type'    => 'string',
						'default' => 'Marketing Lead',
					),
				),
			)
		);
	}

	/**
	 * Render an accessible FAQ accordion.
	 *
	 * @param array $attributes Block attributes.
	 * @return string
	 */
	public static function render_faq_accordion( $attributes ) {
		$question = self::get_attribute( $attributes, 'question' );
		$answer   = self::get_attribute( $attributes, 'answer' );

		return sprintf(
			'<details class="wp-block-ui-feature-faq"><summary>%1$s</summary><div class="wp-block-ui-feature-faq__answer">%2$s</div></details>',
			esc_html( $question ),
			wp_kses_post( wpautop( $answer ) )
		);
	}

	/**
	 * Render a pricing card.
	 *
	 * @param array $attributes Block attributes.
	 * @return string
	 */
	public static function render_pricing_card( $attributes ) {
		$plan_name   = self::get_attribute( $attributes, 'planName' );
		$price       = self::get_attribute( $attributes, 'price' );
		$description = self::get_attribute( $attributes, 'description' );
		$button_text = self::get_attribute( $attributes, 'buttonText' );

		return sprintf(
			'<section class="wp-block-ui-feature-pricing"><p class="wp-block-ui-feature-pricing__eyebrow">%1$s</p><h2>%2$s</h2><p class="wp-block-ui-feature-pricing__price">%3$s</p><p>%4$s</p><span class="wp-block-ui-feature-pricing__button">%5$s</span></section>',
			esc_html__( 'Plan', 'ui-feature-blocks' ),
			esc_html( $plan_name ),
			esc_html( $price ),
			esc_html( $description ),
			esc_html( $button_text )
		);
	}

	/**
	 * Render a testimonial card.
	 *
	 * @param array $attributes Block attributes.
	 * @return string
	 */
	public static function render_testimonial( $attributes ) {
		$quote = self::get_attribute( $attributes, 'quote' );
		$name  = self::get_attribute( $attributes, 'name' );
		$role  = self::get_attribute( $attributes, 'role' );

		return sprintf(
			'<figure class="wp-block-ui-feature-testimonial"><blockquote>%1$s</blockquote><figcaption><strong>%2$s</strong><span>%3$s</span></figcaption></figure>',
			wp_kses_post( wpautop( $quote ) ),
			esc_html( $name ),
			esc_html( $role )
		);
	}

	/**
	 * Safely read a string attribute.
	 *
	 * @param array  $attributes Block attributes.
	 * @param string $key Attribute key.
	 * @return string
	 */
	private static function get_attribute( $attributes, $key ) {
		return isset( $attributes[ $key ] ) && is_string( $attributes[ $key ] ) ? $attributes[ $key ] : '';
	}
}
