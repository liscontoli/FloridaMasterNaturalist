<?php

//----------------------------------------------------------------------------------
//	Add panels, sections, settings, and controls to the Customizer
//----------------------------------------------------------------------------------
function ct_challenger_add_customizer_content( $wp_customize ) {

  // Move the Site Identity section higher
	$wp_customize->get_section( 'title_tagline' )->priority = 2;

	// Homepage Settings doesn't exist if user has 0 published pages
	if ( is_object( $wp_customize->get_section( 'static_front_page' ) ) ) {
    // move Homepage Settings below Site Identity
		$wp_customize->get_section( 'static_front_page' )->priority = 5;
	}

	// Add PostMessage support to site title and tagline settings
	$wp_customize->get_setting( 'blogname' )->transport        = 'postMessage';
  $wp_customize->get_setting( 'blogdescription' )->transport = 'postMessage';
	
	//XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
	//----------------------------------------------------------------------------------
	//	Add custom controls
	//----------------------------------------------------------------------------------
	//XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
	
	//----------------------------------------------------------------------------------
	//	Multi-checkbox
	//----------------------------------------------------------------------------------
  class CT_challenger_Control_Checkbox_Multiple extends WP_Customize_Control {
    // Establish a "type" for the control
		public $type = 'checkbox-multiple';
		// Output the HTML for the control
    public function render_content() {
        if ( empty( $this->choices ) ) {
					return;
				}
				if ( !empty( $this->label ) ) : ?>
					<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
        <?php endif; ?>
        <?php if ( !empty( $this->description ) ) : ?>
					<span class="description customize-control-description"><?php echo $this->description; ?></span>
        <?php endif; ?>
        <?php // convert saved string of values into an array
        $multi_values = !is_array( $this->value() ) ? explode( ',', $this->value() ) : $this->value(); ?>
        <ul>
          <?php // Output a checkbox for each possible value
						foreach ( $this->choices as $value => $label ) : ?>
						<li>
							<input type="checkbox" value="<?php echo esc_attr( $value ); ?>" <?php checked( in_array( $value, $multi_values ) ); ?> />
							<label><?php echo esc_html( $label ); ?></label>
						</li>
					<?php endforeach; ?>
        </ul>
        <input type="hidden" <?php $this->link(); ?> value="<?php echo esc_attr( implode( ',', $multi_values ) ); ?>" />
		<?php }
	}

	//XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
	//----------------------------------------------------------------------------------
	//	Add Customizer panels, sections, settings, and controls
	//----------------------------------------------------------------------------------
	//XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

	//----------------------------------------------------------------------------------
	// Add panels
	//----------------------------------------------------------------------------------
	
	if ( method_exists( 'WP_Customize_Manager', 'add_panel' ) ) {

		$wp_customize->add_panel( 'ct_challenger_show_hide_panel', array(
			'priority'    => 30,
			'title'       => __( 'Show/Hide Elements', 'challenger' ),
			'description' => __( 'Choose which elements you want displayed on the site.', 'challenger' )
		) );
	}
	
	//----------------------------------------------------------------------------------
  //	Header Box
  //----------------------------------------------------------------------------------

	$wp_customize->add_section( 'challenger_header', array(
		'title'    => __( 'Header', 'challenger'  ),
		'priority' => 7
	) );
	// Show it setting
	$wp_customize->add_setting( 'header_box', array(
		'default'           => 'yes',
		'sanitize_callback' => 'ct_challenger_sanitize_yes_no_settings'
	) );
	// Show it control
	$wp_customize->add_control( 'header_box', array(
		'label'    => __( 'Show the lead generation header?', 'challenger'  ),
		'section'  => 'challenger_header',
		'settings' => 'header_box',
		'type'     => 'radio',
		'choices'  => array(
			'yes' => __( 'Yes', 'challenger'  ),
			'no'  => __( 'No', 'challenger'  )
		)
	) );
	// Page display setting
	$wp_customize->add_setting( 'header_box_display', array(
		'default'           => array('homepage'),
		'sanitize_callback' => 'ct_challenger_sanitize_header_box_display'
	) );
	// Page display control
	$wp_customize->add_control(
		new CT_challenger_Control_Checkbox_Multiple( 
			$wp_customize, 'header_box_display', array(
			'label'    => __( 'Which pages should it display on?', 'challenger'  ),
			'section'  => 'challenger_header',
			'settings' => 'header_box_display',
			'choices'  => array(
				'homepage' => __( 'Homepage', 'challenger'  ),
				'blog'  	 => __( 'Blog', 'challenger'  ),
				'posts'  	 => __( 'Posts', 'challenger'  ),
				'pages'  	 => __( 'Pages', 'challenger'  ),
				'archives' => __( 'Archives', 'challenger'  ),
				'search'   => __( 'Search results', 'challenger'  )
			) )
	) );
	// Title setting
	$wp_customize->add_setting( 'header_box_title', array(
		'default'           => __('Become a professional blogger with our FREE 5-day email course', 'challenger' ),
		'sanitize_callback' => 'ct_challenger_sanitize_text',
		'transport'					=> 'postMessage'
	) );
	// Title control
	$wp_customize->add_control( 'header_box_title', array(
		'label'    => __( 'Title text', 'challenger'  ),
		'section'  => 'challenger_header',
		'settings' => 'header_box_title',
		'type'     => 'textarea'
	) );
	// Title color setting
	$wp_customize->add_setting( 'header_box_title_color', array(
		'default' 					=> '#fff',
		'sanitize_callback' => 'sanitize_hex_color',
		'transport'					=> 'postMessage'
	) );
	// Title color control
	$wp_customize->add_control( new WP_Customize_Color_Control(
		$wp_customize, 'header_box_title_color', array(
			'label'    => __( 'Title text color', 'challenger'  ),
			'section'  => 'challenger_header',
			'settings' => 'header_box_title_color'
		)
	) );
	// Other elements color setting
	$wp_customize->add_setting( 'header_box_color', array(
		'default' 					=> '#fff',
		'sanitize_callback' => 'sanitize_hex_color',
		'transport'					=> 'postMessage'
	) );
	// Other elements color control
	$wp_customize->add_control( new WP_Customize_Color_Control(
		$wp_customize, 'header_box_color', array(
			'label'    => __( 'Site title, tagline, and menu color', 'challenger'  ),
			'section'  => 'challenger_header',
			'settings' => 'header_box_color'
		)
	) );
	// Button text setting
	$wp_customize->add_setting( 'header_box_button_text', array(
		'default'           => __('Signup Now', 'challenger' ),
		'sanitize_callback' => 'ct_challenger_sanitize_text',
		'transport'					=> 'postMessage'
	) );
	// Button text control
	$wp_customize->add_control( 'header_box_button_text', array(
		'label'    => __( 'Button text', 'challenger'  ),
		'section'  => 'challenger_header',
		'settings' => 'header_box_button_text',
		'type'     => 'text'
	) );
	// Button URL setting
	$wp_customize->add_setting( 'header_box_button_url', array(
		'default'           => '#',
		'sanitize_callback' => 'ct_challenger_sanitize_text',
		'transport'					=> 'postMessage'
	) );
	// Button URL control
	$wp_customize->add_control( 'header_box_button_url', array(
		'label'    => __( 'Button URL', 'challenger'  ),
		'section'  => 'challenger_header',
		'settings' => 'header_box_button_url',
		'type'     => 'text'
	) );
	// Button link target setting
	$wp_customize->add_setting( 'header_box_button_target', array(
		'default'           => 'no',
		'sanitize_callback' => 'ct_challenger_sanitize_yes_no_settings'
	) );
	// Button link target control
	$wp_customize->add_control( 'header_box_button_target', array(
		'label'    => __( 'Open the button link in a new tab?', 'challenger'  ),
		'section'  => 'challenger_header',
		'settings' => 'header_box_button_target',
		'type'     => 'radio',
		'choices'  => array(
			'yes' => __( 'Yes', 'challenger'  ),
			'no'  => __( 'No', 'challenger'  )
		)
	) );
	// Button text color setting
	$wp_customize->add_setting( 'header_box_button_color', array(
		'default' 					=> '#fff',
		'sanitize_callback' => 'sanitize_hex_color',
		'transport'					=> 'postMessage'
	) );
	// Button text color control
	$wp_customize->add_control( new WP_Customize_Color_Control(
		$wp_customize, 'header_box_button_color', array(
			'label'    => __( 'Button text color', 'challenger'  ),
			'section'  => 'challenger_header',
			'settings' => 'header_box_button_color'
		)
	) );
	// Button background color setting
	$wp_customize->add_setting( 'header_box_button_bg_color', array(
		'default' 					=> '#ff9900',
		'sanitize_callback' => 'sanitize_hex_color',
		'transport'					=> 'postMessage'
	) );
	// Button background color control
	$wp_customize->add_control( new WP_Customize_Color_Control(
		$wp_customize, 'header_box_button_bg_color', array(
			'label'    => __( 'Button background color', 'challenger'  ),
			'section'  => 'challenger_header',
			'settings' => 'header_box_button_bg_color'
		)
	) );
	// Background color setting
	$wp_customize->add_setting( 'header_box_overlay', array(
		'default' 					=> '#05b0e7',
		'sanitize_callback' => 'sanitize_hex_color',
		'transport'					=> 'postMessage'
	) );
	// Background color control
	$wp_customize->add_control( new WP_Customize_Color_Control(
		$wp_customize, 'header_box_overlay', array(
			'label'    => __( 'Background overlay color', 'challenger'  ),
			'section'  => 'challenger_header',
			'settings' => 'header_box_overlay'
		)
	) );
	// Background opacity setting
	$wp_customize->add_setting( 'header_box_overlay_opacity', array(
		'default' 					=> 0.8,
		'sanitize_callback' => 'ct_challenger_sanitize_header_box_overlay_opacity',
		'transport'					=> 'postMessage'
	) );
	// Background opacity control
	$wp_customize->add_control( 'header_box_overlay_opacity', array(
		'label'    => __( 'Overlay opacity', 'challenger'  ),
		'section'  => 'challenger_header',
		'settings' => 'header_box_overlay_opacity',
		'type'     => 'range',
		'input_attrs' => array(
			'min'  => 0,
			'max'  => 1,
			'step' => 0.01
		)
	) );
	// Background image setting
	$wp_customize->add_setting( 'header_box_image', array(
		'sanitize_callback' => 'esc_url_raw'
	) );
	// Background image control
	$wp_customize->add_control( new WP_Customize_Image_Control(
		$wp_customize, 'header_box_image', array(
			'label'    		=> __( 'Background image', 'challenger'  ),
			'description' => __( 'Use an image that is 2,000px wide for best results.', 'challenger'  ),
			'section'  		=> 'challenger_header',
			'settings' 		=> 'header_box_image'
		)
	) );	
	// Alternate logo setting
	$wp_customize->add_setting( 'header_box_alt_logo', array(
		'sanitize_callback' => 'esc_url_raw'
	) );
	// Alternate logo control
	$wp_customize->add_control( new WP_Customize_Image_Control(
		$wp_customize, 'header_box_alt_logo', array(
			'label'    		=> __( 'Alternate logo', 'challenger'  ),
			'description' => __( 'Upload a light variation of your logo to better match the background overlay.', 'challenger'  ),
			'section'  		=> 'challenger_header',
			'settings' 		=> 'header_box_alt_logo'
		)
	) );

	//----------------------------------------------------------------------------------
	//	Social Media Icons
  //----------------------------------------------------------------------------------
  
  // Get the social sites array
  $social_sites = ct_challenger_social_array();
  // Set a priority to keep the social sites in order
  $priority = 1;

  // Section
  $wp_customize->add_section( 'ct_challenger_social_media_icons', array(
    'title'       => __( 'Social Media Icons', 'challenger'  ),
    'description' => __( 'Enter your social profile URLs to include new icons in the header.', 'challenger'  ),
    'priority'    => 10
  ) );

  // Create a setting and control for each social site
  foreach ( $social_sites as $social_site => $value ) {

    // Get properly capitalized/spaced site name
    $label = ct_challenger_social_icon_labels( $social_site );
    // Set name of sanitization function
    $sanitize_callback = 'esc_url_raw';
    // Email, phone, and Skype icons need custom sanitization functions
    if ( $social_site == 'email' ) {
      $sanitize_callback = 'ct_challenger_sanitize_email';
    } elseif ( $social_site == 'phone' ) {
      $sanitize_callback = 'ct_challenger_sanitize_phone';
    } elseif ( $social_site == 'skype' ) {
      $sanitize_callback = 'ct_challenger_sanitize_skype';
    }

    // Add setting & control
    $wp_customize->add_setting( $social_site, array(
      'sanitize_callback' => $sanitize_callback
    ) );
    $wp_customize->add_control( $social_site, array(
      'type'     => 'url',
      'label'    => $label,
      'section'  => 'ct_challenger_social_media_icons',
      'priority' => $priority
    ) );

    // Increment the priority for next site
    $priority = $priority + 1;
  }

	//----------------------------------------------------------------------------------
  //	Featured Image Size
  //----------------------------------------------------------------------------------

	$wp_customize->add_section( 'challenger_fi_size', array(
		'title'    => __( 'Featured Image Size', 'challenger'  ),
		'priority' => 25
	) );
	// Aspect ratio or natural setting
	$wp_customize->add_setting( 'fi_size_type', array(
		'default'           => 'yes',
		'sanitize_callback' => 'ct_challenger_sanitize_fi_size_type'
	) );
	// Aspect ratio or natural control
	$wp_customize->add_control( 'fi_size_type', array(
		'label'    => __( 'Lock Featured Image aspect ratio?', 'challenger'  ),
		'section'  => 'challenger_fi_size',
		'settings' => 'fi_size_type',
		'type'     => 'radio',
		'choices'  => array(
			'yes' => __( 'Yes, use the same aspect ratio for all Featured Images', 'challenger'  ),
			'no'  => __( 'No, use the natural aspect ratio of each image', 'challenger'  )
		)
	) );
	// Customize aspect ratio setting
	$wp_customize->add_setting( 'fi_size', array(
		'default'           => '40',
		'sanitize_callback' => 'absint',
		'transport'					=> 'postMessage'
	) );
	// Customize aspect ratio control
	$wp_customize->add_control( 'fi_size', array(
		'label'    => __( 'Featured Image Aspect Ratio', 'challenger'  ),
		'section'  => 'challenger_fi_size',
		'settings' => 'fi_size',
		'type'     => 'range',
		'input_attrs' => array(
			'min'  => 15,
			'max'  => 80, 
			'step' => 1
		)
	) );

	//----------------------------------------------------------------------------------
  //	Blog
  //----------------------------------------------------------------------------------

  $wp_customize->add_section( 'challenger_blog', array(
    'title'    => __( 'Blog', 'challenger'  ),
    'priority' => 20
  ) );
  // Show full post setting
  $wp_customize->add_setting( 'full_post', array(
    'default'           => 'no',
    'sanitize_callback' => 'ct_challenger_sanitize_yes_no_settings'
  ) );
  // Show full post control
  $wp_customize->add_control( 'full_post', array(
    'label'    => __( 'Show full posts on blog?', 'challenger'  ),
    'section'  => 'challenger_blog',
    'settings' => 'full_post',
    'type'     => 'radio',
    'choices'  => array(
      'yes' => __( 'Yes', 'challenger'  ),
      'no'  => __( 'No', 'challenger'  )
    )
  ) );
  // Show comment link setting
  $wp_customize->add_setting( 'comment_link', array(
    'default'           => 'yes',
    'sanitize_callback' => 'ct_challenger_sanitize_yes_no_settings'
  ) );
  // Show comment link control
  $wp_customize->add_control( 'comment_link', array(
    'label'    => __( 'Show link to comments after each post?', 'challenger'  ),
    'section'  => 'challenger_blog',
    'settings' => 'comment_link',
    'type'     => 'radio',
    'choices'  => array(
      'yes' => __( 'Yes', 'challenger'  ),
      'no'  => __( 'No', 'challenger'  )
    )
  ) );
  // Link author name to post archive setting
  $wp_customize->add_setting( 'author_link', array(
    'default'           => 'yes',
    'sanitize_callback' => 'ct_challenger_sanitize_yes_no_settings'
  ) );
  // Link author name to post archive control
  $wp_customize->add_control( 'author_link', array(
    'label'    => __( 'Link author name to post archive?', 'challenger'  ),
    'section'  => 'challenger_blog',
    'settings' => 'author_link',
    'type'     => 'radio',
    'choices'  => array(
      'yes' => __( 'Yes', 'challenger'  ),
      'no'  => __( 'No', 'challenger'  )
    )
  ) );
  // Excerpt length setting
  $wp_customize->add_setting( 'excerpt_length', array(
    'default'           => '35',
    'sanitize_callback' => 'absint'
  ) );
  // Excerpt length control
  $wp_customize->add_control( 'excerpt_length', array(
    'label'    => __( 'Excerpt word count', 'challenger'  ),
    'section'  => 'challenger_blog',
    'settings' => 'excerpt_length',
    'type'     => 'number'
  ) );
  // Read More text setting
  $wp_customize->add_setting( 'read_more_text', array(
    'default'           => __( 'Continue reading', 'challenger'  ),
    'sanitize_callback' => 'ct_challenger_sanitize_text',
    'transport'					=> 'postMessage'
  ) );
  // Read More text control
  $wp_customize->add_control( 'read_more_text', array(
    'label'    => __( 'Read More button text', 'challenger'  ),
    'section'  => 'challenger_blog',
    'settings' => 'read_more_text',
    'type'     => 'text'
  ) );

	//----------------------------------------------------------------------------------
  //	Show/Hide Elements
  //----------------------------------------------------------------------------------

  // Section - Header
  $wp_customize->add_section( 'challenger_show_hide_header', array(
    'title' => __( 'Header', 'challenger'  ),
    'panel' => 'ct_challenger_show_hide_panel'
  ) );
  // setting
  $wp_customize->add_setting( 'display_site_title', array(
    'default'           => 'yes',
    'sanitize_callback' => 'ct_challenger_sanitize_yes_no_settings'
  ) );
  // control
  $wp_customize->add_control( 'display_site_title', array(
    'label'    => __( 'Show the site title?', 'challenger'  ),
    'section'  => 'challenger_show_hide_header',
    'settings' => 'display_site_title',
    'type'     => 'radio',
    'choices'  => array(
      'yes' => __( 'Yes', 'challenger'  ),
      'no'  => __( 'No', 'challenger'  )
    )
  ) );
  // setting
  $wp_customize->add_setting( 'display_tagline', array(
    'default'           => 'yes',
    'sanitize_callback' => 'ct_challenger_sanitize_yes_no_settings'
  ) );
  // control
  $wp_customize->add_control( 'display_tagline', array(
    'label'    => __( 'Show the tagline?', 'challenger'  ),
    'section'  => 'challenger_show_hide_header',
    'settings' => 'display_tagline',
    'type'     => 'radio',
    'choices'  => array(
      'yes' => __( 'Yes', 'challenger'  ),
      'no'  => __( 'No', 'challenger'  )
    )
  ) );
  // Section - Posts
  $wp_customize->add_section( 'challenger_show_hide_posts', array(
    'title' => __( 'Posts', 'challenger'  ),
    'panel' => 'ct_challenger_show_hide_panel'
  ) );
  // setting
  $wp_customize->add_setting( 'display_featured_image_post', array(
    'default'           => 'yes',
    'sanitize_callback' => 'ct_challenger_sanitize_yes_no_settings'
  ) );
  // control
  $wp_customize->add_control( 'display_featured_image_post', array(
    'label'    => __( 'Show Featured Image?', 'challenger'  ),
    'section'  => 'challenger_show_hide_posts',
    'settings' => 'display_featured_image_post',
    'type'     => 'radio',
    'choices'  => array(
      'yes' => __( 'Yes', 'challenger'  ),
      'no'  => __( 'No', 'challenger'  )
    )
  ) );
  // setting
  $wp_customize->add_setting( 'post_byline_avatar', array(
    'default'           => 'yes',
    'sanitize_callback' => 'ct_challenger_sanitize_yes_no_settings'
  ) );
  // control
  $wp_customize->add_control( 'post_byline_avatar', array(
    'label'    => __( 'Show author avatar in post byline?', 'challenger'  ),
    'section'  => 'challenger_show_hide_posts',
    'settings' => 'post_byline_avatar',
    'type'     => 'radio',
    'choices'  => array(
      'yes' => __( 'Yes', 'challenger'  ),
      'no'  => __( 'No', 'challenger'  )
    )
  ) );
  // setting
  $wp_customize->add_setting( 'post_byline_author', array(
    'default'           => 'yes',
    'sanitize_callback' => 'ct_challenger_sanitize_yes_no_settings'
  ) );
  // control
  $wp_customize->add_control( 'post_byline_author', array(
    'label'    => __( 'Show author name in post byline?', 'challenger'  ),
    'section'  => 'challenger_show_hide_posts',
    'settings' => 'post_byline_author',
    'type'     => 'radio',
    'choices'  => array(
      'yes' => __( 'Yes', 'challenger'  ),
      'no'  => __( 'No', 'challenger'  )
    )
  ) );
  // setting
  $wp_customize->add_setting( 'post_byline_date', array(
    'default'           => 'yes',
    'sanitize_callback' => 'ct_challenger_sanitize_yes_no_settings'
  ) );
  // control
  $wp_customize->add_control( 'post_byline_date', array(
    'label'    => __( 'Show date in post byline?', 'challenger'  ),
    'section'  => 'challenger_show_hide_posts',
    'settings' => 'post_byline_date',
    'type'     => 'radio',
    'choices'  => array(
      'yes' => __( 'Yes', 'challenger'  ),
      'no'  => __( 'No', 'challenger'  )
    )
  ) );
  // setting
  $wp_customize->add_setting( 'continue_reading', array(
    'default'           => 'yes',
    'sanitize_callback' => 'ct_challenger_sanitize_yes_no_settings'
  ) );
  // control
  $wp_customize->add_control( 'continue_reading', array(
    'label'    => __( 'Show "Continue Reading" button after posts?', 'challenger'  ),
    'section'  => 'challenger_show_hide_posts',
    'settings' => 'continue_reading',
    'type'     => 'radio',
    'choices'  => array(
      'yes' => __( 'Yes', 'challenger'  ),
      'no'  => __( 'No', 'challenger'  )
    )
  ) );
  // setting
  $wp_customize->add_setting( 'author_box', array(
    'default'           => 'yes',
    'sanitize_callback' => 'ct_challenger_sanitize_yes_no_settings'
  ) );
  // control
  $wp_customize->add_control( 'author_box', array(
    'label'    => __( 'Show author box after posts?', 'challenger'  ),
    'section'  => 'challenger_show_hide_posts',
    'settings' => 'author_box',
    'type'     => 'radio',
    'choices'  => array(
      'yes' => __( 'Yes', 'challenger'  ),
      'no'  => __( 'No', 'challenger'  )
    )
  ) );
  // setting
  $wp_customize->add_setting( 'post_categories', array(
    'default'           => 'yes',
    'sanitize_callback' => 'ct_challenger_sanitize_yes_no_settings'
  ) );
  // control
  $wp_customize->add_control( 'post_categories', array(
    'label'    => __( 'Show categories after the post?', 'challenger'  ),
    'section'  => 'challenger_show_hide_posts',
    'settings' => 'post_categories',
    'type'     => 'radio',
    'choices'  => array(
      'yes' => __( 'Yes', 'challenger'  ),
      'no'  => __( 'No', 'challenger'  )
    )
  ) );
  // setting
  $wp_customize->add_setting( 'post_tags', array(
    'default'           => 'yes',
    'sanitize_callback' => 'ct_challenger_sanitize_yes_no_settings'
  ) );
  // control
  $wp_customize->add_control( 'post_tags', array(
    'label'    => __( 'Show tags after the post?', 'challenger'  ),
    'section'  => 'challenger_show_hide_posts',
    'settings' => 'post_tags',
    'type'     => 'radio',
    'choices'  => array(
      'yes' => __( 'Yes', 'challenger'  ),
      'no'  => __( 'No', 'challenger'  )
    )
  ) );
  // Section - Blog & Archives
  $wp_customize->add_section( 'challenger_show_hide_blog_archives', array(
    'title' => __( 'Blog & Archives', 'challenger'  ),
    'panel' => 'ct_challenger_show_hide_panel'
  ) );
  // setting
  $wp_customize->add_setting( 'display_featured_image_blog', array(
    'default'           => 'yes',
    'sanitize_callback' => 'ct_challenger_sanitize_yes_no_settings'
  ) );
  // control
  $wp_customize->add_control( 'display_featured_image_blog', array(
    'label'    => __( 'Show the Featured Images?', 'challenger'  ),
    'section'  => 'challenger_show_hide_blog_archives',
    'settings' => 'display_featured_image_blog',
    'type'     => 'radio',
    'choices'  => array(
      'yes' => __( 'Yes', 'challenger'  ),
      'no'  => __( 'No', 'challenger'  )
    )
  ) );
  // Section - Archives
  $wp_customize->add_section( 'challenger_show_hide_archives', array(
    'title' => __( 'Archives', 'challenger'  ),
    'panel' => 'ct_challenger_show_hide_panel'
  ) );
  // setting
  $wp_customize->add_setting( 'archive_header', array(
    'default'           => 'yes',
    'sanitize_callback' => 'ct_challenger_sanitize_yes_no_settings'
  ) );
  // control
  $wp_customize->add_control( 'archive_header', array(
    'label'    => __( 'Show archive page titles?', 'challenger'  ),
    'section'  => 'challenger_show_hide_archives',
    'settings' => 'archive_header',
    'type'     => 'radio',
    'choices'  => array(
      'yes' => __( 'Yes', 'challenger'  ),
      'no'  => __( 'No', 'challenger'  )
    )
  ) );
  // setting
  $wp_customize->add_setting( 'display_archive_description', array(
    'default'           => 'yes',
    'sanitize_callback' => 'ct_challenger_sanitize_yes_no_settings'
  ) );
  // control
  $wp_customize->add_control( 'display_archive_description', array(
    'label'    => __( 'Show archive description?', 'challenger'  ),
    'section'  => 'challenger_show_hide_archives',
    'settings' => 'display_archive_description',
    'type'     => 'radio',
    'choices'  => array(
      'yes' => __( 'Yes', 'challenger'  ),
      'no'  => __( 'No', 'challenger'  )
    )
  ) );

	//----------------------------------------------------------------------------------
  //	Additional Options
  //----------------------------------------------------------------------------------

	// section
	$wp_customize->add_section( 'ct_challenger_additional_options', array(
		'title'    => __( 'Additional Options', 'challenger'  ),
		'priority' => 30
	) );
	// setting - last updated
	$wp_customize->add_setting( 'last_updated', array(
		'default'           => 'no',
		'sanitize_callback' => 'ct_challenger_sanitize_yes_no_settings'
	) );
	// control - last updated
	$wp_customize->add_control( 'last_updated', array(
		'label'    => __( 'Display the date each post was last updated?', 'challenger'  ),
		'section'  => 'ct_challenger_additional_options',
		'settings' => 'last_updated',
		'type'     => 'radio',
		'choices'  => array(
			'yes' => __( 'Yes', 'challenger'  ),
			'no'  => __( 'No', 'challenger'  )
		)
	) );
}
add_action( 'customize_register', 'ct_challenger_add_customizer_content' );

//XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
//----------------------------------------------------------------------------------
//	Custom sanitization functions
//----------------------------------------------------------------------------------
//XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

// Sanitize plain text
function ct_challenger_sanitize_text( $input ) {
	return wp_kses_post( force_balance_tags( $input ) );
}

// Sanitize email address
function ct_challenger_sanitize_email( $input ) {
	return sanitize_email( $input );
}

// Sanitize phone link protocol
function ct_challenger_sanitize_phone( $input ) {
	if ( $input != '' ) {
		return esc_url_raw( 'tel:' . $input, array( 'tel' ) );
	} else {
		return '';
	}
}

// Sanitize link with Skype protocol
function ct_challenger_sanitize_skype( $input ) {
	return esc_url_raw( $input, array( 'http', 'https', 'skype' ) );
}

// Sanitize yes/no settings
function ct_challenger_sanitize_yes_no_settings( $input ) {

	$valid = array(
		'yes' => __( 'Yes', 'challenger'  ),
		'no'  => __( 'No', 'challenger'  )
	);
	return array_key_exists( $input, $valid ) ? $input : '';
}

// Sanitize Featured Image Size type
function ct_challenger_sanitize_fi_size_type( $input ) {

	$valid = array(
		'yes' => __( 'Yes, keep all Featured Images the same aspect ratio', 'challenger'  ),
		'no'  => __( 'No, use the natural size of each image', 'challenger'  )
	);
	return array_key_exists( $input, $valid ) ? $input : '';
}

// Sanitize Header Box pages to display
function ct_challenger_sanitize_header_box_display( $values ) {

	$multi_values = !is_array( $values ) ? explode( ',', $values ) : $values;
	return !empty( $multi_values ) ? array_map( 'sanitize_text_field', $multi_values ) : array();
}

// Sanitize Header Box background opacity
function ct_challenger_sanitize_header_box_overlay_opacity( $input ) {
	if ( is_float( floatval( $input ) ) ) {
		return $input;
	} else {
		return 0.8;
	}
}

//XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
//----------------------------------------------------------------------------------
//	Helper Functions
//----------------------------------------------------------------------------------
//XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

//----------------------------------------------------------------------------------
//	Return proper capitalization and spacing for social media site names
//----------------------------------------------------------------------------------
function ct_challenger_social_icon_labels( $social_site ) {

	$label = ucfirst( $social_site );
			
	if ( $social_site == 'email' ) {
		$label = __('Email Address', 'challenger' );
	} elseif ( $social_site == 'researchgate' ) {
		$label = __('ResearchGate', 'challenger' );
	} elseif ( $social_site == 'rss' ) {
		$label = __('RSS', 'challenger' );
	} elseif ( $social_site == 'soundcloud' ) {
		$label = __('SoundCloud', 'challenger' );
	} elseif ( $social_site == 'slideshare' ) {
		$label = __('SlideShare', 'challenger' );
	} elseif ( $social_site == 'codepen' ) {
		$label = __('CodePen', 'challenger' );
	} elseif ( $social_site == 'stumbleupon' ) {
		$label = __('StumbleUpon', 'challenger' );
	} elseif ( $social_site == 'deviantart' ) {
		$label = __('DeviantArt', 'challenger' );
	} elseif ( $social_site == 'hacker-news' ) {
		$label = __('Hacker News', 'challenger' );
	} elseif ( $social_site == 'whatsapp' ) {
		$label = __('WhatsApp', 'challenger' );
	} elseif ( $social_site == 'qq' ) {
		$label = __('QQ', 'challenger' );
	} elseif ( $social_site == 'vk' ) {
		$label = __('VK', 'challenger' );
	} elseif ( $social_site == 'wechat' ) {
		$label = __('WeChat', 'challenger' );
	} elseif ( $social_site == 'tencent-weibo' ) {
		$label = __('Tencent Weibo', 'challenger' );
	} elseif ( $social_site == 'paypal' ) {
		$label = __('PayPal', 'challenger' );
	} elseif ( $social_site == 'email-form' ) {
		$label = __('Contact Form', 'challenger' );
	} elseif ( $social_site == 'google-wallet' ) {
		$label = __('Google Wallet', 'challenger' );
	} elseif ( $social_site == 'stack-overflow' ) {
		$label = __('Stack Overflow', 'challenger' );
	} elseif ( $social_site == 'ok-ru' ) {
		$label = __('OK.ru', 'challenger' );
	} elseif ( $social_site == 'artstation' ) {
		$label = __('ArtStation', 'challenger' );
	} elseif ( $social_site == 'twitter' ) {
		$label = __('X (Twitter)', 'challenger' );
	}

	return $label;
}


function ct_challenger_customize_preview_js() {
	if ( !defined( 'CHALLENGER_PRO_FILE' ) && !(isset($_GET['mailoptin_optin_campaign_id']) || isset($_GET['mailoptin_email_campaign_id'])) ) {
		$url = 'https://www.competethemes.com/challenger-pro/?utm_source=wp-dashboard&utm_medium=Customizer&utm_campaign=Challenger%20Pro%20-%20Customizer';
		$content = "<script>jQuery('#customize-info').prepend('<div class=\"upgrades-ad\"><a href=\"". $url ."\" target=\"_blank\">Change Layouts with Challenger Pro <span>&rarr;</span></a></div>')</script>";
		echo apply_filters('ct_challenger_customizer_ad', $content);
	}
}
add_action('customize_controls_print_footer_scripts', 'ct_challenger_customize_preview_js');