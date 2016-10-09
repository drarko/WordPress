<?php
/**
 * Template for Main Sidebar
 * @package themify
 * @since 1.0.0
 */
?>

<?php themify_sidebar_before(); // hook ?>

<aside id="sidebar" itemscope="itemscope" itemtype="https://schema.org/WPSidebar">
	<div class="sidebar-inner">
		<?php themify_sidebar_start(); // hook ?>
	    
		<?php dynamic_sidebar( 'sidebar-main' ); ?>
	    
		<?php themify_sidebar_end(); // hook ?>
	</div>
</aside>
<!-- /#sidebar -->

<?php themify_sidebar_after(); // hook ?>