<?php
/*
Template Name: Sitemap
*/
?>

<?php get_header(); ?>


<?php 
/////////////////////////////////////////////
// Check page layout option	 							
/////////////////////////////////////////////
?>

<?php $layout = (themify_get('page_layout') != "default" && themify_check('page_layout')) ? themify_get('page_layout') : themify_get('setting-default_page_layout'); /* set default layout */ if($layout == ''): $layout = 'sidebar1'; endif; ?>
				
<!-- layout-container -->
<div id="layout" class="clearfix <?php echo $layout; ?>">

	<!-- content -->
	<div id="content" class="clearfix">
	
		<?php if(is_search()): ?>
			<h1 class="page-title"><?php _e('Search Results for:','themify'); ?> <em><?php echo get_search_query(); ?></em></h1>
		<?php endif; ?>

		<h3><?php _e('Pages', 'themify') ?></h3>
		<ul>
			<?php wp_list_pages('depth=0&sort_column=menu_order&title_li=' ); ?>
		</ul>
		<h3><?php _e('Categories', 'themify') ?></h3>
		<ul>
			<?php wp_list_categories('title_li=&hierarchical=0&show_count=1') ?>
		</ul>
		<h3><?php _e('Posts Per Category', 'themify') ?></h3>
		<?php							 
			$cats = get_categories();
			foreach ($cats as $cat) {
			query_posts('cat='.$cat->cat_ID);							
		?>
		<h4><?php echo $cat->cat_name; ?></h4>
		<ul>
			<?php while (have_posts()) : the_post(); ?>
			<li><a href="<?php the_permalink() ?>"><?php the_title(); ?></a></li>
			<?php endwhile;  ?>
		</ul>
		<?php } ?>

	</div>
	<!--/content -->
	
	<?php 
	/////////////////////////////////////////////
	// Sidebar							
	/////////////////////////////////////////////
	?>
	
	<?php if ($layout != "sidebar-none"): get_sidebar(); endif; ?>
	
</div>
<!-- layout-container -->

<?php get_footer(); ?>