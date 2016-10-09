<?php
/** Themify Default Variables
 *  @var object */
global $themify; ?>

<?php themify_post_before(); //hook ?>
<div class="post menu-post <?php echo themify_get('icon'); ?>">
	<?php themify_post_start(); //hook ?>

	<?php if($themify->zoom_image): ?>
		<a href="<?php echo themify_get('feature_image'); ?>" class="lightbox menu-image"><span class="zoom"></span><?php themify_image('w='.$themify->width.'&h='.$themify->height); ?></a>
	<?php else: ?>
		<div class="menu-image"><?php themify_image('w='.$themify->width.'&h='.$themify->height); ?></div>
	<?php endif; ?>

	<div class="menu-content">
		<h4 class="menu-post-title entry-title">
			<?php if($themify->link_item){
				echo "<a href='".get_permalink()."'>".get_the_title()."</a>";
			} else {
				the_title();
			} ?>
			<small><?php echo get_post_meta($post->ID, 'price', true); ?></small>
		</h4>
		<div class="menu-icon clearfix">
			<span class="<?php echo themify_get('icon'); ?>"></span>
			<span class="<?php echo themify_get('icon2'); ?>"></span>
			<span class="<?php echo themify_get('icon3'); ?>"></span>
		</div>
		<?php the_content(); ?>
		<?php edit_post_link(__('Edit','themify'), '[', ']'); ?>
	</div>

    <?php themify_post_end(); //hook ?>
</div>
<!--/post -->
<?php themify_post_after(); //hook ?>
