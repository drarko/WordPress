<?php if(!is_single()) { global $more; $more = 0; } //enable more link ?>
<?php
/** Themify Default Variables
 *  @var object */
global $themify; ?>


<?php themify_post_before(); // hook ?>
<!-- post -->
<div id="post-<?php the_id(); ?>" <?php post_class( themify_check('post_color') && themify_get('post_color') != 'default' ? themify_get('post_color') : themify_get('setting-default_color') ); ?>>

	<!-- post -->
	<div class="post-inner clearfix <?php echo $themify->get_categories_as_classes(get_the_id()); ?> ">
		<?php themify_post_start(); // hook ?>

		<span class="post-icon"></span><!-- /post-icon -->
		<?php get_template_part('includes/loop-' . $themify->get_format_template()); ?>

		<?php edit_post_link(__('Edit', 'themify'), '<span class="edit-button">[', ']</span>'); ?>

		<!-- post-comments -->
		<?php  if( !themify_get('setting-comments_posts') && comments_open() ) : ?>
			<div class="post-comment">
			<?php comments_popup_link('0', '1', '%', 'comments-link', ''); ?>
			</div>
		<?php endif; //post comment ?>
		<!-- /post-comments -->

		<!-- post-meta -->
		<?php if($themify->hide_meta != 'yes' || $themify->hide_date != 'yes'): ?>
			<p class="post-meta entry-meta">
				<?php if($themify->hide_date != 'yes'): ?>
					<span class="post-date entry-date updated"><?php echo human_time_diff(get_the_time('U'), current_time('timestamp')) . ' ago'; ?></span>
				<?php endif; ?>

				<?php if($themify->hide_meta != 'yes'): ?>
					<?php echo get_the_term_list( get_the_id(), 'category', '<span class="post-category">&bull; ', ', ', ' </span>' ); ?>
					<?php the_tags('<span class="post-tag"> '.__('&bull; Tags: ','themify'), ', ', '</span>'); ?>
				<?php endif; ?>
			</p>
		<?php endif; ?>
		<!-- /post-meta -->

		<?php themify_post_end(); // hook ?>
	</div>
	<!-- /post-inner -->

</div>
<!-- /post -->
<?php themify_post_after(); // hook ?>
