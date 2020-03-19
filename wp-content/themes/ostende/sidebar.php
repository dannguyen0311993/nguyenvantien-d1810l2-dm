<?php
/**
 * The Sidebar containing the main widget areas.
 *
 
 * @subpackage OSTENDE
 * @since OSTENDE 1.0
 */

if (ostende_sidebar_present()) {
	ob_start();
	$ostende_sidebar_name = ostende_get_theme_option('sidebar_widgets');
	ostende_storage_set('current_sidebar', 'sidebar');
	if ( is_active_sidebar($ostende_sidebar_name) ) {
		dynamic_sidebar($ostende_sidebar_name);
	}
	$ostende_out = trim(ob_get_contents());
	ob_end_clean();
	if (!empty($ostende_out)) {
		$ostende_sidebar_position = ostende_get_theme_option('sidebar_position');
		?>
		<div class="sidebar <?php echo esc_attr($ostende_sidebar_position); ?> widget_area<?php if (!ostende_is_inherit(ostende_get_theme_option('sidebar_scheme'))) echo ' scheme_'.esc_attr(ostende_get_theme_option('sidebar_scheme')); ?>" role="complementary">
			<?php
			// Skip link anchor to fast access to the sidebar from keyboard
			?>
			<a id="sidebar_skip_link_anchor" class="ostende_skip_link_anchor" href="#"></a>
			<div class="sidebar_inner">
				<?php
				do_action( 'ostende_action_before_sidebar' );
				ostende_show_layout(preg_replace("/<\/aside>[\r\n\s]*<aside/", "</aside><aside", $ostende_out));
				do_action( 'ostende_action_after_sidebar' );
				?>
			</div><!-- /.sidebar_inner -->
		</div><!-- /.sidebar -->
		<?php
	}
}
?>