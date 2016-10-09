<?php
/** Themify Default Variables
 * @var object */
global $themify; ?>

<?php themify_image("field_name=post_image, image, wp_thumb&w=100&h=100&before=<p class='audio-image'><a href='".get_permalink()."'>&after=</a></p>" ); ?>

<?php
	$src = themify_get("audio_url");
	if ( trim( $src ) != '' ) {
?>
<!-- audio-player -->
<div class="audio-player">
<?php
	$fallbackpl = '<a href="'.$src.'" title="' . __('Click to open', 'themify') . '" id="f-'.$post->ID.'" style="display:none;">' . __('Audio MP3', 'themify') . '</a><script type="text/javascript">AudioPlayer.embed("f-'.$post->ID.'", {soundFile: "'.$src.'"});</script>';
	
	if(strpos(strtolower($src),'.wav')) $format = 'wav';
	if(strpos(strtolower($src),'.m4a')) $format = 'm4a';
	if(strpos(strtolower($src),'.ogg')) $format = 'ogg';
	if(strpos(strtolower($src),'.oga')) $format = 'oga';
	if(strpos(strtolower($src),'.mp3')) $format = 'mp3';
	
	if(strpos($format, 'og')) $html5incompl = false; else $html5incompl = true;
	
	$output = '<div class="audio_wrap html5audio">';
	if ($html5incompl) $output .= '<div style="display:none;">'.$fallbackpl.'</div>';
	$output .= '<audio controls id="' . $post->ID . '" class="html5audio">';
	
	if ($format == 'wav') $output .= '<source src="'.$src.'" type="audio/wav" />';
	if ($format == 'm4a') $output .= '<source src="'.$src.'" type="audio/mp4" />';
	if ($format == 'oga') $output .= '<source src="'.$src.'" type="audio/ogg" />';
	if ($format == 'ogg') $output .= '<source src="'.$src.'" type="audio/ogg" />';
	if ($format == 'mp3') $output .= '<source src="'.$src.'" type="audio/mpeg" />';
	
	$output .= $fallbackpl . '</audio></div>';
	
	if ($html5incompl) $output .= '<script type="text/javascript">if (jQuery.browser.mozilla) {tempaud=document.getElementsByTagName("audio")[0]; jQuery(tempaud).remove(); jQuery("div.audio_wrap div").show()} else jQuery("div.audio_wrap div *").remove();</script>';
	
	echo $output;
?>
</div>
<!-- /audio-player -->
<?php } // endif ?>

<!-- post-content -->
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

</div>
<!-- /post-content -->