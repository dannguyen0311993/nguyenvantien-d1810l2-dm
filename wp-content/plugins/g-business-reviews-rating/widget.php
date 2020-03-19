<?php

if (!defined('ABSPATH'))
{
    die();
}

class google_business_reviews_rating_widget extends WP_Widget
{
	private
		$alias = NULL,
		$reference = NULL,
		$api_key = NULL,
		$place_id = NULL,
		$rating = NULL,
		$business_name = NULL,
		$business_icon = NULL,
		$demo = NULL,
		$user_ratings_total = NULL,
		$theme = NULL,
		$result = array(),
		$data = array(),
		$reviews = array(),
		$reviews_filtered = array(),
		$review_sort_option = NULL,
		$review_sort_options = array(),
		$reviews_themes = array();
	
    public function __construct()
    {
		$this->alias = preg_replace('/^(.+)[_-][^_-]+$/', '$1', __CLASS__);
		$this->reference = preg_replace('/[^0-9a-z-]/', '-', $this->alias);
		
        parent::__construct($this->alias, __('Reviews and Rating', 'g-business-reviews-rating'), array(
            'description' => __('Have your rating and a review showing in your sidebar', 'g-business-reviews-rating'),
			'classname' => $this->reference . '-widget'
        ));
		
		$this->set();
		
		add_action('admin_enqueue_scripts', array($this, 'admin_css_load'));
		add_action('admin_enqueue_scripts', array($this, 'admin_js_load'));
        return TRUE;
    }
	
	private function set()
	{
		// Set rating and review data
		
		$this->review_sort_options = array(
			'relevance_desc' => array(
				'name' => 'Relevance Descending',
				'min_max_values' => array('High', 'Low'),
				'field' => NULL,
				'asc' => FALSE
			),
			'relevance_asc' => array(
				'name' => 'Relevance Ascending',
				'min_max_values' => array('Low', 'High'),
				'field' => NULL,
				'asc' => TRUE
			),
			'date_desc' => array(
				'name' => 'Date Descending',
				'min_max_values' => array('New', 'Old'),
				'field' => 'time',
				'asc' => FALSE
			),
			'date_asc' => array(
				'name' => 'Date Ascending',
				'min_max_values' => array('Old', 'New'),
				'field' => 'time',
				'asc' => TRUE
			),
			'rating_desc' => array(
				'name' => 'Rating Descending',
				'min_max_values' => array('High', 'Low'),
				'field' => 'rating',
				'asc' => FALSE
			),
			'rating_asc' => array(
				'name' => 'Rating Ascending',
				'min_max_values' => array('Low', 'High'),
				'field' => 'rating',
				'asc' => TRUE
			),
			'author_name_asc' => array(
				'name' => 'Author’s Name Ascending',
				'min_max_values' => array('A', 'Z'),
				'field' => 'author_name',
				'asc' => TRUE
			),
			'author_name_desc' => array(
				'name' => 'Author’s Name Descending',
				'min_max_values' => array('Z', 'A'),
				'field' => 'author_name',
				'asc' => FALSE
			),
			'id_asc' => array(
				'name' => 'ID Ascending',
				'min_max_values' => array('Low', 'High'),
				'field' => 'id',
				'asc' => TRUE
			),
			'id_desc' => array(
				'name' => 'ID Descending',
				'min_max_values' => array('High', 'Low'),
				'field' => 'id',
				'asc' => FALSE
			),
			'shuffle' => array(
				'name' => 'Random Shuffle'
			)
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
			'badge light narrow' => __('Narrow Badge, Light Background with Fonts', 'g-business-reviews-rating'),
			'badge light narrow fonts' => __('Narrow Badge, Light Background with Fonts', 'g-business-reviews-rating'),
			'badge dark' => __('Badge, Dark Background', 'g-business-reviews-rating'),
			'badge dark fonts' => __('Badge, Dark Background with Fonts', 'g-business-reviews-rating'),
			'badge dark narrow' => __('Narrow Badge, Dark Background', 'g-business-reviews-rating'),
			'badge dark narrow fonts' => __('Narrow Badge, Dark Background with Fonts', 'g-business-reviews-rating')
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
			'hr' => 'Croatian',
			'cs' => 'Czech',
			'da' => 'Danish',
			'nl' => 'Dutch',
			'en' => 'English',
			'et' => 'Estonian',
			'fa' => 'Farsi',
			'fi' => 'Finnish',
			'fil' => 'Filipino',
			'fr' => 'French',
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
			'pa' => 'Punjabi',
			'ro' => 'Romanian',
			'ru' => 'Russian',
			'sr' => 'Serbian',
			'si' => 'Sinhalese',
			'sk' => 'Slovak',
			'sl' => 'Slovenian',
			'es' => 'Spanish',
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

		$this->plugin_settings_url = './options-general.php?page='.$this->alias.'_settings';
		$this->demo = get_option($this->alias . '_demo');
		$this->api_key = get_option($this->alias . '_api_key');
		$this->place_id = get_option($this->alias . '_place_id');		
		$this->theme = get_option($this->alias . '_theme');
		$this->more = get_option($this->alias . '_more');
		
		if (!$this->demo)
		{
			$icon_image_id = get_option($this->alias . '_icon');
			$icon_image_url = NULL;
		
			if (is_numeric($icon_image_id))
			{
				global $wpdb;
				$icon_image_url = $wpdb->get_var("SELECT `guid` FROM `" . $wpdb->posts . "` WHERE ID='" . intval($icon_image_id) . "' LIMIT 1");
			}

			$this->result = get_option($this->alias . '_result');
			$this->data = (isset($this->result['result'])) ? $this->result['result'] : array();
			$this->rating = (is_array($this->data) && !empty($this->data) && isset($this->data['rating'])) ? floatval($this->data['rating']) : NULL;
			$this->business_name = (is_array($this->data) && !empty($this->data) && isset($this->data['name'])) ? $this->data['name'] : NULL;
			$this->business_icon = ($icon_image_url != NULL) ? $icon_image_url : ((is_array($this->data) && !empty($this->data) && isset($this->data['icon'])) ? $this->data['icon'] : NULL);
			$this->user_ratings_total = (is_array($this->data) && !empty($this->data) && isset($this->data['user_ratings_total'])) ? intval($this->data['user_ratings_total']) : NULL;
			$this->reviews = get_option($this->alias . '_reviews');
			$this->reviews_filtered = $this->reviews;
			return TRUE;
		}
		
		$this->result = json_decode(GOOGLE_BUSINESS_REVIEWS_RATING_DEMO_RESULT, TRUE);
		$this->data = $this->result['result'];
		$this->rating = (is_array($this->data) && !empty($this->data) && isset($this->data['rating'])) ? floatval($this->data['rating']) : NULL;
		$this->business_name = (is_array($this->data) && !empty($this->data) && isset($this->data['name'])) ? $this->data['name'] : NULL;
		$this->business_icon = (is_array($this->data) && !empty($this->data) && isset($this->data['icon'])) ? $this->data['icon'] : NULL;
		$this->user_ratings_total = (is_array($this->data) && !empty($this->data) && isset($this->data['user_ratings_total'])) ? intval($this->data['user_ratings_total']) : NULL;

		$this->reviews = array();
		$reviews_length = 0;
		$count = 1;
		
		foreach($this->data['reviews'] as $review)
		{
			$key = $review['time'].'_'.$review['rating'].'_'.md5($review['author_name'].'_'.substr($review['text'], 0, 100));
			
			$a['id'] = $reviews_length + $count;
			$a['place_id'] = ($this->demo) ? NULL : get_option($this->alias . '_place_id');
			$a['order'] = $count;
			$a['checked'] = NULL;
			$a['retrieved'] = time();
			$a['status'] = TRUE;
			
			$this->reviews[$key] = $a + $review;
			$count++;
		}
		
		uksort($this->reviews, function ($a, $b) { return $this->reviews[$b]['retrieved'] - ($this->reviews[$b]['order'] * 0.1) - $this->reviews[$a]['retrieved'] - ($this->reviews[$a]['order'] * 0.1); });
		$this->reviews_filtered = $this->reviews;

        return TRUE;
	}
	
	private function default_values()
	{
		// Set the default values
		
		if (empty($this->reviews))
		{
			return array();
		}
		
		$count = $this->reviews_count();
		
		return array(
			'title' => __('Google Rating', 'g-business-reviews-rating'),
			'limit' => ($count < 3) ? $count : 3,
			'sort' => NULL,
			'offset' => 0,
			'rating_min' => 1,
			'rating_max' => 5,
			'review_text_min' => 0,
			'review_text_max' => NULL,
			'excerpt_length' => 120,
			'more' => __('More', 'g-business-reviews-rating'),
			'language' => NULL,
			'theme' => NULL,
			'display_name' => FALSE,
			'display_icon' => FALSE,
			'display_rating' => TRUE,
			'display_rating_stars' => TRUE,
			'display_review_count' => TRUE,
			'display_reviews' => TRUE,
			'display_review_text' => TRUE,
			'display_view_reviews_button' => FALSE,
			'display_write_review_button' => FALSE,
			'display_attribution' => TRUE,
			'animate' => TRUE,
			'stylesheet' => TRUE
		);
	}

	private function reviews_filter($filters = array(), $override = TRUE)
	{
		// Filter review data
				
		if (empty($filters) || empty($this->reviews))
		{
			return FALSE;
		}
		
		$count = 0;
		$id = NULL;
		$ids = array();
		
		if (isset($filters['id']))
		{
			$ids = (is_numeric($filters['id']) && $filters['id'] > 0) ? array(intval($filters['id'])) : ((preg_match('/^(?:\d+)(?:,\s*(?:\d+))+$/', $filters['id'])) ? array_unique(preg_split('/[^\d]+/', $filters['id'])) : array());
			$id = (!empty($ids)) ? $ids[0] : NULL;
		}
		
		$place_id = (!$this->demo && isset($filters['place_id']) && is_string($filters['place_id']) && strlen($filters['place_id']) >= 20) ? $filters['place_id'] : NULL;
		$rating_min = ($id == NULL && is_numeric($filters['rating_min']) && $filters['rating_min'] >= 0 && $filters['rating_min'] <= 5) ? intval($filters['rating_min']) : NULL;
		$rating_max = ($id == NULL && is_numeric($filters['rating_max']) && $filters['rating_max'] >= 1 && $filters['rating_max'] <= 5) ? intval($filters['rating_max']) : NULL;
		$offset = ($id == NULL && is_numeric($filters['offset']) && $filters['offset'] >= 0) ? intval($filters['offset']) : 0;
		$limit = ($id == NULL && is_numeric($filters['limit']) && $filters['limit'] >= 0) ? intval($filters['limit']) : NULL;
		$sort = ($id == NULL && array_key_exists('sort', $filters) && is_string($filters['sort'])) ? preg_replace('/[^\w_-]/', '', $filters['sort']) : NULL;
		$excerpt_length = (is_numeric($filters['excerpt_length']) && $filters['excerpt_length'] >= 20) ? intval($filters['excerpt_length']) : NULL;
		$review_text_min = (is_numeric($filters['review_text_min']) && $filters['review_text_min'] >= 0) ? intval($filters['review_text_min']) : NULL;
		$review_text_max = (is_numeric($filters['review_text_max']) && $filters['review_text_max'] >= 0 && (!is_numeric($filters['review_text_min']) || is_numeric($filters['review_text_min']) && $filters['review_text_min'] <= $filters['review_text_max'])) ? intval($filters['review_text_max']) : NULL;
		$language = (isset($filters['language']) && is_string($filters['language']) && strlen($filters['language']) >= 2 && strlen($filters['language']) <= 16) ? preg_replace('/^([a-z]{2,3}).*$/i', '$1', strtolower($filters['language'])) : NULL;

		if (!$override)
		{
			$limit = (is_numeric($limit)) ? intval($limit) : get_option($this->alias . '_review_limit', NULL);
			$sort = (is_string($sort)) ? preg_replace('/[^\w_-]/', '', $sort) : get_option($this->alias . '_review_sort', NULL);
			$rating_min = (is_numeric($rating_min)) ? intval($rating_min) : get_option($this->alias . '_rating_min', NULL);
			$rating_max = (is_numeric($rating_max)) ? intval($rating_max) : get_option($this->alias . '_rating_max', NULL);
			$review_text_min = (is_numeric($review_text_min) && $review_text_min >= 0) ? intval($review_text_min) : get_option($this->alias . '_review_text_min', NULL);
			$review_text_max = (is_numeric($review_text_max) && $review_text_max >= 0) ? intval($review_text_max) : get_option($this->alias . '_review_text_max', NULL);
		}
		
		$this->review_sort_option = ($sort != NULL && $sort != 'relevance_desc' && array_key_exists($sort, $this->review_sort_options)) ? $sort : NULL;
				
		if (!$filters['display_reviews'])
		{
			$limit = 0;
		}
		elseif (is_numeric($limit) && $limit == 0)
		{
			$display_reviews = FALSE;
		}
		
		if (!empty($ids))
		{
			$this->reviews_filtered = array();
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
			
			if (!$a['status'])
			{
				unset($this->reviews_filtered[$key]);
				continue;
			}
			
			if (is_numeric($rating_min) && $a['rating'] < $rating_min || is_numeric($rating_max) && $a['rating'] > $rating_max)
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
	
	private function reviews_count()
	{
		// Count the number of reviews stored and visible
		
		$count = 0;
		
		if (!is_array($this->reviews))
		{
			return $count;
		}

		foreach ($this->reviews as $a)
		{
			$count += ($a['status']) ? 1 : 0;
		}
		
		return $count;
	}
	
    public function update($new_instance, $old_instance = array())
    {
		// Process Dashboard form updates
		
		$default_values = $this->default_values();
		$set_default = (!array_key_exists('title', $new_instance));
        $a = array();
		
		foreach ($default_values as $key => $default_value)
		{
			if (!array_key_exists($key, $new_instance))
			{
				if ($set_default)
				{
					$a[$key] = $default_value;
					continue;
				}
				
				$a[$key] = (is_bool($default_value)) ? FALSE : NULL;
				continue;
			}
			
			if (is_bool($default_value))
			{
				$a[$key] = (!$set_default) ? (bool)$new_instance[$key] : $default_value;
				continue;
			}
			
			if (is_numeric($default_value) || preg_match('/^.+_(?:min|max)$/i', $key))
			{
				$a[$key] = (is_numeric($new_instance[$key])) ? intval($new_instance[$key]) : (($set_default) ? $default_value : NULL);
				continue;
			}
			
			if (!is_string($new_instance[$key]))
			{
				continue;
			}
			
			$a[$key] = ($new_instance[$key] != NULL) ? strip_tags($new_instance[$key]) : (($set_default) ? $default_value : NULL);
		}
		
		if ((!is_numeric($a['limit']) || is_numeric($a['limit']) && $a['limit'] > 5) && preg_match('/badge/i', $a['theme']))
		{
			$a['limit'] = (is_numeric($a['limit']) && $a['limit'] > 5) ? 5 : 0;
		}
		
		if (!$a['display_reviews'] && (!is_numeric($a['limit']) || is_numeric($a['limit']) && $a['limit'] > 0))
		{
			$a['limit'] = 0;
		}
		elseif ($a['display_reviews'] && is_numeric($a['limit']) && $a['limit'] == 0)
		{
			$a['display_reviews'] = FALSE;
			
			if (!array_key_exists('display_reviews', $old_instance) || array_key_exists('display_reviews', $old_instance) && !$old_instance['display_reviews'])
			{
				$a['display_reviews'] = TRUE;
				$a['limit'] = 1;
			}
		}
						
		if ($a['sort'] == 'relevance_desc')
		{
			$a['sort'] = NULL;
		}
		
		if (is_numeric($a['rating_max']) && $a['rating_max'] < 1)
		{
			$a['rating_max'] = 1;
		}
		
		if (is_numeric($a['rating_min']) && $a['rating_min'] < 1)
		{
			$a['rating_min'] = 1;
		}
		
		if (is_numeric($a['rating_min']) && is_numeric($a['rating_max']) && $a['rating_min'] > $a['rating_max'])
		{
			$a['rating_min'] = $a['rating_max'];
		}
		
		if (is_numeric($a['review_text_min']) && is_numeric($a['review_text_max']) && $a['review_text_min'] > $a['review_text_max'])
		{
			$a['review_text_min'] = $a['review_text_max'];
		}
		
		foreach ($default_values as $k => $v)
		{
			if (is_bool($v))
			{
				$a[$k] = $a[$k] ? '1' : '';
			}
		}
        
        return $a;
    }

	public function admin_css_load()
	{
		// Load style sheet in the Dashboard
		
		global $pagenow;
		
		if (!preg_match('/^(?:widgets|customize)\.php$/', $pagenow))
		{
			return;
		}
		
 		wp_register_style($this->alias . '_admin_css', plugins_url('g-business-reviews-rating/admin/css/css.css'));
		wp_enqueue_style($this->alias . '_admin_css');
	}
	
	public function admin_js_load()
	{
		// Load Javascript in the Dashboard
		
		global $pagenow;
		
		if (!preg_match('/^(?:widgets|customize)\.php$/', $pagenow))
		{
			return;
		}
		
		wp_register_script(__CLASS__ . '_admin_js', plugins_url('g-business-reviews-rating/admin/js/js.js'));
		wp_localize_script(__CLASS__ . '_admin_js', $this->alias . '_admin_ajax', array('url' => admin_url('admin-ajax.php'), 'action' => 'google_business_reviews_rating_admin_ajax'));
		wp_enqueue_script(__CLASS__ . '_admin_js');
	}
	
    public function widget($args, $instance)
    {
		// Display the widget
		
		$html = '';
		$default_values = $this->default_values();
        extract($args, EXTR_SKIP);
        extract($instance, EXTR_SKIP);

		if (count($default_values) > count($instance))
		{
			extract($default_values, EXTR_SKIP);
		}

        $title = apply_filters('widget_title', $title);
        $multiplier = (isset($multiplier)) ? $multiplier : NULL;
		$view_reviews_url = ($this->demo) ? 'https://search.google.com/local/reviews?placeid=ChIJq6pqZz2uEmsRaQAMbAl0RW0' :  'https://search.google.com/local/reviews?placeid=' . esc_attr($this->place_id);			
		$write_review_url = ($this->demo) ? 'https://search.google.com/local/writereview?placeid=ChIJq6pqZz2uEmsRaQAMbAl0RW0' :  'https://search.google.com/local/writereview?placeid=' . esc_attr($this->place_id);			
		
		if ($this->data == NULL || !isset($this->data['reviews']) || isset($this->data) && !is_array($this->data['reviews']))
		{
			$html = '<p class="error">' . esc_html__('Error: No review data found', 'g-business-reviews-rating') . '</p>';
			return $before_widget . (($title != NULL) ? $before_title . $title . $after_title : '') . $html . $after_widget;
		}

		$this->reviews_filter($instance);
		
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
			$theme = $this->theme;
			
			if (preg_match('/^light(?:\s+([^\s].+))?$/i', $theme, $m))
			{
				$theme = (isset($m[1])) ? $m[1] : NULL;
			}
		}
								
		$html = '<div class="google-business-reviews-rating'
		. (($stylesheet) ? ((is_string($theme) && strlen($theme) > 2) ? ' ' . esc_attr($theme) . ((!$display_reviews && !$display_view_reviews_button && !$display_write_review_button && preg_match('/\bbadge\b/i', $theme)) ? ' link' : '') : '') : '-clear') . '"'
		. ((!$display_reviews && !$display_view_reviews_button && !$display_write_review_button && preg_match('/\bbadge\b/i', $theme) && $view_reviews_url != NULL) ? ' data-href="' . esc_attr($view_reviews_url) . '"' : '')
		. '>
';
		if ($display_name)
		{
			$html .= '	<h3 class="business-name">' . (($display_icon && $this->business_icon != NULL) ? '<span class="icon"><img src="' . esc_attr($this->business_icon) . '" alt="' . esc_attr($this->business_name . ' ' . __('Icon', 'g-business-reviews-rating')) . '"></span>' : '') . esc_html($this->business_name) . '</h3>
';
		}
		elseif ($display_icon && $this->business_icon != NULL)
		{
			$html .= '	<h3 class="business-icon icon"><img src="' . esc_attr($this->business_icon) . '" alt="' . esc_attr($this->business_name . ' ' . __('Icon', 'g-business-reviews-rating')) . '"></h3>
';
		}
		
		if ($display_rating)
		{
			$html .= '	<p class="rating">';
			if ($display_rating_stars)
			{
				$html .= '<span class="number">' . esc_html(number_format($this->rating, 1)) . '</span> ';
				if (get_option($this->alias . '_stylesheet'))
				{
					$partial = (round($this->rating * 10, 0, PHP_ROUND_HALF_UP) - floor($this->rating) * 10) * 10;
					$html .= '<span class="all-stars' . (($animate) ? ' animate' : '') . '">'
					. str_repeat('<span class="star"></span>', ($partial > 0) ? floor($this->rating) : ceil($this->rating))
					. (($partial > 0) ? '<span class="star split-' . $partial . '-' . (100 - $partial) . '"></span>' : '')
					. str_repeat('<span class="star gray"></span>', ($partial > 0) ? (5 - ceil($this->rating)) : (5 - floor($this->rating)))
					. '</span> ';
				}
				else
				{
					$html .=  '<span class="rating-stars" data-rating="'.esc_attr($this->rating).'" data-multiplier="'.(is_numeric($multiplier) ? esc_attr($multiplier) : '').'">'.str_repeat('★', round($this->rating)) . ((round($this->rating) < 5) ? '<span class="not">' . str_repeat('☆', (5 - round($this->rating, 0, PHP_ROUND_HALF_DOWN))) . '</span>' : '').'</span> ';
				}
			}
			else
			{
				$html .= '<span class="number-text">' . esc_html__('Rating:', 'g-business-reviews-rating') . '</span> <span class="number">' . esc_html(number_format($this->rating, 1)) . '</span> ';
			}
			
			if ($display_review_count)
			{

				$html .= '<a href="' . esc_attr($view_reviews_url). '" target="_blank" rel="nofollow" class="count">' . esc_html($this->user_ratings_total) . ' ' . (($this->user_ratings_total == 1) ? 'review' : 'reviews') . '</a>';
			}
			$html .= '</p>
';
		}
		elseif ($display_review_count)
		{
			$html .= '	<p class="review-count"><a href="' . esc_attr($view_reviews_url). '" target="_blank" rel="nofollow" class="count">' . esc_html($this->user_ratings_total) . ' ' . (($this->user_ratings_total == 1) ? 'review' : 'reviews') . '</a></p>
';
		}
		
		if ($display_reviews)
		{
			if (empty($this->reviews))
			{
				$html .= '	<p class="listing no-reviews">No reviews found.</p>
';
			}
			elseif (empty($this->reviews_filtered))
			{
				$html .= '	<p class="listing no-reviews">No reviews found due to filtering restrictions.</p>
';
			}
			else
			{
				$html .= '	<ul class="listing">
';
				foreach ($this->reviews_filtered as $i => $a)
				{
					$html .= '		<li class="' . esc_attr('rating-' . $a['rating']) . '">
			<span class="author-avatar"><a href="' . esc_attr($a['author_url']) . '" target="_blank">' . (($a['profile_photo_url'] != NULL) ? '<img src="' . esc_attr($a['profile_photo_url']) . '" alt="Avatar">' : '&mdash;') . '</a></span>
			<span class="author-name"><a href="' . esc_attr($a['author_url']) . '" target="_blank">' . esc_html($a['author_name']) . '</a></span>
			<span class="rating">' . str_repeat('★', $a['rating']) . (($a['rating'] < 5) ? '<span class="not">' . str_repeat('☆', (5 - $a['rating'])) . '</span>' : '') . '</span>
			<span class="relative-time-description">' . esc_html($a['relative_time_description']) . '</span>
';

					if ($display_review_text)
					{
						$set_excerpt = (is_numeric($excerpt_length) && strlen(strip_tags($a['text'])) > 20 && $excerpt_length < round(strlen(strip_tags($a['text'])) * 1.1));
						$html .= '			<div class="text' . (($set_excerpt) ? ' text-excerpt' : '') . '">';
						
						if ($set_excerpt)
						{
							$html .= preg_replace('/(\r\n|\r|\n)+/', '<br>' . PHP_EOL . '				', preg_replace('/^(.{' . $excerpt_length . '}[^\s]{0,20})(.*)$/is', '<span class="review-snippet">$1</span> <span class="review-more-placeholder">… ' . esc_html($more) . '</span><span class="review-full-text">$2</span>', esc_html(strip_tags($a['text']))));
						}
						else
						{
							$html .= preg_replace('/(\r\n|\r|\n)+/', '<br>' . PHP_EOL . '				', esc_html(strip_tags($a['text'])));
						}
						
						$html .= '</div>
';
					}
					
					$html .= '		</li>
';
				}
				$html .= '	</ul>
';
			}
		}
		
		if ($display_view_reviews_button || $display_write_review_button)
		{
			$html .= '	<p class="buttons">';
			
			if ($display_view_reviews_button)
			{
				$html .= '<a href="' . esc_attr($view_reviews_url) . '" target="_blank" rel="nofollow" class="button widget-button view-reviews">' . esc_html__('View Reviews', 'g-business-reviews-rating') . '</a>';
			}
			
			if ($display_view_reviews_button && $display_write_review_button)
			{
				$html .= ' ';
			}
			
			if ($display_write_review_button)
			{
				$html .= '<a href="' . esc_attr($write_review_url) . '" target="_blank" rel="nofollow" class="button widget-button write-review">' . esc_html__('Write Review', 'g-business-reviews-rating') . '</a>';
			}
			
			$html .= '</p>
';
		}
		
		if ($display_attribution)
		{
			$html .= '	<p class="attribution"><span class="powered-by-google"></span></p>
';
		}
		$html .= '</div>
';
        echo $before_widget . (($title != NULL) ? $before_title . $title . $after_title : '') . $html . $after_widget;
    }
    
    public function form($instance)
    {
		// Display the widget form in Dashboard
				
		$html = '';
		
		if (!$this->demo)
		{
			if ((!$this->api_key || $this->api_key == NULL) && (!$this->place_id || $this->place_id == NULL))
			{
				$html = '        <p class="error"><a href="' . esc_attr($this->plugin_settings_url) . '">' . esc_html__('Please set your Google API Key and Place ID', 'g-business-reviews-rating') . '</a>.</p>
        <p class="buttons"><a href="' . esc_attr($this->plugin_settings_url) . '" class="button button-secondary">' . esc_html(__('Settings', 'g-business-reviews-rating')) . '</a></p>
';
			}
			elseif (!$this->api_key || $this->api_key == NULL)
			{
				$html = '        <p class="error"><a href="' . esc_attr($this->plugin_settings_url) . '">' . esc_html__('Please set your Google API Key', 'g-business-reviews-rating') . '</a>.</p>
        <p class="buttons"><a href="' . esc_attr($this->plugin_settings_url) . '" class="button button-secondary">' . esc_html__('Settings', 'g-business-reviews-rating') . '</a></p>
';
			}
			elseif (!$this->place_id || $this->place_id == NULL)
			{
				$html = '        <p class="error"><a href="' . esc_attr($this->plugin_settings_url) . '">' . esc_html__('Please set your Place ID', 'g-business-reviews-rating') . '</a>.</p>
        <p class="buttons"><a href="' . esc_attr($this->plugin_settings_url) . '" class="button button-secondary">' . esc_html__('Settings', 'g-business-reviews-rating') . '</a></p>
';
			}
			elseif ($this->result == NULL)
			{
				$html = '        <p class="error">'.esc_html__('No rating or review data found.', 'g-business-reviews-rating') . ' <a href="' . esc_attr($this->plugin_settings_url) . '">' . esc_html__('Please check your Rating and Reviews settings.', 'g-business-reviews-rating') . '</a>.</p>
        <p class="buttons"><a href="' . esc_attr($this->plugin_settings_url) . '" class="button button-secondary">' . esc_html__('Settings', 'g-business-reviews-rating') . '</a></p>
';
			}
		}
		
		if ($html != '')
		{
			echo $html;
			return;
		}

		$count = $this->reviews_count();
		
		if ((!is_numeric($this->rating) || is_numeric($this->rating) && $this->rating == 0) && $count == 0)
		{
			$html = '        <p class="error">' . esc_html__('Not reviews or ratings exist.', 'g-business-reviews-rating') . '</p>
';
		}
		
		if ($html != '')
		{
			echo $html;
			return;
		}

		$default_values = $this->default_values();

		if (!array_key_exists('title', $instance) || !array_key_exists('limit', $instance) || !array_key_exists('rating_min', $instance) || !array_key_exists('rating_max', $instance))
		{
			$instance = array_merge($default_values, $instance);
		}
		
		extract($instance, EXTR_SKIP);
		
		if (count($default_values) != count($instance))
		{
			extract($default_values, EXTR_SKIP);
		}

		include(plugin_dir_path(__FILE__) . 'templates/widget.php');
		return;
    }
}
