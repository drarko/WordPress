<?php if(!is_single()) { global $more; $more = 0; } //enable more link ?>
<?php
/** Themify Default Variables
 *  @var object */
	global $themify; ?>

<?php themify_post_before(); //hook ?>
<div id="post-<?php the_id(); ?>" <?php post_class( 'post clearfix' ); ?>>
	<?php themify_post_start(); //hook ?>

	<?php
	if( $post_image = themify_get_image($themify->auto_featured_image . $themify->image_setting . "w=".$themify->width."&h=".$themify->height) ){
		if($themify->hide_image != 'yes'): ?>
			<?php themify_before_post_image(); // hook ?>
			<div class="post-image <?php echo $themify->image_align; ?>">
				<?php if( 'yes' == $themify->unlink_image): ?>
					<?php echo $post_image; ?>
				<?php else: ?>
					<a href="<?php echo $themify->external_link(); ?>"><?php echo $post_image; ?></a>
				<?php endif; ?>
			</div>
			<?php themify_after_post_image(); // hook ?>
		<?php endif; //post image
	} ?>
	<!-- /post-image -->

	<div class="post-content">

		<?php if($themify->hide_date != "yes"): ?>
			<p class="post-date"><?php echo get_the_date( apply_filters( 'themify_loop_date', '' ) ) ?></p>
		<?php endif; ?>

		<?php if($themify->hide_title != "yes"): ?>
			<?php themify_before_post_title(); // hook ?>
			<?php if($themify->unlink_title == "yes"): ?>
				<h2 class="post-title"><?php the_title(); ?></h2>
			<?php else: ?>
				<h2 class="post-title"><a href="<?php the_permalink() ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
			<?php endif; //unlink post title ?>
			<?php themify_after_post_title(); // hook ?>
		<?php endif; ?>

		<?php if($themify->hide_meta != 'yes'): ?>
			<p class="post-meta">
				<span class="post-author"><em>by</em> <?php the_author() ?> &bull;</span>
				<span class="post-category"><?php the_category(', ') ?></span>
				<?php the_tags(__(' <span class="post-tag">&bull; Tags: ','themify'), ', ', '</span>'); ?>
				<?php  if( !themify_get('setting-comments_posts') && comments_open() ) : ?>
                	<span class="post-comment">&bull; <?php comments_popup_link( __( '0 Comments', 'themify' ), __( '1 Comment', 'themify' ), __( '% Comments', 'themify' ) ); ?></span>
                <?php endif; //post comment ?>
			</p>
		<?php endif; ?>

		<?php if ( 'excerpt' == $themify->display_content && ! is_attachment() ) : ?>

			<?php the_excerpt(); ?>

		<?php elseif ( 'none' == $themify->display_content && ! is_attachment() ) : ?>

		<?php else: ?>

			<?php the_content(themify_check('setting-default_more_text')? themify_get('setting-default_more_text') : __('More &rarr;', 'themify')); ?>

		<?php endif; ?>

		<?php edit_post_link('Edit', '[', ']'); ?>
	</div><!-- /post-content -->
    <?php themify_post_end(); //hook ?>

</div>
<!-- /post -->
<?php themify_post_after(); //hook ?>
