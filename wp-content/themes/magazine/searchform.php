<?php
/**
 * Template for search form.
 * @package themify
 * @since 1.0.0
 */
?>
<form method="get" id="searchform" action="<?php echo home_url(); ?>/">
	<i class="fa fa-search icon-search"></i>
	<input type="text" name="s" id="s" placeholder="<?php _e('Search', 'themify'); ?>" />

</form>