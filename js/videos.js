jQuery(document).ready(function($){

	/************************************************************************/

	function Model(obj){
		this.videos = obj.videos || [];
		if(obj.videos){
			this.currentVideo = obj.videos[0];
		}else{
			this.currentVideo = {};
		}
	}

	function View(){
		
	}

	function VideoManager(Model, View){

		var thisVm = this;

		var modelObj = {};

		if($('.theVideoBox .side li').length == 0){
			this.noVideo();
		}else{
			modelObj.videos = thisVm.getVideosFromView();
		}

		this.view = new View();
		this.model = new Model(modelObj);

		this.addVideoForm = new window.Form(soVideosAjax.ajaxurl, [
				{ id: 'q_name', validation: '' },
				{ id: 'q_ytvid', validation: '' }
			]);

		this.player;
		this.productTable = $('#productTable');

		/*** events ***/

		$('#addVideo').on('click', function(){
			
			var btn = this;

			var data = { action: 'so_add_video', q_name: $('#' + thisVm.addVideoForm.fields[0].id).val(),
				q_ytvid: $('#' + thisVm.addVideoForm.fields[1].id).val() };

			if(thisVm.addVideoForm.isValid()){
				$(btn).addClass('loading');
				thisVm.addVideoForm.send(data, function(data){
					if(data.success == 1){
						thisVm.reloadVideos(data.videos);
						thisVm.addVideoForm.resetForm();
					}else{
						alert('Something went wrong');
					}
					$(btn).removeClass('loading');
				});	
			}

		});

		$('.soaff').on('click', '.side li', function(){
			thisVm.switchVideo(this);
		});

		$('.savid_x').on('click', function(){
			thisVm.deleteVideo();
		});

		$('.add_product').on('click', function(){
			thisVm.addProduct();
		});

		$('#productTable').on('click', '.x', function(){
			$(this).closest('tr').remove();
		});

		$('#saveVideo').on('click', function(){
			thisVm.saveVideo($(this).data('videoindex'));
		});

	}

	VideoManager.prototype.getProductsFromTable = function() {
		var products = [];
		$('#productTable tr').each(function(){
			var tds = $(this).children();
			products.push({
				time: $(tds[0]).text(),
				productid: $(tds[1]).data('productid'),
				title: $(tds[2]).text()
			});
		});
		return products;
	};

	VideoManager.prototype.saveVideo = function(videoIndex) {

		var data = {
			action: 'so_saveVideo',
			video_id: parseInt(this.model.currentVideo.id),
			title: $('#q_current_name').val(),
			youtube_id: $('#q_current_ytvid').val(),
			products: this.getProductsFromTable()
		};

		console.log('DATA to send: ', data);

		var thisVm = this;

		$('.side').addClass('loading');

		$.ajax({
			url: soVideosAjax.ajaxurl,
			method: 'POST',
			dataType: 'json',
			data: data,
			success: function(data){
				console.log('DATA returned: ', data);
				if(data.success == 1){
					thisVm.reloadVideos(data.videos, videoIndex);
					$('#youtubeVideo iframe').attr('src', 'https://www.youtube.com/embed/' + $('#q_current_ytvid').val());
				}else{
					alert('Something went wrong');
				}
				$('.side').removeClass('loading');
			},
			error: function(a, b, c){
				alert('Something went wrong');
				console.log(a, b, c);
			}
		});

	};

	VideoManager.prototype.getVideosFromView = function() {
		var videos = [];
		$('.theVideoBox .side li').each(function(){
			videos.push({
				title: $(this).text(), 
				id: $(this).data('videoid')
			});
		});
		return videos;
	};

	VideoManager.prototype.addProduct = function() {

		var addProductForm = new window.Form('', [
				{ id: 'q_mins', validation: '' },
				{ id: 'q_seconds', validation: '' },
				{ id: 'q_product', validation: '' },
				{ id: 'q_titleoveride', validation: '' }
			]);

		if(addProductForm.isValid()){
			var data = addProductForm.buildSimpleFormData();
			var row = '<tr>';
			row += '<td>' + data.q_mins + ':' + data.q_seconds + '</td>';
			row += '<td data-productid="' + data.q_product + '">' + $('#q_product option[value="' + data.q_product + '"]').text() + '</td>';
			row += '<td>' + data.q_titleoveride + '</td>';
			row += '<td><div class="x">x</div></td>';
			row += '</tr>';
			this.productTable.append(row);
			$('#q_titleoveride').val('');
		}

	};

	VideoManager.prototype.reloadVideos = function(videos, index = null) {

		this.model.videos = videos;
		$('.soaff .side').empty();
		var str = '';
		for(var i=0; i<videos.length; i++) {
			if(index == i){
				str += '<li data-videoid="' + videos[i].id + '" class="active">' + videos[i].name + '</li>';	
			}else{
				str += '<li data-videoid="' + videos[i].id + '">' + videos[i].name + '</li>';
			}
			
		}
		$('.soaff .side').append(str);

	};

	VideoManager.prototype.doError = function(msg) {

	};

	VideoManager.prototype.switchVideo = function(li){

		var thisVm = this;
		var vidId = $(li).data('videoid');

		if(vidId){
			thisVm.showVideo();
		}

		// start loading
		$('.soaff_mainvidsection').addClass('loading');

		// apply active class
		$('.soaff .side li').removeClass('active');
		$(li).addClass('active');

		// ajax call
		$.ajax({
			url: soVideosAjax.ajaxurl,
			method: 'POST',
			dataType: 'json',
			data: {
				action: 'so_getVideo',
				video_id: vidId
			},
			success: function(data){
				if(data.success == 1){

					thisVm.model.currentVideo = data.video;
					// title
					$('#q_current_name').val(thisVm.model.currentVideo.name);
					// iframe video
					$('#youtubeVideo').empty();
					$('#youtubeVideo').append('<iframe width="560" height="315" src="https://www.youtube.com/embed/' + thisVm.model.currentVideo.yt_video_id + '" frameborder="0" allowfullscreen></iframe>');

					$('.soaff_mainvidsection').removeClass('loading');

					// youtube id
					$('#q_current_ytvid').val(thisVm.model.currentVideo.yt_video_id);

					$('#productTable').empty();

					for(var i=0; i<data.products.length; i++){
						var product = data.products[i];
						var row = '<tr>';
						row += '<td>' + product.video_time + '</td>';
						row += '<td data-productid="' + product.id + '">' + $('#q_product option[value="' + product.id + '"]').text() + '</td>';
						row += '<td>' + product.link_text + '</td>';
						row += '<td><div class="x">x</div></td>';
						row += '</tr>';
						$('#productTable').append(row);
					}

					$('#saveVideo').data('videoindex', $(li).index());

				}else{
					alert('Something went wrong');
				}
			},
			error: function(a, b, c){
				console.log('abc ', a, b, c);
			}
		});

	};

	VideoManager.prototype.deleteVideo = function() {

		var thisVm = this;
		var areYouSure = new window.PopUp(
			function(){
				$('#theVideoBox').addClass('loading');
				areYouSure.popDown();
				$.ajax({
					url: soVideosAjax.ajaxurl,
					method: 'POST',
					dataType: 'json',
					data: {
						action: 'so_removeVideo',
						video_id: thisVm.model.currentVideo.id
					},
					success: function(data){
						console.log(data);
						if(data.success == 1){
							thisVm.reloadVideos(data.videos);
							thisVm.resetScreen();
							if(data.videos.length > 0){
								if($($('.theVideoBox .side li').length) == 0){
									thisVm.noVideo();
								}
								thisVm.switchVideo($($('.theVideoBox .side li').get(0)));
							}
						}else{
							alert('Something went wrong');
						}
						$('#theVideoBox').removeClass('loading');
					},
					error: function(a, b, c){
						console.log('abc ', a, b, c);
						areYouSure.popDown();
					}
				});
			},
			function(){
				areYouSure.popDown();
			}
		);

		areYouSure.popUp('Are you sure you want to delete this video?');

	};

	VideoManager.prototype.resetScreen = function(){
		$('#q_current_name').val('');
		$('#youtubeVideo').empty();
		$('#q_current_ytvid').empty();
		$('.products').empty();
	};

	VideoManager.prototype.noVideo = function() {
		$('.soaff_mainvidsection').addClass('soaff_novideo');
	};

	VideoManager.prototype.showVideo = function() {
		$('.soaff_mainvidsection').removeClass('soaff_novideo');
	};

	var vidManager = new VideoManager(Model, View);
	
});