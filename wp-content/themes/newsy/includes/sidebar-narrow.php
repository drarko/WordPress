<?php themify_sidebar_alt_before(); //hook ?>
<!-- sidebar-narrow -->
<div id="sidebar-narrow">
	<?php themify_sidebar_alt_start(); //hook ?>

	<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('sidebar-alt') ); ?>

	<?php themify_sidebar_alt_end(); //hook ?>
</div>
<!--/sidebar-narrow -->
<?php themify_sidebar_alt_after(); //hook ?>
