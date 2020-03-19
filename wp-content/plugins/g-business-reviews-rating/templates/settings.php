<?php

if (!defined('ABSPATH'))
{
	die();
}

?>
<div id="google-business-reviews-rating-settings" class="wrap banner">
	<h1><?php esc_html_e('Reviews and Rating - Google Business', 'g-business-reviews-rating') . (($this->demo) ? ' <span class="demo"><span class="dashicons dashicons-warning"></span> ' . __('Demo Mode', 'g-business-reviews-rating') . '</span>' : ''); ?></h1>
    <p id="plugin-attribution"><span class="powered-by-google"></span></p>
		<nav class="nav-tab-wrapper wp-clearfix" aria-label="Secondary menu">
            <a href="#setup" class="nav-tab<?php echo ($this->section == NULL) ? ' nav-tab-active' : ''; ?>"><span class="dashicons dashicons-admin-settings"></span> <?php esc_html_e('Setup', 'g-business-reviews-rating'); ?></a>

<?php if ($this->valid()): ?>
            <a href="#shortcodes" class="nav-tab<?php echo ($this->section == 'shortcodes') ? ' nav-tab-active' : ''; ?>"><span class="dashicons dashicons-editor-code"></span> <?php esc_html_e('Shortcodes', 'g-business-reviews-rating'); ?></a><?php endif; ?>

<?php if ($this->count_reviews_all >= 1): ?>
            <a href="#reviews" class="nav-tab<?php echo ($this->section == 'reviews') ? ' nav-tab-active' : ''; ?>"><span class="dashicons dashicons-star-filled"></span> <?php esc_html_e('Reviews', 'g-business-reviews-rating'); ?> <span class="count"><?php echo esc_html($this->count_reviews_all); ?></span></a><?php endif; ?>

<?php if ($this->retrieved_data_check()): ?>
            <a href="#data" class="nav-tab<?php echo ($this->section == 'data') ? ' nav-tab-active' : ''; ?>"><span class="dashicons dashicons-cloud"></span> <?php esc_html_e('Retrieved Data', 'g-business-reviews-rating'); ?></a><?php endif; ?>

            <a href="#advanced" class="nav-tab<?php echo ($this->section == 'advanced') ? ' nav-tab-active' : ''; ?>"><span class="dashicons dashicons-buddicons-groups"></span> <?php esc_html_e('Advanced', 'g-business-reviews-rating'); ?></a>

            <a href="#about" class="nav-tab<?php echo ($this->section == 'about') ? ' nav-tab-active' : ''; ?>"><span class="dashicons dashicons-heart"></span> <?php esc_html_e('About', 'g-business-reviews-rating'); ?></a>
		</nav>
		<div id="setup" class="section<?php echo (($this->section != NULL) ? ' hide' : '') . ((preg_match('/\bdark\b/i', get_option('google_business_reviews_rating_reviews_theme'))) ? ' dark' : '') . ((preg_match('/\bfonts\b/i', get_option('google_business_reviews_rating_reviews_theme'))) ? ' fonts' : '') ?>"<?php echo ($this->data_hunter('test')) ? ' data-hunter="' . esc_attr($this->data_hunter('json')) . '"' : ''; ?>>
            <form method="post" action="options.php" id="google-business-reviews-rating-setup">
<?php
	settings_fields('google_business_reviews_rating_settings');
	do_settings_sections('google_business_reviews_rating_settings');
	
if ($this->valid()): ?>
            <h2><?php esc_html_e('Reviews and Rating', 'g-business-reviews-rating'); ?></h2>
            <p><?php _e('The general settings for your reviews and rating elements. Shortcode parameters will take precedence.', 'g-business-reviews-rating'); ?></p>
            <table id="reviews-rating-settings" class="form-table">
                <tr>
                    <th scope="row"><label for="review-limit"><?php esc_html_e('Review Limit', 'g-business-reviews-rating'); ?></label></th>
                    <td>
                        <p class="input">
                            <input type="number" id="review-limit" class="small-text" name="google_business_reviews_rating_review_limit" value="<?php echo esc_attr(get_option('google_business_reviews_rating_review_limit')); ?>" placeholder="&mdash;" step="1" min="0" max="<?php echo ($this->count_reviews_all > 5) ? esc_attr(round($this->count_reviews_all * 1.2)) : 5; ?>">
                            <label for="review-limit-hide"><input type="radio" id="review-limit-hide" name="google_business_reviews_rating_review_limit_option" value="0"<?php echo (!$this->show_reviews) ? ' checked="checked"' : ''; ?>> <?php esc_html_e('Hide Reviews', 'g-business-reviews-rating'); ?></label>
                            <label for="review-limit-set"><input type="radio" id="review-limit-set" name="google_business_reviews_rating_review_limit_option" value="1"<?php echo ($this->show_reviews && is_numeric(get_option('google_business_reviews_rating_review_limit'))) ? ' checked="checked"' : ''; ?>> <?php esc_html_e('Show Limited Reviews', 'g-business-reviews-rating'); ?></label>
                            <label for="review-limit-all"><input type="radio" id="review-limit-all" name="google_business_reviews_rating_review_limit_option" value="all"<?php echo ($this->show_reviews && !is_numeric(get_option('google_business_reviews_rating_review_limit'))) ? ' checked="checked"' : ''; ?>> <?php esc_html_e('Show All Reviews', 'g-business-reviews-rating'); ?></label>
                        </p>
                        <p class="description"><?php printf(_n('You currently have %1$s active review retrieved from Google Places.', 'You currently have %1$s active reviews retrieved from Google Places (and imported).', $this->count_reviews_active), $this->count_reviews_active); ?></p>
                    </td>
                </tr>
                <tr class="show-reviews">
                    <th scope="row"><label for="review-sort"><?php esc_html_e('Review Sort', 'g-business-reviews-rating'); ?></label></th>
                    <td>
                        <select id="review-sort" name="google_business_reviews_rating_review_sort"<?php echo (!$this->show_reviews) ? ' disabled="disabled"' : ''; ?>>
<?php
	foreach ($this->review_sort_options as $k => $a)
	{
?>
                            <option value="<?php echo (($k == 'relevance_desc') ? '' : esc_attr($k)); ?>"<?php echo (get_option('google_business_reviews_rating_review_sort') == $k || $k == 'relevance_desc' && get_option('google_business_reviews_rating_review_sort') == NULL) ? ' selected' : ''; ?>><?php echo esc_attr($a['name'] . ((isset($a['min_max_values']) && is_array($a['min_max_values'])) ? ' (' . implode(' → ', $a['min_max_values']) . ')' : '')); ?></option>
<?php
	}
?>
                        </select>
                    </td>
                </tr>
                <tr class="show-reviews">
                    <th scope="row"><label for="rating-min"><?php esc_html_e('Rating Range', 'g-business-reviews-rating'); ?></label></th>
                    <td>
                        <select id="rating-min" class="min" name="google_business_reviews_rating_rating_min"<?php echo (!$this->show_reviews) ? ' disabled="disabled"' : ''; ?>>
                            <option value=""<?php echo (get_option('google_business_reviews_rating_rating_min') == NULL) ? ' selected' : ''; ?>>&mdash;</option>
<?php
	for ($i = 1; $i <= 5; $i++)
	{
?>

                            <option value="<?php echo esc_attr($i); ?>"<?php echo (get_option('google_business_reviews_rating_rating_min') == $i) ? ' selected' : ''; ?>><?php echo esc_attr($i); ?></option>
<?php
	}
?>
                        </select> – 
                        <select id="rating-max" class="max" name="google_business_reviews_rating_rating_max"<?php echo (!$this->show_reviews) ? ' disabled="disabled"' : ''; ?>>
                            <option value=""<?php echo (get_option('google_business_reviews_rating_rating_max') == NULL) ? ' selected' : ''; ?>>&mdash;</option>
<?php
	for ($i = 1; $i <= 5; $i++)
	{
?>
                            <option value="<?php echo esc_attr($i); ?>"<?php echo (get_option('google_business_reviews_rating_rating_max') == $i) ? ' selected' : ''; ?>><?php echo esc_attr($i); ?></option>
<?php
	}
?>
                        </select>
                    </td>
                </tr>
                <tr class="show-reviews">
                    <th scope="row"><label for="rating-min"><?php esc_html_e('Review Text Length Range', 'g-business-reviews-rating'); ?></label></th>
                    <td>
                        <input type="number" id="review-text-min" class="min" name="google_business_reviews_rating_review_text_min" value="<?php echo esc_attr(get_option('google_business_reviews_rating_review_text_min')); ?>" placeholder="&mdash;" step="1" min="0"<?php echo (!$this->show_reviews) ? ' disabled="disabled"' : ''; ?>> – 
                        <input type="number" id="review-text-max" class="min" name="google_business_reviews_rating_review_text_max" value="<?php echo esc_attr(get_option('google_business_reviews_rating_review_text_max')); ?>" placeholder="&mdash;" step="1" min="0"<?php echo (!$this->show_reviews) ? ' disabled="disabled"' : ''; ?>> 
                    </td>
                </tr>
                <tr class="show-reviews">
                    <th scope="row"><?php esc_html_e('Review Excerpt Length', 'g-business-reviews-rating'); ?></th>
                    <td>
                        <p class="input">
                            <input type="number" id="review-text-excerpt-length" class="small-text" name="google_business_reviews_rating_review_text_excerpt_length" value="<?php echo esc_attr(get_option('google_business_reviews_rating_review_text_excerpt_length')); ?>" placeholder="&mdash;" step="1" min="20"<?php echo (!$this->show_reviews) ? ' disabled="disabled"' : ''; ?>>
                        </p>
                        <p class="description"><?php /* translators: %s: refers to a HTML ID, leave unchanged */
						echo sprintf(__('The characters displayed before a <a href="%s" class="void">… More</a> toggle is shown to reveal the full review text. Leave empty for no excerpt.', 'g-business-reviews-rating'), '#review-text-excerpt-length'); ?></p>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="reviews-theme"><?php esc_html_e('Theme', 'g-business-reviews-rating'); ?></label></th>
                    <td>
                        <select id="reviews-theme" name="google_business_reviews_rating_reviews_theme"<?php echo (!get_option('google_business_reviews_rating_stylesheet') ? ' disabled="disabled"' : ''); ?>>
							<option value=""<?php echo (get_option('google_business_reviews_rating_reviews_theme') == NULL) ? ' selected' : ''; ?>><?php esc_html_e('Default', 'g-business-reviews-rating'); ?></option>
<?php
	foreach ($this->reviews_themes as $k => $name)
	{
?>
                            <option value="<?php echo esc_attr($k); ?>"<?php echo (get_option('google_business_reviews_rating_reviews_theme') == $k) ? ' selected' : ''; ?>><?php echo esc_attr($name); ?></option>
<?php
	}
?>
                        </select>
                        <label for="stylesheet"><input type="checkbox" id="stylesheet" name="google_business_reviews_rating_stylesheet" value="1"<?php echo (get_option('google_business_reviews_rating_stylesheet') ? ' checked="checked"' : ''); ?>> <?php esc_html_e('Load Style Sheet', 'g-business-reviews-rating'); ?></label>
                        <p id="theme-recommendation-badge" class="description"><?php _e('We recommend hiding all reviews with this theme.', 'g-business-reviews-rating'); ?></p>
                        <p id="theme-recommendation-columns" class="description"><?php _e('We recommend matching the limit to multiples of columns', 'g-business-reviews-rating'); ?></p>
                    </td>
                </tr>
                <tr<?php echo ((get_option('google_business_reviews_rating_icon') == NULL) ? ' class="empty"' : ''); ?>>
                    <th scope="row"><?php esc_html_e('Icon', 'g-business-reviews-rating'); ?></th>
                    <td>
                        <p class="icon-image<?php echo (get_option('google_business_reviews_rating_icon') == NULL) ? ' empty' : ''; ?>">
                            <span id="icon-image-preview" class="image thumbnail"><?php echo (get_option('google_business_reviews_rating_icon') != NULL) ? preg_replace('/\s+(?:width|height)="\d*"/i', '', wp_get_attachment_image($this->icon_image_id, 'large')) : ''; ?></span>
                            <span class="set"><button type="button" id="icon-image" class="button button-secondary ui-button" name="icon-image" value="1" data-set-text="<?php esc_attr_e('Choose Image', 'g-business-reviews-rating'); ?>" data-replace-text="<?php esc_attr_e('Replace', 'g-business-reviews-rating'); ?>"><span class="dashicons dashicons-format-image"></span> <?php echo (get_option('google_business_reviews_rating_icon') == NULL) ? esc_attr(__('Choose Image', 'g-business-reviews-rating')) : esc_attr(__('Replace', 'g-business-reviews-rating')); ?></button></span>
                            <span class="delete"<?php echo (get_option('google_business_reviews_rating_icon') == NULL) ? ' style="display: none;"' : ''; ?>><button type="button" id="icon-image-delete" class="button button-secondary ui-button" name="icon-image-delete" value="1"><span class="dashicons dashicons-no"></span> Remove</button></span>
                            <input type="hidden" id="icon-image-id" name="google_business_reviews_rating_icon" value="<?php echo esc_attr($this->icon_image_id); ?>">
                        </p>
                    </td>
                </tr>
            </table>
            <h2 id="reviews-rating-preview-heading" class="hide"><a href="reviews-rating-preview"><span class="dashicons dashicons-arrow-right"></span> <?php esc_html_e('Preview', 'g-business-reviews-rating'); ?></a></h2>
            <div id="reviews-rating-preview" class="hide <?php echo esc_attr(get_option('google_business_reviews_rating_reviews_theme')); ?>">
            </div>

<?php else: ?>
            <input type="hidden" id="review-limit" name="google_business_reviews_rating_review_limit" value="<?php echo esc_attr(get_option('google_business_reviews_rating_review_limit')); ?>">
            <input type="hidden" id="rating-min" name="google_business_reviews_rating_rating_min" value="<?php echo esc_attr(get_option('google_business_reviews_rating_rating_min')); ?>">
            <input type="hidden" id="rating-max" name="google_business_reviews_rating_rating_max" value="<?php echo esc_attr(get_option('google_business_reviews_rating_rating_max')); ?>">
            <input type="hidden" id="review-text-min" name="google_business_reviews_rating_review_text_min" value="<?php echo esc_attr(get_option('google_business_reviews_rating_review_text_min')); ?>">
            <input type="hidden" id="review-text-max" name="google_business_reviews_rating_review_text_max" value="<?php echo esc_attr(get_option('google_business_reviews_rating_review_text_max')); ?>">
            <input type="hidden" id="review-text-excerpt-length" name="google_business_reviews_rating_review_text_excerpt_length" value="<?php echo esc_attr(get_option('google_business_reviews_rating_review_text_excerpt_length')); ?>">
            <input type="hidden" id="reviews-theme" name="google_business_reviews_rating_reviews_theme" value="<?php echo esc_attr(get_option('google_business_reviews_rating_reviews_theme')); ?>">
            <input type="hidden" id="stylesheet" name="google_business_reviews_rating_stylesheet" value="<?php echo esc_attr(get_option('google_business_reviews_rating_stylesheet')); ?>">
            <input type="hidden" id="icon" name="google_business_reviews_rating_icon" value="<?php echo esc_attr(get_option('google_business_reviews_rating_icon')); ?>">
            <input type="hidden" id="structured-data" name="google_business_reviews_rating_structured_data" value="<?php echo esc_attr(get_option('google_business_reviews_rating_structured_data')); ?>">
            <input type="hidden" id="telephone" name="google_business_reviews_rating_telephone" value="<?php echo esc_attr(get_option('google_business_reviews_rating_telephone')); ?>">
            <input type="hidden" id="business-type" name="google_business_reviews_rating_business_type" value="<?php echo esc_attr(get_option('google_business_reviews_rating_business_type')); ?>">
            <input type="hidden" id="price-range" name="google_business_reviews_rating_price_range" value="<?php echo esc_attr(get_option('google_business_reviews_rating_price_range')); ?>">
            <input type="hidden" id="logo" name="google_business_reviews_rating_logo" value="<?php echo esc_attr(get_option('google_business_reviews_rating_logo')); ?>">
<?php endif; ?>

<?php if (!$this->demo && $this->valid() && $this->structured_data(TRUE)): ?>
            <h2><?php esc_html_e('Structured Data', 'g-business-reviews-rating'); ?></h2>
            <p><?php /* translators: %s: refers to Schema URL and name, leave unchanged */ 
			echo sprintf(__('Allow search engines to easily read review data for your website using Structured Data %s which includes general business information and recent, relevant and visible reviews.', 'g-business-reviews-rating'), '(<a href="//schema.org" class="components-external-link" target="_blank">Schema.org</a>)'); ?></p>
            <table class="form-table">
                <tr>
                    <th scope="row"><label for="structured-data"><?php esc_html_e('Structured Data', 'g-business-reviews-rating'); ?></label></th>
                    <td>
                        <p>
                            <label for="structured-data"><input type="checkbox" id="structured-data" name="google_business_reviews_rating_structured_data" value="1"<?php echo (get_option('google_business_reviews_rating_structured_data') ? ' checked="checked"' : ''); ?><?php echo ($this->count_reviews_active == 0) ? ' disabled="disabled"' : ''; ?>> <?php esc_html_e('Enable and insert Structured Data on home page.', 'g-business-reviews-rating'); ?></label>
                            <button type="button" name="structured-data-preview" id="structured-data-preview" class="button button-secondary structured-data"<?php echo (get_option('google_business_reviews_rating_structured_data') ? '' : ' style="display: none"'); ?>><span class="dashicons dashicons-text-page"></span> <?php esc_html_e('Preview', 'g-business-reviews-rating'); ?></button>
						</p>
                    </td>
                </tr>
                <tr class="structured-data"<?php echo (get_option('google_business_reviews_rating_structured_data') ? '' : ' style="display: none"'); ?>>
                    <th scope="row"><label for="telephone"><?php esc_html_e('Telephone', 'g-business-reviews-rating'); ?></label></th>
                    <td>
                        <input type="tel" id="telephone" name="google_business_reviews_rating_telephone" placeholder="&mdash;" value="<?php echo esc_attr(get_option('google_business_reviews_rating_telephone')); ?>">
                    </td>
                </tr>
                <tr class="structured-data"<?php echo (get_option('google_business_reviews_rating_structured_data') ? '' : ' style="display: none"'); ?>>
                    <th scope="row"><label for="business-type"><?php esc_html_e('Business Type', 'g-business-reviews-rating'); ?></label></th>
                    <td>
                        <select id="business-type" name="google_business_reviews_rating_business_type">
                        <option value=""<?php echo (get_option('google_business_reviews_rating_business_type') == NULL) ? ' selected' : ''; ?>><?php esc_html_e('Not Applicable/Other', 'g-business-reviews-rating'); ?></option>
<?php
	foreach ($this->business_types as $k => $name)
	{
?>
                            <option value="<?php echo esc_attr($k); ?>"<?php echo (get_option('google_business_reviews_rating_business_type') == $k) ? ' selected' : ''; ?>><?php echo esc_attr($name); ?></option>
<?php
	}
?>
                        </select>
                    </td>
                </tr>
                <tr class="structured-data"<?php echo (get_option('google_business_reviews_rating_structured_data') ? '' : ' style="display: none"'); ?>>
                    <th scope="row"><label for="price-range"><?php esc_html_e('Price Range', 'g-business-reviews-rating'); ?></label></th>
                    <td>
                        <select id="price-range" name="google_business_reviews_rating_price_range">
                            <option value=""<?php echo (get_option('google_business_reviews_rating_price_range') == NULL) ? ' selected' : ''; ?>><?php esc_attr_e('Not Applicable', 'g-business-reviews-rating'); ?></option>
<?php
	foreach ($this->price_ranges as $k => $a)
	{
?>
                            <option value="<?php echo esc_attr($k); ?>"<?php echo (get_option('google_business_reviews_rating_price_range') == $k) ? ' selected' : ''; ?>><?php echo esc_attr($a['name']); ?></option>
<?php
	}
?>
                        </select>
                    </td>
                </tr>
                <tr id="logo-image-row" class="structured-data<?php echo ((get_option('google_business_reviews_rating_logo') == NULL) ? ' empty' : ''); ?>"<?php echo ((get_option('google_business_reviews_rating_structured_data') ? '' : ' style="display: none"')); ?>>
                    <th scope="row"><?php esc_html_e('Logo', 'g-business-reviews-rating'); ?></th>
                    <td>
                        <p class="logo-image<?php echo (get_option('google_business_reviews_rating_logo') == NULL) ? ' empty' : ''; ?>">
                            <span id="logo-image-preview" class="image thumbnail"><?php echo (get_option('google_business_reviews_rating_logo') != NULL) ? preg_replace('/\s+(?:width|height)="\d*"/i', '', wp_get_attachment_image($this->logo_image_id, 'large')) : ''; ?></span>
                            <span class="set"><button type="button" id="logo-image" class="button button-secondary ui-button" name="logo-image" value="1" data-set-text="<?php esc_attr_e('Choose Image', 'g-business-reviews-rating'); ?>" data-replace-text="<?php esc_attr_e('Replace', 'g-business-reviews-rating'); ?>"><span class="dashicons dashicons-format-image"></span> <?php echo (get_option('google_business_reviews_rating_logo') == NULL) ? esc_attr(__('Choose Image', 'g-business-reviews-rating')) : esc_attr(__('Replace', 'g-business-reviews-rating')); ?></button></span>
                            <span class="delete"<?php echo (get_option('google_business_reviews_rating_logo') == NULL) ? ' style="display: none;"' : ''; ?>><button type="button" id="logo-image-delete" class="button button-secondary ui-button" name="logo-image-delete" value="1"><span class="dashicons dashicons-no"></span> Remove</button></span>
                            <input type="hidden" id="logo-image-id" name="google_business_reviews_rating_logo" value="<?php echo esc_attr($this->logo_image_id); ?>">
                        </p>
                    </td>
                </tr>
            </table>

<?php endif; ?>
            <h2><?php esc_html_e('Google Credentials', 'g-business-reviews-rating'); ?></h2>
            <table class="form-table">
                <tr>
                    <th scope="row"><label for="api-key"><?php esc_html_e('Google API Key', 'g-business-reviews-rating'); ?></label></th>
                    <td>
                        <p class="input">
                            <input type="text" id="api-key" class="regular-text code" name="google_business_reviews_rating_api_key" placeholder="<?php echo esc_attr(str_repeat('x', 40)); ?>" value="<?php echo esc_attr(get_option('google_business_reviews_rating_api_key')); ?>">
                        </p>
                        <p class="description<?php echo ((get_option('google_business_reviews_rating_api_key') == NULL) ? ' unset' : ''); ?>"><?php /* translators: 1: URL of Place ID Finder, 2: IP of the web server, 3: Help icon and reveal toggle link */ 
						echo sprintf(__('In order to retrieve Google Business data, you’ll need your own <a href="%1$s" class="components-external-link" target="_blank">API Key</a>, with API: <span class="highlight">Places API</span> and restrict to IP: <span class="highlight">%2$s</span> %3$s', 'g-business-reviews-rating'), 'https://developers.google.com/maps/documentation/javascript/get-api-key', esc_html($this->server_ip()), ' <a id="google-credentials-help" href="#google-credentials-steps"><span class="dashicons dashicons-editor-help"></span></a>'); ?></p>
                        <?php /* translators: 1: a HTML ID, 2: URL of Google Developer Console, 3: URL of Place API, 4: URL of Google Developer Console, 5: IP of web server */
						echo sprintf(__('<ol id="%1$s">
                   			<li>Create a new project or open an existing project in <a href="%2$s" class="components-external-link" target="_blank">Google Developer&rsquo;s Console</a>;</li>
							<li>Optionally, ensure <a href="%3$s" class="components-external-link" target="_blank">Places API</a> is enabled in your account;</li>
                   			<li>Go to: <a href="%4$s" class="components-external-link" target="_blank">Credentials</a>;</li>
                            <li>Click &ldquo;+ Create Credentials&rdquo;;</li>
                            <li>Select &ldquo;API Key&rdquo;;</li>
                            <li>Click &ldquo;Restrict Key&rdquo;;</li>
                            <li>Set &ldquo;Application restrictions&rdquo; to &ldquo;IP addresses&rdquo; and enter: <span class="highlight">%5$s</span> ;</li>
                            <li>Select &ldquo;API restrictions&rdquo; to &ldquo;Restrict key&rdquo; and select the following API request: &ldquo;Place API&rdquo;;</li>
                            <li>Press &ldquo;Save&rdquo;.</li>
                        </ol>', 'g-business-reviews-rating'), 'google-credentials-steps', 'https://console.developers.google.com/apis/credentials', 'https://console.cloud.google.com/apis/library/places-backend.googleapis.com?q=place', 'https://console.developers.google.com/apis/credentials', $this->server_ip()); ?>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="place-id"><?php esc_html_e('Google Place ID', 'g-business-reviews-rating'); ?></label></th>
                    <td>
                        <p class="input">
                            <input type="text" id="place-id" class="regular-text code" name="google_business_reviews_rating_place_id" placeholder="<?php echo esc_attr(str_repeat('x', 26)); ?>" value="<?php echo esc_attr(get_option('google_business_reviews_rating_place_id')); ?>">
                        </p>
                        <p class="description"><?php /* translators: %s: the Google Place Finder URL */ 
						echo sprintf(__('You can find your unique Place ID by searching by your business&rsquo; name in <a href="%s" class="components-external-link" target="_blank">Google&rsquo;s Place ID Finder</a>. Single business locations are accepted; coverage areas are not.', 'g-business-reviews-rating'), 'https://developers.google.com/places/place-id'); ?></p>
                    </td>
                </tr>
<?php if ($this->valid()): ?>
                <tr>
                    <th scope="row"><label for="place-name"><?php esc_html_e('Place Name', 'g-business-reviews-rating'); ?></label></th>
                    <td>
                        <p class="input">
                            <input type="text" id="place-name" class="regular-text" name="place_name" placeholder="<?php echo esc_attr(str_repeat('x', 26)); ?>" value="<?php echo esc_attr($this->data['result']['name']); ?>" disabled="disabled">
                        </p>
<?php if (!$this->demo): ?>
                        <p class="description"><?php /* translators: %s: the URL of the business in Google Maps */ 
						echo sprintf(__('Edit the place name listing in <a href="%s" class="components-external-link" target="_blank">Google Maps</a>.', 'g-business-reviews-rating'), esc_attr($this->data['result']['url'])); ?></p>
<?php endif; ?>
                    </td>
                </tr>
<?php endif; ?>
                <tr>
                    <th scope="row"><label for="language"><?php esc_html_e('Retrieval Language', 'g-business-reviews-rating'); ?></label></th>
                    <td>
                        <select id="language" name="google_business_reviews_rating_language">
                        <option value=""<?php echo (get_option('google_business_reviews_rating_language') == NULL) ? ' selected' : ''; ?>><?php esc_html_e('Select', 'g-business-reviews-rating'); ?></option>
<?php
	foreach ($this->languages as $k => $name)
	{
?>
                            <option value="<?php echo esc_attr($k); ?>"<?php echo (get_option('google_business_reviews_rating_language') == $k) ? ' selected' : ''; ?>><?php echo esc_attr($name); ?></option>
<?php
	}
?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="update"><?php esc_html_e('Update Frequency', 'g-business-reviews-rating'); ?></label></th>
                    <td>
                        <select id="update" name="google_business_reviews_rating_update">
<?php
	foreach ($this->updates as $k => $name)
	{
?>
                            <option value="<?php echo esc_attr($k); ?>"<?php echo (get_option('google_business_reviews_rating_update') == $k) ? ' selected' : ''; ?>><?php echo esc_attr($name); ?></option>
<?php
	}
?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="additional-array-sanitization"><?php esc_html_e('Clean Retrieved Data', 'g-business-reviews-rating'); ?></label></th>
                    <td>
                        <label for="additional-array-sanitization"><input type="checkbox" id="additional-array-sanitization" name="google_business_reviews_rating_additional_array_sanitization" value="1"<?php echo (get_option('google_business_reviews_rating_additional_array_sanitization') ? ' checked="checked"' : ''); ?>> <?php esc_html_e('Additional sanitization of retrieved data — emoticons are removed from text', 'g-business-reviews-rating'); ?></label>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="demo"><?php esc_html_e('Demo Mode', 'g-business-reviews-rating'); ?></label></th>
                    <td>
                        <label for="demo"><input type="checkbox" id="demo" name="google_business_reviews_rating_demo" value="1"<?php echo (get_option('google_business_reviews_rating_demo') ? ' checked="checked"' : ''); ?>> <?php esc_html_e('Enable Demo Mode with dummy data', 'g-business-reviews-rating'); ?></label>
                    </td>
                </tr>
            </table>
			<?php submit_button(); ?>
		</form>
    </div>

    <div id="shortcodes" class="section<?php echo ($this->section != 'shortcodes') ? ' hide' : ''; ?>">
        <form method="post" action="options.php" id="google-business-reviews-rating-shortcodes">
	        <h2><?php esc_html_e('Shortcodes', 'g-business-reviews-rating'); ?></h2>
            <h3><?php esc_html_e('Reviews', 'g-business-reviews-rating'); ?></h3>
            <table class="form-table">
                <tr>
                    <th><?php esc_html_e('Google reviews', 'g-business-reviews-rating'); ?></th>
                    <td><input id="<?php $id = 0; echo 'shortcode-' . $id; $id++; ?>" name="shortcode[]" class="shortcode" type="text" value="[reviews_rating]" readonly></td>
                </tr>
                <tr>
                    <th><?php esc_html_e('Google reviews (IDs)', 'g-business-reviews-rating'); ?></th>
                    <td><input id="<?php echo 'shortcode-' . $id; $id++; ?>" name="shortcode[]" class="shortcode" type="text" value="[reviews_rating id=&quot;1,5,3&quot;]" readonly></td>
                </tr>
                <tr>
                    <th rowspan="4"><?php esc_html_e('Google reviews (options)', 'g-business-reviews-rating'); ?></th>
                    <td><input id="<?php echo 'shortcode-' . $id; $id++; ?>" name="shortcode[]" class="shortcode" type="text" value="[reviews_rating theme=&quot;dark&quot; min=3 max=5 offset=0 limit=3 summary=&quot;yes&quot; icon=&quot;no&quot; excerpt=160 more=&quot;read more&quot;]" readonly></td>
                </tr>
                <tr>
                    <td><input id="<?php echo 'shortcode-' . $id; $id++; ?>" name="shortcode[]" class="shortcode" type="text" value="[reviews_rating theme=&quot;columns three fonts&quot; vicinity=&quot;E4, London&quot; reviews_link=&quot;View Google Reviews&quot; write_review_link=&quot;Leave A Review&quot;]" readonly></td>
                </tr>
                <tr>
                    <td><input id="<?php echo 'shortcode-' . $id; $id++; ?>" name="shortcode[]" class="shortcode" type="text" value="[reviews_rating icon=&quot;/wp-content/uploads/logo.png&quot; avatar=&quot;false&quot; review_item_order=&quot;text first&quot; review_text_min=200]" readonly></td>
                </tr>
                <tr>
                    <td><input id="<?php echo 'shortcode-' . $id; $id++; ?>" name="shortcode[]" class="shortcode" type="text" value="[reviews_rating place_id=&quot;xxxxxxxxxxxxxxxxxxxxxxxxxxxxxx&quot; animate=&quot;no&quot; review_text=&quot;no&quot; attribution=&quot;yes&quot;]" readonly></td>
                </tr>
            </table>
            <h3><?php esc_html_e('Links', 'g-business-reviews-rating'); ?></h3>
            <table class="form-table">
                <tr>
                    <th><?php esc_html_e('Google reviews link', 'g-business-reviews-rating'); ?></th>
                    <td><input id="<?php echo 'shortcode-' . $id; $id++; ?>" name="shortcode[]" class="shortcode" type="text" value="<?php echo esc_attr('[reviews_rating_link reviews_link]' . __('Our Reviews on Google', 'g-business-reviews-rating') . '[/reviews_rating_link]'); ?>" readonly></td>
                </tr>
                <tr>
                    <th rowspan="3"><?php esc_html_e('Google reviews link (options)', 'g-business-reviews-rating'); ?></th>
                    <td><input id="<?php echo 'shortcode-' . $id; $id++; ?>" name="shortcode[]" class="shortcode" type="text" value="<?php echo esc_attr('[reviews_rating_link reviews_link class="button" target="_blank"]' . __('Our Reviews on Google', 'g-business-reviews-rating') . '[/reviews_rating_link]'); ?>" readonly></td>
                </tr>
                <tr>
                    <td><input id="<?php echo 'shortcode-' . $id; $id++; ?>" name="shortcode[]" class="shortcode" type="text" value="<?php echo esc_attr('[reviews_rating_link reviews_link]&lt;span class=&quot;google-icon&quot;&gt;&lt;/span&gt; ' . __('Our Reviews on Google', 'g-business-reviews-rating') . '[/reviews_rating_link]'); ?>" readonly></td>
                </tr>
                <tr>
                    <td><input id="<?php echo 'shortcode-' . $id; $id++; ?>" name="shortcode[]" class="shortcode" type="text" value="<?php echo esc_attr('[reviews_rating_link reviews_link]' . __('Our Reviews on Google', 'g-business-reviews-rating') . ' &lt;span class=&quot;google-icon black end&quot;&gt;&lt;/span&gt;[/reviews_rating_link]'); ?>" readonly></td>
                </tr>
                <tr>
                    <th><?php esc_html_e('Write a Google review link', 'g-business-reviews-rating'); ?></th>
                    <td><input id="<?php echo 'shortcode-' . $id; $id++; ?>" name="shortcode[]" class="shortcode" type="text" value="<?php echo esc_attr('[reviews_rating_link write_review_link]' . __('Leave Your Review on Google', 'g-business-reviews-rating') . '[/reviews_rating_link]'); ?>" readonly></td>
                </tr>
                <tr>
                    <th><?php esc_html_e('Google Maps link', 'g-business-reviews-rating'); ?></th>
                    <td><input id="<?php echo 'shortcode-' . $id; $id++; ?>" name="shortcode[]" class="shortcode" type="text" value="<?php echo esc_attr('[reviews_rating_link maps_link]' . __('View Location on Google Maps', 'g-business-reviews-rating') . '[/reviews_rating_link]'); ?>" readonly></td>
                </tr>
            </table>
            <h3><?php esc_html_e('Text', 'g-business-reviews-rating'); ?></h3>
            <table class="form-table">
                <tr>
                    <th><?php esc_html_e('Google rating', 'g-business-reviews-rating'); ?></th>
                    <td><input id="<?php echo 'shortcode-' . $id; $id++; ?>" name="shortcode[]" class="shortcode" type="text" value="[reviews_rating rating]" readonly></td>
                </tr>
                <tr>
                    <th><?php esc_html_e('Google review count', 'g-business-reviews-rating'); ?></th>
                    <td><input id="<?php echo 'shortcode-' . $id; $id++; ?>" name="shortcode[]" class="shortcode" type="text" value="[reviews_rating review_count]" readonly></td>
                </tr>
                <tr>
                    <th><?php esc_html_e('Google reviews URL', 'g-business-reviews-rating'); ?></th>
                    <td><input id="<?php echo 'shortcode-' . $id; $id++; ?>" name="shortcode[]" class="shortcode" type="text" value="[reviews_rating reviews_url]" readonly></td>
                </tr>
                <tr>
                    <th><?php esc_html_e('Write a Google review URL', 'g-business-reviews-rating'); ?></th>
                    <td><input id="<?php echo 'shortcode-' . $id; $id++; ?>" name="shortcode[]" class="shortcode" type="text" value="[reviews_rating write_review_url]" readonly></td>
                </tr>
                <tr>
                    <th><?php esc_html_e('Google Maps URL', 'g-business-reviews-rating'); ?></th>
                    <td><input id="<?php echo 'shortcode-' . $id; $id++; ?>" name="shortcode[]" class="shortcode" type="text" value="[reviews_rating maps_url]" readonly></td>
                </tr>
            </table>
            
            <h2><?php esc_html_e('Parameters', 'g-business-reviews-rating'); ?></h2>
            <p><?php _e('There are quite a wide range of parameters that are accepted, so a guide will help cover all the possibilities to customize the output of your reviews, links and text. Shortcode parameters will override the values in the Setup. All parameters are optional.', 'g-business-reviews-rating'); ?></p>
            <table class="wp-list-table widefat fixed striped parameters">
                <tr>
                    <th class="parameter"><?php esc_html_e('Parameter', 'g-business-reviews-rating'); ?></th>
                    <th class="description"><?php esc_html_e('Description', 'g-business-reviews-rating'); ?></th>
                    <th class="accepted"><?php esc_html_e('Accepted Values', 'g-business-reviews-rating'); ?></th>
                    <th class="default"><?php esc_html_e('Default', 'g-business-reviews-rating'); ?></th>
                    <th class="boolean"><?php esc_html_e('Reviews', 'g-business-reviews-rating'); ?></th>
                    <th class="boolean"><?php esc_html_e('Links', 'g-business-reviews-rating'); ?></th>
                </tr>
                <tr id="parameter-limit">
                    <td class="parameter">limit</td>
                    <td class="description">Simply sets the number of reviews you would like listed. Do not set or leave this empty (<span class="code">NULL</span>) = all reviews and <span class="code">0</span> = hide reviews.</td>
                    <td class="accepted"><span class="code">NULL</span>, <span class="code">0</span>, <span class="code">1</span>, <span class="code">2</span>, &hellip;</td>
                    <td class="default"><span class="code">NULL</span></td>
                    <td class="boolean"><span class="dashicons dashicons-yes"></span></td>
                    <td class="boolean"><span class="dashicons dashicons-no"></span></td>
                </tr>
                <tr id="parameter-sort">
                    <td class="parameter">sort</td>
                    <td class="description">Your preference for sorting when 2 or more reviews are displayed. Leave empty (<span class="code">NULL</span>) to sort by Google&rsquo;s relevance.</td>
                    <td class="accepted"><span class="code">NULL</span>, <?php $review_sort_options = array_keys($this->review_sort_options); array_shift($review_sort_options); echo esc_html(implode(', ', $review_sort_options)); ?></td>
                    <td class="default"><span class="code">NULL</span></td>
                    <td class="boolean"><span class="dashicons dashicons-yes"></span></td>
                    <td class="boolean"><span class="dashicons dashicons-no"></span></td>
                </tr>
                <tr id="parameter-offset">
                    <td class="parameter">offset</td>
                    <td class="description">When the limit is 1 or more, you can offset the review  results to &ldquo;jump&rdquo; forward.</td>
                    <td class="accepted"><span class="code">0</span>, <span class="code">1</span>, <span class="code">2</span>, &hellip;</td>
                    <td class="default"><span class="code">0</span></td>
                    <td class="boolean"><span class="dashicons dashicons-yes"></span></td>
                    <td class="boolean"><span class="dashicons dashicons-no"></span></td>
                </tr>
                <tr id="parameter-id">
                    <td class="parameter">id</td>
                    <td class="description">Select one or more reviews by their ID (see Reviews). Ordering is maintained when a comma separated listed is set; enclose in quotes for two or more IDs.</td>
                    <td class="accepted"><span class="code">1</span>, <span class="code">2</span>, <span class="code">3</span>, &hellip; or &quot;1, 5, 6, 2&quot;</td>
                    <td class="default"><span class="code">NULL</span></td>
                    <td class="boolean"><span class="dashicons dashicons-yes"></span></td>
                    <td class="boolean"><span class="dashicons dashicons-no"></span></td>
                </tr>
                <tr id="parameter-place-id">
                    <td class="parameter">place_id</td>
                    <td class="description">If you have more than one Place in the retrieved reviews, you can filter by the Place ID. Only the active Place will receive new reviews and data.</td>
                    <td class="accepted"><em>String</em></td>
                    <td class="default"><span class="code">NULL</span></td>
                    <td class="boolean"><span class="dashicons dashicons-yes"></span></td>
                    <td class="boolean"><span class="dashicons dashicons-no"></span></td>
                </tr>
                <tr id="parameter-language">
                    <td class="parameter">language</td>
                    <td class="description">Filter results based on the language using the two letter language code. Empty reviews have no language  will always appear.</td>
                    <td class="accepted">en, fr, de, &hellip;</td>
                    <td class="default"><span class="code">NULL</span></td>
                    <td class="boolean"><span class="dashicons dashicons-yes"></span></td>
                    <td class="boolean"><span class="dashicons dashicons-no"></span></td>
                </tr>
                <tr id="parameter-theme">
                    <td class="parameter">theme</td>
                    <td class="description">Set a theme based on your overall visual requirements. You may use the full text name (not recommended as these are subject to change) or the actual value listed here. These values match with the class attribute set to the main HTML element.</td>
                    <td class="accepted"><span class="code">NULL</span>, <?php echo esc_html('"' . implode('", "', array_keys($this->reviews_themes)) . '"'); ?></td>
                    <td class="default">light</td>
                    <td class="boolean"><span class="dashicons dashicons-yes"></span></td>
                    <td class="boolean"><span class="dashicons dashicons-no"></span></td>
                </tr>
                <tr id="parameter-min">
                    <td class="parameter">min</td>
                    <td class="description">Set to filter out any ratings that fall below this minimum value.</td>
                    <td class="accepted"><span class="code">1</span>, <span class="code">2</span>, <span class="code">3</span>, <span class="code">4</span>, <span class="code">5</span></td>
                    <td class="default"><span class="code">NULL</span></td>
                    <td class="boolean"><span class="dashicons dashicons-yes"></span></td>
                    <td class="boolean"><span class="dashicons dashicons-no"></span></td>
                </tr>
                <tr id="parameter-max">
                    <td class="parameter">max</td>
                    <td class="description">Set to filter out any ratings that lie above this maximum value.</td>
                    <td class="accepted"><span class="code">1</span>, <span class="code">2</span>, <span class="code">3</span>, <span class="code">4</span>, <span class="code">5</span></td>
                    <td class="default"><span class="code">NULL</span></td>
                    <td class="boolean"><span class="dashicons dashicons-yes"></span></td>
                    <td class="boolean"><span class="dashicons dashicons-no"></span></td>
                </tr>
                <tr id="parameter-summary">
                    <td class="parameter">summary</td>
                    <td class="description">Show or hide the summary section &mdash; containing the icon, business name and vicinity.</td>
                    <td class="accepted">yes, no, true, false, <span class="code">1</span>, <span class="code">0</span>, show, hide, on, off</td>
                    <td class="default"><span class="code">TRUE</span></td>
                    <td class="boolean"><span class="dashicons dashicons-yes"></span></td>
                    <td class="boolean"><span class="dashicons dashicons-no"></span></td>
                </tr>
                <tr id="parameter-icon">
                    <td class="parameter">icon</td>
                    <td class="description">Show or hide business icon &mdash; or specify your own image replacement (jpg, jpeg, png, gif, svg extensions are supported).</td>
                    <td class="accepted">yes, no, true, false, <span class="code">1</span>, <span class="code">0</span>, show, hide, on, off, <em>/url/to/image.jpg</em></td>
                    <td class="default"><span class="code">TRUE</span></td>
                    <td class="boolean"><span class="dashicons dashicons-yes"></span></td>
                    <td class="boolean"><span class="dashicons dashicons-no"></span></td>
                </tr>
                <tr id="parameter-name">
                    <td class="parameter">name</td>
                    <td class="description">Show or hide business name &mdash; or specify your own choice of business name.</td>
                    <td class="accepted">yes, no, true, false, <span class="code">1</span>, <span class="code">0</span>, show, hide, on, off, <em>Any string</em></td>
                    <td class="default"><span class="code">TRUE</span></td>
                    <td class="boolean"><span class="dashicons dashicons-yes"></span></td>
                    <td class="boolean"><span class="dashicons dashicons-no"></span></td>
                </tr>
                <tr id="parameter-vicinity">
                    <td class="parameter">vicinity</td>
                    <td class="description">Show or hide business vicinity according to Google &mdash; or specify your own text replacement.</td>
                    <td class="accepted">yes, no, true, false, <span class="code">1</span>, <span class="code">0</span>, show, hide, on, off, <em>Any string</em></td>
                    <td class="default"><span class="code">TRUE</span></td>
                    <td class="boolean"><span class="dashicons dashicons-yes"></span></td>
                    <td class="boolean"><span class="dashicons dashicons-no"></span></td>
                </tr>
                <tr id="parameter-stars">
                    <td class="parameter">stars</td>
                    <td class="description">Show or hide stars for the overall rating, with an option to override default orange color for stars in the overall rating. Set as <em>css</em> to mirror the style sheet rule <em>color</em>.</td>
                    <td class="accepted">svg, html, css, yes, no, true, false, show, hide, on, off, <em>Any valid color string</em></td>
                    <td class="default">svg</td>
                    <td class="boolean"><span class="dashicons dashicons-yes"></span></td>
                    <td class="boolean"><span class="dashicons dashicons-no"></span></td>
                </tr>
                <tr id="parameter-stars-gray">
                    <td class="parameter">stars_gray</td>
                    <td class="description">Optionally override the default gray color for stars in the overall rating. Set as <em>css</em> to mirror the style sheet rule <em>color</em>.</td>
                    <td class="accepted"><span class="code">NULL</span>, css, <em>Any valid color string</em></td>
                    <td class="default"><span class="code">NULL</span></td>
                    <td class="boolean"><span class="dashicons dashicons-yes"></span></td>
                    <td class="boolean"><span class="dashicons dashicons-no"></span></td>
                </tr>
                <tr id="parameter-count">
                    <td class="parameter">count</td>
                    <td class="description">Show or hide total review/rating count.</td>
                    <td class="accepted">yes, no, true, false, show, hide, on, off</td>
                    <td class="default"><span class="code">TRUE</span></td>
                    <td class="boolean"><span class="dashicons dashicons-yes"></span></td>
                    <td class="boolean"><span class="dashicons dashicons-no"></span></td>
                </tr>
                <tr id="parameter-review-word">
                    <td class="parameter">review_word</td>
                    <td class="description">Word displayed after the total review/rating count number in the summary. For completeness, you may separate singular and plural with , or / characters.</td>
                    <td class="accepted"><em>Any valid string</em></td> 
                    <td class="default"><?php esc_html_e('review', 'g-business-reviews-rating'); ?>/<?php esc_html_e('reviews', 'g-business-reviews-rating'); ?></td>
                    <td class="boolean"><span class="dashicons dashicons-yes"></span></td>
                    <td class="boolean"><span class="dashicons dashicons-no"></span></td>
                </tr>
                <tr id="parameter-animate">
                    <td class="parameter">animate</td>
                    <td class="description">Animate the rating stars on load or set as static (<span class="code">FALSE</span>).</td>
                    <td class="accepted">yes, no, true, false, <span class="code">1</span>, <span class="code">0</span>, show, hide, on, off, animate, static</td>
                    <td class="default"><span class="code">TRUE</span></td>
                    <td class="boolean"><span class="dashicons dashicons-yes"></span></td>
                    <td class="boolean"><span class="dashicons dashicons-no"></span></td>
                </tr>
                <tr id="parameter-review-text">
                    <td class="parameter">review_text</td>
                    <td class="description">Show or hide all review text leaving just the names, ratings and relative times.</td>
                    <td class="accepted">yes, no, true, false, <span class="code">1</span>, <span class="code">0</span>, show, hide, on, off</td>
                    <td class="default"><span class="code">TRUE</span></td>
                    <td class="boolean"><span class="dashicons dashicons-yes"></span></td>
                    <td class="boolean"><span class="dashicons dashicons-no"></span></td>
                </tr>
                <tr id="parameter-review-text-min">
                    <td class="parameter">review_text_min</td>
                    <td class="description">Filter by a minimum review text character count. Empty (<span class="code">NULL</span>) or <span class="code">0</span> = no minimum. </td>
                    <td class="accepted"><span class="code">NULL</span>, <span class="code">0</span>, <span class="code">1</span>, <span class="code">2</span>, &hellip;</td>
                    <td class="default"><span class="code">NULL</span></td>
                    <td class="boolean"><span class="dashicons dashicons-yes"></span></td>
                    <td class="boolean"><span class="dashicons dashicons-no"></span></td>
                </tr>
                <tr id="parameter-review-text-max">
                    <td class="parameter">review_text_max</td>
                    <td class="description">Filter by a maximum review text character count. Empty (<span class="code">NULL</span>) = no maximum. <span class="code">0</span> = no review text. </td>
                    <td class="accepted"><span class="code">NULL</span>, <span class="code">0</span>, <span class="code">1</span>, <span class="code">2</span>, &hellip;</td>
                    <td class="default"><span class="code">NULL</span></td>
                    <td class="boolean"><span class="dashicons dashicons-yes"></span></td>
                    <td class="boolean"><span class="dashicons dashicons-no"></span></td>
                </tr>
                <tr id="parameter-review-text-inc">
                    <td class="parameter">review_text_inc</td>
                    <td class="description">Require a specific word or words in review text. Case insensitive; full word match. Multiple required words as a comma separated list.</td>
                    <td class="accepted">excellent or &quot;good, superb, great, &hellip;&quot;, <em>Any string</em></td>
                    <td class="default"><span class="code">NULL</span></td>
                    <td class="boolean"><span class="dashicons dashicons-yes"></span></td>
                    <td class="boolean"><span class="dashicons dashicons-no"></span></td>
                </tr>
                <tr id="parameter-review-text-exc">
                    <td class="parameter">review_text_exc</td>
                    <td class="description">Filter out reviews containing a specific word or words. Case insensitive; full word match. Multiple required words as a comma separated list.</td>
                    <td class="accepted">poor or &quot;average, bad, avoid, &hellip;&quot;, <em>Any string</em></td>
                    <td class="default"><span class="code">NULL</span></td>
                    <td class="boolean"><span class="dashicons dashicons-yes"></span></td>
                    <td class="boolean"><span class="dashicons dashicons-no"></span></td>
                </tr>
                <tr id="parameter-excerpt">
                    <td class="parameter">excerpt</td>
                    <td class="description">Characters in review text to show before a &ldquo;more&rdquo; toggle link is shown to expand the remainder of the review. Empty (<span class="code">NULL</span>) = no excerpt; show all review text.</td>
                    <td class="accepted"><span class="code">NULL</span>, <span class="code">20</span>, <span class="code">21</span>, <span class="code">22</span>, &hellip;</td>
                    <td class="default"><span class="code">235</span></td>
                    <td class="boolean"><span class="dashicons dashicons-yes"></span></td>
                    <td class="boolean"><span class="dashicons dashicons-no"></span></td>
                </tr>
                <tr id="parameter-more">
                    <td class="parameter">more</td>
                    <td class="description">Text to use in the &ldquo;more&rdquo; toggle link.</td>
                    <td class="accepted"><em>Any string</em></td> 
                    <td class="default"><?php esc_html_e('More', 'g-business-reviews-rating'); ?></td>
                    <td class="boolean"><span class="dashicons dashicons-yes"></span></td>
                    <td class="boolean"><span class="dashicons dashicons-no"></span></td>
                </tr>
                <tr id="parameter-date">
                    <td class="parameter">date</td>
                    <td class="description">Choose how to display the review dates, either using the <a href="https://www.php.net/date" class="components-external-link" target="_blank">PHP date</a>, relative text or to hide entirely. Actual review date may be an approximation if imported.</td>
                    <td class="accepted">relative, no, false, <span class="code">0</span>, hide, off, <em>Any valid string</em></td>
                    <td class="default">relative</td>
                    <td class="boolean"><span class="dashicons dashicons-yes"></span></td>
                    <td class="boolean"><span class="dashicons dashicons-no"></span></td>
                </tr>
                <tr id="parameter-link">
                    <td class="parameter">link</td>
                    <td class="description">Set the entire element as a link to all reviews listed externally at Google (only if no reviews listed). Recommended with Badge theme.</td>
                    <td class="accepted">reviews, &quot;write review&quot;, yes, no, true, false, <span class="code">1</span>, <span class="code">0</span>, <em>URL string</em></td>
                    <td class="default"><span class="code">FALSE</span></td>
                    <td class="boolean"><span class="dashicons dashicons-yes"></span></td>
                    <td class="boolean"><span class="dashicons dashicons-no"></span></td>
                </tr>
                <tr id="parameter-reviews-link">
                    <td class="parameter">reviews_link</td>
                    <td class="description">Show a link/button for all reviews listed externally at Google. Specify custom text string to appear as the link text. This is hidden by default.</td>
                    <td class="accepted">yes, no, true, false, <span class="code">1</span>, <span class="code">0</span>, show, hide, on, off, <em>Any string</em></td>
                    <td class="default"><span class="code">FALSE</span></td>
                    <td class="boolean"><span class="dashicons dashicons-yes"></span></td>
                    <td class="boolean"><span class="dashicons dashicons-no"></span></td>
                </tr>
                <tr id="parameter-write-review-link">
                    <td class="parameter">write_review_link</td>
                    <td class="description">Show a link/button to  allow a visitor to leave a review at Google. Specify custom text string to appear as the link text. This is hidden by default.</td>
                    <td class="accepted">yes, no, true, false, <span class="code">1</span>, <span class="code">0</span>, show, hide, on, off, <em>Any string</em></td>
                    <td class="default"><span class="code">FALSE</span></td>
                    <td class="boolean"><span class="dashicons dashicons-yes"></span></td>
                    <td class="boolean"><span class="dashicons dashicons-no"></span></td>
                </tr>
                <tr id="parameter-avatar">
                    <td class="parameter">avatar</td>
                    <td class="description">Show or hide users&rsquo; avatars &mdash; or specify your own [single] image replacement (jpg, jpeg, png, gif, svg extensions are supported).</td>
                    <td class="accepted">yes, no, true, false, <span class="code">1</span>, <span class="code">0</span>, show, hide, on, off, <em>/url/to/image.jpg</em></td>
                    <td class="default"><span class="code">TRUE</span></td>
                    <td class="boolean"><span class="dashicons dashicons-yes"></span></td>
                    <td class="boolean"><span class="dashicons dashicons-no"></span></td>
                </tr>
                <tr id="parameter-name-format">
                    <td class="parameter">name_format</td>
                    <td class="description">Control the format of reviewers&rsquo; names such as (e.g. initials or last name initials).</td>
                    <td class="accepted"><span class="code">NULL</span>, &quot;full name&quot;, &quot;intials&quot;, &quot;first initial&quot;, &quot;last initial&quot;, &quot;intials with dots&quot;, &quot;first initial with dot&quot;, &quot;last initial with dot&quot;, &quot;initials with dots and space&quot;,  &quot;first initial with dot and space&quot;, &quot;last initial with dot and space&quot;</td>
                    <td class="default">&quot;full name&quot;</td>
                    <td class="boolean"><span class="dashicons dashicons-yes"></span></td>
                    <td class="boolean"><span class="dashicons dashicons-no"></span></td>
                </tr>
                <tr id="parameter-review-item-order">
                    <td class="parameter">review_item_order</td>
                    <td class="description">Change the ordering of each review item  &mdash; review text can be set to appear at the top of each entry. Add <em>inline</em> to set the author name, stars and date inline. This may be extended if there is a <a href="https://designextreme.com/wordpress/" class="components-external-link" target="_blank">demand</a>.</td>
                    <td class="accepted"><span class="code">NULL</span>, &quot;text first&quot;, &quot;text last&quot;, inline, &quot;text first inline&quot;, &quot;text last inline&quot;</td>
                    <td class="default">&quot;text last&quot;</td>
                    <td class="boolean"><span class="dashicons dashicons-yes"></span></td>
                    <td class="boolean"><span class="dashicons dashicons-no"></span></td>
                </tr>
                <tr id="parameter-attribution">
                    <td class="parameter">attribution</td>
                    <td class="description">Show or hide the &ldquo;powered by Google&rdquo; attribution.</td>
                    <td class="accepted">yes, no, true, false, <span class="code">1</span>, <span class="code">0</span>, show, hide, on, off, light, dark</td>
                    <td class="default"><span class="code">TRUE</span></td>
                    <td class="boolean"><span class="dashicons dashicons-yes"></span></td>
                    <td class="boolean"><span class="dashicons dashicons-no"></span></td>
                </tr>
                <tr id="parameter-html-tags">
                    <td class="parameter">html_tags</td>
                    <td class="description">Set your own HTML tags for elements such as replacing <span class="code">&lt;h2&gt;</span> with <span class="code">&lt;h3&gt;</span>. Any sequence length accepted; separated by commas for: heading, vicinity, rating, list, list item, buttons, attribution and errors.</td>
                    <td class="accepted">h3 or &quot;h4, div, div, <em>&hellip;&quot;</em></td>
                    <td class="accepted"><?php echo esc_html('"' . implode(', ', $this->default_html_tags) . '"'); ?></td>
                    <td class="boolean"><span class="dashicons dashicons-yes"></span></td>
                    <td class="boolean"><span class="dashicons dashicons-no"></span></td>
                </tr>
                <tr id="parameter-class">
                    <td class="parameter">class</td>
                    <td class="description">Set the class attribute for main HTML element or the single anchor link. Use <em>js-links</em> for an alternative <em><?php esc_html_e('More', 'g-business-reviews-rating'); ?></em> toggle event. Separated by spaces; not commas.</td>
                    <td class="accepted"><em>Any valid string</em></td>
                    <td class="default"><span class="code">NULL</span></td>
                    <td class="boolean"><span class="dashicons dashicons-yes"></span></td>
                    <td class="boolean"><span class="dashicons dashicons-yes"></span></td>
                </tr>
                <tr id="parameter-link-class">
                    <td class="parameter">link_class</td>
                    <td class="description">Specifically set the class attribute for a link or links. Separated by spaces; not commas.</td>
                    <td class="accepted"><em>Any valid string</em></td>
                    <td class="default"><span class="code">NULL</span></td>
                    <td class="boolean"><span class="dashicons dashicons-yes"></span></td>
                    <td class="boolean"><span class="dashicons dashicons-yes"></span></td>
                </tr>
                <tr id="parameter-reviews-link-class">
                    <td class="parameter">reviews_link_class</td>
                    <td class="description">Set the class attribute for the Google reviews link only. Separated by spaces; not commas.</td>
                    <td class="accepted"><em>Any valid string</em></td>
                    <td class="default"><span class="code">NULL</span></td>
                    <td class="boolean"><span class="dashicons dashicons-yes"></span></td>
                    <td class="boolean"><span class="dashicons dashicons-no"></span></td>
                </tr>
                <tr id="parameter-write-review-link-class">
                    <td class="parameter">write_review_link_class</td>
                    <td class="description">Set the class attribute for the Write a Google review link only. Separated by spaces; not commas.</td>
                    <td class="accepted"><em>Any valid string</em></td>
                    <td class="default"><span class="code">NULL</span></td>
                    <td class="boolean"><span class="dashicons dashicons-yes"></span></td>
                    <td class="boolean"><span class="dashicons dashicons-no"></span></td>
                </tr>
                <tr id="parameter-target">
                    <td class="parameter">target</td>
                    <td class="description">Set the anchor link&rsquo;s <a href="https://www.w3schools.com/tags/att_a_target.asp" class="components-external-link" target="_blank">target</a>. Empty (<span class="code">NULL</span>) to remove attribute.</td>
                    <td class="accepted"><span class="code">NULL</span>, <em>Any valid string</em></td>
                    <td class="default">_blank</td>
                    <td class="boolean"><span class="dashicons dashicons-yes"></span></td>
                    <td class="boolean"><span class="dashicons dashicons-yes"></span></td>
                </tr>
                <tr id="parameter-stylesheet">
                    <td class="parameter">stylesheet</td>
                    <td class="description">Choose to not load the style sheet that makes your rating and reviews look good. <em>Not recommended as a parameter.</em></td>
                    <td class="accepted">yes, no, true, false, <span class="code">1</span>, <span class="code">0</span>, show, hide, on, off</td>
                    <td class="default"><span class="code">TRUE</span></td>
                    <td class="boolean"><span class="dashicons dashicons-yes"></span></td>
                    <td class="boolean"><span class="dashicons dashicons-no"></span></td>
                </tr>
                <tr id="parameter-multiplier">
                    <td class="parameter">multiplier</td>
                    <td class="description">If the stars aren&rsquo;t aligning in the overall rating, you can modify this value to adjust the width. Only applicable when used with stars parameter: <em>html</em> or class parameter: <em>version-1</em>.</td>
                    <td class="accepted"><em>Positive float number:</em> <span class="code">0.001</span> &ndash; <span class="code">10</span></td>
                    <td class="default"><span class="code">0.193</span></td>
                    <td class="boolean"><span class="dashicons dashicons-yes"></span></td>
                    <td class="boolean"><span class="dashicons dashicons-no"></span></td>
                </tr>
                <tr id="parameter-errors">
                    <td class="parameter">errors</td>
                    <td class="description">You can choose to hide error notices caused by lack of reviews, filtering that leads to no reviews or lack of source data. Defaults to <span class="code">WP_DEBUG</span> if defined in <em>wp-config.php</em>.</td>
                    <td class="accepted">yes, no, true, false, <span class="code">1</span>, <span class="code">0</span>, show, hide, on, off</td>
                    <td class="default"><span class="code">FALSE</span></td>
                    <td class="boolean"><span class="dashicons dashicons-yes"></span></td>
                    <td class="boolean"><span class="dashicons dashicons-yes"></span></td>
                </tr>
            </table>
        </form>
    </div>

<?php if ($this->count_reviews_all >= 1): ?>
	<div id="reviews" class="section<?php echo ($this->section != 'reviews') ? ' hide' : ''; ?>">
		<h2><?php esc_html_e('Reviews', 'g-business-reviews-rating'); ?></h2>
		<p class="rating"><span class="rating-field"><?php esc_html_e('Rating:', 'g-business-reviews-rating'); ?></span> <span class="number"><?php echo esc_html(number_format($this->data['result']['rating'], 1)); ?></span> <span class="all-stars"><?php echo esc_html(str_repeat('★', 5)); ?><span class="rating-stars" style="<?php echo esc_attr('width: ' . round(0.835 * $this->data['result']['rating'], 2) . 'em;'); ?>"><?php echo esc_html(str_repeat('★', ceil($this->data['result']['rating']))); ?></span></span> <span class="count"><?php echo esc_html($this->data['result']['user_ratings_total'] . ' ' . (($this->data['result']['user_ratings_total'] == 1) ? __('review', 'g-business-reviews-rating') : __('reviews', 'g-business-reviews-rating'))) . ($this->data['result']['user_ratings_total'] > $this->count_reviews_all ? '*' : ''); ?></span></p>
<?php echo $this->get_reviews('html'); ?>
<?php if ($this->demo || $this->data['result']['user_ratings_total'] > $this->count_reviews_all): ?>
		<p class="note help">* <?php _e('Please note: the total number of reviews listed at Google will not always match the number of reviews that are retrievable through its API.', 'g-business-reviews-rating'); ?></p>
<?php endif; ?>
	</div>
<?php endif; ?>
<?php if ($this->retrieved_data_check()): ?>
	<div id="data" class="section<?php echo ($this->section != 'data') ? ' hide' : ''; ?>">
		<h2><?php esc_html_e('Retrieved Data', 'g-business-reviews-rating'); ?></h2>
<?php echo $this->get_data('html'); ?>
<?php if (!$this->retrieved_data_check(TRUE)) : ?>
		<h2><?php esc_html_e('Most Recent Valid Retrieved Data', 'g-business-reviews-rating'); ?></h2>
        <p><?php /* translators: 1: URL of reviews on Google, 2: URL of Place Finder */ 
		echo sprintf(__('This is the last successfully retrieved data from Google and will be used in the website. While your current reviews may still be visible on <a href="%1$s" class="components-external-link" target="_blank">Google</a>, they are no longer being retrieved.
		Please check and update your <a href="%2$s" class="components-external-link" target="_blank">Place ID</a> if you wish to regain full functionality.', 'g-business-reviews-rating'), 'https://search.google.com/local/reviews?placeid=' . esc_attr($this->place_id), 'https://developers.google.com/places/place-id'); ?></p>
<?php echo $this->get_data('html', NULL, TRUE); ?>
<?php endif; ?>

	</div>
<?php endif; ?>

	<div id="advanced" class="section<?php echo ($this->section != 'advanced') ? ' hide' : ''; ?>">
		<h2><?php esc_html_e('Advanced', 'g-business-reviews-rating'); ?></h2>
		<h3><?php esc_html_e('Import', 'g-business-reviews-rating'); ?></h3>
<?php if (!$this->demo && $this->place_id != NULL && $this->count_reviews_all >= 5): ?>
        <div class="introduction">
<?php /* translators: 1: URL of reviews on Google, 2: URL of diagram image, 3: URL of diagram image, 4: URL of diagram image */ 
	echo sprintf(__('
            <div class="entry-content advanced">
                <p>Okay, this bit is little advanced, if you can use the browser&rsquo;s inspector, you can load <em>all</em> the Google Reviews into your website with approximate dates.</p>
                <ol>
                  <li>Go to your <a href="%1$s" target="_blank">Google Reviews</a>;</li>
                  <li>Wait until it loads; expand all reviews by scrolling down;</li>
                  <li><em>Inspect</em> the overall popup &mdash; on the outer white margin: <span class="right-click">right click</span> | Inspect (Fig. 1, 2); </li>
                  <li>In the HTML Inspector panel, <span class="right-click">right click</span> on the &lt;div&gt; that highlights all the reviews and <em>Copy |</em> Outer HTML (Fig. 3); </li>
                  <li>Paste this HTML into the <label for="html-import">textarea below</label>:</li>
                </ol>
            </div>
            <div class="entry-meta advanced">
                <ul id="html-import-figures">
                    <li id="html-import-figure-1"><img src="%2$s" alt="Fig. 1: Import Step 3, Part 1"><span class="caption"><strong>Fig 1:</strong> In the margin, <span class="right-click">right click</span>.</span></li>
                    <li id="html-import-figure-2"><img src="%3$s" alt="Fig. 2: Import Step 3, Part 2"><span class="caption"><strong>Fig 2:</strong> Select <em>Inspect</em>.</span></li>
                    <li id="html-import-figure-3"><img src="%4$s" alt="Fig. 3: Import Step 4"><span class="caption"><strong>Fig 3:</strong> <span class="right-click">Right click</span> on the highlighted &lt;div&gt; tag and click <em>Copy | Outer HTML</em>.</span></li>
                </ul>
            </div>', 'g-business-reviews-rating'),
			esc_attr('https://search.google.com/local/reviews?placeid=' . $this->place_id),
			esc_attr(plugin_dir_url(__FILE__) . 'images/advanced-html-import-step-3a.jpg'),
			esc_attr(plugin_dir_url(__FILE__) . 'images/advanced-html-import-step-3b.jpg'),
			esc_attr(plugin_dir_url(__FILE__) . 'images/advanced-html-import-step-4.jpg')); ?>
        </div>
        <p class="html-import">
            <textarea id="html-import" name="html-import" data-relative-times="<?php echo esc_attr(json_encode($this->relative_times)); ?>" placeholder="<?php echo '&lt;div class=&quot;lcorif fp-w&quot;&gt;&lt;div&gt;' . esc_attr(__('HTML from your Reviews on Google', 'g-business-reviews-rating')) . '&lt;/div&gt;&lt;/div&gt;'; ?>"></textarea>
            <select id="html-import-review-text" name="html-import-review-text">
            	<option value="original" selected><?php echo esc_html__('Only import original', 'g-business-reviews-rating'); ?></option>
            	<option value="translation"><?php echo esc_html__('Only import translation', 'g-business-reviews-rating'); ?></option>
            	<option value=""><?php echo esc_html__('Import full review text', 'g-business-reviews-rating'); ?></option>
            </select>
            <label id="html-import-existing-label" for="html-import-existing"><input type="checkbox" id="html-import-existing" name="html-import-existing" value="1"> <?php esc_html_e('Show existing review entries', 'g-business-reviews-rating') ?></label>
        </p>
        <p class="submit">
        	<button type="button" name="import-process" id="import-process-button" class="button button-primary"><?php echo esc_html__('Process', 'g-business-reviews-rating'); ?></button>
            <button type="button" name="import" id="import-button" class="button button-primary"><?php echo esc_html__('Import', 'g-business-reviews-rating'); ?></button>
        	<button type="button" name="import-clear" id="import-clear-button" class="button button-secondary"><?php echo esc_html__('Clear', 'g-business-reviews-rating'); ?></button>
		</p>
<?php else: ?>
        <p><?php _e('This section is only available when the following criteria are met:', 'g-business-reviews-rating'); ?></p>
        <ul class="check-list">
        	<li><?php echo (!$this->demo) ? '<span class="dashicons dashicons-yes"></span>' : '<span class="dashicons dashicons-no"></span>'; ?> <?php _e('Demo mode is inactive;', 'g-business-reviews-rating'); ?></li>
        	<li><?php echo ($this->valid()) ? '<span class="dashicons dashicons-yes"></span>' : '<span class="dashicons dashicons-no"></span>'; ?> <?php _e('Place ID is set and valid;', 'g-business-reviews-rating'); ?></li>
        	<li><?php echo ($this->demo) ? '<span class="dashicons dashicons-minus"></span>' : (($this->count_reviews_all >= 5) ? '<span class="dashicons dashicons-yes"></span>' : '<span class="dashicons dashicons-no"></span>'); ?> <?php _e('Five or more reviews retrieved from Google.', 'g-business-reviews-rating'); ?></li>
        </ul>
<?php endif; ?>
        <form method="post" action="options.php" id="google-business-reviews-rating-settings-custom-styles">
            <h3><?php esc_html_e('Custom Styles', 'g-business-reviews-rating'); ?></h3>
            <p><?php _e('If you prefer to manage your style sheet outside of your theme, you may add your own customized styles.', 'g-business-reviews-rating'); ?></p>
            <p>
                <textarea id="custom-styles" name="google_business_reviews_rating_custom_styles" placeholder="&#x2F;&#x2A;&#x20;CSS&#x20;Document&#x20;&#x2A;&#x2F;&#13;&#10;&#13;&#10;.google-business-reviews-rating.badge&#x20;{&#13;&#10;&#x9;box-shadow:&#x20;0&#x20;14px&#x20;3px&#x20;-8px&#x20;rgba(0,&#x20;0,&#x20;0,&#x20;0.25),&#x20;0&#x20;0&#x20;0&#x20;3px&#x20;#F00&#x20;inset;&#13;&#10;}"><?php echo esc_html(get_option('google_business_reviews_rating_custom_styles')); ?></textarea>
			</p>
            <p class="submit">
                <button type="button" name="reset" id="custom-styles-button" class="button button-primary"><?php esc_html_e('Save', 'g-business-reviews-rating'); ?></button>
            </p>
        </form>
<?php if (!$this->demo): ?>
        <form method="post" action="options.php" id="google-business-reviews-rating-settings-cache">
            <h3><?php esc_html_e('Cache', 'g-business-reviews-rating'); ?></h3>
            <p><?php _e('You may wish to clear the cache and retrieve fresh data from Google.', 'g-business-reviews-rating'); ?></p>
            <p class="submit">
                <button type="button" name="clear-cache" id="clear-cache-button" class="button button-primary"><?php esc_html_e('Clear Cache', 'g-business-reviews-rating'); ?></button>
            </p>
        </form>
<?php endif; ?>
        <form method="post" action="options.php" id="google-business-reviews-rating-settings-reset">
            <h3><?php esc_html_e('Reset', 'g-business-reviews-rating'); ?></h3>
            <p><?php _e('At times you may wish to start over, so you can clear all the plugin&rsquo;s settings here.', 'g-business-reviews-rating'); ?></p>
            <p id="reset-confirm-text">
                <label for="reset-all"><input type="checkbox" id="reset-all" name="google_business_reviews_rating_reset_all" value="1"> <?php esc_html_e('Yes, I am sure.', 'g-business-reviews-rating'); ?></label>
<?php if ($this->count_reviews_all > 5): ?>
                <label for="reset-reviews"><input type="checkbox" id="reset-reviews" name="google_business_reviews_rating_reset_reviews" value="1"> <?php esc_html_e('Clear the review archive only.', 'g-business-reviews-rating'); ?></label>
<?php endif; ?>
			</p>
            <p class="submit">
                <button type="button" name="reset" id="reset-button" class="button button-primary"><?php esc_html_e('Reset', 'g-business-reviews-rating'); ?></button>
            </p>
        </form>
	</div>

	<div id="about" class="section<?php echo ($this->section != 'about') ? ' hide' : ''; ?>">
    	<div class="entry-content">
            <h2><?php esc_html_e('About', 'g-business-reviews-rating'); ?></h2>
<?php /* translators: 1: plugin support URL, 2: author's name, 3: author's website, 4: author's business name */ 
	echo sprintf(__('			<p>This little plugin came about as a side-effect to collecting a business&rsquo;s opening times using data from a client&rsquo;s Google Business listing. The recent review data is available and, with some tweaks, it could be displayed anywhere in a similar style to the actual Google review popup.</p>
			<p>The data is updated every hour and cached to reduce external requests and improve load times. The shortcodes can be used in any post, page or used the widget&rsquo;s to place in the sidebar or footer. Shortcode arguments will overwrite the default settings. I have kept the style sheet minimal to allow for your own customizations &mdash; as a developer/designer this is what I&rsquo;d like for all plugins.</p>
			<p>This is my first published plugin for WordPress so I&rsquo;d appreciate any feedback. So if you have any comments, feature requests or wish to show me your own designs, please feel free to <a href="%1$s">get in touch</a> with me.</p>
			<p><span class="signature" title="%2$s"></span><br>
				Developer, <a href="%3$s">%4$s</a></p>', 'g-business-reviews-rating'), 'https://designextreme.com/wordpress/', 'Noah H', 'https://designextreme.com', 'Design Extreme'); ?>

			<h2><a href="<?php echo esc_attr(admin_url('plugin-install.php?s=designextreme&tab=search&type=author')); ?>"><?php esc_html_e('Plugins by the Developer', 'g-business-reviews-rating'); ?></a></h2>
            <ul id="wordpress-plugin-list">
            	<li id="wordpress-plugin-g-business-reviews-rating">
                	<h3><a href="https://wordpress.org/plugins/g-business-reviews-rating/" class="components-external-link" target="_blank"><span class="icon"></span> Reviews and Rating – Google Business</a></h3>
                    <p>Shortcode and widget for Google reviews and rating. Give customers a chance to leave their own rating/review; includes Structured Data for SEO.</p>
                    <p class="more-details"><a href="https://wordpress.org/plugins/g-business-reviews-rating/" class="components-external-link" target="_blank"><?php esc_html_e('More Details', 'g-business-reviews-rating'); ?></a></p>
                    <p class="installed"><?php esc_html_e('Installed', 'g-business-reviews-rating'); ?></p>
                </li>
            	<li id="wordpress-plugin-open">
                	<h3><a href="https://wordpress.org/plugins/opening-hours/" class="components-external-link" target="_blank"><span class="icon"></span> We’re Open!</a></h3>
                    <p>Simple and easy to manage regular and special opening hours for your business, includes support for Structured Data and populating from Google Business.</p>
                    <p class="more-details"><a href="https://wordpress.org/plugins/opening-hours/" class="components-external-link" target="_blank"><?php esc_html_e('More Details', 'g-business-reviews-rating'); ?></a></p>
<?php if (is_plugin_active('opening-hours/opening-hours.php')) : ?>
                    <p class="installed"><?php esc_html_e('Installed', 'g-business-reviews-rating'); ?></p>
<?php endif; ?>
                </li>
            </ul>
		</div>
    	<div class="entry-meta">
            <div class="widget plugin-ratings">
                <h3 class="widget-title"><?php esc_html_e('Ratings', 'g-business-reviews-rating'); ?></h3>
                <p class="aside"><?php esc_html_e('Love this plugin? Why not offer your feedback?', 'g-business-reviews-rating'); ?></p>
                <p><a class="button" href="https://wordpress.org/support/plugin/g-business-reviews-rating/reviews/#new-post"><?php esc_html_e('Add my review', 'g-business-reviews-rating'); ?></a></p>			
            </div>
            <div class="widget plugin-support">
                <h3 class="widget-title"><?php esc_html_e('Support', 'g-business-reviews-rating'); ?></h3>
                <p class="aside"><?php esc_html_e('Got something to say? Need help?', 'g-business-reviews-rating'); ?></p>
                <p><a class="button" href="https://wordpress.org/support/plugin/g-business-reviews-rating/"><?php esc_html_e('View support forum', 'g-business-reviews-rating'); ?></a></p>			
            </div>
            <div class="widget plugin-donate">
                <h3 class="widget-title"><?php esc_html_e('Donate', 'g-business-reviews-rating'); ?></h3>
                <p class="aside"><?php esc_html_e('Would you like to support the advancement of this plugin?', 'g-business-reviews-rating'); ?></p>
                <p><a class="button button-secondary" href="https://paypal.me/designextreme"><?php esc_html_e('Donate to this plugin', 'g-business-reviews-rating'); ?></a></p>
            </div>
		</div>
	</div>
</div>
