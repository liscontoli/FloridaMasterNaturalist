<!DOCTYPE html>

<html <?php language_attributes(); ?>>

<head>
	<?php wp_head(); ?>
</head>

<body id="<?php echo esc_attr( get_stylesheet() ); ?>" <?php body_class(); ?>>
	<?php do_action( 'challenger_body_top' ); ?>
	<?php 
	if ( function_exists( 'wp_body_open' ) ) {
				wp_body_open();
		} else {
				do_action( 'wp_body_open' );
	} ?>
	<a class="skip-content" href="#main"><?php esc_html_e( 'Press "Enter" to skip to content', 'challenger' ); ?></a>
	<div id="overflow-container" class="overflow-container">
		<div id="max-width" class="max-width">
			<?php do_action( 'challenger_before_header' ); ?>
			<?php 
			// Elementor `header` location
			if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'header' ) ) :
			?>
			<header id="site-header" class="site-header" role="banner">
        <?php do_action( 'challenger_header_top' ); ?>
				<div class="max-width">
					<div id="title-container" class="title-container">
						<?php get_template_part( 'logo' ) ?>
						<?php if ( get_bloginfo( 'description' ) && get_theme_mod( 'display_tagline' ) != 'no' ) {
							echo '<p class="tagline">' . esc_html( get_bloginfo( 'description' ) ) . '</p>';
						} ?>
					</div>
					<div id="menu-primary-container" class="menu-primary-container">
						<?php get_template_part( 'menu', 'primary' ); ?>
						<?php ct_challenger_output_social_icons( 'header' ); ?>
					</div>
					<button id="toggle-navigation" class="toggle-navigation" name="toggle-navigation" aria-expanded="false">
						<span class="screen-reader-text"><?php esc_html_e( 'open menu', 'challenger' ); ?></span>
						<?php echo ct_challenger_svgs( 'toggle-navigation' ); ?>
					</button>
					<?php get_template_part( 'content/header-box' ); ?>
        </div>
        <?php do_action( 'challenger_header_bottom' ); ?>
			</header>
			<?php endif; ?>
			<?php do_action( 'challenger_after_header' ); ?>
			<div class="layout-container">
        <?php do_action( 'challenger_before_main' ); ?>
				<section id="main" class="main" role="main">
					<?php do_action( 'challenger_main_top' );
					if ( function_exists( 'yoast_breadcrumb' ) ) {
						yoast_breadcrumb( '<p id="breadcrumbs">', '</p>' );
					}
