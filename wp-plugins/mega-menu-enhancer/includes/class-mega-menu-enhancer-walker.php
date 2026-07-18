<?php
/**
 * Enhanced nav menu walker.
 *
 * @package MegaMenuEnhancer
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Outputs scoped mega-menu markup without modifying theme menu locations.
 */
final class Mega_Menu_Enhancer_Walker extends Walker_Nav_Menu {
	/** Number of dropdown columns. */
	private $columns;

	/** Promo block settings. */
	private $promo;

	/**
	 * Constructor.
	 *
	 * @param int   $columns Column count.
	 * @param array $promo Promo block settings.
	 */
	public function __construct( $columns, $promo ) {
		$this->columns = $columns;
		$this->promo   = $promo;
	}

	/**
	 * Starts a submenu level.
	 *
	 * @param string $output Used to append additional content.
	 * @param int    $depth Depth of menu item.
	 * @param object $args Menu args.
	 * @return void
	 */
	public function start_lvl( &$output, $depth = 0, $args = null ) {
		$indent = str_repeat( "\t", $depth );
		$class  = 0 === $depth ? 'mme-submenu mme-submenu--mega' : 'mme-submenu';
		$output .= "\n$indent<ul class=\"" . esc_attr( $class ) . "\" style=\"--mme-columns:" . esc_attr( (string) $this->columns ) . ";\">\n";
	}

	/**
	 * Ends a submenu level.
	 *
	 * @param string $output Used to append additional content.
	 * @param int    $depth Depth of menu item.
	 * @param object $args Menu args.
	 * @return void
	 */
	public function end_lvl( &$output, $depth = 0, $args = null ) {
		$indent = str_repeat( "\t", $depth );

		if ( 0 === $depth && $this->has_promo() ) {
			$output .= $this->render_promo();
		}

		$output .= "$indent</ul>\n";
	}

	/**
	 * Starts an element output.
	 *
	 * @param string  $output Used to append additional content.
	 * @param WP_Post $item Menu item data object.
	 * @param int     $depth Depth of menu item.
	 * @param object  $args Menu args.
	 * @param int     $id Current item ID.
	 * @return void
	 */
	public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
		$classes      = empty( $item->classes ) ? array() : (array) $item->classes;
		$has_children = in_array( 'menu-item-has-children', $classes, true );
		$is_promo     = in_array( 'mme-promo', $classes, true );
		$icon         = $this->get_icon_from_classes( $classes );
		$class_names  = array_filter(
			array_merge(
				$classes,
				array(
					'mme-menu__item',
					$has_children ? 'mme-menu__item--has-children' : '',
					$is_promo ? 'mme-menu__item--promo' : '',
				)
			)
		);
		$class_names  = implode( ' ', array_map( 'sanitize_html_class', $class_names ) );
		$description  = trim( (string) $item->description );
		$title        = apply_filters( 'the_title', $item->title, $item->ID );
		$atts         = array(
			'href' => ! empty( $item->url ) ? $item->url : '',
		);

		$output .= '<li class="' . esc_attr( $class_names ) . '">';
		$output .= '<div class="mme-menu__link-wrap">';
		$output .= '<a class="mme-menu__link" href="' . esc_url( $atts['href'] ) . '">';

		if ( '' !== $icon ) {
			$output .= '<span class="mme-menu__icon" aria-hidden="true">' . esc_html( $icon ) . '</span>';
		}

		$output .= '<span class="mme-menu__text"><span class="mme-menu__title">' . esc_html( $title ) . '</span>';

		if ( '' !== $description ) {
			$output .= '<span class="mme-menu__description">' . esc_html( $description ) . '</span>';
		}

		$output .= '</span></a>';

		if ( $has_children ) {
			$output .= '<button class="mme-menu__toggle" type="button" aria-expanded="false"><span class="screen-reader-text">' . esc_html__( 'Toggle submenu', 'mega-menu-enhancer' ) . '</span></button>';
		}

		$output .= '</div>';
	}

	/**
	 * Ends an element output.
	 *
	 * @param string  $output Used to append additional content.
	 * @param WP_Post $item Menu item data object.
	 * @param int     $depth Depth of menu item.
	 * @param object  $args Menu args.
	 * @return void
	 */
	public function end_el( &$output, $item, $depth = 0, $args = null ) {
		$output .= "</li>\n";
	}

	/**
	 * Get an icon from menu item classes.
	 *
	 * Supports classes like `mme-icon-★`, `mme-icon-home`, or `icon-★`.
	 *
	 * @param array $classes Menu item classes.
	 * @return string
	 */
	private function get_icon_from_classes( $classes ) {
		$icon_map = array(
			'home'      => '⌂',
			'star'      => '★',
			'bolt'      => '⚡',
			'cart'      => '🛒',
			'book'      => '📘',
			'mail'      => '✉',
			'support'   => '❔',
			'analytics' => '◈',
		);

		foreach ( $classes as $class ) {
			$class = sanitize_html_class( $class );

			if ( 0 === strpos( $class, 'mme-icon-' ) ) {
				$key = substr( $class, 9 );
				return isset( $icon_map[ $key ] ) ? $icon_map[ $key ] : strtoupper( substr( $key, 0, 1 ) );
			}

			if ( 0 === strpos( $class, 'icon-' ) ) {
				$key = substr( $class, 5 );
				return isset( $icon_map[ $key ] ) ? $icon_map[ $key ] : strtoupper( substr( $key, 0, 1 ) );
			}
		}

		return '';
	}

	/**
	 * Determine whether a shortcode-level promo block should render.
	 *
	 * @return bool
	 */
	private function has_promo() {
		return ! empty( $this->promo['title'] ) || ! empty( $this->promo['text'] );
	}

	/**
	 * Render shortcode-level promo block.
	 *
	 * @return string
	 */
	private function render_promo() {
		$output  = '<li class="mme-menu__item mme-menu__promo-block">';
		$output .= '<div class="mme-promo-card">';

		if ( ! empty( $this->promo['title'] ) ) {
			$output .= '<strong>' . esc_html( $this->promo['title'] ) . '</strong>';
		}

		if ( ! empty( $this->promo['text'] ) ) {
			$output .= '<p>' . esc_html( $this->promo['text'] ) . '</p>';
		}

		if ( ! empty( $this->promo['url'] ) ) {
			$output .= '<a href="' . esc_url( $this->promo['url'] ) . '">' . esc_html( $this->promo['label'] ) . '</a>';
		}

		$output .= '</div></li>';

		return $output;
	}
}
