<?php
/**
 * Main plugin bootstrap.
 *
 * @package TestimonialCarousel
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Registers testimonial content, meta, carousel output, and schema.
 */
final class Testimonial_Carousel {
	/** Testimonial custom post type. */
	const POST_TYPE = 'tc_testimonial';

	/** Meta key for star rating. */
	const META_RATING = '_tc_rating';

	/** Meta key for client role. */
	const META_ROLE = '_tc_client_role';

	/** Meta key for client company. */
	const META_COMPANY = '_tc_client_company';

	/**
	 * Initialize plugin hooks.
	 *
	 * @return void
	 */
	public static function init() {
		add_action( 'plugins_loaded', array( __CLASS__, 'load_textdomain' ) );
		add_action( 'init', array( __CLASS__, 'register_content_type' ) );
		add_action( 'init', array( __CLASS__, 'register_assets' ) );
		add_action( 'init', array( __CLASS__, 'register_block' ) );
		add_action( 'add_meta_boxes', array( __CLASS__, 'add_meta_boxes' ) );
		add_action( 'save_post_' . self::POST_TYPE, array( __CLASS__, 'save_meta' ) );
		add_shortcode( 'testimonial_carousel', array( __CLASS__, 'render_shortcode' ) );
		add_shortcode( 'tc_testimonial_carousel', array( __CLASS__, 'render_shortcode' ) );
	}

	/**
	 * Activation callback.
	 *
	 * @return void
	 */
	public static function activate() {
		self::register_content_type();
		flush_rewrite_rules();
	}

	/**
	 * Deactivation callback.
	 *
	 * @return void
	 */
	public static function deactivate() {
		flush_rewrite_rules();
	}

	/**
	 * Load translations.
	 *
	 * @return void
	 */
	public static function load_textdomain() {
		load_plugin_textdomain(
			'testimonial-carousel',
			false,
			dirname( plugin_basename( TESTIMONIAL_CAROUSEL_FILE ) ) . '/languages'
		);
	}

	/**
	 * Register testimonial post type.
	 *
	 * @return void
	 */
	public static function register_content_type() {
		register_post_type(
			self::POST_TYPE,
			array(
				'labels'       => array(
					'name'          => esc_html__( 'Testimonials', 'testimonial-carousel' ),
					'singular_name' => esc_html__( 'Testimonial', 'testimonial-carousel' ),
					'add_new_item'  => esc_html__( 'Add New Testimonial', 'testimonial-carousel' ),
					'edit_item'     => esc_html__( 'Edit Testimonial', 'testimonial-carousel' ),
				),
				'public'       => true,
				'show_in_rest' => true,
				'menu_icon'    => 'dashicons-format-quote',
				'has_archive'  => true,
				'rewrite'      => array( 'slug' => 'testimonials' ),
				'supports'     => array( 'title', 'editor', 'thumbnail', 'excerpt', 'page-attributes' ),
			)
		);
	}

	/**
	 * Register frontend and editor assets.
	 *
	 * @return void
	 */
	public static function register_assets() {
		$editor_asset_path = TESTIMONIAL_CAROUSEL_DIR . 'assets/editor.js';
		$style_asset_path  = TESTIMONIAL_CAROUSEL_DIR . 'assets/carousel.css';
		$script_asset_path = TESTIMONIAL_CAROUSEL_DIR . 'assets/carousel.js';

		wp_register_script(
			'testimonial-carousel-editor',
			TESTIMONIAL_CAROUSEL_URL . 'assets/editor.js',
			array( 'wp-blocks', 'wp-components', 'wp-element', 'wp-i18n', 'wp-block-editor' ),
			file_exists( $editor_asset_path ) ? filemtime( $editor_asset_path ) : TESTIMONIAL_CAROUSEL_VERSION,
			true
		);

		wp_register_style(
			'testimonial-carousel',
			TESTIMONIAL_CAROUSEL_URL . 'assets/carousel.css',
			array(),
			file_exists( $style_asset_path ) ? filemtime( $style_asset_path ) : TESTIMONIAL_CAROUSEL_VERSION
		);

		wp_register_script(
			'testimonial-carousel',
			TESTIMONIAL_CAROUSEL_URL . 'assets/carousel.js',
			array(),
			file_exists( $script_asset_path ) ? filemtime( $script_asset_path ) : TESTIMONIAL_CAROUSEL_VERSION,
			true
		);
	}

	/**
	 * Register dynamic carousel block.
	 *
	 * @return void
	 */
	public static function register_block() {
		if ( ! function_exists( 'register_block_type' ) ) {
			return;
		}

		register_block_type(
			'testimonial-carousel/carousel',
			array(
				'api_version'     => 2,
				'editor_script'   => 'testimonial-carousel-editor',
				'style'           => 'testimonial-carousel',
				'script'          => 'testimonial-carousel',
				'render_callback' => array( __CLASS__, 'render_block' ),
				'attributes'      => self::get_attribute_schema(),
			)
		);
	}

	/**
	 * Add testimonial details meta box.
	 *
	 * @return void
	 */
	public static function add_meta_boxes() {
		add_meta_box(
			'tc_testimonial_details',
			esc_html__( 'Testimonial Details', 'testimonial-carousel' ),
			array( __CLASS__, 'render_meta_box' ),
			self::POST_TYPE,
			'side'
		);
	}

	/**
	 * Render testimonial meta box.
	 *
	 * @param WP_Post $post Current post.
	 * @return void
	 */
	public static function render_meta_box( $post ) {
		wp_nonce_field( 'tc_save_testimonial_meta', 'tc_testimonial_meta_nonce' );

		$rating  = get_post_meta( $post->ID, self::META_RATING, true );
		$role    = get_post_meta( $post->ID, self::META_ROLE, true );
		$company = get_post_meta( $post->ID, self::META_COMPANY, true );
		?>
		<p>
			<label for="tc-rating"><strong><?php echo esc_html__( 'Star rating', 'testimonial-carousel' ); ?></strong></label>
			<select id="tc-rating" name="tc_rating" class="widefat">
				<?php for ( $i = 1; $i <= 5; $i++ ) : ?>
					<option value="<?php echo esc_attr( (string) $i ); ?>" <?php selected( (string) $rating, (string) $i ); ?>><?php echo esc_html( (string) $i ); ?></option>
				<?php endfor; ?>
			</select>
		</p>
		<p>
			<label for="tc-role"><strong><?php echo esc_html__( 'Client role', 'testimonial-carousel' ); ?></strong></label>
			<input id="tc-role" name="tc_role" class="widefat" type="text" value="<?php echo esc_attr( $role ); ?>" />
		</p>
		<p>
			<label for="tc-company"><strong><?php echo esc_html__( 'Client company/logo name', 'testimonial-carousel' ); ?></strong></label>
			<input id="tc-company" name="tc_company" class="widefat" type="text" value="<?php echo esc_attr( $company ); ?>" />
		</p>
		<p class="description"><?php echo esc_html__( 'Use the featured image for the client image or logo.', 'testimonial-carousel' ); ?></p>
		<?php
	}

	/**
	 * Save testimonial meta.
	 *
	 * @param int $post_id Post ID.
	 * @return void
	 */
	public static function save_meta( $post_id ) {
		if ( ! isset( $_POST['tc_testimonial_meta_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['tc_testimonial_meta_nonce'] ) ), 'tc_save_testimonial_meta' ) ) {
			return;
		}

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		$rating  = isset( $_POST['tc_rating'] ) ? min( 5, max( 1, absint( wp_unslash( $_POST['tc_rating'] ) ) ) ) : 5;
		$role    = isset( $_POST['tc_role'] ) ? sanitize_text_field( wp_unslash( $_POST['tc_role'] ) ) : '';
		$company = isset( $_POST['tc_company'] ) ? sanitize_text_field( wp_unslash( $_POST['tc_company'] ) ) : '';

		update_post_meta( $post_id, self::META_RATING, (string) $rating );
		update_post_meta( $post_id, self::META_ROLE, $role );
		update_post_meta( $post_id, self::META_COMPANY, $company );
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
					'schema_item_name' => '',
				)
			),
			(array) $atts,
			'testimonial_carousel'
		);

		if ( '' !== $attributes['schema_item_name'] ) {
			$attributes['schemaItemName'] = $attributes['schema_item_name'];
		}

		return self::render_carousel( $attributes );
	}

	/**
	 * Render block output.
	 *
	 * @param array $attributes Block attributes.
	 * @return string
	 */
	public static function render_block( $attributes ) {
		return self::render_carousel( wp_parse_args( (array) $attributes, self::get_default_attributes() ) );
	}

	/**
	 * Render carousel markup and schema.
	 *
	 * @param array $attributes Carousel attributes.
	 * @return string
	 */
	private static function render_carousel( $attributes ) {
		wp_enqueue_style( 'testimonial-carousel' );
		wp_enqueue_script( 'testimonial-carousel' );

		$attributes   = wp_parse_args( (array) $attributes, self::get_default_attributes() );
		if ( '' === $attributes['schemaItemName'] ) {
			$attributes['schemaItemName'] = get_bloginfo( 'name' );
		}

		$testimonials = self::get_testimonials( $attributes );
		$autoplay     = self::truthy( $attributes['autoplay'] );
		$show_schema  = self::truthy( $attributes['schema'] );
		$carousel_id  = 'tc-' . wp_generate_uuid4();

		ob_start();
		?>
		<section class="tc-carousel" id="<?php echo esc_attr( $carousel_id ); ?>" data-tc-carousel data-tc-autoplay="<?php echo esc_attr( $autoplay ? 'true' : 'false' ); ?>" data-tc-interval="<?php echo esc_attr( (string) absint( $attributes['interval'] ) ); ?>">
			<header class="tc-carousel__header">
				<h2><?php echo esc_html( $attributes['title'] ); ?></h2>
				<div class="tc-carousel__controls" aria-label="<?php echo esc_attr__( 'Carousel controls', 'testimonial-carousel' ); ?>">
					<button type="button" data-tc-prev aria-label="<?php echo esc_attr__( 'Previous testimonial', 'testimonial-carousel' ); ?>">‹</button>
					<button type="button" data-tc-toggle aria-pressed="<?php echo esc_attr( $autoplay ? 'true' : 'false' ); ?>"><?php echo esc_html( $autoplay ? __( 'Pause', 'testimonial-carousel' ) : __( 'Play', 'testimonial-carousel' ) ); ?></button>
					<button type="button" data-tc-next aria-label="<?php echo esc_attr__( 'Next testimonial', 'testimonial-carousel' ); ?>">›</button>
				</div>
			</header>

			<?php if ( ! empty( $testimonials ) ) : ?>
				<div class="tc-carousel__track" data-tc-track>
					<?php foreach ( $testimonials as $index => $testimonial ) : ?>
						<?php echo self::render_slide( $testimonial, 0 === $index ); ?>
					<?php endforeach; ?>
				</div>

				<div class="tc-carousel__dots" data-tc-dots aria-label="<?php echo esc_attr__( 'Testimonial slides', 'testimonial-carousel' ); ?>">
					<?php foreach ( $testimonials as $index => $testimonial ) : ?>
						<button type="button" data-tc-dot="<?php echo esc_attr( (string) $index ); ?>" class="<?php echo 0 === $index ? esc_attr( 'is-active' ) : ''; ?>" aria-label="<?php echo esc_attr( sprintf( __( 'Show testimonial %d', 'testimonial-carousel' ), $index + 1 ) ); ?>"></button>
					<?php endforeach; ?>
				</div>
			<?php else : ?>
				<p class="tc-carousel__empty"><?php echo esc_html__( 'No testimonials found.', 'testimonial-carousel' ); ?></p>
			<?php endif; ?>
		</section>
		<?php

		if ( $show_schema && ! empty( $testimonials ) ) {
			echo self::render_schema( $testimonials, $attributes['schemaItemName'] );
		}

		return ob_get_clean();
	}

	/**
	 * Query testimonial posts.
	 *
	 * @param array $attributes Carousel attributes.
	 * @return array
	 */
	private static function get_testimonials( $attributes ) {
		$query = new WP_Query(
			array(
				'post_type'              => self::POST_TYPE,
				'post_status'            => 'publish',
				'posts_per_page'         => absint( $attributes['limit'] ),
				'orderby'                => array(
					'menu_order' => 'ASC',
					'date'       => 'DESC',
				),
				'no_found_rows'          => true,
				'update_post_meta_cache' => true,
			)
		);

		return $query->posts;
	}

	/**
	 * Render a testimonial slide.
	 *
	 * @param WP_Post $testimonial Testimonial post.
	 * @param bool    $active Whether slide is active.
	 * @return string
	 */
	private static function render_slide( $testimonial, $active ) {
		$rating  = self::get_rating( $testimonial->ID );
		$role    = get_post_meta( $testimonial->ID, self::META_ROLE, true );
		$company = get_post_meta( $testimonial->ID, self::META_COMPANY, true );
		$image   = get_the_post_thumbnail( $testimonial, 'thumbnail', array( 'class' => 'tc-slide__image' ) );

		ob_start();
		?>
		<figure class="tc-slide<?php echo $active ? esc_attr( ' is-active' ) : ''; ?>" data-tc-slide <?php echo $active ? '' : 'hidden'; ?>>
			<?php if ( $image ) : ?>
				<?php echo wp_kses_post( $image ); ?>
			<?php endif; ?>
			<div class="tc-slide__stars" aria-label="<?php echo esc_attr( sprintf( __( '%d out of 5 stars', 'testimonial-carousel' ), $rating ) ); ?>">
				<?php echo esc_html( str_repeat( '★', $rating ) . str_repeat( '☆', 5 - $rating ) ); ?>
			</div>
			<blockquote><?php echo wp_kses_post( apply_filters( 'the_content', $testimonial->post_content ) ); ?></blockquote>
			<figcaption>
				<strong><?php echo esc_html( get_the_title( $testimonial ) ); ?></strong>
				<?php if ( '' !== $role || '' !== $company ) : ?>
					<span><?php echo esc_html( trim( $role . ( '' !== $role && '' !== $company ? ', ' : '' ) . $company ) ); ?></span>
				<?php endif; ?>
			</figcaption>
		</figure>
		<?php
		return ob_get_clean();
	}

	/**
	 * Render review schema markup.
	 *
	 * @param array  $testimonials Testimonial posts.
	 * @param string $item_name Reviewed item name.
	 * @return string
	 */
	private static function render_schema( $testimonials, $item_name ) {
		$reviews = array();

		foreach ( $testimonials as $testimonial ) {
			$reviews[] = array(
				'@type'         => 'Review',
				'author'        => array(
					'@type' => 'Person',
					'name'  => wp_strip_all_tags( get_the_title( $testimonial ) ),
				),
				'reviewBody'    => wp_strip_all_tags( $testimonial->post_content ),
				'reviewRating'  => array(
					'@type'       => 'Rating',
					'ratingValue' => self::get_rating( $testimonial->ID ),
					'bestRating'  => 5,
				),
				'itemReviewed' => array(
					'@type' => 'Organization',
					'name'  => wp_strip_all_tags( $item_name ),
				),
			);
		}

		$schema = array(
			'@context' => 'https://schema.org',
			'@graph'   => $reviews,
		);

		return sprintf( '<script type="application/ld+json">%s</script>', wp_json_encode( $schema ) );
	}

	/**
	 * Get a normalized rating.
	 *
	 * @param int $post_id Post ID.
	 * @return int
	 */
	private static function get_rating( $post_id ) {
		$rating = absint( get_post_meta( $post_id, self::META_RATING, true ) );

		return min( 5, max( 1, $rating ) );
	}

	/**
	 * Get default block/shortcode attributes.
	 *
	 * @return array
	 */
	private static function get_default_attributes() {
		return array(
			'title'          => __( 'What clients say', 'testimonial-carousel' ),
			'limit'          => '6',
			'autoplay'       => 'true',
			'interval'       => '5000',
			'schema'         => 'true',
			'schemaItemName' => get_bloginfo( 'name' ),
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

	/**
	 * Determine if an attribute is truthy.
	 *
	 * @param string $value Attribute value.
	 * @return bool
	 */
	private static function truthy( $value ) {
		return in_array( strtolower( (string) $value ), array( '1', 'true', 'yes', 'on' ), true );
	}
}
