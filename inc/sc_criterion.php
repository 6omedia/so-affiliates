<?php

	$productData = new ProductData($postid);
	$criterion = $productData->getCriterion();

?>

<div class="criterion">
	<?php

		if($criterion){

			echo '<ul class="criterion_list">';

			foreach($criterion as $key => $criteria){
				echo '<li>';
				echo '<p>' . ucwords($key) . '</p>';
				echo '<div class="bar">';
				echo '<div class="soaff_criteria_bar" style="width: ' . $criteria * 10 . '%"></div>';
				echo '</div>';
				echo '<div class="criteria_num">' . $criteria . ' / 10</div>';
				echo '</li>';
			}

			echo '</ul>';

		}

	?>
</div>