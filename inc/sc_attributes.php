<?php

	$productData = new ProductData($postid);
	$attributes = $productData->getAttributes();

?>

<div class="attributes">
	<h3>Details</h3>
	<table class="attributes_table">
		<?php foreach($attributes as $key => $attr) { ?>
			<tr>
				<th><?php echo $key; ?>:</th>
				<td><?php echo $attr; ?></td>
			</tr>
		<?php } ?>
	</table>
</div>