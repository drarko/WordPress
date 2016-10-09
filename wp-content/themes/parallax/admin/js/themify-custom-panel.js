/* Themify Custom Panel script to pick "sidebar-none" layout when user selects Full Section Scrolling */
(function($){

	'use strict';
	
	$(document).ready(function(){
		$( '#hide_header' ).on( 'change', function(){
			if( $( this ).is( ':checked' ) ) {
				$( '#menu_bar_position-bottom' ).closest( '.themify_field_row' ).addClass( 'disabled' );
			} else {
				$( '#menu_bar_position-bottom' ).closest( '.themify_field_row' ).removeClass( 'disabled' );
			}
		} ).trigger( 'change' );
	});

})(jQuery);