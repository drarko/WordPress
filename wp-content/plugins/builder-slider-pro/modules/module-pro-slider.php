<?php

if (!defined('ABSPATH'))
	exit; // Exit if accessed directly

/**
 * Module Name: Slider Pro
 */
class TB_Pro_Slider_Module extends Themify_Builder_Module {

	function __construct() {
		parent::__construct(array(
			'name' => __('Slider Pro', 'builder-slider-pro'),
			'slug' => 'pro-slider'
		));
	}

	public function get_options() {
		return array(
			array(
				'id' => 'mod_title_slider',
				'type' => 'text',
				'label' => __( 'Module Title', 'builder-slider-pro' ),
				'class' => 'large'
			),
			array(
				'id' => 'builder_ps_triggers_position',
				'type' => 'radio',
				'label' => __('Slider Pager', 'builder-slider-pro'),
				'options' => array(
					'standard' => __('Default (overlap)', 'builder-slider-pro'),
					'below' => __('Below', 'builder-slider-pro'),
					'none' => __('No pager', 'builder-slider-pro'),
				),
				'default' => 'standard',
			),
			array(
				'id' => 'builder_ps_triggers_type',
				'type' => 'radio',
				'label' => __('Pager design', 'builder-slider-pro'),
				'options' => array(
					'circle' => __('Circle', 'builder-slider-pro'),
					'thumb' => __('Photo Thumb', 'builder-slider-pro'),
					'square' => __('Square', 'builder-slider-pro'),
				),
				'default' => 'circle',
				'option_js' => true
			),
			array(
				'id' => 'builder_ps_aa',
				'type' => 'select',
				'label' => __('Auto advance to next slide', 'builder-slider-pro'),
				'options' => array(
					'off' => __('Off', 'builder-slider-pro'),
					'2000' => __('2 Seconds', 'builder-slider-pro'),
					'3000' => __('3 Seconds', 'builder-slider-pro'),
					'4000' => __('4 Seconds', 'builder-slider-pro'),
					'5000' => __('5 Seconds', 'builder-slider-pro'),
					'6000' => __('6 Seconds', 'builder-slider-pro'),
					'7000' => __('7 Seconds', 'builder-slider-pro'),
					'8000' => __('8 Seconds', 'builder-slider-pro'),
					'9000' => __('9 Seconds', 'builder-slider-pro'),
					'10000' => __('10 Seconds', 'builder-slider-pro'),
				),
				'separated' => 'top'
			),
			array(
				'id' => 'builder_ps_hover_pause',
				'type' => 'select',
				'label' => __('Pause Hover', 'builder-slider-pro'),
				'options' => array(
					'none' => __('Continue the autoplay', 'builder-slider-pro'),
					'pause' => __('Pause the autoplay', 'builder-slider-pro'),
					'stop' => __('Stop the autoplay', 'builder-slider-pro'),
				),
			),
			array(
				'id' => 'builder_ps_timer',
				'type' => 'checkbox',
				'label' => false,
				'pushed' => 'pushed',
				'options' => array(
					array('name' => 'yes', 'value' => __('Show timer bar', 'builder-slider-pro')),
				)
			),
			array(
				'id' => 'builder_ps_size',
				'type' => 'multi',
				'label' => __('Slider Size', 'builder-slider-pro'),
				'fields' => array(
					array(
						'id' => 'builder_ps_width',
						'type' => 'text',
						'label' => __('Slider Width', 'builder-slider-pro'),
						'help' => __('px', 'builder-slider-pro'),
						'class' => 'medium',
					),
					array(
						'id' => 'builder_ps_height',
						'type' => 'text',
						'label' => __('Slider Height', 'builder-slider-pro'),
						'help' => __('px', 'builder-slider-pro'),
						'class' => 'medium',
					),
				)
			),
			array(
				'id' => 'builder_ps_thumb_size',
				'type' => 'multi',
				'label' => __('Thumbnail Size', 'builder-slider-pro'),
				'fields' => array(
					array(
						'id' => 'builder_ps_thumb_width',
						'type' => 'text',
						'label' => __('Thumbnail Width', 'builder-slider-pro'),
						'help' => __('px', 'builder-slider-pro'),
						'class' => 'medium',
					),
					array(
						'id' => 'builder_ps_thumb_height',
						'type' => 'text',
						'label' => __('Thumbnail Height', 'builder-slider-pro'),
						'help' => __('px', 'builder-slider-pro'),
						'class' => 'medium',
					),
				),
				'wrap_with_class' => 'tf-group-element tf-group-element-thumb'
			),
			array(
				'id' => 'builder_slider_pro_slides',
				'type' => 'builder',
				'options' => array(
					array(
						'id' => 'builder_ps_layout',
						'type' => 'select',
						'label' => __('Slide Layout', 'builder-slider-pro'),
						'options' => array(
							'bsp-slide-content-left' => __('Left Aligned', 'builder-slider-pro'),
							'bsp-slide-content-center' => __('Center Aligned', 'builder-slider-pro'),
							'bsp-slide-content-right' => __('Right Aligned', 'builder-slider-pro')
						)
					),
					array(
						'id' => 'builder_ps_slide_type',
						'type' => 'select',
						'label' => __('Slide Background Type', 'builder-slider-pro'),
						'options' => array(
							'Image' => __('Image', 'builder-slider-pro'),
							'Video' => __('Video', 'builder-slider-pro'),
						),
						'default' => 'Image',
					),
					array(
						'id' => 'builder_ps_bg_option',
						'type' => 'multi',
						'label' => __('Slide Image Background', 'builder-slider-pro'),
						'options' => array(
							array(
								'id' => 'builder-ps-bg-color',
								'type' => 'text',
								'colorpicker' => true,
								'label' => false,
							),
							array(
								'id' => 'builder-ps-bg-image',
								'type' => 'image',
								'label' => false,
								'class' => 'large',
							),
						)
					),
					array(
						'id' => 'builder_ps_vbg_option',
						'type' => 'text',
						'label' => __('Video URL', 'builder-slider-pro'),
						'class' => 'fullwidth',
						'help' => array('text' => __('YouTube, Vimeo, etc. video <a href="http://themify.me/docs/video-embeds" target="_blank">embed link</a>', 'builder-slider-pro'))
					),
					array(
						'id' => 'builder-ps-slide-image',
						'type' => 'image',
						'label' => __('Slide Content Image', 'builder-slider-pro'),
						'class' => 'large',
						'help' => array('text' => __('Image will appear on the right/left side (depending of slide layout) Slider container'))
					),
					array(
						'id' => 'builder_ps_heading',
						'type' => 'text',
						'label' => __('Slide Heading', 'builder-slider-pro'),
						'class' => 'fullwidth',
					),
					array(
						'id' => 'builder_ps_text',
						'type' => 'wp_editor',
						'label' => false,
						'class' => 'fullwidth',
						'rows' => 1
					),
					array(
						'id' => 'builder_ps_text_option',
						'type' => 'multi',
						'label' => __('Slide Text', 'builder-slider-pro'),
						'options' => array(
							array(
								'id' => 'builder_ps_text_color',
								'type' => 'text',
								'colorpicker' => true,
								'label' => __('Color', 'builder-slider-pro'),
							),
							array(
								'id' => 'builder_ps_text_link_color',
								'type' => 'text',
								'colorpicker' => true,
								'label' => __('Link Color', 'builder-slider-pro'),
							)
						)
					),
					array(
						'id' => 'builder-ps-button-option',
						'type' => 'multi',
						'label' => __('Action Button', 'builder-slider-pro'),
						'options' => array(
							array(
								'id' => 'builder_ps_button_text',
								'type' => 'text',
								'label' => 'Text',
							),
							array(
								'id' => 'builder_ps_button_link',
								'type' => 'text',
								'label' => 'Link',
								'class' => '',
							),
						),
						'separated' => 'top'
					),
					array(
						'id' => 'builder-ps-button-option2',
						'type' => 'multi',
						'label' => false,
						'options' => array(
							array(
								'id' => 'builder_ps_button_icon',
								'type' => 'text',
								'iconpicker' => true,
								'label' => __('Icon', 'builder-slider-pro'),
							),
							array(
								'id' => 'builder_ps_button_color',
								'type' => 'text',
								'label' => 'Color',
								'colorpicker' => true,
							),
							array(
								'id' => 'builder_ps_button_bg',
								'type' => 'text',
								'label' => 'Background',
								'colorpicker' => true,
								'class' => ''
							),
						)
					),
					array(
						'separated' => 'top',
						'id' => 'builder-ps-button-option2',
						'type' => 'multi',
						'label' => 'Slide Transition',
						'options' => array(
							array(
								'id' => 'builder_ps_tranzition',
								'type' => 'select',
								'label' => __('Select', 'builder-slider-pro'),
								'options' => array(
									'slideTop' => __('Slide to Top', 'builder-slider-pro'),
									'slideBottom' => __('Slide to Bottom', 'builder-slider-pro'),
									'slideLeft' => __('Slide to Left', 'builder-slider-pro'),
									'slideRight' => __('Slide to Right', 'builder-slider-pro'),
									'slideTopFade' => __('Fade and Slide from Top', 'builder-slider-pro'),
									'slideBottomFade' => __('Fade and Slide from Bottom', 'builder-slider-pro'),
									'slideLeftFade' => __('Fade and Slide from Left', 'builder-slider-pro'),
									'slideRightFade' => __('Fade and Slide from Right', 'builder-slider-pro'),
									'fade' => __('Fade', 'builder-slider-pro'),
									'zoomOut' => __('Zoom Out', 'builder-slider-pro'),
									'zoomTop' => __('Zoom Out and slide from Top', 'builder-slider-pro'),
									'zoomBottom' => __('Zoom Out and slide from Bottom', 'builder-slider-pro'),
									'zoomLeft' => __('Zoom Out and slide from Left', 'builder-slider-pro'),
									'zoomRight' => __('Zoom Out and slide from Right', 'builder-slider-pro'),
								),
							),
							array(
								'id' => 'builder_ps_tranzition_duration',
								'type' => 'text',
								'label' => __('Duration', 'builder-slider-pro'),
								'class' => 'small',
								'help' => array('text' => __('s', 'builder-slider-pro')),
							)
						)
					),
					array(
						'separated' => 'top',
						'id' => 'builder_ps_animation',
						'type' => 'multi',
						'label' => __('Slide Title', 'builder-slider-pro'),
						'options' => array(
							array(
								'id' => 'builder_ps_h3s_timer',
								'type' => 'select',
								'label' => __('Start Animation', 'builder-slider-pro'),
								'pushed' => 'pushed',
								'options' => array(
									// 'fade' => __('Fade', 'builder-slider-pro'),
									'shortTop' => __('Short from Top', 'builder-slider-pro'),
									'shortBottom' => __('Short from Bottom', 'builder-slider-pro'),
									'shortLeft' => __('Short from Left', 'builder-slider-pro'),
									'shortRight' => __('Short from Right', 'builder-slider-pro'),
								),
							),
							array(
								'id' => 'builder_ps_h3s_tranzition_duration',
								'type' => 'text',
								'label' => '&nbsp;',
								'class' => 'medium',
								'help' => array('text' => __('s', 'builder-slider-pro')),
							),
							array(
								'id' => 'builder_ps_h3e_timer',
								'type' => 'select',
								'label' => __('End Animation', 'builder-slider-pro'),
								'pushed' => 'pushed',
								'options' => array(
									// 'fadeOut' => __('Fade Out', 'builder-slider-pro'),
									'shortTopOut' => __('Short to Top', 'builder-slider-pro'),
									'shortBottomOut' => __('Short to Bottom', 'builder-slider-pro'),
									'shortLeftOut' => __('Short to Left', 'builder-slider-pro'),
									'shortRightOut' => __('Short to Right', 'builder-slider-pro'),
								),
							),
							array(
								'id' => 'builder_ps_h3e_tranzition_duration',
								'type' => 'text',
								'label' => '&nbsp;',
								'class' => 'medium',
								'help' => array('text' => __('s', 'builder-slider-pro')),
							),
						)
					),
					array(
						'id' => 'builder_ps_animation',
						'type' => 'multi',
						'label' => __('Slide Text', 'builder-slider-pro'),
						'options' => array(
							array(
								'id' => 'builder_ps_ps_timer',
								'type' => 'select',
								'label' => '',
								'pushed' => 'pushed',
								'options' => array(
									// 'fade' => __('Fade', 'builder-slider-pro'),
									'shortTop' => __('Short from Top', 'builder-slider-pro'),
									'shortBottom' => __('Short from Bottom', 'builder-slider-pro'),
									'shortLeft' => __('Short from Left', 'builder-slider-pro'),
									'shortRight' => __('Short from Right', 'builder-slider-pro'),
								),
							),
							array(
								'id' => 'builder_ps_ps_tranzition_duration',
								'type' => 'text',
								'label' => '',
								'class' => 'medium',
								'help' => array('text' => __('s', 'builder-slider-pro')),
							),
							array(
								'id' => 'builder_ps_pe_timer',
								'type' => 'select',
								'label' => '',
								'pushed' => 'pushed',
								'options' => array(
									// 'fadeOut' => __('Fade Out', 'builder-slider-pro'),
									'shortTopOut' => __('Short to Top', 'builder-slider-pro'),
									'shortBottomOut' => __('Short to Bottom', 'builder-slider-pro'),
									'shortLeftOut' => __('Short to Left', 'builder-slider-pro'),
									'shortRightOut' => __('Short to Right', 'builder-slider-pro'),
								),
							),
							array(
								'id' => 'builder_ps_pe_tranzition_duration',
								'type' => 'text',
								'label' => '',
								'class' => 'medium',
								'help' => array('text' => __('s', 'builder-slider-pro')),
							),
						)
					),
					array(
						'id' => 'builder_ps_animation',
						'type' => 'multi',
						'label' => __('Slide Action Button', 'builder-slider-pro'),
						'options' => array(
							array(
								'id' => 'builder_ps_as_timer',
								'type' => 'select',
								'label' => '',
								'pushed' => 'pushed',
								'options' => array(
									// 'fade' => __('Fade', 'builder-slider-pro'),
									'shortTop' => __('Short from Top', 'builder-slider-pro'),
									'shortBottom' => __('Short from Bottom', 'builder-slider-pro'),
									'shortLeft' => __('Short from Left', 'builder-slider-pro'),
									'shortRight' => __('Short from Right', 'builder-slider-pro'),
								),
							),
							array(
								'id' => 'builder_ps_as_tranzition_duration',
								'type' => 'text',
								'label' => '',
								'class' => 'medium',
								'help' => array('text' => __('s', 'builder-slider-pro')),
							),
							array(
								'id' => 'builder_ps_ae_timer',
								'type' => 'select',
								'label' => '',
								'pushed' => 'pushed',
								'options' => array(
									// 'fadeOut' => __('Fade Out', 'builder-slider-pro'),
									'shortTopOut' => __('Short to Top', 'builder-slider-pro'),
									'shortBottomOut' => __('Short to Bottom', 'builder-slider-pro'),
									'shortLeftOut' => __('Short to Left', 'builder-slider-pro'),
									'shortRightOut' => __('Short to Right', 'builder-slider-pro'),
								),
							),
							array(
								'id' => 'builder_ps_ae_tranzition_duration',
								'type' => 'text',
								'label' => '',
								'class' => 'medium',
								'help' => array('text' => __('s', 'builder-slider-pro')),
							),
						)
					),
					array(
						'id' => 'builder_ps_animation',
						'type' => 'multi',
						'label' => __('Slide Content Image', 'builder-slider-pro'),
						'options' => array(
							array(
								'id' => 'builder_ps_imgs_timer',
								'type' => 'select',
								'label' => '',
								'pushed' => 'pushed',
								'options' => array(
									// 'fade' => __('Fade', 'builder-slider-pro'),
									'shortTop' => __('Short from Top', 'builder-slider-pro'),
									'shortBottom' => __('Short from Bottom', 'builder-slider-pro'),
									'shortLeft' => __('Short from Left', 'builder-slider-pro'),
									'shortRight' => __('Short from Right', 'builder-slider-pro'),
								),
							),
							array(
								'id' => 'builder_ps_imgs_tranzition_duration',
								'type' => 'text',
								'label' => '',
								'class' => 'medium',
								'help' => array('text' => __('s', 'builder-slider-pro')),
							),
							array(
								'id' => 'builder_ps_imge_timer',
								'type' => 'select',
								'label' => '',
								'pushed' => 'pushed',
								'options' => array(
									// 'fadeOut' => __('Fade Out', 'builder-slider-pro'),
									'shortTopOut' => __('Short to Top', 'builder-slider-pro'),
									'shortBottomOut' => __('Short to Bottom', 'builder-slider-pro'),
									'shortLeftOut' => __('Short to Left', 'builder-slider-pro'),
									'shortRightOut' => __('Short to Right', 'builder-slider-pro'),
								),
							),
							array(
								'id' => 'builder_ps_imge_tranzition_duration',
								'type' => 'text',
								'label' => '',
								'class' => 'medium',
								'help' => array('text' => __('s', 'builder-slider-pro')),
							),
						)
					),
				)
			)
		);
	}

	public function get_styling() {
		return array(
			array(
				'id' => 'separator_title',
				'type' => 'separator',
				'meta' => array('html' => '<h4>' . __('Title', 'builder-slider-pro') . '</h4>'),
			),
			array(
				'id' => 'title_font_family',
				'type' => 'font_select',
				'label' => __('Font Family', 'builder-slider-pro'),
				'class' => 'font-family-select',
				'prop' => 'font-family',
				'selector' => array( '.module-pro-slider .bsp-slide-post-title' ),
			),
			array(
				'id' => 'title_font_size',
				'type' => 'multi',
				'label' => __('Font Size', 'builder-slider-pro'),
				'fields' => array(
					array(
						'id' => 'font_size_title',
						'type' => 'text',
						'class' => 'xsmall',
						'prop' => 'font-size',
						'selector' => '.module-pro-slider .bsp-slide-post-title',
					),
					array(
						'id' => 'font_size_title_unit',
						'type' => 'select',
						'meta' => array(
							array('value' => '', 'name' => ''),
							array('value' => 'px', 'name' => __('px', 'builder-slider-pro')),
							array('value' => 'em', 'name' => __('em', 'builder-slider-pro'))
						)
					)
				)
			),
			array(
				'id' => 'title_line_height',
				'type' => 'multi',
				'label' => __('Line Height', 'builder-slider-pro'),
				'fields' => array(
					array(
						'id' => 'line_height_title',
						'type' => 'text',
						'class' => 'xsmall',
						'prop' => 'line-height',
						'selector' => '.module-pro-slider .bsp-slide-post-title',
					),
					array(
						'id' => 'line_height_title_unit',
						'type' => 'select',
						'meta' => array(
							array('value' => '', 'name' => ''),
							array('value' => 'px', 'name' => __('px', 'builder-slider-pro')),
							array('value' => 'em', 'name' => __('em', 'builder-slider-pro'))
						)
					)
				)
			),
			array(
				'id' => 'separator_text_l',
				'type' => 'separator',
				'meta' => array('html' => '<hr />'),
			),
			array(
				'id' => 'separator_text',
				'type' => 'separator',
				'meta' => array('html' => '<h4>' . __('Text', 'builder-slider-pro') . '</h4>'),
			),
			array(
				'id' => 'text_font_family',
				'type' => 'font_select',
				'label' => __('Font Family', 'builder-slider-pro'),
				'class' => 'font-family-select',
				'prop' => 'font-family',
				'selector' => array( '.module-pro-slider .bsp-slide-excerpt', '.module-pro-slider .bsp-slide-excerpt p' ),
			),
			array(
				'id' => 'text_font_size',
				'type' => 'multi',
				'label' => __('Font Size', 'builder-slider-pro'),
				'fields' => array(
					array(
						'id' => 'font_size_text',
						'type' => 'text',
						'class' => 'xsmall',
						'prop' => 'font-size',
						'selector' => array( '.module-pro-slider .bsp-slide-excerpt', '.module-pro-slider .bsp-slide-excerpt p' ),
					),
					array(
						'id' => 'font_size_text_unit',
						'type' => 'select',
						'meta' => array(
							array('value' => '', 'name' => ''),
							array('value' => 'px', 'name' => __('px', 'builder-slider-pro')),
							array('value' => 'em', 'name' => __('em', 'builder-slider-pro'))
						)
					)
				)
			),
			array(
				'id' => 'text_line_height',
				'type' => 'multi',
				'label' => __('Line Height', 'builder-slider-pro'),
				'fields' => array(
					array(
						'id' => 'line_height_text',
						'type' => 'text',
						'class' => 'xsmall',
						'prop' => 'line-height',
						'selector' => array( '.module-pro-slider .bsp-slide-excerpt', '.module-pro-slider .bsp-slide-excerpt p' ),
					),
					array(
						'id' => 'line_height_text_unit',
						'type' => 'select',
						'meta' => array(
							array('value' => '', 'name' => ''),
							array('value' => 'px', 'name' => __('px', 'builder-slider-pro')),
							array('value' => 'em', 'name' => __('em', 'builder-slider-pro'))
						)
					)
				)
			),
			array(
				'id' => 'separator_btn_l',
				'type' => 'separator',
				'meta' => array('html' => '<hr />'),
			),
			array(
				'id' => 'separator_btn',
				'type' => 'separator',
				'meta' => array('html' => '<h4>' . __('Action Button', 'builder-slider-pro') . '</h4>'),
			),
			array(
				'id' => 'button_font_family',
				'type' => 'font_select',
				'label' => __('Font Family', 'builder-slider-pro'),
				'class' => 'font-family-select',
				'prop' => 'font-family',
				'selector' => '.module-pro-slider .bsp-slide-button',
			),
			array(
				'id' => 'button_font_size',
				'type' => 'multi',
				'label' => __('Font Size', 'builder-slider-pro'),
				'fields' => array(
					array(
						'id' => 'font_size_button',
						'type' => 'text',
						'class' => 'xsmall',
						'prop' => 'font-size',
						'selector' => '.module-pro-slider .bsp-slide-button',
					),
					array(
						'id' => 'font_size_button_unit',
						'type' => 'select',
						'meta' => array(
							array('value' => '', 'name' => ''),
							array('value' => 'px', 'name' => __('px', 'builder-slider-pro')),
							array('value' => 'em', 'name' => __('em', 'builder-slider-pro'))
						)
					)
				)
			),
			array(
				'id' => 'button_line_height',
				'type' => 'multi',
				'label' => __('Line Height', 'builder-slider-pro'),
				'fields' => array(
					array(
						'id' => 'line_height_button',
						'type' => 'text',
						'class' => 'xsmall',
						'prop' => 'line-height',
						'selector' => '.module-pro-slider .bsp-slide-button',
					),
					array(
						'id' => 'line_height_button_unit',
						'type' => 'select',
						'meta' => array(
							array('value' => '', 'name' => ''),
							array('value' => 'px', 'name' => __('px', 'builder-slider-pro')),
							array('value' => 'em', 'name' => __('em', 'builder-slider-pro'))
						)
					)
				)
			),
			array(
				'id' => 'separator_btn',
				'type' => 'separator',
				'meta' => array('html' => '<br /><h4>' . __('Timer Bar', 'builder-slider-pro') . '</h4>'),
			),
			array(
				'id' => 'timer_bar_background_color',
				'type' => 'color',
				'label' => __('Color', 'builder-slider-pro'),
				'class' => 'small',
				'prop' => 'background-color',
				'selector' => array( '.module-pro-slider .bsp-timer-bar' ),
			),
		);
	}

	public static function make_attr( $arr ) {
		$output = '';
		foreach( $arr as $key => $value ) {
			$output .= " data-{$key}=\"{$value}\"";
		}
		return $output;
	}
}
Themify_Builder_Model::register_module('TB_Pro_Slider_Module');