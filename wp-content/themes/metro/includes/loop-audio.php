<?php
/** Themify Default Variables
 *  @var object */
	global $themify; ?>

<?php themify_image("field_name=post_image, image, wp_thumb&w=100&h=100&before=<p class='audio-image'><a href='".get_permalink()."'>&after=</a></p>" ); ?>

<?php
	$src = themify_get('audio_url');
	if( trim( $src ) != '' ) {
?>
<!-- audio-player -->
<div class="audio-player">
<?php
	if(strpos(strtolower($src),'.wav')) $format = 'wav';
	if(strpos(strtolower($src),'.m4a')) $format = 'm4a';
	if(strpos(strtolower($src),'.ogg')) $format = 'ogg';
	if(strpos(strtolower($src),'.oga')) $format = 'oga';
	if(strpos(strtolower($src),'.mp3')) $format = 'mp3';

	if('ogg' == $format || 'oga' == $format)
		$html5incompl = false;
	else
		$html5incompl = true;

	$html5incompl_datas = $html5incompl? 'data-html5incompl="yes" data-post_id="f-'.$post->ID.'" data-src="'.$src.'"' : 'data-html5incompl="no"';

	$fallbackpl = '<a id="f-'.$post->ID.'" class="f-embed-audio" '.$html5incompl_datas.' href="'.$src.'" title="' . __('Click to open', 'themify') . '" style="display:none;">' . __('Audio MP3', 'themify') . '</a>';

	$output = '<div class="audio_wrap html5audio">';
	if ($html5incompl) $output .= '<div class="hidden_video_div" style="display: none;">'.$fallbackpl.'</div>';
	$output .= '<audio controls id="' . $post->ID . '" class="html5audio">';

	if ($format == 'wav') $output .= '<source src="'.$src.'" type="audio/wav" />';
	if ($format == 'm4a') $output .= '<source src="'.$src.'" type="audio/mp4" />';
	if ($format == 'oga') $output .= '<source src="'.$src.'" type="audio/ogg" />';
	if ($format == 'ogg') $output .= '<source src="'.$src.'" type="audio/ogg" />';
	if ($format == 'mp3') $output .= '<source src="'.$src.'" type="audio/mpeg" />';

	$output .= $fallbackpl . '</audio></div>';

	echo $output;
?>
</div>
<!-- /audio-player -->
<?php } // endif ?>

<div class="post-content">

	<div class="entry-content">

	<?php if ( 'excerpt' == $themify->display_content && ! is_attachment() ) : ?>

		<?php the_excerpt(); ?>

			<?php if( themify_check('setting-excerpt_more') ) : ?>
				<p><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute('echo=0'); ?>" class="more-link"><?php echo themify_check('setting-default_more_text')? themify_get('setting-default_more_text') : __('More &rarr;', 'themify') ?></a></p>
			<?php endif; ?>

	<?php elseif ( 'none' == $themify->display_content && ! is_attachment() ) : ?>

	<?php else: ?>

		<?php the_content(themify_check('setting-default_more_text')? themify_get('setting-default_more_text') : __('More &rarr;', 'themify')); ?>

	<?php endif; //display content ?>

	</div><!-- /.entry-content -->

	<?php if($themify->hide_date != "yes" || $themify->hide_meta != 'yes'): ?>
		<p class="post-meta entry-meta">

			<?php if($themify->hide_meta != 'yes'): ?>
				
				<span class="post-author"><?php echo themify_get_author_link() ?></span> <span class="separator">|</span>
			<?php endif; ?>

			<?php if($themify->hide_date != "yes"): ?>
				
				<time datetime="<?php the_time('o-m-d') ?>" class="post-date entry-date updated"><?php echo get_the_date( apply_filters( 'themify_loop_date', '' ) ) ?></time> <span class="separator">|</span>
			<?php endif; ?>
			<!-- /post-date -->

			<?php if($themify->hide_meta != 'yes'): ?>

				<?php echo ' '. get_the_term_list( get_the_id(), 'category', '<span class="post-category">', ', ', ' <span class="separator">|</span></span><!-- /post-category -->' ); ?>

				<?php the_tags(' <span class="post-tag">', ', ', ' <span class="separator">|</span></span><!-- /post-tag -->'); ?>

				<?php  if( !themify_get('setting-comments_posts') && comments_open() ) : ?>
						<span class="post-comment"><?php comments_popup_link( __( '0 Comments', 'themify' ), __( '1 Comment', 'themify' ), __( '% Comments', 'themify' ) ); ?>
						<!-- /post-comments -->
				<?php endif; ?>

			<?php endif; //post meta ?>
		</p>
	 <?php endif; //post data and meta ?>

	<?php edit_post_link(__('Edit', 'themify'), '<span class="edit-button">[', ']</span>'); ?>

</div>
<!-- /post-content -->
