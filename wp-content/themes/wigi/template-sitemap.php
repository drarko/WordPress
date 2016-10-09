<?php
/*
Template Name: Sitemap
*/
?>

<?php get_header(); ?>
		
<?php $layout = themify_get('setting-default_layout'); if($layout == ''): $layout = 'sidebar1'; endif;  /* get default page layout */ ?>

<!-- layout-container -->
<div id="layout" class="clearfix <?php echo $layout; ?>">	

	<!-- content -->
	<div id="content" class="clearfix">
	
		<div class="page-content clearfix">

			<h1 class="page-title"><?php the_title() ?></h1>
			
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
		<!-- /.page-content -->

	
	</div>
	<!-- /content -->

	<?php if ($layout != "sidebar-none"): get_sidebar(); endif; ?>
	
</div>
<!-- /layout-container -->
	
<?php get_footer(); ?>
