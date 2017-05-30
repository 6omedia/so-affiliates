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

		function findParents(&$parents, $cat){

			// echo '<pre>findParents';
			// print_r($parents);
			// echo '</pre>';

			$catObj = get_term_by('id', $cat, $this->shop_taxonomy);

			if($catObj->parent != 0){

				$parents[] = $catObj;
				// print_r($parents);
				$this->findParents($parents, $catObj->parent);

			}else{
				$parents[] = $catObj;
				return;
			}

		}

		function outPutBreadCrumbs($page, $product = ''){

			$linkArray = array();

			if($page == 'shop'){
				$linkArray[] = array(
					'link' => home_url() . '/shop',
					'text' => 'shop'
				);
			}

			if($page == 'category'){

				$cat = $_GET['product_cat'];

				$parents = [];

				$catObj = get_term_by('slug', $cat, $this->shop_taxonomy);

				$this->findParents($parents, $catObj->term_id);

				$parents = array_reverse($parents);

				$linkArray[] = array(
					'link' => home_url() . '/shop',
					'text' => 'shop'
				);
			
				foreach ($parents as $parent) {
					$linkArray[] = array(
						'link' => home_url() . '/shop/?product_cat=' . $parent->slug,
						'text' => str_replace('-', ' ', $parent->slug)
					);
				}

			}

			if($page == 'product'){

				$proId = get_the_ID();

				$parents = [];

				$catObj = get_the_terms($proId, $this->shop_taxonomy);

				// term_id

				$this->findParents($parents, $catObj[0]->term_id);

				$parents = array_reverse($parents);

				$linkArray[] = array(
					'link' => home_url() . '/shop',
					'text' => 'shop'
				);
			
				foreach ($parents as $parent) {
					$linkArray[] = array(
						'link' => home_url() . '/shop/?product_cat=' . $parent->slug,
						'text' => str_replace('-', ' ', $parent->slug)
					);
				}

				// $product = get_post($proId);

				$linkArray[] = array(
					'link' => '',
					'text' => $product
				);

			}

			?>

			<ul class="list breadCrumbs">
				<?php 

					$numItems = count($linkArray);
					$i = 0; 

					foreach ($linkArray as $crumb) { ?>
					
					<li>
						<a href="<?php echo $crumb['link']; ?>"><?php echo $crumb['text']; ?></a>

						<?php if(++$i != $numItems) { ?>
							<span>&rarr;</span>
						<?php } ?>
					</li>

				<?php } ?>
			</ul>

			<?php

		}

		// function getProducts(){

		// 	$args = array(
		// 		'post_type' => 'affproducts',
		// 		'posts_per_page' => 10,
		// 		'paged' => $paged
		// 	);

		// 	if(isset($_GET['product_cat'])){
		// 		$cat = $_GET['product_cat'];
		// 		$args = array(
		// 			'post_type' => 'affproducts',
		// 			'tax_query' => array(
		// 	            array(
		// 	                'taxonomy' => $this->shop_taxonomy,
		// 	                'field' => 'slug',
		// 	                'terms' => $cat,
		// 	            )
		// 	        ),
		// 			'posts_per_page' => 10,
		// 			'paged' => $paged
		// 		);
		// 	}

		// 	return new WP_Query( $args );

		// }

		// function filter_by_cats($query){

		// 	if(isset($_GET['product_cat'])){
		//  		$cat = $_GET['product_cat'];
		//  		$query->set('tax_query', array(
		// 	            array(
		// 	                'taxonomy' => $this->shop_taxonomy,
		// 	                'field' => 'slug',
		// 	                'terms' => $cat,
		// 	            )
		// 	        ));
		//  	}

		// }

		function shop_pagination() {
		    global $wp_query;

		    $big = 999999999;
		    $pages = paginate_links(array(
		        'base' => str_replace($big, '%#%', get_pagenum_link($big)),
		        'format' => '?page=%#%',
		        'current' => max(1, get_query_var('paged')),
		        'total' => $wp_query->max_num_pages,
		        'prev_next' => false,
		        'type' => 'array',
		        'prev_next' => TRUE,
		        'prev_text' => '&larr;',
		        'next_text' => '&rarr;'
            ));

		    if (is_array($pages)) {
		        $current_page = ( get_query_var('paged') == 0 ) ? 1 : get_query_var('paged');
		        echo '<ul class="pagination">';
		        foreach ($pages as $i => $page) {
		            if ($current_page == 1 && $i == 0) {
		                echo "<li class='active'>$page</li>";
		            } else {
		                if ($current_page != 1 && $current_page == $i) {
		                    echo "<li class='active'>$page</li>";
		                } else {
		                    echo "<li>$page</li>";
		                }
		            }
		        }
		        echo '</ul>';
		    }

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

						