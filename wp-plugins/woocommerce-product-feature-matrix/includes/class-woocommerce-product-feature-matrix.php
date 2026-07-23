<?php
/**
 * Main plugin bootstrap.
 *
 * @package WooCommerceProductFeatureMatrix
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Registers WooCommerce product feature matrix assets, block, shortcode, and rendering.
 */
final class WooCommerce_Product_Feature_Matrix {
	/**
	 * Initialize plugin hooks.
	 *
	 * @return void
	 */
	public static function init() {
		add_action( 'plugins_loaded', array( __CLASS__, 'load_textdomain' ) );
		add_action( 'init', array( __CLASS__, 'register_assets' ) );
		add_action( 'init', array( __CLASS__, 'register_block' ) );
		add_action( 'admin_notices', array( __CLASS__, 'render_woocommerce_notice' ) );
		add_shortcode( 'wc_product_feature_matrix', array( __CLASS__, 'render_shortcode' ) );
		add_shortcode( 'product_feature_matrix', array( __CLASS__, 'render_shortcode' ) );
	}

	/**
	 * Load translations.
	 *
	 * @return void
	 */
	public static function load_textdomain() {
		load_plugin_textdomain(
			'woocommerce-product-feature-matrix',
			false,
			dirname( plugin_basename( WPFM_FILE ) ) . '/languages'
		);
	}

	/**
	 * Register frontend and editor assets.
	 *
	 * @return void
	 */
	public static function register_assets() {
		$style_path  = WPFM_DIR . 'assets/feature-matrix.css';
		$script_path = WPFM_DIR . 'assets/feature-matrix.js';
		$editor_path = WPFM_DIR . 'assets/editor.js';

		wp_register_style(
			'woocommerce-product-feature-matrix',
			WPFM_URL . 'assets/feature-matrix.css',
			array(),
			file_exists( $style_path ) ? filemtime( $style_path ) : WPFM_VERSION
		);

		wp_register_script(
			'woocommerce-product-feature-matrix',
			WPFM_URL . 'assets/feature-matrix.js',
			array(),
			file_exists( $script_path ) ? filemtime( $script_path ) : WPFM_VERSION,
			true
		);

		wp_register_script(
			'woocommerce-product-feature-matrix-editor',
			WPFM_URL . 'assets/editor.js',
			array( 'wp-blocks', 'wp-element', 'wp-components', 'wp-block-editor', 'wp-i18n' ),
			file_exists( $editor_path ) ? filemtime( $editor_path ) : WPFM_VERSION,
			true
		);
	}

	/**
	 * Register dynamic comparison block.
	 *
	 * @return void
	 */
	public static function register_block() {
		register_block_type(
			'woocommerce-product-feature-matrix/matrix',
			array(
				'api_version'     => 2,
				'editor_script'   => 'woocommerce-product-feature-matrix-editor',
				'style'           => 'woocommerce-product-feature-matrix',
				'render_callback' => array( __CLASS__, 'render_matrix' ),
				'attributes'      => array(
					'title'       => array( 'type' => 'string', 'default' => 'Compare products' ),
					'products'    => array( 'type' => 'string', 'default' => '' ),
					'attributes'  => array( 'type' => 'string', 'default' => '' ),
					'showFilters' => array( 'type' => 'boolean', 'default' => true ),
				),
			)
		);
	}

	/**
	 * Render an admin notice when WooCommerce is not active.
	 *
	 * @return void
	 */
	public static function render_woocommerce_notice() {
		if ( self::is_woocommerce_available() ) {
			return;
		}

		printf(
			'<div class="notice notice-warning"><p>%s</p></div>',
			esc_html__( 'WooCommerce Product Feature Matrix requires WooCommerce to render product comparisons.', 'woocommerce-product-feature-matrix' )
		);
	}

	/**
	 * Render shortcode output.
	 *
	 * @param array $atts Shortcode attributes.
	 * @return string
	 */
	public static function render_shortcode( $atts ) {
		return self::render_matrix( shortcode_atts( self::default_attributes(), $atts, 'wc_product_feature_matrix' ) );
	}

	/**
	 * Render product matrix.
	 *
	 * @param array $attributes Render attributes.
	 * @return string
	 */
	public static function render_matrix( $attributes = array() ) {
		$attributes = self::normalize_attributes( $attributes );

		wp_enqueue_style( 'woocommerce-product-feature-matrix' );

		if ( ! self::is_woocommerce_available() ) {
			return self::render_missing_woocommerce();
		}

		$products = self::get_products( $attributes['products'] );

		if ( empty( $products ) ) {
			return self::render_empty_state();
		}

		$attribute_rows = self::get_attribute_rows( $products, $attributes['attributes'] );

		wp_enqueue_script( 'woocommerce-product-feature-matrix' );

		ob_start();
		?>
		<section class="wpfm-matrix" data-wpfm-matrix>
			<div class="wpfm-matrix__header">
				<h2 class="wpfm-matrix__title"><?php echo esc_html( $attributes['title'] ); ?></h2>
				<?php if ( self::truthy( $attributes['showFilters'] ) && ! empty( $attribute_rows ) ) : ?>
					<label class="wpfm-filter">
						<span><?php echo esc_html__( 'Filter features', 'woocommerce-product-feature-matrix' ); ?></span>
						<select data-wpfm-filter>
							<option value="all"><?php echo esc_html__( 'All attributes', 'woocommerce-product-feature-matrix' ); ?></option>
							<?php foreach ( $attribute_rows as $row ) : ?>
								<option value="<?php echo esc_attr( $row['key'] ); ?>"><?php echo esc_html( $row['label'] ); ?></option>
							<?php endforeach; ?>
						</select>
					</label>
				<?php endif; ?>
			</div>
			<div class="wpfm-matrix__scroll" tabindex="0">
				<table class="wpfm-table">
					<thead>
						<tr>
							<th class="wpfm-table__feature wpfm-table__sticky" scope="col"><?php echo esc_html__( 'Feature', 'woocommerce-product-feature-matrix' ); ?></th>
							<?php foreach ( $products as $product ) : ?>
								<th class="wpfm-product-heading wpfm-table__sticky" scope="col">
									<a class="wpfm-product-heading__link" href="<?php echo esc_url( get_permalink( $product->get_id() ) ); ?>">
										<?php echo wp_kses_post( $product->get_image( 'woocommerce_thumbnail', array( 'class' => 'wpfm-product-heading__image' ) ) ); ?>
										<span class="wpfm-product-heading__name"><?php echo esc_html( $product->get_name() ); ?></span>
									</a>
								</th>
							<?php endforeach; ?>
						</tr>
					</thead>
					<tbody>
						<?php self::render_core_rows( $products ); ?>
						<?php foreach ( $attribute_rows as $row ) : ?>
							<tr data-wpfm-row="<?php echo esc_attr( $row['key'] ); ?>">
								<th scope="row"><?php echo esc_html( $row['label'] ); ?></th>
								<?php foreach ( $products as $product ) : ?>
									<td><?php echo wp_kses_post( self::get_product_attribute_value( $product, $row['key'] ) ); ?></td>
								<?php endforeach; ?>
							</tr>
						<?php endforeach; ?>
						<?php self::render_cart_row( $products ); ?>
					</tbody>
				</table>
			</div>
		</section>
		<?php
		return ob_get_clean();
	}

	/**
	 * Render core product rows.
	 *
	 * @param WC_Product[] $products Products.
	 * @return void
	 */
	private static function render_core_rows( $products ) {
		$rows = array(
			'price'  => esc_html__( 'Price', 'woocommerce-product-feature-matrix' ),
			'rating' => esc_html__( 'Rating', 'woocommerce-product-feature-matrix' ),
			'stock'  => esc_html__( 'Stock', 'woocommerce-product-feature-matrix' ),
		);

		foreach ( $rows as $key => $label ) {
			echo '<tr data-wpfm-row="core-' . esc_attr( $key ) . '">';
			echo '<th scope="row">' . esc_html( $label ) . '</th>';

			foreach ( $products as $product ) {
				echo '<td>' . wp_kses_post( self::get_core_value( $product, $key ) ) . '</td>';
			}

			echo '</tr>';
		}
	}

	/**
	 * Render add-to-cart row.
	 *
	 * @param WC_Product[] $products Products.
	 * @return void
	 */
	private static function render_cart_row( $products ) {
		echo '<tr class="wpfm-cart-row" data-wpfm-row="core-cart">';
		echo '<th scope="row">' . esc_html__( 'Action', 'woocommerce-product-feature-matrix' ) . '</th>';

		foreach ( $products as $product ) {
			echo '<td>' . wp_kses_post( self::get_cart_button( $product ) ) . '</td>';
		}

		echo '</tr>';
	}

	/**
	 * Get core product value.
	 *
	 * @param WC_Product $product Product object.
	 * @param string     $key Row key.
	 * @return string
	 */
	private static function get_core_value( $product, $key ) {
		if ( 'price' === $key ) {
			return $product->get_price_html() ? $product->get_price_html() : '&mdash;';
		}

		if ( 'rating' === $key ) {
			$rating = function_exists( 'wc_get_rating_html' ) ? wc_get_rating_html( $product->get_average_rating(), $product->get_rating_count() ) : '';
			return $rating ? $rating : esc_html__( 'No reviews yet', 'woocommerce-product-feature-matrix' );
		}

		if ( 'stock' === $key ) {
			$availability = $product->get_availability();
			return ! empty( $availability['availability'] ) ? $availability['availability'] : esc_html__( 'Available', 'woocommerce-product-feature-matrix' );
		}

		return '&mdash;';
	}

	/**
	 * Get add-to-cart button markup.
	 *
	 * @param WC_Product $product Product object.
	 * @return string
	 */
	private static function get_cart_button( $product ) {
		if ( ! $product->is_purchasable() || ! $product->is_in_stock() ) {
			return sprintf( '<a class="wpfm-button wpfm-button--secondary" href="%1$s">%2$s</a>', esc_url( get_permalink( $product->get_id() ) ), esc_html__( 'View product', 'woocommerce-product-feature-matrix' ) );
		}

		$classes = array( 'wpfm-button', 'add_to_cart_button', 'product_type_' . $product->get_type() );

		if ( $product->supports( 'ajax_add_to_cart' ) ) {
			$classes[] = 'ajax_add_to_cart';
		}

		return sprintf(
			'<a class="%1$s" href="%2$s" data-product_id="%3$d" data-quantity="1" rel="nofollow">%4$s</a>',
			esc_attr( implode( ' ', $classes ) ),
			esc_url( $product->add_to_cart_url() ),
			absint( $product->get_id() ),
			esc_html( $product->add_to_cart_text() )
		);
	}

	/**
	 * Get configured products.
	 *
	 * @param string $product_ids Comma-separated product IDs.
	 * @return array
	 */
	private static function get_products( $product_ids ) {
		$products = array();

		foreach ( self::list_from_attribute( $product_ids ) as $product_id ) {
			$product = wc_get_product( absint( $product_id ) );

			if ( $product && $product->is_visible() ) {
				$products[] = $product;
			}
		}

		return $products;
	}

	/**
	 * Build attribute rows from selected products and optional allow-list.
	 *
	 * @param WC_Product[] $products Products.
	 * @param string       $attribute_keys Attribute key allow-list.
	 * @return array
	 */
	private static function get_attribute_rows( $products, $attribute_keys ) {
		$allowed = self::list_from_attribute( $attribute_keys );
		$rows    = array();

		foreach ( $products as $product ) {
			foreach ( $product->get_attributes() as $attribute ) {
				$key = self::normalize_attribute_key( $attribute->get_name() );

				if ( ! empty( $allowed ) && ! in_array( $key, $allowed, true ) && ! in_array( $attribute->get_name(), $allowed, true ) ) {
					continue;
				}

				if ( ! isset( $rows[ $key ] ) ) {
					$rows[ $key ] = array(
						'key'   => $key,
						'label' => self::get_attribute_label( $attribute->get_name() ),
					);
				}
			}
		}

		return array_values( $rows );
	}

	/**
	 * Get a product attribute value.
	 *
	 * @param WC_Product $product Product object.
	 * @param string     $key Normalized attribute key.
	 * @return string
	 */
	private static function get_product_attribute_value( $product, $key ) {
		foreach ( $product->get_attributes() as $attribute ) {
			if ( self::normalize_attribute_key( $attribute->get_name() ) !== $key ) {
				continue;
			}

			if ( $attribute->is_taxonomy() ) {
				$values = wc_get_product_terms( $product->get_id(), $attribute->get_name(), array( 'fields' => 'names' ) );
				return ! empty( $values ) ? esc_html( implode( ', ', $values ) ) : '&mdash;';
			}

			$options = $attribute->get_options();
			return ! empty( $options ) ? esc_html( implode( ', ', $options ) ) : '&mdash;';
		}

		return '&mdash;';
	}

	/**
	 * Get a readable attribute label.
	 *
	 * @param string $name Attribute name.
	 * @return string
	 */
	private static function get_attribute_label( $name ) {
		if ( function_exists( 'wc_attribute_label' ) ) {
			return wc_attribute_label( $name );
		}

		return ucwords( str_replace( array( 'pa_', '-', '_' ), array( '', ' ', ' ' ), $name ) );
	}

	/**
	 * Normalize an attribute key.
	 *
	 * @param string $name Attribute name.
	 * @return string
	 */
	private static function normalize_attribute_key( $name ) {
		return sanitize_title( str_replace( 'pa_', '', $name ) );
	}

	/**
	 * Render missing WooCommerce state.
	 *
	 * @return string
	 */
	private static function render_missing_woocommerce() {
		return '<div class="wpfm-notice">' . esc_html__( 'WooCommerce is required to render the product feature matrix.', 'woocommerce-product-feature-matrix' ) . '</div>';
	}

	/**
	 * Render empty product state.
	 *
	 * @return string
	 */
	private static function render_empty_state() {
		return '<div class="wpfm-notice">' . esc_html__( 'Select WooCommerce product IDs to build a feature matrix.', 'woocommerce-product-feature-matrix' ) . '</div>';
	}

	/**
	 * Default rendering attributes.
	 *
	 * @return array
	 */
	private static function default_attributes() {
		return array(
			'title'       => esc_html__( 'Compare products', 'woocommerce-product-feature-matrix' ),
			'products'    => '',
			'attributes'  => '',
			'showFilters' => true,
		);
	}

	/**
	 * Normalize shortcode and block attributes.
	 *
	 * @param array $attributes Raw attributes.
	 * @return array
	 */
	private static function normalize_attributes( $attributes ) {
		if ( isset( $attributes['product_ids'] ) ) {
			$attributes['products'] = $attributes['product_ids'];
		}

		if ( isset( $attributes['showfilters'] ) ) {
			$attributes['showFilters'] = $attributes['showfilters'];
		}

		if ( isset( $attributes['show_filters'] ) ) {
			$attributes['showFilters'] = $attributes['show_filters'];
		}

		return wp_parse_args( $attributes, self::default_attributes() );
	}

	/**
	 * Parse comma-separated values from shortcode/block attributes.
	 *
	 * @param string $value Attribute value.
	 * @return array
	 */
	private static function list_from_attribute( $value ) {
		if ( empty( $value ) || ! is_string( $value ) ) {
			return array();
		}

		return array_values( array_filter( array_map( 'trim', explode( ',', $value ) ) ) );
	}

	/**
	 * Determine truthy values across shortcode and block inputs.
	 *
	 * @param mixed $value Input value.
	 * @return bool
	 */
	private static function truthy( $value ) {
		return in_array( $value, array( true, 1, '1', 'true', 'yes', 'on' ), true );
	}

	/**
	 * Check WooCommerce availability.
	 *
	 * @return bool
	 */
	private static function is_woocommerce_available() {
		return function_exists( 'wc_get_product' ) && class_exists( 'WooCommerce' );
	}
}
