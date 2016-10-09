;// Themify Theme Scripts - http://themify.me/

// Initialize Audio Player
function doAudio(context){
	jQuery('.f-embed-audio', context).each(function(index){
		$this = jQuery(this);
		f_id = $this.attr('id');
		
		if('yes' == $this.data('html5incompl')){
			up = $this.parent().parent();
			
			AudioPlayer.embed( f_id, { soundFile: $this.data('src') } );
			
			if(jQuery.browser.mozilla) {
				tempaud=document.getElementsByTagName("audio")[0];
				jQuery(tempaud).remove();
				jQuery("div.audio_wrap div").show()
			} else {
				jQuery("div.audio_wrap div *").remove();
			}
		}
	});
}

jQuery(window).load(function(){
	
	// For audio player
	doAudio(document);
	
	jQuery('#loops-wrapper').isotope({
		itemSelector : '.post',
		transformsEnabled: false,
		isOriginLeft : ! jQuery( 'body' ).hasClass( 'rtl' )
	});
});

jQuery(document).ready(function($){
	
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
	
		
	// Set path to audio player
	AudioPlayer.setup(themifyScript.audioPlayer, {
		width: '90%',
		transparentpagebg: 'yes'
	});
});