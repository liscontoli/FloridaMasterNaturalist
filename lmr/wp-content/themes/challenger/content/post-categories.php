<?php
if ( get_theme_mod( 'post_categories') == 'no' ) return;

$categories = get_the_category( $post->ID );
$separator = ' ';
$output    = '';

if ( $categories ) {
	echo '<div class="post-categories">';
	echo '<span class="label">' . esc_html__( 'Category', 'challenger' ) . '</span> ';
	foreach ( $categories as $category ) {
		// translators: placeholder is the name of the post category
		$output .= '<a href="' . esc_url( get_category_link( $category->term_id ) ) . '" title="' . esc_attr( sprintf( _x( "View all posts in %s", 'View all posts in post category', 'challenger' ), esc_html( $category->name ) ) ) . '">' . esc_html( $category->cat_name ) . '</a>' . $separator;
	}
	echo wp_kses_post( trim( $output, $separator ) );
	echo "</div>";
}