<?php

	class ShopFront {

		private $shopname = '';
		private $shop_taxonomy = 'category';

		private function outPutLi($level, $slug, $cat, $hasChildren){
			return '<li class="cat_level_' . $level . ' ' . $hasChildren . '"><a href="' . home_url() . '/shop/?product_cat=' . $slug . '">' . $cat . '</li>';
		}

		private function getShopCategories(){

			$productCats = get_terms(array(
				'taxonomy' => $this->shop_taxonomy,
				'hide_empty' => false
			));

			return $productCats;

		}

		function getShopTaxonomy(){
			return $this->shop_taxonomy;
		}

		function hasChildren($cats, $catId){

			foreach ($cats as $cat) {
				if($cat->parent == $catId){
					return 'hasChildren';
				}
			}

			return '';

		}

		function outputShopCats(){

			$cats = $this->getShopCategories();
			
			echo '<ul class="list shop_categories">';

			foreach ($cats as $cat) {
				if($cat->parent == 0){
					echo $this->outPutLi('0', $cat->slug, $cat->name, '');
					echo '<ul class="ul_level_1">';
					foreach ($cats as $subCat) {
						if($subCat->parent == $cat->term_id){
							echo $this->outPutLi('1', $subCat->slug, $subCat->name, $this->hasChildren($cats, $subCat->term_id));
							echo '<ul class="ul_level_2">';
							foreach ($cats as $subSubCat) {
								if($subSubCat->parent == $subCat->term_id){
									echo $this->outPutLi('2', $subSubCat->slug, $subSubCat->name, $this->hasChildren($cats, $subSubCat->term_id));
								}
							}
							echo '</ul>';
						}
					}		
					echo '</ul>';
				}
			}

			echo '</ul>';

		}

		function getProducts(){

			$args = array(
				'post_type' => 'affproducts',
				'posts_per_page' => 15,
				'paged' => $paged
			);

			if(isset($_GET['product_cat'])){
				$cat = $_GET['product_cat'];
				$args = array(
					'post_type' => 'affproducts',
					'tax_query' => array(
			            array(
			                'taxonomy' => $this->shop_taxonomy,
			                'field' => 'slug',
			                'terms' => $cat,
			            )
			        ),
					'posts_per_page' => 15,
					'paged' => $paged
				);
			}

			return new WP_Query( $args );

		}

		function __construct(){
			
			$options = get_option('soaffiliates');
	
			if($options != ''){
				
				if($options['shop_categories'] != '')
					$this->shop_taxonomy = $options['shop_categories'];

			}

		}

	}

?>

						