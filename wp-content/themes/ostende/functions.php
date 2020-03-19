<?php
/**
 * Theme functions: init, enqueue scripts and styles, include required files and widgets
 *
 
 * @subpackage OSTENDE
 * @since OSTENDE 1.0
 */

if (!defined("OSTENDE_THEME_DIR")) define("OSTENDE_THEME_DIR", trailingslashit( get_template_directory() ));
if (!defined("OSTENDE_CHILD_DIR")) define("OSTENDE_CHILD_DIR", trailingslashit( get_stylesheet_directory() ));

//-------------------------------------------------------
//-- Theme init
//-------------------------------------------------------

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

if ( !function_exists('ostende_theme_setup1') ) {
	add_action( 'after_setup_theme', 'ostende_theme_setup1', 1 );
	function ostende_theme_setup1() {
		// Make theme available for translation
		// Translations can be filed in the /languages directory
		// Attention! Translations must be loaded before first call any translation functions!
		load_theme_textdomain( 'ostende', get_template_directory() . '/languages' );

		// Set theme content width
		$GLOBALS['content_width'] = apply_filters( 'ostende_filter_content_width', 1170 );
	}
}

if ( !function_exists('ostende_theme_setup') ) {
	add_action( 'after_setup_theme', 'ostende_theme_setup' );
	function ostende_theme_setup() {

		// Add default posts and comments RSS feed links to head 
		add_theme_support( 'automatic-feed-links' );
		
		// Custom header setup
		add_theme_support( 'custom-header', array(
			'header-text' => false,
			'video' => true
			)
		);
		
		// Custom logo
		add_theme_support( 'custom-logo', array(
			'width'       => 250,
			'height'      => 60,
			'flex-width'  => true,
			'flex-height' => true
			)
		);
		// Custom backgrounds setup
		add_theme_support( 'custom-background', array()	);

		// Partial refresh support in the Customize
		add_theme_support( 'customize-selective-refresh-widgets' );
		
		// Supported posts formats
		add_theme_support( 'post-formats', array('gallery', 'video', 'audio', 'link', 'quote', 'image', 'status', 'aside', 'chat') ); 
 
 		// Autogenerate title tag
		add_theme_support('title-tag');
 		
		// Add theme menus
		add_theme_support('nav-menus');
		
		// Switch default markup for search form, comment form, and comments to output valid HTML5.
		add_theme_support( 'html5', array('search-form', 'comment-form', 'comment-list', 'gallery', 'caption') );
		
		// Editor custom stylesheet - for user
		add_editor_style( array_merge(
			array(
				'css/editor-style.css',
				ostende_get_file_url('css/font-icons/css/fontello-embedded.css')
			),
			ostende_theme_fonts_for_editor()
			)
		);	
	
		// Register navigation menu
		register_nav_menus(array(
			'menu_main' => esc_html__('Main Menu', 'ostende'),
			'menu_footer' => esc_html__('Footer Menu', 'ostende')
			)
		);
		
		// Register theme-specific thumb sizes
		add_theme_support( 'post-thumbnails' );
		set_post_thumbnail_size(370, 0, false);
		$thumb_sizes = ostende_storage_get('theme_thumbs');
		$mult = ostende_get_theme_option('retina_ready', 1);
		if ($mult > 1) $GLOBALS['content_width'] = apply_filters( 'ostende_filter_content_width', 1170*$mult);
		foreach ($thumb_sizes as $k=>$v) {
			add_image_size( $k, $v['size'][0], $v['size'][1], $v['size'][2]);
			if ($mult > 1) add_image_size( $k.'-@retina', $v['size'][0]*$mult, $v['size'][1]*$mult, $v['size'][2]);
		}
		// Add new thumb names
		add_filter( 'image_size_names_choose',	'ostende_theme_thumbs_sizes' );

		// Excerpt filters
		add_filter( 'excerpt_length',			'ostende_excerpt_length' );
		add_filter( 'excerpt_more',				'ostende_excerpt_more' );
		
		// Add required meta tags in the head
		add_action('wp_head',		 			'ostende_wp_head', 0);
		
		// Load current page/post customization (if present)
		add_action('wp_footer',		 			'ostende_wp_footer');
		add_action('admin_footer',	 			'ostende_wp_footer');
		
		// Enqueue scripts and styles for frontend
		add_action('wp_enqueue_scripts', 		'ostende_wp_scripts', 1000);			// priority 1000 - load styles
																						// before the plugin's support custom styles
																						// (with priority 1100)
																						// and child-theme styles
																						// (with priority 1200)
		add_action('wp_enqueue_scripts', 		'ostende_wp_scripts_child', 1200);		// priority 1200 - load styles
																						// after the plugin's support custom styles
																						// (with priority 1100)
		add_action('wp_enqueue_scripts', 		'ostende_wp_scripts_responsive', 2000);	// priority 2000 - load responsive
																						// after all other styles
		add_action('wp_footer',		 			'ostende_localize_scripts');
		
		// Add body classes
		add_filter( 'body_class',				'ostende_add_body_classes' );

		// Register sidebars
		add_action('widgets_init',				'ostende_register_sidebars');
	}

}


//-------------------------------------------------------
//-- Theme scripts and styles
//-------------------------------------------------------

// Load frontend scripts
if ( !function_exists( 'ostende_wp_scripts' ) ) {
	function ostende_wp_scripts() {
		
		// Enqueue styles
		//------------------------
		
		// Links to selected fonts
		$links = ostende_theme_fonts_links();
		if (count($links) > 0) {
			foreach ($links as $slug => $link) {
				wp_enqueue_style( sprintf('ostende-font-%s', $slug), $link, array(), null );
			}
		}
		
		// Font icons styles must be loaded before main stylesheet
		// This style NEED the theme prefix, because style 'fontello' in some plugin contain different set of characters
		// and can't be used instead this style!
		wp_enqueue_style( 'ostende-icons',  ostende_get_file_url('css/font-icons/css/fontello-embedded.css'), array(), null );

		// Load main stylesheet
		$main_stylesheet = get_template_directory_uri() . '/style.css';
		wp_enqueue_style( 'ostende-main', $main_stylesheet, array(), null );
		wp_enqueue_style( 'ostende-layouts',  ostende_get_file_url('css/layouts.css'), array(), null );
		wp_enqueue_style( 'ostende-layouts-responsive',  ostende_get_file_url('css/layouts.responsive.css'), array(), null );

		// Add custom bg image for the Front page
		if ( is_front_page() 
			&& ostende_is_on(ostende_get_theme_option('front_page_enabled'))
			&& ($bg_image = ostende_remove_protocol_from_url(ostende_get_theme_option('front_page_bg_image'), false)) != '' )
			wp_add_inline_style( 'ostende-main', 'body.frontpage, body.home-page { background-image:url('.esc_url($bg_image).') !important }' );

		// Add custom bg image for the body_style == 'boxed'
		else if ( ostende_get_theme_option('body_style') == 'boxed' && ($bg_image = ostende_get_theme_option('boxed_bg_image')) != '' )
			wp_add_inline_style( 'ostende-main', '.body_style_boxed { background-image:url('.esc_url($bg_image).') !important }' );

		// Custom colors
		if ( !is_customize_preview() && !isset($_GET['color_scheme']) && ostende_is_off(ostende_get_theme_option('debug_mode')) )
			wp_enqueue_style( 'ostende-colors', ostende_get_file_url('css/__colors.css'), array(), null );
		else
			wp_add_inline_style( 'ostende-main', ostende_customizer_get_css() );

		// Add post nav background
		ostende_add_bg_in_post_nav();


		// Enqueue scripts	
		//------------------------

		// Modernizr will load in head before other scripts and styles
		$need_masonry = (ostende_storage_get('blog_archive')===true
							&& in_array(substr(ostende_get_theme_option('blog_style'), 0, 7), array('gallery', 'portfol', 'masonry')))
						|| (is_single()
							&& str_replace('post-format-', '', get_post_format())=='gallery');
		if ( $need_masonry )
			wp_enqueue_script( 'modernizr', ostende_get_file_url('js/theme-gallery/modernizr.min.js'), array(), null, false );

		// Superfish Menu
		// Attention! To prevent duplicate this script in the plugin and in the menu, don't merge it!
		wp_enqueue_script( 'superfish', ostende_get_file_url('js/superfish/superfish.min.js'), array('jquery'), null, true );
		
		// Merged scripts
		if ( ostende_is_off(ostende_get_theme_option('debug_mode')) )
			wp_enqueue_script( 'ostende-init', ostende_get_file_url('js/__scripts.js'), array('jquery'), null, true );
		else {
			// Skip link focus
			wp_enqueue_script( 'skip-link-focus-fix', ostende_get_file_url('js/skip-link-focus-fix.js'), null, true );
			// Background video
			$header_video = ostende_get_header_video();
			if (!empty($header_video) && !ostende_is_inherit($header_video)) {
				if (ostende_is_youtube_url($header_video))
					wp_enqueue_script( 'tubular', ostende_get_file_url('js/jquery.tubular.js'), array('jquery'), null, true );
				else
					wp_enqueue_script( 'bideo', ostende_get_file_url('js/bideo.js'), array(), null, true );
			}
			// Theme scripts
			wp_enqueue_script( 'ostende-utils', ostende_get_file_url('js/theme-utils.js'), array('jquery'), null, true );
			wp_enqueue_script( 'ostende-init', ostende_get_file_url('js/theme-init.js'), array('jquery'), null, true );	
		}
		// Load scripts for 'Masonry' layout
		if ( $need_masonry ) {
			wp_enqueue_script( 'imagesloaded' );
			wp_enqueue_script( 'masonry' );
			wp_enqueue_script( 'classie', ostende_get_file_url('js/theme-gallery/classie.min.js'), array(), null, true );
			wp_enqueue_script( 'ostende-gallery-script', ostende_get_file_url('js/theme-gallery/theme-gallery.js'), array(), null, true );
		}
		// Load scripts for 'Portfolio' layout
		if ( ostende_storage_get('blog_archive')===true
				&& in_array(substr(ostende_get_theme_option('blog_style'), 0, 7), array('gallery', 'portfol'))
				&& !is_customize_preview())
			wp_enqueue_script('jquery-ui-tabs', false, array('jquery', 'jquery-ui-core'), null, true);
		
		// Comments
		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}

		// Media elements library	
		if (ostende_get_theme_setting('use_mediaelements')) {
			wp_enqueue_style ( 'mediaelement' );
			wp_enqueue_style ( 'wp-mediaelement' );
			wp_enqueue_script( 'mediaelement' );
			wp_enqueue_script( 'wp-mediaelement' );
		}
	}
}

// Load child-theme stylesheet (if different) after all styles (with priorities 1000 and 1100)
if ( !function_exists( 'ostende_wp_scripts_child' ) ) {
	function ostende_wp_scripts_child() {
		$main_stylesheet = get_template_directory_uri() . '/style.css';
		$child_stylesheet = get_stylesheet_directory_uri() . '/style.css';
		if ($child_stylesheet != $main_stylesheet) {
			wp_enqueue_style( 'ostende-child', $child_stylesheet, array('ostende-main'), null );
		}
	}
}

// Add variables to the scripts in the frontend
if ( !function_exists( 'ostende_localize_scripts' ) ) {
	function ostende_localize_scripts() {

		$video = ostende_get_header_video();

		wp_localize_script( 'ostende-init', 'OSTENDE_STORAGE', apply_filters( 'ostende_filter_localize_script', array(
			// AJAX parameters
			'ajax_url' => esc_url(admin_url('admin-ajax.php')),
			'ajax_nonce' => esc_attr(wp_create_nonce(admin_url('admin-ajax.php'))),
			
			// Site base url
			'site_url' => home_url(),
			'theme_url' => get_template_directory_uri(),
						
			// Site color scheme
			'site_scheme' => sprintf('scheme_%s', ostende_get_theme_option('color_scheme')),
			
			// User logged in
			'user_logged_in' => is_user_logged_in() ? true : false,
			
			// Window width to switch the site header to the mobile layout
			'mobile_layout_width' => 1023,
			'mobile_device' => wp_is_mobile(),
						
			// Sidemenu options
			'menu_side_stretch' => ostende_get_theme_option('menu_side_stretch') > 0 ? true : false,
			'menu_side_icons' => ostende_get_theme_option('menu_side_icons') > 0 ? true : false,

			// Video background
			'background_video' => ostende_is_from_uploads($video) ? $video : '',

			// Video and Audio tag wrapper
			'use_mediaelements' => ostende_get_theme_setting('use_mediaelements') ? true : false,

			// Messages max length
			'comment_maxlength'	=> intval(ostende_get_theme_setting('comment_maxlength')),

			
			// Internal vars - do not change it!
			
			// Flag for review mechanism
			'admin_mode' => false,

			// E-mail mask
			'email_mask' => '^([a-zA-Z0-9_\\-]+\\.)*[a-zA-Z0-9_\\-]+@[a-z0-9_\\-]+(\\.[a-z0-9_\\-]+)*\\.[a-z]{2,6}$',
			
			// Strings for translation
			'strings' => array(
					'ajax_error'		=> esc_html__('Invalid server answer!', 'ostende'),
					'error_global'		=> esc_html__('Error data validation!', 'ostende'),
					'name_empty' 		=> esc_html__("The name can't be empty", 'ostende'),
					'name_long'			=> esc_html__('Too long name', 'ostende'),
					'email_empty'		=> esc_html__('Too short (or empty) email address', 'ostende'),
					'email_long'		=> esc_html__('Too long email address', 'ostende'),
					'email_not_valid'	=> esc_html__('Invalid email address', 'ostende'),
					'text_empty'		=> esc_html__("The message text can't be empty", 'ostende'),
					'text_long'			=> esc_html__('Too long message text', 'ostende')
					)
			))
		);
	}
}

// Load responsive styles (priority 2000 - load it after main styles and plugins custom styles)
if ( !function_exists( 'ostende_wp_scripts_responsive' ) ) {
	function ostende_wp_scripts_responsive() {
		wp_enqueue_style( 'ostende-responsive', ostende_get_file_url('css/responsive.css'), array(), null );
	}
}

//  Add meta tags and inline scripts in the header for frontend
if (!function_exists('ostende_wp_head')) {
	function ostende_wp_head() {
		?>
		<meta charset="<?php bloginfo( 'charset' ); ?>">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
		<meta name="format-detection" content="telephone=no">
		<link rel="profile" href="//gmpg.org">
		<?php
		if ( is_singular() && pings_open() ) {
			?>
			<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
			<?php
		}
	}
}

// Add theme specified classes to the body
if ( !function_exists('ostende_add_body_classes') ) {
	function ostende_add_body_classes( $classes ) {
		$classes[] = 'body_tag';	// Need for the .scheme_self
		$classes[] = 'scheme_' . esc_attr(ostende_get_theme_option('color_scheme'));

		$blog_mode = ostende_storage_get('blog_mode');
		$classes[] = 'blog_mode_' . esc_attr($blog_mode);
		$classes[] = 'body_style_' . esc_attr(ostende_get_theme_option('body_style'));

		if (in_array($blog_mode, array('post', 'page'))) {
			$classes[] = 'is_single';
		} else {
			$classes[] = ' is_stream';
			$classes[] = 'blog_style_'.esc_attr(ostende_get_theme_option('blog_style'));
			if (ostende_storage_get('blog_template') > 0)
				$classes[] = 'blog_template';
		}
		
		if (ostende_sidebar_present()) {
			$classes[] = 'sidebar_show sidebar_' . esc_attr(ostende_get_theme_option('sidebar_position')) ;
		} else {
			$classes[] = 'sidebar_hide';
			if (ostende_is_on(ostende_get_theme_option('expand_content')))
				 $classes[] = 'expand_content';
		}
		
		if (ostende_is_on(ostende_get_theme_option('remove_margins')))
			 $classes[] = 'remove_margins';

		if ( is_front_page() 
			&& ostende_is_on(ostende_get_theme_option('front_page_enabled')) 
			&& ($bg_image = ostende_get_theme_option('front_page_bg_image')) != '' )
			$classes[] = 'with_bg_image';

		
		
		$classes[] = 'header_type_' . esc_attr(ostende_get_theme_option("header_type"));
		$classes[] = 'header_style_' . esc_attr(ostende_get_theme_option("header_type")=='default'
													? 'header-default'
													: ostende_get_theme_option("header_style"));
		$classes[] = 'header_position_' . esc_attr(ostende_get_theme_option("header_position"));

		$menu_style= ostende_get_theme_option("menu_style");
		$classes[] = 'menu_style_' . esc_attr($menu_style) . (in_array($menu_style, array('left', 'right'))	? ' menu_style_side' : '');
		$classes[] = 'no_layout';
		
		return $classes;
	}
}
	
// Load current page/post customization (if present)
if ( !function_exists( 'ostende_wp_footer' ) ) {
	function ostende_wp_footer() {
		// Add header zoom
		$header_zoom = max(0.3, min(2, (float) ostende_get_theme_option('header_zoom')));
		if ( $header_zoom != 1 ) ostende_add_inline_css(".sc_layouts_title_title{font-size:{$header_zoom}em}");
		// Add logo zoom
		$logo_zoom = max(0.3, min(2, (float) ostende_get_theme_option('logo_zoom')));
		if ( $logo_zoom != 1 ) ostende_add_inline_css(".custom-logo-link,.sc_layouts_logo{font-size:{$logo_zoom}em}");
		// Put inline styles to the output
		if (($css = ostende_get_inline_css()) != '') {
			wp_enqueue_style(  'ostende-inline-styles',  ostende_get_file_url('css/__inline.css'), array(), null );
			wp_add_inline_style( 'ostende-inline-styles', $css );
		}
	}
}


//-------------------------------------------------------
//-- Sidebars and widgets
//-------------------------------------------------------

// Register widgetized areas
if ( !function_exists('ostende_register_sidebars') ) {
	function ostende_register_sidebars() {
		$sidebars = ostende_get_sidebars();
		if (is_array($sidebars) && count($sidebars) > 0) {
			foreach ($sidebars as $id=>$sb) {
				register_sidebar( array(
										'name'          => esc_html($sb['name']),
										'description'   => esc_html($sb['description']),
										'id'            => esc_attr($id),
										'before_widget' => '<aside id="%1$s" class="widget %2$s">',
										'after_widget'  => '</aside>',
										'before_title'  => '<h5 class="widget_title">',
										'after_title'   => '</h5>'
										)
								);
			}
		}
	}
}

// Return theme specific widgetized areas
if ( !function_exists('ostende_get_sidebars') ) {
	function ostende_get_sidebars() {
		$list = apply_filters('ostende_filter_list_sidebars', array(
			'sidebar_widgets'		=> array(
							'name' => esc_html__('Sidebar Widgets', 'ostende'),
							'description' => esc_html__('Widgets to be shown on the main sidebar', 'ostende')
							),
			'header_widgets'		=> array(
							'name' => esc_html__('Header Widgets', 'ostende'),
							'description' => esc_html__('Widgets to be shown at the top of the page (in the page header area)', 'ostende')
							),
			'above_page_widgets'	=> array(
							'name' => esc_html__('Top Page Widgets', 'ostende'),
							'description' => esc_html__('Widgets to be shown below the header, but above the content and sidebar', 'ostende')
							),
			'above_content_widgets' => array(
							'name' => esc_html__('Above Content Widgets', 'ostende'),
							'description' => esc_html__('Widgets to be shown above the content, near the sidebar', 'ostende')
							),
			'below_content_widgets' => array(
							'name' => esc_html__('Below Content Widgets', 'ostende'),
							'description' => esc_html__('Widgets to be shown below the content, near the sidebar', 'ostende')
							),
			'below_page_widgets' 	=> array(
							'name' => esc_html__('Bottom Page Widgets', 'ostende'),
							'description' => esc_html__('Widgets to be shown below the content and sidebar, but above the footer', 'ostende')
							),
			'footer_widgets'		=> array(
							'name' => esc_html__('Footer Widgets', 'ostende'),
							'description' => esc_html__('Widgets to be shown at the bottom of the page (in the page footer area)', 'ostende')
							)
			)
		);
		return $list;
	}
}


//-------------------------------------------------------
//-- Theme fonts
//-------------------------------------------------------

// Return links for all theme fonts
if ( !function_exists('ostende_theme_fonts_links') ) {
	function ostende_theme_fonts_links() {
		$links = array();
		
		/*
		Translators: If there are characters in your language that are not supported
		by chosen font(s), translate this to 'off'. Do not translate into your own language.
		*/
		$google_fonts_enabled = ( 'off' !== esc_html_x( 'on', 'Google fonts: on or off', 'ostende' ) );
		$custom_fonts_enabled = ( 'off' !== esc_html_x( 'on', 'Custom fonts (included in the theme): on or off', 'ostende' ) );
		
		if ( ($google_fonts_enabled || $custom_fonts_enabled) && !ostende_storage_empty('load_fonts') ) {
			$load_fonts = (array)ostende_storage_get('load_fonts');
			if (count($load_fonts) > 0) {
				$google_fonts = '';
				foreach ($load_fonts as $font) {
					$url = '';
					if ($custom_fonts_enabled && empty($font['styles'])) {
						$slug = ostende_get_load_fonts_slug($font['name']);
						$url  = ostende_get_file_url( sprintf('css/font-face/%s/stylesheet.css', $slug));
						if ($url != '') $links[$slug] = $url;
					}
					if ($google_fonts_enabled && empty($url)) {
						$google_fonts .= ($google_fonts ? '|' : '') 
										. str_replace(' ', '+', $font['name'])
										. ':' 
										. (empty($font['styles']) ? '400,400italic,700,700italic' : $font['styles']);
					}
				}
				if ($google_fonts && $google_fonts_enabled) {
					$links['google_fonts'] = sprintf('%s://fonts.googleapis.com/css?family=%s&subset=%s', ostende_get_protocol(), $google_fonts, ostende_get_theme_option('load_fonts_subset'));
				}
			}
		}
		return $links;
	}
}

// Return links for WP Editor
if ( !function_exists('ostende_theme_fonts_for_editor') ) {
	function ostende_theme_fonts_for_editor() {
		$links = array_values(ostende_theme_fonts_links());
		if (is_array($links) && count($links) > 0) {
			for ($i=0; $i<count($links); $i++) {
				$links[$i] = str_replace(',', '%2C', $links[$i]);
			}
		}
		return $links;
	}
}


//-------------------------------------------------------
//-- The Excerpt
//-------------------------------------------------------
if ( !function_exists('ostende_excerpt_length') ) {
	function ostende_excerpt_length( $length ) {
		return max(1, ostende_get_theme_option('excerpt_length'));
	}
}

if ( !function_exists('ostende_excerpt_more') ) {
	function ostende_excerpt_more( $more ) {
		return '&hellip;';
	}
}




//-------------------------------------------------------
//-- Thumb sizes
//-------------------------------------------------------
if ( !function_exists('ostende_theme_thumbs_sizes') ) {
	function ostende_theme_thumbs_sizes( $sizes ) {
		$thumb_sizes = ostende_storage_get('theme_thumbs');
		$mult = ostende_get_theme_option('retina_ready', 1);
		foreach($thumb_sizes as $k=>$v) {
			$sizes[$k] = $v['title'];
			if ($mult > 1) $sizes[$k.'-@retina'] = $v['title'].' '.esc_html__('@2x', 'ostende' );
		}
		return $sizes;
	}
}



// revslider date for Events
add_filter('revslider_slide_setLayersByPostData_post', 'ostende_revslider_date', 10, 4);
function ostende_revslider_date($attr, $postData, $sliderID, $sliderObj) {
    if (!empty($postData['ID']) && !empty($postData['post_type']) && $postData['post_type'] == 'tribe_events') {
        $attr['postDate'] = tribe_get_start_date($postData['ID'], true, 'd M') .' - ' . tribe_get_end_date($postData['ID'], true, 'd M');
    }
    return $attr;
}




//-------------------------------------------------------
//-- Include theme (or child) PHP-files
//-------------------------------------------------------

require_once OSTENDE_THEME_DIR . 'includes/utils.php';
require_once OSTENDE_THEME_DIR . 'includes/storage.php';
require_once OSTENDE_THEME_DIR . 'includes/lists.php';
require_once OSTENDE_THEME_DIR . 'includes/wp.php';

if (is_admin()) {
	require_once OSTENDE_THEME_DIR . 'includes/tgmpa/class-tgm-plugin-activation.php';
	require_once OSTENDE_THEME_DIR . 'includes/admin.php';
}

require_once OSTENDE_THEME_DIR . 'theme-options/theme-customizer.php';

require_once OSTENDE_THEME_DIR . 'theme-specific/theme-tags.php';
require_once OSTENDE_THEME_DIR . 'theme-specific/theme-hovers/theme-hovers.php';
require_once OSTENDE_THEME_DIR . 'theme-specific/theme-about/theme-about.php';

require_once OSTENDE_THEME_DIR . 'theme-specific/theme-about/theme-upgrade.php';


// Plugins support
if (is_array($OSTENDE_STORAGE['required_plugins']) && count($OSTENDE_STORAGE['required_plugins']) > 0) {
	foreach ($OSTENDE_STORAGE['required_plugins'] as $plugin_slug => $plugin_name) {
		$plugin_slug = ostende_esc($plugin_slug);
		$plugin_path = OSTENDE_THEME_DIR . sprintf('plugins/%s/%s.php', $plugin_slug, $plugin_slug);
		if (file_exists($plugin_path)) { require_once $plugin_path; }
	}
}
?>