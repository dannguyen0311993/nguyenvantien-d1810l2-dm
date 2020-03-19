<?php
/**
 * Plugin Name: Animated Live Wall
 * Description: The Animated Live Wall Gallery is a responsive animated gallery that helps to makes beutiful your WordPress site.
 * Version: 1.0.9
 * Author: A WP Life
 * Plugin URI: 
 * Author URI: https://www.awplife.com
 * License: GPLv2 or later
 * Text Domain: animated-live-wall
 * Domain Path: /languages
 **/
 
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Awl_Photo_Wall' ) ) {

	class Awl_Photo_Wall {		
		
		public function __construct() {
			$this->_constants();
			$this->_hooks();
		}		
		
		protected function _constants() {
			//Plugin Version
			define( 'ALW_PLUGIN_VER', '1.0.9' );
			
			//Plugin Text Domain
			define("ALW_TXTDM","animated-live-wall" );

			//Plugin Name
			define( 'ALW_PLUGIN_NAME', __( 'Animated Live Wall Premium', ALW_TXTDM ) );

			//Plugin Slug
			define( 'ALW_PLUGIN_SLUG', 'animated-live-wall' );

			//Plugin Directory Path
			define( 'ALW_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

			//Plugin Directory URL
			define( 'ALW_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

			define( 'ALW_SECURE_KEY', md5( NONCE_KEY ) );
			
		} // end of constructor function
		
		protected function _hooks() {
			
			//Load text domain
			add_action( 'plugins_loaded', array( $this, 'load_textdomain' ) );
			
			//add gallery menu item, change menu filter for multi-site
			add_action( 'admin_menu', array( $this, 'alw_menu' ), 101 );
			
			//Create Animated Live Wall Custom Post
			add_action( 'init', array( $this, 'Photo_Wall' ));
			
			//Add meta box to custom post
			add_action( 'add_meta_boxes', array( $this, 'admin_add_meta_box' ) );
			 
			//loaded during admin init 
			add_action( 'admin_init', array( $this, 'admin_add_meta_box' ) );
			
			add_action('wp_ajax_alw_gallery_js', array(&$this, '_ajax_alw_gallery'));
		
			add_action('save_post', array(&$this, '_alw_save_settings'));

			//Shortcode Compatibility in Text Widgets
			add_filter('widget_text', 'do_shortcode');
			
			add_image_size('custum_500x500', 500, 500, true );
			add_image_size('custum_800x800', 800, 800, true );

		} // end of hook function
		
		public function load_textdomain() {
			load_plugin_textdomain( ALW_TXTDM, false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
		}
		
		public function alw_menu() {
			$filter_menu = add_submenu_page( 'edit.php?post_type='.ALW_PLUGIN_SLUG, __( 'Filters', ALW_TXTDM ), __( 'Filters', ALW_TXTDM ), 'administrator', 'alw-filter-page', array( $this, 'awl_filter_page') );
			$doc_menu    = add_submenu_page( 'edit.php?post_type='.ALW_PLUGIN_SLUG, __( 'Docs', ALW_TXTDM ), __( 'Docs', ALW_TXTDM ), 'administrator', 'sr-doc-page', array( $this, 'alw_doc_page') );
		}
		
		public function Photo_Wall() {
			$labels = array(
				'name'                => _x( 'Animated Live Wall', ALW_TXTDM ),
				'singular_name'       => _x( 'Animated Live Wall', ALW_TXTDM ),
				'menu_name'           => __( 'Animated Live Wall', ALW_TXTDM ),
				'name_admin_bar'      => __( 'Portfolio Filter', ALW_TXTDM ),
				'parent_item_colon'   => __( 'Parent Item:', ALW_TXTDM ),
				'all_items'           => __( 'All Animated Live Wall', ALW_TXTDM ),
				'add_new_item'        => __( 'Add New', ALW_TXTDM ),
				'add_new'             => __( 'Add New', ALW_TXTDM ),
				'new_item'            => __( 'New Animated Live Wall', ALW_TXTDM ),
				'edit_item'           => __( 'Edit Animated Live Wall', ALW_TXTDM ),
				'update_item'         => __( 'Update Animated Live Wall', ALW_TXTDM ),
				'search_items'        => __( 'Search Animated Live Wall', ALW_TXTDM ),
				'not_found'           => __( 'Animated Live Wall Not found', ALW_TXTDM ),
				'not_found_in_trash'  => __( 'Animated Live Wall Not found in Trash', ALW_TXTDM ),
			);
			
			$args = array(
				'label'               => __( 'Animated Live Wall', ALW_TXTDM ),
				'description'         => __( 'Custom Post Type For Animated Live Wall', ALW_TXTDM ),
				'labels'              => $labels,
				'supports'            => array('title'),
				'taxonomies'          => array(),
				'hierarchical'        => false,
				'public'              => true,
				'show_ui'             => true,
				'show_in_menu'        => true,
				'menu_position'       => 65,
				'menu_icon'           => 'dashicons-layout',
				'show_in_admin_bar'   => true,
				'show_in_nav_menus'   => true,
				'can_export'          => true,
				'has_archive'         => true,		
				'exclude_from_search' => false,
				'publicly_queryable'  => true,
				'capability_type'     => 'page',
			);
			
			register_post_type( 'animated_live_wall', $args );
		} // end of post type function
		
		public function admin_add_meta_box() {
			add_meta_box( 'add-photo-wall', __('Add Animated Live Wall', ALW_TXTDM), array(&$this, 'ALW_Genrate_Gallery'), 'animated_live_wall', 'normal', 'default' );
			add_meta_box( 'alw-shortcode', __('Copy Shortcode', ALW_TXTDM), array(&$this, 'ALW_Shortcode'), 'animated_live_wall', 'side', 'default' );
		}
			
		public function ALW_Genrate_Gallery($post) {		
			//js
			wp_enqueue_script('jquery');
			wp_enqueue_script('media-upload');
			wp_enqueue_script('alw-bootstrap-js', ALW_PLUGIN_URL . 'assets/bootstrap/js/bootstrap.js', array('jquery'));
			wp_enqueue_script('alw-option-tab-js', ALW_PLUGIN_URL . 'assets/js/alw-option-tab.js', array('jquery'));
			wp_enqueue_script('alw-uploader-js', ALW_PLUGIN_URL . 'assets/js/alw-uploader.js', array('jquery'));
			
			//CSS
			wp_enqueue_style('alw-bootstrap-css', ALW_PLUGIN_URL . 'assets/css/bootstrap-min.css');
			wp_enqueue_style('alw-option-tab-css', ALW_PLUGIN_URL . 'assets/css/alw-option-tab.css');
			
			wp_enqueue_style('alw-uploader-css', ALW_PLUGIN_URL . 'assets/css/alw-uploader.css');
			wp_enqueue_media();			
			wp_enqueue_style( 'wp-color-picker' );			
			require_once('include/admin/animated-live-wall-setting.php');
		}
		
		public function ALW_Shortcode($post) { ?>
			<div class="pw-shortcode">
				<input type="text" name="shortcode" id="shortcode" value="<?php echo "[ALW ID=".$post->ID."]"; ?>" readonly style="height: 60px; text-align: center; font-size: 20px; width: 100%; border: 2px dotted;">
				<p id="pw-copt-code"><?php _e('Shortcode copied to clipboard!', ALW_TXTDM); ?></p>
				<p><?php _e('Copy & Embed shortcode into any Page/ Post / Text Widget to display your image gallery on site.', ALW_TXTDM); ?><br></p>
			</div>
			<span onclick="copyToClipboard('#shortcode')" class="pw-copy dashicons dashicons-clipboard"></span>
			<style>
			.pw-copy {
				position: absolute;
				top: 9px;
				right: 24px;
				font-size: 26px;
				cursor: pointer;
			}
			</style>
			<script>
			jQuery( "#pw-copt-code" ).hide();
			function copyToClipboard(element) {
			  var temp = jQuery("<input>");
			  jQuery("body").append(temp);
			  temp.val(jQuery(element).val()).select();
			  document.execCommand("copy");
			  temp.remove();
			  jQuery( "#shortcode" ).select();
			  jQuery( "#pw-copt-code" ).fadeIn();
			}
			</script>
			<?php			
		}// end of gallery generation
		
		public function _ig_ajax_callback_function($id) {
			//wp_get_attachment_image_src ( int $attachment_id, string|array $size = 'thumbnail', bool $icon = false );
			//thumb, thumbnail, medium, large, post-thumbnail
			$thumbnail = wp_get_attachment_image_src($id, 'medium', true);
			$attachment = get_post( $id ); // $id = attachment id
			?>
			
			<li class="item image col-lg-2 col-md-3 col-sm-6 col-xs-12">
				<img class="new-image" src="<?php echo $thumbnail[0]; ?>" alt="<?php echo get_the_title($id); ?>" style="height: 150px;">
				<div class="item-overlay bottom label label-info" style="opacity:0; position:absolute; color: #fff; background-color:#5bc0de; padding:2px;">ID-<?php echo $id; ?></div>
				<input type="hidden" id="image-ids[]" name="image-ids[]" value="<?php echo $id; ?>" />
				<input type="text" name="image-title[]" id="image-title[]" placeholder="Image Title" value="<?php echo get_the_title($id); ?>">
				<input type="text" name="image-link[]" id="image-link[]" placeholder="Video URL / Link URL"  value="<?php echo $image_link; ?>">
				<!--<a href="<?php //echo wp_get_attachment_url( get_post_thumbnail_id($post->ID) ); ?>" class="attachments pw-edit-icon" href="#"><span class="dashicons dashicons-edit"></span></a>-->
				<a class="pw-trash-icon" name="remove-image" id="remove-image" href="#"><span class="dashicons dashicons-trash"></span></a>
			</li>
			<?php
		}
		
		public function _ajax_alw_gallery() {
			echo $this->_ig_ajax_callback_function($_POST['imageId']);
			die;
		}
		
		public function _alw_save_settings($post_id) {
			if(isset($_POST['alw_save_nonce'])) {
				if ( !isset( $_POST['alw_save_nonce'] ) || !wp_verify_nonce( $_POST['alw_save_nonce'], 'alw_save_settings' ) ) {
				   print 'Sorry, your nonce did not verify.';
				   exit;
				} else {					
					
					//make sure things are set before using them
					$image_ids = isset( $_POST['image-ids'] ) ? (array) $_POST['image-ids'] : array();
					$image_titles = isset( $_POST['image-title'] ) ? (array) $_POST['image-title'] : array();
					
					// WordPress data sanitization function
					$image_ids = array_map( 'esc_attr', $image_ids );
					$image_titles = array_map( 'esc_attr', $image_titles );
					
					
					$i = 0;
					foreach($image_ids as $image_id) {
						$single_image_update = array(
							'ID'           => $image_id,
							'post_title'   => $image_titles[$i],						
							//'post_content'   => $image_desc[$i],						
						);
						wp_update_post( $single_image_update );
						$i++;
					}					
					$awl_animated_live_wall_shortcode_setting = "awl_animated_live_wall".$post_id;
					update_post_meta($post_id, $awl_animated_live_wall_shortcode_setting, $_POST);
				}
			}			
		}// end save setting		
	}
	$animated_live_wall_object = new Awl_Photo_Wall();		
	//Generate random number
	function random() { return (float)rand()/(float)getrandmax(); }
	require_once('shortcode.php');
}
?>