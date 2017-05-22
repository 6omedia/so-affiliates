
jQuery(document).ready(function($){

	function updateShopCatsSelectBox(){

		var taxList = $('#taxList');
		var taxCheckBoxes = $('#taxList li input');
		var taxSelect = $('#shop_categories');

		taxCheckBoxes.on('change', function(){

			taxSelect.empty();
			taxCheckBoxes.each(function(){
				if($(this).attr('checked')){
					taxSelect.append(
						'<option value="' + $(this).val() + '">' + $(this).val() + '</option>'
					);
				}
			});

		});

	}

	updateShopCatsSelectBox();

});