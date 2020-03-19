<?php
/**
 * The template to display default site header
 *
 
 * @subpackage OSTENDE
 * @since OSTENDE 1.0
 */

$ostende_header_css = '';
$ostende_header_image = get_header_image();
$ostende_header_video = ostende_get_header_video();
if (!empty($ostende_header_image) && ostende_trx_addons_featured_image_override(is_singular() || ostende_storage_isset('blog_archive') || is_category())) {
	$ostende_header_image = ostende_get_current_mode_image($ostende_header_image);
}

?><header class="top_panel top_panel_default<?php
					echo !empty($ostende_header_image) || !empty($ostende_header_video) ? ' with_bg_image' : ' without_bg_image';
					if ($ostende_header_video!='') echo ' with_bg_video';
					if ($ostende_header_image!='') echo ' '.esc_attr(ostende_add_inline_css_class('background-image: url('.esc_url($ostende_header_image).');'));
					if (is_single() && has_post_thumbnail()) echo ' with_featured_image';
					if (ostende_is_on(ostende_get_theme_option('header_fullheight'))) echo ' header_fullheight ostende-full-height';
					if (!ostende_is_inherit(ostende_get_theme_option('header_scheme')))
						echo ' scheme_' . esc_attr(ostende_get_theme_option('header_scheme'));
					?>"><?php

	// Background video
	if (!empty($ostende_header_video)) {
		get_template_part( 'templates/header-video' );
	}
	
	// Main menu
	if (ostende_get_theme_option("menu_style") == 'top') {
		get_template_part( 'templates/header-navi' );
	}

	// Mobile header
	if (ostende_is_on(ostende_get_theme_option("header_mobile_enabled"))) {
		get_template_part( 'templates/header-mobile' );
	}
	
	// Page title and breadcrumbs area
	get_template_part( 'templates/header-title');

	// Header widgets area
	get_template_part( 'templates/header-widgets' );

	// Display featured image in the header on the single posts
	// Comment next line to prevent show featured image in the header area
	// and display it in the post's content

?></header>