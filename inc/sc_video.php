<div class="soaff_video">
	
	<!-- <pre> -->
	<?php

		// print_r($video); 
		global $wpdb;
		$vProducts = $wpdb->get_results('SELECT * FROM ' . $wpdb->prefix . 'aff_video_products WHERE video_id=' . $id);
		// print_r($vProducts);
		$pIds = [];

		$productsFullData = [];

	?>

	<div id="youtubePlayer" data-ytid="<?php echo $video->yt_video_id; ?>"></div>
	
	<div class="soaff_videoslider soaffvideo_width">
		<div class="soaff_video_products">
			<p>Shop this video:</p>
			<div class="soaff_arrows left">&#x2039;</div>
			<div class="soaff_arrows right">&#x203A;</div>
			<ul class="ul_products" id="soaff_video_product_list">
				<?php foreach($vProducts as $product){ ?>
					<li data-start="<?php echo $product->video_time; ?>">
						<?php

							$theProduct = get_post($product->linkorproductcode);
							$rating = get_post_meta($product->linkorproductcode, 'rating');
							$merchants = get_post_meta($product->linkorproductcode, 'merchants')[0];
							$links = get_post_meta($product->linkorproductcode, 'links')[0];

							$theLink = $links[0];
							$otherMerchants = '';

							if(sizeof($merchants) > 1){
								$theLink = $theProduct->guid;
								$otherMerchants = ' - plus ' . sizeof($merchants) . ' more merchants';
							}

							array_push($productsFullData, array(
								'time' => substr($product->video_time, 3),
								'title' => $theProduct->post_title,
								'link' => $theLink
							));

						?>

						<a href="<?php echo $theLink; ?>" target="_blank">
							<img src="<?php echo get_the_post_thumbnail_url($product->linkorproductcode, 'small'); ?>">
						</a>
						<div class="details">
							<span class="time"><?php echo substr($product->video_time, 3); ?></span>
							<div class="description">
								<a href="<?php echo $theLink; ?>" target="_blank">
									<p><?php echo $theProduct->post_title; ?></p>
								</a>
							</div>
							<ul class="rating list rating-<?php echo $rating[0]; ?>">
								<li></li>
								<li></li>
								<li></li>
								<li></li>
								<li></li>
							</ul>
							<p class="merchant"><span><?php echo $merchants[0]; ?></span><?php echo $otherMerchants; ?></p>
						</div>
						
					</li>
				<?php } ?>
			</ul>
		</div>

		<?php if($list){ ?>
			<div class="soaffvideo_width">
				<p class="listHeader <?php echo $list; ?>">All Products Featured</p>
				<table class="allProducts">
					<?php foreach($productsFullData as $product){ ?>
						<tr>
							<td class="time"><?php echo $product['time']; ?></td>
							<td>
								<a target="_blank" href="<?php echo $product['link']; ?>">
									<?php echo $product['title']; ?>
								</a>
							</td>
						</tr>
					<?php } ?>
				</table>
			</div>
		<?php } ?>

	</div>


</div>