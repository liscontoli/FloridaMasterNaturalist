<div <?php post_class(); ?>>
	<?php do_action( 'challenger_before_post' ); ?>
	<article>
		<div class='post-header'>
			<h1 class='post-title'><?php the_title(); ?></h1>
			<?php get_template_part( 'content/post-byline' ); ?>
		</div>
		<?php ct_challenger_featured_image(); ?>
		<div class="post-content">
      <?php do_action( 'challenger_before_post_content' ); ?>
			<?php ct_challenger_output_last_updated_date(); ?>
			<?php the_content(); ?>
			<?php wp_link_pages( array(
				'before' => '<p class="singular-pagination">' . esc_html__( 'Pages:', 'challenger' ),
				'after'  => '</p>',
			) ); ?>
      <?php get_template_part( 'content/author-box' ); ?>
      <?php do_action( 'challenger_after_post_content' ); ?>
		</div>
		<?php do_action( 'challenger_before_post_meta' ); ?>
		<div class="post-meta">
			<?php get_template_part( 'content/post-categories' ); ?>
			<?php get_template_part( 'content/post-tags' ); ?>
		</div>
		<?php do_action( 'challenger_after_post_meta' ); ?>
  </article>
  <?php do_action( 'challenger_after_post' ); ?>
	<?php comments_template(); ?>
</div>