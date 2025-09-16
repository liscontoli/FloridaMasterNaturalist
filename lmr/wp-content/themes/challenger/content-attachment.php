<div <?php post_class(); ?>>
	<?php do_action( 'challenger_attachment_before' ); ?>
	<article>
		<div class='post-header'>
			<h1 class='post-title'><?php the_title(); ?></h1>
		</div>
		<div class="post-content">
			<?php the_content(); ?>
		</div>
	</article>
	<?php do_action( 'challenger_attachment_after' ); ?>
	<?php comments_template(); ?>
</div>