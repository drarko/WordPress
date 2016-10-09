<?php
/**
 * Template for single portfolio view
 * @package themify
 * @since 1.0.0
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>

	<head>
		<meta charset="<?php bloginfo('charset'); ?>">

		<title><?php echo is_home() || is_front_page()? get_bloginfo('name') : wp_title('');?></title>

		<!-- wp_header -->
		<?php wp_head(); ?>

		<base target="_parent" />
	</head>

	<body class="single-portfolio-expanded single-portfolio">

	<?php
	/** Themify Default Variables
	 *  @var object */
	global $themify; ?>

	<?php if( have_posts() ) while ( have_posts() ) : the_post(); ?>

		<!-- layout-container -->
		<div class="pagewidth clearfix">

			<?php themify_content_before(); // hook ?>

			<?php themify_content_start(); // hook ?>

			<?php get_template_part( 'includes/loop-portfolio', 'single'); ?>

			<?php wp_link_pages(array('before' => '<p class="post-pagination"><strong>' . __('Pages:', 'themify') . ' </strong>', 'after' => '</p>', 'next_or_number' => 'number')); ?>

			<?php get_template_part( 'includes/author-box', 'single'); ?>

			<?php get_template_part( 'includes/post-nav', 'portfolio' ); ?>

			<?php themify_content_end(); // hook ?>

			<?php themify_content_after(); // hook ?>

		</div>
		<!-- /layout-container -->

	<?php endwhile; ?>
	
	<?php wp_footer(); ?>
	
	</body>

</html>