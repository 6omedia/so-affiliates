<?php 

	

	// function createMerchants(){

	// 	$merchantArray = [];

	// 	global $post;
			
	// 	$custom = get_post_custom($post->ID);
	// 	$merchants = unserialize($custom['merchants'][0]);
	// 	$merchantids = unserialize($custom['merchantids'][0]);
	// 	$links = unserialize($custom['links'][0]);
	// 	$prices = unserialize($custom['prices'][0]);

	// 	for($i=0; $i<sizeof($merchants); $i++){

	// 		$merchant = array(
	// 			'name' => $merchants[$i],
	// 			'id' => $merchantids[$i],
	// 			'link' => $links[$i],
	// 			'price' => $prices[$i]
	// 		);

	// 		$merchantArray[] = $merchant;

	// 	}

	// 	return $merchantArray;

	// }

	get_header(); 

	require( plugin_dir_path(__FILE__) . '../so-productdata.php');

	$productData = new ProductData(get_post_custom($post->ID));
	$instagramUrls = $productData->getInstagramPictures();
	$merchants = $productData->getMerchants();

?>

	<section>
		<div class="container">

			<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

				<div class="row">
					<div class="col-sm-7">
						<div class="affproduct">
							<h1><?php the_title(); ?></h1>
							<?php the_post_thumbnail(); ?>

							<!-- instagram -->
							<div class="instagram_box">
								<img src="<?php echo $instagramUrls[0]; ?>">
								<img src="<?php echo $instagramUrls[1]; ?>">
								<img src="<?php echo $instagramUrls[2]; ?>">
								<img src="<?php echo $instagramUrls[3]; ?>">
							</div>

							<!-- description -->
							<?php the_content(); ?>
		
						</div>
					</div>
					<div class="col-sm-5">
						<div class="where_to_buy_box pinkbox">
							<h2>Where to Buy</h2>
							<!-- merchant links -->
							<table class="where_to_buy_table">
							<?php foreach ($merchants as $merchant) { ?>
								<tr>	
									<td>
										<img src="<?php echo $merchant['img']; ?>">
									</td>
									<td>
										<span>£<?php echo $merchant['price']; ?></span>
									</td>
									<td>
										<a class="buy_button" href="<?php echo $merchant['link']; ?>">Buy Now</a>
									</td>
								</tr>
							<?php } ?>
							</table>
						</div>
						<!-- reviews -->
						<div class="product_reviews pinkbox">
							<h2>Reviews</h2>
						</div>
						<!-- similar -->
						<div class="similar_products pinkbox">
							<h2>Similar Products</h2>
						</div>
					</div>
				</div>

			<?php endwhile; else : ?>
			<?php endif; ?>

		</div>
	</section>

<?php get_footer(); ?>
