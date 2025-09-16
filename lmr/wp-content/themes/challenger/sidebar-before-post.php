<?php if ( is_active_sidebar( 'before-post' ) ) : 
	$widgets      = get_option( 'sidebars_widgets' );
	$widget_class = count( $widgets['before-post'] );
	?>
	<aside id="before-post" class="widget-area widget-area-before-post active-<?php echo esc_attr( $widget_class ); ?>" role="complementary">
		<?php dynamic_sidebar( 'before-post' ); ?>
	</aside>
<?php endif;