<?php

	// http://blog.teamtreehouse.com/create-your-first-wordpress-custom-post-type

	class AffShop {

		// Set up post type
		function aff_product_post_type(){

			$labels = array(
				'name' => __( 'Aff Products' ),
				'singular_name' => __( 'Aff Product' )
			);

			$supports = array('title', 'editor', 'thumbnail');

			$options = get_option('soaffiliates');
			$productTaxes = $options['aff_product_taxonomies'];

			$args = array(
					'labels' => $labels,
					'supports' => $supports,
					'public' => true,
					'has_archive' => true,
					'taxonomies'          => $productTaxes,
					//'show_ui' => false,
					'rewrite' => array('slug' => 'shop'),
					'menu_icon' => 'dashicons-cart'
				);

			register_post_type( 'affproducts', $args );
			flush_rewrite_rules();

		}

		// Add Brand and Product Categories as Taxonomies
		function add_shop_taxonomies(){

			$args = array(
		            'hierarchical' => true,
		            'label' => 'Brands',
		            'query_var' => true,
		            'rewrite' => array( 'slug' => 'brands', 'with_front' => true )
		        );
		    register_taxonomy(  'brands', 'post', $args);

		    $args = array(
		            'hierarchical' => true,
		            'label' => 'Product Categories',
		            'query_var' => true,
		            'rewrite' => array( 'slug' => 'aff_product_categories', 'with_front' => true )
		        );
		    register_taxonomy(  'aff_product_categories', 'post', $args); 

		}

		function custom_fields_for_products(){

			// add merchants
			add_meta_box('merchants', 'Merchants', array($this, "merchants_box"), 'affproducts', 'normal', 'low');

			// instagram urls
			add_meta_box("instagram_url", "Instagram URL", array($this, "instagram_url_box"), "affproducts", "normal", "low");
			
			// reviews
			add_meta_box("review", "Review", array($this, "review_box"), "affproducts", "normal", "low");		

		}

		function instagram_url_box(){

			global $post;
 			$custom = get_post_custom($post->ID);

  			$instagram_url_1 = $custom["instagram_url_1"][0];
  			$instagram_page_1 = $custom["instagram_page_1"][0];

  			$instagram_url_2 = $custom["instagram_url_2"][0];
  			$instagram_page_2 = $custom["instagram_page_2"][0];

  			$instagram_url_3 = $custom["instagram_url_3"][0];
  			$instagram_page_3 = $custom["instagram_page_3"][0];

  			$instagram_url_4 = $custom["instagram_url_4"][0];
  			$instagram_page_4 = $custom["instagram_page_4"][0];

  			?>

  			<ul class="instagram_list">
  				<li>
  					<label>Image URL One:</label><br>
					<input name="instagram_url_1" value="<?php echo $instagram_url_1; ?>" /><br>
					<label>Page URL One:</label><br>
					<input name="instagram_page_1" value="<?php echo $instagram_page_1; ?>" />	
  				</li>
  				<li>
  					<label>Image URL Two:</label><br>
					<input name="instagram_url_2" value="<?php echo $instagram_url_2; ?>" /><br>
					<label>Page URL One:</label><br>
					<input name="instagram_page_2" value="<?php echo $instagram_page_2; ?>" />
  				</li>
  				<li>
  					<label>Image URL Three:</label><br>
					<input name="instagram_url_3" value="<?php echo $instagram_url_3; ?>" /><br>
					<label>Page URL One:</label><br>
					<input name="instagram_page_3" value="<?php echo $instagram_page_3; ?>" />
  				</li>
  				<li>
  					<label>Image URL Four:</label><br>
					<input name="instagram_url_4" value="<?php echo $instagram_url_4; ?>" /><br>
					<label>Page URL One:</label><br>
					<input name="instagram_page_4" value="<?php echo $instagram_page_4; ?>" />
  				</li>
			</ul>

			<?php

		}

		function merchants_box(){

			$merchantArray = [];

			global $post;
 			
			$custom = get_post_custom($post->ID);

			$merchants = unserialize($custom['merchants'][0]);
			$merchantids = unserialize($custom['merchantids'][0]);
			$links = unserialize($custom['links'][0]);
			$prices = unserialize($custom['prices'][0]);

			$merchSize = '';

			if(!empty($merchants)){
				$merchSize = sizeof($merchants);
			}

 			for($i=0; $i<$merchSize; $i++){

 				$merchant = array(
 					'name' => $merchants[$i],
 					'id' => $merchantids[$i],
 					'link' => $links[$i],
 					'price' => $prices[$i]
 				);

 				$merchantArray[] = $merchant;

 			}

  			?>

  			<!-- Maybe change these to use name[] instead -->
			
			<table id="merchantTable">

				<tr>
					<th>Merchant</th>
					<th>Link</th>
					<th>Price</th>
					<th></th>
				</tr>
				<tr>
					<td>
<!-- 						<input id="merchantid" value="<?php // echo $merchant['id']; ?>" type="hidden"> -->
						<select id="merchantInput">
							<option value="" data-merchant_id="">- select merchant -</option>

							<?php

								$merches = get_posts( array(
								   'numberposts' => -1,
								   'post_type' => 'merchants'
								));

								foreach ($merches as $merch) {
									echo '<option value="' . $merch->ID . '">' . $merch->post_title . '</option>';
								}

							?>

						</select>
					</td>
					<td>
						<input id="linkInput" type="text">
					</td>
					<td>
						<input id="priceInput" type="number" min="0" placeholder="0.00">
					</td>
					<td>
						<div class="button" id="addMerchant">Add Merchant</div>
					</td>
				</tr>

				<?php

				foreach ($merchantArray as $merchant) { ?>
						
					<tr>
						<td>
							<input name="merchants[]" value="<?php echo $merchant['name']; ?>" readonly>
							<input name="merchantids[]" value="<?php echo $merchant['id']; ?>" type="hidden">
						</td>
						<td>
							<input name="links[]" value="<?php echo $merchant['link']; ?>">
						</td>
						<td>
							<input name="prices[]" value="<?php echo $merchant['price']; ?>">
						</td>
						<td>
							<div class="removeBtn"><span class="dashicons dashicons-no-alt"></span></div>
						</td>
					</tr>

				<?php } ?>

			</table>

			<?php

		}

		function review_box(){

			global $post;
 			
			$custom = get_post_custom($post->ID);
			$rating = 5;
			$pros = [];
			$cons = [];

			if(!empty($custom['rating'][0])) 
				$rating = $custom['rating'][0];

			if(!empty($custom['pro'][0]))
				$pros = unserialize($custom['pro'][0]);

			if(!empty($custom['con'][0]))
				$cons = unserialize($custom['con'][0]);

			?>

			<label>Rating Out Of 10</label>
			<input type="number" min="0" max="10" name="rating" value="<?php echo $rating; ?>">
			<div class="procons">
				<div class="proconBox">
					<input id="pro" placeholder="Pro...">
					<div id="add_pro_button" class="button">Add Pro</div>
					<ul id="proList" class="ratingList">
						<?php foreach ($pros as $pro){ ?>
							<li>
								<input name="pro[]" type="text" value="<?php echo $pro; ?>">
								<span class="dashicons dashicons-no-alt"></span>
							</li>
						<?php } ?>
					</ul>
				</div>
				<div class="proconBox">
					<input id="con" placeholder="Con...">
					<div id="add_con_button" class="button">Add Con</div>
					<ul id="conList" class="cons ratingList">
						<?php foreach ($cons as $con){ ?>
							<li>
								<input name="con[]" type="text" value="<?php echo $con; ?>">
								<span class="dashicons dashicons-no-alt"></span>
							</li>
						<?php } ?>
					</ul>
				</div>
			</div>

			<?php

		}

		function save_product_meta(){

			global $post;
 
 			// Instagram
			update_post_meta($post->ID, "instagram_url_1", $_POST["instagram_url_1"]);
			update_post_meta($post->ID, "instagram_page_1", $_POST["instagram_page_1"]);

			update_post_meta($post->ID, "instagram_url_2", $_POST["instagram_url_2"]);
			update_post_meta($post->ID, "instagram_page_2", $_POST["instagram_page_2"]);

			update_post_meta($post->ID, "instagram_url_3", $_POST["instagram_url_3"]);
			update_post_meta($post->ID, "instagram_page_3", $_POST["instagram_page_3"]);

			update_post_meta($post->ID, "instagram_url_4", $_POST["instagram_url_4"]);
			update_post_meta($post->ID, "instagram_page_4", $_POST["instagram_page_4"]);

			// Merchants
			update_post_meta($post->ID, "merchants", $_POST["merchants"]);
			update_post_meta($post->ID, "merchantids", $_POST["merchantids"]);
			update_post_meta($post->ID, "links", $_POST["links"]);
			update_post_meta($post->ID, "prices", $_POST["prices"]);

			// Reviews
			update_post_meta($post->ID, "rating", $_POST["rating"]);
			update_post_meta($post->ID, "pro", $_POST["pro"]);
			update_post_meta($post->ID, "con", $_POST["con"]);

		}

		function use_affproduct_template($single) {

		    global $wp_query, $post, $plugin_url;

		    /* Checks for single template by post type */
		    if ($post->post_type == "affproducts"){
		    	return dirname( __FILE__ ) . '/templates/single-affproduct.php';
		    }
		    return $single;

		}

		function use_affshop_template($template){

			global $post;

		    if ( is_post_type_archive ( 'affproducts' ) ) {
		         $archive_template = dirname( __FILE__ ) . '/templates/archive-affproducts.php';
		         return $archive_template;
		    }
		    
		    return $template;

		}

		// function add_shop_admin_menu_item() {
		//   // $page_title, $menu_title, $capability, $menu_slug, $callback_function
		//   add_menu_page('Shop', 'Shop', 'shop', 'edit.php?post_status=draft&post_type=post');
		// }

		function __construct(){

			/* Create Shop */

			add_action( 'init', array($this, 'add_shop_taxonomies'));
			add_action( 'init', array($this, 'aff_product_post_type'));

			// Add Custom Fields
			add_action( 'admin_init', array($this, 'custom_fields_for_products'));
			// Save Custom Fields
			add_action( 'save_post', array($this, 'save_product_meta'));

			// Templates
			add_filter( 'single_template', array($this, 'use_affproduct_template'));
			add_filter( 'archive_template', array($this, 'use_affshop_template'));

			// Add Shop Menu Item
			// add_filter('nav_menu_items_affproducts', array($this, 'add_shop_admin_menu_item'));

		}

	}

?>