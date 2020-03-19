<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$allimages = array(  'p' => $alw_id, 'post_type' => 'animated_live_wall', 'orderby' => 'ASC');
$loop = new WP_Query( $allimages );
while ( $loop->have_posts() ) : $loop->the_post(); ?>
	<section class="main">
		<div id="ri-grid-<?php echo $alw_id; ?>" class="ri-grid ri-grid-size-2">
			<div id="alw-loader" style="display:none; text-align:center; width:100%;">
				<!-- begin folding modal -->
				<div class="alw-pre-loader folding">
					<div class="sk-cube1 sk-cube"></div>
					<div class="sk-cube2 sk-cube"></div>
					<div class="sk-cube4 sk-cube"></div>
					<div class="sk-cube3 sk-cube"></div>
				</div>
			</div>
			<ul>
			<?php
			if(isset($instagram_data['data'])) {
				$count = 0;
				$no = 0;
				foreach($instagram_data['data'] as $attachment_id) {
					
					$insta_photos_link = $attachment_id['link'];
					$insta_photos_like = $attachment_id['likes']['count'];
					$insta_photos_comment = $attachment_id['comments']['count'];
					$insta_photos_caption = $attachment_id['caption']['text'];
					$insta_media_type = $attachment_id['type'];	
					
					//set thumbnail size
					if($alw_grid_thumb_size == "thumbnail") { $thumbnail_url = $attachment_id['images']['thumbnail']['url']; }
					if($alw_grid_thumb_size == "medium") { $thumbnail_url = $attachment_id['images']['low_resolution']['url']; }
					if($alw_grid_thumb_size == "large") { $thumbnail_url = $attachment_id['images']['standard_resolution']['url']; }
					if($alw_grid_thumb_size == "full") { $thumbnail_url = $attachment_id['images']['standard_resolution']['url']; }
					?>
					<li class="brick">
						<a class="snip1467" href="<?php echo $insta_photos_link; ?>" target="<?php echo $alw_insta_link; ?>">
							<img src="<?php echo $thumbnail_url; ?>"/>
							<?php if($insta_media_type == 'video') { ?>
							<span class='instagram-video fas fa-video'></span>
							<?php } ?>
							<?php if($alw_insta_icon == 'instagram') { echo "<i class='pw-instagram fab fa-instagram'></i>"; 
							} else { 
								?>
								<i class="pw-heart far fa-heart"> <?php echo $insta_photos_like; ?></i>
								<i class="pw-comment far fa-comment"> <?php echo $insta_photos_comment; ?></i>
								<?php if($alw_insta_caption == 'true') { ?>
									<figcaption>
										<p class="pw-caption"><?php echo wp_trim_words($insta_photos_caption, 3); ?></p>
									</figcaption>
									<?php
								}
							}
							?>
						</a>
					</li>					
					<?php
					$no++;
					$count++;
				}// end of attachment foreach
			} else {
				_e('Sorry! No image gallery found.', ALW_TXTDM);
				echo ":[ALW id=$alw_id]";
			} // end of if else of images available check into image
			?>
			</ul>
		</div>
	</section>
<?php
endwhile;
wp_reset_query();
?>
<script type="text/javascript">	
jQuery( document ).ready(function() {
	jQuery( '#ri-grid-<?php echo esc_js($alw_id); ?>' ).gridrotator( {
		rows : <?php echo esc_js($alw_grid_rows); ?>,
		// number of columns 
		columns : <?php echo esc_js($alw_grid_columns); ?>,
		w1024 : { rows : <?php echo esc_js($alw_grid_rows); ?>, columns : <?php echo esc_js($alw_grid_columns); ?> },
		w768 : {rows : <?php echo esc_js($alw_grid_rows); ?>,columns : <?php echo esc_js($alw_grid_columns); ?> },
		w480 : {rows : 3,columns : 1 },
		w320 : {rows : 3,columns : 1 },
		w240 : {rows : 3,columns : 1 },
		// step: number of items that are replaced at the same time
		// random || [some number]
		// note: for performance issues, the number "can't" be > options.maxStep
		<?php if($alw_grid_stop_anim == 'yes') { ?>
		step : [0],
		<?php } else { ?>
		step : 'random',
		<?php } ?>
		// change it as you wish..
		maxStep : 3,
		// prevent user to click the items
		preventClick : false,
		// animation type
		// showHide || fadeInOut || 
		// slideLeft || slideRight || slideTop || slideBottom || 
		// rotateBottom || rotateLeft || rotateRight || rotateTop || 
		// scale ||
		// rotate3d ||
		// rotateLeftScale || rotateRightScale || rotateTopScale || rotateBottomScale || 
		// random
		animType : '<?php echo esc_js($alw_grid_animation); ?>',
		// animation speed
		animSpeed : 700, // 100 to 3000
		// animation easings
		animEasingOut : 'linear',
		animEasingIn: 'linear',
		// the item(s) will be replaced every 3 seconds
		// note: for performance issues, the time "can't" be < 300 ms
		interval : 1200, //100 to 3000
		// if false the animations will not start
		// use false if onhover is true for example
		slideshow : true,
		// if true the items will switch when hovered
		onhover : false,
		// ids of elements that shouldn't change
		nochange : [] // 
	} );

	
	// lightbox
	jQuery(function(){
		//jQuery('.brick a').simpleLightbox();			
	});
});
</script>