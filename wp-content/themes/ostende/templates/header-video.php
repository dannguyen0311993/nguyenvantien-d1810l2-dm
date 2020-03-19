<?php
/**
 * The template to display the background video in the header
 *
 
 * @subpackage OSTENDE
 * @since OSTENDE 1.0.14
 */
$ostende_header_video = ostende_get_header_video();
$ostende_embed_video = '';
if (!empty($ostende_header_video) && !ostende_is_from_uploads($ostende_header_video)) {
	if (ostende_is_youtube_url($ostende_header_video) && preg_match('/[=\/]([^=\/]*)$/', $ostende_header_video, $matches) && !empty($matches[1])) {
		?><div id="background_video" data-youtube-code="<?php echo esc_attr($matches[1]); ?>"></div><?php
	} else {
		global $wp_embed;
		if (false && is_object($wp_embed)) {
			$ostende_embed_video = do_shortcode($wp_embed->run_shortcode( '[embed]' . trim($ostende_header_video) . '[/embed]' ));
			$ostende_embed_video = ostende_make_video_autoplay($ostende_embed_video);
		} else {
			$ostende_header_video = str_replace('/watch?v=', '/embed/', $ostende_header_video);
			$ostende_header_video = ostende_add_to_url($ostende_header_video, array(
				'feature' => 'oembed',
				'controls' => 0,
				'autoplay' => 1,
				'showinfo' => 0,
				'modestbranding' => 1,
				'wmode' => 'transparent',
				'enablejsapi' => 1,
				'origin' => esc_url(home_url()),
				'widgetid' => 1
			));
			$ostende_embed_video = '<iframe src="' . esc_url($ostende_header_video) . '" width="1170" height="658" allowfullscreen="0" frameborder="0"></iframe>';
		}
		?><div id="background_video"><?php ostende_show_layout($ostende_embed_video); ?></div><?php
	}
}
?>