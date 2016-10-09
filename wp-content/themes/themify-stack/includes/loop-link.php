<?php
/**
 * Template for link format display.
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
