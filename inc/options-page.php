<?php

	$shop_name = '';
	$affTaxes = [];
	$shop_category = 'categories';

	if($helpFuncs->hasFormSubmitted('so_options_form_submitted')){

		$options['shop_name'] = $_POST['shop_name'];
		$options['aff_product_taxonomies'] = $_POST['aff_product_taxonomies'];
		$options['shop_categories'] = $_POST['shop_categories'];
		$options['last_updated'] = time();

		update_option('soaffiliates', $options);

	}

	$options = get_option('soaffiliates');
	
	if($options != ''){
		
		if($options['shop_name'] != '')
			$shop_name = $options['shop_name'];

		if($options['aff_product_taxonomies'] != '')
			$affTaxes = $options['aff_product_taxonomies'];

		if($options['shop_categories'] != '')
			$shop_category = $options['shop_categories'];

	}

?>

<div class="wrap">
	<h1>Manage <?php echo $shop_name; ?> Shop</h1>
	<div id="dashboard-widgets-wrap">
	    <div id="dashboard-widgets" class="metabox-holder">
	    	<div id="postbox-container-1" class="postbox-container">
	        	<div id="normal-sortables" class="ui-sortable meta-box-sortables">
	                <!-- BOXES -->
	                <div class="postbox">
	                	<div class="inside">
		                	<h2>Main Settings</h2>
		                	<div class="inside">
		                		<form name="so_options_form" method="post" action="">

		                			<input type="hidden" name="so_options_form_submitted" value="Y">

		                			<?php $taxes = $helpFuncs->get_aff_taxonomies(); ?>

		                			<table class="form-table">
		                				<tr>
		                					<td>
		                						<label for="shop_name">Shop Name</label>
		                					</td>
		                					<td>
		                						<input name="shop_name" id="shop_name" type="text" value="<?php echo $shop_name; ?>">
		                					</td>
		                				</tr>
		                				<tr>
		                					<td>
		                						<label for="taxes">Taxonomies for Products</label>
		                					</td>
		                					<td>
		                						<ul id="taxList">
		                						<?php foreach ($taxes as $tax) { 

		                								$checked = '';

		                								foreach ($affTaxes as $affTax) {
		                									if($tax == $affTax){
		                										$checked = 'checked';
		                									}
		                								}
		                							
		                								?>

			                								<li>
			                									<input id="taxcheck_<?php echo $tax; ?>" type="checkbox" value="<?php echo $tax; ?>" name="aff_product_taxonomies[]" <?php echo $checked; ?>>
				                								<label for="taxcheck_<?php echo $tax; ?>">
				                									<?php echo $tax; ?>
				                								</label>
			                								</li>
	                									
	                									<?php 

		                							} 

		                						?>
		                						</ul>
		                					</td>
		                				</tr>
		                				<tr>
		                					<td>
		                						<label for="shop_categories">Select taxonomy for shop categories</label>
		                					</td>
		                					<td>
		                						<select id="shop_categories" name="shop_categories">
		                							<?php foreach ($affTaxes as $affTax) { 

		                								echo '<option value="' . $affTax . '" '; 
		                								if($affTax == $shop_category)
		                									echo 'selected';
		                								echo '>';
		                								echo $affTax;
		                								echo '</option>';

		                							 } ?>
		                						</select>
		                					</td>
		                				</tr>
		                			</table>
		                			<p>
		                				<input class="button-primary" type="submit" name="shop_name_submit" value="Save">
		                			</p>
		                		</form>
		                	</div>
	                	</div>
	                </div>
	            </div>
	        </div>
	        <div class="postbox-container">
	        	<div id="normal-sortables" class="ui-sortable meta-box-sortables">
	                <!-- BOXES -->
	                <div class="postbox">
	                	<div class="inside">
	                		<h2>Views/Clicks</h2>
	                	</div>
	                </div>
	            </div>
	        </div>
	    </div>
	</div>
</div>

<!-- <div class="wrap">
	<div id="poststuff" class="so-affiliates">
		<h1>Yeah Affiliate Plugin</h1>
		<div id="side-sortables">
			<div class="meta-box-sortables ui-sortable">
				<div class="postbox">
					<h3>Settings</h3>
				</div>
			</div>
		</div>
		<div id="post-body">
			<div id="post-body-content">
	            <div id="titlediv">
	            	
	            </div>
            </div>
		</div>
	</div>
</div> -->