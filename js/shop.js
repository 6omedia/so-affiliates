
jQuery(document).ready(function($){

	var dropDownTriangle = $('.add_ddtri');

	dropDownTriangle.on('click', function(e){

		e.preventDefault();

		var theAtag = $(this).parent();
		var childUl = $(this).parent().next();

		if($(this).hasClass('dd_open')){
			childUl.slideUp(200);
			$(this).removeClass('dd_open');
			theAtag.removeClass('dd_active');
		}else{
			childUl.slideDown(200);
			$(this).addClass('dd_open');
			theAtag.addClass('dd_active');
		}

	});

});