<?php
/** Themify Default Variables
 *  @var object */
global $themify; ?>

<?php if(themify_check('gallery_shortcode')) { ?>
	<?php themify_before_post_image(); // hook ?>
	<div class="post-media">
		<?php $themify->theme->show_gallery(); ?>
	</div>
	<?php themify_after_post_image(); // hook ?>
<?php } elseif(themify_check('slider')) { ?>
	<div class="post-media">
		<?php $themify->theme->show_slider(); ?>
	</div>
	<?php themify_after_post_image(); // hook ?>
<?php } elseif(themify_check('map')) { ?>
	<?php themify_before_post_image(); // hook ?>
	<div class="post-media">
		<?php $themify->theme->show_map(); ?>
	</div>
	<?php themify_after_post_image(); // hook ?>
<?php } elseif( themify_check('video_url') ) { ?>
	<?php themify_before_post_image(); // hook ?>
	<div class="post-media">
		<?php $themify->theme->show_video(); ?>
	</div>
	<?php themify_after_post_image(); // hook ?>
<?php } elseif( $post_image = themify_get_image($themify->auto_featured_image . $themify->image_setting . "w=".$themify->width."&h=".$themify->height ) ){ ?>
	<?php if($themify->hide_image != 'yes') { ?>
		<?php themify_before_post_image(); // hook ?>
		<div class="post-media">
			<figure class="post-image <?php echo $themify->image_align; ?>">
				<?php $themify->theme->show_image($post_image); ?>
			</figure>
		</div>
		<?php themify_after_post_image(); // hook ?>
	<?php } // hide post image ?>
<?php } ?>
