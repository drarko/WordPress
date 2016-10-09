<?php
/**
 * Template for video format display.
 *
 * @package themify
 * @since 1.0.0
 */
?>

<?php
/**
 * Themify Default Variables
 *
 * @var object
 */
global $themify;
?>

<?php if ( $themify->hide_image != 'yes' ) : ?>
	<?php themify_before_post_image(); // Hook ?>

	<?php if ( themify_get( 'video_url' ) != '' ) : ?>

		<?php
		global $wp_embed;
		echo $wp_embed->run_shortcode( '[embed]' . esc_url( themify_get( 'video_url' ) ) . '[/embed]' );
		?>

	<?php elseif ( $post_image = themify_get_image( $themify->auto_featured_image . $themify->image_setting . "w=" . $themify->width . "&h=" . $themify->height ) ) : ?>

		<figure class="post-image <?php echo esc_attr( $themify->image_align ); ?>">
			<?php if ( 'yes' == $themify->unlink_image ): ?>
				<?php echo wp_kses_post( $post_image ); ?>
			<?php else: ?>
				<a href="<?php echo themify_get_featured_image_link(); ?>"><?php echo wp_kses_post( $post_image ); ?><?php themify_zoom_icon(); ?></a>
			<?php endif; // unlink image ?>
		</figure>
		<!-- /.post-image -->

	<?php endif; // video else image ?>

	<?php themify_after_post_image(); // Hook ?>
<?php endif; // hide image ?>
