<?php
/**
 * Post Navigation Template
 * @package themify
 * @since 1.0.0
 */

$post_type = 'portfolio';

if ( ! themify_check( "setting-{$post_type}_nav_disable" ) ) :

	$in_same_cat = themify_check( "setting-{$post_type}_nav_same_cat" )? true: false;
	$this_taxonomy = 'portfolio-category';
	$previous = get_previous_post_link( '<span class="prev">%link</span>', '<i class="icon-arrow-prev"></i>', $in_same_cat, '', $this_taxonomy );
	$next = get_next_post_link( '<span class="next">%link</span>', '<i class="icon-arrow-next"></i>', $in_same_cat, '', $this_taxonomy );

	if ( ( ! empty( $previous ) || ! empty( $next ) ) || ( isset($_GET['porto_expand']) && $_GET['porto_expand'] == 1 ) ) : ?>

		<div class="post-nav clearfix">
			<?php if ( ! empty( $previous ) || ! empty( $next ) ) : ?>
				<?php echo $previous; ?>
				<?php echo $next; ?>
			<?php endif;?>
			
			<?php if( isset($_GET['porto_expand']) && $_GET['porto_expand'] == 1 ): ?>
				<span class="right">
					<a href="#" class="close-portfolio"><i class="icon-close"></i></a>
				</span>
			<?php endif; ?>
		</div>
		<!-- /.post-nav -->

	<?php endif; // empty previous or next

endif; // check setting nav disable