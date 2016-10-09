<?php
/**
 * Template for single post view
 * @package themify
 * @since 1.0.0
 */
?>

<?php get_header(); ?>

<?php 
/** Themify Default Variables
 *  @var object */
global $themify;
?>

<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>

<!-- layout-container -->
<div id="layout" class="clearfix">

	<?php get_template_part( 'includes/content-single' ); ?>

<?php endwhile; ?>

</div>
<!-- /layout-container -->
	
<?php get_footer(); ?>