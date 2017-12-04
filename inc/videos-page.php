<div class="wrap soaff">
	<h1>Manage Videos</h1>

	<ul class="addvid_bar">
		<li>
			<label>Name</label>
			<input id="q_name" type="text">
		</li>
		<li>
			<label>YouTube Video ID</label>
			<input id="q_ytvid" type="text">
		</li>
		<li>
			<div class="button" id="addVideo">Add New Video</div>
		</li>
	</ul>

	<div class="theVideoBox">
		<ul class="side">
			<?php

				global $wpdb;
				$rows = $wpdb->get_results('SELECT * FROM ' . $wpdb->prefix . 'aff_video');

				$first = true;
				foreach ($rows as $row) {
					if($first){
						echo '<li class="active" data-videoid="' . $row->id . '">' . $row->name . '</li>';
					}else{
						echo '<li data-videoid="' . $row->id . '">' . $row->name . '</li>';
					}
					$first = false;
				}

			?>
		</ul>
		<div class="soaff_mainvidsection" data-initvidid="<?php echo $rows[0]->id; ?>" data-ytvidid="<?php echo $rows[0]->yt_video_id; ?>">
			<?php

				$vidName = '';
				$vidYtid = '';
				$productRows = false;

				if(sizeof($rows) > 0){
					$vidId = $rows[0]->id;
					$vidName = $rows[0]->name;
					$vidYtid = $rows[0]->yt_video_id;
					global $wpdb;
					$productRows = $wpdb->get_results('SELECT * FROM ' . $wpdb->prefix . 'aff_video_products WHERE video_id=' . $vidId);
				}

			?>

			<div class="edit_box">
				<label>Title</label>
				<input id="q_current_name" value="<?php echo $vidName; ?>">
				<div class="edit"></div>
			</div>

			<div class="savid_x"></div>

			<!-- video -->
			<div id="youtubeVideo">
			<?php if(sizeof($rows) > 0){ ?>

				<iframe width="560" height="315" src="https://www.youtube.com/embed/<?php echo $vidYtid; ?>" frameborder="0" allowfullscreen></iframe>

			<?php } ?>
			</div>

			<div class="edit_box">
				<label>YouTube ID</label>
				<input id="q_current_ytvid" value="<?php echo $vidYtid; ?>">
				<div class="edit"></div>
			</div>

			<!-- table of products -->
			<table>
				<tr>
					<th>
						<label>Time:</label>
						<input type="number" id="q_mins" min="0">
						<span>:</span>
						<input type="number" id="q_seconds" min="0" max="59">
					</th>
					<th>
						<label>Product ID:</label>
						<?php

							$products = get_posts(array(
									'post_type' => 'affproducts'
								));

						?>
						<select id="q_product">
							<option value="">- select product -</option>
							<?php foreach($products as $product){ ?>
								<option value="<?php echo $product->ID; ?>">
									<?php echo $product->post_title; ?>
								</option>
							<?php } ?>
						</select>
					</th>
					<th>
						<label>Title Override:</label>
						<input type="text" id="q_titleoveride">
					</th>
					<th>
						<label></label>
						<div class="button add_product">Add Product</div>
					</th>
				</tr>
			</table>

			<div class="products">
				<table id="productTable">
					<?php 

						if($productRows){
				
							foreach($productRows as $pRow){
								echo '<tr><td>' . $pRow->video_time .'</td>';
								echo '<td>' . $pRow->linkorproductcode . '</td>';
								echo '<td>' . $pRow->link_text . '</td>';
								echo '<td><div class="x">x</div></td></tr>';
							}

						} 

					?>
				</table>
			</div>

			<div class="button button-primary" data-videoindex="0" id="saveVideo">Save</div>

		</div>
	</div>
</div>