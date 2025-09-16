<?php if ( get_theme_mod( 'header_box' ) == 'no' ) return; 

if ( ct_challenger_header_box_output_rules() == false ) return;

$title         = get_theme_mod( 'header_box_title' ) ? get_theme_mod( 'header_box_title' ) : __( 'Become a professional blogger with our FREE 5-day email course', 'challenger' );
$button_text   = get_theme_mod( 'header_box_button_text' ) ? get_theme_mod( 'header_box_button_text' ) : __( 'Signup Now', 'challenger' );
$button_url    = get_theme_mod( 'header_box_button_url' ) ? get_theme_mod( 'header_box_button_url' ) : '#';
$button_target = get_theme_mod( 'header_box_button_target' ) ? get_theme_mod( 'header_box_button_target' ) : 'no';
if ( $button_target == 'yes' ) {
  $button_target = 'target=_blank';
} else {
  $button_target = 'target=_self';
}
?>
<div id="header-box" class="header-box">
  <div class="content">
    <div class="title"><?php echo esc_html( $title ); ?></div>
    <a class="button" href="<?php echo esc_url( $button_url ); ?>" <?php echo esc_attr( $button_target ); ?>><?php echo esc_html( $button_text ); ?></a>
  </div>
</div>
<div class="overlay"></div>
<div id="menu-overflow-cover" class="menu-overflow-cover"></div>