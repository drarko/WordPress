<?php
/** Themify Default Variables
 @var object */
	global $themify; ?>

<!-- post-image -->
<?php
if( $post_image = themify_get_image($themify->auto_featured_image . $themify->image_setting . "w=".$themify->width."&h=".$themify->height) ){
	if($themify->hide_image != 'yes'): ?>
		<?php themify_before_post_image(); // Hook ?>
		<p class="post-image <?php echo $themify->image_align; ?>">
			<?php if( 'yes' == $themify->unlink_image): ?>
				<?php echo $post_image; ?>
			<?php else: ?>
				<a href="<?php echo themify_get_featured_image_link(); ?>"><?php echo $post_image; ?><?php themify_zoom_icon(); ?></a>
			<?php endif; ?>
		</p>
		<?php themify_after_post_image(); // Hook ?>
	<?php endif; //post image
} ?>
<!-- /post-image -->

<!-- post-content -->
<div class="post-content">

	<!-- post-title -->
	<?php if($themify->hide_title != "yes"): ?>
		<?php themify_before_post_title(); // Hook ?>
		<?php if($themify->unlink_title == "yes"): ?>
			<h1 class="post-title entry-title"><?php the_title(); ?></h1>
		<?php else: ?>
			<h1 class="post-title entry-title"><a href="<?php echo themify_get_featured_image_link(); ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h1>
		<?php endif; //unlink post title ?>
		<?php themify_after_post_title(); // Hook ?>
	<?php endif; //post title ?>
	<!-- /post-title -->

	<div class="entry-content">

	<?php if ( 'excerpt' == $themify->display_content && ! is_attachment() ) : ?>

		<?php the_excerpt(); ?>

			<?php if( themify_check('setting-excerpt_more') ) : ?>
				<p><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute('echo=0'); ?>" class="more-link"><?php echo themify_check('setting-default_more_text')? themify_get('setting-default_more_text') : __('More &rarr;', 'themify') ?></a></p>
			<?php endif; ?>

	<?php elseif ( 'none' == $themify->display_content && ! is_attachment() ) : ?>

	<?php else: ?>

		<?php the_content(themify_check('setting-default_more_text')? themify_get('setting-default_more_text') : __('More &rarr;', 'themify')); ?>

	<?php endif; //display content ?>

	</div><!-- /.entry-content -->

</div><!--/post-content -->
