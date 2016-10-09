<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Module Name: Image Pro
 * Description: 
 */
class TB_Image_Pro_Module extends Themify_Builder_Module {
	function __construct() {
		parent::__construct(array(
			'name' => __('Image Pro', 'builder-image-pro'),
			'slug' => 'pro-image'
		));
	}

	public function get_options() {
		$image_sizes = themify_get_image_sizes_list( false );
		$options = array(
			array(
				'id' => 'mod_title_image',
				'type' => 'text',
				'label' => __('Module Title', 'builder-image-pro'),
				'class' => 'large'
			),
			array(
				'type' => 'separator',
				'meta' => array( 'html' => '<hr class="meta_fields_separator" /><h3>'. __( 'Image', 'builder-image-pro' ) .'</h3>' )
			),
			array(
				'id' => 'url_image',
				'type' => 'image',
				'label' => __('Image URL', 'builder-image-pro'),
				'class' => 'fullwidth'
			),
			array(
				'id' => 'link_image_type',
				'type' => 'radio',
				'label' => __('Image Link Type', 'builder-image-pro'),
				'options' => array(
					'image_external' => __( 'Link', 'builder-image-pro' ),
					'image_lightbox_link' => __( 'Lightbox Link', 'builder-image-pro' ),
					'image_modal' => __('Text modal (it will open text content in a lightbox)', 'builder-image-pro'),
				),
				'default' => 'image_external',
				'option_js' => true
			),
			array(
				'id' => 'link_image_new_window',
				'type' => 'radio',
				'label' => __('Open in new window', 'builder-image-pro'),
				'options' => array(
					'yes' => __( 'Yes', 'builder-image-pro' ),
					'no' => __( 'No', 'builder-image-pro' ),
				),
				'default' => 'no',
				'wrap_with_class' => 'tf-group-element tf-group-element-image_external',
			),
			array(
				'id' => 'image_content_modal',
				'type' => 'wp_editor',
				'class' => 'fullwidth',
				'wrap_with_class' => 'tf-group-element tf-group-element-image_modal',
			),
			array(
				'id' => 'link_image',
				'type' => 'text',
				'label' => __('Image Link', 'builder-image-pro'),
				'class' => 'fullwidth',
				'wrap_with_class' => 'tf-group-element tf-group-element-image_external tf-group-element-image_lightbox_link',
			),
			array(
				'id' => 'image_size_image',
				'type' => 'select',
				'label' => Themify_Builder_Model::is_img_php_disabled() ? __('Image Size', 'builder-image-pro') : false,
				'empty' => array(
					'val' => '',
					'label' => ''
				),
				'hide' => Themify_Builder_Model::is_img_php_disabled() ? false : true,
				'options' => $image_sizes
			),
			array(
				'id' => 'width_image',
				'type' => 'text',
				'label' => __('Width', 'builder-image-pro'),
				'class' => 'xsmall',
				'help' => 'px',
				'value' => 300
			),
			array(
				'id' => 'appearance_image',
				'type' => 'checkbox',
				'label' => '&nbsp;',
				'default' => 'rounded',
				'options' => array(
					array( 'name' => 'fullwidth_image', 'value' => __('Auto full width', 'builder-image-pro'), 'help' => __('Span the image across available width', 'builder-image-pro'))
				)
			),
			array(
				'id' => 'height_image',
				'type' => 'text',
				'label' => __('Height', 'builder-image-pro'),
				'class' => 'xsmall',
				'help' => 'px',
				'value' => 200
			),
			array(
				'id' => 'appearance_image2',
				'type' => 'checkbox',
				'label' => __('Image Appearance', 'builder-image-pro'),
				'default' => 'rounded',
				'options' => array(
					array( 'name' => 'rounded', 'value' => __('Rounded', 'builder-image-pro')),
					array( 'name' => 'circle', 'value' => __('Circle', 'builder-image-pro'), 'help' => __('(square format image only)', 'builder-image-pro')),
				)
			),
			array(
				'id' => 'image_filter',
				'type' => 'select',
				'label' => __('Image Filter', 'builder-image-pro'),
				'options' => array(
					'none' => __('', 'builder-image-pro'),
					'grayscale' => __('Grayscale', 'builder-image-pro'),
					'grayscale-reverse' => __('Grayscale Reverse', 'builder-image-pro'),
					'sepia' => __('Sepia', 'builder-image-pro'),
					'blur' => __('Blur', 'builder-image-pro'),
				),
			),
			array(
				'id' => 'image_effect',
				'type' => 'select',
				'label' => __('Image Hover Effect', 'builder-image-pro'),
				'options' => array(
					'none' => __('', 'builder-image-pro'),
					'zoomin' => __('Zoom In', 'builder-image-pro'),
					'zoomout' => __('Zoom Out', 'builder-image-pro'),
					'rotate' => __('Rotate', 'builder-image-pro'),
					'shine' => __('Shine', 'builder-image-pro'),
					'glow' => __('Glow', 'builder-image-pro'),
				),
			),
						 array(
				'id' => 'image_alignment',
				'type' => 'radio',
				'label' => __('Image Alignment', 'builder-image-pro'),
				'options' => array(
					'image_alignment_left' => __( 'Left', 'builder-image-pro' ),
					'image_alignment_center' => __( 'Center', 'builder-image-pro' ),
					'image_alignment_right' => __('Right', 'builder-image-pro'),
				),
				'default' => 'image_alignment_left',
			),
			array(
				'type' => 'separator',
				'meta' => array( 'html' => '<hr class="meta_fields_separator" /><h3>'. __( 'Overlay', 'builder-image-pro' ) .'</h3>' )
			),
			array(
				'id' => 'title_image',
				'type' => 'text',
				'label' => __('Image Title', 'builder-image-pro'),
				'class' => 'fullwidth'
			),
			array(
				'id' => 'caption_image',
				'type' => 'textarea',
				'label' => __('Image Caption', 'builder-image-pro'),
				'class' => 'fullwidth'
			),
			array(
				'id' => 'action_button',
				'type' => 'text',
				'label' => __( 'Action Button', 'builder-image-pro' ),
				'class' => 'fullwidth',
			),
			array(
				'id' => 'color_button',
				'type' => 'layout',
				'label' => __('Button Color', 'builder-image-pro'),
				'options' => array(
					array('img' => 'color-black.png', 'value' => 'black', 'label' => __('black', 'builder-image-pro')),
					array('img' => 'color-grey.png', 'value' => 'gray', 'label' => __('gray', 'builder-image-pro')),
					array('img' => 'color-blue.png', 'value' => 'blue', 'label' => __('blue', 'builder-image-pro')),
					array('img' => 'color-light-blue.png', 'value' => 'light-blue', 'label' => __('light-blue', 'builder-image-pro')),
					array('img' => 'color-green.png', 'value' => 'green', 'label' => __('green', 'builder-image-pro')),
					array('img' => 'color-light-green.png', 'value' => 'light-green', 'label' => __('light-green', 'builder-image-pro')),
					array('img' => 'color-purple.png', 'value' => 'purple', 'label' => __('purple', 'builder-image-pro')),
					array('img' => 'color-light-purple.png', 'value' => 'light-purple', 'label' => __('light-purple', 'builder-image-pro')),
					array('img' => 'color-brown.png', 'value' => 'brown', 'label' => __('brown', 'builder-image-pro')),
					array('img' => 'color-orange.png', 'value' => 'orange', 'label' => __('orange', 'builder-image-pro')),
					array('img' => 'color-yellow.png', 'value' => 'yellow', 'label' => __('yellow', 'builder-image-pro')),
					array('img' => 'color-red.png', 'value' => 'red', 'label' => __('red', 'builder-image-pro')),
					array('img' => 'color-pink.png', 'value' => 'pink', 'label' => __('pink', 'builder-image-pro')),
					array('img' => 'color-transparent.png', 'value' => 'default', 'label' => __('Default', 'builder-image-pro')),
					array('img' => Builder_Image_Pro::get_instance()->url . 'assets/white.png', 'value' => 'white', 'label' => __('White', 'builder-image-pro')),
					array('img' => Builder_Image_Pro::get_instance()->url . 'assets/outline.png', 'value' => 'outline', 'label' => __('Outline', 'builder-image-pro'))
				)
			),
			array(
				'id' => 'link_type',
				'type' => 'radio',
				'label' => __('Button Link Type', 'builder-image-pro'),
				'options' => array(
					'external' => __( 'Link', 'builder-image-pro' ),
					'lightbox_link' => __( 'Lightbox Link', 'builder-image-pro' ),
					'modal' => __('Text modal (it will open text content in a lightbox)', 'builder-image-pro'),
				),
				'default' => 'external',
				'option_js' => true
			),
			array(
				'id' => 'link_new_window',
				'type' => 'radio',
				'label' => __('Open in new window', 'builder-image-pro'),
				'options' => array(
					'yes' => __( 'Yes', 'builder-image-pro' ),
					'no' => __( 'No', 'builder-image-pro' ),
				),
				'default' => 'no',
				'wrap_with_class' => 'tf-group-element tf-group-element-external',
			),
			array(
				'id' => 'link_address',
				'type' => 'text',
				'label' => __('Button Link', 'builder-image-pro'),
				'class' => 'fullwidth',
				'wrap_with_class' => 'tf-group-element tf-group-element-external tf-group-element-lightbox_link',
			),
			array(
				'id' => 'content_modal',
				'type' => 'wp_editor',
				'class' => 'fullwidth',
				'wrap_with_class' => 'tf-group-element tf-group-element-modal',
			),
			array(
				'id' => 'overlay_styles',
				'type' => 'multi',
				'label' => __('Overlay', 'builder-image-pro'),
				'fields' => array(
					array(
						'id' => 'overlay_color',
						'type' => 'text',
						'colorpicker' => true,
						'label' => __('Overlay Color', 'builder-image-pro'),
						'class' => 'small',
						'wrap_with_class' => ''
					),
					array(
						'id' => 'overlay_image',
						'type' => 'image',
						'label' => __('Overlay Image', 'builder-image-pro'),
						'class' => 'xlarge'
					),
				)
			),
			array(
				'id' => 'overlay_effect',
				'type' => 'select',
				'label' => __( 'Overlay Effect', 'builder-image-pro' ),
				'options' => array(
					'none' => __( 'No Effect', 'builder-image-pro' ),
					'fadeIn' => __( 'Fade In', 'builder-image-pro' ),
					'partial-overlay' => __( 'Partial Overlay', 'builder-image-pro' ),
					'flip-horizontal' => __( 'Horizontal Flip', 'builder-image-pro' ),
					'flip-vertical' => __( 'Vertical Flip', 'builder-image-pro' ),
					'fadeInUp' => __( 'fadeInUp', 'builder-image-pro' ),
					'fadeInLeft' => __( 'fadeInLeft', 'builder-image-pro' ),
					'fadeInRight' => __( 'fadeInRight', 'builder-image-pro' ),
					'fadeInDown' => __( 'fadeInDown', 'builder-image-pro' ),
					'zoomInUp' => __( 'zoomInUp', 'builder-image-pro' ),
					'zoomInLeft' => __( 'zoomInLeft', 'builder-image-pro' ),
					'zoomInRight' => __( 'zoomInRight', 'builder-image-pro' ),
					'zoomInDown' => __( 'zoomInDown', 'builder-image-pro' ),
				),
				'wrap_with_class' => '',
			),
						// Additional CSS
			array(
				'type' => 'separator',
				'meta' => array( 'html' => '<hr/>')
			),
			array(
				'id' => 'css_image',
				'type' => 'text',
				'label' => __('Additional CSS Class', 'themify'),
				'class' => 'large exclude-from-reset-field',
				'help' => sprintf( '<br/><small>%s</small>', __( 'Add additional CSS class(es) for custom styling', 'themify' ) )
			)
		);
		return $options;
	}

	public function get_animation() {
		$animation = array(
			array(
				'id' => 'animation_effect',
				'type' => 'animation_select',
				'label' => __( 'Effect', 'builder-image-pro' )
			),
			array(
				'id' => 'animation_effect_delay',
				'type' => 'text',
				'label' => __( 'Delay', 'builder-image-pro' ),
				'class' => 'xsmall',
				'description' => __( 'Delay (s)', 'builder-image-pro' ),
			),
			array(
				'id' => 'animation_effect_repeat',
				'type' => 'text',
				'label' => __( 'Repeat', 'builder-image-pro' ),
				'class' => 'xsmall',
				'description' => __( 'Repeat (x)', 'builder-image-pro' ),
			),
		);

		return $animation;
	}

	public function get_styling() {
		$styling = array(
			// Background
			array(
				'type' => 'separator',
				'meta' => array('html'=>'<hr />')
			),
			array(
				'id' => 'separator_image_background',
				'title' => '',
				'description' => '',
				'type' => 'separator',
				'meta' => array('html'=>'<h4>'.__('Background', 'builder-image-pro').'</h4>'),
			),
			array(
				'id' => 'background_color',
				'type' => 'color',
				'label' => __('Background Color', 'builder-image-pro'),
				'class' => 'small',
				'prop' => 'background-color',
				'selector' => '.module-pro-image',
			),
			// Font
			array(
				'type' => 'separator',
				'meta' => array('html'=>'<hr />')
			),
			array(
				'id' => 'separator_font',
				'type' => 'separator',
				'meta' => array('html'=>'<h4>'.__('Font', 'builder-image-pro').'</h4>'),
			),
			array(
				'id' => 'font_family',
				'type' => 'font_select',
				'label' => __('Font Family', 'builder-image-pro'),
				'class' => 'font-family-select',
				'prop' => 'font-family',
				'selector' => array( '.module-pro-image .image-pro-caption', '.module-pro-image .image-pro-title', '.module-pro-image .image-pro-action-button' )
			),
			array(
				'id' => 'font_color',
				'type' => 'color',
				'label' => __('Font Color', 'builder-image-pro'),
				'class' => 'small',
				'prop' => 'color',
				'selector' => array( '.module-pro-image .image-pro-title', '.module-pro-image .image-pro-caption' ),
			),
			array(
				'id' => 'multi_font_size',
				'type' => 'multi',
				'label' => __('Font Size', 'builder-image-pro'),
				'fields' => array(
					array(
						'id' => 'font_size',
						'type' => 'text',
						'class' => 'xsmall',
						'prop' => 'font-size',
						'selector' => '.module-pro-image'
					),
					array(
						'id' => 'font_size_unit',
						'type' => 'select',
						'meta' => array(
							array('value' => '', 'name' => ''),
							array('value' => 'px', 'name' => __('px', 'builder-image-pro')),
							array('value' => 'em', 'name' => __('em', 'builder-image-pro'))
						)
					)
				)
			),
			array(
				'id' => 'multi_line_height',
				'type' => 'multi',
				'label' => __('Line Height', 'builder-image-pro'),
				'fields' => array(
					array(
						'id' => 'line_height',
						'type' => 'text',
						'class' => 'xsmall',
						'prop' => 'line-height',
						'selector' => '.module-pro-image'
					),
					array(
						'id' => 'line_height_unit',
						'type' => 'select',
						'meta' => array(
							array('value' => '', 'name' => ''),
							array('value' => 'px', 'name' => __('px', 'builder-image-pro')),
							array('value' => 'em', 'name' => __('em', 'builder-image-pro')),
							array('value' => '%', 'name' => __('%', 'builder-image-pro'))
						)
					)
				)
			),
			array(
				'id' => 'text_align',
				'label' => __( 'Text Align', 'builder-image-pro' ),
				'type' => 'radio',
				'meta' => array(
					array( 'value' => '', 'name' => __( 'Default', 'builder-image-pro' ), 'selected' => true ),
					array( 'value' => 'left', 'name' => __( 'Left', 'builder-image-pro' ) ),
					array( 'value' => 'center', 'name' => __( 'Center', 'builder-image-pro' ) ),
					array( 'value' => 'right', 'name' => __( 'Right', 'builder-image-pro' ) ),
					array( 'value' => 'justify', 'name' => __( 'Justify', 'builder-image-pro' ) )
				),
				'prop' => 'text-align',
				'selector' => '.module-pro-image'
			),
			// Padding
			array(
				'type' => 'separator',
				'meta' => array('html'=>'<hr />')
			),
			array(
				'id' => 'separator_padding',
				'type' => 'separator',
				'meta' => array('html'=>'<h4>'.__('Padding', 'builder-image-pro').'</h4>'),
			),
			array(
				'id' => 'multi_padding_top',
				'type' => 'multi',
				'label' => __('Padding', 'builder-image-pro'),
				'fields' => array(
					array(
						'id' => 'padding_top',
						'type' => 'text',
						'class' => 'xsmall',
						'prop' => 'padding-top',
						'selector' => '.module-pro-image',
					),
					array(
						'id' => 'padding_top_unit',
						'type' => 'select',
						'description' => __('top', 'builder-image-pro'),
						'meta' => array(
							array('value' => 'px', 'name' => __('px', 'builder-image-pro')),
							array('value' => '%', 'name' => __('%', 'builder-image-pro'))
						)
					),
				)
			),
			array(
				'id' => 'multi_padding_right',
				'type' => 'multi',
				'label' => '',
				'fields' => array(
					array(
						'id' => 'padding_right',
						'type' => 'text',
						'class' => 'xsmall',
						'prop' => 'padding-right',
						'selector' => '.module-pro-image',
					),
					array(
						'id' => 'padding_right_unit',
						'type' => 'select',
						'description' => __('right', 'builder-image-pro'),
						'meta' => array(
							array('value' => 'px', 'name' => __('px', 'builder-image-pro')),
							array('value' => '%', 'name' => __('%', 'builder-image-pro'))
						)
					),
				)
			),
			array(
				'id' => 'multi_padding_bottom',
				'type' => 'multi',
				'label' => '',
				'fields' => array(
					array(
						'id' => 'padding_bottom',
						'type' => 'text',
						'class' => 'xsmall',
						'prop' => 'padding-bottom',
						'selector' => '.module-pro-image',
					),
					array(
						'id' => 'padding_bottom_unit',
						'type' => 'select',
						'description' => __('bottom', 'builder-image-pro'),
						'meta' => array(
							array('value' => 'px', 'name' => __('px', 'builder-image-pro')),
							array('value' => '%', 'name' => __('%', 'builder-image-pro'))
						)
					),
				)
			),
			array(
				'id' => 'multi_padding_left',
				'type' => 'multi',
				'label' => '',
				'fields' => array(
					array(
						'id' => 'padding_left',
						'type' => 'text',
						'class' => 'xsmall',
						'prop' => 'padding-left',
						'selector' => '.module-pro-image',
					),
					array(
						'id' => 'padding_left_unit',
						'type' => 'select',
						'description' => __('left', 'builder-image-pro'),
						'meta' => array(
							array('value' => 'px', 'name' => __('px', 'builder-image-pro')),
							array('value' => '%', 'name' => __('%', 'builder-image-pro'))
						)
					),
				)
			),
			// Margin
			array(
				'type' => 'separator',
				'meta' => array('html'=>'<hr />')
			),
			array(
				'id' => 'separator_margin',
				'type' => 'separator',
				'meta' => array('html'=>'<h4>'.__('Margin', 'builder-image-pro').'</h4>'),
			),
			array(
				'id' => 'multi_margin_top',
				'type' => 'multi',
				'label' => __('Margin', 'builder-image-pro'),
				'fields' => array(
					array(
						'id' => 'margin_top',
						'type' => 'text',
						'class' => 'xsmall',
						'prop' => 'margin-top',
						'selector' => '.module-pro-image',
					),
					array(
						'id' => 'margin_top_unit',
						'type' => 'select',
						'description' => __('top', 'builder-image-pro'),
						'meta' => array(
							array('value' => 'px', 'name' => __('px', 'builder-image-pro')),
							array('value' => '%', 'name' => __('%', 'builder-image-pro'))
						)
					),
				)
			),
			array(
				'id' => 'multi_margin_right',
				'type' => 'multi',
				'label' => '',
				'fields' => array(
					array(
						'id' => 'margin_right',
						'type' => 'text',
						'class' => 'xsmall',
						'prop' => 'margin-right',
						'selector' => '.module-pro-image',
					),
					array(
						'id' => 'margin_right_unit',
						'type' => 'select',
						'description' => __('right', 'builder-image-pro'),
						'meta' => array(
							array('value' => 'px', 'name' => __('px', 'builder-image-pro')),
							array('value' => '%', 'name' => __('%', 'builder-image-pro'))
						)
					),
				)
			),
			array(
				'id' => 'multi_margin_bottom',
				'type' => 'multi',
				'label' => '',
				'fields' => array(
					array(
						'id' => 'margin_bottom',
						'type' => 'text',
						'class' => 'xsmall',
						'prop' => 'margin-bottom',
						'selector' => '.module-pro-image',
					),
					array(
						'id' => 'margin_bottom_unit',
						'type' => 'select',
						'description' => __('bottom', 'builder-image-pro'),
						'meta' => array(
							array('value' => 'px', 'name' => __('px', 'builder-image-pro')),
							array('value' => '%', 'name' => __('%', 'builder-image-pro'))
						)
					),
				)
			),
			array(
				'id' => 'multi_margin_left',
				'type' => 'multi',
				'label' => '',
				'fields' => array(
					array(
						'id' => 'margin_left',
						'type' => 'text',
						'class' => 'xsmall',
						'prop' => 'margin-left',
						'selector' => '.module-pro-image',
					),
					array(
						'id' => 'margin_left_unit',
						'type' => 'select',
						'description' => __('left', 'builder-image-pro'),
						'meta' => array(
							array('value' => 'px', 'name' => __('px', 'builder-image-pro')),
							array('value' => '%', 'name' => __('%', 'builder-image-pro'))
						)
					),
				)
			),
			// Border
			array(
				'type' => 'separator',
				'meta' => array('html'=>'<hr />')
			),
			array(
				'id' => 'separator_border',
				'type' => 'separator',
				'meta' => array('html'=>'<h4>'.__('Border', 'builder-image-pro').'</h4>'),
			),
			array(
				'id' => 'multi_border_top',
				'type' => 'multi',
				'label' => __('Border', 'builder-image-pro'),
				'fields' => array(
					array(
						'id' => 'border_top_color',
						'type' => 'color',
						'class' => 'small',
						'prop' => 'border-top-color',
						'selector' => '.module-pro-image',
					),
					array(
						'id' => 'border_top_width',
						'type' => 'text',
						'description' => 'px',
						'class' => 'style_border style_field xsmall',
						'prop' => 'border-top-width',
						'selector' => '.module-pro-image',
					),
					array(
						'id' => 'border_top_style',
						'type' => 'select',
						'description' => __('top', 'builder-image-pro'),
						'meta' => Themify_Builder_model::get_border_styles(),
						'prop' => 'border-top-style',
						'selector' => '.module-pro-image',
					),
				)
			),
			array(
				'id' => 'multi_border_right',
				'type' => 'multi',
				'label' => '',
				'fields' => array(
					array(
						'id' => 'border_right_color',
						'type' => 'color',
						'class' => 'small',
						'prop' => 'border-right-color',
						'selector' => '.module-pro-image',
					),
					array(
						'id' => 'border_right_width',
						'type' => 'text',
						'description' => 'px',
						'class' => 'style_border style_field xsmall',
						'prop' => 'border-right-width',
						'selector' => '.module-pro-image',
					),
					array(
						'id' => 'border_right_style',
						'type' => 'select',
						'description' => __('right', 'builder-image-pro'),
						'meta' => Themify_Builder_model::get_border_styles(),
						'prop' => 'border-right-style',
						'selector' => '.module-pro-image',
					)
				)
			),
			array(
				'id' => 'multi_border_bottom',
				'type' => 'multi',
				'label' => '',
				'fields' => array(
					array(
						'id' => 'border_bottom_color',
						'type' => 'color',
						'class' => 'small',
						'prop' => 'border-bottom-color',
						'selector' => '.module-pro-image',
					),
					array(
						'id' => 'border_bottom_width',
						'type' => 'text',
						'description' => 'px',
						'class' => 'style_border style_field xsmall',
						'prop' => 'border-bottom-width',
						'selector' => '.module-pro-image',
					),
					array(
						'id' => 'border_bottom_style',
						'type' => 'select',
						'description' => __('bottom', 'builder-image-pro'),
						'meta' => Themify_Builder_model::get_border_styles(),
						'prop' => 'border-bottom-style',
						'selector' => '.module-pro-image',
					)
				)
			),
			array(
				'id' => 'multi_border_left',
				'type' => 'multi',
				'label' => '',
				'fields' => array(
					array(
						'id' => 'border_left_color',
						'type' => 'color',
						'class' => 'small',
						'prop' => 'border-left-color',
						'selector' => '.module-pro-image',
					),
					array(
						'id' => 'border_left_width',
						'type' => 'text',
						'description' => 'px',
						'class' => 'style_border style_field xsmall',
						'prop' => 'border-left-width',
						'selector' => '.module-pro-image',
					),
					array(
						'id' => 'border_left_style',
						'type' => 'select',
						'description' => __('left', 'builder-image-pro'),
						'meta' => Themify_Builder_model::get_border_styles(),
						'prop' => 'border-left-style',
						'selector' => '.module-pro-image',
					)
				)
			),
			array(
				'type' => 'separator',
				'meta' => array('html' => '<hr />')
			),
			array(
				'id' => 'separator',
				'title' => '',
				'description' => '',
				'type' => 'separator',
				'meta' => array('html' => '<h4>' . __('Action Button', 'builder-image-pro') . '</h4>'),
			),
			array(
				'id' => 'button_background_color',
				'type' => 'color',
				'label' => __('Background Color', 'builder-image-pro'),
				'class' => 'small',
				'prop' => 'background-color',
				'selector' => '.module-pro-image a.image-pro-action-button',
			),
			array(
				'id' => 'button_font_color',
				'type' => 'color',
				'label' => __('Font Color', 'builder-image-pro'),
				'class' => 'small',
				'prop' => 'color',
				'selector' => array( '.module-pro-image a.image-pro-action-button' ),
			)
		);
		return $styling;
	}
}

Themify_Builder_Model::register_module( 'TB_Image_Pro_Module' );