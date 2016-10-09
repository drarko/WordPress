<?php

/*
To add custom modules to the theme, create a new 'custom-modules.php' file in the theme folder.
They will be added to the theme automatically.
*/

/* 	Custom Modules
/***************************************************************************/		
		
	///////////////////////////////////////////
	// Homepage Header Slider Function
	///////////////////////////////////////////
	function themify_slider($data=array()){
		$data = themify_get_data();
		
		/** Slider values @var array */
		$slider_ops = array( __('On', 'themify') => 'on', __('Off', 'themify') => 'off' );
		/** Slider status */
		$enabled_display = '';
		$display_posts = '';
		$display_images = '';
		$display_posts_display = '';
		$display_images_display = '';
		if ( '' == themify_get( 'setting-slider_enabled' ) || 'on' == themify_get( 'setting-slider_enabled' ) ) {
			$enabled_checked = "selected='selected'";
		} else {
			$enabled_display = "style='display:none;'";	
		}
		if ( ! isset( $data['setting-slider_visible'] ) || '' == $data['setting-slider_visible'] ) {
			$data['setting-slider_visible'] = "1";	
		}
		
		if ( 'images' == themify_get( 'setting-slider_display' ) ) {
			$display_images = "checked='checked'";
			$display_posts_display = "style='display:none;'";
		} else {
			$display_posts = "checked='checked'";	
			$display_images_display = "style='display:none;'";
		}
		$title_options = array('','yes','no');
		$auto_options = array(__('Off', 'themify')=>0, __('1 Sec', 'themify')=>1000, __('2 Sec', 'themify')=>2000, __('3 Sec', 'themify')=>3000, __('4 Sec', 'themify')=>4000, __('5 Sec', 'themify')=>5000, __('6 Sec', 'themify')=>6000, __('7 Sec', 'themify')=>7000, __('8 Sec', 'themify')=>8000, __('9 Sec', 'themify')=>9000, __('10 Sec', 'themify')=>10000); 
		$scroll_options = array(1,2,3,4,5,6,7);
		$effect_options = array(__('Slide', 'themify') =>'slide', __('Fade', 'themify') =>'fade');
		$display_options = array(
			__('Excerpt', 'themify') => 'excerpt',
			__('Full Content', 'themify') => 'content',
			__('None', 'themify') => 'none'
		);
		$speed_options = array(__('Slow', 'themify') =>2000, __('Normal', 'themify') =>1000, __('Fast', 'themify') =>200);
		$image_options = array("one","two","three","four","five","six","seven","eight","nine","ten");
		
		/**
		 * HTML for settings panel
		 * @var string
		 */
		$output = '<p><span class="label">' . __('Enable Slider', 'themify') . '</span> <select name="setting-slider_enabled" class="feature_box_enabled_check">';
		/** Iterate through slider options */
		foreach ( $slider_ops as $key => $val ) {
			$output .= '<option value="'.$val.'" ' . selected( themify_get( 'setting-slider_enabled' ), $val, false ) . '>' . $key . '</option>';
		}
		$output .= '</select>' . '</p>
		
					<div class="feature_box_enabled_display" '.$enabled_display.'>
					
					<p><span class="label">' . __('Display', 'themify') . '</span> <input type="radio" class="feature-box-display" name="setting-slider_display" value="posts" '.$display_posts.' /> ' . __('Posts', 'themify') . ' <input type="radio" class="feature-box-display" name="setting-slider_display" value="images" '.$display_images.' /> ' . __('Images', 'themify') . '</p>
					
					<p class="pushlabel feature_box_posts" '.$display_posts_display.'>';
							$output .= wp_dropdown_categories(array("show_option_all"=>__('All Categories', 'themify'),"hide_empty"=>0,"echo"=>0,"name"=>"setting-slider_posts_category","selected"=> themify_get( 'setting-slider_posts_category' )));
		$output .=	'	<input type="text" name="setting-slider_posts_slides" value="' . themify_get( 'setting-slider_posts_slides' ) . '" class="width2" /> ' . __('number of slides', 'themify') . ' 
					</p>
					
					<p class="feature_box_posts" '.$display_posts_display.'>
						<span class="label">' . __('Display', 'themify') . '</span>
								<select name="setting-slider_default_display">
								';
								
								foreach($display_options as $option => $value){
									if ( isset( $data['setting-slider_default_display'] ) && ( $value == $data['setting-slider_default_display'] ) ) {
										$output .= '<option value="'.$value.'" selected="selected">'.$option.'</option>';
									} else {
										$output .= '<option value="'.$value.'">'.$option.'</option>';
									}
								}
								
		$output .= '
					</select>
				</p>';
				
		$output .= '<p class="feature_box_posts" '.$display_posts_display.'>
					<span class="label">' . __('Hide Title', 'themify') . '</span>
					<select name="setting-slider_hide_title">
					';
					foreach($title_options as $option){
							if ( isset( $data['setting-slider_hide_title'] ) && ( $option == $data['setting-slider_hide_title'] ) ) {
								$output .= '<option value="'.$option.'" selected="selected">'.$option.'</option>';
							} else {
								$output .= '<option value="'.$option.'">'.$option.'</option>';
							}
						}			
		$output .= '
					</select>
				</p>';
				
		$output .= '<div class="feature_box_images" '.$display_images_display.'>';
				
				$x = 0;
				foreach ( $image_options as $option ) {
					$x++;
					$output .= '
					<div class="uploader-fields">
						<span class="label">' . __('Title', 'themify') . ' '.$x.'</span>
						<input type="text" class="width10" name="setting-slider_images_'.$option.'_title" value="' . esc_attr( themify_get( 'setting-slider_images_'.$option.'_title' ) ) . '" />
						<span class="label">' . __('Link', 'themify') . ' '.$x.'</span>
						<input type="text" class="width10" name="setting-slider_images_'.$option.'_link" value="' . esc_attr( themify_get( 'setting-slider_images_'.$option.'_link' ) ) . '" />
						<span class="label">' . __('Image', 'themify') . ' '.$x.'</span>
						<input type="text" class="width10 feature_box_img" id="setting-slider_images_'.$option.'_image" name="setting-slider_images_'.$option.'_image" value="' . esc_attr( themify_get( 'setting-slider_images_'.$option.'_image' ) ) . '" /> 
						<div class="pushlabel" style="display:block;">' . themify_get_uploader('setting-slider_images_'.$option.'_image', array('tomedia' => true)) . '</div>
					</div>';
				}
	
		$output .= '</div>
				<hr />
				<p>
				<span class="label">' . __('Auto Play', 'themify') . '</span>
							<select name="setting-slider_auto">
							';
						foreach($auto_options as $name => $val){
							if ( isset( $data['setting-slider_auto'] ) && ( $val == $data['setting-slider_auto'] ) ) {
								$output .= '<option value="'.$val.'" selected="selected">'.$name.'</option>';
							} else {
								$output .= '<option value="'.$val.'">'.$name.'</option>';
							}
						}		
		$output .= '
					</select>
				</p>
				<p>
					<span class="label">' . __('Speed', 'themify') . '</span> 
					<select name="setting-slider_speed">';
					foreach($speed_options as $name => $val){
						if ( isset( $data['setting-slider_speed'] ) && ( $val == $data['setting-slider_speed'] ) ) {
							$output .= '<option value="'.$val.'" selected="selected">'.$name.'</option>';	
						} else {
							$output .= '<option value="'.$val.'">'.$name.'</option>';	
						}	
					}
		$output .= '</select>
				</p>
				<p>
					<span class="label">' . __('Effect', 'themify') . '</span> 
					<select name="setting-slider_effect">';
					foreach($effect_options as $effc => $val){
						if ( isset( $data['setting-slider_effect'] ) && ( $val == $data['setting-slider_effect'] ) ) {
							$output .= '<option value="'.$val.'" selected="selected">'.$effc.'</option>';	
						} else {
							$output .= '<option value="'.$val.'">'.$effc.'</option>';	
						}	
					}
		$output .= '</select>
				</p>
				<hr />
				<p>
					<span class="label">' . __('Feature Image Size', 'themify') . '</span>
					<input type="text" name="setting-slider_width" class="width2" value="' . themify_get( 'setting-slider_width' ) . '" /> ' . __('width', 'themify') . ' <small>(in px)</small>
					<input type="text" name="setting-slider_height" class="width2" value="' . themify_get( 'setting-slider_height' ) . '" /> ' . __('height', 'themify') . ' <small>(in px)</small>
				</p>
				</div>';		
		return $output;
	}
	
	///////////////////////////////////////////
	// Homepage Header Slider Function - Action
	///////////////////////////////////////////
	function themify_slider_action($data=array()){
		$data = themify_get_data();
		if($data['setting-slider_speed'] == ""){
			$speed = '1000';
		} else {
			$speed = $data['setting-slider_speed'];	
		}
		if($data['setting-slider_auto'] == ""){
			$auto = '0';
		} else {
			$auto = $data['setting-slider_auto'];	
		}
		if($data['setting-slider_effect'] == ""){
			$effc = 'slide';
		} else {
			$effc = $data['setting-slider_effect'];	
		}
		$speed_str = '';
		if ( $auto != '0' ) {
			$speed_str = "slideshowSpeed:".$auto.",";
		} 	
		?>
		<!-- slider -->
		<script>
		/////////////////////////////////////////////
		// Slider	 							
		/////////////////////////////////////////////
		jQuery(document).ready(function($){
			$('#slider').flexslider({
				animation: "<?php echo $effc; ?>",
				animationDuration: <?php echo $speed; ?>,
				slideshow: <?php echo ( '0' == $auto ) ? 'false' : 'true'; ?>,
				animationLoop: true, 
				directionNav: true,
				prevText: "&laquo;",
				nextText: "&raquo;",
				<?php if($auto != "0") {  ?>
				slideshowSpeed: "<?php echo $auto; ?>",
				<?php } ?>
				pauseOnHover: true
		    });
		});
		</script>

        <?php
	}
	/** Output slider js on the footer if it's enabled */
	if('' == themify_get('setting-slider_enabled') || 'on' == themify_get('setting-slider_enabled'))
		add_action('wp_footer', 'themify_slider_action');	
		
	///////////////////////////////////////////
	// Default Page Layout Module - Action
	///////////////////////////////////////////
	function themify_default_page_layout($data=array()){
		$data = themify_get_data();
		
		$options = array(
					array('value' => 'sidebar1', 'img' => 'images/layout-icons/sidebar1.png', 'selected' => true, 'title' => __('Sidebar Right', 'themify')),
					 array('value' => 'sidebar1 sidebar-left', 'img' => 'images/layout-icons/sidebar1-left.png', 'title' => __('Sidebar Left', 'themify')),
					 array('value' => 'sidebar-none', 'img' => 'images/layout-icons/sidebar-none.png', 'title' => __('No Sidebar', 'themify'))
		 );
		
		$default_options = array(
								array('name'=>'','value'=>''),
								array('name'=>__('Yes', 'themify'),'value'=>'yes'),
								array('name'=>__('No', 'themify'),'value'=>'no')
								);
							 
		$val = isset( $data['setting-default_page_layout'] ) ? $data['setting-default_page_layout'] : '';
		
		/**
		 * HTML for settings panel
		 * @var string
		 */
		$output = '<p>
						<span class="label">' . __('Page Sidebar Option', 'themify') . '</span>';
		foreach ( $options as $option ) {
			if ( ( '' == $val || ! $val || ! isset( $val ) ) && ( isset( $option['selected'] ) && $option['selected'] ) ) { 
				$val = $option['value'];
			}
			if ( $val == $option['value'] ) { 
				$class = "selected";
			} else {
				$class = "";	
			}
			$output .= '<a href="#" class="preview-icon '.$class.'" title="'.$option['title'].'"><img src="'.THEME_URI.'/'.$option['img'].'" alt="'.$option['value'].'"  /></a>';	
		}
		$output .= '<input type="hidden" name="setting-default_page_layout" class="val" value="'.$val.'" /></p>';
		$output .= '<p>
						<span class="label">' . __('Hide Title in All Pages', 'themify') . '</span>
						
						<select name="setting-hide_page_title">';
						foreach ( $default_options as $title_option ) {
							if ( isset( $data['setting-hide_page_title'] ) && ( $title_option['value'] == $data['setting-hide_page_title'] ) ) {
								$output .= '<option selected="selected" value="'.$title_option['value'].'">'.$title_option['name'].'</option>';
							} else {
								$output .= '<option value="'.$title_option['value'].'">'.$title_option['name'].'</option>';
							}
						}
						
						
		$output .=	'</select>
					</p>';
		/**
		 * Hide Feauted images in All Pages
		 */
		$output .= '<p>
                    <span class="label">' . __('Hide Featured Image', 'themify') . '</span>
                    <select name="setting-hide_page_image">' .
                        themify_options_module($default_options, 'setting-hide_page_image') . '
                    </select>
                </p>';
		if ( isset( $data['setting-comments_pages'] ) && $data['setting-comments_pages'] ) {
			$pages_checked = "checked='checked'";	
		}
		$output .= '<p><span class="label">' . __('Page Comments', 'themify') . '</span><label for="setting-comments_pages"><input type="checkbox" id="setting-comments_pages" name="setting-comments_pages" '.checked( themify_get( 'setting-comments_pages' ), 'on', false ).' /> ' . __('Disable comments in all Pages', 'themify') . '</label></p>';	
		
		return $output;													 
	}
	
	///////////////////////////////////////////
	// Default Index Layout Module - Action
	///////////////////////////////////////////
	function themify_default_layout($data=array()){
		$data = themify_get_data();
		
		if ( ! isset( $data['setting-default_more_text'] ) || '' == $data['setting-default_more_text'] ) {
			$more_text = __('More', 'themify');
		} else {
			$more_text = $data['setting-default_more_text'];
		}
		
		$default_options = array(
								array('name'=>'','value'=>''),
								array('name'=>__('Yes', 'themify'),'value'=>'yes'),
								array('name'=>__('No', 'themify'),'value'=>'no')
								);
		$default_layout_options = array(
								array('name' => __('Full Content', 'themify'),'value'=>'content'),
								array('name' => __('Excerpt', 'themify'),'value'=>'excerpt'),
								array('name' => __('None', 'themify'),'value'=>'none')
								);	
		$default_post_layout_options = array(
			 array('value' => 'list-post', 'img' => 'images/layout-icons/list-post.png', 'title' => __('List Post', 'themify'), "selected" => true),
			 array('value' => 'grid4', 'img' => 'images/layout-icons/grid4.png', 'title' => __('Grid 4', 'themify')),
			 array('value' => 'grid3', 'img' => 'images/layout-icons/grid3.png', 'title' => __('Grid 3', 'themify')),
			 array('value' => 'grid2', 'img' => 'images/layout-icons/grid2.png', 'title' => __('Grid 2', 'themify')),
			 array('value' => 'list-large-image', 'img' => 'images/layout-icons/list-large-image.png', 'title' => __('List Large Image', 'themify')),
			 array('value' => 'list-thumb-image', 'img' => 'images/layout-icons/list-thumb-image.png', 'title' => __('List Thumb Image', 'themify')),
			 array('value' => 'grid2-thumb', 'img' => 'images/layout-icons/grid2-thumb.png', 'title' => __('Grid 2 Thumb', 'themify')),
			 );
																 	 
		$options = array(
			array('value' => 'sidebar1', 'img' => 'images/layout-icons/sidebar1.png', 'selected' => true, 'title' => __('Sidebar Right', 'themify')),
			 array('value' => 'sidebar1 sidebar-left', 'img' => 'images/layout-icons/sidebar1-left.png', 'title' => __('Sidebar Left', 'themify')),
			 array('value' => 'sidebar-none', 'img' => 'images/layout-icons/sidebar-none.png', 'title' => __('No Sidebar', 'themify'))
			 );
						 
		$val = isset( $data['setting-default_layout'] ) ? $data['setting-default_layout'] : '';
		
		/**
		 * HTML for settings panel
		 * @var string
		 */
		$output = '<div class="themify-info-link">' . __( 'Here you can set the <a href="https://themify.me/docs/default-layouts">Default Layouts</a> for WordPress archive post layout (category, search, archive, tag pages, etc.), single post layout (single post page), and the static Page layout. The default single post and page layout can be override individually on the post/page > edit > Themify Custom Panel.', 'themify' ) . '</div>';

		$output .= '<p>
						<span class="label">' . __('Archive Sidebar Option', 'themify') . '</span>';
		foreach ( $options as $option ) {
			if ( ( '' == $val || ! $val || ! isset( $val ) ) && ( isset( $option['selected'] ) && $option['selected'] ) ) { 
				$val = $option['value'];
			}
			if ( $val == $option['value'] ) { 
				$class = "selected";
			} else {
				$class = "";	
			}
			$output .= '<a href="#" class="preview-icon '.$class.'" title="'.$option['title'].'"><img src="'.THEME_URI.'/'.$option['img'].'" alt="'.$option['value'].'"  /></a>';	
		}
		
		$output .= '<input type="hidden" name="setting-default_layout" class="val" value="'.$val.'" />';
		$output .= '</p>';
		$output .= '<p>
						<span class="label">' . __('Post Layout', 'themify') . '</span>';
						
		$val = isset( $data['setting-default_post_layout'] ) ? $data['setting-default_post_layout'] : '';
		
		foreach ( $default_post_layout_options as $option ) {
			if ( ( '' == $val || ! $val || ! isset( $val ) ) && ( isset( $option['selected'] ) && $option['selected'] ) ) { 
				$val = $option['value'];
			}
			if ( $val == $option['value'] ) { 
				$class = "selected";
			} else {
				$class = "";	
			}
			$output .= '<a href="#" class="preview-icon '.$class.'" title="'.$option['title'].'"><img src="'.THEME_URI.'/'.$option['img'].'" alt="'.$option['value'].'"  /></a>';	
		}
		
		$output .= '<input type="hidden" name="setting-default_post_layout" class="val" value="'.$val.'" />
					</p>
					<p>
						<span class="label">' . __('Display Content', 'themify') . '</span> 
						<select name="setting-default_layout_display">';
						foreach ( $default_layout_options as $layout_option ) {
							if ( isset( $data['setting-default_layout_display'] ) && ( $layout_option['value'] == $data['setting-default_layout_display'] ) ) {
								$output .= '<option selected="selected" value="'.$layout_option['value'].'">'.$layout_option['name'].'</option>';
							} else {
								$output .= '<option value="'.$layout_option['value'].'">'.$layout_option['name'].'</option>';
							}
						}
		$output .=	'	</select>
					</p>
					
					<p>
						<span class="label">' . __('More Text', 'themify') . '</span>
						<input type="text" name="setting-default_more_text" value="'.$more_text.'">
<span class="pushlabel vertical-grouped"><label for="setting-excerpt_more"><input type="checkbox" value="1" id="setting-excerpt_more" name="setting-excerpt_more" '.checked( themify_get( 'setting-excerpt_more' ), 1, false ).'/> ' . __('Display more link button in excerpt mode as well.', 'themify') . '</label></span>
					</p>';
					
		/**
		 * Order & OrderBy Options
		 */
		if( function_exists( 'themify_post_sorting_options' ) ) 
			$output .= themify_post_sorting_options('setting-index_order', $data);
		
		/**
		 * Hide Post Title
		 */
		$output .=	'<p>
						<span class="label">' . __('Hide Post Title', 'themify') . '</span>
						
						<select name="setting-default_post_title">';
						foreach ( $default_options as $title_option ) {
							if ( isset( $data['setting-default_post_title'] ) && ( $title_option['value'] == $data['setting-default_post_title'] ) ) {
								$output .= '<option selected="selected" value="'.$title_option['value'].'">'.$title_option['name'].'</option>';
							} else {
								$output .= '<option value="'.$title_option['value'].'">'.$title_option['name'].'</option>';
							}
						}
		$output .=	'</select>
					</p>
					<p>
						<span class="label">' . __('Unlink Post Title', 'themify') . '</span>
						
						<select name="setting-default_unlink_post_title">';
						foreach ( $default_options as $title_option ) {
							if ( isset( $data['setting-default_unlink_post_title'] ) && ( $title_option['value'] == $data['setting-default_unlink_post_title'] ) ) {
								$output .= '<option selected="selected" value="'.$title_option['value'].'">'.$title_option['name'].'</option>';
							} else {
								$output .= '<option value="'.$title_option['value'].'">'.$title_option['name'].'</option>';
							}
						}
		$output .=	'</select>
					</p>
					<p>
						<span class="label">' . __('Hide Post Meta', 'themify') . '</span>
						
						<select name="setting-default_post_meta">';
						foreach ( $default_options as $title_option ) {
							if ( isset( $data['setting-default_post_meta'] ) && ( $title_option['value'] == $data['setting-default_post_meta'] ) ) {
								$output .= '<option selected="selected" value="'.$title_option['value'].'">'.$title_option['name'].'</option>';
							} else {
								$output .= '<option value="'.$title_option['value'].'">'.$title_option['name'].'</option>';
							}
						}
		$output .=	'</select>
					</p>
					<p>
						<span class="label">' . __('Hide Post Date', 'themify') . '</span>
						
						<select name="setting-default_post_date">';
						foreach ( $default_options as $title_option ) {
							if ( isset( $data['setting-default_post_date'] ) && ( $title_option['value'] == $data['setting-default_post_date'] ) ) {
								$output .= '<option selected="selected" value="'.$title_option['value'].'">'.$title_option['name'].'</option>';
							} else {
								$output .= '<option value="'.$title_option['value'].'">'.$title_option['name'].'</option>';
							}
						}
		$output .=	'</select>
					</p>
					<p>
						<span class="label">' . __('Auto Featured Image', 'themify') . '</span>

						<label for="setting-auto_featured_image"><input type="checkbox" value="1" id="setting-auto_featured_image" name="setting-auto_featured_image" '.checked( themify_get( 'setting-auto_featured_image' ), 1, false ).'/> ' . __('If no featured image is specified, display first image in content.', 'themify') . '</label>
					</p>

					<p>
						<span class="label">' . __('Hide Featured Image', 'themify') . '</span>

						<select name="setting-default_post_image">';
						foreach ( $default_options as $title_option ) {
							if ( isset( $data['setting-default_post_image'] ) && ( $title_option['value'] == $data['setting-default_post_image'] ) ) {
								$output .= '<option selected="selected" value="'.$title_option['value'].'">'.$title_option['name'].'</option>';
							} else {
								$output .= '<option value="'.$title_option['value'].'">'.$title_option['name'].'</option>';
							}
						}
		$output .=	'</select>
					</p>
					<p>
						<span class="label">' . __('Unlink Featured Image', 'themify') . '</span>
						
						<select name="setting-default_unlink_post_image">';
						foreach ( $default_options as $title_option ) {
							if ( isset( $data['setting-default_unlink_post_image'] ) && ( $title_option['value'] == $data['setting-default_unlink_post_image'] ) ) {
								$output .= '<option selected="selected" value="'.$title_option['value'].'">'.$title_option['name'].'</option>';
							} else {
								$output .= '<option value="'.$title_option['value'].'">'.$title_option['name'].'</option>';
							}
						}
		$output .=	'</select>
					</p>';
		
		$output .= themify_feature_image_sizes_select('image_post_feature_size');
		$data = themify_get_data();
		$image_alignment = array( 'left' => __( 'Left', 'themify' ), 'right' => __( 'Right', 'themify' ) );

		$output .= '<p>
						<span class="label">' . __('Image Size', 'themify') . '</span>  
						<input type="text" class="width2" name="setting-image_post_width" value="' . themify_get( 'setting-image_post_width' ) . '" /> ' . __('width', 'themify') . ' <small>(px)</small>  
						<input type="text" class="width2 show_if_enabled_img_php" name="setting-image_post_height" value="' . themify_get( 'setting-image_post_height' ) . '" /> <span class="show_if_enabled_img_php">' . __('height', 'themify') . ' <small>(px)</small></span>
						<br /><span class="pushlabel show_if_enabled_img_php"><small>' . __('Enter height = 0 to disable vertical cropping with img.php enabled', 'themify') . '</small></span>
					</p>';
		return $output;
	}
	
	///////////////////////////////////////////
	// Default Single ' . __('Post Layout', 'themify') . '
	///////////////////////////////////////////
	function themify_default_post_layout($data=array()){
		
		$data = themify_get_data();
		
		$default_options = array(
								array('name'=>'','value'=>''),
								array('name'=>__('Yes', 'themify'),'value'=>'yes'),
								array('name'=>__('No', 'themify'),'value'=>'no')
								);
		
		$val = isset( $data['setting-default_page_post_layout'] ) ? $data['setting-default_page_post_layout'] : '';

		/**
		 * HTML for settings panel
		 * @var string
		 */
		$output = '<p>
						<span class="label">' . __('Post Sidebar Option', 'themify') . '</span>';
						
		$options = array(
							 array('value' => 'sidebar1', 'img' => 'images/layout-icons/sidebar1.png', 'selected' => true, 'title' => __('Sidebar Right', 'themify')),
							 array('value' => 'sidebar1 sidebar-left', 'img' => 'images/layout-icons/sidebar1-left.png', 'title' => __('Sidebar Left', 'themify')),
							 array('value' => 'sidebar-none', 'img' => 'images/layout-icons/sidebar-none.png', 'title' => __('No Sidebar', 'themify'))
							 );
										
		foreach ( $options as $option ) {
			if ( ( '' == $val || ! $val || ! isset( $val ) ) && ( isset( $option['selected'] ) && $option['selected'] ) ) { 
				$val = $option['value'];
			}
			if ( $val == $option['value'] ) { 
				$class = "selected";
			} else {
				$class = "";	
			}
			$output .= '<a href="#" class="preview-icon '.$class.'" title="'.$option['title'].'"><img src="'.THEME_URI.'/'.$option['img'].'" alt="'.$option['value'].'"  /></a>';	
		}
		
		$output .= '<input type="hidden" name="setting-default_page_post_layout" class="val" value="'.$val.'" />';
		$output .= '</p>
					<p>
						<span class="label">' . __('Hide Post Title', 'themify') . '</span>
						
						<select name="setting-default_page_post_title">';
						foreach ( $default_options as $title_option ) {
							if ( isset( $data['setting-default_page_post_title'] ) && ( $title_option['value'] == $data['setting-default_page_post_title'] ) ) {
								$output .= '<option selected="selected" value="'.$title_option['value'].'">'.$title_option['name'].'</option>';
							} else {
								$output .= '<option value="'.$title_option['value'].'">'.$title_option['name'].'</option>';
							}
						}
		$output .=	'</select>
					</p>
					<p>
						<span class="label">' . __('Unlink Post Title', 'themify') . '</span>
						
						<select name="setting-default_page_unlink_post_title">';
						foreach ( $default_options as $title_option ) {
							if ( isset( $data['setting-default_page_unlink_post_title'] ) && ( $title_option['value'] == $data['setting-default_page_unlink_post_title'] ) ) {
								$output .= '<option selected="selected" value="'.$title_option['value'].'">'.$title_option['name'].'</option>';
							} else {
								$output .= '<option value="'.$title_option['value'].'">'.$title_option['name'].'</option>';
							}
						}
		$output .=	'</select>
					</p>
					<p>
						<span class="label">' . __('Hide Post Meta', 'themify') . '</span>
						
						<select name="setting-default_page_post_meta">';
						foreach ( $default_options as $title_option ) {
							if ( isset( $data['setting-default_page_post_meta'] ) && ( $title_option['value'] == $data['setting-default_page_post_meta'] ) ) {
								$output .= '<option selected="selected" value="'.$title_option['value'].'">'.$title_option['name'].'</option>';
							} else {
								$output .= '<option value="'.$title_option['value'].'">'.$title_option['name'].'</option>';
							}
						}
		$output .=	'</select>
					</p>
					<p>
						<span class="label">' . __('Hide Post Date', 'themify') . '</span>
						
						<select name="setting-default_page_post_date">';
						foreach ( $default_options as $title_option ) {
							if ( isset( $data['setting-default_page_post_date'] ) && ( $title_option['value'] == $data['setting-default_page_post_date'] ) ) {
								$output .= '<option selected="selected" value="'.$title_option['value'].'">'.$title_option['name'].'</option>';
							} else {
								$output .= '<option value="'.$title_option['value'].'">'.$title_option['name'].'</option>';
							}
						}
		$output .=	'</select>
					</p>
					<p>
						<span class="label">' . __('Hide Featured Image', 'themify') . '</span>
						
						<select name="setting-default_page_post_image">';
						foreach ( $default_options as $title_option ) {
							if ( isset( $data['setting-default_page_post_image'] ) && ( $title_option['value'] == $data['setting-default_page_post_image'] ) ) {
								$output .= '<option selected="selected" value="'.$title_option['value'].'">'.$title_option['name'].'</option>';
							} else {
								$output .= '<option value="'.$title_option['value'].'">'.$title_option['name'].'</option>';
							}
						}
		$output .=	'</select>
					</p><p>
						<span class="label">' . __('Unlink Featured Image', 'themify') . '</span>
						
						<select name="setting-default_page_unlink_post_image">';
						foreach ( $default_options as $title_option ) {
							if ( isset( $data['setting-default_page_unlink_post_image'] ) && ( $title_option['value'] == $data['setting-default_page_unlink_post_image'] ) ) {
								$output .= '<option selected="selected" value="'.$title_option['value'].'">'.$title_option['name'].'</option>';
							} else {
								$output .= '<option value="'.$title_option['value'].'">'.$title_option['name'].'</option>';
							}
						}
		$output .= '</select></p>';
		$output .= themify_feature_image_sizes_select('image_post_single_feature_size');
		$output .= '<p>
				<span class="label">' . __('Image Size', 'themify') . '</span>
						<input type="text" class="width2" name="setting-image_post_single_width" value="' . themify_get( 'setting-image_post_single_width' ) . '" /> ' . __('width', 'themify') . ' <small>(px)</small>  
						<input type="text" class="width2 show_if_enabled_img_php" name="setting-image_post_single_height" value="' . themify_get( 'setting-image_post_single_height' ) . '" /> <span class="show_if_enabled_img_php">' . __('height', 'themify') . ' <small>(px)</small></span>
						<br /><span class="pushlabel show_if_enabled_img_php"><small>' . __('Enter height = 0 to disable vertical cropping with img.php enabled', 'themify') . '</small></span>
					</p>';
		if ( themify_check( 'setting-comments_posts' ) ) {
			$comments_posts_checked = "checked='checked'";	
		}
		$output .= '<p><span class="label">' . __('Post Comments', 'themify') . '</span><label for="setting-comments_posts"><input type="checkbox" id="setting-comments_posts" name="setting-comments_posts" '.checked( themify_get( 'setting-comments_posts' ), 'on', false ).' /> ' . __('Disable comments in all Posts', 'themify') . '</label></p>';	
		
		if ( themify_check( 'setting-post_author_box' ) ) {
			$author_box_checked = "checked='checked'";	
		}
		$output .= '<p><span class="label">' . __('Show Author Box', 'themify') . '</span><label for="setting-post_author_box"><input type="checkbox" id="setting-post_author_box" name="setting-post_author_box" '.checked( themify_get( 'setting-post_author_box' ), 'on', false ).' /> ' . __('Show author box in all Posts', 'themify') . '</label></p>';

		// Post Navigation
		$pre = 'setting-post_nav_';
		$output .= '
		<p>
			<span class="label">' . __('Post Navigation', 'themify') . '</span>
			<label for="'.$pre.'disable">
				<input type="checkbox" id="'.$pre.'disable" name="'.$pre.'disable" '. checked( themify_get( $pre.'disable' ), 'on', false ) .'/> ' . __('Remove Post Navigation', 'themify') . '
				</label>
		<span class="pushlabel vertical-grouped">
				<label for="'.$pre.'same_cat">
					<input type="checkbox" id="'.$pre.'same_cat" name="'.$pre.'same_cat" '. checked( themify_get( $pre.'same_cat' ), 'on', false ) .'/> ' . __('Show only posts in the same category', 'themify') . '
				</label>
			</span>
		</p>';	
			
		return $output;
	}

	///////////////////////////////////////////
	// Footer Logo
	///////////////////////////////////////////
	function themify_footer_logo($data=array()){
		if($data['attr']['target'] != ''){
			$target = "<span class='hide target'>".$data['attr']['target']."</span>";	
		}
		$data = themify_get_data();
		if($data['setting-footer_logo'] == "image"){
			$image = "checked='checked'";
			$image_display = "style='display:block;'";
			$text_display = "";
		} else {
			$text = "checked='checked'";	
			$text_display = "";
			$image_display = "style='display:none;'";
		}
		return '<div class="themify_field_row">
					<p>
						<span class="label">' . __('Display', 'themify') . '</span> 
						<input name="setting-footer_logo" type="radio" value="text" '.$text.' /> ' . __('Site Title', 'themify') . ' 
						<input name="setting-footer_logo" type="radio" value="image" '.$image.' /> ' . __('Image', 'themify') . '
					</p>
					'.$target.'
					<div class="uploader-fields pushlabel image" '.$image_display.'>
						<input type="text" class="width10" id="setting-footer_logo_image_value" name="setting-footer_logo_image_value" value="'.$data['setting-footer_logo_image_value'].'" /> <br />
						<div style="display:block;">' . themify_get_uploader('setting-footer_logo_image_value', array('tomedia' => true)) . '</div>
					</div>
					<p class="pushlabel image" '.$image_display.'>
						<input type="text" name="setting-footer_logo_width" class="width2" value="'.$data['setting-footer_logo_width'].'" /> ' . __('width', 'themify') . ' 
						<input type="text" name="setting-footer_logo_height" class="width2" value="'.$data['setting-footer_logo_height'].'" /> ' . __('height', 'themify') . '
					</p>
				</div>';	
	}

/**
 * Highlights migration -------------------------------------------------------------------------------
 * @param array $data
 * @return string
 */
function themify_migrate_highlights_module( $data = array() ) {
	return '<p>
				<span class="label">' . __('Migrate Theme Highlights', 'themify') . '</span>
				<span>
				<a href="#" class="migrate-highlights button">'.__('Migrate', 'themify').'</a>
				<br/><small>'.__("If you haven't done so yet, you must convert your theme highlights to builder highlights by clicking the button.", 'themify').'</small></span>
			</p>
			<script type="text/javascript">
				jQuery(document).ready(function($){
					$(".migrate-highlights").click(function(e){
						e.preventDefault();
						$(".alert").addClass("busy").fadeIn(800);
						$.post(
							ajaxurl,
							{
								action: "themify_highlights_migration",
								nonce : themify_js_vars.nonce
							},
							function(data) {
								$(".alert").removeClass("busy").addClass("done").delay(800).fadeOut(800, function(){
									$(this).removeClass("done");
									window.location.reload(true);
								});
							}
						);
					});
				});
			</script>';
}

add_action('wp_ajax_themify_highlights_migration', 'themify_highlights_migration');
add_filter('themify_theme_config_setup', 'themify_theme_config_migrate_highlights');
add_action('admin_notices', 'themify_theme_migrate_highlights_notice');

/**
 * Get all highlights entries and set its post type to highlight
 */
function themify_highlights_migration() {
	check_ajax_referer( 'ajax-nonce', 'nonce' );
	$entries = get_posts(
		array(
			'post_type' => 'highlights',
			'posts_per_page' => -1
		)
	);
	if( $entries ) {
		foreach( $entries as $entry ) {
			set_post_type( $entry->ID, 'highlight' );
		}
	}
	update_option('did_highlight_migration', true);
	die();
}

/**
 * Filter Builder to add highlights migration module
 * @param $themify_theme_config
 * @return mixed
 */
function themify_theme_config_migrate_highlights( $themify_theme_config ) {
	$option = get_option( 'did_highlight_migration' );
	$counts = wp_count_posts( 'highlights' );
	$total = 0;
	foreach( $counts as $k => $v ) {
		$total += $v;
	}
	if( false === $option && $total > 0 ) {
		array_unshift( $themify_theme_config['panel']['settings']['tab']['page_builder']['custom-module'],
			array(
				'title' => __('Migrate Highlights', 'themify'),
				'function' => 'themify_migrate_highlights_module'
			)
		);
	}
	return $themify_theme_config;
}

/**
 * Displays a message prompting user to migrate highlights
 */
function themify_theme_migrate_highlights_notice(){
	$option = get_option( 'did_highlight_migration' );
	$counts = wp_count_posts( 'highlights' );
	$total = 0;
	foreach( $counts as $k => $v ) {
		$total += $v;
	}
	if( false === $option && $total > 0 ) {
		printf( '<div class="update-nag">'.__('Remember to migrate theme highlights to Builder highlights in <strong>%s > Page Builder</strong>', 'themify').'</div>',
			wp_get_theme()->Name);
	}
}
?>