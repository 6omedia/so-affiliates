<?php

/* 
 * Plugin Name: SixOm Affiliates
 * Plugin URI: http://6omedia.co.uk
 * Description: Manage affilate products with multiple merchants
 * Version: 1.0
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

		// Add Merchant Page
		add_submenu_page(
			'soaffilate',
			'Merchants',
			'Merchants',
			'manage_options',
			'merchants',
			array($this, 'soaffilates_merchants_page')
		);

	}

	function soaffilates_option_page(){

		if( !current_user_can('manage_options')){
			wp_die('You do not have permission to access this page');
		}

		global $plugin_url;
		global $options;
		global $helpFuncs;

		require('inc/options-page.php');

	}

	function soaffilates_merchants_page(){

		if( !current_user_can('manage_options')){
			wp_die('You do not have permission to access this page');
		}

		global $plugin_url;
		global $options;
		global $helpFuncs;

		require('inc/merchants.php');

	}

	// function createDbTables(){

	// 	global $wpdb;
	// 	$charset_collate = $wpdb->get_charset_collate();

	// 	$table_name = $wpdb->prefix . 'so_merchants';

	// 	$sql = "CREATE TABLE $table_name (
	// 		id mediumint(9) NOT NULL AUTO_INCREMENT,
	// 		name varchar(60) DEFAULT '' NOT NULL,
	// 		img varchar(60) DEFAULT '' NOT NULL,
	// 		UNIQUE KEY id (id)
	// 	) $charset_collate;";

	// 	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	// 	dbDelta( $sql );

	// }

	function soaff_load_admin_assets(){
		wp_enqueue_style('soff_styles', plugins_url( 'so-affiliates/css/admin_styles.css' ));
		wp_enqueue_script('soff_main_js', plugins_url( 'so-affiliates/js/main.js' ), array('jquery'));
		wp_enqueue_script('soff_merchant_js', plugins_url( 'so-affiliates/js/merchants.js' ), array('soff_main_js'));
	}

	function soaff_load_front_assets(){
		wp_enqueue_style('soff_front_styles', plugins_url( 'so-affiliates/css/frontaff.css' ));
	}

	function setup_aff_shop(){

		require('so-affshop.php');
		$affShop = new AffShop();

		require('so-merchants.php');
		$merchants = new Merchants();

	}

	function __construct() {
		
		// Setup Merchant Table in DB
		// register_activation_hook( __FILE__, array($this, 'createDbTables') );
		
		// Add Menu
		add_action( 'admin_menu', array( $this, 'so_aff_menu' ) );

		// Add Styles and Scripts
		add_action( 'admin_head', array( $this, 'soaff_load_admin_assets' ));
		add_action( 'wp_enqueue_scripts', array($this, 'soaff_load_front_assets'));

		// Add Aff Products Post Type
		$this->setup_aff_shop();

	}

}

// Get the show on the road.
$so_affilates = new SoAffilates();

?>