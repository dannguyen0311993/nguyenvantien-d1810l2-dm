<?php
/**
 * The Footer: widgets area, logo, footer menu and socials
 *
 
 * @subpackage OSTENDE
 * @since OSTENDE 1.0
 */

						// Widgets area inside page content
						ostende_create_widgets_area('widgets_below_content');
						?>				
					</div><!-- </.content> -->

					<?php
					// Show main sidebar
					get_sidebar();

					// Widgets area below page content
					ostende_create_widgets_area('widgets_below_page');

					$ostende_body_style = ostende_get_theme_option('body_style');
					if ($ostende_body_style != 'fullscreen') {
						?></div><!-- </.content_wrap> --><?php
					}
					?>
			</div><!-- </.page_content_wrap> -->
			
			<?php
			// Skip link anchor to fast access to the footer from keyboard
			?>
			<a id="footer_skip_link_anchor" class="ostende_skip_link_anchor" href="#"></a>
			<?php

			// Footer
			get_template_part( "templates/footer-default");
			?>

		</div><!-- /.page_wrap -->

	</div><!-- /.body_wrap -->

	<?php wp_footer(); ?>

</body>
</html>