<?php
/**
 * Main shortcode plugin bootstrap.
 *
 * @package UIFeatureShortcodes
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Registers shortcode-based UI components.
 */
final class UI_Feature_Shortcodes {
	/**
	 * Initialize plugin hooks.
	 *
	 * @return void
	 */
	public static function init() {
		add_action( 'plugins_loaded', array( __CLASS__, 'load_textdomain' ) );
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'register_assets' ) );
		add_shortcode( 'ui_pricing_table', array( __CLASS__, 'render_pricing_table' ) );
		add_shortcode( 'ui_faq_accordion', array( __CLASS__, 'render_faq_accordion' ) );
		add_shortcode( 'ui_testimonial_carousel', array( __CLASS__, 'render_testimonial_carousel' ) );
		add_shortcode( 'ui_timeline', array( __CLASS__, 'render_timeline' ) );
		add_shortcode( 'ui_modal', array( __CLASS__, 'render_modal' ) );
		add_shortcode( 'ui_toast', array( __CLASS__, 'render_toast' ) );
	}

	/**
	 * Load plugin translations.
	 *
	 * @return void
	 */
	public static function load_textdomain() {
		load_plugin_textdomain(
			'ui-feature-shortcodes',
			false,
			dirname( plugin_basename( UI_FEATURE_SHORTCODES_FILE ) ) . '/languages'
		);
	}

	/**
	 * Register and enqueue lightweight shared assets.
	 *
	 * @return void
	 */
	public static function register_assets() {
		$style_asset_path  = UI_FEATURE_SHORTCODES_DIR . 'assets/shortcodes.css';
		$script_asset_path = UI_FEATURE_SHORTCODES_DIR . 'assets/shortcodes.js';

		wp_enqueue_style(
			'ui-feature-shortcodes',
			UI_FEATURE_SHORTCODES_URL . 'assets/shortcodes.css',
			array(),
			file_exists( $style_asset_path ) ? filemtime( $style_asset_path ) : UI_FEATURE_SHORTCODES_VERSION
		);

		wp_enqueue_script(
			'ui-feature-shortcodes',
			UI_FEATURE_SHORTCODES_URL . 'assets/shortcodes.js',
			array(),
			file_exists( $script_asset_path ) ? filemtime( $script_asset_path ) : UI_FEATURE_SHORTCODES_VERSION,
			true
		);
	}

	/**
	 * Render a pricing table/card shortcode.
	 *
	 * @param array $atts Shortcode attributes.
	 * @return string
	 */
	public static function render_pricing_table( $atts ) {
		$atts = shortcode_atts(
			array(
				'plan'        => __( 'Starter', 'ui-feature-shortcodes' ),
				'price'       => '$19',
				'period'      => __( '/mo', 'ui-feature-shortcodes' ),
				'description' => __( 'A focused plan for small teams launching quickly.', 'ui-feature-shortcodes' ),
				'features'    => __( 'Responsive layout|Scoped styles|No builder lock-in', 'ui-feature-shortcodes' ),
				'button_text' => __( 'Get started', 'ui-feature-shortcodes' ),
				'button_url'  => '',
			),
			(array) $atts,
			'ui_pricing_table'
		);

		$features = self::list_from_attribute( $atts['features'] );

		ob_start();
		?>
		<section class="uisc uisc-pricing" aria-label="<?php echo esc_attr( $atts['plan'] ); ?>">
			<p class="uisc-pricing__eyebrow"><?php echo esc_html__( 'Plan', 'ui-feature-shortcodes' ); ?></p>
			<h2><?php echo esc_html( $atts['plan'] ); ?></h2>
			<p class="uisc-pricing__price"><span><?php echo esc_html( $atts['price'] ); ?></span><?php echo esc_html( $atts['period'] ); ?></p>
			<p><?php echo esc_html( $atts['description'] ); ?></p>
			<?php if ( ! empty( $features ) ) : ?>
				<ul class="uisc-pricing__features">
					<?php foreach ( $features as $feature ) : ?>
						<li><?php echo esc_html( $feature ); ?></li>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>
			<?php if ( '' !== $atts['button_url'] ) : ?>
				<a class="uisc-button" href="<?php echo esc_url( $atts['button_url'] ); ?>"><?php echo esc_html( $atts['button_text'] ); ?></a>
			<?php else : ?>
				<span class="uisc-button" role="presentation"><?php echo esc_html( $atts['button_text'] ); ?></span>
			<?php endif; ?>
		</section>
		<?php
		return ob_get_clean();
	}

	/**
	 * Render an FAQ accordion shortcode.
	 *
	 * @param array       $atts Shortcode attributes.
	 * @param string|null $content Optional shortcode content.
	 * @return string
	 */
	public static function render_faq_accordion( $atts, $content = null ) {
		$atts = shortcode_atts(
			array(
				'question' => __( 'Can I use this in a page builder?', 'ui-feature-shortcodes' ),
				'answer'   => __( 'Yes. Shortcodes can be used in classic content, widgets, templates, and many page-builder shortcode modules.', 'ui-feature-shortcodes' ),
				'open'     => 'false',
			),
			(array) $atts,
			'ui_faq_accordion'
		);

		$answer = null !== $content && '' !== trim( $content ) ? do_shortcode( $content ) : $atts['answer'];

		return sprintf(
			'<details class="uisc uisc-faq"%1$s><summary>%2$s</summary><div class="uisc-faq__answer">%3$s</div></details>',
			self::truthy( $atts['open'] ) ? ' open' : '',
			esc_html( $atts['question'] ),
			wp_kses_post( wpautop( $answer ) )
		);
	}

	/**
	 * Render a testimonial carousel-style shortcode.
	 *
	 * @param array $atts Shortcode attributes.
	 * @return string
	 */
	public static function render_testimonial_carousel( $atts ) {
		$atts = shortcode_atts(
			array(
				'quotes' => __( 'This component made our landing page feel polished.|Shortcodes let our editors reuse UI patterns anywhere.|The scoped CSS worked cleanly with our theme.', 'ui-feature-shortcodes' ),
				'names'  => __( 'Alex Morgan|Jamie Lee|Riley Chen', 'ui-feature-shortcodes' ),
				'roles'  => __( 'Marketing Lead|Content Manager|Founder', 'ui-feature-shortcodes' ),
			),
			(array) $atts,
			'ui_testimonial_carousel'
		);

		$quotes = self::list_from_attribute( $atts['quotes'] );
		$names  = self::list_from_attribute( $atts['names'] );
		$roles  = self::list_from_attribute( $atts['roles'] );

		ob_start();
		?>
		<div class="uisc uisc-testimonials" aria-label="<?php echo esc_attr__( 'Testimonials', 'ui-feature-shortcodes' ); ?>">
			<?php foreach ( $quotes as $index => $quote ) : ?>
				<figure class="uisc-testimonials__item">
					<blockquote><?php echo esc_html( $quote ); ?></blockquote>
					<figcaption>
						<strong><?php echo esc_html( isset( $names[ $index ] ) ? $names[ $index ] : __( 'Customer', 'ui-feature-shortcodes' ) ); ?></strong>
						<span><?php echo esc_html( isset( $roles[ $index ] ) ? $roles[ $index ] : __( 'Reviewer', 'ui-feature-shortcodes' ) ); ?></span>
					</figcaption>
				</figure>
			<?php endforeach; ?>
		</div>
		<?php
		return ob_get_clean();
	}

	/**
	 * Render a timeline shortcode.
	 *
	 * @param array $atts Shortcode attributes.
	 * @return string
	 */
	public static function render_timeline( $atts ) {
		$atts = shortcode_atts(
			array(
				'items' => __( 'Discovery|Planning|Launch', 'ui-feature-shortcodes' ),
				'text'  => __( 'Review goals and content.|Map the implementation steps.|Publish the finished experience.', 'ui-feature-shortcodes' ),
			),
			(array) $atts,
			'ui_timeline'
		);

		$items = self::list_from_attribute( $atts['items'] );
		$text  = self::list_from_attribute( $atts['text'] );

		ob_start();
		?>
		<ol class="uisc uisc-timeline">
			<?php foreach ( $items as $index => $item ) : ?>
				<li>
					<span class="uisc-timeline__marker"><?php echo esc_html( (string) ( $index + 1 ) ); ?></span>
					<div>
						<strong><?php echo esc_html( $item ); ?></strong>
						<p><?php echo esc_html( isset( $text[ $index ] ) ? $text[ $index ] : '' ); ?></p>
					</div>
				</li>
			<?php endforeach; ?>
		</ol>
		<?php
		return ob_get_clean();
	}

	/**
	 * Render a modal shortcode.
	 *
	 * @param array       $atts Shortcode attributes.
	 * @param string|null $content Optional shortcode content.
	 * @return string
	 */
	public static function render_modal( $atts, $content = null ) {
		$atts = shortcode_atts(
			array(
				'id'          => 'ui-feature-modal',
				'button_text' => __( 'Open modal', 'ui-feature-shortcodes' ),
				'title'       => __( 'Newsletter signup', 'ui-feature-shortcodes' ),
				'content'     => __( 'Add your modal content with shortcode attributes or enclosed shortcode content.', 'ui-feature-shortcodes' ),
			),
			(array) $atts,
			'ui_modal'
		);

		$modal_id      = sanitize_html_class( $atts['id'] );
		$modal_content = null !== $content && '' !== trim( $content ) ? do_shortcode( $content ) : $atts['content'];

		ob_start();
		?>
		<div class="uisc-modal-wrap">
			<button class="uisc-button" type="button" data-uisc-modal-open="<?php echo esc_attr( $modal_id ); ?>"><?php echo esc_html( $atts['button_text'] ); ?></button>
			<div class="uisc-modal" id="<?php echo esc_attr( $modal_id ); ?>" role="dialog" aria-modal="true" aria-labelledby="<?php echo esc_attr( $modal_id ); ?>-title" hidden>
				<div class="uisc-modal__backdrop" data-uisc-modal-close></div>
				<div class="uisc-modal__panel" role="document">
					<button class="uisc-modal__close" type="button" data-uisc-modal-close aria-label="<?php echo esc_attr__( 'Close modal', 'ui-feature-shortcodes' ); ?>">&times;</button>
					<h2 id="<?php echo esc_attr( $modal_id ); ?>-title"><?php echo esc_html( $atts['title'] ); ?></h2>
					<div class="uisc-modal__content"><?php echo wp_kses_post( wpautop( $modal_content ) ); ?></div>
				</div>
			</div>
		</div>
		<?php
		return ob_get_clean();
	}

	/**
	 * Render a toast shortcode.
	 *
	 * @param array       $atts Shortcode attributes.
	 * @param string|null $content Optional shortcode content.
	 * @return string
	 */
	public static function render_toast( $atts, $content = null ) {
		$atts = shortcode_atts(
			array(
				'type'    => 'success',
				'message' => __( 'Your changes were saved successfully.', 'ui-feature-shortcodes' ),
			),
			(array) $atts,
			'ui_toast'
		);

		$type    = sanitize_html_class( $atts['type'] );
		$message = null !== $content && '' !== trim( $content ) ? wp_strip_all_tags( do_shortcode( $content ) ) : $atts['message'];

		return sprintf(
			'<div class="uisc uisc-toast uisc-toast--%1$s" role="status"><span>%2$s</span><button type="button" data-uisc-toast-close aria-label="%3$s">&times;</button></div>',
			esc_attr( $type ),
			esc_html( $message ),
			esc_attr__( 'Dismiss notification', 'ui-feature-shortcodes' )
		);
	}

	/**
	 * Convert a pipe-separated attribute to a clean list.
	 *
	 * @param string $value Attribute value.
	 * @return array
	 */
	private static function list_from_attribute( $value ) {
		$items = array_map( 'trim', explode( '|', (string) $value ) );

		return array_values( array_filter( $items, 'strlen' ) );
	}

	/**
	 * Determine whether a shortcode attribute should be treated as true.
	 *
	 * @param string $value Attribute value.
	 * @return bool
	 */
	private static function truthy( $value ) {
		return in_array( strtolower( (string) $value ), array( '1', 'true', 'yes', 'open' ), true );
	}
}
