<?php

//-----------------------------------------------------------------------------
//	Load front-end scripts & stylesheets
//-----------------------------------------------------------------------------
function ct_challenger_enqueue_scripts_styles() {

  // Enqueue Google Fonts
	$font_args = array(
		'family'  => urlencode( 'Poppins:300,300i,700' ),
		'subset'  => urlencode( 'latin,latin-ext' ),
		'display' => 'swap'
	);
	$fonts_url = add_query_arg( $font_args, '//fonts.googleapis.com/css' );
	
	wp_enqueue_style( 'ct-challenger-google-fonts', $fonts_url );

  // Enqueue front-end JS file
  wp_enqueue_script( 'ct-challenger-js', get_template_directory_uri() . '/js/prod/front-end.min.js', array( 'jquery' ), '', true );
  // Localize all English text in JS files
	wp_localize_script( 'ct-challenger-js', 'objectL10n', array(
		'openMenu'       => esc_html__( 'open menu', 'challenger' ),
		'closeMenu'      => esc_html__( 'close menu', 'challenger' ),
		'openChildMenu'  => esc_html__( 'open dropdown menu', 'challenger' ),
		'closeChildMenu' => esc_html__( 'close dropdown menu', 'challenger' )
  ) );
  // Enqueue Font Awesome (custom handle to avoid degrading to <FA5.0)
  wp_enqueue_style( 'ct-challenger-font-awesome', get_template_directory_uri() . '/assets/font-awesome/css/all.min.css' );
  // Enqueue theme stylesheet
	wp_enqueue_style( 'ct-challenger-style', get_stylesheet_uri() );

	// enqueue comment-reply script only on posts & pages with comments open ( included in WP core )
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'ct_challenger_enqueue_scripts_styles' );

//-----------------------------------------------------------------------------
//	Load custom stylesheet for the post editor
//-----------------------------------------------------------------------------
if ( ! function_exists( 'ct_challenger_add_editor_styles' ) ) {
	function ct_challenger_add_editor_styles() {
		add_editor_style( 'styles/editor.css' );
	}
}
add_action( 'admin_init', 'ct_challenger_add_editor_styles' );

//-----------------------------------------------------------------------------
//	Load stylesheets in WP admin
//-----------------------------------------------------------------------------
function ct_challenger_enqueue_admin_styles( $hook ) {

  // Enqueue styles for theme options page
	if ( $hook == 'appearance_page_challenger-options' ) {
		wp_enqueue_style( 'ct-challenger-admin-styles', get_template_directory_uri() . '/styles/admin.min.css' );
  }
  // Enqueue font to be used in the post editor
	if ( $hook == 'post.php' || $hook == 'post-new.php' ) {

		$font_args = array(
			'family' => urlencode( 'Poppins:300,300i,700' ),
			'subset' => urlencode( 'latin,latin-ext' )
		);
		$fonts_url = add_query_arg( $font_args, '//fonts.googleapis.com/css' );
	
		wp_enqueue_style( 'ct-challenger-google-fonts', $fonts_url );
	}
}
add_action( 'admin_enqueue_scripts', 'ct_challenger_enqueue_admin_styles' );

//-----------------------------------------------------------------------------
//  Load scripts & stylesheets in the Customizer
//-----------------------------------------------------------------------------
function ct_challenger_enqueue_customizer_scripts() {
  // Load stylesheet for styling Customizer controls
  wp_enqueue_style( 'ct-challenger-customizer-styles', get_template_directory_uri() . '/styles/customizer.min.css' );
  // Load JS for additional user interactions with Customizer controls
	wp_enqueue_script( 'ct-challenger-customizer-js', get_template_directory_uri() . '/js/prod/customizer.min.js', array( 'jquery' ), '', true );
}
add_action( 'customize_controls_enqueue_scripts', 'ct_challenger_enqueue_customizer_scripts' );

//-----------------------------------------------------------------------------
//  Enqueue script for handling instant updates from Customizer controls using 
//  transport => postMessage
//-----------------------------------------------------------------------------
function ct_challenger_enqueue_customizer_post_message_scripts() {
	wp_enqueue_script( 'ct-challenger-customizer-post-message-js', get_template_directory_uri() . '/js/prod/postMessage.min.js', array( 'jquery' ), '', true );
}
add_action( 'customize_preview_init', 'ct_challenger_enqueue_customizer_post_message_scripts' );