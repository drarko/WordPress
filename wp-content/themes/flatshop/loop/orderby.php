<?php
/**
 * Show options for ordering
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/loop/orderby.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woothemes.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.2.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>

	<?php themify_sorting_before(); //hook ?>
	<div class="orderby-wrap">
		<h4 class="sort-by"><?php _e('Sort by', 'themify') ?></h4>
		<ul class="orderby">
		<?php
		foreach ( $catalog_orderby_options as $id => $name ) {
			$selected = isset( $_GET['orderby'] ) && $_GET['orderby'] == $id ? 'class="selected"': '';
			echo '<li ' . $selected . '><a href="'. esc_url( add_query_arg( array( 'orderby' => $id ) ) ) . '">' . esc_attr( $name ) . '</a></li>';
		}
		?>
		</ul>
	</div>
	<!-- /orderby-wrap -->
	<div class="sorting-gap"></div>

	<?php themify_sorting_after(); //hook ?>
	<?php
	// Keep query string vars intact
	foreach ( $_GET as $key => $val ) {
		if ( 'orderby' == $key )
			continue;
		echo '<input type="hidden" name="' . esc_attr( $key ) . '" value="' . esc_attr( $val ) . '" />';
	}
?>