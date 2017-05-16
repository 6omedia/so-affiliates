<?php

	class Merchants {

		function merchant_post_type(){

			$labels = array(
				'name' => __( 'Merchants' ),
				'singular_name' => __( 'Merchant' )
			);

			$supports = array('title', 'thumbnail');

			$args = array(
					'labels' => $labels,
					'supports' => $supports,
					'public' => true,
					'has_archive' => true,
					'taxonomies'          => array( 'brands' ),
					//'show_ui' => false,
					'rewrite' => array('slug' => 'merchants'),
					'menu_icon' => 'dashicons-store'
				);

			register_post_type( 'merchants', $args );
			flush_rewrite_rules();
		
		}

		function custom_fields_for_merchant(){

			add_meta_box('network', 'Network', array($this, "network_box"), 'merchants', 'normal', 'low');

		}

		function network_box(){

			global $post;
 			$custom = get_post_custom($post->ID);

  			$network = $custom["network"][0]; ?>

  			<label>Network:</label>
			<input name="network" value="<?php echo $network; ?>" />

			<?php

		}

		function save_merchant_meta(){

			global $post;
			update_post_meta($post->ID, "network", $_POST["network"]);

		}

		function __construct(){

			/* Create Shop */

			// add_action( 'init', array($this, 'add_brand_taxonomy'));
			add_action( 'init', array($this, 'merchant_post_type'));

			// Add Custom Fields
			add_action( 'admin_init', array($this, 'custom_fields_for_merchant'));
			// Save Custom Fields
			add_action( 'save_post', array($this, 'save_merchant_meta'));
			// add_filter( 'single_template', array($this, 'use_affproduct_template'));

		}

	}

?>