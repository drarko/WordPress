;// Themify Theme Scripts - http://themify.me/

jQuery(document).ready(function($){
		
	/////////////////////////////////////////////
	// Set grid post clear							
	/////////////////////////////////////////////
	$(".grid4 .loops-wrapper .post:nth-of-type(4n+1), .grid4 .loops-wrapper .category-section .post:nth-of-type(4n+1)").css({"margin-left":"0"}).before("<div style='clear:both;'></div>");
	$(".grid3 .loops-wrapper .post:nth-of-type(3n+1), .grid3 .loops-wrapper .category-section .post:nth-of-type(3n+1)").css({"margin-left":"0"}).before("<div style='clear:both;'></div>");
	$(".grid2 .loops-wrapper .post:nth-of-type(2n+1), .grid2 .loops-wrapper .category-section .post:nth-of-type(2n+1)").css({"margin-left":"0"}).before("<div style='clear:both;'></div>");
	$(".grid2 .loops-wrapper-thumb .post:nth-of-type(2n+1), .grid2 .loops-wrapper-thumb .category-section .post:nth-of-type(2n+1)").css({"margin-left":"0"}).before("<div style='clear:both;'></div>");


	/////////////////////////////////////////////
	// Scroll to top 							
	/////////////////////////////////////////////
	$('.back-top a').click(function () {
            $('body,html').animate({scrollTop: 0}, 800);
            return false;
	});

	
	/////////////////////////////////////////////
	// Add image-wrap to images for styling 							
	/////////////////////////////////////////////
	$('.post-image img, #slider img, .gallery img, .pagewidth .avatar, .flickr_badge_image img, .attachment img, .feature-posts-list .post-img, img.alignleft, img.aligncenter, img.alignright, img.alignnone, .wp-caption img, .slide-image img').each(function() {
            var imgClass = $(this).attr('class');
            $(this).wrap('<span class="image-wrap ' + imgClass + '" style="width: auto; height: auto;"/>');
            $(this).removeAttr('class');
	});

	/////////////////////////////////////////////
	// Toggle menu on mobile 							
	/////////////////////////////////////////////
	$("#menu-icon").click(function(){
            $("#headerwrap #main-nav").fadeToggle();
            $("#headerwrap #searchform").hide();
            $(this).toggleClass("active");
	});

	if( $( 'body' ).hasClass( 'touch' ) && typeof jQuery.fn.themifyDropdown != 'function' ) {
		Themify.LoadAsync(themify_vars.url + '/js/themify.dropdown.js', function(){
			$( '#main-nav' ).themifyDropdown();
		});
	}

	/////////////////////////////////////////////
	// Toggle searchform on mobile 							
	/////////////////////////////////////////////
	$("#search-icon").click(function(){
            $("#headerwrap #searchform").fadeToggle();
            $("#headerwrap #main-nav").hide();
            $('#headerwrap #s').focus();
            $(this).toggleClass("active");
	});
});