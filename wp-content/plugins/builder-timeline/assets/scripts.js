jQuery(function($){

	function create_story( module ) {
		if( module.find( '.storyjs-embed' ).length == 0 ) {
			var id = module.attr( 'id' ).trim();
			var source = builder_timeline.data[id];
			var embed = module.find( '.timeline-embed' );
			var config = embed.data( 'config' );
			config.source = source;
			createStoryJS( config );
		}
	}

	function create_stories() {
		$( '.module.module-timeline.layout-graph' ).each(function(){
			create_story( $( this ) );
		});
	}

	$( window ).on( 'load', create_stories );
	$( 'body' ).on( 'builder_load_module_partial', create_stories );
	$( 'body' ).on( 'builder_toggle_frontend', create_stories );
});