<?php
/**
 * Partial template for home widgets
 */
$homepage_widgets = (themify_get('setting-homepage_widgets') == '') ? 'homewidget-3col' : themify_get('setting-homepage_widgets');

if('' != $homepage_widgets){ ?>
	
	<?php
	$columns = array('homewidget-4col' => array('col4-1','col4-1','col4-1','col4-1'),
					 'homewidget-3col' => array('col3-1','col3-1','col3-1'),
					 'homewidget-2col' => array('col4-2','col4-2'),
					 'homewidget-1col' => array('') );
	$x = 0;
	?>
	<div class="divider clearfix">
		<?php foreach($columns[$homepage_widgets] as $col): ?>
			<?php 
				$x++;
				if($x == 1){ 
					$class = 'first'; 
				} else {
					$class = '';
				}
			?>
			<div class="<?php echo $col;?> <?php echo $class; ?>">
				<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('home-widget-'.$x) ) ?>
			</div>
		<?php endforeach; ?>
	</div>
	

<?php } ?>