<?php

class ProductData {
	
	private $merchants = [];
	private $instagramPictures = ['', '', '', ''];
	private $reviews = array(
				'rating' => '5',
				'pros' => array(),
				'cons' => array()
			);
	
	function getMerchants(){
		return $this->merchants;
	}

	function getInstagramPictures(){
		return $this->instagramPictures;
	}

	function getReviews(){
		return $this->reviews;
	}

	function loadInstagramPictures($customData){

		$this->instagramPictures[0] = $customData['instagram_url_1'][0];
		$this->instagramPictures[1] = $customData['instagram_url_2'][0];
		$this->instagramPictures[2] = $customData['instagram_url_3'][0];
		$this->instagramPictures[3] = $customData['instagram_url_4'][0];

	}

	function loadMerchants($customData){

		global $post;
			
		$merchants = unserialize($customData['merchants'][0]);
		$merchantids = unserialize($customData['merchantids'][0]);
		$links = unserialize($customData['links'][0]);
		$prices = unserialize($customData['prices'][0]);

		for($i=0; $i<sizeof($merchants); $i++){

			$merchant = array(
				'img' => wp_get_attachment_url( get_post_thumbnail_id($merchantids[$i]), 'thumbnail' ),
				'name' => $merchants[$i],
				'id' => $merchantids[$i],
				'link' => $links[$i],
				'price' => $prices[$i]
			);

			$this->merchants[] = $merchant;

		}

	}

	function loadReviews($customData){

		global $post;
			
		$rating = $customData['rating'][0];
		$pros = unserialize($customData['pro'][0]);
		$cons = unserialize($customData['con'][0]);

		$this->reviews['rating'] = $rating;
		$this->reviews['pros'] = $pros;
		$this->reviews['cons'] = $cons;

	}

	function __construct($customData){

		$this->loadInstagramPictures($customData);
		$this->loadMerchants($customData);
		$this->loadReviews($customData);

	}

}

?>