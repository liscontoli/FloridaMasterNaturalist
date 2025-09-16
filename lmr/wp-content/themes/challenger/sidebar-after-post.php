<?php if ( is_active_sidebar( 'after-post' ) ) : 
	$widgets      = get_option( 'sidebars_widgets' );
	$widget_class = count( $widgets['after-post'] );
	?>
	<aside id="after-post" class="widget-area widget-area-after-post active-<?php echo esc_attr( $widget_class ); ?>" role="complementary">
		<?php dynamic_sidebar( 'after-post' ); ?>
	</aside>
<?php endif;