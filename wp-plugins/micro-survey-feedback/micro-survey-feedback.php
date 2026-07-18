<?php
/**
 * Plugin Name: Micro Survey Feedback
 * Description: Page-level emoji and rating feedback with optional comments, admin results, shortcode, and block output.
 * Version: 0.1.0
 * Author: UI Feature Gallery
 * License: GPL-2.0-or-later
 * Text Domain: micro-survey-feedback
 * Requires at least: 6.0
 * Requires PHP: 7.4
 *
 * @package MicroSurveyFeedback
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'MICRO_SURVEY_FEEDBACK_VERSION', '0.1.0' );
define( 'MICRO_SURVEY_FEEDBACK_FILE', __FILE__ );
define( 'MICRO_SURVEY_FEEDBACK_DIR', plugin_dir_path( __FILE__ ) );
define( 'MICRO_SURVEY_FEEDBACK_URL', plugin_dir_url( __FILE__ ) );

require_once MICRO_SURVEY_FEEDBACK_DIR . 'includes/class-micro-survey-feedback.php';

register_activation_hook( __FILE__, array( 'Micro_Survey_Feedback', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'Micro_Survey_Feedback', 'deactivate' ) );

Micro_Survey_Feedback::init();
