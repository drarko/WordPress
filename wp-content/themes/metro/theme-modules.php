<?php

/*
To add custom modules to the theme, create a new 'custom-modules.php' file in the theme folder.
They will be added to the theme automatically.
*/

/* 	Custom Modules
/***************************************************************************/

	/**
	 * Choose pagination or infinite scroll
	 * @param array $data
	 * @return string
	 */
	function themify_pagination_infinite($data=array()){
		$data = themify_get_data();

		$html = '<p>';

		//Infinite Scroll
		$html .= '<input ' . checked( themify_check( 'setting-more_posts' ) ? themify_get( 'setting-more_posts' ) : 'infinite', 'infinite', false ) . ' type="radio" name="setting-more_posts" value="infinite" /> ';
		$html .= __('Infinite Scroll (posts are loaded on the same page)', 'themify');
		$html .= '<br/>';
		$html .= '<label for="setting-autoinfinite"><input class="disable-autoinfinite" type="checkbox" id="setting-autoinfinite" name="setting-autoinfinite" '.checked( themify_get( 'setting-autoinfinite' ), 'on', false ).'/> ' . __('Disable automatic infinite scroll', 'themify').'</label>';
		$html .= '<br/><br/>';

		//Numbered pagination
		$html .= '<input ' . checked( themify_get( 'setting-more_posts' ), 'pagination', false ) . ' type="radio" name="setting-more_posts" value="pagination" /> ';
		$html .= __('Standard Pagination', 'themify');
		$html .= '</p>';

		return $html;
	}

	///////////////////////////////////////////
	// Default Page Layout Module - Action
	///////////////////////////////////////////
	function themify_default_page_layout($data=array()){
		$data = themify_get_data();

		$options = array(
			array('value' => 'sidebar1', 'img' => 'images/layout-icons/sidebar1.png', 'selected' => true, 'title' => __('Sidebar Right', 'themify')),
			array('value' => 'sidebar1 sidebar-left', 'img' => 'images/layout-icons/sidebar1-left.png', 'title' => __('Sidebar Left', 'themify')),
			array('value' => 'sidebar-none', 'img' => 'images/layout-icons/sidebar-none.png', 'title' => __('No Sidebar', 'themify')),
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
							if ( $title_option['value'] == themify_get( 'setting-hide_page_title' ) ) {
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
			array('value' => 'grid3', 'img' => 'images/layout-icons/grid3.png', 'title' => __('Grid 3', 'themify')),
			array('value' => 'grid2', 'img' => 'images/layout-icons/grid2.png', 'title' => __('Grid 2', 'themify')),
		);

		$options = array(
			array('value' => 'sidebar1', 'img' => 'images/layout-icons/sidebar1.png', 'selected' => true, 'title' => __('Sidebar Right', 'themify')),
			array('value' => 'sidebar1 sidebar-left', 'img' => 'images/layout-icons/sidebar1-left.png', 'title' => __('Sidebar Left', 'themify')),
			array('value' => 'sidebar-none', 'img' => 'images/layout-icons/sidebar-none.png', 'title' => __('No Sidebar', 'themify')),
		);

		$val = isset( $data['setting-default_layout'] ) ? $data['setting-default_layout'] : '';

		/**
		 * HTML for settings panel
		 * @var string
		 */
		$output = '<div class="themify-info-link">' . __( 'Here you can set the <a href="https://themify.me/docs/default-layouts">Default Layouts</a> for WordPress archive post layout (category, search, archive, tag pages, etc.), single post layout (single post page), and the static Page layout. The default single post and page layout can be override individually on the post/page > edit > Themify Custom Panel.', 'themify' ) . '</div>';

		$output .= '<p><span class="label">' . __('Archive Sidebar Option', 'themify') . '</span>';
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
		$options = array( 'left', 'right' );

		$output .= '<p>
						<span class="label">' . __('Image Size', 'themify') . '</span>
						<input type="text" class="width2" name="setting-image_post_width" value="' . themify_get( 'setting-image_post_width' ) . '" /> ' . __('width', 'themify') . ' <small>(px)</small>
						<input type="text" class="width2 show_if_enabled_img_php" name="setting-image_post_height" value="' . themify_get( 'setting-image_post_height' ) . '" /> <span class="show_if_enabled_img_php">' . __('height', 'themify') . ' <small>(px)</small></span>
						<br /><span class="pushlabel show_if_enabled_img_php"><small>' . __('Enter height = 0 to disable vertical cropping with img.php enabled', 'themify') . '</small></span>
					</p>';
		return $output;
	}

	///////////////////////////////////////////
	// Default Single Post Layout
	///////////////////////////////////////////
	function themify_default_post_layout($data=array()){

		$data = themify_get_data();

		$default_options = array(
								array('name'=>'','value'=>''),
								array('name'=>__('Yes', 'themify'),'value'=>'yes'),
								array('name'=>__('No', 'themify'),'value'=>'no')
								);

		$val = isset( $data['setting-default_page_post_layout'] ) ? $data['setting-default_page_post_layout'] : '';

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
		$output .= '</p>';

		$color_options = array(
			array('value' => 'default', 'img' => 'images/layout-icons/color-default.png', 'title' => __('Default', 'themify'), 'selected' => true),
			array('value' => 'purple', 'img' => 'images/layout-icons/color-purple.png', 'title' => __('Purple', 'themify')),
			array('value' => 'orange', 'img' => 'images/layout-icons/color-orange.png', 'title' => __('Orange', 'themify')),
			array('value' => 'pink', 'img' => 'images/layout-icons/color-pink.png', 'title' => __('Pink', 'themify')),
			array('value' => 'teal', 'img' => 'images/layout-icons/color-teal.png', 'title' => __('Teal', 'themify')),
			array('value' => 'blue', 'img' => 'images/layout-icons/color-blue.png', 'title' => __('Blue', 'themify')),
			array('value' => 'green', 'img' => 'images/layout-icons/color-green.png', 'title' => __('Green', 'themify')),
			array('value' => 'red', 'img' => 'images/layout-icons/color-red.png', 'title' => __('Red', 'themify')),
			array('value' => 'magenta', 'img' => 'images/layout-icons/color-magenta.png', 'title' => __('Magenta', 'themify')),
			array('value' => 'lime', 'img' => 'images/layout-icons/color-lime.png', 'title' => __('Lime', 'themify')),
			array('value' => 'brown', 'img' => 'images/layout-icons/color-brown.png', 'title' => __('Brown', 'themify')),
			array('value' => 'white', 'img' => 'images/layout-icons/color-white.png', 'title' => __('White', 'themify')),
			array('value' => 'black', 'img' => 'images/layout-icons/color-black.png', 'title' => __('Black', 'themify'))
		);

		$output .= '
		<div class="themify_field_row">
			<span class="label">' . __('Default Color', 'themify') . '</span>';
			foreach($color_options as $option){
				$class = ((isset($data['setting-default_color'])? $data['setting-default_color'] : 'default') == $option['value']) ? 'selected' : '';
        		$output .= '<a href="#" class="preview-icon '.$class.'" title="'.$option['title'].'"><img src="'.THEME_URI.'/'.$option['img'].'" alt="'.$option['value'].'"  /></a>';
        	}
			$default_color = isset($data['setting-default_color'])? $data['setting-default_color'] : 'default';
        	$output .= '<input type="hidden" name="setting-default_color" value="'.$default_color.'" class="val" />
        </div>';

		$output .= '<p>
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
	 * Default Single Portfolio Layout
	 * @param array $data
	 * @return string
	 */
	function themify_default_portfolio_single_layout($data=array()){
		/**
		 * Associative array containing theme settings
		 * @var array
		 */
		$data = themify_get_data();
		/**
		 * Variable prefix key
		 * @var string
		 */
		$prefix = 'setting-default_portfolio_single_';
		/**
		 * Basic default options '', 'yes', 'no'
		 * @var array
		 */
		$default_options = array(
			array('name'=>'','value'=>''),
			array('name'=>__('Yes', 'themify'),'value'=>'yes'),
			array('name'=>__('No', 'themify'),'value'=>'no')
		);
		/**
		 * Sidebar Layout
		 * @var string
		 */
		$layout = isset( $data[$prefix.'layout'] ) ? $data[$prefix.'layout'] : '';
		/**
		 * Sidebar Layout Options
		 * @var array
		 */
		$sidebar_options = array(
			array('value' => 'sidebar1', 'img' => 'images/layout-icons/sidebar1.png', 'title' => __('Sidebar Right', 'themify')),
			array('value' => 'sidebar1 sidebar-left', 'img' => 'images/layout-icons/sidebar1-left.png', 'title' => __('Sidebar Left', 'themify')),
			array('value' => 'sidebar-none', 'img' => 'images/layout-icons/sidebar-none.png', 'selected' => true, 'title' => __('No Sidebar', 'themify')),
		);
		/**
		 * HTML for settings panel
		 * @var string
		 */
		$output = '<p>
						<span class="label">' . __('Portfolio Sidebar Option', 'themify') . '</span>';
						foreach($sidebar_options as $option){
							if ( ( '' == $layout || !$layout || ! isset( $layout ) ) && ( isset( $option['selected'] ) && $option['selected'] ) ) {
								$layout = $option['value'];
							}
							if($layout == $option['value']){
								$class = 'selected';
							} else {
								$class = '';
							}
							$output .= '<a href="#" class="preview-icon '.$class.'" title="'.$option['title'].'"><img src="'.THEME_URI.'/'.$option['img'].'" alt="'.$option['value'].'"  /></a>';
						}
						$output .= '<input type="hidden" name="'.$prefix.'layout" class="val" value="'.$layout.'" />';

		$color_options = array(
			array('value' => 'default', 'img' => 'images/layout-icons/color-default.png', 'title' => __('Default', 'themify'), 'selected' => true),
			array('value' => 'purple', 'img' => 'images/layout-icons/color-purple.png', 'title' => __('Purple', 'themify')),
			array('value' => 'orange', 'img' => 'images/layout-icons/color-orange.png', 'title' => __('Orange', 'themify')),
			array('value' => 'pink', 'img' => 'images/layout-icons/color-pink.png', 'title' => __('Pink', 'themify')),
			array('value' => 'teal', 'img' => 'images/layout-icons/color-teal.png', 'title' => __('Teal', 'themify')),
			array('value' => 'blue', 'img' => 'images/layout-icons/color-blue.png', 'title' => __('Blue', 'themify')),
			array('value' => 'green', 'img' => 'images/layout-icons/color-green.png', 'title' => __('Green', 'themify')),
			array('value' => 'red', 'img' => 'images/layout-icons/color-red.png', 'title' => __('Red', 'themify')),
			array('value' => 'magenta', 'img' => 'images/layout-icons/color-magenta.png', 'title' => __('Magenta', 'themify')),
			array('value' => 'lime', 'img' => 'images/layout-icons/color-lime.png', 'title' => __('Lime', 'themify')),
			array('value' => 'brown', 'img' => 'images/layout-icons/color-brown.png', 'title' => __('Brown', 'themify')),
			array('value' => 'white', 'img' => 'images/layout-icons/color-white.png', 'title' => __('White', 'themify')),
			array('value' => 'black', 'img' => 'images/layout-icons/color-black.png', 'title' => __('Black', 'themify'))
		);

		$output .= '
		<div class="themify_field_row">
			<span class="label">' . __('Default Color', 'themify') . '</span>';
			foreach($color_options as $option){
				$class = ((isset($data['setting-default_portfolio_color'])? $data['setting-default_portfolio_color'] : 'default') == $option['value']) ? 'selected' : '';
        		$output .= '<a href="#" class="preview-icon '.$class.'" title="'.$option['title'].'"><img src="'.THEME_URI.'/'.$option['img'].'" alt="'.$option['value'].'"  /></a>';
        	}
			$default_color = isset($data['setting-default_portfolio_color'])? $data['setting-default_portfolio_color'] : 'default';
        	$output .= '<input type="hidden" name="setting-default_portfolio_color" value="'.$default_color.'" class="val" />
        </div>';

		$output .= '<p>
						<span class="label">' . __('Hide Portfolio Title', 'themify') . '</span>
						<select name="'.$prefix.'title">' .
							themify_options_module($default_options, $prefix.'title') . '
						</select>
					</p>';

		$output .=	'<p>
						<span class="label">' . __('Unlink Portfolio Title', 'themify') . '</span>
						<select name="'.$prefix.'unlink_post_title">' .
							themify_options_module($default_options, $prefix.'unlink_post_title') . '
						</select>
					</p>';

		$output .=	'<p>
						<span class="label">' . __('Hide Portfolio Meta', 'themify') . '</span>
						<select name="'.$prefix.'post_meta">' .
							themify_options_module($default_options, $prefix.'post_meta') . '
						</select>
					</p>';

		$output .=	'<p>
						<span class="label">' . __('Hide Portfolio Date', 'themify') . '</span>
						<select name="'.$prefix.'post_date">' .
							themify_options_module($default_options, $prefix.'post_date') . '
						</select>
					</p>';
		/**
		 * Image Dimensions
		 */
		$output .= '<p>
						<span class="label">' . __('Image Size', 'themify') . '</span>
						<input type="text" class="width2" name="'.$prefix.'image_post_width" value="'.themify_get( $prefix.'image_post_width' ).'" /> ' . __('width', 'themify') . ' <small>(px)</small>
						<input type="text" class="width2 show_if_enabled_img_php" name="'.$prefix.'image_post_height" value="'.themify_get( $prefix.'image_post_height' ).'" /> <span class="show_if_enabled_img_php">' . __('height', 'themify') . ' <small>(px)</small></span>
					</p>';

		// Portfolio Navigation
		$prefix = 'setting-portfolio_nav_';
		$output .= '
			<p>
				<span class="label">' . __('Portfolio Navigation', 'themify') . '</span>
				<label for="'. $prefix .'disable">
					<input type="checkbox" id="'. $prefix .'disable" name="'. $prefix .'disable" '. checked( themify_get( $prefix.'disable' ), 'on', false ) .'/> ' . __('Remove Portfolio Navigation', 'themify') . '
				</label>
				<span class="pushlabel vertical-grouped">
				<label for="'. $prefix .'same_cat">
					<input type="checkbox" id="'. $prefix .'same_cat" name="'. $prefix .'same_cat" '. checked( themify_get( $prefix. 'same_cat' ), 'on', false ) .'/> ' . __('Show only portfolios in the same category', 'themify') . '
				</label>
				</span>
			</p>';

		return $output;
	}

	/**
	 * Default Archive Portfolio Layout
	 * @param array $data
	 * @return string
	 */
	function themify_default_portfolio_index_layout($data=array()){
		/**
		 * Associative array containing theme settings
		 * @var array
		 */
		$data = themify_get_data();
		/**
		 * Variable prefix key
		 * @var string
		 */
		$prefix = 'setting-default_portfolio_index_';
		/**
		 * Basic default options '', 'yes', 'no'
		 * @var array
		 */
		$default_options = array(
			array('name'=>'','value'=>''),
			array('name'=>__('Yes', 'themify'),'value'=>'yes'),
			array('name'=>__('No', 'themify'),'value'=>'no')
		);
		/**
		 * Sidebar Layout
		 * @var string
		 */
		$layout = isset( $data[$prefix.'layout'] ) ? $data[$prefix.'layout'] : '';
		/**
		 * Sidebar Layout Options
		 * @var array
		 */
		$sidebar_options = array(
			array('value' => 'sidebar1', 'img' => 'images/layout-icons/sidebar1.png', 'title' => __('Sidebar Right', 'themify')),
			array('value' => 'sidebar1 sidebar-left', 'img' => 'images/layout-icons/sidebar1-left.png', 'title' => __('Sidebar Left', 'themify')),
			array('value' => 'sidebar-none', 'img' => 'images/layout-icons/sidebar-none.png', 'selected' => true, 'title' => __('No Sidebar', 'themify')),
		);
		/**
		 * Post Layout Options
		 * @var array
		 */
		$post_layout_options = array(
			array('value' => 'list-post', 'img' => 'images/layout-icons/list-post.png', 'title' => __('List Post', 'themify'), "selected" => true),
			array('value' => 'grid3', 'img' => 'images/layout-icons/grid3.png', 'title' => __('Grid 3', 'themify')),
			array('value' => 'grid2', 'img' => 'images/layout-icons/grid2.png', 'title' => __('Grid 2', 'themify'))
		);
		/**
		 * HTML for settings panel
		 * @var string
		 */
		$output = '<p>
						<span class="label">' . __('Portfolio Sidebar Option', 'themify') . '</span>';
						foreach($sidebar_options as $option){
							if ( ( '' == $layout || !$layout || ! isset( $layout ) ) && ( isset( $option['selected'] ) && $option['selected'] ) ) {
								$layout = $option['value'];
							}
							if($layout == $option['value']){
								$class = 'selected';
							} else {
								$class = '';
							}
							$output .= '<a href="#" class="preview-icon '.$class.'" title="'.$option['title'].'"><img src="'.THEME_URI.'/'.$option['img'].'" alt="'.$option['value'].'"  /></a>';
						}
						$output .= '<input type="hidden" name="'.$prefix.'layout" class="val" value="'.$layout.'" />';
		$output .= '</p>';
		/**
		 * Post Layout
		 */
		$output .= '<p>
						<span class="label">' . __('Post Layout', 'themify') . '</span>';

						$val = isset( $data[$prefix.'post_layout'] ) ? $data[$prefix.'post_layout'] : '';

						foreach($post_layout_options as $option){
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

		$output .= '	<input type="hidden" name="'.$prefix.'post_layout" class="val" value="'.$val.'" />
					</p>';
		/**
		 * Display Content
		 */
		$output .= '<p>
						<span class="label">' . __('Display Content', 'themify') . '</span>
						<select name="'.$prefix.'display">'.
							themify_options_module(array(
								array('name' => __('None', 'themify'),'value'=>'none'),
								array('name' => __('Full Content', 'themify'),'value'=>'content'),
								array('name' => __('Excerpt', 'themify'),'value'=>'excerpt')
							), $prefix.'display').'
						</select>
					</p>';

		$output .= '<p>
						<span class="label">' . __('Hide Portfolio Title', 'themify') . '</span>
						<select name="'.$prefix.'title">' .
							themify_options_module($default_options, $prefix.'title') . '
						</select>
					</p>';

		$output .=	'<p>
						<span class="label">' . __('Unlink Portfolio Title', 'themify') . '</span>
						<select name="'.$prefix.'unlink_post_title">' .
							themify_options_module($default_options, $prefix.'unlink_post_title') . '
						</select>
					</p>';

		$output .=	'<p>
						<span class="label">' . __('Hide Portfolio Meta', 'themify') . '</span>
						<select name="'.$prefix.'post_meta">' .
							themify_options_module($default_options, $prefix.'post_meta', true, 'yes') . '
						</select>
					</p>';

		$output .=	'<p>
						<span class="label">' . __('Hide Portfolio Date', 'themify') . '</span>
						<select name="'.$prefix.'post_date">' .
							themify_options_module($default_options, $prefix.'post_date', true, 'yes') . '
						</select>
					</p>';
		/**
		 * Image Dimensions
		 */
		$output .= '<p>
						<span class="label">' . __('Image Size', 'themify') . '</span>
						<input type="text" class="width2" name="'.$prefix.'image_post_width" value="'.themify_get( $prefix.'image_post_width' ).'" /> ' . __('width', 'themify') . ' <small>(px)</small>
						<input type="text" class="width2 show_if_enabled_img_php" name="'.$prefix.'image_post_height" value="'.themify_get( $prefix.'image_post_height' ).'" /> <span class="show_if_enabled_img_php">' . __('height', 'themify') . ' <small>(px)</small></span>
					</p>';
		return $output;
	}

function themify_portfolio_slider(){
	/**
	 * Associative array containing theme settings
	 * @var array
	 */
	$data = themify_get_data();
	/**
	 * Variable prefix key
	 * @var string
	 */
	$prefix = 'setting-portfolio_slider_';
	/**
	 * Basic default options '', 'yes', 'no'
	 * @var array
	 */
	$default_options = array(
		array('name'=>__('Yes', 'themify'), 'value'=>'yes'),
		array('name'=>__('No', 'themify'), 'value'=>'no')
	);
	$auto_options = array(
		__('4 Secs (default)', 'themify') => 4000,
		__('Off', 'themify') => 'off',
		__('1 Sec', 'themify') => 1000,
		__('2 Secs', 'themify') => 2000,
		__('3 Secs', 'themify') => 3000,
		__('4 Secs', 'themify') => 4000,
		__('5 Secs', 'themify') => 5000,
		__('6 Secs', 'themify') => 6000,
		__('7 Secs', 'themify') => 7000,
		__('8 Secs', 'themify') => 8000,
		__('9 Secs', 'themify') => 9000,
		__('10 Secs', 'themify')=> 10000
	);
	$speed_options = array(
		__('Fast', 'themify') => 500,
		__('Normal', 'themify') => 1000,
		__('Slow', 'themify') => 1500
	);
	$effect_options = array(
		array('name' => __('Slide', 'themify'), 'value' => 'slide'),
		array('name' => __('Fade', 'themify'), 'value' =>'fade')
	);

	/**
	 * HTML for settings panel
	 * @var string
	 */
	$output = '<p>
					<span class="label">' . __('Auto Play', 'themify') . '</span>
					<select name="'.$prefix.'autoplay">';
					foreach($auto_options as $name => $val){
						$output .= '<option value="'.$val.'" ' . selected( themify_get( $prefix.'autoplay' ), themify_check( $prefix.'autoplay' )? $val: 4000, false) . '>'.$name.'</option>';
					}
	$output .= '	</select>
				</p>';
	$output .= '<p>
					<span class="label">' . __('Effect', 'themify') . '</span>
					<select name="'.$prefix.'effect">'.
					themify_options_module($effect_options, $prefix.'effect') . '
					</select>
				</p>';
	$output .= '<p>
					<span class="label">' . __('Transition Speed', 'themify') . '</span>
					<select name="'.$prefix.'transition_speed">';
					foreach($speed_options as $name => $val){
						$output .= '<option value="'.$val.'" ' . selected( themify_get( $prefix.'transition_speed' ), themify_check( $prefix.'transition_speed' )? $val: 500, false) . '>'.$name.'</option>';
					}
	$output .= '	</select>
				</p>';

	return $output;
}

?>
