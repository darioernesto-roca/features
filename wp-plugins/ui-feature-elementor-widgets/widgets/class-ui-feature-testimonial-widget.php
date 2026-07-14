<?php
/**
 * Testimonial Elementor widget.
 *
 * @package UIFeatureElementorWidgets
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Testimonial widget.
 */
final class UI_Feature_Testimonial_Widget extends \Elementor\Widget_Base {
	/**
	 * Get widget name.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'ui_feature_testimonial';
	}

	/**
	 * Get widget title.
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'UI Testimonial', 'ui-feature-elementor-widgets' );
	}

	/**
	 * Get widget icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-testimonial';
	}

	/**
	 * Get widget categories.
	 *
	 * @return array
	 */
	public function get_categories() {
		return array( UI_Feature_Elementor_Widgets::CATEGORY );
	}

	/**
	 * Get style dependencies.
	 *
	 * @return array
	 */
	public function get_style_depends() {
		return array( 'ui-feature-elementor-widgets' );
	}

	/**
	 * Register widget controls.
	 *
	 * @return void
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'content_section',
			array(
				'label' => esc_html__( 'Testimonial Content', 'ui-feature-elementor-widgets' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'quote',
			array(
				'label'   => esc_html__( 'Quote', 'ui-feature-elementor-widgets' ),
				'type'    => \Elementor\Controls_Manager::TEXTAREA,
				'default' => esc_html__( 'This widget helped us add a polished section without custom theme work.', 'ui-feature-elementor-widgets' ),
			)
		);

		$this->add_control(
			'name',
			array(
				'label'   => esc_html__( 'Name', 'ui-feature-elementor-widgets' ),
				'type'    => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'Alex Morgan', 'ui-feature-elementor-widgets' ),
			)
		);

		$this->add_control(
			'role',
			array(
				'label'   => esc_html__( 'Role', 'ui-feature-elementor-widgets' ),
				'type'    => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'Marketing Lead', 'ui-feature-elementor-widgets' ),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Render widget output.
	 *
	 * @return void
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();
		?>
		<figure class="ufe-widget ufe-widget-testimonial">
			<blockquote><?php echo esc_html( $settings['quote'] ); ?></blockquote>
			<figcaption>
				<strong><?php echo esc_html( $settings['name'] ); ?></strong>
				<span><?php echo esc_html( $settings['role'] ); ?></span>
			</figcaption>
		</figure>
		<?php
	}
}
