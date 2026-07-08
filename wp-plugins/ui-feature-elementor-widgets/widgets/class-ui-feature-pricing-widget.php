<?php
/**
 * Pricing card Elementor widget.
 *
 * @package UIFeatureElementorWidgets
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Pricing card widget.
 */
final class UI_Feature_Pricing_Widget extends \Elementor\Widget_Base {
	/**
	 * Get widget name.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'ui_feature_pricing';
	}

	/**
	 * Get widget title.
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'UI Pricing Card', 'ui-feature-elementor-widgets' );
	}

	/**
	 * Get widget icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-price-table';
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
				'label' => esc_html__( 'Pricing Content', 'ui-feature-elementor-widgets' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'plan_name',
			array(
				'label'       => esc_html__( 'Plan Name', 'ui-feature-elementor-widgets' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => esc_html__( 'Starter', 'ui-feature-elementor-widgets' ),
				'label_block' => true,
			)
		);

		$this->add_control(
			'price',
			array(
				'label'   => esc_html__( 'Price', 'ui-feature-elementor-widgets' ),
				'type'    => \Elementor\Controls_Manager::TEXT,
				'default' => '$19',
			)
		);

		$this->add_control(
			'description',
			array(
				'label'   => esc_html__( 'Description', 'ui-feature-elementor-widgets' ),
				'type'    => \Elementor\Controls_Manager::TEXTAREA,
				'default' => esc_html__( 'A focused plan for small teams launching quickly.', 'ui-feature-elementor-widgets' ),
			)
		);

		$this->add_control(
			'button_text',
			array(
				'label'   => esc_html__( 'Button Text', 'ui-feature-elementor-widgets' ),
				'type'    => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'Get started', 'ui-feature-elementor-widgets' ),
			)
		);

		$this->add_control(
			'button_url',
			array(
				'label'       => esc_html__( 'Button URL', 'ui-feature-elementor-widgets' ),
				'type'        => \Elementor\Controls_Manager::URL,
				'placeholder' => 'https://example.com',
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
		$settings      = $this->get_settings_for_display();
		$button_url    = isset( $settings['button_url']['url'] ) ? $settings['button_url']['url'] : '';
		$button_target = ! empty( $settings['button_url']['is_external'] ) ? '_blank' : '';
		$button_rel    = ! empty( $settings['button_url']['nofollow'] ) ? 'nofollow' : '';
		?>
		<section class="ufe-widget ufe-widget-pricing">
			<p class="ufe-widget-pricing__eyebrow"><?php echo esc_html__( 'Plan', 'ui-feature-elementor-widgets' ); ?></p>
			<h2><?php echo esc_html( $settings['plan_name'] ); ?></h2>
			<p class="ufe-widget-pricing__price"><?php echo esc_html( $settings['price'] ); ?></p>
			<p><?php echo esc_html( $settings['description'] ); ?></p>
			<?php if ( '' !== $button_url ) : ?>
				<a class="ufe-widget-pricing__button" href="<?php echo esc_url( $button_url ); ?>"<?php echo '' !== $button_target ? ' target="' . esc_attr( $button_target ) . '"' : ''; ?><?php echo '' !== $button_rel ? ' rel="' . esc_attr( $button_rel ) . '"' : ''; ?>>
					<?php echo esc_html( $settings['button_text'] ); ?>
				</a>
			<?php else : ?>
				<span class="ufe-widget-pricing__button"><?php echo esc_html( $settings['button_text'] ); ?></span>
			<?php endif; ?>
		</section>
		<?php
	}
}
