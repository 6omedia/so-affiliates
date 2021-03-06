<?php

/* 
 * Plugin Name: SixOm Affiliates
 * Plugin URI: http://6omedia.co.uk
 * Description: Manage affilate products with multiple merchants
 * Version: 2.0.0
 * Author: 6oMedia
 * Author URI:
 * License: GPL2
*/
 
// Global Variables

$plugin_url = WP_PLUGIN_URL . '/so-affiliates';
$options = array();

require('helpful_funcs.php');

$helpFuncs = new helpful_funcs();

class SoAffilates {

	function setup_database_tables(){

		global $wpdb;
		$charset_collate = $wpdb->get_charset_collate();

		$table_name = $wpdb->prefix . 'aff_video';

		$sql = "CREATE TABLE $table_name (
			id mediumint(9) NOT NULL AUTO_INCREMENT,
			name varchar(20) NOT NULL,
			yt_video_id varchar(60) DEFAULT '' NOT NULL,
			display_as varchar(20) NOT NULL,
			UNIQUE KEY id (id)
		) $charset_collate;";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );

		$table_name = $wpdb->prefix . 'aff_video_products';

		$sql = "CREATE TABLE $table_name (
			id mediumint(9) NOT NULL AUTO_INCREMENT,
			video_id mediumint(9) NOT NULL,
			video_time time NOT NULL,
			link_text varchar(30) NOT NULL,
			linkorproductcode varchar(50) NOT NULL,
			UNIQUE KEY id (id)
		) $charset_collate;";

		dbDelta( $sql );

		// $table_name = $wpdb->prefix . 'aff_attributes';

		// $sql = "CREATE TABLE $table_name (
		// 	id mediumint(9) NOT NULL AUTO_INCREMENT,
		// 	taxonomy varchar(30) NOT NULL,
		// 	term varchar(30) NOT NULL,
		// 	attribute_name varchar(30) NOT NULL,
		// 	attribute_value varchar(50) NOT NULL,
		// 	UNIQUE KEY id (id)
		// ) $charset_collate;";

		// dbDelta( $sql );

	}

	function so_aff_menu(){

		global $plugin_url;

		// Add Setting Page
		add_menu_page(
			'SixOm Affiliates',
			'SixOm Affiliates',
			'manage_options',
			'soaffilate',
			array( $this, 'soaffilates_option_page'),
			$plugin_url . '/img/icon.png'
		);

		add_submenu_page(
			'soaffilate',
			'Videos',
			'Videos',
			'manage_options',
			'affvideos',
			array($this, 'so_videos_page')
		);

	}

	function soaffilates_option_page(){

		if( !current_user_can('manage_options') ){
			wp_die('You do not have permission to access this page');
		}

		global $plugin_url;
		global $options;
		global $helpFuncs;

		require('inc/options-page.php');

	}

	function so_videos_page(){

		if( !current_user_can('manage_options') ){
			wp_die('You do not have permission to access this page');
		}

		global $plugin_url;
		global $options;
		global $helpFuncs;

		require('inc/videos-page.php');

	}

	function soaff_load_admin_assets(){
		
		wp_enqueue_style('soff_styles', plugins_url( 'so-affiliates/css/admin_styles.css' ));
		wp_enqueue_script('soff_main_js', plugins_url( 'so-affiliates/js/main.js' ), array('jquery'));
		wp_enqueue_script('soff_product_js', plugins_url( 'so-affiliates/js/product.js' ), array('soff_main_js'));

		if(isset($_GET['page'])){
			if($_GET['page'] == 'soaffilate'){
				wp_enqueue_script('soff_options_js', plugins_url( 'so-affiliates/js/options.js' ), array('soff_main_js'));
			}
			if($_GET['page'] == 'affvideos'){

				wp_enqueue_script('soff_form_js', plugins_url( 'so-affiliates/js/form.js' ), array('soff_main_js'));
				wp_enqueue_script('soff_popup_js', plugins_url( 'so-affiliates/js/popup.js' ), array('soff_main_js'));

				wp_register_script('soff_videos_js', plugins_url( 'so-affiliates/js/videos.js' ), array('jquery', 'soff_form_js'));
				wp_localize_script('soff_videos_js', 'soVideosAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' )));
				wp_enqueue_script('soff_videos_js');

			}
		}

	}

	function soaff_load_front_assets(){

		wp_enqueue_style('soff_front_styles', plugins_url( 'so-affiliates/css/frontaff.css' ));
		wp_enqueue_script('shop_js', plugins_url( 'so-affiliates/js/shop.js' ), array('jquery'));

		if(is_single()){
			wp_enqueue_script('frontend_video_js', plugins_url( 'so-affiliates/js/frontend_video.js' ), array('jquery'));
		}
	
	}

	function setup_aff_shop(){

		require('so-affshop.php');
		$affShop = new AffShop();

		require('so-merchants.php');
		$merchants = new Merchants();

		require('so-videos.php');
		$videos = new Videos();

		require('so-attributes.php');
		$videos = new SoAttributes();

	}

	function __construct() {
		
		// database
		register_activation_hook( __FILE__, array($this, 'setup_database_tables') );

		// Add Menu
		add_action( 'admin_menu', array( $this, 'so_aff_menu' ) );

		// Add Styles and Scripts
		add_action( 'admin_head', array( $this, 'soaff_load_admin_assets' ));
		add_action( 'wp_enqueue_scripts', array($this, 'soaff_load_front_assets' ));

		// Add Aff Products Post Type
		$this->setup_aff_shop();

	}

}

// Get the show on the road.
$so_affilates = new SoAffilates();

?>