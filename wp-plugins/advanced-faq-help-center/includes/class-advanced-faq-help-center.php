<?php
/**
 * Main plugin bootstrap.
 *
 * @package AdvancedFAQHelpCenter
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Registers FAQ content, output, assets, and schema.
 */
final class Advanced_FAQ_Help_Center {
	/** FAQ custom post type. */
	const POST_TYPE = 'afhc_faq';

	/** FAQ category taxonomy. */
	const TAXONOMY = 'afhc_faq_category';

	/**
	 * Initialize plugin hooks.
	 *
	 * @return void
	 */
	public static function init() {
		add_action( 'plugins_loaded', array( __CLASS__, 'load_textdomain' ) );
		add_action( 'init', array( __CLASS__, 'register_content_types' ) );
		add_action( 'init', array( __CLASS__, 'register_assets' ) );
		add_action( 'init', array( __CLASS__, 'register_block' ) );
		add_shortcode( 'advanced_faq_help_center', array( __CLASS__, 'render_shortcode' ) );
		add_shortcode( 'faq_help_center', array( __CLASS__, 'render_shortcode' ) );
	}

	/**
	 * Plugin activation callback.
	 *
	 * @return void
	 */
	public static function activate() {
		self::register_content_types();
		flush_rewrite_rules();
	}

	/**
	 * Plugin deactivation callback.
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
			'advanced-faq-help-center',
			false,
			dirname( plugin_basename( ADVANCED_FAQ_HELP_CENTER_FILE ) ) . '/languages'
		);
	}

	/**
	 * Register the FAQ post type and category taxonomy.
	 *
	 * @return void
	 */
	public static function register_content_types() {
		register_post_type(
			self::POST_TYPE,
			array(
				'labels'       => array(
					'name'          => esc_html__( 'FAQs', 'advanced-faq-help-center' ),
					'singular_name' => esc_html__( 'FAQ', 'advanced-faq-help-center' ),
					'add_new_item'  => esc_html__( 'Add New FAQ', 'advanced-faq-help-center' ),
					'edit_item'     => esc_html__( 'Edit FAQ', 'advanced-faq-help-center' ),
				),
				'public'       => true,
				'show_in_rest' => true,
				'menu_icon'    => 'dashicons-editor-help',
				'has_archive'  => true,
				'rewrite'      => array( 'slug' => 'faqs' ),
				'supports'     => array( 'title', 'editor', 'excerpt', 'page-attributes' ),
			)
		);

		register_taxonomy(
			self::TAXONOMY,
			self::POST_TYPE,
			array(
				'labels'            => array(
					'name'          => esc_html__( 'FAQ Categories', 'advanced-faq-help-center' ),
					'singular_name' => esc_html__( 'FAQ Category', 'advanced-faq-help-center' ),
				),
				'public'            => true,
				'hierarchical'      => true,
				'show_admin_column' => true,
				'show_in_rest'      => true,
				'rewrite'           => array( 'slug' => 'faq-category' ),
			)
		);
	}

	/**
	 * Register shared frontend and editor assets.
	 *
	 * @return void
	 */
	public static function register_assets() {
		$editor_asset_path = ADVANCED_FAQ_HELP_CENTER_DIR . 'assets/editor.js';
		$style_asset_path  = ADVANCED_FAQ_HELP_CENTER_DIR . 'assets/help-center.css';
		$script_asset_path = ADVANCED_FAQ_HELP_CENTER_DIR . 'assets/help-center.js';

		wp_register_script(
			'advanced-faq-help-center-editor',
			ADVANCED_FAQ_HELP_CENTER_URL . 'assets/editor.js',
			array( 'wp-blocks', 'wp-components', 'wp-element', 'wp-i18n', 'wp-block-editor' ),
			file_exists( $editor_asset_path ) ? filemtime( $editor_asset_path ) : ADVANCED_FAQ_HELP_CENTER_VERSION,
			true
		);

		wp_register_style(
			'advanced-faq-help-center',
			ADVANCED_FAQ_HELP_CENTER_URL . 'assets/help-center.css',
			array(),
			file_exists( $style_asset_path ) ? filemtime( $style_asset_path ) : ADVANCED_FAQ_HELP_CENTER_VERSION
		);

		wp_register_script(
			'advanced-faq-help-center',
			ADVANCED_FAQ_HELP_CENTER_URL . 'assets/help-center.js',
			array(),
			file_exists( $script_asset_path ) ? filemtime( $script_asset_path ) : ADVANCED_FAQ_HELP_CENTER_VERSION,
			true
		);
	}

	/**
	 * Register the dynamic FAQ help center block.
	 *
	 * @return void
	 */
	public static function register_block() {
		if ( ! function_exists( 'register_block_type' ) ) {
			return;
		}

		register_block_type(
			'advanced-faq-help-center/help-center',
			array(
				'api_version'     => 2,
				'editor_script'   => 'advanced-faq-help-center-editor',
				'style'           => 'advanced-faq-help-center',
				'script'          => 'advanced-faq-help-center',
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
		$attributes = shortcode_atts( self::get_default_attributes(), (array) $atts, 'advanced_faq_help_center' );

		return self::render_help_center( $attributes );
	}

	/**
	 * Render block output.
	 *
	 * @param array $attributes Block attributes.
	 * @return string
	 */
	public static function render_block( $attributes ) {
		return self::render_help_center( wp_parse_args( (array) $attributes, self::get_default_attributes() ) );
	}

	/**
	 * Render the searchable accordion and schema.
	 *
	 * @param array $attributes Output attributes.
	 * @return string
	 */
	private static function render_help_center( $attributes ) {
		wp_enqueue_style( 'advanced-faq-help-center' );
		wp_enqueue_script( 'advanced-faq-help-center' );

		$attributes = wp_parse_args( (array) $attributes, self::get_default_attributes() );
		$faqs       = self::get_faqs( $attributes );
		$terms      = get_terms(
			array(
				'taxonomy'   => self::TAXONOMY,
				'hide_empty' => true,
			)
		);
		$show_search = self::truthy( $attributes['search'] );
		$show_schema = self::truthy( $attributes['schema'] );
		$layout      = self::normalize_choice( $attributes['layout'], array( 'single', 'grouped' ), 'grouped' );

		ob_start();
		?>
		<section class="afhc-help-center afhc-help-center--<?php echo esc_attr( $layout ); ?>" data-afhc-help-center>
			<header class="afhc-help-center__header">
				<h2><?php echo esc_html( $attributes['title'] ); ?></h2>
				<?php if ( '' !== $attributes['description'] ) : ?>
					<p><?php echo esc_html( $attributes['description'] ); ?></p>
				<?php endif; ?>
			</header>

			<?php if ( $show_search || ( ! is_wp_error( $terms ) && ! empty( $terms ) ) ) : ?>
				<div class="afhc-help-center__tools">
					<?php if ( $show_search ) : ?>
						<label class="afhc-help-center__search">
							<span><?php echo esc_html__( 'Search FAQs', 'advanced-faq-help-center' ); ?></span>
							<input type="search" data-afhc-search placeholder="<?php echo esc_attr__( 'Search questions...', 'advanced-faq-help-center' ); ?>" />
						</label>
					<?php endif; ?>

					<?php if ( ! is_wp_error( $terms ) && ! empty( $terms ) ) : ?>
						<div class="afhc-help-center__filters" aria-label="<?php echo esc_attr__( 'FAQ category filters', 'advanced-faq-help-center' ); ?>">
							<button type="button" data-afhc-filter="all" class="is-active"><?php echo esc_html__( 'All', 'advanced-faq-help-center' ); ?></button>
							<?php foreach ( $terms as $term ) : ?>
								<button type="button" data-afhc-filter="<?php echo esc_attr( $term->slug ); ?>"><?php echo esc_html( $term->name ); ?></button>
							<?php endforeach; ?>
						</div>
					<?php endif; ?>
				</div>
			<?php endif; ?>

			<div class="afhc-help-center__items">
				<?php echo self::render_faq_items( $faqs, $layout ); ?>
			</div>
			<p class="afhc-help-center__empty" data-afhc-empty hidden><?php echo esc_html__( 'No FAQs matched your search.', 'advanced-faq-help-center' ); ?></p>
		</section>
		<?php

		if ( $show_schema && ! empty( $faqs ) ) {
			echo self::render_schema( $faqs );
		}

		return ob_get_clean();
	}

	/**
	 * Query FAQ posts.
	 *
	 * @param array $attributes Output attributes.
	 * @return array
	 */
	private static function get_faqs( $attributes ) {
		$query_args = array(
			'post_type'              => self::POST_TYPE,
			'post_status'            => 'publish',
			'posts_per_page'         => absint( $attributes['limit'] ),
			'orderby'                => array(
				'menu_order' => 'ASC',
				'date'       => 'DESC',
			),
			'no_found_rows'          => true,
			'update_post_meta_cache' => false,
		);

		if ( '' !== $attributes['category'] ) {
			$query_args['tax_query'] = array(
				array(
					'taxonomy' => self::TAXONOMY,
					'field'    => 'slug',
					'terms'    => array_map( 'sanitize_title', array_map( 'trim', explode( ',', $attributes['category'] ) ) ),
				),
			);
		}

		$query = new WP_Query( $query_args );

		return $query->posts;
	}

	/**
	 * Render FAQ items in the selected accordion layout.
	 *
	 * @param array  $faqs FAQ posts.
	 * @param string $layout Layout slug.
	 * @return string
	 */
	private static function render_faq_items( $faqs, $layout ) {
		if ( 'grouped' !== $layout ) {
			return implode( '', array_map( array( __CLASS__, 'render_faq_item' ), $faqs ) );
		}

		$groups = array();

		foreach ( $faqs as $faq ) {
			$terms = get_the_terms( $faq, self::TAXONOMY );

			if ( is_wp_error( $terms ) || empty( $terms ) ) {
				$groups['uncategorized']['label']  = __( 'General', 'advanced-faq-help-center' );
				$groups['uncategorized']['items'][] = $faq;
				continue;
			}

			$term = reset( $terms );
			$groups[ $term->slug ]['label']  = $term->name;
			$groups[ $term->slug ]['items'][] = $faq;
		}

		ob_start();
		foreach ( $groups as $slug => $group ) :
			?>
			<section class="afhc-help-center__group" data-afhc-group="<?php echo esc_attr( $slug ); ?>">
				<h3><?php echo esc_html( $group['label'] ); ?></h3>
				<?php foreach ( $group['items'] as $faq ) : ?>
					<?php echo self::render_faq_item( $faq ); ?>
				<?php endforeach; ?>
			</section>
			<?php
		endforeach;

		return ob_get_clean();
	}

	/**
	 * Render a single FAQ accordion item.
	 *
	 * @param WP_Post $faq FAQ post.
	 * @return string
	 */
	private static function render_faq_item( $faq ) {
		$terms      = get_the_terms( $faq, self::TAXONOMY );
		$term_slugs = array();

		if ( ! is_wp_error( $terms ) && ! empty( $terms ) ) {
			$term_slugs = wp_list_pluck( $terms, 'slug' );
		}

		$search_text = strtolower( wp_strip_all_tags( get_the_title( $faq ) . ' ' . $faq->post_content ) );

		return sprintf(
			'<details class="afhc-faq" data-afhc-item data-afhc-categories="%1$s" data-afhc-search-text="%2$s"><summary>%3$s</summary><div class="afhc-faq__answer">%4$s</div></details>',
			esc_attr( implode( ' ', $term_slugs ) ),
			esc_attr( $search_text ),
			esc_html( get_the_title( $faq ) ),
			wp_kses_post( apply_filters( 'the_content', $faq->post_content ) )
		);
	}

	/**
	 * Render FAQPage JSON-LD schema.
	 *
	 * @param array $faqs FAQ posts.
	 * @return string
	 */
	private static function render_schema( $faqs ) {
		$entities = array();

		foreach ( $faqs as $faq ) {
			$entities[] = array(
				'@type'          => 'Question',
				'name'           => wp_strip_all_tags( get_the_title( $faq ) ),
				'acceptedAnswer' => array(
					'@type' => 'Answer',
					'text'  => wp_strip_all_tags( apply_filters( 'the_content', $faq->post_content ) ),
				),
			);
		}

		$schema = array(
			'@context'   => 'https://schema.org',
			'@type'      => 'FAQPage',
			'mainEntity' => $entities,
		);

		return sprintf( '<script type="application/ld+json">%s</script>', wp_json_encode( $schema ) );
	}

	/**
	 * Get default block and shortcode attributes.
	 *
	 * @return array
	 */
	private static function get_default_attributes() {
		return array(
			'title'       => __( 'Help Center', 'advanced-faq-help-center' ),
			'description' => __( 'Browse common questions and answers.', 'advanced-faq-help-center' ),
			'category'    => '',
			'limit'       => '12',
			'layout'      => 'grouped',
			'search'      => 'true',
			'schema'      => 'true',
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
	 * Determine whether an attribute is true.
	 *
	 * @param string $value Attribute value.
	 * @return bool
	 */
	private static function truthy( $value ) {
		return in_array( strtolower( (string) $value ), array( '1', 'true', 'yes', 'on' ), true );
	}
}
