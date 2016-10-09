<?php if(!is_single()) { global $more; $more = 0; } //enable more link ?>
<?php
/** Themify Default Variables
 *  @var object */
global $themify; ?>

<?php themify_post_before(); //hook ?>
<article id="post-<?php the_id(); ?>" <?php post_class("post clearfix portfolio-post" . $themify->theme->get_categories_as_classes(get_the_id()) . ' ' . $themify->theme->get_post_classes(get_the_id())); ?>>

	<div class="post-inner">
		<?php themify_post_start(); // hook ?>

		<?php if(themify_check('gallery_shortcode')) { ?>
			<?php themify_before_post_image(); // hook ?>
			<div class="post-media">
				<?php $themify->theme->show_gallery(); ?>
			</div>
			<?php themify_after_post_image(); // hook ?>
		<?php } elseif(themify_check('slider')) { ?>
			<?php themify_before_post_image(); // hook ?>
			<div class="post-media">
				<?php $themify->theme->show_slider(); ?>
			</div>
			<?php themify_after_post_image(); // hook ?>
		<?php } elseif( themify_check('video_url') ) { ?>
			<?php themify_before_post_image(); // hook ?>
			<div class="post-media">
				<?php $themify->theme->show_video(); ?>
			</div>
			<?php themify_after_post_image(); // hook ?>
		<?php } elseif( $post_image = themify_get_image($themify->auto_featured_image . $themify->image_setting . "w=".$themify->width."&h=".$themify->height ) ) { ?>
			<?php if($themify->hide_image != 'yes') { ?>
				<?php themify_before_post_image(); // hook ?>
				<div class="post-media">
					<figure class="post-image <?php echo $themify->image_align; ?>">
						<?php $themify->theme->show_image($post_image); ?>
					</figure>
				</div>
				<?php themify_after_post_image(); // hook ?>
			<?php } //post image ?>
		<?php } ?>

		<div class="post-content">

			<?php if($themify->hide_meta != 'yes'): ?>
				<p class="post-meta entry-meta">
					<?php echo get_the_term_list( get_the_id(), get_post_type().'-category', ' <span class="post-category">', ', ', '</span>' ); ?>
				</p>
			<?php endif; //post meta ?>

			<?php if($themify->hide_title != 'yes'): ?>
				<?php themify_before_post_title(); // hook ?>
				<?php if($themify->unlink_title == 'yes'): ?>
					<h1 class="post-title entry-title"><?php the_title(); ?></h1>
				<?php else: ?>
					<h1 class="post-title entry-title"><a href="<?php echo themify_get_featured_image_link(); ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h1>
				<?php endif; //unlink post title ?>
				<?php themify_after_post_title(); // hook ?>
			<?php endif; //post title ?>

			<?php if($themify->hide_date != 'yes'): ?>
				<time datetime="<?php the_time('o-m-d') ?>" class="post-date entry-date updated"><?php echo get_the_date( apply_filters( 'themify_loop_date', '' ) ) ?></time>
			<?php endif; //post date ?>

			<div class="entry-content">

			<?php if ( 'excerpt' == $themify->display_content && ! is_attachment() ) : ?>

				<?php the_excerpt(); ?>

			<?php elseif ( 'none' == $themify->display_content && ! is_attachment() ) : ?>

			<?php else: ?>

				<?php the_content(themify_check('setting-default_more_text')? themify_get('setting-default_more_text') : __('More &rarr;', 'themify')); ?>

			<?php endif; //display content ?>

			</div><!-- /.entry-content -->

			<?php edit_post_link(__('Edit', 'themify'), '<span class="edit-button">[', ']</span>'); ?>

		</div>
		<!-- /.post-content -->

	<?php themify_post_end(); // hook ?>
	</div> <!-- /.post-inner -->

</article>
<!-- /.post -->
<?php themify_post_after(); // hook ?>
