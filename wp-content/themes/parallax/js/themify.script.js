;// Themify Theme Scripts - http://themify.me/

// Themify Lightbox, Fixed Header and Parallax /////////////////////////
var FixedHeader, ThemifyParallax;

// Begin jQuery functions
(function($) {

	function UpdateQueryString(a,b,c){
		c||(c=window.location.href);var d=RegExp("([?|&])"+a+"=.*?(&|#|$)(.*)","gi");if(d.test(c))return b!==void 0&&null!==b?c.replace(d,"$1"+a+"="+b+"$2$3"):c.replace(d,"$1$3").replace(/(&|\?)$/,"");if(b!==void 0&&null!==b){var e=-1!==c.indexOf("?")?"&":"?",f=c.split("#");return c=f[0]+e+a+"="+b,f[1]&&(c+="#"+f[1]),c}return c;
	}

	// Scroll to Element //////////////////////////////
	function themeScrollTo(offset, duration) {
		duration = duration || 800;
		$('html, body').stop().animate({ scrollTop: offset }, duration);
	}

	// Themify Scroll
	(function($, window, document, undefined) {

		// Create the defaults once
		var pluginName = "themifyScroll", defaults = {
			speed : 1800
		};

		// The actual plugin constructor
		function Plugin(element, options) {
			this.element = element;
			this.options = $.extend({}, defaults, options);
			this._defaults = defaults;
			this._name = pluginName;
			this.onClicking = false;
			this.init();
		}

		Plugin.prototype = {
			sections: [],
			init : function() {
				var self = this,
					IE = this.ie();

				self.sections = $('#main-nav li a').map(function(){
					return this.hash || null;
				}).get();
				self.sections.push('#header');

				// click event
				$('body').on('click', 'a[href*="#"]', function(e) {
					var $this = $(this), elementClick = $this.prop('hash');
					
					if ( 'undefined' != typeof elementClick && elementClick.indexOf('#') != -1 && $(elementClick).length > 0 ) {
						var destination = $(elementClick).offset().top + 10, newHash = '#' + elementClick.replace('#', '');
						if( $(elementClick).length === 0 ) { return; }

						// update state
						self.onClicking = true;

						e.preventDefault();
						$("html,body").animate({
							scrollTop: destination
						}, self.options.speed, function(){
							// add active class
							$this.parent('li').addClass('current_page_item').siblings().removeClass('current_page_item');

							if(elementClick.replace('#','') !== 'header'){
								self.setHash(newHash);
							} else {
								self.clearHash();
							}

							self.onClicking = false;
						});
					}
					
				});

				// change hash url
				if( !IE || (IE && IE > 9) ) {
					this.changeHash();
				}
			},

			changeHash : function() {
				var self = this;

				if (self.sections.length > 0) {
					var didScroll = false,
						currentHash;
					$(window).scroll(function() {
						didScroll = true;
					});

					setInterval(function() {

						if(self.onClicking) return;

						if ( didScroll ) {
							didScroll = false;
							currentHash = window.location.hash.replace(/^#\!\//, "");

							if ( $(window).scrollTop() == 0 && currentHash != '' ) {
								self.clearHash();
							}

							$.each(self.sections, function(index, value){
								var section = value, obj = $(value);
								if (obj.length > 0) {
									var offsetY = obj.offset().top,
										elemHeight = obj.outerHeight();

									if ( offsetY < window.pageYOffset && offsetY + elemHeight > window.pageYOffset ) {
										if( section == currentHash || section == '#header' ) return;
										
										self.setHash(section);
										$('a[href*="'+section+'"]').parent('li').addClass('current_page_item').siblings().removeClass('current_page_item current-menu-item');
										
									}
								}
							});
						}
						
					}, 500);
				}
			},

			setHash: function(hash) {
				if(history.pushState) {
					history.pushState(null, null, hash);
				}
				else {
					location.hash = hash;
				}
			},

			clearHash: function() {
				// remove hash
				if ( window.history && window.history.replaceState ) { 
					window.history.replaceState('', '', window.location.pathname); 
				} else { 
					window.location.href = window.location.href.replace(/#.*$/, '#'); 
				}
			},

			ie: function() {
				var undef,
					v = 3,
					div = document.createElement('div'),
					all = div.getElementsByTagName('i');
				
				while (
					div.innerHTML = '<!--[if gt IE ' + (++v) + ']><i></i><![endif]-->',
					all[0]
				);
				
				return v > 4 ? v : undef;
			}
		};

		$.fn[pluginName] = function(options) {
			return this.each(function() {
				if (!$.data(this, "plugin_" + pluginName)) {
					$.data(this, "plugin_" + pluginName, new Plugin(this, options));
				}
			});
		};

	})(jQuery, window, document);

	// Test if touch event exists //////////////////////////////
	function is_touch_device() {
		return $('body').hasClass('touch') || !!('window.navigator.msMaxTouchPoints' in window);
	}

	function is_ie() {
		return navigator.appName == 'Microsoft Internet Explorer';
	}

	// Start infinite scroll
	function infinite(itemSelectorInf, containerInfinite) {

		// Get max pages for regular category pages and home
		var scrollMaxPages = parseInt(themifyScript.maxPages), $containerInfinite = $(containerInfinite);
		// Get max pages for Query Category pages
		if ( typeof qp_max_pages != 'undefined')
			scrollMaxPages = qp_max_pages;

		// infinite scroll
		$containerInfinite.infinitescroll({
			navSelector : '#load-more a:last', // selector for the paged navigation
			nextSelector : '#load-more a:last', // selector for the NEXT link (to page 2)
			itemSelector : itemSelectorInf, // selector for all items you'll retrieve
			loadingText : '',
			donetext : '',
			loading : {
				img : themifyScript.loadingImg
			},
			maxPage : scrollMaxPages,
			behavior : 'auto' != themifyScript.autoInfinite ? 'twitter' : '',
			pathParse : function(path, nextPage) {
				return path.match(/^(.*?)\b2\b(?!.*\b2\b)(.*?$)/).slice(1);
			}
		}, function(newElements) {
			var $newElems = $(newElements).hide();

			// Mark new items: remove newItems from already loaded items and add it to loaded items
			$('.post.newItems').removeClass('newItems');
			$newElems.addClass('newItems');

			$newElems.imagesLoaded(function() {

				$newElems.fadeIn();

				$('#infscr-loading').fadeOut('normal');
				if (1 == scrollMaxPages) {
					$('#load-more, #infscr-loading').remove();
				}

				// Apply lightbox/fullscreen gallery to new items
				Themify.InitGallery();
				// Create carousel on portfolio new items
				if($('.type-portfolio.newItems .slideshow' ).length>0){
					if(!$.fn.carouFredSel){
						Themify.LoadAsync(themify_vars.url+'/js/carousel.js',function(){
							createCarousel( $( '.type-portfolio.newItems .slideshow' ), 'normal' );
						});
					}
					else{
					   createCarousel( $( '.type-portfolio.newItems .slideshow' ), 'normal' );
					}
				}

				// reset the breakpoints for portfolio expander
				portfolio_item_break_point();
			});

			scrollMaxPages = scrollMaxPages - 1;
			if (1 < scrollMaxPages && 'auto' != themifyScript.autoInfinite)
				$('#load-more a').show();
		});

		// disable auto infinite scroll based on user selection
		if ('auto' == themifyScript.autoInfinite) {
			$('#load-more, #load-more a').hide();
		}

	}

	// Initialize carousels
	function createCarousel(obj, mode) {
		obj.each(function() {
			var $this = $(this),
				this_id = $this.data('id');
			$this.carouFredSel({
				responsive: true,
				prev: function() {
					if ( $this.closest( '.module-portfolio' ).length > 0 ) {
						var path = '#' + $this.closest( '.module-portfolio' ).eq(0).attr('id') + ' #' + this_id + ' .carousel-prev';
						return path;
					} else if ( $this.closest( '.type-page' ).length > 0 ) {
						var path = '#' + $this.closest( '.type-page' ).eq(0).attr('id') + ' .page-content .shortcode #' + this_id + ' .carousel-prev';
						return path;
					} else if ( $this.closest( '#loops-wrapper' ).length > 0 ) {
						return '#loops-wrapper #' + this_id + ' .carousel-prev';
					} else {
						return '#' + this_id + ' .carousel-prev';
					}
				},
				next: function() {
					if ( $this.closest( '.module-portfolio' ).length > 0 ) {
						var path = '#' + $this.closest( '.module-portfolio' ).eq(0).attr('id') + ' #' + this_id + ' .carousel-next';
						return path;
					} else if ( $this.closest( '.type-page' ).length > 0 ) {
						var path = '#' + $this.closest( '.type-page' ).eq(0).attr('id') + ' .page-content .shortcode #' + this_id + ' .carousel-next';
						return path;
					} else if ( $this.closest( '#loops-wrapper' ).length > 0 ) {
						return '#loops-wrapper #' + this_id + ' .carousel-next';
					} else {
						return '#' + this_id + ' .carousel-next';
					}
				},
				pagination: {
					container: function() {
					if ( $this.closest( '.module-portfolio' ).length > 0 ) {
						var path = '#' + $this.closest( '.module-portfolio' ).eq(0).attr('id') + ' #' + this_id + ' .carousel-pager';
						return path;
					} else if ( $this.closest( '.type-page' ).length > 0 ) {
						var path = '#' + $this.closest( '.type-page' ).eq(0).attr('id') + ' .page-content .shortcode #' + this_id + ' .carousel-pager';
						return path;
					} else if ( $this.closest( '#loops-wrapper' ).length > 0 ) {
						return '#loops-wrapper #' + this_id + ' .carousel-pager';
					} else {
						return '#' + this_id + ' .carousel-pager';
					}
				}
				},
				circular: true,
				infinite: true,
				swipe: true,
				scroll: {
					items: 1,
					fx: $this.data('effect'),
					duration: parseInt($this.data('speed'))
				},
				auto: {
					play: ('off' != $this.data('autoplay')),
					timeoutDuration: 'off' != $this.data('autoplay') ? parseInt($this.data('autoplay')) : 0
				},
				items: {
					visible: {
						min: 1,
						max: 1
					},
					width: 222
				},
				onCreate: function() {
					$this.closest('.slideshow-wrap').css({
						'visibility': 'visible',
						'height': 'auto'
					});
					$('.carousel-next, .carousel-prev', $this.closest('.slideshow-wrap')).hide();
					$(window).resize();
				}
			});
		});
	}

	// Fixed Header /////////////////////////
	FixedHeader = {
		init : function() {
			if ( 'fixed-header' !== themifyScript.fixedHeader ) {
				return;
			}
			this.headerHeight = $('#header').outerHeight() - $('#nav-bar').outerHeight();

			if ( $('body').hasClass('ie') ) { $('html, body').addClass('iefix'); }

			this.activate();
			$(window).on('scroll', this.activate)
			.on('touchstart.touchScroll', this.activate)
			.on('touchmove.touchScroll', this.activate);
		},
		activate : function() {

			if ( $(window).scrollTop() <= FixedHeader.headerHeight ) {
				FixedHeader.scrollDisabled();
			} else {
				FixedHeader.scrollEnabled();
			}
		},
		scrollDisabled : function() {
			$('#nav-bar').removeClass('fixed-nav-bar');
			$('body').removeClass('fixed-header-on');
		},
		scrollEnabled : function() {
			$('#nav-bar').addClass('fixed-nav-bar');
			$('body').addClass('fixed-header-on');
		}
	};

	// Parallax /////////////////////////
	ThemifyParallax = {
		init: function() {
			this.windowHeight = $(window).height();
			$(window).resize(function(){
				ThemifyParallax.windowHeight = $(window).height();
			});

			if(themifyScript.scrollingEffect) {
				if(themifyScript.scrollingEffectType == 'effect1'){
					this.setup_effect_1();
				} else {
					this.setup_effect_2();
				}
			} else {
				this.setBackground();
			}
		},

		setup_effect_1: function() {
			var self = ThemifyParallax;

			self.setBackground(); // set background fullcover

			// only work in query section
			if ( ! $('body').hasClass('query-section') || is_ie() ){ return; }
			
			$('.section-post, #headerwrap').each(function(){
				var ids = $(this).prop('id'),
					$elemns = $('#' + ids ),
					didScroll = false;

				setInterval(function() {
					if ( didScroll ) {
						didScroll = false;
						self.run($elemns);
					}
				}, 550);

				$(window).on('scroll touchstart.touchScroll touchmove.touchScroll', function(){
					didScroll = true;
				});
			});
		},

		setup_effect_2: function(){
			$('.section-post, #headerwrap').each(function(){
				// Store some variables based on where we are
				var $self = $(this),
					bgImage = typeof $self.data('bg') !== 'undefined' ? 'url("'+ $self.data('bg') +'")' : '';

				$self.addClass('scrolling-bg-image').css({
					backgroundImage: bgImage
				});

				if( typeof $.fn.builderParallax !== 'undefined' ){
					$self.builderParallax("50%", 0.1);
				}
			});
		},

		run: function(elemns) {
			var $window = $(window),
				pos = $window.scrollTop(),
				$element = elemns,
				top = $element.offset().top,
				height = $element.outerHeight(true);	

			// Check if totally above or totally below viewport
			if (top + height < pos || top > pos + ThemifyParallax.windowHeight) {
				return;
			}
			var cssTopPos = Math.max(Math.round((pos - top) * 0.2), 0) + 'px';

			$element.css('top', cssTopPos);

			if($('#nav-bar').hasClass('fixed-nav-bar')) {
				$('#nav-bar').css({'bottom': ''});
			}
		},

		newPos: function( pos, adjust, ratio ) {
			return ((pos - adjust) * ratio)  + "px";
		},

		setBackground: function(){
			// Fullscreen bg
			if ( typeof $.fn.backstretch !== 'undefined') {
				var $section = $('.section-post, #headerwrap'),
					resizeId;
				$section.each(function() {
					var bg = $(this).data('bg');
					if ( typeof bg !== 'undefined') {
						if ($(this).hasClass('fullcover')) {
							$(this).backstretch(bg);
						} else {
							$(this).css('background-image', 'url("' + bg + '")');
						}
					}
				});

				$(window).on('backstretch.show', function(e, instance) {
					instance.$container.css('z-index', '');
				}).on('resize', function(){
					clearTimeout(resizeId);
					resizeId = setTimeout(function(){
						$section.each(function(){
							if($(this).hasClass('fullcover')){
								var instance = $(this).data("backstretch");
								if('undefined' !== typeof instance) instance.resize();
							}
						});
					}, 500);
				});
			}
		}
	};

	// portfolio ajax post break point
	function portfolio_item_break_point() {
		// section portfolio break point
		$('.shortcode.portfolio, .module.module-portfolio, #loops-wrapper.query-type-portfolio').each(function(){
			var selector = '', viewport = $(window).width();

			// reset
			$(this).find('.sec-post-break').remove();

			if($(this).hasClass('grid2-thumb') || $(this).hasClass('grid2')){
				selector = '.post:nth-of-type(2n+2)';
			} else if($(this).hasClass('grid4')) {
				selector = '.post:nth-of-type(4n+4)';
			} else if($(this).hasClass('grid3')){
				selector = '.post:nth-of-type(3n+3)';
			} else if($(this).hasClass('grid2')) {
				selector = '.post:nth-of-type(2n+2)';
			} else {
				selector = '.post:nth-of-type(1n+1)';
			}

			// mobile
			if(viewport < 480) {
				selector = '.post:nth-of-type(1n+1)';
			}

			$(this).find(selector).each(function(){
				$('<div class="sec-post-break" />').insertAfter($(this));
			});
		});
	}

	// Returns real dimensions from scaled or hidden elements
	function realDimension($obj, dimension) {
		var $clone = $obj.clone().css('visibility', 'hidden').appendTo($('body') ),
			value = 'width' == dimension ? $clone.width() : $clone.height();
		$clone.remove();
		return value;
	}

	/////////////////////////////////////////////
	// Execute when DOM is ready
	/////////////////////////////////////////////
	$(document).ready(function() {

		var $body = $('body'),
			$skills = $('.progress-bar', $body);

		// Toggle main nav on mobile
			
		$('#menu-icon').themifySideMenu({
			close: '#menu-icon-close'
		});
		
		var $overlay = $( '<div class="body-overlay">' );
		$body.append( $overlay ).on( 'sidemenushow.themify', function () {
			$overlay.addClass( 'body-overlay-on' );
		} ).on( 'sidemenuhide.themify', function () {
			$overlay.removeClass( 'body-overlay-on' );
		} ).on( 'click.themify touchend.themify', '.body-overlay', function () {
			$( '#menu-icon' ).themifySideMenu( 'hide' );
		} ); 
		
		$(window).resize(function(){
			if( $( '#menu-icon' ).is(':visible') && $('#mobile-menu').hasClass('sidemenu-on')){
				$overlay.addClass( 'body-overlay-on' );
			}
			else{
				 $overlay.removeClass( 'body-overlay-on' );
			}
		});

		$(window).resize(function(){
			var viewport = $(window).width();
			if (viewport > 1000) {
				$( '#menu-icon' ).themifySideMenu( 'hide' );
			}
		});

		// Back to top
		$('.not-ie .back-top a').on('click', function(e){
			e.preventDefault();
			themeScrollTo(0);
		});

		// Remove padding for admin bar, just let it sit on top of the background
		if ( $body.hasClass( 'home' ) && $body.hasClass( 'admin-bar' ) ) {
			$('html').css( 'cssText', 'margin-top:0 !important;' );
		}

		// If is touch device, add class
		if (is_touch_device()) {
			$body.addClass('is-touch');
			var vh = $(window).height();
			if ( $body.hasClass('home') ) {
				$('#headerwrap, #header').css('height', vh);
			}
		}
		
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

		// Fixed header
		FixedHeader.init();

		// Parallax section background
		ThemifyParallax.init();

		// Height of top bar
		var menuHeight = $('#nav-bar').outerHeight();

		// portfolio breakpoint
		portfolio_item_break_point();

		// Portfolio expand js
		var portoInitialPos;
		$( 'body' ).on( 'click', '.porto-expand-js', function(e){
			if ( 0 === $(e.target).closest( '.themify_builder_slider' ).length ) {
				e.preventDefault();
				var $this = $(this),
					url = UpdateQueryString( 'porto_expand', 1, $this.attr('href') ),
					placehold = $this.closest('.post').nextAll('.sec-post-break').first();

				if ( placehold.length === 0 ) placehold = $this.closest('.shortcode');
				if ( placehold.length === 0 ) placehold = $this.closest('.module-portfolio');
				if ( placehold.length === 0 ) placehold = $('<div class="sec-post-break" />').appendTo( $this.closest('.post').parent() );

				// cache the initial position
				portoInitialPos = $(window).scrollTop();

				$('.portfolio-expanded').remove();
				$('<div class="portfolio-expanded"><div class="portfolio-expand-scaler"><iframe id="portfolio-lightbox-iframe" frameborder="0" allowfullscreen></iframe></div></div>')
				.appendTo(placehold).hide();
				$('.portfolio-loader').remove();
				$('<div class="portfolio-loader">').appendTo($this.closest('.portfolio-post').find('.post-image')).show();

				$('.portfolio-expanded').show();
				$('#portfolio-lightbox-iframe').prop('src', url).load(function(){
					var $contents = $(this).contents();
					$('.portfolio-loader').remove();
					$('.portfolio-expanded').hide().slideDown('slow');
					themeScrollTo( $('.portfolio-expanded').offset().top - menuHeight, 1000 );
					$body.trigger('portfolio_expanded');

					$contents.find('.close-expanded').on('click', function(e){
						e.preventDefault();
						$('.portfolio-expanded').slideUp(800, function(){
							$(this).remove();
						});
						themeScrollTo(portoInitialPos);
					});
					var $expand_posts = [],
						$current = $this.data('post-id'),
						$current_key = false;
					
					$this.closest('.post').parent().find('.post').each(function(){
						var $post = $(this).find('a.porto-expand-js').first();
						if($post.length>0){
							var $id = $post.data('post-id');
							 if($id){
								$expand_posts.push({'id':$id,'post':$post});
								if($current==$id){
									$current_key = parseInt($expand_posts.length-1);
								}
							 }
						}
					});
					if($current_key===0){
						$contents.find('.expand-prev').remove();
					}
					if($current_key===$expand_posts.length-1){
						$contents.find('.expand-next').remove();
					}
				   
					if($expand_posts.length>1){
						$contents.find('.expand-arrow').on('click', function(e){
							e.preventDefault();
							var $key = false;
							for(var $i in $expand_posts){
								if($expand_posts[$i].id==$current){
									$key = parseInt($i);
									break;
								}
							}
							if($key!==false){
								var $next = $(this).hasClass('expand-next')?$key+1:$key-1;
								if($expand_posts[$next]){
									$expand_posts[$next].post.trigger('click');
								}
							}
								
						}).show();
					}
				}).iframeAutoHeight({
					callback: function() {
						var $this = $(this);
						setTimeout(function(){
							$this.height( $this.contents().find('body').height() );
						}, 800);
					}
				});
			}
		});

		// #main-nav
		$body.on('touchstart touchmove touchend', '#main-nav', function(e) {
			e.stopPropagation();
		});

		/////////////////////////////////////////////
		// Chart Initialization
		/////////////////////////////////////////////
		if($('.chart').length>0){
			if(!$.fn.waypoint || !$.fn.easyPieChart){
				Themify.LoadAsync(themify_vars.url+'/js/jquery.easy-pie-chart.js',function(){
					Themify.LoadAsync(themify_vars.url+'/js/waypoints.min.js',ThemifyChart);
				});
			}
			else{
				ThemifyChart();
			}
		}

		function ThemifyChart(){
			$.each(themifyScript.chart, function(index, value){
				if( 'false' == value || 'true' == value ){
					themifyScript.chart[index] = 'false'!=value;
				} else if( parseInt(value) ){
					themifyScript.chart[index] = parseInt(value);
				} else if( parseFloat(value) ){
					themifyScript.chart[index] = parseFloat(value);
				}
			});
			$('.chart', $body).each(function(){
				var $self = $(this),
						barColor = $self.data('color'),
						percent = $self.data('percent');

				if( typeof barColor !== 'undefined' ) {
					themifyScript.chart.barColor = '#' + barColor.toString().replace('#', '');

					$self.easyPieChart( themifyScript.chart );
					$self.data('easyPieChart').update(0);
					$self.data('easyPieChart').update(percent);
				}
			});
		}
		$('.no-chart', $body).not('.module-feature').each(function () {
			var $self  = $(this),
				$img   = $self.find('img'),
				width  = themifyScript.chart.size,
				height = themifyScript.chart.size;

			if ( $img.length > 0 ) {
				width  = realDimension($img, 'width');
				height = realDimension($img, 'height');
			}

			$self.css({
				'width' : width + 'px',
				'height': height + 'px'
			}).addClass('no-chart-ready');
		});
		
		/////////////////////////////////////////////
		// Skillset Animation
		/////////////////////////////////////////////
		if($skills.length>0) {
			if(!$.fn.waypoint){
				Themify.LoadAsync(themify_vars.url+'/js/waypoints.min.js',ThemifySkils);
			}
			else{
				ThemifySkils();
			}
		}
		function ThemifySkils(){
			$skills.each(function(){	
				var $self = $(this).find('span'),
                                    percent = $self.data('percent');
				$self.waypoint(function(direction){
                                    $self.animate({width: percent}, 800);
				}, {offset: '80%'});
				
				$self.waypoint(function(direction){
                                    if(direction === 'up') {
                                            $self.animate({width: percent}, 800);
                                    }
				}, {offset: '20%'});
				
				$self.waypoint(function(direction){
                                    if(direction === 'up') {
                                            $self.width(0);
                                    }
				}, {offset: -10});
				
				$self.waypoint(function(direction){
                                    if(direction === 'down') {
                                            $self.width(0);
                                    }
				}, {offset: '110%'});
			});
		}
		$(window).on('resize', function () {
			// Section Type Message Vertical Height
			var $context = $('#loops-wrapper'),
							$verticalSection = $('.section-post.message', $context);
				
			for (var i = 0; i < $verticalSection.length; i++) {
				var $selection = $verticalSection.eq(i);
				$selection.css({'lineHeight': parseInt( $verticalSection.css('height') ) + 'px'});
				if($selection.find('.vertical-centered').length == 0){
					$selection.find('.section-inner').wrapInner('<div class="vertical-centered" />');
				}
			}
		});
	});

	/////////////////////////////////////////////
	// Execute when page is fully loaded
	/////////////////////////////////////////////
	$(window).load(function() {
		// scrolling nav
		$('body').themifyScroll({
			speed : 1000
		});

		// Header Video
		function ThemifyBideo(){
			var $videos = $('[data-fullwidthvideo]').not('.themify_builder_row'),
				fwvideos = [];
			$.each($videos, function (i, elm) {
					fwvideos[i] = new $.BigVideo({
							useFlashForFirefox: false,
							container: $(elm).find('#header'),
							id: i
					});
					fwvideos[i].init();
					fwvideos[i].show($(elm).data('fullwidthvideo'), {
							doLoop : true
					});
			});
		}
		if ($('[data-fullwidthvideo]').not('.themify_builder_row').length>0 && !is_touch_device()) {
			if(typeof $.BigVideo == 'undefined'){
				 Themify.LoadAsync(themify_vars.url+'/js/video.js',function(){
					 Themify.LoadAsync(themify_vars.url+'/js/bigvideo.js',ThemifyBideo);
				 });
			}
			else{
			   ThemifyBideo();
			}
		}
		// Header Background Color
		var $headerColor = $('#headerwrap[data-bgcolor]');
		if ( $headerColor.length > 0 ) {
			var bgColor = $headerColor.data('bgcolor');
			if ( ! $headerColor.hasClass('header-gallery') ) {
				var $stretchColor = $headerColor.find('.backstretch');
				if ( $stretchColor.length > 0 ) {
					$headerColor.css( 'background', 'none' );
					$stretchColor.css( 'background-color', '#' + bgColor );
				} else {
					$headerColor.css( 'background-color', '#' + bgColor );
				}
			}
		}

		// Carousel initialization //////////////////////////
		if($('.portfolio-post .slideshow').length>0){
			if(!$.fn.carouFredSel){
				Themify.LoadAsync(themify_vars.url+'/js/carousel.js',function(){
					createCarousel( $( '.portfolio-post .slideshow' ), 'normal' );
				});
			}
			else{
			   createCarousel( $( '.portfolio-post .slideshow' ), 'normal' );
			}
		}

		var target = window.location.hash.replace(/^#\!\//, "").replace('#','');
		if (target !== '' && $('#' + target).length > 0) {
			var anchor = $('#' + target), destination = anchor.offset().top;
			themeScrollTo( destination, 1000 );
		}

		// Check if infinitescroll is enabled ////////////////
		if ( typeof ($.fn.infinitescroll) !== 'undefined') {

			if ($('.post').length > 0) {
				// item fetched by infinite scroll, infinite scroll
				infinite('#content .post', '#loops-wrapper');
			}

			if ($('.portfolio-post').length > 0) {
				// item fetched by infinite scroll, infinite scroll
				infinite('.portfolio-post', '.portfolio-wrapper');
			}

		}
	
		// EDGE MENU //
		jQuery(function ($) {
			$("#main-nav li").on('mouseenter mouseleave dropdown_open', function (e) {
				if ($('ul', this).length) {
					var elm = $('ul:first', this);
					var off = elm.offset();
					var l = off.left;
					var w = elm.width();
					var docW = $(window).width();
					var isEntirelyVisible = (l + w <= docW);

					if (!isEntirelyVisible) {
						$(this).addClass('edge');
					} else {
						$(this).removeClass('edge');
					}

				}
			});
		});
	
	});

}(jQuery));