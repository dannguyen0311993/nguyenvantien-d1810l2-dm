<?php
/**
 * The template to display the logo or the site name and the slogan in the Header
 *
 
 * @subpackage OSTENDE
 * @since OSTENDE 1.0
 */

$ostende_args = get_query_var('ostende_logo_args');

// Site logo
$ostende_logo_type   = isset($ostende_args['type']) ? $ostende_args['type'] : '';
$ostende_logo_image  = ostende_get_logo_image($ostende_logo_type);
$ostende_logo_text   = ostende_is_on(ostende_get_theme_option('logo_text')) ? get_bloginfo( 'name' ) : '';
$ostende_logo_slogan = get_bloginfo( 'description', 'display' );
if (!empty($ostende_logo_image) || !empty($ostende_logo_text)) {
	?><a class="sc_layouts_logo" tabindex="0"  href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php
		if (!empty($ostende_logo_image)) {
			if (empty($ostende_logo_type) && function_exists('the_custom_logo') && (int) $ostende_logo_image > 0) {
				the_custom_logo();
			} else {
				$ostende_attr = ostende_getimagesize($ostende_logo_image);
				echo '<img src="'.esc_url($ostende_logo_image).'" alt="'.esc_attr($ostende_logo_text).'"'.(!empty($ostende_attr[3]) ? ' '.wp_kses_data($ostende_attr[3]) : '').'>';
			}
		} else {
			ostende_show_layout(ostende_prepare_macros($ostende_logo_text), '<span class="logo_text">', '</span>');
			ostende_show_layout(ostende_prepare_macros($ostende_logo_slogan), '<span class="logo_slogan">', '</span>');
		}
	?></a><?php
}
?>