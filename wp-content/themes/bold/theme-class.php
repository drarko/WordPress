<?php
/**
 * Theme Class
 *
 * Classes that provides special functions for the theme front end and admin.
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if(!class_exists('Themify_Portfolio')){
/**
 * Themify_Portfolio
 * Class for filtering and sorting portfolios in admin.
 *
 * @class 		Themify_Portfolio
 * @author 		Themify
 */
class Themify_Portfolio {

	public $post_type = '';
	public $taxonomies;
	static $run_once = true;
	
	function __construct($args = array()) {
		$defaults = array(
			'prefix' => '',
			'post_type' => ''
		);
		$args = wp_parse_args($args, $defaults);
		$this->post_type = $args['post_type'];
		$this->manage_and_filter();
	}
	
	/**
	 * Trigger at the end of __construct of this shortcode
	 */
	function manage_and_filter(){
		if(is_admin()){
			add_filter( "manage_edit-{$this->post_type}_columns", array(&$this, "{$this->post_type}_column_header"), 10, 2);
			add_filter( "manage_{$this->post_type}_posts_custom_column", array(&$this, "{$this->post_type}_column"), 10, 3);
			add_action( 'load-edit.php', array(&$this, "{$this->post_type}_load") );
		}
	}

	/**
	 * Add columns when filtering posts in edit.php
	 */
	public function add_columns( $taxonomies ) {
		return array_merge( $taxonomies, $this->taxonomies );
	}

	/**
	 * Filter request to sort
	 */
	function portfolio_load() {
		add_action( current_filter(), array( $this, 'setup_vars' ), 20 );
		add_action( 'restrict_manage_posts', array( $this, 'get_select' ) );
		add_filter( "manage_taxonomies_for_{$this->post_type}_columns", array( $this, 'add_columns' ) );
	}
	
	/**
	 * Display an additional column in list
	 * @param array
	 * @return array
	 */
	function portfolio_column_header($columns){
		$columns['color'] = __( 'Color', 'themify' );
		$columns['media_type'] = __( 'Media Type', 'themify' );
	    return $columns;
	}
	/**
	 * Display shortcode, type, size and color in columns in tiles list
	 * @param string column key
	 * @param number post id
	 */
	function portfolio_column( $column, $post_id ) {
		switch( $column ) {
			case 'color':
				$color_url = themify_check('background_color_preset')? themify_get('background_color_preset') : 'default';
				echo '<img src="'.THEME_URI.'/images/layout-icons/color-'.$color_url.'.png" />';
				break;
			case 'media_type':
				echo themify_check('media_type')? ucwords(str_replace('media-', '', themify_get('media_type'))) : __('Image', 'themify');
				break;
		}
	}

	/**
	 * Parses the arguments given as category to see if they are category IDs or slugs and returns a proper tax_query
	 * @param $category
	 * @param $post_type
	 * @return array
	 */
	function parse_category_args($category, $post_type) {
		if( 'all' != $category){
			$tax_query_terms = explode(',', $category);
			if(preg_match('#[a-z]#', $category)){
				return array( array(
					'taxonomy' => $post_type . '-category',
					'field' => 'slug',
					'terms' => $tax_query_terms
				));
			} else {
				return array( array(
					'taxonomy' => $post_type . '-category',
					'field' => 'id',
					'terms' => $tax_query_terms
				));
			}
		}
	}
	
	/**
	* Select form element to filter the post list
	* @return string HTML
	*/
	public function get_select() {
		if(!self::$run_once){
			return;
		}
		self::$run_once = false;
		$html = '';
		foreach ( $this->taxonomies as $tax ) {
			$options = sprintf('<option value="">%s %s</option>',
            	__( 'View All', 'themify' ),
				get_taxonomy($tax)->label
			);
			$class = is_taxonomy_hierarchical( $tax ) ? ' class="level-0"' : '';
			foreach ( get_terms( $tax ) as $taxon ) {
				$options .= sprintf( '<option %s%s value="%s">%s%s</option>',
					isset( $_GET[$tax] ) ? selected( $taxon->slug, $_GET[$tax], false ) : '',
					'0' !== $taxon->parent ? ' class="level-1"' : $class,
					$taxon->slug,
					'0' !== $taxon->parent ? str_repeat( '&nbsp;', 3 ) : '',
					"{$taxon->name} ({$taxon->count})"
				);
			}
			$html .= sprintf('<select name="%s" id="%s" class="postform">%s</select>', $tax, $tax, $options);
		}
        return print $html;
    }
	/**
	 * Setup vars when filtering posts in edit.php
	 */
	public function setup_vars() {
		$this->post_type  = get_current_screen()->post_type;
		$this->taxonomies = array_diff(
			get_object_taxonomies( $this->post_type ),
			get_taxonomies( array( 'show_admin_column' => 'false' ) )
		);
	}
}// class end
}// end if class exists

if(!class_exists('Themify_Bold')) {
/**
 * Themify_Bold
 * Class for theme front end
 *
 * @class 		Themify_Bold
 * @author 		Themify
 */
class Themify_Bold {
	
	// Custom Post Types
	static $portfolio = 'portfolio';

	public $google_fonts = '';
	
	function __construct() {

		add_action( 'init', array( $this, 'register' ) );
		add_filter( 'themify_post_types', array( $this, 'extend_post_types' ) );
		add_filter('themify_default_social_links', array( $this, 'themify_default_social_links' ) );
		
		add_action('themify_layout_before', array( $this, 'themify_welcome_message' ) );
		add_action('themify_post_end', array( $this, 'custom_post_css' ) );

		$class_name = apply_filters( 'themify_theme_class_type', 'Themify_' . ucwords( self::$portfolio ) );
		$new_class = new $class_name
		( array( 'post_type' => self::$portfolio ) );
		add_filter( "builder_is_portfolio_active", '__return_true' );

		add_action( 'wp_footer', array($this, 'enqueue_google_fonts') );
	}
	
	function register() {
		/**
		 * @var array Custom Post Types to create with its plural and singular forms
		 */
		$cpts = array(
			self::$portfolio => array(
				'plural' => __('Portfolios', 'themify'),
				'singular' => __('Portfolio', 'themify'),
				'rewrite' => themify_check('themify_portfolio_slug')? themify_get('themify_portfolio_slug') : apply_filters('themify_portfolio_rewrite', 'project')
			)
		);
		$position = 52;
		foreach( $cpts as $key => $cpt ){
			register_post_type( $key, array(
				'labels' => array(
					'name' => $cpt['plural'],
					'singular_name' => $cpt['singular'],
					'add_new' => __( 'Add New', 'themify' ),
					'add_new_item' => sprintf(__( 'Add New %s', 'themify' ), $cpt['singular']),
					'edit_item' => sprintf(__( 'Edit %s', 'themify' ), $cpt['singular']),
					'new_item' => sprintf(__( 'New %s', 'themify' ), $cpt['singular']),
					'view_item' => sprintf(__( 'View %s', 'themify' ), $cpt['singular']),
					'search_items' => sprintf(__( 'Search %s', 'themify' ), $cpt['plural']),
					'not_found' => sprintf(__( 'No %s found', 'themify' ), $cpt['plural']),
					'not_found_in_trash' => sprintf(__( 'No %s found in Trash', 'themify' ), $cpt['plural']),
					'menu_name' => $cpt['plural']
				),
				'supports' => isset($cpt['supports'])? $cpt['supports'] : array('title', 'editor', 'thumbnail', 'custom-fields', 'excerpt','author'),
				'menu_position' => $position++,
				'hierarchical' => false,
				'public' => true,
				'show_ui' => true,
				'show_in_menu' => true,
				'show_in_nav_menus' => true,
				'publicly_queryable' => true,
				'rewrite' => array( 'slug' => isset($cpt['rewrite'])? $cpt['rewrite']: strtolower($cpt['singular']) ),
				'query_var' => true,
				'can_export' => true,
				'capability_type' => 'post'
			));
			register_taxonomy( $key.'-category', array($key), array(
				'labels' => array(
					'name' => sprintf(__( '%s Categories', 'themify' ), $cpt['singular']),
					'singular_name' => sprintf(__( '%s Category', 'themify' ), $cpt['singular']),
					'search_items' => sprintf(__( 'Search %s Categories', 'themify' ), $cpt['singular']),
					'popular_items' => sprintf(__( 'Popular %s Categories', 'themify' ), $cpt['singular']),
					'all_items' => sprintf(__( 'All Categories', 'themify' ), $cpt['singular']),
					'parent_item' => sprintf(__( 'Parent %s Category', 'themify' ), $cpt['singular']),
					'parent_item_colon' => sprintf(__( 'Parent %s Category:', 'themify' ), $cpt['singular']),
					'edit_item' => sprintf(__( 'Edit %s Category', 'themify' ), $cpt['singular']),
					'update_item' => sprintf(__( 'Update %s Category', 'themify' ), $cpt['singular']),
					'add_new_item' => sprintf(__( 'Add New %s Category', 'themify' ), $cpt['singular']),
					'new_item_name' => sprintf(__( 'New %s Category', 'themify' ), $cpt['singular']),
					'separate_items_with_commas' => sprintf(__( 'Separate %s Category with commas', 'themify' ), $cpt['singular']),
					'add_or_remove_items' => sprintf(__( 'Add or remove %s Category', 'themify' ), $cpt['singular']),
					'choose_from_most_used' => sprintf(__( 'Choose from the most used %s Category', 'themify' ), $cpt['singular']),
					'menu_name' => sprintf(__( '%s Category', 'themify' ), $cpt['singular']),
				),
				'public' => true,
				'show_in_nav_menus' => true,
				'show_ui' => true,
				'show_tagcloud' => true,
				'hierarchical' => true,
				'rewrite' => true,
				'query_var' => true
			));
			add_filter('manage_edit-'.$key.'-category_columns', array(&$this, 'taxonomy_header'), 10, 2);
			add_filter('manage_'.$key.'-category_custom_column', array(&$this, 'taxonomy_column_id'), 10, 3);
		}
	}

	/**
	 * Includes this custom post to array of cpts managed by Themify
	 * @param Array
	 * @return Array
	 */
	function extend_post_types($types){
		return array_merge($types, array(self::$portfolio));
	}
	
	/**
	 * Display an additional column in categories list
	 * @since 1.0.0
	 */
	function taxonomy_header($cat_columns){
	    $cat_columns['cat_id'] = 'ID';
	    return $cat_columns;
	}
	/**
	 * Display ID in additional column in categories list
	 * @since 1.0.0
	 */
	function taxonomy_column_id($null, $column, $termid){
		return $termid;
	}
	
	function custom_post_css() {
		$post_id = get_the_ID();
		$css = array();
		$style = '';
		$rules = array(
			'#post-'.$post_id => array(
				array(	'prop' => 'font-size',
						'key' => array('font_size', 'font_size_unit')
				),
				array(	'prop' => 'font-family',
						'key' => 'font_family'
				),
				array(	'prop' => 'color',
						'key' => 'font_color'
				),
				array(	'prop' => 'background-color',
						'key' => 'background_color'
				),
				array(	'prop' => 'background-image',
						'key' => 'background_image'
				),
				array(	'prop' => 'background-repeat',
						'key' => 'background_repeat'
				),
				array(	'prop' => 'background-position',
						'key' => array('background_position_x', 'background_position_y')
				),
			),
			'#post-'.$post_id.' a' => array(
				array(	'prop' => 'color',
						'key' => 'link_color'
				)
			)
		);
		foreach ($rules as $selector => $property) {
			foreach ($property as $val) {
				$prop = $val['prop'];
				$key = $val['key'];
				if(is_array($key)) {
					if('font-size' == $prop && themify_check($key[0])){
						$css[$selector][$prop] = $prop .': '. themify_get($key[0]) . themify_get($key[1]);
					}
					if('background-position' == $prop && themify_check($key[0])){
						$css[$selector][$prop] = $prop .': '. themify_get($key[0]) . ' ' . themify_get($key[1]);
					}
				} elseif(themify_check($key) && 'default' != themify_get($key)) {
					if('color' == $prop || stripos($prop, 'color')) {
						$css[$selector][$prop] = $prop .': #'.themify_get($key);
					}
					elseif('background-image' == $prop) {
						$css[$selector][$prop] = $prop .': url('.themify_get($key).')';
					}
					elseif( $prop == 'font-family' ) {
						$font = themify_get($key);
						$css[$selector][$prop] = $prop .': '. $font;
						if( ! in_array( $font, themify_get_web_safe_font_list(true) ) ) {
							$this->google_fonts .= str_replace(' ', '+', $font.'|');
						}
					}
					else {
						$css[$selector][$prop] = $prop .': '. themify_get($key);
					}
				}
			}
			if(!empty($css[$selector])){
				$style .= "$selector {\n\t" . implode(";\n\t", $css[$selector]) . "\n}\n";
			}
		}

		if('' != $style){
			echo "\n<!-- Post-$post_id Style -->\n<style>\n$style</style>\n<!-- End Post-$post_id Style -->\n";
		}
	}
	
	function get_post_classes($post_id) {
		$class = array();
		$styles = array('post_layout', 'media_type', 'preset_font',
						'background_color_preset', 'background_image_preset');

		foreach ($styles as $key) {
			$class[$key] = (themify_check($key) && themify_get($key) != 'default')? themify_get($key): 'default';
		}

		if('default' == $class['background_color_preset']){
			$class['background_color_preset'] = themify_get('setting-default_color')?
				themify_get('setting-default_color'): 'default';
		}
		return implode(' ', $class);
	}

	function enqueue_google_fonts() {
		if( '' == $this->google_fonts ) return;
		$this->google_fonts = substr($this->google_fonts, 0, -1);
		wp_enqueue_style('section-styling-google-fonts', themify_https_esc('http://fonts.googleapis.com/css'). '?family='.$this->google_fonts);
	}
	
	// Replace default squared social link icons with circular versions
	function themify_default_social_links($data) {
		$pre = 'setting-link_img_themify-link-';
		$data[$pre.'0'] = THEME_URI . '/images/social/twitter.png';
		$data[$pre.'1'] = THEME_URI . '/images/social/facebook.png';
		$data[$pre.'2'] = THEME_URI . '/images/social/google-plus.png';
		$data[$pre.'3'] = THEME_URI . '/images/social/youtube.png';
		$data[$pre.'4'] = THEME_URI . '/images/social/pinterest.png';
		return $data;
	}
	
	// Show welcome message
	function themify_welcome_message() {
		if(is_front_page() && !is_paged()) {
			get_template_part( 'includes/welcome-message');
		}
	}
	
	function show_image($post_image) {
		global $themify; ?>

		<?php if( 'yes' == $themify->unlink_image) { ?>
			<?php echo $post_image; ?>
		<?php } else { ?>
			<a href="<?php echo $this->get_featured_image_link(); ?>">
				<?php if(themify_check('lightbox_icon')){ ?>
					<span class="zoom"></span>
				<?php } ?>
				<?php echo $post_image; ?>
			</a>
		<?php }
	}

	function show_video() {
		echo themify_post_video();
	}
	
	function show_map() {
		$map_address = preg_replace( "[\n|\r]", ' ', themify_get('map'));
		echo do_shortcode('[map address="'.$map_address.'"  zoom="'.themify_get('map_zoom').'" width=100%]');
	}
	
	function show_gallery($echo = true) {
		$gallery_shortcode = themify_get('gallery_shortcode');
		$out = do_shortcode($gallery_shortcode);
		if($echo) echo $out;
		return $out;
	}
	
	function show_slider() {
		global $themify;
	
		$sc_gallery = preg_replace('#\[gallery(.*)ids="([0-9|,]*)"(.*)\]#i', '$2', themify_get('slider'));
		$image_ids = explode(',', str_replace(' ', '', $sc_gallery));
		$out = '';
		
		// Check if post has more than one image in gallery 
		$gallery_images = get_posts(array(
			'post__in' => $image_ids,
			'post_type' => 'attachment',
			'post_mime_type' => 'image',
			'numberposts' => -1,
			'orderby' => 'post__in',
			'order' => 'ASC'
		));
	
		$autoplay = themify_check('setting-post_slider_autoplay')?
						themify_get('setting-post_slider_autoplay'): '4000';
	
		$effect = themify_check('setting-post_slider_effect')?
					themify_get('setting-post_slider_effect'):	'scroll';
	
		$speed = themify_check('setting-post_slider_transition_speed')?
					themify_get('setting-post_slider_transition_speed'): '500';
	
		$out .= '<div id="post-slider-'.get_the_ID().'" class="slideshow-wrap">
					<ul class="slideshow" data-id="post-slider-'.get_the_ID().'" data-autoplay="'.$autoplay.'" data-effect="'.$effect.'" data-speed="'.$speed.'">';
		
		foreach ( $gallery_images as $gallery_image ) {
			$out .= '<li>';
				$out .= '<a href="'.$this->get_featured_image_link().'">';
				$out .= $this->portfolio_image($gallery_image->ID, $themify->width, $themify->height);
				$out .= '</a>';
				if(is_singular('portfolio')){
					if('' != $img_caption = $gallery_image->post_excerpt) {
						$out .= '<div class="slider-image-caption">'.$img_caption.'</div>'; 
					}
				}
			$out .= '</li>';
		}
	
		$out .= '</ul></div>';
		
		echo $out;
	}

	/**
	 * Returns post category IDs concatenated in a string
	 * @param number Post ID
	 * @return string Category IDs
	 */
	public function get_categories_as_classes($post_id) {
		$categories = wp_get_post_categories($post_id);
		$class = '';
		foreach($categories as $cat)
			$class .= ' cat-'.$cat;
		return $class;
	}
	 
	/**
	 * Returns escaped URL for featured image link
	 * @return string 
	 */
	public function get_featured_image_link() {
		if ( themify_get('external_link') != '') {
			$link = esc_url(themify_get('external_link'));
		} elseif ( themify_get('lightbox_link') != '' ) {
			$link = esc_url( themify_get('lightbox_link') );
			if(themify_check('iframe_url')) {
				$do_iframe = '?iframe=true&width=100%&height=100%';
			} else {
				$do_iframe = '';
			}
			$link = $link . $do_iframe . '" class="themify_lightbox';
		} else {
			$link = get_permalink();
		}
		
		return $link;
	}
	 
	/**
	 * Returns category description
	 * @return string
	 */
	function get_category_description() {
	 	$category_description = category_description();
		if ( !empty( $category_description ) ){
			return '<div class="category-description">' . $category_description . '</div>';
		}
	}
	
	/**
	 * Returns all IDs from the given taxonomy
	 * @param string $tax Taxonomy to retrieve terms from.
	 * @return array $term_ids Array of all taxonomy terms
	 */
	function get_all_terms_ids($tax = 'category') {
		if ( ! $term_ids = wp_cache_get( 'all_'.$tax.'_ids', $tax ) ) {
			$term_ids = get_terms( $tax, array('fields' => 'ids', 'get' => 'all') );
			wp_cache_add( 'all_'.$tax.'_ids', $term_ids, $tax );
		}
		return $term_ids;
	}

	/**
	 * Returns the image for the portfolio slider
	 * @param int $attachment_id Image attachment ID
	 * @param int $width Width of the returned image
	 * @param int $height Height of the returned image
	 * @param string $size Size of the returned image
	 * @return string
	 * @since 1.1.3
	 */
	function portfolio_image($attachment_id, $width, $height, $size = 'large') {
		$size = apply_filters( 'themify_portfolio_image_size', $size );
		if ( themify_check( 'setting-img_settings_use' ) ) {
			// Image Script is disabled, use WP image
			$html = wp_get_attachment_image( $attachment_id, $size );
		} else {
			// Image Script is enabled, use it to process image
			$img = wp_get_attachment_image_src($attachment_id, $size);
			$html = themify_get_image('ignore=true&src='.$img[0].'&w='.$width.'&h='.$height);
		}
		return apply_filters( 'themify_portfolio_image_html', $html, $attachment_id, $width, $height, $size );
	}
		
}// class end
}// end if class exists

?>