<?php

	// http://blog.teamtreehouse.com/create-your-first-wordpress-custom-post-type

	class AffShop {

		// Set up post type
		function aff_product_post_type(){

			$labels = array(
				'name' => __( 'Aff Products' ),
				'singular_name' => __( 'Aff Product' )
			);

			$supports = array('title', 'editor', 'thumbnail', 'revisions');

			$args = array(
					'labels' => $labels,
					'supports' => $supports,
					'public' => true,
					'has_archive' => true,
					'taxonomies'          => array( 'brands' ),
					//'show_ui' => false,
					'rewrite' => array('slug' => 'affproducts'),
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

		}

		function custom_fields_for_products(){

			// where to buy
				// add merchants
			add_meta_box('merchants', 'Merchants', array($this, "merchants_box"), 'affproducts', 'normal', 'low');

			// instagram urls
			add_meta_box("instagram_url", "Instagram URL", array($this, "instagram_url_box"), "affproducts", "normal", "low");
			// add_meta_box("review", "Instagram URL", array($this, "instagram_url_box"), "affproducts", "normal", "low");

			// reviews
			// add_meta_box('');

		}

		function instagram_url_box(){

			global $post;
 			$custom = get_post_custom($post->ID);

 			// echo '<pre>';
 			// print_r($custom);
 			// echo '</pre>';

  			$instagram_url_1 = $custom["instagram_url_1"][0];
  			$instagram_url_2 = $custom["instagram_url_2"][0];
  			$instagram_url_3 = $custom["instagram_url_3"][0];
  			$instagram_url_4 = $custom["instagram_url_4"][0];
  			
  			?>

  			<!-- Maybe change these to use name[] instead -->

  			<label>Instagram URL One:</label>
			<input name="instagram_url_1" value="<?php echo $instagram_url_1; ?>" />
			<label>Instagram URL Two:</label>
			<input name="instagram_url_2" value="<?php echo $instagram_url_2; ?>" />
			<label>Instagram URL Three:</label>
			<input name="instagram_url_3" value="<?php echo $instagram_url_3; ?>" />
			<label>Instagram URL Four:</label>
			<input name="instagram_url_4" value="<?php echo $instagram_url_4; ?>" />
			
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

 			for($i=0; $i<sizeof($merchants); $i++){

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

				<?php foreach ($merchantArray as $merchant) { ?>
						
					<tr>
						<td>
							<input name="merchants[]" value="<?php echo $merchant['name']; ?>" disabled>
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

		function save_product_meta(){

			global $post;
 
 			// Instagram
			update_post_meta($post->ID, "instagram_url_1", $_POST["instagram_url_1"]);
			update_post_meta($post->ID, "instagram_url_2", $_POST["instagram_url_2"]);
			update_post_meta($post->ID, "instagram_url_3", $_POST["instagram_url_3"]);
			update_post_meta($post->ID, "instagram_url_4", $_POST["instagram_url_4"]);

		//	error_log('Post ' . $_POST);

			// Merchants
			update_post_meta($post->ID, "merchants", $_POST["merchants"]);
			update_post_meta($post->ID, "merchantids", $_POST["merchantids"]);
			update_post_meta($post->ID, "links", $_POST["links"]);
			update_post_meta($post->ID, "prices", $_POST["prices"]);

		}

		function use_affproduct_template($single) {

		    global $wp_query, $post, $plugin_url;

		    /* Checks for single template by post type */
		    if ($post->post_type == "affproducts"){
		    	return dirname( __FILE__ ) . '/templates/single-affproduct.php';
		    }
		    return $single;

		}

		function __construct(){

			/* Create Shop */

			add_action( 'init', array($this, 'add_shop_taxonomies'));
			add_action( 'init', array($this, 'aff_product_post_type'));

			// Add Custom Fields
			add_action( 'admin_init', array($this, 'custom_fields_for_products'));
			// Save Custom Fields
			add_action( 'save_post', array($this, 'save_product_meta'));
			add_filter( 'single_template', array($this, 'use_affproduct_template'));

		}

	}

?>