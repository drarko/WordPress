<?php
/**
 * Related Products
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/related.php.
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
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( isset( $_GET['post_in_lightbox'] ) && 1 == $_GET['post_in_lightbox'] ) exit;

global $product, $woocommerce_loop, $themify;

$related = $product->get_related();

$themify->is_related_loop = true;

if ( sizeof( $related ) == 0 ) return;

$args = apply_filters('woocommerce_related_products_args', array(
	'post_type'				=> 'product',
	'ignore_sticky_posts'	=> 1,
	'no_found_rows' 		=> 1,
	'posts_per_page' 		=> $posts_per_page,
	'orderby' 				=> $orderby,
	'post__in' 				=> $related,
	'post__not_in'			=> array($product->id)
) );

$products = new WP_Query( $args );

$woocommerce_loop['columns'] 	= $columns;

$grid = themify_get( 'setting-related_products_limit' );

switch ( $grid ) {
	case $grid % 4 == 0:
		$grid = 'grid4';
		break;
	case $grid % 3 == 0:
		$grid = 'grid3';
		break;
	case $grid % 2 == 0:
		$grid = 'grid2';
		break;
	default:
		$grid = 'grid3';
		break;
}

if ( $products->have_posts() ) : ?>

	<div class="related products <?php echo $grid; ?> noisotope <?php echo 'sidebar-none' == $themify->layout? 'pagewidth' : ''; ?>">

		<h2 class=""><?php _e( 'Related Products', 'woocommerce' ); ?></h2>

		<?php woocommerce_product_loop_start(); ?>

			<?php while ( $products->have_posts() ) : $products->the_post(); ?>

				<?php wc_get_template_part( 'content', 'product' ); ?>

			<?php endwhile; // end of the loop. ?>

		<?php woocommerce_product_loop_end(); ?>

	</div>

<?php endif;

$themify->is_related_loop = false;

wp_reset_postdata();
