<?php get_header(); ?>
	
	<?php if (is_home() && !is_paged()) { get_template_part('includes/slider'); } ?>
    
	<?php get_template_part( 'includes/welcome-message'); ?>

	<?php get_template_part( 'includes/home-widgets'); ?>

<?php get_footer(); ?>