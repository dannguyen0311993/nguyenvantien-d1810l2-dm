<?php
/**
 * The default template to display the content of the single post, page or attachment
 *
 * Used for index/archive/search.
 *
 
 * @subpackage OSTENDE
 * @since OSTENDE 1.0
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'post_item_single post_type_'.esc_attr(get_post_type()) 
												. ' post_format_'.esc_attr(str_replace('post-format-', '', get_post_format())));?>>
<?php

	do_action('ostende_action_before_post_data'); 

	// Featured image
	if ( ostende_is_on(ostende_get_theme_option('show_featured_on_single'))
			&& !ostende_sc_layouts_showed('featured') 
			&& strpos(get_the_content(), '[trx_widget_banner]')===false) {
		do_action('ostende_action_before_post_featured');
            $cats = get_post_type()=='post' ? get_the_category_list(' ') : apply_filters('ostende_filter_get_post_categories', '');
            if (!empty($cats)) {
                $cats = '<span class="post_meta_item post_categories">'.($cats).'</span>';
            }
		ostende_show_post_featured(array(
            'post_info' => $cats
        ));
		do_action('ostende_action_after_post_featured'); 
	} else if (has_post_thumbnail()) {
		?><meta itemprop="image" itemtype="http://schema.org/ImageObject" content="<?php echo esc_url(wp_get_attachment_url(get_post_thumbnail_id())); ?>"><?php
	}

	// Title and post meta
	if ( (!ostende_sc_layouts_showed('title') || !ostende_sc_layouts_showed('postmeta')) && !in_array(get_post_format(), array('link', 'aside', 'status', 'quote')) ) {
		do_action('ostende_action_before_post_title'); 
		?>
		<div class="post_header entry-header">
			<?php
			// Post title
			if (!ostende_sc_layouts_showed('title')) {
				the_title( '<h3 class="post_title entry-title"'.($ostende_seo ? ' itemprop="headline"' : '').'>', '</h3>' );
			}
			// Post meta
			if (!ostende_sc_layouts_showed('postmeta') && ostende_is_on(ostende_get_theme_option('show_post_meta'))) {
				ostende_show_post_meta(apply_filters('ostende_filter_post_meta_args', array(
					'components' => ostende_array_get_keys_by_value(ostende_get_theme_option('meta_parts')),
					'counters' => ostende_array_get_keys_by_value(ostende_get_theme_option('counters'))
					), 'single', 1)
				);
			}
			?>
		</div><!-- .post_header -->
		<?php
		do_action('ostende_action_after_post_title'); 
	}

	do_action('ostende_action_before_post_content'); 

	// Post content
	?>
	<div class="post_content entry-content" itemprop="mainEntityOfPage">
		<?php
		the_content( );

		do_action('ostende_action_before_post_pagination'); 

		wp_link_pages( array(
			'before'      => '<div class="page_links"><span class="page_links_title">' . esc_html__( 'Pages:', 'ostende' ) . '</span>',
			'after'       => '</div>',
			'link_before' => '<span>',
			'link_after'  => '</span>',
			'pagelink'    => '<span class="screen-reader-text">' . esc_html__( 'Page', 'ostende' ) . ' </span>%',
			'separator'   => '<span class="screen-reader-text">, </span>',
		) );

		// Taxonomies and share
		if ( is_single() && !is_attachment() ) {
			
			do_action('ostende_action_before_post_meta'); 

			?><div class="post_meta post_meta_single"><?php
				
				// Post taxonomies
				the_tags( '<span class="post_meta_item post_tags"><span class="post_meta_label">'.esc_html__('Tags:', 'ostende').'</span> ', ' ', '</span>' );

				// Share
				if (ostende_is_on(ostende_get_theme_option('show_share_links'))) {
					ostende_show_share_links(array(
							'type' => 'block',
							'caption' => '',
							'before' => '<span class="post_meta_item post_share">',
							'after' => '</span>'
						));
				}
			?></div><?php

			do_action('ostende_action_after_post_meta'); 
		}
		?>
	</div><!-- .entry-content -->
	

	<?php
	do_action('ostende_action_after_post_content'); 

	// Author bio.
	if ( ostende_get_theme_option('show_author_info')==1 && is_single() && !is_attachment() && get_the_author_meta( 'description' ) ) {
		do_action('ostende_action_before_post_author'); 
		get_template_part( 'templates/author-bio' );
		do_action('ostende_action_after_post_author'); 
	}

	do_action('ostende_action_after_post_data'); 
	?>
</article>
