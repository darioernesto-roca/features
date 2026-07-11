<?php
/**
 * FAQ accordion Elementor widget.
 *
 * @package UIFeatureElementorWidgets
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * FAQ accordion widget.
 */
final class UI_Feature_Faq_Widget extends \Elementor\Widget_Base {
	/**
	 * Get widget name.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'ui_feature_faq';
	}

	/**
	 * Get widget title.
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'UI FAQ Accordion', 'ui-feature-elementor-widgets' );
	}

	/**
	 * Get widget icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-help-o';
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
				'label' => esc_html__( 'FAQ Content', 'ui-feature-elementor-widgets' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'question',
			array(
				'label'       => esc_html__( 'Question', 'ui-feature-elementor-widgets' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => esc_html__( 'Can I use this with my existing theme?', 'ui-feature-elementor-widgets' ),
				'label_block' => true,
			)
		);

		$this->add_control(
			'answer',
			array(
				'label'   => esc_html__( 'Answer', 'ui-feature-elementor-widgets' ),
				'type'    => \Elementor\Controls_Manager::WYSIWYG,
				'default' => esc_html__( 'Yes. The widget uses scoped classes and Elementor asset loading so it can be added without editing theme files.', 'ui-feature-elementor-widgets' ),
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
		<details class="ufe-widget ufe-widget-faq">
			<summary><?php echo esc_html( $settings['question'] ); ?></summary>
			<div class="ufe-widget-faq__answer">
				<?php echo wp_kses_post( $settings['answer'] ); ?>
			</div>
		</details>
		<?php
	}
}
