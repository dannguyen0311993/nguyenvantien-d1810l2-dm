<?php
/**
 * The template to display the widgets area in the footer
 *
 
 * @subpackage OSTENDE
 * @since OSTENDE 1.0.10
 */

// Footer sidebar
$ostende_footer_name = ostende_get_theme_option('footer_widgets');
$ostende_footer_present = !ostende_is_off($ostende_footer_name) && is_active_sidebar($ostende_footer_name);
if ($ostende_footer_present) { 
	ostende_storage_set('current_sidebar', 'footer');
	$ostende_footer_wide = ostende_get_theme_option('footer_wide');
	ob_start();
	if ( is_active_sidebar($ostende_footer_name) ) {
		dynamic_sidebar($ostende_footer_name);
	}
	$ostende_out = trim(ob_get_contents());
	ob_end_clean();
	if (!empty($ostende_out)) {
		$ostende_out = preg_replace("/<\\/aside>[\r\n\s]*<aside/", "</aside><aside", $ostende_out);
		$ostende_need_columns = true;
		if ($ostende_need_columns) {
			$ostende_columns = max(0, (int) ostende_get_theme_option('footer_columns'));
			if ($ostende_columns == 0) $ostende_columns = min(4, max(1, substr_count($ostende_out, '<aside ')));
			if ($ostende_columns > 1)
				$ostende_out = preg_replace("/<aside([^>]*)class=\"widget/", "<aside$1class=\"column-1_".esc_attr($ostende_columns).' widget', $ostende_out);
			else
				$ostende_need_columns = false;
		}
		?>
		<div class="footer_widgets_wrap widget_area<?php echo !empty($ostende_footer_wide) ? ' footer_fullwidth' : ''; ?> sc_layouts_row sc_layouts_row_type_normal">
			<div class="footer_widgets_inner widget_area_inner">
				<?php 
				if (!$ostende_footer_wide) { 
					?><div class="content_wrap"><?php
				}
				if ($ostende_need_columns) {
					?><div class="columns_wrap"><?php
				}
				do_action( 'ostende_action_before_sidebar' );
				ostende_show_layout($ostende_out);
				do_action( 'ostende_action_after_sidebar' );
				if ($ostende_need_columns) {
					?></div><!-- /.columns_wrap --><?php
				}
				if (!$ostende_footer_wide) {
					?></div><!-- /.content_wrap --><?php
				}
				?>
			</div><!-- /.footer_widgets_inner -->
		</div><!-- /.footer_widgets_wrap -->
		<?php
	}
}
?>