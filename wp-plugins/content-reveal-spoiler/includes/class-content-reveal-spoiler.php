<?php
/**
 * Main plugin class.
 *
 * @package ContentRevealSpoiler
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Registers reveal assets, shortcodes, and the dynamic block.
 */
final class Content_Reveal_Spoiler {
	/**
	 * Number used to create unique panel IDs.
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
		add_action( 'init', array( __CLASS__, 'register_block' ) );
		add_shortcode( 'content_reveal', array( __CLASS__, 'render_shortcode' ) );
		add_shortcode( 'spoiler', array( __CLASS__, 'render_shortcode' ) );
	}

	/**
	 * Load translations.
	 *
	 * @return void
	 */
	public static function load_textdomain() {
		load_plugin_textdomain(
			'content-reveal-spoiler',
			false,
			dirname( plugin_basename( CONTENT_REVEAL_SPOILER_FILE ) ) . '/languages'
		);
	}

	/**
	 * Register frontend and editor assets.
	 *
	 * @return void
	 */
	public static function register_assets() {
		wp_register_style(
			'content-reveal-spoiler',
			CONTENT_REVEAL_SPOILER_URL . 'assets/reveal.css',
			array(),
			self::asset_version( 'assets/reveal.css' )
		);

		wp_register_script(
			'content-reveal-spoiler',
			CONTENT_REVEAL_SPOILER_URL . 'assets/reveal.js',
			array(),
			self::asset_version( 'assets/reveal.js' ),
			true
		);

		wp_register_script(
			'content-reveal-spoiler-editor',
			CONTENT_REVEAL_SPOILER_URL . 'assets/editor.js',
			array( 'wp-blocks', 'wp-block-editor', 'wp-components', 'wp-element', 'wp-i18n' ),
			self::asset_version( 'assets/editor.js' ),
			true
		);
	}

	/**
	 * Register the server-rendered block.
	 *
	 * @return void
	 */
	public static function register_block() {
		if ( ! function_exists( 'register_block_type' ) ) {
			return;
		}

		register_block_type(
			'content-reveal-spoiler/reveal',
			array(
				'api_version'     => 2,
				'editor_script'   => 'content-reveal-spoiler-editor',
				'style'           => 'content-reveal-spoiler',
				'script'          => 'content-reveal-spoiler',
				'render_callback' => array( __CLASS__, 'render_block' ),
				'attributes'      => self::attribute_schema(),
			)
		);
	}

	/**
	 * Render a reveal shortcode.
	 *
	 * @param array|string $atts Shortcode attributes.
	 * @param string|null  $content Enclosed content.
	 * @return string
	 */
	public static function render_shortcode( $atts, $content = null ) {
		$atts = shortcode_atts(
			array(
				'label'        => __( 'Show hidden content', 'content-reveal-spoiler' ),
				'hide_label'   => __( 'Hide content', 'content-reveal-spoiler' ),
				'style'        => 'panel',
				'open'         => 'false',
				'one_way'      => 'false',
				'screen_label' => '',
			),
			(array) $atts,
			'content_reveal'
		);

		return self::render_reveal(
			array(
				'label'       => $atts['label'],
				'hideLabel'   => $atts['hide_label'],
				'style'       => $atts['style'],
				'open'        => self::is_truthy( $atts['open'] ),
				'oneWay'      => self::is_truthy( $atts['one_way'] ),
				'screenLabel' => $atts['screen_label'],
				'content'     => null === $content ? '' : do_shortcode( $content ),
			)
		);
	}

	/**
	 * Render the dynamic block.
	 *
	 * @param array $attributes Block attributes.
	 * @return string
	 */
	public static function render_block( $attributes ) {
		return self::render_reveal( wp_parse_args( (array) $attributes, self::defaults() ) );
	}

	/**
	 * Build accessible reveal markup.
	 *
	 * @param array $attributes Reveal settings.
	 * @return string
	 */
	private static function render_reveal( $attributes ) {
		$attributes   = wp_parse_args( (array) $attributes, self::defaults() );
		$style        = in_array( $attributes['style'], array( 'panel', 'blur' ), true ) ? $attributes['style'] : 'panel';
		$is_open      = self::is_truthy( $attributes['open'] );
		$is_one_way   = self::is_truthy( $attributes['oneWay'] );
		$label        = sanitize_text_field( $attributes['label'] );
		$hide_label   = sanitize_text_field( $attributes['hideLabel'] );
		$screen_label = sanitize_text_field( $attributes['screenLabel'] );
		$content      = wp_kses_post( $attributes['content'] );

		self::$instance++;
		$panel_id = 'crs-reveal-' . self::$instance;

		wp_enqueue_style( 'content-reveal-spoiler' );
		wp_enqueue_script( 'content-reveal-spoiler' );

		ob_start();
		?>
		<div class="crs-reveal crs-reveal--<?php echo esc_attr( $style ); ?><?php echo $is_open ? ' is-open' : ''; ?>" data-crs-reveal<?php echo $is_one_way ? ' data-crs-one-way' : ''; ?>>
			<button class="crs-reveal__trigger" type="button" aria-expanded="<?php echo $is_open ? 'true' : 'false'; ?>" aria-controls="<?php echo esc_attr( $panel_id ); ?>" data-crs-show-label="<?php echo esc_attr( $label ); ?>" data-crs-hide-label="<?php echo esc_attr( $hide_label ); ?>"<?php echo $is_open && $is_one_way ? ' hidden' : ''; ?>>
				<span class="crs-reveal__trigger-text"><?php echo esc_html( $is_open ? $hide_label : $label ); ?></span>
			</button>
			<div class="crs-reveal__content" id="<?php echo esc_attr( $panel_id ); ?>"<?php echo $is_open ? '' : ' hidden'; ?>>
				<?php if ( '' !== $screen_label ) : ?>
					<p class="screen-reader-text"><?php echo esc_html( $screen_label ); ?></p>
				<?php endif; ?>
				<?php echo $content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Sanitized with wp_kses_post above. ?>
			</div>
		</div>
		<?php
		return ob_get_clean();
	}

	/**
	 * Return block attributes.
	 *
	 * @return array
	 */
	private static function attribute_schema() {
		return array(
			'label'       => array( 'type' => 'string', 'default' => __( 'Show hidden content', 'content-reveal-spoiler' ) ),
			'hideLabel'   => array( 'type' => 'string', 'default' => __( 'Hide content', 'content-reveal-spoiler' ) ),
			'style'       => array( 'type' => 'string', 'default' => 'panel' ),
			'open'        => array( 'type' => 'boolean', 'default' => false ),
			'oneWay'      => array( 'type' => 'boolean', 'default' => false ),
			'screenLabel' => array( 'type' => 'string', 'default' => '' ),
			'content'     => array( 'type' => 'string', 'default' => '' ),
		);
	}

	/**
	 * Return rendering defaults.
	 *
	 * @return array
	 */
	private static function defaults() {
		return array(
			'label'       => __( 'Show hidden content', 'content-reveal-spoiler' ),
			'hideLabel'   => __( 'Hide content', 'content-reveal-spoiler' ),
			'style'       => 'panel',
			'open'        => false,
			'oneWay'      => false,
			'screenLabel' => '',
			'content'     => '',
		);
	}

	/**
	 * Normalize shortcode boolean values.
	 *
	 * @param mixed $value Value to normalize.
	 * @return bool
	 */
	private static function is_truthy( $value ) {
		return in_array( strtolower( (string) $value ), array( '1', 'true', 'yes', 'on' ), true );
	}

	/**
	 * Get a cache-safe asset version.
	 *
	 * @param string $relative_path Relative plugin path.
	 * @return int|string
	 */
	private static function asset_version( $relative_path ) {
		$path = CONTENT_REVEAL_SPOILER_DIR . $relative_path;
		return file_exists( $path ) ? filemtime( $path ) : CONTENT_REVEAL_SPOILER_VERSION;
	}
}
