<?php
// Add plugin-specific colors and fonts to the custom CSS
if ( !function_exists( 'ostende_elm_get_css' ) ) {
	add_filter( 'ostende_filter_get_css', 'ostende_elm_get_css', 10, 4 );
	function ostende_elm_get_css($css, $colors, $fonts, $scheme='') {
		if (isset($css['fonts']) && $fonts) {
			$css['fonts'] .= <<<CSS
			
			
			.elementor-drop-cap-letter {
				{$fonts['h5_font-family']}
			}
			

CSS;
		}

		if (isset($css['colors']) && $colors) {
			$css['colors'] .= <<<CSS

/* Shape above and below rows */
.elementor-shape .elementor-shape-fill {
	fill: {$colors['bg_color']};
}

/* Divider */
.elementor-divider-separator {
	border-color: {$colors['bd_color']};
}




.elementor-custom-embed-play i {
    color: {$colors['extra_link']};
}
.elementor-custom-embed-play i:hover {
    background-color: {$colors['extra_link']};
    color: {$colors['inverse_link']};
}


.elementor-progress-text {
    color: {$colors['text_dark']};
}
.elementor-progress-percentage {
    color: {$colors['text']};
}
.elementor-progress-bar {
    background-color: {$colors['text_dark']};
}





CSS;
		}
		
		return $css;
	}
}
?>