<?php
/**
 * The template to display the featured image in the single post
 *
 
 * @subpackage OSTENDE
 * @since OSTENDE 1.0
 */

if ( get_query_var('ostende_header_image')=='' && is_singular() && has_post_thumbnail() && in_array(get_post_type(), array('post', 'page')) )  {
	$ostende_src = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'full' );
	if (!empty($ostende_src[0])) {
		ostende_sc_layouts_showed('featured', true);
		?><div class="sc_layouts_featured with_image without_content <?php echo esc_attr(ostende_add_inline_css_class('background-image:url('.esc_url($ostende_src[0]).');')); ?>"></div><?php
	}
}
?>