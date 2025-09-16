<?php

/**
 * @class FLContentSliderModule
 */
class FLContentSliderModule extends FLBuilderModule {

	/**
	 * @method __construct
	 */
	public function __construct() {
		parent::__construct(array(
			'name'            => __( 'Content Slider', 'bb-booster' ),
			'description'     => __( 'Displays multiple slides with an optional heading and call to action.', 'bb-booster' ),
			'category'        => __( 'Media', 'bb-booster' ),
			'partial_refresh' => true,
			'icon'            => 'slides.svg',
		));

		$this->add_css( 'jquery-bxslider' );
		$this->add_js( 'jquery-bxslider' );
	}

	/**
	 * Ensure backwards compatibility with old settings.
	 *
	 * @since 2.2
	 * @param object $settings A module settings object.
	 * @param object $helper A settings compatibility helper.
	 * @return object
	 */
	public function filter_settings( $settings, $helper ) {

		// Handle slide settings.
		for ( $i = 0; $i < count( $settings->slides ); $i++ ) {

			if ( ! is_object( $settings->slides[ $i ] ) ) {
				continue;
			}

			// Old slide text bg opacity.
			$helper->handle_opacity_inputs( $settings->slides[ $i ], 'text_bg_opacity', 'text_bg_color' );

			// Handle old button module settings.
			$helper->filter_child_module_settings( 'button', $settings->slides[ $i ], array(
				'btn_3d'                 => 'three_d',
				'btn_style'              => 'style',
				'btn_padding'            => 'padding',
				'btn_padding_top'        => 'padding_top',
				'btn_padding_bottom'     => 'padding_bottom',
				'btn_padding_left'       => 'padding_left',
				'btn_padding_right'      => 'padding_right',
				'btn_font_size'          => 'font_size',
				'btn_font_size_unit'     => 'font_size_unit',
				'btn_typography'         => 'typography',
				'btn_bg_color'           => 'bg_color',
				'btn_bg_hover_color'     => 'bg_hover_color',
				'btn_bg_opacity'         => 'bg_opacity',
				'btn_bg_hover_opacity'   => 'bg_hover_opacity',
				'btn_border'             => 'border',
				'btn_border_hover_color' => 'border_hover_color',
				'btn_border_radius'      => 'border_radius',
				'btn_border_size'        => 'border_size',
			) );
		}

		return $settings;
	}

	/**
	 * @method render_background
	 */
	public function render_background( $slide ) {
		// Background photo
		if ( 'photo' == $slide->bg_layout && ! empty( $slide->bg_photo_src ) ) {
			echo '<div class="fl-slide-bg-photo"></div>';
		} elseif ( 'video' == $slide->bg_layout && ! empty( $slide->bg_video ) ) {
			echo '<div class="fl-slide-bg-video">' . $slide->bg_video . '</div>';
		}

		// Background link
		if ( ! empty( $slide->link ) && ( 'photo' == $slide->bg_layout || 'color' == $slide->bg_layout ) ) {
			echo '<a class="fl-slide-bg-link" href="' . esc_attr( $slide->link ) . '" target="' . $slide->link_target . '" aria-label="' . esc_attr( $slide->title ) . '"></a>';
		}
	}

	/**
	 * @method render_content
	 */
	public function render_content( $slide ) {
		global $wp_embed;

		if ( 'none' == $slide->content_layout || 'video' == $slide->bg_layout ) {
			return;
		}

		echo '<div class="fl-slide-content-wrap">';
		echo '<div class="fl-slide-content">';

		if ( ! empty( $slide->title ) ) {
			echo '<' . $slide->title_tag . ' class="fl-slide-title">' . $slide->title . '</' . $slide->title_tag . '>';
		}
		if ( ! empty( $slide->text ) ) {
			echo '<div class="fl-slide-text">' . wpautop( $wp_embed->autoembed( $slide->text ) ) . $this->render_link( $slide ) . '</div>';
		}

		$this->render_button( $slide );

		echo '</div>';
		echo '</div>';
	}

	/**
	 * @method render_media
	 */
	public function render_media( $slide ) {
		if ( 'none' == $slide->content_layout || 'video' == $slide->bg_layout ) {
			return;
		}

		// Photo
		if ( 'photo' == $slide->content_layout && ! empty( $slide->fg_photo_src ) ) {

			$alt = get_post_meta( $slide->fg_photo, '_wp_attachment_image_alt', true );

			echo '<div class="fl-slide-photo-wrap">';
			echo '<div class="fl-slide-photo">';

			if ( ! empty( $slide->link ) ) {
				echo '<a href="' . $slide->link . '" target="' . $slide->link_target . '">';
			}

			printf( '<img %s class="fl-slide-photo-img wp-image-%s" src="%s" alt="%s" />', FLBuilderUtils::img_lazyload( 'false' ), $slide->fg_photo, $slide->fg_photo_src, esc_attr( $alt ) );

			if ( ! empty( $slide->link ) ) {
				echo '</a>';
			}

			echo '</div>';
			echo '</div>';
		} elseif ( 'video' == $slide->content_layout && ! empty( $slide->fg_video ) ) {
			echo '<div class="fl-slide-photo-wrap">';
			echo '<div class="fl-slide-photo">' . $slide->fg_video . '</div>';
			echo '</div>';
		}
	}

	/**
	 * @method render_mobile_media
	 */
	public function render_mobile_media( $slide ) {
		if ( 'video' == $slide->bg_layout ) {
			return;
		}

		// Photo
		if ( 'photo' == $slide->content_layout ) {

			$src = '';
			$alt = '';

			if ( 'main' == $slide->r_photo_type && ! empty( $slide->fg_photo_src ) ) {
				$id  = $slide->fg_photo;
				$src = $slide->fg_photo_src;
				$alt = get_post_meta( $slide->bg_photo, '_wp_attachment_image_alt', true );
			} elseif ( 'another' == $slide->r_photo_type && ! empty( $slide->r_photo_src ) ) {
				$id  = $slide->r_photo;
				$src = $slide->r_photo_src;
				$alt = get_post_meta( $slide->r_photo, '_wp_attachment_image_alt', true );
			}

			if ( ! empty( $src ) ) {
				echo '<div class="fl-slide-mobile-photo">';
				printf( '<img %s class="fl-slide-mobile-photo-img wp-image-%s" src="%s" alt="%s" />', FLBuilderUtils::img_lazyload( 'false' ), $id, $src, esc_attr( $alt ) );
				echo '</div>';
			}
		} elseif ( 'video' == $slide->content_layout && ! empty( $slide->fg_video ) ) {
			echo '<div class="fl-slide-mobile-photo">' . $slide->fg_video . '</div>';
		} elseif ( 'photo' == $slide->bg_layout ) { // BG Photo

			$src = '';
			$alt = '';

			if ( 'main' == $slide->r_photo_type && ! empty( $slide->bg_photo_src ) ) {
				$id  = $slide->bg_photo;
				$src = $slide->bg_photo_src;
				$alt = get_post_meta( $slide->bg_photo, '_wp_attachment_image_alt', true );
			} elseif ( 'another' == $slide->r_photo_type && ! empty( $slide->r_photo_src ) ) {
				$id  = $slide->r_photo;
				$src = $slide->r_photo_src;
				$alt = get_post_meta( $slide->r_photo, '_wp_attachment_image_alt', true );
			}

			if ( ! empty( $src ) ) {
				echo '<div class="fl-slide-mobile-photo">';
				printf( '<img %s class="fl-slide-mobile-photo-img wp-image-%s" src="%s" alt="%s" />', FLBuilderUtils::img_lazyload( 'false' ), $id, $src, esc_attr( $alt ) );
				echo '</div>';
			}
		}
	}

	/**
	 * @method render_link
	 */
	public function render_link( $slide ) {
		if ( 'link' == $slide->cta_type ) {
			return '<a href="' . $slide->link . '" target="' . $slide->link_target . '" class="fl-slide-cta-link">' . $slide->cta_text . '</a>';
		}
	}

	/**
	 * Returns an array of settings used to render a button module.
	 *
	 * @since 2.2
	 * @param object $slide
	 * @return array
	 */
	public function get_button_settings( $slide ) {
		$settings = array(
			'link'          => $slide->link,
			'link_nofollow' => isset( $slide->link_nofollow ) ? $slide->link_nofollow : 'no',
			'link_target'   => $slide->link_target,
			'text'          => $slide->cta_text,
			'width'         => 'auto',
		);

		foreach ( $slide as $key => $value ) {
			if ( strstr( $key, 'btn_' ) ) {
				$key              = str_replace( 'btn_', '', $key );
				$settings[ $key ] = $value;
			}
		}

		return $settings;
	}

	/**
	 * @method render_button
	 */
	public function render_button( $slide ) {
		if ( 'button' == $slide->cta_type ) {
			echo '<div class="fl-slide-cta-button">';
			FLBuilder::render_module_html( 'button', $this->get_button_settings( $slide ) );
			echo '</div>';
		}
	}

	/**
	 * @method is_loop_enabled
	 */
	public function is_loop_enabled() {
		if ( 'true' == $this->settings->loop &&
			1 == count( $this->settings->slides ) &&
			'video' == $this->settings->slides[0]->bg_layout
			) {
			return 'false';
		} else {
			return $this->settings->loop;
		}
	}
}

/**
 * Register the module and its form settings.
 */
FLBuilder::register_module('FLContentSliderModule', array(
	'slides'  => array(
		'title'    => __( 'Slides', 'bb-booster' ),
		'sections' => array(
			'general' => array(
				'title'  => '',
				'fields' => array(
					'slides' => array(
						'type'         => 'form',
						'label'        => __( 'Slide', 'bb-booster' ),
						'form'         => 'content_slider_slide', // ID from registered form below
						'preview_text' => 'label', // Name of a field to use for the preview text
						'multiple'     => true,
					),
				),
			),
		),
	),
	'general' => array(
		'title'    => __( 'Slider', 'bb-booster' ),
		'sections' => array(
			'general' => array(
				'title'  => '',
				'fields' => array(
					'auto_play'  => array(
						'type'    => 'select',
						'label'   => __( 'Auto Play', 'bb-booster' ),
						'default' => '1',
						'options' => array(
							'0' => __( 'No', 'bb-booster' ),
							'1' => __( 'Yes', 'bb-booster' ),
						),
						'toggle'  => array(
							'1' => array(
								'fields' => array( 'play_pause' ),
							),
						),
					),
					'shuffle'    => array(
						'type'    => 'select',
						'label'   => __( 'Shuffle', 'bb-booster' ),
						'default' => '0',
						'options' => array(
							'0' => __( 'No', 'bb-booster' ),
							'1' => __( 'Yes', 'bb-booster' ),
						),
					),
					'auto_hover' => array(
						'type'    => 'select',
						'label'   => __( 'Pause On Hover', 'bb-booster' ),
						'default' => '1',
						'options' => array(
							'0' => __( 'No', 'bb-booster' ),
							'1' => __( 'Yes', 'bb-booster' ),
						),
					),
					'loop'       => array(
						'type'    => 'select',
						'label'   => __( 'Loop', 'bb-booster' ),
						'default' => 'true',
						'options' => array(
							'false' => __( 'No', 'bb-booster' ),
							'true'  => __( 'Yes', 'bb-booster' ),
						),
					),
					'delay'      => array(
						'type'     => 'unit',
						'label'    => __( 'Delay', 'bb-booster' ),
						'default'  => '5',
						'sanitize' => 'absint',
						'units'    => array( 'seconds' ),
						'slider'   => array(
							'max'  => 10,
							'step' => .5,
						),
						'help'     => __( 'Delay should be greater than the Transition Speed.', 'bb-booster' ),
					),
					'transition' => array(
						'type'    => 'select',
						'label'   => __( 'Transition', 'bb-booster' ),
						'default' => 'slide',
						'options' => array(
							'horizontal' => _x( 'Slide', 'Transition type.', 'bb-booster' ),
							'fade'       => __( 'Fade', 'bb-booster' ),
						),
					),
					'speed'      => array(
						'type'     => 'unit',
						'label'    => __( 'Transition Speed', 'bb-booster' ),
						'default'  => '0.5',
						'sanitize' => 'FLBuilderUtils::sanitize_non_negative_number',
						'units'    => array( 'seconds' ),
						'slider'   => array(
							'max'  => 10,
							'step' => .5,
						),
						'help'     => __( 'Transition Speed should be less than the Delay value.', 'bb-booster' ),
					),
					'play_pause' => array(
						'type'    => 'select',
						'label'   => __( 'Show Play/Pause', 'bb-booster' ),
						'default' => '0',
						'options' => array(
							'0' => __( 'No', 'bb-booster' ),
							'1' => __( 'Yes', 'bb-booster' ),
						),
					),
					'arrows'     => array(
						'type'    => 'select',
						'label'   => __( 'Show Arrows', 'bb-booster' ),
						'default' => '0',
						'options' => array(
							'0' => __( 'No', 'bb-booster' ),
							'1' => __( 'Yes', 'bb-booster' ),
						),
						'toggle'  => array(
							'1' => array(
								'sections' => array( 'arrows' ),
							),
						),
					),
					'dots'       => array(
						'type'    => 'select',
						'label'   => __( 'Show Dots', 'bb-booster' ),
						'default' => '1',
						'options' => array(
							'0' => __( 'No', 'bb-booster' ),
							'1' => __( 'Yes', 'bb-booster' ),
						),
					),
				),
			),
		),
	),
	'styles'  => array(
		'title'    => __( 'Style', 'bb-booster' ),
		'sections' => array(
			'general' => array(
				'title'  => '',
				'fields' => array(
					'height'    => array(
						'type'     => 'unit',
						'label'    => __( 'Height', 'bb-booster' ),
						'default'  => '400',
						'sanitize' => 'absint',
						'units'    => array( 'px' ),
						'slider'   => array(
							'max'  => 600,
							'step' => 10,
						),
						'help'     => __( 'This setting is the minimum height of the content slider. Content will expand the height automatically.', 'bb-booster' ),
					),
					'max_width' => array(
						'type'     => 'unit',
						'label'    => __( 'Max Content Width', 'bb-booster' ),
						'default'  => '1100',
						'sanitize' => 'absint',
						'units'    => array( 'px' ),
						'slider'   => array(
							'max'  => 1100,
							'step' => 10,
						),
						'help'     => __( 'The max width that the content area will be within your slides.', 'bb-booster' ),
					),
				),
			),
			'arrows'  => array(
				'title'  => __( 'Arrows', 'bb-booster' ),
				'fields' => array(
					'arrows_bg_color'   => array(
						'type'        => 'color',
						'connections' => array( 'color' ),
						'label'       => __( 'Arrows Background Color', 'bb-booster' ),
						'show_reset'  => true,
						'show_alpha'  => true,
					),
					'arrows_bg_style'   => array(
						'type'    => 'select',
						'label'   => __( 'Arrows Background Style', 'bb-booster' ),
						'default' => 'circle',
						'options' => array(
							'circle' => __( 'Circle', 'bb-booster' ),
							'square' => __( 'Square', 'bb-booster' ),
						),
					),
					'arrows_text_color' => array(
						'type'        => 'color',
						'connections' => array( 'color' ),
						'label'       => __( 'Arrows Color', 'bb-booster' ),
						'show_reset'  => true,
						'show_alpha'  => true,
						'preview'     => array(
							'type'     => 'css',
							'selector' => '.fl-content-slider-navigation path',
							'property' => 'fill',
						),
					),
				),
			),
		),
	),
));

/**
 * Register the slide settings form.
 */
FLBuilder::register_settings_form('content_slider_slide', array(
	'title' => __( 'Slide Settings', 'bb-booster' ),
	'tabs'  => array(
		'general' => array( // Tab
			'title'    => __( 'General', 'bb-booster' ), // Tab title
			'sections' => array( // Tab Sections
				'general'    => array(
					'title'  => '',
					'fields' => array(
						'label' => array(
							'type'  => 'text',
							'label' => __( 'Slide Label', 'bb-booster' ),
							'help'  => __( 'A label to identify this slide on the Slides tab of the Content Slider settings.', 'bb-booster' ),
						),
					),
				),
				'background' => array(
					'title'  => __( 'Background Layout', 'bb-booster' ),
					'fields' => array(
						'bg_layout'              => array(
							'type'    => 'select',
							'label'   => __( 'Type', 'bb-booster' ),
							'default' => 'photo',
							'help'    => __( 'This setting is for the entire background of your slide.', 'bb-booster' ),
							'options' => array(
								'photo' => __( 'Photo', 'bb-booster' ),
								'video' => __( 'Video', 'bb-booster' ),
								'color' => __( 'Color', 'bb-booster' ),
								'none'  => _x( 'None', 'Background type.', 'bb-booster' ),
							),
							'toggle'  => array(
								'photo' => array(
									'fields'   => array( 'bg_photo', 'bg_photo_overlay_color' ),
									'sections' => array( 'content', 'text' ),
								),
								'color' => array(
									'fields'   => array( 'bg_color' ),
									'sections' => array( 'content', 'text' ),
								),
								'video' => array(
									'fields' => array( 'bg_video' ),
								),
								'none'  => array(
									'sections' => array( 'content', 'text' ),
								),
							),
						),
						'bg_photo'               => array(
							'type'        => 'photo',
							'show_remove' => true,
							'label'       => __( 'Background Photo', 'bb-booster' ),
						),
						'bg_photo_overlay_color' => array(
							'type'        => 'color',
							'connections' => array( 'color' ),
							'label'       => __( 'Overlay Color', 'bb-booster' ),
							'show_reset'  => true,
							'show_alpha'  => true,
						),
						'bg_color'               => array(
							'type'        => 'color',
							'connections' => array( 'color' ),
							'label'       => __( 'Background Color', 'bb-booster' ),
							'show_reset'  => true,
							'show_alpha'  => true,
						),
						'bg_video'               => array(
							'type'  => 'textarea',
							'label' => __( 'Background Video Code', 'bb-booster' ),
							'rows'  => '6',
						),
					),
				),
				'content'    => array(
					'title'  => __( 'Content Layout', 'bb-booster' ),
					'fields' => array(
						'content_layout' => array(
							'type'    => 'select',
							'label'   => __( 'Type', 'bb-booster' ),
							'default' => 'none',
							'help'    => __( 'This allows you to add content over or in addition to the background selection above. The location of the content layout can be selected in the style tab.', 'bb-booster' ),
							'options' => array(
								'text'  => __( 'Text', 'bb-booster' ),
								'photo' => __( 'Text &amp; Photo', 'bb-booster' ),
								'video' => __( 'Text &amp; Video', 'bb-booster' ),
								'none'  => _x( 'None', 'Content type.', 'bb-booster' ),
							),
							'toggle'  => array(
								'text'  => array(
									'fields'   => array( 'title', 'text' ),
									'sections' => array( 'text' ),
								),
								'photo' => array(
									'fields'   => array( 'title', 'text', 'fg_photo' ),
									'sections' => array( 'text' ),
								),
								'video' => array(
									'fields'   => array( 'title', 'text', 'fg_video' ),
									'sections' => array( 'text' ),
								),
							),
						),
						'fg_photo'       => array(
							'type'        => 'photo',
							'show_remove' => true,
							'label'       => __( 'Photo', 'bb-booster' ),
						),
						'fg_video'       => array(
							'type'  => 'textarea',
							'label' => __( 'Video Embed Code', 'bb-booster' ),
							'rows'  => '6',
						),
						'title'          => array(
							'type'  => 'text',
							'label' => __( 'Heading', 'bb-booster' ),
						),
						'text'           => array(
							'type'          => 'editor',
							'media_buttons' => false,
							'wpautop'       => false,
							'rows'          => 16,
						),
					),
				),
			),
		),
		'style'   => array( // Tab
			'title'    => __( 'Style', 'bb-booster' ), // Tab title
			'sections' => array( // Tab Sections
				'title'         => array(
					'title'  => __( 'Heading', 'bb-booster' ),
					'fields' => array(
						'title_tag'         => array(
							'type'    => 'select',
							'label'   => __( 'Heading Tag', 'bb-booster' ),
							'default' => 'h2',
							'options' => array(
								'h1' => 'h1',
								'h2' => 'h2',
								'h3' => 'h3',
								'h4' => 'h4',
								'h5' => 'h5',
								'h6' => 'h6',
							),
						),
						'title_size'        => array(
							'type'    => 'select',
							'label'   => __( 'Heading Size', 'bb-booster' ),
							'default' => 'default',
							'options' => array(
								'default' => __( 'Default', 'bb-booster' ),
								'custom'  => __( 'Custom', 'bb-booster' ),
							),
							'toggle'  => array(
								'custom' => array(
									'fields' => array( 'title_custom_size' ),
								),
							),
						),
						'title_custom_size' => array(
							'type'    => 'unit',
							'label'   => __( 'Heading Size', 'bb-booster' ),
							'default' => '24',
							'units'   => array( 'px' ),
							'slider'  => true,
						),
					),
				),
				'text_position' => array(
					'title'  => __( 'Text Position', 'bb-booster' ),
					'fields' => array(
						'text_position' => array(
							'type'    => 'select',
							'label'   => __( 'Position', 'bb-booster' ),
							'default' => 'top-left',
							'help'    => __( 'The position will move the content layout selections left, right or center over the background of the slide.', 'bb-booster' ),
							'options' => array(
								'left'   => __( 'Left', 'bb-booster' ),
								'center' => __( 'Center', 'bb-booster' ),
								'right'  => __( 'Right', 'bb-booster' ),
							),
						),
						'text_width'    => array(
							'type'    => 'unit',
							'label'   => __( 'Width', 'bb-booster' ),
							'default' => '50',
							'units'   => array( '%' ),
							'slider'  => true,
						),
					),
				),
				'text_margins'  => array(
					'title'  => __( 'Text Spacing', 'bb-booster' ),
					'fields' => array(
						'text_margin'  => array(
							'type'    => 'dimension',
							'label'   => __( 'Margins', 'bb-booster' ),
							'default' => '60',
						),
						'text_padding' => array(
							'type'    => 'dimension',
							'label'   => __( 'Padding', 'bb-booster' ),
							'default' => '60',
						),
					),
				),
				'text_style'    => array(
					'title'  => __( 'Text Colors', 'bb-booster' ),
					'fields' => array(
						'text_color'     => array(
							'type'        => 'color',
							'connections' => array( 'color' ),
							'label'       => __( 'Text Color', 'bb-booster' ),
							'default'     => 'ffffff',
							'show_reset'  => true,
							'show_alpha'  => true,
						),
						'text_shadow'    => array(
							'type'    => 'select',
							'label'   => __( 'Text Shadow', 'bb-booster' ),
							'default' => '0',
							'options' => array(
								'0' => __( 'No', 'bb-booster' ),
								'1' => __( 'Yes', 'bb-booster' ),
							),
						),
						'text_bg_color'  => array(
							'type'        => 'color',
							'connections' => array( 'color' ),
							'label'       => __( 'Text Background Color', 'bb-booster' ),
							'help'        => __( 'The color applies to the overlay behind text over the background selections.', 'bb-booster' ),
							'show_reset'  => true,
							'show_alpha'  => true,
						),
						'text_bg_height' => array(
							'type'    => 'select',
							'label'   => __( 'Text Background Height', 'bb-booster' ),
							'default' => 'auto',
							'help'    => __( 'Auto will allow the overlay to fit however long the text content is. 100% will fit the overlay to the top and bottom of the slide.', 'bb-booster' ),
							'options' => array(
								'auto' => _x( 'Auto', 'Background height.', 'bb-booster' ),
								'100%' => '100%',
							),
						),
					),
				),
			),
		),
		'cta'     => array(
			'title'    => __( 'Link', 'bb-booster' ),
			'sections' => array(
				'link'       => array(
					'title'  => __( 'Link', 'bb-booster' ),
					'fields' => array(
						'link' => array(
							'type'          => 'link',
							'label'         => __( 'Link', 'bb-booster' ),
							'show_target'   => true,
							'show_nofollow' => true,
							'help'          => __( 'The link applies to the entire slide. If choosing a call to action type below, this link will also be used for the text or button.', 'bb-booster' ),
						),
					),
				),
				'cta'        => array(
					'title'  => __( 'Call to Action', 'bb-booster' ),
					'fields' => array(
						'cta_type' => array(
							'type'    => 'select',
							'label'   => __( 'Type', 'bb-booster' ),
							'default' => 'none',
							'options' => array(
								'none'   => _x( 'None', 'Call to action.', 'bb-booster' ),
								'link'   => __( 'Link', 'bb-booster' ),
								'button' => __( 'Button', 'bb-booster' ),
							),
							'toggle'  => array(
								'none'   => array(),
								'link'   => array(
									'fields' => array( 'cta_text' ),
								),
								'button' => array(
									'fields'   => array( 'cta_text' ),
									'sections' => array( 'btn_icon', 'btn_style', 'btn_text', 'btn_colors', 'btn_border' ),
								),
							),
						),
						'cta_text' => array(
							'type'  => 'text',
							'label' => __( 'Text', 'bb-booster' ),
						),
					),
				),
				'btn_icon'   => array(
					'title'  => __( 'Button Icon', 'bb-booster' ),
					'fields' => array(
						'btn_icon'           => array(
							'type'        => 'icon',
							'label'       => __( 'Button Icon', 'bb-booster' ),
							'show_remove' => true,
							'show'        => array(
								'fields' => array( 'btn_icon_position', 'btn_icon_animation' ),
							),
						),
						'btn_duo_color1'     => array(
							'label'      => __( 'DuoTone Primary Color', 'bb-booster' ),
							'type'       => 'color',
							'default'    => '',
							'show_reset' => true,
							'show_alpha' => true,
							'preview'    => array(
								'type'      => 'css',
								'selector'  => 'i.fl-button-icon.fad:before',
								'property'  => 'color',
								'important' => true,
							),
						),
						'btn_duo_color2'     => array(
							'label'      => __( 'DuoTone Secondary Color', 'bb-booster' ),
							'type'       => 'color',
							'default'    => '',
							'show_reset' => true,
							'show_alpha' => true,
							'preview'    => array(
								'type'      => 'css',
								'selector'  => 'i.fl-button-icon.fad:after',
								'property'  => 'color',
								'important' => true,
							),
						),
						'btn_icon_position'  => array(
							'type'    => 'select',
							'label'   => __( 'Button Icon Position', 'bb-booster' ),
							'default' => 'before',
							'options' => array(
								'before' => __( 'Before Text', 'bb-booster' ),
								'after'  => __( 'After Text', 'bb-booster' ),
							),
						),
						'btn_icon_animation' => array(
							'type'    => 'select',
							'label'   => __( 'Button Icon Visibility', 'bb-booster' ),
							'default' => 'disable',
							'options' => array(
								'disable' => __( 'Always Visible', 'bb-booster' ),
								'enable'  => __( 'Fade In On Hover', 'bb-booster' ),
							),
						),
					),
				),
				'btn_style'  => array(
					'title'  => __( 'Button Style', 'bb-booster' ),
					'fields' => array(
						'btn_padding' => array(
							'type'       => 'dimension',
							'label'      => __( 'Button Padding', 'bb-booster' ),
							'responsive' => true,
							'slider'     => true,
							'units'      => array( 'px' ),
							'preview'    => array(
								'type'     => 'css',
								'selector' => 'a.fl-button',
								'property' => 'padding',
							),
						),
					),
				),
				'btn_text'   => array(
					'title'  => __( 'Button Text', 'bb-booster' ),
					'fields' => array(
						'btn_text_color'       => array(
							'type'        => 'color',
							'connections' => array( 'color' ),
							'label'       => __( 'Button Text Color', 'bb-booster' ),
							'default'     => '',
							'show_reset'  => true,
							'show_alpha'  => true,
							'preview'     => array(
								'type'      => 'css',
								'selector'  => 'a.fl-button, a.fl-button *',
								'property'  => 'color',
								'important' => true,
							),
						),
						'btn_text_hover_color' => array(
							'type'        => 'color',
							'connections' => array( 'color' ),
							'label'       => __( 'Button Text Hover Color', 'bb-booster' ),
							'default'     => '',
							'show_reset'  => true,
							'show_alpha'  => true,
							'preview'     => array(
								'type'      => 'css',
								'selector'  => 'a.fl-button:hover, a.fl-button:hover *, a.fl-button:focus, a.fl-button:focus *',
								'property'  => 'color',
								'important' => true,
							),
						),
						'btn_typography'       => array(
							'type'       => 'typography',
							'label'      => __( 'Button Typography', 'bb-booster' ),
							'responsive' => true,
							'preview'    => array(
								'type'     => 'css',
								'selector' => 'a.fl-button',
							),
						),
					),
				),
				'btn_colors' => array(
					'title'  => __( 'Button Background', 'bb-booster' ),
					'fields' => array(
						'btn_bg_color'          => array(
							'type'        => 'color',
							'connections' => array( 'color' ),
							'label'       => __( 'Button Background Color', 'bb-booster' ),
							'default'     => '',
							'show_reset'  => true,
							'show_alpha'  => true,
							'preview'     => array(
								'type' => 'none',
							),
						),
						'btn_bg_hover_color'    => array(
							'type'        => 'color',
							'connections' => array( 'color' ),
							'label'       => __( 'Button Background Hover Color', 'bb-booster' ),
							'default'     => '',
							'show_reset'  => true,
							'show_alpha'  => true,
							'preview'     => array(
								'type' => 'none',
							),
						),
						'btn_style'             => array(
							'type'    => 'select',
							'label'   => __( 'Button Background Style', 'bb-booster' ),
							'default' => 'flat',
							'options' => array(
								'flat'     => __( 'Flat', 'bb-booster' ),
								'gradient' => __( 'Gradient', 'bb-booster' ),
							),
						),
						'btn_button_transition' => array(
							'type'    => 'select',
							'label'   => __( 'Button Background Animation', 'bb-booster' ),
							'default' => 'disable',
							'options' => array(
								'disable' => __( 'Disabled', 'bb-booster' ),
								'enable'  => __( 'Enabled', 'bb-booster' ),
							),
							'preview' => array(
								'type' => 'none',
							),
						),
					),
				),
				'btn_border' => array(
					'title'  => __( 'Button Border', 'bb-booster' ),
					'fields' => array(
						'btn_border'             => array(
							'type'       => 'border',
							'label'      => __( 'Button Border', 'bb-booster' ),
							'responsive' => true,
							'preview'    => array(
								'type'      => 'css',
								'selector'  => 'a.fl-button',
								'important' => true,
							),
						),
						'btn_border_hover_color' => array(
							'type'        => 'color',
							'connections' => array( 'color' ),
							'label'       => __( 'Button Border Hover Color', 'bb-booster' ),
							'default'     => '',
							'show_reset'  => true,
							'show_alpha'  => true,
							'preview'     => array(
								'type' => 'none',
							),
						),
					),
				),
			),
		),
		'mobile'  => array(
			'title'    => _x( 'Mobile', 'Module settings form tab. Display on mobile devices.', 'bb-booster' ),
			'sections' => array(
				'r_photo'      => array(
					'title'  => __( 'Mobile Photo', 'bb-booster' ),
					'fields' => array(
						'r_photo_type' => array(
							'type'    => 'select',
							'label'   => __( 'Type', 'bb-booster' ),
							'default' => 'main',
							'help'    => __( 'You can choose a different photo that the slide will change to on mobile devices or no photo if desired.', 'bb-booster' ),
							'options' => array(
								'main'    => __( 'Use Main Photo', 'bb-booster' ),
								'another' => __( 'Choose Another Photo', 'bb-booster' ),
								'none'    => __( 'No Photo', 'bb-booster' ),
							),
							'toggle'  => array(
								'another' => array(
									'fields' => array( 'r_photo' ),
								),
							),
						),
						'r_photo'      => array(
							'type'        => 'photo',
							'show_remove' => true,
							'label'       => __( 'Photo', 'bb-booster' ),
						),
					),
				),
				'r_text_style' => array(
					'title'  => __( 'Mobile Text Colors', 'bb-booster' ),
					'fields' => array(
						'r_text_color'    => array(
							'type'        => 'color',
							'connections' => array( 'color' ),
							'label'       => __( 'Text Color', 'bb-booster' ),
							'default'     => 'ffffff',
							'show_reset'  => true,
							'show_alpha'  => true,
						),
						'r_text_bg_color' => array(
							'type'        => 'color',
							'connections' => array( 'color' ),
							'label'       => __( 'Text Background Color', 'bb-booster' ),
							'default'     => '333333',
							'show_reset'  => true,
							'show_alpha'  => true,
						),
					),
				),
			),
		),
	),
));
