<?php
/**
 * Partial template for pagination
 */

/** Themify Default Variables
 *  @var object */
global $themify; ?>

<?php if( 'numbered' == themify_get( 'setting-entries_nav' ) || '' == themify_get( 'setting-entries_nav' ) && $themify->post_layout != 'slideshow' ) { ?>
	<?php themify_pagenav(); ?> 
<?php } else { ?>
	<div class="post-nav post-nav-<?php echo $themify->post_layout; ?>">
		<span class="prev prev-<?php echo $themify->post_layout; ?>"><?php next_posts_link(__('&laquo; Older Entries', 'themify')) ?></span>
		<span class="next next-<?php echo $themify->post_layout; ?>"><?php previous_posts_link(__('Newer Entries &raquo;', 'themify')) ?></span>
	</div>
<?php } ?>