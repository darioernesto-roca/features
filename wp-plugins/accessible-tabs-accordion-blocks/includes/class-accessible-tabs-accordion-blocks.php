<?php
/**
 * Main plugin class.
 *
 * @package AccessibleTabsAccordionBlocks
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Registers and renders the tabs and accordion blocks.
 */
final class Accessible_Tabs_Accordion_Blocks {
	/**
	 * Number used to create unique component IDs.
	 *
	 * @var int
	 */
	private static $instance = 0;

	/**
	 * Register plugin hooks.
	 *
	 * @return void
	 */
	public static function init() {
		add_action( 'plugins_loaded', array( __CLASS__, 'load_textdomain' ) );
		add_action( 'init', array( __CLASS__, 'register_assets' ) );
		add_action( 'init', array( __CLASS__, 'register_blocks' ) );
	}

	/**
	 * Load translations.
	 *
	 * @return void
	 */
	public static function load_textdomain() {
		load_plugin_textdomain(
			'accessible-tabs-accordion-blocks',
			false,
			dirname( plugin_basename( ATAB_FILE ) ) . '/languages'
		);
	}

	/**
	 * Register shared assets.
	 *
	 * @return void
	 */
	public static function register_assets() {
		wp_register_style(
			'atab-blocks',
			ATAB_URL . 'assets/blocks.css',
			array(),
			self::asset_version( 'assets/blocks.css' )
		);

		wp_register_script(
			'atab-frontend',
			ATAB_URL . 'assets/frontend.js',
			array(),
			self::asset_version( 'assets/frontend.js' ),
			true
		);

		wp_register_script(
			'atab-editor',
			ATAB_URL . 'assets/editor.js',
			array( 'wp-blocks', 'wp-block-editor', 'wp-components', 'wp-element', 'wp-i18n' ),
			self::asset_version( 'assets/editor.js' ),
			true
		);
	}

	/**
	 * Register dynamic blocks.
	 *
	 * @return void
	 */
	public static function register_blocks() {
		if ( ! function_exists( 'register_block_type' ) ) {
			return;
		}

		$shared = array(
			'api_version'   => 2,
			'editor_script' => 'atab-editor',
			'style'         => 'atab-blocks',
			'script'        => 'atab-frontend',
			'attributes'    => array(
				'items' => array(
					'type'    => 'array',
					'default' => self::default_items(),
				),
			),
		);

		register_block_type(
			'accessible-tabs-accordion/tabs',
			array_merge(
				$shared,
				array(
					'render_callback' => array( __CLASS__, 'render_tabs' ),
					'attributes'      => array_merge(
						$shared['attributes'],
						array(
							'orientation' => array( 'type' => 'string', 'default' => 'horizontal' ),
							'activeTab'   => array( 'type' => 'number', 'default' => 0 ),
						)
					),
				)
			)
		);

		register_block_type(
			'accessible-tabs-accordion/accordion',
			array_merge(
				$shared,
				array(
					'render_callback' => array( __CLASS__, 'render_accordion' ),
					'attributes'      => array_merge(
						$shared['attributes'],
						array(
							'allowMultiple' => array( 'type' => 'boolean', 'default' => false ),
							'openItem'      => array( 'type' => 'number', 'default' => 0 ),
							'faqSchema'     => array( 'type' => 'boolean', 'default' => false ),
							'headingLevel'  => array( 'type' => 'number', 'default' => 3 ),
						)
					),
				)
			)
		);
	}

	/**
	 * Render the tabs block.
	 *
	 * @param array $attributes Block attributes.
	 * @return string
	 */
	public static function render_tabs( $attributes ) {
		$items = self::sanitize_items( isset( $attributes['items'] ) ? $attributes['items'] : array() );
		if ( empty( $items ) ) {
			return '';
		}

		self::$instance++;
		$base_id     = 'atab-tabs-' . self::$instance;
		$orientation = isset( $attributes['orientation'] ) && 'vertical' === $attributes['orientation'] ? 'vertical' : 'horizontal';
		$active      = isset( $attributes['activeTab'] ) ? absint( $attributes['activeTab'] ) : 0;
		$active      = min( $active, count( $items ) - 1 );

		ob_start();
		?>
		<div class="atab-tabs atab-tabs--<?php echo esc_attr( $orientation ); ?>" data-atab-tabs>
			<div class="atab-tabs__list" role="tablist" aria-orientation="<?php echo esc_attr( $orientation ); ?>">
				<?php foreach ( $items as $index => $item ) : ?>
					<button class="atab-tabs__tab" id="<?php echo esc_attr( $base_id . '-tab-' . ( $index + 1 ) ); ?>" type="button" role="tab" aria-selected="<?php echo $active === $index ? 'true' : 'false'; ?>" aria-controls="<?php echo esc_attr( $base_id . '-panel-' . ( $index + 1 ) ); ?>" tabindex="<?php echo $active === $index ? '0' : '-1'; ?>" data-atab-slug="<?php echo esc_attr( sanitize_title( $item['title'] ) ); ?>"><?php echo esc_html( $item['title'] ); ?></button>
				<?php endforeach; ?>
			</div>
			<div class="atab-tabs__panels">
				<?php foreach ( $items as $index => $item ) : ?>
					<div class="atab-tabs__panel" id="<?php echo esc_attr( $base_id . '-panel-' . ( $index + 1 ) ); ?>" role="tabpanel" aria-labelledby="<?php echo esc_attr( $base_id . '-tab-' . ( $index + 1 ) ); ?>" tabindex="0"<?php echo $active === $index ? '' : ' hidden'; ?>><?php echo wp_kses_post( wpautop( $item['content'] ) ); ?></div>
				<?php endforeach; ?>
			</div>
		</div>
		<?php
		return ob_get_clean();
	}

	/**
	 * Render the accordion block.
	 *
	 * @param array $attributes Block attributes.
	 * @return string
	 */
	public static function render_accordion( $attributes ) {
		$items = self::sanitize_items( isset( $attributes['items'] ) ? $attributes['items'] : array() );
		if ( empty( $items ) ) {
			return '';
		}

		self::$instance++;
		$base_id        = 'atab-accordion-' . self::$instance;
		$allow_multiple = ! empty( $attributes['allowMultiple'] );
		$open_item      = isset( $attributes['openItem'] ) ? absint( $attributes['openItem'] ) : 0;
		$heading_level  = isset( $attributes['headingLevel'] ) ? absint( $attributes['headingLevel'] ) : 3;
		$heading_level  = min( 6, max( 2, $heading_level ) );
		$heading_tag    = 'h' . $heading_level;

		ob_start();
		?>
		<div class="atab-accordion" data-atab-accordion<?php echo $allow_multiple ? ' data-atab-multiple' : ''; ?>>
			<?php foreach ( $items as $index => $item ) : ?>
				<?php $is_open = $open_item === $index; ?>
				<section class="atab-accordion__item">
					<<?php echo esc_attr( $heading_tag ); ?> class="atab-accordion__heading">
						<button class="atab-accordion__trigger" id="<?php echo esc_attr( $base_id . '-trigger-' . ( $index + 1 ) ); ?>" type="button" aria-expanded="<?php echo $is_open ? 'true' : 'false'; ?>" aria-controls="<?php echo esc_attr( $base_id . '-panel-' . ( $index + 1 ) ); ?>">
							<span><?php echo esc_html( $item['title'] ); ?></span><span class="atab-accordion__icon" aria-hidden="true"></span>
						</button>
					</<?php echo esc_attr( $heading_tag ); ?>>
					<div class="atab-accordion__panel" id="<?php echo esc_attr( $base_id . '-panel-' . ( $index + 1 ) ); ?>" role="region" aria-labelledby="<?php echo esc_attr( $base_id . '-trigger-' . ( $index + 1 ) ); ?>"<?php echo $is_open ? '' : ' hidden'; ?>><?php echo wp_kses_post( wpautop( $item['content'] ) ); ?></div>
				</section>
			<?php endforeach; ?>
		</div>
		<?php
		if ( ! empty( $attributes['faqSchema'] ) ) {
			$schema = array(
				'@context'   => 'https://schema.org',
				'@type'      => 'FAQPage',
				'mainEntity' => array(),
			);
			foreach ( $items as $item ) {
				$schema['mainEntity'][] = array(
					'@type'          => 'Question',
					'name'           => wp_strip_all_tags( $item['title'] ),
					'acceptedAnswer' => array(
						'@type' => 'Answer',
						'text'  => wp_strip_all_tags( $item['content'] ),
					),
				);
			}
			printf( '<script type="application/ld+json">%s</script>', wp_json_encode( $schema, JSON_UNESCAPED_UNICODE ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- wp_json_encode safely serializes structured data and escapes closing tags.
		}

		return ob_get_clean();
	}

	/**
	 * Sanitize editor-provided items.
	 *
	 * @param mixed $items Items to sanitize.
	 * @return array
	 */
	private static function sanitize_items( $items ) {
		$sanitized = array();
		if ( ! is_array( $items ) ) {
			return $sanitized;
		}

		foreach ( $items as $item ) {
			if ( ! is_array( $item ) || empty( $item['title'] ) ) {
				continue;
			}
			$sanitized[] = array(
				'title'   => sanitize_text_field( $item['title'] ),
				'content' => isset( $item['content'] ) ? wp_kses_post( $item['content'] ) : '',
			);
		}

		return $sanitized;
	}

	/**
	 * Return starter items.
	 *
	 * @return array
	 */
	private static function default_items() {
		return array(
			array(
				'title'   => __( 'First section', 'accessible-tabs-accordion-blocks' ),
				'content' => __( 'Add content for the first section.', 'accessible-tabs-accordion-blocks' ),
			),
			array(
				'title'   => __( 'Second section', 'accessible-tabs-accordion-blocks' ),
				'content' => __( 'Add content for the second section.', 'accessible-tabs-accordion-blocks' ),
			),
		);
	}

	/**
	 * Get a cache-safe asset version.
	 *
	 * @param string $relative_path Relative plugin path.
	 * @return int|string
	 */
	private static function asset_version( $relative_path ) {
		$path = ATAB_DIR . $relative_path;
		return file_exists( $path ) ? filemtime( $path ) : ATAB_VERSION;
	}
}
