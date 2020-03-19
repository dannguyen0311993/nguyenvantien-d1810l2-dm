<?php
/**
 * The template to display the copyright info in the footer
 *
 
 * @subpackage OSTENDE
 * @since OSTENDE 1.0.10
 */

// Copyright area
?> 
<div class="footer_copyright_wrap<?php
				if (!ostende_is_inherit(ostende_get_theme_option('copyright_scheme')))
					echo ' scheme_' . esc_attr(ostende_get_theme_option('copyright_scheme'));
 				?>">
	<div class="footer_copyright_inner">
		<div class="content_wrap">
			<div class="copyright_text"><?php
				$ostende_copyright = ostende_get_theme_option('copyright');
				if (!empty($ostende_copyright)) {
					// Replace {{Y}} or {Y} with the current year
					$ostende_copyright = str_replace(array('{{Y}}', '{Y}'), date('Y'), $ostende_copyright);
					// Replace {{...}} and ((...)) on the <i>...</i> and <b>...</b>
					$ostende_copyright = ostende_prepare_macros($ostende_copyright);
					// Display copyright
					echo wp_kses_data(nl2br($ostende_copyright));
				}
			?></div>
		</div>
	</div>
</div>
