<?php
/* Elementor Builder support functions
------------------------------------------------------------------------------- */

// Theme init priorities:
// 9 - register other filters (for installer, etc.)
if (!function_exists('ostende_elm_theme_setup9')) {
	add_action( 'after_setup_theme', 'ostende_elm_theme_setup9', 9 );
	function ostende_elm_theme_setup9() {
		
		add_filter( 'ostende_filter_merge_styles',					'ostende_elm_merge_styles' );
		add_filter( 'ostende_filter_merge_styles_responsive', 		'ostende_elm_merge_styles_responsive');

		if (ostende_exists_elementor()) {
			add_action( 'init',										'ostende_elm_init_once', 3 );
			add_action( 'elementor/editor/before_enqueue_scripts',	'ostende_elm_editor_scripts');
			add_action( 'elementor/element/before_section_end',		'ostende_elm_add_color_scheme_control', 10, 3 );
			add_action( 'elementor/element/after_section_end',		'ostende_elm_add_page_options', 10, 3 );
		}
		if (is_admin()) {
			add_filter( 'ostende_filter_tgmpa_required_plugins',	'ostende_elm_tgmpa_required_plugins' );
		}
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'ostende_elm_tgmpa_required_plugins' ) ) {
	function ostende_elm_tgmpa_required_plugins($list=array()) {
		if (ostende_storage_isset('required_plugins', 'elementor')) {
			$list[] = array(
				'name' 		=> ostende_storage_get_array('required_plugins', 'elementor'),
				'slug' 		=> 'elementor',
				'required' 	=> false
			);
		}
		return $list;
	}
}

// Check if Elementor is installed and activated
if ( !function_exists( 'ostende_exists_elementor' ) ) {
	function ostende_exists_elementor() {
		return class_exists('Elementor\Plugin');
	}
}
	
// Merge custom styles
if ( !function_exists( 'ostende_elm_merge_styles' ) ) {
	function ostende_elm_merge_styles($list) {
		if (ostende_exists_elementor()) {
			$list[] = 'plugins/elementor/_elementor.scss';
		}
		return $list;
	}
}

// Merge responsive styles
if ( !function_exists( 'ostende_elm_merge_styles_responsive' ) ) {
	function ostende_elm_merge_styles_responsive($list) {
		if (ostende_exists_elementor()) {
			$list[] = 'plugins/elementor/_elementor-responsive.scss';
		}
		return $list;
	}
}


// Load required styles and scripts for Elementor Editor mode
if ( !function_exists( 'ostende_elm_editor_scripts' ) ) {
	function ostende_elm_editor_scripts() {
		// Load font icons
		wp_enqueue_style(  'ostende-icons', ostende_get_file_url('css/font-icons/css/fontello-embedded.css'), array(), null );
	}
}

// Add theme-specific controls to sections and columns
if (!function_exists('ostende_elm_add_color_scheme_control')) {
	function ostende_elm_add_color_scheme_control($element, $section_id, $args) {
		if ( is_object($element) ) {
			$el_name = $element->get_name();
			// Add color scheme selector
			if (apply_filters('ostende_filter_add_scheme_in_elements', 
							  (in_array($el_name, array('section', 'column')) && $section_id === 'section_advanced')
							  || ($el_name === 'common' && $section_id === '_section_style'),
							  $element, $section_id, $args)) {
				$element->add_control('scheme', array(
						'type' => \Elementor\Controls_Manager::SELECT,
						'label' => esc_html__("Color scheme", 'ostende'),
						'label_block' => true,
						'options' => ostende_array_merge(array('' => esc_html__('Inherit', 'ostende')), ostende_get_list_schemes()),
						'default' => '',
						'prefix_class' => 'scheme_'
						) );
			}
			// Add 'Override section width'
			if ($el_name == 'section' && $section_id === 'section_advanced') {
				$element->add_control('justify_columns', array(
						'type' => \Elementor\Controls_Manager::SWITCHER,
						'label' => esc_html__('Justify columns', 'ostende'),
						'description' => wp_kses_data( __("Stretch columns to align the left and right edges to the site content area", 'ostende') ),
						'label_off' => esc_html__( 'Off', 'ostende' ),
						'label_on' => esc_html__( 'On', 'ostende' ),
						'return_value' => 'justified',
						'prefix_class' => 'elementor-section-'
						) );
			}
			// Set default gap between columns to 'Extended'
			if ($el_name == 'section' && $section_id === 'section_layout') {
				$element->update_control('gap', array(
						'default' => 'extended'
						) );
			}
		}
	}
}


// Add tab with theme-specific Page Options to the Page Settings
//---------------------------------------------------------------
if (!function_exists('ostende_elm_add_page_options')) {
	function ostende_elm_add_page_options($element, $section_id, $args) {
		if ( is_object($element) ) {
			$el_name = $element->get_name();
			if ($el_name == 'page-settings' && $section_id == 'section_page_style') {
				$post_id = get_the_ID();
				$post_type = get_post_type($post_id);
				if ($post_id > 0 && ostende_allow_override_options($post_type)) {
					// Load saved options 
					$meta = get_post_meta($post_id, 'ostende_options', true);
					$sections = array();
					global $OSTENDE_STORAGE;
					// Refresh linked data if this field is controller for the another (linked) field
					// Do this before show fields to refresh data in the $OSTENDE_STORAGE
					foreach ($OSTENDE_STORAGE['options'] as $k=>$v) {
						if (!isset($v['override']) || strpos($v['override']['mode'], $post_type)===false) continue;
						if (!empty($v['linked'])) {
							$v['val'] = isset($meta[$k]) ? $meta[$k] : 'inherit';
							if (!empty($v['val']) && !ostende_is_inherit($v['val']))
								ostende_refresh_linked_data($v['val'], $v['linked']);
						}
					}
					// Collect fields to the tabs
					foreach ($OSTENDE_STORAGE['options'] as $k=>$v) {
						if (!isset($v['override']) || strpos($v['override']['mode'], $post_type)===false || $v['type'] == 'hidden') continue;
						$sec = empty($v['override']['section']) ? esc_html__('General', 'ostende') : $v['override']['section'];
						if (!isset($sections[$sec])) {
							$sections[$sec] = array();
						}
						$v['val'] = isset($meta[$k]) ? $meta[$k] : 'inherit';
						$sections[$sec][$k] = $v;
					}
					if (count($sections) > 0) {
						$cnt = 0;
						foreach ($sections as $sec => $v) {
							$cnt++;
							$element->start_controls_section(
								"section_theme_options_{$cnt}",
								[
									'label' => $sec,
									'tab' => \Elementor\Controls_Manager::TAB_LAYOUT 
								]
							);
							foreach ($v as $field_id => $params) {
								ostende_elm_add_page_options_field($element, $field_id, $params);
							}
							$element->end_controls_section();
						}
					}
				}
			}
		}
	}
}


// Add control for the specified field
if (!function_exists('ostende_elm_add_page_options_field')) {
	function ostende_elm_add_page_options_field($element, $id, $field) {
		// If fields is inherit
		$inherit_state = isset($field['val']) && ostende_is_inherit($field['val']);
		// Inherit param
		$element->add_control( "{$id}_inherit", [
			'label' => $field['title'],
			'label_block' => in_array($field['type'], array('media')),
			'description' => !empty($field['override']['desc']) ? $field['override']['desc'] : (!empty($field['desc']) ? $field['desc'] : ''),
			'separator' => 'before',
			'default' => $inherit_state ? '' : '1',
			'type' => \Elementor\Controls_Manager::SWITCHER,
			'label_off' => esc_html__( 'Inherit', 'ostende' ),
			'label_on' => esc_html__( 'Override', 'ostende' ),
			'return_value' => '1'
		] );

		// Field params
		$params = array(
				'label' => esc_html__('New value', 'ostende'),
				'label_block' => in_array($field['type'], array('media')),
				'default' => $field['val']
			);
		// Add dependency to params
		$params['condition'] = array(
			"{$id}_inherit" => '1'
		);
		if (!empty($field['dependency'])) {
			foreach ($field['dependency'] as $k => $v) {
				if (substr($k, 0, 1) == '#') $k = substr($k, 1);
				$params['condition'][$k] = $v;
			}
		}
		// Type 'checkbox'
		if ($field['type'] == 'checkbox') {
			$params = array_merge($params, [
					'type' => \Elementor\Controls_Manager::SWITCHER,
					'label_off' => esc_html__( 'Off', 'ostende' ),
					'label_on' => esc_html__( 'On', 'ostende' ),
					'return_value' => '1'
					]);
			$element->add_control( $id, $params );

		// Type 'switch' (2 choises) or 'radio' (3+ choises) or 'select'
		} else if (in_array($field['type'], array('switch', 'radio', 'select'))) {
			$field['options'] = apply_filters('ostende_filter_options_get_list_choises', $field['options'], $id);
			$params = array_merge($params, [
					'type' => \Elementor\Controls_Manager::SELECT,
					'options' => $field['options']
					]);
			$element->add_control( $id, $params );

		// Type 'checklist' and 'select2'
		} else if (in_array($field['type'], array('checklist', 'select2'))) {
			$field['options'] = apply_filters('ostende_filter_options_get_list_choises', $field['options'], $id);
			$params = array_merge($params, [
					'type' => \Elementor\Controls_Manager::SELECT2,
					'options' => $field['options'],
					'multiple' => $field['type']=='checklist' || !empty($field['multiple'])
					]);
			$element->add_control( $id, $params );


		// Type 'text' or 'time'
		} else if (in_array($field['type'], array('text', 'time'))) {
			$params = array_merge($params, [
					'type' => \Elementor\Controls_Manager::TEXT
					]);
			$element->add_control( $id, $params );

		// Type 'date'
		} else if ($field['type'] == 'date') {
			$params = array_merge($params, [
					'type' => \Elementor\Controls_Manager::DATE_TIME
					]);
			$element->add_control( $id, $params );

		// Type 'textarea'
		} else if ($field['type'] == 'textarea') {
			$params = array_merge($params, [
					'type' => \Elementor\Controls_Manager::TEXTAREA,
					'rows' => !empty($field['rows']) ? max(1, $field['rows']) : 5
					]);
			$element->add_control( $id, $params );

		// Type 'text_editor'
		} else if ($field['type'] == 'text_editor') {
			$params = array_merge($params, [
					'type' => \Elementor\Controls_Manager::WYSIWYG
					]);
			$element->add_control( $id, $params );

		// Type 'media'
		} else if (in_array($field['type'], array('image', 'media', 'video', 'audio'))) {
			$params = array_merge($params, [
					'type' => \Elementor\Controls_Manager::MEDIA,
					'default' => [
						'url' => !empty($field['val']) && !ostende_is_inherit($field['val']) ? $field['val'] : ''
						]
					]);
			$element->add_control( $id, $params );

		// Type 'color'
		} else if ($field['type'] == 'color') {
			$params = array_merge($params, [
					'type' => \Elementor\Controls_Manager::COLOR,
					'scheme' => [
						'type' => \Elementor\Scheme_Color::get_type(),
						'value' => \Elementor\Scheme_Color::COLOR_1,
						]
					]);
			$element->add_control( $id, $params );

		// Type 'slider' or 'range'
		} else if (in_array($field['type'], array('slider', 'range'))) {
			$params = array_merge($params, [
					'type' => \Elementor\Controls_Manager::SLIDER,
					'default' => [
						'size' => !empty($field['val']) && !ostende_is_inherit($field['val']) ? $field['val'] : '',
						'unit' => 'px'
					],
					'range' => [
						'px' => [
							'min' => !empty($field['min']) ? $field['min'] : 0,
							'max' => !empty($field['max']) ? $field['max'] : 1000
						]
					]
					]);
			$element->add_control( $id, $params );

		}
	}
}


// Add plugin-specific colors and fonts to the custom CSS
if (ostende_exists_elementor()) { require_once OSTENDE_THEME_DIR . 'plugins/elementor/elementor-styles.php'; }
?>