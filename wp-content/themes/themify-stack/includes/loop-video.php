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

<?php if ( themify_get( 'video_url' ) != '' ) : ?>

	<div class="post-video">
		<?php
		global $wp_embed;
		echo $wp_embed->run_shortcode( '[embed]' . esc_url( themify_get( 'video_url' ) ) . '[/embed]' );
		?>
	</div>
	<!-- /.post-video -->

<?php endif; // video_url ?>
