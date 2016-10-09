/* Themify Theme Scripts - http://themify.me/ */
/* global themifyScript, ThemifyGallery, ThemifyMediaElement, qp_max_pages */

// Initialize object literals
var LayoutAndFilter = {},
	Themify_Carousel_Tools = {};

//////////////////////////////////////////////
// jQuery functions					
/////////////////////////////////////////////
;(function($){

function doInfinite($container, selector){

	if ( 'undefined' !== typeof $.fn.infinitescroll ) {

		// Get max pages for regular category pages and home
		var scrollMaxPages = parseInt(themifyScript.maxPages);

		// Get max pages for Query Category pages
		if( typeof qp_max_pages != 'undefined') {
			scrollMaxPages = qp_max_pages;
		}

		// infinite scroll
		$container.infinitescroll({
			navSelector  : '#load-more a:last', 		// selector for the paged navigation
			nextSelector : '#load-more a:last', 		// selector for the NEXT link (to page 2)
			itemSelector : selector, 	// selector for all items you'll retrieve
			loadingText  : '',
			donetext     : '',
			loading 	 : { img: themifyScript.loadingImg },
			maxPage      : scrollMaxPages,
			behavior	 : 'auto' != themifyScript.autoInfinite? 'twitter' : '',
			pathParse 	 : function ( path ) {
				return path.match(/^(.*?)\b2\b(?!.*\b2\b)(.*?$)/).slice(1);
			},
			bufferPx: 10,
			pixelsFromNavToBottom: 30
		}, function(newElements) {
			// call Isotope for new elements
			var $newElems = $(newElements);

			// Mark new items: remove newItems from already loaded items and add it to loaded items
			$('.newItems').removeClass('newItems');
			$newElems.addClass('newItems');

			if ( 'reset' == themifyScript.resetFilterOnLoad ) {
				// Make filtered elements visible again
				LayoutAndFilter.reset();
			}

			$newElems.hide().imagesLoaded(function(){

				$newElems.css({'margin-left': 0}).fadeIn();

				// Create carousel on portfolio new items
				createCarousel( $( '.newItems .slideshow' ) );

				$('.wp-audio-shortcode, .wp-video-shortcode').not('div').each(function() {
					var $self = $(this);
					if ( $self.closest('.mejs-audio').length == 0 ) {
						ThemifyMediaElement.init($self);
					}
				});

				// Apply lightbox/fullscreen gallery to new items
				 Themify.InitGallery();

				// Portfolio background images
				doCoverBackgrounds();

				// Wide gallery
				doWideGallery();

				if ( LayoutAndFilter.masonryActive && 'object' == typeof $container.data('isotope') ) {
					$container.isotope('appended', $newElems );
				}
				if ( LayoutAndFilter.filterActive ) {
					// If new elements with new categories were added enable them in filter bar
					LayoutAndFilter.enableFilters();

					if ( 'scroll' == themifyScript.scrollToNewOnLoad ) {
						LayoutAndFilter.restore();
					}
				}

				$('#infscr-loading').fadeOut('normal');
				if( 1 == scrollMaxPages ){
					$('#load-more, #infscr-loading').remove();
				}

				/**
			     * Fires event after the elements and its images are loaded.
			     *
			     * @event infiniteloaded.themify
			     * @param {object} $newElems The elements that were loaded.
			     */
				$('body').trigger( 'infiniteloaded.themify', [$newElems] );

				$(window).trigger( 'resize' );
			});

			scrollMaxPages = scrollMaxPages - 1;
			if ( 1 < scrollMaxPages && 'auto' != themifyScript.autoInfinite ) {
				$('.load-more-button').show();
			}
		});

		// disable auto infinite scroll based on user selection
		if( 'auto' == themifyScript.autoInfinite ){
			$('#load-more, #load-more a').hide();
		}

	}

}

Themify_Carousel_Tools = {

	intervals: [],

	highlight: function( item ) {
		item.addClass('current');
	},
	unhighlight: function($context) {
		$('li', $context).removeClass('current');
	},

	timer: function($timer, timeout) {
		$timer.css('width', '0%');
		$timer.stop().animate({
			width: '100%'
		}, timeout, 'linear');
	},

	getCenter: function($context) {
		var visible = $context.triggerHandler('currentVisible');
		return Math.floor(visible.length / 2 );
	},

	getDirection: function($context, $element) {
		var visible = $context.triggerHandler( 'currentVisible' ),
			center = Math.floor(visible.length / 2 ),
			index = $element.index();
		if ( index >= center ) {
			return 'next';
		}
		return 'prev';
	},

	adjustCarousel: function($context) {
		if ( $context.closest('.twg-wrap').length > 0 ) {
			var visible = $context.triggerHandler('currentVisible').length,
				liWidth = $('li:first-child', $context).width();
			$context.triggerHandler('configuration', { width: ''+liWidth * visible, responsive: false });
			$context.parent().css('width', ( liWidth * visible ) + 'px');
		}
	}
};

// Initialize carousels //////////////////////////////
function createCarousel(obj) {
	if( typeof $.fn.carouFredSel == 'undefined' ) {
		return;
	}

	obj.each(function() {
		var $this = $(this),
			autoSpeed = 'off' != $this.data('autoplay') ? parseInt($this.data('autoplay'), 10) : 0,
			$twgList = $this.find('.twg-list').find('li');

		var oddNum = 1, odd_visible = oddNum;
		if ( $twgList.length > 0 ) {
			if ( oddNum < $twgList.length  ) {
				if ( $twgList.length % 2 == 0 ) {
					odd_visible = $twgList.length - 1;
				} else {
					odd_visible = $twgList.length;
				}
			}
		}

		var visibleSlides = $this.data('visible') ? parseInt( $this.data('visible'), 10 ) : odd_visible,
			sliderArgs = {
				responsive: true,
				circular:  ('yes' == $this.data('wrap')),
				infinite: true,
				height: 'auto',
				swipe: true,
				scroll: {
					items: $this.data('scroll') ? parseInt( $this.data('scroll'), 10 ) : 1,
					fx: $this.data('effect'),
					duration: parseInt($this.data('speed')),
					onBefore: function(items) {
						var $twgWrap = $this.closest('.twg-wrap'),
							$timer = $('.timer-bar', $twgWrap);
						if ( $timer.length > 0 ) {
							Themify_Carousel_Tools.timer($timer, autoSpeed);
							Themify_Carousel_Tools.unhighlight( $this );
						}
					},
					onAfter: function(items) {
						var newItems = items.items.visible;
						var $twgWrap = $this.closest('.twg-wrap' );
						if ( $twgWrap.length > 0 ) {
							var $center = newItems.filter(':eq(' + Themify_Carousel_Tools.getCenter($this) + ')');
							$('.twg-link', $center).trigger(themifyScript.galleryEvent);
							Themify_Carousel_Tools.highlight( $center );
						}
					}
				},
				auto: {
					play: ('off' != $this.data('autoplay')),
					timeoutDuration : autoSpeed
				},
				items: {
					visible: {
						min: 1,
						max: visibleSlides
					},
					width: $this.data('width') ? parseInt( $this.data('width'), 10 ) : $this.closest('.carousel-wrap').width() / odd_visible
				},
				prev: {
					button: 'yes' == $this.data('slidernav') ? '#' + $this.data('sliderid') + ' .carousel-prev' : null
				},
				next: {
					button: 'yes' == $this.data('slidernav') ? '#' + $this.data('sliderid') + ' .carousel-next' : null
				},
				pagination: {
					container: 'yes' == $this.data('pager') ? '#' + $this.data('sliderid') + ' .carousel-pager' : null,
					anchorBuilder: function() {
						return false;
					}
				},
				onCreate: function() {
					var $carouselWrap = $this.closest('.carousel-wrap'),
						$twgWrap = $this.closest('.twg-wrap');

					$this.closest('.slider').prevAll('.slideshow-slider-loader').first().remove();

					$carouselWrap.css({
						'visibility' : 'visible',
						'height' : 'auto'
					}).addClass('carousel-ready');

					$carouselWrap.find('.gallery-slider-thumbs').css( 'visibility', 'visible' );

					if ( 'no' == $this.data('slidernav') ) {
						$('.carousel-prev', $carouselWrap).remove();
						$('.carousel-next', $carouselWrap).remove();
					}

					if ( $twgWrap.length > 0 ) {

						var center = Themify_Carousel_Tools.getCenter($this),
							$center = $('li', $this).filter(':eq(' + center + ')'),
							$timer = $('.timer-bar', $twgWrap);

						Themify_Carousel_Tools.highlight( $center );

						if ( $timer.length > 0 ) {
							Themify_Carousel_Tools.timer($timer, autoSpeed);
							Themify_Carousel_Tools.unhighlight( $this );
						}

						$this.trigger( 'slideTo', [ -center, { duration: 0 } ] );

						$('.carousel-pager', $twgWrap).remove();
						$('.carousel-prev', $twgWrap).addClass('gallery-slider-prev').text('');
						$('.carousel-next', $twgWrap).addClass('gallery-slider-next').text('');

					}

					$(window).resize();

					Themify_Carousel_Tools.adjustCarousel($this);
				}
			};

		// Fix unresponsive js script when there are only one slider item
		if ( $this.children().length < 2 ) {
			sliderArgs.onCreate();
			// skip initialize carousel on this element
			return true;
		}

		$this.carouFredSel( sliderArgs ).find('li').on(themifyScript.galleryEvent, function(){
			if ( $this.closest('.twg-wrap').length > 0 ) {
				var $thisli = $(this);
				$('li', $this).removeClass('current');
				$thisli.addClass('current');
				$thisli.trigger('slideTo', [
						$thisli,
						- Themify_Carousel_Tools.getCenter($this),
						false,
						{
							items: 1,
							duration: 300,
							onBefore: function(items) {
								var $twgWrap = $this.closest('.twg-wrap' ),
									$timer = $('.timer-bar', $twgWrap);
								if ( $timer.length > 0 ) {
									Themify_Carousel_Tools.timer($timer, autoSpeed);
									Themify_Carousel_Tools.unhighlight( $this );
								}
							},
							onAfter: function(items) { }
						},
						null,
						Themify_Carousel_Tools.getDirection($this, $thisli)]
				);
			}
		});

		/////////////////////////////////////////////
		// Resize thumbnail strip on window resize
		/////////////////////////////////////////////
		$(window).on('debouncedresize', Themify_Carousel_Tools.adjustCarousel($this) );

	});
}

// Scroll to Element //////////////////////////////
function themeScrollTo(offset) {
	$('body,html').animate({ scrollTop: offset }, 800);
}

// Entry Filter /////////////////////////
LayoutAndFilter = {
	filterActive: false,
	masonryActive: false,
	init: function() {
		themifyScript.disableMasonry = $('body').hasClass('masonry-enabled') ? '' : 'disable-masonry';
		if ( 'disable-masonry' != themifyScript.disableMasonry ) {
			this.enableFilters();
			this.filter();
			this.filterActive = true;
		}
	},
	enableFilters: function() {
		var $filter = $('.post-filter');
		if ( $filter.find('a').length > 0 && 'undefined' !== typeof $.fn.isotope ) {
			$filter.find('li').each(function(){
				var $li = $(this),
					$entries = $li.parent().next(),
					cat = $li.attr('class').replace(/(current-cat)|(cat-item)|(-)|(active)/g, '').replace(' ', '');
				if ( $entries.find('.portfolio-post.cat-' + cat).length <= 0 ) {
					$li.hide();
				} else {
					$li.show();
				}
			});
		}
	},
	filter: function() {
		var $filter = $('.post-filter');
		if ( $filter.find('a').length > 0 && 'undefined' !== typeof $.fn.isotope ) {
			$filter.addClass('filter-visible').on('click', 'a', function( e ) {
				e.preventDefault();
				var $li = $(this).parent(),
					$entries = $li.parent().next();
				if ( $li.hasClass('active') ) {
					$li.removeClass('active');
					$entries.isotope({
						layoutMode: 'packery',
						filter: '.portfolio-post',
						isOriginLeft : ! $( 'body' ).hasClass( 'rtl' )
					});
				} else {
					$li.siblings('.active').removeClass('active');
					$li.addClass('active');
					$entries.isotope({
						filter: '.cat-' + $li.attr('class').replace(/(current-cat)|(cat-item)|(-)|(active)/g, '').replace(' ', ''),
						isOriginLeft : ! $( 'body' ).hasClass( 'rtl' )
					});
				}
			});
		}
	},
	scrolling: false,
	reset: function() {
		$('.post-filter').find('li.active').find('a').addClass('previous-active').trigger('click');
		this.scrolling = true;
	},
	restore: function() {
		//$('.previous-active').removeClass('previous-active').trigger('click');
		var $first = $('.newItems').first(),
			self = this,
			to = $first.offset().top - ( $first.outerHeight(true)/2 ),
			speed = 800;

		if ( to >= 800 ) {
			speed = 800 + Math.abs( ( to/1000 ) * 100 );
		}
		$('html,body').stop().animate({
			scrollTop: to
		}, speed, function() {
			self.scrolling = false;
		});
	},
	layout: function() {
		if ( 'disable-masonry' != themifyScript.disableMasonry ) {
			$('.loops-wrapper.portfolio,.loops-wrapper.portfolio-taxonomy').isotope({
				layoutMode: 'packery',
				itemSelector : '.portfolio-post',
				isOriginLeft : ! $( 'body' ).hasClass( 'rtl' )
			}).addClass('masonry-done');

			$('.loops-wrapper.grid4,.loops-wrapper.grid3,.loops-wrapper.grid2').not('.portfolio-taxonomy,.portfolio')
				.isotope({
					layoutMode: 'packery',
					itemSelector: '.loops-wrapper > article',
					isOriginLeft : ! $( 'body' ).hasClass( 'rtl' )
				})
				.addClass('masonry-done')
				.isotope( 'once', 'layoutComplete', function() {
					$(window).trigger('resize');
				});

			$('.woocommerce.archive').find('#content').find('ul.products').isotope({
				layoutMode: 'packery',
				itemSelector : '.product',
				isOriginLeft : ! $( 'body' ).hasClass( 'rtl' )
			}).addClass('masonry-done');

			this.masonryActive = true;
		}
		var $gallery = $('.gallery-wrapper.packery-gallery');
		if ( $gallery.length > 0 ) {
			$gallery.isotope({
				layoutMode: 'packery',
				itemSelector: '.item',
				isOriginLeft : ! $( 'body' ).hasClass( 'rtl' )
			});
			this.masonryActive = true;
		}
	},
	reLayout: function() {
		var $loopsWrapper = $('.loops-wrapper'), $gallery = $('.gallery-wrapper.packery-gallery');
		if ( $loopsWrapper.hasClass('masonry-done') && 'object' == typeof $loopsWrapper.data('isotope') ) {
			if ( this.masonryActive ) {
				$loopsWrapper.isotope('layout');
			}
		}
		if ( $gallery.length > 0 && 'object' == typeof $gallery.data('isotope') ) {
			if ( this.masonryActive ) {
				$gallery.isotope('layout');
			}
		}
	}
};

function doCoverBackgrounds() {
	if ( 'undefined' !== typeof $.fn.backstretch && $('.masonry-layout').length > 0 ) {
		$('.portfolio-post').each(function() {
			var $postImage = $(this).find('.post-image'),
				$img = $postImage.find('img'),
				src = $img.attr('src');
			if ( 'undefined' !== typeof src && $postImage.find('.backstretch').length <= 0 ) {
				$postImage.backstretch(src);
				var $a = $postImage.find('a'),
					$saveA = $a;
				$a.remove();
				$img.remove();
				$postImage.find('img').wrap($saveA);
			}
		});
	}
}

function doWideGallery() {
	if ( 'undefined' !== typeof $.fn.ThemifyWideGallery ) {
		$('.twg-wrap').ThemifyWideGallery({
			speed: parseInt(themifyScript.galleryFadeSpeed, 10),
			event: themifyScript.galleryEvent,
			ajax_url: themifyScript.ajax_url,
			ajax_nonce: themifyScript.ajax_nonce,
			networkError: themifyScript.networkError,
			termSeparator: themifyScript.termSeparator
		});
	}
}

// Test if this is a touch device /////////
function is_touch_device() {
	return  $('body').hasClass('touch');
}

// DOCUMENT READY
$(document).ready(function() {

	var $body = $('body'), $window = $(window), $header = $('#header');

	// Add dropdown toggle that display child menu items.
	$( '.main-nav .page_item_has_children > a, .main-nav .menu-item-has-children > a' ).append( '<button class="dropdown-toggle" aria-expanded="false">' + themifyScript.expand + '</button>' );

	$('body').on('click touchend', '.dropdown-toggle', function( e ) {
		var $this = $( this),
			$parent = $this.parent();
		e.preventDefault();
		e.stopPropagation();
		$this.addClass( 'fading-icon' );
		$parent.next( '.children, .sub-menu' ).stop().slideToggle().promise().done(function(){
			$(this).toggleClass( 'expanded' );
			$header.getNiceScroll().resize();
			$this.removeClass( 'fading-icon' );
			$this.toggleClass( 'toggle-on' );
			$this.html( $this.html() === themifyScript.expand ? themifyScript.collapse : themifyScript.expand );
		});
		$this.attr( 'aria-expanded', $this.attr( 'aria-expanded' ) === 'false' ? 'true' : 'false' );
	} );

	// Portfolio background images
	doCoverBackgrounds();

	/////////////////////////////////////////////
	// Scroll to row when a menu item is clicked.
	/////////////////////////////////////////////
	if ( 'undefined' !== typeof $.fn.themifyScrollHighlight ) {
		$body.themifyScrollHighlight();
	}

	/////////////////////////////////////////////
	// Initialize Layout
	/////////////////////////////////////////////
	LayoutAndFilter.init();
	/////////////////////////////////////////////
	// Entry Filter Layout
	/////////////////////////////////////////////
	LayoutAndFilter.layout();

	/////////////////////////////////////////////
	// Scroll to top
	/////////////////////////////////////////////
	$('.back-top a').on('click', function(e){
		e.preventDefault();
		themeScrollTo(0);
	});

	/////////////////////////////////////////////
	// Toggle main nav on mobile
	/////////////////////////////////////////////
	if( $( 'body' ).hasClass( 'touch' ) && typeof jQuery.fn.themifyDropdown != 'function' ) {
		Themify.LoadAsync(themify_vars.url + '/js/themify.dropdown.js', function(){
			$( '#main-nav' ).themifyDropdown();
		});
	}

	/////////////////////////////////////////////
	// Side Menu
	/////////////////////////////////////////////
	$('#menu-icon').themifySideMenu({
		side: 'left'
	});
        
        var $overlay = $( '<div class="body-overlay">' );
        $body.append( $overlay ).on( 'sidemenushow.themify', function () {
            $overlay.addClass( 'body-overlay-on' );
        } ).on( 'sidemenuhide.themify', function () {
            $overlay.removeClass( 'body-overlay-on' );
        } ).on( 'click.themify touchend.themify', '.body-overlay', function () {
            $( '#menu-icon' ).themifySideMenu( 'hide' );
        } ); 
        
	if ( 'undefined' !== typeof $.fn.niceScroll && ! is_touch_device() ) {
		// Initialize NiceScroll
		$header.niceScroll();

		$('#mobile-menu').on( 'sidemenuaftershow.themify', function(){
			$header.niceScroll().resize();
		});
		// Release spacing taken by mobile menu
		$window.on('debouncedresize', function(){
			$header.niceScroll().resize();
		});
	}

	/////////////////////////////////////////////
	// Reset slide nav width
	/////////////////////////////////////////////
	if ( $(window).width() < 780 ) {
		$('#main-nav').addClass('scroll-nav');
	}
	$(window).resize(function(){
            if ( $(window).width() > 780 ) {
                    $('body').removeAttr('style');
                    $('#main-nav').removeClass('scroll-nav');
            } else {
                    $('#main-nav').addClass('scroll-nav');
            }
            if( $( '#menu-icon' ).is(':visible') && $('#mobile-menu').hasClass('sidemenu-on')){
                $overlay.addClass( 'body-overlay-on' );
            }
            else{
                 $overlay.removeClass( 'body-overlay-on' );
            }
	});

});

// WINDOW LOAD
$(window).load(function() {

	var $body = $('body');

	/////////////////////////////////////////////
	// Carousel initialization
	/////////////////////////////////////////////
	if( typeof $.fn.carouFredSel !== 'undefined' ) {
		createCarousel($('.slideshow'));
	}

	/////////////////////////////////////////////
	// Entry Filter Layout
	/////////////////////////////////////////////
	$body.imagesLoaded(function(){
		LayoutAndFilter.reLayout();
		$(window).resize();
	});

	///////////////////////////////////////////
	// Initialize infinite scroll
	///////////////////////////////////////////
	doInfinite( $('#loops-wrapper'), '.post' );

	/////////////////////////////////////////////
	// Initialize WordPress Gallery in Section
	/////////////////////////////////////////////
	doWideGallery();

	///////////////////////////////////////////
	// Initialize infinite scroll
	///////////////////////////////////////////
	var $products = $('.woocommerce.archive').find('#content').find('ul.products');
	if ( $body.hasClass( 'post-type-archive-product' ) && $products.length > 0 ) {
		doInfinite( $products, '.product' );
	}
});

})(jQuery);