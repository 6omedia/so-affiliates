<?php

class helpful_funcs {

	function hasFormSubmitted($submittedName){

		if(isset($_POST[$submittedName])){
			$hidden_field = esc_html($_POST[$submittedName]);
			if($hidden_field == "Y"){
				return true;
			}else{
				return false;
			}
		}else{
			return false;
		}

	}
	
}

?>