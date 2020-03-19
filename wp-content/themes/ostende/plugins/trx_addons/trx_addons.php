<?php
/* ThemeREX Addons support functions
------------------------------------------------------------------------------- */


// Theme init priorities:
// 1 - register filters, that add/remove lists items for the Theme Options
if (!function_exists('ostende_trx_addons_theme_setup1')) {
	add_action( 'after_setup_theme', 'ostende_trx_addons_theme_setup1', 1 );
	function ostende_trx_addons_theme_setup1() {
		if (ostende_exists_trx_addons()) {
			add_filter( 'ostende_filter_list_posts_types',			'ostende_trx_addons_list_post_types');
			add_filter( 'ostende_filter_list_header_footer_types',	'ostende_trx_addons_list_header_footer_types');
			add_filter( 'ostende_filter_list_header_styles',		'ostende_trx_addons_list_header_styles');
			add_filter( 'ostende_filter_list_footer_styles',		'ostende_trx_addons_list_footer_styles');
			add_action( 'ostende_action_load_options',				'ostende_trx_addons_add_link_edit_layout');
			add_filter( 'trx_addons_filter_default_layouts',		'ostende_trx_addons_default_layouts');
			add_filter( 'trx_addons_cpt_list_options',				'ostende_trx_addons_cpt_list_options', 10, 2);
			add_filter( 'trx_addons_filter_sass_import',			'ostende_trx_addons_sass_import', 10, 2);
		}
	}
}

// Theme init priorities:
// 9 - register other filters (for installer, etc.)
if (!function_exists('ostende_trx_addons_theme_setup9')) {
	add_action( 'after_setup_theme', 'ostende_trx_addons_theme_setup9', 9 );
	function ostende_trx_addons_theme_setup9() {
		if (ostende_exists_trx_addons()) {
			add_filter( 'trx_addons_filter_add_thumb_sizes',			'ostende_trx_addons_add_thumb_sizes');
			add_filter( 'trx_addons_filter_get_thumb_size',				'ostende_trx_addons_get_thumb_size');
			add_filter( 'trx_addons_filter_featured_image',				'ostende_trx_addons_featured_image', 10, 2);
			add_filter( 'trx_addons_filter_no_image',					'ostende_trx_addons_no_image' );
			add_filter( 'trx_addons_filter_get_list_icons',				'ostende_trx_addons_get_list_icons', 10, 2 );
			add_action( 'wp_enqueue_scripts', 							'ostende_trx_addons_frontend_scripts', 1100 );
			add_filter( 'ostende_filter_query_sort_order',	 			'ostende_trx_addons_query_sort_order', 10, 3);
			add_filter( 'ostende_filter_merge_scripts',					'ostende_trx_addons_merge_scripts');
			add_filter( 'ostende_filter_prepare_css',					'ostende_trx_addons_prepare_css', 10, 2);
			add_filter( 'ostende_filter_prepare_js',					'ostende_trx_addons_prepare_js', 10, 2);
			add_filter( 'ostende_filter_localize_script',				'ostende_trx_addons_localize_script');
			add_filter( 'ostende_filter_get_post_categories',		 	'ostende_trx_addons_get_post_categories');
			add_filter( 'ostende_filter_get_post_date',		 			'ostende_trx_addons_get_post_date');
			add_filter( 'trx_addons_filter_get_post_date',		 		'ostende_trx_addons_get_post_date_wrap');
			add_filter( 'ostende_filter_post_type_taxonomy',			'ostende_trx_addons_post_type_taxonomy', 10, 2 );
			add_filter( 'trx_addons_filter_theme_logo',					'ostende_trx_addons_theme_logo');
			add_filter( 'trx_addons_filter_show_site_name_as_logo',		'ostende_trx_addons_show_site_name_as_logo');
			add_filter( 'trx_addons_filter_post_meta',					'ostende_trx_addons_post_meta', 10, 2);
			if (is_admin()) {
				add_filter( 'ostende_filter_allow_override_options', 			'ostende_trx_addons_allow_override_options', 10, 2);
				add_filter( 'ostende_filter_allow_theme_icons', 		'ostende_trx_addons_allow_theme_icons', 10, 2);
				add_filter( 'trx_addons_filter_export_options',			'ostende_trx_addons_export_options');
			} else {
				add_filter( 'trx_addons_filter_inc_views',		 		'ostende_trx_addons_inc_views');
				add_filter( 'ostende_filter_post_meta_args',			'ostende_trx_addons_post_meta_args', 10, 3);
				add_filter( 'trx_addons_filter_args_related',			'ostende_trx_addons_args_related');
				add_filter( 'trx_addons_filter_seo_snippets',			'ostende_trx_addons_seo_snippets');
				add_action( 'trx_addons_action_before_article',			'ostende_trx_addons_before_article', 10, 1);
				add_filter( 'ostende_filter_get_mobile_menu',			'ostende_trx_addons_get_mobile_menu');
				add_filter( 'ostende_filter_detect_blog_mode',			'ostende_trx_addons_detect_blog_mode' );
				add_filter( 'ostende_filter_get_blog_title', 			'ostende_trx_addons_get_blog_title');
				add_action( 'ostende_action_login',						'ostende_trx_addons_action_login');
				add_action( 'ostende_action_cart',						'ostende_trx_addons_action_cart');
				add_action( 'ostende_action_breadcrumbs',				'ostende_trx_addons_action_breadcrumbs');
				add_action( 'ostende_action_show_layout',				'ostende_trx_addons_action_show_layout', 10, 1);
				add_filter( 'ostende_filter_get_translated_layout',		'ostende_trx_addons_filter_get_translated_layout', 10, 1);
				add_filter( 'trx_addons_filter_sc_layout_content',		'ostende_trx_addons_replace_current_year', 20, 2 );
				add_action( 'ostende_action_user_meta',					'ostende_trx_addons_action_user_meta');
				add_action( 'ostende_action_before_post_meta',			'ostende_trx_addons_action_before_post_meta'); 
				add_filter( 'trx_addons_filter_featured_image_override','ostende_trx_addons_featured_image_override');
				add_filter( 'trx_addons_filter_get_current_mode_image',	'ostende_trx_addons_get_current_mode_image');
				add_filter( 'ostende_filter_related_thumb_size',		'ostende_trx_addons_related_thumb_size');
				add_filter( 'ostende_filter_get_post_iframe', 			'ostende_trx_addons_get_post_iframe', 10, 1);
			}
		}
		
		// Add this filter any time: if plugin exists - load plugin's styles, if not exists - load layouts.scss instead plugin's styles
		add_action( 'wp_enqueue_scripts', 								'ostende_trx_addons_layouts_styles' );
		add_filter( 'ostende_filter_merge_styles',						'ostende_trx_addons_merge_styles');
		add_filter( 'ostende_filter_merge_styles_responsive',			'ostende_trx_addons_merge_styles_responsive');
		
		if (is_admin()) {
			add_filter( 'ostende_filter_tgmpa_required_plugins',		'ostende_trx_addons_tgmpa_required_plugins' );
		} else {
			add_action( 'ostende_action_search',						'ostende_trx_addons_action_search', 10, 3);
		}
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'ostende_trx_addons_tgmpa_required_plugins' ) ) {
	function ostende_trx_addons_tgmpa_required_plugins($list=array()) {
		if (ostende_storage_isset('required_plugins', 'trx_addons')) {
			$path = ostende_get_file_dir('plugins/trx_addons/trx_addons.zip');
			if (!empty($path) || ostende_get_theme_setting('tgmpa_upload')) {
				$list[] = array(
					'name' 		=> ostende_storage_get_array('required_plugins', 'trx_addons'),
					'slug' 		=> 'trx_addons',
					'version'	=> '1.6.429',
					'source'	=> !empty($path) ? $path : 'upload://trx_addons.zip',
					'required' 	=> true
				);
			}
		}
		return $list;
	}
}


/* Add options in the Theme Options Customizer
------------------------------------------------------------------------------- */

if (!function_exists('ostende_trx_addons_cpt_list_options')) {
	function ostende_trx_addons_cpt_list_options($options, $cpt) {
		if ($cpt == 'layouts') {
			$options = array();
		} else if (is_array($options)) {
			foreach ($options as $k=>$v) {
				// Store this option in the external (not theme's) storage
				$options[$k]['options_storage'] = 'trx_addons_options';
				// Hide this option from plugin's options (only for overriden options)
				$options[$k]['hidden'] = in_array($cpt, array('cars', 'cars_agents', 'certificates', 'courses', 'dishes', 'portfolio', 'properties', 'agents', 'resume', 'services', 'sport', 'team', 'testimonials'));
			}
		}
		return $options;
	}
}

// Return plugin's specific options for CPT
if (!function_exists('ostende_trx_addons_get_list_cpt_options')) {
	function ostende_trx_addons_get_list_cpt_options($cpt) {
		$options = array();
		if ($cpt == 'cars')
			$options = array_merge(
						trx_addons_cpt_cars_get_list_options(),
						trx_addons_cpt_cars_agents_get_list_options()
						);
		else if ($cpt == 'certificates')
			$options = trx_addons_cpt_certificates_get_list_options();
		else if ($cpt == 'courses')
			$options = trx_addons_cpt_courses_get_list_options();
		else if ($cpt == 'dishes')
			$options = trx_addons_cpt_dishes_get_list_options();
		else if ($cpt == 'portfolio')
			$options = trx_addons_cpt_portfolio_get_list_options();
		else if ($cpt == 'resume')
			$options = trx_addons_cpt_resume_get_list_options();
		else if ($cpt == 'services')
			$options = trx_addons_cpt_services_get_list_options();
		else if ($cpt == 'properties')
			$options = array_merge(
						trx_addons_cpt_properties_get_list_options(),
						trx_addons_cpt_agents_get_list_options()
						);
		else if ($cpt == 'sport')
			$options = trx_addons_cpt_sport_get_list_options();
		else if ($cpt == 'team')
			$options = trx_addons_cpt_team_get_list_options();
		else if ($cpt == 'testimonials')
			$options = trx_addons_cpt_testimonials_get_list_options();

		foreach ($options as $k=>$v) {
			// Disable refresh the preview area on change any plugin's option
			$options[$k]['refresh'] = false;
			// Remove parameter 'hidden'
			if (!empty($v['hidden']))
				unset($options[$k]['hidden']);
			// Add description
			if ($v['type'] == 'info')
				$options[$k]['desc'] = wp_kses_data(__('In order to see changes made by settings of this section, click "Save" and refresh the page', 'ostende'));
		}
		return $options;
	}
}

// Theme init priorities:
// 3 - add/remove Theme Options elements
if (!function_exists('ostende_trx_addons_setup3')) {
	add_action( 'after_setup_theme', 'ostende_trx_addons_setup3', 3 );
	function ostende_trx_addons_setup3() {
		
		// Section 'Cars' - settings to show 'Cars' blog archive and single posts
		if (ostende_exists_cars()) {
			ostende_storage_merge_array('options', '', array_merge(
				array(
					'cars' => array(
						"title" => esc_html__('Cars', 'ostende'),
						"desc" => wp_kses_data( __('Select parameters to display the cars pages.', 'ostende') ),
						"type" => "section"
						)
				),
				ostende_trx_addons_get_list_cpt_options('cars'),
				ostende_options_get_list_cpt_options('cars'),
				array(
					"single_info_cars" => array(
						"title" => esc_html__('Single car', 'ostende'),
						"desc" => '',
						"type" => "info",
						),
					'show_related_posts_cars' => array(
						"title" => esc_html__('Show related posts', 'ostende'),
						"desc" => wp_kses_data( __("Show section 'Related cars' on the single car page", 'ostende') ),
						"std" => 1,
						"type" => "checkbox"
						),
					'related_posts_cars' => array(
						"title" => esc_html__('Related cars', 'ostende'),
						"desc" => wp_kses_data( __('How many related cars should be displayed on the single car page?', 'ostende') ),
						"dependency" => array(
							'show_related_posts_cars' => array(1)
						),
						"std" => 3,
						"options" => ostende_get_list_range(1,9),
						"type" => "select"
						),
					'related_columns_cars' => array(
						"title" => esc_html__('Related columns', 'ostende'),
						"desc" => wp_kses_data( __('How many columns should be used to output related cars on the single car page?', 'ostende') ),
						"dependency" => array(
							'show_related_posts_cars' => array(1)
						),
						"std" => 3,
						"options" => ostende_get_list_range(1,4),
						"type" => "select"
						)
				)
			));
		}
		
		// Section 'Certificates'
		if (ostende_exists_certificates()) {
			ostende_storage_merge_array('options', '', array_merge(
				array(
					'certificates' => array(
						"title" => esc_html__('Certificates', 'ostende'),
						"desc" => wp_kses_data( __('Select parameters to display "Certificates"', 'ostende') ),
						"type" => "section"
						)
				),
				ostende_trx_addons_get_list_cpt_options('certificates')
			));
		}
		
		// Section 'Courses' - settings to show 'Courses' blog archive and single posts
		if (ostende_exists_courses()) {
		
			ostende_storage_merge_array('options', '', array_merge(
				array(
					'courses' => array(
						"title" => esc_html__('Courses', 'ostende'),
						"desc" => wp_kses_data( __('Select parameters to display the courses pages', 'ostende') ),
						"type" => "section"
						)
				),
				ostende_trx_addons_get_list_cpt_options('courses'),
				ostende_options_get_list_cpt_options('courses'),
				array(
					"single_info_courses" => array(
						"title" => esc_html__('Single course', 'ostende'),
						"desc" => '',
						"type" => "info",
						),
					'show_related_posts_courses' => array(
						"title" => esc_html__('Show related posts', 'ostende'),
						"desc" => wp_kses_data( __("Show section 'Related courses' on the single course page", 'ostende') ),
						"std" => 1,
						"type" => "checkbox"
						),
					'related_posts_courses' => array(
						"title" => esc_html__('Related courses', 'ostende'),
						"desc" => wp_kses_data( __('How many related courses should be displayed on the single course page?', 'ostende') ),
						"dependency" => array(
							'show_related_posts_courses' => array(1)
						),
						"std" => 3,
						"options" => ostende_get_list_range(1,9),
						"type" => "select"
						),
					'related_columns_courses' => array(
						"title" => esc_html__('Related columns', 'ostende'),
						"desc" => wp_kses_data( __('How many columns should be used to output related courses on the single course page?', 'ostende') ),
						"dependency" => array(
							'show_related_posts_courses' => array(1)
						),
						"std" => 3,
						"options" => ostende_get_list_range(1,4),
						"type" => "select"
						)
				)
			));
		}
		
		// Section 'Dishes' - settings to show 'Dishes' blog archive and single posts
		if (ostende_exists_dishes()) {

			ostende_storage_merge_array('options', '', array_merge(
				array(
					'dishes' => array(
						"title" => esc_html__('Dishes', 'ostende'),
						"desc" => wp_kses_data( __('Select parameters to display the dishes pages', 'ostende') ),
						"type" => "section"
						)
				),
				ostende_trx_addons_get_list_cpt_options('dishes'),
				ostende_options_get_list_cpt_options('dishes'),
				array(
					"single_info_dishes" => array(
						"title" => esc_html__('Single dish', 'ostende'),
						"desc" => '',
						"type" => "info",
						),
					'show_related_posts_dishes' => array(
						"title" => esc_html__('Show related posts', 'ostende'),
						"desc" => wp_kses_data( __("Show section 'Related dishes' on the single dish page", 'ostende') ),
						"std" => 1,
						"type" => "checkbox"
						),
					'related_posts_dishes' => array(
						"title" => esc_html__('Related dishes', 'ostende'),
						"desc" => wp_kses_data( __('How many related dishes should be displayed on the single dish page?', 'ostende') ),
						"dependency" => array(
							'show_related_posts_dishes' => array(1)
						),
						"std" => 3,
						"options" => ostende_get_list_range(1,9),
						"type" => "select"
						),
					'related_columns_dishes' => array(
						"title" => esc_html__('Related columns', 'ostende'),
						"desc" => wp_kses_data( __('How many columns should be used to output related dishes on the single dish page?', 'ostende') ),
						"dependency" => array(
							'show_related_posts_dishes' => array(1)
						),
						"std" => 3,
						"options" => ostende_get_list_range(1,4),
						"type" => "select"
						)
					)
				)
			);
		}
		
		// Section 'Portfolio' - settings to show 'Portfolio' blog archive and single posts
		if (ostende_exists_portfolio()) {
			ostende_storage_merge_array('options', '', array_merge(
				array(
					'portfolio' => array(
						"title" => esc_html__('Portfolio', 'ostende'),
						"desc" => wp_kses_data( __('Select parameters to display the portfolio pages', 'ostende') ),
						"type" => "section"
						)
				),
				ostende_trx_addons_get_list_cpt_options('portfolio'),
				ostende_options_get_list_cpt_options('portfolio'),
				array(
					"single_info_portfolio" => array(
						"title" => esc_html__('Single portfolio item', 'ostende'),
						"desc" => '',
						"type" => "info",
						),
					'show_related_posts_portfolio' => array(
						"title" => esc_html__('Show related posts', 'ostende'),
						"desc" => wp_kses_data( __("Show section 'Related portfolio items' on the single portfolio page", 'ostende') ),
						"std" => 1,
						"type" => "checkbox"
						),
					'related_posts_portfolio' => array(
						"title" => esc_html__('Related portfolio items', 'ostende'),
						"desc" => wp_kses_data( __('How many related portfolio items should be displayed on the single portfolio page?', 'ostende') ),
						"dependency" => array(
							'show_related_posts_portfolio' => array(1)
						),
						"std" => 3,
						"options" => ostende_get_list_range(1,9),
						"type" => "select"
						),
					'related_columns_portfolio' => array(
						"title" => esc_html__('Related columns', 'ostende'),
						"desc" => wp_kses_data( __('How many columns should be used to output related portfolio on the single portfolio page?', 'ostende') ),
						"dependency" => array(
							'show_related_posts_portfolio' => array(1)
						),
						"std" => 3,
						"options" => ostende_get_list_range(1,4),
						"type" => "select"
						)
				)
			));
		}
		
		// Section 'Properties' - settings to show 'Properties' blog archive and single posts
		if (ostende_exists_properties()) {
		
			ostende_storage_merge_array('options', '', array_merge(
				array(
					'properties' => array(
						"title" => esc_html__('Properties', 'ostende'),
						"desc" => wp_kses_data( __('Select parameters to display the properties pages', 'ostende') ),
						"type" => "section"
						)
				),
				ostende_trx_addons_get_list_cpt_options('properties'),
				ostende_options_get_list_cpt_options('properties'),
				array(
					"single_info_properties" => array(
						"title" => esc_html__('Single property', 'ostende'),
						"desc" => '',
						"type" => "info",
						),
					'show_related_posts_properties' => array(
						"title" => esc_html__('Show related posts', 'ostende'),
						"desc" => wp_kses_data( __("Show section 'Related properties' on the single property page", 'ostende') ),
						"std" => 1,
						"type" => "checkbox"
						),
					'related_posts_properties' => array(
						"title" => esc_html__('Related properties', 'ostende'),
						"desc" => wp_kses_data( __('How many related properties should be displayed on the single property page?', 'ostende') ),
						"dependency" => array(
							'show_related_posts_properties' => array(1)
						),
						"std" => 3,
						"options" => ostende_get_list_range(1,9),
						"type" => "select"
						),
					'related_columns_properties' => array(
						"title" => esc_html__('Related columns', 'ostende'),
						"desc" => wp_kses_data( __('How many columns should be used to output related properties on the single property page?', 'ostende') ),
						"dependency" => array(
							'show_related_posts_properties' => array(1)
						),
						"std" => 3,
						"options" => ostende_get_list_range(1,4),
						"type" => "select"
						)
					)
				)
			);
		}
		
		// Section 'Resume'
		if (ostende_exists_resume()) {
			ostende_storage_merge_array('options', '', array_merge(
				array(
					'resume' => array(
						"title" => esc_html__('Resume', 'ostende'),
						"desc" => wp_kses_data( __('Select parameters to display "Resume"', 'ostende') ),
						"type" => "section"
						)
				),
				ostende_trx_addons_get_list_cpt_options('resume')
			));
		}
		
		// Section 'Services' - settings to show 'Services' blog archive and single posts
		if (ostende_exists_services()) {
		
			ostende_storage_merge_array('options', '', array_merge(
				array(
					'services' => array(
						"title" => esc_html__('Services', 'ostende'),
						"desc" => wp_kses_data( __('Select parameters to display the services pages', 'ostende') ),
						"type" => "section"
						)
				),
				ostende_trx_addons_get_list_cpt_options('services'),
				ostende_options_get_list_cpt_options('services'),
				array(
					"single_info_services" => array(
						"title" => esc_html__('Single service item', 'ostende'),
						"desc" => '',
						"type" => "info",
						),
					'show_related_posts_services' => array(
						"title" => esc_html__('Show related posts', 'ostende'),
						"desc" => wp_kses_data( __("Show section 'Related services' on the single service page", 'ostende') ),
						"std" => 0,
						"type" => "checkbox"
						),
					'related_posts_services' => array(
						"title" => esc_html__('Related services', 'ostende'),
						"desc" => wp_kses_data( __('How many related services should be displayed on the single service page?', 'ostende') ),
						"dependency" => array(
							'show_related_posts_services' => array(1)
						),
						"std" => 3,
						"options" => ostende_get_list_range(1,9),
						"type" => "select"
						),
					'related_columns_services' => array(
						"title" => esc_html__('Related columns', 'ostende'),
						"desc" => wp_kses_data( __('How many columns should be used to output related services on the single service page?', 'ostende') ),
						"dependency" => array(
							'show_related_posts_services' => array(1)
						),
						"std" => 3,
						"options" => ostende_get_list_range(1,4),
						"type" => "select"
						)
				)
			));
		}
		
		// Section 'Sport' - settings to show 'Sport' blog archive and single posts
		if (ostende_exists_sport()) {
			ostende_storage_merge_array('options', '', array_merge(
				array(
					'sport' => array(
						"title" => esc_html__('Sport', 'ostende'),
						"desc" => wp_kses_data( __('Select parameters to display the sport pages', 'ostende') ),
						"type" => "section"
						)
				),
				ostende_trx_addons_get_list_cpt_options('sport'),
				ostende_options_get_list_cpt_options('sport')
			));
		}
		
		// Section 'Team' - settings to show 'Team' blog archive and single posts
		if (ostende_exists_team()) {
			ostende_storage_merge_array('options', '', array_merge(
				array(
					'team' => array(
						"title" => esc_html__('Team', 'ostende'),
						"desc" => wp_kses_data( __('Select parameters to display the team members pages.', 'ostende') ),
						"type" => "section"
						)
				),
				ostende_trx_addons_get_list_cpt_options('team'),
				ostende_options_get_list_cpt_options('team')
			));
		}
		
		// Section 'Testimonials'
		if (ostende_exists_resume()) {
			ostende_storage_merge_array('options', '', array_merge(
				array(
					'testimonials' => array(
						"title" => esc_html__('Testimonials', 'ostende'),
						"desc" => wp_kses_data( __('Select parameters to display "Testimonials"', 'ostende') ),
						"type" => "section"
						)
				),
				ostende_trx_addons_get_list_cpt_options('testimonials')
			));
		}
	}
}

// Add 'layout edit' link to the 'description' in the 'header_style' and 'footer_style' parameters
if ( !function_exists('ostende_trx_addons_add_link_edit_layout') ) {
	function ostende_trx_addons_add_link_edit_layout() {
		static $added = false;
		if ($added) return;
		$added = true;
		$options = ostende_storage_get('options');
		foreach($options as $k=>$v) {
			if (!isset($v['std'])) continue;
			$k1 = substr($k, 0, 12);
			if ($k1=='header_style' || $k1=='footer_style') {
				$layout = ostende_get_theme_option($k);
				if (ostende_is_inherit($layout))
					$layout = ostende_get_theme_option($k1);
				if (!empty($layout)) {
					$layout = explode('-', $layout);
					$layout = $layout[count($layout)-1];
					if ($layout > 0) {
						ostende_storage_set_array2('options', $k, 'desc', $v['desc']
												. '<br>'
												. sprintf('<a href="%1$s" class="ostende_post_editor'.(intval($layout)==0 ? ' ostende_hidden' : '').'" target="_blank">%2$s</a>',
															admin_url( sprintf( "post.php?post=%d&amp;action=edit", $layout ) ),
															esc_html__("Open selected layout in a new tab to edit", 'ostende')
														)
												);
					}
				}
			}
		}
	}
}


// Setup internal plugin's parameters
if (!function_exists('ostende_trx_addons_init_settings')) {
	add_filter( 'trx_addons_init_settings', 'ostende_trx_addons_init_settings');
	function ostende_trx_addons_init_settings($settings) {
		$settings['socials_type']	= ostende_get_theme_setting('socials_type');
		$settings['icons_type']		= ostende_get_theme_setting('icons_type');
		$settings['icons_selector']	= ostende_get_theme_setting('icons_selector');
		return $settings;
	}
}



/* Plugin's support utilities
------------------------------------------------------------------------------- */

// Check if plugin installed and activated
if ( !function_exists( 'ostende_exists_trx_addons' ) ) {
	function ostende_exists_trx_addons() {
		return defined('TRX_ADDONS_VERSION');
	}
}

// Return true if cars is supported
if ( !function_exists( 'ostende_exists_cars' ) ) {
	function ostende_exists_cars() {
		return defined('TRX_ADDONS_CPT_CARS_PT');
	}
}

// Return true if certificates is supported
if ( !function_exists( 'ostende_exists_certificates' ) ) {
	function ostende_exists_certificates() {
		return defined('TRX_ADDONS_CPT_CERTIFICATES_PT');
	}
}

// Return true if courses is supported
if ( !function_exists( 'ostende_exists_courses' ) ) {
	function ostende_exists_courses() {
		return defined('TRX_ADDONS_CPT_COURSES_PT');
	}
}

// Return true if dishes is supported
if ( !function_exists( 'ostende_exists_dishes' ) ) {
	function ostende_exists_dishes() {
		return defined('TRX_ADDONS_CPT_DISHES_PT');
	}
}

// Return true if layouts is supported
if ( !function_exists( 'ostende_exists_layouts' ) ) {
	function ostende_exists_layouts() {
		return defined('TRX_ADDONS_CPT_LAYOUTS_PT');
	}
}

// Return true if portfolio is supported
if ( !function_exists( 'ostende_exists_portfolio' ) ) {
	function ostende_exists_portfolio() {
		return defined('TRX_ADDONS_CPT_PORTFOLIO_PT');
	}
}

// Return true if properties is supported
if ( !function_exists( 'ostende_exists_properties' ) ) {
	function ostende_exists_properties() {
		return defined('TRX_ADDONS_CPT_PROPERTIES_PT');
	}
}

// Return true if resume is supported
if ( !function_exists( 'ostende_exists_resume' ) ) {
	function ostende_exists_resume() {
		return defined('TRX_ADDONS_CPT_RESUME_PT');
	}
}

// Return true if services is supported
if ( !function_exists( 'ostende_exists_services' ) ) {
	function ostende_exists_services() {
		return defined('TRX_ADDONS_CPT_SERVICES_PT');
	}
}

// Return true if sport is supported
if ( !function_exists( 'ostende_exists_sport' ) ) {
	function ostende_exists_sport() {
		return defined('TRX_ADDONS_CPT_COMPETITIONS_PT');
	}
}

// Return true if team is supported
if ( !function_exists( 'ostende_exists_team' ) ) {
	function ostende_exists_team() {
		return defined('TRX_ADDONS_CPT_TEAM_PT');
	}
}

// Return true if testimonials is supported
if ( !function_exists( 'ostende_exists_testimonials' ) ) {
	function ostende_exists_testimonials() {
		return defined('TRX_ADDONS_CPT_TESTIMONIALS_PT');
	}
}


// Return true if it's cars page
if ( !function_exists( 'ostende_is_cars_page' ) ) {
	function ostende_is_cars_page() {
		return function_exists('trx_addons_is_cars_page') && trx_addons_is_cars_page();
	}
}

// Return true if it's courses page
if ( !function_exists( 'ostende_is_courses_page' ) ) {
	function ostende_is_courses_page() {
		return function_exists('trx_addons_is_courses_page') && trx_addons_is_courses_page();
	}
}

// Return true if it's dishes page
if ( !function_exists( 'ostende_is_dishes_page' ) ) {
	function ostende_is_dishes_page() {
		return function_exists('trx_addons_is_dishes_page') && trx_addons_is_dishes_page();
	}
}

// Return true if it's properties page
if ( !function_exists( 'ostende_is_properties_page' ) ) {
	function ostende_is_properties_page() {
		return function_exists('trx_addons_is_properties_page') && trx_addons_is_properties_page();
	}
}

// Return true if it's portfolio page
if ( !function_exists( 'ostende_is_portfolio_page' ) ) {
	function ostende_is_portfolio_page() {
		return function_exists('trx_addons_is_portfolio_page') && trx_addons_is_portfolio_page();
	}
}

// Return true if it's services page
if ( !function_exists( 'ostende_is_services_page' ) ) {
	function ostende_is_services_page() {
		return function_exists('trx_addons_is_services_page') && trx_addons_is_services_page();
	}
}

// Return true if it's team page
if ( !function_exists( 'ostende_is_team_page' ) ) {
	function ostende_is_team_page() {
		return function_exists('trx_addons_is_team_page') && trx_addons_is_team_page();
	}
}

// Return true if it's sport page
if ( !function_exists( 'ostende_is_sport_page' ) ) {
	function ostende_is_sport_page() {
		return function_exists('trx_addons_is_sport_page') && trx_addons_is_sport_page();
	}
}

// Return true if custom layouts are available
if ( !function_exists( 'ostende_is_layouts_available' ) ) {
	function ostende_is_layouts_available() {
		return ostende_exists_trx_addons() 
										&& (
											function_exists('ostende_exists_sop') && ostende_exists_sop()
											||
											function_exists('ostende_exists_elementor') && ostende_exists_elementor()
											||
											function_exists('ostende_exists_visual_composer') && ostende_exists_visual_composer()
											);
	}
}

// Detect current blog mode
if ( !function_exists( 'ostende_trx_addons_detect_blog_mode' ) ) {
	function ostende_trx_addons_detect_blog_mode($mode='') {
		if ( ostende_is_cars_page() )
			$mode = 'cars';
		else if ( ostende_is_courses_page() )
			$mode = 'courses';
		else if ( ostende_is_dishes_page() )
			$mode = 'dishes';
		else if ( ostende_is_properties_page() )
			$mode = 'properties';
		else if ( ostende_is_portfolio_page() )
			$mode = 'portfolio';
		else if ( ostende_is_services_page() )
			$mode = 'services';
		else if ( ostende_is_sport_page() )
			$mode = 'sport';
		else if ( ostende_is_team_page() )
			$mode = 'team';
		return $mode;
	}
}

// Disallow increment views counter on the blog archive
if ( !function_exists( 'ostende_trx_addons_inc_views' ) ) {
	function ostende_trx_addons_inc_views($allow=false) {
		return $allow && is_page() && ostende_storage_isset('blog_archive') ? false : $allow;
	}
}

// Add team, courses, etc. to the supported posts list
if ( !function_exists( 'ostende_trx_addons_list_post_types' ) ) {
	function ostende_trx_addons_list_post_types($list=array()) {
		if (function_exists('trx_addons_get_cpt_list')) {
			$cpt_list = trx_addons_get_cpt_list();
			foreach ($cpt_list as $cpt => $title) {
				if (   
					   (defined('TRX_ADDONS_CPT_CARS_PT') && $cpt == TRX_ADDONS_CPT_CARS_PT)
					|| (defined('TRX_ADDONS_CPT_COURSES_PT') && $cpt == TRX_ADDONS_CPT_COURSES_PT)
					|| (defined('TRX_ADDONS_CPT_DISHES_PT') && $cpt == TRX_ADDONS_CPT_DISHES_PT)
					|| (defined('TRX_ADDONS_CPT_PORTFOLIO_PT') && $cpt == TRX_ADDONS_CPT_PORTFOLIO_PT)
					|| (defined('TRX_ADDONS_CPT_PROPERTIES_PT') && $cpt == TRX_ADDONS_CPT_PROPERTIES_PT)
					|| (defined('TRX_ADDONS_CPT_SERVICES_PT') && $cpt == TRX_ADDONS_CPT_SERVICES_PT)
					|| (defined('TRX_ADDONS_CPT_COMPETITIONS_PT') && $cpt == TRX_ADDONS_CPT_COMPETITIONS_PT)
					|| (defined('TRX_ADDONS_CPT_TEAM_PT') && $cpt == TRX_ADDONS_CPT_TEAM_PT)
					)
					$list[$cpt] = $title;
			}
		}
		return $list;
	}
}

// Return taxonomy for current post type
if ( !function_exists( 'ostende_trx_addons_post_type_taxonomy' ) ) {
	function ostende_trx_addons_post_type_taxonomy($tax='', $post_type='') {
		if ( defined('TRX_ADDONS_CPT_CARS_PT') && $post_type == TRX_ADDONS_CPT_CARS_PT )
			$tax = TRX_ADDONS_CPT_CARS_TAXONOMY_MAKER;
		else if ( defined('TRX_ADDONS_CPT_COURSES_PT') && $post_type == TRX_ADDONS_CPT_COURSES_PT )
			$tax = TRX_ADDONS_CPT_COURSES_TAXONOMY;
		else if ( defined('TRX_ADDONS_CPT_DISHES_PT') && $post_type == TRX_ADDONS_CPT_DISHES_PT )
			$tax = TRX_ADDONS_CPT_DISHES_TAXONOMY;
		else if ( defined('TRX_ADDONS_CPT_PORTFOLIO_PT') && $post_type == TRX_ADDONS_CPT_PORTFOLIO_PT )
			$tax = TRX_ADDONS_CPT_PORTFOLIO_TAXONOMY;
		else if ( defined('TRX_ADDONS_CPT_PROPERTIES_PT') && $post_type == TRX_ADDONS_CPT_PROPERTIES_PT )
			$tax = TRX_ADDONS_CPT_PROPERTIES_TAXONOMY_TYPE;
		else if ( defined('TRX_ADDONS_CPT_SERVICES_PT') && $post_type == TRX_ADDONS_CPT_SERVICES_PT )
			$tax = TRX_ADDONS_CPT_SERVICES_TAXONOMY;
		else if ( defined('TRX_ADDONS_CPT_COMPETITIONS_PT') && $post_type == TRX_ADDONS_CPT_COMPETITIONS_PT )
			$tax = TRX_ADDONS_CPT_COMPETITIONS_TAXONOMY;
		else if ( defined('TRX_ADDONS_CPT_TEAM_PT') && $post_type == TRX_ADDONS_CPT_TEAM_PT )
			$tax = TRX_ADDONS_CPT_TEAM_TAXONOMY;
		return $tax;
	}
}

// Show categories of the team, courses, etc.
if ( !function_exists( 'ostende_trx_addons_get_post_categories' ) ) {
	function ostende_trx_addons_get_post_categories($cats='') {

		if ( defined('TRX_ADDONS_CPT_CARS_PT') ) {
			if (get_post_type()==TRX_ADDONS_CPT_CARS_PT) {
				$cats = ostende_get_post_terms(', ', get_the_ID(), TRX_ADDONS_CPT_CARS_TAXONOMY_TYPE);
			}
		}
		if ( defined('TRX_ADDONS_CPT_COURSES_PT') ) {
			if (get_post_type()==TRX_ADDONS_CPT_COURSES_PT) {
				$cats = ostende_get_post_terms(', ', get_the_ID(), TRX_ADDONS_CPT_COURSES_TAXONOMY);
			}
		}
		if ( defined('TRX_ADDONS_CPT_DISHES_PT') ) {
			if (get_post_type()==TRX_ADDONS_CPT_DISHES_PT) {
				$cats = ostende_get_post_terms(', ', get_the_ID(), TRX_ADDONS_CPT_DISHES_TAXONOMY);
			}
		}
		if ( defined('TRX_ADDONS_CPT_PORTFOLIO_PT') ) {
			if (get_post_type()==TRX_ADDONS_CPT_PORTFOLIO_PT) {
				$cats = ostende_get_post_terms(', ', get_the_ID(), TRX_ADDONS_CPT_PORTFOLIO_TAXONOMY);
			}
		}
		if ( defined('TRX_ADDONS_CPT_PROPERTIES_PT') ) {
			if (get_post_type()==TRX_ADDONS_CPT_PROPERTIES_PT) {
				$cats = ostende_get_post_terms(', ', get_the_ID(), TRX_ADDONS_CPT_PROPERTIES_TAXONOMY_TYPE);
			}
		}
		if ( defined('TRX_ADDONS_CPT_SERVICES_PT') ) {
			if (get_post_type()==TRX_ADDONS_CPT_SERVICES_PT) {
				$cats = ostende_get_post_terms(', ', get_the_ID(), TRX_ADDONS_CPT_SERVICES_TAXONOMY);
			}
		}
		if ( defined('TRX_ADDONS_CPT_COMPETITIONS_PT') ) {
			if (get_post_type()==TRX_ADDONS_CPT_COMPETITIONS_PT) {
				$cats = ostende_get_post_terms(', ', get_the_ID(), TRX_ADDONS_CPT_COMPETITIONS_TAXONOMY);
			}
		}
		if ( defined('TRX_ADDONS_CPT_TEAM_PT') ) {
			if (get_post_type()==TRX_ADDONS_CPT_TEAM_PT) {
				$cats = ostende_get_post_terms(', ', get_the_ID(), TRX_ADDONS_CPT_TEAM_TAXONOMY);
			}
		}
		return $cats;
	}
}

// Show post's date with the theme-specific format
if ( !function_exists( 'ostende_trx_addons_get_post_date_wrap' ) ) {
	function ostende_trx_addons_get_post_date_wrap($dt='') {
		return apply_filters('ostende_filter_get_post_date', $dt);
	}
}

// Show date of the courses
if ( !function_exists( 'ostende_trx_addons_get_post_date' ) ) {
	function ostende_trx_addons_get_post_date($dt='') {

		if ( defined('TRX_ADDONS_CPT_COURSES_PT') && get_post_type()==TRX_ADDONS_CPT_COURSES_PT) {
			$meta = get_post_meta(get_the_ID(), 'trx_addons_options', true);
			$dt = $meta['date'];
			// Translators: Add formatted date to the output
			$dt = sprintf($dt < date('Y-m-d') ? esc_html__('Started on %s', 'ostende') : esc_html__('Starting %s', 'ostende'), 
					date_i18n(get_option('date_format'), strtotime($dt)));

		} else if ( defined('TRX_ADDONS_CPT_COMPETITIONS_PT') && in_array(get_post_type(), array(TRX_ADDONS_CPT_COMPETITIONS_PT, TRX_ADDONS_CPT_ROUNDS_PT, TRX_ADDONS_CPT_MATCHES_PT))) {
			$meta = get_post_meta(get_the_ID(), 'trx_addons_options', true);
			$dt = $meta['date_start'];
			// Translators: Add formatted date to the output
			$dt = sprintf($dt < date('Y-m-d').(!empty($meta['time_start']) ? ' H:i' : '') ? esc_html__('Started on %s', 'ostende') : esc_html__('Starting %s', 'ostende'), 
					date_i18n(get_option('date_format') . (!empty($meta['time_start']) ? ' '.get_option('time_format') : ''), strtotime($dt.(!empty($meta['time_start']) ? ' '.trim($meta['time_start']) : ''))));

		} else if ( defined('TRX_ADDONS_CPT_COMPETITIONS_PT') && get_post_type() == TRX_ADDONS_CPT_PLAYERS_PT) {
			// Uncomment (remove) next line if you want to show player's birthday in the page title block
			if (false) {
				$meta = get_post_meta(get_the_ID(), 'trx_addons_options', true);
				// Translators: Add formatted date to the output
				$dt = !empty($meta['birthday']) ? sprintf(esc_html__('Birthday: %s', 'ostende'), date_i18n(get_option('date_format'), strtotime($meta['birthday']))) : '';
			} else
				$dt = '';
		}
		return $dt;
	}
}

// Check if override options is allowed
if (!function_exists('ostende_trx_addons_allow_override_options')) {
	function ostende_trx_addons_allow_override_options($allow, $post_type) {
		return $allow
					|| (defined('TRX_ADDONS_CPT_CARS_PT') && in_array($post_type, array(
																				TRX_ADDONS_CPT_CARS_PT,
																				TRX_ADDONS_CPT_CARS_AGENTS_PT
																				)))
					|| (defined('TRX_ADDONS_CPT_COURSES_PT') && $post_type==TRX_ADDONS_CPT_COURSES_PT)
					|| (defined('TRX_ADDONS_CPT_DISHES_PT') && $post_type==TRX_ADDONS_CPT_DISHES_PT)
					|| (defined('TRX_ADDONS_CPT_PORTFOLIO_PT') && $post_type==TRX_ADDONS_CPT_PORTFOLIO_PT) 
					|| (defined('TRX_ADDONS_CPT_PROPERTIES_PT') && in_array($post_type, array(
																				TRX_ADDONS_CPT_PROPERTIES_PT,
																				TRX_ADDONS_CPT_AGENTS_PT
																				)))
					|| (defined('TRX_ADDONS_CPT_RESUME_PT') && $post_type==TRX_ADDONS_CPT_RESUME_PT) 
					|| (defined('TRX_ADDONS_CPT_SERVICES_PT') && $post_type==TRX_ADDONS_CPT_SERVICES_PT) 
					|| (defined('TRX_ADDONS_CPT_COMPETITIONS_PT') && in_array($post_type, array(
																				TRX_ADDONS_CPT_COMPETITIONS_PT,
																				TRX_ADDONS_CPT_ROUNDS_PT,
																				TRX_ADDONS_CPT_MATCHES_PT
																				)))
					|| (defined('TRX_ADDONS_CPT_TEAM_PT') && $post_type==TRX_ADDONS_CPT_TEAM_PT);
	}
}

// Check if theme icons is allowed
if (!function_exists('ostende_trx_addons_allow_theme_icons')) {
	function ostende_trx_addons_allow_theme_icons($allow, $post_type) {
		$screen = function_exists('get_current_screen') ? get_current_screen() : false;
		return $allow
					|| (defined('TRX_ADDONS_CPT_LAYOUTS_PT') && $post_type==TRX_ADDONS_CPT_LAYOUTS_PT)
					|| (!empty($screen->id) && in_array($screen->id, array(
																		'appearance_page_trx_addons_options',
																		'profile'
																	)
														)
						);
	}
}

// Disable theme-specific fields in the exported options
if (!function_exists('ostende_trx_addons_export_options')) {
	function ostende_trx_addons_export_options($options) {
		// ThemeREX Addons
		if (!empty($options['trx_addons_options'])) {
			$options['trx_addons_options']['debug_mode'] = 0;
			$options['trx_addons_options']['api_google'] = '';
			$options['trx_addons_options']['api_google_analitics'] = '';
			$options['trx_addons_options']['api_google_remarketing'] = '';
			$options['trx_addons_options']['demo_enable'] = 0;
			$options['trx_addons_options']['demo_referer'] = '';
			$options['trx_addons_options']['demo_default_url'] = '';
			$options['trx_addons_options']['demo_logo'] = '';
			$options['trx_addons_options']['demo_post_type'] = '';
			$options['trx_addons_options']['demo_taxonomy'] = '';
			$options['trx_addons_options']['demo_logo'] = '';
			$options['trx_addons_options']['demo_logo'] = '';
			unset($options['trx_addons_options']['themes_market_referals']);
		}
		// ThemeREX Donations
		if (!empty($options['trx_donations_options'])) {
			$options['trx_donations_options']['pp_account'] = '';
		}
		// WooCommerce
		if (!empty($options['woocommerce_paypal_settings'])) {
			$options['woocommerce_paypal_settings']['email'] = '';
			$options['woocommerce_paypal_settings']['receiver_email'] = '';
			$options['woocommerce_paypal_settings']['identity_token'] = '';
		}
		return $options;		
	}
}

// Set related posts and columns for the plugin's output
if (!function_exists('ostende_trx_addons_args_related')) {
	function ostende_trx_addons_args_related($args) {
		if (!empty($args['template_args_name']) 
			&& in_array($args['template_args_name'], 
				array('trx_addons_args_sc_cars', 
					  'trx_addons_args_sc_courses',
					  'trx_addons_args_sc_dishes',
					  'trx_addons_args_sc_portfolio',
					  'trx_addons_args_sc_properties',
  					  'trx_addons_args_sc_services'))) {
			$args['posts_per_page'] = (int) ostende_get_theme_option('show_related_posts')
												? ostende_get_theme_option('related_posts')
												: 0;
			$args['columns'] = ostende_get_theme_option('related_columns');
		}
		return $args;
	}
}
// Add 'custom' to the headers types list
if ( !function_exists( 'ostende_trx_addons_list_header_footer_types' ) ) {
	function ostende_trx_addons_list_header_footer_types($list=array()) {
		if (ostende_exists_layouts()) {
			$list['custom'] = esc_html__('Custom', 'ostende');
		}
		return $list;
	}
}

// Add layouts to the headers list
if ( !function_exists( 'ostende_trx_addons_list_header_styles' ) ) {
	function ostende_trx_addons_list_header_styles($list=array()) {
		if (ostende_exists_layouts()) {
			$layouts = ostende_get_list_posts(false, array(
							'post_type' => TRX_ADDONS_CPT_LAYOUTS_PT,
							'meta_key' => 'trx_addons_layout_type',
							'meta_value' => 'header',
							'orderby' => 'ID',
							'order' => 'asc',
							'not_selected' => false
							)
						);
			$new_list = array();
			foreach ($layouts as $id=>$title) {
				if ($id != 'none') $new_list['header-custom-'.intval($id)] = $title;
			}
			$list = ostende_array_merge($new_list, $list);
		}
		return $list;
	}
}

// Add layouts to the footers list
if ( !function_exists( 'ostende_trx_addons_list_footer_styles' ) ) {
	function ostende_trx_addons_list_footer_styles($list=array()) {
		if (ostende_exists_layouts()) {
			$layouts = ostende_get_list_posts(false, array(
							'post_type' => TRX_ADDONS_CPT_LAYOUTS_PT,
							'meta_key' => 'trx_addons_layout_type',
							'meta_value' => 'footer',
							'orderby' => 'ID',
							'order' => 'asc',
							'not_selected' => false
							)
						);
			$new_list = array();
			foreach ($layouts as $id=>$title) {
				if ($id != 'none') $new_list['footer-custom-'.intval($id)] = $title;
			}
			$list = ostende_array_merge($new_list, $list);
		}
		return $list;
	}
}


// Replace {{Y}} or {Y} with the current year in the layouts
if ( !function_exists( 'ostende_trx_addons_replace_current_year' ) ) {
	function ostende_trx_addons_replace_current_year($content, $post_id=0) {
		return str_replace(array('{{Y}}', '{Y}'), date('Y'), $content);
	}
}


// Add theme-specific layouts to the list
if (!function_exists('ostende_trx_addons_default_layouts')) {
	function ostende_trx_addons_default_layouts($default_layouts=array()) {
		if (ostende_storage_isset('trx_addons_default_layouts')) {
			$layouts = ostende_storage_get('trx_addons_default_layouts');
		} else {
			require_once OSTENDE_THEME_DIR . 'theme-specific/trx_addons-layouts.php';
			if (!isset($layouts) || !is_array($layouts))
				$layouts = array();
			ostende_storage_set('trx_addons_default_layouts', $layouts);
		}
		if (count($layouts) > 0)
			$default_layouts = array_merge($default_layouts, $layouts);
		return $default_layouts;
	}
}



// Enqueue custom styles
if ( !function_exists( 'ostende_trx_addons_layouts_styles' ) ) {
	function ostende_trx_addons_layouts_styles() {
		if (!ostende_exists_trx_addons()) {
			if (ostende_get_file_dir('plugins/trx_addons/layouts/layouts.css')!='') {
				wp_enqueue_style( 'ostende-trx-addons-layouts',  ostende_get_file_url('plugins/trx_addons/layouts/layouts.css'), array(), null );
			}
			if (ostende_get_file_dir('plugins/trx_addons/layouts/layouts.responsive.css')!='') {
				wp_enqueue_style( 'ostende-trx-addons-layouts-responsive',  ostende_get_file_url('plugins/trx_addons/layouts/layouts.responsive.css'), array(), null );
			}
		}
	}
}

// Enqueue custom styles and scripts
if ( !function_exists( 'ostende_trx_addons_frontend_scripts' ) ) {
	function ostende_trx_addons_frontend_scripts() {
		if (ostende_exists_trx_addons()) {
			if (ostende_is_on(ostende_get_theme_option('debug_mode')) && ostende_get_file_dir('plugins/trx_addons/trx_addons.js')!='')
				wp_enqueue_script( 'ostende-trx-addons', ostende_get_file_url('plugins/trx_addons/trx_addons.js'), array('jquery'), null, true );
		}
	}
}
	
// Merge custom styles
if ( !function_exists( 'ostende_trx_addons_merge_styles' ) ) {
	function ostende_trx_addons_merge_styles($list) {
		$list[] = 'plugins/trx_addons/_trx_addons.scss';
		return $list;
	}
}

// Merge responsive styles
if ( !function_exists( 'ostende_trx_addons_merge_styles_responsive' ) ) {
	function ostende_trx_addons_merge_styles_responsive($list) {
		$list[] = 'plugins/trx_addons/_trx_addons-responsive.scss';
		return $list;
	}
}

// Add theme-specific vars to the list of responsive files of ThemeREX Addons
if ( !function_exists( 'ostende_trx_addons_sass_import' ) ) {
	function ostende_trx_addons_sass_import($output='', $file='') {
		if (strpos($file, 'vars.scss') !== false)
			$output .= "\n" . ostende_fgc(ostende_get_file_dir('css/_theme-vars.scss')) . "\n";
		return $output;
	}
}
	
// Merge custom scripts
if ( !function_exists( 'ostende_trx_addons_merge_scripts' ) ) {
	function ostende_trx_addons_merge_scripts($list) {
		$list[] = 'plugins/trx_addons/trx_addons.js';
		return $list;
	}
}



// Plugin API - theme-specific wrappers for plugin functions
//------------------------------------------------------------------------

// Check if URL contain specified string
if (!function_exists('ostende_check_url')) {
	function ostende_check_url($val='', $defa=false) {
		return function_exists('trx_addons_check_url') 
					? trx_addons_check_url($val) 
					: $defa;
	}
}

// Check if layouts components are showed or set new state
if (!function_exists('ostende_sc_layouts_showed')) {
	function ostende_sc_layouts_showed($name, $val=null) {
		if (function_exists('trx_addons_sc_layouts_showed')) {
			if ($val!==null)
				trx_addons_sc_layouts_showed($name, $val);
			else
				return trx_addons_sc_layouts_showed($name);
		} else {
			if ($val!==null)
				ostende_storage_set_array('sc_layouts_components', $name, $val);
			else
				return ostende_storage_get_array('sc_layouts_components', $name);
		}
	}
}

// Return image size multiplier
if (!function_exists('ostende_get_retina_multiplier')) {
	function ostende_get_retina_multiplier($force_retina=0) {
		$mult = function_exists('trx_addons_get_retina_multiplier') ? trx_addons_get_retina_multiplier($force_retina) : 1;
		return max(1, $mult);
	}
}

// Return slider layout
if (!function_exists('ostende_get_slider_layout')) {
	function ostende_get_slider_layout($args) {
		return function_exists('trx_addons_get_slider_layout') 
					? trx_addons_get_slider_layout($args) 
					: '';
	}
}

// Return video player layout
if (!function_exists('ostende_get_video_layout')) {
	function ostende_get_video_layout($args) {
		return function_exists('trx_addons_get_video_layout') 
					? trx_addons_get_video_layout($args) 
					: '';
	}
}

// Return theme specific layout of the featured image block
if ( !function_exists( 'ostende_trx_addons_featured_image' ) ) {
	function ostende_trx_addons_featured_image($processed=false, $args=array()) {
		$args['show_no_image'] = true;
		$args['singular'] = false;
		$args['hover'] = isset($args['hover']) && $args['hover']=='' ? '' : ostende_get_theme_option('image_hover');
		ostende_show_post_featured($args);
		return true;
	}
}

// Remove some thumb-sizes from the ThemeREX Addons list
if ( !function_exists( 'ostende_trx_addons_add_thumb_sizes' ) ) {
	function ostende_trx_addons_add_thumb_sizes($list=array()) {
		if (is_array($list)) {
			$thumb_sizes = ostende_storage_get('theme_thumbs');
			foreach ($thumb_sizes as $v) {
				if (!empty($v['subst']) && isset($list[$v['subst']]))
					unset($list[$v['subst']]);
			}
		}
		return $list;
	}
}

// and replace removed styles with theme-specific thumb size
if ( !function_exists( 'ostende_trx_addons_get_thumb_size' ) ) {
	function ostende_trx_addons_get_thumb_size($thumb_size='') {
		$thumb_sizes = ostende_storage_get('theme_thumbs');
		foreach ($thumb_sizes as $k => $v) {
			if (strpos($thumb_size, $v['subst']) !== false) {
				$thumb_size = str_replace($thumb_size, $v['subst'], $k);
				break;
			}
		}
		return $thumb_size;
	}
}

// Return theme specific 'no-image' picture
if ( !function_exists( 'ostende_trx_addons_no_image' ) ) {
	function ostende_trx_addons_no_image($no_image='') {
		return ostende_get_no_image($no_image);
	}
}

// Return theme-specific icons
if ( !function_exists( 'ostende_trx_addons_get_list_icons' ) ) {
	function ostende_trx_addons_get_list_icons($list, $prepend_inherit) {
		return ostende_get_list_icons($prepend_inherit);
	}
}

// Return links to the social profiles
if (!function_exists('ostende_get_socials_links')) {
	function ostende_get_socials_links($style='icons') {
		return function_exists('trx_addons_get_socials_links') 
					? trx_addons_get_socials_links($style)
					: '';
	}
}

// Return links to share post
if (!function_exists('ostende_get_share_links')) {
	function ostende_get_share_links($args=array()) {
		return function_exists('trx_addons_get_share_links') 
					? trx_addons_get_share_links($args)
					: '';
	}
}

// Display links to share post
if (!function_exists('ostende_show_share_links')) {
	function ostende_show_share_links($args=array()) {
		if (function_exists('trx_addons_get_share_links')) {
			$args['echo'] = true;
			trx_addons_get_share_links($args);
		}
	}
}

// Return post icon
if (!function_exists('ostende_get_post_icon')) {
	function ostende_get_post_icon($post_id=0) {
		return function_exists('trx_addons_get_post_icon') 
					? trx_addons_get_post_icon($post_id)
					: '';
	}
}

// Show reactions in the single posts
if (!function_exists('ostende_trx_addons_action_before_post_meta')) {
	function ostende_trx_addons_action_before_post_meta() {
		if (trx_addons_is_on(trx_addons_get_option('emotions_allowed')) && is_single() && !is_attachment())
			trx_addons_get_post_reactions(true);
	}
}


// Return image from the category
if (!function_exists('ostende_get_category_image')) {
	function ostende_get_category_image($term_id=0) {
		return function_exists('trx_addons_get_category_image') 
					? trx_addons_get_category_image($term_id)
					: '';
	}
}

// Return small image (icon) from the category
if (!function_exists('ostende_get_category_icon')) {
	function ostende_get_category_icon($term_id=0) {
		return function_exists('trx_addons_get_category_icon') 
					? trx_addons_get_category_icon($term_id)
					: '';
	}
}

// Return string with counters items
if (!function_exists('ostende_get_post_counters')) {
	function ostende_get_post_counters($counters='views') {
		return function_exists('trx_addons_get_post_counters')
					? str_replace('post_counters_item', 'post_meta_item post_counters_item', trx_addons_get_post_counters($counters))
					: '';
	}
}

// Return list with animation effects
if (!function_exists('ostende_get_list_animations_in')) {
	function ostende_get_list_animations_in() {
		return function_exists('trx_addons_get_list_animations_in') 
					? trx_addons_get_list_animations_in()
					: array();
	}
}

// Return classes list for the specified animation
if (!function_exists('ostende_get_animation_classes')) {
	function ostende_get_animation_classes($animation, $speed='normal', $loop='none') {
		return function_exists('trx_addons_get_animation_classes') 
					? trx_addons_get_animation_classes($animation, $speed, $loop)
					: '';
	}
}

// Return string with the likes counter for the specified comment
if (!function_exists('ostende_get_comment_counters')) {
	function ostende_get_comment_counters($counters = 'likes') {
		return function_exists('trx_addons_get_comment_counters') 
					? trx_addons_get_comment_counters($counters)
					: '';
	}
}

// Display likes counter for the specified comment
if (!function_exists('ostende_show_comment_counters')) {
	function ostende_show_comment_counters($counters = 'likes') {
		if (function_exists('trx_addons_get_comment_counters'))
			trx_addons_get_comment_counters($counters, true);
	}
}

// Add query params to sort posts by views or likes
if (!function_exists('ostende_trx_addons_query_sort_order')) {
	function ostende_trx_addons_query_sort_order($q=array(), $orderby='date', $order='desc') {
		if ($orderby == 'views') {
			$q['orderby'] = 'meta_value_num';
			$q['meta_key'] = 'trx_addons_post_views_count';
		} else if ($orderby == 'likes') {
			$q['orderby'] = 'meta_value_num';
			$q['meta_key'] = 'trx_addons_post_likes_count';
		}
		return $q;
	}
}

// Return theme-specific logo to the plugin
if ( !function_exists( 'ostende_trx_addons_theme_logo' ) ) {
	function ostende_trx_addons_theme_logo($logo) {
		return ostende_get_logo_image();
	}
}

// Return true, if theme allow use site name as logo
if ( !function_exists( 'ostende_trx_addons_show_site_name_as_logo' ) ) {
	function ostende_trx_addons_show_site_name_as_logo($allow=true) {
		return $allow && ostende_is_on(ostende_get_theme_option('logo_text'));
	}
}

// Return theme-specific post meta to the plugin
if ( !function_exists( 'ostende_trx_addons_post_meta' ) ) {
	function ostende_trx_addons_post_meta($meta, $args=array()) {
		return ostende_show_post_meta(apply_filters('ostende_filter_post_meta_args', $args, 'trx_addons', 1));
	}
}

// Return theme-specific post meta args
if ( !function_exists( 'ostende_trx_addons_post_meta_args' ) ) {
	function ostende_trx_addons_post_meta_args($args=array(), $from='', $columns=1) {
		if (is_single() && $from=='trx_addons') {
			$args['components'] = ostende_array_get_keys_by_value(ostende_get_theme_option('meta_parts'));
			$args['counters'] = ostende_array_get_keys_by_value(ostende_get_theme_option('counters'));
		}
		return $args;
	}
}

// Check if featured image override is allowed
if ( !function_exists( 'ostende_trx_addons_featured_image_override' ) ) {
	function ostende_trx_addons_featured_image_override($flag=false) {
		if ($flag) {
			$flag = ostende_is_on(ostende_get_theme_option('header_image_override')) 
					&& apply_filters('ostende_filter_allow_override_header_image', true);
		}
		return $flag;
	}
}

// Return featured image for current mode (post/page/category/blog template ...)
if ( !function_exists( 'ostende_trx_addons_get_current_mode_image' ) ) {
	function ostende_trx_addons_get_current_mode_image($img='') {
		return ostende_get_current_mode_image($img);
	}
}

// Return featured image size for related posts
if ( !function_exists( 'ostende_trx_addons_related_thumb_size' ) ) {
	function ostende_trx_addons_related_thumb_size($size='') {
		if (defined('TRX_ADDONS_CPT_CERTIFICATES_PT') && get_post_type() == TRX_ADDONS_CPT_CERTIFICATES_PT)
			$size = ostende_get_thumb_size( 'masonry-big' );
		return $size;
	}
}
	
// Redirect action 'get_mobile_menu' to the plugin
// Return stored items as mobile menu
if ( !function_exists( 'ostende_trx_addons_get_mobile_menu' ) ) {
	function ostende_trx_addons_get_mobile_menu($menu) {
		return apply_filters('trx_addons_filter_get_mobile_menu', $menu);
	}
}

// Redirect action 'login' to the plugin
if (!function_exists('ostende_trx_addons_action_login')) {
	function ostende_trx_addons_action_login($args=array()) {
		do_action( 'trx_addons_action_login', $args );
	}
}

// Redirect action 'cart' to the plugin
if (!function_exists('ostende_trx_addons_action_cart')) {
	function ostende_trx_addons_action_cart($args=array()) {
		do_action( 'trx_addons_action_cart', $args );
	}
}

// Redirect action 'search' to the plugin
if (!function_exists('ostende_trx_addons_action_search')) {
	function ostende_trx_addons_action_search($style, $class, $ajax) {
		if (ostende_exists_trx_addons())
			do_action( 'trx_addons_action_search', $style, $class, $ajax );
		else {
			set_query_var('ostende_search_args', compact('style', 'class', 'ajax'));
			get_template_part('templates/search-form');
			set_query_var('ostende_search_args', array());
		}
	}
}

// Redirect action 'breadcrumbs' to the plugin
if (!function_exists('ostende_trx_addons_action_breadcrumbs')) {
	function ostende_trx_addons_action_breadcrumbs() {
		do_action( 'trx_addons_action_breadcrumbs' );
	}
}

// Redirect action 'show_layout' to the plugin
if (!function_exists('ostende_trx_addons_action_show_layout')) {
	function ostende_trx_addons_action_show_layout($layout_id='') {
		do_action( 'trx_addons_action_show_layout', $layout_id );
	}
}

// Redirect filter 'get_translated_layout' to the plugin
if (!function_exists('ostende_trx_addons_filter_get_translated_layout')) {
	function ostende_trx_addons_filter_get_translated_layout($layout_id='') {
		return apply_filters( 'trx_addons_filter_get_translated_layout', $layout_id );
	}
}

// Show user meta (socials)
if (!function_exists('ostende_trx_addons_action_user_meta')) {
	function ostende_trx_addons_action_user_meta() {
		do_action( 'trx_addons_action_user_meta' );
	}
}

// Redirect filter 'get_blog_title' to the plugin
if ( !function_exists( 'ostende_trx_addons_get_blog_title' ) ) {
	function ostende_trx_addons_get_blog_title($title='') {
		return apply_filters('trx_addons_filter_get_blog_title', $title);
	}
}

// Redirect filter 'get_post_iframe' to the plugin
if ( !function_exists( 'ostende_trx_addons_get_post_iframe' ) ) {
	function ostende_trx_addons_get_post_iframe($html='') {
		return apply_filters('trx_addons_filter_get_post_iframe', $html);
	}
}


// Redirect filter 'prepare_css' to the plugin
if (!function_exists('ostende_trx_addons_prepare_css')) {
	function ostende_trx_addons_prepare_css($css='', $remove_spaces=true) {
		return apply_filters( 'trx_addons_filter_prepare_css', $css, $remove_spaces );
	}
}

// Redirect filter 'prepare_js' to the plugin
if (!function_exists('ostende_trx_addons_prepare_js')) {
	function ostende_trx_addons_prepare_js($js='', $remove_spaces=true) {
		return apply_filters( 'trx_addons_filter_prepare_js', $js, $remove_spaces );
	}
}

// Add plugin's specific variables to the scripts
if (!function_exists('ostende_trx_addons_localize_script')) {
	function ostende_trx_addons_localize_script($arr) {
		$arr['trx_addons_exists'] = ostende_exists_trx_addons();
		return $arr;
	}
}

// Add plugin-specific colors and fonts to the custom CSS
if (ostende_exists_trx_addons()) { require_once OSTENDE_THEME_DIR . 'plugins/trx_addons/trx_addons-styles.php'; }
?>