
$(window).load(function(){
	// Gallery Slideshows Script
	if($('.thumbs.cycle-slideshow').length > 0) {
		$('.thumbs.cycle-slideshow').each(function() {
			
			// Define the root slider for this iteration
			var thumb_scroller = $(this);
			var slideshow_cont = thumb_scroller.closest('.gallery-slider-wrapper');
			
			// Display clicked thumbnail in the big stage (if needed)
			if(slideshow_cont.find(".stage.cycle-slideshow").length > 0) {
				
				thumb_scroller.find(".cycle-slide").click(function(){
					var index = thumb_scroller.data("cycle.API").getSlideIndex(this) % (slideshow_cont.find(".stage.cycle-slideshow img").length - 1);
					slideshow_cont.find(".stage.cycle-slideshow").cycle("goto", index);
				});
			
				// Add a 'selected' class to the thumbs that are clicked
				thumb_scroller.find('.cycle-slide').click(function() {
					var index = $(this).index();
					
					thumb_scroller.find('.cycle-slide').removeClass('selected').eq(index).addClass('selected');
				});
			}
			
			// Handle the clicks of the ticks
			slideshow_cont.find('.cycle-pager a').click(function() {
				var target_tick_index = $(this).index();
				var selected_tick_index = slideshow_cont.find('.cycle-pager a.selected').index();
				
				moveSlideshowPage(slideshow_cont,(target_tick_index - selected_tick_index < 0 ? 'right' : 'left'),Math.abs(target_tick_index - selected_tick_index));
			});
			
			// Handle the Next and Prev
			slideshow_cont.find('.cycle-next').click(function(event) {
				event.preventDefault();
				
				moveSlideshowPage(slideshow_cont,'left',1);
			});
			slideshow_cont.find('.cycle-prev').click(function(event) {
				event.preventDefault();
				
				moveSlideshowPage(slideshow_cont,'right',1);
			});
			
			// On Page Load
			$(window).load(function() {
				toggleSlideshowControls(slideshow_cont);
			});
		});
	}
});

var content_width = 940;
var thumbs_per_page = 6;

function moveSlideshowPage(slideshow_cont,direction,pages) {
	
	if(slideshow_cont.find('.cycle-carousel-wrap').data('current_page') == undefined) {
		slideshow_cont.find('.cycle-carousel-wrap').data('current_page',0);
		slideshow_cont.find('.cycle-carousel-wrap').data('total_pages',slideshow_cont.find('.cycle-pager a').length);
	}
	
	var direction_multiplier = direction == 'right' ? -1 : 1;
	var future_index = slideshow_cont.find('.cycle-carousel-wrap').data('current_page')+(direction_multiplier * pages);
	var slide_width = slideshow_cont.find('.thumbs.cycle-slideshow .cycle-slide').outerWidth(true);
	var move_correction = Math.ceil(((content_width-(slide_width * thumbs_per_page))/2)/thumbs_per_page);
		slide_width = slide_width - move_correction
	var move_pixels = slideshow_cont.find('.cycle-carousel-wrap').position().left - (direction_multiplier * (slide_width * thumbs_per_page) * pages);
		move_pixels = move_pixels-(move_pixels/(slide_width*thumbs_per_page));
	
	if((future_index == slideshow_cont.find('.cycle-carousel-wrap').data('total_pages') - 1 && 'right') || (slideshow_cont.find('.cycle-carousel-wrap').data('current_page') == slideshow_cont.find('.cycle-carousel-wrap').data('total_pages') - 1 && 'left')) {
		
		var incomplete_thumbs_per_page = slideshow_cont.find('.thumbs.cycle-slideshow .thumb').length - ((slideshow_cont.find('.cycle-carousel-wrap').data('total_pages') - 1) * thumbs_per_page);
		var thumb_padding = parseInt(slideshow_cont.find('.thumbs.cycle-slideshow .cycle-slide').css('padding-left').replace('px','')) + parseInt(slideshow_cont.find('.thumbs.cycle-slideshow .cycle-slide').css('padding-right').replace('px',''));
		var thumb_borders = parseInt(slideshow_cont.find('.thumbs.cycle-slideshow .cycle-slide').css('border-left-width').replace('px','')) + parseInt(slideshow_cont.find('.thumbs.cycle-slideshow .cycle-slide').css('border-right-width').replace('px',''));
		
		move_pixels = slideshow_cont.find('.cycle-carousel-wrap').position().left - (direction_multiplier * (slide_width * thumbs_per_page) * (pages - 1));
		move_pixels -= direction_multiplier * (slide_width * incomplete_thumbs_per_page);
		move_pixels -= direction_multiplier * (slideshow_cont.find('.thumbs.cycle-slideshow .cycle-slide').width() - slide_width - (thumb_padding - thumb_borders));
		move_pixels = move_pixels-(move_pixels/(slide_width*thumbs_per_page));
	}
	
	slideshow_cont.find('.cycle-carousel-wrap').animate({
		'left':move_pixels+'px'
	}, {
		duration: 'medium',
		progress: function() {
			toggleSlideshowControls(slideshow_cont);
		},
		done: function() {
			slideshow_cont.find('.cycle-carousel-wrap').data('current_page',future_index);
			slideshow_cont.find('.cycle-carousel-wrap').css('left',move_pixels+'px');
			toggleSlideshowControls(slideshow_cont);
		}
	});
	
	var selected_tick_index = slideshow_cont.find('.cycle-pager a.selected').index();
	slideshow_cont.find('.cycle-pager a').removeClass('selected').eq(selected_tick_index + (direction_multiplier * pages)).addClass('selected');
}

function toggleSlideshowControls(slideshow_cont) {

	var thumb_padding = parseInt(slideshow_cont.find('.thumbs.cycle-slideshow .cycle-slide').css('padding-left').replace('px','')) + parseInt(slideshow_cont.find('.thumbs.cycle-slideshow .cycle-slide').css('padding-right').replace('px',''));
	var thumb_borders = parseInt(slideshow_cont.find('.thumbs.cycle-slideshow .cycle-slide').css('border-left-width').replace('px','')) + parseInt(slideshow_cont.find('.thumbs.cycle-slideshow .cycle-slide').css('border-right-width').replace('px',''));
	
	if(Math.abs(slideshow_cont.find('.cycle-carousel-wrap').position().left) > (slideshow_cont.find('.cycle-carousel-wrap').width() - content_width - (thumb_padding + thumb_borders))) {
		slideshow_cont.find('.cycle-next').hide();
	} else {
		slideshow_cont.find('.cycle-next').show();
	}
	
	if(slideshow_cont.find('.cycle-carousel-wrap').position().left > thumb_padding * thumb_borders * -1) {
		slideshow_cont.find('.cycle-prev').hide();
	} else {
		slideshow_cont.find('.cycle-prev').show();
	}
	
	toggleShadows(slideshow_cont);
}

function toggleShadows(slideshow_cont) {
	
	var thumb_padding = parseInt(slideshow_cont.find('.thumbs.cycle-slideshow .cycle-slide').css('padding-left').replace('px','')) + parseInt(slideshow_cont.find('.thumbs.cycle-slideshow .cycle-slide').css('padding-right').replace('px',''));
	var thumb_borders = parseInt(slideshow_cont.find('.thumbs.cycle-slideshow .cycle-slide').css('border-left-width').replace('px','')) + parseInt(slideshow_cont.find('.thumbs.cycle-slideshow .cycle-slide').css('border-right-width').replace('px',''));
	
	if(slideshow_cont.find('.cycle-carousel-wrap').width() > content_width) {
		slideshow_cont.find('.thumbs-wrapper .thumbs-shadow.right').show();
	} else {
		slideshow_cont.find('.thumbs-wrapper .thumbs-shadow.right').hide();
	}
	if(slideshow_cont.find('.cycle-carousel-wrap').position().left < thumb_padding * thumb_borders * -1) {
		slideshow_cont.find('.thumbs-wrapper .thumbs-shadow.left').show();
	} else {
		slideshow_cont.find('.thumbs-wrapper .thumbs-shadow.left').hide();
	}
	if(Math.abs(slideshow_cont.find('.cycle-carousel-wrap').position().left) > (slideshow_cont.find('.cycle-carousel-wrap').width() - content_width - (thumb_padding + thumb_borders))) {
		slideshow_cont.find('.thumbs-wrapper .thumbs-shadow.right').hide();
	}
}