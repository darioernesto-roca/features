<?php
/**
 * Main plugin bootstrap.
 *
 * @package ReadingProgressToc
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Registers reading progress, generated TOC, block, shortcode, and per-post controls.
 */
final class Reading_Progress_Toc {
	/** Post meta key used to disable enhancements. */
	const DISABLE_META = '_rptoc_disable';

	/**
	 * Initialize plugin hooks.
	 *
	 * @return void
	 */
	public static function init() {
		add_action( 'plugins_loaded', array( __CLASS__, 'load_textdomain' ) );
		add_action( 'init', array( __CLASS__, 'register_assets' ) );
		add_action( 'init', array( __CLASS__, 'register_block' ) );
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'enqueue_frontend_assets' ) );
		add_action( 'wp_body_open', array( __CLASS__, 'render_progress_bar' ) );
		add_action( 'wp_footer', array( __CLASS__, 'render_progress_bar' ) );
		add_action( 'add_meta_boxes', array( __CLASS__, 'add_meta_box' ) );
		add_action( 'save_post', array( __CLASS__, 'save_post_meta' ) );
		add_shortcode( 'reading_progress_toc', array( __CLASS__, 'render_shortcode' ) );
		add_shortcode( 'content_toc', array( __CLASS__, 'render_shortcode' ) );
	}

	/**
	 * Load translations.
	 *
	 * @return void
	 */
	public static function load_textdomain() {
		load_plugin_textdomain(
			'reading-progress-toc',
			false,
			dirname( plugin_basename( READING_PROGRESS_TOC_FILE ) ) . '/languages'
		);
	}

	/**
	 * Register frontend and block editor assets.
	 *
	 * @return void
	 */
	public static function register_assets() {
		$style_path  = READING_PROGRESS_TOC_DIR . 'assets/reading-progress.css';
		$script_path = READING_PROGRESS_TOC_DIR . 'assets/reading-progress.js';
		$editor_path = READING_PROGRESS_TOC_DIR . 'assets/editor.js';

		wp_register_style(
			'reading-progress-toc',
			READING_PROGRESS_TOC_URL . 'assets/reading-progress.css',
			array(),
			file_exists( $style_path ) ? filemtime( $style_path ) : READING_PROGRESS_TOC_VERSION
		);

		wp_register_script(
			'reading-progress-toc',
			READING_PROGRESS_TOC_URL . 'assets/reading-progress.js',
			array(),
			file_exists( $script_path ) ? filemtime( $script_path ) : READING_PROGRESS_TOC_VERSION,
			true
		);

		wp_register_script(
			'reading-progress-toc-editor',
			READING_PROGRESS_TOC_URL . 'assets/editor.js',
			array( 'wp-blocks', 'wp-element', 'wp-components', 'wp-block-editor', 'wp-i18n' ),
			file_exists( $editor_path ) ? filemtime( $editor_path ) : READING_PROGRESS_TOC_VERSION,
			true
		);
	}

	/**
	 * Register dynamic TOC block.
	 *
	 * @return void
	 */
	public static function register_block() {
		register_block_type(
			'reading-progress-toc/table-of-contents',
			array(
				'api_version'     => 2,
				'editor_script'   => 'reading-progress-toc-editor',
				'style'           => 'reading-progress-toc',
				'render_callback' => array( __CLASS__, 'render_toc' ),
				'attributes'      => array(
					'title'       => array( 'type' => 'string', 'default' => 'On this page' ),
					'selector'    => array( 'type' => 'string', 'default' => '.entry-content, .wp-site-blocks main, article' ),
					'minLevel'    => array( 'type' => 'number', 'default' => 2 ),
					'maxLevel'    => array( 'type' => 'number', 'default' => 3 ),
					'placeholder' => array( 'type' => 'string', 'default' => 'Headings will appear here on the front end.' ),
				),
			)
		);
	}

	/**
	 * Enqueue frontend assets on enabled singular content and when shortcodes are likely present.
	 *
	 * @return void
	 */
	public static function enqueue_frontend_assets() {
		if ( ! self::should_load_assets() ) {
			return;
		}

		wp_enqueue_style( 'reading-progress-toc' );
		wp_enqueue_script( 'reading-progress-toc' );
		wp_add_inline_script(
			'reading-progress-toc',
			'window.ReadingProgressTocSettings = ' . wp_json_encode(
				array(
					'contentSelector' => '.entry-content, .wp-site-blocks main, article',
					'smoothScroll'    => true,
				)
			) . ';',
			'before'
		);
	}

	/**
	 * Render top progress bar shell.
	 *
	 * @return void
	 */
	public static function render_progress_bar() {
		static $rendered = false;

		if ( $rendered || ! self::should_load_assets() ) {
			return;
		}

		$rendered = true;

		echo '<div class="rptoc-progress" aria-hidden="true"><span class="rptoc-progress__bar" data-rptoc-progress-bar></span></div>';
	}

	/**
	 * Render shortcode output.
	 *
	 * @param array $atts Shortcode attributes.
	 * @return string
	 */
	public static function render_shortcode( $atts ) {
		return self::render_toc( shortcode_atts( self::default_attributes(), $atts, 'reading_progress_toc' ) );
	}

	/**
	 * Render a TOC placeholder populated by frontend JS.
	 *
	 * @param array $attributes Render attributes.
	 * @return string
	 */
	public static function render_toc( $attributes = array() ) {
		if ( self::is_current_post_disabled() ) {
			return '';
		}

		$attributes = self::normalize_attributes( $attributes );

		wp_enqueue_style( 'reading-progress-toc' );
		wp_enqueue_script( 'reading-progress-toc' );

		ob_start();
		?>
		<nav class="rptoc-toc" data-rptoc-toc data-rptoc-selector="<?php echo esc_attr( $attributes['selector'] ); ?>" data-rptoc-min-level="<?php echo esc_attr( $attributes['minLevel'] ); ?>" data-rptoc-max-level="<?php echo esc_attr( $attributes['maxLevel'] ); ?>" aria-label="<?php echo esc_attr__( 'Table of contents', 'reading-progress-toc' ); ?>">
			<h2 class="rptoc-toc__title"><?php echo esc_html( $attributes['title'] ); ?></h2>
			<p class="rptoc-toc__placeholder" data-rptoc-placeholder><?php echo esc_html( $attributes['placeholder'] ); ?></p>
			<ol class="rptoc-toc__list" data-rptoc-list></ol>
		</nav>
		<?php
		return ob_get_clean();
	}

	/**
	 * Add per-post disable control.
	 *
	 * @return void
	 */
	public static function add_meta_box() {
		$post_types = get_post_types( array( 'public' => true, 'show_ui' => true ), 'names' );

		foreach ( $post_types as $post_type ) {
			add_meta_box(
				'reading-progress-toc',
				esc_html__( 'Reading Progress / TOC', 'reading-progress-toc' ),
				array( __CLASS__, 'render_meta_box' ),
				$post_type,
				'side',
				'default'
			);
		}
	}

	/**
	 * Render per-post disable control.
	 *
	 * @param WP_Post $post Current post.
	 * @return void
	 */
	public static function render_meta_box( $post ) {
		$disabled = self::is_post_disabled( $post->ID );
		wp_nonce_field( 'rptoc_save_meta', 'rptoc_meta_nonce' );
		?>
		<p>
			<label>
				<input type="checkbox" name="rptoc_disable" value="1" <?php checked( $disabled ); ?> />
				<?php echo esc_html__( 'Disable reading progress and TOC on this content.', 'reading-progress-toc' ); ?>
			</label>
		</p>
		<p class="description"><?php echo esc_html__( 'Use this for landing pages, builder layouts, or content where generated anchors are not desired.', 'reading-progress-toc' ); ?></p>
		<?php
	}

	/**
	 * Save per-post meta.
	 *
	 * @param int $post_id Post ID.
	 * @return void
	 */
	public static function save_post_meta( $post_id ) {
		if ( ! isset( $_POST['rptoc_meta_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['rptoc_meta_nonce'] ) ), 'rptoc_save_meta' ) ) {
			return;
		}

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		if ( isset( $_POST['rptoc_disable'] ) ) {
			update_post_meta( $post_id, self::DISABLE_META, '1' );
			return;
		}

		delete_post_meta( $post_id, self::DISABLE_META );
	}

	/**
	 * Determine if frontend enhancements should load.
	 *
	 * @return bool
	 */
	private static function should_load_assets() {
		if ( is_admin() ) {
			return false;
		}

		if ( is_singular() && ! self::is_current_post_disabled() ) {
			return true;
		}

		return false;
	}

	/**
	 * Default rendering attributes.
	 *
	 * @return array
	 */
	private static function default_attributes() {
		return array(
			'title'       => esc_html__( 'On this page', 'reading-progress-toc' ),
			'selector'    => '.entry-content, .wp-site-blocks main, article',
			'minLevel'    => 2,
			'maxLevel'    => 3,
			'placeholder' => esc_html__( 'Headings will appear here on the front end.', 'reading-progress-toc' ),
		);
	}

	/**
	 * Normalize shortcode and block attributes.
	 *
	 * @param array $attributes Raw attributes.
	 * @return array
	 */
	private static function normalize_attributes( $attributes ) {
		if ( isset( $attributes['minlevel'] ) ) {
			$attributes['minLevel'] = $attributes['minlevel'];
		}

		if ( isset( $attributes['maxlevel'] ) ) {
			$attributes['maxLevel'] = $attributes['maxlevel'];
		}

		if ( isset( $attributes['min_level'] ) ) {
			$attributes['minLevel'] = $attributes['min_level'];
		}

		if ( isset( $attributes['max_level'] ) ) {
			$attributes['maxLevel'] = $attributes['max_level'];
		}

		$attributes             = wp_parse_args( $attributes, self::default_attributes() );
		$attributes['minLevel'] = max( 1, min( 6, absint( $attributes['minLevel'] ) ) );
		$attributes['maxLevel'] = max( $attributes['minLevel'], min( 6, absint( $attributes['maxLevel'] ) ) );
		$attributes['selector'] = sanitize_text_field( $attributes['selector'] );

		return $attributes;
	}

	/**
	 * Determine if current singular post is disabled.
	 *
	 * @return bool
	 */
	private static function is_current_post_disabled() {
		return is_singular() && self::is_post_disabled( get_the_ID() );
	}

	/**
	 * Determine if a post disables enhancements.
	 *
	 * @param int $post_id Post ID.
	 * @return bool
	 */
	private static function is_post_disabled( $post_id ) {
		return '1' === get_post_meta( $post_id, self::DISABLE_META, true );
	}
}
