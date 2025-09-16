<?php
$avatar_display = get_theme_mod( 'post_byline_avatar' );
$author_display = get_theme_mod( 'post_byline_author' );
$date_display   = get_theme_mod( 'post_byline_date' );

if ( $author_display == 'no' && $date_display == 'no' && $avatar_display == 'no' ) {
	return;
}

$author = get_the_author();
if ( get_theme_mod( 'author_link' ) != 'no' ) {
	$author = '<a href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( $author ) . '</a>';
}
$date = date_i18n( get_option( 'date_format' ), strtotime( get_the_date('c') ) );
$avatar = get_avatar( get_the_author_meta( 'ID' ), 36, '', get_the_author() );

echo '<div class="post-byline">';
	if ( $avatar_display != 'no' ) {
		echo wp_kses( $avatar, array(
			'img' => array(
				'src' 	 => array(),
				'srcset' => array(),
				'alt' 	 => array(),
				'id' 		 => array(),
				'class'  => array(),
				'height' => array(),
				'width'  => array()
			)
		) );
	}
	if ( $author_display != 'no' ) {
		echo wp_kses( $author, array(
			'a' 	 => array(
				'href' 	 => array()
			)
		) );
	}
	if ( $author_display != 'no' && $date_display != 'no' ) {
		echo ' - ';
	}
	if ( $date_display != 'no' ) {
		echo esc_html( $date );
	}
echo '</div>';
