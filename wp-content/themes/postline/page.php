<?php get_header(); ?>

<?php
/** Themify Default Variables
 *  @var object */
global $themify; ?>

<!-- layout-container -->
<div id="layout" class="pagewidth clearfix">

	<?php themify_content_before(); //hook ?>
	<!-- content -->
	<div id="content" class="clearfix">
    	<?php themify_content_start(); //hook ?>

		<?php
		/////////////////////////////////////////////
		// 404
		/////////////////////////////////////////////
		?>
		<?php if(is_404()): ?>
			<h1 class="page-title"><?php _e('404','themify'); ?></h1>
			<p><?php _e( 'Page not found.', 'themify' ); ?></p>
		<?php endif; ?>

		<?php
		/////////////////////////////////////////////
		// PAGE
		/////////////////////////////////////////////
		?>
		<?php if ( ! is_404() && have_posts() ) : while ( have_posts() ) : the_post(); ?>
			<div id="page-<?php the_ID(); ?>" class="type-page">
			<?php global $post; if( '' != $post->post_content || '' != $post->post_excerpt ): ?>
				<div class="page-inner clearfix">
			<?php endif; ?>
			<!-- page-title -->
			<?php if($themify->page_title != "yes"): ?>
				
				<time datetime="<?php the_time( 'o-m-d' ); ?>"></time>
				<h1 class="page-title"><?php the_title(); ?></h1>
			<?php endif; ?>
			<!-- /page-title -->

			<div class="page-content entry-content">

				<?php if ( $themify->hide_page_image != 'yes' && has_post_thumbnail() ) : ?>
					<figure class="post-image"><?php themify_image( "{$themify->auto_featured_image}w={$themify->page_image_width}&h=0&ignore=true" ); ?></figure>
				<?php endif; ?>

				<?php the_content(); ?>

				<?php wp_link_pages(array('before' => '<p class="post-pagination"><strong>'.__('Pages:','themify').'</strong> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>

				<?php edit_post_link(__('Edit','themify'), '[', ']'); ?>

				<!-- comments -->
				<?php if(!themify_check('setting-comments_pages') && $themify->query_category == ""): ?>
					<?php comments_template(); ?>
				<?php endif; ?>
				<!-- /comments -->

			</div>
			<!-- /.post-content -->
			<?php global $post; if( '' != $post->post_content || '' != $post->post_excerpt ): ?>
				</div><!-- /.post-inner -->
			<?php endif; ?>
			</div><!-- /.type-page -->
		<?php endwhile; endif; ?>

		<?php
		/////////////////////////////////////////////
		// Query Category
		/////////////////////////////////////////////
		if ( $themify->query_category != '' ): ?>

			<?php query_posts( apply_filters( 'themify_query_posts_page_args', 'cat=' . $themify->query_category . '&posts_per_page=' . $themify->posts_per_page . '&paged=' . $themify->paged . '&order=' . $themify->order . '&orderby=' . $themify->orderby ) ); ?>

			<?php if ( have_posts() ): ?>

				<?php if ( 'timeline' == $themify->post_layout ) : ?>
					<?php get_template_part( 'includes/layout-timeline', themify_get( 'timeline_query' ) ); ?>
				<?php else : ?>
					<!-- loops-wrapper -->
					<div id="loops-wrapper" class="loops-wrapper infinite-scrolling <?php echo $themify->layout . ' ' . $themify->post_layout; ?>">

						<?php while ( have_posts() ) : the_post(); ?>
							<article id="post-<?php the_ID(); ?>" <?php post_class( "post clearfix " . $themify->get_categories_as_classes( get_the_ID() ) ); ?>>
								<div class="post-inner">
									<?php get_template_part( 'includes/loop', 'query' ); ?>
								</div>
								<!-- /.post-inner -->
							</article>
							<!-- /.post -->
						<?php endwhile; ?>

					</div>
					<!-- /loops-wrapper -->
					<?php if ( $themify->page_navigation != 'yes' ): ?>
						<?php get_template_part( 'includes/pagination' ); ?>
					<?php endif; ?>

				<?php endif; // timeline layout ?>

			<?php endif; ?>

		<?php endif; ?>

		<?php wp_reset_query(); ?>

        <?php themify_content_end(); //hook ?>
	</div>
	<!-- /content -->
    <?php themify_content_after() //hook; ?>

	<?php
	/////////////////////////////////////////////
	// Sidebar
	/////////////////////////////////////////////
	if ($themify->layout != "sidebar-none"): get_sidebar(); endif; ?>

	

</div>
<!-- /layout-container -->

<?php get_footer(); ?>
