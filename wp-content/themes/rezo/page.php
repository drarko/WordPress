<?php
/** Themify Default Variables
 *  @var object */
global $themify;
	
	if($themify->menu_category != '' || $themify->menu_category_list != '') {
		get_template_part('template-menu');
	} else {
		get_template_part('template-page');
	}
?>