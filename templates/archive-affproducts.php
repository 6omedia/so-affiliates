<?php get_header(); ?> 

<?php require( plugin_dir_path(__FILE__) . '../so-shopfront.php'); ?>

<section class="aff_shop_page">
	<div class="container">
		<h1>Shop</h1>
		<div class="row">
			<div class="col-sm-3">
				<p class="visible-xs" id="shopByCats">By Category</p>
				<div class="sidebar shop_all_sidebar">
					<div class="x shop-x visible-xs">x</div>
					<!-- <h2>Categories</h2> -->
					<!-- output categories that the plugin uses -->
					<?php 

						$shopFront = new ShopFront();
						$shopFront->outputShopCats();

						// wp_list_categories(array(
						// 	'hide_empty' => 0,
						// 	'title_li' => ''
						// ));

					?>
				</div>
			</div>
			<div class="col-sm-9">
				<?php $products = $shopFront->getProducts(); ?>

				<div class="row">
					<?php if($products->have_posts() ) : while ( $products->have_posts() ) : $products->the_post(); ?>

						<div class="col-sm-4 col-md-3">
							<div class="product">
								<a href="<?php the_permalink(); ?>">
									<?php the_post_thumbnail(); ?>
									<p><?php the_title(); ?></p>
								</a>
							</div>
						</div>

					<?php endwhile; else : ?>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
</section>

<script>
	


</script>

<?php get_footer(); ?>