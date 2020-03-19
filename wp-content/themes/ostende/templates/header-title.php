<?php
/**
 * The template to display the page title and breadcrumbs
 *
 
 * @subpackage OSTENDE
 * @since OSTENDE 1.0
 */

// Page (category, tag, archive, author) title

if ( ostende_need_page_title() ) {
	ostende_sc_layouts_showed('title', true);
	ostende_sc_layouts_showed('postmeta', false);
	?>
	<div class="top_panel_title sc_layouts_row sc_layouts_row_type_normal">
		<div class="content_wrap">
			<div class="sc_layouts_column sc_layouts_column_align_center">
				<div class="sc_layouts_item">
					<div class="sc_layouts_title sc_align_center">
						<?php
						// Post meta on the single post
						if ( false && is_single() )  {
							?><div class="sc_layouts_title_meta"><?php
								ostende_show_post_meta(apply_filters('ostende_filter_post_meta_args', array(
									'components' => ostende_array_get_keys_by_value(ostende_get_theme_option('meta_parts')),
									'counters' => ostende_array_get_keys_by_value(ostende_get_theme_option('counters'))
									), 'header', 1)
								);
							?></div><?php
						}
						
						// Blog/Post title
						?><div class="sc_layouts_title_title"><?php
							$ostende_blog_title = ostende_get_blog_title();
							$ostende_blog_title_text = $ostende_blog_title_class = $ostende_blog_title_link = $ostende_blog_title_link_text = '';
							if (is_array($ostende_blog_title)) {
								$ostende_blog_title_text = $ostende_blog_title['text'];
								$ostende_blog_title_class = !empty($ostende_blog_title['class']) ? ' '.$ostende_blog_title['class'] : '';
								$ostende_blog_title_link = !empty($ostende_blog_title['link']) ? $ostende_blog_title['link'] : '';
								$ostende_blog_title_link_text = !empty($ostende_blog_title['link_text']) ? $ostende_blog_title['link_text'] : '';
							} else
								$ostende_blog_title_text = $ostende_blog_title;
							?>
							<h1 itemprop="headline" class="sc_layouts_title_caption<?php echo esc_attr($ostende_blog_title_class); ?>"><?php
								$ostende_top_icon = ostende_get_category_icon();
								if (!empty($ostende_top_icon)) {
									$ostende_attr = ostende_getimagesize($ostende_top_icon);
									?><img src="<?php echo esc_url($ostende_top_icon); ?>" alt="<?php if (!empty($ostende_attr[3])) ostende_show_layout($ostende_attr[3]);?>"><?php
								}
								echo wp_kses_post($ostende_blog_title_text);
							?></h1>
							<?php
							if (!empty($ostende_blog_title_link) && !empty($ostende_blog_title_link_text)) {
								?><a href="<?php echo esc_url($ostende_blog_title_link); ?>" class="theme_button theme_button_small sc_layouts_title_link"><?php echo esc_html($ostende_blog_title_link_text); ?></a><?php
							}
							$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
							// Category/Tag description
							if ( is_category() || is_tag() || is_tax() ) 
								if( $paged < 2 )the_archive_description( '<div class="sc_layouts_title_description">', '</div>' );
		
						?></div><?php
	
						// Breadcrumbs
						?><div class="sc_layouts_title_breadcrumbs"><?php
							do_action( 'ostende_action_breadcrumbs');
						?></div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php
}
?>