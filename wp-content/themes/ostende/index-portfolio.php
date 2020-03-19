<?php
/**
 * The template for homepage posts with "Portfolio" style
 *
 
 * @subpackage OSTENDE
 * @since OSTENDE 1.0
 */

ostende_storage_set('blog_archive', true);

get_header(); 

if (have_posts()) {

	ostende_show_layout(get_query_var('blog_archive_start'));

	$ostende_stickies = is_home() ? get_option( 'sticky_posts' ) : false;
	$ostende_sticky_out = ostende_get_theme_option('sticky_style')=='columns' 
							&& is_array($ostende_stickies) && count($ostende_stickies) > 0 && get_query_var( 'paged' ) < 1;
	
	// Show filters
	$ostende_cat = ostende_get_theme_option('parent_cat');
	$ostende_post_type = ostende_get_theme_option('post_type');
	$ostende_taxonomy = ostende_get_post_type_taxonomy($ostende_post_type);
	$ostende_show_filters = ostende_get_theme_option('show_filters');
	$ostende_tabs = array();
	if (!ostende_is_off($ostende_show_filters)) {
		$ostende_args = array(
			'type'			=> $ostende_post_type,
			'child_of'		=> $ostende_cat,
			'orderby'		=> 'name',
			'order'			=> 'ASC',
			'hide_empty'	=> 1,
			'hierarchical'	=> 0,
			'exclude'		=> '',
			'number'		=> '',
			'taxonomy'		=> $ostende_taxonomy,
			'pad_counts'	=> false
		);
		$ostende_portfolio_list = get_terms($ostende_args);
		if (is_array($ostende_portfolio_list) && count($ostende_portfolio_list) > 0) {
			$ostende_tabs[$ostende_cat] = esc_html__('All', 'ostende');
			foreach ($ostende_portfolio_list as $ostende_term) {
				if (isset($ostende_term->term_id)) $ostende_tabs[$ostende_term->term_id] = $ostende_term->name;
			}
		}
	}
	if (count($ostende_tabs) > 0) {
		$ostende_portfolio_filters_ajax = true;
		$ostende_portfolio_filters_active = $ostende_cat;
		$ostende_portfolio_filters_id = 'portfolio_filters';
		?>
		<div class="portfolio_filters ostende_tabs ostende_tabs_ajax">
			<ul class="portfolio_titles ostende_tabs_titles">
				<?php
				foreach ($ostende_tabs as $ostende_id=>$ostende_title) {
					?><li><a href="<?php echo esc_url(ostende_get_hash_link(sprintf('#%s_%s_content', $ostende_portfolio_filters_id, $ostende_id))); ?>" data-tab="<?php echo esc_attr($ostende_id); ?>"><?php echo esc_html($ostende_title); ?></a></li><?php
				}
				?>
			</ul>
			<?php
			$ostende_ppp = ostende_get_theme_option('posts_per_page');
			if (ostende_is_inherit($ostende_ppp)) $ostende_ppp = '';
			foreach ($ostende_tabs as $ostende_id=>$ostende_title) {
				$ostende_portfolio_need_content = $ostende_id==$ostende_portfolio_filters_active || !$ostende_portfolio_filters_ajax;
				?>
				<div id="<?php echo esc_attr(sprintf('%s_%s_content', $ostende_portfolio_filters_id, $ostende_id)); ?>"
					class="portfolio_content ostende_tabs_content"
					data-blog-template="<?php echo esc_attr(ostende_storage_get('blog_template')); ?>"
					data-blog-style="<?php echo esc_attr(ostende_get_theme_option('blog_style')); ?>"
					data-posts-per-page="<?php echo esc_attr($ostende_ppp); ?>"
					data-post-type="<?php echo esc_attr($ostende_post_type); ?>"
					data-taxonomy="<?php echo esc_attr($ostende_taxonomy); ?>"
					data-cat="<?php echo esc_attr($ostende_id); ?>"
					data-parent-cat="<?php echo esc_attr($ostende_cat); ?>"
					data-need-content="<?php echo (false===$ostende_portfolio_need_content ? 'true' : 'false'); ?>"
				>
					<?php
					if ($ostende_portfolio_need_content) 
						ostende_show_portfolio_posts(array(
							'cat' => $ostende_id,
							'parent_cat' => $ostende_cat,
							'taxonomy' => $ostende_taxonomy,
							'post_type' => $ostende_post_type,
							'page' => 1,
							'sticky' => $ostende_sticky_out
							)
						);
					?>
				</div>
				<?php
			}
			?>
		</div>
		<?php
	} else {
		ostende_show_portfolio_posts(array(
			'cat' => $ostende_cat,
			'parent_cat' => $ostende_cat,
			'taxonomy' => $ostende_taxonomy,
			'post_type' => $ostende_post_type,
			'page' => 1,
			'sticky' => $ostende_sticky_out
			)
		);
	}

	ostende_show_layout(get_query_var('blog_archive_end'));

} else {

	if ( is_search() )
		get_template_part( 'content', 'none-search' );
	else
		get_template_part( 'content', 'none-archive' );

}

get_footer();
?>