<?php

	class Merchants {

		function merchant_post_type(){

			$labels = array(
				'name' => __( 'Merchants' ),
				'singular_name' => __( 'Merchant' ),
				'edit_item' => __( 'Edit Merchant' ),
				'featured_image' => __( 'Merchant Logo' )
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

 			$network = '';

 			if(isset($custom["network"][0]))
  				$network = $custom["network"][0]; ?>

  			<label>Network:</label>
			<input name="network" value="<?php echo $network; ?>" />

			<?php

		}

		function save_merchant_meta(){

			global $post;

			if( !is_object($post) ) 
        		return;

			update_post_meta($post->ID, "network", $_POST["network"]);

		}

		function restrict_articles_by_issue() {
		    global $wpdb;
		    $merchants = $wpdb->get_col("
		        SELECT DISTINCT meta_value
		        FROM ". $wpdb->postmeta ."
		        WHERE meta_key = 'merchants'
		        ORDER BY meta_value
		    ");
		    ?>
		    <label for="issue">Merchants:</label>
		    <select name="issue_restrict_articles" id="issue">
		        <option value="">Show all</option>
		        <?php foreach ($issues as $issue) { ?>
		        <option value="<?php echo esc_attr( $issue ); ?>" <?php if(isset($_GET['issue_restrict_articles']) && !empty($_GET['issue_restrict_articles']) ) selected($_GET['issue_restrict_articles'], $issue); ?>>
		        <?php
		          $issue   = get_post($issue);
		          echo $issue->post_title;
		        ?>
		        </option>
		        <?php } ?>
		    </select>
		    <?php
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