jQuery(document).ready(function($){

  /* === Toggle visibility of post comments link control === */
  const fullPostControl = $('#customize-control-full_post');
  const commentLinkControl = $('#customize-control-comment_link');
  toggleControlVisibility(
    fullPostControl.find('input:checked').val(), 
    commentLinkControl
  );
  fullPostControl.on('click', function() {
    toggleControlVisibility(
      fullPostControl.find('input:checked').val(), 
      commentLinkControl
    );
  });

  /* === Toggle visibility of the Featured Image size control === */
  const FISizeTypeControl = $('#customize-control-fi_size_type');
  const FISizeControl = $('#customize-control-fi_size');
  toggleControlVisibility(
    FISizeTypeControl.find('input:checked').val(), 
    FISizeControl
  );
  FISizeTypeControl.on('click', function() {
    toggleControlVisibility(
      FISizeTypeControl.find('input:checked').val(), 
      FISizeControl
    );
  });
  
  function toggleControlVisibility(value, target) {
    if ( value == 'yes' ) {
      target.addClass('show');
    } else {
      target.removeClass('show');
    }
  }

  /* === Add reset button to Header overlay opacity === */
  const resetOpacityButton = '<input type="button" id="reset-overlay-opacity" class="button button-small wp-picker-default" value="Reset" aria-label="Reset Featured Image Size">';
  $('#customize-control-header_box_overlay_opacity').append(resetOpacityButton);
  $('#reset-overlay-opacity').on('click', function() {
    $('#_customize-input-header_box_overlay_opacity').val(0.8);
    $('#_customize-input-header_box_overlay_opacity').trigger( 'change' );
  });

  /* === Add reset button to Featured Image size === */
  const resetFIButton = '<input type="button" id="reset-fi-size" class="button button-small wp-picker-default" value="Reset" aria-label="Reset Featured Image Size">';
  $('#customize-control-fi_size').append(resetFIButton);
  $('#reset-fi-size').on('click', function() {
    $('#_customize-input-fi_size').val(40);
    $('#_customize-input-fi_size').trigger( 'change' );
  });

  /* === Header box display options === */
  jQuery( '.customize-control-checkbox-multiple input[type="checkbox"]' ).on( 'change', function() {
    checkbox_values = jQuery( this ).parents( '.customize-control' ).find( 'input[type="checkbox"]:checked' ).map(
      function() {
        return this.value;
      }
    ).get().join( ',' );
    jQuery( this ).parents( '.customize-control' ).find( 'input[type="hidden"]' ).val( checkbox_values ).trigger( 'change' );
  }
);
});