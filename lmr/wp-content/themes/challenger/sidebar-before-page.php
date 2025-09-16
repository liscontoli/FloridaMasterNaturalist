<?php if ( is_active_sidebar( 'before-page' ) ) : 
	$widgets      = get_option( 'sidebars_widgets' );
	$widget_class = count( $widgets['before-page'] );
	?>
	<aside id="before-page" class="widget-area widget-area-before-page active-<?php echo esc_attr( $widget_class ); ?>" role="complementary">
		<?php dynamic_sidebar( 'before-page' ); ?>
	</aside>
<?php endif;