jQuery(document).ready(function($){

	// vars
	var merchantTable = $('#merchantTable');
	var addMerchantBtn = $('#addMerchant');
	var removeMerchantBtn = $('.removeMerchant');

	var merchantInput = $('#q_merchant');
	var linkInput = $('#q_link');
	var priceInput = $('#q_price');

	function addMerchant(merchant, merchantId, link, price){

		var row = '<tr>';
			row += '<td>';
			row += '<input name="merchants[]" value="' + merchant + '" disabled>';
			row += '<input name="merchantids[]" value="' + merchantId + '" type="hidden">';
			row += '</td>';
			row += '<td>';
			row += '<input name="links[]" value="' + link + '">';
			row += '</td>';
			row += '<td>';
			row += '<input name="prices[]" value="' + price + '">';
			row += '</td>';
			row += '<td>';
			row += '<div class="removeBtn"><span class="dashicons dashicons-no-alt"></span></div>';
			row += '</td>';
		row += '</tr>';

		merchantTable.append(row);

	}

	addMerchantBtn.on('click', function(){

		var merchant = $('#merchantInput option:selected').text();
		var merchantId = $('#merchantInput').val();
		var link = $('#linkInput').val();
		var price = $('#priceInput').val();

		addMerchant(merchant, merchantId, link, price);

	});

	$('table').on('click', '.removeBtn', function(){

		$(this).parent().parent().remove();

	});

});