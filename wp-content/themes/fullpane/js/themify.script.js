;// Themify Theme Scripts - http://themify.me/

// Initialize object literals
var AutoColumnClass = {}, Themify_Carousel_Tools = {}, themifyScript, tbLocalScript;

/////////////////////////////////////////////
// jQuery functions
/////////////////////////////////////////////
(function ($) {

	var $sections = $('.type-section'),
			isFullPageScroll = ('undefined' !== typeof $.fn.fullpage) && (themifyScript.fullPageScroll ? true : false) && ($sections.length > 0),
			usesRows = ($sections.length == 0),
			sectionClass = '.section-post',
			sectionsWrapper = '#loops-wrapper',
			wowInit2;

// Setup variables if it uses Builder rows instead of section post type
	if (usesRows) {
		isFullPageScroll = ('undefined' !== typeof $.fn.fullpage) && (themifyScript.fullPageScroll ? true : false) && ($('.themify_builder').length > 0) && $('body').hasClass('full-section-scrolling');
		sectionClass = '.themify_builder_row';
		sectionsWrapper = '.themify_builder_content:not(.not_editable_builder)';
	}

	Themify_Carousel_Tools = {
		intervals: [],
		highlight: function (item) {
			item.addClass('current');
		},
		unhighlight: function ($context) {
			$('li', $context).removeClass('current');
		},
		timer: function ($timer, intervalID, timeout, step) {
			var progress = 0,
					increment = 0;

			this.resetTimer($timer, intervalID);

			this.intervals[intervalID] = setInterval(function () {
				progress += step;
				increment = (progress * 100) / timeout;
				$timer.css('width', increment + '%');
			}, step);
		},
		resetTimer: function ($timer, intervalID) {
			if (null !== this.intervals[intervalID]) {
				clearInterval(this.intervals[intervalID]);
			}
			$timer.width('width', '0%');
		},
		getCenter: function ($context) {
			var visible = $context.triggerHandler('currentVisible'),
					value = typeof visible !== 'undefined' ? visible.length : 1;

			return Math.floor(value / 2);
		},
		getDirection: function ($context, $element) {
			var visible = $context.triggerHandler('currentVisible'),
					center = Math.floor(visible.length / 2),
					index = $element.index();
			if (index >= center) {
				return 'next';
			}
			return 'prev';
		},
		adjustCarousel: function ($context) {
			if ($context.closest('.twg-wrap').length > 0) {
				var visible = $context.triggerHandler('currentVisible'),
						visibleLength = typeof visible !== 'undefined' ? visible.length : 1,
						liWidth = $('li:first-child', $context).width();

				$context.triggerHandler('configuration', {width: '' + liWidth * visibleLength, responsive: false});
				$context.parent().css('width', (liWidth * visible) + 'px');
			}
		}
	};

// Initialize carousels //////////////////////////////
	function createCarousel(obj) {
		obj.each(function () {
			var $this = $(this),
					autoSpeed = 'off' != $this.data('autoplay') ? parseInt($this.data('autoplay'), 10) : 0,
					sliderArgs = {
						responsive: true,
						circular: ('yes' == $this.data('wrap')),
						infinite: true,
						height: 'auto',
						swipe: true,
						scroll: {
							items: $this.data('scroll') ? parseInt($this.data('scroll'), 10) : 1,
							fx: $this.data('effect'),
							duration: parseInt($this.data('speed')),
							onBefore: function () {
								var $twgWrap = $this.closest('.twg-wrap'),
										$timer = $('.timer-bar', $twgWrap);
								if ($timer.length > 0) {
									Themify_Carousel_Tools.timer($timer, $this.data('id'), autoSpeed, 20);
									Themify_Carousel_Tools.unhighlight($this);
								}
							},
							onAfter: function (items) {
								var newItems = items.items.visible;
								var $twgWrap = $this.closest('.twg-wrap');
								if ($twgWrap.length > 0) {
									var $center = newItems.filter(':eq(' + Themify_Carousel_Tools.getCenter($this) + ')');
									$('.twg-link', $center).trigger(themifyScript.galleryEvent);
									Themify_Carousel_Tools.highlight($center);
								}
							}
						},
						auto: {
							play: ('off' != $this.data('autoplay')),
							timeoutDuration: autoSpeed
						},
						items: {
							visible: {
								min: 1,
								max: $this.data('visible') ? parseInt($this.data('visible'), 10) : 1
							},
							width: $this.data('width') ? parseInt($this.data('width'), 10) : 222
						},
						prev: {
							button: 'yes' == $this.data('slidernav') ? '#' + $this.data('id') + ' .carousel-prev' : null
						},
						next: {
							button: 'yes' == $this.data('slidernav') ? '#' + $this.data('id') + ' .carousel-next' : null
						},
						pagination: {
							container: 'yes' == $this.data('pager') ? '#' + $this.data('id') + ' .carousel-pager' : null,
							anchorBuilder: function () {
								if ($this.closest('.testimonial.slider').length > 0) {
									var thumb = $('.testimonial-post', this).data('thumb'),
											thumbw = $('.testimonial-post', this).data('thumbw'),
											thumbh = $('.testimonial-post', this).data('thumbh');
									return '<span><a href="#"><img src="' + thumb + '" width="' + thumbw + '" height="' + thumbh + '" /></a></span>';
								}
								if (($this.closest('.portfolio-multiple.slider').length > 0) || ($this.closest('.team-multiple.slider').length > 0)) {
									return '<a href="#"></a>';
								}
								return false;
							}
						},
						onCreate: function () {
							var $slideshowWrap = $this.closest('.slideshow-wrap'),
									$teamSliderWrap = $this.closest('.team-multiple.slider'),
									$portfolioSliderWrap = $this.closest('.portfolio-multiple.slider'),
									$testimonialSlider = $this.closest('.testimonial.slider'),
									$twgWrap = $this.closest('.twg-wrap');

							$this.closest('.slider').prevAll('.slideshow-slider-loader').first().remove(); // remove slider loader

							if ($testimonialSlider.closest('.fp-tableCell').length === 0) {
								$slideshowWrap.css({
									'visibility': 'visible',
									'height': 'auto'
								}).addClass('carousel-ready');
							} else {
								$slideshowWrap.css({
									'height': 'auto'
								}).addClass('carousel-ready');
							}

							if ($testimonialSlider.length > 0) {
								if ($testimonialSlider.closest('.fp-tableCell').length !== 0 || $('body').hasClass('themify_builder_active')) {
									$testimonialSlider.css({
										'visibility': 'visible',
										'height': 'auto'
									});
								}
								$('.carousel-pager', $slideshowWrap).addClass('testimonial-pager');
							}

							if ($teamSliderWrap.length > 0) {
								$teamSliderWrap.css({
									'visibility': 'visible',
									'height': 'auto'
								});
								$('.carousel-prev, .carousel-next', $teamSliderWrap).text('');
							}
							if ($portfolioSliderWrap.length > 0) {
								$portfolioSliderWrap.css({
									'visibility': 'visible',
									'height': 'auto'
								});
								$('.carousel-prev, .carousel-next', $portfolioSliderWrap).text('');
							}

							if ('no' == $this.data('slidernav')) {
								$('.carousel-prev', $slideshowWrap).remove();
								$('.carousel-next', $slideshowWrap).remove();
							}

							if ($twgWrap.length > 0) {

								var center = Themify_Carousel_Tools.getCenter($this),
										$center = $('li', $this).filter(':eq(' + center + ')'),
										$inTableCell = $('.twg-slider').parents('.row_inner').parents('.fp-tableCell');

								Themify_Carousel_Tools.highlight($center);

								$this.trigger('slideTo', [-center, {duration: 0}]);

								$('.carousel-pager', $twgWrap).remove();
								$('.carousel-prev', $twgWrap).addClass('gallery-slider-prev').text('');
								$('.carousel-next', $twgWrap).addClass('gallery-slider-next').text('');

								if ($inTableCell.length > 0) {
									$inTableCell.css('display', 'block');
								}
							}

							$(window).on('debouncedresize', function () {
								// Get all the possible height values from the slides
								var heights = $this.children().map(function () {
									return $(this).height();
								});
								// Find the max height and set it
								$this.parent().add($this).height(Math.max.apply(null, heights));
							}).trigger('resize');

							Themify_Carousel_Tools.adjustCarousel($this);
						}
					};

			// Fix unresponsive js script when there are only one slider item
			if ($this.children().length < 2) {
				sliderArgs.onCreate();
				return true; // skip initialize carousel on this element
			}

			$this.carouFredSel(sliderArgs).find('li').on(themifyScript.galleryEvent, function () {
				if ($this.closest('.twg-wrap').length > 0) {
					var $thisli = $(this);
					$('li', $this).removeClass('current');
					$thisli.addClass('current');
					$thisli.trigger('slideTo', [
						$thisli,
						-Themify_Carousel_Tools.getCenter($this),
						false,
						{
							items: 1,
							duration: 300,
							onBefore: function () {
								var $twgWrap = $this.closest('.twg-wrap'),
										$timer = $('.timer-bar', $twgWrap);
								if ($timer.length > 0) {
									Themify_Carousel_Tools.timer($timer, $this.data('id'), autoSpeed, 20);
									Themify_Carousel_Tools.unhighlight($this);
								}
							},
							onAfter: function (items) {
							}
						},
						null,
						Themify_Carousel_Tools.getDirection($this, $thisli)]
							);
				}
			});

			/////////////////////////////////////////////
			// Resize thumbnail strip on window resize
			/////////////////////////////////////////////
			$(window).on('debouncedresize', Themify_Carousel_Tools.adjustCarousel($this));

		});
	}

// Get builder rows anchor class to ID //////////////////////////////
	function getClassToId($section) {
		var classes = $section.prop('class').split(' '),
				expr = new RegExp('^tb_section-', 'i'),
				spanClass = null;
		for (var i = 0; i < classes.length; i++) {
			if (expr.test(classes[i])) {
				spanClass = classes[i];
			}
		}

		if (spanClass == null)
			return '';

		return spanClass.replace('tb_section-', '');
	}

// Create fullpage scrolling //////////////////////////////
	function createFullScrolling() {
		var $body = $('body'),
				sectionSelector = usesRows ? sectionsWrapper + ' > ' + sectionClass : sectionClass,
				autoScrolling = !usesRows && '' != themifyScript.hash.replace('#', '') ? false : true;
		$(sectionsWrapper).fullpage({
			resize: false,
			sectionSelector: sectionSelector,
			paddingBottom: $body.hasClass('menubar-top') ? 0 : $( '#headerwrap' ).outerHeight( true ),
			scrollOverflow: true,
			navigation: true,
			lockAnchors: true,
			autoScrolling: autoScrolling,
			afterRender: function () {
				if (!autoScrolling) { // hack deep linking not working when use section row
					$.fn.fullpage.setAutoScrolling(true);
				}

				var $section = $(sectionClass + '.active', $(sectionsWrapper)),
						section_id = usesRows && $section.is('[class*="tb_section-"]') ? getClassToId($section) : $section.prop('id'),
						$aSectionHref = $('#main-nav').find('a[href$="#' + section_id + '"]');

				setTimeout(function () {
					$('.section_loader').hide();
					if ('undefined' !== typeof Themify && 'undefined' !== typeof wowInit2) {
						wowInit2();
					}
				}, 1000);

				if (usesRows) {
					var extraEmptyRow = $('#fp-nav').find('li').get($('.themify_builder_row').length);
					if ('undefined' !== typeof extraEmptyRow) {
						extraEmptyRow.hide();
					}
				}

				if ($aSectionHref.length > 0) {
					$aSectionHref.closest('li').addClass('current_page_item').siblings().removeClass('current_page_item current-menu-item');
					
					if (history.pushState) {
						history.pushState(null, null, '#' + section_id);
					}
				}

				$body.trigger('themify_onepage_after_render', [$section, section_id]);
			},
			afterLoad: function () {
				var $section = $(sectionClass + '.active', $(sectionsWrapper)),
						section_id = usesRows && $section.is('[class*="tb_section-"]') ? getClassToId($section) : $section.prop('id'),
						$aSectionHref = $('#main-nav').find('a[href$="#' + section_id + '"]');

				if ($aSectionHref.length > 0) {
					$aSectionHref.closest('li').addClass('current_page_item').siblings().removeClass('current_page_item current-menu-item');
					
					if (history.pushState) {
						history.pushState(null, null, '#' + section_id);
					}
				}

				$body.trigger('themify_onepage_afterload', [$section, section_id]);
			},
			onLeave: function (index, nextIndex, direction) {

				// when lightbox is active, prevent scrolling the page
				if ($body.find('> .mfp-wrap').length > 0) {
					return false;
				}

				var $rows = usesRows ? $(sectionsWrapper).children(sectionClass) : $(sectionsWrapper).find(sectionClass);
				if ($rows.length > 0) {

					if (index > 0 && nextIndex > 0) {
						var sectionIndex = index;
						if ('up' === direction) {
							for (sectionIndex = index; sectionIndex >= nextIndex; sectionIndex--) {
								$rows.eq(sectionIndex - 1).css('visibility', 'visible');
							}
						} else {
							for (sectionIndex = index; sectionIndex <= nextIndex; sectionIndex++) {
								$rows.eq(sectionIndex - 1).css('visibility', 'visible');
							}
						}

					}
				}
			}
		});
	}

// Apply auto column position element class /////////////////////////
	AutoColumnClass = {
		init: function () {
			this.setup();
		},
		setup: function () {
			// shortcode columns add class
			$('.col2-1.first, .col3-1.first, .col3-2.first, .col4-1.first, .col4-2.first, .col4-3.first', $('#body')).each(function () {
				var $this = $(this);
				if ($this.hasClass('col2-1')) {
					$this.next('.col2-1').addClass('last');
					$this.next('.col4-1').addClass('third').next('.col4-1').addClass('last');
				} else if ($this.hasClass('col3-1')) {
					$this.next('.col3-1').addClass('second').next('.col3-1').addClass('last');
					$this.next('.col3-2').addClass('last');
				} else if ($this.hasClass('col3-2')) {
					$this.next('.col3-1').addClass('last');
				} else if ($this.hasClass('col4-1')) {
					$this.next('.col4-1').addClass('second').next('.col4-1').addClass('third').next('.col4-1').addClass('last');
					$this.next('.col4-2').addClass('second');
				} else if ($this.hasClass('col4-2')) {
					$this.next('.col4-2').addClass('last');
					$this.next('.col4-1').addClass('third').next('.col4-1').addClass('last');
				} else if ($this.hasClass('col4-3')) {
					$this.next('.col4-1').addClass('last');
				}
			});
			var col_nums = 1;
			$('.col-full').each(function () {
				var $this = $(this);
				if (col_nums % 2 == 0) {
					$this.addClass('animate-last');
				} else {
					$this.addClass('animate-first');
				}
				col_nums += 1;
			});
		}
	};

// Scroll to Element //////////////////////////////
	function themeScrollTo(offset) {
		$('body,html').animate({scrollTop: offset}, 800);
	}

// DOCUMENT READY
	$(document).ready(function () {

		var $window = $(window),
				$body = $('body'),
				$charts = $('.chart', $body),
				$skills = $('.progress-bar', $body);

		//////////////////////
		// Slide menu
		//////////////////////
		$('#menu-icon').themifySideMenu({
			close: '#menu-icon-close'
		});

		var $overlay = $('<div class="body-overlay">');
		$body.append($overlay).on('sidemenushow.themify', function () {
			$overlay.addClass('body-overlay-on');
		}).on('sidemenuhide.themify', function () {
			$overlay.removeClass('body-overlay-on');
		}).on('click.themify touchend.themify', '.body-overlay', function () {
			$('#menu-icon-close').trigger('click');
		});
		$(window).resize(function () {
			if ($('#menu-icon').is(':visible') && $('#mobile-menu').hasClass('sidemenu-on')) {
				$overlay.addClass('body-overlay-on');
			}
			else {
				$overlay.removeClass('body-overlay-on');
			}
		});
		/* recalculation of each waypoint’s trigger point, required for modules that use waypoints */
		$body.on('themify_onepage_afterload themify_onepage_after_render', function (event, $section, section_id) {
			if ($.fn.waypoint) {
				Waypoint.refreshAll();
			}
		});

		$body.on('themify_onepage_afterload', function (e, $panel) {
			// Trigger wow display for elements in this panel
			if (tbLocalScript && tbLocalScript.animationInviewSelectors && typeof Themify.wow !== 'undefined' && Themify.wow) {
				$(tbLocalScript.animationInviewSelectors).each(function (i, selector) {
					$(selector, $panel).each(function () {
						Themify.wow.show(this);
					});
				});
			}
		});

		/////////////////////////////////////////////
		// Chart Initialization
		/////////////////////////////////////////////
		if ($charts.length > 0) {
			if (!$.fn.easyPieChart) {
				Themify.LoadAsync(themify_vars.url + '/js/jquery.easy-pie-chart.js', ThemifyChart);
			}
			else {
				ThemifyChart();
			}
		}
		function ThemifyChart() {
			$.each(themifyScript.chart, function (index, value) {
				if ('false' == value || 'true' == value) {
					themifyScript.chart[index] = 'false' != value;
				} else if (parseInt(value)) {
					themifyScript.chart[index] = parseInt(value);
				} else if (parseFloat(value)) {
					themifyScript.chart[index] = parseFloat(value);
				}
			});

			for (var i = 0; i < $charts.length; i++) {
				var $self = $charts.eq(i),
						barColor = $self.data('color'),
						percent = $self.data('percent');
				if ('undefined' !== typeof barColor) {
					themifyScript.chart.barColor = '#' + barColor.toString().replace('#', '');
				}
				$self.easyPieChart(themifyScript.chart);
				$self.data('easyPieChart').update(0);
			}

			if (isFullPageScroll && $body.hasClass('query-section')) {
				$body.on('themify_onepage_afterload themify_onepage_after_render', function (event, $section, section_id) {
					var chartLength = $section.find('.chart').length;
					for (var i = 0; i < chartLength; i++) {
						var $self = $section.find('.chart').eq(i),
								percent = $self.data('percent');
						$self.data('easyPieChart').update(percent);
					}
				});
			} else {
				for (var i = 0; i < $charts.length; i++) {
					var $self = $charts.eq(i),
							percent = $self.data('percent');
					if (typeof $.fn.waypoint !== 'undefined') {
						$self.waypoint(function (direction) {
							$self.data('easyPieChart').update(percent);
						}, {offset: '80%'});
					} else {
						$self.data('easyPieChart').update(percent);
					}
				}
			}
		}

		/////////////////////////////////////////////
		// Skillset Animation
		/////////////////////////////////////////////
		for (var i = 0; i < $skills.length; i++) {
			$('span', $skills.eq(i)).width('0%');
		}
		if (isFullPageScroll && $body.hasClass('query-section')) {
			$body.on('themify_onepage_afterload themify_onepage_after_render', function (event, $section, section_id) {
				// Skillset
				var $progressBars = $section.find('.progress-bar');
				if ($progressBars.length > 0) {
					$progressBars.find('span').each(function () {
						var $bar = $(this),
								percent = $bar.data('percent');
						if ('undefined' !== typeof percent) {
							$bar.delay(200).animate({
								width: percent
							}, 800);
						}
					});
				}
			});
		} else {
			$skills.each(function(){
				var $this = $(this),
				percent = $('span', $this).data('percent');
				if (typeof $.fn.waypoint !== 'undefined') {
					$this.waypoint(function (direction) {
						$this.find('span').each(function () {
							var $bar = $(this),
								percent = $bar.data('percent');
							if ('undefined' !== typeof percent) {
								$bar.delay(200).animate({
									width: percent
								}, 800);
							}
						});
					}, {offset: '80%'});
				} else {
					$('span', $skills.eq(i)).width(percent);
				}
			});
		}

		/////////////////////////////////////////////
		// Transition Animation ( FlyIn or FadeIn )
		/////////////////////////////////////////////
		AutoColumnClass.init(); // apply auto column class
		if (isFullPageScroll && $body.hasClass('query-section')) {
			var runAnimation = function ($section, section_id) {
				if ('undefined' !== typeof Themify && Themify.wow !== null && typeof Themify.wow.scrollHandler() === 'boolean') {
					Themify.wow.scrollHandler();
				}

				if ($section.length > 0 && 'undefined' !== typeof tbLocalScript && tbLocalScript.isAnimationActive) {
					// show animation
					$section.find('.section-title').addClass('animated fadeInLeftBig')
							.end().find('.section-content').addClass('animated flyInBottom');
				}
			};
			$body.on('themify_onepage_afterload themify_onepage_after_render', function (event, $section, section_id) {
				runAnimation($section, section_id);
			});
		} else {
			if ('undefined' !== typeof $.fn.waypoints && 'undefined' !== typeof tbLocalScript && tbLocalScript.isAnimationActive) {
				$(sectionClass, $(sectionsWrapper)).each(function () {
					var $this = $(this);
					$this.waypoint(function () {
						$this.find('.section-title').addClass('animated fadeInLeftBig')
								.end().find('.section-content').addClass('animated flyInBottom');
					}, {offset: '50%'});
				});
			}
		}


		/////////////////////////////////////////////
		// Scroll to top
		/////////////////////////////////////////////
		$('.back-top a').on('click', function (e) {
			e.preventDefault();
			themeScrollTo(0);
		});

		// anchor scrollTo
		// exclude lightbox links from scrolling
		$('#body').on('click', 'a[href*="#"]:not(.themify_lightbox)', function (e) {
			var url = $(this).prop('href'),
					idx = url.indexOf('#'),
					hash = idx != -1 ? url.substring(idx + 1) : '',
					offset = 0,
					$hash = $('#' + hash),
					$sectionEl = usesRows ? $('.tb_section-' + hash) : $('#' + hash);

			if (hash.length > 1 && ($hash.length > 0 || $sectionEl.length > 0) && hash !== 'header') {
				offset = $hash.length > 0 ? $hash.offset().top : $sectionEl.offset().top;
				// If header is set to fixed, calculate this
				if ($('.fixed-header').length > 0) {
					offset += $('#headerwrap').outerHeight();
				}

				if (isFullPageScroll && $body.hasClass('query-section')) {
					if ($sectionEl.length > 0) {
						$.fn.fullpage.moveTo($sectionEl.index(sectionClass) + 1);
					}
				} else {
					themeScrollTo(offset);
				}
				e.preventDefault();
			}
		});

		/////////////////////////////////////////////
		// Toggle main nav on mobile
		/////////////////////////////////////////////
		$body.on('click', '#menu-icon', function (e) {
			e.preventDefault();
			//$('#main-nav').fadeToggle();
			$('#top-nav', $('#headerwrap')).hide();
			$(this).toggleClass('active');
		});

		/////////////////////////////////////////////
		// Add class "first" to first elements
		/////////////////////////////////////////////
		$('.highlight-post:odd').addClass('odd');

		/////////////////////////////////////////////
		// Fullscreen bg
		/////////////////////////////////////////////
		if ('undefined' !== typeof $.fn.backstretch) {
			var $sectionPost = $(sectionClass);
			$sectionPost.each(function () {
				var bg = $(this).data('bg');
				if ('undefined' !== typeof bg) {
					if ($(this).hasClass('fullcover')) {
						$(this).backstretch(bg);
					} else {
						$(this).css('background-image', 'url(' + bg + ')');
					}
				}
			});
			$window.on('backstretch.show', function (e, instance) {
				instance.$container.css('z-index', '');
			})
			.on('debouncedresize', function () {
				$sectionPost.each(function () {
					if ($(this).hasClass('fullcover')) {
						var instance = $(this).data("backstretch");
						if ('undefined' !== typeof instance)
							instance.resize();
					}
				});
			});
		}

		/////////////////////////////////////////////
		// Single Gallery Post Type
		/////////////////////////////////////////////
		if ($('body.single-gallery').length > 0 && 'undefined' !== typeof $.fn.masonry) {
			$('.gallery-type-gallery').masonry({
				itemSelector: '.item',
				isFitWidth: true,
				isAnimated: !Modernizr.csstransitions
			}).imagesLoaded(function () {
				$(this).masonry('reload');
			});
		}

		/////////////////////////////////////////////
		// One Page Scroll
		/////////////////////////////////////////////
		themifyScript.hash = window.location.hash.replace('#', '').replace('!/', '');
		if (isFullPageScroll && $body.hasClass('query-section')) {
			// Get rid of wow js animation since animation is managed with fullpage js
			var callbackTimer = setInterval(function () {
				var call = false;
				try {
					call = ('undefined' !== typeof Themify);
				} catch (e) {
				}

				if (call) {
					clearInterval(callbackTimer);
					wowInit2 = Themify.wowInit;
					Themify.wowInit = function () {
					};
				}
			}, 100);

			createFullScrolling();
			$body.on('builder_toggle_frontend', function (event, is_edit) {
				if (typeof $.fn.fullpage !== 'undefined') {
					if (is_edit) {
						$.fn.fullpage.destroy('all');
					} else {
						createFullScrolling();
					}
				}
			});
		}

		$('#main-nav').on('click', 'a[href*="#"]', function (e) {
			var section_id = $(this).prop('hash'),
					sectionNoHash = section_id.replace('#', ''),
					sectionEl = usesRows ? '.tb_section-' + sectionNoHash : section_id;

			if ($(sectionEl).length > 0) {
				e.preventDefault();
				if (isFullPageScroll && $('body').hasClass('query-section')) {
					var index_el = $(sectionEl).index() + 1;
					$.fn.fullpage.moveTo(index_el);
				} else {
					var offset = $(sectionEl).offset().top;
					themeScrollTo(offset);
				}
				setTimeout(function () {
					window.location.hash = sectionNoHash;
				}, 800);
			}

			// close mobile menu
			if ($window.width() <= 1200 && $('#main-nav').is(':visible')) {
				$('#menu-icon-close').trigger('click');
			}
		});

		/////////////////////////////////////////////
		// Portfolio Expander
		/////////////////////////////////////////////
		if ('undefined' !== typeof $.fn.themifyPortfolioExpander) {
			$body.themifyPortfolioExpander({
				itemContainer: '.shortcode.portfolio',
				animeasing: 'easeInQuart',
				animspeed: 500
			});
		}

		/////////////////////////////////////////////
		// Footer Toggle
		/////////////////////////////////////////////
		$('#footer-tab').on('click', 'a', function (e) {
			e.preventDefault();
			$('#footerwrap-inner').slideToggle();
			$('#footerwrap').toggleClass('expanded');
		});

		/////////////////////////////////////////////
		// Fullcover Gallery
		/////////////////////////////////////////////
		function fullCoverGallery() {
			var height = parseInt($('#headerwrap').height()),
					areaHeight = $window.height() + 5,
					fullPageOn = isFullPageScroll && $body.hasClass('query-section') ? true : false,
					$contexts = $(sectionClass + '.gallery, .twg-slider');

			$contexts.find('.gallery-image-holder').each(function () {
				var $self = $(this),
						thisAreaHeight = areaHeight,
						$section = $self.closest(sectionClass),
						$sectionTitle = $section.find('.section-title'),
						$adminBar = $('#wpadminbar');
				// If this gallery is placed inside a section
				if ( $section.length > 0 ) {
					$section.css( 'paddingBottom', 0 );
					if( $sectionTitle.length > 0) {
						thisAreaHeight -= $sectionTitle.outerHeight(true);
					}
					if ( $( '#headerwrap' ).length > 0 ) {
						thisAreaHeight -= $( '#headerwrap' ).outerHeight(true);
						if( $( 'body' ).hasClass( 'menubar-top' ) ) {
							$self.css( 'marginTop', $( '#headerwrap' ).outerHeight(true) );
						}
					}
				}
				if ($adminBar.length > 0) {
					thisAreaHeight -= $adminBar.outerHeight();
				}
				$self.css({minHeight: thisAreaHeight + 'px'});
			});
			if (! fullPageOn) {
				$contexts.find('.gallery-slider-wrap').css({bottom: ''});
			}
		}
		fullCoverGallery();
		$window.on('debouncedresize', fullCoverGallery);

		$window.on('debouncedresize', function () {
			var in_customizer = false;

			// check for wp.customize return boolean
			if ( typeof wp !== 'undefined' ) {
				in_customizer =  typeof wp.customize !== 'undefined' ? true : false;
			}
			if ( ! in_customizer ) {
				$('#menu-icon-close').trigger('click');
			}
		});

		$body.on('touchstart touchmove touchend mousewheel DOMMouseScroll', '#main-nav, #portfolio-full', function (e) {
			e.stopPropagation();
		});


	});

// WINDOW LOAD
	$(window).load(function () {
		var $body = $('body');

		// scrolling nav
		if ('undefined' !== typeof $.fn.themifySectionHighlight) {
			if (isFullPageScroll && $body.hasClass('query-section')) {
				$body.on('themify_onepage_after_render', function () {
					$body.themifySectionHighlight();
				});
			} else {
				$body.themifySectionHighlight();
			}
			$(window).trigger('scroll');
		}

		if ('undefined' !== typeof $.fn.themifyScrollHighlight) {
			$body.on('scrollhighlight.themify', function (e, section) {
				if ('undefined' != typeof section && '' != section) {
					$('#fp-nav').find('li').eq($('.tb_section-' + section.replace('#', '')).index()).find('a').trigger('click');
				}
			});
			$(window).trigger('scroll');
		}

		/////////////////////////////////////////////
		// Carousel initialization
		/////////////////////////////////////////////
		if ($('.slideshow').length > 0) {
			var carouselSlideshow = function () {
				if (!$.fn.carouFredSel) {
					Themify.LoadAsync(themify_vars.url + '/js/carousel.js', function () {
						createCarousel($('.slideshow'));
					});
				}
				else {
					createCarousel($('.slideshow'));
				}
			};
			carouselSlideshow();
			$('body').on('builder_load_module_partial builder_toggle_frontend', carouselSlideshow);
		}
		/////////////////////////////////////////////
		// Initialize WordPress Gallery in Section
		/////////////////////////////////////////////
		if ('undefined' !== typeof $.fn.ThemifyWideGallery) {
			$('.twg-wrap').ThemifyWideGallery({
				speed: parseInt(themifyScript.galleryFadeSpeed, 10),
				event: themifyScript.galleryEvent,
				ajax_url: themifyScript.ajax_url,
				networkError: themifyScript.networkError,
				termSeparator: themifyScript.termSeparator
			});
		}

		// hide section loader
		if (!isFullPageScroll && $('.section_loader').length > 0) {
			$('.section_loader').hide();
		}

		// Hack Chrome browser doesn't autoplay the video background
		$body.on('themify_onepage_after_render', function () {
			if ('undefined' !== typeof ThemifyBuilderModuleJs && ThemifyBuilderModuleJs.fwvideos.length > 0) {
				$.each(ThemifyBuilderModuleJs.fwvideos, function (i, video) {
					video.getPlayer().play();
				});
			}
			$.each(tbLocalScript.animationInviewSelectors, function (index, selector) {
				$(selector).css('visibility', 'hidden');
			});

			// Section deep linking
			if (window.location.hash) {
				setTimeout(function () {
					var hashSection = themifyScript.hash;
					if ('' != hashSection && '#' != hashSection) {
						var $sectionEl = usesRows ? $('.tb_section-' + hashSection) : $('#' + hashSection);
						if ($sectionEl.length > 0) {
							$.fn.fullpage.moveTo($sectionEl.index() + 1);
							$(tbLocalScript.animationInviewSelectors).each(function (i, selector) {
								$(selector, $sectionEl).addBack().each(function () {
									Themify.wow.show(this);
								});
							});
						}
					}
				}, 1500);
			}
		});

		// Make row backgrounds visible.
		$('.themify_builder_row').css('visibility', 'visible');

	});

})(jQuery);