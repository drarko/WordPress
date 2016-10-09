<?php if(!is_single()) { global $more; $more = 0; } //enable more link ?>
<?php
/** Themify Default Variables
 *  @var object */
global $themify; ?>

<?php themify_post_before(); //hook ?>
<article id="post-<?php the_id(); ?>" <?php post_class( 'post clearfix ' . $themify->get_categories_as_classes(get_the_id()) . ' ' . $themify->get_post_color_class(get_the_id()) ); ?>>
	<?php themify_post_start(); //hook ?>

		<?php if ( $themify->hide_image != 'yes' ) : ?>

		<?php themify_before_post_image(); // Hook ?>

		<?php if ( themify_has_post_video() ) : ?>

			<?php echo themify_post_video(); ?>

		<?php elseif( $post_image = themify_get_image($themify->auto_featured_image . $themify->image_setting . "w=".$themify->width."&h=".$themify->height ) ) : ?>

			<figure class="post-image <?php echo $themify->image_align; ?>">
				<?php if( 'yes' == $themify->unlink_image): ?>
					<?php echo $post_image; ?>
				<?php else: ?>
					<a href="<?php echo themify_get_featured_image_link(); ?>"><?php echo $post_image; ?><?php themify_zoom_icon(); ?></a>
				<?php endif; // unlink image ?>
			</figure>

		<?php endif; // video else image ?>

		<?php themify_after_post_image(); // Hook ?>

	<?php endif; // hide image ?>

	<div class="post-inner">

		<span class="post-icon"></span>
		<!-- /post-icon -->

		<?php if($themify->hide_title != "yes"): ?>
			<?php themify_before_post_title(); // Hook ?>
			<?php if($themify->unlink_title == "yes"): ?>
				<h1 class="post-title entry-title"><?php the_title(); ?></h1>
			<?php else: ?>
				<h1 class="post-title entry-title"><a href="<?php echo themify_get_featured_image_link(); ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h1>
			<?php endif; //unlink post title ?>
			<?php themify_after_post_title(); // Hook ?>
		<?php endif; //post title ?>
		<!-- / Title -->

		<?php get_template_part('includes/loop-' . $themify->get_format_template()); ?>

		<?php if(is_single()): ?>
			<?php wp_link_pages(array('before' => '<p class="post-pagination"><strong>' . __('Pages:', 'themify') . '</strong> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>

			<?php get_template_part( 'includes/author-box', 'single'); ?>

			<?php get_template_part( 'includes/post-nav'); ?>

			<?php if(!themify_check('setting-comments_posts')): ?>
				<?php comments_template(); ?>
			<?php endif; ?>
		<?php endif; ?>

	</div>
	<!-- /.post-inner -->
    <?php themify_post_end(); //hook ?>

	

</article>
<!-- /.post -->
<?php themify_post_after(); //hook ?>
