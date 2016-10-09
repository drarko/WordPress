;// Themify Theme Scripts - http://themify.me/

// Initialize object literals
var EntryFilter = {};

/////////////////////////////////////////////
// Fullscreen API
/////////////////////////////////////////////
var
    fullScreenApi = {
        supportsFullScreen: false,
        isFullScreen: function() { return false; },
        requestFullScreen: function() {},
        cancelFullScreen: function() {},
        fullScreenEventName: '',
        prefix: ''
    },
    browserPrefixes = 'webkit moz o ms khtml'.split(' ');

// check for native support
if (typeof document.cancelFullScreen != 'undefined') {
    fullScreenApi.supportsFullScreen = true;
} else {
    // check for fullscreen support by vendor prefix
    for (var i = 0, il = browserPrefixes.length; i < il; i++ ) {
        fullScreenApi.prefix = browserPrefixes[i];

        if (typeof document[fullScreenApi.prefix + 'CancelFullScreen' ] != 'undefined' ) {
            fullScreenApi.supportsFullScreen = true;

            break;
        }
    }
}

// update methods to do something useful
if (fullScreenApi.supportsFullScreen) {
    fullScreenApi.fullScreenEventName = fullScreenApi.prefix + 'fullscreenchange';

    fullScreenApi.isFullScreen = function() {
        switch (this.prefix) {
            case '':
                return document.fullScreen;
            case 'webkit':
                return document.webkitIsFullScreen;
            default:
                return document[this.prefix + 'FullScreen'];
        }
    }
    fullScreenApi.requestFullScreen = function(el) {
        return (this.prefix === '') ? el.requestFullScreen() : el[this.prefix + 'RequestFullScreen']();
    }
    fullScreenApi.cancelFullScreen = function(el) {
        return (this.prefix === '') ? document.cancelFullScreen() : document[this.prefix + 'CancelFullScreen']();
    }
}

// jQuery plugin
if (typeof jQuery != 'undefined') {
    jQuery.fn.requestFullScreen = function() {

        return this.each(function() {
            if (fullScreenApi.supportsFullScreen) {
                fullScreenApi.requestFullScreen(this);
            }
        });
    };
}

// export api
window.fullScreenApi = fullScreenApi;

/////////////////////////////////////////////
// Themify Slides Script
/////////////////////////////////////////////
var ThemifySlides = {
	init: function(config) {
		// private
		this.currPage = 1;
		this.startAt = 0;
		this.currSlide = 0;
		this.isFrameLoading = false;
		this.lightboxOpen = false;

		// public
		this.config = config;

		this.initFlexSlider();
		this.bindEvents();
		this.setupLightbox();

	},

	bindEvents: function() {
		var self = ThemifySlides;

		jQuery('body').on('click', '.load-page-js', this.loadPage);
		jQuery('#slider').on('hover', '.flex-control-nav li a', this.hoverThumbnail);
		jQuery('body').on('click', '#fullscreen-button', this.fullScreen);
		jQuery('body').on('click', '.themify_lightbox_post', this.clickLightBox);
		jQuery('body').on('click', '.close-lightbox', this.closeLightBox);
		jQuery('body').on('click', '.slide-autoplay', this.clickAutoPlay);
		jQuery('body').on('click', '.flex-control-nav a, .flex-direction-nav a', this.clickControlNav);

		// direction nav
		jQuery('body').on('click', '.flex-next', this.clickFlexNext);
		jQuery('body').on('click', '.flex-prev', this.clickFlexPrev);

		// lightbox navigation
		jQuery('body').on('click', '.lightbox-prev', this.clickLightBoxPrev);
		jQuery('body').on('click', '.lightbox-next', this.clickLightBoxNext);

		jQuery(document).keyup(this.keyUp);

		if(fullScreenApi.supportsFullScreen){
			jQuery('<div/>', {id: 'pattern'}).appendTo('body'); // setup patern

			document.addEventListener(fullScreenApi.fullScreenEventName, function() {
				if (!fullScreenApi.isFullScreen()) {
					self.escFullScreen(); // escape fullscreen
				}
			}, true);
		}
		else{
			jQuery('<div/>', {id: 'pattern'}).appendTo('body'); // setup patern
		}

	},

	initFlexSlider: function() {
		var self = ThemifySlides;
		jQuery('<div/>', {id:'loader'}).appendTo('#slider-inner');
		jQuery('<div/>', {id:'loader-gap'}).attr('style', 'display:block;width:100%;height:200px;').appendTo('#slider-inner');

		jQuery('#slider')
		.hide()
		.imagesLoaded(function(){
			self.flexSlider();
			jQuery('#loader').remove();
			jQuery('#loader-gap').remove();
			jQuery(this).show();
		});
	},

	flexSlider: function() {
		var self = ThemifySlides;

		themifyScript.sliderSpeed = parseInt(themifyScript.sliderSpeed);
		themifyScript.sliderAnimationSpeed = parseInt(themifyScript.sliderAnimationSpeed);
		themifyScript.sliderAuto = ('true' == themifyScript.sliderAuto)? true: false;

		jQuery('#slider .slides-wrapper').flexslider({
		animation: themifyScript.sliderEffect,
		animationSpeed: themifyScript.sliderAnimationSpeed,
		slideshow: themifyScript.sliderAuto,
		slideshowSpeed: themifyScript.sliderSpeed,
                prevText: "«",
		nextText: "»",
		animationLoop: false,
		startAt: self.startAt,
		pausePlay: true,
		directionNav: false,
		start: function(slider){
			self.setupThumbPreview();
			self.setupDirectionNav();

			if(slider.currentSlide == 0 && self.currPage == 1) {
				jQuery('.flex-direction-nav .flex-prev').hide();
			}

			jQuery('.slide-preview').hide();
			jQuery('.flex-pauseplay').hide();

			jQuery('#slider').append('<div id="control-nav-wrapper" class="control-nav-wrapper"></div>');

			if(slider.count > 1) {
				autoplayParam = themifyScript.sliderAuto? 'on' : '';
				jQuery('<a class="autoplay ' + autoplayParam + ' slide-autoplay">#</a>').appendTo('#control-nav-wrapper');
			}
			jQuery('.flex-control-nav').appendTo('#control-nav-wrapper');

			var slideLength = jQuery('#slider .slides li').length;

			if(self.currPage > 1 && slideLength == 1){
				jQuery('.flex-direction-nav .flex-next').hide();
				jQuery('.flex-direction-nav .flex-prev').addClass('load-page-js');
				self.swipeAction(); // apply manual swipe for only 1 slider post
			}

			// go to next page slider
			if(slider.atEnd && slider.currentSlide == 0){
				jQuery('.flex-direction-nav .flex-prev').addClass('load-page-js');
			}

			// go back to prev page slider
			if(slider.currentSlide == (slider.count - 1)){
				jQuery('.flex-direction-nav .flex-next').addClass('load-page-js');
			}

			if(themifyScript.sliderEffect == 'fade'){
				jQuery('.slides li').hide(); // fix bugs fade
				jQuery('.slides .flex-active-slide').show();
			}

		},
		before: function(slider){
			if(slider.atEnd && slider.direction == 'prev' && slider.currentSlide == 0){
				jQuery('.flex-direction-nav .load-page-js').trigger('click');
			} else {
				jQuery('.flex-direction-nav .flex-prev').removeClass('load-page-js slide-disabled');
			}

			if(slider.atEnd && slider.direction == 'next' && slider.currentSlide == (slider.count - 1)){
				jQuery('.flex-direction-nav .load-page-js').trigger('click');
			} else {
				jQuery('.flex-direction-nav .flex-next').removeClass('load-page-js slide-disabled');
			}

		},
		after: function(slider){
			if(slider.atEnd && slider.direction == 'prev'){
				jQuery('.flex-direction-nav .flex-prev').addClass('load-page-js');
			}

			if(slider.atEnd && slider.direction == 'next'){
				jQuery('.flex-direction-nav .flex-next').addClass('load-page-js');
			}

			jQuery('.flex-prev, .flex-next').show();
		},
		end: function(){
			jQuery('.slide-autoplay').removeClass('on');
		}
	  });
	},

	setupThumbPreview: function() {
		var cloneImg = [];
		jQuery('#slider .slides li').each(function(){
			var img = jQuery(this).find('.slide-feature-image img');
			cloneImg.push(img.attr('src'));
		});

		jQuery('.flex-control-nav li').each(function(i){
			if(cloneImg[i] != 'undefined'){
				jQuery('<img/>')
				.attr({'src': cloneImg[i], 'width': '60px'})
				.prependTo(this)
				.wrap('<div class="slide-preview"/>');
			}
		});

	},

	setupDirectionNav: function() {
		jQuery('<ul class="flex-direction-nav"><li><a class="flex-prev" href="#">«</a></li><li><a class="flex-next" href="#">»</a></li></ul>').appendTo('#slider');
	},

	clickFlexNext: function() {
		jQuery('#slider .slides-wrapper').flexslider("next");
		return false;
	},

	clickFlexPrev: function() {
		jQuery('#slider .slides-wrapper').flexslider("prev");
		return false;
	},

	setupLightbox: function() {
		jQuery('<div id="post-lightbox-wrap"><a href="#" class="close-lightbox">close</a><div id="post-lightbox-container"><div class="carousel"></div></div></div><a href="#" class="lightbox-direction-nav lightbox-prev">»</a><a href="#" class="lightbox-direction-nav lightbox-next">«</a>')
		.hide()
		.prependTo('body');
	},

	loadPage: function(e) {
		if(jQuery(this).hasClass('slide-disabled')){
			return false;
		}

                var self = ThemifySlides;
		var load_page_id = '.prev-slideshow a';
		var isPrev = false;
                jQuery(this).addClass('slide-disabled');

		if(jQuery(this).hasClass('flex-prev')){
			// return false when no page
			if(self.currPage <= 1){
				jQuery('.flex-prev').hide();
				return;
			}

			self.currPage--;
			isPrev = true;
		}

		if(jQuery(this).hasClass('flex-next')){
			if(jQuery(load_page_id).length == 0){
				jQuery('.flex-next').hide();
				return false;
			}
			self.currPage++;
		}

		var href = jQuery(load_page_id).attr('href');
		var box = jQuery('<div/>');

		// get the relative URL - everything past the domain name.
		var relurl = /(.*?\/\/).*?(\/.*)/, path = href;

		// set the path to be a relative URL from root.
		path = self.determinePath(path, relurl);

		var url = path.join(self.currPage) + ' #slider > *';

		jQuery('#slider').append('<div id="loader"></div>');
		box.load(url, function(response, status, xhr) {
			var $newElems = jQuery(box.children().get());
	  	var $container = jQuery('#slider');
	  	var slideLength = $newElems.find('.slides li').length;

                if(slideLength > 0){

                $newElems.imagesLoaded(function(){

                        var extraLightboxArgs = {};
                        if(!jQuery('body').hasClass('post-lightbox-iframe')){
                            extraLightboxArgs = {'displayIframeContentsInParent': false};
                        } else {
                          extraLightboxArgs = {'displayIframeContentsInParent': true};
                        }
                        Themify.InitGallery($newElems,extraLightboxArgs);

                        // fullscreen
                        if(fullScreenApi.supportsFullScreen && fullScreenApi.isFullScreen()){
                            jQuery('#fullscreen-button', $newElems).addClass('active');
                        }

                        $container.hide().html($newElems).fadeIn(800);
                        self.startAt = isPrev?slideLength - 1:0;
                        self.currSlide = 0;
                        self.flexSlider(); // re initial flexslider
                        jQuery('#loader').remove();
                    }
                );

                }
                else{
                    jQuery('.flex-next').removeClass('load-page-js').hide();
                    self.currPage--;

                    jQuery('#loader').remove();
                }

		});

		e.preventDefault();
	},

	hoverThumbnail: function(e) {
		if(e.type=='mouseenter')
    {
        jQuery(this).prev().fadeIn('slow');
    }
    else if(e.type=='mouseleave')
    {
        jQuery(this).prev().fadeOut('fast');
    }
	},

	fullScreen: function() {
		jQuery(this).toggleClass('active');
		jQuery('body').toggleClass('fullscreen');

  	if (fullScreenApi.supportsFullScreen) {
  		jQuery(document).toggleFullScreen();
  	}

  	jQuery('#pattern').fadeToggle(800);

	},

	escFullScreen: function() {
		jQuery('#fullscreen-button').removeClass('active');
  	jQuery('body').removeClass('fullscreen');
		jQuery('#pattern').fadeOut();

		if(jQuery('.close-lightbox').is(':visible')){
			jQuery('.close-lightbox').trigger('click');
		}

		// fix chrome issue
		jQuery(window).resize();
	},

	determinePath: function(path, relurl) {
		path.match(relurl) ? path.match(relurl)[2] : path;

		// there is a 2 in the url surrounded by slashes, e.g. /page/2/
		if (path.match(/^(.*?)\b2\b(.*?$)/)) {
			path = path.match(/^(.*?)\b2\b(.*?$)/).slice(1);
		} else
		// if there is any 2 in the url at all.
		if (path.match(/^(.*?)2(.*?$)/)) {

			// page= is used in django:
			//   http://www.infinite-scroll.com/changelog/comment-page-1/#comment-127
			if (path.match(/^(.*?page=)2(\/.*|$)/)) {
				path = path.match(/^(.*?page=)2(\/.*|$)/).slice(1);
				return path;
			}

			path = path.match(/^(.*?)2(.*?$)/).slice(1);
		} else {

			// page= is used in drupal too but second page is page=1 not page=2:
			// thx Jerod Fritz, vladikoff
			if (path.match(/^(.*?page=)1(\/.*|$)/)) {
				path = path.match(/^(.*?page=)1(\/.*|$)/).slice(1);
				return path;
			}

		}

		return path;
	},

	clickLightBox: function(e) {
		var self = ThemifySlides;
		var url = jQuery(this).attr('href');
		self.isFrameLoading = true;

		jQuery('body').addClass('post-lightbox');
		jQuery('#pattern').hide().fadeIn(800);
		jQuery('<div/>', {id: 'loader'}).appendTo('body');
		jQuery('#pagewrap').hide();

		jQuery('#post-lightbox-container').empty();

		jQuery('<iframe/>', {id: 'post-lightbox-iframe', src: url, frameborder: 0, width: '100%', scrolling: 'no'}).load(function(){
			jQuery('#loader').remove();

			jQuery("#post-lightbox-wrap")
				.show()
				.css('top', self.getDocHeight())
				.animate({
			    top: 0
			  }, 800 );


			jQuery('.lightbox-direction-nav').show();

			var prev = jQuery(this).contents().find('.post-nav .prev a');
			var next = jQuery(this).contents().find('.post-nav .next a');

		  if(prev.length == 0){
		  	jQuery('.lightbox-prev').hide();
		  }

		  if(next.length == 0){
		  	jQuery('.lightbox-next').hide();
		  }

		  // redirect to corresponding page
		  jQuery(this).contents().find("a:not([class='comment-reply-link'], [id='cancel-comment-reply-link'], .lightbox, .post-content a[href$='jpg'], .post-content a[href$='gif'], .post-content a[href$='png'], .post-content a[href$='JPG'], .post-content a[href$='GIF'], .post-content a[href$='PNG'])").click(function(){
		  	var href = jQuery(this).attr('href');
		  	window.location.replace(href);
		  	return false;
		  });

		  // also for the form should exit the lightbox
		  jQuery(this).contents().find("form").attr('target', '_top');

		  self.isFrameLoading = false; // update current status
		  self.lightboxOpen = true;

		}).iframeAutoHeight().appendTo('#post-lightbox-container');

		jQuery('#slider').hide();

		e.preventDefault();
	},

	closeLightBox: function(e) {
		var self = ThemifySlides;

		jQuery('#pagewrap').show();
		jQuery('#post-lightbox-wrap').animate({
			top: self.getDocHeight()
	  }, 800, function() {
	    // Animation complete.
	    jQuery('#pattern').fadeOut(800, function(){
	    	jQuery('body').removeClass('post-lightbox');
	    	jQuery('#post-lightbox-container').empty().parent().hide();
	    	jQuery('.lightbox-direction-nav').hide();
	    	jQuery('#slider').show();

	    	if(jQuery('body').hasClass('fullscreen')){
	    		jQuery('#pattern').show();
	    	}

	    	jQuery(window).resize(); // fix issue
	    });
	  });

	  self.lightboxOpen = false; // update current status

	  e.preventDefault();
	},

	getDocHeight: function(){
		var D = document;
    return Math.max(
		    Math.max(D.body.scrollHeight, D.documentElement.scrollHeight),
		    Math.max(D.body.offsetHeight, D.documentElement.offsetHeight),
		    Math.max(D.body.clientHeight, D.documentElement.clientHeight)
		);
	},

	clickAutoPlay: function(e) {
		jQuery(this).toggleClass('on');

		if(jQuery(this).hasClass('on')){
			jQuery('#slider .slides-wrapper').flexslider("play");
		}
		else{
			jQuery('#slider .slides-wrapper').flexslider("pause");
		}

		e.preventDefault();
	},

	clickControlNav: function() {
		jQuery('.slide-autoplay').removeClass('on');
	},

	clickLightBoxPrev: function(e) {
		var self = ThemifySlides;
		var link = jQuery('#post-lightbox-iframe').contents().find('.post-nav .prev a').attr('href');
		link += '?post_in_lightbox=1';

		if(jQuery(this).hasClass('click-disabled')){
			return false;
		}

		jQuery(this).addClass('click-disabled');

		self.loadLightboxPost(link);
		e.preventDefault();
	},

	clickLightBoxNext: function(e) {
		var self = ThemifySlides;
		var link = jQuery('#post-lightbox-iframe').contents().find('.post-nav .next a').attr('href');
		link += '?post_in_lightbox=1';

		if(jQuery(this).hasClass('click-disabled')){
			return false;
		}

		jQuery(this).addClass('click-disabled');

		self.loadLightboxPost(link);
		e.preventDefault();
	},

	loadLightboxPost: function(url) {
		var self = ThemifySlides;
		jQuery('<div/>', {id: 'loader'}).appendTo('body');
		self.isFrameLoading = true;

		jQuery('#post-lightbox-wrap, .lightbox-direction-nav').hide();
		jQuery('#post-lightbox-container').empty();

		jQuery('<iframe/>', {id: 'post-lightbox-iframe', src: url, frameborder: 0, width: '100%', scrolling: 'no'}).load(function(){
			jQuery('#loader').remove();

			jQuery("#post-lightbox-wrap")
				.show()
				.css('top', self.getDocHeight())
				.animate({
			    top: 0
			  }, 800 );

			jQuery('.lightbox-direction-nav').removeClass('click-disabled').show();

			var prev = jQuery(this).contents().find('.post-nav .prev a');
			var next = jQuery(this).contents().find('.post-nav .next a');

		  if(prev.length == 0){
		  	jQuery('.lightbox-prev').hide();
		  }

		  if(next.length == 0){
		  	jQuery('.lightbox-next').hide();
		  }

		  // redirect to corresponding page
		  jQuery(this).contents().find("a:not([class='comment-reply-link'], [id='cancel-comment-reply-link'], .themify_lightbox, .post-content a[href$='jpg'], .post-content a[href$='gif'], .post-content a[href$='png'], .post-content a[href$='JPG'], .post-content a[href$='GIF'], .post-content a[href$='PNG'])").click(function(){
		  	var href = jQuery(this).attr('href');
		  	window.location.replace(href);
		  	return false;
		  });

		  // also for the form should exit the lightbox
		  jQuery(this).contents().find("form").attr('target', '_top');

		  self.isFrameLoading = false;

		}).iframeAutoHeight().appendTo('#post-lightbox-container');

	},

	keyUp: function(e) {
		var self = ThemifySlides;

		if(self.isFrameLoading && e.keyCode == 27){
			self.cancelLightBox();
		}

		if (self.lightboxOpen && e.keyCode == 27) {
			jQuery('.close-lightbox').trigger('click');
			jQuery('#loader').remove();
		}
	},

	cancelLightBox: function(){
		var self = ThemifySlides;

		jQuery('#pagewrap').show();
		jQuery('#pattern').hide();
		jQuery('body').removeClass('post-lightbox');
	  	jQuery('#post-lightbox-container').empty().parent().hide();
	  	jQuery('.lightbox-direction-nav').hide();
	  	jQuery('#slider').show();
	  	jQuery('#loader').remove();

	  	if(jQuery('body').hasClass('fullscreen')){
	  		jQuery('#pattern').show();
	  	}
	  	jQuery(window).resize(); // fix issue

	  	self.isFrameLoading = false;
	},

	swipeAction: function() {
		jQuery("#slider").touchwipe({
		   wipeLeft: function() {
		   	//jQuery('.flex-direction-nav .load-page-js').trigger('click');
		   },
		   wipeRight: function() {
		   	jQuery('.flex-direction-nav .load-page-js').trigger('click');
		   },
		   min_move_x: 20,
		   min_move_y: 20,
		   preventDefaultEvents: true
		});
	},

	wipeLightboxLeft: function() {
		if(jQuery('.lightbox-prev').is(':visible')){
			jQuery('.lightbox-prev').trigger('click');
		}
	},

	wipeLightboxRight: function() {
		if(jQuery('.lightbox-next').is(':visible')){
			jQuery('.lightbox-next').trigger('click');
		}
	}

};

/////////////////////////////////////////////
// jQuery functions
/////////////////////////////////////////////
(function($){

	// Entry Filter /////////////////////////
	EntryFilter = {

		options: {},

		init: function( settings ) {
			this.options = $.extend( {}, {
				type: '.type-post',
				filter: '.post-filter',
				loops: '.loops-wrapper'
			}, settings );
			this.filter();
		},

		setFilters: function(refresh){
			var self = this,
				$filter = $(self.options.filter);

			if ( $filter.find('a').length > 0 && 'undefined' !== typeof $.fn.isotope ){
				$filter.find('li').each(function(){
					var $li = $(this),
						$entries = $li.parent().next(),
						cat = $li.attr('class').replace( /(current-cat)|(cat-item)|(-)|(active)|(enabled)|(disabled)/g, '' ).replace( ' ', '' );
					if ( $entries.find(self.options.type + '.cat-' + cat).length <= 0 ) {
						$li.addClass('disabled').removeClass('enabled');
					} else {
						$li.addClass('enabled').removeClass('disabled');
					}
				});
			}
			if ( refresh ) {
				$filter.find('.active').find('a').trigger('click');
			}
		},

		filter: function(){
			var self = this,
				$filter = $(self.options.filter);
			if ( $filter.find('a').length > 0 && 'undefined' !== typeof $.fn.isotope ){
				self.setFilters();

				$filter.show().on('click', 'a', function(e) {
					e.preventDefault();
					var $li = $(this).parent(),
						$entries = $li.parent().next();
					if ( $li.hasClass('active') ) {
						$li.removeClass('active');
						$entries.isotope( {
							filter: self.options.type
						} );
					} else {
						$li.siblings('.active').removeClass('active');
						$li.addClass('active');
						$entries.isotope( {
							filter: '.cat-' + $li.attr('class').replace( /(current-cat)|(cat-item)|(-)|(active)|(enabled)|(disabled)/g, '' ).replace( ' ', '' )  } );
					}
				} );
			}
		},

		layout: function(){
			var self = this;
			$(self.options.loops).isotope({
				layoutMode: 'fitRows',
				animationEngine: 'jquery',
				transformsEnabled: false,
				itemSelector: self.options.type
			});
		}
	};


	jQuery(document).ready(function($){
                /////////////////////////////////////////////
		// Initialize prettyPhoto
		/////////////////////////////////////////////
		var extraLightboxArgs = {};
		if(!$('body').hasClass('post-lightbox-iframe')){
			extraLightboxArgs = {'displayIframeContentsInParent': false};
		} else {
		  extraLightboxArgs = {'displayIframeContentsInParent': true};
		}
                themifyScript.extraLightboxArgs;

		/////////////////////////////////////////////
		// Entry Filter
		/////////////////////////////////////////////
		EntryFilter.init({filter: '.sorting-nav'});

		/////////////////////////////////////////////
		// Scroll to top
		/////////////////////////////////////////////
		$('.back-top a').click(function () {
                    $('body,html').animate({scrollTop: 0}, 800);
                    return false;
		});

		/////////////////////////////////////////////
		// Toggle menu on mobile
		/////////////////////////////////////////////
		$("#menu-icon").click(function(){
                    $("#headerwrap #main-nav").fadeToggle();
                    $("#headerwrap #searchform").hide();
                    $(this).toggleClass("active");
		});

		/////////////////////////////////////////////
		// Toggle searchform on mobile
		/////////////////////////////////////////////
		$("#search-icon").click(function(){
                    $("#headerwrap #searchform").fadeToggle();
                    $("#headerwrap #main-nav").hide();
                    $('#headerwrap #s').focus();
                    $(this).toggleClass("active");
		});

		/////////////////////////////////////////////
		// Initialize Themify Slide
		/////////////////////////////////////////////
		ThemifySlides.init();


	});


	jQuery(window).load(function(){

		/////////////////////////////////////////////
		// Entry Filter Layout
		/////////////////////////////////////////////
		EntryFilter.layout();

	});


})(jQuery);
