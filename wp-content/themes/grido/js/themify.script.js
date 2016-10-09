;// Themify Theme Scripts - http://themify.me/

(function($){

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
				if( (! $('body.list-post').length > 0) && doIso ){
					$container.isotope('appended', $newElems );
				}
				$('#infscr-loading').fadeOut('normal');
				if( 1 == scrollMaxPages ){
					$('#load-more, #infscr-loading').remove();
				}

				// Apply lightbox/fullscreen gallery to new items
				Themify.InitGallery();

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

	$(document).ready(function(){

		// Initialize masonry layout //////////////////////
		if(typeof ($.fn.isotope) !== 'undefined') {
			$('#loops-wrapper.grid4,#loops-wrapper.grid3,#loops-wrapper.grid2,#loops-wrapper.grid2-thumb').prepend('<div class="grid-sizer">').prepend('<div class="gutter-sizer">');
		}

		/////////////////////////////////////////////
		// Scroll to top
		/////////////////////////////////////////////
		$('.back-top a').click(function () {
			$('body,html').animate({
				scrollTop: 0
			}, 800);
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
			$('#headerwrap #s');
			$(this).toggleClass("active");
		});

		// Set path to audio player
		AudioPlayer.setup(themifyScript.audioPlayer, {
			width: '90%',
			transparentpagebg: 'yes'
		});

		// Lightbox / Fullscreen initialization ///////////
		if(typeof ThemifyGallery !== 'undefined'){ ThemifyGallery.init({'context': $(themifyScript.lightboxContext)}); }
		});

	// Initialize Audio Player
	function doAudio(context){
		$('.f-embed-audio', context).each(function(index){
			$this = $(this);
			f_id = $this.attr('id');

			if('yes' == $this.data('html5incompl')){
				up = $this.parent().parent();

				AudioPlayer.embed( f_id, { soundFile: $this.data('src') } );

				if($.browser.mozilla) {
					$('audio').remove();
					$("div.audio_wrap div").show()
				} else {
					$("div.audio_wrap div *").remove();
				}
			}
		});
	}

	$(window).load(function() {

		// For audio player
		doAudio(document);

		// Check if isotope is enabled ////////////////
		if(typeof ($.fn.isotope) !== 'undefined'){
			if($('.post').length > 0){
				// isotope container, isotope item, item fetched by infinite scroll, infinite scroll
				infiniteIsotope('#loops-wrapper', '.post', '#content .post', '#loops-wrapper', true);
			}
		}
	});

}(jQuery));
