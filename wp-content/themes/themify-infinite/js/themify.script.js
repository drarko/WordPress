/* Themify Theme Scripts - http://themify.me/ */
var themifyScript,
	ThemifyLayout = {},
	ThemifyInfiniteScroll = {},
	ThemifyTheme = {},
	ThemifyMediaElement,
	qp_max_pages;

jQuery( function ( $ ) {

	'use strict';

	var $body = $( 'body' ),
		$window = $( window );

	/**
	 * Infinite scroll.
	 *
	 * @type {{constructor, doInfinite}}
	 */
	ThemifyInfiniteScroll = {

		// Back to top link
		$backTop: null,

		// Cache #footerwrap
		$footerwrap: null,

		/**
		 * Initialize infinite scroll on specific areas
		 */
		constructor: function () {
			this.doInfinite( $( '#loops-wrapper' ), '#loops-wrapper .post' );

			var $products = $( '.woocommerce.archive' ).find( '#content' ).find( 'ul.products' );
			if ( $body.hasClass( 'post-type-archive-product' ) && $products.length > 0 ) {
				this.doInfinite( $products, '.product' );
			}

			// Bind functions to this so they have the correct scope
			_.bindAll( this, 'onScroll', 'toggleBackTop' );

			// Throttle browser URL change.
			$window.on( 'scroll.themifysi', _.throttle( this.onScroll, 250 ) );
		},

		/**
		 * Perform actions on document scroll.
		 */
		onScroll: function() {
			this.toggleBackTop();
		},

		/**
		 * Show or hide the link to go back to the top.
		 */
		toggleBackTop: function() {
			if ( _.isNull( this.$backTop ) ) {
				return;
			}
			if ( this.$backTop.length > 0 && ( ThemifyTheme.isVisible( this.$footerwrap ) || window.scrollY < 10 ) ) {
				this.$backTop.addClass( 'infinite-back-top-hide' );
			} else {
				this.$backTop.removeClass( 'infinite-back-top-hide' );
			}
		},

		/**
		 * Add infinite scroll to the $container and load elements pointed by selector
		 *
		 * @param { object } $container Area where infinite scroll will be initialized.
		 * @param { string } selector Elements that are fetched and placed.
		 */
		doInfinite: function ( $container, selector ) {

			if ( 'undefined' !== typeof $.fn.infinitescroll ) {

				var self = this;

				// Get max pages for regular category pages and home
				var scrollMaxPages = parseInt( themifyScript.maxPages );

				// Get max pages for Query Category pages
				if ( typeof qp_max_pages !== 'undefined' ) {
					scrollMaxPages = qp_max_pages;
				}

				// infinite scroll
				$container.infinitescroll( {
					navSelector          : '#load-more a:last', // selector for the paged navigation
					nextSelector         : '#load-more a:last', // selector for the NEXT link (to page 2)
					itemSelector         : selector, // selector for all items you'll retrieve
					loadingText          : '',
					donetext             : '',
					loading              : { img: themifyScript.loadingImg },
					maxPage              : scrollMaxPages,
					behavior             : 'auto' !== themifyScript.autoInfinite ? 'twitter' : '',
					pathParse            : function ( path ) {
						return path.match( /^(.*?)\b2\b(?!.*\b2\b)(.*?$)/ ).slice( 1 );
					},
					bufferPx             : 10,
					pixelsFromNavToBottom: 30
				}, function ( newElements ) {
					// call Isotope for new elements
					var $newElems = $( newElements );

					// Mark new items: remove newItems from already loaded items and add it to loaded items
					$( '.newItems' ).removeClass( 'newItems' );
					$newElems.addClass( 'newItems' );

					$newElems.hide().imagesLoaded( function () {

						if($( 'body' ).hasClass('masonry-enabled')){
                                                    $newElems.css( { 'margin-left': 0 } ).fadeIn();
                                                }

						$( '.wp-audio-shortcode, .wp-video-shortcode' ).not( 'div' ).each( function () {
							var $self = $( this );
							if ( $self.closest( '.mejs-audio' ).length > 0 ) {
								ThemifyMediaElement.init( $self );
							}
						} );

						// Apply lightbox/fullscreen gallery to new items
						Themify.InitGallery();

						$( '#infscr-loading' ).detach().appendTo( $container ).fadeOut( 'normal' );
						if ( 1 === scrollMaxPages ) {
							$( '#load-more, #infscr-loading' ).remove();
						}

						// Create back to top link if it doesn't exist
						if ( _.isNull( self.$backTop ) ) {
							self.$footerwrap = $( '#footerwrap' );
							self.$backTop = $( themifyScript.back_top ).on( 'click', function( e ){
								e.preventDefault();
								$( 'body,html' ).animate( {
									scrollTop: 0
								}, 800 );
							} );
							$( 'body' ).append( self.$backTop );
						}
						// Check back to top link visibility
						self.toggleBackTop();

						/**
						 * Fires event after the elements and its images are loaded after actions on appended items are performed.
						 *
						 * @event infiniteloaded.themify
						 * @param {object} $newElems The elements that were loaded.
						 */
						$body.trigger( 'infiniteloaded.themify', [$newElems, $container] );

						$window.trigger( 'resize' );
					} );

					scrollMaxPages = scrollMaxPages - 1;
					if ( 1 < scrollMaxPages && 'auto' !== themifyScript.autoInfinite ) {
						$( '.load-more-button' ).show();
					}
				} );

				// disable auto infinite scroll based on user selection
				if ( 'auto' === themifyScript.autoInfinite ) {
					$( '#load-more, #load-more a' ).hide();
				}
			}
		}
	};

	/**
	 * Packery layout and filter.
	 *
	 * @type {{masonryActive: boolean, filterActive: boolean, scrolling: boolean, constructor: function, infiniteloaded: function, enableFilters: function,
	 * filter: function, reset: function, restore: function, layout: function, reLayout:function }}
	 */
	ThemifyLayout = {
		masonryActive: false,
		filterActive : false,
		scrolling    : false,

		/**
		 * Initialize layout.
		 */
		constructor: function () {
			if ( 'undefined' === typeof themifyScript.resetFilterOnLoad ) {
				themifyScript.resetFilterOnLoad = 'reset';
			}
			if ( 'undefined' === typeof themifyScript.scrollToNewOnLoad ) {
				themifyScript.scrollToNewOnLoad = 'noscroll';
			}

			$body.imagesLoaded( function () {
				ThemifyLayout.layout();
				$( window ).trigger( 'resize' );
			} );

			themifyScript.disableMasonry = $body.hasClass( 'masonry-enabled' ) ? '' : 'disable-masonry';

			if ( 'disable-masonry' !== themifyScript.disableMasonry ) {
				$( '.post-filter + .portfolio.list-post,.loops-wrapper.grid4,.loops-wrapper.grid3,.loops-wrapper.grid2,.loops-wrapper.portfolio.grid4,.loops-wrapper.portfolio.grid3,.loops-wrapper.portfolio.grid2' ).prepend( '<div class="grid-sizer">' ).prepend( '<div class="gutter-sizer">' );

				this.enableFilters();
				this.filter();
				this.filterActive = true;
			}

			this.listenInfinite();
		},

		/**
		 * If this theme has infinite scroll, listen to its event and relayout elements after loading them.
		 */
		listenInfinite: function () {
			var self = this;

			$body.on( 'infiniteloaded.themify', function ( e, $newElems, $container ) {
				if ( 'reset' === themifyScript.resetFilterOnLoad ) {
					// Make filtered elements visible again
					self.reset();
				}

				if ( self.masonryActive && 'object' === typeof $container.data( 'isotope' ) ) {
					$container.isotope( 'appended', $newElems );
				}
				if ( self.filterActive ) {
					// If new elements with new categories were added enable them in filter bar
					self.enableFilters();

					if ( 'scroll' === themifyScript.scrollToNewOnLoad ) {
						self.restore();
					}
				}
			} );
		},

		enableFilters: function () {
			var $filter = $( '.post-filter' );
			if ( $filter.find( 'a' ).length > 0 && 'undefined' !== typeof $.fn.isotope ) {
				$filter.find( 'li' ).each( function () {
					var $li = $( this ),
						$entries = $li.parent().next(),
						cat = $li.attr( 'class' ).replace( /(current-cat)|(cat-item)|(-)|(active)/g, '' ).replace( ' ', '' );
					if ( $entries.find( '.portfolio-post.cat-' + cat ).length <= 0 ) {
						$li.hide();
					} else {
						$li.show();
					}
				} );
			}
		},

		filter: function () {
			var $filter = $( '.post-filter' );
			if ( $filter.find( 'a' ).length > 0 && 'undefined' !== typeof $.fn.isotope ) {
				$filter.addClass( 'filter-visible' ).on( 'click', 'a', function ( e ) {
					e.preventDefault();
					var $li = $( this ).parent(),
						$entries = $li.parent().next();
					if ( $li.hasClass( 'active' ) ) {
						$li.removeClass( 'active' );
						$entries.isotope( {
							masonry: {
								columnWidth: '.grid-sizer',
								gutter     : '.gutter-sizer'
							},
							filter : '.portfolio-post',
							isOriginLeft : ! $( 'body' ).hasClass( 'rtl' )
						} );
					} else {
						$li.siblings( '.active' ).removeClass( 'active' );
						$li.addClass( 'active' );
						$entries.isotope( {
							filter: '.cat-' + $li.attr( 'class' ).replace( /(current-cat)|(cat-item)|(-)|(active)/g, '' ).replace( ' ', '' ),
							isOriginLeft : ! $( 'body' ).hasClass( 'rtl' )
						} );
					}
				} );
			}
		},

		reset: function () {
			$( '.post-filter' ).find( 'li.active' ).find( 'a' ).addClass( 'previous-active' ).trigger( 'click' );
			this.scrolling = true;
		},

		restore: function () {
			var self = this;

			//$('.previous-active').removeClass('previous-active').trigger('click');
			var $first = $( '.newItems' ).first(),
				to = $first.offset().top - $first.outerHeight( true ) / 2,
				speed = 800;

			if ( to >= 800 ) {
				speed = 800 + Math.abs( to / 1000 * 100 );
			}
			$( 'html,body' ).stop().animate( {
				scrollTop: to
			}, speed ).promise().done( function () {
				self.scrolling = false;
			} );
		},

		layout: function () {
			if ( 'disable-masonry' !== themifyScript.disableMasonry ) {
				$( '.post-filter + .portfolio.list-post,.loops-wrapper.portfolio.grid4,.loops-wrapper.portfolio.grid3,.loops-wrapper.portfolio.grid2,.loops-wrapper.portfolio-taxonomy' ).isotope( {
					masonry     : {
						columnWidth: '.grid-sizer',
						gutter     : '.gutter-sizer'
					},
					itemSelector: '.portfolio-post',
					isOriginLeft : ! $( 'body' ).hasClass( 'rtl' )
				} ).addClass( 'masonry-done' );

				$( '.loops-wrapper.grid4,.loops-wrapper.grid3,.loops-wrapper.grid2' ).not( '.portfolio-taxonomy,.portfolio' ).isotope( {
					masonry     : {
						columnWidth: '.grid-sizer',
						gutter     : '.gutter-sizer'
					},
					itemSelector: '.loops-wrapper > article',
					isOriginLeft : ! $( 'body' ).hasClass( 'rtl' )
				} ).addClass( 'masonry-done' ).isotope( 'once', 'layoutComplete', function () {
					$( window ).trigger( 'resize' );
				} );

				$( '.woocommerce.archive' ).find( '#content' ).find( 'ul.products' ).isotope( {
					layoutMode  : 'packery',
					itemSelector: '.product',
					isOriginLeft : ! $( 'body' ).hasClass( 'rtl' )
				} ).addClass( 'masonry-done' );

				this.masonryActive = true;
			}
		},

		reLayout: function () {
			$( '.loops-wrapper' ).each( function () {
				var $loopsWrapper = $( this );
				if ( 'object' === typeof $loopsWrapper.data( 'isotope' ) ) {
					if ( self.masonryActive ) {
						$loopsWrapper.isotope( 'layout' );
					}
				}
			} );
			var $gallery = $( '.gallery-wrapper.packery-gallery' );
			if ( $gallery.length > 0 && 'object' === typeof $gallery.data( 'isotope' ) ) {
				if ( this.masonryActive ) {
					$gallery.isotope( 'layout' );
				}
			}
		}
	};

	/**
	 * Main object that initializes the theme functionality.
	 *
	 * @type {{constructor: function, ready: function, load: function, scrollUp: function, initMenu: function, animateTagline: function, initGallery:
	 * function, menuToSidePanel: function}}
	 */
	ThemifyTheme = {

		/**
		 * Initialize theme behaviour
		 */
		constructor: function () {

			themifyScript.archive_ad.ad_code_cycle = parseInt( themifyScript.archive_ad.ad_code_cycle );

			var didResize = false;
			$window.on( 'resize', function () {
				didResize = true;
			} );
			setInterval( function () {
				if ( didResize ) {
					didResize = false;
					$body.trigger( 'didresize.themify' );
				}
			}, 250 );

			var didScroll = false;
			$window.on( 'scroll', function () {
				didScroll = true;
			} );
			setInterval( function () {
				if ( didScroll ) {
					didScroll = false;
					$body.trigger( 'didscroll.themify' );
				}
			}, 250 );

			$body.on( 'infiniteloaded.themify', function( e, $newElems, $container ) {
				ThemifyTheme.insertAd( $container );
			} );
		},

		ready: function () {
			this.scrollUp();
			this.initMenu();
			this.animateTagline();
			this.insertAd();
			ThemifyLayout.constructor();
			ThemifyInfiniteScroll.constructor();
			this.showHideHeader();
			this.makeClickableBodyOverlay();
			this.stickyShareButtons();
			this.stickySidebar();
		},

		load: function () {
			this.initGallery();
			this.menuToSidePanel();
		},

		/**
		 * Hide header on scroll down, show it again on scroll up.
		 */
		showHideHeader: function () {
			var $headerwrap = $( '#headerwrap' ),
				direction = 'down',
				previousY = 0;
			$body.on( 'didscroll.themify', function () {
				direction = previousY < window.scrollY ? 'down' : 'up';
				previousY = window.scrollY;
				if ( 'up' === direction || 0 == window.scrollY ) {
					if ( $headerwrap.hasClass( 'hidden' ) ) {
						$headerwrap.css( 'top', '' ).removeClass( 'hidden' );
					}
				} else if ( !$headerwrap.hasClass( 'hidden' ) && 0 < window.scrollY ) {
					$headerwrap.css( 'top', -$headerwrap.outerHeight() ).addClass( 'hidden' );
				}
			} );
		},

		makeClickableBodyOverlay: function () {
			var $overlay = $( '<div class="body-overlay">' );
			$body.append( $overlay ).on( 'sidemenushow.themify', function () {
				$overlay.addClass( 'body-overlay-on' );
			} ).on( 'sidemenuhide.themify', function () {
				$overlay.removeClass( 'body-overlay-on' );
			} ).on( 'click.themify touchend.themify', '.body-overlay', function () {
				$( '#menu-icon' ).themifySideMenu( 'hide' );
			} );
		},

		stickyShareButtons: function () {
			themifyScript.stickyShare.screenThreshold = parseInt( themifyScript.stickyShare.screenThreshold );
			themifyScript.stickyShare.leftConstant = parseInt( themifyScript.stickyShare.leftConstant );
			var self = this,
				top = 0,
				bottom = 0,
				$share = {},
				shareHeight = 0,
				headerHeight = $( '#headerwrap' ).outerHeight(),
				stick = function () {
					if ( themifyScript.stickyShare.screenThreshold < window.innerWidth ) {
						$( '.js-sticky-share-content' ).each( function () {
							var $self = $( this );
							if ( _.intersection( $self.closest( '.single-wrapper' ).get(0).classList, [ 'sidebar-left', 'full_width' ] ).length > 0 ) {
								return;
							}
							var	$share = $self.find( '.social-share-wrap' );
							if ( $share.length > 0 ) {
								if ( self.isVisible( $self ) ) {
									if ( 0 === shareHeight ) {
										shareHeight = $share.outerHeight();
									}
									var selfPaddingBottom = parseInt( $self.css( 'paddingBottom' ).replace( 'px', '' ) );
									if ( $share.hasClass( 'social-share-fixed' ) ) {
										top = $self.offset().top - headerHeight + $self.find( '.post-image' ).outerHeight( true );
									} else {
										top = $share.offset().top - headerHeight;
									}
									bottom = $self.offset().top + $self.outerHeight() - selfPaddingBottom - shareHeight;
									if ( top < window.scrollY && window.scrollY < bottom && ! self.isVisible( $self.find( '.post-image' ) ) ) {
										$share.css( { top: '', left: $self.offset().left - themifyScript.stickyShare.leftConstant } );
										$share.removeClass( 'social-share-absolute' );
										if ( ! $share.hasClass( 'social-share-fixed' ) ) {
											$share.addClass( 'social-share-fixed' );
										}
									} else {
										if ( $share.hasClass( 'social-share-fixed' ) ) {
											var shareTop = $share.position().top;
											$share.removeClass( 'social-share-fixed' ).css( 'left', '' );
											if ( window.scrollY > bottom ) {
												$share.addClass( 'social-share-absolute' ).css( 'top', bottom - top + selfPaddingBottom - shareHeight );
											}
										}
									}
								} else {
									$share.removeClass( 'social-share-absolute social-share-fixed' ).css( { top: '', left: ''	} );
								}
							}
						} );
					}
				};

			stick = _.throttle( stick, 50 );

			$window.on( 'scroll', stick );
			$window.on( 'resize', stick );
		},

		stickySidebar: function () {
			themifyScript.stickySidebar.widthPercent = parseInt( themifyScript.stickySidebar.widthPercent ) / 100;
			themifyScript.stickySidebar.screenThreshold = parseInt( themifyScript.stickySidebar.screenThreshold );
			themifyScript.stickySidebar.topPadding = parseInt( themifyScript.stickySidebar.topPadding );
			var isProduct = $body.hasClass( 'single-product' );
			if ( isProduct ) {
				$('#content.list-post' ).addClass( 'js-sticky-sidebar-content' );
			}
			var self = this,
				top = 0,
				bottom = 0,
				sidebarHeight = 0,
				headerHeight = $( '#headerwrap' ).outerHeight(),
				sbstick = function () {
					$( '.js-sticky-sidebar-content' ).each( function () {
						var $self = $( this ),
							$wrapper = isProduct ? $body : $self.closest( '.single-wrapper' ),
							$sidebar = $self.next();
						if ( $sidebar.length > 0 && $wrapper.hasClass( 'sidebar1' ) && $sidebar.innerHeight() < window.innerHeight ) {
							var side, noside;
							if ( $wrapper.hasClass( 'sidebar-left' ) ) {
								side = 'left';
								noside = 'right';
							} else {
								side = 'right';
								noside = 'left';
							}
							if ( self.isVisible( $self ) && window.innerWidth > themifyScript.stickySidebar.screenThreshold ) {
								top = $self.offset().top - headerHeight + themifyScript.stickySidebar.topPadding;
								if ( 0 === sidebarHeight ) {
									sidebarHeight = $sidebar.outerHeight();
								}
								bottom = top + $self.outerHeight() - sidebarHeight;
								if ( top < window.scrollY && window.scrollY < bottom ) {
									$sidebar.css( 'top', '' );
									if ( 'right' === side ) {
										$sidebar.css( 'right', $self.position().left );
									}
									$sidebar.css( 'width', $self.closest( '.pagewidth' ).innerWidth() * themifyScript.stickySidebar.widthPercent );
									if ( !$sidebar.hasClass( 'sticky-sidebar-fixed' ) ) {
										$sidebar.removeClass( 'sticky-sidebar-absolute' ).addClass( 'sticky-sidebar-fixed' );
									}
								} else {
									if ( $sidebar.hasClass( 'sticky-sidebar-fixed' ) ) {
										$sidebar.removeClass( 'sticky-sidebar-fixed' );
										if ( window.scrollY > bottom ) {
											$sidebar.addClass( 'sticky-sidebar-absolute' ).css( 'top', ( bottom - top ) );
										}
									}
								}
							} else {
								$sidebar.removeClass( 'sticky-sidebar-absolute' ).removeClass( 'sticky-sidebar-fixed' ).css( 'top', '' ).css( side, '' ).css( noside, '' );
							}
						}
					} );
				};
			sbstick = _.throttle( sbstick, 50 );

			$window.on( 'scroll', sbstick );
			$window.on( 'resize', sbstick );
		},

		/**
		 * Check if the element is in viewport.
		 *
		 * @param { object } el
		 */
		isVisible: function ( el ) {
                    if(el.length>0){
			var win = $window,
				viewport = {
					top : win.scrollTop(),
					left: win.scrollLeft()
				},
				bounds = el.offset();

			viewport.right = viewport.left + win.width();
			viewport.bottom = viewport.top + win.height();

			bounds.right = bounds.left + el.outerWidth();
			bounds.bottom = bounds.top + el.outerHeight();

			return !(viewport.right < bounds.left || viewport.left > bounds.right || viewport.bottom < bounds.top || viewport.top > bounds.bottom);
                    }
                    else{
                        return true;
                    }
		},

		scrollUp: function () {
			// Scroll to top
			$( '.back-top a' ).click( function () {
				$( 'body,html' ).animate( {
					scrollTop: 0
				}, 800 );
				return false;
			} );
		},

		initMenu: function () {
			// Toggle main nav on mobile
			$( '#menu-icon' ).themifySideMenu( {
				close: '#menu-icon-close'
			} );

			// touch dropdown menu
			if( $( 'body' ).hasClass( 'touch' ) && typeof jQuery.fn.themifyDropdown != 'function' ) {
				Themify.LoadAsync(themify_vars.url + '/js/themify.dropdown.js', function(){
					$( '#main-nav' ).themifyDropdown();
				});
			}
		},

		animateTagline: function () {
			// Tagline Animation
			var $siteLogo = $( '#site-logo' ),
				leftPosition = $siteLogo.width(),
				tagLine = $( '#site-description' );
			$siteLogo.hover( function () {
				tagLine.stop().animate( { left: leftPosition, opacity: '1', zIndex: '1' }, 100 );
			}, function () {
				tagLine.stop().animate( { left: '0', opacity: '0', zIndex: '-1' }, 100 );
			} );
		},

		initGallery: function () {
			 Themify.InitGallery();
		},

		menuToSidePanel: function () {
			// Move menu into side panel on small screens
			var $mainMenu = $( '#main-nav-wrap, #searchform-wrap, .social-widget' ),
				sbarWidth;

			if ( sbarWidth === undefined ) {
				var scrollDiv = document.createElement("div");
				scrollDiv.className = "scrollbar-parent";
				document.body.appendChild(scrollDiv);

				// Get the scrollbar width and assign to sbarWidth
				var sbarWidth = scrollDiv.offsetWidth - scrollDiv.clientWidth;
				
				// Delete the DIV 
				document.body.removeChild(scrollDiv);
			}

			if ( $mainMenu.length > 0 ) {
				themifyScript.smallScreen = parseInt( themifyScript.smallScreen, 10 );
				themifyScript.resizeRefresh = parseInt( themifyScript.resizeRefresh, 10 );
				var isSmallScreen = function isSmallScreen() {
						return $window.width() <= themifyScript.smallScreen;
					},
					$mobileNavWrap = $( '.slideout-widgets' ),
					$desktopNavWrap = $( '#menu-wrapper' );

				if ( isSmallScreen() ) {
					$mainMenu.detach().insertBefore( $mobileNavWrap );
					themifyScript.menuInSidePanel = true;
				} else {
					themifyScript.menuInSidePanel = false;
				}

				$body.on( 'didresize.themify', function () {
					var $jQwidth;
					if ( $( '#mobile-menu' ).hasClass( 'sidemenu-on' ) ) {
						$jQwidth = $window.outerWidth();
					} else {
						$jQwidth = $window.outerWidth() + sbarWidth;
					}
					if ( $jQwidth <= themifyScript.smallScreen ) {
						$mainMenu.detach().insertBefore( $mobileNavWrap );
						themifyScript.menuInSidePanel = true;
					} else {
						$mainMenu.detach().prependTo( $desktopNavWrap );
						themifyScript.menuInSidePanel = false;
					}
				});
			}
		},
		
		/**
		 * Insert ad every certain number of entries in a certain context.
		 * The number of entries to skip is specified in themifyScript.archive_ad.ad_code_cycle.
		 */
		insertAd: function ( $container ) {
			var newItems = false;
			if ( 'undefined' === typeof $container ) {
				$container = $('.loops-wrapper');
			} else {
				newItems = true;
			}
			if ( !_.isEmpty( themifyScript.archive_ad.ad_code ) && $( '#tmpl-themify_archive_ad' ).length > 0 ) {
				if ( newItems ) {
					$container.find( '.archive-divider-ad' ).last().nextAll( '.post' ).each( function ( index ) {
						var $self = $( this );
						index++;
						if ( index % themifyScript.archive_ad.ad_code_cycle === 0 ) {
							var $ad = $( wp.template( 'themify_archive_ad' )( themifyScript.archive_ad ) );
							$self.after( $ad );
							if ( $container.data( 'isotope' ) ) {
								$container.isotope( 'appended', $ad );
							}
						}
					} );
					$container.isotope( 'layout' );
				} else {
					$container.find( '.post' ).each( function ( index ) {
						var $self = $( this );
						index++;
						if ( index % themifyScript.archive_ad.ad_code_cycle === 0 ) {
							var $ad = $( wp.template( 'themify_archive_ad' )( themifyScript.archive_ad ) );
							$self.after( $ad ).fadeIn();
						}
					} );
				}
			}
		}

	};

	ThemifyTheme.constructor();

	$( document ).ready( function () {
		ThemifyTheme.ready();
	} );

	$window.load( function () {
		ThemifyTheme.load();
		
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
		
		// Mega menu width
		var $megamenuwidth = $('#header').width();		 
		var vviewport = $(window).width();
		
		if (vviewport > 1000) {
			$('#main-nav li.has-mega-column > ul, #main-nav li.has-mega-sub-menu > .mega-sub-menu').css(
					'width', $megamenuwidth
				);
		} else {
			$('#main-nav li.has-mega-column > ul').removeAttr("style");
			$('#main-nav li.has-mega-sub-menu > .mega-sub-menu').removeAttr("style"); 
		}
		$(window).resize(function(){
			var $megamenuwidth = $('#header').width();		 
			var vviewport = $(window).width();
			
			if (vviewport > 1000) {
				$('#main-nav li.has-mega-column > ul, #main-nav li.has-mega-sub-menu > .mega-sub-menu').css(
						'width', $megamenuwidth
					);
			} else {
				$('#main-nav li.has-mega-column > ul').removeAttr("style");
				$('#main-nav li.has-mega-sub-menu > .mega-sub-menu').removeAttr("style"); 
			}
		});
		
	} );
} );