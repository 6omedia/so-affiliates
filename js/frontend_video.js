var tag = document.createElement('script');

// var $ = jQuery;

tag.src = "https://www.youtube.com/iframe_api";
var firstScriptTag = document.getElementsByTagName('script')[0];
firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

// 3. This function creates an <iframe> (and YouTube player)
//    after the API code downloads.

function SoAffVideoPlayer(playerId){

	var thisPlayer = this;
	this.currentVideoIndex = 0;
	this.done = false;
	this.interval;
	this.stayStill = false;
	this.time;

	this.productTimes = null;

	this.pTimeIndex = 0;

	this.player = new YT.Player(playerId, {
		height: '390',
		width: '640',
		videoId: jQuery('#youtubePlayer').data('ytid'),
		events: {
			'onReady': function(event){
				thisPlayer.productTimes = thisPlayer.getProductTimesArray();
			},
			'onStateChange': function(event){
				if(event.data == YT.PlayerState.PLAYING && !thisPlayer.done) {
					thisPlayer.interval = setInterval(function(){

							var currentTime = thisPlayer.player.getCurrentTime();

							thisPlayer.checkSeeking(currentTime);

							thisPlayer.time = currentTime;

							if(thisPlayer.time > thisPlayer.productTimes[thisPlayer.pTimeIndex].start){
								if(!thisPlayer.stayStill){

									var productIndex = thisPlayer.findProductIndexByTime(currentTime);
									thisPlayer.currentVideoIndex = productIndex;
									thisPlayer.nextProduct(thisPlayer.currentVideoIndex);

									thisPlayer.stayStill = true;

								}else{
									if(thisPlayer.time > thisPlayer.productTimes[thisPlayer.pTimeIndex].end){

										if(thisPlayer.pTimeIndex < thisPlayer.productTimes.length - 1){
											thisPlayer.pTimeIndex++;
											thisPlayer.stayStill = false;
										}else{
											thisPlayer.stayStill = true;
										}
										
									}
								}
							}

						}, 500);
					thisPlayer.done = true;
				}
			}
		}
	});

	var thisPlayer = this;

	jQuery('.soaff_arrows.left').on('click', function(){
	
		if(thisPlayer.currentVideoIndex > 0){
			thisPlayer.currentVideoIndex--;	
		}else{
			thisPlayer.currentVideoIndex = thisPlayer.productTimes.length - 1;
		}
		
		thisPlayer.nextProduct(thisPlayer.currentVideoIndex);
		// thisPlayer.player.seekTo(thisPlayer.productTimes[thisPlayer.currentVideoIndex].start);

	});

	jQuery('.soaff_arrows.right').on('click', function(){

		if(thisPlayer.currentVideoIndex < thisPlayer.productTimes.length - 1){
			thisPlayer.currentVideoIndex++;	
		}else{
			thisPlayer.currentVideoIndex = 0;
		}
		
		thisPlayer.nextProduct(thisPlayer.currentVideoIndex);
		// thisPlayer.player.seekTo(thisPlayer.productTimes[thisPlayer.currentVideoIndex].start);

	});

	jQuery('.soaff_videoslider .listHeader').on('click', function(){

		if(jQuery(this).hasClass('opened')){
			jQuery(this).removeClass('opened');
		}else{
			jQuery(this).addClass('opened');
		}

	});

	jQuery('.soaff_video .time').on('click', function(){

		console.log('njkjl');

		var time = thisPlayer.getSeconds('00:' + jQuery(this).text());
		thisPlayer.player.seekTo(time);

	});

}

SoAffVideoPlayer.prototype.checkSeeking = function(changedTime){

	var seeked = false;
	var range = {
		low: this.time - 3,
		high: this.time + 3
	};

	if(changedTime < range.low || changedTime > range.high){
	
		seeked = true;

		var productIndex = this.findProductIndexByTime(changedTime);
		this.currentVideoIndex = productIndex;
		this.nextProduct(this.currentVideoIndex);
		this.stayStill = false;

	}	

	return seeked;

};

SoAffVideoPlayer.prototype.findProductIndexByTime = function(changedTime) {

	var productIndex = 0;

	// var thisPlayer = this;

	jQuery(this.productTimes).each(function(index){
		if(changedTime > this.start && changedTime < this.end){
			productIndex = index;
			return false;
		}
	});

	return productIndex;

};

SoAffVideoPlayer.prototype.getSeconds = function(timeString) {
	
	var timeElements = timeString.split(':');
	var hours = parseInt(timeElements[0]);
	var mins = parseInt(timeElements[1]);
	var seconds = parseInt(timeElements[2]);

	return (hours * 60 * 60) + (mins * 60) + seconds;

};

SoAffVideoPlayer.prototype.getProductTimesArray = function(videoLength) {
	
	var ptArray = [];
	var thisPlayer = this;

	var productLis = jQuery('#soaff_video_product_list > li');
	productLis.each(function(index){

		var end;

		if(index == productLis.length - 1){
			end = 5000;
		}else{
			end = thisPlayer.getSeconds(jQuery(productLis[index + 1]).data('start'));
		}

		ptArray.push({
			start: thisPlayer.getSeconds(jQuery(this).data('start')),
			end: end
		});
	});

	console.log('Array ', ptArray);

	return ptArray;

};

SoAffVideoPlayer.prototype.onPlayerReady = function(event) {
	
};

SoAffVideoPlayer.prototype.nextProduct = function(index) {

	var productLis = jQuery('#soaff_video_product_list > li');

	//console.log('Product List ', productLis);
	jQuery(productLis).hide();
	//console.log(jQuery(productLis)[this.currentVideoIndex]);
	var li = jQuery(productLis)[index];
	jQuery(li).show();

	console.log('So no its... ', index);

	// this.currentVideoIndex++;

}

////////////////////////////////////////////////////////////

function onYouTubeIframeAPIReady() {

	var soAddVideoPlayer = new SoAffVideoPlayer('youtubePlayer');

}