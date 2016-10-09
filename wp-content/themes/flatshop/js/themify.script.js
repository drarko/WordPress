;// Themify Theme Scripts - http://themify.me/

// debouncedresize event
(function($){var $event=$.event,$special,resizeTimeout;$special=$event.special.debouncedresize={setup:function(){$(this).on("resize",$special.handler);},teardown:function(){$(this).off("resize",$special.handler);},handler:function(event,execAsap){var context=this,args=arguments,dispatch=function(){event.type="debouncedresize";$event.dispatch.apply(context,args);};if(resizeTimeout){clearTimeout(resizeTimeout);}execAsap?dispatch():resizeTimeout=setTimeout(dispatch,$special.threshold);},threshold:150};})(jQuery);

// Initialize object literals
var FixedHeader = {}, ParallaxHeader = {};

/////////////////////////////////////////////
// jQuery functions					
/////////////////////////////////////////////
(function($){

// Test if touch event exists //////////////////////////////
function is_touch_device() {
	return $('body').hasClass('touch');
}

//////////////////////////////
// Fixed Header
//////////////////////////////
FixedHeader = {
	init: function() {
		this.calculateHeaderHeight();
		this.activate();
		$(window)
		.on('scroll', this.activate)
		.on('touchstart.touchScroll', this.activate)
		.on('touchmove.touchScroll', this.activate);

		// Announcement Bar compatibility: recalculate headerHeight when announcements are visible
		$( window ).load( this.calculateHeaderHeight );
		$( 'body' ).on( 'announcement_bar_position', this.calculateHeaderHeight );
		$( 'body' ).on( 'announcement_bar_scroll_on_after', this.calculateHeaderHeight );
	},

	activate: function() {
		var $window = $(window),
			scrollTop = $window.scrollTop();

		if( scrollTop > FixedHeader.headerHeight ) {
			FixedHeader.scrollEnabled();
		} else {
			FixedHeader.scrollDisabled();
		}
	},

	scrollDisabled: function() {
		$('#headerwrap').removeClass('fixed-header');
		$('#header').removeClass('header-on-scroll');
		$('body').removeClass('fixed-header-on');
	},

	scrollEnabled: function() {
		$('#headerwrap').addClass('fixed-header');
		$('#header').addClass('header-on-scroll');
		$('body').addClass('fixed-header-on');
	},
	
	calculateHeaderHeight: function(){
		var cons = is_touch_device() ? 10 : 0;
		FixedHeader.headerHeight = $('#headerwrap').height() - cons;
		FixedHeader.headerHeight += parseFloat( $('#headerwrap').css( 'marginTop' ) ); // take headerwrap margin top into account
	}
};

function themify_transition(){
	var selector = '.grid2 .products .product, .grid3 .products .product, .grid4 .products .product',
            transitionClass = 'transition-one-up';

	$(selector).each(function(){
		if($(this).find('.'+transitionClass).length == 0){
			var prodId = $(this).data('product-id'),
				prodStyle = 'product-style-'+prodId;
			$(this).wrapInner('<div class="'+transitionClass+'" />')
			.prepend('<div class="product-bg-container"><div class="product-bg '+prodStyle+'"></div></div>').removeClass(prodStyle);
		}
	});

        // shortcode columns add class
        $('.col2-1.first, .col3-1.first, .col3-2.first, .col4-1.first, .col4-2.first, .col4-3.first').each(function(){
                var $this = $(this);
                if($this.hasClass('col2-1')) {
                        $this.next('.col2-1').addClass('last');
                        $this.next('.col4-1').addClass('third').next('.col4-1').addClass('last');
                } else if($this.hasClass('col3-1')) {
                        $this.next('.col3-1').addClass('second').next('.col3-1').addClass('last');
                        $this.next('.col3-2').addClass('last');
                } else if($this.hasClass('col3-2')) {
                        $this.next('.col3-1').addClass('last');
                } else if($this.hasClass('col4-1')) {
                        $this.next('.col4-1').addClass('second').next('.col4-1').addClass('third').next('.col4-1').addClass('last');
                } else if($this.hasClass('col4-2')) {
                        $this.next('.col4-2').addClass('last');
                        $this.next('.col4-1').addClass('third').next('.col4-1').addClass('last');
                } else if($this.hasClass('col4-3')) {
                        $this.next('.col4-1').addClass('last');
                }
        });
        var col_nums = 1;
        $('.col-full').each(function(){
                var $this = $(this);
                if( col_nums % 2 == 0) {
                        $this.addClass('animate-last');
                } else {
                        $this.addClass('animate-first');
                }
                col_nums += 1;
        });
}

ParallaxHeader = {
	init: function(){
		if(!themifyScript.headerParallax) return;
		this.sliderWrapper = ['#sliderwrap', '.single-post #body > .single-media-wrap'];

		$(window).load(this.onLoaded);
	},

	onLoaded: function(){
		var self = ParallaxHeader, resizeId;
		$('body').addClass('parallax-header');
		
		$.each(self.sliderWrapper, function(i,v){
			if($(v).length > 0)	{
				var $layoutWrap = $(v).nextAll('div').first();
				$layoutWrap.css('marginTop', $(v).height());

				$(window).on('scroll', function(){
					self.transition(v);
				}).on('touchmove.touchScroll', function(){
					self.transition(v);
				}).on('resize', function(){
					clearTimeout(resizeId);
					resizeId = setTimeout(function(){
						$layoutWrap.css('marginTop', $(v).height());
					}, 500);
				}).on('orientationchange', function(){
					self.transition(v);
				});
			}
		});
	},

	transition: function(obj){
		var n = Math.ceil($(window).scrollTop() / 3);
		$(obj).css({
			'-webkit-transform' : 'translateY(-'+n+'px)',
			'-moz-transform' : 'translateY(-'+n+'px)',
			'-o-transform' : 'translateY(-'+n+'px)',
			'-ms-transform' : 'translateY(-'+n+'px)',
			'transform' : 'translateY(-'+n+'px)'
		});
		if($(window).scrollTop() > $(obj).height()){
			$(obj).hide();
		} else {
			$(obj).show();
		}
	}
};

function setSlidersHeight() {
	// Set fixed slider height based on the heighest slide
	var $slider = $('#slider'),
		sliderHeight = 0;
	$slider.find('.slides > li').css('height','');
	if($(window).width() > 780) return;
	$slider.find('.slides > li').each(function(){
		slideHeight = $(this).height();
		if (sliderHeight < slideHeight) {
			sliderHeight = slideHeight;
		}
	});
	$slider.find('.slides > li').css({'height' : sliderHeight});
}

// DOCUMENT READY
$(document).ready(function() {

	// Transition Effect
	// Call earlier than $.ready or $.load
	themify_transition();

	// Fixed header
	if ( themifyScript.fixedHeader ) {
		FixedHeader.init();
	}

	/////////////////////////////////////////////
	// Scroll to top
	/////////////////////////////////////////////
	$('.back-top a').click(function() {
		$('body,html').animate({ scrollTop: 0 }, 800);
		return false;
	});

	/////////////////////////////////////////////
	// Google map width
	/////////////////////////////////////////////
	var w = $(document).width();
	$('.google-map iframe').width(w);


	/////////////////////////////////////////////
	// Toggle main nav on mobile
	/////////////////////////////////////////////
	$('#menu-icon').themifySideMenu({
		close: '#menu-icon-close'
	});
	$('#cart-icon').themifySideMenu({
		panel: '#slide-cart',
		close: '#cart-icon-close'
	});
        var $body = $('body'),
            $overlay = $( '<div class="body-overlay">' );
        $body.append( $overlay ).on( 'sidemenushow.themify', function () {
            $overlay.addClass( 'body-overlay-on' );
        } ).on( 'sidemenuhide.themify', function () {
            $overlay.removeClass( 'body-overlay-on' );
        } ).on( 'click.themify touchend.themify', '.body-overlay', function () {
            $( '#menu-icon' ).themifySideMenu( 'hide' );
            $( '#cart-icon' ).themifySideMenu( 'hide' );
        } ); 
        $(window).resize(function(){
            if( $( '#menu-icon' ).is(':visible') && $('#mobile-menu').hasClass('sidemenu-on')){
                $overlay.addClass( 'body-overlay-on' );
            }
            else{
                 $overlay.removeClass( 'body-overlay-on' );
            }
        });

	//////////////////////////////////////////////////////////
	// Show/Hide search form and unfold/fold search options
	//////////////////////////////////////////////////////////
	$('#headerwrap .search-option').each(function(index){
		$this = $(this);
		$('input', $this).change(function(e){
			searchValue = $(this).val();
			$(this).parent().siblings('.search-type').val( searchValue );
		});
	});

	function createCookie(name,value) {
		document.cookie = name+"="+value+"; path=/";
	}

	function readCookie(name) {
		name = name + "=";
		var ca = document.cookie.split(';');
		for(var i=0;i < ca.length;i++) {
			var c = ca[i];
			while (c.charAt(0)==' ') c = c.substring(1,c.length);
			if (c.indexOf(name) == 0) return c.substring(name.length,c.length);
		}
		return null;
	}

	$(window).resize(function(){
		setSlidersHeight(); // slider equal height
	});

	// Builder modules initialization in InfiniteScroll posts
	$( document ).on( 'newElements', function(){
		if( typeof ThemifyBuilderModuleJs == 'object' ) {
			ThemifyBuilderModuleJs.loadOnAjax(); // re-init core modules
		}
		$('body').trigger( 'builder_load_module_partial' ); // trigger the builder_load_module_partial event, which re-inits Builder addons
	} );
});

function infiniteIsotope(containerSelector, itemSelectorIso, itemSelectorInf, containerInfinite, isoCallback){

	// Get max pages for regular category pages and home
	var scrollMaxPages = parseInt(themifyScript.maxPages),
		$container = $(containerSelector),
		$containerInfinite = $(containerInfinite),
		isProductLayout = ( itemSelectorIso === '.product' );

	if(themifyScript.masonryLayout && $container.find( '.grid-sizer' ).length < 1 ) {
		$container.prepend( '<div class="grid-sizer"></div><div class="gutter-sizer"></div>' );
	}

	// Get max pages for Query Category pages
	if( typeof qp_max_pages != 'undefined')
		scrollMaxPages = qp_max_pages;

	if ( isProductLayout ) {
		if ( themifyScript.masonryLayout ) {
			// isotope init
			$container.isotope({
				itemSelector : itemSelectorIso,
				transformsEnabled : false,
				isOriginLeft : ! $( 'body' ).hasClass( 'rtl' )
			});
		}
	} else {
		// isotope init
		$container.isotope({
			itemSelector : itemSelectorIso,
			transformsEnabled : false,
			isOriginLeft : ! $( 'body' ).hasClass( 'rtl' )
		});
	}

	$(window).resize();
	// load callback
	if( $.isFunction(isoCallback) ){
		isoCallback.call(this, $container);
	}
	// infinite scroll
	$containerInfinite.infinitescroll({
		navSelector  : '#load-more a', 		// selector for the paged navigation
		nextSelector : '#load-more a', 		// selector for the NEXT link (to page 2)
		itemSelector : itemSelectorInf, 	// selector for all items you'll retrieve
		loadingText  : '',
		donetext     : '',
		loading 	 : { img: themifyScript.loadingImg },
		maxPage      : scrollMaxPages,
		behavior	 : 'auto' != themifyScript.autoInfinite? 'twitter' : '',
		bufferPx	 : parseInt(themifyScript.bufferPx),
		pathParse 	 : function (path, nextPage) {
			return path.match(/^(.*?)\b2\b(?!.*\b2\b)(.*?$)/).slice(1);
		}
	}, function(newElements) {
		// call Isotope for new elements
		var $newElems = $(newElements);
		$newElems.hide();
		// FlyIn Effect
		themify_transition();
		if ( 'undefined' !== typeof ThemifyBuilderModuleJs ) {
			ThemifyBuilderModuleJs.doAnimation(true);
		}
		$newElems.imagesLoaded(function(imgLoad){
			$newElems = $(imgLoad.elements);
			$newElems.show();

			if ( isProductLayout ) {
				if ( themifyScript.masonryLayout ) {
					$container.isotope('appended', $newElems );
				}
			} else {
				$container.isotope('appended', $newElems );
			}

			$('#infscr-loading').fadeOut('normal');
			if ( 1 == scrollMaxPages ) {
				$('#load-more, #infscr-loading').remove();
			}

			// Apply lightbox/fullscreen gallery to new items
                        Themify.InitGallery();

			// Remove tag to recently new elements
			$('.infscr_newElements').removeClass('infscr_newElements');

			// Tag elements added as new
			$newElems.addClass('infscr_newElements');

			// Tell everyone we have new elements
			$(document).trigger('newElements');

		});
		//$container.isotope('insert', $(newElements)).delay(400).isotope('layout');
		scrollMaxPages = scrollMaxPages - 1;
		if( 1 < scrollMaxPages && 'auto' != themifyScript.autoInfinite)
			$('#load-more a').show();
	});

	// disable auto infinite scroll based on user selection
	if( 'auto' == themifyScript.autoInfinite ){
		$('#load-more, #load-more a').hide();
	}
}

// WINDOW LOAD
$(window).load(function() {

	/////////////////////////////////////////////
	// Masonry layout
	/////////////////////////////////////////////
	if ( $( '.products' ).length > 0 ) {
		// isotope container, isotope item, item fetched by infinite scroll, infinite scroll
		infiniteIsotope('.grid2:not(.noisotope) .products, .grid3:not(.noisotope) .products, .grid4:not(.noisotope) .products', '.product', '#content .product', ' .products', function(container){
			if ( themifyScript.masonryLayout ) {
				container.isotope({resizable: false});
			}
			// update columnWidth on window resize
			$(window).on( 'debouncedresize', function(){
				if ( themifyScript.masonryLayout ) {
					container.isotope({
						// update columnWidth to a percentage of container width
						masonry: { columnWidth: '.grid-sizer' },
						isOriginLeft : ! $( 'body' ).hasClass( 'rtl' )
					}).isotope('layout');
				}
			});
		});
	}
	if ( $( '.post' ).length > 0 && ! $('body').hasClass('query-product') ) {
		// isotope container, isotope item, item fetched by infinite scroll, infinite scroll
		infiniteIsotope(null, '.post', '#content .post', '#loops-wrapper');
	}

	// Main Slider
	$('#slider').flexslider( {
		animation: themifyScript.animation,
		animationSpeed: parseInt( themifyScript.animationDuration ),
		slideshow: !('false' == themifyScript.slideshowSpeed),
		slideshowSpeed: parseInt( themifyScript.slideshowSpeed ),
		animationLoop: true,
		directionNav: true,
		prevText: "&laquo;",
		nextText: "&raquo;",
		pauseOnHover: true,
		start: function() {
			$('#slider').fadeIn(800);
			$(window).resize();

			// Slider Height
			setSlidersHeight();
		},
		before: function() {
			if ( $('.edit-button').length > 0 ) {
				$('.edit-button').stop().fadeOut(10, function(){
					$('.slide-excerpt').fadeOut(300, function(){
						$('.slide-post-title').fadeOut(300);
					});
				});
			} else {
				$('.slide-excerpt').stop().fadeOut(300, function(){
					$('.slide-post-title').fadeOut(300);
				});
			}
		},
		after: function() {
			var $activeSlide = $('.flex-active-slide');
			if ( $activeSlide.find('.slide-post-title').length > 0 ) {
				$activeSlide.find('.slide-post-title' ).stop().fadeIn(400, function(){
					$activeSlide.find('.slide-excerpt').fadeIn(400, function(){
						if ( $activeSlide.find('.edit-button').length > 0 )
							$activeSlide.find('.edit-button').fadeIn(400);
					});
				});
			} else {
				$activeSlide.find('.slide-excerpt').fadeIn(400, function(){
					if ( $activeSlide.find('.edit-button').length > 0 )
						$activeSlide.find('.edit-button').fadeIn(400);
				});
			}
		}
	} );

	// Move menu into side panel on small screens
	var $hMenu = $('#horizontal-menu');
	if ( $hMenu.length > 0 ) {
		themifyScript.smallScreen = parseInt( themifyScript.smallScreen, 10 );
		themifyScript.resizeRefresh = parseInt( themifyScript.resizeRefresh, 10 );
		var isSmallScreen = function() { return $(window).width() <= themifyScript.smallScreen; },
			didResize = false,
			$mainNavWrap = $('.main-nav-wrap'),
			$horizontalWrap = $('.horizontal-menu-wrap');

		if ( isSmallScreen() ) {
			$hMenu.detach().insertBefore( $mainNavWrap );
		}

		$(window).resize(function() {
			didResize = true;
		});

		setInterval(function() {
			if ( didResize ) {
				didResize = false;
				if ( isSmallScreen() ) {
					$hMenu.detach().insertBefore( $mainNavWrap );
				} else {
					$hMenu.detach().prependTo( $horizontalWrap );
				}
			}
		}, themifyScript.resizeRefresh);
	}
	
	if( $( 'body' ).hasClass( 'touch' ) && typeof jQuery.fn.themifyDropdown != 'function' ) {
		Themify.LoadAsync(themify_vars.url + '/js/themify.dropdown.js', function(){
			$( '#horizontal-menu' ).themifyDropdown();
		});
	}

	// EDGE MENU //
	$(function ($) {
		$("#horizontal-menu li").on('mouseenter mouseleave dropdown_open', function (e) {
			if ($('ul', this).length) {
				var elm = $('ul:first', this);
				var off = elm.offset();
				var l = off.left;
				var w = elm.width();
				var docW = $(window).width();
				var isEntirelyVisible = (l + w <= docW);

				if (!isEntirelyVisible) {
					$(this).addClass('edge');
					console.log('isEntirelyVisible');
				} else {
					$(this).removeClass('edge');
				}

			}
		});
	});

});

// Parallax Header
ParallaxHeader.init();
	
})(jQuery);