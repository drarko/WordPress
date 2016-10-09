<?php
/**
 * Template for audio format display.
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

<?php get_template_part( 'includes/loop', 'image' ); ?>

<?php if ( $audio_src = themify_get( 'audio_url' ) ) : ?>

	<div class="audio-player-wrapper">
		<?php echo do_shortcode( '[audio src="' . esc_url( $audio_src ) . '" preload="metadata"]' ); ?>
	</div>
	<!-- /.audio-player-wrapper -->

<?php endif; ?>