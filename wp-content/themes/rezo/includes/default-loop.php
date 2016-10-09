<?php if(!is_single()) { global $more; $more = 0; } //enable more link ?>
<?php
/** Themify Default Variables
 *  @var object */
	global $themify; ?>

<?php themify_post_before(); //hook ?>
<div id="post-<?php the_id(); ?>" <?php post_class( 'post clearfix' ); ?>>
	<?php themify_post_start(); //hook ?>

	<?php if($themify->hide_title != "yes"): ?>
		<?php themify_before_post_title(); // Hook ?>
		<?php if($themify->unlink_title == "yes"): ?>
			<h2 class="post-title"><?php the_title(); ?> <?php  if( !themify_get('setting-comments_posts') && comments_open() ) : ?><sup class="post-comment"><?php comments_popup_link('0', '1', '%'); ?></sup><?php endif; //post comment ?></h2>
		<?php else: ?>
			<h2 class="post-title"><a href="<?php the_permalink() ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a> <?php  if( !themify_get('setting-comments_posts') && comments_open() ) : ?><sup class="post-comment"><?php comments_popup_link('0', '1', '%'); ?></sup><?php endif; //post comment ?></h2>
		<?php endif; //unlink post title ?>
		<?php themify_after_post_title(); // Hook ?>
	<?php endif; ?>

	<?php if ( $themify->hide_meta != 'yes' || $themify->hide_date != 'yes' ) : ?>
		<p class="post-meta">
			<?php if ( $themify->hide_meta != 'yes' ) : ?>
				<span class="post-author"><?php printf( __('<em>by</em> %s', 'themify'), get_the_author() ); ?> &bull;</span>
				<span class="post-category"><?php the_category(', ') ?></span>
				<?php the_tags(__(' <span class="post-tag">&bull; Tags: ','themify'), ', ', '</span>'); ?>
			<?php endif; // post meta ?>
			<?php if ( $themify->hide_date != 'yes' ) : ?>
				<span class="post-date"><?php printf( __( '<em>on</em> %s', 'themify' ), get_the_date( apply_filters( 'themify_loop_date', '' ) ) ); ?></span>
			<?php endif; ?>
		</p>
	<?php endif; ?>

	<?php
	if( $post_image = themify_get_image($themify->auto_featured_image . $themify->image_setting . "w=".$themify->width."&h=".$themify->height) ){
		if($themify->hide_image != 'yes'): ?>
			<?php themify_before_post_image(); // Hook ?>
			<div class="post-image <?php echo $themify->image_align; ?>">
				<?php if( 'yes' == $themify->unlink_image): ?>
					<?php echo $post_image; ?>
				<?php else: ?>
					<a href="<?php echo $themify->external_link(); ?>"><?php echo $post_image; ?></a>
				<?php endif; ?>
			</div>
			<?php themify_after_post_image(); // Hook ?>
		<?php endif; //post image
	} ?>
	<!-- /post-image -->
	<div class="post-content">
		<?php if($themify->display_content == 'excerpt'){ ?>
			<?php the_excerpt(); ?>

			<?php if( themify_check('setting-excerpt_more') ) : ?>
				<p><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute('echo=0'); ?>" class="more-link"><?php echo themify_check('setting-default_more_text')? themify_get('setting-default_more_text') : __('More &rarr;', 'themify') ?></a></p>
			<?php endif; ?>
		<?php } elseif($themify->display_content == 'none'){ ?>
		<?php } else { ?>
			<?php the_content(themify_check('setting-default_more_text')? themify_get('setting-default_more_text') : __('More &rarr;', 'themify')); ?>
		<?php } ?>
	</div>
	<!-- /post-content -->

	<?php edit_post_link(__('Edit', 'themify'), '<span class="edit-button">[', ']</span>'); ?>
    <?php themify_post_end(); //hook ?>

</div>
<!--/post -->
<?php themify_post_after(); //hook ?>
