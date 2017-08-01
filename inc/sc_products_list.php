<div class="affproducts_box list">

	<table>
		<?php foreach ($products as $product){ ?>

			<tr>
				<td class="img_td">
					<a href="<?php echo get_permalink($product->ID); ?>">
						<?php echo get_the_post_thumbnail($product->ID, 'thumbnail'); ?>
					</a>
				</td>
				<td>
					<a href="<?php echo get_permalink($product->ID); ?>">
						<?php echo $product->post_title; ?>
					</a>
				</td>
				<td class="price">£<?php echo number_format($product->cheapest['price'], 2); ?></td>
				<td><a class="add_buy" href="<?php echo $product->cheapest['link']; ?>">View</a></td>
			</tr>

		<?php } ?>
	</table>
</div>