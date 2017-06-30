<div class="affproducts_box merchants_box">
	<h3><?php echo $product->post_title; ?></h3>
	<?php echo get_the_post_thumbnail($product->ID, 'medium'); ?>

	<table class="table_merchants">
		<?php foreach ($merchants as $merchant) { ?>
			
			<tr>
				<td class="img_td"><img src="<?php echo $merchant['img']; ?>" alt="<?php echo $merchant['name']; ?>"></td>
				<td class="price">£<?php echo number_format($merchant['price'], 2); ?></td>
				<td><a class="add_buy" href="<?php echo $merchant['link']; ?>">View</a></td>
			</tr>

		<?php } ?>
	</table>
</div>