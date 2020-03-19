<?php
/**
 * The template 'Style 1' to displaying related posts
 *
 
 * @subpackage OSTENDE
 * @since OSTENDE 1.0
 */


$ostende_post_format = get_post_format();
$ostende_post_format = empty($ostende_post_format) ? 'standard' : str_replace('post-format-', '', $ostende_post_format);

$ostende_show_info = !in_array($ostende_post_format, array('audio'));

?><div id="post-<?php the_ID(); ?>" 
	<?php post_class( 'related_item related_item_style_1 post_format_'.esc_attr($ostende_post_format) ); ?>><?php
	ostende_show_post_featured(array(
		'thumb_size' => apply_filters('ostende_filter_related_thumb_size', ostende_get_thumb_size( (int) ostende_get_theme_option('related_posts') == 1 ? 'huge' : 'big' )),
		'show_no_image' => ostende_get_theme_setting('allow_no_image'),
		'singular' => false,
		'post_info' => '<div class="post_header entry-header">'
							. ($ostende_show_info ?  '<div class="post_categories">'.wp_kses_post(ostende_get_post_categories(' ')).'</div>'  : '')
							. (!empty(get_the_title()) ? '<h6 class="post_title entry-title"><a href="'.esc_url(get_permalink()).'">'.wp_kses_post(get_the_title()).'</a></h6>' : '<a href="'.esc_url(get_permalink()).'" class="more-link">'.esc_html__('Read more', 'ostende').'</a>')
						. '</div>'
		)
	);
?></div>