<?php

//----------------------------------------------------------------------------------
//	Add social profile fields to user profile menu. 
//----------------------------------------------------------------------------------
if ( ! function_exists( ( 'ct_challenger_add_author_social_profiles' ) ) ) {
	function ct_challenger_add_author_social_profiles( $user ) {

    // Only available for user's who can create posts
		$user_id = get_current_user_id();
		if ( ! current_user_can( 'edit_posts', $user_id ) ) {
			return false;
    }
    // Get social icons data
    $social_sites = ct_challenger_social_array();
		?>
		<table class="form-table">
			<tr>
				<th>
					<h3><?php esc_html_e( 'Social Profiles', 'challenger' ); ?></h3>
				</th>
			</tr>
			<?php
			foreach ( $social_sites as $name => $id ) {
        $label = ct_challenger_social_icon_labels( $name ); 
        $type = 'url';
        if ( $name == 'email' ) {
          $value = is_email( get_the_author_meta( $id, $user->ID ) );
          $type = 'text';
        } elseif ( $name == 'skype' ) {
          $value = esc_url( get_the_author_meta( $id, $user->ID ), array(
            'http',
            'https',
            'skype'
          ) );
        } elseif ( $name == 'phone' ) {
          $value = esc_url( get_the_author_meta( $id, $user->ID ), array( 'tel' ) );
        } else {
          $value = esc_url( get_the_author_meta( $id, $user->ID ) );
        }
        ?>
				<tr>
					<th>
            <label for="<?php echo esc_attr( $id ); ?>"><?php echo esc_html( $label ); ?></label>
					</th>
					<td>
						<input type='<?php echo esc_attr( $type ); ?>' id='<?php echo esc_attr( $id ); ?>' class='regular-text'
              name='<?php echo esc_attr( $id ); ?>' value='<?php echo $value; ?>'/>
					</td>
				</tr>
			<?php } ?>
		</table>
		<?php
	}
}
add_action( 'show_user_profile', 'ct_challenger_add_author_social_profiles' );
add_action( 'edit_user_profile', 'ct_challenger_add_author_social_profiles' );

//----------------------------------------------------------------------------------
//	Save the user's social profile links
//----------------------------------------------------------------------------------
if ( ! function_exists( ( 'ct_challenger_save_social_profiles' ) ) ) {
	function ct_challenger_save_social_profiles( $user_id ) {

		if ( ! current_user_can( 'edit_user', $user_id ) ) {
			return false;
		}
    // Get social icon data
		$social_sites = ct_challenger_social_array();

    // Loop through icons and save any with URLs entered
		foreach ( $social_sites as $name => $id ) {
      if ( isset( $_POST[$id] ) ) {
        // if email, only accept 'mailto' protocol
        if ( $name == 'email' ) {
          update_user_meta( $user_id, $id, sanitize_email( wp_unslash( $_POST[$id] ) ) );
        } // accept skype protocol
        elseif ( $name == 'skype' ) {
          update_user_meta( $user_id, $id, esc_url_raw( wp_unslash( $_POST[$id] ), array(
            'http',
            'https',
            'skype'
          ) ) );
        } // if phone, only accept 'tel' protocol 
        elseif ( $name == 'phone' ) {
          if ( $_POST[$id] == '' ) {
            update_user_meta( $user_id, $id, '' );
          } else {
            update_user_meta( $user_id, $id, esc_url_raw( 'tel:' . $_POST[$id], array( 'tel' ) ) );
          }
        } else {
          update_user_meta( $user_id, $id, esc_url_raw( wp_unslash( $_POST[$id] ) ) );
        }
      }
		}
	}
}
add_action( 'personal_options_update', 'ct_challenger_save_social_profiles' );
add_action( 'edit_user_profile_update', 'ct_challenger_save_social_profiles' );