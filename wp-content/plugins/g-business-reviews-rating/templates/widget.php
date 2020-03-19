<?php

if (!defined('ABSPATH'))
{
	die();
}

?>
	<div class="<?php echo esc_attr($this->reference)	; ?>">
        <p>
            <label for="<?php echo esc_attr($this->get_field_name('title')); ?>"><?php echo esc_attr(_e('Title:', 'g-business-reviews-rating')); ?></label>
            <input type="text" id="<?php echo esc_attr($this->get_field_id('title')); ?>" class="widefat" name="<?php echo esc_attr($this->get_field_name('title')); ?>" value="<?php echo esc_attr($title); ?>">
        </p>
        <p class="business-name"><?php echo (($this->business_icon != NULL) ? '<span class="icon"><img src="' . esc_attr($this->business_icon) . '" alt="' . esc_attr($this->business_name . ' ' . __('Icon', 'g-business-reviews-rating')) . '"></span>' : '') . esc_html($this->business_name); ?></p>
	    <p class="plugin-attribution"><span class="powered-by-google"></span></p>
        <p class="theme">
            <label for="<?php echo esc_attr($this->get_field_name('theme')); ?>"><?php echo esc_attr(_e('Theme:', 'g-business-reviews-rating')); ?></label>
            <select id="<?php echo esc_attr($this->get_field_id('theme')); ?>" name="<?php echo esc_attr($this->get_field_name('theme')); ?>">
                <option value=""<?php echo ($theme == NULL) ? ' selected' : ''; ?>><?php echo esc_html(__('Default', 'g-business-reviews-rating')); ?></option>
<?php
	foreach ($this->reviews_themes as $k => $theme_name)
	{
?>
                <option value="<?php echo esc_attr($k); ?>"<?php echo (($theme == $k) ? ' selected' : ''); ?>><?php echo esc_html($theme_name); ?></option>
<?php
	}
?>
            </select>
		</p>
        <p class="limit">
			<label for="<?php echo esc_attr($this->get_field_name('limit')); ?>"><?php echo esc_attr(_e('Review Limit:', 'g-business-reviews-rating')); ?></label>
			<input type="number" id="<?php echo esc_attr($this->get_field_id('limit')); ?>" class="small-text" name="<?php echo esc_attr($this->get_field_name('limit')); ?>" value="<?php echo esc_attr($limit); ?>" placeholder="&mdash;" step="1" min="0" max="<?php echo esc_attr($count); ?>">
			<span class="description" id="tagline-description"><a href="<?php echo esc_attr($this->plugin_settings_url . '#reviews'); ?>"><?php ($count == 1) ? printf(__( '%1$s review available'), $count) : printf(__( '%1$s reviews available'), $count); ?></a></span>
        </p>
        <p class="sort">
			<label for="<?php echo esc_attr($this->get_field_name('sort')); ?>"><?php echo esc_attr(_e('Review Sort:', 'g-business-reviews-rating')); ?></label>
			<select id="<?php echo esc_attr($this->get_field_id('sort')); ?>" name="<?php echo esc_attr($this->get_field_name('sort')); ?>">
<?php
	foreach ($this->review_sort_options as $k => $a)
	{
?>
				<option value="<?php echo (($k == 'relevance_desc') ? '' : esc_attr($k)); ?>"<?php echo ($sort == $k || $k == 'relevance_desc' && $sort == NULL) ? ' selected' : ''; ?>><?php echo esc_attr($a['name'].((isset($a['min_max_values']) && is_array($a['min_max_values'])) ? ' ('.implode(' → ', $a['min_max_values']).')' : '')); ?></option>
<?php
	}
?>
			</select>
        </p>
        <p>
			<label for="<?php echo esc_attr($this->get_field_name('offset')); ?>"><?php echo esc_attr(_e('Review Offset:', 'g-business-reviews-rating')); ?></label>
			<input type="number" id="<?php echo esc_attr($this->get_field_id('offset')); ?>" class="small-text" name="<?php echo esc_attr($this->get_field_name('offset')); ?>" value="<?php echo esc_attr($offset); ?>" placeholder="&mdash;" step="1" min="0" max="<?php echo esc_attr(($count - 1)); ?>">
        </p>
        <p class="rating">
			<label for="<?php echo esc_attr($this->get_field_name('rating_min')); ?>"><?php echo esc_attr(_e('Rating Range:', 'g-business-reviews-rating')); ?></label>
			<input type="number" id="<?php echo esc_attr($this->get_field_id('rating_min')); ?>" class="small-text" name="<?php echo esc_attr($this->get_field_name('rating_min')); ?>" value="<?php echo esc_attr($rating_min); ?>" step="1" min="1" max="5"> &mdash;
			<input type="number" id="<?php echo esc_attr($this->get_field_id('rating_max')); ?>" class="small-text" name="<?php echo esc_attr($this->get_field_name('rating_max')); ?>" value="<?php echo esc_attr($rating_max); ?>" step="1" min="1" max="5">
        </p>

        <p class="language">
            <label for="<?php echo esc_attr($this->get_field_name('language')); ?>"><?php echo esc_attr(_e('Review Text Language:', 'g-business-reviews-rating')); ?></label>
            <select id="<?php echo esc_attr($this->get_field_id('language')); ?>" name="<?php echo esc_attr($this->get_field_name('language')); ?>">
                <option value=""<?php echo ($language == NULL) ? ' selected' : ''; ?>><?php echo esc_html(__('Any Language', 'g-business-reviews-rating')); ?></option>
<?php
	foreach ($this->languages as $k => $language_name)
	{
?>
                <option value="<?php echo esc_attr($k); ?>"<?php echo (($language == $k) ? ' selected' : ''); ?>><?php echo esc_html($language_name); ?></option>
<?php
	}
?>
            </select>
        </p>
        <p class="review-text-length">
			<label for="<?php echo esc_attr($this->get_field_name('review_text_min')); ?>"><?php echo esc_attr(_e('Review Text Length Range:', 'g-business-reviews-rating')); ?></label>
			<input type="number" id="<?php echo esc_attr($this->get_field_id('review_text_min')); ?>" class="small-text" name="<?php echo esc_attr($this->get_field_name('review_text_min')); ?>" value="<?php echo esc_attr($review_text_min); ?>" step="1" min="0" max="4000"> &mdash;
			<input type="number" id="<?php echo esc_attr($this->get_field_id('review_text_max')); ?>" class="small-text" name="<?php echo esc_attr($this->get_field_name('review_text_max')); ?>" value="<?php echo esc_attr($review_text_max); ?>" step="1" min="0" max="4000">
        </p>
        <p class="excerpt-length">
			<label for="<?php echo esc_attr($this->get_field_name('excerpt_length')); ?>"><?php echo esc_attr(_e('Review Excerpt Length:', 'g-business-reviews-rating')); ?></label>
			<input type="number" id="<?php echo esc_attr($this->get_field_id('excerpt_length')); ?>" class="small-text" name="<?php echo esc_attr($this->get_field_name('excerpt_length')); ?>" value="<?php echo esc_attr($excerpt_length); ?>" placeholder="&mdash;" step="1" min="20" max="4000">
        </p>
        <p class="language">
            <label for="<?php echo esc_attr($this->get_field_name('more')); ?>"><?php echo esc_attr(_e('More Text:', 'g-business-reviews-rating')); ?></label>
            <input type="text" id="<?php echo esc_attr($this->get_field_id('more')); ?>" class="medium-text" name="<?php echo esc_attr($this->get_field_name('more')); ?>" value="<?php echo esc_attr($more); ?>">
        </p>
        <p>
			<input class="checkbox" type="checkbox" id="<?php echo esc_attr($this->get_field_id('display_name')); ?>" name="<?php echo esc_attr($this->get_field_name('display_name')); ?>" value="1"<?php echo (($display_name) ? ' checked="checked"' : ''); ?>> <label for="<?php echo esc_attr($this->get_field_id('display_name')); ?>"><?php echo esc_attr(_e('Display business name', 'g-business-reviews-rating')); ?></label><br>
			<input class="checkbox" type="checkbox" id="<?php echo esc_attr($this->get_field_id('display_icon')); ?>" name="<?php echo esc_attr($this->get_field_name('display_icon')); ?>" value="1"<?php echo (($display_icon) ? ' checked="checked"' : ''); ?>> <label for="<?php echo esc_attr($this->get_field_id('display_icon')); ?>"><?php echo esc_attr(_e('Display icon', 'g-business-reviews-rating')); ?></label><br>
			<input class="checkbox" type="checkbox" id="<?php echo esc_attr($this->get_field_id('display_rating')); ?>" name="<?php echo esc_attr($this->get_field_name('display_rating')); ?>" value="1"<?php echo (($display_rating) ? ' checked="checked"' : ''); ?>> <label for="<?php echo esc_attr($this->get_field_id('display_rating')); ?>"><?php echo esc_attr(_e('Display rating', 'g-business-reviews-rating')); ?></label><br>
			<input class="checkbox" type="checkbox" id="<?php echo esc_attr($this->get_field_id('display_rating_stars')); ?>" name="<?php echo esc_attr($this->get_field_name('display_rating_stars')); ?>" value="1"<?php echo (($display_rating_stars) ? ' checked="checked"' : ''); ?>> <label for="<?php echo esc_attr($this->get_field_id('display_rating_stars')); ?>"><?php echo esc_attr(_e('Display rating stars', 'g-business-reviews-rating')); ?></label><br>
			<input class="checkbox" type="checkbox" id="<?php echo esc_attr($this->get_field_id('display_review_count')); ?>" name="<?php echo esc_attr($this->get_field_name('display_review_count')); ?>" value="1"<?php echo (($display_review_count) ? ' checked="checked"' : ''); ?>> <label for="<?php echo esc_attr($this->get_field_id('display_review_count')); ?>"><?php echo esc_attr(_e('Display review count', 'g-business-reviews-rating')); ?></label><br>
			<input class="checkbox" type="checkbox" id="<?php echo esc_attr($this->get_field_id('display_reviews')); ?>" name="<?php echo esc_attr($this->get_field_name('display_reviews')); ?>" value="1"<?php echo (($display_reviews) ? ' checked="checked"' : ''); ?>> <label for="<?php echo esc_attr($this->get_field_id('display_reviews')); ?>"><?php echo esc_attr(_e('Display reviews', 'g-business-reviews-rating')); ?></label><br>
			<input class="checkbox" type="checkbox" id="<?php echo esc_attr($this->get_field_id('display_review_text')); ?>" name="<?php echo esc_attr($this->get_field_name('display_review_text')); ?>" value="1"<?php echo (($display_review_text) ? ' checked="checked"' : ''); ?>> <label for="<?php echo esc_attr($this->get_field_id('display_review_text')); ?>"><?php echo esc_attr(_e('Display review text', 'g-business-reviews-rating')); ?></label><br>
			<input class="checkbox" type="checkbox" id="<?php echo esc_attr($this->get_field_id('display_view_reviews_button')); ?>" name="<?php echo esc_attr($this->get_field_name('display_view_reviews_button')); ?>" value="1"<?php echo (($display_view_reviews_button) ? ' checked="checked"' : ''); ?>> <label for="<?php echo esc_attr($this->get_field_id('display_view_reviews_button')); ?>"><?php echo esc_attr(_e('Display view reviews button', 'g-business-reviews-rating')); ?></label><br>
			<input class="checkbox" type="checkbox" id="<?php echo esc_attr($this->get_field_id('display_write_review_button')); ?>" name="<?php echo esc_attr($this->get_field_name('display_write_review_button')); ?>" value="1"<?php echo (($display_write_review_button) ? ' checked="checked"' : ''); ?>> <label for="<?php echo esc_attr($this->get_field_id('display_write_review_button')); ?>"><?php echo esc_attr(_e('Display write review button', 'g-business-reviews-rating')); ?></label><br>
			<input class="checkbox" type="checkbox" id="<?php echo esc_attr($this->get_field_id('display_attribution')); ?>" name="<?php echo esc_attr($this->get_field_name('display_attribution')); ?>" value="1"<?php echo (($display_attribution) ? ' checked="checked"' : ''); ?>> <label for="<?php echo esc_attr($this->get_field_id('display_attribution')); ?>"><?php echo esc_attr(_e('Display attribution', 'g-business-reviews-rating')); ?></label><br>
			<input class="checkbox" type="checkbox" id="<?php echo esc_attr($this->get_field_id('animate')); ?>" name="<?php echo esc_attr($this->get_field_name('animate')); ?>" value="1"<?php echo (($animate) ? ' checked="checked"' : ''); ?>> <label for="<?php echo esc_attr($this->get_field_id('animate')); ?>"><?php echo esc_attr(_e('Animate rating stars', 'g-business-reviews-rating')); ?></label><br>
			<input class="checkbox" type="checkbox" id="<?php echo esc_attr($this->get_field_id('stylesheet')); ?>" name="<?php echo esc_attr($this->get_field_name('stylesheet')); ?>" value="1"<?php echo (($stylesheet) ? ' checked="checked"' : ''); ?>> <label for="<?php echo esc_attr($this->get_field_id('stylesheet')); ?>"><?php echo esc_attr(_e('Style Sheet active', 'g-business-reviews-rating')); ?></label>
		</p>
        <p class="buttons"><a href="<?php echo esc_attr($this->plugin_settings_url); ?>" class="button button-secondary"><?php echo esc_html(__('Settings', 'g-business-reviews-rating')); ?></a><?php echo ($this->demo) ? ' <a href="' . esc_attr($this->plugin_settings_url) . '" class="demo"><span class="dashicons dashicons-warning"></span> ' . __('Demo Mode', 'g-business-reviews-rating') . '</a>' : ''; ?></p>
    </div>
