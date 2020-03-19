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
$ostende_columns = empty($ostende_blog_style[1]) ? 1 : max(1, $ostende_blog_style[1]);
$ostende_expanded = !ostende_sidebar_present() && ostende_is_on(ostende_get_theme_option('expand_content'));
$ostende_post_format = get_post_format();
$ostende_post_format = empty($ostende_post_format) ? 'standard' : str_replace('post-format-', '', $ostende_post_format);
$ostende_animation = ostende_get_theme_option('blog_animation');

?><article id="post-<?php the_ID(); ?>" 
	<?php post_class( 'post_item post_layout_chess post_layout_chess_'.esc_attr($ostende_columns).' post_format_'.esc_attr($ostende_post_format) ); ?>
	<?php echo (!ostende_is_off($ostende_animation) ? ' data-animation="'.esc_attr(ostende_get_animation_classes($ostende_animation)).'"' : ''); ?>>

	<?php
	// Add anchor
	if ($ostende_columns == 1 && shortcode_exists('trx_sc_anchor')) {
		echo do_shortcode('[trx_sc_anchor id="post_'.esc_attr(get_the_ID()).'" title="'.esc_attr(get_the_title()).'" icon="'.esc_attr(ostende_get_post_icon()).'"]');
	}

	// Sticky label
	if ( is_sticky() && !is_paged() ) {
		?><span class="post_label label_sticky"></span><?php
	}

	// Featured image
	ostende_show_post_featured( array(
											'class' => $ostende_columns == 1 ? 'ostende-full-height' : '',
											'show_no_image' => true,
											'thumb_bg' => true,
											'thumb_size' => ostende_get_thumb_size(
																	strpos(ostende_get_theme_option('body_style'), 'full')!==false
																		? ( $ostende_columns > 1 ? 'huge' : 'original' )
																		: (	$ostende_columns > 2 ? 'big' : 'huge')
																	)
											) 
										);

	?><div class="post_inner"><div class="post_inner_content"><?php 

		?><div class="post_header entry-header"><?php 
			do_action('ostende_action_before_post_title'); 

			// Post title
			the_title( sprintf( '<h3 class="post_title entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h3>' );
			
			do_action('ostende_action_before_post_meta'); 

			// Post meta
			$ostende_components = ostende_array_get_keys_by_value(ostende_get_theme_option('meta_parts'));
			$ostende_counters = ostende_array_get_keys_by_value(ostende_get_theme_option('counters'));
			$ostende_post_meta = empty($ostende_components) 
										? '' 
										: ostende_show_post_meta(apply_filters('ostende_filter_post_meta_args', array(
												'components' => $ostende_components,
												'counters' => $ostende_counters,
												'echo' => false
												), $ostende_blog_style[0], $ostende_columns)
											);
			ostende_show_layout($ostende_post_meta);
		?></div><!-- .entry-header -->
	
		<div class="post_content entry-content">
			<div class="post_content_inner">
				<?php
				$ostende_show_learn_more = !in_array($ostende_post_format, array('link', 'aside', 'status', 'quote'));
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
				ostende_show_layout($ostende_post_meta);
			}
			// More button
			if ( $ostende_show_learn_more ) {
				?><p><a class="more-link" href="<?php echo esc_url(get_permalink()); ?>"><?php esc_html_e('Read more', 'ostende'); ?></a></p><?php
			}
			?>
		</div><!-- .entry-content -->

	</div></div><!-- .post_inner -->

</article>