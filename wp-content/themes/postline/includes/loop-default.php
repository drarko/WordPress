<?php
/** Themify Default Variables
 * @var object */
	global $themify; ?>

<?php if($themify->hide_image != "yes"): ?>
	<?php themify_before_post_image(); // Hook ?>
	<?php
		//check if there is a video url in the custom field
		if( themify_has_post_video() ){
			echo themify_post_video();
		} else{ ?>
		<?php
		//otherwise display the featured image
		if( $post_image = themify_get_image($themify->auto_featured_image . $themify->image_setting . "w=".$themify->width."&h=".$themify->height ) ){ ?>

			<figure class="post-image <?php echo $themify->image_align; ?>">
				<?php if( 'yes' == $themify->unlink_image): ?>
					<?php echo $post_image; ?>
				<?php else: ?>
					<a href="<?php echo themify_get_featured_image_link(); ?>"><?php echo $post_image; ?><?php themify_zoom_icon(); ?></a>
				<?php endif; ?>
			</figure>

		<?php } ?>

	<?php }// end if video/image ?>
	<?php themify_after_post_image(); // Hook ?>
<?php endif; //post image ?>

<div class="post-content">

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

	<?php if($themify->hide_meta != 'yes'): ?>
			<p class="post-meta entry-meta">
				<span class="post-author"><?php echo themify_get_author_link(); ?> <em>&sdot;</em></span>
				<span class="post-category"><?php the_category(', '); ?> <em>&sdot;</em></span>
				<?php the_tags(' <span class="post-tag">', ', ', ' <em>&sdot;</em> </span>'); ?>
				<?php  if( !themify_get('setting-comments_posts') && comments_open() ) : ?>
					<span class="post-comment">
						<?php comments_popup_link( __( 'No comments', 'themify' ), __( '1 comment', 'themify' ), __( '% comments', 'themify' ) ); ?>
					</span>
				<?php endif; //post comment ?>
			</p>
		<?php endif; //post meta ?>

		<?php edit_post_link(__('Edit', 'themify'), '<span class="edit-button">[', ']</span>'); ?>

</div>
<!-- /.post-content -->
