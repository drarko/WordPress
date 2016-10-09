;// Themify Theme Scripts - http://themify.me/

// Begin jQuery functions
(function($) {

//////////////////////////////
// Test if touch event exists
//////////////////////////////
function is_touch_device() {
	return $('body').hasClass('touch');
}

$(document).ready(function(){

	//Show slider after it's fully loaded
	$('#sliderwrap').css( {'height': 'auto', 'visibility' : 'visible'} );


	/////////////////////////////////////////////
	// Scroll to top
	/////////////////////////////////////////////
	$('.back-top a').click(function () {
            $('body,html').animate({scrollTop: 0}, 800);
            return false;
	});

	/////////////////////////////////////////////
	// Toggle top nav on mobile
	/////////////////////////////////////////////
	$("#top-menu-icon").click(function(){
		$("#top-nav").fadeToggle();
		$("#headerwrap #searchform").hide();
		$("#headerwrap #main-nav").hide();
		$(this).toggleClass("active");
	});

	/////////////////////////////////////////////
	// Toggle main nav on mobile
	/////////////////////////////////////////////
	$("#menu-icon").click(function(){
		$("#main-nav").fadeToggle();
		$("#headerwrap #searchform").hide();
		$("#headerwrap #top-nav").hide();
		$(this).toggleClass("active");
	});

	/////////////////////////////////////////////
	// Toggle searchform on mobile
	/////////////////////////////////////////////
	$("#search-icon").click(function(){
		$("#headerwrap #searchform").fadeToggle();
		$("#headerwrap #main-nav").hide();
		$("#headerwrap #top-nav").hide();
		$(this).toggleClass("active");
	});

	// Show/Hide search form and unfold/fold search options
	$('#headerwrap #searchform').on('touchend mouseenter', function() {
		$('#headerwrap #searchform #s').stop().animate({
			width : 140
		}, {
			queue : false,
			duration : 300
			, easing: 'easeInOutCubic'
		});
		$('#headerwrap .search-option').css('visibility', 'visible').stop().animate({
			opacity : 1,
			height : 21,
			left : 0,
			width : 117
		}, {
			queue : false,
			duration : 500
			, easing: 'easeInOutCubic'
		});
	});

	$('#headerwrap #searchform').on('mouseleave', function() {
		$('#headerwrap #searchform #s').stop().animate({
			width : '55'
		}, {
			queue : false,
			duration : 200
		});
		$('#headerwrap .search-option').stop().animate({
			opacity : 0,
			height : '0',
			left : 117,
			width : 0
		}, {
			queue : false,
			duration : 200
		});
	});

	$('#headerwrap .search-option').each(function(index){
		$this = $(this);
		$('input', $this).change(function(e){
			searchValue = $(this).val();
			$(this).parent().siblings('.search-type').val( searchValue );
		});
	});
});

}(jQuery));

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
