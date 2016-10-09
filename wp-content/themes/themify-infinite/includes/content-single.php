<?php
/**
 * Partial template for single view.
 * Loaded in regular page load queries and also through ajax with Themify Single Infinite.
 *
 * @since 1.0.0
 */
global $themify;
?>

<div class="single-wrapper <?php echo esc_attr( themify_theme_single_wrapper_classes() ); ?>" data-title="<?php the_title_attribute(); ?>" data-url="<?php echo esc_url( themify_https_esc( get_permalink() ) ); ?>">

	<?php if ( 'sidebar-none' == $themify->layout ) : ?>
		<div class="featured-area">
			<?php get_template_part( 'includes/post-media', 'loop' ); ?>
		</div>
	<?php endif; // themify single infinite scroll ?>

	<div class="pagewidth">

		<?php themify_content_before(); // hook ?>
		<!-- content -->
		<div id="content" class="list-post js-sticky-share-content js-sticky-sidebar-content">
			<?php themify_content_start(); // hook ?>

			<?php get_template_part( 'includes/loop', 'single' ); ?>

			<?php wp_link_pages( array( 'before' => '<p class="post-pagination"><strong>' . __( 'Pages:', 'themify' ) . ' </strong>', 'after' => '</p>', 'next_or_number' => 'number', ) ); ?>

			<?php get_template_part( 'includes/author-box', 'single' ); ?>

			<?php if ( ! themify_theme_is_single_infinite_enabled() ) : get_template_part( 'includes/post-nav' ); endif; ?>

			<?php if ( ! themify_check( 'setting-comments_posts' ) ) : ?>
				<?php comments_template(); ?>
			<?php endif; ?>

			<?php themify_content_end(); // hook ?>
		</div>
		<!-- /content -->
		<?php themify_content_after(); // hook ?>

		<?php
		/////////////////////////////////////////////
		// Sidebar
		/////////////////////////////////////////////
		if ( $themify->layout != 'sidebar-none' ) : get_sidebar(); endif; ?>

	</div>
	<!-- /.pagewidth -->

</div>
<!-- /.single-wrapper -->
