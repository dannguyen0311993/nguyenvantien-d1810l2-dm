<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$allimages = array(  'p' => $alw_id, 'post_type' => 'animated_live_wall', 'orderby' => 'ASC');
$loop = new WP_Query( $allimages );
while ( $loop->have_posts() ) : $loop->the_post();
?>
	<style type="text/css">
	.brick {
		background: #f5f5f5;
		box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.33);
		color: #333;
		border: none;
	}
	
	.brick {
		width: 221.2px;
	}
	
	.info {
		padding: 15px;
		color: #333;
	}
	
	.brick img {
		margin: 0px;
		padding: 0px;
		display: block;
	}
	</style>
	<div id="alw-loader" style="display:none; text-align:center; width:100%;">
		<!-- begin folding modal -->
		<div class="alw-pre-loader folding">
			<div class="sk-cube1 sk-cube"></div>
			<div class="sk-cube2 sk-cube"></div>
			<div class="sk-cube4 sk-cube"></div>
			<div class="sk-cube3 sk-cube"></div>
		</div>
	</div>
	<div id="freewall" class="free-wall">
		<?php
		if(isset($instagram_data['data'])) {
			$alw_total_images = count($instagram_data['data']);
			$count = 0;
			$no = 1;
			// If user want to show load more
			foreach($instagram_data['data'] as $attachment_id) {
				$insta_photos = $attachment_id['images']['standard_resolution']['url'];
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
				<div class="brick">
					<a class='grid' target='<?php echo $alw_insta_link; ?>' href='<?php echo $insta_photos_link; ?>'>
						<div class='snip1467'><img src='<?php echo $thumbnail_url; ?>' width='100%'>
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
						</div>
					</a>
				</div>
				<?php
				$no++;
				$count++;
				
			}// end of attachment foreach
		} else {
			_e('Sorry! No image gallery found ', ALW_TXTDM);
			echo ":[ALW id=$alw_id]";
		} // end of if else of images available check into image
		?>	
	</div>
	<?php
endwhile;
wp_reset_query();

// column settins
$ma_column = 300;
if($column_setting == 'small') { $ma_column = 200; }
if($column_setting == 'large') { $ma_column = 300; }
?>
<script type="text/javascript">
jQuery( document ).ready(function() {
	var wall = new Freewall("#freewall");
	wall.reset({
		animate: false,
		cellW: <?php echo $ma_column; ?>, // function(container) {return 100;} 200 to 300
		cellH: 'auto', // function(container) {return 100;}
		delay: 50, // slowdown active block;
		engine: 'giot',
		fixSize: null, // resize + adjust = fill gap;
		//fixSize: 0, resize but keep ratio = no fill gap;
		//fixSize: 1, no resize + no adjust = no fill gap;
		gutterX: <?php echo $alw_images_gap; ?>, // width spacing between blocks;
		gutterY: <?php echo $alw_images_gap; ?>, // height spacing between blocks;
		keepOrder: false,
		selector: '.brick',
		draggable: false,
		cacheSize: true, // caches the original size of block;
		rightToLeft: false,
		bottomToTop: false,
		onGapFound: function() {},
		onComplete: function() {},
		onResize: function() {
			wall.fitWidth();
		},
		onBlockDrag: function() {},
		onBlockMove: function() {},
		onBlockDrop: function() {},
		onBlockReady: function() {},
		onBlockFinish: function() {},
		onBlockActive: function() {},
		onBlockResize: function() {}		
	});

	wall.container.find('.brick a').load(function() {
		wall.fitWidth();
	});
	wall.container.find('.brick a').resize();	
});
</script>