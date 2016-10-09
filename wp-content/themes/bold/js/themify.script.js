;// Themify Theme Scripts - http://themify.me/

// Themify Lightbox and Fullscreen and Fixed Header /////////////////////////
var FixedHeader;

(function($){
	
// Fixed Header /////////////////////////
FixedHeader = {
	init: function() {
		this.headerHeight = $('#headerwrap').outerHeight();
		$(window).on('scroll', this.activate);
		$(window).on('touchstart.touchScroll', this.activate);
		$(window).on('touchmove.touchScroll', this.activate);
	},

	activate: function() {
		if (($(window).innerHeight() + $(window).scrollTop()) >= $('body').height()) {
			//return true;
		} else {
			var scrollAmount = $(window).scrollTop();
			if(scrollAmount <= FixedHeader.headerHeight){
				FixedHeader.scrollDisabled();
			} else {
				FixedHeader.scrollEnabled();
			}
		}
	},

	scrollDisabled: function() {
		$('#headerwrap').removeClass('fixed-header');
		$('#header').removeClass('header-on-scroll');
		$('#pagewrap').css('padding-top', '');
		$('body').removeClass('fixed-header-on');
	},

	scrollEnabled: function() {
		$('#headerwrap').addClass('fixed-header');
		$('#header').addClass('header-on-scroll');
		$('#pagewrap').css('padding-top', FixedHeader.headerHeight);
		$('body').addClass('fixed-header-on');
	}
};

// Test if touch event exists //////////////////////////////
function is_touch_device() {
	return $('body').hasClass('touch');
}
	
function infiniteIsotope(containerSelector, itemSelectorIso, itemSelectorInf, containerInfinite, doIso){
	
	// Get max pages for regular category pages and home
	var scrollMaxPages = parseInt(themifyScript.maxPages),
		$container = $(containerSelector),
		$containerInfinite = $(containerInfinite);
	// Get max pages for Query Category pages
	if( typeof qp_max_pages != 'undefined')
		scrollMaxPages = qp_max_pages;

	if( (! $('body.list-post').length > 0) && doIso ){
		// isotope init
		$container.addClass('masonry-done').isotope({
			masonry: {
				columnWidth: '.grid-sizer',
				gutter: '.gutter-sizer'
			},
			itemSelector : itemSelectorIso,
			isOriginLeft : ! $( 'body' ).hasClass( 'rtl' )
		});
	}

	// infinite scroll
	$containerInfinite.infinitescroll({
		navSelector  : '#load-more a:last', 		// selector for the paged navigation
		nextSelector : '#load-more a:last', 		// selector for the NEXT link (to page 2)
		itemSelector : itemSelectorInf, 	// selector for all items you'll retrieve
		loadingText  : '',
		donetext     : '',
		loading 	 : { img: themifyScript.loadingImg },
		maxPage      : scrollMaxPages,
		behavior	 : 'auto' != themifyScript.autoInfinite? 'twitter' : '',
		pathParse 	 : function (path, nextPage) {
			return path.match(/^(.*?)\b2\b(?!.*\b2\b)(.*?$)/).slice(1);
		}
	}, function(newElements) {
		// call Isotope for new elements
		var $newElems = $(newElements).hide();
		
		// Mark new items: remove newItems from already loaded items and add it to loaded items
		$('.post.newItems').removeClass('newItems');
		$newElems.addClass('newItems');
		
		$newElems.imagesLoaded(function(){
			
			$newElems.fadeIn();

			$('#infscr-loading').fadeOut('normal');
			if( 1 == scrollMaxPages ){
				$('#load-more, #infscr-loading').remove();
			}
			
			// Apply lightbox/fullscreen gallery to new items
			Themify.InitGallery();
                        if($('.media-slider.newItems .slideshow').length>0){
                            if(!$.fn.carouFredSel){
                               Themify.LoadAsync(themify_vars.url+'/js/carousel.js',function(){
                                   createCarousel($('.post.media-slider .slideshow'));
                               });
                           }
                           else{
                               createCarousel($('.post.media-slider .slideshow'));
                           }
                       }
			
			if( (! $('body.list-post').length > 0) && doIso ){
				$container.isotope('appended', $newElems );
			}
		});

		scrollMaxPages = scrollMaxPages - 1;
		if( 1 < scrollMaxPages && 'auto' != themifyScript.autoInfinite)
			$('#load-more a').show();
	});
	
	// disable auto infinite scroll based on user selection
	if( 'auto' == themifyScript.autoInfinite ){
		$('#load-more, #load-more a').hide();
	}

}

function createCarousel(obj){
	obj.each(function(){
		$this = $(this);
		$this.carouFredSel({
			responsive: true,
			prev: '#' + $this.data('id') + ' .carousel-prev',
			next: '#' + $this.data('id') + ' .carousel-next',
			pagination: { container: '#' + $this.data('id') + ' .carousel-pager' },
			circular: true,
			infinite: true,
			swipe: true,
			scroll: {
				items: 1,
				fx: $this.data('effect'),
				duration: parseInt($this.data('speed'))
			},
			auto: {
				play: 'off' != $this.data('autoplay')? true: false,
				timeoutDuration: 'off' != $this.data('autoplay')? parseInt($this.data('autoplay')): 0
			},
			items: {
				visible: { min: 1, max: 1 },
				width: 222
			},
			onCreate: function(){
				$this.closest('.slideshow-wrap').css({'visibility':'visible', 'height':'auto'});
				$('.carousel-next, .carousel-prev', $this.closest('.slideshow-wrap')).hide();
				$(window).resize();
			}
		});
	});
}

$(document).ready(function(){

	// Initialize masonry layout //////////////////////
	if(typeof ($.fn.isotope) !== 'undefined') {
		$('#loops-wrapper.grid4,#loops-wrapper.grid3,#loops-wrapper.grid2,.portfolio.loops-wrapper').prepend('<div class="grid-sizer">').prepend('<div class="gutter-sizer">');
	}
	
	// Initialize Fixed Header	///////////////////////
	if(themifyScript.fixedHeader){
		FixedHeader.init();
	}

	// HTML5 placeholder fallback
	$('[placeholder]').focus(function() {
	  var input = $(this);
	  if (input.val() == input.attr('placeholder')) {
	    input.val('');
	    input.removeClass('placeholder');
	  }
	}).blur(function() {
	  var input = $(this);
	  if (input.val() == '' || input.val() == input.attr('placeholder')) {
	    input.addClass('placeholder');
	    input.val(input.attr('placeholder'));
	  }
	}).blur();
	$('[placeholder]').parents('form').submit(function() {
	  $(this).find('[placeholder]').each(function() {
	    var input = $(this);
	    if (input.val() == input.attr('placeholder')) {
		 input.val('');
	    }
	  })
	});
	
	// Show/Hide direction arrows
	$('#body').on('mouseover mouseout', '.slideshow-wrap', function(event) {
		if (event.type == 'mouseover') {
			if( $(window).width() > 600 ){
				$('.carousel-next, .carousel-prev', $(this)).css('display', 'block');
			}
		} else {
			if( $(window).width() > 600 ){
				$('.carousel-next, .carousel-prev', $(this)).css('display', 'none');
			}
		}
	});

	// Scroll to top
	$('.back-top a').click(function () {
		$('body,html').animate({
			scrollTop: 0
		}, 800);
		return false;
	});

	// Toggle main nav on mobile
	$("#menu-icon").click(function(){
		$("#main-nav").fadeToggle();
		$("#headerwrap #top-nav").hide();
		$(this).toggleClass("active");
	});
	
	if( $( 'body' ).hasClass( 'touch' ) && typeof jQuery.fn.themifyDropdown != 'function' ) {
		Themify.LoadAsync(themify_vars.url + '/js/themify.dropdown.js', function(){
			$( '#main-nav' ).themifyDropdown();
		});
	}

	// If is touch device, add class
	if( is_touch_device() && screen.width < 480 ){ $('body').addClass('is-touch'); }


});

$(window).load(function() {
	// Carousel initialization //////////////////////////
        if($('.post.media-slider .slideshow').length>0){
             if(!$.fn.carouFredSel){
                Themify.LoadAsync(themify_vars.url+'/js/carousel.js',function(){
                    createCarousel($('.post.media-slider .slideshow'));
                });
            }
            else{
                createCarousel($('.post.media-slider .slideshow'));
            }
        }

	// Check if isotope is enabled ////////////////
	if(typeof ($.fn.isotope) !== 'undefined'){

		if($('.post').length > 0){
			// isotope container, isotope item, item fetched by infinite scroll, infinite scroll
			infiniteIsotope('#loops-wrapper', '.post', '#content .post', '#loops-wrapper', true);
		}

		if($('.portfolio-post').length > 0){
			// isotope container, isotope item, item fetched by infinite scroll, infinite scroll
			infiniteIsotope('.portfolio-wrapper', '.portfolio-post', '.portfolio-post', '.portfolio-wrapper', true);
		}
	}
	
});

}(jQuery));