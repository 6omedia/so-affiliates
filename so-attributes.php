<?php

	class SoAttributes {

		// A callback function to add a custom field to our "presenters" taxonomy  
		function attributes_taxonomy_custom_fields($tag) {  
			   // Check for existing taxonomy meta for the term you're editing  
			    $t_id = $tag->term_id; // Get the ID of the term you're editing  
			    $term_meta = get_option( "taxonomy_term_$t_id" ); // Do the check  

			?>

			<tr class="form-field">  
			    <th scope="row" valign="top">  
			        <label for="presenter_id"><?php _e('Attributes'); ?></label>  
			    </th>  
			    <td>  
			        <input type="text" name="term_meta[attributes]" id="term_meta[attributes]" size="25" style="width:60%;" value="<?php echo $term_meta['attributes'] ? $term_meta['attributes'] : ''; ?>"><br />  
			        <span class="description"><?php _e('List the attributes that can be set for this term seperated by commas'); ?></span>  
			    </td>  
			</tr>

			<tr class="form-field">  
			    <th scope="row" valign="top">  
			        <label for="presenter_id"><?php _e('Criterion'); ?></label>  
			    </th>  
			    <td>  
			        <input type="text" name="term_meta[criterion]" id="term_meta[criterion]" size="25" style="width:60%;" value="<?php echo $term_meta['criterion'] ? $term_meta['criterion'] : ''; ?>"><br />  
			        <span class="description"><?php _e('List the criterion that can be set for this term seperated by commas'); ?></span>  
			    </td>  
			</tr>
			  
			<?php  
		}  

		// A callback function to save our extra taxonomy field(s)  
		function save_taxonomy_custom_fields( $term_id ) {  
		    if ( isset( $_POST['term_meta'] ) ) {  
		        $t_id = $term_id;  
		        $term_meta = get_option( "taxonomy_term_$t_id" );  
		        $cat_keys = array_keys( $_POST['term_meta'] );  
		            foreach ( $cat_keys as $key ){  
		            if ( isset( $_POST['term_meta'][$key] ) ){  
		                $term_meta[$key] = $_POST['term_meta'][$key];  
		            }  
		        }  
		        //save the option array  
		        update_option( "taxonomy_term_$t_id", $term_meta );  
		    }  
		}

		function attribute_shortcode( $atts, $content = null ){

			global $post;
			global $wpdb;

			require_once('so-productdata.php');

			extract( 
				shortcode_atts( 
					array(
						'postid' => ''
					),
					$atts
				)
			);

			if($postid != ''){

				ob_start();
				require('inc/sc_attributes.php');
				$content = ob_get_clean();
				return $content;

			}

		}

		function criterion_shortcode( $atts, $content = null ){

			global $post;
			global $wpdb;

			// require_once('so-productdata.php');

			extract( 
				shortcode_atts( 
					array(
						'postid' => ''
					),
					$atts
				)
			);

			if($postid != ''){

				ob_start();
				require('inc/sc_criterion.php');
				$content = ob_get_clean();
				return $content;

			}

		}

		function __construct() {

			$theTaxonomy = get_option('soaffiliates')['shop_categories'];

			// Add the fields to the "presenters" taxonomy, using our callback function  
			add_action( $theTaxonomy . '_edit_form_fields', array($this, 'attributes_taxonomy_custom_fields' ), 10, 2 );  
	  
			// Save the changes made on the "presenters" taxonomy, using our callback function  
			add_action( 'edited_' . $theTaxonomy, array($this, 'save_taxonomy_custom_fields' ), 10, 2 );

			add_shortcode( 'aff_attributes', array($this, 'attribute_shortcode') );
			add_shortcode( 'aff_criterion', array($this, 'criterion_shortcode') );

		}

	}

?>