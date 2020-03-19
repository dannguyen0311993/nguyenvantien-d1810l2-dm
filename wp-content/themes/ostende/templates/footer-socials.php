<?php
/**
 * The template to display the socials in the footer
 *
 
 * @subpackage OSTENDE
 * @since OSTENDE 1.0.10
 */


// Socials
if ( ostende_is_on(ostende_get_theme_option('socials_in_footer')) && ($ostende_output = ostende_get_socials_links()) != '') {
	?>
	<div class="footer_socials_wrap socials_wrap">
		<div class="footer_socials_inner">
			<?php ostende_show_layout($ostende_output); ?>
		</div>
	</div>
	<?php
}
?>