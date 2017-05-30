<?php get_header(); ?> 

<?php require( plugin_dir_path(__FILE__) . '../so-shopfront.php'); ?>

<?php 

	$shopFront = new ShopFront();
	$bcPage = 'shop';
	
	if(isset($_GET['product_cat'])){
		$bcPage = 'category';
	}

?>

<section class="aff_shop_page">
	<div class="container">
		<h1>Shop</h1>

		<?php $shopFront->outPutBreadCrumbs($bcPage); ?>

		<div class="row">
			<div class="col-sm-3">
				<p class="visible-xs" id="shopByCats">By Category</p>
				<div class="sidebar shop_all_sidebar">
					<div class="x shop-x visible-xs">x</div>
					<?php $shopFront->outputShopCats(); ?>
				</div>
			</div>
			<div class="col-sm-9">

				<?php // $products = $shopFront->getProducts();

					if(isset($_GET['product_cat'])){
				  		$cat = $_GET['product_cat'];
						query_posts(array(
							'post_type' => 'affproducts',
							'tax_query' => array(
					            array(
					                'taxonomy' => $shopFront->getShopTaxonomy(),
					                'field' => 'slug',
					                'terms' => $cat,
					            )
					        ),
							'posts_per_page' => 10,
							'paged' => $paged
						));

					}

				?>

				<div class="row">
					<?php if(have_posts() ) : while (have_posts() ) : the_post(); ?>

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

					<?php $shopFront->shop_pagination(); ?>

				</div>
			</div>
		</div>
	</div>
</section>

<script>
	


</script>

<?php get_footer(); ?>