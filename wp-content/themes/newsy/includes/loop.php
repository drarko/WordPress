<?php if(!is_single()) { global $more; $more = 0; } //enable more link ?>
<?php
/** Themify Default Variables
 *  @var object */
global $themify; ?>

<?php themify_post_before(); //hook ?>
<div id="post-<?php the_id(); ?>" <?php post_class("post clearfix " . $themify->get_categories_as_classes(get_the_id())); ?>>
	<?php themify_post_start(); //hook ?>

	<?php
	if( $post_image = themify_get_image($themify->auto_featured_image . $themify->image_setting . "w=".$themify->width."&h=".$themify->height ) ){
		if($themify->hide_image != 'yes'): ?>
			<?php themify_before_post_image(); // Hook ?>
			<div class="post-image <?php echo $themify->image_align; ?>">
				<?php if( 'yes' == $themify->unlink_image): ?>
					<?php echo $post_image; ?>
				<?php else: ?>
					<a href="<?php echo themify_get_featured_image_link(); ?>"><?php echo $post_image; ?><?php themify_zoom_icon(); ?></a>
				<?php endif; ?>
			</div>
			<?php themify_after_post_image(); // Hook ?>
		<?php endif; //post image
	} ?>
	<!--/post-image -->

	<div class="post-content">

		<?php if($themify->hide_date != "yes"): ?>
			<p class="post-date entry-date updated"><?php echo get_the_date( apply_filters( 'themify_loop_date', '' ) ) ?></p>
		<?php endif; ?>

		<?php if($themify->hide_title != "yes"): ?>
			<?php themify_before_post_title(); // Hook ?>
			<?php if($themify->unlink_title == "yes"): ?>
				<h1 class="post-title entry-title"><?php the_title(); ?> <?php  if( !themify_get('setting-comments_posts') && comments_open() ) : ?><sup class="post-comments"><?php comments_popup_link('0', '1', '%'); ?></sup><?php endif; //post comment ?></h1>
			<?php else: ?>
				<h1 class="post-title entry-title"><a href="<?php echo themify_get_featured_image_link(); ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a> <?php  if( !themify_get('setting-comments_posts') && comments_open() ) : ?><sup class="post-comments"><?php comments_popup_link('0', '1', '%'); ?></sup><?php endif; //post comment ?></h1>
			<?php endif; //unlink post title ?>
			<?php themify_after_post_title(); // Hook ?>
		<?php endif; ?>

		<?php if($themify->hide_meta != 'yes'): ?>
			<p class="post-meta entry-meta">
				<span class="post-author"><?php _e( 'by', 'themify' ); ?> <?php echo themify_get_author_link(); ?> &bull;</span>
				<span class="post-category"><?php the_category(', ') ?></span>
				<?php the_tags(__(' <span class="post-tag">&bull; Tags: ','themify'), ', ', '</span>'); ?></p>
		<?php endif; ?>

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

		<?php edit_post_link(__('Edit', 'themify'), '<span class="edit-button">[', ']</span>'); ?>

	</div>
	<!--/post-content -->
    <?php themify_post_end(); //hook ?>
	
</div>
<!--/post -->
<?php themify_post_after(); //hook ?>
