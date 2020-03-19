<?php
/* Custom Feeds for Instagram support functions
------------------------------------------------------------------------------- */

// Theme init priorities:
// 9 - register other filters (for installer, etc.)
if (!function_exists('ostende_custom_twitter_feeds_feed_theme_setup9')) {
	add_action( 'after_setup_theme', 'ostende_custom_twitter_feeds_feed_theme_setup9', 9 );
	function ostende_custom_twitter_feeds_feed_theme_setup9() {
		
		if (is_admin()) {
			add_filter( 'ostende_filter_tgmpa_required_plugins',	'ostende_custom_twitter_feeds_feed_tgmpa_required_plugins' );
		}
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'ostende_custom_twitter_feeds_feed_tgmpa_required_plugins' ) ) {
	function ostende_custom_twitter_feeds_feed_tgmpa_required_plugins($list=array()) {
		if (ostende_storage_isset('required_plugins', 'custom-twitter-feeds')) {
			$list[] = array(
					'name' 		=> ostende_storage_get_array('required_plugins', 'custom-twitter-feeds'),
					'slug' 		=> 'custom-twitter-feeds',
					'required' 	=> false
				);
		}
		return $list;
	}
}

// Check if Custom Feeds for Instagram installed and activated
if ( !function_exists( 'ostende_exists_custom_twitter_feeds_feed' ) ) {
	function ostende_exists_custom_twitter_feeds_feed() {
		return defined('CTF_VERSION');
	}
}
?>