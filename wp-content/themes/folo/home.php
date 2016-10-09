<?php get_header(); ?>

<div id="upperwrap">
	<div id="upperwrap-inner">
		<div class="pagewidth">
		
			<?php if (is_home() && !is_paged()) { get_template_part( 'includes/slider'); } ?>

		</div>
		<!-- /.pagewidth -->
	</div>
	<!-- /#upperwrap-inner -->
</div>
<!-- /#upperwrap -->

<div id="layout" class="pagewidth clearfix">
				
	<?php get_template_part( 'includes/home-highlights'); ?>

	<?php get_template_part( 'includes/welcome-message'); ?>

	<?php get_template_part( 'includes/home-widgets'); ?>

		
</div>
<!-- /#layout -->

<?php get_footer(); ?>