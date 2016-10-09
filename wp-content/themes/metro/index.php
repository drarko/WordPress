<?php get_header(); ?>

<?php
/** Themify Default Variables
 *  @var object */
global $themify; ?>
		
<!-- layout -->
<div id="layout" class="pagewidth clearfix">

	<?php themify_content_before(); //hook ?>
	<!-- content -->
	<div id="content">
    	<?php themify_content_start(); //hook ?>
		
		<?php if(is_front_page() && !is_paged()){ get_template_part( 'includes/welcome-message'); } ?>

		<?php 
		/////////////////////////////////////////////
		// Author Page	 							
		/////////////////////////////////////////////
		?>
		<?php if(is_author()) : ?>
			<?php
			$curauth = (isset($_GET['author_name'])) ? get_user_by('slug', $author_name) : get_userdata(intval($author));
			$author_url = $curauth->user_url;
			?>
			<div class="author-bio clearfix">
				<p class="author-avatar"><?php echo get_avatar( $curauth->user_email, $size = '48' ); ?></p>
				<h2 class="author-name"><?php _e('About ','themify'); ?> <span><?php echo $curauth->display_name; ?></span></h2>
				<?php if($author_url != ''): ?><p class="author-url"><a href="<?php echo $author_url; ?>"><?php echo $author_url; ?></a></p><?php endif; //author url ?>
				<div class="author-description">
					<?php echo $curauth->user_description; ?>
				</div>
				<!-- /.author-description -->
			</div>
			<!-- /.author bio -->
			
			<h2 class="author-posts-by"><?php printf(__('Posts by %s:','themify'), $curauth->display_name); ?></h2>
		<?php endif; ?>

		<?php 
		/////////////////////////////////////////////
		// Search Title	 							
		/////////////////////////////////////////////
		?>
		<?php if(is_search()): ?>
			<h1 class="page-title"><?php _e('Search Results for:','themify'); ?> <em><?php echo get_search_query(); ?></em></h1>
		<?php endif; ?>
	
		<?php
		/////////////////////////////////////////////
		// Date Archive Title
		/////////////////////////////////////////////
		?>
		<?php if ( is_day() ) : ?>
			<h1 class="page-title"><?php printf( __( 'Daily Archives: <span>%s</span>', 'themify' ), get_the_date() ); ?></h1>
		<?php elseif ( is_month() ) : ?>
			<h1 class="page-title"><?php printf( __( 'Monthly Archives: <span>%s</span>', 'themify' ), get_the_date( _x( 'F Y', 'monthly archives date format', 'themify' ) ) ); ?></h1>
		<?php elseif ( is_year() ) : ?>
			<h1 class="page-title"><?php printf( __( 'Yearly Archives: <span>%s</span>', 'themify' ), get_the_date( _x( 'Y', 'yearly archives date format', 'themify' ) ) ); ?></h1>
		<?php endif; ?>

		<?php 
		/////////////////////////////////////////////
		// Category Title	 							
		/////////////////////////////////////////////
		?>
		<?php if(is_category() || is_tag() || is_tax() ): ?>
				<h1 class="page-title"><?php single_cat_title(); ?></h1>
				<?php echo $themify->get_category_description(); ?>
		<?php endif; ?>

		<?php 
		/////////////////////////////////////////////
		// Default query categories	 							
		/////////////////////////////////////////////
		?>
		<?php if( !is_search() ): ?>
			<?php
			global $query_string;
			query_posts( apply_filters( 'themify_query_posts_args', $query_string.'&order='.$themify->order.'&orderby='.$themify->orderby ) );
			?>
		<?php endif; ?>

		<?php
		// If it's a taxonomy, set the related post type
		if( is_tax() ){
			$set_post_type = str_replace('-category', '', $wp_query->query_vars['taxonomy']);
			if( in_array($wp_query->query_vars['taxonomy'], get_object_taxonomies($set_post_type)) ){
				query_posts('post_type='.$set_post_type);
			}
		}
		?>

		<?php 
		/////////////////////////////////////////////
		// Loop	 							
		/////////////////////////////////////////////
		?>
		<?php if (have_posts()) : ?>
		
			<!-- loops-wrapper -->
			<div id="loops-wrapper" class="loops-wrapper <?php echo $themify->layout . ' ' . $themify->post_layout; ?>">

				<?php while (have_posts()) : the_post(); ?>
		
					<?php if(is_search()): ?>
						<?php get_template_part( 'includes/loop' , 'search'); ?>
					<?php else: ?>
						<?php get_template_part( 'includes/loop' , 'index'); ?>
					<?php endif; ?>
		
				<?php endwhile; ?>
							
			</div>
			<!-- /loops-wrapper -->

			<?php get_template_part( 'includes/pagination'); ?>
		
		<?php 
		/////////////////////////////////////////////
		// Error - No Page Found	 							
		/////////////////////////////////////////////
		?>
	
		<?php else : ?>

			<p><?php _e( 'Sorry, nothing found.', 'themify' ); ?></p>
	
		<?php endif; ?>			
	
    	<?php themify_content_end(); //hook ?>
	</div>
	<!-- /#content -->
    <?php themify_content_after() //hook; ?>

	<?php 
	/////////////////////////////////////////////
	// Sidebar							
	/////////////////////////////////////////////
	if ($themify->layout != "sidebar-none"): get_sidebar(); endif; ?>

</div>
<!-- /#layout -->

<?php get_footer(); ?>