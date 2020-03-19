<?php
/**
 * The template to display the site logo in the footer
 *
 
 * @subpackage OSTENDE
 * @since OSTENDE 1.0.10
 */

// Logo
if (ostende_is_on(ostende_get_theme_option('logo_in_footer'))) {
	$ostende_logo_image = '';
	if (ostende_is_on(ostende_get_theme_option('logo_retina_enabled')) && ostende_get_retina_multiplier() > 1)
		$ostende_logo_image = ostende_get_theme_option( 'logo_footer_retina' );
	if (empty($ostende_logo_image)) 
		$ostende_logo_image = ostende_get_theme_option( 'logo_footer' );
	$ostende_logo_text   = get_bloginfo( 'name' );
	if (!empty($ostende_logo_image) || !empty($ostende_logo_text)) {
		?>
		<div class="footer_logo_wrap">
			<div class="footer_logo_inner">
				<?php
				if (!empty($ostende_logo_image)) {
					$ostende_attr = ostende_getimagesize($ostende_logo_image);
					echo '<a href="'.esc_url(home_url('/')).'"><img src="'.esc_url($ostende_logo_image).'" class="logo_footer_image" alt="'.(!empty($ostende_attr[3]) ? ' ' . wp_kses_data($ostende_attr[3]) : '').'"></a>' ;
				} else if (!empty($ostende_logo_text)) {
					echo '<h1 class="logo_footer_text"><a href="'.esc_url(home_url('/')).'">' . esc_html($ostende_logo_text) . '</a></h1>';
				}
				?>
			</div>
		</div>
		<?php
	}
}
?>