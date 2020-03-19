<?php
/**
 * Information about this theme
 *
 
 * @subpackage OSTENDE
 * @since OSTENDE 1.0.30
 */


// Add 'About Theme' item in the Appearance menu
if (!function_exists('ostende_about_add_menu_items')) {
	add_action( 'admin_menu', 'ostende_about_add_menu_items' );
	function ostende_about_add_menu_items() {
		$theme = wp_get_theme();
		$theme_name = $theme->name . ' ' . esc_html__('Free', 'ostende');
		add_theme_page(
			// Translators: Add theme name to the page title
			sprintf(esc_html__('About %s', 'ostende'), $theme_name),	//page_title
			// Translators: Add theme name to the menu title
			sprintf(esc_html__('About %s', 'ostende'), $theme_name),	//menu_title
			'manage_options',											//capability
			'ostende_about',											//menu_slug
			'ostende_about_page_builder'								//callback
		);
	}
}


// Load page-specific scripts and styles
if (!function_exists('ostende_about_enqueue_scripts')) {
	add_action( 'admin_enqueue_scripts', 'ostende_about_enqueue_scripts' );
	function ostende_about_enqueue_scripts() {
		$screen = function_exists('get_current_screen') ? get_current_screen() : false;
		if (is_object($screen) && $screen->id == 'appearance_page_ostende_about') {
			// Scripts
			wp_enqueue_script( 'jquery-ui-tabs', false, array('jquery', 'jquery-ui-core'), null, true );
			
			if (function_exists('ostende_plugins_installer_enqueue_scripts'))
				ostende_plugins_installer_enqueue_scripts();
			
			// Styles
			wp_enqueue_style( 'ostende-icons',  ostende_get_file_url('css/font-icons/css/fontello-embedded.css'), array(), null );
			if ( ($fdir = ostende_get_file_url('theme-specific/theme-about/theme-about.css')) != '' )
				wp_enqueue_style( 'ostende-about',  $fdir, array(), null );
		}
	}
}


// Build 'About Theme' page
if (!function_exists('ostende_about_page_builder')) {
	function ostende_about_page_builder() {
		$theme = wp_get_theme();
		?>
		<div class="ostende_about">

			<?php do_action('ostende_action_theme_about_before_header', $theme); ?>

			<div class="ostende_about_header">

				<?php do_action('ostende_action_theme_about_before_logo'); ?>

				<div class="ostende_about_logo"><?php
					$logo = ostende_get_file_url('theme-specific/theme-about/logo.png');
					if (empty($logo)) $logo = ostende_get_file_url('screenshot.jpg');
					if (!empty($logo)) {
						?><img src="<?php echo esc_url($logo); ?>"><?php
					}
				?></div>

				<?php do_action('ostende_action_theme_about_before_title', $theme); ?>
				
				<h1 class="ostende_about_title"><?php
					// Translators: Add theme name and version to the 'Welcome' message
					echo esc_html(sprintf(__('Welcome to %1$s %2$s v.%3$s', 'ostende'),
											$theme->name,
											esc_html__('Free', 'ostende'),
											$theme->version
										)
								);
				?></h1>

				<?php do_action('ostende_action_theme_about_before_description', $theme); ?>

				<div class="ostende_about_description">
					<?php
						if ( OSTENDE_THEME_FREE_WP ) {
							?><p><?php
								// Translators: Add the download url and the theme name to the message
								echo wp_kses_data(sprintf(__('Now you are using Free version of <a href="%1$s">%2$s Pro Theme</a>.', 'ostende'),
														esc_url(ostende_storage_get('theme_download_url')),
														$theme->name
														)
												);
							?></p><?php
						}
						?><p><?php
							// Translators: Add the theme name and supported plugins list to the message
							echo '<br>' . wp_kses_data(sprintf(__('This theme is SEO- and Retina-ready. It also has a built-in support for parallax and slider with swipe gestures. %1$s Free is compatible with many popular plugins, such as %2$s', 'ostende'),
														$theme->name,
														ostende_about_get_supported_plugins()
														)
												);
						?></p>
						<p><?php
							echo wp_kses_data( __('We hope you have a great acquaintance with our themes.', 'ostende') );
							if ( OSTENDE_THEME_FREE_WP ) {
								// Translators: Add the download url to the message
								echo ' ' . wp_kses_data(sprintf(__('If you are looking for a fully functional website, you can get the <a href="%s">Pro Version here</a>', 'ostende'),
														esc_url(ostende_storage_get('theme_download_url'))
														)
												);
							}
						?></p>
				</div>

				<?php do_action('ostende_action_theme_about_after_description', $theme); ?>

			</div>

			<?php do_action('ostende_action_theme_about_before_tabs', $theme); ?>

			<div id="ostende_about_tabs" class="ostende_tabs ostende_about_tabs">
				<ul>
					<?php do_action('ostende_action_theme_about_before_tabs_list', $theme); ?>
					<li><a href="#ostende_about_section_start"><?php esc_html_e('Getting started', 'ostende'); ?></a></li>
					<li><a href="#ostende_about_section_actions"><?php esc_html_e('Recommended actions', 'ostende'); ?></a></li>
					<?php do_action('ostende_action_theme_about_after_tabs_list', $theme); ?>
				</ul>

				<?php do_action('ostende_action_theme_about_before_tabs_sections', $theme); ?>

				<div id="ostende_about_section_start" class="ostende_tabs_section ostende_about_section"><?php
				
					// Install required plugins
					if (OSTENDE_THEME_FREE_WP) {
						?><div class="ostende_about_block"><div class="ostende_about_block_inner">
							<h2 class="ostende_about_block_title">
								<i class="dashicons dashicons-admin-plugins"></i>
								<?php esc_html_e('ThemeREX Addons', 'ostende'); ?>
							</h2>
							<div class="ostende_about_block_description"><?php
								esc_html_e('It is highly recommended that you install the companion plugin "ThemeREX Addons" to have access to the layouts builder, awesome shortcodes, team and testimonials, services and slider, and many other features ...', 'ostende');
							?></div>
							<?php ostende_plugins_installer_get_button_html('trx_addons'); ?>
						</div></div><?php
					}
					
					// Install recommended plugins
					?><div class="ostende_about_block"><div class="ostende_about_block_inner">
						<h2 class="ostende_about_block_title">
							<i class="dashicons dashicons-admin-plugins"></i>
							<?php esc_html_e('Recommended plugins', 'ostende'); ?>
						</h2>
						<div class="ostende_about_block_description"><?php
							// Translators: Add the theme name to the message
							echo esc_html(sprintf(__('Theme %s is compatible with a large number of popular plugins. You can install only those that are going to use in the near future.', 'ostende'), $theme->name));
						?></div>
						<a href="<?php echo esc_url(admin_url().'themes.php?page=tgmpa-install-plugins'); ?>"
						   class="ostende_about_block_link button button-primary"><?php
							esc_html_e('Install plugins', 'ostende');
						?></a>
					</div></div><?php
					
					// Customizer or Theme Options
					?><div class="ostende_about_block"><div class="ostende_about_block_inner">
						<h2 class="ostende_about_block_title">
							<i class="dashicons dashicons-admin-appearance"></i>
							<?php esc_html_e('Setup Theme options', 'ostende'); ?>
						</h2>
						<div class="ostende_about_block_description"><?php
							esc_html_e('Using the WordPress Customizer you can easily customize every aspect of the theme.', 'ostende');
						?></div>
						<a href="<?php echo esc_url(admin_url().'customize.php'); ?>"
						   class="ostende_about_block_link button button-primary"><?php
							esc_html_e('Customizer', 'ostende');
						?></a>
					</div></div><?php
					
					// Documentation
					?><div class="ostende_about_block"><div class="ostende_about_block_inner">
						<h2 class="ostende_about_block_title">
							<i class="dashicons dashicons-book"></i>
							<?php esc_html_e('Read Full Documentation', 'ostende');	?>
						</h2>
						<div class="ostende_about_block_description"><?php
							// Translators: Add the theme name to the message
							echo esc_html(sprintf(__('Need more details? Please check our full online documentation for detailed information on how to use %s.', 'ostende'), $theme->name));
						?></div>
						<a href="<?php echo esc_url(ostende_storage_get('theme_doc_url')); ?>"
						   target="_blank"
						   class="ostende_about_block_link button button-primary"><?php
							esc_html_e('Documentation', 'ostende');
						?></a>
					</div></div><?php
					
					// Video tutorials
					?><div class="ostende_about_block"><div class="ostende_about_block_inner">
						<h2 class="ostende_about_block_title">
							<i class="dashicons dashicons-video-alt2"></i>
							<?php esc_html_e('Video tutorials', 'ostende');	?>
						</h2>
						<div class="ostende_about_block_description"><?php
							// Translators: Add the theme name to the message
							echo esc_html(sprintf(__('No time for reading documentation? Check out our video tutorials and learn how to customize %s in detail.', 'ostende'), $theme->name));
						?></div>
						<a href="<?php echo esc_url(ostende_storage_get('theme_video_url')); ?>"
						   target="_blank"
						   class="ostende_about_block_link button button-primary"><?php
							esc_html_e('Watch videos', 'ostende');
						?></a>
					</div></div><?php
										
					// Online Demo
					?><div class="ostende_about_block"><div class="ostende_about_block_inner">
						<h2 class="ostende_about_block_title">
							<i class="dashicons dashicons-images-alt2"></i>
							<?php esc_html_e('On-line demo', 'ostende'); ?>
						</h2>
						<div class="ostende_about_block_description"><?php
							// Translators: Add the theme name to the message
							echo esc_html(sprintf(__('Visit the Demo Version of %s to check out all the features it has', 'ostende'), $theme->name));
						?></div>
						<a href="<?php echo esc_url(ostende_storage_get('theme_demo_url')); ?>"
						   target="_blank"
						   class="ostende_about_block_link button button-primary"><?php
							esc_html_e('View demo', 'ostende');
						?></a>
					</div></div>
					
				</div>



				<div id="ostende_about_section_actions" class="ostende_tabs_section ostende_about_section"><?php
				
					// Install required plugins
					if (OSTENDE_THEME_FREE_WP) {
						?><div class="ostende_about_block"><div class="ostende_about_block_inner">
							<h2 class="ostende_about_block_title">
								<i class="dashicons dashicons-admin-plugins"></i>
								<?php esc_html_e('ThemeREX Addons', 'ostende'); ?>
							</h2>
							<div class="ostende_about_block_description"><?php
								esc_html_e('It is highly recommended that you install the companion plugin "ThemeREX Addons" to have access to the layouts builder, awesome shortcodes, team and testimonials, services and slider, and many other features ...', 'ostende');
							?></div>
							<?php ostende_plugins_installer_get_button_html('trx_addons'); ?>
						</div></div><?php
					}
					
					// Install recommended plugins
					?><div class="ostende_about_block"><div class="ostende_about_block_inner">
						<h2 class="ostende_about_block_title">
							<i class="dashicons dashicons-admin-plugins"></i>
							<?php esc_html_e('Recommended plugins', 'ostende'); ?>
						</h2>
						<div class="ostende_about_block_description"><?php
							// Translators: Add the theme name to the message
							echo esc_html(sprintf(__('Theme %s is compatible with a large number of popular plugins. You can install only those that are going to use in the near future.', 'ostende'), $theme->name));
						?></div>
						<a href="<?php echo esc_url(admin_url().'themes.php?page=tgmpa-install-plugins'); ?>"
						   class="ostende_about_block_link button button button-primary"><?php
							esc_html_e('Install plugins', 'ostende');
						?></a>
					</div></div><?php
					
					// Customizer or Theme Options
					?><div class="ostende_about_block"><div class="ostende_about_block_inner">
						<h2 class="ostende_about_block_title">
							<i class="dashicons dashicons-admin-appearance"></i>
							<?php esc_html_e('Setup Theme options', 'ostende'); ?>
						</h2>
						<div class="ostende_about_block_description"><?php
							esc_html_e('Using the WordPress Customizer you can easily customize every aspect of the theme.', 'ostende');
						?></div>
						<a href="<?php echo esc_url(admin_url().'customize.php'); ?>"
						   target="_blank"
						   class="ostende_about_block_link button button-primary"><?php
							esc_html_e('Customizer', 'ostende');
						?></a>
					</div></div>
					
				</div>

				<?php do_action('ostende_action_theme_about_after_tabs_sections', $theme); ?>
				
			</div>

			<?php do_action('ostende_action_theme_about_after_tabs', $theme); ?>

		</div>
		<?php
	}
}


// Utils
//------------------------------------

// Return supported plugin's names
if (!function_exists('ostende_about_get_supported_plugins')) {
	function ostende_about_get_supported_plugins() {
		return '"' . join('", "', array_values(ostende_storage_get('required_plugins'))) . '"';
	}
}

// Display banner
if (!function_exists('ostende_about_show_banner')) {
	function ostende_about_show_banner() {
		?>
		<div class="ostende_notice_image">
			<a href="//themerex.net/downloads/ostende-theater-art-culture-wp-theme/" target="_blank"><img src="<?php
				echo esc_url( ostende_get_file_url( '/theme-specific/theme-about/images/banner.jpg' ) );
			?>" alt="<?php esc_attr_e( 'Get Premium', 'ostende' ); ?>"></a>
		</div>
		<?php
	}
}

require_once OSTENDE_THEME_DIR . 'includes/plugins-installer/plugins-installer.php';
?>