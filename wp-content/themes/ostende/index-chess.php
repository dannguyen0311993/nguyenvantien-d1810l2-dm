<?php
/**
 * The template for homepage posts with "Chess" style
 *
 
 * @subpackage OSTENDE
 * @since OSTENDE 1.0
 */

ostende_storage_set('blog_archive', true);

get_header(); 

if (have_posts()) {

	ostende_show_layout(get_query_var('blog_archive_start'));

	$ostende_stickies = is_home() ? get_option( 'sticky_posts' ) : false;
	$ostende_sticky_out = ostende_get_theme_option('sticky_style')=='columns' 
							&& is_array($ostende_stickies) && count($ostende_stickies) > 0 && get_query_var( 'paged' ) < 1;
	if ($ostende_sticky_out) {
		?><div class="sticky_wrap columns_wrap"><?php	
	}
	if (!$ostende_sticky_out) {
		?><div class="chess_wrap posts_container"><?php
	}
	while ( have_posts() ) { the_post(); 
		if ($ostende_sticky_out && !is_sticky()) {
			$ostende_sticky_out = false;
			?></div><div class="chess_wrap posts_container"><?php
		}
		get_template_part( 'content', $ostende_sticky_out && is_sticky() ? 'sticky' :'chess' );
	}
	
	?></div><?php

	ostende_show_pagination();

	ostende_show_layout(get_query_var('blog_archive_end'));

} else {

	if ( is_search() )
		get_template_part( 'content', 'none-search' );
	else
		get_template_part( 'content', 'none-archive' );

}

get_footer();
?>