<?php do_action( 'challenger_main_bottom' ); ?>
</section> <!-- .main -->
<?php do_action( 'challenger_after_main' ); ?>
</div> <!-- .layout-container -->
<?php 
// Elementor `footer` location
if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'footer' ) ) :
?>
<footer id="site-footer" class="site-footer" role="contentinfo">
    <div class="max-width">
        <?php do_action( 'challenger_footer_top' ); ?>
        <div class="design-credit">
            <span>
                <?php
                // translators: placeholder is a URL
                $footer_text = sprintf( __( '<a href="%s" rel="nofollow">Challenger WordPress Theme</a> by Compete Themes.', 'challenger' ), 'https://www.competethemes.com/challenger/' );
                $footer_text = apply_filters( 'ct_challenger_footer_text', $footer_text );
                echo do_shortcode( wp_kses_post( $footer_text ) );
                ?>
            </span>
        </div>
        <?php do_action( 'challenger_footer_bottom' ); ?>
    </div>
</footer>
<?php endif; ?>
</div><!-- .max-width -->
</div><!-- .overflow-container -->

<?php do_action( 'challenger_body_bottom' ); ?>

<?php wp_footer(); ?>

</body>
</html>