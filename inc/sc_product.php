<div class="affproducts_box merchants_box">
	<div class="affboxcont">
		<a href="<?php echo get_permalink($product->ID); ?>">
			<h3><?php echo $product->post_title; ?></h3>
		</a>
		
		<a href="<?php echo get_permalink($product->ID); ?>">
			<?php echo get_the_post_thumbnail($product->ID, 'medium'); ?>
		</a>

		<table class="table_merchants">
			<?php foreach ($merchants as $merchant) { ?>
				
				<tr>
					<td class="img_td"><img src="<?php echo $merchant['img']; ?>" alt="<?php echo $merchant['name']; ?>"></td>
					<td class="price">£<?php echo number_format($merchant['price'], 2); ?></td>
					<td><a class="add_buy" href="<?php echo $merchant['link']; ?>">View</a></td>
				</tr>

			<?php } ?>
		</table>
		<div class="disclosureCenter">
			<?php include(plugin_dir_path(__FILE__) . '../inc/disclosure.php'); ?>
		</div>
	</div>
</div>