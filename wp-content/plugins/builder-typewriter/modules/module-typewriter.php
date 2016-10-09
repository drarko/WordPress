<?php
/* Exit if accessed directly */
defined( 'ABSPATH' ) or die( '-1' );

/**
 * Module Name: Typewriter
 * Description: Display Typewriter content
 */
class TB_Typewriter extends Themify_Builder_Module {
	
	public function __construct() {
		parent::__construct(
			array(
				'name' => __( 'Typewriter', 'builder-typewriter' ),
				'slug' => 'typewriter'
			)
		);
	}

	public function get_title( $module ) {
		$text = isset( $module['mod_settings']['mod_title_typewriter'] ) ? $module['mod_settings']['mod_title_typewriter'] : '';
		$return = wp_trim_words( $text, 100 );
		return $return;
	}

	public function get_options() {
		$options = array(
			array(
				'id' => 'mod_title_typewriter',
				'type' => 'text',
				'label' => __( 'Module Title', 'themify' ),
				'class' => 'large'
			),
			// Typewriter
			array(
				'id' => 'separator_typewriter',
				'type' => 'separator',
				'meta' => array( 'html' => '<hr /><h4>'. __( 'Typewriter', 'builder-typewriter' ) .'</h4>' ),
			),
			array(
				'id' => 'builder_typewriter_tag',
				'type' => 'select',
				'label' => __( 'Text Tag', 'builder-typewriter' ),
				'options' => array(
					'p' => __( 'p', 'builder-typewriter' ),
					'h1' => __( 'h1', 'builder-typewriter' ),
					'h2' => __( 'h2', 'builder-typewriter' ),
					'h3' => __( 'h3', 'builder-typewriter' ),
					'h4' => __( 'h4', 'builder-typewriter' ),
					'h5' => __( 'h5', 'builder-typewriter' ),
					'h6' => __( 'h6', 'builder-typewriter' ),
				),
			),
			array(
				'id' => 'builder_typewriter_text_before',
				'type' => 'text',
				'label' => __( 'Before Text', 'builder-typewriter' ),
				'class' => 'fullwidth'
			),
			array(
				'id' => 'builder_typewriter_text_after',
				'type' => 'text',
				'label' => __( 'After Text', 'builder-typewriter' ),
				'class' => 'fullwidth'
			),
			array(
				'id' => 'builder_typewriter_terms',
				'type' => 'builder',
				'options' => array(
					array(
						'id' => 'builder_typewriter_term',
						'type' => 'text',
						'label' => __( 'Text', 'builder-typewriter' ),
						'class' => 'large'
					)
				),
				'new_row_text' => __( 'Add New Text', 'builder-typewriter' )
			),
			// Effects
			array(
				'id' => 'separator_effect',
				'type' => 'separator',
				'meta' => array( 'html' => '<hr /><h4>'. __( 'Effects', 'builder-typewriter' ) .'</h4>' ),
			),
			array(
				'id' => 'builder_typewriter_highlight_speed',
				'type' => 'select',
				'label' => __( 'Highlight Speed', 'builder-typewriter' ),
				'default' => 'Normal',
				'options' => array(
					'50' => __( 'Normal', 'builder-typewriter' ),
					'100' => __( 'Slow', 'builder-typewriter' ),
					'25' => __( 'Fast', 'builder-typewriter' ),
				),
			),
			array(
				'id' => 'builder_typewriter_type_speed',
				'type' => 'select',
				'label' => __( 'Type Speed', 'builder-typewriter' ),
				'default' => 'Normal',
				'options' => array(
					'60' => __( 'Normal', 'builder-typewriter' ),
					'120' => __( 'Slow', 'builder-typewriter' ),
					'35' => __( 'Fast', 'builder-typewriter' ),
				),
			),
			array(
				'id' => 'builder_typewriter_clear_delay',
				'type' => 'text',
				'label' => __( 'Clear Delay', 'builder-typewriter' ),
				'class' => 'small',
				'after' => __( 'second(s)', 'builder-typewriter' )
			),
			array(
				'id' => 'builder_typewriter_type_delay',
				'type' => 'text',
				'label' => __( 'Type Delay', 'builder-typewriter' ),
				'class' => 'small',
				'after' => __( 'second(s)', 'builder-typewriter' )
			),
			array(
				'id' => 'builder_typewriter_typer_interval',
				'type' => 'text',
				'label' => __( 'Highlight Delay', 'builder-typewriter' ),
				'class' => 'small',
				'after' => __( 'second(s)', 'builder-typewriter' )
			)
                        ,
			// Additional CSS
			array(
				'type' => 'separator',
				'meta' => array( 'html' => '<hr/>')
			),
			array(
				'id' => 'add_css_text',
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
				'id' => 'multi_Animation Effect',
				'type' => 'multi',
				'label' => __('Effect', 'themify'),
				'fields' => array(
					array(
						'id' => 'animation_effect',
						'type' => 'animation_select',
						'label' => __( 'Effect', 'themify' )
					),
					array(
						'id' => 'animation_effect_delay',
						'type' => 'text',
						'label' => __( 'Delay', 'themify' ),
						'class' => 'xsmall',
						'description' => __( 'Delay (s)', 'themify' ),
					),
					array(
						'id' => 'animation_effect_repeat',
						'type' => 'text',
						'label' => __( 'Repeat', 'themify' ),
						'class' => 'xsmall',
						'description' => __( 'Repeat (x)', 'themify' ),
					),
				)
			)
		);

		return $animation;
	}

	public function get_styling() {
		$general = array(
			// Background
			array(
				'id' => 'separator_image_background',
				'title' => '',
				'description' => '',
				'type' => 'separator',
				'meta' => array('html'=>'<h4>'.__('Background', 'themify').'</h4>'),
			),
			array(
				'id' => 'background_image',
				'type' => 'image',
				'label' => __('Background Image', 'themify'),
				'class' => 'xlarge',
				'prop' => 'background-image',
				'selector' => '.module-typewriter'
			),
			array(
				'id' => 'background_color',
				'type' => 'color',
				'label' => __('Background Color', 'themify'),
				'class' => 'small',
				'prop' => 'background-color',
				'selector' => '.module-typewriter',
			),
			// Background repeat
			array(
				'id' 		=> 'background_repeat',
				'label'		=> __('Background Repeat', 'themify'),
				'type' 		=> 'select',
				'default'	=> '',
				'meta'		=> array(
					array('value' => 'repeat', 'name' => __('Repeat All', 'themify')),
					array('value' => 'repeat-x', 'name' => __('Repeat Horizontally', 'themify')),
					array('value' => 'repeat-y', 'name' => __('Repeat Vertically', 'themify')),
					array('value' => 'repeat-none', 'name' => __('Do not repeat', 'themify')),
					array('value' => 'fullcover', 'name' => __('Fullcover', 'themify'))
				),
				'prop' => 'background-repeat',
				'selector' => '.module-typewriter'
			),
			// Font
			array(
				'type' => 'separator',
				'meta' => array('html'=>'<hr />')
			),
			array(
				'id' => 'separator_font',
				'type' => 'separator',
				'meta' => array('html'=>'<h4>'.__('Font', 'themify').'</h4>'),
			),
			array(
				'id' => 'font_family',
				'type' => 'font_select',
				'label' => __('Font Family', 'themify'),
				'class' => 'font-family-select',
				'prop' => 'font-family',
				'selector' => array( '.module-typewriter', '.module-typewriter p', '.module-typewriter h1', '.module-typewriter h2', '.module-typewriter h3:not(.module-title)', '.module-typewriter h4', '.module-typewriter h5', '.module-typewriter h6' )
			),
			array(
				'id' => 'font_color',
				'type' => 'color',
				'label' => __('Font Color', 'themify'),
				'class' => 'small',
				'prop' => 'color',
				'selector' => array( '.module-typewriter', '.module-typewriter p', '.module-typewriter h1', '.module-typewriter h2', '.module-typewriter h3:not(.module-title)', '.module-typewriter h4', '.module-typewriter h5', '.module-typewriter h6' ),
			),
			array(
				'id' => 'multi_font_size',
				'type' => 'multi',
				'label' => __('Font Size', 'themify'),
				'fields' => array(
					array(
						'id' => 'font_size',
						'type' => 'text',
						'class' => 'xsmall',
						'prop' => 'font-size',
						'selector' => array( '.module-typewriter', '.module-typewriter p', '.module-typewriter h1', '.module-typewriter h2', '.module-typewriter h3:not(.module-title)', '.module-typewriter h4', '.module-typewriter h5', '.module-typewriter h6' ),
					),
					array(
						'id' => 'font_size_unit',
						'type' => 'select',
						'meta' => array(
							array('value' => '', 'name' => ''),
							array('value' => 'px', 'name' => __('px', 'themify')),
							array('value' => 'em', 'name' => __('em', 'themify'))
						)
					)
				),
			),
			array(
				'id' => 'multi_line_height',
				'type' => 'multi',
				'label' => __('Line Height', 'themify'),
				'fields' => array(
					array(
						'id' => 'line_height',
						'type' => 'text',
						'class' => 'xsmall',
						'prop' => 'line-height',
						'selector' => array( '.module-typewriter', '.module-typewriter p', '.module-typewriter h1', '.module-typewriter h2', '.module-typewriter h3:not(.module-title)', '.module-typewriter h4', '.module-typewriter h5', '.module-typewriter h6' ),
					),
					array(
						'id' => 'line_height_unit',
						'type' => 'select',
						'meta' => array(
							array('value' => '', 'name' => ''),
							array('value' => 'px', 'name' => __('px', 'themify')),
							array('value' => 'em', 'name' => __('em', 'themify')),
							array('value' => '%', 'name' => __('%', 'themify'))
						)
					)
				)
			),
			array(
				'id' => 'text_align',
				'label' => __( 'Text Align', 'themify' ),
				'type' => 'radio',
				'meta' => array(
					array( 'value' => '', 'name' => __( 'Default', 'themify' ), 'selected' => true ),
					array( 'value' => 'left', 'name' => __( 'Left', 'themify' ) ),
					array( 'value' => 'center', 'name' => __( 'Center', 'themify' ) ),
					array( 'value' => 'right', 'name' => __( 'Right', 'themify' ) ),
					array( 'value' => 'justify', 'name' => __( 'Justify', 'themify' ) )
				),
				'prop' => 'text-align',
				'selector' => '.module-typewriter'
			),
			// Padding
			array(
				'type' => 'separator',
				'meta' => array('html'=>'<hr />')
			),
			array(
				'id' => 'separator_padding',
				'type' => 'separator',
				'meta' => array('html'=>'<h4>'.__('Padding', 'themify').'</h4>'),
			),
			array(
				'id' => 'multi_padding_top',
				'type' => 'multi',
				'label' => __('Padding', 'themify'),
				'fields' => array(
					array(
						'id' => 'padding_top',
						'type' => 'text',
						'class' => 'style_padding style_field xsmall',
						'prop' => 'padding-top',
						'selector' => '.module-typewriter',
					),
					array(
						'id' => 'padding_top_unit',
						'type' => 'select',
						'description' => __('top', 'themify'),
						'meta' => array(
							array('value' => 'px', 'name' => __('px', 'themify')),
							array('value' => '%', 'name' => __('%', 'themify'))
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
						'class' => 'style_padding style_field xsmall',
						'prop' => 'padding-right',
						'selector' => '.module-typewriter',
					),
					array(
						'id' => 'padding_right_unit',
						'type' => 'select',
						'description' => __('right', 'themify'),
						'meta' => array(
							array('value' => 'px', 'name' => __('px', 'themify')),
							array('value' => '%', 'name' => __('%', 'themify'))
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
						'class' => 'style_padding style_field xsmall',
						'prop' => 'padding-bottom',
						'selector' => '.module-typewriter',
					),
					array(
						'id' => 'padding_bottom_unit',
						'type' => 'select',
						'description' => __('bottom', 'themify'),
						'meta' => array(
							array('value' => 'px', 'name' => __('px', 'themify')),
							array('value' => '%', 'name' => __('%', 'themify'))
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
						'class' => 'style_padding style_field xsmall',
						'prop' => 'padding-left',
						'selector' => '.module-typewriter',
					),
					array(
						'id' => 'padding_left_unit',
						'type' => 'select',
						'description' => __('left', 'themify'),
						'meta' => array(
							array('value' => 'px', 'name' => __('px', 'themify')),
							array('value' => '%', 'name' => __('%', 'themify'))
						)
					),
				)
			),
			// "Apply all" // apply all padding
			array(
				'id' => 'checkbox_padding_apply_all',
				'class' => 'style_apply_all style_apply_all_padding',
				'type' => 'checkbox',
				'label' => false,
				'options' => array(
					array( 'name' => 'padding', 'value' => __( 'Apply to all padding', 'themify' ) )
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
				'meta' => array('html'=>'<h4>'.__('Margin', 'themify').'</h4>'),
			),
			array(
				'id' => 'multi_margin_top',
				'type' => 'multi',
				'label' => __('Margin', 'themify'),
				'fields' => array(
					array(
						'id' => 'margin_top',
						'type' => 'text',
						'class' => 'style_margin style_field xsmall',
						'prop' => 'margin-top',
						'selector' => '.module-typewriter',
					),
					array(
						'id' => 'margin_top_unit',
						'type' => 'select',
						'description' => __('top', 'themify'),
						'meta' => array(
							array('value' => 'px', 'name' => __('px', 'themify')),
							array('value' => '%', 'name' => __('%', 'themify'))
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
						'class' => 'style_margin style_field xsmall',
						'prop' => 'margin-right',
						'selector' => '.module-typewriter',
					),
					array(
						'id' => 'margin_right_unit',
						'type' => 'select',
						'description' => __('right', 'themify'),
						'meta' => array(
							array('value' => 'px', 'name' => __('px', 'themify')),
							array('value' => '%', 'name' => __('%', 'themify'))
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
						'class' => 'style_margin style_field xsmall',
						'prop' => 'margin-bottom',
						'selector' => '.module-typewriter',
					),
					array(
						'id' => 'margin_bottom_unit',
						'type' => 'select',
						'description' => __('bottom', 'themify'),
						'meta' => array(
							array('value' => 'px', 'name' => __('px', 'themify')),
							array('value' => '%', 'name' => __('%', 'themify'))
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
						'class' => 'style_margin style_field xsmall',
						'prop' => 'margin-left',
						'selector' => '.module-typewriter',
					),
					array(
						'id' => 'margin_left_unit',
						'type' => 'select',
						'description' => __('left', 'themify'),
						'meta' => array(
							array('value' => 'px', 'name' => __('px', 'themify')),
							array('value' => '%', 'name' => __('%', 'themify'))
						)
					),
				)
			),
			// "Apply all" // apply all margin
			array(
				'id' => 'checkbox_margin_apply_all',
				'class' => 'style_apply_all style_apply_all_margin',
				'type' => 'checkbox',
				'label' => false,
				'options' => array(
					array( 'name' => 'margin', 'value' => __( 'Apply to all margin', 'themify' ) )
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
				'meta' => array('html'=>'<h4>'.__('Border', 'themify').'</h4>'),
			),
			array(
				'id' => 'multi_border_top',
				'type' => 'multi',
				'label' => __('Border', 'themify'),
				'fields' => array(
					array(
						'id' => 'border_top_color',
						'type' => 'color',
						'class' => 'small',
						'prop' => 'border-top-color',
						'selector' => '.module-typewriter',
					),
					array(
						'id' => 'border_top_width',
						'type' => 'text',
						'description' => 'px',
						'class' => 'style_border style_field xsmall',
						'prop' => 'border-top-width',
						'selector' => '.module-typewriter',
					),
					array(
						'id' => 'border_top_style',
						'type' => 'select',
						'description' => __('top', 'themify'),
						'meta' => Themify_Builder_model::get_border_styles(),
						'prop' => 'border-top-style',
						'selector' => '.module-typewriter',
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
						'selector' => '.module-typewriter',
					),
					array(
						'id' => 'border_right_width',
						'type' => 'text',
						'description' => 'px',
						'class' => 'style_border style_field xsmall',
						'prop' => 'border-right-width',
						'selector' => '.module-typewriter',
					),
					array(
						'id' => 'border_right_style',
						'type' => 'select',
						'description' => __('right', 'themify'),
						'meta' => Themify_Builder_model::get_border_styles(),
						'prop' => 'border-right-style',
						'selector' => '.module-typewriter',
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
						'selector' => '.module-typewriter',
					),
					array(
						'id' => 'border_bottom_width',
						'type' => 'text',
						'description' => 'px',
						'class' => 'style_border style_field xsmall',
						'prop' => 'border-bottom-width',
						'selector' => '.module-typewriter',
					),
					array(
						'id' => 'border_bottom_style',
						'type' => 'select',
						'description' => __('bottom', 'themify'),
						'meta' => Themify_Builder_model::get_border_styles(),
						'prop' => 'border-bottom-style',
						'selector' => '.module-typewriter',
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
						'selector' => '.module-typewriter',
					),
					array(
						'id' => 'border_left_width',
						'type' => 'text',
						'description' => 'px',
						'class' => 'style_border style_field xsmall',
						'prop' => 'border-left-width',
						'selector' => '.module-typewriter',
					),
					array(
						'id' => 'border_left_style',
						'type' => 'select',
						'description' => __('left', 'themify'),
						'meta' => Themify_Builder_model::get_border_styles(),
						'prop' => 'border-left-style',
						'selector' => '.module-typewriter',
					)
				)
			),
			// "Apply all" // apply all border
			array(
				'id' => 'checkbox_border_apply_all',
				'class' => 'style_apply_all style_apply_all_border',
				'type' => 'checkbox',
				'label' => false,
				'options' => array(
					array( 'name' => 'border', 'value' => __( 'Apply to all border', 'themify' ) )
				)
			)
		);

		$typewriter = array(
			// Background
			array(
				'id' => 'separator_image_background_span',
				'title' => '',
				'description' => '',
				'type' => 'separator',
				'meta' => array( 'html' => '<h4>'. __( 'Background', 'builder-typewriter' ). '</h4>' ),
			),
			array(
				'id' => 'span_background_color',
				'type' => 'color',
				'label' => __( 'Background Color (Highlighted)', 'builder-typewriter' ),
				'class' => 'small',
			),
			// Font
			array(
				'type' => 'separator',
				'meta' => array( 'html' => '<hr />' )
			),
			array(
				'id' => 'separator_font_span',
				'type' => 'separator',
				'meta' => array( 'html' => '<h4>'. __( 'Font', 'builder-typewriter' ) .'</h4>' ),
			),
			array(
				'id' => 'span_font_color',
				'type' => 'color',
				'label' => __( 'Font Color (Highlighted)', 'builder-typewriter' ),
				'class' => 'small',
			),
			// Padding
			array(
				'type' => 'separator',
				'meta' => array('html'=>'<hr />')
			),
			array(
				'id' => 'separator_padding',
				'type' => 'separator',
				'meta' => array('html'=>'<h4>'.__('Padding', 'builder-typewriter').'</h4>'),
			),
			array(
				'id' => 'span_multi_padding_top',
				'type' => 'multi',
				'label' => __('Padding (Highlighted)', 'builder-typewriter'),
				'fields' => array(
					array(
						'id' => 'span_padding_top',
						'type' => 'text',
						'class' => 'style_padding style_field xsmall',
					),
					array(
						'id' => 'span_padding_top_unit',
						'type' => 'select',
						'description' => __('top', 'builder-typewriter'),
						'meta' => array(
							array('value' => 'px', 'name' => __('px', 'builder-typewriter')),
							array('value' => '%', 'name' => __('%', 'builder-typewriter'))
						)
					),
				)
			),
			array(
				'id' => 'span_multi_padding_right',
				'type' => 'multi',
				'label' => '',
				'fields' => array(
					array(
						'id' => 'span_padding_right',
						'type' => 'text',
						'class' => 'style_padding style_field xsmall',
					),
					array(
						'id' => 'span_padding_right_unit',
						'type' => 'select',
						'description' => __('right', 'builder-typewriter'),
						'meta' => array(
							array('value' => 'px', 'name' => __('px', 'builder-typewriter')),
							array('value' => '%', 'name' => __('%', 'builder-typewriter'))
						)
					),
				)
			),
			array(
				'id' => 'span_multi_padding_bottom',
				'type' => 'multi',
				'label' => '',
				'fields' => array(
					array(
						'id' => 'span_padding_bottom',
						'type' => 'text',
						'class' => 'style_padding style_field xsmall',
					),
					array(
						'id' => 'span_padding_bottom_unit',
						'type' => 'select',
						'description' => __('bottom', 'builder-typewriter'),
						'meta' => array(
							array('value' => 'px', 'name' => __('px', 'builder-typewriter')),
							array('value' => '%', 'name' => __('%', 'builder-typewriter'))
						)
					),
				)
			),
			array(
				'id' => 'span_multi_padding_left',
				'type' => 'multi',
				'label' => '',
				'fields' => array(
					array(
						'id' => 'span_padding_left',
						'type' => 'text',
						'class' => 'style_padding style_field xsmall',
					),
					array(
						'id' => 'span_padding_left_unit',
						'type' => 'select',
						'description' => __('left', 'builder-typewriter'),
						'meta' => array(
							array('value' => 'px', 'name' => __('px', 'builder-typewriter')),
							array('value' => '%', 'name' => __('%', 'builder-typewriter'))
						)
					),
				)
			),
			// "Apply all" // apply all padding
			array(
				'id' => 'checkbox_padding_apply_all_span',
				'class' => 'style_apply_all style_apply_all_padding',
				'type' => 'checkbox',
				'label' => false,
				'options' => array(
					array( 'name' => 'padding', 'value' => __( 'Apply to all padding', 'builder-typewriter' ) )
				)
			),
			// Border
			array(
				'type' => 'separator',
				'meta' => array('html'=>'<hr />')
			),
			array(
				'id' => 'separator_border_span',
				'type' => 'separator',
				'meta' => array('html'=>'<h4>'.__('Border', 'builder-typewriter').'</h4>'),
			),
			array(
				'id' => 'span_multi_border_top',
				'type' => 'multi',
				'label' => __('Border (Highlighted)', 'builder-typewriter'),
				'fields' => array(
					array(
						'id' => 'span_border_top_color',
						'type' => 'color',
						'class' => 'small',
					),
					array(
						'id' => 'span_border_top_width',
						'type' => 'text',
						'description' => 'px',
						'class' => 'style_border style_field xsmall',
					),
					array(
						'id' => 'span_border_top_style',
						'type' => 'select',
						'description' => __('top', 'builder-typewriter'),
						'meta' => Themify_Builder_model::get_border_styles(),
					),
				)
			),
			array(
				'id' => 'span_multi_border_right',
				'type' => 'multi',
				'label' => '',
				'fields' => array(
					array(
						'id' => 'span_border_right_color',
						'type' => 'color',
						'class' => 'small',
					),
					array(
						'id' => 'span_border_right_width',
						'type' => 'text',
						'description' => 'px',
						'class' => 'style_border style_field xsmall',
					),
					array(
						'id' => 'span_border_right_style',
						'type' => 'select',
						'description' => __('right', 'builder-typewriter'),
						'meta' => Themify_Builder_model::get_border_styles(),
					)
				)
			),
			array(
				'id' => 'span_multi_border_bottom',
				'type' => 'multi',
				'label' => '',
				'fields' => array(
					array(
						'id' => 'span_border_bottom_color',
						'type' => 'color',
						'class' => 'small',
					),
					array(
						'id' => 'span_border_bottom_width',
						'type' => 'text',
						'description' => 'px',
						'class' => 'style_border style_field xsmall',
					),
					array(
						'id' => 'span_border_bottom_style',
						'type' => 'select',
						'description' => __('bottom', 'builder-typewriter'),
						'meta' => Themify_Builder_model::get_border_styles(),
					)
				)
			),
			array(
				'id' => 'span_multi_border_left',
				'type' => 'multi',
				'label' => '',
				'fields' => array(
					array(
						'id' => 'span_border_left_color',
						'type' => 'color',
						'class' => 'small',
					),
					array(
						'id' => 'span_border_left_width',
						'type' => 'text',
						'description' => 'px',
						'class' => 'style_border style_field xsmall',
					),
					array(
						'id' => 'span_border_left_style',
						'type' => 'select',
						'description' => __('left', 'builder-typewriter'),
						'meta' => Themify_Builder_model::get_border_styles(),
					)
				)
			),
			// "Apply all" // apply all border
			array(
				'id' => 'checkbox_border_apply_all_span',
				'class' => 'style_apply_all style_apply_all_border',
				'type' => 'checkbox',
				'label' => false,
				'options' => array(
					array( 'name' => 'border', 'value' => __( 'Apply to all border', 'builder-typewriter' ) )
				)
			)
		);

		return array(
			array(
				'type' => 'tabs',
				'id' => 'module-styling',
				'tabs' => array(
					'general' => array(
		        	'label' => __( 'General', 'themify' ),
					'fields' => $general
					),
					'typewriter' => array(
						'label' => __( 'Typewriter', 'builder-typewriter' ),
						'fields' => $typewriter
					)
				)
			),
		);
	}

}

Themify_Builder_Model::register_module( 'TB_Typewriter' );
