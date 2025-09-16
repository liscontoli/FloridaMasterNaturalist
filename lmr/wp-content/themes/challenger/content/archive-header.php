<?php

// Don't output on bbPress (Forum pages count as archives)
if ( function_exists( 'is_bbpress' ) ) {
	if ( is_bbpress() ) {
		return;
	} 
}
// Output h1 on blog home which is otherwise missing
if ( is_home() ) {
	echo '<h1 class="screen-reader-text">' . esc_html( get_bloginfo( "name" ) ) . '</h1>';
}
// Ensure this is an archive and not another post type
if ( ! is_archive() ) {
	return;
}

$icon_class = 'folder-open';
$prefix = esc_html_x( 'Posts published in', 'Posts published in CATEGORY', 'challenger' );

if ( is_tag() ) {
	$icon_class = 'tag';
	$prefix = esc_html__( 'Posts tagged as', 'challenger' );
} elseif ( is_author() ) {
	$icon_class = 'user';
	$prefix = esc_html_x( 'Posts published by', 'Posts published by AUTHOR', 'challenger' );
} elseif ( is_date() ) {
	$icon_class = 'calendar';
	// Repeating default value to add new translator note - context may change word choice
	$prefix = esc_html_x( 'Posts published in', 'Posts published in MONTH', 'challenger' );
}
?>

<div class='archive-header'>
	<?php if ( get_theme_mod( 'archive_header' ) != 'no' ) : ?>
		<h1>
			<i class="fas fa-<?php echo esc_attr( $icon_class ); ?>"></i>
			<?php
			echo esc_html( $prefix ) . ' ';
			the_archive_title( '&ldquo;', '&rdquo;' );
			?>
		</h1>
	<?php endif; ?>
	<?php if ( get_the_archive_description() != '' && get_theme_mod( 'display_archive_description' ) != 'no' ) : ?>
		<p class="description">
			<?php the_archive_description(); ?>
		</p>
	<?php endif; ?>
</div>