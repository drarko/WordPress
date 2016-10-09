jQuery(function($){
	$( 'body' ).on( 'click', '.module-audio .track-title', function(){
		$( this ).closest( '.track' ).find( '.mejs-playpause-button' ).click();
		return false;
	});

	$( 'body' ).on( 'builder_load_module_partial', audioInit );
	$( 'body' ).on( 'builder_toggle_frontend', audioInit );

	function audioInit(){
		$('audio').mediaelementplayer();
	}
});