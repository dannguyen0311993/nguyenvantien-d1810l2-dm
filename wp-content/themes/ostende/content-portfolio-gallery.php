<?php
/**
 * The Gallery template to display posts
 *
 * Used for index/archive/search.
 *
 
 * @subpackage OSTENDE
 * @since OSTENDE 1.0
 */

$ostende_blog_style = explode('_', ostende_get_theme_option('blog_style'));
$ostende_columns = empty($ostende_blog_style[1]) ? 2 : max(2, $ostende_blog_style[1]);
$ostende_post_format = get_post_format();
$ostende_post_format = empty($ostende_post_format) ? 'standard' : str_replace('post-format-', '', $ostende_post_format);
$ostende_animation = ostende_get_theme_option('blog_animation');
$ostende_image = wp_get_attachment_image_src( get_post_thumbnail_id(get_the_ID()), 'full' );

?><article id="post-<?php the_ID(); ?>" 
	<?php post_class( 'post_item post_layout_portfolio post_layout_gallery post_layout_gallery_'.esc_attr($ostende_columns).' post_format_'.esc_attr($ostende_post_format) ); ?>
	<?php echo (!ostende_is_off($ostende_animation) ? ' data-animation="'.esc_attr(ostende_get_animation_classes($ostende_animation)).'"' : ''); ?>
	data-size="<?php if (!empty($ostende_image[1]) && !empty($ostende_image[2])) echo intval($ostende_image[1]) .'x' . intval($ostende_image[2]); ?>"
	data-src="<?php if (!empty($ostende_image[0])) echo esc_url($ostende_image[0]); ?>"
	>

	<?php

	// Sticky label
	if ( is_sticky() && !is_paged() ) {
		?><span class="post_label label_sticky"></span><?php
	}

	// Featured image
	$ostende_image_hover = 'icon';
	if (in_array($ostende_image_hover, array('icons', 'zoom'))) $ostende_image_hover = 'dots';
	$ostende_components = ostende_array_get_keys_by_value(ostende_get_theme_option('meta_parts'));
	$ostende_counters = ostende_array_get_keys_by_value(ostende_get_theme_option('counters'));
	ostende_show_post_featured(array(
		'hover' => $ostende_image_hover,
		'thumb_size' => ostende_get_thumb_size( strpos(ostende_get_theme_option('body_style'), 'full')!==false || $ostende_columns < 3 ? 'masonry-big' : 'masonry' ),
		'thumb_only' => true,
		'show_no_image' => true,
		'post_info' => '<div class="post_details">'
							. '<h2 class="post_title"><a href="'.esc_url(get_permalink()).'">'. esc_html(get_the_title()) . '</a></h2>'
							. '<div class="post_description">'
								. (!empty($ostende_components)
										? ostende_show_post_meta(apply_filters('ostende_filter_post_meta_args', array(
											'components' => $ostende_components,
											'counters' => $ostende_counters,
											'echo' => false
											), $ostende_blog_style[0], $ostende_columns))
										: '')
								. '<div class="post_description_content">'
									. apply_filters('the_excerpt', get_the_excerpt())
								. '</div>'
								. '<a href="'.esc_url(get_permalink()).'" class="theme_button post_readmore"><span class="post_readmore_label">' . esc_html__('Learn more', 'ostende') . '</span></a>'
							. '</div>'
						. '</div>'
	));
	?>
</article>