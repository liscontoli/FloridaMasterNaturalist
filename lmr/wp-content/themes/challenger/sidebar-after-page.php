<?php if ( is_active_sidebar( 'after-page' ) ) : 
	$widgets      = get_option( 'sidebars_widgets' );
	$widget_class = count( $widgets['after-page'] );
	?>
	<aside id="after-page" class="widget-area widget-area-after-page active-<?php echo esc_attr( $widget_class ); ?>" role="complementary">
		<?php dynamic_sidebar( 'after-page' ); ?>
	</aside>
<?php endif;