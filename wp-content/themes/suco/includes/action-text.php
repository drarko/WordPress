<?php
/**
 * User-set action text. Can be filtered using 'themify_action_text'.
 * @var String|null
 */
$action_text = apply_filters('themify_action_text', function_exists( 'icl_t' )? icl_t('Themify', 'setting-action_text', themify_get('setting-action_text')) : themify_get('setting-action_text'));

if( '' != $action_text ) { ?>	

	<div class="pagewidth">
		 <div class="action-text">
			  <?php echo do_shortcode($action_text); ?>
		 </div>
		 <!-- /.action-message -->
	</div>

<?php } ?>