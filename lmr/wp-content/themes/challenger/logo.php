<?php
if ( get_theme_mod( 'display_site_title' ) == 'no' ) {
	return;
}
$alt_logo = get_theme_mod( 'header_box_alt_logo' );
echo "<div id='site-title' class='site-title'>";
	if ( ct_challenger_header_box_output_rules() == true && $alt_logo ) {
		echo "<a href='" . esc_url( home_url() ) . "' class='custom-logo-link' rel='home' itemprop='url'>";
			echo '<img src="'. esc_url( $alt_logo ) .'" alt="'. esc_attr( get_bloginfo('name') ) .'" itemprop="logo" />';
		echo "</a>";
	} elseif ( has_custom_logo() ) {
		the_custom_logo();
	} else {
		echo "<a href='" . esc_url( home_url() ) . "'>";
			bloginfo( 'name' );
		echo "</a>";
	}
echo "</div>";