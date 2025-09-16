<?php
/*
** Template Name: Full-width
*/
get_header(); ?>
<div id="loop-container" class="loop-container">
  <?php
  if ( have_posts() ) :
    while ( have_posts() ) :
      the_post(); ?>
      <div <?php post_class(); ?>>
        <?php do_action( 'challenger_before_page' ); ?>
        <article>
          <?php ct_challenger_featured_image(); ?>
          <div class='post-header'>
            <h1 class='post-title'><?php the_title(); ?></h1>
          </div>
          <div class="post-content">
          <?php do_action( 'challenger_before_page_content' ); ?>
            <?php the_content(); ?>
            <?php wp_link_pages( array(
              'before' => '<p class="singular-pagination">' . esc_html__( 'Pages:', 'challenger' ),
              'after'  => '</p>',
            ) ); ?>
            <?php do_action( 'challenger_after_page_content' ); ?>
          </div>
        </article>
        <?php do_action( 'challenger_after_page' ); ?>
        <?php comments_template(); ?>
      </div>
    <?php endwhile;
  endif;
  ?>
</div>
<?php get_footer();