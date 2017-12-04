<?php

	class Videos {

		function so_add_video(){

			$response['success'] = 0;

			// stuff goes here
			global $wpdb;
			$wpdb->insert($wpdb->prefix . 'aff_video', array(
			    'name' => $_POST['q_name'],
			    'yt_video_id' => $_POST['q_ytvid']
			));

			$lastid = $wpdb->insert_id;

			if($lastid){
				$rows = $wpdb->get_results('SELECT * FROM ' . $wpdb->prefix . 'aff_video');
				$response['videos'] = $rows;
				$response['success'] = 1;
			}

			echo json_encode($response);
		    die();

		}

		function so_getVideo(){

			$response['success'] = 0;

			global $wpdb;
			$results = $wpdb->get_results('SELECT * FROM ' . $wpdb->prefix . 'aff_video WHERE id=' . $_POST['video_id']);

			if($results){

				$products = $wpdb->get_results('SELECT * FROM ' . $wpdb->prefix . 'aff_video_products WHERE video_id=' . $_POST['video_id']);

				$response['success'] = 1;
				$response['video'] = $results[0];
				$response['products'] = $products;

			}

			echo json_encode($response);
		    die();

		}

		function so_removeVideo(){

			$response['success'] = 0;

			global $wpdb;
			$deleted = $wpdb->delete( $wpdb->prefix . 'aff_video', array( 'id' => $_POST['video_id'] ) );

			if($deleted){

				$results = $wpdb->get_results('SELECT * FROM ' . $wpdb->prefix . 'aff_video');

				$response['success'] = 1;
				$response['videos'] = $results;

			}

			echo json_encode($response);
		    die();

		}

		function outputProductValues($vidId, $products) {
			
			$values = '';
			$size = sizeof($products);
			for($i=0; $i<$size; $i++) {
				$values .= '(' . $vidId . ', "00:' . $products[$i]['time'] . '", "' . $products[$i]['title'] . '", ' . $products[$i]['productid'] . ')';
				if($i == $size - 1){
					$values .= ';';
				}else{
					$values .= ',';
				}
			}
			return $values;
		}

		function so_saveVideo(){

			$response['success'] = 0;

			global $wpdb;

			if(sizeof($_POST['products']) > 0){
				$deleted = $wpdb->delete( $wpdb->prefix . 'aff_video_products', array( 'video_id' => $_POST['video_id'] ) );
				$query = 'INSERT INTO ' . $wpdb->prefix . 'aff_video_products (video_id, video_time, link_text, linkorproductcode) VALUES ' . $this->outputProductValues($_POST['video_id'], $_POST['products']);
				$wpdb->query($query);
			}

			// update video
			
			$updateResult = $wpdb->query('UPDATE ' . $wpdb->prefix . 'aff_video SET name="' . $_POST['title'] . '", yt_video_id="' . $_POST['youtube_id'] . '" WHERE id="' . $_POST['video_id'] . '";');
			$response['error'] = $wpdb->last_error;
			$response['update'] = $updateResult;
			$response['videos'] = $wpdb->get_results('SELECT * FROM ' . $wpdb->prefix . 'aff_video');
			$response['success'] = 1;
			
			echo json_encode($response);
		    die();

		}

		function video_shortcode( $atts, $content = null ){

			global $post;
			global $wpdb;

			// require_once('so-productdata.php');

			extract( 
				shortcode_atts( 
					array(
						'id' => '',
						'list' => ''
					),
					$atts
				)
			);

			if($id != ''){

				$video =  $wpdb->get_results('SELECT * FROM ' . $wpdb->prefix . 'aff_video WHERE id=' . $id)[0];

				ob_start();
				require('inc/sc_video.php');
				$content = ob_get_clean();
				return $content;

			}

		}

		function __construct(){
		
			add_action( 'wp_ajax_so_add_video', array($this, 'so_add_video') );
			add_action( 'wp_ajax_nopriv_so_add_video', array($this, 'so_add_video') );

			add_action( 'wp_ajax_so_getVideo', array($this, 'so_getVideo') );
			add_action( 'wp_ajax_nopriv_so_getVideo', array($this, 'so_getVideo') );

			add_action( 'wp_ajax_so_removeVideo', array($this, 'so_removeVideo') );
			add_action( 'wp_ajax_nopriv_so_removeVideo', array($this, 'so_removeVideo') );

			add_action( 'wp_ajax_so_saveVideo', array($this, 'so_saveVideo') );
			add_action( 'wp_ajax_nopriv_so_saveVideo', array($this, 'so_saveVideo') );

			add_shortcode( 'affvideo', array($this, 'video_shortcode') );

		}

	}

?>