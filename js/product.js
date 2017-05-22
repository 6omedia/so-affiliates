jQuery(document).ready(function($){

	/*********************************

		MERCHANTS

	**********************************/

	var merchantTable = $('#merchantTable');
	var addMerchantBtn = $('#addMerchant');
	var removeMerchantBtn = $('.removeMerchant');

	var merchantInput = $('#q_merchant');
	var linkInput = $('#q_link');
	var priceInput = $('#q_price');

	function addMerchant(merchant, merchantId, link, price){

		var row = '<tr>';
			row += '<td>';
			row += '<input name="merchants[]" value="' + merchant + '" readonly>';
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

	/*********************************

		PROS AND CONS

	**********************************/

	class Validator {

		isValid(){

			var valid = false;

			for(var i=0; i<this.fields.length; i++){
				if(this.fields[i].val() != ""){
					valid = true;
				}
			}

			return valid;

		}

		constructor(fields){
			this.fields = fields;
		}

	}

	class ListManager {

		addItem(){
			var value = this.input.val();
			var name = this.name;

			var thisList = this;

			var li = $('<li></li>');
			var input = $('<input name="' + name + '[]" type="text" value="' + value + '">');
			var span = $('<span class="dashicons dashicons-no-alt"></span>').on('click', function(){
				thisList.removeItem(this);
			});

			li.append(input);
			li.append(span);
			this.list.append(li);

			this.input.val('');
		}

		removeItem(span){
			$(span).parent().remove();
		}

		setUpListeners(){
			var thisList = this;
			var validator = new Validator([this.input]);

			this.addBtn.on('click', function(){
				if(validator.isValid()){
					thisList.addItem();
				}else{
					alert('Can\'t be empty');
				}
			});

			$(this.list).find('span').on('click', function(){
				thisList.removeItem(this);
			});
		}

		constructor(addBtn, removeBtn, list, input, name){
			this.addBtn = addBtn;
			this.removeBtn = removeBtn;
			this.list = list;
			this.input = input;
			this.name = name;
			this.setUpListeners();
		}

	}

	var proList = new ListManager(
						$('#add_pro_button'), 
						$('.removeProBtn'), 
						$('#proList'), 
						$('#pro'), 
						'pro'
					);

	var conList = new ListManager(
						$('#add_con_button'), 
						$('.removeConBtn'), 
						$('#conList'), 
						$('#con'), 
						'con'
					);

});