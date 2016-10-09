<?php
/**
 * Template for generic post display.
 * @package themify
 * @since 1.0.0
 */
?>

<?php
/** Themify Default Variables
 *  @var object */
global $themify;
?>

<?php
// Enable more link
if ( ! is_single() ) {
	global $more;
	$more = 0;
}
// Save post format of current entry
$post_format = get_post_format();
?>

<?php themify_post_before(); // hook ?>
<article id="post-<?php the_id(); ?>" <?php post_class( 'post clearfix' ); ?>>
	<?php themify_post_start(); // hook ?>

	<div class="post-media">

		<?php
		if ( false == $post_format ) {
			get_template_part( 'includes/loop', 'image' );
		} else {
			switch ( $post_format ) {
				case 'image':
				case 'video':
				case 'audio':
				case 'gallery':
					get_template_part( 'includes/loop', $post_format );
					break;
			}
		}
		?>

		<div class="post-meta-bottom entry-meta">
			<?php if( $themify->hide_meta != 'yes' || $themify->hide_date != 'yes' ) : ?>
				<div class="post-author">
					<?php if( $themify->hide_meta != 'yes' && $themify->hide_meta_author != 'yes' ) : ?>
						<span class="author-avatar">
							<?php echo get_avatar( get_the_author_meta('ID'), 46 ); ?>
						</span>
						<?php echo themify_get_author_link() ?>

					<?php endif; ?>
				</div>

				<?php if($themify->hide_date != 'yes'): ?>
					<time datetime="<?php the_time('o-m-d') ?>" class="post-date entry-date updated"><?php echo get_the_date( apply_filters( 'themify_loop_date', '' ) ) ?></time>
				<?php endif; //post date ?>

			<?php endif; ?>
		</div>
		<!-- /.post-meta-bottom -->

	</div>
	<!-- /.post-media -->

	<div class="post-inner">

		<?php if ( $themify->hide_meta != 'yes' ): ?>
			<div class="post-meta-top entry-meta">

				<span class="post-icon"></span>

				<?php if ( $themify->hide_meta_category != 'yes' ): ?>
					<?php the_terms( get_the_id(), 'category', ' <span class="post-category">', ', ', '</span>' ); ?>
				<?php endif; // meta category ?>

				<?php if ( $themify->hide_meta_tag != 'yes' ): ?>
					<?php the_terms( get_the_id(), 'post_tag', ' <span class="post-tag">', ', ', '</span>' ); ?>
				<?php endif; // meta tag ?>

				<?php if ( !themify_get('setting-comments_posts') && comments_open() && $themify->hide_meta_comment != 'yes' ) : ?>
					<span class="post-comment"><?php comments_popup_link( __( '0', 'themify' ), __( '1', 'themify' ), __( '%', 'themify' ) ); ?></span>
				<?php endif; ?>

			</div>
		<?php endif; //post meta ?>

		<?php if ( $themify->hide_title != 'yes' ): ?>
			<?php themify_before_post_title(); // Hook ?>

			<h2 class="post-title entry-title">
				<?php if ( $themify->unlink_title == 'yes' ): ?>
					<?php the_title(); ?>
				<?php else: ?>
					<a href="<?php echo themify_get_featured_image_link(); ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a>
				<?php endif; //unlink post title ?>
			</h2>

			<?php themify_after_post_title(); // Hook ?>
		<?php endif; //post title ?>

		<div class="post-content">

			<?php
			switch ( $post_format ) {
				case 'quote':
					get_template_part( 'includes/loop', $post_format );
					break;
				default:
					get_template_part( 'includes/loop', 'default' );
					break;
			}
			?>

			<?php edit_post_link(__('Edit', 'themify'), '<span class="edit-button">[', ']</span>'); ?>
		</div>
		<!-- /.post-content -->



	</div>
	<!-- /.post-inner -->
	<?php themify_post_end(); // hook ?>

</article>
<!-- /.post -->
<?php themify_post_after(); // hook ?>
