<?php

class ProductData {
	
	private $merchants = [];
	private $instagramPictures = [];
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

		for($i=1; $i<5; $i++){
			$this->instagramPictures[] = array(
				'imgSrc' => $customData['instagram_url_' . $i][0],
				'pageUrl' => $customData['instagram_page_' . $i][0]
			);
		}

		// $img1 = array(
		// 	'imgSrc' => $customData['instagram_url_1'][0],
		// 	'pageUrl' => $customData['instagram_page_1'][0]
		// );

		// $img1 = array(
		// 	'imgSrc' => $customData['instagram_url_1'][0],
		// 	'pageUrl' => $customData['instagram_page_1'][0]
		// );

		// $img1 = array(
		// 	'imgSrc' => $customData['instagram_url_1'][0],
		// 	'pageUrl' => $customData['instagram_page_1'][0]
		// );

		// $img1 = array(
		// 	'imgSrc' => $customData['instagram_url_1'][0],
		// 	'pageUrl' => $customData['instagram_page_1'][0]
		// );

		// $this->instagramPictures[0] = $customData['instagram_url_1'][0];
		// $this->instagramPictures[1] = $customData['instagram_page_1'][0];

		// $this->instagramPictures[2] = $customData['instagram_url_2'][0];
		// $this->instagramPictures[3] = $customData['instagram_page_2'][0];

		// $this->instagramPictures[4] = $customData['instagram_url_3'][0];
		// $this->instagramPictures[5] = $customData['instagram_page_3'][0];

		// $this->instagramPictures[6] = $customData['instagram_url_4'][0];
		// $this->instagramPictures[7] = $customData['instagram_page_4'][0];

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

	function outputSimilarProducts($productId, $taxonomy){

		$terms = get_the_terms($productId, $taxonomy);
		$termArray = [];

		if(!empty($terms)){
			foreach($terms as $term){
				$termArray[] = $term->slug;
			}
		}

		$args = array(
			'post_type' => 'affproducts',
			'post__not_in' => array($productId),
			'tax_query' => array(
	            array(
	                'taxonomy' => $taxonomy,
	                'field' => 'slug',
	                'terms' => $termArray,
	            )
	        ),
			'posts_per_page' => 6
		);

		$simProducts = new WP_Query( $args );

		?>

		<div class="row">

			<?php if( $simProducts->have_posts() ) : while ( $simProducts->have_posts() ) : $simProducts->the_post(); ?>

				<div class="col-xs-6 col-sm-4">
					<div class="product">
						<a href="<?php the_permalink(); ?>">
							<?php the_post_thumbnail(); ?>
							<p><?php the_title(); ?></p>
						</a>
					</div>
				</div>

			<?php endwhile; else : ?>
			<?php endif; ?>

		</div>

		<?php

	}


	function __construct($productId){

		$customData = get_post_custom($productId);

		$this->loadInstagramPictures($customData);
		$this->loadMerchants($customData);
		$this->loadReviews($customData);

	}

}

?>