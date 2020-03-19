<?php
/**
 * The template to display the 404 page
 *
 
 * @subpackage OSTENDE
 * @since OSTENDE 1.0
 */

get_header();



get_template_part( 'content', '404' );

ostende_storage_set_array2('options', 'sidebar_position_blog', 'val', 'right');

get_footer();
?>