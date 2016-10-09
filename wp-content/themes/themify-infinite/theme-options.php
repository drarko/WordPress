<?php
/**
 * Main Themify class
 * @package themify
 * @since 1.0.0
 */

class Themify {
	/** Default sidebar layout
	 * @var string */
	public $layout;
	/** Default posts layout
	 * @var string */
	public $post_layout;
	
	public $hide_title;
	public $hide_meta;
	public $hide_meta_author;
	public $hide_meta_category;
	public $hide_meta_comment;
	public $hide_meta_tag;
	public $hide_date;
	public $hide_image;
	public $media_position;
	
	public $unlink_title;
	public $unlink_image;
	
	public $display_content = '';
	public $auto_featured_image;
	
	public $width = '';
	public $height = '';
	
	public $avatar_size = 96;
	public $page_navigation;
	public $posts_per_page;
	
	public $image_setting = '';
	
	public $page_id = '';
	public $page_image_width = 1200;
	public $query_category = '';
	public $query_post_type = '';
	public $paged = '';
	
	/////////////////////////////////////////////
	// Set Default Image Sizes 					
	/////////////////////////////////////////////
	
	// Default Index Layout
	static $content_width = 1200;
	static $sidebar1_content_width = 600;
	
	// Default Single Post Layout
	static $single_content_width = 1200;
	static $single_sidebar1_content_width = 600;
	
	// Default Single Image Size
	static $single_image_width = 1200;
	static $single_image_height = 600;
	
	// Grid4
	static $grid4_width = 222;
	static $grid4_height = 140;
	
	// Grid3
	static $grid3_width = 306;
	static $grid3_height = 180;
	
	// Grid2
	static $grid2_width = 474;
	static $grid2_height = 250;
	
	// List Large
	static $list_large_image_width = 680;
	static $list_large_image_height = 390;
	 
	// List Thumb
	static $list_thumb_image_width = 230;
	static $list_thumb_image_height = 200;
	
	// List Grid2 Thumb
	static $grid2_thumb_width = 120;
	static $grid2_thumb_height = 100;
	
	// List Post
	static $list_post_width = 1200;
	static $list_post_height = 600;
	
	// Sorting Parameters
	public $order = 'DESC';
	public $orderby = 'date';

	function __construct() {
		
		///////////////////////////////////////////
		//Global options setup
		///////////////////////////////////////////
		$this->layout = themify_get('setting-default_layout');
		if($this->layout == '' ) $this->layout = 'sidebar1'; 
		
		$this->post_layout = themify_get('setting-default_post_layout');
		if($this->post_layout == '') $this->post_layout = 'list-post'; 
		
		$this->page_title = themify_get('setting-hide_page_title');
		$this->hide_title = themify_get('setting-default_post_title');
		$this->unlink_title = themify_get('setting-default_unlink_post_title');
		$this->media_position = themify_check('setting-default_media_position')? themify_get('setting-default_media_position') : 'above';
		$this->hide_image = themify_get('setting-default_post_image');
		$this->unlink_image = themify_get('setting-default_unlink_post_image');
		$this->auto_featured_image = !themify_check('setting-auto_featured_image')? 'field_name=post_image, image, wp_thumb&' : '';
		$this->hide_page_image = themify_get( 'setting-hide_page_image' ) == 'yes' ? 'yes' : 'no';
		
		$this->hide_meta = themify_get('setting-default_post_meta');
		$this->hide_meta_author = themify_get('setting-default_post_meta_author');
		$this->hide_meta_category = themify_get('setting-default_post_meta_category');
		$this->hide_meta_comment = themify_get('setting-default_post_meta_comment');
		$this->hide_meta_tag = themify_get('setting-default_post_meta_tag');

		$this->hide_date = themify_get('setting-default_post_date');
		
		// Set Order & Order By parameters for post sorting
		$this->order = themify_check('setting-index_order')? themify_get('setting-index_order'): 'DESC';
		$this->orderby = themify_check('setting-index_orderby')? themify_get('setting-index_orderby'): 'date';

		$this->display_content = themify_get('setting-default_layout_display');
		$this->avatar_size = apply_filters('themify_author_box_avatar_size', 96);

		if ( isset( $_GET[ 'themify_si' ] ) ) {
			$this->template_redirect();
		} else {
			add_action( 'template_redirect', array( $this, 'template_redirect' ) );
		}
	}

	function template_redirect() {
		
		$post_image_width = $post_image_height = '';
		if (is_page()) {
                    if(post_password_required()){
                        return;
                    }
                    $this->page_id = get_the_ID();
                    $this->post_layout = (themify_get('layout') != "default" && themify_check('layout')) ?
                                        themify_get('layout') : themify_get('setting-default_post_layout');
                    // set default post layout
                    if($this->post_layout == ''){
                            $this->post_layout = 'list-post';
                    }
                    $post_image_width = themify_get('image_width');
                    $post_image_height = themify_get('image_height');
		}
		if(!isset($post_image_width) || $post_image_width===''){
                    $post_image_width = themify_get('setting-image_post_width');
		}
		if(!isset($post_image_height) || $post_image_height===''){
                    $post_image_height = themify_get('setting-image_post_height');
		}


		if( is_singular() ) {
			$this->display_content = 'content';
		}
		
		if($post_image_width==='' || $post_image_height===''){
                    ///////////////////////////////////////////
                    // Setting image width, height
                    ///////////////////////////////////////////
                    switch ($this->post_layout){
                        case 'grid4':
                            $this->width = self::$grid4_width;
                            $this->height = self::$grid4_height;
                        break;
                        case 'grid3':
                            $this->width = self::$grid3_width;
                            $this->height = self::$grid3_height;
                        break;
                        case 'grid2':
                            $this->width = self::$grid2_width;
                            $this->height = self::$grid2_height;
                        break;
                        case 'list-large-image':
                            $this->width = self::$list_large_image_width;
                            $this->height = self::$list_large_image_height;
                        break;
                        case 'list-thumb-image':
                            $this->width = self::$list_thumb_image_width;
                            $this->height = self::$list_thumb_image_height;
                        break;
                        case 'grid2-thumb':
                            $this->width = self::$grid2_thumb_width;
                            $this->height = self::$grid2_thumb_height;
                        break;
                        default :
                            $this->width = self::$list_post_width;
                            $this->height = self::$list_post_height;
                        break;
                    }
                }
		if ($post_image_width>=0) {
                    $this->width = $post_image_width;
		}
		if($post_image_height>=0){
                    $this->height = $post_image_height;
		}
		
		if ( is_page() ) {
			if ( get_query_var( 'paged' ) ) {
				$this->paged = get_query_var('paged');
			} elseif ( get_query_var( 'page' ) ) {
				$this->paged = get_query_var( 'page' );
			} else {
				$this->paged = 1;
			}
			$this->query_category = themify_get('query_category');
			$this->query_taxonomy = 'category';
			$this->query_post_type = 'post';
			
			$this->layout = (themify_get('page_layout') != 'default' && themify_check('page_layout')) ? themify_get('page_layout') : themify_get('setting-default_page_layout');
			if($this->layout == ''){
				$this->layout = 'sidebar1'; 
                        }
			
			$this->post_layout = (themify_get('layout') != 'default' && themify_check('layout')) ? themify_get('layout') : themify_get('setting-default_post_layout');
			if($this->post_layout == ''){
				$this->post_layout = 'list-post';
                        }
			
			$this->page_title = (themify_get('hide_page_title') != 'default' && themify_check('hide_page_title')) ? themify_get('hide_page_title') : themify_get('setting-hide_page_title'); 
			$this->hide_title = themify_get('hide_title'); 
			$this->unlink_title = themify_get('unlink_title');
			$this->media_position = themify_check('media_position')? themify_get('media_position') : 'above'; 
			$this->hide_image = themify_get('hide_image'); 
                        $this->unlink_image = themify_get('unlink_image'); 

			// Post Meta Values ///////////////////////
			$post_meta_keys = array(
				'_author' 	=> 'post_meta_author',
				'_category' => 'post_meta_category',
				'_comment'  => 'post_meta_comment',
				'_tag' 	 	=> 'post_meta_tag'
			);
			$post_meta_key = 'setting-default_';
			$this->hide_meta = themify_check('hide_meta_all')?
								themify_get('hide_meta_all') : themify_get($post_meta_key . 'post_meta');
			foreach($post_meta_keys as $k => $v){
				$this->{'hide_meta'.$k} = themify_check('hide_meta'.$k)? themify_get('hide_meta'.$k) : themify_get($post_meta_key . $v);
			}

			$this->hide_date = themify_get('hide_date'); 
			$this->display_content = themify_get('display_content');
			$this->post_image_width = themify_get('image_width'); 
			$this->post_image_height = themify_get('image_height'); 
			$this->page_navigation = themify_get('hide_navigation'); 
			$this->posts_per_page = themify_get('posts_per_page');
			$this->order = themify_get('order');
			$this->orderby = themify_get('orderby');
		}

		elseif( is_single() ) {
			$this->hide_title = (themify_get('hide_post_title') != 'default' && themify_check('hide_post_title')) ? themify_get('hide_post_title') : themify_get('setting-default_page_post_title');
			$this->unlink_title = (themify_get('unlink_post_title') != 'default' && themify_check('unlink_post_title')) ? themify_get('unlink_post_title') : themify_get('setting-default_page_unlink_post_title');
			$this->hide_date = (themify_get('hide_post_date') != 'default' && themify_check('hide_post_date')) ? themify_get('hide_post_date') : themify_get('setting-default_page_post_date');
			$this->hide_image = (themify_get('hide_post_image') != 'default' && themify_check('hide_post_image')) ? themify_get('hide_post_image') : themify_get('setting-default_page_post_image');
			$this->unlink_image = (themify_get('unlink_post_image') != 'default' && themify_check('unlink_post_image')) ? themify_get('unlink_post_image') : themify_get('setting-default_page_unlink_post_image');
			$this->media_position = 'above';

			// Post Meta Values ///////////////////////
			$post_meta_keys = array(
				'_author' 	=> 'post_meta_author',
				'_category' => 'post_meta_category',
				'_comment'  => 'post_meta_comment',
				'_tag' 	 	=> 'post_meta_tag'
			);

			$post_meta_key = 'setting-default_page_';
			$this->hide_meta = themify_check('hide_meta_all')?
								themify_get('hide_meta_all') : themify_get($post_meta_key . 'post_meta');
			foreach($post_meta_keys as $k => $v){
				$this->{'hide_meta'.$k} = themify_check('hide_meta'.$k)? themify_get('hide_meta'.$k) : themify_get($post_meta_key . $v);
			}
			
			$this->layout = (themify_get('layout') == 'sidebar-none'
							|| themify_get('layout') == 'sidebar1'
							|| themify_get('layout') == 'sidebar1 sidebar-left'
							|| themify_get('layout') == 'sidebar2') ?
								themify_get('layout') : themify_get('setting-default_page_post_layout');
			 // set default layout
			 if($this->layout == ''){
			 	$this->layout = 'sidebar1';
                         }
			
			$this->display_content = '';
			self::$content_width = self::$single_content_width;
			self::$sidebar1_content_width = self::$single_sidebar1_content_width;
			
			 // Set Default Image Sizes for Single
                        $post_image_width = themify_get('setting-image_post_single_width');
                        $post_image_height = themify_get('setting-image_post_single_height');
			$this->width =$post_image_width>=0?$post_image_width:self::$single_image_width;
                        $this->height = $post_image_height>=0?$post_image_height:self::$single_image_height;
		}
		
		if(is_single() && $this->hide_image != 'yes') {
			$this->image_setting = 'setting=image_post_single&';
		} elseif($this->query_category != '' && $this->hide_image != 'yes') {
			$this->image_setting = '';
		} else {
			$this->image_setting = 'setting=image_post&';
		}
	}
}

/**
 * Initializes Themify class
 * @since 1.0.0
 */
function themify_global_options(){
	global $themify;
	$themify = new Themify();
}

// Builder requires it in after setup theme and single infinite in template redirect.
if ( isset( $_GET[ 'themify_si' ] ) ) {
	add_action( 'template_redirect','themify_global_options', 12 );
} else {
	add_action( 'after_setup_theme','themify_global_options', 12 );
}