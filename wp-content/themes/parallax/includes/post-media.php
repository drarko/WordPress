<?php
/** Themify Default Variables
 *  @var object */
global $themify; ?>

<?php if ( themify_has_post_video() ) : ?>
	<?php themify_before_post_image(); // hook ?>
	<div class="post-image">

		<?php $themify->theme->show_video(); ?>

	</div>
	<?php themify_after_post_image(); // hook ?>

<?php elseif ( $post_image = themify_get_image( $themify->auto_featured_image . $themify->image_setting . 'w=' . $themify->width . '&h=' . $themify->height  ) ) : ?>

	<?php if ( $themify->hide_image != 'yes' ) : ?>
		<?php themify_before_post_image(); // hook ?>
		<figure class="post-image <?php echo $themify->image_align; ?>">

			<?php $themify->theme->show_image( $post_image ); ?>

		</figure>
		<?php themify_after_post_image(); // hook ?>
	<?php endif; // hide post image ?>

<?php endif; // video url ?>
