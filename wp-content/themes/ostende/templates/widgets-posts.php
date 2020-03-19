<?php
/**
 * The template to display posts in widgets and/or in the search results
 *
 
 * @subpackage OSTENDE
 * @since OSTENDE 1.0
 */

$ostende_post_id    = get_the_ID();
$ostende_post_date  = ostende_get_date();
$ostende_post_title = get_the_title();
$ostende_post_link  = esc_url(get_permalink());
$ostende_post_author_id   = get_the_author_meta('ID');
$ostende_post_author_name = get_the_author_meta('display_name');
$ostende_post_author_url  = get_author_posts_url($ostende_post_author_id, '');

$ostende_args = get_query_var('ostende_args_widgets_posts');
$ostende_show_date = isset($ostende_args['show_date']) ? (int) $ostende_args['show_date'] : 1;
$ostende_show_image = isset($ostende_args['show_image']) ? (int) $ostende_args['show_image'] : 1;
$ostende_show_author = isset($ostende_args['show_author']) ? (int) $ostende_args['show_author'] : 1;
$ostende_show_counters = isset($ostende_args['show_counters']) ? (int) $ostende_args['show_counters'] : 1;
$ostende_show_categories = isset($ostende_args['show_categories']) ? (int) $ostende_args['show_categories'] : 1;

$ostende_output = ostende_storage_get('ostende_output_widgets_posts');

$ostende_post_counters_output = '';
if ( $ostende_show_counters ) {
	$ostende_post_counters_output = '<span class="post_info_item post_info_counters">'
								. ostende_get_post_counters('comments')
							. '</span>';
}


$ostende_output .= '<article class="post_item with_thumb">';

if ($ostende_show_image) {
	$ostende_post_thumb = get_the_post_thumbnail($ostende_post_id, ostende_get_thumb_size('tiny'), array(
		'alt' => get_the_title()
	));
	if ($ostende_post_thumb) $ostende_output .= '<div class="post_thumb">' . ($ostende_post_link ? '<a href="' . esc_url($ostende_post_link) . '">' : '') . ($ostende_post_thumb) . ($ostende_post_link ? '</a>' : '') . '</div>';
}

$ostende_output .= '<div class="post_content">'
			. ($ostende_show_categories 
					? '<div class="post_categories">'
						. ostende_get_post_categories()
						. $ostende_post_counters_output
						. '</div>' 
					: '')
			. '<h6 class="post_title">' . ($ostende_post_link ? '<a href="' . esc_url($ostende_post_link) . '">' : '') . ($ostende_post_title) . ($ostende_post_link ? '</a>' : '') . '</h6>'
			. apply_filters('ostende_filter_get_post_info', 
								'<div class="post_info">'
									. ($ostende_show_date 
										? '<span class="post_info_item post_info_posted">'
											. ($ostende_post_link ? '<a href="' . esc_url($ostende_post_link) . '" class="post_info_date">' : '') 
											. esc_html($ostende_post_date) 
											. ($ostende_post_link ? '</a>' : '')
											. '</span>'
										: '')
									. ($ostende_show_author 
										? '<span class="post_info_item post_info_posted_by">' 
											. esc_html__('by', 'ostende') . ' ' 
											. ($ostende_post_link ? '<a href="' . esc_url($ostende_post_author_url) . '" class="post_info_author">' : '') 
											. esc_html($ostende_post_author_name) 
											. ($ostende_post_link ? '</a>' : '') 
											. '</span>'
										: '')
									. (!$ostende_show_categories && $ostende_post_counters_output
										? $ostende_post_counters_output
										: '')
								. '</div>')
		. '</div>'
	. '</article>';
ostende_storage_set('ostende_output_widgets_posts', $ostende_output);
?>