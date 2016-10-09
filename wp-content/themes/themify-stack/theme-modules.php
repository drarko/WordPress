<?php
/***************************************************************************
 *						Theme Modules
 * 	----------------------------------------------------------------------
 * 						DO NOT EDIT THIS FILE
 *	----------------------------------------------------------------------
 * 
 *  					Copyright (C) Themify
 * 						http://themify.me
 *
 *  To add custom modules to the theme, create a new 'custom-modules.php' file in the theme folder.
 *  They will be added to the theme automatically.
 * 
 ***************************************************************************/

/**
 * Choose pagination or infinite scroll
 * @param array $data
 * @return string
 */
function themify_pagination_infinite($data=array()){
	$html = '<p>';

	//Infinite Scroll
	$html .= '<input ' . checked( themify_check( 'setting-more_posts' ) ? themify_get( 'setting-more_posts' ) : 'infinite', 'infinite', false ) . ' type="radio" name="setting-more_posts" value="infinite" /> ';
	$html .= __('Infinite Scroll (posts are loaded on the same page)', 'themify');
	$html .= '<br/>';
	$html .= '<label for="setting-autoinfinite"><input class="disable-autoinfinite" type="checkbox" id="setting-autoinfinite" name="setting-autoinfinite" '.checked( themify_check( 'setting-autoinfinite' ), true, false ).'/> ' . __('Disable automatic infinite scroll', 'themify').'</label>';
	$html .= '<br/><br/>';

	//Numbered pagination
	$html .= '<input ' . checked( themify_get( 'setting-more_posts' ), 'pagination', false ) . ' type="radio" name="setting-more_posts" value="pagination" /> ';
	$html .= __('Standard Pagination', 'themify');
	$html .= '</p>';

	return $html;
}

/**
 * Default Index Layout Module
 * @param array $data Theme settings data
 * @return string Markup for module.
 * @since 1.0.0
 */
function themify_default_layout( $data = array() ){
	$data = themify_get_data();
	/**
	 * Theme Settings Option Key Prefix
	 * @var string
	 */
	$prefix = 'setting-default_';
	
	if ( ! isset( $data[$prefix.'more_text'] ) || '' == $data[$prefix.'more_text'] ) {
		$more_text = __('More', 'themify');
	} else {
		$more_text = $data[$prefix.'more_text'];
	}
	/**
	 * Tertiary options <blank>|yes|no
	 * @var array
	 */
	$default_options = array(
		array('name' => '', 'value' => ''),
		array('name' => __('Yes', 'themify'), 'value' => 'yes'),
		array('name' => __('No', 'themify'), 'value' => 'no')
	);
	/**
	 * Default options 'yes', 'no'
	 * @var array
	 */
	$binary_options = array(
		array('name'=>__('Yes', 'themify'),'value'=>'yes'),
		array('name'=>__('No', 'themify'),'value'=>'no')
	);
	/**
	 * Post content display options
	 * @var array
	 */
	$default_display_options = array(
		array('name' => __('Full Content', 'themify'),'value' => 'content'),
		array('name' => __('Excerpt', 'themify'),'value' => 'excerpt'),
		array('name' => __('None', 'themify'),'value' => 'none')
	);
	/**
	 * Post layout options
	 * @var array
	 */
	$default_post_layout_options = array(
		array('value' => 'list-post', 'img' => 'images/layout-icons/list-post.png', 'title' => __('List Post', 'themify')),
		array('value' => 'grid4', 'img' => 'images/layout-icons/grid4.png', 'title' => __('Grid 4', 'themify')),
		array('value' => 'grid3', 'img' => 'images/layout-icons/grid3.png', 'title' => __('Grid 3', 'themify'), 'selected' => true),
		array('value' => 'grid2', 'img' => 'images/layout-icons/grid2.png', 'title' => __('Grid 2', 'themify')),
	);
	/**
	 * Sidebar placement options
	 * @var array
	 */
	$sidebar_location_options = array(
		array('value' => 'sidebar1', 'img' => 'images/layout-icons/sidebar1.png', 'title' => __('Sidebar Right', 'themify')),
		array('value' => 'sidebar1 sidebar-left', 'img' => 'images/layout-icons/sidebar1-left.png', 'title' => __('Sidebar Left', 'themify')),
		array('value' => 'sidebar-none', 'img' => 'images/layout-icons/sidebar-none.png', 'title' => __('No Sidebar', 'themify'), 'selected' => true )
	);
	/**
	 * Image alignment options
	 * @var array
	 */
	$alignment_options = array(
		array('name' => '', 'value' => ''),
		array('name' => __('Left', 'themify'), 'value' => 'left'),
		array('name' => __('Right', 'themify'), 'value' => 'right')
	);

	/**
	 * HTML for settings panel
	 * @var string
	 */
	$output = '<div class="themify-info-link">' . __( 'Here you can set the <a href="https://themify.me/docs/default-layouts">Default Layouts</a> for WordPress archive post layout (category, search, archive, tag pages, etc.), single post layout (single post page), and the static Page layout. The default single post and page layout can be override individually on the post/page > edit > Themify Custom Panel.', 'themify' ) . '</div>';
	
	/**
	 * Index Sidebar Option
	 */
	$output .= '<p>
					<span class="label">' . __('Archive Sidebar Option', 'themify') . '</span>';
	$val = isset( $data[$prefix.'layout'] ) ? $data[$prefix.'layout'] : '';
	foreach ( $sidebar_location_options as $option ) {
		if ( ( '' == $val || ! $val || ! isset( $val ) ) && ( isset( $option['selected'] ) && $option['selected'] ) ) { 
			$val = $option['value'];
		}
		if ( $val == $option['value'] ) { 
			$class = 'selected';
		} else {
			$class = '';
		}
		$output .= '<a href="#" class="preview-icon ' . esc_attr( $class ) . '" title="' . esc_attr( $option['title'] ) . '"><img src="' . esc_url( THEME_URI.'/'.$option['img'] ) . '" alt="' . esc_attr( $option['value'] ) . '"  /></a>';	
	}
	
	$output .= '	<input type="hidden" name="' . esc_attr( $prefix . 'layout' ) . '" class="val" value="' . esc_attr( $val ) . '" />
				</p>';

	/**
	 * Post Layout
	 */
	$output .= '<p>
					<span class="label">' . __('Post Layout', 'themify') . '</span>';
	$val = isset( $data[$prefix.'post_layout'] ) ? $data[$prefix.'post_layout'] : '';
	foreach ( $default_post_layout_options as $option ) {
		if ( ( '' == $val || ! $val || ! isset( $val ) ) && ( isset( $option['selected'] ) && $option['selected'] ) ) { 
			$val = $option['value'];
		}
		if ( $val == $option['value'] ) { 
			$class = 'selected';
		} else {
			$class = '';
		}
		$output .= '<a href="#" class="preview-icon ' . esc_attr( $class ) . '" title="' . esc_attr( $option['title'] ) . '"><img src="' . esc_url( THEME_URI.'/'.$option['img'] ) . '" alt="' . esc_attr( $option['value'] ) . '"  /></a>';	
	}

	$output .= '	<input type="hidden" name="' . esc_attr( $prefix ) . 'post_layout" class="val" value="' . esc_attr( $val ) . '" />
				</p>';

	/**
	 * Enable Masonry
	 */
	$output .=	'<p>
					<span class="label">' . __('Enable Masonry Layout', 'themify') . '</span>
					<select name="setting-disable_masonry">' .
						themify_options_module($binary_options, 'setting-disable_masonry') . '
					</select>
					<br/>
					<small>' . __( 'Masonry produces the post stacking layout (posts are placed above each other)', 'themify' ) . '</small>
				</p>';

	/**
	 * Display Content
	 */
	$output .= '<p>
					<span class="label">' . __('Display Content', 'themify') . '</span> 
					<select name="' . esc_attr( $prefix ) . 'layout_display">'.
						themify_options_module($default_display_options, $prefix.'layout_display').'
					</select>
				</p>';
	
	/**
	 * More Text
	 */
	$output .= '<p>
					<span class="label">' . __('More Text', 'themify') . '</span>
					<input type="text" name="' . esc_attr( $prefix ) . 'more_text" value="' . esc_attr( $more_text ) . '">
				</p>';

	/**
	 * Display more link in excerpt mode
	 */
	$output .= '<p><span class="pushlabel vertical-grouped"><label for="setting-excerpt_more"><input type="checkbox" value="1" id="setting-excerpt_more" name="setting-excerpt_more" '.checked( themify_get( 'setting-excerpt_more' ), 1, false ).'/> ' . __('Display more link button in excerpt mode as well.', 'themify') . '</label></span></p>';
					
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
					<select name="' . esc_attr( $prefix ) . 'post_title">' .
						themify_options_module($default_options, $prefix.'post_title') . '
					</select>
				</p>';
	
	/**
	 * Unlink Post Title
	 */
	$output .= '<p>
					<span class="label">' . __('Unlink Post Title', 'themify') . '</span>
					<select name="' . esc_attr( $prefix ) . 'unlink_post_title">' .
						themify_options_module($default_options, $prefix.'unlink_post_title') . '
					</select>
				</p>';
	
	/**
	 * Hide Post Meta
	 */
	$output .= themify_post_meta_options($prefix.'post_meta', $data);
	
	/**
	 * Hide Post Date
	 */
	$output .= '<p>
					<span class="label">' . __('Hide Post Date', 'themify') . '</span>
					<select name="' . esc_attr( $prefix ) . 'post_date">' .
						themify_options_module($default_options, $prefix.'post_date') . '
					</select>
				</p>';
	
	/**
	 * Auto Featured Image
	 */
	$output .= '<p>
					<span class="label">' . __('Auto Featured Image', 'themify') . '</span>
					<label for="setting-auto_featured_image"><input type="checkbox" value="1" id="setting-auto_featured_image" name="setting-auto_featured_image" '.checked( themify_get( 'setting-auto_featured_image' ), 1, false ).'/> ' . __('If no featured image is specified, display first image in content.', 'themify') . '</label>
				</p>';
	
	/**
	 * Hide Featured Image
	 */
	$output .= '<p>
					<span class="label">' . __('Hide Featured Image', 'themify') . '</span>
					<select name="' . esc_attr( $prefix ) . 'post_image">' .
						themify_options_module($default_options, $prefix.'post_image') . '
					</select>
				</p>';
	
	/**
	 * Unlink Featured Image
	 */
	$output .= '<p>
					<span class="label">' . __('Unlink Featured Image', 'themify') . '</span>
					<select name="' . esc_attr( $prefix ) . 'unlink_post_image">' .
						themify_options_module($default_options, $prefix.'unlink_post_image') . '
					</select>
				</p>';
	
	/**
	 * Featured Image Sizes
	 */
	$output .= themify_feature_image_sizes_select('image_post_feature_size');
	
	/**
	 * Image Dimensions
	 */	
	$output .= '<p>
					<span class="label">' . __('Image Size', 'themify') . '</span>  
					<input type="text" class="width2" name="setting-image_post_width" value="' . themify_get( 'setting-image_post_width' ) . '" /> ' . __('width', 'themify') . ' <small>(px)</small>  
					<input type="text" class="width2 show_if_enabled_img_php" name="setting-image_post_height" value="' . themify_get( 'setting-image_post_height' ) . '" /> <span class="show_if_enabled_img_php">' . __('height', 'themify') . ' <small>(px)</small></span>
					<br /><span class="pushlabel show_if_enabled_img_php"><small>' . __('Enter height = 0 to disable vertical cropping with img.php enabled', 'themify') . '</small></span>
				</p>';
	
	return $output;
}