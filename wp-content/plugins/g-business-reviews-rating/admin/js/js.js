// JavaScript Document

function google_business_reviews_rating_admin(popstate) {
	if (typeof popstate == 'undefined') {
		var popstate = false;
	}

	var place_id = null,
		google_api_key = null,
		language = null,
		update = null,
		section = null,
		data = [],
		review = {},
		reviews = [],
		relative_times = {},
		i = 0,
		j = 0,
		k = 0,
		count = 0,
		mean = 0,
		regex = null,
		name = null,
		html = null,
		review_limit = (jQuery('#review-limit').length && jQuery('#review-limit').val().length && jQuery('#review-limit').val().match(/^\d+(?:\.\d+)?$/)) ? parseInt(jQuery('#review-limit').val()) : null,
		existing_review_limit = (review_limit != null && review_limit > 1) ? review_limit : 1,
		existing = false,
		existing_show = false,
		existing_button = null,
		theme_columns = 1,
		time_unit = null,
		date_temp = null,
		date_estimate = null,
		date_actual = null,
		text = '',
		row = '';
	
	if (jQuery('#google-business-reviews-rating-settings').length) {
		place_id = jQuery('#place-id').val();
		google_api_key = jQuery('#api-key').val();
	}
	
	if (jQuery('.section', '#wpbody-content').length) {
		if (!jQuery('.nav-tab-active', jQuery('nav:eq(0)', '#wpbody-content')).length || typeof window.location.hash == 'string' && window.location.hash.length) {
			jQuery('.section', '#wpbody-content').each(function(section_index) {
				section = (typeof window.location.hash == 'string' && window.location.hash.length) ? window.location.hash.replace(/^#([\w-]+)/, '$1') : null;
				if (section == null && section_index == 0 || section != null && section == jQuery(this).attr('id')) {
					if (jQuery(this).hasClass('hide')) {
						jQuery(this).removeClass('hide');
					}
				}
				else if (!jQuery(this).hasClass('hide')) {
					jQuery(this).addClass('hide');
				}
			});
			
			if (jQuery('.nav-tab-active', jQuery('nav:eq(0)', '#wpbody-content')).length >= 1) {
				jQuery('.nav-tab-active', jQuery('nav:eq(0)', '#wpbody-content')).each(function(section_index) {
					if (section != null && jQuery(this).attr('href') != '#' + section || section == null && section_index == 0) {
						jQuery(this).removeClass('nav-tab-active');
					}
                });
			}
			
			jQuery('.nav-tab', jQuery('nav:eq(0)', '#wpbody-content')).each(function(tab_index) {
				section = (typeof jQuery(this).attr('href') == 'string') ? jQuery(this).attr('href').replace(/^.*#([\w-]+)/, '$1') : null;
				
				if ((tab_index == 0 && (section == null || typeof window.location.hash == 'undefined' || !window.location.hash.length)) || typeof window.location.hash == 'string' && window.location.hash.length && window.location.hash.replace(/^#([\w-]+)/, '$1') == section) {
					jQuery(this).addClass('nav-tab-active').prop('aria-current', 'page');
				}
			});
		}
	}
	
	if (popstate) {
		if (jQuery('.section', '#wpbody-content').length) {
			jQuery('.nav-tab', jQuery('nav:eq(0)', '#wpbody-content')).removeClass('nav-tab-active').removeProp('aria-current');
		}
		
		return;
	}
	
	if (jQuery('div', '#widgets-right').length) {
		jQuery('div', '#widgets-right').each(function() {
            if (typeof jQuery(this).attr('id') == 'string' && jQuery(this).attr('id').match(/google[_-]?business[_-]?reviews[_-]?rating/i)) {
				// console.log('Reviews and Rating - Google Business - Widget');
			}
        });
	}

	if (jQuery('#setup.section', '#wpbody-content').length && typeof jQuery('#setup.section', '#wpbody-content').data('hunter') == 'object' && jQuery('#setup.section', '#wpbody-content').data('hunter') != null) {
		data = jQuery('#setup.section', '#wpbody-content').data('hunter');
		google_api_key = (typeof data.api_key == 'string' && data.api_key.length > 10) ? data.api_key : null;
		place_id = (typeof data.place_id == 'string' && data.place_id.length > 10) ? data.place_id : null;
		language = (typeof data.language == 'string' && data.language.length > 1) ? data.language : null;

		if (!jQuery('#place-id').val().length) {
			if (!jQuery('#api-key').val().length) {
				jQuery('#api-key').val(google_api_key);
			}
	
			jQuery('#place-id').val(place_id);
		}

		if (!jQuery('#language').val().length && jQuery('option[value="' + language + '"]', '#language').length) {
			jQuery('#language').val(language);
		}

		if (update != null && !jQuery('#update').val().length && jQuery('option[value="' + update + '"]', '#update').length) {
			jQuery('#update').val(update);
		}
	}
	
	if (jQuery('.section', '#wpbody-content').length) {
		jQuery('.is-dismissible').each(function() {
			if (jQuery(this).hasClass('notice-success') || jQuery(this).hasClass('notice-error')) {
				jQuery(this).addClass('visible');
			}
			else {
				jQuery(this).remove();
			}
		});
		
		setTimeout(function() {
			if (jQuery('.is-dismissible').length) {
				jQuery('.is-dismissible').slideUp(300, function() { jQuery(this).remove(); });
			}
		}, 15000);

		jQuery('#review-limit, #review-limit-hide, #review-limit-set, #review-limit-all', '#wpbody-content').on('change', function() {
			theme_columns = (jQuery('#reviews-theme').length && typeof jQuery('#reviews-theme').val() == 'string' && jQuery('#reviews-theme').val().match(/\b(?:two|three|four)\b/i)) ? ((jQuery('#reviews-theme').val().match(/\bfour\b/i)) ? 4 : ((jQuery('#reviews-theme').val().match(/\bthree\b/i))) ? 3 : 2) : 1;

			if (jQuery(this).is('#review-limit')) {
				review_limit = (typeof jQuery(this).val() == 'number' || typeof jQuery(this).val() == 'string' && jQuery(this).val().match(/^\d+(?:\.\d+)?$/) && parseInt(jQuery(this).val()) >= 0) ? parseInt(jQuery(this).val()) : null;
				if (typeof review_limit != 'number') {
					jQuery('#review-limit-hide:checked').removeProp('checked');
					jQuery('#review-limit-set:checked').removeProp('checked');
					jQuery('#review-limit-all').prop('checked', 'checked');
					
					if (jQuery('#theme-recommendation-badge').is(':hidden') && jQuery('#reviews-theme').val().match(/\bbadge\b/i)) {
						jQuery('#theme-recommendation-badge').slideDown(300);
					}
					else if (jQuery('#theme-recommendation-columns').is(':hidden') && jQuery('#reviews-theme').val().match(/\bcolumns\b/i)) {
						jQuery('#theme-recommendation-columns').slideDown(300);
					}
				}
				else if (typeof review_limit == 'number' && review_limit < 1) {
					jQuery('#review-limit-all:checked').removeProp('checked');
					jQuery('#review-limit-set:checked').removeProp('checked');
					jQuery('#review-limit-hide').prop('checked', 'checked');
						
					if (jQuery('#theme-recommendation-badge').is(':visible') && jQuery('#reviews-theme').val().match(/\bbadge\b/i)) {
						jQuery('#theme-recommendation-badge').slideUp(300);
					}
					else if (jQuery('#theme-recommendation-columns').is(':visible') && jQuery('#reviews-theme').val().match(/\bcolumns\b/i)) {
						jQuery('#theme-recommendation-columns').slideUp(300);
					}
				}
				else {
					jQuery('#review-limit-hide:checked').removeProp('checked');
					jQuery('#review-limit-all:checked').removeProp('checked');
					jQuery('#review-limit-set').prop('checked', 'checked');
											
					if (jQuery('#theme-recommendation-badge').is(':hidden') && jQuery('#reviews-theme').val().match(/\bbadge\b/i)) {
						jQuery('#theme-recommendation-badge').slideDown(300);
					}
					
					if (jQuery('#reviews-theme').val().match(/\bcolumns\b/i)) {
						if (jQuery('#theme-recommendation-columns').is(':hidden') && review_limit%theme_columns > 0) {
							jQuery('#theme-recommendation-columns').slideDown(300);
						}
						else if (jQuery('#theme-recommendation-columns').is(':visible') && review_limit%theme_columns == 0) {
							jQuery('#theme-recommendation-columns').slideUp(300);
						}
					}
				}
			}
			else if (jQuery(this).is('#review-limit-hide')) {
				jQuery('#review-limit').val(0);
					
				if (jQuery('#theme-recommendation-badge').is(':visible') && jQuery('#reviews-theme').val().match(/\bbadge\b/i)) {
					jQuery('#theme-recommendation-badge').slideUp(300);
				}
				
				if (jQuery('#theme-recommendation-columns').is(':hidden') && jQuery('#reviews-theme').val().match(/\bcolumns\b/i)) {
					jQuery('#theme-recommendation-columns').slideDown(300);
				}
			}
			else if (jQuery(this).is('#review-limit-set')) {
				if (typeof(existing_review_limit) == 'number' && existing_review_limit > 1) {
					review_limit = existing_review_limit;
				}
				else if (typeof(theme_columns) == 'number' && theme_columns > 1) {
					review_limit = theme_columns;
				}
				else {
					review_limit = 1;
				}
								
				jQuery('#review-limit').val(review_limit);
				
				if (jQuery('#theme-recommendation-badge').is(':hidden') && jQuery('#reviews-theme').val().match(/\bbadge\b/i)) {
					jQuery('#theme-recommendation-badge').slideDown(300);
				}
				else if (jQuery('#reviews-theme').val().match(/\bcolumns\b/i)) {
					if (jQuery('#theme-recommendation-columns').is(':hidden') && review_limit%theme_columns > 0) {
						jQuery('#theme-recommendation-columns').slideDown(300);
					}
					else if (jQuery('#theme-recommendation-columns').is(':visible') && review_limit%theme_columns == 0) {
						jQuery('#theme-recommendation-columns').slideUp(300);
					}
				}
			}
			else if (jQuery(this).is('#review-limit-all')) {
				jQuery('#review-limit').val('');
					
				if (jQuery('#theme-recommendation-badge').is(':hidden') && jQuery('#reviews-theme').val().match(/\bbadge\b/i)) {
					jQuery('#theme-recommendation-badge').slideDown(300);
				}
				else if (jQuery('#theme-recommendation-columns').is(':hidden') && jQuery('#reviews-theme').val().match(/\bcolumns\b/i)) {
					jQuery('#theme-recommendation-columns').slideDown(300);
				}
			}
			
			jQuery(':input', jQuery('.show-reviews', '#wpbody-content')).each(function() {
                if (!jQuery(this).is(':disabled') && (typeof jQuery('#review-limit').val() == 'string' && jQuery('#review-limit').val() == '0' || typeof jQuery('#review-limit').val() == 'number' && jQuery('#review-limit').val() <= 0)) {
					jQuery(this).prop('disabled', true);
				}
                else if (jQuery(this).is(':disabled') && (typeof jQuery('#review-limit').val() == 'string' && jQuery('#review-limit').val() != '0' || typeof jQuery('#review-limit').val() == 'number' && jQuery('#review-limit').val() > 0)) {
					jQuery(this).removeProp('disabled');
				}
            });
		});
		
		jQuery(':input', '#google-business-reviews-rating-setup').on('change', function() {
			return google_business_reviews_rating_preview();
		});
				
		jQuery('#reviews-theme', '#wpbody-content').on('change', function() {
			review_limit = (jQuery('#review-limit').length && jQuery('#review-limit').val().length && jQuery('#review-limit').val().match(/^\d+(?:\.\d+)?$/)) ? parseInt(jQuery('#review-limit').val()) : null;
			theme_columns = (jQuery('#reviews-theme').length && typeof jQuery('#reviews-theme').val() == 'string' && jQuery('#reviews-theme').val().match(/\b(?:two|three|four)\b/i)) ? ((jQuery('#reviews-theme').val().match(/\bfour\b/i)) ? 4 : ((jQuery('#reviews-theme').val().match(/\bthree\b/i))) ? 3 : 2) : 1;
			
			if (jQuery('#theme-recommendation-badge').is(':hidden') && jQuery(this).val().match(/\bbadge\b/i) && (review_limit == null || review_limit >= 1)) {
				jQuery('#theme-recommendation-badge').slideDown(300);
			}
			else if (jQuery('#theme-recommendation-badge').is(':visible') && !jQuery(this).val().match(/\bbadge\b/i)) {
				jQuery('#theme-recommendation-badge').slideUp(300);
			}
	
			if (jQuery(this).val().match(/\bcolumns\b/i) && (typeof review_limit != 'number' || typeof review_limit == 'number' && (review_limit < 1 || review_limit%theme_columns > 0))) {
				if (jQuery('#theme-recommendation-columns').is(':hidden')) {
					jQuery('#theme-recommendation-columns').slideDown(300);
				}
			}
			else if (jQuery('#theme-recommendation-columns').is(':visible')) {
				jQuery('#theme-recommendation-columns').slideUp(300);
			}
		
			if (jQuery(this).val().match(/\bdark\b/i) && !jQuery(this).closest('.section').hasClass('dark')) {
				jQuery(this).closest('.section').addClass('dark')
			}
			else if (!jQuery(this).val().match(/\bdark\b/i) && jQuery(this).closest('.section').hasClass('dark')) {
				jQuery(this).closest('.section').removeClass('dark')
			}
			
			if (jQuery(this).val().match(/\bfonts\b/i) && !jQuery(this).closest('.section').hasClass('fonts')) {
				jQuery(this).closest('.section').addClass('fonts')
			}
			else if (!jQuery(this).val().match(/\bfonts\b/i) && jQuery(this).closest('.section').hasClass('fonts')) {
				jQuery(this).closest('.section').removeClass('fonts')
			}
		});
		
		jQuery('#stylesheet', '#wpbody-content').on('change', function() {
			if (!jQuery(this).is(':checked') && !jQuery('#reviews-theme', '#wpbody-content').is(':disabled')) {
				jQuery('#reviews-theme', '#wpbody-content').prop('disabled', 'disabled');
			}
			else if (jQuery(this).is(':checked') && jQuery('#reviews-theme', '#wpbody-content').is(':disabled')) {
				jQuery('#reviews-theme', '#wpbody-content').removeProp('disabled');
			}
		});
		
		jQuery('#structured-data', '#wpbody-content').on('change', function() {
			jQuery('.structured-data', '#wpbody-content').each(function() {
                if (jQuery('#structured-data', '#wpbody-content').is(':checked')) {
					jQuery(this).show();
				}
				else {
					jQuery(this).hide();
				}
            });
			
			if (jQuery('#structured-data', '#wpbody-content').is(':checked')) {
				jQuery('#telephone', '#wpbody-content').focus();
			}
		});
		
		jQuery('a', '#reviews-rating-preview-heading').on('click', function (event) {
			event.preventDefault();
			
			if (jQuery('#reviews-rating-preview').hasClass('show')) {
				jQuery('#reviews-rating-preview').removeClass('show');
				jQuery('#reviews-rating-preview-heading').removeClass('active');
				jQuery('.dashicons', this).removeClass('dashicons-arrow-down').addClass('dashicons-arrow-right');
			}
			else {
				jQuery('#reviews-rating-preview').addClass('show');
				jQuery('#reviews-rating-preview-heading').addClass('active');
				jQuery('.dashicons', this).removeClass('dashicons-arrow-right').addClass('dashicons-arrow-down');
			}
		});
		
		jQuery('#structured-data-preview').on('click', function (event) {
			event.preventDefault();
			if (jQuery('#google-business-reviews-rating-overlay').length) {
				jQuery('#google-business-reviews-rating-overlay').remove();
			}
			
			jQuery('#structured-data-preview').after('<div id="google-business-reviews-rating-overlay"></div>');
			jQuery('#google-business-reviews-rating-overlay').on('click', function(event) {
				if (jQuery(event.target).attr('id') == 'google-business-reviews-rating-overlay') {
					jQuery(this).fadeOut(300, function() { jQuery(this).remove(); });
				}
			});
			
			jQuery('#google-business-reviews-rating-overlay').append('<div id="google-business-reviews-rating-close" class="close"><span class="dashicons dashicons-no" title="Close"></span></div><pre id="google-business-reviews-rating-structured-data"></pre>');

			jQuery('#google-business-reviews-rating-close').on('click', function() {
				jQuery('#google-business-reviews-rating-overlay').fadeOut(300, function() { jQuery('#google-business-reviews-rating-overlay').remove(); });
			});
			
			data = {
				action: 'google_business_reviews_rating_admin_ajax',
				type: 'structured_data'
			};
			
			if (jQuery('#logo').length) {
				data['logo'] = jQuery('#logo').val();
			}

			if (jQuery('#telephone').length) {
				data['telephone'] = jQuery('#telephone').val();
			}

			if (jQuery('#business-type').length) {
				data['business_type'] = jQuery('#business-type').val();
			}

			if (jQuery('#price-range').length) {
				data['price-range'] = jQuery('#price-range').val();
			}

			jQuery.post(google_business_reviews_rating_admin_ajax.url, data, function(response) {
				if (response.success) {
					jQuery('#google-business-reviews-rating-structured-data').html(response.data);
					google_business_reviews_rating_syntax_highlight(jQuery('#google-business-reviews-rating-structured-data'));
				}
				else {
					jQuery(this).fadeOut(300, function() { jQuery(this).remove(); });
				}
			}, 'json');

		});
		
		jQuery('#custom-styles-button').on('click', function () {
			existing_button = jQuery('#custom-styles-button').html();
			jQuery('#custom-styles-button').html('Saving&hellip;');
			data = {
				action: 'google_business_reviews_rating_admin_ajax',
				type: 'custom_styles',
				custom_styles: jQuery('#custom-styles').val()
			};

			jQuery.post(google_business_reviews_rating_admin_ajax.url, data, function(response) {
				if (response.success) {
					jQuery('#custom-styles-button').html('Saved');
					setTimeout(function() { jQuery('#custom-styles-button').html(existing_button); }, 1200);
					
					if (typeof response.message == 'string') {
						google_business_reviews_rating_message(response.message, 'success');
					}
				}
				else {
					if (typeof response.message == 'string') {
						google_business_reviews_rating_message(response.message, 'error');
					}
					
					jQuery('#custom-styles-button').html('Retry');
				}
			}, 'json');
		});
		
		jQuery('#google-credentials-help').on('click', function (event) {
			event.preventDefault();
			if (jQuery('#google-credentials-steps').is(':visible')) {
				jQuery('#google-credentials-steps').slideUp(300);
			}
			else {
				jQuery('#google-credentials-steps').slideDown(300);
			}
		});
		
		jQuery('#clear-cache-button').on('click', function () {
			jQuery('#clear-cache-button').html('Clearing&hellip;');
			data = {
				action: 'google_business_reviews_rating_admin_ajax',
				type: 'clear_cache'
			};

			jQuery.post(google_business_reviews_rating_admin_ajax.url, data, function(response) {
				if (response.success) {
					jQuery('#clear-cache-button').html('Cleared');
					
					if (typeof response.message == 'string') {
						google_business_reviews_rating_message(response.message, 'success');
						setTimeout(function() { document.location.href = document.location.href.replace(location.hash, ''); }, 500);
					}
					else {
						setTimeout(function() { document.location.href = document.location.href.replace(location.hash, ''); }, 300);
					}
				}
				else {
					if (typeof response.message == 'string') {
						google_business_reviews_rating_message(response.message, 'error');
					}
					
					jQuery('#clear-cache-button').html('Retry Clear Cache');
				}
			}, 'json');
		});

		jQuery('#reset-button').on('click', function () {
			if (jQuery('#reset-confirm-text').is(':hidden')) {
				jQuery('#reset-confirm-text').slideDown(300);
			}
			else if (jQuery('#reset-confirm-text').is(':visible') && (jQuery('#reset-all').is(':checked') || jQuery('#reset-reviews').is(':checked'))) {
				data = {
					action: 'google_business_reviews_rating_admin_ajax',
					type: (jQuery('#reset-all').is(':checked')) ? 'reset' : 'reset_reviews'
				};

				jQuery.post(google_business_reviews_rating_admin_ajax.url, data, function(response) {
					if (response.success) {
						if (jQuery('#reset-all').is(':checked')) {
							jQuery('.nav-tab[href="#shortcodes"], .nav-tab[href="#reviews"], .nav-tab[href="#data"]', jQuery('nav:eq(0)', '#wpbody-content')).hide();
						}
						
						if (typeof response.message == 'string') {
							google_business_reviews_rating_message(response.message, 'success');

							setTimeout(function() {
								document.location.href = document.location.href.replace(location.hash, '');
							}, 500);
						}
						else {
							document.location.href = document.location.href.replace(location.hash, '');
						}
					}
					
					jQuery('#reset-all').prop('checked', false);
					jQuery('#reset-reviews').prop('checked', false);
				}, 'json');
			}
		});

		jQuery('.nav-tab', jQuery('nav:eq(0)', '#wpbody-content')).each(function(tab_index) {
			if (jQuery('.count', this).length && typeof jQuery('.count', this).text() == 'string' && jQuery('.count', this).text().match(/^\d{3,}$/i)) {
				jQuery('.count', this).attr('title', jQuery('.count', this).text()).addClass('more-than-99').text('99+');
			}
			jQuery(this).on('click', function (event) {
				event.preventDefault();
				section = (typeof jQuery(this).attr('href') == 'string') ? jQuery(this).attr('href').replace(/#([\w-]+)/, '$1') : null;
				
				if (jQuery('.is-dismissible', '#wpbody-content').length) {
					jQuery('.is-dismissible', '#wpbody-content').remove();
				}
				
				if (tab_index != jQuery('.nav-tab-active', jQuery('nav:eq(0)', '#wpbody-content')).index('.nav-tab')) {
					jQuery('.nav-tab:not(:eq('+tab_index+'))', jQuery('nav:eq(0)', '#wpbody-content')).removeClass('nav-tab-active').removeProp('aria-current');
					jQuery('.nav-tab:eq('+tab_index+')', jQuery('nav:eq(0)', '#wpbody-content')).addClass('nav-tab-active').prop('aria-current', 'page');
				}
				
				jQuery('.section', '#wpbody-content').each(function(section_index) {
					if (section == null && section_index == 0 || section != null && section == jQuery(this).attr('id')) {
						if (jQuery(this).hasClass('hide')) {
							jQuery(this).removeClass('hide');
						}
					}
					else if (!jQuery(this).hasClass('hide')) {
						jQuery(this).addClass('hide');
					}
				});
				
				data = {
					action: 'google_business_reviews_rating_admin_ajax',
					type: 'section',
					section: (typeof section == 'string' && !section.match(/^setup$/i)) ? section : null
				};

				jQuery.post(google_business_reviews_rating_admin_ajax.url, data, function(response) {
					if (response.success) {
						if (window.history && window.history.pushState) {
							history.pushState(null, null, '#' + section);
						}
						else {
							location.hash = '#' + section;
						}
						
						if (data.section == null) {
							google_business_reviews_rating_preview();
						}
					}
				}, 'json');
						
				setTimeout(function() {
					window.scrollTo(0, 0);
					setTimeout(function() {
						window.scrollTo(0, 0);
						if (tab_index != jQuery('.nav-tab-active', jQuery('nav:eq(0)', '#wpbody-content')).index('.nav-tab')) {
							jQuery('.nav-tab:not(:eq('+tab_index+'))', jQuery('nav:eq(0)', '#wpbody-content')).removeClass('nav-tab-active').removeProp('aria-current');
							jQuery('.nav-tab:eq('+tab_index+')', jQuery('nav:eq(0)', '#wpbody-content')).addClass('nav-tab-active').prop('aria-current', 'page');
						}
						}, 100);
					}, 10);
				
			});
		});

		setTimeout(function() {
			window.scrollTo(0, 0);
			setTimeout(function() {
				window.scrollTo(0, 0);
				}, 100);
			}, 10);
	}
	
	if (jQuery('#rating-min').length && jQuery('#rating-max').length) {
		jQuery('#rating-min,#rating-max').on('change', function() {
			if (jQuery('#rating-min').val().length && jQuery('#rating-max').val().length && parseInt(jQuery('#rating-min').val()) > parseInt(jQuery('#rating-max').val())) {
				jQuery('#rating-min').val(jQuery('#rating-max').val());
			}
		});
	}
	
	if (jQuery('#review-text-min').length && jQuery('#review-text-max').length) {
		jQuery('#review-text-min,#review-text-max').on('change', function() {
			if (jQuery('#review-text-min').val().length && jQuery('#review-text-max').val().length && parseInt(jQuery('#review-text-min').val()) > parseInt(jQuery('#review-text-max').val())) {
				jQuery('#review-text-min').val(jQuery('#review-text-max').val());
			}
		});
	}
	
	if (jQuery('.review', '#reviews-table').length) {
		jQuery('.review', '#reviews-table').each(function() {
			jQuery('.show-hide', jQuery('.id', this)).on('click', function(event) {
				event.preventDefault();
				google_business_reviews_rating_status(this);
				return;
			});
			
			jQuery('.remove', jQuery('.id', this)).on('click', function(event) {
				event.preventDefault();
				google_business_reviews_rating_remove(this);
				return;
			});
			
			jQuery('.date', jQuery('.submitted', this)).on('click', function(event) {
				if (jQuery(this).closest('.review').hasClass('estimate')) {
					event.preventDefault();
					jQuery(this).hide();
					jQuery(this).siblings('.time-estimate').show().focus();
				}
				return;
			});
			
			jQuery('.time-estimate', jQuery('.submitted', this)).on('change', function(event) {
				event.preventDefault();
				google_business_reviews_rating_submitted(this);
				return;
			});

		});
	}
	
	if (jQuery('li', '#advanced .entry-content').length && jQuery('#html-import-figure-1').length) {
		
		jQuery('li:eq(2)', '#advanced .entry-content').on('mouseover mouseout', function(event) {
			if (event.type == 'mouseover') {
				jQuery('img', '#html-import-figure-1, #html-import-figure-2').css('box-shadow', '0 0 0 3px #008ec2');
			}
			else {
				jQuery('img', '#html-import-figure-1, #html-import-figure-2').removeAttr('style');
			}
		});
		
		jQuery('li:eq(3)', '#advanced .entry-content').on('mouseover mouseout', function(event) {
			if (event.type == 'mouseover') {
				jQuery('img', '#html-import-figure-3').css('box-shadow', '0 0 0 3px #008ec2');
			}
			else {
				jQuery('img', '#html-import-figure-3').removeAttr('style');
			}
		});
	}
	
	jQuery('.right-click').each(function() {
		if (typeof navigator != 'undefined' && typeof navigator.appVersion == 'string' && navigator.appVersion.match(/i(?:phone|pod|pad)|android|blackberry|webos/i)) {
			jQuery(this).text(((jQuery(this).text().match(/^[A-Z]/)) ? 'P' : 'p') + 'ress and hold')
		}
		else if (typeof navigator != 'undefined' && typeof navigator.appVersion == 'string' && navigator.appVersion.indexOf('Mac') >= 0) {
			jQuery(this).text(((jQuery(this).text().match(/^[A-Z]/)) ? 'C' : 'c') + 'ommand click')
		}
	});
	
	jQuery('#import-button').on('click', function() {
		if (jQuery(this).is(':disabled') || jQuery(this).is(':hidden') || !jQuery('.review', '#reviews-import-table').length) {
			return;
		}
		
		i = 0;
		data = {
			action: 'google_business_reviews_rating_admin_ajax',
			type: 'import',
			review: {},
			reviews: []
		};

		jQuery('.review', '#reviews-import-table').each(function() {
			if (!jQuery(this).hasClass('existing') && jQuery(':input:checkbox:checked', this).length && typeof jQuery(this).data('review') != 'undefined' && jQuery('.date :input:eq(0)', this).val().length) {
				review = jQuery(this).data('review');
				review.time = jQuery('.date :input:eq(0)', this).val();
				data.reviews.push(review);
			}
		});
		
		jQuery.post(google_business_reviews_rating_admin_ajax.url, data, function(response) {
			if (response.success) {
				google_business_reviews_rating_message(response.message, 'success');
				jQuery('#html-import-input, #html-import-output').remove();
				jQuery('#advanced').removeClass('import-results');
				jQuery('#import-button, #import-clear-button').hide();
				jQuery('#import-process-button, #html-import, #advanced > .introduction, #google-business-reviews-rating-settings-custom-styles, #google-business-reviews-rating-settings-cache, #google-business-reviews-rating-settings-reset').show();
				jQuery('#html-import').val('');
				reviews = [];
				window.scrollTo(0, 0);
				
				setTimeout(function() {
					section = 'reviews';
					jQuery('a[href="#advanced"]', '.nav-tab-wrapper:eq(0)').removeClass('nav-tab-active');
					jQuery('a[href="#' + section + '"]', '.nav-tab-wrapper:eq(0)').removeClass('nav-tab-active');
					jQuery('#advanced', '#google-business-reviews-rating-settings').hide();
					jQuery('#' + section, '#google-business-reviews-rating-settings').show();
					jQuery('#reviews-table', '#google-business-reviews-rating-settings').css('opacity', 0.4);
					jQuery('#reviews-table', '#google-business-reviews-rating-settings').append('        <tr id="temp-row">\n            <td class="full-width" colspan="' + jQuery('th, td', jQuery('#reviews-table tr:eq(0)', '#google-business-reviews-rating-settings')).length + '">&hellip;</td>\n        </tr>');

					setTimeout(function() {
						window.location.hash = '#' + section;
						window.scrollTo(0, 0);
						window.location.reload(true);
					}, 500);
				}, 100);
			}
			else {
				google_business_reviews_rating_message(response.message, 'error');
				jQuery('#html-import-input, #html-import-output').remove();
				jQuery('#advanced').removeClass('import-results');
				jQuery('#import-button, #import-clear-button').hide();
				jQuery('#import-process-button, #html-import, #advanced > .introduction, #google-business-reviews-rating-settings-custom-styles, #google-business-reviews-rating-settings-cache, #google-business-reviews-rating-settings-reset').show();
				jQuery('#html-import').val('');
				reviews = [];
			}
		}, 'json');
		return;

	});
	
	jQuery('.void').each(function() {
		jQuery(this).on('click', function(event) {
			event.preventDefault();
		});
	});
	
	if (jQuery('#html-import').length) {
		jQuery('#import-clear-button').on('click', function(event) {
			jQuery('#html-import-input, #html-import-output').remove();
			jQuery('#advanced').removeClass('import-results');
			jQuery('#import-button, #import-clear-button').hide();
			jQuery('#import-process-button, #html-import, #html-import-review-text, #html-import-existing-label, #advanced > .introduction, #google-business-reviews-rating-settings-custom-styles, #google-business-reviews-rating-settings-cache, #google-business-reviews-rating-settings-reset').show();
			jQuery('#html-import').val('');
			reviews = [];
		});
		
		jQuery('#html-import, #import-process-button').on('change blur click', function(event) {
			
			jQuery('#html-import').removeClass('error').removeClass('valid');
			
			if (!jQuery('#html-import').val().length || jQuery(this).is('#html-import') && event.type == 'click' || jQuery(this).is('#import-process-button') && event.type != 'click') {
				return;
			}
			
			html = jQuery('#html-import').val();
			
			if (!jQuery('#html-import-input').length) {
				jQuery('#html-import-existing-label').after('<div id="html-import-input" style="display: none;"></div>');
			}
			
			jQuery.parseHTML(html, null, false);
			document.getElementById('html-import-input').innerHTML = html;
			
			if (jQuery('*[data-google-review-count]', '#html-import-input').length) {
				jQuery('#html-import').addClass('valid');
				
				if (jQuery(this).is('#html-import')) {
					return;
				}
				
				jQuery('#import-process-button, #html-import, #html-import-review-text, #html-import-existing-label, #advanced > .introduction, #google-business-reviews-rating-settings-custom-styles, #google-business-reviews-rating-settings-cache, #google-business-reviews-rating-settings-reset').hide();
				jQuery('#advanced').addClass('import-results');

				jQuery('*[data-google-review-count]', '#html-import-input').each(function() {
					jQuery('> div', this).each(function() {
						text = (jQuery('.review-full-text', this).length && typeof jQuery('div:eq(0) > div:eq(2) .review-full-text:eq(0)', this).html() == 'string') ? jQuery('div:eq(0) > div:eq(2) .review-full-text:eq(0)', this).html() : ((typeof jQuery('div:eq(0) > div:eq(2) > div:eq(1) span:eq(0)', this).html() == 'string') ? jQuery('div:eq(0) > div:eq(2) > div:eq(1) span:eq(0)', this).html() : null);

						switch (jQuery('#html-import-review-text').val()) {
						case 'translation':
							if (!text.match(/^\s*\([^)]{4,100}\)\s*.+$/)) {
								break;
							}
							text = text.replace(/^\s*\([^)]{4,100}\)\s+(.+)\s*(?:<br\s?\/?>\s*){2,3}\([^)]{4,100}\)\s*(?:<br\s?\/?>\s*){1,3}(.+)$/, '$1')
							break;
						case 'original':
							if (!text.match(/^\s*\([^)]{4,100}\)\s*.+$/)) {
								break;
							}
							text = text.replace(/^\s*\([^)]{4,100}\)\s+(.+)\s*(?:<br\s?\/?>\s*){2,3}\([^)]{4,100}\)\s*(?:<br\s?\/?>\s*){1,3}(.+)$/, '$2')
							break;
						default:
							break;
						}
						
						reviews.push({
							author_name: (typeof jQuery('div:eq(0) a:eq(0)', this).text() == 'string') ? jQuery('div:eq(0) a:eq(0)', this).text() : null,
							author_url: (typeof jQuery('div:eq(0) a:eq(0)', this).attr('href') == 'string') ? jQuery('div:eq(0) a:eq(0)', this).attr('href') : null,
							profile_photo_url: (typeof jQuery('img:eq(0)', this).attr('src') == 'string') ? jQuery('img:eq(0)', this).attr('src') : null,
							rating: (typeof jQuery('div:eq(0) > div:eq(2) span:eq(0)', this).attr('aria-label') == 'string') ? (Math.round(parseFloat(jQuery('div:eq(0) > div:eq(2) span:eq(0)', this).attr('aria-label').replace(/^[^\d]*(\d+(?:\.\d+)?).*$/, '$1'))*10)*0.1) : null,
							relative_time_description: (typeof jQuery('div:eq(0) > div:eq(2) span:eq(2)', this).text() == 'string') ? jQuery('div:eq(0) > div:eq(2) span:eq(2)', this).text() : null,
							text: text,
							time: null
						});
					});
				});
				
				if (reviews.length) {
					jQuery('#reviews-import-table').remove();
					jQuery('#html-import-input').after('<div id="html-import-output"><table id="reviews-import-table" class="wp-list-table widefat fixed striped reviews-table"></table></div>');
					row = '<tr>\n'
						+ '<td id="cb" class="manage-column column-cb check-column"><label class="screen-reader-text" for="review-import-select-all">Select all</label><input id="review-import-select-all" type="checkbox" checked="checked"></td>\n'
						+ '<th class="author">Name</th>\n'
						+ '<th class="rating">Rating</th>\n'
						+ '<th class="text">Review</th>\n'
						+ '<th class="relative-time-description date">Relative Date</th>\n'
						+ '<th class="submitted date" title="Approximate Submitted Date">Approx. Date</th>\n'
						+ '</tr>\n';
					jQuery('#reviews-import-table').append(row);
					
					jQuery('#review-import-select-all').on('click', function() {
						jQuery('.review', '#reviews-import-table').each(function() {
							jQuery(':input:checkbox', this).prop('checked', !jQuery(':input:checkbox', this).is(':checked'));
						});
					});
										
					existing_show = jQuery('#html-import-existing').is(':checked');
					relative_times = jQuery('#html-import').data('relative-times');
					count = 0;
					
					for (j = 0; j < 2; j++) {
						for (i in reviews) {
							review = reviews[i];
							existing = false;
							date_actual = null;
							
							if (!existing) {
								jQuery('.review', '#reviews-table').each(function() {
									if (!existing) {
										if (jQuery('.author:eq(0) > .name', this).text() == review.author_name && jQuery('.author:eq(0) > .name a:eq(0)', this).attr('href').replace(/^.+\/(\d{20,120}).*$/, '$1') == review.author_url.replace(/^.+\/(\d{20,120}).*$/, '$1')) {
											existing = true;
											date_actual = jQuery('.submitted', this).text();
										}
									}
								});
							}
							
							reviews[i].existing = existing;		
							
							if (!existing && j == 0 || existing_show && existing && j == 1) {
							
								date_temp = null;
								time_unit = (review.relative_time_description.match(/^([\d]{1,3})\s+[^\s]+\s+\w+/i)) ? parseInt(review.relative_time_description.match(/^.*(\d{1,3}).*$/i, '$1')) : 1;
								
								for (k in relative_times) {
									regex = new RegExp('^' + relative_times[k].text.replace(/%u/g, '\\d+').replace(/ /g, '\\s+') + '$', 'i');
									
									if (review.relative_time_description.match(regex)) {
										date_temp = new Date();
										if (relative_times[k].singular) {
											date_temp.setDate(date_temp.getDate() - Math.round(Math.round((relative_times[k].min_time + relative_times[k].max_time) * 0.5) / 86400));
										}
										else {
											date_temp.setDate(date_temp.getDate() - Math.round((relative_times[k].divider * time_unit) / 86400));
										}
										
										break;
									}
								}
								
								date_estimate = (date_temp != null) ? date_temp.getFullYear() + '-' + ((date_temp.getMonth() < 9) ? '0' + String(date_temp.getMonth() + 1) : (date_temp.getMonth() + 1)) + '-' + ((date_temp.getDate() < 10) ? '0' + String(date_temp.getDate()) : date_temp.getDate()) : '';
								
								row = '<tr id="review-import-' + (parseInt(i) + 1) + '" class="review rating-' + review.rating + ((existing) ? ' existing' : '') + '">\n'
									+ '<td class="check-column" scope="row">' + ((!existing) ? '<label class="screen-reader-text" for="review-import-cb-' + (parseInt(i) + 1) + '">Select</label><input id="review-import-cb-' + (parseInt(i) + 1) + '" type="checkbox" checked="checked">' : '&nbsp;') + '</td>\n'
									+ '<td class="author"><span class="name"><a href="'+ review.author_url + '">'+ review.author_name + '</a></span> <span class="avatar"><a href="'+ review.author_url + '"><img src="'+ review.profile_photo_url + '" alt="Avatar"></a></span></td>\n'
									+ '<td class="rating">' + String('★').repeat(parseInt(review.rating)) + ((parseInt(review.rating) < 5) ? '<span class="not">' + String('☆').repeat(5 - parseInt(review.rating)) + '</span>' : '') + ' <span class="rating-number">(' + parseInt(review.rating) + ')</span></td>\n'
									+ '<td class="text"><div class="text-wrap">' + ((review.text.length) ? review.text : '<span class="none" title="None">—</span>') + '</div></td>\n'
									+ '<td class="relative-time-description date">'+ review.relative_time_description + '</td>\n'
									+ '<td class="submitted date">' + ((!existing) ? '<input type="date" id="review-import-date-' + (parseInt(i) + 1) + '" name="review-import-date[]" value="' + date_estimate + '" title="Approximate Submitted Date">' : '<span title="Submitted Date">' + date_actual + '</span>') + '</td>\n'
									+ '</tr>\n';
								jQuery('table', '#html-import-output').append(row);
								
								if (!existing) {
									count++;
								}
							}
						}
					}
					
					if (count > 0) {
						for (i in reviews) {
							if (!reviews[i].existing) {
								jQuery('#review-import-' + (parseInt(i) + 1)).data('review', reviews[i]);
							}
						}
					}

				}

				if (count > 0) {
					jQuery('#import-button, #import-clear-button').show();
					if (count > 20) {
						jQuery('#html-import-output').prepend('<p>Found ' + count + ' new reviews.</p>');
					}
				}
				else {
					if (!existing_show) {
						jQuery('#html-import-output').html('<p>No new reviews found.</p>');
					}
					else {
						jQuery('#html-import-output').prepend('<p>No additional reviews found.</p>');
					}
					
					jQuery('#import-clear-button').show();
				}
			}
			else {
				jQuery('#reviews-import-table').remove();
				jQuery('#html-import').addClass('error');
			}
			
			jQuery('#html-import-input').html('');
		});
	}

	google_business_reviews_rating_media_image('icon');
	google_business_reviews_rating_media_image('logo');
	google_business_reviews_rating_preview();
	
	if (jQuery('#google-business-reviews-rating-data').length) {
		google_business_reviews_rating_syntax_highlight(jQuery('#google-business-reviews-rating-data'));
	}
		
	if (jQuery('#google-business-reviews-rating-valid-data').length) {
		google_business_reviews_rating_syntax_highlight(jQuery('#google-business-reviews-rating-valid-data'));
	}
		
	return;
}

function google_business_reviews_rating_message(message, type) {
	if (typeof message != 'string') {
		return;
	}
	
	if (typeof type != 'string') {
		var type = 'success';
	}
	
	var html = '<div id="google-business-reviews-rating-settings-message" class="notice ' + type + ' notice-' + type + ' visible is-dismissible">\n'
	+ '<p><strong>' + message + '</strong></p>\n'
	+ '<button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>\n'
	+ '</div>';
	
	if (jQuery('#google-business-reviews-rating-settings-message').length) {
		jQuery('#google-business-reviews-rating-settings-message').remove();
	}
	
	jQuery('h1:eq(0)', '#google-business-reviews-rating-settings').after(html);
	jQuery('button.notice-dismiss:eq(0)', '#google-business-reviews-rating-settings').on('click', function () {
		jQuery('#google-business-reviews-rating-settings-message').remove();
	});
	
	setTimeout(function() {
		if (jQuery('#google-business-reviews-rating-settings-message').length) {
			jQuery('#google-business-reviews-rating-settings-message').remove();
		}
	}, 15000);

	return;
}

function google_business_reviews_rating_preview() {
	if (!jQuery('#google-business-reviews-rating-setup').length || !jQuery('#review-limit').length || jQuery('#review-limit').length && !jQuery('#review-limit').is(':visible')) {
		return;
	}
	
	var name = null,
		data = {
			action: 'google_business_reviews_rating_admin_ajax',
			type: 'preview',
			limit: (jQuery('#review-limit').val().length && parseInt(jQuery('#review-limit').val()) >= 0) ? parseInt(jQuery('#review-limit').val()) : null,
			min: (jQuery('#rating-min').val().length && parseInt(jQuery('#rating-min').val()) >= 0 && parseInt(jQuery('#rating-min').val()) <= 5) ? parseInt(jQuery('#rating-min').val()) : null,
			max: (jQuery('#rating-max').val().length && parseInt(jQuery('#rating-max').val()) >= 0 && parseInt(jQuery('#rating-max').val()) <= 5) ? parseInt(jQuery('#rating-max').val()) : null,
			review_text_min: (jQuery('#review-text-min').val().length && parseInt(jQuery('#review-text-min').val()) >= 0) ? parseInt(jQuery('#review-text-min').val()) : null,
			review_text_max: (jQuery('#review-text-max').val().length && parseInt(jQuery('#review-text-max').val()) >= 0) ? parseInt(jQuery('#review-text-max').val()) : null,
			theme: (jQuery('#reviews-theme').val().length && jQuery('#reviews-theme').val().match(/^[\w ]+$/) && jQuery('#reviews-theme').val() != 'light') ? jQuery('#reviews-theme').val() : null,
			stylesheet: (jQuery('#stylesheet').is(':hidden') || jQuery('#stylesheet').is(':checked')),
			sort: (jQuery('#review-sort').val().length && jQuery('#review-sort').val().match(/^[\w_]+$/)) ? jQuery('#review-sort').val() : null,
			excerpt: (jQuery('#review-text-excerpt-length').val().length && parseInt(jQuery('#review-text-excerpt-length').val()) >= 20) ? parseInt(jQuery('#review-text-excerpt-length').val()) : null
		};
		
	
	if (typeof data.min == 'number' && typeof data.max == 'number' && data.min > data.max) {
		data.min = data.max;
	}
	
	if (typeof data.review_text_min == 'number' && typeof data.review_text_max == 'number' && data.review_text_min > data.review_text_max) {
		data.review_text_min = data.review_text_max;
	}
	
	if (jQuery('#review-limit-hide').is(':checked')) {
		data.limit = 0;
	}
	else if (jQuery('#review-limit-all').is(':checked')) {
		data.limit = null;
	}
	
	jQuery.post(google_business_reviews_rating_admin_ajax.url, data, function(response) {
		if (response.success) {
			if (jQuery('#reviews-rating-preview-heading').hasClass('hide')) {
				if (window.outerWidth < 1450) {
					jQuery('#reviews-rating-preview-heading').slideDown(300, function() {
						jQuery('#reviews-rating-preview-heading').removeAttr('class').removeAttr('style');
					});
				}
				else {
					jQuery('#reviews-rating-preview-heading').removeAttr('class');
				}
			}
			
			if (jQuery('#reviews-rating-preview').hasClass('show')) {
				if (typeof data.theme == 'string' && data.theme.length) {
					jQuery('#reviews-rating-preview').prop('class', data.theme + ' show');
				}
				else {
					jQuery('#reviews-rating-preview').prop('class', 'show');
				}
			}
			else {
				if (typeof data.theme == 'string' && data.theme.length) {
					jQuery('#reviews-rating-preview').prop('class', data.theme);
				}
				else {
					jQuery('#reviews-rating-preview').removeAttr('class');
				}
			}
			
			jQuery('#reviews-rating-preview').html(response.html);
			
			if (typeof google_business_reviews_rating == 'function') {
				google_business_reviews_rating(jQuery('#reviews-rating-preview > div'));
			}
		}
		else {
			if (!jQuery('#reviews-rating-preview-heading').hasClass('hide')) {
				jQuery('#reviews-rating-preview-heading').addClass('class');
			}
			
			if (!jQuery('#reviews-rating-preview').hasClass('hide')) {
				jQuery('#reviews-rating-preview').addClass('class');
			}
		}
	}, 'json');

	return;
}

function google_business_reviews_rating_media_image(image_type) {
	var data = {},
		image_id = null
		image_frame = null,
		selection = null,
		gallery_ids = new Array(),
		my_index = 0;
	
	jQuery('#' + image_type + '-image-delete').on('click', function(event) {
		data = {
			'action': 'google_business_reviews_rating_admin_ajax',
			'type': image_type + '_delete'
		};
	
		jQuery.post(google_business_reviews_rating_admin_ajax.url, data, function(response) {
			if (response.success) {
				jQuery('#' + image_type + '-image-id').val('');
				jQuery('img', '#' + image_type + '-image-preview').remove();
				jQuery('#' + image_type + '-image-preview').html('');
				jQuery('#' + image_type + '-image').html(jQuery('.dashicons', '#' + image_type + '-image')[0].outerHTML + ' ' + jQuery('#' + image_type + '-image').data('set-text'));
				jQuery('.' + image_type + '-image:eq(0)').addClass('empty');
				jQuery('.delete', '.' + image_type + '-image:eq(0)').hide();
				jQuery('#' + image_type + '-image-row').addClass('empty');
				google_business_reviews_rating_preview();
			}
		}, 'json');
		
		return;		
	});

	jQuery('#' + image_type + '-image, #' + image_type + '-image-preview').on('click', function(event) {
		event.preventDefault();
		
		if (typeof wp == 'undefined') {
			return;
		}
				
		if (image_frame) {
			image_frame.open();
		}
		
		image_frame = wp.media({
			title: 'Select Media',
			multiple: false,
			library: {
				type: 'image',
			}
		});
		
		image_frame.on('close', function() {
			selection = image_frame.state().get('selection');
			gallery_ids = new Array();
			my_index = 0;
			
			selection.each(function(attachment) {
				gallery_ids[my_index] = attachment['id'];
				my_index++;
			});
			
			image_id = gallery_ids.join(",");
			jQuery('#' + image_type + '-image-id').val(image_id);
			
			data = {
				'action': 'google_business_reviews_rating_admin_ajax',
				'type': image_type,
				'id': image_id
			};
			
			jQuery.post(google_business_reviews_rating_admin_ajax.url, data, function(response) {
				if (response.success) {
					jQuery('#' + image_type + '-image-row').removeClass('empty');
					jQuery('.' + image_type + '-image.empty').removeClass('empty');
					jQuery('#' + image_type + '-image-preview')
						.html(response.image)
						.addClass('image');
					jQuery('#' + image_type + '-image').html(jQuery('.dashicons', '#' + image_type + '-image')[0].outerHTML + ' ' + jQuery('#' + image_type + '-image').data('replace-text'));
					jQuery('.delete', '.' + image_type + '-image:eq(0)').css('display', 'inline-block');
					google_business_reviews_rating_preview();
				}
			}, 'json');
		});
		
		image_frame.on('open', function() {
			var selection = image_frame.state()
				.get('selection'),
				ids = jQuery('#' + image_type + '-image-id').val().split(',');
				
			ids.forEach(function(id) {
				var attachment = wp.media.attachment(id);
				attachment.fetch();
				selection.add(attachment ? [attachment] : []);
			});
		});
		
		image_frame.open();
	});
	
	return;
}

function google_business_reviews_rating_syntax_highlight(e) {
	if (typeof e == 'undefined') {
		var e = jQuery('#google-business-reviews-rating-data');
	}
	
	if (!jQuery(e).length || jQuery('span', jQuery(e)).length) {
		return;
	}
	
	var json = e
		.html()
		.replace(/&/g, '&amp;')
		.replace(/</g, '&lt;')
		.replace(/>/g, '&gt;');

	jQuery(e)
		.html(json.replace(/("(\\u[a-zA-Z0-9]{4}|\\[^u]|[^\\"])*"(\s*:)?|\b(true|false|null)\b|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?)/g, function(match) {
			var class_name = 'number';
			if (/^"/.test(match)) {
				if (/:$/.test(match)) {
					class_name = 'key';
				}
				else {
					class_name = 'string';
				}
			}
			else if (/true|false/.test(match)) {
				class_name = 'boolean';
			}
			else if (/null/.test(match)) {
				class_name = 'null';
			}
			return '<span class="' + class_name + '">' + match + '</span>';
		}));
		
	if (jQuery(e).attr('id').match(/structured[_-]?data/i)) {
		jQuery(e).html(jQuery(e).html().replace(/(<span\s+class="key">"image":<\/span>\s+<span\s+class="boolean)(">)(false)(<\/span>)/i, '$1 error$2$3 <span class="dashicons dashicons-warning" title="Required"></span>$4'));
	}
	
	return;
}

function google_business_reviews_rating_submitted(e) {	
	if (typeof e == 'undefined') {
		return;
	}
	
	var e = jQuery(e).closest('.review'),
		data = {
			'action': 'google_business_reviews_rating_admin_ajax',
			'type': 'submitted',
			'review': jQuery(e).attr('id').replace(/[^0-9a-z_]/gi, '_'),
			'submitted': jQuery('.time-estimate:input', e).val()
		};
	
	if (!jQuery(e).hasClass('estimate')) {
		return;
	}
	
	jQuery.post(google_business_reviews_rating_admin_ajax.url, data, function(response) {
		if (response.success) {
			// Updated
		}
	}, 'json');
	return;
}

function google_business_reviews_rating_status(e) {	
	if (typeof e == 'undefined') {
		return;
	}
	
	var e = jQuery(e).closest('.review'),
		data = {
			'action': 'google_business_reviews_rating_admin_ajax',
			'type': 'status',
			'review': jQuery(e).attr('id').replace(/[^0-9a-z_]/gi, '_'),
			'status': jQuery(e).hasClass('inactive')
		};
	
	jQuery.post(google_business_reviews_rating_admin_ajax.url, data, function(response) {
		if (response.success) {
			if (data.status) {
				jQuery(e).removeClass('inactive');
				jQuery('.show-hide .dashicons', e).removeClass('dashicons-hidden').addClass('dashicons-visibility');
				jQuery('.show-hide', e).prop('title', 'Hide');
			}
			else {
				jQuery(e).addClass('inactive');
				jQuery('.show-hide .dashicons', e).removeClass('dashicons-visibility').addClass('dashicons-hidden');
				jQuery('.show-hide', e).prop('title', 'Show');
			}
		}
	}, 'json');
	return;
}

function google_business_reviews_rating_remove(e) {	
	if (typeof e == 'undefined') {
		return;
	}
	
	var e = jQuery(e).closest('.review'),
		data = {
			'action': 'google_business_reviews_rating_admin_ajax',
			'type': 'delete',
			'review': jQuery(e).attr('id').replace(/[^0-9a-z_]/gi, '_')
		};
	
	if (!jQuery(e).hasClass('estimate')) {
		return;
	}

	jQuery.post(google_business_reviews_rating_admin_ajax.url, data, function(response) {
		if (response.success) {
			jQuery(e).remove();
		}
	}, 'json');
	return;
}


jQuery(document).ready(function($) {
	google_business_reviews_rating_admin();
	if (window.history && window.history.pushState) {
		jQuery(window).on('popstate', function() {
			google_business_reviews_rating_admin(true);
		});
	}
});

jQuery(window).bind('keydown', function(event) {
    if (jQuery('.button-primary').is(':visible') && (event.ctrlKey || event.metaKey)) {
        if (String.fromCharCode(event.which).toLowerCase() == 's') {
            event.preventDefault();
			jQuery('.button-primary:visible:eq(0)').trigger('click');
			return false;
        }
    }
});
