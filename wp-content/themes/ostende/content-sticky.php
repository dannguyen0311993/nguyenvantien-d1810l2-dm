<?php
/**
 * The Sticky template to display the sticky posts
 *
 * Used for index/archive
 *
 
 * @subpackage OSTENDE
 * @since OSTENDE 1.0
 */

$ostende_columns = max(1, min(3, count(get_option( 'sticky_posts' ))));
$ostende_post_format = get_post_format();
$ostende_post_format = empty($ostende_post_format) ? 'standard' : str_replace('post-format-', '', $ostende_post_format);
$ostende_animation = ostende_get_theme_option('blog_animation');

?><div class="column-1_<?php echo esc_attr($ostende_columns); ?>"><article id="post-<?php the_ID(); ?>" 
	<?php post_class( 'post_item post_layout_sticky post_format_'.esc_attr($ostende_post_format) ); ?>
	<?php echo (!ostende_is_off($ostende_animation) ? ' data-animation="'.esc_attr(ostende_get_animation_classes($ostende_animation)).'"' : ''); ?>
	>

	<?php
	if ( is_sticky() && is_home() && !is_paged() ) {
		?><span class="post_label label_sticky"></span><?php
	}

	// Featured image
	ostende_show_post_featured(array(
		'thumb_size' => ostende_get_thumb_size($ostende_columns==1 ? 'big' : ($ostende_columns==2 ? 'med' : 'avatar'))
	));

	if ( !in_array($ostende_post_format, array('link', 'aside', 'status', 'quote')) ) {
		?>
		<div class="post_header entry-header">
			<?php
			// Post title
			the_title( sprintf( '<h6 class="post_title entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h6>' );
			// Post meta
			ostende_show_post_meta(apply_filters('ostende_filter_post_meta_args', array(), 'sticky', $ostende_columns));
			?>
		</div><!-- .entry-header -->
		<?php
	}
	?>
</article></div>