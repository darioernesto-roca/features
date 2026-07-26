<?php
/**
 * Main plugin bootstrap.
 *
 * @package MicroSurveyFeedback
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Registers feedback collection, display output, and admin reporting.
 */
final class Micro_Survey_Feedback {
	/** Response storage post type. */
	const POST_TYPE = 'msfw_response';

	/** Nonce action. */
	const NONCE_ACTION = 'msfw_submit_feedback';

	/**
	 * Initialize plugin hooks.
	 *
	 * @return void
	 */
	public static function init() {
		add_action( 'plugins_loaded', array( __CLASS__, 'load_textdomain' ) );
		add_action( 'init', array( __CLASS__, 'register_response_type' ) );
		add_action( 'init', array( __CLASS__, 'register_assets' ) );
		add_action( 'init', array( __CLASS__, 'register_block' ) );
		add_action( 'admin_menu', array( __CLASS__, 'add_results_page' ) );
		add_action( 'wp_ajax_msfw_submit_feedback', array( __CLASS__, 'handle_submit' ) );
		add_action( 'wp_ajax_nopriv_msfw_submit_feedback', array( __CLASS__, 'handle_submit' ) );
		add_shortcode( 'micro_survey_feedback', array( __CLASS__, 'render_shortcode' ) );
		add_shortcode( 'feedback_widget', array( __CLASS__, 'render_shortcode' ) );
	}

	/**
	 * Activate plugin.
	 *
	 * @return void
	 */
	public static function activate() {
		self::register_response_type();
		flush_rewrite_rules();
	}

	/**
	 * Deactivate plugin.
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
			'micro-survey-feedback',
			false,
			dirname( plugin_basename( MICRO_SURVEY_FEEDBACK_FILE ) ) . '/languages'
		);
	}

	/**
	 * Register private response post type for exportable, no-table storage.
	 *
	 * @return void
	 */
	public static function register_response_type() {
		register_post_type(
			self::POST_TYPE,
			array(
				'labels'       => array(
					'name'          => esc_html__( 'Feedback Responses', 'micro-survey-feedback' ),
					'singular_name' => esc_html__( 'Feedback Response', 'micro-survey-feedback' ),
				),
				'public'       => false,
				'show_ui'      => false,
				'show_in_rest' => false,
				'supports'     => array( 'title', 'editor' ),
			)
		);
	}

	/**
	 * Register frontend and editor assets.
	 *
	 * @return void
	 */
	public static function register_assets() {
		$style_path  = MICRO_SURVEY_FEEDBACK_DIR . 'assets/feedback.css';
		$script_path = MICRO_SURVEY_FEEDBACK_DIR . 'assets/feedback.js';
		$editor_path = MICRO_SURVEY_FEEDBACK_DIR . 'assets/editor.js';

		wp_register_style(
			'micro-survey-feedback',
			MICRO_SURVEY_FEEDBACK_URL . 'assets/feedback.css',
			array(),
			file_exists( $style_path ) ? filemtime( $style_path ) : MICRO_SURVEY_FEEDBACK_VERSION
		);

		wp_register_script(
			'micro-survey-feedback',
			MICRO_SURVEY_FEEDBACK_URL . 'assets/feedback.js',
			array(),
			file_exists( $script_path ) ? filemtime( $script_path ) : MICRO_SURVEY_FEEDBACK_VERSION,
			true
		);

		wp_register_script(
			'micro-survey-feedback-editor',
			MICRO_SURVEY_FEEDBACK_URL . 'assets/editor.js',
			array( 'wp-blocks', 'wp-element', 'wp-components', 'wp-block-editor', 'wp-i18n' ),
			file_exists( $editor_path ) ? filemtime( $editor_path ) : MICRO_SURVEY_FEEDBACK_VERSION,
			true
		);
	}

	/**
	 * Register dynamic Gutenberg block.
	 *
	 * @return void
	 */
	public static function register_block() {
		register_block_type(
			'micro-survey-feedback/widget',
			array(
				'api_version'     => 2,
				'editor_script'   => 'micro-survey-feedback-editor',
				'style'           => 'micro-survey-feedback',
				'render_callback' => array( __CLASS__, 'render_widget' ),
				'attributes'      => array(
					'title'       => array( 'type' => 'string', 'default' => 'Was this helpful?' ),
					'description' => array( 'type' => 'string', 'default' => 'Share a quick reaction so we can improve this page.' ),
					'comment'     => array( 'type' => 'boolean', 'default' => true ),
					'toast'       => array( 'type' => 'boolean', 'default' => true ),
					'thankYou'    => array( 'type' => 'string', 'default' => 'Thanks for the feedback!' ),
				),
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
		return self::render_widget( shortcode_atts( self::default_attributes(), $atts, 'micro_survey_feedback' ) );
	}

	/**
	 * Render the survey widget.
	 *
	 * @param array $attributes Render attributes.
	 * @return string
	 */
	public static function render_widget( $attributes = array() ) {
		$attributes = self::normalize_attributes( $attributes );
		$page_id    = self::resolve_page_id( $attributes );
		$page_url   = $page_id ? get_permalink( $page_id ) : self::current_url();
		$widget_id  = 'msfw-' . wp_generate_uuid4();
		$emojis     = self::parse_emojis( isset( $attributes['emojis'] ) ? $attributes['emojis'] : '' );

		self::enqueue_widget_assets();

		ob_start();
		?>
		<form class="msfw-widget" data-msfw-widget data-msfw-toast="<?php echo esc_attr( self::truthy( $attributes['toast'] ) ? 'true' : 'false' ); ?>" data-msfw-thank-you="<?php echo esc_attr( $attributes['thankYou'] ); ?>">
			<input type="hidden" name="page_id" value="<?php echo esc_attr( $page_id ); ?>" />
			<input type="hidden" name="page_url" value="<?php echo esc_url( $page_url ); ?>" />
			<input type="hidden" name="reaction" value="" data-msfw-reaction-input />
			<div class="msfw-card" aria-labelledby="<?php echo esc_attr( $widget_id ); ?>-title">
				<h2 class="msfw-title" id="<?php echo esc_attr( $widget_id ); ?>-title"><?php echo esc_html( $attributes['title'] ); ?></h2>
				<p class="msfw-description"><?php echo esc_html( $attributes['description'] ); ?></p>
				<div class="msfw-emoji-group" role="group" aria-label="<?php echo esc_attr__( 'Choose a reaction', 'micro-survey-feedback' ); ?>">
					<?php foreach ( $emojis as $emoji ) : ?>
						<button class="msfw-emoji" type="button" data-msfw-reaction="<?php echo esc_attr( $emoji['value'] ); ?>" aria-pressed="false">
							<span aria-hidden="true"><?php echo esc_html( $emoji['icon'] ); ?></span>
							<span><?php echo esc_html( $emoji['label'] ); ?></span>
						</button>
					<?php endforeach; ?>
				</div>
				<fieldset class="msfw-rating">
					<legend><?php echo esc_html__( 'Rate this page', 'micro-survey-feedback' ); ?></legend>
					<?php for ( $rating = 1; $rating <= 5; $rating++ ) : ?>
						<label><input type="radio" name="rating" value="<?php echo esc_attr( $rating ); ?>" /> <span><?php echo esc_html( $rating ); ?></span></label>
					<?php endfor; ?>
				</fieldset>
				<?php if ( self::truthy( $attributes['comment'] ) ) : ?>
					<label class="msfw-comment-label" for="<?php echo esc_attr( $widget_id ); ?>-comment"><?php echo esc_html__( 'Anything else to share?', 'micro-survey-feedback' ); ?></label>
					<textarea id="<?php echo esc_attr( $widget_id ); ?>-comment" name="comment" rows="3" maxlength="1000" placeholder="<?php echo esc_attr__( 'Optional comment', 'micro-survey-feedback' ); ?>"></textarea>
				<?php endif; ?>
				<button class="msfw-submit" type="submit"><?php echo esc_html__( 'Send feedback', 'micro-survey-feedback' ); ?></button>
				<p class="msfw-status" data-msfw-status role="status" aria-live="polite"></p>
			</div>
		</form>
		<?php
		return ob_get_clean();
	}

	/**
	 * Handle AJAX feedback submissions.
	 *
	 * @return void
	 */
	public static function handle_submit() {
		check_ajax_referer( self::NONCE_ACTION, 'nonce' );

		$page_id  = isset( $_POST['page_id'] ) ? absint( wp_unslash( $_POST['page_id'] ) ) : 0;
		$page_url = isset( $_POST['page_url'] ) ? esc_url_raw( wp_unslash( $_POST['page_url'] ) ) : '';
		$reaction = isset( $_POST['reaction'] ) ? sanitize_key( wp_unslash( $_POST['reaction'] ) ) : '';
		$rating   = isset( $_POST['rating'] ) ? absint( wp_unslash( $_POST['rating'] ) ) : 0;
		$comment  = isset( $_POST['comment'] ) ? sanitize_textarea_field( wp_unslash( $_POST['comment'] ) ) : '';

		if ( '' === $reaction && 0 === $rating && '' === $comment ) {
			wp_send_json_error( array( 'message' => esc_html__( 'Please choose a reaction or rating first.', 'micro-survey-feedback' ) ), 400 );
		}

		if ( $rating < 0 || $rating > 5 ) {
			wp_send_json_error( array( 'message' => esc_html__( 'Please use a rating from 1 to 5.', 'micro-survey-feedback' ) ), 400 );
		}

		$response_id = wp_insert_post(
			array(
				'post_type'    => self::POST_TYPE,
				'post_status'  => 'private',
				'post_title'   => self::build_response_title( $page_id, $reaction, $rating ),
				'post_content' => $comment,
			),
			true
		);

		if ( is_wp_error( $response_id ) ) {
			wp_send_json_error( array( 'message' => esc_html__( 'Feedback could not be saved. Please try again.', 'micro-survey-feedback' ) ), 500 );
		}

		update_post_meta( $response_id, '_msfw_page_id', $page_id );
		update_post_meta( $response_id, '_msfw_page_url', $page_url );
		update_post_meta( $response_id, '_msfw_reaction', $reaction );
		update_post_meta( $response_id, '_msfw_rating', $rating );

		wp_send_json_success( array( 'message' => esc_html__( 'Thanks for the feedback!', 'micro-survey-feedback' ) ) );
	}

	/**
	 * Add admin results page.
	 *
	 * @return void
	 */
	public static function add_results_page() {
		add_management_page(
			esc_html__( 'Micro Survey Feedback', 'micro-survey-feedback' ),
			esc_html__( 'Micro Survey Feedback', 'micro-survey-feedback' ),
			'manage_options',
			'micro-survey-feedback',
			array( __CLASS__, 'render_results_page' )
		);
	}

	/**
	 * Render admin results table.
	 *
	 * @return void
	 */
	public static function render_results_page() {
		$responses = get_posts(
			array(
				'post_type'      => self::POST_TYPE,
				'post_status'    => 'private',
				'posts_per_page' => 50,
				'orderby'        => 'date',
				'order'          => 'DESC',
			)
		);
		?>
		<div class="wrap">
			<h1><?php echo esc_html__( 'Micro Survey Feedback', 'micro-survey-feedback' ); ?></h1>
			<p><?php echo esc_html__( 'Recent page-level reactions collected by shortcode and block widgets. Data stays in WordPress and does not use external analytics.', 'micro-survey-feedback' ); ?></p>
			<table class="widefat striped">
				<thead>
					<tr>
						<th><?php echo esc_html__( 'Date', 'micro-survey-feedback' ); ?></th>
						<th><?php echo esc_html__( 'Page', 'micro-survey-feedback' ); ?></th>
						<th><?php echo esc_html__( 'Reaction', 'micro-survey-feedback' ); ?></th>
						<th><?php echo esc_html__( 'Rating', 'micro-survey-feedback' ); ?></th>
						<th><?php echo esc_html__( 'Comment', 'micro-survey-feedback' ); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php if ( empty( $responses ) ) : ?>
						<tr><td colspan="5"><?php echo esc_html__( 'No feedback has been collected yet.', 'micro-survey-feedback' ); ?></td></tr>
					<?php endif; ?>
					<?php foreach ( $responses as $response ) : ?>
						<?php
						$page_id  = absint( get_post_meta( $response->ID, '_msfw_page_id', true ) );
						$page_url = get_post_meta( $response->ID, '_msfw_page_url', true );
						$page     = $page_id ? get_the_title( $page_id ) : $page_url;
						?>
						<tr>
							<td><?php echo esc_html( get_date_from_gmt( $response->post_date_gmt, get_option( 'date_format' ) . ' ' . get_option( 'time_format' ) ) ); ?></td>
							<td><?php echo $page_url ? '<a href="' . esc_url( $page_url ) . '">' . esc_html( $page ) . '</a>' : esc_html( $page ); ?></td>
							<td><?php echo esc_html( get_post_meta( $response->ID, '_msfw_reaction', true ) ); ?></td>
							<td><?php echo esc_html( get_post_meta( $response->ID, '_msfw_rating', true ) ); ?></td>
							<td><?php echo esc_html( wp_trim_words( $response->post_content, 20 ) ); ?></td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
		<?php
	}

	/**
	 * Enqueue widget assets and localized AJAX data.
	 *
	 * @return void
	 */
	private static function enqueue_widget_assets() {
		wp_enqueue_style( 'micro-survey-feedback' );
		wp_enqueue_script( 'micro-survey-feedback' );
		wp_add_inline_script(
			'micro-survey-feedback',
			'window.MicroSurveyFeedback = ' . wp_json_encode(
				array(
					'ajaxUrl' => admin_url( 'admin-ajax.php' ),
					'nonce'   => wp_create_nonce( self::NONCE_ACTION ),
				)
			) . ';',
			'before'
		);
	}

	/**
	 * Default rendering attributes.
	 *
	 * @return array
	 */
	private static function default_attributes() {
		return array(
			'title'       => esc_html__( 'Was this helpful?', 'micro-survey-feedback' ),
			'description' => esc_html__( 'Share a quick reaction so we can improve this page.', 'micro-survey-feedback' ),
			'comment'     => true,
			'toast'       => true,
			'thankYou'    => esc_html__( 'Thanks for the feedback!', 'micro-survey-feedback' ),
			'emojis'      => '',
			'page_id'     => 0,
		);
	}

	/**
	 * Normalize shortcode and block attribute shapes.
	 *
	 * @param array $attributes Raw attributes.
	 * @return array
	 */
	private static function normalize_attributes( $attributes ) {
		if ( isset( $attributes['thankyou'] ) ) {
			$attributes['thankYou'] = $attributes['thankyou'];
		}

		if ( isset( $attributes['thank_you'] ) ) {
			$attributes['thankYou'] = $attributes['thank_you'];
		}

		return wp_parse_args( $attributes, self::default_attributes() );
	}

	/**
	 * Resolve current page ID from attributes or query context.
	 *
	 * @param array $attributes Render attributes.
	 * @return int
	 */
	private static function resolve_page_id( $attributes ) {
		if ( ! empty( $attributes['page_id'] ) ) {
			return absint( $attributes['page_id'] );
		}

		return is_singular() ? absint( get_the_ID() ) : 0;
	}

	/**
	 * Build response title.
	 *
	 * @param int    $page_id Page ID.
	 * @param string $reaction Reaction key.
	 * @param int    $rating Rating value.
	 * @return string
	 */
	private static function build_response_title( $page_id, $reaction, $rating ) {
		$page_label = $page_id ? get_the_title( $page_id ) : esc_html__( 'Unknown page', 'micro-survey-feedback' );
		$signal     = $reaction ? $reaction : sprintf( 'rating-%d', $rating );

		return sprintf( '%1$s feedback: %2$s', $page_label, $signal );
	}

	/**
	 * Parse emoji labels from a pipe-delimited attribute.
	 *
	 * @param string $value Custom emoji labels.
	 * @return array
	 */
	private static function parse_emojis( $value ) {
		$defaults = array(
			array( 'icon' => '😞', 'label' => esc_html__( 'Not helpful', 'micro-survey-feedback' ), 'value' => 'negative' ),
			array( 'icon' => '😐', 'label' => esc_html__( 'Okay', 'micro-survey-feedback' ), 'value' => 'neutral' ),
			array( 'icon' => '🙂', 'label' => esc_html__( 'Helpful', 'micro-survey-feedback' ), 'value' => 'positive' ),
			array( 'icon' => '😍', 'label' => esc_html__( 'Excellent', 'micro-survey-feedback' ), 'value' => 'excellent' ),
		);

		if ( empty( $value ) || ! is_string( $value ) ) {
			return $defaults;
		}

		$items = array_filter( array_map( 'trim', explode( '|', $value ) ) );

		if ( empty( $items ) ) {
			return $defaults;
		}

		return array_map(
			function ( $item, $index ) {
				return array(
					'icon'  => $item,
					'label' => $item,
					'value' => 'custom-' . absint( $index + 1 ),
				);
			},
			array_values( $items ),
			array_keys( array_values( $items ) )
		);
	}

	/**
	 * Determine truthy values across shortcode/block inputs.
	 *
	 * @param mixed $value Input value.
	 * @return bool
	 */
	private static function truthy( $value ) {
		return in_array( $value, array( true, 1, '1', 'true', 'yes', 'on' ), true );
	}

	/**
	 * Get current URL as a fallback association.
	 *
	 * @return string
	 */
	private static function current_url() {
		$host = isset( $_SERVER['HTTP_HOST'] ) ? sanitize_text_field( wp_unslash( $_SERVER['HTTP_HOST'] ) ) : '';
		$uri  = isset( $_SERVER['REQUEST_URI'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '';

		return $host && $uri ? home_url( $uri ) : home_url( '/' );
	}
}
