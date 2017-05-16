<?php

	$shop_name = '';

	if($helpFuncs->hasFormSubmitted('so_options_form_submitted')){

		$options['shop_name'] = $_POST['shop_name'];
		$options['last_updated'] = time();
		
		update_option('soaffiliates', $options);

	}

	$options = get_option('soaffiliates');
	
	if($options != ''){
		$shop_name = $options['shop_name'];
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
	                	<h2>Main Settings</h2>
	                	<div class="inside">
	                		<form name="so_options_form" method="post" action="">

	                			<input type="hidden" name="so_options_form_submitted" value="Y">

	                			<table class="form-table">
	                				<tr>
	                					<td>
	                						<label for="shop_name">Shop Name</label>
	                					</td>
	                					<td>
	                						<input name="shop_name" id="shop_name" type="text">
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
	        <div id="postbox-container-1" class="postbox-container">
	            <div id="normal-sortables" class="ui-sortable meta-box-sortables">
	                <!-- BOXES -->
	                <div class="postbox">
	                	<h2>Merchants</h2>
	                	<ul>
	                		<li><a href="">Boots</a></li>
	                		<li><a href="">Debanhams</a></li>
	                		<li><a href="">Superdrug</a></li>
	                	</ul>
	                	<button>View All</button>
	                	<button>Add</button>
	                </div>
	            </div>
	        </div>
	        <div class="postbox-container">
	        	<div id="normal-sortables" class="ui-sortable meta-box-sortables">
	                <!-- BOXES -->
	                <div class="postbox"><h2>Products</h2></div>
	            </div>
	        </div>
	        <div class="postbox-container">
	        	<div id="normal-sortables" class="ui-sortable meta-box-sortables">
	                <!-- BOXES -->
	                <div class="postbox"><h2>Views/Clicks</h2></div>
	            </div>
	        </div>
	        <div class="postbox-container">
	        	<div id="normal-sortables" class="ui-sortable meta-box-sortables">
	                <!-- BOXES -->
	                <div class="postbox"><h2>Other Stuff</h2></div>
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