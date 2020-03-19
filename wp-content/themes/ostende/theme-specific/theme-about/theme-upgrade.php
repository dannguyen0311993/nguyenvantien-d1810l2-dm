<?php
/**
 * Upgrade theme to the PRO version
 *
 
 * @subpackage OSTENDE
 * @since OSTENDE 1.0.41
 */


// Add buttons, tabs and form to the 'About theme' screen
//--------------------------------------------------------------------

// Add tab 'Free vs PRO' to the 'About theme' screen
if (!function_exists('ostende_pro_add_tab_to_about')) {
	add_action( 'ostende_action_theme_about_after_tabs_list', 'ostende_pro_add_tab_to_about');
	function ostende_pro_add_tab_to_about() {	
		?><li><a href="#ostende_about_section_pro"><?php esc_html_e('Free vs PRO', 'ostende'); ?></a></li><?php
	}
}


// Add section 'Free vs PRO' to the 'About theme' screen
if (!function_exists('ostende_pro_add_section_to_about')) {
	add_action( 'ostende_action_theme_about_after_tabs_sections', 'ostende_pro_add_section_to_about', 10, 1);
	function ostende_pro_add_section_to_about($theme) {	
		?>
		<div id="ostende_about_section_pro" class="ostende_tabs_section ostende_about_section">
			<table class="ostende_about_table" cellpadding="0" cellspacing="0" border="0">
				<thead>
					<tr>
						<td class="ostende_about_table_info">&nbsp;</td>
						<td class="ostende_about_table_check"><?php
							// Translators: Show theme name with suffix 'Free'
							echo esc_html(sprintf(__('%s Free', 'ostende'), $theme->name));
						?></td>
						<td class="ostende_about_table_check"><?php
							// Translators: Show theme name with suffix 'PRO'
							echo esc_html(sprintf(__('%s PRO', 'ostende'), $theme->name));
						?></td>
					</tr>
				</thead>
				<tbody>


					<?php
					// Responsive layouts
					?>
					<tr>
						<td class="ostende_about_table_info">
							<h2 class="ostende_about_table_info_title">
								<?php esc_html_e('Mobile friendly', 'ostende'); ?>
							</h2>
							<div class="ostende_about_table_info_description"><?php
								esc_html_e('Responsive layout. Looks great on any device.', 'ostende');
							?></div>
						</td>
						<td class="ostende_about_table_check"><i class="dashicons dashicons-yes"></i></td>
						<td class="ostende_about_table_check"><i class="dashicons dashicons-yes"></i></td>
					</tr>

					<?php
					// Built-in slider
					?>
					<tr>
						<td class="ostende_about_table_info">
							<h2 class="ostende_about_table_info_title">
								<?php esc_html_e('Built-in posts slider', 'ostende'); ?>
							</h2>
							<div class="ostende_about_table_info_description"><?php
								esc_html_e('Allows you to add beautiful slides using the built-in shortcode/widget "Slider" with swipe gestures support.', 'ostende');
							?></div>
						</td>
						<td class="ostende_about_table_check"><i class="dashicons dashicons-yes"></i></td>
						<td class="ostende_about_table_check"><i class="dashicons dashicons-yes"></i></td>
					</tr>

					<?php
					// Revolution slider
					if (ostende_storage_isset('required_plugins', 'revslider')) {
					?>
					<tr>
						<td class="ostende_about_table_info">
							<h2 class="ostende_about_table_info_title">
								<?php esc_html_e('Revolution Slider Compatibility', 'ostende'); ?>
							</h2>
							<div class="ostende_about_table_info_description"><?php
								esc_html_e('Our built-in shortcode/widget "Slider" is able to work not only with posts, but also with slides created  in "Revolution Slider".', 'ostende');
							?></div>
						</td>
						<td class="ostende_about_table_check"><i class="dashicons dashicons-yes"></i></td>
						<td class="ostende_about_table_check"><i class="dashicons dashicons-yes"></i></td>
					</tr>
					<?php } ?>

					<?php
					// SiteOrigin Panels
					if (ostende_storage_isset('required_plugins', 'siteorigin-panels')) {
					?>
					<tr>
						<td class="ostende_about_table_info">
							<h2 class="ostende_about_table_info_title">
								<?php esc_html_e('Free PageBuilder', 'ostende'); ?>
							</h2>
							<div class="ostende_about_table_info_description"><?php
								esc_html_e('Full integration with a nice free page builder "SiteOrigin Panels".', 'ostende');
							?></div>
						</td>
						<td class="ostende_about_table_check"><i class="dashicons dashicons-yes"></i></td>
						<td class="ostende_about_table_check"><i class="dashicons dashicons-yes"></i></td>
					</tr>
					<tr>
						<td class="ostende_about_table_info">
							<h2 class="ostende_about_table_info_title">
								<?php esc_html_e('Additional widgets pack', 'ostende'); ?>
							</h2>
							<div class="ostende_about_table_info_description"><?php
								esc_html_e('A number of useful widgets to create beautiful homepages and other sections of your website with SiteOrigin Panels.', 'ostende');
							?></div>
						</td>
						<td class="ostende_about_table_check"><i class="dashicons dashicons-no"></i></td>
						<td class="ostende_about_table_check"><i class="dashicons dashicons-yes"></i></td>
					</tr>
					<?php } ?>

					<?php
					// WP Bakery Page Builder
					?>
					<tr>
						<td class="ostende_about_table_info">
							<h2 class="ostende_about_table_info_title">
								<?php esc_html_e('WP Bakery Page Builder', 'ostende'); ?>
							</h2>
							<div class="ostende_about_table_info_description"><?php
								esc_html_e('Full integration with a very popular page builder "WP Bakery Page Builder". A number of useful shortcodes and widgets to create beautiful homepages and other sections of your website.', 'ostende');
							?></div>
						</td>
						<td class="ostende_about_table_check"><i class="dashicons dashicons-no"></i></td>
						<td class="ostende_about_table_check"><i class="dashicons dashicons-yes"></i></td>
					</tr>
					<tr>
						<td class="ostende_about_table_info">
							<h2 class="ostende_about_table_info_title">
								<?php esc_html_e('Additional shortcodes pack', 'ostende'); ?>
							</h2>
							<div class="ostende_about_table_info_description"><?php
								esc_html_e('A number of useful shortcodes to create beautiful homepages and other sections of your website with WP Bakery Page Builder.', 'ostende');
							?></div>
						</td>
						<td class="ostende_about_table_check"><i class="dashicons dashicons-no"></i></td>
						<td class="ostende_about_table_check"><i class="dashicons dashicons-yes"></i></td>
					</tr>

					<?php
					// Layouts builder
					?>
					<tr>
						<td class="ostende_about_table_info">
							<h2 class="ostende_about_table_info_title">
								<?php esc_html_e('Headers and Footers builder', 'ostende'); ?>
							</h2>
							<div class="ostende_about_table_info_description"><?php
								esc_html_e('Powerful visual builder of headers and footers! No manual code editing - use all the advantages of drag-and-drop technology.', 'ostende');
							?></div>
						</td>
						<td class="ostende_about_table_check"><i class="dashicons dashicons-no"></i></td>
						<td class="ostende_about_table_check"><i class="dashicons dashicons-yes"></i></td>
					</tr>

					<?php
					// WooCommerce
					if (ostende_storage_isset('required_plugins', 'woocommerce')) {
					?>
					<tr>
						<td class="ostende_about_table_info">
							<h2 class="ostende_about_table_info_title">
								<?php esc_html_e('WooCommerce Compatibility', 'ostende'); ?>
							</h2>
							<div class="ostende_about_table_info_description"><?php
								esc_html_e('Ready for e-commerce. You can build an online store with this theme.', 'ostende');
							?></div>
						</td>
						<td class="ostende_about_table_check"><i class="dashicons dashicons-yes"></i></td>
						<td class="ostende_about_table_check"><i class="dashicons dashicons-yes"></i></td>
					</tr>
					<?php } ?>

					<?php
					// Easy Digital Downloads
					if (ostende_storage_isset('required_plugins', 'easy-digital-downloads')) {
					?>
					<tr>
						<td class="ostende_about_table_info">
							<h2 class="ostende_about_table_info_title">
								<?php esc_html_e('Easy Digital Downloads Compatibility', 'ostende'); ?>
							</h2>
							<div class="ostende_about_table_info_description"><?php
								esc_html_e('Ready for digital e-commerce. You can build an online digital store with this theme.', 'ostende');
							?></div>
						</td>
						<td class="ostende_about_table_check"><i class="dashicons dashicons-no"></i></td>
						<td class="ostende_about_table_check"><i class="dashicons dashicons-yes"></i></td>
					</tr>
					<?php } ?>

					<?php
					// Other plugins
					?>
					<tr>
						<td class="ostende_about_table_info">
							<h2 class="ostende_about_table_info_title">
								<?php esc_html_e('Many other popular plugins compatibility', 'ostende'); ?>
							</h2>
							<div class="ostende_about_table_info_description"><?php
								esc_html_e('PRO version is compatible (was tested and has built-in support) with many popular plugins.', 'ostende');
							?></div>
						</td>
						<td class="ostende_about_table_check"><i class="dashicons dashicons-no"></i></td>
						<td class="ostende_about_table_check"><i class="dashicons dashicons-yes"></i></td>
					</tr>

					<?php
					// Support
					?>
					<tr>
						<td class="ostende_about_table_info">
							<h2 class="ostende_about_table_info_title">
								<?php esc_html_e('Support', 'ostende'); ?>
							</h2>
							<div class="ostende_about_table_info_description"><?php
								esc_html_e('Our premium support is going to take care of any problems, in case there will be any of course.', 'ostende');
							?></div>
						</td>
						<td class="ostende_about_table_check"><i class="dashicons dashicons-no"></i></td>
						<td class="ostende_about_table_check"><i class="dashicons dashicons-yes"></i></td>
					</tr>

					<?php
					// Get PRO version
					if ( OSTENDE_THEME_FREE_WP ) {
						?>
						<tr>
							<td class="ostende_about_table_info">&nbsp;</td>
							<td class="ostende_about_table_check" colspan="2">
								<a href="//themerex.net/downloads/ostende-theater-art-culture-wp-theme/" target="_blank" class="ostende_about_block_link button button-action"><?php
									esc_html_e('Get PRO version', 'ostende');
								?></a>
							</td>
						</tr>
						<?php
					} else {
						?>
						<tr>
							<td colspan="3" align="right"><?php ostende_about_show_banner(); ?></td>
						</tr>
						<?php
					}
					?>
				</tbody>
			</table>
		</div>
		<?php
	}
}


// Add button 'Get PRO Version' to the 'About theme' screen
if (!function_exists('ostende_pro_add_button')) {
	if ( OSTENDE_THEME_FREE_WP ) add_action( 'ostende_action_theme_about_before_title', 'ostende_pro_add_button', 10);
	function ostende_pro_add_button() {
		?><a href="//themerex.net/downloads/ostende-theater-art-culture-wp-theme" target="_blank" class="button button-action"><?php esc_html_e('Get PRO version', 'ostende'); ?></a><?php
	}
}




// Create control for Customizer
//--------------------------------------------------------------------

// Theme init priorities:
// 3 - add/remove Theme Options elements
if (!function_exists('ostende_pro_theme_setup3')) {
	if ( OSTENDE_THEME_FREE_WP ) add_action( 'after_setup_theme', 'ostende_pro_theme_setup3', 3 );
	function ostende_pro_theme_setup3() {

		// Add section "Get PRO Version" if current theme is free
		// ------------------------------------------------------
		ostende_storage_set_array_before('options', 'title_tagline', array(
			'pro_section' => array(
				"title" => esc_html__('Get PRO Version', 'ostende'),
				"desc" => '',
				"priority" => 5,
				"type" => "section"
				),
			'pro_version' => array(
				"title" => esc_html__('Upgrade to the PRO Version', 'ostende'),
				"desc" => wp_kses_data( __('Get the PRO License Key and paste it to the field below to upgrade current theme to the PRO Version', 'ostende') ),
				"std" => '',
				"refresh" => false,
				"type" => "get_pro_version"
				),
		));
	}
}


// Register custom controls for the customizer
if (!function_exists('ostende_pro_customizer_custom_controls')) {
	add_action( 'customize_register', 'ostende_pro_customizer_custom_controls' );
	function ostende_pro_customizer_custom_controls( $wp_customize ) {
		class Ostende_Customize_Get_Pro_Version_Control extends WP_Customize_Control {
			public $type = 'get_pro_version';

			public function render_content() {
				?><div class="customize-control-wrap"><?php
				if (!empty($this->label)) {
					?><span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span><?php
				}
				if (!empty($this->description)) {
					?><span class="customize-control-description description"><?php ostende_show_layout( $this->description ); ?></span><?php
				}
				?><span class="customize-control-field-wrap"><a href="//themerex.net/downloads/ostende-theater-art-culture-wp-theme" target="_blank" class="button button-action"><?php esc_html_e('Get PRO version', 'ostende'); ?></a></span></div><?php
			}
		}
	}
}


// Register custom controls for the customizer
if (!function_exists('ostende_pro_customizer_register_controls')) {
	add_filter('ostende_filter_register_customizer_control', 'ostende_pro_customizer_register_controls', 10, 7);
	function ostende_pro_customizer_register_controls( $result, $wp_customize, $id, $section, $priority, $transport, $opt ) {

		if ($opt['type'] == 'get_pro_version') {
			$wp_customize->add_setting( $id, array(
				'default'           => ostende_get_theme_option($id),
				'sanitize_callback' => !empty($opt['sanitize']) 
											? $opt['sanitize'] 
											: 'wp_kses_post',
				'transport'         => $transport
			) );

			$wp_customize->add_control( new Ostende_Customize_Get_Pro_Version_Control( $wp_customize, $id, array(
					'label'    => $opt['title'],
					'description' => $opt['desc'],
					'section'  => esc_attr($section),
					'priority' => $priority,
					'active_callback' => !empty($opt['active_callback']) ? $opt['active_callback'] : '',
				) ) );

			$result = true;
		}

		return $result;
	}
}

?>