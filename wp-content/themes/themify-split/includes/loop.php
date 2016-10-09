<?php
/**
 * Template for generic post display.
 * @package themify
 * @since 1.0.0
 */
?>
<?php if(!is_single()){ global $more; $more = 0; } //enable more link ?>

<?php
/** Themify Default Variables
 *  @var object */
global $themify; ?>

<?php themify_post_before(); // hook ?>
<article id="post-<?php the_id(); ?>" <?php post_class( 'post clearfix' ); ?>>
	<?php themify_post_start(); // hook ?>


	<div class="post-content">

		<?php if ( ! is_single() ) : ?>

			<?php if ( 'below' != $themify->media_position ) : ?>
				<?php get_template_part( 'includes/post-media', 'loop'); ?>
			<?php endif; ?>

			<p class="post-meta entry-meta">
			<?php if ( $themify->hide_meta != 'yes' && $themify->hide_meta_category != 'yes' ) : ?>
				<?php the_terms( get_the_id(), 'category', ' <span class="post-category">', ', ', '</span>' ); ?>
			<?php endif; ?>
			</p>

			<?php if ( $themify->hide_title != 'yes' ): ?>
				<?php themify_before_post_title(); // Hook ?>

				<h2 class="post-title entry-title">
					<?php if ( $themify->unlink_title == 'yes' ): ?>
						<?php the_title(); ?>
					<?php else: ?>
						<a href="<?php echo themify_get_featured_image_link(); ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a>
					<?php endif; //unlink post title ?>
				</h2>

				<?php themify_after_post_title(); // Hook ?>
			<?php endif; //post title ?>

			<?php if ( 'below' == $themify->media_position ) : ?>
				<?php get_template_part( 'includes/post-media', 'loop'); ?>
			<?php endif; ?>

			<?php if ( $themify->hide_meta != 'yes' || $themify->hide_date != 'yes' ) : ?>
				<p class="post-meta entry-meta">

					<?php if ( $themify->hide_meta != 'yes' && $themify->hide_meta_author != 'yes' ) : ?>
						<span class="author-avatar"><?php echo get_avatar( get_the_author_meta('user_email'), $themify->avatar_size ); ?></span>
						<span class="post-author"><?php echo themify_get_author_link(); ?></span>
					<?php endif; // post author ?>

					<span class="post-meta-inline">
						<?php if ( $themify->hide_date != 'yes' ) : ?>
							<time datetime="<?php the_time('o-m-d') ?>" class="post-date entry-date updated"><?php the_time( apply_filters( 'themify_loop_date', get_option( 'date_format' ) ) ) ?></time>
						<?php endif; // post date ?>

						<?php if ( $themify->hide_meta != 'yes' && $themify->hide_meta_tag != 'yes' ) : ?>
							<?php the_terms( get_the_id(), 'post_tag', ' <span class="post-tag">', ', ', '</span>' ); ?>
						<?php endif; ?>

						<?php if ( ! themify_get( 'setting-comments_posts' ) && comments_open() && $themify->hide_meta != 'yes' &&  $themify->hide_meta_comment != 'yes' ) : ?>
							<span class="post-comment"><?php comments_popup_link( '0', '1', '%' ); ?></span>
						<?php endif; // meta comments ?>
					</span>

				</p>
				<!-- /post-meta -->
			<?php endif; // end meta or date  ?>

		<?php endif; // elements not in single view ?>

		<?php if ( 'excerpt' == $themify->display_content && ! is_attachment() ) : ?>

			<?php the_excerpt(); ?>

			<?php if( themify_check('setting-excerpt_more') ) : ?>
				<p><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute('echo=0'); ?>" class="more-link"><?php echo themify_check('setting-default_more_text')? themify_get('setting-default_more_text') : __('More &rarr;', 'themify') ?></a><p>
			<?php endif; ?>

		<?php elseif($themify->display_content == 'none'): ?>

		<?php else: ?>

			<div class="entry-content">
				<?php the_content(themify_check('setting-default_more_text')? themify_get('setting-default_more_text') : __('More &rarr;', 'themify')); ?>
			</div>

		<?php endif; //display content ?>

		<?php edit_post_link(__('Edit', 'themify'), '<span class="edit-button">[', ']</span>'); ?>

	</div>
	<!-- /.post-content -->
	<?php themify_post_end(); // hook ?>

</article>
<!-- /.post -->
<?php themify_post_after(); // hook ?>
