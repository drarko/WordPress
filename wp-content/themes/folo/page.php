<?php get_header(); ?>

<?php
/** Themify Default Variables
 *  @var object */
global $themify;
?>

<div id="upperwrap">
	<div id="upperwrap-inner">
		<div class="pagewidth">

			<?php if (is_front_page() && !is_paged()) { get_template_part( 'includes/slider'); } ?>

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
			// Page
			/////////////////////////////////////////////
			?>
			<!-- page-title -->
			<?php if($themify->page_title != "yes"): ?>
				
				<time datetime="<?php the_time( 'o-m-d' ); ?>"></time>
				<h1 class="page-title"><?php the_title(); ?></h1>
			<?php endif; ?>
			<!-- /page-title -->

		</div>
		<!-- /.pagewidth -->
	</div>
	<!-- /#upperwrap-inner -->
</div>
<!-- /#upperwrap -->

<!-- layout-container -->
<div id="layout" class="pagewidth clearfix">

	<?php if (is_front_page() && !is_paged()) { get_template_part( 'includes/home-highlights'); } ?>

	<?php if (is_front_page() && !is_paged()) { get_template_part( 'includes/welcome-message'); } ?>

	<?php themify_content_before(); //hook ?>
	<!-- content -->
	<div id="content" class="clearfix">
    	<?php themify_content_start(); //hook ?>

		<?php if ( ! is_404() && have_posts() ) : while ( have_posts() ) : the_post(); ?>
			<div id="page-<?php the_ID(); ?>" class="type-page">

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
			<!-- /page-content -->

			</div><!-- /.type-page -->
		<?php endwhile; endif; ?>

		<?php
		/////////////////////////////////////////////
		// Query category
		/////////////////////////////////////////////
		?>

		<?php
		///////////////////////////////////////////
		// Setting image width, height
		///////////////////////////////////////////
		if($themify->query_category != ""): ?>

			<?php
			/////////////////////////////////////////////
			// Sorting navigation
			/////////////////////////////////////////////
			?>
			<?php if($themify->allow_sorting == 'yes'): ?>
			<ul class='sorting-nav'>
				<li class='active all'><a href='#'><?php _e('All', 'themify'); ?></a></li>
				<?php
				wp_list_categories('hierarchical=0&show_count=0&title_li=&include='.$themify->query_category); ?>
			</ul>
			<?php endif; ?>

			<?php if(themify_get('section_categories') != 'yes'): ?>

				<?php query_posts( apply_filters( 'themify_query_posts_page_args', 'cat='.$themify->query_category.'&posts_per_page='.$themify->posts_per_page.'&paged='.$themify->paged.'&order='.$themify->order.'&orderby='.$themify->orderby ) ); ?>

					<?php if(have_posts()): ?>

						<!-- loops-wrapper -->
						<div class="<?php echo $themify->allow_sorting == 'yes' ? 'sortable' : '' ; ?> loops-wrapper <?php echo $themify->post_layout; ?>">

							<?php while(have_posts()) : the_post(); ?>

								<?php get_template_part('includes/loop', 'query'); ?>

							<?php endwhile; ?>

						</div>
						<!-- /loops-wrapper -->

						<?php if ($themify->page_navigation != "yes"): ?>
							<?php get_template_part( 'includes/pagination'); ?>
						<?php endif; ?>

					<?php else : ?>

					<?php endif; ?>

			<?php else: ?>

				<?php $categories = explode(",",str_replace(" ","",$themify->query_category)); ?>

				<?php foreach($categories as $category): ?>

				<?php $category = get_term_by(is_numeric($category)? 'id': 'slug', $category, 'category');
					$cats = get_categories( array( 'include' => isset( $category ) && isset( $category->term_id )? $category->term_id : 0, 'orderby' => 'id' ) ); ?>

				<?php foreach($cats as $cat): ?>

				<?php query_posts( apply_filters( 'themify_query_posts_page_args', 'cat='.$cat->cat_ID.'&posts_per_page='.$themify->posts_per_page.'&paged='.$themify->paged.'&order='.$themify->order.'&orderby='.$themify->orderby ) );	?>

					<?php if(have_posts()): ?>

						<!-- category-section -->
						<div class="category-section clearfix <?php echo $cat->slug; ?>-category">

							<h3 class="category-section-title"><a href="<?php echo esc_url( get_category_link($cat->cat_ID) ); ?>" title="<?php _e('View more posts', 'themify'); ?>"><?php echo $cat->cat_name; ?></a></h3>

							<div class='sortable loops-wrapper <?php echo $themify->post_layout; ?>'>

								<?php while(have_posts()) : the_post(); ?>

									<?php get_template_part('includes/loop', 'query'); ?>

								<?php endwhile; ?>

							</div>
							<!-- /.sortable -->

							<?php if ($themify->page_navigation != "yes"): ?>
								<?php get_template_part( 'includes/pagination'); ?>
							<?php endif; ?>

						</div>
						<!-- /category-section -->

					<?php else : ?>

					<?php endif; ?>

				<?php endforeach; ?>

				<?php endforeach; ?>

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

	

	<?php if (is_front_page() && !is_paged()) { get_template_part( 'includes/home-widgets'); } ?>

</div>
<!-- /layout-container -->

<?php get_footer(); ?>
