<?php get_header(); ?> 


<?php 

	require( plugin_dir_path(__FILE__) . '../so-productdata.php');

	$productData = new ProductData(get_post_custom($post->ID));
	$instagramUrls = $productData->getInstagramPictures();
	$merchants = $productData->getMerchants();
	$review = $productData->getReviews();

	$userId = '';

	if(is_user_logged_in()){
		$userId = get_current_user_id();
	}

?>

	<section class="aff_product_page">
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
							<h2>Where to Buy <span data-post_id="<?php echo $post->ID; ?>" data-user_id="<?php echo $userId; ?>" class="ws_heart"></span></h2>
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
						<div class="product_review pinkbox">
							<h2>Review</h2>
							<div>
								<ul class="rating list rating-<?php echo $review['rating']; ?>">
									<li></li>
									<li></li>
									<li></li>
									<li></li>
									<li></li>
								</ul>
							</div>
							<ul class="list procon pros">
								<?php foreach ($review['pros'] as $pro) { ?>
										<li><?php echo $pro; ?></li>
								<?php } ?>
							</ul>
							<ul class="list procon cons">
								<?php foreach ($review['cons'] as $con) { ?>
										<li><?php echo $con; ?></li>
								<?php } ?>
							</ul>
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
