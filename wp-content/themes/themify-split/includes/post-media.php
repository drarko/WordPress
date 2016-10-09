<?php
/**
 * Post Media Template.
 * If there's a Video URL in Themify Custom Panel it will show it, otherwise shows the featured image.
 * @package themify
 * @since 1.0.0
 */

/** Themify Default Variables
 *  @var object */
global $themify; ?>

<?php if ( $themify->hide_image != 'yes' ) : ?>
	<?php themify_before_post_image(); // Hook ?>

	<?php
	$video_file = themify_get( 'video_url' );

	if ( ! empty( $video_file ) ) : ?>

		<?php if ( $video_file && 'mp4' !== substr( $video_file, -3, 3 ) ) :
			global $wp_embed; ?>
			<?php echo $wp_embed->run_shortcode('[embed]' . $video_file . '[/embed]'); ?>
		<?php elseif ( themify_is_touch() || ( ! themify_is_touch() && ! is_single() ) ) : ?>
			<div class="post-video">
				<?php echo do_shortcode( '[video src="' . $video_file . '"]' ); ?>
			</div>
		<?php endif; ?>

	<?php elseif ( ! is_single() && $post_image = themify_get_image($themify->auto_featured_image . $themify->image_setting . "w=".$themify->width."&h=".$themify->height) ) : ?>

		<figure class="post-image">

			<?php if( 'yes' == $themify->unlink_image): ?>
				<?php echo wp_kses_post( $post_image ); ?>
			<?php else: ?>
				<a href="<?php echo themify_get_featured_image_link(); ?>"><?php echo wp_kses_post( $post_image ); ?><?php themify_zoom_icon(); ?></a>
			<?php endif; // unlink image ?>

		</figure>

	<?php endif; // video else image ?>

	<?php themify_after_post_image(); // Hook ?>
<?php endif; // hide image ?>
