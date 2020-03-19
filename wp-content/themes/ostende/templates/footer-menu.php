<?php
/**
 * The template to display menu in the footer
 *
 
 * @subpackage OSTENDE
 * @since OSTENDE 1.0.10
 */

// Footer menu
$ostende_menu_footer = ostende_get_nav_menu(array(
											'location' => 'menu_footer',
											'class' => 'sc_layouts_menu sc_layouts_menu_default'
											));
if (!empty($ostende_menu_footer)) {
	?>
	<div class="footer_menu_wrap">
		<div class="footer_menu_inner">
			<?php ostende_show_layout($ostende_menu_footer); ?>
		</div>
	</div>
	<?php
}
?>