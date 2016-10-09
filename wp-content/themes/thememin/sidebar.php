		<?php themify_sidebar_before(); //hook ?>
        <div id="sidebar" itemscope="itemscope" itemtype="https://schema.org/WPSidebar">
        	<?php themify_sidebar_start(); //hook ?>
			<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('sidebar-main') ); ?>

			<div class="clearfix">
				<div class="secondary">
				<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('sidebar-main-2a') ); ?>
				</div>
				<div class="secondary last">
				<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('sidebar-main-2b') ); ?>
				</div>
			</div>
			<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('sidebar-3') ); ?>

			<?php themify_sidebar_end(); //hook ?>
		</div>
		<!--/sidebar -->
        <?php themify_sidebar_after(); //hook ?>
