<?php

if (!defined('ABSPATH'))
{
	die();
}

class google_business_reviews_rating
{
	public
		$id = NULL;
	
	private
		$dashboard = NULL,
		$data = array(),
		$result = array(),
		$result_valid = array(),
		$reviews = array(),
		$reviews_filtered = array(),
		$relative_times = array(),
		$languages = array(),
		$reviews_themes = array(),
		$review_sort_option = NULL,
		$review_sort_options = array(),
		$default_html_tags = array(),
		$updates = array(),
		$business_types = array(),
		$price_ranges = array(),
		$request_count = NULL,
		$retrieved_data_valid = NULL,
		$retrieved_data_exists = NULL,
		$place_id = NULL,
		$api_key = NULL,
		$section = NULL,
		$show_reviews = FALSE,
		$count_reviews_all = NULL,
		$count_reviews_active = NULL,
		$icon_image_id = NULL,
		$icon_image_url = NULL,
		$logo_image_id = NULL,
		$logo_image_url = NULL,
		$demo = FALSE;
	
	public function __construct()
	{
		// Class contructor that starts everything

		$this->id = (isset($_REQUEST['id'])) ? intval($_REQUEST['id']) : NULL;
		$this->dashboard = (is_admin() || defined('DOING_CRON'));
		$this->request_count = 0;
		$this->retrieved_data_valid = FALSE;

		$this->review_sort_options = array(
			'relevance_desc' => array(
				'name' => __('Relevance Descending', 'g-business-reviews-rating'),
				'min_max_values' => array(__('High', 'g-business-reviews-rating'), __('Low', 'g-business-reviews-rating')),
				'field' => NULL,
				'asc' => FALSE
			),
			'relevance_asc' => array(
				'name' => __('Relevance Ascending', 'g-business-reviews-rating'),
				'min_max_values' => array(__('Low', 'g-business-reviews-rating'), __('High', 'g-business-reviews-rating')),
				'field' => NULL,
				'asc' => TRUE
			),
			'date_desc' => array(
				'name' => __('Date Descending', 'g-business-reviews-rating'),
				'min_max_values' => array(__('New', 'g-business-reviews-rating'), __('Old', 'g-business-reviews-rating')),
				'field' => 'time',
				'asc' => FALSE
			),
			'date_asc' => array(
				'name' => __('Date Ascending', 'g-business-reviews-rating'),
				'min_max_values' => array(__('Old', 'g-business-reviews-rating'), __('New', 'g-business-reviews-rating')),
				'field' => 'time',
				'asc' => TRUE
			),
			'rating_desc' => array(
				'name' => __('Rating Descending', 'g-business-reviews-rating'),
				'min_max_values' => array(__('High', 'g-business-reviews-rating'), __('Low', 'g-business-reviews-rating')),
				'field' => 'rating',
				'asc' => FALSE
			),
			'rating_asc' => array(
				'name' => __('Rating Ascending', 'g-business-reviews-rating'),
				'min_max_values' => array(__('Low', 'g-business-reviews-rating'), __('High', 'g-business-reviews-rating')),
				'field' => 'rating',
				'asc' => TRUE
			),
			'author_name_asc' => array(
				'name' => __('Author’s Name Ascending', 'g-business-reviews-rating'),
				'min_max_values' => array('A', 'Z'),
				'field' => 'author_name',
				'asc' => TRUE
			),
			'author_name_desc' => array(
				'name' => __('Author’s Name Descending', 'g-business-reviews-rating'),
				'min_max_values' => array('Z', 'A'),
				'field' => 'author_name',
				'asc' => FALSE
			),
			'id_asc' => array(
				'name' => __('ID Ascending', 'g-business-reviews-rating'),
				'min_max_values' => array(__('Low', 'g-business-reviews-rating'), __('High', 'g-business-reviews-rating')),
				'field' => 'id',
				'asc' => TRUE
			),
			'id_desc' => array(
				'name' => __('ID Descending', 'g-business-reviews-rating'),
				'min_max_values' => array(__('High', 'g-business-reviews-rating'), __('Low', 'g-business-reviews-rating')),
				'field' => 'id',
				'asc' => FALSE
			),
			'shuffle' => array(
				'name' => __('Random Shuffle', 'g-business-reviews-rating')
			)
		);
		
		$this->default_html_tags = array('h2', 'p', 'p', 'ul', 'li', 'p', 'p', 'p');

		$this->admin_init();
		$this->wp_init();
		return TRUE;
	}
	
	public static function activate()
	{
		// Activate plugin
		
		global $wpdb;
		
		if (!current_user_can('activate_plugins'))
		{
			return;
		}
		
		if (is_bool(get_option(__CLASS__ . '_place_id')))
		{
			$plugin_data = (function_exists('get_file_data')) ? get_file_data(plugin_dir_path(__FILE__) . 'g-business-reviews-rating.php', array('Version' => 'Version'), FALSE) : array();
			$version = (array_key_exists('Version', $plugin_data)) ? $plugin_data['Version'] : NULL;
	
			update_option(__CLASS__ . '_initial_version', $version, 'no');
			update_option(__CLASS__ . '_place_id', NULL, 'no');
			update_option(__CLASS__ . '_api_key', NULL, 'no');
			update_option(__CLASS__ . '_language', NULL, 'no');
			update_option(__CLASS__ . '_demo', FALSE, 'yes');
			update_option(__CLASS__ . '_update', 24, 'no');
			update_option(__CLASS__ . '_review_limit', NULL, 'yes');
			update_option(__CLASS__ . '_review_sort', NULL, 'yes');
			update_option(__CLASS__ . '_rating_min', NULL, 'yes');
			update_option(__CLASS__ . '_rating_max', NULL, 'yes');
			update_option(__CLASS__ . '_review_text_min', NULL, 'yes');
			update_option(__CLASS__ . '_review_text_max', NULL, 'yes');
			update_option(__CLASS__ . '_review_text_excerpt_length', 235, 'yes');
			update_option(__CLASS__ . '_reviews_theme', NULL, 'yes');
			update_option(__CLASS__ . '_stylesheet', TRUE, 'yes');
			update_option(__CLASS__ . '_icon', NULL, 'no');
			update_option(__CLASS__ . '_logo', NULL, 'no');
			update_option(__CLASS__ . '_telephone', NULL, 'no');
			update_option(__CLASS__ . '_business_type', NULL, 'no');
			update_option(__CLASS__ . '_price_range', NULL, 'no');
			update_option(__CLASS__ . '_places', NULL, 'yes');
			update_option(__CLASS__ . '_structured_data', FALSE, 'yes');
			update_option(__CLASS__ . '_retrieval', NULL, 'no');
			update_option(__CLASS__ . '_result', NULL, 'no');
			update_option(__CLASS__ . '_result_valid', NULL, 'no');
			update_option(__CLASS__ . '_reviews', NULL, 'no');
			update_option(__CLASS__ . '_section', NULL, 'no');
			update_option(__CLASS__ . '_custom_styles', NULL, 'yes');
			update_option(__CLASS__ . '_additional_array_sanitization', FALSE, 'yes');
			update_option(__CLASS__ . '_related_plugins', NULL, 'yes');
		}
		
		if (!wp_next_scheduled(__CLASS__ . '_sync'))
		{
			require_once(plugin_dir_path(__FILE__) . 'cron.php');
		}
		
		return TRUE;
	}
	
	public static function deactivate()
	{
		// Deactivate the plugin
		
		if (!current_user_can('activate_plugins'))
		{
			return;
		}
		
		wp_cache_delete('structured_data', __CLASS__);
		wp_cache_delete('result', __CLASS__);
		wp_cache_delete('result_valid', __CLASS__);
		wp_cache_delete('result_demo', __CLASS__);
		wp_cache_delete('reviews', __CLASS__);
		wp_cache_delete('reviews_demo', __CLASS__);
		
		update_option(__CLASS__ . '_result', NULL, 'no');
		update_option(__CLASS__ . '_result_valid', NULL, 'no');

		require_once(plugin_dir_path(__FILE__) . 'cron.php');
		
		$cron = new google_business_reviews_rating_cron();
		$cron->deactivate();
		
		return TRUE;
	}
	
	public static function uninstall()
	{
		// Uninstall plugin

		if (!current_user_can('activate_plugins', __CLASS__))
		{
			return;
		}

		require_once(plugin_dir_path(__FILE__) . 'cron.php');
		
		$cron = new google_business_reviews_rating_cron();
		$cron->deactivate();
		
		delete_option('widget_' . __CLASS__);
		delete_option(__CLASS__ . '_initial_version');
		delete_option(__CLASS__ . '_place_id');
		delete_option(__CLASS__ . '_api_key');
		delete_option(__CLASS__ . '_language');
		delete_option(__CLASS__ . '_demo');
		delete_option(__CLASS__ . '_update');
		delete_option(__CLASS__ . '_review_limit');
		delete_option(__CLASS__ . '_review_sort');
		delete_option(__CLASS__ . '_rating_min');
		delete_option(__CLASS__ . '_rating_max');
		delete_option(__CLASS__ . '_review_text_min');
		delete_option(__CLASS__ . '_review_text_max');
		delete_option(__CLASS__ . '_review_text_excerpt_length');
		delete_option(__CLASS__ . '_reviews_theme');
		delete_option(__CLASS__ . '_stylesheet');
		delete_option(__CLASS__ . '_icon');
		delete_option(__CLASS__ . '_logo');
		delete_option(__CLASS__ . '_telephone');
		delete_option(__CLASS__ . '_business_type');
		delete_option(__CLASS__ . '_places');
		delete_option(__CLASS__ . '_price_range');
		delete_option(__CLASS__ . '_structured_data');
		delete_option(__CLASS__ . '_settings');
		delete_option(__CLASS__ . '_retrieval');
		delete_option(__CLASS__ . '_result');
		delete_option(__CLASS__ . '_result_valid');
		delete_option(__CLASS__ . '_reviews');
		delete_option(__CLASS__ . '_custom_styles');
		delete_option(__CLASS__ . '_additional_array_sanitization');
		delete_option(__CLASS__ . '_related_plugins');
		delete_option(__CLASS__ . '_section');

		return TRUE;
	}
	
	public static function upgrade($object, $options)
	{
		// Upgrade plugin
		
		if (!isset($options['action']) || isset($options['action']) && $options['action'] != 'update' || !isset($options['type']) || isset($options['type']) && $options['type'] != 'plugin' || !isset($options['plugins']) || isset($options['plugins']) && !is_array($options['plugins']))
		{
			return TRUE;
		}
		
		$current_path = plugin_basename(__FILE__);
		
		foreach ($options['plugins'] as $path)
		{
			if ($current_path != $path)
			{
				continue;
			}

			global $wpdb;
			
			wp_cache_delete('structured_data', __CLASS__);
			wp_cache_delete('result', __CLASS__);
			wp_cache_delete('result_valid', __CLASS__);
			wp_cache_delete('result_demo', __CLASS__);
			wp_cache_delete('reviews', __CLASS__);
			wp_cache_delete('reviews_demo', __CLASS__);
			
			update_option(__CLASS__ . '_result', NULL, 'no');
			
			return TRUE;
		}
		
		return TRUE;
	}
	
	private function reset()
	{
		// Reset the plugin to a fresh installation
		
		if (!current_user_can('activate_plugins'))
		{
			return FALSE;
		}
		
		$this->data = array();
		$this->result = array();
		$this->reviews = array();
		$this->reviews_filtered = array();
		
		if (!self::deactivate())
		{
			return FALSE;
		}
		
		if (!self::uninstall())
		{
			return FALSE;
		}
		
		return self::activate();
	}

	public function admin_init()
	{
		// Initiate the plugin in the dashboard
		
		$this->demo = get_option(__CLASS__ . '_demo');

		register_setting(__CLASS__ . '_settings', __CLASS__ . '_additional_array_sanitization', array('type' => 'boolean'));
		register_setting(__CLASS__ . '_settings', __CLASS__ . '_api_key', array('type' => 'string', 'sanitize_callback' => array($this, 'sanitize_api_key')));
		register_setting(__CLASS__ . '_settings', __CLASS__ . '_place_id', array('type' => 'string', 'sanitize_callback' => array($this, 'sanitize_place_id')));
		register_setting(__CLASS__ . '_settings', __CLASS__ . '_language', array('type' => 'string'));
		register_setting(__CLASS__ . '_settings', __CLASS__ . '_demo', array('type' => 'boolean', 'sanitize_callback' => array($this, 'sanitize_demo')));
		register_setting(__CLASS__ . '_settings', __CLASS__ . '_update', array('type' => 'string'));
		register_setting(__CLASS__ . '_settings', __CLASS__ . '_review_limit', array('type' => 'string'));
		register_setting(__CLASS__ . '_settings', __CLASS__ . '_review_sort', array('type' => 'string'));
		register_setting(__CLASS__ . '_settings', __CLASS__ . '_rating_min', array('type' => 'string'));
		register_setting(__CLASS__ . '_settings', __CLASS__ . '_rating_max', array('type' => 'string'));
		register_setting(__CLASS__ . '_settings', __CLASS__ . '_review_text_min', array('type' => 'string'));
		register_setting(__CLASS__ . '_settings', __CLASS__ . '_review_text_max', array('type' => 'string'));
		register_setting(__CLASS__ . '_settings', __CLASS__ . '_review_text_excerpt_length', array('type' => 'string'));
		register_setting(__CLASS__ . '_settings', __CLASS__ . '_reviews_theme', array('type' => 'string'));
		register_setting(__CLASS__ . '_settings', __CLASS__ . '_stylesheet', array('type' => 'boolean'));
		register_setting(__CLASS__ . '_settings', __CLASS__ . '_icon', array('type' => 'string'));
		register_setting(__CLASS__ . '_settings', __CLASS__ . '_logo', array('type' => 'string'));
		register_setting(__CLASS__ . '_settings', __CLASS__ . '_telephone', array('type' => 'string'));
		register_setting(__CLASS__ . '_settings', __CLASS__ . '_business_type', array('type' => 'string'));
		register_setting(__CLASS__ . '_settings', __CLASS__ . '_price_range', array('type' => 'string'));
		register_setting(__CLASS__ . '_settings', __CLASS__ . '_structured_data', array('type' => 'boolean'));
		
		add_action('admin_menu', array($this, 'admin_menu'));
		add_action('admin_enqueue_scripts', array($this, 'admin_css_load'));
		add_action('admin_enqueue_scripts', array($this, 'admin_js_load'));
		add_action('wp_ajax_'.__CLASS__.'_admin_ajax', array($this, 'admin_ajax'));
		add_action('admin_notices', array($this, 'admin_notices'));
		add_action('widgets_init', array($this, 'widget'));
		add_action('plugins_loaded', array($this, 'loaded'));		

		add_filter('upload_mimes', array(__CLASS__, 'admin_uploads_file_types'), 10, 2);
		add_filter('plugin_action_links', array(__CLASS__, 'admin_add_action_links'), 10, 5);
		add_filter('plugin_row_meta', array(__CLASS__, 'admin_add_plugin_meta'), 10, 2);
		
		$this->set_data();
		$this->set_reviews();
		$this->set_icon();
		$this->set_logo();
		
		return TRUE;
	}
	
	public function wp_init()
	{
		// Initiate the plugin in the front-end
		
		$this->demo = get_option(__CLASS__ . '_demo');

		add_shortcode(__CLASS__, array($this, 'wp_display'));
		add_shortcode('reviews_rating', array($this, 'wp_display'));
		add_shortcode('reviews_rating_links', array($this, 'wp_display'));
		add_shortcode('reviews_rating_link', array($this, 'wp_display'));
		add_shortcode('links_google_business', array($this, 'wp_display'));
		add_shortcode('link_google_business', array($this, 'wp_display'));
		
		if (get_option(__CLASS__ . '_stylesheet'))
		{
			add_action('wp_enqueue_scripts', array($this, 'wp_css_load'));
		}
		
		add_action('wp_enqueue_scripts', array($this, 'wp_js_load'));
		
		if (get_option(__CLASS__ . '_structured_data'))
		{
			add_action('wp_head', array($this, 'structured_data'));
		}

		add_action('plugins_loaded', array($this, 'loaded'));		

		return TRUE;
	}
	
	public function admin_menu()
	{
		// Set the menu item
		
		if (!current_user_can('manage_options', __CLASS__))
		{
			return TRUE;
		}
		
		$pages = array(array('add_options_page', __('Reviews and Rating - Google Business', 'g-business-reviews-rating'), __('Reviews and Rating - Google Business', 'g-business-reviews-rating'), 'manage_options', __CLASS__ . '_settings', array($this, 'admin_settings')));
		
		foreach ($pages as $p)
		{
			if ($p[0] == 'add_menu_page' || $p[0] == 'add_options_page')
			{
				$function = $p[0];
			}
			else
			{
				$function = 'add_submenu_page';
			}
			array_shift($p);
			call_user_func_array($function, $p);
			continue;
		}
		return TRUE;
	}
	
	public function sync()
	{
		// Handle synchronization from CRON job
		
		if (!defined('DOING_CRON') || defined('DOING_CRON') && !DOING_CRON)
		{
			return FALSE;
		}
		
		global $wpdb;
		
		if (get_option(__CLASS__ . '_place_id') == NULL || get_option(__CLASS__ . '_api_key') == NULL || !is_numeric(get_option(__CLASS__ . '_update')))
		{
			return FALSE;
		}
		
		if (get_option(__CLASS__ . '_update') == 24 && (function_exists('wp_date') && wp_date("H") != 0 || date("H") != 0))
		{
			return $this->set_reviews(TRUE);
		}
		
		$this->set_data(TRUE);

		return TRUE;
	}
	
	private function admin_current()
	{
		// Check if the plugin is showing in the Dashboard

		if (!current_user_can('manage_options', __CLASS__))
		{
			return FALSE;
		}
		
		return (isset($_GET['page']) && preg_match('/^google[\s_-]?(?:business)[\s_-]?reviews[\s_-]?rating[\s_-]?(?:(?:data|place)s?)?.*$/i', $_GET['page']));
	}
	
	private function valid()
	{
		// Check setup uses valid data and returning a result
		
		if ($this->demo)
		{
			return TRUE;
		}
		
		global $wpdb;
		
		$api_key = get_option(__CLASS__ . '_api_key');
		$place_id = get_option(__CLASS__ . '_place_id');
		
		if ((!is_string($api_key) || is_string($api_key) && strlen($api_key) < 10) || (!is_string($place_id) || is_string($place_id) && strlen($place_id) < 10))
		{
			return FALSE;
		}
		
		return (!empty($this->data) && isset($this->data['status']) && preg_match('/^OK$/i', $this->data['status']));
	}
	
	public function retrieved_data_check($current = FALSE)
	{
		// Check to display retrieved data
		
		if ($this->demo)
		{
			return TRUE;
		}

		if ($current)
		{
			return ($this->get_data('boolean', NULL, TRUE));
		}
		
		if (is_bool($this->retrieved_data_exists))
		{
			return $this->retrieved_data_exists;
		}
		
		$this->retrieved_data_exists = (get_option('google_business_reviews_rating_place_id') != NULL && $this->get_data('test'));
		return $this->retrieved_data_exists;
	}
	
	public function admin_settings()
	{
		// Set and process settings in the Dashboard
		
		if (!current_user_can('manage_options', __CLASS__))
		{
			wp_die(__('You do not have sufficient permissions to access this page.', 'g-business-reviews-rating'));
		}
				
		$this->relative_times = array(
			'hour' => array(
				'text' => __('just now', 'g-business-reviews-rating'),
				'min_time' => NULL,
				'max_time' => 7200,
				'divider' => 3600,
				'singular' => TRUE
			),
			'hours' => array(
				/* translators: %u: number of hours, days, weeks, months or years and should remain untouched */
				'text' => __('%u hours ago', 'g-business-reviews-rating'),
				'min_time' => 7200,
				'max_time' => (11 * 3600),
				'divider' => 3600,
				'singular' => FALSE
			),
			'day' => array(
				'text' => __('a day ago', 'g-business-reviews-rating'),
				'min_time' => (11 * 3600),
				'max_time' => (1.5 * 86400),
				'divider' => NULL,
				'singular' => TRUE
			),
			'days' => array(
				/* translators: %u: number of hours, days, weeks, months or years and should remain untouched */
				'text' => __('%u days ago', 'g-business-reviews-rating'),
				'min_time' => (1.5 * 86400),
				'max_time' => (3.5 * 86400),
				'divider' => 86400,
				'singular' => FALSE
			),
			'within_week' => array(
				'text' => __('in the last week', 'g-business-reviews-rating'),
				'min_time' => (3.5 * 86400),
				'max_time' => (6.5 * 86400),
				'divider' => NULL,
				'singular' => TRUE
			),
			'week' => array(
				'text' => __('a week ago', 'g-business-reviews-rating'),
				'min_time' => (6.5 * 86400),
				'max_time' => (13.5 * 86400),
				'divider' => NULL,
				'singular' => TRUE
			),
			'weeks' => array(
				/* translators: %u: number of hours, days, weeks, months or years and should remain untouched */
				'text' => __('%u weeks ago', 'g-business-reviews-rating'),
				'min_time' => (13.5 * 86400),
				'max_time' => (29.5 * 86400),
				'divider' => (7 * 86400),
				'singular' => FALSE
			),
			'month' => array(
				'text' => __('a month ago', 'g-business-reviews-rating'),
				'min_time' => (29.5 * 86400),
				'max_time' => (58 * 86400),
				'divider' => NULL,
				'singular' => TRUE
			),
			'months' => array(
				/* translators: %u: number of hours, days, weeks, months or years and should remain untouched */
				'text' => __('%u months ago', 'g-business-reviews-rating'),
				'min_time' => (58 * 86400),
				'max_time' => (350 * 86400),
				'divider' => (30.5 * 86400),
				'singular' => FALSE
			),
			'year' => array(
				'text' => __('a year ago', 'g-business-reviews-rating'),
				'min_time' => (350 * 86400),
				'max_time' => (700 * 86400),
				'divider' => NULL,
				'singular' => TRUE
			),
			'years' => array(
				/* translators: %u: number of hours, days, weeks, months or years and should remain untouched */
				'text' => __('%u years ago', 'g-business-reviews-rating'),
				'min_time' => (700 * 86400),
				'max_time' => NULL,
				'divider' => (365.25 * 86400),
				'singular' => FALSE
			),
		);
		
		$this->languages = array(
			'af' => 'Afrikaans',
			'sq' => 'Albanian',
			'am' => 'Amharic',
			'ar' => 'Arabic',
			'hy' => 'Armenian',
			'az' => 'Azerbaijani',
			'eu' => 'Basque',
			'be' => 'Belarusian',
			'bn' => 'Bengali',
			'bs' => 'Bosnian',
			'bg' => 'Bulgarian',
			'my' => 'Burmese',
			'ca' => 'Catalan',
			'zh' => 'Chinese',
			'zh-CN' => 'Chinese (Simplified)',
			'zh-HK' => 'Chinese (Hong Kong)',
			'zh-TW' => 'Chinese (Traditional)',
			'hr' => 'Croatian',
			'cs' => 'Czech',
			'da' => 'Danish',
			'nl' => 'Dutch',
			'en' => 'English',
			'en-AU' => 'English (Australian)',
			'en-GB' => 'English (Great Britain)',
			'et' => 'Estonian',
			'fa' => 'Farsi',
			'fi' => 'Finnish',
			'fil' => 'Filipino',
			'fr' => 'French',
			'fr-CA' => 'French (Canada)',
			'gl' => 'Galician',
			'ka' => 'Georgian',
			'de' => 'German',
			'el' => 'Greek',
			'gu' => 'Gujarati',
			'iw' => 'Hebrew',
			'hi' => 'Hindi',
			'hu' => 'Hungarian',
			'is' => 'Icelandic',
			'id' => 'Indonesian',
			'it' => 'Italian',
			'ja' => 'Japanese',
			'kn' => 'Kannada',
			'kk' => 'Kazakh',
			'km' => 'Khmer',
			'ko' => 'Korean',
			'ky' => 'Kyrgyz',
			'lo' => 'Lao',
			'lv' => 'Latvian',
			'lt' => 'Lithuanian',
			'mk' => 'Macedonian',
			'ms' => 'Malay',
			'ml' => 'Malayalam',
			'mr' => 'Marathi',
			'mn' => 'Mongolian',
			'ne' => 'Nepali',
			'no' => 'Norwegian',
			'pl' => 'Polish',
			'pt' => 'Portuguese',
			'pt-BR' => 'Portuguese (Brazil)',
			'pt-PT' => 'Portuguese (Portugal)',
			'pa' => 'Punjabi',
			'ro' => 'Romanian',
			'ru' => 'Russian',
			'sr' => 'Serbian',
			'si' => 'Sinhalese',
			'sk' => 'Slovak',
			'sl' => 'Slovenian',
			'es' => 'Spanish',
			'es-419' => 'Spanish (Latin America)',
			'sw' => 'Swahili',
			'sv' => 'Swedish',
			'ta' => 'Tamil',
			'te' => 'Telugu',
			'th' => 'Thai',
			'tr' => 'Turkish',
			'uk' => 'Ukrainian',
			'ur' => 'Urdu',
			'uz' => 'Uzbek',
			'vi' => 'Vietnamese',
			'zu' => 'Zulu'
		);
		
		$this->reviews_themes = array(
			'light' => __('Light Background', 'g-business-reviews-rating'),
			'light fonts' => __('Light Background with Fonts', 'g-business-reviews-rating'),
			'light narrow' => __('Narrow, Light Background', 'g-business-reviews-rating'),
			'light narrow fonts' => __('Narrow, Light Background with Fonts', 'g-business-reviews-rating'),
			'dark' => __('Dark Background', 'g-business-reviews-rating'),
			'dark fonts' => __('Dark Background with Fonts', 'g-business-reviews-rating'),
			'dark narrow' => __('Narrow, Dark Background', 'g-business-reviews-rating'),
			'dark narrow fonts' => __('Narrow, Dark Background with Fonts', 'g-business-reviews-rating'),
			'badge light' => __('Badge, Light Background', 'g-business-reviews-rating'),
			'badge light fonts' => __('Badge, Light Background with Fonts', 'g-business-reviews-rating'),
			'badge light narrow' => __('Narrow Badge, Light Background', 'g-business-reviews-rating'),
			'badge light narrow fonts' => __('Narrow Badge, Light Background with Fonts', 'g-business-reviews-rating'),
			'badge dark' => __('Badge, Dark Background', 'g-business-reviews-rating'),
			'badge dark fonts' => __('Badge, Dark Background with Fonts', 'g-business-reviews-rating'),
			'badge dark narrow' => __('Narrow Badge, Dark Background', 'g-business-reviews-rating'),
			'badge dark narrow fonts' => __('Narrow Badge, Dark Background with Fonts', 'g-business-reviews-rating'),
			'columns two' => __('Two Columns, Light Background', 'g-business-reviews-rating'),
			'columns two fonts' => __('Two Columns, Light Background with Fonts', 'g-business-reviews-rating'),
			'columns two dark' => __('Two Columns, Dark Background', 'g-business-reviews-rating'),
			'columns two dark fonts' => __('Two Columns, Dark Background with Fonts', 'g-business-reviews-rating'),
			'columns three' => __('Three Columns, Light Background', 'g-business-reviews-rating'),
			'columns three fonts' => __('Three Columns, Light Background with Fonts', 'g-business-reviews-rating'),
			'columns three dark' => __('Three Columns, Dark Background', 'g-business-reviews-rating'),
			'columns three dark fonts' => __('Three Columns, Dark Background with Fonts', 'g-business-reviews-rating'),
			'columns four' => __('Four Columns, Light Background', 'g-business-reviews-rating'),
			'columns four fonts' => __('Four Columns, Light Background with Fonts', 'g-business-reviews-rating'),
			'columns four dark' => __('Four Columns, Dark Background', 'g-business-reviews-rating'),
			'columns four dark fonts' => __('Four Columns, Dark Background with Fonts', 'g-business-reviews-rating')
		);
		
		$this->business_types = array(
			'AnimalShelter' => __('Animal Shelter', 'g-business-reviews-rating'),
			'ArchiveOrganization' => __('Archive Organization', 'g-business-reviews-rating'),
			'AutomotiveBusiness' => __('Automotive Business', 'g-business-reviews-rating'),
			'ChildCare' => __('Child Care', 'g-business-reviews-rating'),
			'Dentist' => __('Dentist', 'g-business-reviews-rating'),
			'DryCleaningOrLaundry' => __('Dry Cleaning or Laundry', 'g-business-reviews-rating'),
			'EmergencyService' => __('Emergency Service', 'g-business-reviews-rating'),
			'EmploymentAgency' => __('Employment Agency', 'g-business-reviews-rating'),
			'EntertainmentBusiness' => __('Entertainment Business', 'g-business-reviews-rating'),
			'FinancialService' => __('Financial Service', 'g-business-reviews-rating'),
			'FoodEstablishment' => __('Food Establishment', 'g-business-reviews-rating'),
			'GovernmentOffice' => __('Government Office', 'g-business-reviews-rating'),
			'HealthAndBeautyBusiness' => __('Health and Beauty Business', 'g-business-reviews-rating'),
			'HomeAndConstructionBusiness' => __('Home and Construction Business', 'g-business-reviews-rating'),
			'InternetCafe' => __('Internet Café', 'g-business-reviews-rating'),
			'LegalService' => __('Legal Service', 'g-business-reviews-rating'),
			'Library' => __('Library', 'g-business-reviews-rating'),
			'LodgingBusiness' => __('Lodging Business', 'g-business-reviews-rating'),
			'MedicalBusiness' => __('Medical Business', 'g-business-reviews-rating'),
			'ProfessionalService' => __('Professional Service', 'g-business-reviews-rating'),
			'RadioStation' => __('Radio Station', 'g-business-reviews-rating'),
			'RealEstateAgent' => __('Real Estate Agent', 'g-business-reviews-rating'),
			'RecyclingCenter' => __('Recycling Center', 'g-business-reviews-rating'),
			'SelfStorage' => __('Self Storage', 'g-business-reviews-rating'),
			'ShoppingCenter' => __('Shopping Center', 'g-business-reviews-rating'),
			'SportsActivityLocation' => __('Sports Activity Location', 'g-business-reviews-rating'),
			'Store' => __('Store', 'g-business-reviews-rating'),
			'TelevisionStation' => __('Television Station', 'g-business-reviews-rating'),
			'TouristInformationCenter' => __('Tourist Information Center', 'g-business-reviews-rating'),
			'TravelAgency' => __('Travel Agency', 'g-business-reviews-rating')
		);
		
		$this->price_ranges = array(
			1 => array(
					'name' => __('$', 'g-business-reviews-rating'),
					'symbol' => '$'
				),
			2 => array(
					'name' => str_repeat(__('$', 'g-business-reviews-rating'), 2),
					'symbol' => str_repeat('$', 2)
				),
			3 => array(
					'name' => str_repeat(__('$', 'g-business-reviews-rating'), 3),
					'symbol' => str_repeat('$', 3)
				),
			4 => array(
					'name' => str_repeat(__('$', 'g-business-reviews-rating'), 4),
					'symbol' => str_repeat('$', 4)
				)
		);
		
		$this->updates = array(
			NULL => __('Never Synchronize', 'g-business-reviews-rating'),
			24 => __('Synchronize Daily', 'g-business-reviews-rating'),
			1 => __('Synchronize Hourly', 'g-business-reviews-rating')
		);
		
		$this->section = get_option(__CLASS__ . '_section');
		
		$this->show_reviews = (!is_numeric(get_option('google_business_reviews_rating_review_limit')) || is_numeric(get_option('google_business_reviews_rating_review_limit')) && get_option('google_business_reviews_rating_review_limit') > 0);
		$this->count_reviews_all = $this->reviews_count();
		$this->count_reviews_active = $this->reviews_count(NULL, TRUE);
		
		include(plugin_dir_path(__FILE__) . 'templates/settings.php');
	}
	
	public function admin_notices()
	{
		// Handle Dashboard notices
		
		if (!current_user_can('manage_options', __CLASS__) || !$this->admin_current())
		{
			return;
		}
		
		$html = '';
		
		global $wpdb;
		
		if (is_string(get_option(__CLASS__ . '_api_key')) && is_string(get_option(__CLASS__ . '_place_id')))
		{
			$this->set_data();
			
			if (!isset($this->data['status']) || preg_match('/^OK$/i', $this->data['status']))
			{
				$html = '';
			}
			elseif (preg_match('/^REQUEST[\s_-]?DENIED$/i', $this->data['status']))
			{
				$html = '<div class="notice notice-error visible is-dismissible">
	<p>'
				/* translators: %s refers to useful URLs to resolve errors and should remain untouched */
				. sprintf(__('<strong>Error:</strong> Your Google API Key is not valid for this request and permission is denied. Please check your Google <a href="%s" target="_blank">API Key</a>.', 'g-business-reviews-rating'), 'https://developers.google.com/maps/documentation/javascript/get-api-key') . '</p>
</div>
';
			}
			elseif (preg_match('/^INVALID[\s_-]?REQUEST$/i', $this->data['status']))
			{
				$html = '<div class="notice notice-error visible is-dismissible">
	<p>'
				/* translators: %s refers to useful URLs to resolve errors and should remain untouched */
				. sprintf(__('<strong>Error:</strong> Google has returned an invalid request error. Please check your <a href="%s" target="_blank">Place ID</a>.', 'g-business-reviews-rating'), 'https://developers.google.com/places/place-id') . '</p>
</div>
';
			}
			elseif (preg_match('/^NOT[\s_-]?FOUND$/i', $this->data['status']))
			{
				$html = '<div class="notice notice-error visible is-dismissible">
	<p>'
				/* translators: %s refers to useful URLs to resolve errors and should remain untouched */
				. sprintf(__('<strong>Error:</strong> Google has not found data for the current Place ID. Please ensure you search for a specific business location; not a region or coordinates using the <a href="%s" target="_blank">Place ID Finder</a>.', 'g-business-reviews-rating'), 'https://developers.google.com/places/place-id') . '</p>
</div>
';
			}
			else
			{
				$html = '<div class="notice notice-error visible is-dismissible">
	<p>' . ((isset($this->data['error_message'])) ? preg_replace('/\s+rel="nofollow"/i', ' target="_blank"', '<strong>' . __('Error:', 'g-business-reviews-rating') . '</strong> ' . $this->data['error_message']) : __('<strong>Error:</strong> Unknown — Please check Retreived data to find out more information', 'g-business-reviews-rating')) . '.</p>
</div>
';
			}
		}
		
		if ($html == '')
		{
			return;
		}
		
		echo $html;
	}
	
	public function admin_ajax()
	{
		// Handle AJAX requests from Dashboard
		
		$post = stripslashes_deep($_POST);
		$ret = array();
		$type = (isset($post['type'])) ? preg_replace('/[^\w_]/', '', strtolower($post['type'])) : NULL;
		$section = (isset($post['section']) && !preg_match('/^setup$/i', $post['section'])) ? preg_replace('/[^\w_-]/', '', strtolower($post['section'])) : NULL;
		$review = (isset($post['review'])) ? preg_replace('/[^\w_]/', '', $post['review']) : NULL;
		$reviews = (isset($post['reviews']) && is_array($post['reviews'])) ? array_unique($post['reviews'], SORT_REGULAR) : array();
		$submitted = (isset($post['submitted']) && is_string($post['submitted'])) ? stripslashes($post['submitted']) : NULL;
		$ids = (isset($post['id']) && (is_string($post['id']))) ? preg_split('/,\s*/', $post['id']) : array();
		$id = (isset($ids[0])) ? $ids[0] : NULL;
		$status = (isset($post['status']) && (is_bool($post['status']) && $post['status'] || is_string($post['status']) && preg_match('/^true$/i', $post['status'])));
		$custom_styles = (isset($post['custom_styles']) && strlen($post['custom_styles']) > 2 && !preg_match('/<\?(?:php|=)/i', $post['custom_styles'])) ? $post['custom_styles'] : NULL;

		switch($type)
		{
		case 'section':
			$this->section = $section;
			update_option(__CLASS__ . '_section', $this->section, 'no');
			$ret = array(
				'success' => TRUE
			);
			break;
		case 'import':
			if ($this->demo || empty($reviews))
			{
				$ret = array(
					'count' => $count,
					'message' =>  __('No reviews imported', 'g-business-reviews-rating'),
					'success' => FALSE
				);
				break;
			}
			
			$this->set_data();
			$review_backup = (is_array($this->reviews)) ? $this->reviews : array();
			$add = array();
			
			foreach ($reviews as $i => $review)
			{
				if (!preg_match('/^.+[^\d](\d{20,120})[^\d].*$/', $review['author_url'], $m))
				{
					continue;
				}
				
				$author_url_id = $m[1];
				
				foreach ($this->reviews as $key => $a)
				{
					if (!preg_match('/^.+[^\d](\d{20,120})[^\d].*$/', $a['author_url'], $m))
					{
						continue;
					}
					
					if ($review['author_name'] == $a['author_name'] || $author_url_id == $m[1])
					{
						continue(2);
					}
				}
			
				$add[] = $i;
			}
			
			$max_id = (!empty($this->reviews) && function_exists('array_column')) ? max(array_column($this->reviews, 'id')) : count($this->reviews);
			$count = 0;
			
			foreach ($add as $i)
			{
				$review = $this->sanitize_array($reviews[$i]);
				
				if (!preg_match('/^(\d+)[^\d]+(\d+)[^\d]+(\d+)(?:[^\d].*)?$/', $review['time'], $t))
				{
					continue;
				}
				
				$time = mktime(0, 0, 0, $t[2], $t[3], $t[1]);
				$key = $time . '_' . $review['rating'] . '_' . md5($review['author_name'] . '_' . substr($review['text'], 0, 100));
				
				if (array_key_exists($key, $this->reviews))
				{
					continue;
				}
				
				$a = array(
					'id' => $max_id + $count + 1,
					'place_id' => $this->place_id,
					'order' => $count + 1,
					'author_name' => $review['author_name'],
					'author_url' => preg_replace('/^([^?]+)(?:\?.+)?$/', '$1', $review['author_url']),
					'language' => ($review['text'] != NULL) ? preg_replace('/^(?:[^?]+)\?(?:hl=([0-9a-z]+)[0-9a-z-]*).+$/i', '$1', $review['author_url']) : NULL,
					'profile_photo_url' => $review['profile_photo_url'],
					'rating' => round($review['rating']),
					'relative_time_description' => $review['relative_time_description'],
					'text' => ($review['text'] != NULL) ? $review['text'] : NULL,
					'time' => $time,
					'checked' => NULL,
					'retrieved' => NULL,
					'imported' => time(),
					'time_estimate' => TRUE,
					'status' => TRUE
				);

				$this->reviews[$key] = $a;
				$count++;
			}
			
			if ($count < 1)
			{
				$ret = array(
					'count' => $count,
					'message' =>  __('No reviews imported', 'g-business-reviews-rating'),
					'success' => FALSE
				);
				break;
			}

			global $wpdb;

			wp_cache_delete('reviews', __CLASS__);
			update_option(__CLASS__ . '_reviews', $this->reviews, 'no');
			
			$this->set_reviews(TRUE);
			$this->count_reviews_all = $this->reviews_count();
			$this->count_reviews_active = $this->reviews_count(NULL, TRUE);
			
			if (count($review_backup) >= $this->count_reviews_all)
			{
				$this->reviews = $review_backup;
				update_option(__CLASS__ . '_reviews', $this->reviews, 'no');
				update_option(__CLASS__ . '_additional_array_sanitization', TRUE, 'yes');
				$this->set_reviews(TRUE);
				$this->reviews_filtered = $this->reviews;
				
				if ($count > 10)
				{
					$ret = array(
						'count' => $count,
						/* translators: %u: number of reviews and should remain untouched */
						'message' =>  sprintf(__('Review import failed. Please select a smaller number of reviews, less than %u.', 'g-business-reviews-rating'), $count),
						'success' => FALSE
					);
					break;
				}

				$ret = array(
					'count' => $count,
					'message' =>  __('Review import failed.', 'g-business-reviews-rating'),
					'success' => FALSE
				);
				break;
			}
			
			$review_verify = get_option(__CLASS__ . '_reviews');

			if (is_array($review_verify) && count($review_verify) == count($this->reviews))
			{
				/*
					Plugin Author Note: There is a fault with the WordPress function maybe_serialize() that causes all reviews to be reset. This check prevents that from happening.
				*/
				
				$review_verify = $wpdb->get_var($wpdb->prepare("SELECT option_value FROM $wpdb->options WHERE option_name = %s LIMIT 1", __CLASS__ . '_reviews'));
				$review_verify = (is_string($review_verify)) ? maybe_unserialize($review_verify) : array();
			}

			if (!is_array($review_verify) || is_array($review_verify) && count($review_verify) != count($this->reviews))
			{
				$this->reviews = $review_backup;
				update_option(__CLASS__ . '_reviews', $this->reviews, 'no');
				$this->set_reviews(TRUE);
				$this->set_data(TRUE);
				
				if ($count > 10)
				{
					$ret = array(
						'count' => $count,
						/* translators: %u: number of reviews and should remain untouched */
						'message' =>  sprintf(__('Review import failed due the handling of serialized data by WordPress. Please select a smaller number of reviews, less than %u.', 'g-business-reviews-rating'), $count),
						'success' => FALSE
					);
					break;
				}

				$ret = array(
					'count' => $count,
					'message' =>  __('Review import failed due the handling of serialized data by WordPress.', 'g-business-reviews-rating'),
					'success' => FALSE
				);
				break;
			}

			$this->reviews_filtered = $this->reviews;
			$this->section = 'reviews';
			update_option(__CLASS__ . '_section', $this->section, 'no');

			$ret = array(
				'count' => $count,
				/* translators: %u: number of reviews and should remain untouched */
				'message' => sprintf(_n('Successfully imported one review', 'Successfully imported %u reviews', $count), $count),
				'success' => TRUE
			);
			break;
		case 'submitted':
			$this->set_data();
			if ($this->demo || !array_key_exists($review, $this->reviews) || !isset($this->reviews[$review]['time_estimate']) || isset($this->reviews[$review]['time_estimate']) && !$this->reviews[$review]['time_estimate'] || !preg_match('/^(\d+)[^\d]+(\d+)[^\d]+(\d+)(?:[^\d].*)?$/', $submitted, $t))
			{
				$ret = array(
					'review' => $review,
					'submitted' => $submitted,
					'success' => FALSE
				);
				break;	
			}

			global $wpdb;

			$time = mktime(0, 0, 0, $t[2], $t[3], $t[1]);
			$this->reviews[$review]['time'] = $time;
			update_option(__CLASS__ . '_reviews', $this->reviews, 'no');
			
			$this->set_reviews(TRUE);
			$this->reviews_filtered = $this->reviews;
			$ret = array(
				'review' => $review,
				'submitted' => $submitted,
				'time' => $time,
				'success' => TRUE
			);

			break;
		case 'icon-delete':
		case 'icon_delete':
		case 'icon-remove':
		case 'icon_remove':
			$this->delete_icon();
			
			$ret = array(
				'id' => NULL,
				'image' => NULL,
				'success' => TRUE
			);
			break;	
		case 'icon':
			if (!is_numeric($id))
			{
				$this->delete_icon();
				
				$ret = array(
					'id' => NULL,
					'image' => NULL,
					'success' => FALSE
				);
				break;	
			}
			
			$this->set_icon($id);
			
			if (!is_string($this->icon_image_url) || is_string($this->icon_image_url) && strlen($this->icon_image_url) < 5)
			{
				$this->delete_icon();
				
				$ret = array(
					'id' => NULL,
					'image' => NULL,
					'success' => FALSE
				);
				
				break;	
			}
			
			$ret = array(
				'id' => $this->icon_image_id,
				'image' => preg_replace('/\s+(?:width|height)="\d*"/i', '', wp_get_attachment_image($this->icon_image_id, 'large', FALSE, array('id' => 'icon-image-preview-image'))),
				'success' => TRUE
			);
			break;
		case 'logo-delete':
		case 'logo_delete':
		case 'logo-remove':
		case 'logo_remove':
			$this->delete_logo();
			
			$ret = array(
				'id' => NULL,
				'image' => NULL,
				'success' => TRUE
			);
			break;	
		case 'logo':
			if (!is_numeric($id))
			{
				$this->delete_logo();
				
				$ret = array(
					'id' => NULL,
					'image' => NULL,
					'success' => FALSE
				);
				break;	
			}
			
			$this->set_logo($id);
			
			if (!is_string($this->logo_image_url) || is_string($this->logo_image_url) && strlen($this->logo_image_url) < 5)
			{
				$this->delete_logo();
				
				$ret = array(
					'id' => NULL,
					'image' => NULL,
					'success' => FALSE
				);
				
				break;	
			}
			
			$ret = array(
				'id' => $this->logo_image_id,
				'image' => preg_replace('/\s+(?:width|height)="\d*"/i', '', wp_get_attachment_image($this->logo_image_id, 'large', FALSE, array('id' => 'logo-image-preview-image'))),
				'success' => TRUE
			);
			break;
		case 'preview':
			$ret = array(
				'html' => $this->admin_preview($post),
				'status' => $status,
				'success' => TRUE
			);
			break;
		case 'structured-data':
		case 'structured_data':
			$data = array();
			
			if (preg_match('/.+\.(?:jpe?g|png|svg|gif)$/i', $this->logo_image_url))
			{
				$data['logo'] = $this->logo_image_url;
			}
			
			if (isset($post['telephone']) && preg_match('/^[\d _()\[\].+-]+$/', $post['telephone']))
			{
				$data['telephone'] = $post['telephone'];
			}
			
			if (isset($post['business_type']) && preg_match('/^[\w\s_-]{1,64}$/i', $post['business_type']))
			{
				$data['business_type'] = $post['business_type'];
			}
			
			if (isset($post['price_range']) && preg_match('/^.{1,16}$/', $post['price_range']))
			{
				$data['price_range'] = $post['price_range'];
			}
			
			$ret = array(
				'data' => $this->structured_data('json', $data),
				'success' => TRUE
			);
			break;
		case 'status':
			$this->set_data();
			if (!array_key_exists($review, $this->reviews) || isset($this->reviews[$review]['status']) && $this->reviews[$review]['status'] == $status)
			{
				$ret = array(
					'review' => $review,
					'status' => $status,
					'success' => FALSE
				);
				break;	
			}

			global $wpdb;

			$this->reviews[$review]['status'] = $status;
			$this->reviews_filtered = $this->reviews;
			wp_cache_set((($this->demo) ? 'reviews_demo' : 'reviews'), $this->reviews, __CLASS__, 3600);
	
			if (!$this->demo)
			{
				update_option(__CLASS__ . '_reviews', $this->reviews, 'no');
			}
	
			$ret = array(
				'review' => $review,
				'status' => $status,
				'success' => TRUE
			);
			break;
		case 'custom-styles':
		case 'custom_styles':
			if ($custom_styles == get_option(__CLASS__ . '_custom_styles'))
			{
				$ret = array(
					'success' => TRUE
				);
			}
			
			update_option(__CLASS__ . '_custom_styles', $custom_styles, 'yes');
			$fp = FALSE;
			$file = plugin_dir_path(__FILE__) . 'wp/css/custom.css';

			if (!is_file($file))
			{
				if (!is_writable(plugin_dir_path(__FILE__) . 'wp/css/'))
				{
					$ret = array(
						/* translators: %s: file directory and should remain untouched */
						'message' => sprintf(__('Cannot create a new file in plugin directory: %s', 'g-business-reviews-rating'), './wp/css/'),
						'success' => FALSE
					);
					break;
				}
				
				$fp = fopen($file, 'w');
				
				if (!$fp || !is_file($file))
				{
					if ($fp)
					{
						fclose($fp);
					}
					
					$ret = array(
						/* translators: %s: file name and should remain untouched */
						'message' => sprintf(__('Cannot create a new file: %s', 'g-business-reviews-rating'), './wp/css/custom.css'),
						'success' => FALSE
					);
					break;
				}
			}
			
			if (!is_writable($file))
			{
				$ret = array(
					/* translators: %s: file name and should remain untouched */
					'message' => sprintf(__('File at: %s is not writable.', 'g-business-reviews-rating'), './wp/css/custom.css'),
					'success' => FALSE
				);
				break;
			}
			
			if (!$fp)
			{
				$fp = fopen($file, 'w');
			}
				
			if (!$fp)
			{
				$ret = array(
					/* translators: %s: file name and should remain untouched */
					'message' =>  sprintf(__('Cannot write new data to file at: %s', 'g-business-reviews-rating'), './wp/css/custom.css'),
					'success' => FALSE
				);
				break;
			}
			
			if (!fwrite($fp, $custom_styles) && $custom_styles != NULL)
			{
				fclose($fp);
				$ret = array(
					'success' => FALSE
				);
				break;
			}
			
			fclose($fp);
			
			$ret = array(
				'message' =>  __('Successfully saved custom styles', 'g-business-reviews-rating'),
				'success' => TRUE
			);
			break;
		case 'clear':
		case 'cache':
		case 'clear-cache':
		case 'clear_cache':
			wp_cache_delete('structured_data', __CLASS__);
			wp_cache_delete('result', __CLASS__);
			wp_cache_delete('result_valid', __CLASS__);
			update_option(__CLASS__ . '_result', NULL, 'no');
			$this->data = array();
			$this->result = array();

			if (!$this->set_data(TRUE))
			{
				$ret = array(
					'success' => FALSE
				);
				break;
			}
			
			$this->section = NULL;
			update_option(__CLASS__ . '_section', $this->section, 'no');

			$ret = array(
				'message' => __('Cache cleared', 'g-business-reviews-rating'),
				'success' => TRUE
			);
			break;
		case 'delete':
		case 'remove':
			$this->set_data();
			if ($this->demo || !array_key_exists($review, $this->reviews) || !isset($this->reviews[$review]['time_estimate']) || isset($this->reviews[$review]['time_estimate']) && !$this->reviews[$review]['time_estimate'])
			{
				$ret = array(
					'review' => $review,
					'success' => FALSE
				);
				break;	
			}

			global $wpdb;

			unset($this->reviews[$review]);
			update_option(__CLASS__ . '_reviews', $this->reviews, 'no');
			
			$this->set_reviews(TRUE);
			$this->reviews_filtered = $this->reviews;
			$ret = array(
				'review' => $review,
				'success' => TRUE
			);
			break;
		case 'reset_reviews':
			wp_cache_delete('reviews', __CLASS__);
			update_option(__CLASS__ . '_reviews', NULL, 'no');
			update_option(__CLASS__ . '_section', NULL, 'no');
			$this->set_data(TRUE);

			$ret = array(
				'message' => __('Review archive successfully reset', 'g-business-reviews-rating'),
				'success' => TRUE
			);
			break;
		case 'reset':
			if (!current_user_can('activate_plugins'))
			{
				$ret = array(
					'message' => __('You do not have permissions to deactivate and reactivate plugin', 'g-business-reviews-rating'),
					'success' => FALSE
				);
				break;
			}
			
			$ret = array(
				'message' => __('Plugin successfully reset', 'g-business-reviews-rating'),
				'success' => $this->reset()
			);
			break;
		default:
			break;
		}

		echo json_encode($ret);
		wp_die();

		return;
	}

	public static function admin_uploads_file_types($types)
	{
		// Add SVG to acceptable file uploads
		
		if (!array_key_exists('svg', $types))
		{
			$types['svg'] = 'image/svg+xml';
		}

		if (!array_key_exists('svgz', $types))
		{
			$types['svgz'] = 'image/svg+xml';
		}

		return $types;
	}
	
	public static function admin_add_action_links($links, $file)
	{
		// Add action link in Dashboard Plugin list
		
		if (!preg_match('#^([^/]+).*$#', $file, $m1) || !preg_match('#^([^/]+).*$#', plugin_basename(__FILE__), $m2) || $m1[1] != $m2[1])
		{
			return $links;
		}
		
		$settings = array('settings' => '<a href="' . admin_url('options-general.php?page=google_business_reviews_rating_settings') . '">' . esc_html__('Settings', 'g-business-reviews-rating') . '</a>');
		$links = array_merge($settings, $links);

		return $links;
	}
	
	public static function admin_add_plugin_meta($links, $file)
	{
		// Add support link in Dashboard Plugin list
		
		if (!preg_match('#^([^/]+).*$#', $file, $m1) || !preg_match('#^([^/]+).*$#', plugin_basename(__FILE__), $m2) || $m1[1] != $m2[1])
		{
			return $links;
		}
		
		$support = array('support' => '<a href="https://designextreme.com/wordpress/#google-business-reviews-rating" target="_blank">' . esc_html__('Support', 'g-business-reviews-rating') . '</a>');
		$links = array_merge($links, $support);
				
		return $links;
	}

	public function admin_css_load()
	{
		// Load style sheet in the Dashboard
		
		if (!$this->admin_current())
		{
			return;
		}
		
		wp_register_style(__CLASS__ . '_admin_css', plugins_url('g-business-reviews-rating/admin/css/css.css'));
		wp_register_style(__CLASS__ . '_wp_css', plugins_url('g-business-reviews-rating/wp/css/css.css'));
		wp_enqueue_style(__CLASS__ . '_admin_css');
		wp_enqueue_style(__CLASS__ . '_wp_css');
		wp_enqueue_media();
	}
	
	public function admin_js_load()
	{
		// Load Javascript in the Dashboard
		
		if (!$this->admin_current())
		{
			return;
		}

		wp_register_script(__CLASS__ . '_admin_js', plugins_url('g-business-reviews-rating/admin/js/js.js'));
		wp_localize_script(__CLASS__ . '_admin_js', __CLASS__ . '_admin_ajax', array('url' => admin_url('admin-ajax.php'), 'action' => 'google_business_reviews_rating_admin_ajax'));
		wp_register_script(__CLASS__ . '_wp_js', plugins_url('g-business-reviews-rating/wp/js/js.js'), array('jquery'));
		wp_enqueue_script(__CLASS__ . '_admin_js');
		wp_enqueue_script(__CLASS__ . '_wp_js');
	}
	
	public function wp_css_load()
	{
		// Load style sheet in the front-end
		
		wp_register_style(__CLASS__ . '_wp_css', plugins_url('g-business-reviews-rating/wp/css/css.css'));
		wp_enqueue_style(__CLASS__ . '_wp_css');
		
		if (is_file(plugin_dir_path(__FILE__) . 'wp/css/custom.css') && filesize(plugin_dir_path(__FILE__) . 'wp/css/custom.css') > 20)
		{
			wp_register_style(__CLASS__ . '_wp_custom_css', plugins_url('g-business-reviews-rating/wp/css/custom.css'));
			wp_enqueue_style(__CLASS__ . '_wp_custom_css');
		}
	}
	
	
	public function wp_js_load()
	{
		// Load Javascript in the front-end
		
		wp_register_script(__CLASS__ . '_wp_js', plugins_url('g-business-reviews-rating/wp/js/js.js'), array('jquery'));
		wp_enqueue_script(__CLASS__ . '_wp_js');
	}
	
	public function get_data($type = 'array', $place_id = NULL, $valid = FALSE)
	{
		// Return data from Google Places API, place data or an option value
		
		$ret = NULL;		
		$retrieved_data_formats = array('boolean', 'html', 'object', 'json', 'array', 'test', NULL);

		if (is_null($place_id) || !is_string($place_id) || is_string($place_id) && strlen($place_id) < 16)
		{
			$place_id = $this->place_id;
		}
		
		if (!in_array($type, $retrieved_data_formats))
		{
			$places = ($place_id != $this->place_id) ? get_option(__CLASS__ . '_places') : array();
			
			if (empty($this->data))
			{
				$this->set_data();
			}
		}
		else
		{
			if ($this->demo)
			{
				return $this->retrieve_data($type);
			}
	
			if ($this->dashboard && !$valid)
			{
				$this->api_key = ($this->api_key != NULL) ? $this->api_key : get_option(__CLASS__ . '_api_key');
				$this->place_id = ($this->place_id != NULL) ? $this->place_id : get_option(__CLASS__ . '_place_id');
				
				return $this->retrieve_data($type);
			}
			
			$data = ($valid) ? get_option(__CLASS__ . '_result_valid') : get_option(__CLASS__ . '_result');
	
			if (!$this->dashboard && !$valid && (!isset($data['status'])) || isset($data['status']) && !preg_match('/^OK$/i', $data['status']))
			{
				$data = get_option(__CLASS__ . '_result_valid');
			}
		}
		
		switch ($type)
		{
		case 'array':
		case NULL:
			$ret = $data;
			break;
		case 'json':
			$ret = json_encode($data);
			break;
		case 'boolean':
			$data = get_option(__CLASS__ . '_result_valid');
			$data_check = get_option(__CLASS__ . '_result');
			$this->retrieved_data_valid = (empty($data_check) || empty($data) || serialize($data_check) == serialize($data));
			$ret = $this->retrieved_data_valid;
			break;
		case 'test':
			$ret = (is_array($data) && !empty($data));
			break;
		case 'html':
			$ret = '	<pre id="google-business-reviews-rating-' . (($valid) ? 'valid-' : '') . 'data">' . esc_html(json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)) . '</pre>
';
			break;
		case 'object':
			$ret = (object) $data;
			break;
		case 'address':
			if (is_array($places))
			{
				foreach ($places as $a)
				{
					if ($a['place_id'] != $place_id)
					{
						continue;
					}
					
					if (!is_numeric($a[$type]))
					{
						break;
					}
					
					$ret = $a[$type];
					break(2);
				}
			}
			
			if (isset($this->data['result']['formatted_address']) && $this->data['result']['formatted_address'] != NULL)
			{
				$ret = $this->data['result']['formatted_address'];
			}
			
			break;
		case 'name':
		case 'vicinity':
		case 'rating':
			if (is_array($places))
			{
				foreach ($places as $a)
				{
					if ($a['place_id'] != $place_id)
					{
						continue;
					}
					
					if (!isset($a[$type]) || isset($a[$type]) && $a[$type] == NULL)
					{
						break;
					}
					
					$ret = $a[$type];
					break(2);
				}
			}
			
			if (isset($this->data['result'][$type]) && $this->data['result'][$type] != NULL)
			{
				$ret = $this->data['result'][$type];
				break;
			}
			
			break;
		case 'rating_rounded':
			$ret = 0;
			
			if (is_array($places))
			{
				foreach ($places as $a)
				{
					if ($a['place_id'] != $place_id)
					{
						continue;
					}
					
					if ($a['rating'] == NULL)
					{
						break;
					}
					
					$ret = (function_exists('number_format_i18n')) ? number_format_i18n($a['rating'], 1) : number_format($a['rating'], 1);
					break(2);
				}
			}
			
			if (isset($this->data['result']['rating']) && $this->data['result']['rating'] != NULL)
			{
				$ret = (function_exists('number_format_i18n')) ? number_format_i18n($this->data['result']['rating'], 1) : number_format($this->data['result']['rating'], 1);
				break;
			}
			
			break;
		case 'rating_count':
			$ret = 0;
			
			if (is_array($places))
			{
				foreach ($places as $a)
				{
					if ($a['place_id'] != $place_id)
					{
						continue;
					}
					
					if (!is_numeric($a[$type]))
					{
						break;
					}
					
					$ret = $a[$type];
					break(2);
				}
			}
			
			if (isset($this->data['result']['user_ratings_total']) && $this->data['result']['user_ratings_total'] != NULL)
			{
				$ret = intval($this->data['result']['user_ratings_total']);
			}
			
			break;
		case 'rating_count_rounded':
			$ret = 0;
			
			if (is_array($places))
			{
				foreach ($places as $a)
				{
					if ($a['place_id'] != $place_id)
					{
						continue;
					}
					
					if ($a['rating'] == NULL)
					{
						break;
					}
					
					$ret = (function_exists('number_format_i18n')) ? number_format_i18n($a['rating_count'], 0) : number_format($a['rating_count'], 0);
					break(2);
				}
			}
			
			if (isset($this->data['result']['rating']) && $this->data['result']['rating'] != NULL)
			{
				$ret = (function_exists('number_format_i18n')) ? number_format_i18n($this->data['result']['user_ratings_total'], 0) : number_format($this->data['result']['user_ratings_total'], 0);
				break;
			}
			
			break;
		case 'logo':
			if ($this->logo_image_url != NULL)
			{
				$ret = $this->logo_image_url;
				break;
			}
	
			$this->logo_image_id = get_option(__CLASS__ . '_logo');
	
			if (is_numeric($this->logo_image_id))
			{
				global $wpdb;
				
				$this->logo_image_url = $wpdb->get_var("SELECT `guid` FROM `" . $wpdb->posts . "` WHERE ID='" . intval($this->logo_image_id) . "' LIMIT 1");
				
				if ($this->logo_image_url != NULL)
				{
					$ret = $this->logo_image_url;
					break;
				}
			}
			
			$seo_titles = get_option('wpseo_titles');
			
			if (is_array($seo_titles) && isset($seo_titles['company_logo']) && is_string($seo_titles['company_logo']))
			{
				$ret = $seo_titles['company_logo'];
				break;
			}
			
			// Intentional continue

		case 'icon':
			if ($this->icon_image_url != NULL)
			{
				$ret = $this->icon_image_url;
				break;
			}
	
			$this->icon_image_id = get_option(__CLASS__ . '_icon');
	
			if (is_numeric($this->icon_image_id))
			{
				global $wpdb;
				
				$this->icon_image_url = $wpdb->get_var("SELECT `guid` FROM `" . $wpdb->posts . "` WHERE ID='" . intval($this->icon_image_id) . "' LIMIT 1");
				
				if ($this->icon_image_url != NULL)
				{
					$ret = $this->icon_image_url;
					break;
				}
			}
			
			if (isset($this->data['result']['icon']) && $this->data['result']['icon'] != NULL)
			{
				$ret = $this->data['result']['icon'];
				break;
			}
			
			if (is_array($places))
			{
				foreach ($places as $a)
				{
					if ($a['place_id'] != $place_id)
					{
						continue;
					}
					
					if (!isset($a['icon']) || $a['icon'] == NULL)
					{
						break;
					}
					
					$ret = $a['icon'];
					break(2);
				}
			}
			
			$retrieval = get_option(__CLASS__ . '_retrieval');
			
			if (is_array($retrieval) && isset($retrieval['requests']) && !empty($retrieval['requests']))
			{
				krsort($retrieval);
				foreach ($retrieval as $a)
				{
					if (!isset($a['icon']) || $a['icon'] == NULL)
					{
						continue;
					}
					
					$ret = $a['icon'];
					break(2);
				}
			}
			
			break;
		}
		
		return $ret;
	}
	
	public function retrieve_data($format = 'array', $force = FALSE)
	{
		// Collect data from Google Places as JSON string
		
		$ret = '';

		if ($this->demo)
		{
			$this->result = json_decode(GOOGLE_BUSINESS_REVIEWS_RATING_DEMO_RESULT, TRUE);

			switch ($format)
			{
			case 'html':
				$ret = '	<pre id="google-business-reviews-rating-data">' . esc_html(json_encode($this->result, JSON_PRETTY_PRINT)) . '</pre>
';
				break;
			case 'array':
				return $this->result;
			case 'object':
				$ret = json_decode(GOOGLE_BUSINESS_REVIEWS_RATING_DEMO_RESULT, FALSE);
				break;
			case 'json':
			default:
				$ret = GOOGLE_BUSINESS_REVIEWS_RATING_DEMO_RESULT;
				break;
			}
			
			return $ret;
		}

		if ($this->place_id == NULL || $this->api_key == NULL || $this->request_count > 2)
		{
			switch ($format)
			{
			case 'html':
				return '';
			case 'array':
				return array();
			case 'object':
				return NULL;
			case 'json':
			default:
				return '';
			}
		}

		global $wpdb;

		$fields = array(
			'icon',
			'name',
			'permanently_closed',
			'formatted_address',
			'vicinity',
			'rating',
			'review',
			'url',
			'user_ratings_total'
		);
		
		$language = get_option(__CLASS__ . '_language');
		$data_string = '';
		$data = array();
		$retrieval = NULL;
		
		if ($force)
		{
			$retrieval = get_option(__CLASS__ . '_retrieval');
			
			if (is_array($retrieval) && isset($retrieval['requests']) && is_array($retrieval['requests']))
			{
				$a = end($retrieval['requests']);
				$force = ($a['place_id'] == $this->place_id && (!isset($a['time']) || isset($a['time']) && round($a['time']/2) != round(time()/2)));
			}
		}
		
		if (!$force && (!is_array($this->result) || empty($this->result)))
		{
			$this->result = ($this->dashboard) ? get_option(__CLASS__ . '_result') : get_option(__CLASS__ . '_result_valid');
		}
		
		if (!$force && is_array($this->result) && !empty($this->result))
		{
			$data_string = json_encode($this->result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
			$data = $this->result;
		}
		else
		{
			$url = 'https://maps.googleapis.com/maps/api/place/details/json?placeid=' . rawurlencode($this->place_id) . (($language != NULL) ? '&language=' . rawurlencode($language) : '') . '&fields=' . rawurlencode(implode(',', $fields)) . '&key=' . rawurlencode($this->api_key);

			if (function_exists('wp_remote_get') && function_exists('wp_remote_retrieve_body'))
			{
				$data_string = wp_remote_retrieve_body(wp_remote_get($url));
			}
			
			if (!is_string($data_string))
			{

				$ret = '<p class="error">'
				/* translators: %s: remote Google API URL and should remain untouched */
				. sprintf(__('Error: Unable to collect remote data from URL: <em>%s</em>', 'g-business-reviews-rating'), $url)
				. '</p>';
				return $ret;
			}
			
			$data = ($data_string != NULL) ? $this->sanitize_array(json_decode($data_string, TRUE)) : array();
			$this->result = $data;
		
			if (is_null($retrieval))
			{
				$retrieval = get_option(__CLASS__ . '_retrieval');
			}

			if (!is_array($retrieval))
			{
				$retrieval = array(
					'count' => 0,
					'initial' => time(),
					'requests' => array()
				);
			}
			elseif (!is_array($retrieval['requests']))
			{
				$retrieval['requests'] = array();
			}
			elseif (count($retrieval['requests']) > 100)
			{
				$retrieval['requests'] = array_slice($retrieval['requests'], -100);
			}
			
			$this->request_count++;
			$retrieval['requests'][] = array(
				'time' => time(),
				'place_id' => $this->place_id,
				'status' => (isset($this->result['status'])) ? $this->result['status'] : NULL,
				'name' => (isset($this->result['result']['name'])) ? $this->result['result']['name'] : NULL,
				'icon' => (isset($this->result['result']['icon'])) ? $this->result['result']['icon'] : NULL,
				'vicinity' => (isset($this->result['result']['vicinity'])) ? $this->result['result']['vicinity'] : NULL,
				'rating' => (isset($this->result['result']['rating'])) ? $this->result['result']['rating'] : NULL,
				'rating_count' => (isset($this->result['result']['user_ratings_total'])) ? $this->result['result']['user_ratings_total'] : NULL,
				'review_count' => (isset($this->result['result']['reviews']) && is_array($this->result['result']['reviews'])) ? count($this->result['result']['reviews']) : NULL,
				'dashboard' => ($this->dashboard && (!defined('DOING_CRON') || defined('DOING_CRON') && !DOING_CRON)),
				'sync' => (defined('DOING_CRON') && DOING_CRON),
				'count' => $this->request_count
			);
			
			$retrieval = $this->sanitize_array($retrieval);

			update_option(__CLASS__ . '_retrieval', $retrieval, 'no');
			
			if (isset($this->result['result']['reviews']) && is_array($this->result['result']['reviews']) && !empty($this->result['result']['reviews']))
			{
				$this->result_valid = $this->result;
				
				$places = get_option(__CLASS__ . '_places');
				$place_set_key = FALSE;
				
				if (!is_array($places))
				{
					$places = array();
				}
				else
				{
					sort($places);
				}
				
				foreach (array_keys($places) as $i)
				{
					if ($places[$i]['place_id'] != $this->place_id)
					{
						if ($places[$i]['default'])
						{
							$places[$i]['default'] = FALSE;
						}
						
						continue;
					}
					
					$place_set_key = $i;
				}
					
				if (!is_numeric($place_set_key))
				{
					$place_set_key = count($places);
				}
				
				$places[$place_set_key] = array(
					'place_id' => $this->place_id,
					'time' => time(),
					'name' => (isset($this->result['result']['name'])) ? $this->result['result']['name'] : NULL,
					'icon' => (isset($this->result['result']['icon'])) ? $this->result['result']['icon'] : NULL,
					'vicinity' => (isset($this->result['result']['vicinity'])) ? $this->result['result']['vicinity'] : NULL,
					'rating' => (isset($this->result['result']['rating'])) ? $this->result['result']['rating'] : NULL,
					'rating_count' => (isset($this->result['result']['user_ratings_total'])) ? $this->result['result']['user_ratings_total'] : NULL,
					'default' => TRUE
				);
				
				sort($places);
				$places = $this->sanitize_array($places);
				update_option(__CLASS__ . '_places', $places, 'yes');
			}
		}
		
		switch ($format)
		{
		case 'html':
			if ($this->place_id == NULL && $this->api_key == NULL)
			{
				$ret = '<p class="error">' . __('Error: Place ID and Google API Key are required.', 'g-business-reviews-rating') . '</p>';
			}
			elseif ($this->place_id == NULL)
			{
				$ret = '<p class="error">' . __('Error: Place ID is required.', 'g-business-reviews-rating') . '</p>';
			}
			elseif ($this->api_key == NULL)
			{
				$ret = '<p class="error">' . __('Error: Google API Key is required.', 'g-business-reviews-rating') . '</p>';
			}
			
			if ($ret != '')
			{
				break;
			}
			
			$ret = '	<pre id="google-business-reviews-rating-data">' . esc_html($data_string) . '</pre>
';
			break;
		case 'array':
			return $data;
		case 'object':
			return json_decode($data_string, FALSE);
		case 'json':
		default:
			if ($this->place_id == NULL && $this->api_key == NULL)
			{
				$ret = json_encode(array(
					'error' => __('Place ID and Google API Key are required.', 'g-business-reviews-rating')
				));
			}
			elseif ($this->place_id == NULL)
			{
				$ret = json_encode(array(
					'error' => __('Error: Place ID is required.', 'g-business-reviews-rating')
				));
			}
			elseif ($this->api_key == NULL)
			{
				$ret = json_encode(array(
					'error' => __('Error: Google API Key is required.', 'g-business-reviews-rating')
				));
			}
			
			if ($ret != '')
			{
				return $ret;
			}
			
			return $data_string;
		}
		
		return $ret;
	}
	
	public function set_data($force = NULL, $api_key = NULL, $place_id = NULL)
	{
		// Set data from Google Places with cache check
		
		$force = (is_bool($force) && $force || !is_bool($force) && ($this->dashboard && $this->request_count == 0 && (isset($_GET['settings-updated']) && preg_match('/^true|1$/i', $_GET['settings-updated']) || !is_array(get_option(__CLASS__ . '_result')))));
		$this->api_key = ($api_key != NULL) ? $api_key : get_option(__CLASS__ . '_api_key');
		$this->place_id = ($place_id != NULL) ? $place_id : get_option(__CLASS__ . '_place_id');		
		
		if (!$force)
		{
			if ($this->dashboard && $this->request_count == 0) 
			{
				$this->data = $this->retrieve_data();
			}
			
			if (is_array($this->data) && !empty($this->data))
			{
				$this->set_reviews(FALSE);
				return TRUE;
			}
			
			if (!$this->dashboard)
			{
				if ($this->demo)
				{
					$this->data = wp_cache_get('result_demo', __CLASS__);
				}
				elseif (wp_cache_get('result_valid', __CLASS__) != FALSE)
				{
					$this->data = wp_cache_get('result_valid', __CLASS__);
				}
				elseif (wp_cache_get('result', __CLASS__) != FALSE)
				{
					$this->data = wp_cache_get('result', __CLASS__);
				}
			}
			
			if (is_array($this->data) && !empty($this->data))
			{
				$this->set_reviews(FALSE);
				return TRUE;
			}
			
			if ($this->demo)
			{
				$this->data = $this->retrieve_data();
				$this->set_reviews(FALSE);
				return (is_array($this->data) && !empty($this->data));
			}
			
			global $wpdb;
			
			$this->data = ($this->retrieved_data_valid) ? get_option(__CLASS__ . '_result') : get_option(__CLASS__ . '_result_valid');
			
			if ((!is_array($this->data) || is_array($this->data) && empty($this->data)) && $this->request_count == 0)
			{
				$this->request_count++;
				$this->data = $this->retrieve_data();
				update_option(__CLASS__ . '_result', $this->data, 'no');
				wp_cache_add('result', $this->data, __CLASS__, 3600);
				$this->set_reviews(FALSE);
				return (is_array($this->data) && !empty($this->data));
			}
			
			$this->set_reviews(FALSE);
			return TRUE;
		}
		
		wp_cache_delete('structured_data', __CLASS__);
		wp_cache_delete((($this->demo) ? 'result_demo' : 'result'), __CLASS__);

		if ($this->request_count > 2)
		{
			return FALSE;
		}
		
		$this->data = $this->retrieve_data('array', TRUE);
		
		if ($this->demo)
		{
			wp_cache_add('result_demo', $this->data, __CLASS__, 3600);

			$this->set_reviews();
			
			return TRUE;
		}
		
		update_option(__CLASS__ . '_result', $this->data, 'no');
		wp_cache_add('result', $this->data, __CLASS__, 3600);
		
		if (is_array($this->result_valid) && !empty($this->result_valid))
		{
			update_option(__CLASS__ . '_result_valid', $this->result_valid, 'no');
			wp_cache_add('result_valid', $this->result_valid, __CLASS__, 3600);
			$this->retrieved_data_valid = (serialize($this->data) == serialize($this->result_valid));
		}
		
		$this->set_reviews(TRUE);

		return TRUE;
	}
	
	public function structured_data($return = FALSE, $data = array())
	{
		// Collect Structured Data to display on the home page
		
		$test = (is_bool($return) && $return);
		$string = (is_string($return) && $return == 'json');
		
		if ($this->demo)
		{
			if ($test)
			{
				return FALSE;
			}
			
			if ($string)
			{
				return NULL;
			}
			
			echo '';
			return;
		}
		
		if ($this->data == NULL)
		{
			$this->set_data();
			if (!isset($this->data['result']) || isset($this->data['result']) && !is_array($this->data['result']))
			{
				if ($test)
				{
					return FALSE;
				}
			
				if ($string)
				{
					return NULL;
				}
				
				echo '';
				return;
			}
		}
	
		if (!$this->valid() || $this->reviews_count(NULL, TRUE) < 1 || !isset($this->data['result']['name']) || isset($this->data['result']['name']) && $this->data['result']['name'] == NULL)
		{
			if ($test)
			{
				return FALSE;
			}
		
			if ($string)
			{
				return NULL;
			}
			
			echo '';
			return;
		}
		
		if ($test)
		{
			return TRUE;
		}
		
		if (!$string)
		{
			$structured_data = wp_cache_get('structured_data', __CLASS__);
			if (is_string($structured_data) && strlen($structured_data) > 20)
			{
				echo $structured_data;
				return;
			}
		}
		
		$name = $this->get_data('name');
		$logo = $this->get_data('logo');
		$address = $this->get_data('address');
		$rating = $this->get_data('rating');
		$rating_count = $this->get_data('rating_count');
		$telephone = get_option(__CLASS__ . '_telephone', FALSE);
		$business_type = (is_string(get_option(__CLASS__ . '_business_type'))) ? get_option(__CLASS__ . '_business_type') : FALSE;
		$price_range = (is_numeric(get_option(__CLASS__ . '_price_range'))) ? str_repeat(__('$', 'g-business-reviews-rating'), get_option(__CLASS__ . '_price_range')) : FALSE;
		
		extract($data, EXTR_OVERWRITE);

		$data = array(
			'@context' => 'http://schema.org',
			'@type' => 'LocalBusiness',
			'name' => ($name != NULL) ? $name : FALSE,
			'address' => ($address != NULL) ? $address : FALSE,
			'image' => ($logo != NULL) ? $logo : FALSE,
			'url' => get_site_url(),
			'telephone' => ($telephone != NULL) ? $telephone : FALSE,
			'additionalType' => ($business_type != NULL) ? $business_type : FALSE,
			'priceRange' => ($price_range != NULL) ? $price_range : FALSE,
			'AggregateRating' => array(
				'@type' => 'AggregateRating',
				'itemReviewed' => ($name != NULL) ? $name : FALSE,
				'ratingCount' => 5,
				'ratingValue' => (is_numeric($rating)) ? $rating : FALSE,
				'ratingCount' => (is_numeric($rating_count)) ? $rating_count : 0
			),
			'review' => array()
		);
		
		foreach ($this->reviews as $a)
		{
			if (!$a['status'])
			{
				continue;
			}
			
			if (count($data['review']) >= 5)
			{
				break;
			}
			
			$data['review'][] = array(
				'@type' => 'Review',
				'author' => ($a['author_name'] != NULL) ? $a['author_name'] : FALSE,
				'datePublished' => (function_exists('wp_date')) ? wp_date("Y-m-d", $a['time']) : date("Y-m-d", $a['time']),
				'description' => (strlen($a['text']) > 1) ? strip_tags($a['text']) : FALSE,
				'name' => ($name != NULL) ? $name : FALSE,
				'reviewRating' => array(
					'@type' => 'Rating',
					'bestRating' => 5,
					'ratingValue' => $a['rating'],
					'worstRating' => 1
				)
			);
		}
		
		$data = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
		$structured_data = '<script type="application/ld+json">' . PHP_EOL . '[ ' . $data . ' ]' . PHP_EOL . '</script>';
		wp_cache_add('structured_data', $structured_data, __CLASS__, 3600);
		
		if ($string)
		{
			return $data;
		}
		
		echo $structured_data;
		return;
	}
	
	private function admin_preview($post = array())
	{
		// Handling front-end previews from the Dashboard
		
		if (empty($this->data))
		{
			$this->set_data();
		}		
		
		$this->reviews_filter();
		
		$theme = (isset($post['theme'])) ? $post['theme'] : NULL;		
		$post['type'] = 'reviews';
		$post['errors'] = TRUE;
		$post['animate'] = FALSE;
		$post['stylesheet'] = (!isset($post['stylesheet']) || isset($post['stylesheet']) && (is_bool($post['stylesheet']) && $post['stylesheet'] || is_string($post['stylesheet']) && !preg_match('/^(?:f(?:alse)?|no?(?:ne)?|0|off|hide)$/i', $post['stylesheet'])));
		
		if (preg_match('/\bthree\b/i', $theme) && (!is_numeric($post['limit']) || is_numeric($post['limit']) && $post['limit'] > 9))
		{
			$post['limit'] = 9;
		}
		elseif (preg_match('/\bfour\b/i', $theme) && (!is_numeric($post['limit']) || is_numeric($post['limit']) && $post['limit'] > 12))
		{
			$post['limit'] = 12;
		}
		elseif (!preg_match('/\b(?:three|four)\b/i', $theme) && (!is_numeric($post['limit']) || is_numeric($post['limit']) && $post['limit'] > 10))
		{
			$post['limit'] = 10;
		}
		
		return $this->wp_display($post);
	}
	
	public function set_reviews($force = TRUE)
	{
		// Update stored record of all reviews collected
		
		if (!$this->valid() || empty($this->data) || !empty($this->data) && isset($this->data['result']) && !isset($this->data['result']['reviews']) || isset($this->data['result']) && !is_array($this->data['result']['reviews']))
		{
			return FALSE;
		}
		
		if (!$force)
		{
			if (!$this->dashboard && wp_cache_get((($this->demo) ? 'reviews_demo' : 'reviews'), __CLASS__) != FALSE)
			{
				$this->reviews = wp_cache_get((($this->demo) ? 'reviews_demo' : 'reviews'), __CLASS__);
			}
			
			if (is_array($this->reviews) && !empty($this->reviews))
			{
				$this->reviews_filtered = $this->reviews;
				return TRUE;
			}
		}
		
		wp_cache_delete('structured_data', __CLASS__);
		wp_cache_delete((($this->demo) ? 'reviews_demo' : 'reviews'), __CLASS__);
		
		global $wpdb;

		$this->reviews = (!$this->demo && is_array(get_option(__CLASS__ . '_reviews'))) ? get_option(__CLASS__ . '_reviews') : array();
		
		$max_id = (!empty($this->reviews) && function_exists('array_column')) ? max(array_column($this->reviews, 'id')) : count($this->reviews);
		$count = 1;
		
		foreach ($this->data['result']['reviews'] as $review)
		{
			$a = array();
			$key = $review['time'] . '_' . $review['rating'] . '_' . md5($review['author_name'] . '_' . substr($review['text'], 0, 100));
			
			if (array_key_exists($key, $this->reviews))
			{
				if (!$this->demo)
				{
					$this->reviews[$key]['relative_time_description'] = $review['relative_time_description'];
				}
				
				$this->reviews[$key]['checked'] = time();
				continue;
			}
			
			if (!$this->demo)
			{
				foreach (array_keys($this->reviews) as $key_temp)
				{
					$author_url_id = (preg_match('/^.+[^\d](\d{20,120})[^\d].*$/', $this->reviews[$key_temp]['author_url'], $m)) ? $m[1] : NULL;
					$author_check = (preg_match('/^.+[^\d](\d{20,120})[^\d].*$/', $review['author_url'], $m)) ? ($author_url_id == $m[1]) : ($author_url_id == NULL);
		
					if ($this->reviews[$key_temp]['author_name'] != $review['author_name'] || !$author_check)
					{
						continue;
					}
	
					$review = array_merge($this->reviews[$key_temp], $review);
					unset($this->reviews[$key_temp]);
	
					$review['order'] = $count;
					$review['retrieved'] = time();
					$review['time_estimate'] = FALSE;
					
					$this->reviews[$key] = $review;
					$count++;
					continue(2);
				}
			}
			
			$a['id'] = $max_id + $count;
			$a['place_id'] = ($this->demo) ? NULL : $this->place_id;
			$a['order'] = $count;
			$a['checked'] = NULL;
			$a['retrieved'] = time();
			$a['imported'] = FALSE;
			$a['time_estimate'] = FALSE;
			$a['status'] = TRUE;
			
			$this->reviews[$key] = $this->sanitize_array($a + $review);
			$count++;
		}
		
		uksort($this->reviews, function ($a, $b)
			{
				if (isset($this->reviews[$a]['imported']) && isset($this->reviews[$b]['imported']))
				{
					return max($this->reviews[$b]['retrieved']*2, $this->reviews[$a]['imported']) - ($this->reviews[$b]['order'] * 0.1) - max($this->reviews[$a]['retrieved']*2, $this->reviews[$b]['imported']) - ($this->reviews[$a]['order'] * 0.1);
				}
				return $this->reviews[$b]['retrieved'] - ($this->reviews[$b]['order'] * 0.1) - $this->reviews[$a]['retrieved'] - ($this->reviews[$a]['order'] * 0.1);
			}
		);
		
		wp_cache_add((($this->demo) ? 'reviews_demo' : 'reviews'), $this->reviews, __CLASS__, 3600);
		
		$this->reviews_filtered = $this->reviews;

		if ($this->demo || ($count == 1))
		{
			return TRUE;
		}

		update_option(__CLASS__ . '_reviews', $this->reviews, 'no');
		
		return TRUE;
	}
	
	private function delete_icon()
	{
		// Delete the icon image
		
		$this->icon_image_id = NULL;
		$this->icon_image_url = NULL;
		update_option(__CLASS__ . '_icon', $this->icon_image_id);

		return TRUE;
	}
	
	private function set_icon($id = NULL)
	{
		// Set the icon image
		
		if (is_numeric($id))
		{
			update_option(__CLASS__ . '_icon', $id);
			$this->icon_image_id = $id;
		}
		else
		{
			$this->icon_image_id = get_option(__CLASS__ . '_icon');
		}
		
		if (is_numeric($this->icon_image_id))
		{
			global $wpdb;
			
			$this->icon_image_url = $wpdb->get_var("SELECT `guid` FROM `" . $wpdb->posts . "` WHERE ID='" . intval($this->icon_image_id) . "' LIMIT 1");
		}
		
		return TRUE;
	}
	
	private function delete_logo()
	{
		// Delete the logo image for Structured Data
		
		$this->logo_image_id = NULL;
		$this->logo_image_url = NULL;
		update_option(__CLASS__ . '_logo', $this->logo_image_id);

		return TRUE;
	}
	
	private function set_logo($id = NULL)
	{
		// Set the logo image for Structured Data
		
		if (is_numeric($id))
		{
			update_option(__CLASS__ . '_logo', $id);
			$this->logo_image_id = $id;
		}
		else
		{
			$this->logo_image_id = get_option(__CLASS__ . '_logo');
		}
		
		if (is_numeric($this->logo_image_id))
		{
			global $wpdb;
			
			$this->logo_image_url = $wpdb->get_var("SELECT `guid` FROM `" . $wpdb->posts . "` WHERE ID='" . intval($this->logo_image_id) . "' LIMIT 1");
		}
		
		return TRUE;
	}
	
	public function server_ip()
	{
		// Retrieve an accurate IP Address for the web server
		
		if (is_string(wp_cache_get('server_ip', __CLASS__)))
		{
			return wp_cache_get('server_ip', __CLASS__);
		}
		
		if (function_exists('wp_remote_get') && function_exists('wp_remote_retrieve_body'))
		{
			$a = preg_split('/,/i', wp_remote_retrieve_body(wp_remote_get('http://ip6.me/api/')));
			
			if (preg_match('/^(((([1]?\d)?\d|2[0-4]\d|25[0-5])\.){3}(([1]?\d)?\d|2[0-4]\d|25[0-5]))|([\da-f]{1,4}(\:[\da-f]{1,4}){7})|(([\da-f]{1,4}:){0,5}::([\da-f]{1,4}:){0,5}[\da-f]{1,4})$/i', $a[1]))
			{
				$string = strtolower($a[1]);
				wp_cache_set('server_ip', $string, __CLASS__, 3600);
				return $string;
			}
			
			$string = preg_replace('/^.+ip\s+address[:\s]+\[?([^<>\s\b\]]+)\]?.*$/i', '$1', wp_remote_retrieve_body(wp_remote_get('http://checkip.dyndns.com/')));
			
			if (preg_match('/^(((([1]?\d)?\d|2[0-4]\d|25[0-5])\.){3}(([1]?\d)?\d|2[0-4]\d|25[0-5]))|([\da-f]{1,4}(\:[\da-f]{1,4}){7})|(([\da-f]{1,4}:){0,5}::([\da-f]{1,4}:){0,5}[\da-f]{1,4})$/i', $string))
			{
				$string = strtolower($string);
				wp_cache_set('server_ip', $string, __CLASS__, 3600);
				return $string;
			}
		}

		if (function_exists('gethostname') && function_exists('gethostbyname'))
		{
			$string = strtolower(gethostbyname(gethostname()));
			wp_cache_set('server_ip', $string, __CLASS__, 3600);
			return $string;
		}
		
		if (isset($_SERVER['SERVER_ADDR']))
		{
			wp_cache_set('server_ip', $_SERVER['SERVER_ADDR'], __CLASS__, 3600);
			return $_SERVER['SERVER_ADDR'];
		}
		
		return NULL;
	}
	
	public function data_hunter($format = 'array', $force = FALSE)
	{
		// Find all references to existing Google Reviews, API Key and Place ID
		
		if (!$force && is_string(get_option(__CLASS__ . '_place_id')))
		{
			switch ($format)
			{
			case 'boolean':
			case 'test':
				return FALSE;
			case 'json':
				return json_encode(NULL);
			default:
				break;
			}
			return array();
		}
		
		global $wpdb;
		
		$ret = array();
		
		if (get_option('we_are_open_api_key') != NULL && get_option('we_are_open_place_id') != NULL)
		{
			$ret = array(
				'api_key' => get_option('we_are_open_api_key'),
				'place_id' => get_option('we_are_open_place_id'),
				'language' => get_option('we_are_open_language')
			);
		}

		if (empty($ret) && is_string(get_option('grw_google_api_key')) && $wpdb->get_var("SHOW TABLES LIKE '" . $wpdb->prefix . "grp_google_place'") == $wpdb->prefix . 'grp_google_place')
		{
			$id = $wpdb->get_var("SELECT `id` FROM `" . $wpdb->prefix . "grp_google_place` ORDER BY `id` DESC LIMIT 1");
			$place_id = $wpdb->get_var("SELECT `place_id` FROM `" . $wpdb->prefix . "grp_google_place` WHERE `id` = '" . esc_sql($id) . "' LIMIT 1");
			$reviews = $wpdb->get_results("SELECT * FROM `" . $wpdb->prefix . "grp_google_review` WHERE `google_place_id` = '" . intval($id) . "'");
			$ret = array(
				'api_key' => get_option('grw_google_api_key'),
				'place_id' => $place_id,
				'reviews' => $reviews
			);
		}
		
		if (empty($ret) && is_array(get_option('wpfbr_google_options')))
		{
			$d = get_option('wpfbr_google_options');
			if ($d['select_google_api'] != 'default' && is_string($d['google_api_key']))
			{
				$reviews = array();
				
				if ($wpdb->get_var("SHOW TABLES LIKE '" . $wpdb->prefix . "wpfb_reviews'") == $wpdb->prefix . 'wpfb_reviews')
				{
					$reviews = $wpdb->get_results("SELECT * FROM `" . $wpdb->prefix . "wpfb_reviews`");
				}
				
				$ret = array(
					'api_key' => $d['google_api_key'],
					'place_id' => (isset($d['google_location_set']['place_id'])) ? $d['google_location_set']['place_id'] : NULL,
					'language' => (isset($d['google_language_option'])) ? $d['google_language_option'] : NULL,
					'reviews' => $reviews
				);
			}
		}
		
		if (empty($ret) && is_array(get_option('googleplacesreviews_options')))
		{
			$d = get_option('googleplacesreviews_options');
			$w = array('place_id' => NULL);
			
			if (array_key_exists('google_places_api_key', $d))
			{
				$w = get_option('googleplacesreviews_options');
				if (is_array($w) && array_key_exists('place_id', $w))
				{
					$place_id = $w['place_id'];
				}
				
				$ret = array(
					'api_key' => $d['google_places_api_key'],
					'place_id' => $place_id
				);
			}
		}
		
		if (empty($ret) && is_string(get_option('google_places_api_key')))
		{
			$ret = array(
				'api_key' => get_option('google_places_api_key')
			);
		}
		
		switch ($format)
		{
		case 'boolean':
		case 'test':
			$ret = (!empty($ret));
			break;
		case 'json':
			if (isset($ret['reviews']))
			{
				$ret['reviews'] = (is_array($ret['reviews'])) ? count($ret['reviews']) : 0;
			}
			
			$ret = json_encode($ret);
			break;
		default:
			break;
		}
		
		return $ret;
	}
	
	public function reviews_count($place_id = NULL, $status = NULL)
	{
		// Count the number of reviews stored
				
		$this->set_reviews();
		
		if (is_string($place_id) || is_bool($place_id) && $place_id)
		{
			$place_id = $this->place_id;
		}
		
		$count = 0;
		
		if (!is_array($this->reviews))
		{
			return $count;
		}

		if ($place_id == NULL && !is_bool($status))
		{
			return count($this->reviews);
		}
		
		foreach ($this->reviews as $a)
		{
			if (is_bool($status))
			{
				if (is_string($place_id))
				{
					if ($a['place_id'] == $place_id)
					{
						$count++;
					}
					
					continue;
				}
				
				if ($a['status'] == $status)
				{
					$count++;
				}
				
				continue;
			}
			
			if ($a['place_id'] == $place_id)
			{
				$count++;
			}
		}
		
		return $count;
	}
	
	private function reviews_filter($filters = NULL, $atts = NULL)
	{
		// Filter review data
		
		if (!$this->set_reviews() || empty($this->reviews))
		{
			return FALSE;
		}
		
		if (!is_array($filters))
		{
			$filters = array();
		}
		
		if (!is_array($atts))
		{
			$atts = array();
		}
		
		$count = 0;
		$ids = (is_numeric($filters['id']) && $filters['id'] > 0) ? array(intval($filters['id'])) : ((preg_match('/^(?:\d+)(?:,\s*(?:\d+))+$/', $filters['id'])) ? array_unique(preg_split('/[^\d]+/', $filters['id'])) : array());
		$id = (!empty($ids)) ? $ids[0] : NULL;
		$place_id = (!$this->demo && is_string($filters['place_id']) && strlen($filters['place_id']) >= 20) ? $filters['place_id'] : NULL;
		$language = (isset($filters['language']) && is_string($filters['language']) && strlen($filters['language']) >= 2 && strlen($filters['language']) <= 16) ? preg_replace('/^([a-z]{2,3}).*$/i', '$1', strtolower($filters['language'])) : NULL;
		$min = ($id == NULL && is_numeric($filters['min']) && $filters['min'] >= 1 && $filters['min'] <= 5) ? intval($filters['min']) : NULL;
		$max = ($id == NULL && is_numeric($filters['max']) && $filters['max'] >= 1 && $filters['max'] <= 5) ? intval($filters['max']) : NULL;
		$offset = ($id == NULL && is_numeric($filters['offset']) && $filters['offset'] >= 0) ? intval($filters['offset']) : 0;
		$limit = ($id == NULL && is_numeric($filters['limit']) && $filters['limit'] >= 0) ? intval($filters['limit']) : NULL;
		$excerpt = (is_numeric($filters['excerpt']) && $filters['excerpt'] >= 20) ? intval($filters['excerpt']) : NULL;
		$review_text_min = (is_numeric($filters['review_text_min']) && $filters['review_text_min'] >= 0) ? intval($filters['review_text_min']) : NULL;
		$review_text_max = (is_numeric($filters['review_text_max']) && $filters['review_text_max'] >= 0 && (!is_numeric($filters['review_text_min']) || is_numeric($filters['review_text_min']) && $filters['review_text_min'] <= $filters['review_text_max'])) ? intval($filters['review_text_max']) : NULL;
		$review_text_inc = (strlen($filters['review_text_inc']) > 1) ? array_unique(preg_split('/,\s*/', $filters['review_text_inc'], 10)) : array();
		$review_text_exc = (strlen($filters['review_text_exc']) > 1) ? array_unique(preg_split('/,\s*/', $filters['review_text_exc'], 10)) : array();

		$limit = (is_numeric($limit)) ? intval($limit) : ((!array_key_exists('limit', $atts)) ? get_option(__CLASS__ . '_review_limit', NULL) : NULL);
		$sort = ($id == NULL && is_string($filters['sort'])) ? preg_replace('/[^\w_-]/', '', $filters['sort']) : get_option(__CLASS__ . '_review_sort', NULL);
		$min = (is_numeric($min)) ? intval($min) : get_option(__CLASS__ . '_rating_min', NULL);
		$max = (is_numeric($max)) ? intval($max) : get_option(__CLASS__ . '_rating_max', NULL);
		$review_text_min = (is_numeric($review_text_min) && $review_text_min >= 0) ? intval($review_text_min) : get_option(__CLASS__ . '_review_text_min', NULL);
		$review_text_max = (is_numeric($review_text_max) && $review_text_max >= 0) ? intval($review_text_max) : get_option(__CLASS__ . '_review_text_max', NULL);

		if (is_numeric($limit) && $limit == 0)
		{
			return TRUE;
		}
		
		switch($sort)
		{
		case 'relevance':
		case 'relevance_desc':
			$sort = NULL;
			break;
		case 'date':
		case 'rating':
			$sort .= '_desc';
			break;
		case 'id':
		case 'author_name':
			$sort .= '_asc';
			break;
		case 'time':
		case 'time_desc':
		case 'relative_time_description':
		case 'relative_time_description_desc':
			$sort = 'date_desc';
			break;
		case 'time_asc':
		case 'relative_time_description_asc':
			$sort = 'date_asc';
			break;
		case 'name':
		case 'author':
		case 'name_asc':
		case 'author_asc':
			$sort = 'author_name_asc';
			break;
		case 'name_desc':
		case 'author_desc':
			$sort = 'author_name_desc';
			break;
		case 'random':
		case 'shuffle':
		case 'random-shuffle':
		case 'random_shuffle':
			$sort = 'shuffle';
			break;
		}

		$this->review_sort_option = ($sort != NULL && array_key_exists($sort, $this->review_sort_options)) ? $sort : NULL;
		
		if (!empty($ids))
		{
			$this->reviews_filtered = array();

			if (is_string($this->review_sort_option) && $sort == 'shuffle')
			{
				shuffle($ids);
			}

			foreach ($ids as $id)
			{
				foreach ($this->reviews as $key => $a)
				{
					if ($a['id'] != $id)
					{
						continue;
					}
					$this->reviews_filtered[$key] = $a;
					break;
				}
			}
			return TRUE;
		}

		foreach ($this->reviews as $key => $a)
		{
			if (!array_key_exists($key, $this->reviews_filtered))
			{
				continue;
			}

			if (!$this->dashboard && !$a['status'])
			{
				unset($this->reviews_filtered[$key]);
				continue;
			}
			
			if (is_numeric($min) && $a['rating'] < $min || is_numeric($max) && $a['rating'] > $max)
			{
				unset($this->reviews_filtered[$key]);
				continue;
			}
			
			if ($place_id != NULL && $a['place_id'] != $place_id)
			{
				unset($this->reviews_filtered[$key]);
				continue;
			}
			
			if ($language != NULL && isset($a['language']) && ($a['language'] == NULL || strtolower($a['language']) != $language))
			{
				unset($this->reviews_filtered[$key]);
				continue;
			}
									
			if (is_numeric($review_text_min) && $review_text_min > strlen(strip_tags($a['text'])) || is_numeric($review_text_max) && $review_text_max < strlen(strip_tags($a['text'])))
			{
				unset($this->reviews_filtered[$key]);
				continue;
			}
									
			if (!empty($review_text_inc) || !empty($review_text_exc))
			{
				$t = strip_tags($a['text']);
				$inc = $exc = FALSE;
					
				if (!empty($review_text_inc))
				{
					foreach ($review_text_inc as $v)
					{
						if (preg_match('/\b' . preg_quote($v, '/'). '\b/i', $t))
						{
							$inc = TRUE;
							break;
						}
					}
					
					if (!$inc)
					{
						unset($this->reviews_filtered[$key]);
						continue;
					}
				}

				if (!empty($review_text_exc))
				{
					foreach ($review_text_exc as $v)
					{
						if (preg_match('/\b' . preg_quote($v, '/'). '\b/i', $t))
						{
							$exc = TRUE;
							break;
						}
					}
					
					if ($exc)
					{
						unset($this->reviews_filtered[$key]);
						continue;
					}
				}
			}
			
			$count++;
		}
		
		if ($this->review_sort_option != NULL)
		{
			if ($this->review_sort_option == 'shuffle')
			{
				$offset = 0;
				$list = $this->reviews_filtered;
				$keys = array_keys($this->reviews_filtered);
				$this->reviews_filtered = array();
				shuffle($keys);
				foreach ($keys as $k)
				{ 
					$this->reviews_filtered[$k] = $list[$k];
				}
			}
			elseif ($this->review_sort_option == 'relevance_asc')
			{
				$this->reviews_filtered = array_reverse($this->reviews_filtered, TRUE);
			}
			else
			{
				uksort($this->reviews_filtered, function ($b, $a)
					{
						$v = $this->reviews_filtered[$a][$this->review_sort_options[$this->review_sort_option]['field']];
						$w = $this->reviews_filtered[$b][$this->review_sort_options[$this->review_sort_option]['field']];
						
						if ($this->review_sort_options[$this->review_sort_option]['field'] != 'id' && is_numeric($v) && $v < 10 && is_numeric($w) && $w < 10 && is_numeric($this->reviews_filtered[$a]['time']) && $this->reviews_filtered[$a]['time'] > 100000000 && is_numeric($this->reviews_filtered[$b]['time']) && $this->reviews_filtered[$b]['time'] > 100000000)
						{
							$v -= (1000000000/$this->reviews_filtered[$a]['time']);
							$w -= (1000000000/$this->reviews_filtered[$b]['time']);
							
							$v *= 100;
							$w *= 100;
						}
						
						if (is_numeric($v) && is_numeric($w))
						{
							return round($v) - round($w);
						}
						
						if (strtolower($v) == strtolower($w))
						{
							return 0;
						}
						
						$c = $d = array(strtolower($v), strtolower($w));
						arsort($c, SORT_REGULAR);
						return (array_keys($c) === array_keys($d)) ? 1 : -1;
					}
				);
				
				if ($this->review_sort_options[$this->review_sort_option]['asc'])
				{
					$this->reviews_filtered = array_reverse($this->reviews_filtered, TRUE);
				}
			}
		}
		
		if (is_numeric($offset) && is_numeric($limit) && $limit < $count)
		{
			$this->reviews_filtered = array_splice($this->reviews_filtered, $offset, $limit);
		}

		return TRUE;
	}
	
	private function sanitize_array($array)
	{
		// Sanitize array data to remove characters that can cause update_option() to fail
		
		if (get_option(__CLASS__ . '_additional_array_sanitization'))
		{
			array_walk_recursive(
				$array,
				function (&$v)
				{
					if (is_string($v) && preg_match('/[^ -\x{2122}]\s+|\s*[^ -\x{2122}]/u', $v))
					{
						$v = preg_replace('/[^ -\x{2122}]\s+|\s*[^ -\x{2122}]/u', '', $v);
					}
				}
			);
		}
		
		return $array;
	}
	
	public function sanitize_api_key($api_key)
	{
		// Sanitize data from API Key setting input
		
		if (strlen($api_key) < 10)
		{
			$api_key = NULL;
		}
		
		if (get_option(__CLASS__ . '_api_key') != $api_key)
		{
			wp_cache_delete('structured_data', __CLASS__);
			wp_cache_delete('result', __CLASS__);
			update_option(__CLASS__ . '_result', NULL, 'no');
			$this->data = array();
			$this->result = array();
		}
		
		return $api_key;
	}
	
	public function sanitize_place_id($place_id)
	{
		// Sanitize data from Place ID setting input
		
		if (strlen($place_id) < 10)
		{
			$place_id = NULL;
		}
		
		if (get_option(__CLASS__ . '_place_id') != $place_id)
		{
			$api_key = get_option(__CLASS__ . '_api_key');
			wp_cache_delete('structured_data', __CLASS__);
			wp_cache_delete('result', __CLASS__);
			update_option(__CLASS__ . '_result', NULL, 'no');
			update_option(__CLASS__ . '_structured_data', FALSE, 'yes');
			$this->place_id = $place_id;
			$this->data = array();
			$this->result = array();
			
			if ($place_id != NULL && $api_key != NULL)
			{
				$this->set_data(TRUE, $api_key, $place_id);
			}
			elseif ($place_id == NULL && $api_key == NULL)
			{
				wp_cache_delete('structured_data', __CLASS__);
				wp_cache_delete('reviews', __CLASS__);
				$this->reviews = array();
				$this->reviews_filtered = array();
			}
		}
		
		return $place_id;
	}
	
	public function sanitize_demo($demo)
	{
		// Handle switch between active and demo versions
		
		$demo = (bool)$demo;

		if (get_option(__CLASS__ . '_demo') != $demo)
		{
			wp_cache_delete('structured_data', __CLASS__);
			wp_cache_delete('result', __CLASS__);
			wp_cache_delete('result_demo', __CLASS__);
			update_option(__CLASS__ . '_result', NULL, 'no');
			$this->data = array();
			$this->result = array();
			$this->reviews = array();
			$this->reviews_filtered = array();
		}
		
		return $demo;
	}
	
	public function get_reviews($format = 'array')
	{
		// Get all reviews in various formats
		
		$this->set_reviews();
		$html = '';
		
		switch ($format)
		{
		case 'array':
			return $this->reviews;
		case 'html':
			$show_place_id = ($this->reviews_count(TRUE) != $this->reviews_count());
			$show_language = FALSE;
			
			foreach ($this->reviews as $a)
			{
				if (!array_key_exists('language', $a) || $a['language'] == NULL)
				{
					continue;
				}

				if (is_bool($show_language))
				{
					$show_language = $a['language'];
					continue;
				}
				
				if ($show_language != $a['language'])
				{
					$show_language = TRUE;
					break;
				}
			}
			
			if (!is_bool($show_language))
			{
				$show_language = FALSE;
			}
			
			$html .= '<table id="reviews-table" class="wp-list-table widefat fixed striped reviews-table">
    <thead>
        <tr>
            <th class="id number" title="ID">ID</th>
            <th class="submitted date" title="Submitted">Submitted</th>
            <th class="author" title="Author">Author</th>
            <th class="rating" title="Rating">Rating</th>
            <th class="text" title="Text">Text</th>
';
			if ($show_language)
			{
				$html .= '            <th class="language" title="Language">Language</th>
';
			}

			$html .= '            <th class="retrieved date" title="Retrieved">Retrieved</th>
';
			if ($show_place_id)
			{
				$html .= '            <th class="place-id" title="Place ID">Place ID</th>
';
			}

			$html .= '        </tr>
    </thead>
    <tbody>
';		
			foreach ($this->reviews as $key => $a)
			{
				$html .= '        <tr id="' . esc_attr(preg_replace('/[^0-9a-z-]/', '-', $key)) . '" class="review ' . esc_attr('rating-' . $a['rating']) . esc_attr((!$a['status']) ? ' inactive' : '') . esc_attr((array_key_exists('time_estimate', $a) && $a['time_estimate']) ? ' estimate' : '') . '" data-order="' . esc_attr($a['order']) . '">
            <td class="id number">' . esc_html($a['id']) . ' <a href="' . esc_attr('#' . preg_replace('/[^0-9a-z-]/', '-', $key)) . '" class="show-hide" title="' . (($a['status']) ? 'Hide' : 'Show') . '">' . (($a['status']) ? '<span class="dashicons dashicons-visibility"></span>' : '<span class="dashicons dashicons-hidden"></span>') . '</a>' . ((array_key_exists('time_estimate', $a) && $a['time_estimate']) ? '<a href="' . esc_attr('#' . preg_replace('/[^0-9a-z-]/', '-', $key)) . '" class="remove" title="' . esc_attr(__('Remove', 'g-business-reviews-rating')) . '"><span class="dashicons dashicons-no"></span></a>' : '') . '</td>
            <td class="submitted date"><span class="date">' . esc_html((array_key_exists('time_estimate', $a) && $a['time_estimate']) ? date("Y/m/d", $a['time']) : date("Y/m/d H:i", $a['time'])) . '</span>' . ((array_key_exists('time_estimate', $a) && $a['time_estimate']) ? '<input type="date" id="' . esc_attr('submitted-' . preg_replace('/[^0-9a-z-]/', '-', $key)) . '" class="time-estimate" name="submitted[]" value="' . esc_attr(date("Y-m-d", $a['time'])) . '">' : '') . '</td>
            <td class="author">
				<span class="name">' . (($a['author_url'] != NULL) ? '<a href="' . esc_attr($a['author_url']) . '" target="_blank">' : '') . esc_html($a['author_name']) . (($a['author_url'] != NULL) ? '</a>' : '') . '</span>
				' . (($a['author_url'] != NULL && $a['profile_photo_url'] != NULL) ? '<span class="avatar"><a href="' . esc_attr($a['author_url']) . '" target="_blank"><img src="' . esc_attr($a['profile_photo_url']) . '" alt="Avatar"></a></span>' : '') . '
			</td>
            <td class="rating">' . str_repeat('★', $a['rating']) . (($a['rating'] < 5) ? '<span class="not">' . str_repeat('☆', (5 - $a['rating'])) . '</span>' : '') . ' <span class="rating-number">(' . esc_html($a['rating']) . ')</span></td>
            <td class="text"><div class="text-wrap">' . (($a['text'] != NULL) ? preg_replace('/(\r\n|\r|\n)+/', '<br>' . PHP_EOL . '            	', esc_html(strip_tags($a['text']))) : '<span class="none" title="' . esc_attr(__('None', 'g-business-reviews-rating')) . '">—</span>') . '</div></td>
';
			if ($show_language)
			{
				$html .= '            <td class="language">' . ((isset($a['language'])) ? esc_html($a['language']) : '&mdash;') . '</td>
';
			}

			$html .= '            <td class="retrieved date">' . ((is_numeric($a['retrieved'])) ? esc_html(date("Y/m/d H:i", $a['retrieved'])) : ((is_numeric($a['imported'])) ? '<span class="none" title="' . esc_attr(__('Imported', 'g-business-reviews-rating') . ': ' . date("Y/m/d H:i", $a['imported'])) . '">—</span>' : '<span class="none" title="' . esc_attr(__('None', 'g-business-reviews-rating')) . '">—</span>')) . '</td>
';
			if ($show_place_id)
			{
				$html .= '            <td class="place-id"><span class="abbr" title="' . (($this->demo) ? 'Abcde-0123456789-Fghij-01234-z' : esc_attr($a['place_id'])) . '">' . (($this->demo) ? 'Abcde…z' : esc_html(substr($a['place_id'], 0, 5)) . '…' . esc_html(substr($a['place_id'], -1, 1))) . '</span></td>
';
			}

			$html .= '        </tr>
';
			}

			$html .= '    </tbody>
</table>
';
			return $html;
		}
		return;
	}
	
	public function wp_display($atts = NULL, $content = NULL, $shortcode = NULL)
	{
		// Display HTML from shortcodes 
		
		global $wpdb;
		
		$type_check = NULL;
		$shortcode_defaults = array(
			'id' => NULL,
			'place_id' => NULL,
			'language' => NULL,
			'type' => NULL,
			'min' => NULL,
			'max' => NULL,
			'offset' => NULL,
			'limit' => NULL,
			'sort' => NULL,
			'review_item_order' => NULL,
			'review_text' => NULL,
			'review_text_min' => NULL,
			'review_text_max' => NULL,
			'review_text_inc' => NULL,
			'review_text_exc' => NULL,
			'excerpt' => NULL,
			'target' => NULL,
			'theme' => NULL,
			'stylesheet' => NULL,
			'class' => NULL,
			'summary' => NULL,
			'animate' => NULL,
			'avatar' => NULL,
			'name_format' => NULL,
			'date' => NULL,
			'link' => NULL,
			'reviews_link' => NULL,
			'write_review_link' => NULL,
			'reviews_link_class' => NULL,
			'write_review_link_class' => NULL,
			'link_class' => NULL,
			'attribution' => NULL,
			'icon' => NULL,
			'name' => NULL,
			'vicinity' => NULL,
			'stars' => NULL,
			'stars_gray' => NULL,
			'stars_grey' => NULL,
			'count' => NULL,
			'review_word' => NULL,
			'more' => NULL,
			'html_tags' => NULL,
			'multiplier' => NULL,
			'errors' => NULL
		);
		$types = array(
			'reviews',
			'rating',
			'rating_count',
			'review_count',
			'reviews_url',
			'reviews_link',
			'write_review_url',
			'write_review_link',
			'maps_url',
			'maps_link',
			'structured_data'
		);
		
		foreach ($types as $t)
		{
			$shortcode_defaults[$t] = 0;
		}
		
		$args = shortcode_atts($shortcode_defaults, $atts);
		
		if (!is_array($atts))
		{
			$atts = array();
		}
	
		foreach ($types as $t)
		{
			if (in_array($t, $atts))
			{
				$type_check = $t;
				break;
			}
		}
		
		foreach ($args as $k => $v)
		{
			if (is_string($v) && (strlen($v) == 0 || $v == 'NULL' || $v == 'null'))
			{
				$args[$k] = NULL;
			}
		}
				
		extract($args, EXTR_SKIP);
		
		$id_name = (is_string($id) && preg_match('/^[a-z][0-9a-z_-]*[0-9a-z]$/i', $id)) ? strtolower($id) : NULL;
		$place_id = (is_string($place_id) && strlen($place_id) >= 20) ? $place_id : NULL;
		$type = (is_string($type)) ? preg_replace('/[^\w_]/', '_', trim(strtolower($type))) : $type_check;
		$target = (is_string($target)) ? preg_replace('/[^\w_-]/', '-', trim(strtolower($target))) : NULL;
		$theme = (is_string($theme)) ? preg_replace('/[^\w _-]/', '-', trim(strtolower($theme))) : NULL;
		$class = (is_string($class)) ? preg_replace('/[^\w _-]/', '-', trim(strtolower($class))) : NULL;
		$stylesheet = (is_bool($stylesheet) || is_string($stylesheet) && !preg_match('/^(?:f(?:alse)?|no?(?:ne)?|0|off|hide)$/i', $stylesheet)) ? (is_bool($stylesheet) && $stylesheet || is_string($stylesheet)) : ((!array_key_exists('stylesheet', $atts)) ? get_option(__CLASS__ . '_stylesheet', NULL) : TRUE);
		$summary = (is_null($summary) || is_bool($summary) && $summary || is_string($summary) && preg_match('/^(?:t(?:rue)?|y(?:es)?|1|on|show)$/i', $summary));
		$icon = (is_null($icon) || is_bool($icon) && $icon || is_string($icon) && preg_match('/^(?:t(?:rue)?|y(?:es)?|1|on|show)$/', $icon)) ? TRUE : ((is_string($icon) && preg_match('/.+\.(?:jpe?g|png|svg|gif)/i', $icon)) ? $icon : FALSE);
		$name = (is_null($name) || is_bool($name) && $name || is_string($name) && preg_match('/^(?:t(?:rue)?|y(?:es)?|1|on|show)$/i', $name)) ? TRUE : ((is_string($name) && !preg_match('/^(?:f(?:alse)?|no?(?:ne)?|0|off|hide)$/i', $name)) ? $name : FALSE);
		$vicinity = (is_null($vicinity) || is_bool($vicinity) && $vicinity || is_string($vicinity) && preg_match('/^(?:t(?:rue)?|y(?:es)?|1|on|show)$/i', $vicinity)) ? TRUE : ((is_string($vicinity) && !preg_match('/^(?:f(?:alse)?|no?(?:ne)?|0|off|hide)$/i', $vicinity)) ? $vicinity : FALSE);
		$stars = (is_null($stars) || is_bool($stars) && $stars || is_string($stars) && preg_match('/^(?:t(?:rue)?|y(?:es)?|1|on|show|svg|vector)$/i', $stars)) ? TRUE : ((is_string($stars) && preg_match('/(#(?:[0-9a-f]{2}){2,4}|#[0-9a-f]{3}|(?:rgba?|hsla?)\((?:\d+%?(?:deg|rad|grad|turn)?(?:,|\s)+){2,3}[\s\/]*[\d\.]+%?\))/i', $stars)) ? $stars : ((is_string($stars) && preg_match('/^(?:html|css)$/i', $stars)) ? strtolower($stars) : FALSE));
		$stars_grey = ((is_string($stars_grey) && preg_match('/(#(?:[0-9a-f]{2}){2,4}|#[0-9a-f]{3}|(?:rgba?|hsla?)\((?:\d+%?(?:deg|rad|grad|turn)?(?:,|\s)+){2,3}[\s\/]*[\d\.]+%?\))/i', $stars_grey)) ? $stars_gray : ((is_string($stars_grey) && preg_match('/^(?:html|css)$/i', $stars_grey)) ? strtolower($stars_grey) : NULL));
		$stars_gray = ((is_string($stars_gray) && preg_match('/(#(?:[0-9a-f]{2}){2,4}|#[0-9a-f]{3}|(?:rgba?|hsla?)\((?:\d+%?(?:deg|rad|grad|turn)?(?:,|\s)+){2,3}[\s\/]*[\d\.]+%?\))/i', $stars_gray)) ? $stars_gray : ((is_string($stars_gray) && preg_match('/^(?:html|css)$/i', $stars_gray)) ? strtolower($stars_gray) : $stars_grey));
		$count = (is_null($count) || is_bool($count) && $count || is_string($count) && preg_match('/^(?:t(?:rue)?|y(?:es)?|1|on|show)$/i', $count));
		$avatar = (is_null($avatar) || is_bool($avatar) && $avatar || is_string($avatar) && preg_match('/^(?:t(?:rue)?|y(?:es)?|1|on|show)$/', $avatar)) ? TRUE : ((is_string($avatar) && preg_match('/.+\.(?:jpe?g|png|svg|gif)/i', $avatar)) ? $avatar : FALSE);
		$name_format = (is_bool($name_format) && !$name_format || is_string($name_format) && preg_match('/^(?:f(?:alse)?|no?(?:ne)?|0|off|hide)$/i', $name_format)) ? FALSE : ((is_string($name_format) && preg_match('/initials?/i', $name_format)) ? $name_format : NULL);
		$date = (is_null($date) || is_bool($date) && $date || is_string($date) && preg_match('/^(?:true|yes|1|on|show|relative)$/', $date)) ? TRUE : ((is_string($date) && preg_match('/^[aABcdDeFgGhHiIjLlmMNnoOPrSstTuUvwWYyzZ ,.()\[\]\/_-]{1,20}$/', $date) && !preg_match('/^(?:false|no(?:ne)?|0|off|hide)$/i', $date)) ? $date : FALSE);
		$link = (is_bool($link) && $link || is_string($link) && preg_match('/^(?:t(?:rue)?|y(?:es)?|1|on|show)$/i', $link)) ? TRUE : ((is_string($link) && !preg_match('/^(?:f(?:alse)?|no?(?:ne)?|0|off|hide)$/i', $link)) ? $link : FALSE);
		$link_class = (is_string($link_class)) ? preg_replace('/[^\w _-]/', '-', trim(strtolower($link_class))) : NULL;
		$reviews_link = (is_bool($reviews_link) && $reviews_link || is_string($reviews_link) && preg_match('/^(?:t(?:rue)?|y(?:es)?|1|on|show)$/i', $reviews_link)) ? TRUE : ((is_string($reviews_link) && !preg_match('/^(?:f(?:alse)?|no?(?:ne)?|0|off|hide)$/i', $reviews_link)) ? $reviews_link : FALSE);
		$write_review_link = (is_bool($write_review_link) && $write_review_link || is_string($write_review_link) && preg_match('/^(?:t(?:rue)?|y(?:es)?|1|on|show)$/i', $write_review_link)) ? TRUE : ((is_string($write_review_link) && !preg_match('/^(?:f(?:alse)?|no?|0|off|hide)$/i', $write_review_link)) ? $write_review_link : FALSE);
		$reviews_link_class = (is_string($reviews_link_class)) ? preg_replace('/[^\w _-]/', '-', trim(strtolower($reviews_link_class))) : $link_class;
		$write_review_link_class = (is_string($write_review_link_class)) ? preg_replace('/[^\w _-]/', '-', trim(strtolower($write_review_link_class))) : $link_class;
		$animate = (is_null($animate) || is_bool($animate) && $animate || is_string($summary) && preg_match('/^(?:t(?:rue)?|y(?:es)?|1|on|show|animate|animation)$/i', $animate));
		$review_text = (is_null($review_text) || is_bool($review_text) && $review_text || is_string($review_text) && preg_match('/^(?:t(?:rue)?|y(?:es)?|1|on|show)$/i', $review_text));
		$attribution = (is_null($attribution) || is_bool($attribution) && $attribution || is_string($attribution) && preg_match('/^(?:t(?:rue)?|y(?:es)?|1|on|show|light|dark)$/i', $attribution)) ? ((is_string($attribution) && preg_match('/^(?:light|dark)$/i', $attribution)) ? strtolower($attribution) : TRUE) : ((is_string($attribution) && !preg_match('/^(?:f(?:alse)?|no?(?:ne)?|0|off|hide)$/i', $attribution)) ? $attribution : FALSE);
		$review_text_excerpt_length = (is_numeric($excerpt) && $excerpt >= 20) ? intval($excerpt) : ((!array_key_exists('excerpt', $atts)) ? get_option(__CLASS__ . '_review_text_excerpt_length', NULL) : NULL);
		$review_word = (is_string($review_word) && strlen($review_word) >= 2) ? preg_split('#[/,]\s*#', $review_word, 2) : array(__('review', 'g-business-reviews-rating'), __('reviews', 'g-business-reviews-rating'));
		$more = (is_string($more)) ? $more : __('More', 'g-business-reviews-rating');
		$language = (is_string($language) && strlen($language) >= 2 && strlen($language) <= 16) ? substr($language, 0, 2) : NULL;
		$html_tags = (is_string($html_tags) && strlen($html_tags) >= 2) ? preg_split('/,+/', preg_replace('/^,+|,+$|[^0-9a-z,]/', '', $html_tags), 8, PREG_SPLIT_NO_EMPTY) : array();
		$multiplier = (is_numeric($multiplier) && $multiplier > 0 && $multiplier < 10) ? floatval($multiplier) : 0.193;
		$errors = (is_bool($errors) && !$errors || is_string($errors) && preg_match('/^(?:f(?:alse)?|no?(?:ne)?|0|off|hide)$/i', $errors)) ? FALSE : ((defined('WP_DEBUG')) ? WP_DEBUG : FALSE);
		
		switch ($type)
		{
		case 'rating':
		case 'rating_overall':
		case 'rating_mean':
		case 'rating_average':
		case 'mean_rating':
		case 'overall_rating':
		case 'overall_google_rating':
		case 'google_rating':
		case 'google_rating_overall':
		case 'google_rating_mean':
		case 'google_rating_average':
			if ($this->data == NULL)
			{
				$this->set_data();
				if (!isset($this->data['result']) || isset($this->data['result']) && !is_array($this->data['result']))
				{
					if (!$errors)
					{
						return '';
					}
					
					$text = esc_html__('Error', 'g-business-reviews-rating') . ': No rating data found';
					return $text;
				}
			}

			$html = $this->get_data('rating_rounded', $place_id);
			break;
		case 'rating_count':
		case 'google_rating_count':
		case 'review_count':
		case 'google_review_count':
			if ($this->data == NULL)
			{
				$this->set_data();
				if (!isset($this->data['result']) || isset($this->data['result']) && !is_array($this->data['result']))
				{
					if (!$errors)
					{
						return '';
					}
					
					$text = esc_html__('Error', 'g-business-reviews-rating') . ': No rating count found';
					return $text;
				}
			}

			$html = $this->get_data('rating_count', $place_id);
			break;
		case NULL;
		case 'reviews':
		case 'google_reviews':
			if ($this->data == NULL)
			{
				$this->set_data();
				if (!isset($this->data['result']['reviews']) || isset($this->data['result']) && !is_array($this->data['result']['reviews']))
				{
					if (!$errors)
					{
						return '';
					}
					
					$html = '<p class="error">' . esc_html__('Error', 'g-business-reviews-rating') . ': No review data found</p>';
					return $html;
				}
			}
			
			$this->reviews_filter($args, $atts);
			
			if (is_string($theme))
			{
				if ($key = array_search($theme, $this->reviews_themes) && is_string($key))
				{
					$theme = $key;
				}
				else
				{
					$theme = preg_replace('/[^0-9a-z -]/', '-', strtolower($theme));
				}
				
				if (preg_match('/^light(?:\s+([^\s].+))?$/i', $theme, $m))
				{
					$theme = (isset($m[1])) ? $m[1] : NULL;
				}
			}
			else
			{
				$theme = get_option(__CLASS__ . '_reviews_theme', NULL);
				
				if (preg_match('/^light(?:\s+([^\s].+))?$/i', $theme, $m))
				{
					$theme = (isset($m[1])) ? $m[1] : NULL;
				}
			}
			
						
			if (preg_match('/^.+[\s_-]?inline$/i', $review_item_order))
			{
				$review_item_order = (is_string($review_item_order) && preg_match('/^(?:review(?:[\s_-])?)?(?:text)?[\s_-]?(?:first|top|before|true|on|high|above|1)?([\s_-]?inline)$/i', $review_item_order)) ? 'text first inline' : 'inline';
			}
			else
			{
				$review_item_order = (is_string($review_item_order) && preg_match('/^(?:review(?:[\s_-])?)?(?:text)?[\s_-]?(?:first|top|before|true|on|high|above|1)$/i', $review_item_order)) ? 'text first' : NULL;
			}
						
			$html_tags = (!empty($html_tags)) ? array_replace($this->default_html_tags, $html_tags) : $this->default_html_tags;
			$reviews_url = ($this->demo) ? 'https://search.google.com/local/reviews?placeid=ChIJq6pqZz2uEmsRaQAMbAl0RW0' : 'https://search.google.com/local/reviews?placeid=' . esc_attr((is_string($place_id)) ? $place_id : get_option(__CLASS__ . '_place_id'));			
			$write_review_url = ($this->demo) ? 'https://search.google.com/local/writereview?placeid=ChIJq6pqZz2uEmsRaQAMbAl0RW0' : 'https://search.google.com/local/writereview?placeid=' . esc_attr((is_string($place_id)) ? $place_id : get_option(__CLASS__ . '_place_id'));			
			$rating = $this->get_data('rating', $place_id);
			$rating_rounded = $this->get_data('rating_rounded', $place_id);
			$name = (is_bool($name) && $name) ? $this->get_data('name', $place_id) : ((is_string($name)) ? $name : FALSE);
			$icon = (is_string($icon)) ? $icon : (is_bool($icon) && $icon);
			$vicinity = (is_bool($vicinity) && $vicinity) ? $this->get_data('vicinity', $place_id) : ((is_string($vicinity)) ? $vicinity : FALSE);
			$avatar = (is_bool($avatar)) ? $avatar : ((is_string($avatar)) ? $avatar : FALSE);
			$date = (is_bool($date)) ? $date : ((is_string($date)) ? $date : FALSE);
			$rating_count_rounded = $this->get_data('rating_count_rounded', $place_id);
			
			if (is_bool($icon) && $icon)
			{
				$icon = $this->get_data('icon', $place_id);
			}
			
			if ((is_bool($link) && $link || is_string($link)) && (!is_numeric($limit) || is_numeric($limit) && $limit > 0))
			{
				$link = FALSE;
			}
			elseif (is_bool($link) && $link || is_string($link) && preg_match('/^(?:view[\s_-]*)?reviews?$/i', $link))
			{
				$link = $reviews_url;
			}
			elseif (is_string($link) && preg_match('/^write[\s_-]*(?:a[\s_-]*)?reviews?$/i', $link))
			{
				$link = $write_review_url;
			}
									
			$html = '<div '
			. (($id_name != NULL) ? 'id="' . esc_attr($id_name) . '" ' : '') 
			. 'class="google-business-reviews-rating'
			. ((is_string($theme) && strlen($theme) > 2) ? ' ' . esc_attr($theme) : '')
			. ((is_string($class)) ? ' ' . $class : '')
			. (($stylesheet && is_string($stars)) ? (($stars == 'html' || $stars == 'css') ? ' stars-' . $stars : ' stars-color') : '')
			. ((is_string($link)) ? ' link' : '')
			. ((!$stylesheet) ? ' no-styles' : '')
			. (($this->demo) ? ' demo' : '') . '"'
			. ((is_string($link)) ? ' data-href="' . esc_attr($link) . '"' : '')
			. (($stylesheet && is_string($stars) && $stars != 'html' && $stars != 'css') ? ' data-stars="' . esc_attr($stars) . '"' : '')
			. (($stylesheet && is_string($stars_gray)) ? ' data-stars-gray="' . esc_attr($stars_gray) . '"' : '')
			. '>
';

			if ($summary)
			{
				if ((!is_bool($icon) || is_bool($icon) && $icon) || (!is_bool($name) || is_bool($name) && $name) || (!is_bool($vicinity) || is_bool($vicinity) && $vicinity))
				{
					if ($icon != NULL && is_string($name))
					{
						$html .= '	<' . $html_tags[0] . ' class="heading' . (($icon == NULL) ? ' no-icon' : '') . ((!is_string($name)) ? ' no-name' : '') . '">' . (($icon != NULL) ? '<span class="icon"><img src="' . esc_attr($icon) . '" alt="' . esc_attr(trim($name . ' ' . __('Icon', 'g-business-reviews-rating'))) . '"></span>' : '') . ((is_string($name)) ? esc_html($name) : '') . '</' . $html_tags[0] . '>
';
					}
					
					if (is_string($vicinity) && strlen($vicinity) >= 1)
					{
						$html .= '	<' . $html_tags[1] . ' class="vicinity">' . esc_html($vicinity) . '</' . $html_tags[1] . '>
';
					}
				}
				
				$html .= '	<' . $html_tags[2] . ' class="rating' . (($rating <= 0) ? ' rating-none' : '') . '"><span class="number">' . esc_html($rating_rounded) . '</span>';
				
				if (is_bool($stars) && $stars || is_string($stars))
				{
					if ($stylesheet && ((!is_string($stars) || is_string($stars) && $stars != 'html') && !preg_match('/\bversion[_-]?1\b/i', $class)))
					{
						$partial = (round($rating * 10, 0, PHP_ROUND_HALF_UP) - floor($rating) * 10) * 10;
						$html .= '<span class="all-stars' . (($animate) ? ' animate' : '') . '">'
						. str_repeat('<span class="star"></span>', ($partial > 0) ? floor($rating) : ceil($rating))
						. (($partial > 0) ? '<span class="star split-' . $partial . '-' . (100 - $partial) . '"></span>' : '')
						. str_repeat('<span class="star gray"></span>', ($partial > 0) ? (5 - ceil($rating)) : (5 - floor($rating)))
						. '</span> ';
					}	
					elseif ($stylesheet)
					{
						$html .= '<span class="all-stars">'
						. str_repeat('★', 5)
						. '<span class="rating-stars' . (($animate) ? ' animate' : '') . '"' . (($animate) ? ' style="width: 0;"' : '') . ' data-multiplier="' . (is_numeric($multiplier) ? esc_attr($multiplier) : '') . '">'
						. str_repeat('★', ceil($rating))
						. '</span></span> ';
					}
					else
					{
						$html .= '<span class="rating-stars' . (is_bool($animate) ? ' animate' : '') . '" data-rating="' . esc_attr($rating) . '" data-multiplier="' . (is_numeric($multiplier) ? esc_attr($multiplier) : '') . '">'
						. str_repeat('★', round($rating)) . ((round($rating) < 5) ? '<span class="not">' . str_repeat('☆', (5 - round($rating, 0, PHP_ROUND_HALF_DOWN))) . '</span>' : '')
						. '</span> ';
					}
				}
				
				if ($count)
				{
					$html .= '<a href="' . esc_attr($reviews_url). '" target="_blank" rel="nofollow" class="count">' . esc_html($rating_count_rounded) . ' ' . ((intval($rating_count_rounded) == 1) ? $review_word[0] : ((count($review_word) > 1) ? $review_word[1] : $review_word[0])) . '</a>';
				}
				
				$html .= '</' . $html_tags[2] . '>
';
			}
						
			if ((!is_numeric($limit) || is_numeric($limit) && $limit > 0) && ($errors || !$errors && !empty($this->reviews) && !empty($this->reviews_filtered)))
			{
				if (empty($this->reviews))
				{
					$html .= '	<' . $html_tags[7] . ' class="listing no-reviews">' . esc_html__('No reviews found.', 'g-business-reviews-rating') . '</' . $html_tags[7] . '>
';
				}
				elseif (empty($this->reviews_filtered))
				{
					$html .= '	<' . $html_tags[7] . ' class="listing no-reviews">' . esc_html__('No reviews found, offset too high or another restriction.', 'g-business-reviews-rating') . '</' . $html_tags[7] . '>
';
				}
				else
				{
					if (is_string($name_format) && preg_match('/^(?:(first|last)\s+)?initials?(?:\s+(only)?)?(?:\s+(?:with\s+)?(dot|(?:full)?stop|point|space)s?(?:\s+(?:and\s+)?(dot|(?:full)?stop|point|space)s?)?)?$/i', $name_format, $name_format_match))
					{
						$author_name_first_initials = (isset($name_format_match[1]) && is_string($name_format_match[1]) && strtolower($name_format_match[1]) == 'first');
						$author_name_last_initials = (isset($name_format_match[1]) && is_string($name_format_match[1]) && strtolower($name_format_match[1]) == 'last');
						$author_name_only = (isset($name_format_match[2]) && is_string($name_format_match[2]) && $name_format_match[2] != NULL);
						$author_name_dot = ((isset($name_format_match[3]) && is_string($name_format_match[3]) && $name_format_match[3] != NULL && strtolower($name_format_match[3]) != 'space') || (isset($name_format_match[4]) && is_string($name_format_match[4]) && $name_format_match[4] != NULL && strtolower($name_format_match[4]) != 'space'));
						$author_name_space = ((isset($name_format_match[3]) && is_string($name_format_match[3]) && strtolower($name_format_match[3]) == 'space') || (isset($name_format_match[4]) && is_string($name_format_match[4]) && strtolower($name_format_match[4]) == 'space'));
					}
					else
					{
						$name_format = NULL;
						$name_format_match = array();
					}
					
					$html .= '	<' . $html_tags[3] . ' class="listing">
';

					foreach ($this->reviews_filtered as $i => $a)
					{
						$author_name = ((is_bool($name_format) && !$name_format)) ? NULL : $a['author_name'];
						
						if ($author_name != NULL && $name_format != NULL && !empty($name_format_match))
						{
							$author_name_array = preg_split('/[.\s]+/', $author_name, -1, PREG_SPLIT_NO_EMPTY);
							$author_name = '';
							
							if (count($author_name_array) == 1 || $author_name_first_initials || $author_name_last_initials)
							{
								if (count($author_name_array) == 1 || $author_name_first_initials)
								{
									$author_name = strtoupper(substr($author_name_array[0], 0, 1) . (($author_name_dot) ? '.' : ''));

									if (count($author_name_array) > 1 && !$author_name_only)
									{
										$author_name .= ' ' . implode(' ', array_slice($author_name_array, 1));
									}
								}
								else
								{
									if (!$author_name_only)
									{
										$author_name = implode(' ', array_slice($author_name_array, 0, -1));
									}
									
									$author_name .= ' ' . strtoupper(substr(end($author_name_array), 0, 1) . (($author_name_dot) ? '.' : ''));
								}
							}
							else
							{
								$author_name = strtoupper(substr($author_name_array[0], 0, 1) . (($author_name_dot) ? '.' : '') . (($author_name_space) ? ' ' : '') . substr(end($author_name_array), 0, 1) . (($author_name_dot) ? '.' : ''));
							}
							
							$author_name = trim($author_name);
						}
						
						$html .= '		<' . $html_tags[4] . ' class="' . esc_attr('rating-' . $a['rating']) . ((is_bool($avatar) && !$avatar) ? ' no-avatar' : '') . ((preg_match('/text[\s_-]?first/i', $review_item_order)) ? ' text-first' : '') . ((preg_match('/(?:.+[\s_-]?)?inline$/', $review_item_order)) ? ' inline' : '') . '">
';
						if (!preg_match('/text[\s_-]?first/i', $review_item_order))
						{
							if ((is_bool($avatar) && $avatar || is_string($avatar)) || (!is_string($theme) || is_string($theme) && !preg_match('/^badge(?:\s+.+)?$/', $theme)))
							{
								$html .= '			<span class="author-avatar"><a href="' . esc_attr($a['author_url']) . '" target="_blank">' . (($a['profile_photo_url'] != NULL) ? '<img src="' . esc_attr((is_string($avatar)) ? $avatar : $a['profile_photo_url']) . '" alt="Avatar">' : '&mdash;') . '</a></span>
';
							}
							
							if ($author_name != NULL)
							{
								$html .= '			<span class="author-name"><a href="' . esc_attr($a['author_url']) . '" target="_blank">' . esc_html($author_name) . '</a></span>
';
							}
							
							$html .= '			<span class="rating">' . str_repeat('★', $a['rating']) . (($a['rating'] < 5) ? '<span class="not">' . str_repeat('☆', (5 - $a['rating'])) . '</span>' : '') . '</span>
';

							if (is_string($date) && is_numeric($a['time']))
							{
								$html .= '			<span class="date">' . esc_html((function_exists('wp_date')) ? wp_date($date, $a['time']) : date($date, $a['time'])) . '</span>
';
							}
							elseif (is_bool($date) && $date)
							{
								$html .= '			<span class="relative-time-description">' . esc_html($a['relative_time_description']) . '</span>
';
							}
						}
						
						if ($review_text)
						{
							$set_excerpt = (is_numeric($review_text_excerpt_length) && strlen(strip_tags($a['text'])) > 20 && $review_text_excerpt_length < round(strlen(strip_tags($a['text'])) * 1.1));
							$html .= '			<div class="text' . (($set_excerpt) ? ' text-excerpt' : '') . '">';
							
							if ($set_excerpt)
							{
								$html .= preg_replace('/(\r\n|\r|\n)+/', '<br>' . PHP_EOL . '				', preg_replace('/^(.{' . $review_text_excerpt_length . '}[^\s]{0,20})(.*)$/is', '<span class="review-snippet">$1</span> <span class="review-more-placeholder">… ' . esc_html($more) . '</span><span class="review-full-text">$2</span>', esc_html(strip_tags($a['text']))));
							}
							else
							{
								$html .= preg_replace('/(\r\n|\r|\n)+/', '<br>' . PHP_EOL . '				', esc_html(strip_tags($a['text'])));
							}
							
							$html .= '</div>
';
						}
							
						if (preg_match('/text[\s_-]?first/i', $review_item_order))
						{
							if ((is_bool($avatar) && $avatar || is_string($avatar)) || (!is_string($theme) || is_string($theme) && !preg_match('/^badge(?:\s+.+)?$/', $theme)))
							{
								$html .= '			<span class="author-avatar"><a href="' . esc_attr($a['author_url']) . '" target="_blank">' . (($a['profile_photo_url'] != NULL) ? '<img src="' . esc_attr((is_string($avatar)) ? $avatar : $a['profile_photo_url']) . '" alt="Avatar">' : '&mdash;') . '</a></span>
';
							}
							
							if ($author_name != NULL)
							{
								$html .= '			<span class="author-name"><a href="' . esc_attr($a['author_url']) . '" target="_blank">' . esc_html($author_name) . '</a></span>
';
							}
							
							$html .= '			<span class="rating">' . str_repeat('★', $a['rating']) . (($a['rating'] < 5) ? '<span class="not">' . str_repeat('☆', (5 - $a['rating'])) . '</span>' : '') . '</span>
';

							if (is_string($date) && is_numeric($a['time']))
							{
								$html .= '			<span class="date">' . esc_html((function_exists('wp_date')) ? wp_date($date, $a['time']) : date($date, $a['time'])) . '</span>
';
							}
							elseif (is_bool($date) && $date)
							{
								$html .= '			<span class="relative-time-description">' . esc_html($a['relative_time_description']) . '</span>
';
							}
						}
	
						$html .= '		</' . $html_tags[4] . '>
';
					}
					$html .= '	</' . $html_tags[3] . '>
';
				}
			}
			
			if ((is_bool($reviews_link) && $reviews_link || is_string($reviews_link)) || (is_bool($write_review_link) && $write_review_link || is_string($write_review_link)))
			{
				if ($reviews_link_class != NULL)
				{
					$reviews_link_class = preg_split('/\s+|,\s*/', $reviews_link_class, 15);
					$reviews_link_class = array_merge(array('view-reviews'), $reviews_link_class);
					$reviews_link_class = implode(' ', array_unique($reviews_link_class));
				}				
				else
				{
					$reviews_link_class = 'button view-reviews';
				}

				if ($write_review_link_class != NULL)
				{
					$write_review_link_class = preg_split('/\s+|,\s*/', $write_review_link_class, 15);
					$write_review_link_class = array_merge(array('write-review'), $write_review_link_class);
					$write_review_link_class = implode(' ', array_unique($write_review_link_class));
				}
				else
				{
					$write_review_link_class = 'button write-review';
				}

				$html .= '	<' . $html_tags[5] . ' class="buttons">';
				
				if (is_bool($reviews_link) && $reviews_link || is_string($reviews_link))
				{
					$html .= '<a href="' . esc_attr($reviews_url). '" target="_blank" rel="nofollow"' . (($reviews_link_class != NULL) ? ' class="' . esc_attr($reviews_link_class) . '"' : '') . '>' . ((is_string($reviews_link)) ? esc_html($reviews_link) : esc_html__('View Reviews', 'g-business-reviews-rating')) . '</a>';
				}
				
				if ((is_bool($reviews_link) && $reviews_link || is_string($reviews_link)) && (is_bool($write_review_link) && $write_review_link || is_string($write_review_link)))
				{
					$html .= ' ';
				}
				
				if (is_bool($write_review_link) && $write_review_link || is_string($write_review_link))
				{
					$html .= '<a href="' . esc_attr($write_review_url). '" target="_blank" rel="nofollow"' . (($write_review_link_class != NULL) ? ' class="' . esc_attr($write_review_link_class) . '"' : '') . '>' . ((is_string($write_review_link)) ? esc_html($write_review_link) : esc_html__('Write Review', 'g-business-reviews-rating')). '</a>';
				}
				
				$html .= '</' . $html_tags[5] . '>
';
			}
			
			if (is_bool($attribution) && $attribution || is_string($attribution) && strlen($attribution) >= 1)
			{
				$html .= '	<' . $html_tags[6] . ' class="attribution"><span class="powered-by-google' . ((is_string($attribution)) ? ' ' . esc_attr($attribution) : '') . '"></span></' . $html_tags[6] . '>
';
			}

			$html .= '</div>
';
			break;
		case 'review':
		case 'review_list':
		case 'reviews_list':
		case 'review_url':
		case 'reviews_url':
		case 'review_link':
		case 'reviews_link':
		case 'review_href':
		case 'reviews_href':
		case 'review_list_link':
		case 'reviews_list_link':
		case 'review_list_href':
		case 'reviews_list_href':
		case 'google_review':
		case 'google_review_list':
		case 'google_reviews_list':
		case 'google_review_url':
		case 'google_reviews_url':
		case 'google_review_link':
		case 'google_reviews_link':
		case 'google_review_href':
		case 'google_reviews_href':
		case 'google_review_list_link':
		case 'google_review_list_href':
		case 'google_reviews_list_link':
		case 'google_reviews_list_href':
			if ($this->demo)
			{
				$url = 'https://search.google.com/local/reviews?placeid=ChIJq6pqZz2uEmsRaQAMbAl0RW0';
			}
			else
			{
				$url = 'https://search.google.com/local/reviews?placeid=' . esc_attr(($place_id != NULL) ? $place_id : get_option(__CLASS__ . '_place_id'));
			}
			
			if ($class == NULL && is_string($link_class))
			{
				$class = $link_class;
			}
			
			$html = ($content != NULL) ? '<a href="' . $url . '"' . (($class != NULL) ? ' class="' . esc_attr($class) . '"' : '') . (($target != NULL) ? ' target="' . esc_attr($target) . '"' : '') . '>' . $content . '</a>' : $url;
			break;
		case 'write_review':
		case 'write_review_url':
		case 'write_review_link':
		case 'write_review_href':
		case 'google_write_review':
		case 'google_write_review_url':
		case 'google_write_review_link':
		case 'google_write_review_href':
			if ($this->demo)
			{
				$url = 'https://search.google.com/local/writereview?placeid=ChIJq6pqZz2uEmsRaQAMbAl0RW0';
			}
			else
			{
				$url = 'https://search.google.com/local/writereview?placeid=' . esc_attr(($place_id != NULL) ? $place_id : get_option(__CLASS__ . '_place_id'));
			}
			
			if ($class == NULL && is_string($link_class))
			{
				$class = $link_class;
			}

			$html = ($content != NULL) ? '<a href="' . $url . '"' . (($class != NULL) ? ' class="' . esc_attr($class) . '"' : '') . (($target != NULL) ? ' target="' . esc_attr($target) . '"' : '') . '>' . $content . '</a>' : $url;
			break;
		case 'url':
		case 'map':
		case 'maps':
		case 'maps_url':
		case 'maps_link':
		case 'maps_href':
		case 'google_map':
		case 'google_maps':
		case 'google_map_url':
		case 'google_map_link':
		case 'google_map_href':
		case 'google_maps_url':
		case 'google_maps_link':
		case 'google_maps_href':
			if ($this->data == NULL)
			{
				$this->set_data();
				if (!isset($this->data['result']) || isset($this->data['result']) && !is_array($this->data['url']))
				{
					if (!$errors)
					{
						return '';
					}
					
					$text = esc_html__('Error', 'g-business-reviews-rating') . ': No URL found';
					return $text;
				}
			}
			
			if ($class == NULL && is_string($link_class))
			{
				$class = $link_class;
			}

			$url = (isset($this->data['result']['url']) && is_string($this->data['result']['url'])) ? $this->data['result']['url'] : '';
			
			$html = ($content != NULL) ? '<a href="' . $url . '"' . (($class != NULL) ? ' class="' . esc_attr($class) . '"' : '') . (($target != NULL) ? ' target="' . esc_attr($target) . '"' : '') . '>' . $content . '</a>' : $url;
			break;
		case 'structured_data':
			$html = '<pre class="structured-data">' . $this->structured_data('json') .'</pre>';
			break;
		default:
			$html = '<pre class="error">[' . esc_html($shortcode) . ' type not found: ' . esc_html($type) . ']</pre>';
			break;
		}
		
		return $html;
	}
	
	public function loaded()
	{
		load_plugin_textdomain('g-business-reviews-rating', FALSE, basename(dirname(__FILE__)) . '/languages');

		return TRUE;
	}
	
	public function widget()
	{
		// Initiate widget
		
		register_widget('google_business_reviews_rating_widget');
	}
	
}

defined('GOOGLE_BUSINESS_REVIEWS_RATING_DEMO_RESULT') or define('GOOGLE_BUSINESS_REVIEWS_RATING_DEMO_RESULT', '{"html_attributions":"","result":{"icon":"https://maps.gstatic.com/mapfiles/place_api/icons/restaurant-71.png","name":"Everyday Demo Restaurant","rating":3.9,"reviews":[{"author_name":"Lisa Dooley","author_url":"#","language":"en","profile_photo_url":"data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz4KPCFET0NUWVBFIHN2ZyBQVUJMSUMgIi0vL1czQy8vRFREIFNWRyAxLjEvL0VOIiAiaHR0cDovL3d3dy53My5vcmcvR3JhcGhpY3MvU1ZHLzEuMS9EVEQvc3ZnMTEuZHRkIj4KPHN2ZyB2ZXJzaW9uPSIxLjEiIGlkPSJMYXllcl8xIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHhtbG5zOnhsaW5rPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5L3hsaW5rIiB4PSIwcHgiIHk9IjBweCIKd2lkdGg9IjEyOHB4IiBoZWlnaHQ9IjEyOHB4IiB2aWV3Qm94PSIwIDAgMTI4IDEyOCIgZW5hYmxlLWJhY2tncm91bmQ9Im5ldyAwIDAgMTI4IDEyOCIgeG1sOnNwYWNlPSJwcmVzZXJ2ZSI+CjxjaXJjbGUgZmlsbD0iIzAwN0Y3MCIgY3g9IjY0IiBjeT0iNjQiIHI9IjY0Ii8+CjxnPgo8cGF0aCBmaWxsPSIjRkZGRkZGIiBkPSJNNDYuOTM5LDI4LjA1aDYuMjU2djYyLjU1N0g4OC4xN3Y1LjQ5N2gtNDEuMjNWMjguMDV6Ii8+CjwvZz4KPC9zdmc+Cg==","rating":5,"relative_time_description":"a month ago","text":"Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.","time":1561637346},{"author_name":"Catherine P","author_url":"#","language":"en","profile_photo_url":"data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz4KPCFET0NUWVBFIHN2ZyBQVUJMSUMgIi0vL1czQy8vRFREIFNWRyAxLjEvL0VOIiAiaHR0cDovL3d3dy53My5vcmcvR3JhcGhpY3MvU1ZHLzEuMS9EVEQvc3ZnMTEuZHRkIj4KPHN2ZyB2ZXJzaW9uPSIxLjEiIGlkPSJMYXllcl8xIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHhtbG5zOnhsaW5rPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5L3hsaW5rIiB4PSIwcHgiIHk9IjBweCIKd2lkdGg9IjEyOHB4IiBoZWlnaHQ9IjEyOHB4IiB2aWV3Qm94PSIwIDAgMTI4IDEyOCIgZW5hYmxlLWJhY2tncm91bmQ9Im5ldyAwIDAgMTI4IDEyOCIgeG1sOnNwYWNlPSJwcmVzZXJ2ZSI+CjxjaXJjbGUgZmlsbD0iI0JGMDkwMCIgY3g9IjY0IiBjeT0iNjQiIHI9IjY0Ii8+CjxnPgo8cGF0aCBmaWxsPSIjRkZGRkZGIiBkPSJNOTIuNjI1LDczLjkyNWMtMC41MDcsNy41ODMtMy4xNSwxMy40OTItNy45MjksMTcuNzI1Qzc5LjkxOCw5NS44ODQsNzMuNDc3LDk4LDY1LjM3Niw5OApjLTQuNTU3LDAtOC42NTUtMC44MjItMTIuMjk1LTIuNDY0Yy0zLjY0MS0xLjY0My02Ljc1Ni0zLjk5Ni05LjM1MS03LjA2MmMtMi41OTUtMy4wNjQtNC41OS02Ljg0LTUuOTgyLTExLjMyNwpjLTEuMzkyLTQuNDg1LTIuMDg4LTkuNTcyLTIuMDg4LTE1LjI2YzAtNS42MjMsMC43MTEtMTAuNjQ3LDIuMTM1LTE1LjA3YzEuNDI1LTQuNDIyLDMuNDM1LTguMTY3LDYuMDI5LTExLjIzMgpjMi41OTUtMy4wNjQsNS43MjktNS40MDMsOS40LTcuMDE0YzMuNjctMS42MTEsNy43ODQtMi40MTcsMTIuMzQyLTIuNDE3YzMuODYxLDAsNy4zOTEsMC41MTMsMTAuNTg3LDEuNTM4CmMzLjE5NSwxLjAyNSw1LjkzMywyLjQ3OSw4LjIxMiw0LjM2YzIuMjc3LDEuODgyLDQuMDY2LDQuMTUsNS4zNjQsNi44MDRjMS4yOTcsMi42NTQsMi4wNDIsNS42MjUsMi4yMzEsOC45MWgtNi4yNTYKYy0wLjUwNi00Ljk5MS0yLjU1OS04LjkyNC02LjE2MS0xMS44MDFjLTMuNjAyLTIuODc1LTguMjc4LTQuMzEzLTE0LjAyNy00LjMxM2MtNy4yNjcsMC0xMi45ODUsMi41NjgtMTcuMTU2LDcuNzAxCmMtNC4xNyw1LjEzNS02LjI1NSwxMi42MTQtNi4yNTUsMjIuNDM4YzAsNC45NDUsMC41NTIsOS4zMTksMS42NTksMTMuMTIyYzEuMTA0LDMuODAzLDIuNjg1LDcuMDA1LDQuNzM5LDkuNjAzCmMyLjA1MywyLjYsNC41MzQsNC41ODEsNy40NCw1Ljk0M2MyLjkwNiwxLjM2Miw2LjE2MSwyLjA0NCw5Ljc2MywyLjA0NGM2LjEyOCwwLDEwLjk5NS0xLjYyNiwxNC41OTctNC44ODIKYzMuNjAyLTMuMjU0LDUuNjIzLTcuODE5LDYuMDY2LTEzLjY5Nkg5Mi42MjV6Ii8+CjwvZz4KPC9zdmc+Cg==","rating":1,"relative_time_description":"2 months ago","text":"Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. \\nExcepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. \\nExcepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.","time":1557738977},{"author_name":"Fay A","author_url":"#","language":"en","profile_photo_url":"data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz4KPCFET0NUWVBFIHN2ZyBQVUJMSUMgIi0vL1czQy8vRFREIFNWRyAxLjEvL0VOIiAiaHR0cDovL3d3dy53My5vcmcvR3JhcGhpY3MvU1ZHLzEuMS9EVEQvc3ZnMTEuZHRkIj4KPHN2ZyB2ZXJzaW9uPSIxLjEiIGlkPSJMYXllcl8xIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHhtbG5zOnhsaW5rPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5L3hsaW5rIiB4PSIwcHgiIHk9IjBweCIKd2lkdGg9IjEyOHB4IiBoZWlnaHQ9IjEyOHB4IiB2aWV3Qm94PSIwIDAgMTI4IDEyOCIgZW5hYmxlLWJhY2tncm91bmQ9Im5ldyAwIDAgMTI4IDEyOCIgeG1sOnNwYWNlPSJwcmVzZXJ2ZSI+CjxjaXJjbGUgZmlsbD0iI0EzMDBDNCIgY3g9IjY0IiBjeT0iNjQiIHI9IjY0Ii8+CjxnPgo8cGF0aCBmaWxsPSIjRkZGRkZGIiBkPSJNNDQuNjY1LDI3Ljk1Nmg0My4xMjZ2NS40OTdINTAuOTJ2MjQuODMzaDMzLjQ1OHY1LjQ5N0g1MC45MnYzMi4zMjFoLTYuMjU2VjI3Ljk1NnoiLz4KPC9nPgo8L3N2Zz4K","rating":5,"relative_time_description":"2 weeks ago","text":"Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam.","time":1563122393},{"author_name":"Dexter Ortega","author_url":"#","language":"sp","profile_photo_url":"data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz4KPCFET0NUWVBFIHN2ZyBQVUJMSUMgIi0vL1czQy8vRFREIFNWRyAxLjEvL0VOIiAiaHR0cDovL3d3dy53My5vcmcvR3JhcGhpY3MvU1ZHLzEuMS9EVEQvc3ZnMTEuZHRkIj4KPHN2ZyB2ZXJzaW9uPSIxLjEiIGlkPSJMYXllcl8xIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHhtbG5zOnhsaW5rPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5L3hsaW5rIiB4PSIwcHgiIHk9IjBweCIKd2lkdGg9IjEyOHB4IiBoZWlnaHQ9IjEyOHB4IiB2aWV3Qm94PSIwIDAgMTI4IDEyOCIgZW5hYmxlLWJhY2tncm91bmQ9Im5ldyAwIDAgMTI4IDEyOCIgeG1sOnNwYWNlPSJwcmVzZXJ2ZSI+CjxjaXJjbGUgZmlsbD0iIzI2NUVGRiIgY3g9IjY0IiBjeT0iNjQiIHI9IjY0Ii8+CjxnPgo8cGF0aCBmaWxsPSIjRkZGRkZGIiBkPSJNNTkuMjE0LDI3Ljk1NmM0LjA0MywwLDcuNzA4LDAuMTg5LDEwLjk5NCwwLjU2OGMzLjI4NSwwLjM3OSw2LjI1NiwxLjQyMiw4LjkxLDMuMTI4CmM0LjE3LDIuNjU0LDcuMzYsNi41MjUsOS41NzMsMTEuNjExYzIuMjExLDUuMDg3LDMuMzE3LDExLjI5NiwzLjMxNywxOC42MjVjMCw3Ljg5OS0xLjIxOCwxNC40NTQtMy42NDksMTkuNjY4CmMtMi40MzQsNS4yMTMtNS45ODcsOS4wODQtMTAuNjYzLDExLjYxYy0yLjQ2NSwxLjMyNy01LjM3MiwyLjE0OS04LjcyMSwyLjQ2NWMtMy4zNSwwLjMxNi03LjIzNSwwLjQ3NC0xMS42NTgsMC40NzRIMzguNTUxVjI3Ljk1NgpoMTYuMDE5SDU5LjIxNHogTTU3Ljk4MSw5MC42MDdjMy43OTIsMCw3LjEwOC0wLjEyNiw5Ljk1Mi0wLjM4MWMyLjg0NC0wLjI1Myw1LjI3NS0wLjk4Miw3LjI5OC0yLjE4OApjNi44ODctNC4xMiwxMC4zMzItMTIuNzc1LDEwLjMzMi0yNS45NjJjMC0xMi43NDItMy4yODctMjEuMjctOS44NTctMjUuNTgxYy0yLjIxMy0xLjQ1Ny00LjgzNC0yLjMzLTcuODY3LTIuNjE1CmMtMy4wMzMtMC4yODQtNi41NC0wLjQyOC0xMC41MjEtMC40MjhINDQuODA3djU3LjE1NUg1Ny45ODF6Ii8+CjwvZz4KPC9zdmc+Cg==","rating":5,"relative_time_description":"3 months ago","text":"Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.","time":1554727451},{"author_name":"Mary N","author_url":"#","language":"en","profile_photo_url":"data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz4KPCFET0NUWVBFIHN2ZyBQVUJMSUMgIi0vL1czQy8vRFREIFNWRyAxLjEvL0VOIiAiaHR0cDovL3d3dy53My5vcmcvR3JhcGhpY3MvU1ZHLzEuMS9EVEQvc3ZnMTEuZHRkIj4KPHN2ZyB2ZXJzaW9uPSIxLjEiIGlkPSJMYXllcl8xIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHhtbG5zOnhsaW5rPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5L3hsaW5rIiB4PSIwcHgiIHk9IjBweCIKd2lkdGg9IjEyOHB4IiBoZWlnaHQ9IjEyOHB4IiB2aWV3Qm94PSIwIDAgMTI4IDEyOCIgZW5hYmxlLWJhY2tncm91bmQ9Im5ldyAwIDAgMTI4IDEyOCIgeG1sOnNwYWNlPSJwcmVzZXJ2ZSI+CjxjaXJjbGUgZmlsbD0iI0I2M0RGRiIgY3g9IjY0IiBjeT0iNjQiIHI9IjY0Ii8+CjxnPgo8cGF0aCBmaWxsPSIjRkZGRkZGIiBkPSJNMzIuODY0LDI3Ljk1Nmg4LjgxNWwyMi40NjMsNTkuOTk4bDIyLjA4NC01OS45OThoOC44MTV2NjguMTQ5aC02LjI1NlYzNi42NzVMNjcuMDgxLDk2LjEwNGgtNS43ODIKTDM5LjEyLDM2LjY3NXY1OS40MjloLTYuMjU2VjI3Ljk1NnoiLz4KPC9nPgo8L3N2Zz4K","rating":4,"relative_time_description":"4 months ago","text":"Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.","time":1552675416},{"author_name":"Jerry Jet","author_url":"#","language":"en","profile_photo_url":"data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz4KPCFET0NUWVBFIHN2ZyBQVUJMSUMgIi0vL1czQy8vRFREIFNWRyAxLjEvL0VOIiAiaHR0cDovL3d3dy53My5vcmcvR3JhcGhpY3MvU1ZHLzEuMS9EVEQvc3ZnMTEuZHRkIj4KPHN2ZyB2ZXJzaW9uPSIxLjEiIGlkPSJMYXllcl8xIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHhtbG5zOnhsaW5rPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5L3hsaW5rIiB4PSIwcHgiIHk9IjBweCIKd2lkdGg9IjEyOHB4IiBoZWlnaHQ9IjEyOHB4IiB2aWV3Qm94PSIwIDAgMTI4IDEyOCIgZW5hYmxlLWJhY2tncm91bmQ9Im5ldyAwIDAgMTI4IDEyOCIgeG1sOnNwYWNlPSJwcmVzZXJ2ZSI+CjxjaXJjbGUgZmlsbD0iI0ZGQjQwNSIgY3g9IjY0IiBjeT0iNjQiIHI9IjY0Ii8+CjxnPgo8cGF0aCBmaWxsPSIjRkZGRkZGIiBkPSJNNDkuNTQ2LDc1LjI1MnY0LjM2YzAsOC41OTQsMy44MjMsMTIuODkxLDExLjQ2OSwxMi44OTFjNC41NSwwLDcuNzM5LTEuMTIxLDkuNTczLTMuMzY1CmMxLjgzMi0yLjI0MiwyLjc0OC01Ljg5MiwyLjc0OC0xMC45NDdWMjguMDVoNi4yNTZ2NTEuNTYyYzAsNi4wNjYtMS42MTEsMTAuNjQ4LTQuODM0LDEzLjc0M0M3MS41MzUsOTYuNDUxLDY2Ljg5MSw5OCw2MC44MjUsOTgKYy0xMS42OTEsMC0xNy41MzUtNS45MzgtMTcuNTM1LTE3LjgxOXYtNC45MjlINDkuNTQ2eiIvPgo8L2c+Cjwvc3ZnPgo=","rating":2,"relative_time_description":"4 months ago","text":"Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur? Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam nihil molestiae consequatur, vel illum qui dolorem eum fugiat quo voluptas nulla pariatur?","time":1552675416},{"author_name":"Ian A","author_url":"#","language":"it","profile_photo_url":"data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz4KPCFET0NUWVBFIHN2ZyBQVUJMSUMgIi0vL1czQy8vRFREIFNWRyAxLjEvL0VOIiAiaHR0cDovL3d3dy53My5vcmcvR3JhcGhpY3MvU1ZHLzEuMS9EVEQvc3ZnMTEuZHRkIj4KPHN2ZyB2ZXJzaW9uPSIxLjEiIGlkPSJMYXllcl8xIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHhtbG5zOnhsaW5rPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5L3hsaW5rIiB4PSIwcHgiIHk9IjBweCIKd2lkdGg9IjEyOHB4IiBoZWlnaHQ9IjEyOHB4IiB2aWV3Qm94PSIwIDAgMTI4IDEyOCIgZW5hYmxlLWJhY2tncm91bmQ9Im5ldyAwIDAgMTI4IDEyOCIgeG1sOnNwYWNlPSJwcmVzZXJ2ZSI+CjxjaXJjbGUgZmlsbD0iIzAwQjk4NyIgY3g9IjY0IiBjeT0iNjQiIHI9IjY0Ii8+CjxnPgo8cGF0aCBmaWxsPSIjRkZGRkZGIiBkPSJNNjAuODI1LDI3Ljk1Nmg2LjI1NXY2OC4xNDloLTYuMjU1VjI3Ljk1NnoiLz4KPC9nPgo8L3N2Zz4K","rating":5,"relative_time_description":"2 months ago","text":"Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt.","time":1557738977}],"url":"https://goo.gl/maps/CciLp41Y9fMZgubPA","user_ratings_total":31,"vicinity":"123 Battersea Place, London"},"status":"OK"}');

new google_business_reviews_rating; 
