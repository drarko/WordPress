<form method="get" id="searchform" action="<?php echo home_url(); ?>/">
	<i class="fa fa-search icon-search"></i>
	<input type="text" name="s" id="s" title="<?php _e( 'Search', 'themify' ); ?>" value="<?php echo get_search_query(); ?>" />
</form>
