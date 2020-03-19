<?php
/**
 * The Portfolio template to display the content
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

?><article id="post-<?php the_ID(); ?>" 
	<?php post_class( 'post_item post_layout_portfolio post_layout_portfolio_'.esc_attr($ostende_columns).' post_format_'.esc_attr($ostende_post_format).(is_sticky() && !is_paged() ? ' sticky' : '') ); ?>
	<?php echo (!ostende_is_off($ostende_animation) ? ' data-animation="'.esc_attr(ostende_get_animation_classes($ostende_animation)).'"' : ''); ?>>
	<?php

	// Sticky label
	if ( is_sticky() && !is_paged() ) {
		?><span class="post_label label_sticky"></span><?php
	}

	$ostende_image_hover = ostende_get_theme_option('image_hover');
	// Featured image
	ostende_show_post_featured(array(
		'thumb_size' => ostende_get_thumb_size(strpos(ostende_get_theme_option('body_style'), 'full')!==false || $ostende_columns < 3 
								? 'masonry-big' 
								: 'masonry'),
		'show_no_image' => true,
		'class' => $ostende_image_hover == 'dots' || $ostende_image_hover == 'zoom' ? 'hover_with_info' : '',
		'post_info' => $ostende_image_hover == 'dots' || $ostende_image_hover == 'zoom' ? '<div class="post_info"><a href="'.esc_url( get_permalink() ).'" rel="bookmark">'.esc_html(get_the_title()).'</a></div>' : ''
	));
	?>
</article>