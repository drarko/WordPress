<?php
/**
 * Ajax Actions
 * @package themify
 * @since 1.0.0
 */

add_action( 'wp_ajax_timeline_add_element', 'themify_timeline_add_element_ajaxify', 10 );
add_action( 'wp_ajax_nopriv_timeline_add_element', 'themify_timeline_add_element_ajaxify' );

function themify_timeline_add_element_ajaxify() {

	check_ajax_referer( 'timeline_load_nonce', 'timeline_load_nonce' );

	$post_ids = is_array($_POST['post_ids']) ? $_POST['post_ids'] : array() ;
	$posts_page = $_POST['posts_page'];
	if ( 'page' == $_POST['context'] ) {
		$pageId = (int) $_POST['query_page_id'];
		$meta_order = get_post_meta( $pageId, 'order', true );
		$meta_orderby = get_post_meta( $pageId, 'orderby', true );
		$order = ($meta_order && '' != $meta_order) ? $meta_order : (themify_check('setting-index_order') ? themify_get('setting-index_order') : 'DESC');
		$orderby = ($meta_orderby && '' != $meta_orderby) ? $meta_orderby : (themify_check('setting-index_orderby') ? themify_get('setting-index_orderby') : 'date');
	} else {
		$order = themify_check('setting-index_order')? themify_get('setting-index_order'): 'DESC';
		$orderby = themify_check('setting-index_orderby')? themify_get('setting-index_orderby'): 'date';
	}

	$query_args = array(
		'post__in' => $post_ids,
		'posts_per_page' => $posts_page,
		'order' => $order,
		'orderby' => $orderby
	);
	$args = array(
		'context' => $_POST['context'],
		'query_page_id' => $_POST['query_page_id']
	);

	if( count($post_ids > 0) ) {
            echo '<div>';
                themify_timeline_loop_element( $query_args, $args );
            echo '</div>';
	}

	die();
}

function themify_timeline_loop_element( $query_args, $args ) {
	global $themify;

	// The Query
	$the_query = new WP_Query( $query_args );

	// set image width and height
	$timeline_default_width = apply_filters( 'themify_timeline_loaded_element_image_width',
		themify_check('setting-image_post_width')? themify_get('setting-image_post_width'): 365 );
	$timeline_default_height = apply_filters( 'themify_timeline_loaded_element_image_height',
		themify_check('setting-image_post_height')?	themify_get('setting-image_post_height'): 0 );

	if( 'page' == $args['context'] ) {
		$query_page_width = get_post_meta( $args['query_page_id'], 'image_width', true);
		$query_page_height = get_post_meta( $args['query_page_id'], 'image_height', true);

		$themify->width = $query_page_width? $query_page_width:	$timeline_default_width;
		$themify->height = $query_page_height? $query_page_height: $timeline_default_height;
		$themify->hide_title = get_post_meta( $args['query_page_id'], 'hide_title', true);
		$themify->unlink_title = get_post_meta( $args['query_page_id'], 'unlink_title', true);
		$themify->hide_image = get_post_meta( $args['query_page_id'], 'hide_image', true);
	    $themify->unlink_image = get_post_meta( $args['query_page_id'], 'unlink_image', true);
		$themify->hide_meta = get_post_meta( $args['query_page_id'], 'hide_meta', true);
		$themify->hide_date = get_post_meta( $args['query_page_id'], 'hide_date', true);
		$themify->display_content = get_post_meta( $args['query_page_id'], 'display_content', true);
	} else {
		$themify->width = $timeline_default_width;
		$themify->height = $timeline_default_height;
	}
	$themify->image_setting = 'ignore=true&';

	// The Loop
	while ( $the_query->have_posts() ) :
		$the_query->the_post(); ?>

		<?php themify_post_before(); //hook ?>
		<article id="post-<?php the_ID(); ?>" <?php post_class("post clearfix"); ?>>
		<?php themify_post_start(); //hook ?>

			<div class="post-inner">
			<?php if(is_search()): ?>
				<?php get_template_part( 'includes/loop' , 'search'); ?>
			<?php else: ?>
				<?php get_template_part( 'includes/loop' , 'index'); ?>
			<?php endif; ?>
			</div>
			<!-- /.post-inner -->

		<?php themify_post_end(); //hook ?>
		</article>
		<!-- /.post -->
		<?php themify_post_after(); //hook ?>

	<?php
	endwhile;
	wp_reset_postdata();

}
?>
