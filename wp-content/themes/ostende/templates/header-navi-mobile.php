<?php
/**
 * The template to show mobile menu
 *
 
 * @subpackage OSTENDE
 * @since OSTENDE 1.0
 */
?>
<div class="menu_mobile_overlay"></div>
<div class="menu_mobile menu_mobile_<?php echo esc_attr(ostende_get_theme_option('menu_mobile_fullscreen') > 0 ? 'fullscreen' : 'narrow'); ?> scheme_dark" tabindex="-1">
	<div class="menu_mobile_inner" >
		<a class="menu_mobile_close icon-cancel"  tabindex="0" ></a><?php

		// Logo
		set_query_var('ostende_logo_args', array('type' => 'mobile'));
		get_template_part( 'templates/header-logo' );
		set_query_var('ostende_logo_args', array());

		// Mobile menu
		$ostende_menu_mobile = ostende_get_nav_menu('menu_mobile');
		if (empty($ostende_menu_mobile)) {
			$ostende_menu_mobile = apply_filters('ostende_filter_get_mobile_menu', '');
			if (empty($ostende_menu_mobile)) $ostende_menu_mobile = ostende_get_nav_menu('menu_main');
			if (empty($ostende_menu_mobile)) $ostende_menu_mobile = ostende_get_nav_menu();
		}
		if (!empty($ostende_menu_mobile)) {
			if (!empty($ostende_menu_mobile))
				$ostende_menu_mobile = str_replace(
					array('menu_main', 'id="menu-', 'sc_layouts_menu_nav', 'sc_layouts_hide_on_mobile', 'hide_on_mobile'),
					array('menu_mobile', 'id="menu_mobile-', '', '', ''),
					$ostende_menu_mobile
					);
			if (strpos($ostende_menu_mobile, '<nav ')===false)
				$ostende_menu_mobile = sprintf('<nav class="menu_mobile_nav_area">%s</nav>', $ostende_menu_mobile);
			ostende_show_layout(apply_filters('ostende_filter_menu_mobile_layout', $ostende_menu_mobile));
		}

		// Search field
		do_action('ostende_action_search', 'normal', 'search_mobile', false);
		
		// Social icons
		ostende_show_layout(ostende_get_socials_links(), '<div class="socials_mobile">', '</div>');
		?>
	</div>
</div>
