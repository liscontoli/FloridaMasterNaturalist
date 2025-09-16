<?php

//----------------------------------------------------------------------------------
//	Include all required files
//----------------------------------------------------------------------------------
require_once(trailingslashit(get_template_directory()) . 'theme-options.php');
require_once(trailingslashit(get_template_directory()) . 'inc/customizer.php');
require_once(trailingslashit(get_template_directory()) . 'inc/last-updated-meta-box.php');
require_once(trailingslashit(get_template_directory()) . 'inc/scripts.php');
require_once(trailingslashit(get_template_directory()) . 'inc/user-profile.php');
// TGMP
require_once(trailingslashit(get_template_directory()) . 'tgm/class-tgm-plugin-activation.php');

function ct_challenger_register_required_plugins()
{
    $plugins = array(

        array(
            'name'      => 'Independent Analytics',
            'slug'      => 'independent-analytics',
            'required'  => false,
        ),
    );
    
    $config = array(
        'id'           => 'ct-challenger',
        'default_path' => '',
        'menu'         => 'tgmpa-install-plugins',
        'has_notices'  => true,
        'dismissable'  => true,
        'dismiss_msg'  => '',
        'is_automatic' => false,
        'message'      => '',
        'strings'      => array(
            'page_title'                      => __('Install Recommended Plugins', 'challenger'),
            'menu_title'                      => __('Recommended Plugins', 'challenger'),
            'notice_can_install_recommended'     => _n_noop(
                'The makers of the Challenger theme now recommend installing Independent Analytics, their new plugin for visitor tracking: %1$s.',
                'The makers of the Challenger theme now recommend installing Independent Analytics, their new plugin for visitor tracking: %1$s.',
                'challenger'
            ),
        )
    );

    tgmpa($plugins, $config);
}
add_action('tgmpa_register', 'ct_challenger_register_required_plugins');

//----------------------------------------------------------------------------------
//	Set content width variable
//----------------------------------------------------------------------------------
if (! function_exists(('ct_challenger_set_content_width'))) {
    function ct_challenger_set_content_width()
    {
        if (! isset($content_width)) {
            $content_width = 723;
        }
    }
}
add_action('after_setup_theme', 'ct_challenger_set_content_width', 0);

//----------------------------------------------------------------------------------
//	Add theme support for various features, register menus, load text domain
//----------------------------------------------------------------------------------
if (!function_exists(('ct_challenger_theme_setup'))) {
    function ct_challenger_theme_setup()
    {

        // Add Featured Image support
        add_theme_support('post-thumbnails');

        // Add links to RSS feed (and RSS comment feed) in <head>
        add_theme_support('automatic-feed-links');

        // Allow themes and plugins to modify the document title tag
        add_theme_support('title-tag');

        // Allow the use of HTML5 markup
        add_theme_support('html5', array(
      'search-form',
      'comment-form',
      'comment-list',
      'gallery',
      'caption'
    ));

        add_theme_support('custom-logo', array(
      'width'       => 240,
      'height'      => 60,
      'flex-width'  => true,
      'flex-height' => true
    ));

        // Add support for Jetpack infinite scroll
        add_theme_support('infinite-scroll', array(
      'container' => 'loop-container',
      'footer'    => 'overflow-container',
      'render'    => 'ct_challenger_infinite_scroll_render'
    ));

        // Add WooCommerce support
        add_theme_support('woocommerce');

        // Add support for WooCommerce image gallery features
        add_theme_support('wc-product-gallery-zoom');
        add_theme_support('wc-product-gallery-lightbox');
        add_theme_support('wc-product-gallery-slider');

        // Gutenberg - add support for wide & full images
        add_theme_support('align-wide');
        add_theme_support('align-full');

        // Gutenberg - add support for editor styles
        add_theme_support('editor-styles');

        // Gutenberg - modify the selectable font sizes
        add_theme_support('editor-font-sizes', array(
      array(
        'name'      => __('small', 'challenger'),
        'shortName' => __('S', 'challenger'),
        'size'      => 14,
        'slug'      => 'small'
      ),
      array(
        'name'      => __('regular', 'challenger'),
        'shortName' => __('M', 'challenger'),
        'size'      => 21,
        'slug'      => 'regular'
      ),
      array(
        'name'      => __('large', 'challenger'),
        'shortName' => __('L', 'challenger'),
        'size'      => 38,
        'slug'      => 'large'
      ),
      array(
        'name'      => __('larger', 'challenger'),
        'shortName' => __('XL', 'challenger'),
        'size'      => 51,
        'slug'      => 'larger'
      )
    ));

        // Register custom menus
        register_nav_menus(array(
      'primary' => esc_html__('Primary', 'challenger')
    ));

        // Load the theme's translated strings
        load_theme_textdomain('challenger', get_template_directory() . '/languages');
    }
}
add_action('after_setup_theme', 'ct_challenger_theme_setup');

//----------------------------------------------------------------------------------
//	Output meta tags in <head>
//----------------------------------------------------------------------------------
if (! function_exists(('ct_challenger_add_meta_tags'))) {
    function ct_challenger_add_meta_tags()
    {
        // Deprecation handling after renaming function
        if (function_exists('ct_challenger_add_meta_elements')) {
            return;
        }
        $meta_tags = '';
        // Set the character encoding
        $meta_tags .= sprintf('<meta charset="%s" />', esc_attr(get_bloginfo('charset'))) . "\n";
        // Set viewport meta tag for responsive scaling
        $meta_tags .= '<meta name="viewport" content="width=device-width, initial-scale=1" />' . "\n";
        // Add theme and theme version
        $theme      = wp_get_theme(get_template());
        $meta_tags .= sprintf('<meta name="template" content="%s %s" />' . "\n", esc_attr($theme->get('Name')), esc_attr($theme->get('Version')));

        // Output escaped meta tags
        echo wp_kses($meta_tags, array(
            'meta' => array(
                'charset' => array(),
                'name' 		=> array(),
                'content' => array()
            )
        ));
    }
}
add_action('wp_head', 'ct_challenger_add_meta_tags', 1);

//----------------------------------------------------------------------------------
//	Get the right template part while in the loop
//  NOTE: Routing templates this way to follow DRY coding patterns
//  Ex. using index.php file only instead of duplicating loop in page.php, post.php, etc.
//----------------------------------------------------------------------------------
if (! function_exists('ct_challenger_get_template')) {
    function ct_challenger_get_template()
    {

        // Get bbpress.php for all bbpress pages
        if (function_exists('is_bbpress')) {
            if (is_bbpress()) {
                get_template_part('content/bbpress');
                return;
            }
        }
        if (is_home() || is_archive()) {
            get_template_part('content-archive', get_post_type());
        } else {
            get_template_part('content', get_post_type());
        }
    }
}

//----------------------------------------------------------------------------------
//	Primary menu fallback function
//	Note: wp_nav_menu() will apply the ".menu-primary-items" ID & class to the containing <div> instead of <ul> when menu
//	is unset which makies styling extremely confusing b/c wp_nav_menu() otherwise adds menu_class & menu_id to the <ul> element.
//	This function changes the <div> class to "menu-unset" to prevent any confusion
//----------------------------------------------------------------------------------
if (! function_exists(('ct_challenger_wp_page_menu'))) {
    function ct_challenger_wp_page_menu()
    {
        wp_page_menu(
            array(
                "menu_class" => "menu-unset",
                "depth"      => -1
            )
        );
    }
}

//----------------------------------------------------------------------------------
//	Register widget areas
//----------------------------------------------------------------------------------
if (! function_exists('ct_challenger_register_widget_areas')) {
    function ct_challenger_register_widget_areas()
    {
        
        
        // Before Post Content
        register_sidebar(array(
    'name'          => esc_html__('Before Post Content', 'challenger'),
    'id'            => 'before-post',
    'description'   => esc_html__('Widgets in this area will be shown on post pages before the content.', 'challenger'),
    'before_widget' => '<section id="%1$s" class="widget %2$s">',
    'after_widget'  => '</section>',
    'before_title'  => '<div class="widget-title">',
    'after_title'   => '</div>'
  ));
        
        // Before Page Content
        register_sidebar(array(
    'name'          => esc_html__('Before Page Content', 'challenger'),
    'id'            => 'before-page',
    'description'   => esc_html__('Widgets in this area will be shown on pages before the content.', 'challenger'),
    'before_widget' => '<section id="%1$s" class="widget %2$s">',
    'after_widget'  => '</section>',
    'before_title'  => '<div class="widget-title">',
    'after_title'   => '</div>'
  ));
        
        // After Post Content
        register_sidebar(array(
    'name'          => esc_html__('After Post Content', 'challenger'),
    'id'            => 'after-post',
    'description'   => esc_html__('Widgets in this area will be shown on post pages after the content.', 'challenger'),
    'before_widget' => '<section id="%1$s" class="widget %2$s">',
    'after_widget'  => '</section>',
    'before_title'  => '<div class="widget-title">',
    'after_title'   => '</div>'
  ));
        
        // After Page Content
        register_sidebar(array(
    'name'          => esc_html__('After Page Content', 'challenger'),
    'id'            => 'after-page',
    'description'   => esc_html__('Widgets in this area will be shown on pages after the content.', 'challenger'),
    'before_widget' => '<section id="%1$s" class="widget %2$s">',
    'after_widget'  => '</section>',
    'before_title'  => '<div class="widget-title">',
    'after_title'   => '</div>'
  ));
    }
}
add_action('widgets_init', 'ct_challenger_register_widget_areas');

//----------------------------------------------------------------------------------
//	Output the After Post Content widget area
//----------------------------------------------------------------------------------
if (!function_exists('ct_challenger_output_widget_area_after_post_content')) {
    function ct_challenger_output_widget_area_after_post_content()
    {
        get_sidebar('after-post');
    }
}
add_action('challenger_after_post_meta', 'ct_challenger_output_widget_area_after_post_content');

//----------------------------------------------------------------------------------
//	Output the After Page Content widget area
//----------------------------------------------------------------------------------
if (!function_exists('ct_challenger_output_widget_area_after_page_content')) {
    function ct_challenger_output_widget_area_after_page_content()
    {
        get_sidebar('after-page');
    }
}
add_action('challenger_after_page_content', 'ct_challenger_output_widget_area_after_page_content');

//----------------------------------------------------------------------------------
//	Output the Before Post Content widget area
//----------------------------------------------------------------------------------
if (!function_exists('ct_challenger_output_widget_area_before_post_content')) {
    function ct_challenger_output_widget_area_before_post_content()
    {
        get_sidebar('before-post');
    }
}
add_action('challenger_before_post_content', 'ct_challenger_output_widget_area_before_post_content');

//----------------------------------------------------------------------------------
//	Output the Before Page Content widget area
//----------------------------------------------------------------------------------
if (!function_exists('ct_challenger_output_widget_area_before_page_content')) {
    function ct_challenger_output_widget_area_before_page_content()
    {
        get_sidebar('before-page');
    }
}
add_action('challenger_before_page_content', 'ct_challenger_output_widget_area_before_page_content');

//----------------------------------------------------------------------------------
//	Add "Continue reading" button.
//  Used for "more tags", custom excerpts, and automatic excerpts
//----------------------------------------------------------------------------------
if (! function_exists('ct_challenger_add_read_more_link')) {
    function ct_challenger_add_read_more_link($custom = false)
    {
        // Deprecation handling after renaming function
        if (function_exists('ct_challenger_filter_read_more_link')) {
            return;
        }
        if (is_feed()) {
            return;
        }
        global $post;
        $custom_text  = get_theme_mod('read_more_text');
        $excerpt_more = (get_theme_mod('excerpt_length') === 0) ? '' : '&#8230;';
        $output = '';

        // add ellipsis (...) if user did not add a "more tag" or define a custom excerpt (automatic excerpts only)
        if (empty(strpos($post->post_content, '<!--more-->')) && $custom !== true) {
            $output .= $excerpt_more;
        }
        // Don't add the "Continue reading" button if user hid it
        if (get_theme_mod('continue_reading') == 'no') {
            return $output;
        }
        // Append the HTML for the "Continue reading" button
        $output .= '<div class="more-link-wrapper"><a class="more-link" href="' . esc_url(get_permalink()) . '">';
        if (empty($custom_text)) {
            $output .= __('Continue Reading', 'challenger');
        } else {
            $output .= esc_html($custom_text);
        }
        $output .= '<span class="screen-reader-text">' . esc_html(get_the_title()) . '</span></a></div>';

        return $output;
    }
}
add_filter('the_content_more_link', 'ct_challenger_add_read_more_link'); // user added a "more tag"
add_filter('excerpt_more', 'ct_challenger_add_read_more_link', 10); // automatic excerpt

//----------------------------------------------------------------------------------
//	Add "Continue reading" button for custom excerpts
//----------------------------------------------------------------------------------
if (! function_exists('ct_challenger_filter_custom_excerpts')) {
    function ct_challenger_filter_custom_excerpts($excerpt)
    {
        // Deprecation handling after renaming function
        if (function_exists('ct_challenger_filter_manual_excerpts')) {
            return;
        }
        if (has_excerpt()) {
            return $excerpt . ct_challenger_add_read_more_link(true);
        } else {
            return $excerpt;
        }
    }
}
add_filter('get_the_excerpt', 'ct_challenger_filter_custom_excerpts');

//----------------------------------------------------------------------------------
//	Output the excerpt.
//  Used in content-archive.php for archives
//----------------------------------------------------------------------------------
if (! function_exists('ct_challenger_excerpt')) {
    function ct_challenger_excerpt()
    {
        global $post;
        // Output the full content if full posts are enabled or the content up to the "more tag"
        if (get_theme_mod('full_post') === 'yes' || strpos($post->post_content, '<!--more-->')) {
            the_content();
            // Add a link to the comment section unless disabled
            if (get_theme_mod('comment_link') != 'no') {
                echo '<div class="comment-link">';
                echo '<i class="fas fa-comment"></i><a href="'. esc_url(get_permalink()) .'#respond">'. esc_html(__('Comment on this post', 'challenger')) .'</a>';
                echo '</div>';
            }
        } else {
            the_excerpt();
        }
    }
}

//----------------------------------------------------------------------------------
//	Change the automatic excerpt length (in # of words)
//----------------------------------------------------------------------------------
if (! function_exists('ct_challenger_custom_excerpt_length')) {
    function ct_challenger_custom_excerpt_length($length)
    {
        $word_count = get_theme_mod('excerpt_length');

        if (!empty($word_count) && $word_count != 35) {
            return $word_count;
        } elseif ($word_count === 0) {
            return 0;
        } else {
            return 35;
        }
    }
}
add_filter('excerpt_length', 'ct_challenger_custom_excerpt_length', 99);

//----------------------------------------------------------------------------------
//	Turn off scrolling to below the excerpt after clicking "more links"
//----------------------------------------------------------------------------------
if (! function_exists('ct_challenger_remove_more_link_scroll')) {
    function ct_challenger_remove_more_link_scroll($link)
    {
        $link = preg_replace('|#more-[0-9]+|', '', $link);
        return $link;
    }
}
add_filter('the_content_more_link', 'ct_challenger_remove_more_link_scroll');

//----------------------------------------------------------------------------------
//	Output the Featured Image
//----------------------------------------------------------------------------------
if (! function_exists('ct_challenger_featured_image')) {
    function ct_challenger_featured_image()
    {
        if (
            (is_singular() && get_theme_mod('display_featured_image_post') == 'no')
            || (is_home() || is_archive() || is_search()) && get_theme_mod('display_featured_image_blog') == 'no') {
            return;
        }
        global $post;
        $featured_image = '';
        $class = 'featured-image';
        if (get_theme_mod('fi_size_type') == 'no') {
            $class .= ' ratio-natural';
        }

        // Store Featured Image markup if the post has a Featured Image
        if (has_post_thumbnail($post->ID)) {
            if (is_singular()) {
                $featured_image = '<div class="'. esc_attr($class) .'">' . get_the_post_thumbnail($post->ID, 'full') . '</div>';
            } else {
                $featured_image = '<div class="'. esc_attr($class) .'"><a href="' . esc_url(get_permalink()) . '">' . esc_html(get_the_title()) . get_the_post_thumbnail($post->ID, 'full') . '</a></div>';
            }
        }

        // Filter even if there is no Featured Image so Challenger Pro can add a Featured Video
        $featured_image = apply_filters('ct_challenger_featured_image', $featured_image);

        // Output safely escaped Featured Image markup
        if ($featured_image) {
            echo wp_kses($featured_image, array(
                'div' => array(
                    'class' => array()
                ),
                'a'   => array(
                    'href' => array()
                ),
                'img' => array(
                    'src' 	 => array(),
                    'srcset' => array(),
                    'alt' 	 => array(),
                    'id' 		 => array(),
                    'class'  => array(),
                    'height' => array(),
                    'width'  => array(),
                    'sizes'  => array()
                ),
                // for Featured Videos in Challenger Pro
                'iframe' => array(
                    'src' 									=> array(),
                    'id' 										=> array(),
                    'title' 								=> array(),
                    'frameborder' 					=> array(),
                    'allow' 								=> array(),
                    'allowfullscreen' 			=> array(),
                    'webkitallowfullscreen' => array(),
                    'mozallowfullscreen' 		=> array()
                )
            ));
        }
    }
}

//----------------------------------------------------------------------------------
//	Customize the markup for individual comments
//  Note: </li> omitted on purpose b/c WP adds it automatically and adding it here
//	prevents reply <ul> container from nesting properly
//----------------------------------------------------------------------------------
if (! function_exists(('ct_challenger_customize_comments'))) {
    function ct_challenger_customize_comments($comment, $args, $depth)
    { ?>
		<li id="li-comment-<?php comment_ID(); ?>" <?php comment_class(); ?>>
			<article id="comment-<?php comment_ID(); ?>" class="comment">
				<div class="comment-author">
					<?php
                    echo get_avatar(get_comment_author_email(), 36, '', get_comment_author());
        ?>
					<span class="author-name"><?php comment_author_link(); ?></span>
				</div>
				<div class="comment-content">
					<?php if ($comment->comment_approved == '0') : ?>
						<p class="awaiting-moderation">
							<?php esc_html_e('Your comment is awaiting moderation.', 'challenger') ?>
						</p>
					<?php endif; ?>
					<?php comment_text(); ?>
				</div>
				<div class="comment-footer">
					<?php comment_reply_link(array_merge($args, array(
            'reply_text' => esc_html__('Reply', 'challenger'),
            'depth'      => $depth,
            'max_depth'  => $args['max_depth']
        ))); ?>
					<?php edit_comment_link(
            esc_html__('Edit', 'challenger')
        ); ?>
				</div>
			</article>
		<?php
    }
}

//----------------------------------------------------------------------------------
//	Remove the allowed HTML text that shows below the comment form
//----------------------------------------------------------------------------------
if (! function_exists('ct_challenger_remove_comments_notes_after')) {
    function ct_challenger_remove_comments_notes_after($defaults)
    {
        $defaults['comment_notes_after'] = '';
        return $defaults;
    }
}
add_action('comment_form_defaults', 'ct_challenger_remove_comments_notes_after');

//----------------------------------------------------------------------------------
//	Array of social media sites available for icons
//----------------------------------------------------------------------------------
if (! function_exists('ct_challenger_social_array')) {
    function ct_challenger_social_array()
    {

        // Top 10 options included before listing alphabetically
        $social_sites = array(
            'twitter'        => 'ct_challenger_twitter_profile',
            'facebook'       => 'ct_challenger_facebook_profile',
            'instagram'      => 'ct_challenger_instagram_profile',
            'linkedin'       => 'ct_challenger_linkedin_profile',
            'pinterest'      => 'ct_challenger_pinterest_profile',
            'youtube'        => 'ct_challenger_youtube_profile',
            'rss'            => 'ct_challenger_rss_profile',
            'email'          => 'ct_challenger_email_profile',
            'phone'          => 'ct_challenger_phone_profile',
            'email-form'     => 'ct_challenger_email_form_profile',
            'amazon'         => 'ct_challenger_amazon_profile',
            'artstation'     => 'ct_challenger_artstation_profile',
            'bandcamp'       => 'ct_challenger_bandcamp_profile',
            'behance'        => 'ct_challenger_behance_profile',
            'bitbucket'      => 'ct_challenger_bitbucket_profile',
            'codepen'        => 'ct_challenger_codepen_profile',
            'delicious'      => 'ct_challenger_delicious_profile',
            'deviantart'     => 'ct_challenger_deviantart_profile',
            'digg'           => 'ct_challenger_digg_profile',
            'discord'        => 'ct_challenger_discord_profile',
            'dribbble'       => 'ct_challenger_dribbble_profile',
            'etsy'           => 'ct_challenger_etsy_profile',
            'flickr'         => 'ct_challenger_flickr_profile',
            'foursquare'     => 'ct_challenger_foursquare_profile',
            'github'         => 'ct_challenger_github_profile',
            'goodreads'		 => 'ct_challenger_goodreads_profile',
            'google-wallet'  => 'ct_challenger_google_wallet_profile',
            'hacker-news'    => 'ct_challenger_hacker-news_profile',
            'medium'         => 'ct_challenger_medium_profile',
            'meetup'         => 'ct_challenger_meetup_profile',
            'mixcloud'       => 'ct_challenger_mixcloud_profile',
            'ok-ru'          => 'ct_challenger_ok_ru_profile',
            'orcid'          => 'ct_challenger_orcid_profile',
            'patreon'        => 'ct_challenger_patreon_profile',
            'paypal'         => 'ct_challenger_paypal_profile',
            'pocket'         => 'ct_challenger_pocket_profile',
            'podcast'        => 'ct_challenger_podcast_profile',
            'quora'          => 'ct_challenger_quora_profile',
            'qq'             => 'ct_challenger_qq_profile',
            'ravelry'        => 'ct_challenger_ravelry_profile',
            'reddit'         => 'ct_challenger_reddit_profile',
            'researchgate'   => 'ct_challenger_researchgate_profile',
            'skype'          => 'ct_challenger_skype_profile',
            'slack'          => 'ct_challenger_slack_profile',
            'slideshare'     => 'ct_challenger_slideshare_profile',
            'snapchat'       => 'ct_challenger_snapchat_profile',
            'soundcloud'     => 'ct_challenger_soundcloud_profile',
            'spotify'        => 'ct_challenger_spotify_profile',
            'stack-overflow' => 'ct_challenger_stack_overflow_profile',
            'steam'          => 'ct_challenger_steam_profile',
            'strava'         => 'ct_challenger_strava_profile',
            'stumbleupon'    => 'ct_challenger_stumbleupon_profile',
            'telegram'       => 'ct_challenger_telegram_profile',
            'tencent-weibo'  => 'ct_challenger_tencent_weibo_profile',
            'tumblr'         => 'ct_challenger_tumblr_profile',
            'twitch'         => 'ct_challenger_twitch_profile',
            'untappd'        => 'ct_challenger_untappd_profile',
            'vimeo'          => 'ct_challenger_vimeo_profile',
            'vine'           => 'ct_challenger_vine_profile',
            'vk'             => 'ct_challenger_vk_profile',
            'wechat'         => 'ct_challenger_wechat_profile',
            'weibo'          => 'ct_challenger_weibo_profile',
            'whatsapp'       => 'ct_challenger_whatsapp_profile',
            'xing'           => 'ct_challenger_xing_profile',
            'yahoo'          => 'ct_challenger_yahoo_profile',
            'yelp'           => 'ct_challenger_yelp_profile',
            '500px'          => 'ct_challenger_500px_profile'
        );

        return $social_sites;
    }
}

//----------------------------------------------------------------------------------
//	Output the social media icons
//----------------------------------------------------------------------------------
if (! function_exists('ct_challenger_output_social_icons')) {
    function ct_challenger_output_social_icons($source = 'header')
    {

        // Get the social icons array
        $social_sites = ct_challenger_social_array();
        // Store only icons with URLs saved
        $saved = array();

        /* Store the site name and ID if saved
        /* name: twitter
        /* id: ct_challenger_twitter_profile */
        foreach ($social_sites as $name => $id) {
            // Access theme mods for header icons
            if ($source == 'header') {
                if (strlen(get_theme_mod($name)) > 0) {
                    $saved[ $name ] = $id;
                }
            }
            // Access the post author's data if used in the "About the author" box
            elseif ($source == 'author') {
                if (strlen(get_the_author_meta($id)) > 0) {
                    $saved[ $name ] = $id;
                }
            }
        }

        // If there are any social profiles saved
        if (!empty($saved)) {
            echo '<ul class="social-media-icons">';
      
            // Output list item for every saved profile
            foreach ($saved as $name => $id) {

                // Store appropriate class for Font Awesome
                if ($name == 'rss') {
                    $class = 'fas fa-rss';
                } elseif ($name == 'email') {
                    $class = 'fas fa-envelope';
                } elseif ($name == 'email-form') {
                    $class = 'far fa-envelope';
                } elseif ($name == 'podcast') {
                    $class = 'fas fa-podcast';
                } elseif ($name == 'ok-ru') {
                    $class = 'fab fa-odnoklassniki';
                } elseif ($name == 'wechat') {
                    $class = 'fab fa-weixin';
                } elseif ($name == 'pocket') {
                    $class = 'fab fa-get-pocket';
                } elseif ($name == 'phone') {
                    $class = 'fas fa-phone';
                } elseif ($name == 'twitter') {
                    $class = 'fab fa-x-twitter';
                } else {
                    $class = 'fab fa-' . $name;
                }
        
                // Access the URL based on the context
                if ($source == 'header') {
                    $url = get_theme_mod($name);
                } elseif ($source == 'author') {
                    $url = get_the_author_meta($id);
                }

                // Escape the URL based on protocol being used
                if ($name == 'email') {
                    $href = 'mailto:' . antispambot(is_email($url));
                    $title = antispambot(is_email($url));
                } elseif ($name == 'skype') {
                    $href = esc_url($url, array( 'http', 'https', 'skype' ));
                    $title = esc_attr($name);
                } elseif ($name == 'phone') {
                    $href = esc_url($url, array( 'tel' ));
                    $title = str_replace('tel:', '', esc_url($url, array( 'tel' )));
                } else {
                    $href = esc_url($url);
                    $title = esc_attr($name);
                }
                // Output the icon?>
				<li>
				  <a class="<?php echo esc_attr($name); ?>" target="_blank" href="<?php echo $href; ?>">
            <i class="<?php echo esc_attr($class); ?>" aria-hidden="true" title="<?php echo $title; ?>"></i>
            <span class="screen-reader-text"><?php echo esc_html($name); ?></span>
          </a>
        </li>
        <?php
            }
            echo '</ul>';
        }
    }
}

//----------------------------------------------------------------------------------
//	Update body classes for styling
//----------------------------------------------------------------------------------
if (! function_exists(('ct_challenger_body_class'))) {
    function ct_challenger_body_class($classes)
    {
        global $post;
        $full_post = get_theme_mod('full_post');

        if ($full_post == 'yes') {
            $classes[] = 'full-post';
        }
        if (get_bloginfo('description')) {
            $classes[] = 'has-tagline';
        }

        return $classes;
    }
}
add_filter('body_class', 'ct_challenger_body_class');

//----------------------------------------------------------------------------------
//	Add classes to post container element for styling
//----------------------------------------------------------------------------------
if (! function_exists(('ct_challenger_post_class'))) {
    function ct_challenger_post_class($classes)
    {
        // always add .entry class to reuse styling across archives and individual post pages
        $classes[] = 'entry';
        return $classes;
    }
}
add_filter('post_class', 'ct_challenger_post_class');

//----------------------------------------------------------------------------------
// Output standard post pagination
//----------------------------------------------------------------------------------
if (! function_exists(('ct_challenger_pagination'))) {
    function ct_challenger_pagination()
    {

        // Never output pagination on bbpress pages
        if (function_exists('is_bbpress')) {
            if (is_bbpress()) {
                return;
            }
        }
        // Output pagination if Jetpack not installed, otherwise check if infinite scroll is active before outputting
        if (!class_exists('Jetpack')) {
            the_posts_pagination(array(
        'prev_text' => esc_html__('Previous', 'challenger'),
        'next_text' => esc_html__('Next', 'challenger')
      ));
        } elseif (!Jetpack::is_module_active('infinite-scroll')) {
            the_posts_pagination(array(
        'prev_text' => esc_html__('Previous', 'challenger'),
        'next_text' => esc_html__('Next', 'challenger')
      ));
        }
    }
}

//----------------------------------------------------------------------------------
//	Add label for "sticky" posts
//----------------------------------------------------------------------------------
if (! function_exists(('ct_challenger_sticky_post_label'))) {
    function ct_challenger_sticky_post_label()
    {
        // Only add it to the blog where the status takes affect
        if (is_sticky() && !is_archive() && !is_search()) {
            echo '<div class="sticky-status"><span>' . esc_html__('Featured', 'challenger') . '</span></div>';
        }
    }
}

//----------------------------------------------------------------------------------
//  Remove taxonomy label that can't be edited with the_archive_title() parameters
//  is_year() and is_day() neglected b/c there is no analogous function for retrieving the title
//  Ex. "Category: Business" => "Business"
//----------------------------------------------------------------------------------
if (! function_exists('ct_challenger_modify_archive_titles')) {
    function ct_challenger_modify_archive_titles($title)
    {
        if (is_category()) {
            $title = single_cat_title('', false);
        } elseif (is_tag()) {
            $title = single_tag_title('', false);
        } elseif (is_author()) {
            $title = get_the_author();
        } elseif (is_month()) {
            $title = single_month_title(' ');
        }

        return $title;
    }
}
add_filter('get_the_archive_title', 'ct_challenger_modify_archive_titles');

//----------------------------------------------------------------------------------
// Add paragraph tags for author bio displayed in content/archive-header.php.
// the_archive_description() includes paragraph tags for tag and category descriptions, but not the author bio.
//----------------------------------------------------------------------------------
if (! function_exists('ct_challenger_modify_archive_descriptions')) {
    function ct_challenger_modify_archive_descriptions($description)
    {
        // Only add <p> to author bio
        if (is_author()) {
            $description = wpautop($description);
        }
        return $description;
    }
}
add_filter('get_the_archive_description', 'ct_challenger_modify_archive_descriptions');

//----------------------------------------------------------------------------------
// Output the post's "Last Updated" date
//----------------------------------------------------------------------------------
if (! function_exists(('ct_challenger_output_last_updated_date'))) {
    function ct_challenger_output_last_updated_date()
    {
        global $post;

        if (get_the_modified_date() != get_the_date()) {
            $updated_post = get_post_meta($post->ID, 'ct_challenger_last_updated', true);
            $updated_customizer = get_theme_mod('last_updated');
            if (
                ($updated_customizer == 'yes' && ($updated_post != 'no'))
                || $updated_post == 'yes'
            ) {
                echo '<p class="last-updated">'. esc_html__('Last updated on', 'challenger') . ' ' . get_the_modified_date() . ' </p>';
            }
        }
    }
}

//----------------------------------------------------------------------------------
//	Reset the Customizer options added by Challenger
//----------------------------------------------------------------------------------
if (! function_exists(('ct_challenger_reset_customizer_options'))) {
    function ct_challenger_reset_customizer_options()
    {

        // Make sure it's okay to run the reset
        if (!isset($_POST['challenger_reset_customizer']) || 'challenger_reset_customizer_settings' !== $_POST['challenger_reset_customizer']) {
            return;
        }

        if (! wp_verify_nonce(wp_unslash($_POST['challenger_reset_customizer_nonce']), 'challenger_reset_customizer_nonce')) {
            return;
        }

        if (! current_user_can('edit_theme_options')) {
            return;
        }

        // Array of all Customizer settings added by Challenger
        $mods_array = array(
      'header_box',
      'header_box_display',
      'header_box_title',
      'header_box_title_color',
      'header_box_color',
      'header_box_button_text',
      'header_box_button_url',
      'header_box_button_target',
      'header_box_button_color',
      'header_box_button_bg_color',
      'header_box_overlay',
      'header_box_overlay_opacity',
      'header_box_image',
      'header_box_alt_logo',
      'fi_size_type',
      'fi_size',
      'full_post',
      'comment_link',
      'author_link',
      'excerpt_length',
      'read_more_text',
      'post_byline_avatar',
      'post_byline_author',
      'post_byline_date',
      'continue_reading',
      'author_box',
      'post_categories',
      'post_tags',
      'archive_header',
      'last_updated'
    );

        // Get the social media icon settings
        $social_sites = ct_challenger_social_array();

        // append each social site setting to mods array
        foreach ($social_sites as $site => $value) {
            $mods_array[] = $site;
        }

        // Apply filter so Challenger Pro can add its options too
        $mods_array = apply_filters('ct_challenger_mods_to_remove', $mods_array);

        // Loop through all mods and reset
        foreach ($mods_array as $theme_mod) {
            remove_theme_mod($theme_mod);
        }

        // Set redirect to Challenger dashboard page
        $redirect = admin_url('themes.php?page=challenger-options');
        // Add status for admin notice that tells user the reset was successful
        $redirect = add_query_arg('challenger_status', 'deleted', $redirect);

        // Safely redirect
        wp_safe_redirect($redirect);
        exit;
    }
}
add_action('admin_init', 'ct_challenger_reset_customizer_options');

//----------------------------------------------------------------------------------
//	Handle admin notices added by Challenger
//----------------------------------------------------------------------------------
if (! function_exists(('ct_challenger_delete_settings_notice'))) {
    function ct_challenger_delete_settings_notice()
    {
        if (isset($_GET['challenger_status'])) {
            if ($_GET['challenger_status'] == 'deleted') { ?>
				<div class="updated">
					<p><?php esc_html_e('Customizer settings deleted', 'challenger'); ?>.</p>
				</div><?php
            }
        }
    }
}
add_action('admin_notices', 'ct_challenger_delete_settings_notice');

//----------------------------------------------------------------------------------
// Sanitize CSS
// Converts "&gt;" back into ">" so direct descendant CSS selectors work
//----------------------------------------------------------------------------------
if (! function_exists('ct_challenger_sanitize_css')) {
    function ct_challenger_sanitize_css($css)
    {
        $css = wp_kses($css, '');
        $css = str_replace('&gt;', '>', $css);

        return $css;
    }
}

//----------------------------------------------------------------------------------
//	Markup for custom SVGs
//----------------------------------------------------------------------------------
if (! function_exists(('ct_challenger_svgs'))) {
    function ct_challenger_svgs($type)
    {
        $svg = '';
        // Markup for menu toggle button
        if ($type == 'toggle-navigation') {
            $svg = '<svg width="36px" height="24px" viewBox="0 0 36 24">
								<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
										<g transform="translate(-320.000000, -20.000000)" fill="#000000">
												<g transform="translate(320.000000, 20.000000)">
														<rect class="top" x="0" y="20" width="36" height="4" rx="2"></rect>
														<rect class="middle" x="5" y="10" width="26" height="4" rx="2"></rect>
														<rect class="bottom" x="0" y="0" width="36" height="4" rx="2"></rect>
												</g>
										</g>
								</g>
						</svg>';
        }

        return $svg;
    }
}

//----------------------------------------------------------------------------------
//  Allows site title to display in Customizer preview when logo is removed
//  NOTE: Core has partial implementation that won't restore the original markup :/
//----------------------------------------------------------------------------------
if (! function_exists(('ct_challenger_logo_refresh'))) {
    function ct_challenger_logo_refresh($wp_customize)
    {
        $wp_customize->get_setting('custom_logo')->transport = 'refresh';
    }
}
add_action('customize_register', 'ct_challenger_logo_refresh', 20);

//----------------------------------------------------------------------------------
//	Set the archive template part for Jetpack's infinite scroll feature
//----------------------------------------------------------------------------------
if (! function_exists(('ct_challenger_infinite_scroll_render'))) {
    function ct_challenger_infinite_scroll_render()
    {
        while (have_posts()) {
            the_post();
            get_template_part('content', 'archive');
        }
    }
}

//----------------------------------------------------------------------------------
//	Allowing Skype URIs to be used for the social icon
//----------------------------------------------------------------------------------
if (! function_exists('ct_challenger_allow_skype_protocol')) {
    function ct_challenger_allow_skype_protocol($protocols)
    {
        $protocols[] = 'skype';

        return $protocols;
    }
}
add_filter('kses_allowed_protocols', 'ct_challenger_allow_skype_protocol');

//----------------------------------------------------------------------------------
//	Output header box styles
//----------------------------------------------------------------------------------
if (! function_exists(('ct_challenger_output_header_styles'))) {
    function ct_challenger_output_header_styles()
    {
        if (get_theme_mod('header_box') == 'no') {
            return;
        }
        if (ct_challenger_header_box_output_rules() == false) {
            return;
        }

        $css = '';
        $header_box_image = get_theme_mod('header_box_image') ? get_theme_mod('header_box_image') : trailingslashit(get_template_directory_uri()) . 'assets/img/header.jpg';
        $overlay_color = get_theme_mod('header_box_overlay') ? get_theme_mod('header_box_overlay') : '#05b0e7';
        $overlay_opacity = get_theme_mod('header_box_overlay_opacity');
        if ((string) $overlay_opacity === '0') {
            $overlay_opacity = 0;
        } else {
            $overlay_opacity = !empty($overlay_opacity) ? $overlay_opacity : '0.8';
        }
        $button_color = get_theme_mod('header_box_button_color') ? get_theme_mod('header_box_button_color') : '#fff';
        $button_bg_color = get_theme_mod('header_box_button_bg_color') ? get_theme_mod('header_box_button_bg_color') : '#ff9900';
        $title_color = get_theme_mod('header_box_title_color') ? get_theme_mod('header_box_title_color') : '#fff';
        $color = get_theme_mod('header_box_color') ? get_theme_mod('header_box_color') : '#fff';

        // Don't add the background image if the opacity is 1 unless in Customizer preview
        if (is_customize_preview() || $overlay_opacity != 1) {
            $css .= '.site-header { background-image: url("'. esc_url($header_box_image) .'"); }';
        }

        $css .= ".site-header .overlay { 
			background: $overlay_color;
			opacity: $overlay_opacity;
		}";
        $css .= ".header-box .button { 
			color: $button_color;
			background: $button_bg_color;
		}";
        $css .= ".header-box .title { color: $title_color; }";
        $css .= ".site-title a, .tagline { color: $color; }";
        $css .= ".has-header-box .toggle-navigation svg g { fill: $color; }";
        $css .= "@media all and (min-width: 800px) {
			.site-header .social-media-icons a, #menu-primary a { color: $color; }
			.site-header .social-media-icons a, .site-header .social-media-icons a:hover { border-color: $color; }
		}";

        if (!empty($css)) {
            $css = ct_challenger_sanitize_css($css);
            wp_add_inline_style('ct-challenger-style', $css);
        }
    }
}
add_action('wp_enqueue_scripts', 'ct_challenger_output_header_styles', 99);

//----------------------------------------------------------------------------------
//	Style Featured Images based on user's height selection in Customizer
//----------------------------------------------------------------------------------
if (! function_exists(('ct_challenger_output_fi_styles'))) {
    function ct_challenger_output_fi_styles()
    {
        $css = '';
        $fi_size_type = get_theme_mod('fi_size_type');

        // No aspect ratio
        if ($fi_size_type == 'no') {
            $css .= ".featured-image { 
				padding-bottom: 0; 
				height: auto;
			}";
            $css .= ".featured-image > a, .featured-image > a > img, .featured-image > img { 
				position: static;
			}";
        } // Adjust height based on aspect ratio selected by user
        else {
            $fi_size = get_theme_mod('fi_size');
            if (!empty($fi_size) && $fi_size != 40) {
                $css .= ".featured-image { padding-bottom: $fi_size%; }";
            }
        }
        // Output inline CSS
        if (!empty($css)) {
            $css = ct_challenger_sanitize_css($css);
            wp_add_inline_style('ct-challenger-style', $css);
        }
    }
}
add_action('wp_enqueue_scripts', 'ct_challenger_output_fi_styles', 99);

//----------------------------------------------------------------------------------
//	Decide whether header box should be output based on user selection and current page
//----------------------------------------------------------------------------------
if (! function_exists(('ct_challenger_header_box_output_rules'))) {
    function ct_challenger_header_box_output_rules()
    {
        $display = get_theme_mod('header_box_display') ? get_theme_mod('header_box_display') : array('homepage');
        $output = false;

        if (is_front_page() && in_array('homepage', $display)) {
            $output = true;
        }
        if (is_home() && in_array('blog', $display)) {
            $output = true;
        }
        if (is_singular('post') && in_array('posts', $display)) {
            $output = true;
        }
        if (is_singular('page') && !is_front_page() && in_array('pages', $display)) {
            $output = true;
        }
        if (is_archive() && in_array('archives', $display)) {
            $output = true;
        }
        if (is_search() && in_array('search', $display)) {
            $output = true;
        }

        return $output;
    }
}

//----------------------------------------------------------------------------------
//	Add class to body element when header box is displayed
//----------------------------------------------------------------------------------
if (! function_exists(('ct_challenger_body_class_extras'))) {
    function ct_challenger_body_class_extras($classes)
    {
        if (get_theme_mod('header_box') != 'no' && ct_challenger_header_box_output_rules() == true) {
            $classes[] = 'has-header-box';
        }

        return $classes;
    }
}
add_filter('body_class', 'ct_challenger_body_class_extras');

//----------------------------------------------------------------------------------
// Add support for Elementor headers & footers
//----------------------------------------------------------------------------------
function ct_challenger_register_elementor_locations($elementor_theme_manager)
{
    $elementor_theme_manager->register_location('header');
    $elementor_theme_manager->register_location('footer');
}
add_action('elementor/theme/register_locations', 'ct_challenger_register_elementor_locations');

//XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
//----------------------------------------------------------------------------------
//	Deprecation handling for functions renamed after switching to CORE to generate files
//----------------------------------------------------------------------------------
//XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

if (!function_exists('ct_challenger_get_content_template')) {
    function ct_challenger_get_content_template()
    {
        ct_challenger_get_template();
    }
}

if (!function_exists('ct_challenger_social_icons_output')) {
    function ct_challenger_social_icons_output()
    {
        ct_challenger_output_social_icons();
    }
}

if (!function_exists('ct_challenger_svg_output')) {
    function ct_challenger_svg_output($name)
    {
        ct_challenger_svgs($name);
    }
}
