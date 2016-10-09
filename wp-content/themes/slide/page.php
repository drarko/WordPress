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

			</div><!-- /.type-page -->
		<?php endwhile; endif; ?>

		<?php
		/////////////////////////////////////////////
		// Query Category
		/////////////////////////////////////////////

		if ( $themify->query_category != '' ): ?>

			<?php
			/////////////////////////////////////////////
			// Sorting navigation
			/////////////////////////////////////////////

			if ( $themify->allow_sorting == 'yes' ): ?>
				<ul class='sorting-nav'>
					<li class='active all'><a href='#'><?php _e( 'All', 'themify' ) ?></a></li>
					<?php wp_list_categories( 'hierarchical=0&show_count=0&title_li=&include=' . $themify->query_category ); ?>
				</ul>
			<?php endif; ?>

			<?php query_posts( apply_filters( 'themify_query_posts_page_args', 'cat=' . $themify->query_category . '&posts_per_page=' . $themify->posts_per_page . '&paged=' . $themify->paged . '&order=' . $themify->order . '&orderby=' . $themify->orderby ) ); ?>

			<?php if ( have_posts() ): ?>

				<?php if ( 'slideshow' == $themify->post_layout ) { ?>
					<?php get_template_part( 'includes/layout', 'slideshow' ); ?>
				<?php } else { ?>
					<!-- loops-wrapper -->
					<div id="loops-wrapper" class="loops-wrapper <?php echo $themify->layout . ' ' . $themify->post_layout; ?>">

						<?php while ( have_posts() ) : the_post(); ?>
							<?php get_template_part( 'includes/loop', 'query' ); ?>
						<?php endwhile; ?>

					</div>
					<!-- /loops-wrapper -->
					<?php if ( $themify->page_navigation != "yes" ): ?>
						<?php get_template_part( 'includes/pagination' ); ?>
					<?php endif; ?>

				<?php } ?>

			<?php else : ?>

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
	if ($themify->layout == 'sidebar-none' || $themify->post_layout == 'slideshow' ) ; else get_sidebar(); ?>

	

</div>
<!-- /layout-container -->

<?php get_footer(); ?>
