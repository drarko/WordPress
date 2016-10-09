<?php
/** Themify Default Variables
 *  @var object */
global $themify;

	if($themify->post_layout == 'list-post' || $themify->post_layout == '' || is_single()) {
		get_template_part('includes/default-loop');
	} else {
		get_template_part('includes/gridview-loop');
	}
?>