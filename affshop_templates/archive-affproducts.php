<?php get_header(); ?> 

<?php require( plugin_dir_path(__FILE__) . '../so-shopfront.php'); ?>

<?php 

	$shopFront = new ShopFront();
	$bcPage = 'shop';
	
	if(isset($_GET['product_kind'])){
		$bcPage = 'category';
	}

	$options = get_option('soaffiliates');
	$productTaxes = $options['aff_product_taxonomies'];
	$useBrand = false;

	if(in_array('brands', $productTaxes)){
		$useBrand = true;
	}

	$located = locate_template( 'archive-affproducts.php' );

?>

<?php if(!$located){ ?>

<section class="aff_shop_page">
	<div class="container">
		<h1>Shop</h1>

		<?php $shopFront->outPutBreadCrumbs($bcPage); ?>

		<div class="row">
			<div class="col-sm-3">
				<p class="visible-xs" id="shopByCats">By Category</p>
				<div class="sidebar shop_all_sidebar">
					<div class="x shop-x visible-xs">x</div>
					<?php 

						echo $shopFront->aff_shop_cats();
						// $shopFront->outputShopCats();  aff_shop_cats
						

					?>
				</div>
			</div>
			<div class="col-sm-9">

				<?php // $products = $shopFront->getProducts();

					if(isset($_GET['product_kind'])){

				  		$cat = $_GET['product_kind'];
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
							<div class="product shop_product">
								<a href="<?php the_permalink(); ?>">
									<?php
										
										global $plugin_url;

										if(has_post_thumbnail()){
										 	the_post_thumbnail(); 
										}else{
											echo '<img src="' . $plugin_url . '/img/picture.png" alt="' . get_the_title() . '" />';
									 	}

									 	$brand = '';

										if($useBrand){

											$terms = get_the_terms(get_the_ID(), 'brands');

											if(!empty($terms)){
												$brand = $terms[0]->name;
											}

										}

								 	?>
								 	<span class="product_brand"><?php echo $brand; ?></span>
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

<?php }else{ 

	load_template($located);

} ?>

<?php get_footer(); ?>