<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
add_shortcode('ALW', 'awl_alw_shortcode');
function awl_alw_shortcode($post_id) {
	ob_start();
	
	//js
	wp_enqueue_script('jquery');
	wp_enqueue_script('imagesloaded');
	wp_enqueue_script('modernizr-custom-js', ALW_PLUGIN_URL . 'assets/js/modernizr.custom.26633.js', array('jquery'), '', false);
	wp_enqueue_script('jquery-gridrotator-js', ALW_PLUGIN_URL . 'assets/js/jquery.gridrotator.js', array('jquery'), '', false);
	
	//CSS
	wp_enqueue_style('alw-style-css', ALW_PLUGIN_URL . 'assets/css/alw-style.css');
	
	//Freewall
	wp_enqueue_script('freewall-js', ALW_PLUGIN_URL . 'assets/freewall/freewall.js', array('jquery'), '', false);
	wp_enqueue_style('freewall-style-css', ALW_PLUGIN_URL . 'assets/freewall/freewall-style.css');
	
	//Lightbox
	wp_enqueue_style('colorbox-lightbox-css', ALW_PLUGIN_URL . 'assets/lightbox/colorbox.css');
	wp_enqueue_script('colorbox-lightbox-js', ALW_PLUGIN_URL . 'assets/lightbox/jquery.colorbox.js', array('jquery'), '', false);
	
	
	//fontawesome 
	wp_enqueue_style('all-fontawesome-min-css', ALW_PLUGIN_URL . 'assets/css/fontawesome-all.min.css');
	
	//hover effects
	wp_enqueue_style('hover-effect-css', ALW_PLUGIN_URL . 'assets/hover-effects/hover-effect.css');
	
	//get the photo wall settings
	$alw_get_settings = get_post_meta( $post_id['id'], 'awl_animated_live_wall'.$post_id['id'], true);

/* 	echo "<pre>";
	print_r($alw_get_settings);
	echo "</pre>"; */
	
	//grid configuration
	if(isset($alw_get_settings['alw_grid_rows'])) $alw_grid_rows = $alw_get_settings['alw_grid_rows']; else $alw_grid_rows = "4"; 
	if(isset($alw_get_settings['alw_grid_columns'])) $alw_grid_columns = $alw_get_settings['alw_grid_columns']; else $alw_grid_columns = "12"; 
	if(isset($alw_get_settings['alw_grid_animation'])) $alw_grid_animation = $alw_get_settings['alw_grid_animation']; else $alw_grid_animation = "random"; 
	if(isset($alw_get_settings['alw_grid_max_step'])) $alw_grid_max_step = $alw_get_settings['alw_grid_max_step']; else $alw_grid_max_step = ""; 
	if(isset($alw_get_settings['alw_grid_anim_speed'])) $alw_grid_anim_speed = $alw_get_settings['alw_grid_anim_speed']; else $alw_grid_anim_speed = "700"; 
	if(isset($alw_get_settings['alw_grid_anim_interval'])) $alw_grid_anim_interval = $alw_get_settings['alw_grid_anim_interval']; else $alw_grid_anim_interval = "1200"; 
	if(isset($alw_get_settings['alw_grid_no_change'])) $alw_grid_no_change = $alw_get_settings['alw_grid_no_change']; else $alw_grid_no_change = ""; 
	if(isset($alw_get_settings['alw_grid_gap'])) $alw_grid_gap = $alw_get_settings['alw_grid_gap']; else $alw_grid_gap = 0; 
	if(isset($alw_get_settings['alw_images_gap'])) $alw_images_gap = $alw_get_settings['alw_images_gap']; else $alw_images_gap = "15"; 
	
	if(isset($alw_get_settings['enable_gallery_layout'])) $enable_gallery_layout = $alw_get_settings['enable_gallery_layout']; else $enable_gallery_layout = ""; 
	if(isset($alw_get_settings['alw_grid_thumb_size'])) $alw_grid_thumb_size = $alw_get_settings['alw_grid_thumb_size']; else $alw_grid_thumb_size = "full"; 
	if(isset($alw_get_settings['alw_thumb_size'])) $alw_thumb_size = $alw_get_settings['alw_thumb_size']; else $alw_thumb_size = "full"; 
	if(isset($alw_get_settings['alw_img_redirection'])) $alw_img_redirection = $alw_get_settings['alw_img_redirection']; else $alw_img_redirection = "_new"; 
	if(isset($alw_get_settings['alw_maso_img_redirection'])) $alw_maso_img_redirection = $alw_get_settings['alw_maso_img_redirection']; else $alw_maso_img_redirection = "_new"; 
	if(isset($alw_get_settings['alw_grid_stop_anim'])) $alw_grid_stop_anim = $alw_get_settings['alw_grid_stop_anim']; else $alw_grid_stop_anim = "no"; 
	
	//instagram
	if(isset($alw_get_settings['alw_instagram_token'])) $alw_instagram_token = $alw_get_settings['alw_instagram_token']; else $alw_instagram_token = "2961809551.1677ed0.c61a67160cd647d4b537305ceb150ea4"; 
	if(isset($alw_get_settings['alw_gallery_wall'])) $alw_gallery_wall = $alw_get_settings['alw_gallery_wall']; else $alw_gallery_wall = ""; 
	if(isset($alw_get_settings['alw_insta_icon'])) $alw_insta_icon = $alw_get_settings['alw_insta_icon']; else $alw_insta_icon = "instagram"; 
	if(isset($alw_get_settings['alw_insta_caption'])) $alw_insta_caption = $alw_get_settings['alw_insta_caption']; else $alw_insta_caption = "false"; 
	if(isset($alw_get_settings['alw_insta_link'])) $alw_insta_link = $alw_get_settings['alw_insta_link']; else $alw_insta_link = "_new"; 
	
	//Flickr 
	if(isset($alw_get_settings['alw_flickr_api_key'])) $alw_flickr_api_key = $alw_get_settings['alw_flickr_api_key']; else $alw_flickr_api_key = "4405cbae4b35b98f14f5e839c6e03599"; 
	if(isset($alw_get_settings['alw_flickr_user_id'])) $alw_flickr_user_id = $alw_get_settings['alw_flickr_user_id']; else $alw_flickr_user_id = "147476924@N07"; 
	
	//Hover
	if(isset($alw_get_settings['alw_hover_effect'])) $alw_hover_effect = $alw_get_settings['alw_hover_effect']; else $alw_hover_effect = "true"; 

	//lightbox 
	if(isset($alw_get_settings['column_setting'])) $column_setting = $alw_get_settings['column_setting']; else $column_setting = "small_column"; 
	if(isset($alw_get_settings['alw_lightbox'])) $alw_lightbox = $alw_get_settings['alw_lightbox']; else $alw_lightbox = "true"; 
	if(isset($alw_get_settings['alw_lightbox_thumb_size'])) $alw_lightbox_thumb_size = $alw_get_settings['alw_lightbox_thumb_size']; else $alw_lightbox_thumb_size = "full"; 
	//load more 
	if(isset($alw_get_settings['alw_load_more'])) $alw_load_more = $alw_get_settings['alw_load_more']; else $alw_load_more = "no"; 
	if(isset($alw_get_settings['alw_load_more_limit'])) $alw_load_more_limit = $alw_get_settings['alw_load_more_limit']; else $alw_load_more_limit = "4"; 
	if(isset($alw_get_settings['alw_load_more_color'])) $alw_load_more_color = $alw_get_settings['alw_load_more_color']; else $alw_load_more_color = "#1ECD97"; 
	
	
	// lod button css
	?>
	<style>
	.progress-button button {
		border: 2px solid <?php echo $alw_load_more_color; ?>;
		color: <?php echo $alw_load_more_color; ?>
	}
	.progress-button button:hover {
		background-color: <?php echo $alw_load_more_color; ?>
	}
	.ri-grid ul li {
		margin: <?php echo $alw_grid_gap; ?>px !important;
	}
	</style>
	<?php
	//get id
	$alw_id = $post_id['id'];
	
	
	if($alw_gallery_wall == 'photo_wall') {
		if($enable_gallery_layout == 'grid') {
			require('include/alw-grid-animated-shortcode.php');
		}
		if($enable_gallery_layout == 'masonry') {
			require('include/alw-masonry-layout-shortcode.php');
		}
		
	}
	if($alw_gallery_wall == 'insta_wall') {
		
		// get instagram api
		$instagram_data_decode = file_get_contents("https://api.instagram.com/v1/users/self/media/recent/?access_token=$alw_instagram_token&count=100");
		$instagram_data = json_decode($instagram_data_decode, true);
		/* echo "<pre>";
		print_r($instagram_data);
		echo "</pre>"; */
		// require layout 
		if($enable_gallery_layout == 'grid') {
			require('include/instagram-gallery/alw-instagram-grid-animated-shortcode.php');
		}
		if($enable_gallery_layout == 'masonry') {
			require('include/instagram-gallery/alw-instagram-masonry-layout-shortcode.php');
		}
		
	}
	if($alw_gallery_wall == 'flickr_wall') {
		
		// get flickr api
		$params = array(
		'api_key'		=>	$alw_flickr_api_key,
		'user_id'		=>	$alw_flickr_user_id,
		'method'		=>	'flickr.people.getPublicPhotos',
		'per_page'		=>	50,
		'format'		=>	'php_serial',
		'extras'		=>	'date_upload, date_taken, owner_name, icon_server, original_format, last_update, geo, tags, machine_tags, o_dims, views, media, path_alias, url, url_sq, url_q, url_t, url_s, url_n, url_m, url_z, url_c, url_l, url_o',
		);

		$encoded_params = array();
		foreach ($params as $k => $v){
			$encoded_params[] = urlencode($k).'='.urlencode($v);
		}

		# call the API and decode the response
		$url = "https://api.flickr.com/services/rest/?".implode('&', $encoded_params);
		$rsp = file_get_contents($url);
		$rsp_obj = unserialize($rsp);
		$flickr_data = $rsp_obj['photos']['photo'];
		
		/* echo "<pre>";
		print_r($flickr_data);
		echo "</pre>";  */
		
		// require layout 
		if($enable_gallery_layout == 'grid') {
			require('include/flickr-gallery/alw-flickr-grid-animated-shortcode.php');
		}
		if($enable_gallery_layout == 'masonry') {
			require('include/flickr-gallery/alw-flickr-masonry-layout-shortcode.php');
		}
		
	}	
	return ob_get_clean();
}