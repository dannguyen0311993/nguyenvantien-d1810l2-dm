<?php
/**
 * The Classic template to display the content
 *
 * Used for index/archive/search.
 *
 
 * @subpackage OSTENDE
 * @since OSTENDE 1.0
 */

$ostende_blog_style = explode('_', ostende_get_theme_option('blog_style'));
$ostende_columns = empty($ostende_blog_style[1]) ? 2 : max(2, $ostende_blog_style[1]);
$ostende_expanded = !ostende_sidebar_present() && ostende_is_on(ostende_get_theme_option('expand_content'));
$ostende_post_format = get_post_format();
$ostende_post_format = empty($ostende_post_format) ? 'standard' : str_replace('post-format-', '', $ostende_post_format);
$ostende_animation = ostende_get_theme_option('blog_animation');
$ostende_components = ostende_array_get_keys_by_value(ostende_get_theme_option('meta_parts'));
$ostende_counters = ostende_array_get_keys_by_value(ostende_get_theme_option('counters'));

?><div class="<?php echo esc_attr($ostende_blog_style[0] == 'classic' ? 'column' : 'masonry_item masonry_item'); ?>-1_<?php echo esc_attr($ostende_columns); ?>"><article id="post-<?php the_ID(); ?>"
	<?php post_class( 'post_item post_format_'.esc_attr($ostende_post_format)
					. ' post_layout_classic post_layout_classic_'.esc_attr($ostende_columns)
					. ' post_layout_'.esc_attr($ostende_blog_style[0]) 
					. ' post_layout_'.esc_attr($ostende_blog_style[0]).'_'.esc_attr($ostende_columns)
					); ?>
	<?php echo (!ostende_is_off($ostende_animation) ? ' data-animation="'.esc_attr(ostende_get_animation_classes($ostende_animation)).'"' : ''); ?>>
	<?php

	// Sticky label
	if ( is_sticky() && !is_paged() ) {
		?><span class="post_label label_sticky"></span><?php
	}

	// Featured image
	ostende_show_post_featured( array( 'thumb_size' => ostende_get_thumb_size($ostende_blog_style[0] == 'classic'
													? (strpos(ostende_get_theme_option('body_style'), 'full')!==false 
															? ( $ostende_columns > 2 ? 'big' : 'huge' )
															: (	$ostende_columns > 2
																? ($ostende_expanded ? 'med' : 'small')
																: ($ostende_expanded ? 'big' : 'med')
																)
														)
													: (strpos(ostende_get_theme_option('body_style'), 'full')!==false 
															? ( $ostende_columns > 2 ? 'masonry-big' : 'full' )
															: (	$ostende_columns <= 2 && $ostende_expanded ? 'masonry-big' : 'masonry')
														)
								) ) );

	if ( !in_array($ostende_post_format, array('link', 'aside', 'status', 'quote')) ) {
		?>
		<div class="post_header entry-header">
			<?php 
			do_action('ostende_action_before_post_title'); 

			// Post title
			the_title( sprintf( '<h4 class="post_title entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h4>' );

			do_action('ostende_action_before_post_meta'); 

			// Post meta
			if (!empty($ostende_components) && !is_search())
				ostende_show_post_meta(apply_filters('ostende_filter_post_meta_args', array(
					'components' => $ostende_components,
					'counters' => $ostende_counters,
					), $ostende_blog_style[0], $ostende_columns)
				);

			do_action('ostende_action_after_post_meta'); 
			?>
		</div><!-- .entry-header -->
		<?php
	}		
	?>

	<div class="post_content entry-content">
		<div class="post_content_inner">
			<?php
			$ostende_show_learn_more = false;
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
			?>
		</div>
		<?php
		// Post meta
		if (in_array($ostende_post_format, array('link', 'aside', 'status', 'quote'))) {
			if (!empty($ostende_components))
				ostende_show_post_meta(apply_filters('ostende_filter_post_meta_args', array(
					'components' => $ostende_components,
					'counters' => $ostende_counters
					), $ostende_blog_style[0], $ostende_columns)
				);
		}
		// More button
		if ( $ostende_show_learn_more ) {
			?><p><a class="more-link" href="<?php echo esc_url(get_permalink()); ?>"><?php esc_html_e('Read more', 'ostende'); ?></a></p><?php
		}
		?>
	</div><!-- .entry-content -->

</article></div>