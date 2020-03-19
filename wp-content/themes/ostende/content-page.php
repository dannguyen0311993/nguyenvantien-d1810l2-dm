<?php
/**
 * The default template to display the content of the single page
 *
 
 * @subpackage OSTENDE
 * @since OSTENDE 1.0
 */


?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'post_item_single post_type_page' ); ?>>

	<?php
	do_action('ostende_action_before_post_data'); 

	// Structured data snippets
	// Now featured image used as header's background
	// Uncomment next rows (or remove false from the condition) to show featured image for the pages
	if ( false && !ostende_sc_layouts_showed('featured') && strpos(get_the_content(), '[trx_widget_banner]')===false) {
		do_action('ostende_action_before_post_featured'); 
		ostende_show_post_featured();
		do_action('ostende_action_after_post_featured'); 
	} 

	do_action('ostende_action_before_post_content'); 
	?>

	<div class="post_content entry-content">
		<?php

		// Featured image
		ostende_show_post_featured(array(
		'thumb_size' => ostende_get_thumb_size('full')
	));

			the_content( );

			wp_link_pages( array(
				'before'      => '<div class="page_links"><span class="page_links_title">' . esc_html__( 'Pages:', 'ostende' ) . '</span>',
				'after'       => '</div>',
				'link_before' => '<span>',
				'link_after'  => '</span>',
				'pagelink'    => '<span class="screen-reader-text">' . esc_html__( 'Page', 'ostende' ) . ' </span>%',
				'separator'   => '<span class="screen-reader-text">, </span>',
			) );
		?>
	</div><!-- .entry-content -->

	<?php
	do_action('ostende_action_after_post_content'); 

	do_action('ostende_action_after_post_data'); 
	?>

</article>
