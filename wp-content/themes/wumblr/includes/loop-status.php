<?php
/** Themify Default Variables
 @var object */
	global $themify; ?>

<div class="status-author-avatar"><?php echo get_avatar( get_the_author_meta('email'), '36' ); ?></div>

<div class="post-content">
				
	<div class="entry-content">

	<?php if ( 'excerpt' == $themify->display_content && ! is_attachment() ) : ?>

		<?php the_excerpt(); ?>
		
		<?php if( themify_check('setting-excerpt_more') ) : ?>
			<p><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute('echo=0'); ?>" class="more-link"><?php echo themify_check('setting-default_more_text')? themify_get('setting-default_more_text') : __('More &rarr;', 'themify') ?></a></p>
		<?php endif; ?>

	<?php elseif ( 'none' == $themify->display_content && ! is_attachment() ) : ?>

	<?php else: ?>
	
		<?php the_content(themify_check('setting-default_more_text')? themify_get('setting-default_more_text') : __('More &rarr;', 'themify')); ?>
	
	<?php endif; //display content ?>

	</div><!-- /.entry-content -->
	
	<?php edit_post_link(__('Edit', 'themify'), '<span class="edit-button">[', ']</span>'); ?>
</div><!--/post-content -->
