<?php if ( get_theme_mod( 'author_box' ) == 'no' ) return; ?>
<div class="author-box">
	<div class="header">
		<div class="avatar-container">
			<?php echo get_avatar( get_the_author_meta( 'ID' ), 60, '', get_the_author() ); ?>
		</div>
		<span><?php the_author(); ?></span>
	</div>
	<p class="bio">
		<?php the_author_meta('description'); ?>
	</p>
	<p class="view-posts">
		<a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta('ID') ) ); ?>"><?php esc_html_e( 'View all posts', 'challenger' ); ?></a>
	</p>
	<?php ct_challenger_output_social_icons( 'author' ) ?>
</div>