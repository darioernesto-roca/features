<?php
/**
 * Plugin Name: Testimonial Carousel
 * Description: Testimonial slider with custom post type, ratings, client images, autoplay controls, arrows, dots, schema, shortcode, and block output.
 * Version: 0.1.0
 * Author: UI Feature Gallery
 * License: GPL-2.0-or-later
 * Text Domain: testimonial-carousel
 * Requires at least: 6.0
 * Requires PHP: 7.4
 *
 * @package TestimonialCarousel
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'TESTIMONIAL_CAROUSEL_VERSION', '0.1.0' );
define( 'TESTIMONIAL_CAROUSEL_FILE', __FILE__ );
define( 'TESTIMONIAL_CAROUSEL_DIR', plugin_dir_path( __FILE__ ) );
define( 'TESTIMONIAL_CAROUSEL_URL', plugin_dir_url( __FILE__ ) );

require_once TESTIMONIAL_CAROUSEL_DIR . 'includes/class-testimonial-carousel.php';

Testimonial_Carousel::init();
register_activation_hook( __FILE__, array( 'Testimonial_Carousel', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'Testimonial_Carousel', 'deactivate' ) );
