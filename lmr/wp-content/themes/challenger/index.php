<?php get_header();

get_template_part( 'content/archive-header' ); ?>

<div id="loop-container" class="loop-container">
    <?php
    if ( have_posts() ) :
        while ( have_posts() ) :
            the_post();
            ct_challenger_get_template();
        endwhile;
    endif;
    ?>
</div><?php

ct_challenger_pagination();

get_footer();