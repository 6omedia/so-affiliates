<div class="affproducts_box list">

	<table>
		<?php foreach ($products as $product){ ?>

			<tr>
				<td class="img_td"><?php echo get_the_post_thumbnail($product->ID, 'thumbnail'); ?></td>
				<td><?php echo $product->post_title; ?></td>
				<td class="price">£<?php echo number_format($product->cheapest['price'], 2); ?></td>
				<td><a class="add_buy" href="<?php echo $product->cheapest['link']; ?>">View</a></td>
			</tr>

		<?php } ?>
	</table>
</div>