;// Themify Theme Scripts - http://themify.me/

// Initialize object literals
var EntryFilter = {};

/////////////////////////////////////////////
// jQuery functions
/////////////////////////////////////////////
(function($){

	// Entry Filter /////////////////////////
	EntryFilter = {

		options: {},

		init: function( settings ) {
			this.options = $.extend( {}, {
				type: '.type-post, .type-page',
				filter: '.post-filter',
				loops: '.loops-wrapper:not(.module)'
			}, settings );
			this.filter();
		},

		filter: function(){
			var self = this,
				$filter = $(self.options.filter);
			if ( $filter.find('a').length > 0 && 'undefined' !== typeof $.fn.isotope ){
				$filter.find('li').each(function(){
					var $li = $(this),
						$entries = $li.parent().next(),
						cat = $li.attr('class').replace( /(current-cat)|(cat-item)|(-)|(active)/g, '' ).replace( ' ', '' );
					if ( $entries.find(self.options.type + '.cat-' + cat).length <= 0 ) {
						$li.remove();
					}
				});

				$filter.show().on('click', 'a', function(e) {
					e.preventDefault();
					var $li = $(this).parent(),
						$entries = $li.parent().next();
					if ( $li.hasClass('active') ) {
						$li.removeClass('active');
						$entries.isotope( {
							filter: self.options.type,
							isOriginLeft : ! $( 'body' ).hasClass( 'rtl' )
						} );
					} else {
						$li.siblings('.active').removeClass('active');
						$li.addClass('active');
						$entries.isotope( {
							filter: '.cat-' + $li.attr('class').replace( /(current-cat)|(cat-item)|(-)|(active)/g, '' ).replace( ' ', '' ),
							isOriginLeft : ! $( 'body' ).hasClass( 'rtl' )
						} );
					}
				} );
			}
		},
		layout: function(){
			var self = this;
			$(self.options.loops).isotope({
				layoutMode: 'fitRows',
				transformsEnabled: false,
				itemSelector: self.options.type,
				isOriginLeft : ! $( 'body' ).hasClass( 'rtl' )
			});
		}
	};

	jQuery(document).ready(function($){

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
		// Entry Filter
		/////////////////////////////////////////////
		EntryFilter.init({
			filter: '.sorting-nav'
		});
	});

	/////////////////////////////////////////////
	// Isotope posts
	/////////////////////////////////////////////
	jQuery(window).load(function(){

		/////////////////////////////////////////////
		// Entry Filter Layout
		/////////////////////////////////////////////
		EntryFilter.layout();

	});

})(jQuery);