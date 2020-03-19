<?php
/**
 * Theme Options and override-options support
 *
 
 * @subpackage OSTENDE
 * @since OSTENDE 1.0.29
 */


// -----------------------------------------------------------------
// -- Override-options
// -----------------------------------------------------------------

if ( !function_exists('ostende_init_override_options') ) {
	add_action( 'after_setup_theme', 'ostende_init_override_options' );
	function ostende_init_override_options() {
		if ( is_admin() ) {
			add_action('admin_enqueue_scripts',	'ostende_add_override_options_scripts');
			add_action('save_post',				'ostende_save_override_options');
			add_filter('trx_addons_filter_override_options',		'ostende_add_override_options');
		}
	}
}
	
// Load required styles and scripts for admin mode
if ( !function_exists( 'ostende_add_override_options_scripts' ) ) {
	function ostende_add_override_options_scripts() {
		// If current screen is 'Edit Page' - load font icons
		$screen = function_exists('get_current_screen') ? get_current_screen() : false;
		if (is_object($screen) && ostende_allow_override_options(!empty($screen->post_type) ? $screen->post_type : $screen->id)) {
			wp_enqueue_style( 'ostende-icons',  ostende_get_file_url('css/font-icons/css/fontello-embedded.css'), array(), null );
			wp_enqueue_script( 'jquery-ui-tabs', false, array('jquery', 'jquery-ui-core'), null, true );
			wp_enqueue_script( 'jquery-ui-accordion', false, array('jquery', 'jquery-ui-core'), null, true );
			wp_enqueue_script( 'ostende-options', ostende_get_file_url('theme-options/theme-options.js'), array('jquery'), null, true );
			wp_localize_script( 'ostende-options', 'ostende_dependencies', ostende_get_theme_dependencies() );
		}
	}
}


// Check if override options is allow
if (!function_exists('ostende_allow_override_options')) {
	function ostende_allow_override_options($post_type) {
		return apply_filters('ostende_filter_allow_override_options', in_array($post_type, array('page', 'post')), $post_type);
	}
}

// Add override options
if (!function_exists('ostende_add_override_options')) {
	function ostende_add_override_options($boxes = array()) {
		global $post_type;
		if (ostende_allow_override_options($post_type)) {
			$boxes[] = array('id' => sprintf('ostende_override_options_%s', $post_type),
				'title' =>  esc_html__('Theme Options', 'ostende'),
				'callback' => 'ostende_show_override_options',
				'page' => $post_type,
				'context' => 'advanced',
				'priority' => 'default'
			);
		}
		return $boxes;
	}
}

// Callback function to show fields in override options
if (!function_exists('ostende_show_override_options')) {
	function ostende_show_override_options($post=false, $args=false) {
		if (empty($post) || !is_object($post) || empty($post->ID)) {
			global $post, $post_type;
			$mb_post_id = $post->ID;
			$mb_post_type = $post_type;
		} else {
			$mb_post_id = $post->ID;
			$mb_post_type = $post->post_type;
		}
		if (ostende_allow_override_options($mb_post_type)) {
			// Load saved options 
			$meta = get_post_meta($mb_post_id, 'ostende_options', true);
			$tabs_titles = $tabs_content = array();
			global $OSTENDE_STORAGE;
			// Refresh linked data if this field is controller for the another (linked) field
			// Do this before show fields to refresh data in the $OSTENDE_STORAGE
			foreach ($OSTENDE_STORAGE['options'] as $k=>$v) {
				if (!isset($v['override']) || strpos($v['override']['mode'], $mb_post_type)===false) continue;
				if (!empty($v['linked'])) {
					$v['val'] = isset($meta[$k]) ? $meta[$k] : 'inherit';
					if (!empty($v['val']) && !ostende_is_inherit($v['val']))
						ostende_refresh_linked_data($v['val'], $v['linked']);
				}
			}
			// Show fields
			foreach ($OSTENDE_STORAGE['options'] as $k=>$v) {
				if (!isset($v['override']) || strpos($v['override']['mode'], $mb_post_type)===false || $v['type'] == 'hidden') continue;
				if (empty($v['override']['section']))
					$v['override']['section'] = esc_html__('General', 'ostende');
				if (!isset($tabs_titles[$v['override']['section']])) {
					$tabs_titles[$v['override']['section']] = $v['override']['section'];
					$tabs_content[$v['override']['section']] = '';
				}
				$v['val'] = isset($meta[$k]) ? $meta[$k] : 'inherit';
				$tabs_content[$v['override']['section']] .= ostende_options_show_field($k, $v, $mb_post_type);
			}
			if (count($tabs_titles) > 0) {
				?>
				<div class="ostende_options ostende_override_options">
					<input type="hidden" name="override_options_post_nonce" value="<?php echo esc_attr(wp_create_nonce(admin_url())); ?>" />
					<input type="hidden" name="override_options_post_type" value="<?php echo esc_attr($mb_post_type); ?>" />
					<div id="ostende_options_tabs" class="ostende_tabs">
						<ul><?php
							$cnt = 0;
							foreach ($tabs_titles as $k=>$v) {
								$cnt++;
								?><li><a href="#ostende_options_<?php echo esc_attr($cnt); ?>"><?php echo esc_html($v); ?></a></li><?php
							}
						?></ul>
						<?php
							$cnt = 0;
							foreach ($tabs_content as $k=>$v) {
								$cnt++;
								?>
								<div id="ostende_options_<?php echo esc_attr($cnt); ?>" class="ostende_tabs_section ostende_options_section">
									<?php ostende_show_layout($v); ?>
								</div>
								<?php
							}
						?>
					</div>
				</div>
				<?php		
			}
		}
	}
}


// Save data from override options
if (!function_exists('ostende_save_override_options')) {
	function ostende_save_override_options($post_id) {

		// verify nonce
		if ( !wp_verify_nonce( ostende_get_value_gp('override_options_post_nonce'), admin_url() ) )
			return $post_id;

		// check autosave
		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
			return $post_id;
		}

		$post_type = wp_kses_data(wp_unslash(isset($_POST['override_options_post_type']) ? sanitize_text_field($_POST['override_options_post_type']) : sanitize_text_field($_POST['post_type'])));

		// check permissions
		$capability = 'page';
		$post_types = get_post_types( array( 'name' => $post_type), 'objects' );
		if (!empty($post_types) && is_array($post_types)) {
			foreach ($post_types  as $type) {
				$capability = $type->capability_type;
				break;
			}
		}
		if (!current_user_can('edit_'.($capability), $post_id)) {
			return $post_id;
		}

		// Save meta
		$meta = array();
		$options = ostende_storage_get('options');
		foreach ($options as $k=>$v) {
			// Skip not overriden options
			if (!isset($v['override']) || strpos($v['override']['mode'], $post_type)===false) continue;
			// Skip inherited options
			if (!empty($_POST['ostende_options_inherit_' . $k])) continue;
			// Get option value from POST
			$meta[$k] = sanitize_text_field(isset($_POST['ostende_options_field_' . $k]))
							? ostende_get_value_gp('ostende_options_field_' . $k)
							: ($v['type']=='checkbox' ? 0 : '');
		}
		update_post_meta($post_id, 'ostende_options', $meta);
		
		// Save separate meta options to search template pages
		if ($post_type=='page' && !empty($_POST['page_template']) && $_POST['page_template']=='blog.php') {
			update_post_meta($post_id, 'ostende_options_post_type', isset($meta['post_type']) ? $meta['post_type'] : 'post');
			update_post_meta($post_id, 'ostende_options_parent_cat', isset($meta['parent_cat']) ? $meta['parent_cat'] : 0);
		}
	}
}
?>