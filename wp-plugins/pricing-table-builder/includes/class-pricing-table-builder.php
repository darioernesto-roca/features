<?php
/**
 * Main plugin bootstrap.
 *
 * @package PricingTableBuilder
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Registers pricing table shortcode, block, assets, and rendering.
 */
final class Pricing_Table_Builder {
	/**
	 * Initialize plugin hooks.
	 *
	 * @return void
	 */
	public static function init() {
		add_action( 'plugins_loaded', array( __CLASS__, 'load_textdomain' ) );
		add_action( 'init', array( __CLASS__, 'register_assets' ) );
		add_action( 'init', array( __CLASS__, 'register_block' ) );
		add_shortcode( 'pricing_table_builder', array( __CLASS__, 'render_shortcode' ) );
		add_shortcode( 'ptb_pricing_table', array( __CLASS__, 'render_shortcode' ) );
	}

	/**
	 * Load plugin translations.
	 *
	 * @return void
	 */
	public static function load_textdomain() {
		load_plugin_textdomain(
			'pricing-table-builder',
			false,
			dirname( plugin_basename( PRICING_TABLE_BUILDER_FILE ) ) . '/languages'
		);
	}

	/**
	 * Register shared frontend and editor assets.
	 *
	 * @return void
	 */
	public static function register_assets() {
		$editor_asset_path = PRICING_TABLE_BUILDER_DIR . 'assets/editor.js';
		$style_asset_path  = PRICING_TABLE_BUILDER_DIR . 'assets/pricing-table.css';
		$script_asset_path = PRICING_TABLE_BUILDER_DIR . 'assets/pricing-table.js';

		wp_register_script(
			'pricing-table-builder-editor',
			PRICING_TABLE_BUILDER_URL . 'assets/editor.js',
			array( 'wp-blocks', 'wp-components', 'wp-element', 'wp-i18n', 'wp-block-editor' ),
			file_exists( $editor_asset_path ) ? filemtime( $editor_asset_path ) : PRICING_TABLE_BUILDER_VERSION,
			true
		);

		wp_register_style(
			'pricing-table-builder',
			PRICING_TABLE_BUILDER_URL . 'assets/pricing-table.css',
			array(),
			file_exists( $style_asset_path ) ? filemtime( $style_asset_path ) : PRICING_TABLE_BUILDER_VERSION
		);

		wp_register_script(
			'pricing-table-builder',
			PRICING_TABLE_BUILDER_URL . 'assets/pricing-table.js',
			array(),
			file_exists( $script_asset_path ) ? filemtime( $script_asset_path ) : PRICING_TABLE_BUILDER_VERSION,
			true
		);
	}

	/**
	 * Register dynamic pricing table block.
	 *
	 * @return void
	 */
	public static function register_block() {
		if ( ! function_exists( 'register_block_type' ) ) {
			return;
		}

		register_block_type(
			'pricing-table-builder/table',
			array(
				'api_version'     => 2,
				'editor_script'   => 'pricing-table-builder-editor',
				'style'           => 'pricing-table-builder',
				'script'          => 'pricing-table-builder',
				'render_callback' => array( __CLASS__, 'render_block' ),
				'attributes'      => self::get_attribute_schema(),
			)
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
			array_merge(
				self::get_default_attributes(),
				array(
					'monthly_prices' => '',
					'yearly_prices'  => '',
					'monthly_label'  => '',
					'yearly_label'   => '',
					'cta_texts'      => '',
					'cta_urls'       => '',
					'product_ids'    => '',
				)
			),
			(array) $atts,
			'pricing_table_builder'
		);

		$aliases = array(
			'monthly_prices' => 'monthlyPrices',
			'yearly_prices'  => 'yearlyPrices',
			'monthly_label'  => 'monthlyLabel',
			'yearly_label'   => 'yearlyLabel',
			'cta_texts'      => 'ctaTexts',
			'cta_urls'       => 'ctaUrls',
			'product_ids'    => 'productIds',
		);

		foreach ( $aliases as $alias => $key ) {
			if ( '' !== $attributes[ $alias ] ) {
				$attributes[ $key ] = $attributes[ $alias ];
			}
		}

		return self::render_table( $attributes );
	}

	/**
	 * Render block output.
	 *
	 * @param array $attributes Block attributes.
	 * @return string
	 */
	public static function render_block( $attributes ) {
		return self::render_table( wp_parse_args( (array) $attributes, self::get_default_attributes() ) );
	}

	/**
	 * Render pricing table markup.
	 *
	 * @param array $attributes Pricing table attributes.
	 * @return string
	 */
	private static function render_table( $attributes ) {
		wp_enqueue_style( 'pricing-table-builder' );
		wp_enqueue_script( 'pricing-table-builder' );

		$attributes = wp_parse_args( (array) $attributes, self::get_default_attributes() );
		$plans      = self::build_plans( $attributes );
		$features   = self::parse_feature_rows( $attributes['features'] );
		$featured   = absint( $attributes['featured'] );
		$table_id   = 'ptb-' . wp_generate_uuid4();

		ob_start();
		?>
		<section class="ptb-pricing" id="<?php echo esc_attr( $table_id ); ?>" data-ptb-pricing data-ptb-monthly-label="<?php echo esc_attr( $attributes['monthlyLabel'] ); ?>" data-ptb-yearly-label="<?php echo esc_attr( $attributes['yearlyLabel'] ); ?>">
			<header class="ptb-pricing__header">
				<h2><?php echo esc_html( $attributes['title'] ); ?></h2>
				<?php if ( '' !== $attributes['description'] ) : ?>
					<p><?php echo esc_html( $attributes['description'] ); ?></p>
				<?php endif; ?>
				<div class="ptb-pricing__toggle" role="group" aria-label="<?php echo esc_attr__( 'Billing period', 'pricing-table-builder' ); ?>">
					<button type="button" class="is-active" data-ptb-period="monthly"><?php echo esc_html__( 'Monthly', 'pricing-table-builder' ); ?></button>
					<button type="button" data-ptb-period="yearly"><?php echo esc_html__( 'Yearly', 'pricing-table-builder' ); ?></button>
				</div>
			</header>

			<div class="ptb-pricing__plans" style="--ptb-plan-count: <?php echo esc_attr( (string) max( 1, count( $plans ) ) ); ?>;">
				<?php foreach ( $plans as $index => $plan ) : ?>
					<article class="ptb-plan<?php echo $featured === $index ? esc_attr( ' is-featured' ) : ''; ?>">
						<?php if ( $featured === $index ) : ?>
							<p class="ptb-plan__ribbon"><?php echo esc_html( $attributes['ribbon'] ); ?></p>
						<?php endif; ?>
						<h3><?php echo esc_html( $plan['name'] ); ?></h3>
						<p class="ptb-plan__price" data-ptb-monthly="<?php echo esc_attr( $plan['monthly'] ); ?>" data-ptb-yearly="<?php echo esc_attr( $plan['yearly'] ); ?>">
							<span class="ptb-plan__currency"><?php echo esc_html( $attributes['currency'] ); ?></span><span data-ptb-price><?php echo esc_html( $plan['monthly'] ); ?></span><small data-ptb-period-label><?php echo esc_html( $attributes['monthlyLabel'] ); ?></small>
						</p>
						<p><?php echo esc_html( $plan['description'] ); ?></p>
						<a class="ptb-plan__button" href="<?php echo esc_url( $plan['url'] ); ?>"><?php echo esc_html( $plan['cta'] ); ?></a>
					</article>
				<?php endforeach; ?>
			</div>

			<?php if ( ! empty( $features ) ) : ?>
				<div class="ptb-comparison" role="table" style="--ptb-plan-count: <?php echo esc_attr( (string) max( 1, count( $plans ) ) ); ?>;" aria-label="<?php echo esc_attr__( 'Feature comparison', 'pricing-table-builder' ); ?>">
					<div class="ptb-comparison__row ptb-comparison__row--head" role="row">
						<strong role="columnheader"><?php echo esc_html__( 'Feature', 'pricing-table-builder' ); ?></strong>
						<?php foreach ( $plans as $plan ) : ?>
							<strong role="columnheader"><?php echo esc_html( $plan['name'] ); ?></strong>
						<?php endforeach; ?>
					</div>
					<?php foreach ( $features as $feature ) : ?>
						<div class="ptb-comparison__row" role="row">
							<span role="cell"><?php echo esc_html( $feature['label'] ); ?></span>
							<?php foreach ( $plans as $index => $plan ) : ?>
								<span role="cell"><?php echo esc_html( isset( $feature['values'][ $index ] ) ? $feature['values'][ $index ] : '—' ); ?></span>
							<?php endforeach; ?>
						</div>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</section>
		<?php
		return ob_get_clean();
	}

	/**
	 * Build normalized plan data from pipe-separated attributes.
	 *
	 * @param array $attributes Pricing table attributes.
	 * @return array
	 */
	private static function build_plans( $attributes ) {
		$names        = self::list_from_attribute( $attributes['plans'] );
		$monthly      = self::list_from_attribute( $attributes['monthlyPrices'] );
		$yearly       = self::list_from_attribute( $attributes['yearlyPrices'] );
		$descriptions = self::list_from_attribute( $attributes['descriptions'] );
		$cta_texts    = self::list_from_attribute( $attributes['ctaTexts'] );
		$cta_urls     = self::list_from_attribute( $attributes['ctaUrls'] );
		$product_ids  = self::list_from_attribute( $attributes['productIds'] );
		$plans        = array();

		foreach ( $names as $index => $name ) {
			$plans[] = array(
				'name'        => $name,
				'monthly'     => isset( $monthly[ $index ] ) ? $monthly[ $index ] : '0',
				'yearly'      => isset( $yearly[ $index ] ) ? $yearly[ $index ] : '0',
				'description' => isset( $descriptions[ $index ] ) ? $descriptions[ $index ] : '',
				'cta'         => isset( $cta_texts[ $index ] ) ? $cta_texts[ $index ] : __( 'Get started', 'pricing-table-builder' ),
				'url'         => self::resolve_cta_url( isset( $cta_urls[ $index ] ) ? $cta_urls[ $index ] : '', isset( $product_ids[ $index ] ) ? $product_ids[ $index ] : '' ),
			);
		}

		return $plans;
	}

	/**
	 * Resolve CTA URL, optionally using WooCommerce product links.
	 *
	 * @param string $fallback_url Manual CTA URL.
	 * @param string $product_id Optional WooCommerce product ID.
	 * @return string
	 */
	private static function resolve_cta_url( $fallback_url, $product_id ) {
		$product_id = absint( $product_id );

		if ( $product_id > 0 && function_exists( 'wc_get_product' ) ) {
			$product = wc_get_product( $product_id );

			if ( $product ) {
				return $product->add_to_cart_url();
			}
		}

		return '' !== $fallback_url ? $fallback_url : '#';
	}

	/**
	 * Parse comparison rows from "Feature:Plan 1|Plan 2;Other:Yes|No" syntax.
	 *
	 * @param string $value Feature row string.
	 * @return array
	 */
	private static function parse_feature_rows( $value ) {
		$rows   = array_filter( array_map( 'trim', explode( ';', (string) $value ) ) );
		$output = array();

		foreach ( $rows as $row ) {
			$parts = array_map( 'trim', explode( ':', $row, 2 ) );

			if ( 2 !== count( $parts ) ) {
				continue;
			}

			$output[] = array(
				'label'  => $parts[0],
				'values' => self::list_from_attribute( $parts[1] ),
			);
		}

		return $output;
	}

	/**
	 * Convert a pipe-separated attribute to a list.
	 *
	 * @param string $value Attribute value.
	 * @return array
	 */
	private static function list_from_attribute( $value ) {
		$items = array_map( 'trim', explode( '|', (string) $value ) );

		return array_values( array_filter( $items, 'strlen' ) );
	}

	/**
	 * Get default shortcode and block attributes.
	 *
	 * @return array
	 */
	private static function get_default_attributes() {
		return array(
			'title'         => __( 'Choose your plan', 'pricing-table-builder' ),
			'description'   => __( 'Switch between monthly and yearly pricing, then compare included features.', 'pricing-table-builder' ),
			'currency'      => '$',
			'monthlyLabel'  => __( '/mo', 'pricing-table-builder' ),
			'yearlyLabel'   => __( '/yr', 'pricing-table-builder' ),
			'plans'         => __( 'Starter|Growth|Scale', 'pricing-table-builder' ),
			'monthlyPrices' => '19|49|99',
			'yearlyPrices'  => '190|490|990',
			'descriptions'  => __( 'Launch quickly|Grow with advanced tools|Scale with premium support', 'pricing-table-builder' ),
			'ctaTexts'      => __( 'Start now|Choose Growth|Contact sales', 'pricing-table-builder' ),
			'ctaUrls'       => '#|#|#',
			'productIds'    => '',
			'featured'      => '1',
			'ribbon'        => __( 'Most Popular', 'pricing-table-builder' ),
			'features'      => __( 'Projects:5|25|Unlimited;Support:Email|Priority|Dedicated;Storage:10GB|100GB|1TB', 'pricing-table-builder' ),
		);
	}

	/**
	 * Get block attribute schema.
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
}
