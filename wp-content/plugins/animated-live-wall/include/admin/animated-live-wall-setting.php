<?php
//CSS
wp_enqueue_script('jquery');
wp_enqueue_script('alw-color-picker-js', ALW_PLUGIN_URL .'assets/js/alw-color-picker.js', array( 'jquery', 'wp-color-picker' ), '', true);

wp_enqueue_style('alw-metabox-css', ALW_PLUGIN_URL . 'assets/css/metabox.css');
wp_enqueue_style('wp-color-picker');

$alw_get_settings = get_post_meta( $post->ID, 'awl_animated_live_wall'.$post->ID, true);
?>
<div class="row pw_gallery_genrate">
	<?php if(isset($alw_get_settings['alw_gallery_wall'])) $alw_gallery_wall = $alw_get_settings['alw_gallery_wall']; else $alw_gallery_wall = "photo_wall"; ?>
	<input type="radio" id="photo_wall" name="alw_gallery_wall" value="photo_wall" <?php if($alw_gallery_wall == "photo_wall") echo "checked='checked'"; ?> style="display:none;">
	<label for="photo_wall">
		<div class="col-lg-4 pw_gallery_tab">
			<div class="card photo_wall">
				<div class="card-body text-center">
					<div class="m-b-20 m-t-10">
						<!--<img src="<?php //echo PW_PLUGIN_URL ?>include/images/mosaic.jpg" alt="Income" style="width:7%";>-->
						<span class="dashicons dashicons-layout" style="width:7%; margin-top:9px; lign-height:0;" ></span>
					</div>
					<span class="text-white display-4"><?php _e('Animated Wall', ALW_TXTDM); ?></span>
				</div>
			</div>
		</div>
	</label>
	<input type="radio" id="insta_wall" name="alw_gallery_wall" value="insta_wall" <?php if($alw_gallery_wall == "insta_wall") echo "checked='checked'"; ?> style="display:none;">
	<label for="insta_wall">
		<div class="col-lg-4 pw_gallery_tab">
			<div class="card insta_wall">
				<div class="card-body text-center">
					<div class="m-b-20 m-t-10">
						<img src="<?php echo ALW_PLUGIN_URL ?>include/images/Instagram_icon.png" style="width:7%";>
					</div>
					<span class="text-white display-4"><?php _e('Insta Feed', ALW_TXTDM); ?></span>
				</div>
			</div>
		</div>
	</label>
	<input type="radio" id="flickr_wall" name="alw_gallery_wall" value="flickr_wall" <?php if($alw_gallery_wall == "flickr_wall") echo "checked='checked'"; ?> style="display:none;">
	<label for="flickr_wall">
		<div class="col-lg-4 pw_gallery_tab">
			<div class="card flickr_wall">
				<div class="card-body text-center">
					<div class="m-b-20 m-t-10">
						<img src="<?php echo ALW_PLUGIN_URL ?>include/images/flickr-logo-png-3.png" alt="Income" style="width:9%";>
					</div>
					<span class="text-white display-4"><?php _e('Flickr Feed', ALW_TXTDM); ?></span>
				</div>
			</div>
		</div>
	</label>
</div>
<div class="row gallery-content-photo-wall">
	<!--Add New Image Button-->
	<div class="file-upload">
		<div class="image-upload-wrap">
			<input class="add-new-images file-upload-input" id="upload_image_button" name="upload_image_button" value="Upload Image" />
			<div class="drag-text">
				<h3><?php _e('ADD IMAGES', ALW_TXTDM); ?></h3>
			</div>
		</div>
	</div>
</div>
<div class="row text-center gallery-content-insta-wall">
	<div class="file-upload">
		<h1><?php _e('WordPress Instagram Feed', ALW_TXTDM); ?></h1>
		<p><?php _e('Show Instagram images on your WordPress website', ALW_TXTDM); ?></p>
	</div>
</div>
<div class="row text-center gallery-content-flickr-wall">
	<div class="file-upload">
		<h1><?php _e('WordPress Flickr Feed', ALW_TXTDM); ?></h1>
		<p><?php _e('Show Flickr images on your WordPress website', ALW_TXTDM); ?></p>
	</div>
</div>
<div class="row">
	<div class="col-lg-12 bhoechie-tab-container">
		<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 bhoechie-tab-menu">
			<div class="list-group">
				<a href="#" class="list-group-item active text-center">
					<span class="dashicons dashicons-format-image"></span><br/><?php _e('Photos', ALW_TXTDM); ?>
				</a>
				<a href="#" class="list-group-item text-center">
					<span class="dashicons dashicons-layout"></span><br/><?php _e('Layouts', ALW_TXTDM); ?>
				</a>
				<a href="#" class="list-group-item text-center">
					<span class="dashicons dashicons-admin-generic"></span><br/><?php _e('Config', ALW_TXTDM); ?>
				</a>
				<a href="#" class="list-group-item text-center">
					<span class="dashicons dashicons-welcome-view-site"></span><br/><?php _e('LightBox', ALW_TXTDM); ?>
				</a>
				<a href="#" class="list-group-item text-center">
					<span class="dashicons dashicons-media-code"></span><br/><?php _e('Custom CSS', ALW_TXTDM); ?>
				</a>
				<a href="#" class="list-group-item text-center">
					<span class="dashicons dashicons-unlock"></span><br/><?php _e('Upgrade Pro', ALW_TXTDM); ?>
				</a>
			</div>
		</div>
		<div class="col-lg-10 col-md-10 col-sm-10 col-xs-10 bhoechie-tab">
			<!-- flight section -->
			<div class="bhoechie-tab-content active">
			<h1><?php _e('Photos', ALW_TXTDM); ?></h1>
				<hr>
				<!--Photos from wordpress-->
				<div id="image-gallery">
					<input type="button" id="remove-all-images" name="remove-all-images" class="button button-large remove-all-images" rel="" value="<?php _e('Delete All Images', ALW_TXTDM); ?>">
					<ul id="remove-images" class="sbox">
						<?php
							if(isset($alw_get_settings['image-ids'])) {								
								$count = 0;
								foreach($alw_get_settings['image-ids'] as $id) {
								$thumbnail = wp_get_attachment_image_src($id, 'medium', true);
								$attachment = get_post( $id );
								$image_link = $alw_get_settings['image-link'][$count];
								?>
								<li class="item image col-lg-2 col-md-3 col-sm-6 col-xs-12">
									<img class="new-image" src="<?php echo $thumbnail[0]; ?>" alt="<?php echo get_the_title($id); ?>" style="height: 150px;">
									<div class="item-overlay bottom label label-info" style="opacity:0; position:absolute; color: #fff; background-color:#5bc0de; padding:2px;">ID-<?php echo $id; ?></div>
									<input type="hidden" id="image-ids[]" name="image-ids[]" value="<?php echo $id; ?>" />
									<input type="text" name="image-title[]" id="image-title[]" placeholder="Image Title" value="<?php echo get_the_title($id); ?>">
									<input type="text" name="image-link[]" id="image-link[]" value="<?php echo $image_link; ?>" placeholder="Video URL / Link URL">
									<!--<a href="<?php //echo wp_get_attachment_url( get_post_thumbnail_id($post->ID) ); ?>" class="attachments pw-edit-icon" href="#"><span class="dashicons dashicons-edit"></span></a>-->
									<a class="pw-trash-icon" name="remove-image" id="remove-image" href="#"><span class="dashicons dashicons-trash"></span></a>
								</li>
							<?php $count++; } // end of foreach
							} //end of if
						?>
					</ul>
				</div>
				
				<!--///////=============//////////-->
				<!--Photos from Instagram-->
				<div id="instaram-gallery">
					<div class="row">
						<div class="col-md-4">
							<div class="ma_field_discription">
								<h4><?php _e('Access Token', ALW_TXTDM); ?></h4>
								<p><?php _e('Enter access token to add the Instagram feed. You can generate access token easily from here', ALW_TXTDM); ?> <a target="_blank" href="https://awplife.com/instagram-access-token-generator/"><?php _e('pixelunion', ALW_TXTDM); ?></a></p> 
							</div>
						</div>
						<div class="col-md-8">
							<div class="ma_field panel-body">
								<?php if(isset($alw_get_settings['alw_instagram_token'])) $alw_instagram_token = $alw_get_settings['alw_instagram_token']; else $alw_instagram_token = "10925106910.1677ed0.ee317ab70ae04d60bfdaf95e6f934cf3"; ?>
								<textarea class="form-control" id="alw_instagram_token" name="alw_instagram_token"><?php echo $alw_instagram_token; ?></textarea>
							</div>
						</div>
					</div>
				</div>
				
				<!--///////=============//////////-->
				<!--Photos from Flickr-->
				<div id="flickr-gallery">
					<div class="row">
						<div class="col-md-4">
							<div class="ma_field_discription">
								<h4><?php _e('Flickr API Key', ALW_TXTDM); ?></h4>
								<p><?php _e('Enter Flickr API Key to add the Flickr feed. how to get your API Key - ', ALW_TXTDM); ?><a target="_blank" href="http://awplife.com/how-to-get-your-api-key-of-flickr/"><?php _e('API Key', ALW_TXTDM); ?></a></p> 
							</div>
						</div>
						<div class="col-md-8">
							<div class="ma_field panel-body">
								<?php if(isset($alw_get_settings['alw_flickr_api_key'])) $alw_flickr_api_key = $alw_get_settings['alw_flickr_api_key']; else $alw_flickr_api_key = "4405cbae4b35b98f14f5e839c6e03599"; ?>
								<textarea class="form-control" id="alw_flickr_api_key" name="alw_flickr_api_key"><?php echo $alw_flickr_api_key; ?></textarea>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="ma_field_discription">
								<h4><?php _e('Flickr User ID', ALW_TXTDM); ?></h4>
								<p><?php _e('Enter Flickr User ID how to get your Flickr User ID - ', ALW_TXTDM); ?><a target="_blank" href="http://awplife.com/how-to-get-your-user-id-of-flickr/"><?php _e('User ID', ALW_TXTDM); ?></a></p> 
							</div>
						</div>
						<div class="col-md-8">
							<div class="ma_field panel-body">
								<?php if(isset($alw_get_settings['alw_flickr_user_id'])) $alw_flickr_user_id = $alw_get_settings['alw_flickr_user_id']; else $alw_flickr_user_id = "147476924@N07"; ?>
								<input type="text" class="form-control" id="alw_flickr_user_id" name="alw_flickr_user_id" value="<?php echo $alw_flickr_user_id; ?>">
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- Gallery section -->
			<div class="bhoechie-tab-content">
				<h1><?php _e('Choose Gallery Type', ALW_TXTDM); ?></h1>
				<hr>
				<?php if(isset($alw_get_settings['enable_gallery_layout'])) $enable_gallery_layout = $alw_get_settings['enable_gallery_layout']; else $enable_gallery_layout = "grid"; ?>
				<div class="col-md-3">
					<input type="radio" name="enable_gallery_layout" id="enable_gallery_layout1" value="grid" <?php if($enable_gallery_layout == "grid") echo "checked=checked"; ?> >
					<label for="enable_gallery_layout1"><img class="gallery_layout_grid" style="width:70%" src="<?php echo ALW_PLUGIN_URL ?>include/images/grid.png"/>
					</label>
				</div>
				<div class="col-md-3">
					<input type="radio" name="enable_gallery_layout" id="enable_gallery_layout2" value="masonry" <?php if($enable_gallery_layout == "masonry") echo "checked=checked"; ?>>
					<label for="enable_gallery_layout2"><img class="gallery_layout_masonry" style="width:70%" src="<?php echo ALW_PLUGIN_URL ?>include/images/masonry.png"/></label>
				</div>
				<div class="col-md-3">
					<input type="radio" name="enable_gallery_layout_pro" id="enable_gallery_layout3" value="mosaic" <?php if($enable_gallery_layout == "mosaic") echo "checked=checked"; ?>>
					<label for="enable_gallery_layout3"><img class="gallery_layout_mosaic" style="width:70%" src="<?php echo ALW_PLUGIN_URL ?>include/images/mosaic.jpg"/><span class="awl_pro_layout dashicons dashicons-lock"></span></label>
					
				</div>
				<div class="col-md-3">
					<input type="radio" name="enable_gallery_layout_pro" id="enable_gallery_layout4" value="justify" <?php if($enable_gallery_layout == "justify") echo "checked=checked"; ?>>
					<label for="enable_gallery_layout4"><img class="gallery_layout_justify" style="width:70%" src="<?php echo ALW_PLUGIN_URL ?>include/images/justify.png"/><span class="awl_pro_layout dashicons dashicons-lock"></span></label>
				</div>
			</div>

			<!-- Configuration -->
			<div class="bhoechie-tab-content">
				<h1><?php _e('Configuration', ALW_TXTDM); ?> [ <?php _e('Layout - ', ALW_TXTDM); ?> <?php echo ucfirst($enable_gallery_layout); ?> ]</h1>
				<hr>
				<!--Grid-->
				<div class="pw_grid_layout_config">
					<div class="row">
						<div class="col-md-4">
							<div class="ma_field_discription">
								<h4> <?php _e('Rows', ALW_TXTDM); ?></h4>
								<p> <?php _e('Choose to show rows for ( grid layout )', ALW_TXTDM); ?></p> 
							</div>
						</div>
						<div class="col-md-8">
							<div class="ma_field panel-body">
								<?php if(isset($alw_get_settings['alw_grid_rows'])) $alw_grid_rows = $alw_get_settings['alw_grid_rows']; else $alw_grid_rows = "3"; ?>
								<select id="alw_grid_rows" name="alw_grid_rows" class="form-control">
									<option value="1" <?php if($alw_grid_rows == "1") echo "selected=selected"; ?>> <?php _e('1', ALW_TXTDM); ?></option>
									<option value="2" <?php if($alw_grid_rows == "2") echo "selected=selected"; ?>> <?php _e('2', ALW_TXTDM); ?></option>
									<option value="3" <?php if($alw_grid_rows == "3") echo "selected=selected"; ?>> <?php _e('3', ALW_TXTDM); ?></option>
									<option value="4" <?php if($alw_grid_rows == "4") echo "selected=selected"; ?>> <?php _e('4', ALW_TXTDM); ?></option>
									<option value="5" <?php if($alw_grid_rows == "5") echo "selected=selected"; ?>> <?php _e('5', ALW_TXTDM); ?></option>
								</select>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="ma_field_discription">
								<h4> <?php _e('Columns', ALW_TXTDM); ?></h4>
								<p> <?php _e('Choose to show column for ( grid layout ) Range  1 to 5', ALW_TXTDM); ?></p> 
							</div>
						</div>
						<div class="col-md-8">
							<div class="ma_field panel-body">
								<?php if(isset($alw_get_settings['alw_grid_columns'])) $alw_grid_columns = $alw_get_settings['alw_grid_columns']; else $alw_grid_columns = "5"; ?>
								<select id="alw_grid_columns" name="alw_grid_columns" class="form-control">
									<option value="1" <?php if($alw_grid_columns == "1") echo "selected=selected"; ?>> <?php _e('1', ALW_TXTDM); ?></option>
									<option value="2" <?php if($alw_grid_columns == "2") echo "selected=selected"; ?>> <?php _e('2', ALW_TXTDM); ?></option>
									<option value="3" <?php if($alw_grid_columns == "3") echo "selected=selected"; ?>> <?php _e('3', ALW_TXTDM); ?></option>
									<option value="4" <?php if($alw_grid_columns == "4") echo "selected=selected"; ?>> <?php _e('4', ALW_TXTDM); ?></option>
									<option value="5" <?php if($alw_grid_columns == "5") echo "selected=selected"; ?>> <?php _e('5', ALW_TXTDM); ?></option>
								</select>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="ma_field_discription">
								<h4> <?php _e('Thumbnail Size', ALW_TXTDM); ?></h4>
								<p> <?php _e('Choose thumbnail / image size for gallery', ALW_TXTDM); ?></p> 
							</div>
						</div>
						<div class="col-md-8">
							<div class="ma_field panel-body">
								<?php if(isset($alw_get_settings['alw_grid_thumb_size'])) $alw_grid_thumb_size = $alw_get_settings['alw_grid_thumb_size']; else $alw_grid_thumb_size = "full"; ?>
								<select id="alw_grid_thumb_size" name="alw_grid_thumb_size" class="form-control">
									<option value="thumbnail" <?php if($alw_grid_thumb_size == "thumbnail") echo "selected=selected"; ?>> <?php _e('Thumbnail', ALW_TXTDM); ?></option>
									<option value="medium" <?php if($alw_grid_thumb_size == "medium") echo "selected=selected"; ?>> <?php _e('Medium', ALW_TXTDM); ?></option>
									<option value="large" <?php if($alw_grid_thumb_size == "large") echo "selected=selected"; ?>> <?php _e('Large', ALW_TXTDM); ?></option>
									<option value="full" <?php if($alw_grid_thumb_size == "full") echo "selected=selected"; ?>> <?php _e('Full', ALW_TXTDM); ?></option>
								</select>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="ma_field_discription">
								<h4><?php _e('Stop Animation', ALW_TXTDM); ?></h4>
								<p> <?php _e('Set gap between photos / images', ALW_TXTDM); ?></p> 
							</div>
						</div>
						<div class="col-md-8">
							<div class="ma_field panel-body">
								<div class="switch-field em_size_field">
									<?php if(isset($alw_get_settings['alw_grid_stop_anim'])) $alw_grid_stop_anim = $alw_get_settings['alw_grid_stop_anim']; else $alw_grid_stop_anim = "no"; ?>
									<input type="radio" name="alw_grid_stop_anim" id="alw_grid_stop_anim1" value="yes" <?php if($alw_grid_stop_anim == "yes") echo "checked='checked'"; ?> >
									<label for="alw_grid_stop_anim1"><?php _e('Yes', ALW_TXTDM); ?></label>
									<input type="radio" name="alw_grid_stop_anim" id="alw_grid_stop_anim2" value="no" <?php if($alw_grid_stop_anim == "no") echo "checked='checked'"; ?>>
									<label for="alw_grid_stop_anim2"><?php _e('No', ALW_TXTDM); ?></label>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="ma_field_discription">
								<h4> <?php _e('Live Animation Type', ALW_TXTDM); ?></h4>
								<p> <?php _e('Choose animation type ( grid layout )', ALW_TXTDM); ?></p> 
							</div>
						</div>
						<div class="col-md-8">
							<div class="ma_field panel-body">
								<?php if(isset($alw_get_settings['alw_grid_animation'])) $alw_grid_animation = $alw_get_settings['alw_grid_animation']; else $alw_grid_animation = "fadeInOut"; ?>
								<select id="alw_grid_animation" name="alw_grid_animation" class="form-control">
									<option value="showHide" <?php if($alw_grid_animation == "showHide") echo "selected=selected"; ?>> <?php _e('showHide', ALW_TXTDM); ?></option>
									<option value="fadeInOut" <?php if($alw_grid_animation == "fadeInOut") echo "selected=selected"; ?>> <?php _e('fadeInOut', ALW_TXTDM); ?></option>
									<option value="scale" <?php if($alw_grid_animation == "scale") echo "selected=selected"; ?>> <?php _e('scale', ALW_TXTDM); ?></option>
								</select>
							</div>
						</div>
					</div>
					
					<div class="row">
						<div class="col-md-4">
							<div class="ma_field_discription">
								<h4><?php _e('Gap Between Photos', ALW_TXTDM); ?></h4>
								<p> <?php _e('Set gap between photos / images', ALW_TXTDM); ?></p> 
							</div>
						</div>
						<div class="col-md-8">
							<div class="ma_field panel-body">
								<div class="switch-field em_size_field">
									<?php if(isset($alw_get_settings['alw_grid_gap'])) $alw_grid_gap = $alw_get_settings['alw_grid_gap']; else $alw_grid_gap = "0"; ?>
									<input type="radio" name="alw_grid_gap" id="alw_grid_gap1" value="15" <?php if($alw_grid_gap == "15") echo "checked='checked'"; ?> >
									<label for="alw_grid_gap1"><?php _e('Yes', ALW_TXTDM); ?></label>
									<input type="radio" name="alw_grid_gap" id="alw_grid_gap2" value="0" <?php if($alw_grid_gap == "0") echo "checked='checked'"; ?>>
									<label for="alw_grid_gap2"><?php _e('No', ALW_TXTDM); ?></label>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="ma_field_discription">
								<h4><?php _e('Image Redirection Link', ALW_TXTDM); ?></h4>
								<p><?php _e('Choose option for image redirection link', ALW_TXTDM); ?></p> 
							</div>
						</div>
						<div class="col-md-8">
							<div class="ma_field panel-body">
								<div class="switch-field em_size_field">
									<?php if(isset($alw_get_settings['alw_img_redirection'])) $alw_img_redirection = $alw_get_settings['alw_img_redirection']; else $alw_img_redirection = "_new"; ?>
									<input type="radio" name="alw_img_redirection" id="alw_img_redirection1" value="_self" <?php if($alw_img_redirection == "_self") echo "checked='checked'"; ?> >
									<label for="alw_img_redirection1"><?php _e('Same Tab', ALW_TXTDM); ?></label>
									<input type="radio" name="alw_img_redirection" id="alw_img_redirection2" value="_new" <?php if($alw_img_redirection == "_new") echo "checked='checked'"; ?>>
									<label for="alw_img_redirection2"><?php _e('New Tab', ALW_TXTDM); ?></label>
								</div>
							</div>
						</div>
					</div>
				</div>
				
				<!--Masonry Mosaic Justify-->
				<div class="pw_masonry_mosaic_justify_layout_config">
					<div class="row">
						<div class="col-md-4">
							<div class="ma_field_discription">
								<h4><?php _e('Colums', ALW_TXTDM); ?></h4>
								<p><?php _e('Choose Columns Settings Small colmns, or Large Column', ALW_TXTDM); ?> </p> 
							</div>
						</div>
						<div class="col-md-8">
							<div class="ma_field panel-body">
								<?php if(isset($alw_get_settings['column_setting'])) $column_setting = $alw_get_settings['column_setting']; else $column_setting = ""; ?>
								<select id="column_setting" name="column_setting" class="form-control">
									<option value="small" <?php if($column_setting == "small") echo "selected=selected"; ?>><?php _e('Small Columns XS', ALW_TXTDM); ?></option>
									<option value="large" <?php if($column_setting == "large") echo "selected=selected"; ?>><?php _e('Large Columns LG', ALW_TXTDM); ?></option>
								</select>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="ma_field_discription">
								<h4> <?php _e('Thumbnail Size', ALW_TXTDM); ?></h4>
								<p> <?php _e('Choose thumbnail / image size for gallery', ALW_TXTDM); ?></p> 
							</div>
						</div>
						<div class="col-md-8">
							<div class="ma_field panel-body">
								<?php if(isset($alw_get_settings['alw_thumb_size'])) $alw_thumb_size = $alw_get_settings['alw_thumb_size']; else $alw_thumb_size = "medium"; ?>
								<select id="alw_thumb_size" name="alw_thumb_size" class="form-control">
									<option value="thumbnail" <?php if($alw_thumb_size == "thumbnail") echo "selected=selected"; ?>><?php _e('Thumbnail', ALW_TXTDM); ?></option>
									<option value="medium" <?php if($alw_thumb_size == "medium") echo "selected=selected"; ?>><?php _e('Medium', ALW_TXTDM); ?></option>
									<option value="large" <?php if($alw_thumb_size == "large") echo "selected=selected"; ?>><?php _e('Large', ALW_TXTDM); ?></option>
									<option value="full" <?php if($alw_thumb_size == "full") echo "selected=selected"; ?>><?php _e('Full', ALW_TXTDM); ?></option>
								</select>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="ma_field_discription">
								<h4><?php _e('Gap Between Photos', ALW_TXTDM); ?></h4>
								<p><?php _e('Set gap between photos / images Range  0 to 15', ALW_TXTDM); ?></p> 
							</div>
						</div>
						<div class="col-md-8">
							<div class="ma_field panel-body">
								<?php if(isset($alw_get_settings['alw_images_gap'])) $alw_images_gap = $alw_get_settings['alw_images_gap']; else $alw_images_gap = "15"; ?>
								<div class="switch-field em_size_field">
									<?php if(isset($alw_get_settings['alw_images_gap'])) $alw_images_gap = $alw_get_settings['alw_images_gap']; else $alw_images_gap = "0"; ?>
									<input type="radio" name="alw_images_gap" id="alw_images_gap1" value="15" <?php if($alw_images_gap == "15") echo "checked='checked'"; ?> >
									<label for="alw_images_gap1"><?php _e('Yes', ALW_TXTDM); ?></label>
									<input type="radio" name="alw_images_gap" id="alw_images_gap2" value="0" <?php if($alw_images_gap == "0") echo "checked='checked'"; ?>>
									<label for="alw_images_gap2"><?php _e('No', ALW_TXTDM); ?></label>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="ma_field_discription">
								<h4><?php _e('Image Redirection Link', ALW_TXTDM); ?></h4>
								<p><?php _e('Choose option for image redirection link', ALW_TXTDM); ?></p> 
							</div>
						</div>
						<div class="col-md-8">
							<div class="ma_field panel-body">
								<div class="switch-field em_size_field">
									<?php if(isset($alw_get_settings['alw_maso_img_redirection'])) $alw_maso_img_redirection = $alw_get_settings['alw_maso_img_redirection']; else $alw_maso_img_redirection = "_new"; ?>
									<input type="radio" name="alw_maso_img_redirection" id="alw_maso_img_redirection1" value="_self" <?php if($alw_maso_img_redirection == "_self") echo "checked='checked'"; ?> >
									<label for="alw_maso_img_redirection1"><?php _e('Same Tab', ALW_TXTDM); ?></label>
									<input type="radio" name="alw_maso_img_redirection" id="alw_maso_img_redirection2" value="_new" <?php if($alw_maso_img_redirection == "_new") echo "checked='checked'"; ?>>
									<label for="alw_maso_img_redirection2"><?php _e('New Tab', ALW_TXTDM); ?></label>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!--Instagram Configuration-->
				<br>
			</div>
			<div class="bhoechie-tab-content">
				<h1><?php _e('LightBox Configuration', ALW_TXTDM); ?></h1>
				<hr>
				<div class="row">
					<div class="col-md-4">
						<div class="ma_field_discription">
							<h4><?php _e('Enable Lightbox', ALW_TXTDM); ?></h4>
							<p><?php _e('Enable or desable lightbox for gallery', ALW_TXTDM); ?> </p> 
						</div>
					</div>
					<div class="col-md-8">
						<div class="ma_field panel-body">
							<div class="switch-field em_size_field">
								<?php if(isset($alw_get_settings['alw_lightbox'])) $alw_lightbox = $alw_get_settings['alw_lightbox']; else $alw_lightbox = "true"; ?>
								<input type="radio" name="alw_lightbox" id="alw_lightbox1" value="true" <?php if($alw_lightbox == "true") echo "checked='checked'"; ?> >
								<label for="alw_lightbox1"><?php _e('Yes', ALW_TXTDM); ?></label>
								<input type="radio" name="alw_lightbox" id="alw_lightbox2" value="false" <?php if($alw_lightbox == "false") echo "checked='checked'"; ?>>
								<label for="alw_lightbox2"><?php _e('No', ALW_TXTDM); ?></label>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="bhoechie-tab-content">
				<h1><?php _e('Custum CSS', ALW_TXTDM); ?> </h1>
				<hr>
				<div class="row">
					<div class="col-md-4">
						<div class="ma_field_discription">
							<h4><?php _e('Custum CSS', ALW_TXTDM); ?></h4>
							<p><?php _e('Apply your own custum CSS. Don"t use style tag', ALW_TXTDM); ?></p> 
						</div>
					</div>
					<div class="col-md-8">
						<div class="ma_field panel-body">
							<?php if(isset($alw_get_settings['alw_custum_css'])) $alw_custum_css = $alw_get_settings['alw_custum_css']; else $alw_custum_css = ""; ?>
							<textarea class="form-control" rows="12" id="alw_custum_css" name="alw_custum_css"><?php echo $alw_custum_css; ?></textarea>
						</div>
					</div>
				</div>
			</div>
			<div class="bhoechie-tab-content">
				<h1><?php _e('Upgrade To Pro', ALW_TXTDM); ?> </h1>
				<hr>
				<div class="row text-center" style="margin-bottom:50px;">
				
					<a href="https://awplife.com/account/signup/animated-live-wall-premium" target="_blank" class="button button-primary button-hero load-customize hide-if-no-customize">Buy Premium Version</a>
					<a href="http://awplife.com/demo/animated-live-wall-premium" target="_blank" class="button button-primary button-hero load-customize hide-if-no-customize">Check Live Demo</a>
					<a href="http://awplife.com/demo/animated-live-wall-premium" target="_blank" class="button button-primary button-hero load-customize hide-if-no-customize">Try Admin Demo</a>
				
				</div>
				<div class="row">
					<div class="col-sm-6">
						<div class="shadow-lg"><a target="_new" href="https://awplife.com/demo/animated-live-wall-premium/5-2/"><img class="img-responsive alignnone" src="https://awplife.com/demo/photo-wall-premium/wp-content/uploads/sites/66/2019/01/grid.jpg" alt=""></a></div>
					</div>
					<div class="col-sm-6">
						<div class="shadow-lg"><a target="_new" href="https://awplife.com/demo/animated-live-wall-premium/mosaic/"><img class="img-responsive alignnone" src="https://awplife.com/demo/photo-wall-premium/wp-content/uploads/sites/66/2019/01/mosaic.jpg" alt="" ></a></div>
					</div>
				</div>
				<div class="row" style="margin-top: 70px;">
					<div class="col-sm-6">
						<div class="shadow-lg"><a target="_new" href="https://awplife.com/demo/animated-live-wall-premium/justify/"><img class="img-responsive alignnone" src="https://awplife.com/demo/photo-wall-premium/wp-content/uploads/sites/66/2019/01/justify.jpg" alt="" ></a></div>
					</div>
					<div class="col-sm-6">
						<div class="shadow-lg"><a target="_new" href="https://awplife.com/demo/animated-live-wall-premium/masonry/"><img class="img-responsive alignnone" src="https://awplife.com/demo/photo-wall-premium/wp-content/uploads/sites/66/2019/01/masonry.jpg" alt="" ></a></div>
					</div>
				</div>
				<div class="row" style="margin-top: 70px;">
					<div class="col-sm-6">
						<div class="shadow-lg"><a target="_new" href="https://awplife.com/demo/animated-live-wall-premium/instagram/"><img class="img-responsive alignleft wp-image-235 size-full" src="https://awplife.com/demo/photo-wall-premium/wp-content/uploads/sites/66/2019/02/instagram-feed.png" alt="" ></a></div>
					</div>
					<div class="col-sm-6">
						<div class="shadow-lg"><a target="_new" href="https://awplife.com/demo/animated-live-wall-premium/flickr-feed/"><img class="img-responsive alignnone" src="https://awplife.com/demo/animated-live-wall-premium/wp-content/uploads/sites/66/2019/02/flickr-feed.png" alt="" ></a></div>
					</div>
				</div>
		
			</div>
		</div>
	</div>
</div>	  
<?php 
	// syntax: wp_nonce_field( 'name_of_my_action', 'name_of_nonce_field' );
	wp_nonce_field( 'alw_save_settings', 'alw_save_nonce' );
?>
<script>
var alw_gallery_wall = jQuery('[name=alw_gallery_wall]:checked').val();
if(alw_gallery_wall == 'photo_wall') {
	jQuery('.photo_wall').addClass("tab-active");
	jQuery("div.insta_wall").removeClass("tab-active");
	jQuery("div.flickr_wall").removeClass("tab-active");
	jQuery('.gallery-content-photo-wall').css("display", "block");
	jQuery('.gallery-content-insta-wall').css("display", "none");
	jQuery('.gallery-content-flickr-wall').css("display", "none");
	// upload photos change
	jQuery('#image-gallery').css("display", "block");
	jQuery('#instaram-gallery').css("display", "none");
	jQuery('#flickr-gallery').css("display", "none");
	//instagram configration 
	jQuery('#instagram-configration').css("display", "none");
}

if(alw_gallery_wall == 'insta_wall') {
	jQuery('.insta_wall').addClass("tab-active");
	jQuery("div.photo_wall").removeClass("tab-active");
	jQuery("div.flickr_wall").removeClass("tab-active");
	 jQuery('.gallery-content-photo-wall').css("display", "none");
	jQuery('.gallery-content-insta-wall').css("display", "block");
	jQuery('.gallery-content-flickr-wall').css("display", "none");
	// upload photos change
	jQuery('#image-gallery').css("display", "none");
	jQuery('#instaram-gallery').css("display", "block");
	jQuery('#flickr-gallery').css("display", "none");
	//instagram configration 
	jQuery('#instagram-configration').css("display", "block");
}
	
if(alw_gallery_wall == 'flickr_wall') {
	jQuery('.flickr_wall').addClass("tab-active");
	jQuery("div.photo_wall").removeClass("tab-active");
	jQuery("div.insta_wall").removeClass("tab-active");
	jQuery('.gallery-content-photo-wall').css("display", "none");
	jQuery('.gallery-content-insta-wall').css("display", "none");
	jQuery('.gallery-content-flickr-wall').css("display", "block");
	// upload photos change
	jQuery('#image-gallery').css("display", "none");
	jQuery('#instaram-gallery').css("display", "none");
	jQuery('#flickr-gallery').css("display", "block");
	//instagram configration 
	jQuery('#instagram-configration').css("display", "none");
}
	
var alwselectedlayout = jQuery('[name=enable_gallery_layout]:checked').val();
if(alwselectedlayout == 'grid') {
	jQuery('.gallery_layout_grid').addClass('gallery_layout'); 
	//hide show configuration setting according gallery layout
	jQuery('.pw_grid_layout_config').show(); 
	jQuery('.pw_masonry_mosaic_justify_layout_config').hide();
} else {
	jQuery('.gallery_layout_grid').removeClass('gallery_layout');
	//jQuery('.alw_masonry_mosaic_justify_layout_config').show(); 			
}
	
if(alwselectedlayout == 'masonry') {
	jQuery('.gallery_layout_masonry').addClass('gallery_layout'); 
	//hide show configuration setting according gallery layout
	jQuery('.pw_grid_layout_config').hide(); 
	jQuery('.pw_masonry_mosaic_justify_layout_config').show(); 
	
} else {
	jQuery('.gallery_layout_masonry').removeClass('gallery_layout'); 
}

/* if(alwselectedlayout == 'mosaic') {
	jQuery('.gallery_layout_mosaic').addClass('gallery_layout');
	//hide show configuration setting according gallery layout
	jQuery('.pw_grid_layout_config').hide(); 
	jQuery('.pw_masonry_mosaic_justify_layout_config').show(); 
} else {
	jQuery('.gallery_layout_mosaic').removeClass('gallery_layout'); 
}

if(alwselectedlayout == 'justify') {
	jQuery('.gallery_layout_justify').addClass('gallery_layout'); 
	//hide show configuration setting according gallery layout
	jQuery('.pw_grid_layout_config').hide(); 
	jQuery('.pw_masonry_mosaic_justify_layout_config').show(); 
} else {
	jQuery('.gallery_layout_justify').removeClass('gallery_layout'); 
} */

var alw_load_more = jQuery('[name=alw_load_more]:checked').val();
if(alw_load_more == 'yes') {
	jQuery('.load_limit').show();
} else {
	jQuery('.load_limit').hide();
}
	
var alw_gallery_wall = jQuery('[name=alw_gallery_wall]:checked').val();
if(alw_gallery_wall == 'photo_wall') {
	jQuery('.photo_wall').addClass("tab-active");
	jQuery("div.insta_wall").removeClass("tab-active");
	jQuery("div.flickr_wall").removeClass("tab-active");
	jQuery('.gallery-content-photo-wall').css("display", "block");
	jQuery('.gallery-content-insta-wall').css("display", "none");
	jQuery('.gallery-content-flickr-wall').css("display", "none");
	// upload photos change
	jQuery('#image-gallery').css("display", "block");
	jQuery('#instaram-gallery').css("display", "none");
	jQuery('#flickr-gallery').css("display", "none");
	//instagram configuration 
	jQuery('#instagram-configration').css("display", "none");
}

if(alw_gallery_wall == 'insta_wall') {
	jQuery('.insta_wall').addClass("tab-active");
	jQuery("div.photo_wall").removeClass("tab-active");
	jQuery("div.flickr_wall").removeClass("tab-active");
	 jQuery('.gallery-content-photo-wall').css("display", "none");
	jQuery('.gallery-content-insta-wall').css("display", "block");
	jQuery('.gallery-content-flickr-wall').css("display", "none");
	// upload photos change
	jQuery('#image-gallery').css("display", "none");
	jQuery('#instaram-gallery').css("display", "block");
	jQuery('#flickr-gallery').css("display", "none");
	//instagram configuration 
	jQuery('#instagram-configration').css("display", "block");
}

if(alw_gallery_wall == 'flickr_wall') {
	jQuery('.flickr_wall').addClass("tab-active");
	jQuery("div.photo_wall").removeClass("tab-active");
	jQuery("div.insta_wall").removeClass("tab-active");
	jQuery('.gallery-content-photo-wall').css("display", "none");
	jQuery('.gallery-content-insta-wall').css("display", "none");
	jQuery('.gallery-content-flickr-wall').css("display", "block");
	// upload photos change
	jQuery('#image-gallery').css("display", "none");
	jQuery('#instaram-gallery').css("display", "none");
	jQuery('#flickr-gallery').css("display", "block");
	//instagram configuration 
	jQuery('#instagram-configration').css("display", "none");
}
	
jQuery(document).ready(function() {
	jQuery('input[type=radio][name=enable_gallery_layout]').change(function() {
		var alwselectedlayout = jQuery('[name=enable_gallery_layout]:checked').val();
		if(alwselectedlayout == 'grid') {
			jQuery('.gallery_layout_grid').addClass('gallery_layout');
			//hide show configuration setting according gallery layout
			jQuery('.pw_grid_layout_config').show(); 
			jQuery('.pw_masonry_mosaic_justify_layout_config').hide(); 
				
		} else {
			jQuery('.gallery_layout_grid').removeClass('gallery_layout'); 
		}
		
		if(alwselectedlayout == 'masonry') {
			jQuery('.gallery_layout_masonry').addClass('gallery_layout'); 
			//hide show configuration setting according gallery layout
			jQuery('.pw_grid_layout_config').hide(); 
			jQuery('.pw_masonry_mosaic_justify_layout_config').show(); 
		} else {
			jQuery('.gallery_layout_masonry').removeClass('gallery_layout'); 
		}
		
		/* if(alwselectedlayout == 'mosaic') {
			jQuery('.gallery_layout_mosaic').addClass('gallery_layout');
			//hide show configuration setting according gallery layout
			jQuery('.pw_grid_layout_config').hide(); 
			jQuery('.pw_masonry_mosaic_justify_layout_config').show(); 
					
		} else {
			jQuery('.gallery_layout_mosaic').removeClass('gallery_layout'); 
		}
		
		if(alwselectedlayout == 'justify') {
			jQuery('.gallery_layout_justify').addClass('gallery_layout'); 
			//hide show configuration setting according gallery layout
			jQuery('.pw_grid_layout_config').hide(); 
			jQuery('.pw_masonry_mosaic_justify_layout_config').show(); 
		
		} else {
			jQuery('.gallery_layout_justify').removeClass('gallery_layout'); 
		}		 */
	});
	
	// tab
    jQuery("div.bhoechie-tab-menu>div.list-group>a").click(function(e) {
        e.preventDefault();
        jQuery(this).siblings('a.active').removeClass("active");
        jQuery(this).addClass("active");
        var index = jQuery(this).index();
        jQuery("div.bhoechie-tab>div.bhoechie-tab-content").removeClass("active");
        jQuery("div.bhoechie-tab>div.bhoechie-tab-content").eq(index).addClass("active");
    });
	
	//load more hide show
	jQuery('input[type=radio][name=alw_load_more]').change(function() {
	var alw_load_more = jQuery('[name=alw_load_more]:checked').val();
		if(alw_load_more == 'yes') {
			jQuery('.load_limit').show();
		} else {
			jQuery('.load_limit').hide();
		}
	});
		
	jQuery('input[type=radio][name=alw_gallery_wall]').change(function() {
		var alw_gallery_wall = jQuery('[name=alw_gallery_wall]:checked').val();
		if(alw_gallery_wall == 'photo_wall') {
			jQuery('.photo_wall').addClass("tab-active");
			jQuery("div.insta_wall").removeClass("tab-active");
			jQuery("div.flickr_wall").removeClass("tab-active");
			jQuery('.gallery-content-photo-wall').css("display", "block");
			jQuery('.gallery-content-insta-wall').css("display", "none");
			jQuery('.gallery-content-flickr-wall').css("display", "none");
			// upload photos change
			jQuery('#image-gallery').css("display", "block");
			jQuery('#instaram-gallery').css("display", "none");
			jQuery('#flickr-gallery').css("display", "none");
			//instagram configuration 
			jQuery('#instagram-configration').css("display", "none");
		}
		
		if(alw_gallery_wall == 'insta_wall') {
			jQuery('.insta_wall').addClass("tab-active");
			jQuery("div.photo_wall").removeClass("tab-active");
			jQuery("div.flickr_wall").removeClass("tab-active");
			jQuery('.gallery-content-photo-wall').css("display", "none");
			jQuery('.gallery-content-insta-wall').css("display", "block");
			jQuery('.gallery-content-flickr-wall').css("display", "none");
			// upload photos change
			jQuery('#image-gallery').css("display", "none");
			jQuery('#instaram-gallery').css("display", "block");
			jQuery('#flickr-gallery').css("display", "none");
			//instagram configuration 
			jQuery('#instagram-configration').css("display", "block");
		}
		
		if(alw_gallery_wall == 'flickr_wall') {
			jQuery('.flickr_wall').addClass("tab-active");
			jQuery("div.photo_wall").removeClass("tab-active");
			jQuery("div.insta_wall").removeClass("tab-active");
			jQuery('.gallery-content-photo-wall').css("display", "none");
			jQuery('.gallery-content-insta-wall').css("display", "none");
			jQuery('.gallery-content-flickr-wall').css("display", "block");
			// upload photos change
			jQuery('#image-gallery').css("display", "none");
			jQuery('#instaram-gallery').css("display", "none");
			jQuery('#flickr-gallery').css("display", "block");
			//instagram configuration 
			jQuery('#instagram-configration').css("display", "none");
		}
	});	
	
	//color-picker
		(function( jQuery ) {
			jQuery(function() {
			// Add Color Picker to all inputs that have 'color-field' class
			jQuery('#alw_load_more_color').wpColorPicker();
			});
		})( jQuery );
});
</script>