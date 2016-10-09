<?php
/**
 * Partial template for pagination
 */

global $hide_page_nav; ?>
<?php if(!$hide_page_nav || $hide_page_nav == '' || !isset($hide_page_nav)){ ?>
   
	<?php if(function_exists('themify_pagenav')){ ?>
	   <?php themify_pagenav(); ?> 
	<?php } else { ?>
		<div class="post-nav">
			<span class="prev"><?php next_posts_link(__('&laquo; Older Entries', 'themify')) ?></span>
			<span class="next"><?php previous_posts_link(__('Newer Entries &raquo;', 'themify')) ?></span>
		</div>
	<?php } ?>

<?php } ?>