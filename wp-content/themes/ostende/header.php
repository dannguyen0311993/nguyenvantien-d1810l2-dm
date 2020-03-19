<?php
/**
 * The Header: Logo and main menu
 *
 
 * @subpackage OSTENDE
 * @since OSTENDE 1.0
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js scheme_<?php
										 // Class scheme_xxx need in the <html> as context for the <body>!
										 echo esc_attr(ostende_get_theme_option('color_scheme'));
										 ?>">
<head>
	<?php wp_head(); ?>
</head>

<body <?php	body_class(); ?>>

	<?php 
	if ( function_exists( 'wp_body_open' ) ) {
		wp_body_open();
	} else {
		do_action( 'wp_body_open' );
	}
	do_action( 'ostende_action_before_body' );
	?>

	<div class="body_wrap">

		<div class="page_wrap">

			<?php
			// Short links to fast access to the content, sidebar and footer from the keyboard
			?>
			<a class="ostende_skip_link skip_to_content_link" href="#content_skip_link_anchor" tabindex="1"><?php esc_html_e( "Skip to content", 'ostende' ); ?></a>
			<?php if ( ostende_sidebar_present() ) { ?>
			<a class="ostende_skip_link skip_to_sidebar_link" href="#sidebar_skip_link_anchor" tabindex="1"><?php esc_html_e( "Skip to sidebar", 'ostende' ); ?></a>
			<?php } ?>
			<a class="ostende_skip_link skip_to_footer_link" href="#footer_skip_link_anchor" tabindex="1"><?php esc_html_e( "Skip to footer", 'ostende' ); ?></a>

			<?php
			// Desktop header
			get_template_part( "templates/header-default");

			// Side menu
			if (in_array(ostende_get_theme_option('menu_style'), array('left', 'right'))) {
				get_template_part( 'templates/header-navi-side' );
			}
			
			// Mobile menu
			get_template_part( 'templates/header-navi-mobile');
			?>

			<div class="page_content_wrap">

				<?php if (ostende_get_theme_option('body_style') != 'fullscreen') { ?>
				<div class="content_wrap">
				<?php } ?>

					<?php
					// Widgets area above page content
					ostende_create_widgets_area('widgets_above_page');
					?>				

					<div class="content">
						<?php
						// Skip link anchor to fast access to the content from keyboard
						?>
						<a id="content_skip_link_anchor" class="ostende_skip_link_anchor" href="#"></a>
						<?php
						// Widgets area inside page content
						ostende_create_widgets_area('widgets_above_content');
						?>				
