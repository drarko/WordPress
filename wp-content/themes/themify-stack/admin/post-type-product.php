<?php
/**
 * Functions and modules related to products.
 *
 * @since 1.0.0
 */

if ( class_exists( 'WooCommerce' ) ) {
	// Specific for infinite scroll in WooCommerce archive pages.
	if ( 'infinite' == themify_get( 'setting-more_posts' ) || '' == themify_get( 'setting-more_posts' ) ) {
		// Remove WC standard pagination.
		remove_action( 'woocommerce_after_shop_loop', 'woocommerce_pagination', 10 );
		// Add Themify's pagination and infinite scroll.
		add_action( 'woocommerce_after_shop_loop', 'themify_shop_infinite_scroll' );
	}

	add_filter( 'the_title', 'themify_no_product_title' );
	add_filter( 'woocommerce_loop_add_to_cart_link', 'themify_no_product_add_to_cart' );

	if ( themify_get( 'setting-product_archive_hide_image' ) == 'no' ) {
		remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail' );
	}
	if ( themify_get( 'setting-product_archive_hide_rating' ) == 'no' ) {
		remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );
	}
	if ( themify_get( 'setting-product_archive_hide_price' ) == 'no' ) {
		remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price' );
	}
}

/**
 * Calculates the max number of pages a query will have and sets the number for JS.
 *
 * @since 1.0.0
 */
function themify_shop_infinite_scroll() {
	get_template_part( 'includes/pagination', 'product' );
}

/**
 * Disables title output following the setting applied in shop settings panel
 *
 * @param $button String
 *
 * @return String
 */
function themify_no_product_add_to_cart( $button ) {
	if ( in_the_loop() && is_shop() && themify_get( 'setting-product_archive_hide_add_to_cart' ) == 'no' ) {
		return '';
	}

	return $button;
}

/**
 * Disables add to cart button output following the setting applied in shop settings panel
 *
 * @param $title String
 *
 * @return String
 */
function themify_no_product_title( $title ) {
	if ( in_the_loop() && is_shop() && themify_get( 'setting-product_archive_hide_title' ) == 'no' ) {
		return '';
	}

	return $title;
}

if ( ! function_exists( 'themify_default_product_index_layout' ) ) {
	/**
	 * Default Index Product Layout
	 *
	 * @param array $data
	 *
	 * @return string
	 */
	function themify_default_product_index_layout( $data = array() ) {

		/**
		 * Default options 'yes', 'no'
		 *
		 * @var array
		 */
		$binary_options = array(
			array( 'name' => __( 'Yes', 'themify' ), 'value' => 'yes' ),
			array( 'name' => __( 'No', 'themify' ), 'value' => 'no' )
		);

		/**
		 * HTML for settings panel
		 *
		 * @var string
		 */
		$output = '';

		$options = array(
			array(
				'name'  => 'product_disable_masonry',
				'label' => __( 'Enable Masonry Layout', 'themify' ),
				'desc'  => __( 'Masonry produces the post stacking layout (products are placed above each other)', 'themify' ),
			),
			array(
				'name'  => 'product_archive_hide_image',
				'label' => __( 'Show Image', 'themify' ),
				'desc'  => '',
			),
			array(
				'name'  => 'product_archive_hide_title',
				'label' => __( 'Show Title', 'themify' ),
				'desc'  => '',
			),
			array(
				'name'  => 'product_archive_hide_price',
				'label' => __( 'Show Price', 'themify' ),
				'desc'  => '',
			),
			array(
				'name'  => 'product_archive_hide_rating',
				'label' => __( 'Show Rating', 'themify' ),
				'desc'  => '',
			),
			array(
				'name'  => 'product_archive_hide_add_to_cart',
				'label' => __( 'Show Add To Cart', 'themify' ),
				'desc'  => '',
			),
		);

		foreach ( $options as $option ) {
			$output .= '<p>
							<span class="label">' . esc_attr( $option['label'] ) . '</span>
							<select name="setting-' . esc_attr( $option['name'] ) . '">' . themify_options_module( $binary_options, 'setting-' . $option['name'] ) . '
							</select>';
			if ( ! empty( $option['desc'] ) ) {
				$output .= '<br/><small>' . wp_kses_post( $option['desc'] ) . '</small>';
			}
			$output .= '</p>';
		}

		return $output;
	}
}