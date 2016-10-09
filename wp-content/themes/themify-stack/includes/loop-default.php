<?php
/**
 * Template for default format display and others without a specialized loop-FORMAT.php.
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
<div class="entry-content">

	<?php if ( 'excerpt' == $themify->display_content && ! is_attachment() ) : ?>

		<?php the_excerpt(); ?>

		<?php if ( themify_check( 'setting-excerpt_more' ) ) : ?>
			<p>
				<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute( 'echo=0' ); ?>" class="more-link"><?php echo themify_check( 'setting-default_more_text' ) ? themify_get( 'setting-default_more_text' ) : __( 'More &rarr;', 'themify' ) ?></a>
			</p>
		<?php endif; ?>

	<?php elseif ( $themify->display_content == 'none' ): ?>

	<?php else: ?>

		<?php the_content( themify_check( 'setting-default_more_text' ) ? themify_get( 'setting-default_more_text' ) : __( 'More &rarr;', 'themify' ) ); ?>

	<?php endif; //display content ?>

</div>
<!-- /.entry-content -->