<?php 

	$theOptions = get_option('soaffiliates'); 

	if(isset($theOptions['disclosure'])){ ?>

		<div class="disclosure">
			Disclosure
			<span class="tooltiptext">
				<div class="tri"></div>
				<?php echo $theOptions['disclosure']; ?>
			</span>
		</div>
	
<?php

	}else{
		echo 'No Disclosure Set';
	}
	
?>
