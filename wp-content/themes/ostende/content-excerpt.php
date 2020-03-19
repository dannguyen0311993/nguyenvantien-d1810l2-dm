<?php
/**
 * The default template to display the content
 *
 * Used for index/archive/search.
 *
 
 * @subpackage OSTENDE
 * @since OSTENDE 1.0
 */

$ostende_post_format = get_post_format();
$ostende_post_format = empty($ostende_post_format) ? 'standard' : str_replace('post-format-', '', $ostende_post_format);
$ostende_animation = ostende_get_theme_option('blog_animation');

?><article id="post-<?php the_ID(); ?>" 
	<?php post_class( 'post_item post_layout_excerpt post_format_'.esc_attr($ostende_post_format) ); ?>
	<?php echo (!ostende_is_off($ostende_animation) ? ' data-animation="'.esc_attr(ostende_get_animation_classes($ostende_animation)).'"' : ''); ?>
	><?php

	// Sticky label
	if ( is_sticky() && !is_paged() ) {
		?><span class="post_label label_sticky"></span><?php
	}

	// Featured image
	ostende_show_post_featured(array( 'extra_slider' => true, 'thumb_size' => ostende_get_thumb_size( strpos(ostende_get_theme_option('body_style'), 'full')!==false ? 'full' : 'big' ) ));

	// Title and post meta
	if (get_the_title() != '') {
		?>
		<div class="post_header entry-header">
			<?php
			do_action('ostende_action_before_post_title'); 

			// Post title
			the_title( sprintf( '<h2 class="post_title entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' );

			do_action('ostende_action_before_post_meta'); 

			// Post meta
			$ostende_components = ostende_array_get_keys_by_value(ostende_get_theme_option('meta_parts'));
			$ostende_counters = ostende_array_get_keys_by_value(ostende_get_theme_option('counters'));

			if (!empty($ostende_components))
				ostende_show_post_meta(apply_filters('ostende_filter_post_meta_args', array(
					'components' => $ostende_components,
					'counters' => $ostende_counters,
					'seo' => false
					), 'excerpt', 1)
				);
			?>
		</div><!-- .post_header --><?php
	}
	
	// Post content
	?><div class="post_content entry-content"><?php
		if (ostende_get_theme_option('blog_content') == 'fullpost') {
			// Post content area
			?><div class="post_content_inner"><?php
				the_content( '' );
			?></div><?php
			// Inner pages
			wp_link_pages( array(
				'before'      => '<div class="page_links"><span class="page_links_title">' . esc_html__( 'Pages:', 'ostende' ) . '</span>',
				'after'       => '</div>',
				'link_before' => '<span>',
				'link_after'  => '</span>',
				'pagelink'    => '<span class="screen-reader-text">' . esc_html__( 'Page', 'ostende' ) . ' </span>%',
				'separator'   => '<span class="screen-reader-text">, </span>',
			) );

		} else {

			$ostende_show_learn_more = !in_array($ostende_post_format, array('link', 'aside', 'status', 'quote'));

			// Post content area
			?><div class="post_content_inner"><?php
				if (has_excerpt()) {
					the_excerpt();
				} else if (strpos(get_the_content('!--more'), '!--more')!==false) {
					the_content( '' );
				} else if (in_array($ostende_post_format, array('link', 'aside', 'status'))) {
					the_content();
				} else if ($ostende_post_format == 'quote') {
					if (($quote = ostende_get_tag(get_the_content(), '<blockquote>', '</blockquote>'))!='')
						ostende_show_layout(wpautop($quote));
					else
						the_excerpt();
				} else if (substr(get_the_content(), 0, 4)!='[vc_') {
					the_excerpt();
				}
			?></div><?php
			// More button
			if ( $ostende_show_learn_more ) {
				?><p><a class="more-link" href="<?php echo esc_url(get_permalink()); ?>"><?php esc_html_e('Read more', 'ostende'); ?></a></p><?php
			}

		}
	?></div><!-- .entry-content -->
</article>