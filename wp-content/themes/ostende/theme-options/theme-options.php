<?php
/**
 * Theme Options, Color Schemes and Fonts utilities
 *
 
 * @subpackage OSTENDE
 * @since OSTENDE 1.0
 */

// -----------------------------------------------------------------
// -- Create and manage Theme Options
// -----------------------------------------------------------------

// Theme init priorities:
// 2 - create Theme Options
if (!function_exists('ostende_options_theme_setup2')) {
	add_action( 'after_setup_theme', 'ostende_options_theme_setup2', 2 );
	function ostende_options_theme_setup2() {
		ostende_create_theme_options();
	}
}

// Step 1: Load default settings and previously saved mods
if (!function_exists('ostende_options_theme_setup5')) {
	add_action( 'after_setup_theme', 'ostende_options_theme_setup5', 5 );
	function ostende_options_theme_setup5() {
		ostende_storage_set('options_reloaded', false);
		ostende_load_theme_options();
	}
}

// Step 2: Load current theme customization mods
if (is_customize_preview()) {
	if (!function_exists('ostende_load_custom_options')) {
		add_action( 'wp_loaded', 'ostende_load_custom_options' );
		function ostende_load_custom_options() {
			if (!ostende_storage_get('options_reloaded')) {
				ostende_storage_set('options_reloaded', true);
				ostende_load_theme_options();
			}
		}
	}
}

// Load current values for each customizable option
if ( !function_exists('ostende_load_theme_options') ) {
	function ostende_load_theme_options() {
		$options = ostende_storage_get('options');
		$reset = (int) get_theme_mod('reset_options', 0);
		foreach ($options as $k=>$v) {
			if (isset($v['std'])) {
				$value = ostende_get_theme_option_std($k, $v['std']);
				if (!$reset) {
					if (isset($_GET[$k]))
						$value = wp_kses_data(wp_unslash($_GET[$k]));
					else {
						$default_value = -987654321;
						$tmp = get_theme_mod($k, $default_value);
						if ($tmp != $default_value) $value = $tmp;
					}
				}
				ostende_storage_set_array2('options', $k, 'val', $value);
				if ($reset) remove_theme_mod($k);
			}
		}
		if ($reset) {
			// Unset reset flag
			set_theme_mod('reset_options', 0);
			// Regenerate CSS with default colors and fonts
			ostende_customizer_save_css();
		} else {
			do_action('ostende_action_load_options');
		}
	}
}

// Override options with stored page/post meta
if ( !function_exists('ostende_override_theme_options') ) {
	add_action( 'wp', 'ostende_override_theme_options', 1 );
	function ostende_override_theme_options($query=null) {
		if (is_page_template('blog.php')) {
			ostende_storage_set('blog_archive', true);
			ostende_storage_set('blog_template', get_the_ID());
		}
		ostende_storage_set('blog_mode', ostende_detect_blog_mode());
		if (is_singular()) {
			ostende_storage_set('options_meta', get_post_meta(get_the_ID(), 'ostende_options', true));
		}
		do_action('ostende_action_override_theme_options');
	}
}

// Override options with stored page meta on 'Blog posts' pages
if ( !function_exists('ostende_blog_override_theme_options') ) {
	add_action( 'ostende_action_override_theme_options', 'ostende_blog_override_theme_options');
	function ostende_blog_override_theme_options() {
		global $wp_query;
		if (is_home() && !is_front_page() && !empty($wp_query->is_posts_page)) {
			if (($id = get_option('page_for_posts')) > 0)
				ostende_storage_set('options_meta', get_post_meta($id, 'ostende_options', true));
		}
	}
}


// Return 'std' value of the option, processed by special function (if specified)
if (!function_exists('ostende_get_theme_option_std')) {
	function ostende_get_theme_option_std($opt_name, $opt_std) {
		if (strpos($opt_std, '$ostende_')!==false) {
			$func = substr($opt_std, 1);
			if (function_exists($func)) {
				$opt_std = $func($opt_name);
			}
		}
		return $opt_std;
	}
}


// Return customizable option value
if (!function_exists('ostende_get_theme_option')) {
	function ostende_get_theme_option($name, $defa='', $strict_mode=false, $post_id=0) {
		$rez = $defa;
		$from_post_meta = false;

		if ($post_id > 0) {
			if (!ostende_storage_isset('post_options_meta', $post_id))
				ostende_storage_set_array('post_options_meta', $post_id, get_post_meta($post_id, 'ostende_options', true));
			if (ostende_storage_isset('post_options_meta', $post_id, $name)) {
				$tmp = ostende_storage_get_array('post_options_meta', $post_id, $name);
				if (!ostende_is_inherit($tmp)) {
					$rez = $tmp;
					$from_post_meta = true;
				}
			}
		}

		if (!$from_post_meta && ostende_storage_isset('options')) {

			$blog_mode = ostende_storage_get('blog_mode');

			if ( !ostende_storage_isset('options', $name) && (empty($blog_mode) || !ostende_storage_isset('options', $name.'_'.$blog_mode)) ) {
				$rez = $tmp = '_not_exists_';
				if (function_exists('trx_addons_get_option'))
					$rez = trx_addons_get_option($name, $tmp, false);
				if ($rez === $tmp) {
					if ($strict_mode) {
						// Translators: Add option's name to the output
						echo '<pre>' . esc_html(sprintf(__('Undefined option "%s" called from:', 'ostende'), $name));
						if (function_exists('dcs')) dcs();
						echo '</pre>';
						die();
					} else
						$rez = $defa;
				}

			} else {

				$blog_mode_parent = $blog_mode=='post'
										? 'blog'
										: str_replace('_single', '', $blog_mode);

				// Override option from GET or POST for current blog mode
				if (!empty($blog_mode) && isset($_REQUEST[$name . '_' . $blog_mode])) {
					$rez = wp_kses_data(wp_unslash($_REQUEST[$name . '_' . $blog_mode]));

				// Override option from GET
				} else if (isset($_REQUEST[$name])) {
					$rez = wp_kses_data(wp_unslash($_REQUEST[$name]));

				// Override option from current page settings (if exists)
				} else if (ostende_storage_isset('options_meta', $name) && !ostende_is_inherit(ostende_storage_get_array('options_meta', $name))) {
					$rez = ostende_storage_get_array('options_meta', $name);

				// Override option from current blog mode settings: 'front', 'search', 'page', 'post', 'blog', etc. (if exists)
				} else if (!empty($blog_mode) && ostende_storage_isset('options', $name . '_' . $blog_mode, 'val') && !ostende_is_inherit(ostende_storage_get_array('options', $name . '_' . $blog_mode, 'val'))) {
					$rez = ostende_storage_get_array('options', $name . '_' . $blog_mode, 'val');

				// Override option for 'post' from 'blog' settings (if exists)
				// Also used for override 'xxx_single' on the 'xxx'
				// (for example, instead 'sidebar_courses_single' return option for 'sidebar_courses')
				} else if (!empty($blog_mode_parent) && $blog_mode!=$blog_mode_parent && ostende_storage_isset('options', $name . '_' . $blog_mode_parent, 'val') && !ostende_is_inherit(ostende_storage_get_array('options', $name . '_' . $blog_mode_parent, 'val'))) {
					$rez = ostende_storage_get_array('options', $name . '_' . $blog_mode_parent, 'val');

				// Get saved option value
				} else if (ostende_storage_isset('options', $name, 'val')) {
					$rez = ostende_storage_get_array('options', $name, 'val');

				// Get ThemeREX Addons option value
				} else if (function_exists('trx_addons_get_option')) {
					$rez = trx_addons_get_option($name, $defa, false);

				}
			}
		}
		return $rez;
	}
}


// Check if customizable option exists
if (!function_exists('ostende_check_theme_option')) {
	function ostende_check_theme_option($name) {
		return ostende_storage_isset('options', $name);
	}
}


// Return customizable option value, stored in the posts meta
if (!function_exists('ostende_get_theme_option_from_meta')) {
	function ostende_get_theme_option_from_meta($name, $defa='') {
		$rez = $defa;
		if (ostende_storage_isset('options_meta')) {
			if (ostende_storage_isset('options_meta', $name))
				$rez = ostende_storage_get_array('options_meta', $name);
			else
				$rez = 'inherit';
		}
		return $rez;
	}
}


// Get dependencies list from the Theme Options
if ( !function_exists('ostende_get_theme_dependencies') ) {
	function ostende_get_theme_dependencies() {
		$options = ostende_storage_get('options');
		$depends = array();
		foreach ($options as $k=>$v) {
			if (isset($v['dependency'])) 
				$depends[$k] = $v['dependency'];
		}
		return $depends;
	}
}



// -----------------------------------------------------------------
// -- Theme Settings utilities
// -----------------------------------------------------------------

// Return internal theme setting value
if (!function_exists('ostende_get_theme_setting')) {
	function ostende_get_theme_setting($name) {
		if ( !ostende_storage_isset('settings', $name) ) {
			// Translators: Add setting's name to the output
			echo '<pre>' . esc_html(sprintf(__('Undefined setting "%s" called from:', 'ostende'), $name));
			if (function_exists('dcs')) dcs();
			echo '</pre>';
			die();
		} else
			return ostende_storage_get_array('settings', $name);
	}
}

// Set theme setting
if ( !function_exists( 'ostende_set_theme_setting' ) ) {
	function ostende_set_theme_setting($option_name, $value) {
		if (ostende_storage_isset('settings', $option_name))
			ostende_storage_set_array('settings', $option_name, $value);
	}
}



// -----------------------------------------------------------------
// -- Color Schemes utilities
// -----------------------------------------------------------------

// Load saved values to the color schemes
if (!function_exists('ostende_load_schemes')) {
	add_action('ostende_action_load_options', 'ostende_load_schemes');
	function ostende_load_schemes() {
		$schemes = ostende_storage_get('schemes');
		$storage = ostende_unserialize(ostende_get_theme_option('scheme_storage'));
		if (is_array($storage) && count($storage) > 0)  {
			foreach ($storage as $k=>$v) {
				if (isset($schemes[$k])) {
					$schemes[$k] = $v;
				}
			}
			ostende_storage_set('schemes', $schemes);
		}
	}
}

// Return specified color from current (or specified) color scheme
if ( !function_exists( 'ostende_get_scheme_color' ) ) {
	function ostende_get_scheme_color($color_name, $scheme = '') {
		if (empty($scheme)) $scheme = ostende_get_theme_option( 'color_scheme' );
		if (empty($scheme) || ostende_storage_empty('schemes', $scheme)) $scheme = 'default';
		$colors = ostende_storage_get_array('schemes', $scheme, 'colors');
		return $colors[$color_name];
	}
}

// Return colors from current color scheme
if ( !function_exists( 'ostende_get_scheme_colors' ) ) {
	function ostende_get_scheme_colors($scheme = '') {
		if (empty($scheme)) $scheme = ostende_get_theme_option( 'color_scheme' );
		if (empty($scheme) || ostende_storage_empty('schemes', $scheme)) $scheme = 'default';
		return ostende_storage_get_array('schemes', $scheme, 'colors');
	}
}

// Return colors from all schemes
if ( !function_exists( 'ostende_get_scheme_storage' ) ) {
	function ostende_get_scheme_storage($scheme = '') {
		return serialize(ostende_storage_get('schemes'));
	}
}

// Return schemes list
if ( !function_exists( 'ostende_get_list_schemes' ) ) {
	function ostende_get_list_schemes($prepend_inherit=false) {
		$list = array();
		$schemes = ostende_storage_get('schemes');
		if (is_array($schemes) && count($schemes) > 0) {
			foreach ($schemes as $slug => $scheme) {
				$list[$slug] = $scheme['title'];
			}
		}
		return $prepend_inherit ? ostende_array_merge(array('inherit' => esc_html__("Inherit", 'ostende')), $list) : $list;
	}
}



// -----------------------------------------------------------------
// -- Theme Fonts utilities
// -----------------------------------------------------------------

// Load saved values into fonts list
if (!function_exists('ostende_load_fonts')) {
	add_action('ostende_action_load_options', 'ostende_load_fonts');
	function ostende_load_fonts() {
		// Fonts to load when theme starts
		$load_fonts = array();
		for ($i=1; $i<=ostende_get_theme_setting('max_load_fonts'); $i++) {
			if (($name = ostende_get_theme_option("load_fonts-{$i}-name")) != '') {
				$load_fonts[] = array(
					'name'	 => $name,
					'family' => ostende_get_theme_option("load_fonts-{$i}-family"),
					'styles' => ostende_get_theme_option("load_fonts-{$i}-styles")
				);
			}
		}
		ostende_storage_set('load_fonts', $load_fonts);
		ostende_storage_set('load_fonts_subset', ostende_get_theme_option("load_fonts_subset"));
		
		// Font parameters of the main theme's elements
		$fonts = ostende_get_theme_fonts();
		foreach ($fonts as $tag=>$v) {
			foreach ($v as $css_prop=>$css_value) {
				if (in_array($css_prop, array('title', 'description'))) continue;
				$fonts[$tag][$css_prop] = ostende_get_theme_option("{$tag}_{$css_prop}");
			}
		}
		ostende_storage_set('theme_fonts', $fonts);
	}
}

// Return slug of the loaded font
if (!function_exists('ostende_get_load_fonts_slug')) {
	function ostende_get_load_fonts_slug($name) {
		return str_replace(' ', '-', $name);
	}
}

// Return load fonts parameter's default value
if (!function_exists('ostende_get_load_fonts_option')) {
	function ostende_get_load_fonts_option($option_name) {
		$rez = '';
		$parts = explode('-', $option_name);
		$load_fonts = (array)ostende_storage_get('load_fonts');
		if ($parts[0] == 'load_fonts' && count($load_fonts) > $parts[1]-1 && isset($load_fonts[$parts[1]-1][$parts[2]])) {
			$rez = $load_fonts[$parts[1]-1][$parts[2]];
		}
		return $rez;
	}
}

// Return load fonts subset's default value
if (!function_exists('ostende_get_load_fonts_subset')) {
	function ostende_get_load_fonts_subset($option_name) {
		return ostende_storage_get('load_fonts_subset');
	}
}

// Return load fonts list
if (!function_exists('ostende_get_list_load_fonts')) {
	function ostende_get_list_load_fonts($prepend_inherit=false) {
		$list = array();
		$load_fonts = ostende_storage_get('load_fonts');
		if (is_array($load_fonts) && count($load_fonts) > 0) {
			foreach ($load_fonts as $font) {
				$list['"'.trim($font['name']).'"'.(!empty($font['family']) ? ','.trim($font['family']): '')] = $font['name'];
			}
		}
		return $prepend_inherit ? ostende_array_merge(array('inherit' => esc_html__("Inherit", 'ostende')), $list) : $list;
	}
}

// Return font settings of the theme specific elements
if ( !function_exists( 'ostende_get_theme_fonts' ) ) {
	function ostende_get_theme_fonts() {
		return ostende_storage_get('theme_fonts');
	}
}

// Return theme fonts parameter's default value
if (!function_exists('ostende_get_theme_fonts_option')) {
	function ostende_get_theme_fonts_option($option_name) {
		$rez = '';
		$parts = explode('_', $option_name);
		$theme_fonts = ostende_storage_get('theme_fonts');
		if (!empty($theme_fonts[$parts[0]][$parts[1]])) {
			$rez = $theme_fonts[$parts[0]][$parts[1]];
		}
		return $rez;
	}
}

// Update loaded fonts list in the each tag's parameter (p, h1..h6,...) after the 'load_fonts' options are loaded
if (!function_exists('ostende_update_list_load_fonts')) {
	add_action('ostende_action_load_options', 'ostende_update_list_load_fonts', 11);
	function ostende_update_list_load_fonts() {
		$theme_fonts = ostende_get_theme_fonts();
		$load_fonts = ostende_get_list_load_fonts(true);
		foreach ($theme_fonts as $tag=>$v) {
			ostende_storage_set_array2('options', $tag.'_font-family', 'options', $load_fonts);
		}
	}
}



// -----------------------------------------------------------------
// -- Other options utilities
// -----------------------------------------------------------------

// Return current theme-specific border radius for form's fields and buttons
if ( !function_exists( 'ostende_get_border_radius' ) ) {
	function ostende_get_border_radius() {
		$rad = str_replace(' ', '', ostende_get_theme_option('border_radius'));
		if (empty($rad)) $rad = 0;
		return ostende_prepare_css_value($rad); 
	}
}



// Display single option's field
if ( !function_exists('ostende_options_show_field') ) {
	function ostende_options_show_field($name, $field, $post_type='') {

		$inherit_allow = !empty($post_type);
		$inherit_state = !empty($post_type) && isset($field['val']) && ostende_is_inherit($field['val']);
		
		$field_data_present = $field['type']!='info' || !empty($field['override']['desc']) || !empty($field['desc']);

		if (   ($field['type'] == 'hidden' && $inherit_allow) 	// Hidden field in the post meta (not in the root Theme Options)
			|| (!empty($field['hidden']) && !$inherit_allow)	// Field only for post meta in the root Theme Options
		   ) return '';
		
		if ($field['type'] == 'hidden') {

			$output = '<input type="hidden" name="ostende_options_field_'.esc_attr($name).'"'
								. ' value="'.esc_attr($field['val']).'"'
								. ' />';
		} else {
		
		$output = (!empty($field['class']) && strpos($field['class'], 'ostende_new_row')!==false 
					? '<div class="ostende_new_row_before"></div>'
					: '')
					. '<div class="ostende_options_item ostende_options_item_'.esc_attr($field['type'])
								. ($inherit_allow ? ' ostende_options_inherit_'.($inherit_state ? 'on' : 'off' ) : '')
								. (!empty($field['class']) ? ' '.esc_attr($field['class']) : '')
								. '">'
						. '<h4 class="ostende_options_item_title">'
							. esc_html($field['title'])
							. ($inherit_allow 
									? '<span class="ostende_options_inherit_lock" id="ostende_options_inherit_'.esc_attr($name).'"></span>'
									: '')
						. '</h4>'
						. ($field_data_present
							? '<div class="ostende_options_item_data">'
								. '<div class="ostende_options_item_field" data-param="'.esc_attr($name).'"'
									. (!empty($field['linked']) ? ' data-linked="'.esc_attr($field['linked']).'"' : '')
									. '>'
							: '');
	
		// Type 'checkbox'
		if ($field['type']=='checkbox') {
			$output .= '<label class="ostende_options_item_label">'
						. '<input type="checkbox" name="ostende_options_field_'.esc_attr($name).'" value="1"'
								.($field['val']==1 ? ' checked="checked"' : '')
								.' />'
						. esc_html($field['title'])
					. '</label>';
		
		// Type 'switch' (2 choises) or 'radio' (3+ choises)
		} else if (in_array($field['type'], array('switch', 'radio'))) {
			$field['options'] = apply_filters('ostende_filter_options_get_list_choises', $field['options'], $name);
			$first = true;
			foreach ($field['options'] as $k=>$v) {
				$output .= '<label class="ostende_options_item_label">'
							. '<input type="radio" name="ostende_options_field_'.esc_attr($name).'"'
									. ' value="'.esc_attr($k).'"'
									. ($field['val']==$k || ($first && !isset($field['options'][$field['val']])) ? ' checked="checked"' : '')
									. ' />'
							. esc_html($v)
						. '</label>';
				$first = false;
			}

		// Type 'text' or 'time' or 'date'
		} else if (in_array($field['type'], array('text', 'time', 'date'))) {
			$output .= '<input type="text" name="ostende_options_field_'.esc_attr($name).'"'
							. ' value="'.esc_attr(ostende_is_inherit($field['val']) ? '' : $field['val']).'"'
							. ' />';
		
		// Type 'textarea'
		} else if ($field['type']=='textarea') {
			$output .= '<textarea name="ostende_options_field_'.esc_attr($name).'">'
							. esc_html(ostende_is_inherit($field['val']) ? '' : $field['val'])
						. '</textarea>';
		
		// Type 'text_editor'
		} else if ($field['type']=='text_editor') {
			$output .= '<input type="hidden" id="ostende_options_field_'.esc_attr($name).'"'
							. ' name="ostende_options_field_'.esc_attr($name).'"'
							. ' value="'.esc_textarea(ostende_is_inherit($field['val']) ? '' : $field['val']).'"'
							. ' />'
						. ostende_show_custom_field('ostende_options_field_'.esc_attr($name).'_tinymce',
													$field,
													ostende_is_inherit($field['val']) ? '' : $field['val']);

		// Type 'select'
		} else if ($field['type']=='select') {
			$field['options'] = apply_filters('ostende_filter_options_get_list_choises', $field['options'], $name);
			$output .= '<select size="1" name="ostende_options_field_'.esc_attr($name).'">';
			foreach ($field['options'] as $k=>$v) {
				$output .= '<option value="'.esc_attr($k).'"'.($field['val']==$k ? ' selected="selected"' : '').'>'.esc_html($v).'</option>';
			}
			$output .= '</select>';

		// Type 'image', 'media', 'video' or 'audio'
		} else if (in_array($field['type'], array('image', 'media', 'video', 'audio'))) {
			if ( (int) $field['val'] > 0 ) {
				$image = wp_get_attachment_image_src( $field['val'], 'full' );
				$field['val'] = $image[0];
			}
			$output .= (!empty($field['multiple'])
						? '<input type="hidden" id="ostende_options_field_'.esc_attr($name).'"'
							. ' name="ostende_options_field_'.esc_attr($name).'"'
							. ' value="'.esc_attr(ostende_is_inherit($field['val']) ? '' : $field['val']).'"'
							. ' />'
						: '<input type="text" id="ostende_options_field_'.esc_attr($name).'"'
							. ' name="ostende_options_field_'.esc_attr($name).'"'
							. ' value="'.esc_attr(ostende_is_inherit($field['val']) ? '' : $field['val']).'"'
							. ' />')
					. ostende_show_custom_field('ostende_options_field_'.esc_attr($name).'_button',
												array(
													'type'			 => 'mediamanager',
													'multiple'		 => !empty($field['multiple']),
													'data_type'		 => $field['type'],
													'linked_field_id'=> 'ostende_options_field_'.esc_attr($name)
												),
												ostende_is_inherit($field['val']) ? '' : $field['val']);

		// Type 'color'
		} else if ($field['type']=='color') {
			$output .= '<input type="text" id="ostende_options_field_'.esc_attr($name).'"'
							. ' class="ostende_color_selector"'
							. ' name="ostende_options_field_'.esc_attr($name).'"'
							. ' value="'.esc_attr($field['val']).'"'
							. ' />';
		
		// Type 'icon'
		} else if ($field['type']=='icon') {
			$output .= '<input type="text" id="ostende_options_field_'.esc_attr($name).'"'
							. ' name="ostende_options_field_'.esc_attr($name).'"'
							. ' value="'.esc_attr(ostende_is_inherit($field['val']) ? '' : $field['val']).'"'
							. ' />'
						. ostende_show_custom_field('ostende_options_field_'.esc_attr($name).'_button',
													array(
														'type'	 => 'icons',
														'button' => true,
														'icons'	 => true
													),
													ostende_is_inherit($field['val']) ? '' : $field['val']);
		
		// Type 'checklist'
		} else if ($field['type']=='checklist') {
			$output .= '<input type="hidden" id="ostende_options_field_'.esc_attr($name).'"'
							. ' name="ostende_options_field_'.esc_attr($name).'"'
							. ' value="'.esc_attr(ostende_is_inherit($field['val']) ? '' : $field['val']).'"'
							. ' />'
						. ostende_show_custom_field('ostende_options_field_'.esc_attr($name).'_list',
													$field,
													ostende_is_inherit($field['val']) ? '' : $field['val']);
		
		// Type 'scheme_editor'
		} else if ($field['type']=='scheme_editor') {
			$output .= '<input type="hidden" id="ostende_options_field_'.esc_attr($name).'"'
							. ' name="ostende_options_field_'.esc_attr($name).'"'
							. ' value="'.esc_attr(ostende_is_inherit($field['val']) ? '' : $field['val']).'"'
							. ' />'
						. ostende_show_custom_field('ostende_options_field_'.esc_attr($name).'_scheme',
													$field,
													ostende_unserialize($field['val']));
		
		// Type 'slider' || 'range'
		} else if (in_array($field['type'], array('slider', 'range'))) {
			$field['show_value'] = !isset($field['show_value']) || $field['show_value'];
			$output .= '<input type="'.(!$field['show_value'] ? 'hidden' : 'text').'" id="ostende_options_field_'.esc_attr($name).'"'
							. ' name="ostende_options_field_'.esc_attr($name).'"'
							. ' value="'.esc_attr(ostende_is_inherit($field['val']) ? '' : $field['val']).'"'
							. ($field['show_value'] ? ' class="ostende_range_slider_value"' : '')
							. ' />'
						. ostende_show_custom_field('ostende_options_field_'.esc_attr($name).'_slider',
													$field,
													ostende_is_inherit($field['val']) ? '' : $field['val']);
			
		}
		
		$output .= ($inherit_allow
						? '<div class="ostende_options_inherit_cover'.(!$inherit_state ? ' ostende_hidden' : '').'">'
							. '<span class="ostende_options_inherit_label">' . esc_html__('Inherit', 'ostende') . '</span>'
							. '<input type="hidden" name="ostende_options_inherit_'.esc_attr($name).'"'
									. ' value="'.esc_attr($inherit_state ? 'inherit' : '').'"'
									. ' />'
							. '</div>'
						: '')
					. ($field_data_present ? '</div>' : '')
					. (!empty($field['override']['desc']) || !empty($field['desc'])
						? '<div class="ostende_options_item_description">'
							. (!empty($field['override']['desc']) 	// param 'desc' already processed with wp_kses()!
									? $field['override']['desc'] 
									: $field['desc'])
							. '</div>'
						: '')
				. ($field_data_present ? '</div>' : '')
			. '</div>';
		}
		return $output;
	}
}


// Show theme specific fields
function ostende_show_custom_field($id, $field, $value) {
	$output = '';
	switch ($field['type']) {
		
		case 'mediamanager':
			wp_enqueue_media( );
			$title = empty($field['data_type']) || $field['data_type']=='image'
							? esc_html__( 'Choose Image', 'ostende')
							: esc_html__( 'Choose Media', 'ostende');
			$output .= '<input type="button"'
							. ' id="'.esc_attr($id).'"'
							. ' class="button mediamanager ostende_media_selector"'
							. '	data-param="' . esc_attr($id) . '"'
							. '	data-choose="'.esc_attr(!empty($field['multiple']) ? esc_html__( 'Choose Images', 'ostende') : $title).'"'
							. ' data-update="'.esc_attr(!empty($field['multiple']) ? esc_html__( 'Add to Gallery', 'ostende') : $title).'"'
							. '	data-multiple="'.esc_attr(!empty($field['multiple']) ? '1' : '0').'"'
							. '	data-type="'.esc_attr(!empty($field['data_type']) ? $field['data_type'] : 'image').'"'
							. '	data-linked-field="'.esc_attr($field['linked_field_id']).'"'
							. ' value="'
								. (!empty($field['multiple'])
										? (empty($field['data_type']) || $field['data_type']=='image'
											? esc_html__( 'Add Images', 'ostende')
											: esc_html__( 'Add Files', 'ostende')
											)
										: esc_html($title)
									)
								. '"'
							. '>';
			$output .= '<span class="ostende_options_field_preview">';
			$images = explode('|', $value);
			if (is_array($images)) {
				foreach ($images as $img)
					$output .= $img && !ostende_is_inherit($img)
							? '<span>'
									. (in_array(ostende_get_file_ext($img), array('gif', 'jpg', 'jpeg', 'png'))
											? '<img src="' . esc_url($img) . '" alt="option">'
											: '<a href="' . esc_attr($img) . '">' . esc_html(basename($img)) . '</a>'
										)
								. '</span>' 
							: '';
			}
			$output .= '</span>';
			break;

		case 'icons':
			$icons_type = !empty($field['style']) 
							? $field['style'] 
							: ostende_get_theme_setting('icons_type');
			if (empty($field['return']))
				$field['return'] = 'full';
			$ostende_icons = $icons_type=='images'
								? ostende_get_list_images()
								: ostende_array_from_list(ostende_get_list_icons());
			if (is_array($ostende_icons)) {
				if (!empty($field['button']))
					$output .= '<span id="'.esc_attr($id).'"'
									. ' class="ostende_list_icons_selector'
											. ($icons_type=='icons' && !empty($value) ? ' '.esc_attr($value) : '')
											.'"'
									. ' title="'.esc_attr__('Select icon', 'ostende').'"'
									. ' data-style="'.($icons_type=='images' ? 'images' : 'icons').'"'
									. ($icons_type=='images' && !empty($value) 
										? ' style="background-image: url('.esc_url($field['return']=='slug' 
																							? $ostende_icons[$value] 
																							: $value).');"' 
											: '')
								. '></span>';
				if (!empty($field['icons'])) {
					$output .= '<div class="ostende_list_icons">'
								. '<input type="text" class="ostende_list_icons_search" placeholder="'.esc_attr__('Search icon ...', 'ostende').'">';
					foreach($ostende_icons as $slug=>$icon) {
						$output .= '<span class="'.esc_attr($icons_type=='icons' ? $icon : $slug)
								. (($field['return']=='full' ? $icon : $slug) == $value ? ' ostende_list_active' : '')
								. '"'
								. ' title="'.esc_attr($slug).'"'
								. ' data-icon="'.esc_attr($field['return']=='full' ? $icon : $slug).'"'
								. ($icons_type=='images' ? ' style="background-image: url('.esc_url($icon).');"' : '')
								. '></span>';
					}
					$output .= '</div>';
				}
			}
			break;

		case 'checklist':
			if (!empty($field['sortable']))
				wp_enqueue_script('jquery-ui-sortable', false, array('jquery', 'jquery-ui-core'), null, true);
			$output .= '<div class="ostende_checklist ostende_checklist_'.esc_attr($field['dir'])
						. (!empty($field['sortable']) ? ' ostende_sortable' : '') 
						. '">';
			if (!is_array($value)) {
				if (!empty($value) && !ostende_is_inherit($value)) parse_str(str_replace('|', '&', $value), $value);
				else $value = array();
			}
			// Sort options by values order
			if (!empty($field['sortable']) && is_array($value)) {
				$field['options'] = ostende_array_merge($value, $field['options']);
			}
			foreach ($field['options'] as $k=>$v) {
				$output .= '<label class="ostende_checklist_item_label' 
								. (!empty($field['sortable']) ? ' ostende_sortable_item' : '') 
								. '">'
							. '<input type="checkbox" value="1" data-name="'.$k.'"'
								.( isset($value[$k]) && (int) $value[$k] == 1 ? ' checked="checked"' : '')
								.' />'
							. (substr($v, 0, 4)=='http' ? '<img src="'.esc_url($v).'">' : esc_html($v))
						. '</label>';
			}
			$output .= '</div>';
			break;

		case 'slider':
		case 'range':
			wp_enqueue_script('jquery-ui-slider', false, array('jquery', 'jquery-ui-core'), null, true);
			$is_range  = $field['type'] == 'range';
			$field_min = !empty($field['min']) ? $field['min'] : 0;
			$field_max = !empty($field['max']) ? $field['max'] : 100;
			$field_step= !empty($field['step']) ? $field['step'] : 1;
			$field_val = !empty($value) 
							? ($value . ($is_range && strpos($value, ',')===false ? ','.$field_max : ''))
							: ($is_range ? $field_min.','.$field_max : $field_min);
			$output .= '<div id="'.esc_attr($id).'"'
							. ' class="ostende_range_slider"'
							. ' data-range="' . esc_attr($is_range ? 'true' : 'min') . '"'
							. ' data-min="' . esc_attr($field_min) . '"'
							. ' data-max="' . esc_attr($field_max) . '"'
							. ' data-step="' . esc_attr($field_step) . '"'
							. '>'
							. '<span class="ostende_range_slider_label ostende_range_slider_label_min">'
								. esc_html($field_min)
							. '</span>'
							. '<span class="ostende_range_slider_label ostende_range_slider_label_max">'
								. esc_html($field_max)
							. '</span>';
			$values = explode(',', $field_val);
			for ($i=0; $i < count($values); $i++) {
				$output .= '<span class="ostende_range_slider_label ostende_range_slider_label_cur">'
								. esc_html($values[$i])
							. '</span>';
			}
			$output .= '</div>';
			break;

		case 'text_editor':
			if (function_exists('wp_enqueue_editor')) wp_enqueue_editor();
			ob_start();
			wp_editor( $value, $id, array(
				'default_editor' => 'tmce',
				'wpautop' => isset($field['wpautop']) ? $field['wpautop'] : false,
				'teeny' => isset($field['teeny']) ? $field['teeny'] : false,
				'textarea_rows' => isset($field['rows']) && $field['rows'] > 1 ? $field['rows'] : 10,
				'editor_height' => 16*(isset($field['rows']) && $field['rows'] > 1 ? (int) $field['rows'] : 10),
				'tinymce' => array(
					'resize'             => false,
					'wp_autoresize_on'   => false,
					'add_unload_trigger' => false
				)
			));
			$editor_html = ob_get_contents();
			ob_end_clean();
			$output .= '<div class="ostende_text_editor">' . $editor_html . '</div>';
			break;

			
		case 'scheme_editor':
			if (!is_array($value)) break;
			if (empty($field['colorpicker'])) $field['colorpicker'] = 'internal';
			$output .= '<div class="ostende_scheme_editor">';
			// Select scheme
			$output .= '<select class="ostende_scheme_editor_selector">';
			foreach ($value as $scheme=>$v)
				$output .= '<option value="' . esc_attr($scheme) . '">' . esc_html($v['title']) . '</option>';
			$output .= '</select>';
			// Select type
			$output .= '<div class="ostende_scheme_editor_type">'
							. '<div class="ostende_scheme_editor_row">'
								. '<span class="ostende_scheme_editor_row_cell">'
									. esc_html__('Editor type', 'ostende')
								. '</span>'
								. '<span class="ostende_scheme_editor_row_cell ostende_scheme_editor_row_cell_span">'
									.'<label>'
										. '<input name="ostende_scheme_editor_type" type="radio" value="simple" checked="checked"> '
										. esc_html__('Simple', 'ostende')
									. '</label>'
									. '<label>'
										. '<input name="ostende_scheme_editor_type" type="radio" value="advanced"> '
										. esc_html__('Advanced', 'ostende')
									. '</label>'
								. '</span>'
							. '</div>'
						. '</div>';
					// Colors
			$groups  = ostende_storage_get( 'scheme_color_groups' );
			$colors  = ostende_storage_get( 'scheme_color_names' );
			$output .= '<div class="ostende_scheme_editor_colors">';
			foreach ( $value as $scheme => $v ) {
				$output .= '<div class="ostende_scheme_editor_header">'
								. '<span class="ostende_scheme_editor_header_cell ostende_scheme_editor_row_cell_caption"></span>';
				foreach ( $groups as $group_name => $group_data ) {
					$output .= '<span class="ostende_scheme_editor_header_cell ostende_scheme_editor_row_cell_color" title="' . esc_attr( $group_data['description'] ) . '">'
								. esc_html( $group_data['title'] )
								. '</span>';
				}
				$output .= '</div>';
				foreach ( $colors as $color_name => $color_data ) {
					$output .= '<div class="ostende_scheme_editor_row">'
								. '<span class="ostende_scheme_editor_row_cell ostende_scheme_editor_row_cell_caption" title="' . esc_attr( $color_data['description'] ) . '">'
								. esc_html( $color_data['title'] )
								. '</span>';
					foreach ( $groups as $group_name => $group_data ) {
						$slug    = 'main' == $group_name
									? $color_name
									: str_replace( 'text_', '', "{$group_name}_{$color_name}" );
						$output .= '<span class="ostende_scheme_editor_row_cell ostende_scheme_editor_row_cell_color">'
									. ( isset( $v['colors'][ $slug ] )
										? "<input type=\"text\" name=\"{$slug}\" class=\""
											. ( 'tiny' == $field['colorpicker']
												? 'tinyColorPicker'
												: ( 'spectrum' == $field['colorpicker']
													? 'spectrumColorPicker'
													: 'iColorPicker'
												)
												) 
											. '" value="' . esc_attr( $v['colors'][ $slug ] ) . '">'
										: ''
										)
									. '</span>';
					}
					$output .= '</div>';
				}
				break;
			}
			$output .= '</div>'
					. '</div>';
			break;
	}
	return apply_filters( 'ostende_filter_show_custom_field', $output, $id, $field, $value );
}



// Save options
if (!function_exists('ostende_options_save')) {
	add_action('after_setup_theme', 'ostende_options_save', 4);
	function ostende_options_save() {

		if (!isset($_REQUEST['page']) || $_REQUEST['page']!='theme_options' || ostende_get_value_gp('ostende_nonce')=='') return;

		// verify nonce
		if ( !wp_verify_nonce( ostende_get_value_gp('ostende_nonce'), admin_url() ) ) {
			ostende_add_admin_message(esc_html__('Bad security code! Options are not saved!', 'ostende'), 'error', true);
			return;
		}

		// Check permissions
		if (!current_user_can('manage_options')) {
			ostende_add_admin_message(esc_html__('Manage options is denied for the current user! Options are not saved!', 'ostende'), 'error', true);
			return;
		}

		// Save options
		$options = ostende_storage_get('options');
		$values = get_theme_mods();
		$external_storages = array();
		foreach ($options as $k=>$v) {
			// Skip non-data options - sections, info, etc.
			if (!isset($v['std'])) continue;
			// Get option value from POST
			$value = sanitize_text_field(isset($_POST['ostende_options_field_' . $k]))
							? ostende_get_value_gp('ostende_options_field_' . $k)
							: ($v['type']=='checkbox' ? 0 : '');
			// Individual options processing
			if ($k == 'custom_logo' && !empty($value) && (int) $value == 0) {
				$value = attachment_url_to_postid(ostende_clear_thumb_size($value));
				if (empty($value)) $value = get_theme_mod($k);
			}
			// Save to the result array
			if (!empty($v['type']) && $v['type']!='hidden' && (empty($v['hidden']) || !$v['hidden']) && $value != ostende_get_theme_option_std($k, $v['std'])) {
				$values[$k] = $value;
			} else if (isset($values[$k])) {
				unset($values[$k]);
			}
			// External plugin's options
			if (!empty($v['options_storage'])) {
				if (!isset($external_storages[$v['options_storage']]))
					$external_storages[$v['options_storage']] = array();
				$external_storages[$v['options_storage']][$k] = $value;
			}
		}

		// Update options in the external storages
		foreach ($external_storages as $storage_name => $storage_values) {
			$storage = get_option($storage_name, false);
			if (is_array($storage)) {
				foreach ($storage_values as $k=>$v)
					$storage[$k] = $v;
				update_option($storage_name, apply_filters('ostende_filter_options_save', $storage, $storage_name));
			}
		}

		// Update Theme Mods (internal Theme Options)
		$stylesheet_slug = get_option('stylesheet');
		update_option("theme_mods_{$stylesheet_slug}", apply_filters('ostende_filter_options_save', $values, 'theme_mods'));

		do_action('ostende_action_just_save_options');

		// Store new schemes colors
		if (!empty($values['scheme_storage'])) {
			$schemes = ostende_unserialize($values['scheme_storage']);
			if (is_array($schemes) && count($schemes) > 0) 
				ostende_storage_set('schemes', $schemes);
		}
		
		// Store new fonts parameters
		$fonts = ostende_get_theme_fonts();
		foreach ($fonts as $tag=>$v) {
			foreach ($v as $css_prop=>$css_value) {
				if (in_array($css_prop, array('title', 'description'))) continue;
				if (isset($values["{$tag}_{$css_prop}"])) $fonts[$tag][$css_prop] = $values["{$tag}_{$css_prop}"];
			}
		}
		ostende_storage_set('theme_fonts', $fonts);

		// Update ThemeOptions save timestamp
		$stylesheet_time = time();
		update_option("ostende_options_timestamp_{$stylesheet_slug}", $stylesheet_time);

		// Sinchronize theme options between child and parent themes
		if (ostende_get_theme_setting('duplicate_options') == 'both') {
			$theme_slug = get_option('template');
			if ($theme_slug != $stylesheet_slug) {
				ostende_customizer_duplicate_theme_options($stylesheet_slug, $theme_slug, $stylesheet_time);
			}
		}

		// Apply action - moved to the delayed state (see below) to load all enabled modules and apply changes after
		// Attention! Don't remove comment the line below!
		// Not need here: do_action('ostende_action_save_options');
		update_option('ostende_action', 'ostende_action_save_options');

        // Return result
        ostende_add_admin_message(esc_html__('Options are saved', 'ostende'));
        wp_redirect(get_admin_url(null, 'themes.php?page=theme_options'));
        exit();

	}
}


//-------------------------------------------------------
//-- Delayed action from previous session
//-- (after save options)
//-- to save new CSS, etc.
//-------------------------------------------------------
if ( !function_exists('ostende_do_delayed_action') ) {
	add_action( 'after_setup_theme', 'ostende_do_delayed_action' );
	function ostende_do_delayed_action() {
		if (($action = get_option('ostende_action')) != '') {
		    do_action($action);
			update_option('ostende_action', '');
		}
	}
}


// Refresh data in the linked field
// according the main field value
if (!function_exists('ostende_refresh_linked_data')) {
	function ostende_refresh_linked_data($value, $linked_name) {
		if ($linked_name == 'parent_cat') {
			$tax = ostende_get_post_type_taxonomy($value);
			$terms = !empty($tax) ? ostende_get_list_terms(false, $tax) : array();
			$terms = ostende_array_merge(array(0 => esc_html__('- Select category -', 'ostende')), $terms);
			ostende_storage_set_array2('options', $linked_name, 'options', $terms);
		}
	}
}


// AJAX: Refresh data in the linked fields
if (!function_exists('ostende_callback_get_linked_data')) {
	add_action('wp_ajax_ostende_get_linked_data', 		'ostende_callback_get_linked_data');
	add_action('wp_ajax_nopriv_ostende_get_linked_data','ostende_callback_get_linked_data');
	function ostende_callback_get_linked_data() {
		if ( !wp_verify_nonce( ostende_get_value_gp('nonce'), admin_url('admin-ajax.php') ) )
			die();
		$chg_name = wp_kses_data(wp_unslash($_REQUEST['chg_name']));
		$chg_value = wp_kses_data(wp_unslash($_REQUEST['chg_value']));
		$response = array('error' => '');
		if ($chg_name == 'post_type') {
			$tax = ostende_get_post_type_taxonomy($chg_value);
			$terms = !empty($tax) ? ostende_get_list_terms(false, $tax) : array();
			$response['list'] = ostende_array_merge(array(0 => esc_html__('- Select category -', 'ostende')), $terms);
		}
		echo json_encode($response);
		die();
	}
}
?>