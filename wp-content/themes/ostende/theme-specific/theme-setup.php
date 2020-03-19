<?php
/**
 * Setup theme-specific fonts and colors
 *
 
 * @subpackage OSTENDE
 * @since OSTENDE 1.0.22
 */

if (!defined("OSTENDE_THEME_FREE"))		define("OSTENDE_THEME_FREE", true);
if (!defined("OSTENDE_THEME_FREE_WP"))	define("OSTENDE_THEME_FREE_WP", true);

// Theme storage
$OSTENDE_STORAGE = array(
	// Theme required plugin's slugs
	'required_plugins' => array_merge(

		// List of plugins
		//-----------------------------------------------------
		array(
			// Required plugins
			// DON'T COMMENT OR REMOVE NEXT LINES!
			'trx_addons'					=> esc_html__('ThemeREX Addons', 'ostende'),

			// Recommended (supported) plugins fot both (lite and full) versions
			// If plugin not need - comment (or remove) it
			'elementor'						=> esc_html__('Elementor', 'ostende'),
			'contact-form-7'				=> esc_html__('Contact Form 7', 'ostende'),
			'instagram-feed'				=> esc_html__('Custom Feeds for Instagram', 'ostende'),
			'custom-twitter-feeds'			=> esc_html__('Custom Twitter Feeds', 'ostende'),
			'mailchimp-for-wp'				=> esc_html__('MailChimp for WP', 'ostende'),
			'wp-gdpr-compliance'			=> esc_html__('WP GDPR Compliance', 'ostende')
		),

		// List of additional plugins
		//-----------------------------------------------------
		array() 
	),
	
	// Key: market[env|loc]-vendor[axiom|ancora|themerex]
	'theme_pro_key'		=> 'env-themerex',

	// Theme-specific URLs (will be escaped in place of the output)
	'theme_demo_url'	=> 'http://ostende.themerex.net',
	'theme_doc_url'		=> 'http://ostende.themerex.net/doc',
	'theme_download_url'    => 'https://themerex.net/downloads/free-theater-wordpress-theme/',

	'theme_support_url'	=> 'http://themerex.ticksy.com',								// ThemeREX

	'theme_video_url'	=> 'https://www.youtube.com/channel/UCnFisBimrK2aIE-hnY70kCA',	// ThemeREX

	// Responsive resolutions
	// Parameters to create css media query: min, max, 
	'responsive' => array(
						// By device
						'desktop'	=> array('min' => 1680),
						'notebook'	=> array('min' => 1280, 'max' => 1679),
						'tablet'	=> array('min' =>  768, 'max' => 1279),
						'mobile'	=> array('max' =>  767),
						// By size
						'xxl'		=> array('max' => 1679),
						'xl'		=> array('max' => 1439),
						'lg'		=> array('max' => 1279),
						'md'		=> array('max' => 1023),
						'sm'		=> array('max' =>  767),
						'sm_wp'		=> array('max' =>  600),
						'xs'		=> array('max' =>  479)
						)
);

// Theme init priorities:
// Action 'after_setup_theme'
// 1 - register filters to add/remove lists items in the Theme Options
// 2 - create Theme Options
// 3 - add/remove Theme Options elements
// 5 - load Theme Options. Attention! After this step you can use only basic options (not overriden)
// 9 - register other filters (for installer, etc.)
//10 - standard Theme init procedures (not ordered)
// Action 'wp_loaded'
// 1 - detect override mode. Attention! Only after this step you can use overriden options (separate values for the shop, courses, etc.)

if ( !function_exists('ostende_customizer_theme_setup1') ) {
	add_action( 'after_setup_theme', 'ostende_customizer_theme_setup1', 1 );
	function ostende_customizer_theme_setup1() {

		// -----------------------------------------------------------------
		// -- ONLY FOR PROGRAMMERS, NOT FOR CUSTOMER
		// -- Internal theme settings
		// -----------------------------------------------------------------
		ostende_storage_set('settings', array(
			
			'duplicate_options'		=> 'child',		// none  - use separate options for the main and the child-theme
													// child - duplicate theme options from the main theme to the child-theme only
													// both  - sinchronize changes in the theme options between main and child themes

			'customize_refresh'		=> 'auto',		// Refresh method for preview area in the Appearance - Customize:
													// auto - refresh preview area on change each field with Theme Options
													// manual - refresh only obn press button 'Refresh' at the top of Customize frame

			'max_load_fonts'		=> 5,			// Max fonts number to load from Google fonts or from uploaded fonts

			'comment_maxlength'		=> 1000,		// Max length of the message from contact form

			'comment_after_name'	=> true,		// Place 'comment' field before the 'name' and 'email'

			'socials_type'			=> 'icons',		// Type of socials:
													// icons - use font icons to present social networks
													// images - use images from theme's folder trx_addons/css/icons.png

			'icons_type'			=> 'icons',		// Type of other icons:
													// icons - use font icons to present icons
													// images - use images from theme's folder trx_addons/css/icons.png

			'icons_selector'		=> 'internal',	// Icons selector in the shortcodes:
													// vc (default) - standard VC icons selector (very slow and don't support images)
													// internal - internal popup with plugin's or theme's icons list (fast)
			'check_min_version'		=> true,		// Check if exists a .min version of .css and .js and return path to it
													// instead the path to the original file
													// (if debug_mode is off and modification time of the original file < time of the .min file)
			'autoselect_menu'		=> false,		// Show any menu if no menu selected in the location 'main_menu'
													// (for example, the theme is just activated)
			'disable_jquery_ui'		=> false,		// Prevent loading custom jQuery UI libraries in the third-party plugins
		
			'use_mediaelements'		=> true,		// Load script "Media Elements" to play video and audio
			
			'tgmpa_upload'			=> false,		// Allow upload not pre-packaged plugins via TGMPA
			
			'allow_no_image'		=> false		// Allow use image placeholder if no image present in the blog, related posts, post navigation, etc.
		));


		// -----------------------------------------------------------------
		// -- Theme fonts (Google and/or custom fonts)
		// -----------------------------------------------------------------
		
		// Fonts to load when theme start
		// It can be Google fonts or uploaded fonts, placed in the folder /css/font-face/font-name inside the theme folder
		// Attention! Font's folder must have name equal to the font's name, with spaces replaced on the dash '-'
		// For example: font name 'TeX Gyre Termes', folder 'TeX-Gyre-Termes'
		ostende_storage_set('load_fonts', array(
			// Google font
			array(
				'name'	 => 'Open Sans',
				'family' => 'sans-serif',
				'styles' => '300,300i,400,400i,600,600i,700,700i,800'		// Parameter 'style' used only for the Google fonts
				),
			// Font-face packed with theme
			array(
				'name'   => 'TeX Gyre Termes',
				'family' => 'sans-serif'
				)
		));
		
		// Characters subset for the Google fonts. Available values are: latin,latin-ext,cyrillic,cyrillic-ext,greek,greek-ext,vietnamese
		ostende_storage_set('load_fonts_subset', 'latin,latin-ext');
		
		// Settings of the main tags
		// Attention! Font name in the parameter 'font-family' will be enclosed in the quotes and no spaces after comma!

		ostende_storage_set('theme_fonts', array(
			'p' => array(
				'title'				=> esc_html__('Main text', 'ostende'),
				'description'		=> esc_html__('Font settings of the main text of the site. Attention! For correct display of the site on mobile devices, use only units "rem", "em" or "ex"', 'ostende'),
				'font-family'		=> '"Open Sans",sans-serif',
				'font-size' 		=> '1rem',
				'font-weight'		=> '400',
				'font-style'		=> 'normal',
				'line-height'		=> '1.88em',
				'text-decoration'	=> 'none',
				'text-transform'	=> 'none',
				'letter-spacing'	=> '0px',
				'margin-top'		=> '0em',
				'margin-bottom'		=> '1.15em'
				),
			'h1' => array(
				'title'				=> esc_html__('Heading 1', 'ostende'),
				'font-family'		=> '"TeX Gyre Termes",sans-serif',
				'font-size' 		=> '4.706em',
				'font-weight'		=> '400',
				'font-style'		=> 'normal',
				'line-height'		=> '1.2em',
				'text-decoration'	=> 'none',
				'text-transform'	=> 'none',
				'letter-spacing'	=> '0px',
				'margin-top'		=> '0.81em',
				'margin-bottom'		=> '0.38em'
				),
			'h2' => array(
				'title'				=> esc_html__('Heading 2', 'ostende'),
                'font-family'		=> '"TeX Gyre Termes",sans-serif',
				'font-size' 		=> '4.118em',
				'font-weight'		=> '400',
				'font-style'		=> 'normal',
				'line-height'		=> '1.15em',
				'text-decoration'	=> 'none',
				'text-transform'	=> 'none',
				'letter-spacing'	=> '0px',
				'margin-top'		=> '1em',
				'margin-bottom'		=> '0.51em'
				),
			'h3' => array(
				'title'				=> esc_html__('Heading 3', 'ostende'),
                'font-family'		=> '"TeX Gyre Termes",sans-serif',
				'font-size' 		=> '3.118em',
				'font-weight'		=> '400',
				'font-style'		=> 'normal',
				'line-height'		=> '1.12em',
				'text-decoration'	=> 'none',
				'text-transform'	=> 'none',
				'letter-spacing'	=> '0px',
				'margin-top'		=> '1.08em',
				'margin-bottom'		=> '0.7879em'
				),
			'h4' => array(
				'title'				=> esc_html__('Heading 4', 'ostende'),
                'font-family'		=> '"TeX Gyre Termes",sans-serif',
				'font-size' 		=> '1.882em',
				'font-weight'		=> '400',
				'font-style'		=> 'normal',
				'line-height'		=> '1.28em',
				'text-decoration'	=> 'none',
				'text-transform'	=> 'none',
				'letter-spacing'	=> '0px',
				'margin-top'		=> '1.5em',
				'margin-bottom'		=> '1em'
				),
			'h5' => array(
				'title'				=> esc_html__('Heading 5', 'ostende'),
                'font-family'		=> '"TeX Gyre Termes",sans-serif',
				'font-size' 		=> '1.529em',
				'font-weight'		=> '400',
				'font-style'		=> 'normal',
				'line-height'		=> '1.23em',
				'text-decoration'	=> 'none',
				'text-transform'	=> 'none',
				'letter-spacing'	=> '0px',
				'margin-top'		=> '1.59em',
				'margin-bottom'		=> '1.3em'
				),
			'h6' => array(
				'title'				=> esc_html__('Heading 6', 'ostende'),
                'font-family'		=> '"TeX Gyre Termes",sans-serif',
				'font-size' 		=> '1.294em',
				'font-weight'		=> '400',
				'font-style'		=> 'normal',
				'line-height'		=> '1.28em',
				'text-decoration'	=> 'none',
				'text-transform'	=> 'none',
				'letter-spacing'	=> '0px',
				'margin-top'		=> '2em',
				'margin-bottom'		=> '0.85em'
				),
			'logo' => array(
				'title'				=> esc_html__('Logo text', 'ostende'),
				'description'		=> esc_html__('Font settings of the text case of the logo', 'ostende'),
				'font-family'		=> '"Open Sans",sans-serif',
				'font-size' 		=> '1.8em',
				'font-weight'		=> '400',
				'font-style'		=> 'normal',
				'line-height'		=> '1.25em',
				'text-decoration'	=> 'none',
				'text-transform'	=> 'uppercase',
				'letter-spacing'	=> '1px'
				),
			'button' => array(
				'title'				=> esc_html__('Buttons', 'ostende'),
        'font-family'		=> '"Open Sans",sans-serif',
				'font-size' 		=> '13px',
				'font-weight'		=> '400',
				'font-style'		=> 'normal',
				'line-height'		=> '20px',
				'text-decoration'	=> 'none',
				'text-transform'	=> 'uppercase',
				'letter-spacing'	=> '1.5px'
				),
			'input' => array(
				'title'				=> esc_html__('Input fields', 'ostende'),
				'description'		=> esc_html__('Font settings of the input fields, dropdowns and textareas', 'ostende'),
                'font-family'		=> '"Open Sans",sans-serif',
				'font-size' 		=> '1rem',
				'font-weight'		=> '400',
				'font-style'		=> 'normal',
				'line-height'		=> 'normal',	// Attention! Firefox don't allow line-height less then 1.5em in the select
				'text-decoration'	=> 'none',
				'text-transform'	=> 'none',
				'letter-spacing'	=> '0px'
				),
			'info' => array(
				'title'				=> esc_html__('Post meta', 'ostende'),
				'description'		=> esc_html__('Font settings of the post meta: date, counters, share, etc.', 'ostende'),
        'font-family'		=> '"Open Sans",sans-serif',
				'font-size' 		=> '13px',
				'font-weight'		=> '400',
				'font-style'		=> 'normal',
				'line-height'		=> '1.7em',
				'text-decoration'	=> 'none',
				'text-transform'	=> 'uppercase',
				'letter-spacing'	=> '0px',
				'margin-top'		=> '0.4em',
				'margin-bottom'		=> '0px'
				),
			'menu' => array(
				'title'				=> esc_html__('Main menu', 'ostende'),
				'description'		=> esc_html__('Font settings of the main menu items', 'ostende'),
				'font-family'		=> '"Open Sans",sans-serif',
				'font-size' 		=> '1em',
				'font-weight'		=> '400',
				'font-style'		=> 'normal',
				'line-height'		=> '1.5em',
				'text-decoration'	=> 'none',
				'text-transform'	=> 'none',
				'letter-spacing'	=> '0px'
				),
			'submenu' => array(
				'title'				=> esc_html__('Dropdown menu', 'ostende'),
				'description'		=> esc_html__('Font settings of the dropdown menu items', 'ostende'),
				'font-family'		=> '"Open Sans",sans-serif',
				'font-size' 		=> '1em',
				'font-weight'		=> '400',
				'font-style'		=> 'normal',
				'line-height'		=> '1.5em',
				'text-decoration'	=> 'none',
				'text-transform'	=> 'none',
				'letter-spacing'	=> '0px'
				)
		));
		
		
		// -----------------------------------------------------------------
		// -- Theme colors for customizer
		// -- Attention! Inner scheme must be last in the array below
		// -----------------------------------------------------------------
		ostende_storage_set('scheme_color_groups', array(
			'main'	=> array(
							'title'			=> esc_html__('Main', 'ostende'),
							'description'	=> esc_html__('Colors of the main content area', 'ostende')
							),
			'alter'	=> array(
							'title'			=> esc_html__('Alter', 'ostende'),
							'description'	=> esc_html__('Colors of the alternative blocks (sidebars, etc.)', 'ostende')
							),
			'extra'	=> array(
							'title'			=> esc_html__('Extra', 'ostende'),
							'description'	=> esc_html__('Colors of the extra blocks (dropdowns, price blocks, table headers, etc.)', 'ostende')
							),
			'inverse' => array(
							'title'			=> esc_html__('Inverse', 'ostende'),
							'description'	=> esc_html__('Colors of the inverse blocks - when link color used as background of the block (dropdowns, blockquotes, etc.)', 'ostende')
							),
			'input'	=> array(
							'title'			=> esc_html__('Input', 'ostende'),
							'description'	=> esc_html__('Colors of the form fields (text field, textarea, select, etc.)', 'ostende')
							),
			)
		);
		ostende_storage_set('scheme_color_names', array(
			'bg_color'	=> array(
							'title'			=> esc_html__('Background color', 'ostende'),
							'description'	=> esc_html__('Background color of this block in the normal state', 'ostende')
							),
			'bg_hover'	=> array(
							'title'			=> esc_html__('Background hover', 'ostende'),
							'description'	=> esc_html__('Background color of this block in the hovered state', 'ostende')
							),
			'bd_color'	=> array(
							'title'			=> esc_html__('Border color', 'ostende'),
							'description'	=> esc_html__('Border color of this block in the normal state', 'ostende')
							),
			'bd_hover'	=>  array(
							'title'			=> esc_html__('Border hover', 'ostende'),
							'description'	=> esc_html__('Border color of this block in the hovered state', 'ostende')
							),
			'text'		=> array(
							'title'			=> esc_html__('Text', 'ostende'),
							'description'	=> esc_html__('Color of the plain text inside this block', 'ostende')
							),
			'text_dark'	=> array(
							'title'			=> esc_html__('Text dark', 'ostende'),
							'description'	=> esc_html__('Color of the dark text (bold, header, etc.) inside this block', 'ostende')
							),
			'text_light'=> array(
							'title'			=> esc_html__('Text light', 'ostende'),
							'description'	=> esc_html__('Color of the light text (post meta, etc.) inside this block', 'ostende')
							),
			'text_link'	=> array(
							'title'			=> esc_html__('Link', 'ostende'),
							'description'	=> esc_html__('Color of the links inside this block', 'ostende')
							),
			'text_hover'=> array(
							'title'			=> esc_html__('Link hover', 'ostende'),
							'description'	=> esc_html__('Color of the hovered state of links inside this block', 'ostende')
							),
			'text_link2'=> array(
							'title'			=> esc_html__('Link 2', 'ostende'),
							'description'	=> esc_html__('Color of the accented texts (areas) inside this block', 'ostende')
							),
			'text_hover2'=> array(
							'title'			=> esc_html__('Link 2 hover', 'ostende'),
							'description'	=> esc_html__('Color of the hovered state of accented texts (areas) inside this block', 'ostende')
							),
			'text_link3'=> array(
							'title'			=> esc_html__('Link 3', 'ostende'),
							'description'	=> esc_html__('Color of the other accented texts (buttons) inside this block', 'ostende')
							),
			'text_hover3'=> array(
							'title'			=> esc_html__('Link 3 hover', 'ostende'),
							'description'	=> esc_html__('Color of the hovered state of other accented texts (buttons) inside this block', 'ostende')
							)
			)
		);
		ostende_storage_set('schemes', array(
		
			// Color scheme: 'default'
			'default' => array(
				'title'	 => esc_html__('Default', 'ostende'),
				'colors' => array(
					
					// Whole block border and background
					'bg_color'			=> '#ffffff',
					'bd_color'			=> '#e8e8e9', //ok
		
					// Text and links colors
					'text'				=> '#606060', //ok
					'text_light'		=> '#919191', //ok
					'text_dark'			=> '#151414', //ok
					'text_link'			=> '#ce172d', //ok
					'text_hover'		=> '#151414', //ok
					'text_link2'		=> '#ce172d',
					'text_hover2'		=> '#8be77c',
					'text_link3'		=> '#ddb837',
					'text_hover3'		=> '#eec432',
		
					// Alternative blocks (sidebar, tabs, alternative blocks, etc.)
					'alter_bg_color'	=> '#f3f5f7',
					'alter_bg_hover'	=> '#e6e8eb',
					'alter_bd_color'	=> '#e5e5e5',
					'alter_bd_hover'	=> '#dadada',
					'alter_text'		=> '#606060', //ok
					'alter_light'		=> '#b7b7b7',
					'alter_dark'		=> '#1d1d1d',
					'alter_link'		=> '#fe7259',
					'alter_hover'		=> '#72cfd5',
					'alter_link2'		=> '#8be77c',
					'alter_hover2'		=> '#80d572',
					'alter_link3'		=> '#eec432',
					'alter_hover3'		=> '#ddb837',
		
					// Extra blocks (submenu, tabs, color blocks, etc.)
					'extra_bg_color'	=> '#131217', //ok
					'extra_bg_hover'	=> '#28272e',
					'extra_bd_color'	=> '#313131',
					'extra_bd_hover'	=> '#3d3d3d',
					'extra_text'		=> '#ffffff',
					'extra_light'		=> '#afafaf',
					'extra_dark'		=> '#ffffff',
					'extra_link'		=> '#d4a48c', //ok
					'extra_hover'		=> '#fe7259',
					'extra_link2'		=> '#80d572',
					'extra_hover2'		=> '#8be77c',
					'extra_link3'		=> '#ddb837',
					'extra_hover3'		=> '#eec432',
		
					// Input fields (form's fields and textarea)
					'input_bg_color'	=> '#ffffff',
					'input_bg_hover'	=> '#ffffff',
					'input_bd_color'	=> '#e8e8e9', //ok
					'input_bd_hover'	=> '#151414', //ok
					'input_text'		=> '#919191', //ok
					'input_light'		=> '#919191', //ok
					'input_dark'		=> '#151414', //ok
					
					// Inverse blocks (text and links on the 'text_link' background)
					'inverse_bd_color'	=> '#67bcc1',
					'inverse_bd_hover'	=> '#5aa4a9',
					'inverse_text'		=> '#1d1d1d',
					'inverse_light'		=> '#333333',
					'inverse_dark'		=> '#131217',
					'inverse_link'		=> '#ffffff',
					'inverse_hover'		=> '#1d1d1d'
				)
			),
		
			// Color scheme: 'dark'
			'dark' => array(
				'title'  => esc_html__('Dark', 'ostende'),
				'colors' => array(
					
					// Whole block border and background
					'bg_color'			=> '#131217', //ok
					'bd_color'			=> '#1f1e23', //ok
		
					// Text and links colors
					'text'				=> '#99989a', //ok
					'text_light'		=> '#7b7a7a', //ok
					'text_dark'			=> '#ffffff',
					'text_link'			=> '#d4a48c', //ok
					'text_hover'		=> '#ffffff', //ok
					'text_link2'		=> '#ce172d', //ok
					'text_hover2'		=> '#8be77c',
					'text_link3'		=> '#ddb837',
					'text_hover3'		=> '#eec432',

					// Alternative blocks (sidebar, tabs, alternative blocks, etc.)
					'alter_bg_color'	=> '#1f1e23', //ok
					'alter_bg_hover'	=> '#333333',
					'alter_bd_color'	=> '#464646',
					'alter_bd_hover'	=> '#4a4a4a',
					'alter_text'		=> '#b2b2b2', //ok
					'alter_light'		=> '#5f5f5f',
					'alter_dark'		=> '#ffffff',
					'alter_link'		=> '#d4a48c', //ok
					'alter_hover'		=> '#ffffff', //ok
					'alter_link2'		=> '#8be77c',
					'alter_hover2'		=> '#80d572',
					'alter_link3'		=> '#eec432',
					'alter_hover3'		=> '#ddb837',

					// Extra blocks (submenu, tabs, color blocks, etc.)
					'extra_bg_color'	=> '#131217', //ok
					'extra_bg_hover'	=> '#28272e',
					'extra_bd_color'	=> '#464646',
					'extra_bd_hover'	=> '#4a4a4a',
					'extra_text'		=> '#a6a6a6',
					'extra_light'		=> '#5f5f5f',
					'extra_dark'		=> '#ffffff',
					'extra_link'		=> '#d4a48c', //ok
					'extra_hover'		=> '#fe7259',
					'extra_link2'		=> '#80d572',
					'extra_hover2'		=> '#8be77c',
					'extra_link3'		=> '#ddb837',
					'extra_hover3'		=> '#eec432',

					// Input fields (form's fields and textarea)
					'input_bg_color'	=> '#1f1e23', //ok
					'input_bg_hover'	=> '#1f1e23', //ok
					'input_bd_color'	=> '#2b2a2f', //ok
					'input_bd_hover'	=> '#1f1e23', //ok
					'input_text'		=> '#99989a', //ok
					'input_light'		=> '#99989a', //ok
					'input_dark'		=> '#ffffff', //ok
					
					// Inverse blocks (text and links on the 'text_link' background)
					'inverse_bd_color'	=> '#e36650',
					'inverse_bd_hover'	=> '#cb5b47',
					'inverse_text'		=> '#1d1d1d',
					'inverse_light'		=> '#5f5f5f',
					'inverse_dark'		=> '#131217',
					'inverse_link'		=> '#ffffff',
					'inverse_hover'		=> '#1d1d1d'
				)
			)
		
		));
		
		// Simple schemes substitution
		ostende_storage_set('schemes_simple', array(
			// Main color	// Slave elements and it's darkness koef.
			'text_link'		=> array('alter_hover' => 1,	'extra_link' => 1, 'inverse_bd_color' => 0.85, 'inverse_bd_hover' => 0.7),
			'text_hover'	=> array('alter_link' => 1,		'extra_hover' => 1),
			'text_link2'	=> array('alter_hover2' => 1,	'extra_link2' => 1),
			'text_hover2'	=> array('alter_link2' => 1,	'extra_hover2' => 1),
			'text_link3'	=> array('alter_hover3' => 1,	'extra_link3' => 1),
			'text_hover3'	=> array('alter_link3' => 1,	'extra_hover3' => 1)
		));

		// Additional colors for each scheme
		ostende_storage_set('scheme_colors_add', array(
			'bg_color_0'		=> array('color' => 'bg_color',			'alpha' => 0),
			'bg_color_02'		=> array('color' => 'bg_color',			'alpha' => 0.2),
			'bg_color_07'		=> array('color' => 'bg_color',			'alpha' => 0.7),
			'bg_color_08'		=> array('color' => 'bg_color',			'alpha' => 0.8),
			'bg_color_09'		=> array('color' => 'bg_color',			'alpha' => 0.95),
			'alter_bg_color_07'	=> array('color' => 'alter_bg_color',	'alpha' => 0.7),
			'alter_bg_color_04'	=> array('color' => 'alter_bg_color',	'alpha' => 0.4),
			'alter_bg_color_02'	=> array('color' => 'alter_bg_color',	'alpha' => 0.2),
			'alter_bd_color_02'	=> array('color' => 'alter_bd_color',	'alpha' => 0.2),
			'alter_link_02'		=> array('color' => 'alter_link',		'alpha' => 0.2),
			'alter_link_07'		=> array('color' => 'alter_link',		'alpha' => 0.7),
			'extra_bg_color_07'	=> array('color' => 'extra_bg_color',	'alpha' => 0.7),
			'extra_link_02'		=> array('color' => 'extra_link',		'alpha' => 0.2),
			'extra_link_07'		=> array('color' => 'extra_link',		'alpha' => 0.7),
			'text_dark_07'		=> array('color' => 'text_dark',		'alpha' => 0.7),
			'text_link_02'		=> array('color' => 'text_link',		'alpha' => 0.2),
			'text_link_07'		=> array('color' => 'text_link',		'alpha' => 0.7),
			'text_link_blend'	=> array('color' => 'text_link',		'hue' => 2, 'saturation' => -5, 'brightness' => 5),
			'alter_link_blend'	=> array('color' => 'alter_link',		'hue' => 2, 'saturation' => -5, 'brightness' => 5)
		));
		
		
		// -----------------------------------------------------------------
		// -- Theme specific thumb sizes
		// -----------------------------------------------------------------
		ostende_storage_set('theme_thumbs', apply_filters('ostende_filter_add_thumb_sizes', array(
			// Width of the image is equal to the content area width (without sidebar)
			// Height is fixed
			'ostende-thumb-huge'		=> array(
												'size'	=> array(1278, 700, true),
												'title' => esc_html__( 'Huge image', 'ostende' ),
												'subst'	=> 'trx_addons-thumb-huge'
												),
			// Width of the image is equal to the content area width (with sidebar)
			// Height is fixed
			'ostende-thumb-big' 		=> array(
												'size'	=> array( 842, 474, true),
												'title' => esc_html__( 'Large image', 'ostende' ),
												'subst'	=> 'trx_addons-thumb-big'
												),

			// Width of the image is equal to the 1/3 of the content area width (without sidebar)
			// Height is fixed
			'ostende-thumb-med' 		=> array(
												'size'	=> array( 406, 228, true),
												'title' => esc_html__( 'Medium image', 'ostende' ),
												'subst'	=> 'trx_addons-thumb-medium'
												),

			// Small square image (for avatars in comments, etc.)
			'ostende-thumb-tiny' 		=> array(
												'size'	=> array(  120,  120, true),
												'title' => esc_html__( 'Small square avatar', 'ostende' ),
												'subst'	=> 'trx_addons-thumb-tiny'
												),

			// Width of the image is equal to the content area width (with sidebar)
			// Height is proportional (only downscale, not crop)
			'ostende-thumb-masonry-big' => array(
												'size'	=> array( 812,   0, false),		// Only downscale, not crop
												'title' => esc_html__( 'Masonry Large (scaled)', 'ostende' ),
												'subst'	=> 'trx_addons-thumb-masonry-big'
												),

			// Width of the image is equal to the 1/3 of the full content area width (without sidebar)
			// Height is proportional (only downscale, not crop)
			'ostende-thumb-masonry'		=> array(
												'size'	=> array( 406,   0, false),		// Only downscale, not crop
												'title' => esc_html__( 'Masonry (scaled)', 'ostende' ),
												'subst'	=> 'trx_addons-thumb-masonry'
												),

            'ostende-thumb-extra-big'		=> array(
                                                    'size'	=> array( 406,   467, true),
                                                    'title' => esc_html__( 'Extra Big', 'ostende' ),
                                                    'subst'	=> 'trx_addons-thumb-extra-big'
                                                ),

            'ostende-thumb-extra-height'		=> array(
                                                    'size'	=> array( 297,   400, true),
                                                    'title' => esc_html__( 'Extra Height', 'ostende' ),
                                                    'subst'	=> 'trx_addons-thumb-extra-height'
                                                )

			))
		);
	}
}




//------------------------------------------------------------------------
// One-click import support
//------------------------------------------------------------------------

// Set theme specific importer options
if ( !function_exists( 'ostende_importer_set_options' ) ) {
	add_filter( 'trx_addons_filter_importer_options', 'ostende_importer_set_options', 9 );
	function ostende_importer_set_options($options=array()) {
		if (is_array($options)) {
			// Save or not installer's messages to the log-file
			$options['debug'] = false;
			// Prepare demo data
			$options['demo_url'] = esc_url(ostende_get_protocol() . '://demofiles.themerex.net/ostende/');
			// Required plugins
			$options['required_plugins'] = array_keys((array)ostende_storage_get('required_plugins'));
			// Set number of thumbnails to regenerate when its imported (if demo data was zipped without cropped images)
			// Set 0 to prevent regenerate thumbnails (if demo data archive is already contain cropped images)
			$options['regenerate_thumbnails'] = 3;
			// Default demo
			$options['files']['default']['title'] = esc_html__('OsTende Demo', 'ostende');
			$options['files']['default']['domain_dev'] = '';		// Developers domain
			$options['files']['default']['domain_demo']= esc_url(ostende_get_protocol().'://ostende.themerex.net');		// Demo-site domain

			// Banners
			$options['banners'] = array(

				array(
					'image' => ostende_get_file_url('theme-specific/theme-about/images/documentation.png'),
					'title' => esc_html__('Read Full Documentation', 'ostende'),
					'content' => wp_kses_post(__('Need more details? Please check our full online documentation for detailed information on how to use OsTende.', 'ostende')),
					'link_url' => esc_url(ostende_storage_get('theme_doc_url')),
					'link_caption' => esc_html__('Online Documentation', 'ostende'),
					'duration' => 15
					),
				array(
					'image' => ostende_get_file_url('theme-specific/theme-about/images/video-tutorials.png'),
					'title' => esc_html__('Video Tutorials', 'ostende'),
					'content' => wp_kses_post(__('No time for reading documentation? Check out our video tutorials and learn how to customize OsTende in detail.', 'ostende')),
					'link_url' => esc_url(ostende_storage_get('theme_video_url')),
					'link_caption' => esc_html__('Video Tutorials', 'ostende'),
					'duration' => 15
					),
				array(
					'image' => ostende_get_file_url('theme-specific/theme-about/images/studio.png'),
					'title' => esc_html__('Mockingbird Website Customization Studio', 'ostende'),
					'content' => wp_kses_post(__("Need a website fast? Order our custom service, and we'll build a website based on this theme for a very fair price. We can also implement additional functionality such as website translation, setting up WPML, and much more.", 'ostende')),
					'link_url' => esc_url('//themerex.net/offers/?utm_source=offers&utm_medium=click&utm_campaign=themeinstall'),
					'link_caption' => esc_html__('Contact Us', 'ostende'),
					'duration' => 25
					)
				);
		}
		return $options;
	}
}




// -----------------------------------------------------------------
// -- Theme options for customizer
// -----------------------------------------------------------------
if (!function_exists('ostende_create_theme_options')) {

	function ostende_create_theme_options() {

		// Message about options override. 
		// Attention! Not need esc_html() here, because this message put in wp_kses_data() below
		$msg_override = esc_html__('Attention! Some of these options can be overridden in the following sections (Blog, Plugins settings, etc.) or in the settings of individual pages', 'ostende');
		
		// Color schemes number: if < 2 - hide fields with selectors
		$hide_schemes = count(ostende_storage_get('schemes')) < 2;
		
		ostende_storage_set('options', array(
		
			// 'Logo & Site Identity'
			'title_tagline' => array(
				"title" => esc_html__('Logo & Site Identity', 'ostende'),
				"desc" => '',
				"priority" => 10,
				"type" => "section"
				),
			'logo_info' => array(
				"title" => esc_html__('Logo in the header', 'ostende'),
				"desc" => '',
				"priority" => 20,
				"type" => "info",
				),
			'logo_text' => array(
				"title" => esc_html__('Use Site Name as Logo', 'ostende'),
				"desc" => wp_kses_data( __('Use the site title and tagline as a text logo if no image is selected', 'ostende') ),
				"class" => "ostende_column-1_2 ostende_new_row",
				"priority" => 30,
				"std" => 1,
				"type" => OSTENDE_THEME_FREE ? "hidden" : "checkbox"
				),
			'logo_retina_enabled' => array(
				"title" => esc_html__('Allow retina display logo', 'ostende'),
				"desc" => wp_kses_data( __('Show fields to select logo images for Retina display', 'ostende') ),
				"class" => "ostende_column-1_2",
				"priority" => 40,
				"refresh" => false,
				"std" => 0,
				"type" => OSTENDE_THEME_FREE ? "hidden" : "checkbox"
				),
			'logo_zoom' => array(
				"title" => esc_html__('Logo zoom', 'ostende'),
				"desc" => wp_kses_data( __("Zoom the logo. 1 - original size. Maximum size of logo depends on the actual size of the picture", 'ostende') ),
				"std" => 1,
				"min" => 0.2,
				"max" => 2,
				"step" => 0.1,
				"refresh" => false,
				"type" => OSTENDE_THEME_FREE ? "hidden" : "slider"
				),
			// Parameter 'logo' was replaced with standard WordPress 'custom_logo'
			'logo_retina' => array(
				"title" => esc_html__('Logo for Retina', 'ostende'),
				"desc" => wp_kses_data( __('Select or upload site logo used on Retina displays (if empty - use default logo from the field above)', 'ostende') ),
				"class" => "ostende_column-1_2",
				"priority" => 70,
				"dependency" => array(
					'logo_retina_enabled' => array(1)
				),
				"std" => '',
				"type" => OSTENDE_THEME_FREE ? "hidden" : "image"
				),
			'logo_mobile_header' => array(
				"title" => esc_html__('Logo for the mobile header', 'ostende'),
				"desc" => wp_kses_data( __('Select or upload site logo to display it in the mobile header (if enabled in the section "Header - Header mobile"', 'ostende') ),
				"class" => "ostende_column-1_2 ostende_new_row",
				"std" => '',
				"type" => "image"
				),
			'logo_mobile_header_retina' => array(
				"title" => esc_html__('Logo for the mobile header for Retina', 'ostende'),
				"desc" => wp_kses_data( __('Select or upload site logo used on Retina displays (if empty - use default logo from the field above)', 'ostende') ),
				"class" => "ostende_column-1_2",
				"dependency" => array(
					'logo_retina_enabled' => array(1)
				),
				"std" => '',
				"type" => OSTENDE_THEME_FREE ? "hidden" : "image"
				),
			'logo_mobile' => array(
				"title" => esc_html__('Logo mobile', 'ostende'),
				"desc" => wp_kses_data( __('Select or upload site logo to display it in the mobile menu', 'ostende') ),
				"class" => "ostende_column-1_2 ostende_new_row",
				"std" => '',
				"type" => "image"
				),
			'logo_mobile_retina' => array(
				"title" => esc_html__('Logo mobile for Retina', 'ostende'),
				"desc" => wp_kses_data( __('Select or upload site logo used on Retina displays (if empty - use default logo from the field above)', 'ostende') ),
				"class" => "ostende_column-1_2",
				"dependency" => array(
					'logo_retina_enabled' => array(1)
				),
				"std" => '',
				"type" => OSTENDE_THEME_FREE ? "hidden" : "image"
				),
			'logo_side' => array(
				"title" => esc_html__('Logo side', 'ostende'),
				"desc" => wp_kses_data( __('Select or upload site logo (with vertical orientation) to display it in the side menu', 'ostende') ),
				"class" => "ostende_column-1_2 ostende_new_row",
				"std" => '',
				"type" => "hidden"
				),
			'logo_side_retina' => array(
				"title" => esc_html__('Logo side for Retina', 'ostende'),
				"desc" => wp_kses_data( __('Select or upload site logo (with vertical orientation) to display it in the side menu on Retina displays (if empty - use default logo from the field above)', 'ostende') ),
				"class" => "ostende_column-1_2",
				"dependency" => array(
					'logo_retina_enabled' => array(1)
				),
				"std" => '',
				"type" => "hidden"
				),
			
		
		
			// 'General settings'
			'general' => array(
				"title" => esc_html__('General Settings', 'ostende'),
				"desc" => wp_kses_data( $msg_override ),
				"priority" => 20,
				"type" => "section",
				),

			'general_layout_info' => array(
				"title" => esc_html__('Layout', 'ostende'),
				"desc" => '',
				"type" => "info",
				),
			'body_style' => array(
				"title" => esc_html__('Body style', 'ostende'),
				"desc" => wp_kses_data( __('Select width of the body content', 'ostende') ),
				"override" => array(
					'mode' => 'page,cpt_team,cpt_services,cpt_dishes,cpt_competitions,cpt_rounds,cpt_matches,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
					'section' => esc_html__('Content', 'ostende')
				),
				"refresh" => false,
				"std" => 'wide',
				"options" => ostende_get_list_body_styles(),
				"type" => "select"
				),
			'boxed_bg_image' => array(
				"title" => esc_html__('Boxed bg image', 'ostende'),
				"desc" => wp_kses_data( __('Select or upload image, used as background in the boxed body', 'ostende') ),
				"dependency" => array(
					'body_style' => array('boxed')
				),
				"override" => array(
					'mode' => 'page,cpt_team,cpt_services,cpt_dishes,cpt_competitions,cpt_rounds,cpt_matches,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
					'section' => esc_html__('Content', 'ostende')
				),
				"std" => '',
				"hidden" => true,
				"type" => "image"
				),
			'remove_margins' => array(
				"title" => esc_html__('Remove margins', 'ostende'),
				"desc" => wp_kses_data( esc_html__('Remove margins above and below the content area', 'ostende') ),
				"override" => array(
					'mode' => 'page,cpt_team,cpt_services,cpt_dishes,cpt_competitions,cpt_rounds,cpt_matches,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
					'section' => esc_html__('Content', 'ostende')
				),
				"refresh" => false,
				"std" => 0,
				"type" => "checkbox"
				),

			'general_sidebar_info' => array(
				"title" => esc_html__('Sidebar', 'ostende'),
				"desc" => '',
				"type" => "info",
				),
			'sidebar_position' => array(
				"title" => esc_html__('Sidebar position', 'ostende'),
				"desc" => wp_kses_data( __('Select position to show sidebar', 'ostende') ),
				"override" => array(
					'mode' => 'page,cpt_team,cpt_services,cpt_dishes,cpt_competitions,cpt_rounds,cpt_matches,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
					'section' => esc_html__('Widgets', 'ostende')
				),
				"std" => 'right',
				"options" => array(),
				"type" => "switch"
				),
			'sidebar_widgets' => array(
				"title" => esc_html__('Sidebar widgets', 'ostende'),
				"desc" => wp_kses_data( __('Select default widgets to show in the sidebar', 'ostende') ),
				"override" => array(
					'mode' => 'page,cpt_team,cpt_services,cpt_dishes,cpt_competitions,cpt_rounds,cpt_matches,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
					'section' => esc_html__('Widgets', 'ostende')
				),
				"dependency" => array(
					'sidebar_position' => array('left', 'right')
				),
				"std" => 'sidebar_widgets',
				"options" => array(),
				"type" => OSTENDE_THEME_FREE ? "hidden" : "select"
				),
			'expand_content' => array(
				"title" => esc_html__('Expand content', 'ostende'),
				"desc" => wp_kses_data( __('Expand the content width if the sidebar is hidden', 'ostende') ),
				"refresh" => false,
				"std" => 1,
				"type" => "checkbox"
				),


			'general_widgets_info' => array(
				"title" => esc_html__('Additional widgets', 'ostende'),
				"desc" => '',
				"type" => OSTENDE_THEME_FREE ? "hidden" : "info",
				),
			'widgets_above_page' => array(
				"title" => esc_html__('Widgets at the top of the page', 'ostende'),
				"desc" => wp_kses_data( __('Select widgets to show at the top of the page (above content and sidebar)', 'ostende') ),
				"override" => array(
					'mode' => 'page,cpt_team,cpt_services,cpt_dishes,cpt_competitions,cpt_rounds,cpt_matches,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
					'section' => esc_html__('Widgets', 'ostende')
				),
				"std" => 'hide',
				"options" => array(),
				"type" => OSTENDE_THEME_FREE ? "hidden" : "select"
				),
			'widgets_above_content' => array(
				"title" => esc_html__('Widgets above the content', 'ostende'),
				"desc" => wp_kses_data( __('Select widgets to show at the beginning of the content area', 'ostende') ),
				"override" => array(
					'mode' => 'page,cpt_team,cpt_services,cpt_dishes,cpt_competitions,cpt_rounds,cpt_matches,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
					'section' => esc_html__('Widgets', 'ostende')
				),
				"std" => 'hide',
				"options" => array(),
				"type" => OSTENDE_THEME_FREE ? "hidden" : "select"
				),
			'widgets_below_content' => array(
				"title" => esc_html__('Widgets below the content', 'ostende'),
				"desc" => wp_kses_data( __('Select widgets to show at the ending of the content area', 'ostende') ),
				"override" => array(
					'mode' => 'page,cpt_team,cpt_services,cpt_dishes,cpt_competitions,cpt_rounds,cpt_matches,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
					'section' => esc_html__('Widgets', 'ostende')
				),
				"std" => 'hide',
				"options" => array(),
				"type" => OSTENDE_THEME_FREE ? "hidden" : "select"
				),
			'widgets_below_page' => array(
				"title" => esc_html__('Widgets at the bottom of the page', 'ostende'),
				"desc" => wp_kses_data( __('Select widgets to show at the bottom of the page (below content and sidebar)', 'ostende') ),
				"override" => array(
					'mode' => 'page,cpt_team,cpt_services,cpt_dishes,cpt_competitions,cpt_rounds,cpt_matches,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
					'section' => esc_html__('Widgets', 'ostende')
				),
				"std" => 'hide',
				"options" => array(),
				"type" => OSTENDE_THEME_FREE ? "hidden" : "select"
				),

			'general_effects_info' => array(
				"title" => esc_html__('Design & Effects', 'ostende'),
				"desc" => '',
				"type" => "info",
				),
			'border_radius' => array(
				"title" => esc_html__('Border radius', 'ostende'),
				"desc" => wp_kses_data( __('Specify the border radius of the form fields and buttons in pixels or other valid CSS units', 'ostende') ),
				"std" => 0,
				"type" => "hidden"
				),

			'general_misc_info' => array(
				"title" => esc_html__('Miscellaneous', 'ostende'),
				"desc" => '',
				"type" => OSTENDE_THEME_FREE ? "hidden" : "info",
				),
		
		
			// 'Header'
			'header' => array(
				"title" => esc_html__('Header', 'ostende'),
				"desc" => wp_kses_data( $msg_override ),
				"priority" => 30,
				"type" => "section"
				),

			'header_style_info' => array(
				"title" => esc_html__('Header style', 'ostende'),
				"desc" => '',
				"type" => OSTENDE_THEME_FREE  ? "hidden" : "switch"
				),
			'header_type' => array(
				"title" => esc_html__('Header style', 'ostende'),
				"desc" => wp_kses_data( __('Choose whether to use the default header or header Layouts (available only if the ThemeREX Addons is activated)', 'ostende') ),
				"override" => array(
					'mode' => 'page,cpt_team,cpt_services,cpt_dishes,cpt_competitions,cpt_rounds,cpt_matches,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
					'section' => esc_html__('Header', 'ostende')
				),
				"std" => 'default',
				"options" => ostende_get_list_header_footer_types(),
				"type" => OSTENDE_THEME_FREE  ? "hidden" : "switch"
				),
			'header_style' => array(
				"title" => esc_html__('Select custom layout', 'ostende'),
				"desc" => wp_kses_post( __("Select custom header from Layouts Builder", 'ostende') ),
				"override" => array(
					'mode' => 'page,cpt_team,cpt_services,cpt_dishes,cpt_competitions,cpt_rounds,cpt_matches,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
					'section' => esc_html__('Header', 'ostende')
				),
				"dependency" => array(
					'header_type' => array('custom')
				),
				"std" => OSTENDE_THEME_FREE ? 'header-custom-sow-header-default' : 'header-custom-header-default',
				"options" => array(),
				"type" => "select"
				),
			'header_position' => array(
				"title" => esc_html__('Header position', 'ostende'),
				"desc" => wp_kses_data( __('Select position to display the site header', 'ostende') ),
				"override" => array(
					'mode' => 'page,cpt_team,cpt_services,cpt_dishes,cpt_competitions,cpt_rounds,cpt_matches,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
					'section' => esc_html__('Header', 'ostende')
				),
				"std" => 'default',
				"options" => array(),
				"type" => OSTENDE_THEME_FREE ? "hidden" : "switch"
				),
			'header_fullheight' => array(
				"title" => esc_html__('Header fullheight', 'ostende'),
				"desc" => wp_kses_data( __("Enlarge header area to fill whole screen. Used only if header have a background image", 'ostende') ),
				"override" => array(
					'mode' => 'page,cpt_team,cpt_services,cpt_dishes,cpt_competitions,cpt_rounds,cpt_matches,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
					'section' => esc_html__('Header', 'ostende')
				),
				"std" => 0,
				"type" => OSTENDE_THEME_FREE ? "hidden" : "checkbox"
				),
			'header_zoom' => array(
				"title" => esc_html__('Header zoom', 'ostende'),
				"desc" => wp_kses_data( __("Zoom the header title. 1 - original size", 'ostende') ),
				"std" => 1,
				"min" => 0.3,
				"max" => 2,
				"step" => 0.1,
				"refresh" => false,
				"type" => OSTENDE_THEME_FREE ? "hidden" : "slider"
				),
			'header_wide' => array(
				"title" => esc_html__('Header fullwidth', 'ostende'),
				"desc" => wp_kses_data( __('Do you want to stretch the header widgets area to the entire window width?', 'ostende') ),
				"override" => array(
					'mode' => 'page,cpt_team,cpt_services,cpt_dishes,cpt_competitions,cpt_rounds,cpt_matches,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
					'section' => esc_html__('Header', 'ostende')
				),
				"dependency" => array(
					'header_type' => array('default')
				),
				"std" => 1,
				"type" => OSTENDE_THEME_FREE ? "hidden" : "checkbox"
				),

			'header_widgets_info' => array(
				"title" => esc_html__('Header widgets', 'ostende'),
				"desc" => wp_kses_data( __('Here you can place a widget slider, advertising banners, etc.', 'ostende') ),
				"type" => OSTENDE_THEME_FREE ? "hidden" : "info"
				),
			'header_widgets' => array(
				"title" => esc_html__('Header widgets', 'ostende'),
				"desc" => wp_kses_data( __('Select set of widgets to show in the header on each page', 'ostende') ),
				"override" => array(
					'mode' => 'page,cpt_team,cpt_services,cpt_dishes,cpt_competitions,cpt_rounds,cpt_matches,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
					'section' => esc_html__('Header', 'ostende'),
					"desc" => wp_kses_data( __('Select set of widgets to show in the header on this page', 'ostende') ),
				),
				"std" => 'hide',
				"options" => array(),
				"type" => OSTENDE_THEME_FREE  ? "hidden" : "select"
				),
			'header_columns' => array(
				"title" => esc_html__('Header columns', 'ostende'),
				"desc" => wp_kses_data( __('Select number columns to show widgets in the Header. If 0 - autodetect by the widgets count', 'ostende') ),
				"override" => array(
					'mode' => 'page,cpt_team,cpt_services,cpt_dishes,cpt_competitions,cpt_rounds,cpt_matches,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
					'section' => esc_html__('Header', 'ostende')
				),
				"dependency" => array(
					'header_type' => array('default'),
					'header_widgets' => array('^hide')
				),
				"std" => 0,
				"options" => ostende_get_list_range(0,6),
				"type" => "select"
				),

			'menu_info' => array(
				"title" => esc_html__('Main menu', 'ostende'),
				"desc" => wp_kses_data( __('Select main menu style, position and other parameters', 'ostende') ),
				"type" => OSTENDE_THEME_FREE ? "hidden" : "info"
				),
			'menu_style' => array(
				"title" => esc_html__('Menu position', 'ostende'),
				"desc" => wp_kses_data( __('Select position of the main menu', 'ostende') ),
				"override" => array(
					'mode' => 'page,cpt_team,cpt_services,cpt_dishes,cpt_competitions,cpt_rounds,cpt_matches,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
					'section' => esc_html__('Header', 'ostende')
				),
				"std" => 'top',
				"options" => array(
					'top'	=> esc_html__('Top',	'ostende'),
				),
				"type" => OSTENDE_THEME_FREE  ? "hidden" : "switch"
				),
			'menu_side_stretch' => array(
				"title" => esc_html__('Stretch sidemenu', 'ostende'),
				"desc" => wp_kses_data( __('Stretch sidemenu to window height (if menu items number >= 5)', 'ostende') ),
				"override" => array(
					'mode' => 'page,cpt_team,cpt_services,cpt_dishes,cpt_competitions,cpt_rounds,cpt_matches,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
					'section' => esc_html__('Header', 'ostende')
				),
				"dependency" => array(
					'menu_style' => array('left', 'right')
				),
				"std" => 0,
				"type" => OSTENDE_THEME_FREE ? "hidden" : "checkbox"
				),
			'menu_side_icons' => array(
				"title" => esc_html__('Iconed sidemenu', 'ostende'),
				"desc" => wp_kses_data( __('Get icons from anchors and display it in the sidemenu or mark sidemenu items with simple dots', 'ostende') ),
				"override" => array(
					'mode' => 'page,cpt_team,cpt_services,cpt_dishes,cpt_competitions,cpt_rounds,cpt_matches,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
					'section' => esc_html__('Header', 'ostende')
				),
				"dependency" => array(
					'menu_style' => array('left', 'right')
				),
				"std" => 1,
				"type" => OSTENDE_THEME_FREE ? "hidden" : "checkbox"
				),
			'menu_mobile_fullscreen' => array(
				"title" => esc_html__('Mobile menu fullscreen', 'ostende'),
				"desc" => wp_kses_data( __('Display mobile and side menus on full screen (if checked) or slide narrow menu from the left or from the right side (if not checked)', 'ostende') ),
				"std" => 1,
				"type" => OSTENDE_THEME_FREE ? "hidden" : "checkbox"
				),

			'header_image_info' => array(
				"title" => esc_html__('Header image', 'ostende'),
				"desc" => '',
				"type" => OSTENDE_THEME_FREE ? "hidden" : "info"
				),
			'header_image_override' => array(
				"title" => esc_html__('Header image override', 'ostende'),
				"desc" => wp_kses_data( __("Allow override the header image with the page's/post's/product's/etc. featured image", 'ostende') ),
				"override" => array(
					'mode' => 'page',
					'section' => esc_html__('Header', 'ostende')
				),
				"std" => 0,
				"type" => OSTENDE_THEME_FREE ? "hidden" : "checkbox"
				),

			'header_mobile_info' => array(
				"title" => esc_html__('Mobile header', 'ostende'),
				"desc" => wp_kses_data( __("Configure the mobile version of the header", 'ostende') ),
				"priority" => 500,
				"dependency" => array(
					'header_type' => array('default')
				),
				"type" => OSTENDE_THEME_FREE ? "hidden" : "info"
				),
			'header_mobile_enabled' => array(
				"title" => esc_html__('Enable the mobile header', 'ostende'),
				"desc" => wp_kses_data( __("Use the mobile version of the header (if checked) or relayout the current header on mobile devices", 'ostende') ),
				"dependency" => array(
					'header_type' => array('default')
				),
				"std" => 0,
				"type" => OSTENDE_THEME_FREE ? "hidden" : "checkbox"
				),
			'header_mobile_additional_info' => array(
				"title" => esc_html__('Additional info', 'ostende'),
				"desc" => wp_kses_data( __('Additional info to show at the top of the mobile header', 'ostende') ),
				"std" => '',
				"dependency" => array(
					'header_type' => array('default'),
					'header_mobile_enabled' => array(1)
				),
				"refresh" => false,
				"teeny" => false,
				"rows" => 20,
				"type" => OSTENDE_THEME_FREE ? "hidden" : "text_editor"
				),
			'header_mobile_hide_info' => array(
				"title" => esc_html__('Hide additional info', 'ostende'),
				"std" => 0,
				"dependency" => array(
					'header_type' => array('default'),
					'header_mobile_enabled' => array(1)
				),
				"type" => OSTENDE_THEME_FREE ? "hidden" : "checkbox"
				),
			'header_mobile_hide_logo' => array(
				"title" => esc_html__('Hide logo', 'ostende'),
				"std" => 0,
				"dependency" => array(
					'header_type' => array('default'),
					'header_mobile_enabled' => array(1)
				),
				"type" => OSTENDE_THEME_FREE ? "hidden" : "checkbox"
				),
			'header_mobile_hide_login' => array(
				"title" => esc_html__('Hide login/logout', 'ostende'),
				"std" => 0,
				"dependency" => array(
					'header_type' => array('default'),
					'header_mobile_enabled' => array(1)
				),
				"type" => OSTENDE_THEME_FREE ? "hidden" : "checkbox"
				),
			'header_mobile_hide_search' => array(
				"title" => esc_html__('Hide search', 'ostende'),
				"std" => 0,
				"dependency" => array(
					'header_type' => array('default'),
					'header_mobile_enabled' => array(1)
				),
				"type" => OSTENDE_THEME_FREE ? "hidden" : "checkbox"
				),
			'header_mobile_hide_cart' => array(
				"title" => esc_html__('Hide cart', 'ostende'),
				"std" => 0,
				"dependency" => array(
					'header_type' => array('default'),
					'header_mobile_enabled' => array(1)
				),
				"type" => OSTENDE_THEME_FREE ? "hidden" : "checkbox"
				),


		
			// 'Footer'
			'footer' => array(
				"title" => esc_html__('Footer', 'ostende'),
				"desc" => wp_kses_data( $msg_override ),
				"priority" => 50,
				"type" => "section"
				),
			'footer_type' => array(
				"title" => esc_html__('Footer style', 'ostende'),
				"desc" => wp_kses_data( __('Choose whether to use the default footer or footer Layouts (available only if the ThemeREX Addons is activated)', 'ostende') ),
				"override" => array(
					'mode' => 'page,cpt_team,cpt_services,cpt_dishes,cpt_competitions,cpt_rounds,cpt_matches,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
					'section' => esc_html__('Footer', 'ostende')
				),
				"std" => 'default',
				"options" => ostende_get_list_header_footer_types(),
				"type" => OSTENDE_THEME_FREE  ? "hidden" : "switch"
				),
			'footer_style' => array(
				"title" => esc_html__('Select custom layout', 'ostende'),
				"desc" => wp_kses_post( __("Select custom footer from Layouts Builder", 'ostende') ),
				"override" => array(
					'mode' => 'page,cpt_team,cpt_services,cpt_dishes,cpt_competitions,cpt_rounds,cpt_matches,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
					'section' => esc_html__('Footer', 'ostende')
				),
				"dependency" => array(
					'footer_type' => array('custom')
				),
				"std" => OSTENDE_THEME_FREE ? 'footer-custom-sow-footer-default' : 'footer-custom-footer-default',
				"options" => array(),
				"type" => "select"
				),
			'footer_widgets' => array(
				"title" => esc_html__('Footer widgets', 'ostende'),
				"desc" => wp_kses_data( __('Select set of widgets to show in the footer', 'ostende') ),
				"override" => array(
					'mode' => 'page,cpt_team,cpt_services,cpt_dishes,cpt_competitions,cpt_rounds,cpt_matches,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
					'section' => esc_html__('Footer', 'ostende')
				),
				"dependency" => array(
					'footer_type' => array('default')
				),
				"std" => 'footer_widgets',
				"options" => array(),
				"type" => OSTENDE_THEME_FREE ? "hidden" : "select"
				),
			'footer_columns' => array(
				"title" => esc_html__('Footer columns', 'ostende'),
				"desc" => wp_kses_data( __('Select number columns to show widgets in the footer. If 0 - autodetect by the widgets count', 'ostende') ),
				"override" => array(
					'mode' => 'page,cpt_team,cpt_services,cpt_dishes,cpt_competitions,cpt_rounds,cpt_matches,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
					'section' => esc_html__('Footer', 'ostende')
				),
				"dependency" => array(
					'footer_type' => array('default'),
					'footer_widgets' => array('^hide')
				),
				"std" => 0,
				"options" => ostende_get_list_range(0,6),
				"type" => "select"
				),
			'footer_wide' => array(
				"title" => esc_html__('Footer fullwidth', 'ostende'),
				"desc" => wp_kses_data( __('Do you want to stretch the footer to the entire window width?', 'ostende') ),
				"override" => array(
					'mode' => 'page,cpt_team,cpt_services,cpt_dishes,cpt_competitions,cpt_rounds,cpt_matches,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
					'section' => esc_html__('Footer', 'ostende')
				),
				"dependency" => array(
					'footer_type' => array('default')
				),
				"std" => 0,
				"type" => "checkbox"
				),
			'logo_in_footer' => array(
				"title" => esc_html__('Show logo', 'ostende'),
				"desc" => wp_kses_data( __('Show logo in the footer', 'ostende') ),
				'refresh' => false,
				"dependency" => array(
					'footer_type' => array('default')
				),
				"std" => 0,
				"type" => "checkbox"
				),
			'logo_footer' => array(
				"title" => esc_html__('Logo for footer', 'ostende'),
				"desc" => wp_kses_data( __('Select or upload site logo to display it in the footer', 'ostende') ),
				"dependency" => array(
					'footer_type' => array('default'),
					'logo_in_footer' => array(1)
				),
				"std" => '',
				"type" => "image"
				),
			'logo_footer_retina' => array(
				"title" => esc_html__('Logo for footer (Retina)', 'ostende'),
				"desc" => wp_kses_data( __('Select or upload logo for the footer area used on Retina displays (if empty - use default logo from the field above)', 'ostende') ),
				"dependency" => array(
					'footer_type' => array('default'),
					'logo_in_footer' => array(1),
					'logo_retina_enabled' => array(1)
				),
				"std" => '',
				"type" => OSTENDE_THEME_FREE ? "hidden" : "image"
				),
			'copyright' => array(
				"title" => esc_html__('Copyright', 'ostende'),
				"desc" => wp_kses_data( __('Copyright text in the footer. Use {Y} to insert current year and press "Enter" to create a new line', 'ostende') ),
				"translate" => true,
				"std" => esc_html__('Proudly powered by WordPress.', 'ostende'),
				"dependency" => array(
					'footer_type' => array('default')
				),
				"refresh" => false,
				"type" => "textarea"
				),
			
		
		
			// 'Blog'
			'blog' => array(
				"title" => esc_html__('Blog', 'ostende'),
				"desc" => wp_kses_data( __('Options of the the blog archive', 'ostende') ),
				"priority" => 70,
				"type" => "panel",
				),
		
				// Blog - Posts page
				'blog_general' => array(
					"title" => esc_html__('Posts page', 'ostende'),
					"desc" => wp_kses_data( __('Style and components of the blog archive', 'ostende') ),
					"type" => "section",
					),
				'blog_general_info' => array(
					"title" => esc_html__('General settings', 'ostende'),
					"desc" => '',
					"type" => "info",
					),
				'blog_style' => array(
					"title" => esc_html__('Blog style', 'ostende'),
					"desc" => '',
					"override" => array(
						'mode' => 'page',
						'section' => esc_html__('Content', 'ostende')
					),
					"dependency" => array(
						'#page_template' => array( 'blog.php' ),
						'.editor-page-attributes__template select' => array( 'blog.php' ),
					),
					"std" => 'excerpt',
					"options" => array(),
					"type" => "select"
					),
				'first_post_large' => array(
					"title" => esc_html__('First post large', 'ostende'),
					"desc" => wp_kses_data( __('Make your first post stand out by making it bigger', 'ostende') ),
					"override" => array(
						'mode' => 'page',
						'section' => esc_html__('Content', 'ostende')
					),
					"dependency" => array(
						'#page_template' => array( 'blog.php' ),
						'.editor-page-attributes__template select' => array( 'blog.php' ),
						'blog_style' => array('classic', 'masonry')
					),
					"std" => 0,
					"type" => "checkbox"
					),
				"blog_content" => array( 
					"title" => esc_html__('Posts content', 'ostende'),
					"desc" => wp_kses_data( __("Display either post excerpts or the full post content", 'ostende') ),
					"std" => "excerpt",
					"dependency" => array(
						'blog_style' => array('excerpt')
					),
					"options" => array(
						'excerpt'	=> esc_html__('Excerpt',	'ostende'),
						'fullpost'	=> esc_html__('Full post',	'ostende')
					),
					"type" => "switch"
					),
				'excerpt_length' => array(
					"title" => esc_html__('Excerpt length', 'ostende'),
					"desc" => wp_kses_data( __("Length (in words) to generate excerpt from the post content. Attention! If the post excerpt is explicitly specified - it appears unchanged", 'ostende') ),
					"dependency" => array(
						'blog_style' => array('excerpt'),
						'blog_content' => array('excerpt')
					),
					"std" => 35,
					"type" => "text"
					),
				'blog_columns' => array(
					"title" => esc_html__('Blog columns', 'ostende'),
					"desc" => wp_kses_data( __('How many columns should be used in the blog archive (from 2 to 4)?', 'ostende') ),
					"std" => 2,
					"options" => ostende_get_list_range(2,4),
					"type" => "hidden"
					),
				'post_type' => array(
					"title" => esc_html__('Post type', 'ostende'),
					"desc" => wp_kses_data( __('Select post type to show in the blog archive', 'ostende') ),
					"override" => array(
						'mode' => 'page',
						'section' => esc_html__('Content', 'ostende')
					),
					"dependency" => array(
						'#page_template' => array( 'blog.php' ),
						'.editor-page-attributes__template select' => array( 'blog.php' ),
					),
					"linked" => 'parent_cat',
					"refresh" => false,
					"hidden" => true,
					"std" => 'post',
					"options" => array(),
					"type" => "select"
					),
				'parent_cat' => array(
					"title" => esc_html__('Category to show', 'ostende'),
					"desc" => wp_kses_data( __('Select category to show in the blog archive', 'ostende') ),
					"override" => array(
						'mode' => 'page',
						'section' => esc_html__('Content', 'ostende')
					),
					"dependency" => array(
						'#page_template' => array( 'blog.php' ),
						'.editor-page-attributes__template select' => array( 'blog.php' ),
					),
					"refresh" => false,
					"hidden" => true,
					"std" => '0',
					"options" => array(),
					"type" => "select"
					),
				'posts_per_page' => array(
					"title" => esc_html__('Posts per page', 'ostende'),
					"desc" => wp_kses_data( __('How many posts will be displayed on this page', 'ostende') ),
					"override" => array(
						'mode' => 'page',
						'section' => esc_html__('Content', 'ostende')
					),
					"dependency" => array(
						'#page_template' => array( 'blog.php' ),
						'.editor-page-attributes__template select' => array( 'blog.php' ),
					),
					"hidden" => true,
					"std" => '',
					"type" => "text"
					),
				"blog_pagination" => array( 
					"title" => esc_html__('Pagination style', 'ostende'),
					"desc" => wp_kses_data( __('Show Older/Newest posts or Page numbers below the posts list', 'ostende') ),
					"override" => array(
						'mode' => 'page',
						'section' => esc_html__('Content', 'ostende')
					),
					"std" => "pages",
					"dependency" => array(
						'#page_template' => array( 'blog.php' ),
						'.editor-page-attributes__template select' => array( 'blog.php' ),
					),
					"options" => array(
						'pages'	=> esc_html__("Page numbers", 'ostende'),
						'links'	=> esc_html__("Older/Newest", 'ostende'),
						'more'	=> esc_html__("Load more", 'ostende'),
						'infinite' => esc_html__("Infinite scroll", 'ostende')
					),
					"type" => "select"
					),
				'show_filters' => array(
					"title" => esc_html__('Show filters', 'ostende'),
					"desc" => wp_kses_data( __('Show categories as tabs to filter posts', 'ostende') ),
					"override" => array(
						'mode' => 'page',
						'section' => esc_html__('Content', 'ostende')
					),
					"dependency" => array(
						'#page_template' => array( 'blog.php' ),
						'.editor-page-attributes__template select' => array( 'blog.php' ),
						'blog_style' => array('portfolio', 'gallery')
					),
					"hidden" => true,
					"std" => 0,
					"type" => OSTENDE_THEME_FREE ? "hidden" : "checkbox"
					),
	
				'blog_sidebar_info' => array(
					"title" => esc_html__('Sidebar', 'ostende'),
					"desc" => '',
					"type" => "info",
					),
				'sidebar_position_blog' => array(
					"title" => esc_html__('Sidebar position', 'ostende'),
					"desc" => wp_kses_data( __('Select position to show sidebar', 'ostende') ),
					"std" => 'right',
					"options" => array(),
					"type" => "switch"
					),
				'sidebar_widgets_blog' => array(
					"title" => esc_html__('Sidebar widgets', 'ostende'),
					"desc" => wp_kses_data( __('Select default widgets to show in the sidebar', 'ostende') ),
					"dependency" => array(
						'sidebar_position_blog' => array('left', 'right')
					),
					"std" => 'sidebar_widgets',
					"options" => array(),
					"type" => OSTENDE_THEME_FREE ? "hidden" : "select"
					),
				'expand_content_blog' => array(
					"title" => esc_html__('Expand content', 'ostende'),
					"desc" => wp_kses_data( __('Expand the content width if the sidebar is hidden', 'ostende') ),
					"refresh" => false,
					"std" => 1,
					"type" => "checkbox"
					),
	
	
				'blog_widgets_info' => array(
					"title" => esc_html__('Additional widgets', 'ostende'),
					"desc" => '',
					"type" => OSTENDE_THEME_FREE ? "hidden" : "info",
					),
				'widgets_above_page_blog' => array(
					"title" => esc_html__('Widgets at the top of the page', 'ostende'),
					"desc" => wp_kses_data( __('Select widgets to show at the top of the page (above content and sidebar)', 'ostende') ),
					"std" => 'hide',
					"options" => array(),
					"type" => OSTENDE_THEME_FREE ? "hidden" : "select"
					),
				'widgets_above_content_blog' => array(
					"title" => esc_html__('Widgets above the content', 'ostende'),
					"desc" => wp_kses_data( __('Select widgets to show at the beginning of the content area', 'ostende') ),
					"std" => 'hide',
					"options" => array(),
					"type" => OSTENDE_THEME_FREE ? "hidden" : "select"
					),
				'widgets_below_content_blog' => array(
					"title" => esc_html__('Widgets below the content', 'ostende'),
					"desc" => wp_kses_data( __('Select widgets to show at the ending of the content area', 'ostende') ),
					"std" => 'hide',
					"options" => array(),
					"type" => OSTENDE_THEME_FREE ? "hidden" : "select"
					),
				'widgets_below_page_blog' => array(
					"title" => esc_html__('Widgets at the bottom of the page', 'ostende'),
					"desc" => wp_kses_data( __('Select widgets to show at the bottom of the page (below content and sidebar)', 'ostende') ),
					"std" => 'hide',
					"options" => array(),
					"type" => OSTENDE_THEME_FREE ? "hidden" : "select"
					),

				'blog_advanced_info' => array(
					"title" => esc_html__('Advanced settings', 'ostende'),
					"desc" => '',
					"type" => "info",
					),
				'no_image' => array(
					"title" => esc_html__('Image placeholder', 'ostende'),
					"desc" => wp_kses_data( __('Select or upload an image used as placeholder for posts without a featured image', 'ostende') ),
					"std" => '',
					"type" => "image"
					),
		
				'sticky_style' => array(
					"title" => esc_html__('Sticky posts style', 'ostende'),
					"desc" => wp_kses_data( __('Select style of the sticky posts output', 'ostende') ),
					"std" => 'inherit',
					"options" => array(
						'inherit' => esc_html__('Decorated posts', 'ostende'),
						'columns' => esc_html__('Mini-cards',	'ostende')
					),
					"type" => OSTENDE_THEME_FREE ? "hidden" : "select"
					),
				"blog_animation" => array( 
					"title" => esc_html__('Animation for the posts', 'ostende'),
					"desc" => wp_kses_data( __('Select animation to show posts in the blog. Attention! Do not use any animation on pages with the "wheel to the anchor" behaviour (like a "Chess 2 columns")!', 'ostende') ),
					"override" => array(
						'mode' => 'page',
						'section' => esc_html__('Content', 'ostende')
					),
					"dependency" => array(
						'#page_template' => array( 'blog.php' ),
						'.editor-page-attributes__template select' => array( 'blog.php' ),
					),
					"std" => "none",
					"options" => array(),
					"type" => OSTENDE_THEME_FREE ? "hidden" : "select"
					),
				'meta_parts' => array(
					"title" => esc_html__('Post meta', 'ostende'),
					"desc" => wp_kses_data( __("If your blog page is created using the 'Blog archive' page template, set up the 'Post Meta' settings in the 'Theme Options' section of that page. Counters and Share Links are available only if plugin ThemeREX Addons is active", 'ostende') )
								. '<br>'
								. wp_kses_data( __("<b>Tip:</b> Drag items to change their order.", 'ostende') ),
					"override" => array(
						'mode' => 'page',
						'section' => esc_html__('Content', 'ostende')
					),
					"dependency" => array(
						'#page_template' => array( 'blog.php' ),
						'.editor-page-attributes__template select' => array( 'blog.php' ),
					),
					"dir" => 'vertical',
					"sortable" => true,
					"std" => 'categories=1|date=1|counters=1|author=0|share=0|edit=0',
					"options" => array(
						'categories' => esc_html__('Categories', 'ostende'),
						'date'		 => esc_html__('Post date', 'ostende'),
						'author'	 => esc_html__('Post author', 'ostende'),
						'counters'	 => esc_html__('Views, Likes and Comments', 'ostende'),
						'share'		 => esc_html__('Share links', 'ostende'),
						'edit'		 => esc_html__('Edit link', 'ostende')
					),
					"type" => OSTENDE_THEME_FREE ? "hidden" : "checklist"
				),
				'counters' => array(
					"title" => esc_html__('Views, Likes and Comments', 'ostende'),
					"desc" => wp_kses_data( __("Likes and Views are available only if ThemeREX Addons is active", 'ostende') ),
					"override" => array(
						'mode' => 'page',
						'section' => esc_html__('Content', 'ostende')
					),
					"dependency" => array(
						'#page_template' => array( 'blog.php' ),
						'.editor-page-attributes__template select' => array( 'blog.php' ),
					),
					"dir" => 'vertical',
					"sortable" => true,
					"std" => 'views=0|likes=1|comments=1',
					"options" => array(
						'views' => esc_html__('Views', 'ostende'),
						'likes' => esc_html__('Likes', 'ostende'),
						'comments' => esc_html__('Comments', 'ostende')
					),
					"type" => OSTENDE_THEME_FREE ? "hidden" : "checklist"
				),

				
				// Blog - Single posts
				'blog_single' => array(
					"title" => esc_html__('Single posts', 'ostende'),
					"desc" => wp_kses_data( __('Settings of the single post', 'ostende') ),
					"type" => "section",
					),
				'show_featured_on_single' => array(
					"title" => esc_html__('Show featured image on the single post', 'ostende'),
					"desc" => wp_kses_data( __("Show featured image on the single post's pages", 'ostende') ),
					"override" => array(
						'mode' => 'page,post',
						'section' => esc_html__('Content', 'ostende')
					),
					"std" => 1,
					"type" => "checkbox"
					),
				'show_sidebar_on_single' => array(
					"title" => esc_html__('Show sidebar on the single post', 'ostende'),
					"desc" => wp_kses_data( __("Show sidebar on the single post's pages", 'ostende') ),
					"std" => 1,
					"type" => "checkbox"
					),
                'expand_content_post' => array(
                    "title" => esc_html__('Expand content', 'ostende'),
                    "desc" => wp_kses_data( __('Expand the content width if the sidebar is hidden', 'ostende') ),
                    "refresh" => false,
                    "std" => 1,
                    "type" => "checkbox"
                ),
				'show_post_meta' => array(
					"title" => esc_html__('Show post meta', 'ostende'),
					"desc" => wp_kses_data( __("Display block with post's meta: date, categories, counters, etc.", 'ostende') ),
					"std" => 1,
					"type" => "checkbox"
					),
				'meta_parts_post' => array(
					"title" => esc_html__('Post meta', 'ostende'),
					"desc" => wp_kses_data( __("Meta parts for single posts. Counters and Share Links are available only if plugin ThemeREX Addons is active", 'ostende') )
								. '<br>'
								. wp_kses_data( __("<b>Tip:</b> Drag items to change their order.", 'ostende') ),
					"dependency" => array(
						'show_post_meta' => array(1)
					),
					"dir" => 'vertical',
					"sortable" => true,
					"std" => 'categories=1|date=1|counters=1|author=0|share=0|edit=0',
					"options" => array(
						'categories' => esc_html__('Categories', 'ostende'),
						'date'		 => esc_html__('Post date', 'ostende'),
						'author'	 => esc_html__('Post author', 'ostende'),
						'counters'	 => esc_html__('Views, Likes and Comments', 'ostende'),
						'share'		 => esc_html__('Share links', 'ostende'),
						'edit'		 => esc_html__('Edit link', 'ostende')
					),
					"type" => OSTENDE_THEME_FREE ? "hidden" : "checklist"
				),
				'counters_post' => array(
					"title" => esc_html__('Views, Likes and Comments', 'ostende'),
					"desc" => wp_kses_data( __("Likes and Views are available only if plugin ThemeREX Addons is active", 'ostende') ),
					"dependency" => array(
						'show_post_meta' => array(1)
					),
					"dir" => 'vertical',
					"sortable" => true,
					"std" => 'views=1|likes=1|comments=1',
					"options" => array(
						'views' => esc_html__('Views', 'ostende'),
						'likes' => esc_html__('Likes', 'ostende'),
						'comments' => esc_html__('Comments', 'ostende')
					),
					"type" => OSTENDE_THEME_FREE  ? "hidden" : "checklist"
				),
		
				'show_author_info' => array(
					"title" => esc_html__('Show author info', 'ostende'),
					"desc" => wp_kses_data( __("Display block with information about post's author", 'ostende') ),
					"std" => 1,
					"type" => "checkbox"
					),
				'blog_single_related_info' => array(
					"title" => esc_html__('Related posts', 'ostende'),
					"desc" => '',
					"type" => "info",
					),
				'show_related_posts' => array(
					"title" => esc_html__('Show related posts', 'ostende'),
					"desc" => wp_kses_data( __("Show section 'Related posts' on the single post's pages", 'ostende') ),
					"override" => array(
						'mode' => 'page,post',
						'section' => esc_html__('Content', 'ostende')
					),
					"std" => 1,
					"type" => "checkbox"
					),
				'related_posts' => array(
					"title" => esc_html__('Related posts', 'ostende'),
					"desc" => wp_kses_data( __('How many related posts should be displayed in the single post? If 0 - no related posts are shown.', 'ostende') ),
					"dependency" => array(
						'show_related_posts' => array(1)
					),
					"std" => 2,
					"options" => ostende_get_list_range(1,9),
					"type" => OSTENDE_THEME_FREE ? "hidden" : "select"
					),
				'related_columns' => array(
					"title" => esc_html__('Related columns', 'ostende'),
					"desc" => wp_kses_data( __('How many columns should be used to output related posts in the single page (from 2 to 4)?', 'ostende') ),
					"dependency" => array(
						'show_related_posts' => array(1)
					),
					"std" => 2,
					"options" => ostende_get_list_range(1,4),
					"type" => OSTENDE_THEME_FREE ? "hidden" : "switch"
					),
				'related_style' => array(
					"title" => esc_html__('Related posts style', 'ostende'),
					"desc" => wp_kses_data( __('Select style of the related posts output', 'ostende') ),
					"dependency" => array(
						'show_related_posts' => array(1)
					),
					"std" => 1,
					"options" => ostende_get_list_styles(1,2),
					"type" => OSTENDE_THEME_FREE ? "hidden" : "switch"
					),
			'blog_end' => array(
				"type" => "panel_end",
				),
			
		
		
			// 'Colors'
			'panel_colors' => array(
				"title" => esc_html__('Colors', 'ostende'),
				"desc" => '',
				"priority" => 300,
				"type" => "section"
				),

			'color_schemes_info' => array(
				"title" => esc_html__('Color schemes', 'ostende'),
				"desc" => wp_kses_data( __('Color schemes for various parts of the site. "Inherit" means that this block is used the Site color scheme (the first parameter)', 'ostende') ),
				"hidden" => $hide_schemes,
				"type" => "info",
				),
			'color_scheme' => array(
				"title" => esc_html__('Site Color Scheme', 'ostende'),
				"desc" => '',
				"override" => array(
					'mode' => 'page,cpt_team,cpt_services,cpt_dishes,cpt_competitions,cpt_rounds,cpt_matches,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
					'section' => esc_html__('Colors', 'ostende')
				),
				"std" => 'default',
				"options" => array(),
				"refresh" => false,
				"type" => $hide_schemes ? 'hidden' : "switch"
				),
			'header_scheme' => array(
				"title" => esc_html__('Header Color Scheme', 'ostende'),
				"desc" => '',
				"override" => array(
					'mode' => 'page,cpt_team,cpt_services,cpt_dishes,cpt_competitions,cpt_rounds,cpt_matches,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
					'section' => esc_html__('Colors', 'ostende')
				),
				"std" => 'inherit',
				"options" => array(),
				"refresh" => false,
				"type" => $hide_schemes ? 'hidden' : "switch"
				),
			'menu_scheme' => array(
				"title" => esc_html__('Sidemenu Color Scheme', 'ostende'),
				"desc" => '',
				"override" => array(
					'mode' => 'page,cpt_team,cpt_services,cpt_dishes,cpt_competitions,cpt_rounds,cpt_matches,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
					'section' => esc_html__('Colors', 'ostende')
				),
				"std" => 'inherit',
				"options" => array(),
				"refresh" => false,
				"type" => "hidden"
				),
			'sidebar_scheme' => array(
				"title" => esc_html__('Sidebar Color Scheme', 'ostende'),
				"desc" => '',
				"override" => array(
					'mode' => 'page,cpt_team,cpt_services,cpt_dishes,cpt_competitions,cpt_rounds,cpt_matches,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
					'section' => esc_html__('Colors', 'ostende')
				),
				"std" => 'inherit',
				"options" => array(),
				"refresh" => false,
				"type" => $hide_schemes ? 'hidden' : "switch"
				),
			'footer_scheme' => array(
				"title" => esc_html__('Footer Color Scheme', 'ostende'),
				"desc" => '',
				"override" => array(
					'mode' => 'page,cpt_team,cpt_services,cpt_dishes,cpt_competitions,cpt_rounds,cpt_matches,cpt_cars,cpt_properties,cpt_courses,cpt_portfolio',
					'section' => esc_html__('Colors', 'ostende')
				),
				"std" => 'dark',
				"options" => array(),
				"refresh" => false,
				"type" => $hide_schemes ? 'hidden' : "switch"
				),

			'color_scheme_editor_info' => array(
				"title" => esc_html__('Color scheme editor', 'ostende'),
				"desc" => wp_kses_data(__('Select color scheme to modify. Attention! Only those sections in the site will be changed which this scheme was assigned to', 'ostende') ),
				"type" => "info",
				),
			'scheme_storage' => array(
				"title" => esc_html__('Color scheme editor', 'ostende'),
				"desc" => '',
				"std" => '$ostende_get_scheme_storage',
				"refresh" => false,
				"colorpicker" => "spectrum",
				"type" => "scheme_editor"
				),


			// 'Hidden'
			'media_title' => array(
				"title" => esc_html__('Media title', 'ostende'),
				"desc" => wp_kses_data( __('Used as title for the audio and video item in this post', 'ostende') ),
				"override" => array(
					'mode' => 'post',
					'section' => esc_html__('Content', 'ostende')
				),
				"hidden" => true,
				"std" => '',
				"type" => OSTENDE_THEME_FREE ? "hidden" : "text"
				),
			'media_author' => array(
				"title" => esc_html__('Media author', 'ostende'),
				"desc" => wp_kses_data( __('Used as author name for the audio and video item in this post', 'ostende') ),
				"override" => array(
					'mode' => 'post',
					'section' => esc_html__('Content', 'ostende')
				),
				"hidden" => true,
				"std" => '',
				"type" => OSTENDE_THEME_FREE ? "hidden" : "text"
				),


			// Internal options.
			// Attention! Don't change any options in the section below!
			// Use huge priority to call render this elements after all options!
			'reset_options' => array(
				"title" => '',
				"desc" => '',
				"std" => '0',
				"priority" => 10000,
				"type" => "hidden",
				),

			'last_option' => array(		// Need to manually call action to include Tiny MCE scripts
				"title" => '',
				"desc" => '',
				"std" => 1,
				"type" => "hidden",
				),

		));


		// Prepare panel 'Fonts'
		// -------------------------------------------------------------
		$fonts = array(
		
			// 'Fonts'
			'fonts' => array(
				"title" => esc_html__('Typography', 'ostende'),
				"desc" => '',
				"priority" => 200,
				"type" => "panel"
				),

			// Fonts - Load_fonts
			'load_fonts' => array(
				"title" => esc_html__('Load fonts', 'ostende'),
				"desc" => wp_kses_data( __('Specify fonts to load when theme start. You can use them in the base theme elements: headers, text, menu, links, input fields, etc.', 'ostende') )
						. '<br>'
						. wp_kses_data( __('Attention! Press "Refresh" button to reload preview area after the all fonts are changed', 'ostende') ),
				"type" => "section"
				),
			'load_fonts_subset' => array(
				"title" => esc_html__('Google fonts subsets', 'ostende'),
				"desc" => wp_kses_data( __('Specify comma separated list of the subsets which will be load from Google fonts', 'ostende') )
						. '<br>'
						. wp_kses_data( __('Available subsets are: latin,latin-ext,cyrillic,cyrillic-ext,greek,greek-ext,vietnamese', 'ostende') ),
				"class" => "ostende_column-1_3 ostende_new_row",
				"refresh" => false,
				"std" => '$ostende_get_load_fonts_subset',
				"type" => "text"
				)
		);

		for ($i=1; $i<=ostende_get_theme_setting('max_load_fonts'); $i++) {
			if (ostende_get_value_gp('page') != 'theme_options') {
				$fonts["load_fonts-{$i}-info"] = array(
					// Translators: Add font's number - 'Font 1', 'Font 2', etc
					"title" => esc_html(sprintf(__('Font %s', 'ostende'), $i)),
					"desc" => '',
					"type" => "info",
					);
			}
			$fonts["load_fonts-{$i}-name"] = array(
				"title" => esc_html__('Font name', 'ostende'),
				"desc" => '',
				"class" => "ostende_column-1_3 ostende_new_row",
				"refresh" => false,
				"std" => '$ostende_get_load_fonts_option',
				"type" => "text"
				);
			$fonts["load_fonts-{$i}-family"] = array(
				"title" => esc_html__('Font family', 'ostende'),
				"desc" => $i==1 
							? wp_kses_data( __('Select font family to use it if font above is not available', 'ostende') )
							: '',
				"class" => "ostende_column-1_3",
				"refresh" => false,
				"std" => '$ostende_get_load_fonts_option',
				"options" => array(
					'inherit' => esc_html__("Inherit", 'ostende'),
					'serif' => esc_html__('serif', 'ostende'),
					'sans-serif' => esc_html__('sans-serif', 'ostende'),
					'monospace' => esc_html__('monospace', 'ostende'),
					'cursive' => esc_html__('cursive', 'ostende'),
					'fantasy' => esc_html__('fantasy', 'ostende')
				),
				"type" => "select"
				);
			$fonts["load_fonts-{$i}-styles"] = array(
				"title" => esc_html__('Font styles', 'ostende'),
				"desc" => $i==1 
							? wp_kses_data( __('Font styles used only for the Google fonts. This is a comma separated list of the font weight and styles. For example: 400,400italic,700', 'ostende') )
								. '<br>'
								. wp_kses_data( __('Attention! Each weight and style increase download size! Specify only used weights and styles.', 'ostende') )
							: '',
				"class" => "ostende_column-1_3",
				"refresh" => false,
				"std" => '$ostende_get_load_fonts_option',
				"type" => "text"
				);
		}
		$fonts['load_fonts_end'] = array(
			"type" => "section_end"
			);

		// Fonts - H1..6, P, Info, Menu, etc.
		$theme_fonts = ostende_get_theme_fonts();
		foreach ($theme_fonts as $tag=>$v) {
			$fonts["{$tag}_section"] = array(
				"title" => !empty($v['title']) 
								? $v['title'] 
								// Translators: Add tag's name to make title 'H1 settings', 'P settings', etc.
								: esc_html(sprintf(__('%s settings', 'ostende'), $tag)),
				"desc" => !empty($v['description']) 
								? $v['description'] 
								// Translators: Add tag's name to make description
								: wp_kses_post( sprintf(__('Font settings of the "%s" tag.', 'ostende'), $tag) ),
				"type" => "section",
				);
	
			foreach ($v as $css_prop=>$css_value) {
				if (in_array($css_prop, array('title', 'description'))) continue;
				$options = '';
				$type = 'text';
				$load_order = 1;
				$title = ucfirst(str_replace('-', ' ', $css_prop));
				if ($css_prop == 'font-family') {
					$type = 'select';
					$options = array();
					$load_order = 2;		// Load this option's value after all options are loaded (use option 'load_fonts' to build fonts list)
				} else if ($css_prop == 'font-weight') {
					$type = 'select';
					$options = array(
						'inherit' => esc_html__("Inherit", 'ostende'),
						'100' => esc_html__('100 (Light)', 'ostende'), 
						'200' => esc_html__('200 (Light)', 'ostende'), 
						'300' => esc_html__('300 (Thin)',  'ostende'),
						'400' => esc_html__('400 (Normal)', 'ostende'),
						'500' => esc_html__('500 (Semibold)', 'ostende'),
						'600' => esc_html__('600 (Semibold)', 'ostende'),
						'700' => esc_html__('700 (Bold)', 'ostende'),
						'800' => esc_html__('800 (Black)', 'ostende'),
						'900' => esc_html__('900 (Black)', 'ostende')
					);
				} else if ($css_prop == 'font-style') {
					$type = 'select';
					$options = array(
						'inherit' => esc_html__("Inherit", 'ostende'),
						'normal' => esc_html__('Normal', 'ostende'), 
						'italic' => esc_html__('Italic', 'ostende')
					);
				} else if ($css_prop == 'text-decoration') {
					$type = 'select';
					$options = array(
						'inherit' => esc_html__("Inherit", 'ostende'),
						'none' => esc_html__('None', 'ostende'), 
						'underline' => esc_html__('Underline', 'ostende'),
						'overline' => esc_html__('Overline', 'ostende'),
						'line-through' => esc_html__('Line-through', 'ostende')
					);
				} else if ($css_prop == 'text-transform') {
					$type = 'select';
					$options = array(
						'inherit' => esc_html__("Inherit", 'ostende'),
						'none' => esc_html__('None', 'ostende'), 
						'uppercase' => esc_html__('Uppercase', 'ostende'),
						'lowercase' => esc_html__('Lowercase', 'ostende'),
						'capitalize' => esc_html__('Capitalize', 'ostende')
					);
				}
				$fonts["{$tag}_{$css_prop}"] = array(
					"title" => $title,
					"desc" => '',
					"class" => "ostende_column-1_5",
					"refresh" => false,
					"load_order" => $load_order,
					"std" => '$ostende_get_theme_fonts_option',
					"options" => $options,
					"type" => $type
				);
			}
			
			$fonts["{$tag}_section_end"] = array(
				"type" => "section_end"
				);
		}

		$fonts['fonts_end'] = array(
			"type" => "panel_end"
			);

		// Add fonts parameters to Theme Options
		ostende_storage_set_array_before('options', 'panel_colors', $fonts);

	}
}


// Return lists with choises when its need in the admin mode
if (!function_exists('ostende_options_get_list_choises')) {
	add_filter('ostende_filter_options_get_list_choises', 'ostende_options_get_list_choises', 10, 2);
	function ostende_options_get_list_choises($list, $id) {
		if (is_array($list) && count($list)==0) {
			if (strpos($id, 'header_style')===0)
				$list = ostende_get_list_header_styles(strpos($id, 'header_style_')===0);
			else if (strpos($id, 'header_position')===0)
				$list = ostende_get_list_header_positions(strpos($id, 'header_position_')===0);
			else if (strpos($id, 'header_widgets')===0)
				$list = ostende_get_list_sidebars(strpos($id, 'header_widgets_')===0, true);
			else if (strpos($id, '_scheme') > 0)
				$list = ostende_get_list_schemes($id!='color_scheme');
			else if (strpos($id, 'sidebar_widgets')===0)
				$list = ostende_get_list_sidebars(strpos($id, 'sidebar_widgets_')===0, true);
			else if (strpos($id, 'sidebar_position')===0)
				$list = ostende_get_list_sidebars_positions(strpos($id, 'sidebar_position_')===0);
			else if (strpos($id, 'widgets_above_page')===0)
				$list = ostende_get_list_sidebars(strpos($id, 'widgets_above_page_')===0, true);
			else if (strpos($id, 'widgets_above_content')===0)
				$list = ostende_get_list_sidebars(strpos($id, 'widgets_above_content_')===0, true);
			else if (strpos($id, 'widgets_below_page')===0)
				$list = ostende_get_list_sidebars(strpos($id, 'widgets_below_page_')===0, true);
			else if (strpos($id, 'widgets_below_content')===0)
				$list = ostende_get_list_sidebars(strpos($id, 'widgets_below_content_')===0, true);
			else if (strpos($id, 'footer_style')===0)
				$list = ostende_get_list_footer_styles(strpos($id, 'footer_style_')===0);
			else if (strpos($id, 'footer_widgets')===0)
				$list = ostende_get_list_sidebars(strpos($id, 'footer_widgets_')===0, true);
			else if (strpos($id, 'blog_style')===0)
				$list = ostende_get_list_blog_styles(strpos($id, 'blog_style_')===0);
			else if (strpos($id, 'post_type')===0)
				$list = ostende_get_list_posts_types();
			else if (strpos($id, 'parent_cat')===0)
				$list = ostende_array_merge(array(0 => esc_html__('- Select category -', 'ostende')), ostende_get_list_categories());
			else if (strpos($id, 'blog_animation')===0)
				$list = ostende_get_list_animations_in();
			else if ($id == 'color_scheme_editor')
				$list = ostende_get_list_schemes();
			else if (strpos($id, '_font-family') > 0)
				$list = ostende_get_list_load_fonts(true);
		}
		return $list;
	}
}
?>