<?php

function ct_challenger_register_theme_page()
{
    add_theme_page(
        sprintf(esc_html__('%s Dashboard', 'challenger'), wp_get_theme()),
        sprintf(esc_html__('%s Dashboard', 'challenger'), wp_get_theme()),
        'edit_theme_options',
        'challenger-options',
        'ct_challenger_options_content'
    );
}
add_action('admin_menu', 'ct_challenger_register_theme_page');

function ct_challenger_options_content()
{
    $pro_url = 'https://www.competethemes.com/challenger-pro/?utm_source=wp-dashboard&utm_medium=Dashboard&utm_campaign=Challenger%20Pro%20-%20Dashboard'; ?>
	<div id="challenger-dashboard-wrap" class="wrap challenger-dashboard-wrap">
		<h2><?php printf(esc_html__('%s Dashboard', 'challenger'), wp_get_theme()); ?></h2>
		<?php do_action('ct_challenger_theme_options_before'); ?>
		<div class="main">
			<?php if (defined('CHALLENGER_PRO_FILE')) : ?>
			<div class="thanks-upgrading" style="background-image: url(<?php echo trailingslashit(get_template_directory_uri()) . 'assets/img/bg-texture.png'; ?>)">
				<h3>Thanks for upgrading!</h3>
				<p>You can find the new features in the Customizer</p>
			</div>
			<?php endif; ?>
			<?php if (!defined('CHALLENGER_PRO_FILE')) : ?>
			<div class="getting-started">
				<h3>Get Started with Challenger</h3>
				<p>Follow this step-by-step guide to customize your website with Challenger:</p>
				<a href="https://www.competethemes.com/help/getting-started-challenger/" target="_blank">Read the Getting Started Guide</a>
			</div>
			<div class="pro">
				<h3>Customize More with Challenger Pro</h3>
				<p>Add 7 new customization features to your site with the <a href="<?php echo esc_url($pro_url); ?>" target="_blank">Challenger Pro</a> plugin.</p>
				<ul class="feature-list">
					<li>
						<div class="image">
							<img src="<?php echo trailingslashit(get_template_directory_uri()) . 'assets/img/layouts.png'; ?>" />
						</div>
						<div class="text">
							<h4>New Layouts</h4>
							<p>New layouts help your content look and perform its best. You can switch to new layouts effortlessly from the Customizer, or from specific posts or pages.</p>
							<p>Challenger Pro adds 8 new layouts.</p>
						</div>
					</li>
					<li>
						<div class="image">
							<img src="<?php echo trailingslashit(get_template_directory_uri()) . 'assets/img/fonts.png'; ?>" />
						</div>
						<div class="text">
							<h4>New Fonts</h4>
							<p>Stylish new fonts add character and charm to your content. Select and instantly preview fonts from the Customizer.</p>
							<p>Since Challenger Pro is powered by Google Fonts, it comes with 728 fonts for you to choose from.</p>
						</div>
					</li>
					<li>
						<div class="image">
							<img src="<?php echo trailingslashit(get_template_directory_uri()) . 'assets/img/font-sizes.png'; ?>" />
						</div>
						<div class="text">
							<h4>Font Sizes</h4>
							<p>Change the size of the fonts used throughout Challenger. Optimize the reading experience for the custom fonts you choose.</p>
							<p>Challenger Pro has 28 font size controls including mobile and desktop specific settings.</p>
						</div>
					</li>
					<li>
						<div class="image">
							<img src="<?php echo trailingslashit(get_template_directory_uri()) . 'assets/img/featured-videos.png'; ?>" />
						</div>
						<div class="text">
							<h4>Featured Videos</h4>
							<p>Featured Videos are an easy way to share videos in place of Featured Images. Instantly embed a Youtube video by copying and pasting its URL into an input.</p>
							<p>Challenger Pro auto-embeds videos from Youtube, Vimeo, DailyMotion, Flickr, Animoto, TED, Blip, Cloudup, FunnyOrDie, Hulu, Vine, WordPress.tv, and VideoPress.</p>
						</div>
					</li>
					<li>
						<div class="image">
							<img src="<?php echo trailingslashit(get_template_directory_uri()) . 'assets/img/sticky-header.png'; ?>" />
						</div>
						<div class="text">
							<h4>Sticky Header</h4>
							<p>Want to keep your menu and social profiles accessible at all times? Easily enable the sticky header to keep it fixed at the top of the screen.</p>
							<p>The sticky header can be toggled on/off instantly for desktop and mobile device visitors.</p>
						</div>
					</li>
					</li>
					<li>
						<div class="image">
							<img src="<?php echo trailingslashit(get_template_directory_uri()) . 'assets/img/widget-areas.png'; ?>" />
						</div>
						<div class="text">
							<h4>New Widget Areas</h4>
							<p>Utilize a sidebar and four additional widget areas for greater flexibility. Increase ad revenue and generate more email subscribers by adding widgets throughout your site.</p>
							<p>Challenger Pro adds 3 new widget areas.</p>
						</div>
					</li>
					<li>
						<div class="image">
							<img src="<?php echo trailingslashit(get_template_directory_uri()) . 'assets/img/footer-text.png'; ?>" />
						</div>
						<div class="text">
							<h4>Custom Footer Text</h4>
							<p>Custom footer text lets you further brand your site. Just start typing to add your own text to the footer.</p>
							<p>The footer text supports plain text and full HTML for adding links.</p>
						</div>
					</li>
				</ul>
				<p><a href="<?php echo esc_url($pro_url); ?>" target="_blank">Click here</a> to view Challenger Pro now, and see what it can do for your site.</p>
			</div>
			<div class="pro-ad" style="background-image: url(<?php echo trailingslashit(get_template_directory_uri()) . 'assets/img/bg-texture.png'; ?>)">
				<h3>Add Incredible Flexibility to Your Site</h3>
				<p>Start customizing with Challenger Pro today</p>
				<a href="<?php echo esc_url($pro_url); ?>" target="_blank">View Challenger Pro</a>
			</div>
			<?php endif; ?>
		</div>
		<div class="sidebar">
			<div class="dashboard-widget">
				<h4>More Amazing Resources</h4>
				<ul>
					<li><a href="https://www.competethemes.com/documentation/challenger-support-center/" target="_blank">Challenger Support Center</a></li>
					<li><a href="https://wordpress.org/support/theme/challenger/" target="_blank">Support Forum</a></li>
					<li><a href="https://www.competethemes.com/help/challenger-changelog/" target="_blank">Changelog</a></li>
					<li><a href="https://www.competethemes.com/help/challenger-css-snippets/" target="_blank">CSS Snippets</a></li>
					<li><a href="https://www.competethemes.com/help/child-theme-challenger/" target="_blank">Starter child theme</a></li>
					<li><a href="https://www.competethemes.com/help/challenger-demo-data/" target="_blank">Challenger demo data</a></li>
					<li><a href="<?php echo esc_url($pro_url); ?>" target="_blank">Challenger Pro</a></li>
				</ul>
			</div>
			<div class="ad iawp">
				<div class="logo-container">
					<img width="308px" height="46px" src="<?php echo trailingslashit(get_template_directory_uri()) . 'assets/img/iawp.svg'; ?>" alt="Independent Analytics logo" />
				</div>
				<div class="features">
					<div class="title">Free WordPress Analytics Plugin</div>
					<ul>
						<li>Beautiful analytics dashboard</li>
						<li>Views & traffic sources</li>
						<li>Easy setup</li>
						<li>GDPR compliant</li>
						<li>Google Analytics alternative</li>
					</ul>
				</div>
				<div class="button">
					<a href="https://independentwp.com" target="_blank" data-product-name="Independent Analytics">Learn More</a>
				</div>
			</div>
			<div class="dashboard-widget">
				<h4>User Reviews</h4>
				<img src="<?php echo trailingslashit(get_template_directory_uri()) . 'assets/img/reviews.png'; ?>" />
				<p>Users are loving Challenger! <a href="https://wordpress.org/support/theme/challenger/reviews/?filter=5#new-post" target="_blank">Click here</a> to leave your own review</p>
			</div>
			<div class="dashboard-widget">
				<h4>Reset Customizer Settings</h4>
				<p><b>Warning:</b> Clicking this button will erase the Challenger theme's current settings in the Customizer.</p>
				<form method="post">
					<input type="hidden" name="challenger_reset_customizer" value="challenger_reset_customizer_settings"/>
					<p>
						<?php wp_nonce_field('challenger_reset_customizer_nonce', 'challenger_reset_customizer_nonce'); ?>
						<?php submit_button('Reset Customizer Settings', 'delete', 'delete', false); ?>
					</p>
				</form>
			</div>
		</div>
		<?php do_action('ct_challenger_theme_options_after'); ?>
	</div>
<?php
}
