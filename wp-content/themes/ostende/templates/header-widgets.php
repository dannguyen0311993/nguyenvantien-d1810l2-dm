<?php
/**
 * The template to display the widgets area in the header
 *
 
 * @subpackage OSTENDE
 * @since OSTENDE 1.0
 */

// Header sidebar
$ostende_header_name = ostende_get_theme_option('header_widgets');
$ostende_header_present = !ostende_is_off($ostende_header_name) && is_active_sidebar($ostende_header_name);
if ($ostende_header_present) { 
	ostende_storage_set('current_sidebar', 'header');
	$ostende_header_wide = ostende_get_theme_option('header_wide');
	ob_start();
	if ( is_active_sidebar($ostende_header_name) ) {
		dynamic_sidebar($ostende_header_name);
	}
	$ostende_widgets_output = ob_get_contents();
	ob_end_clean();
	if (!empty($ostende_widgets_output)) {
		$ostende_widgets_output = preg_replace("/<\/aside>[\r\n\s]*<aside/", "</aside><aside", $ostende_widgets_output);
		$ostende_need_columns = strpos($ostende_widgets_output, 'columns_wrap')===false;
		if ($ostende_need_columns) {
			$ostende_columns = max(0, (int) ostende_get_theme_option('header_columns'));
			if ($ostende_columns == 0) $ostende_columns = min(6, max(1, substr_count($ostende_widgets_output, '<aside ')));
			if ($ostende_columns > 1)
				$ostende_widgets_output = preg_replace("/<aside([^>]*)class=\"widget/", "<aside$1class=\"column-1_".esc_attr($ostende_columns).' widget', $ostende_widgets_output);
			else
				$ostende_need_columns = false;
		}
		?>
		<div class="header_widgets_wrap widget_area<?php echo !empty($ostende_header_wide) ? ' header_fullwidth' : ' header_boxed'; ?>">
			<div class="header_widgets_inner widget_area_inner">
				<?php 
				if (!$ostende_header_wide) { 
					?><div class="content_wrap"><?php
				}
				if ($ostende_need_columns) {
					?><div class="columns_wrap"><?php
				}
				do_action( 'ostende_action_before_sidebar' );
				ostende_show_layout($ostende_widgets_output);
				do_action( 'ostende_action_after_sidebar' );
				if ($ostende_need_columns) {
					?></div>	<!-- /.columns_wrap --><?php
				}
				if (!$ostende_header_wide) {
					?></div>	<!-- /.content_wrap --><?php
				}
				?>
			</div>	<!-- /.header_widgets_inner -->
		</div>	<!-- /.header_widgets_wrap -->
		<?php
	}
}
?>