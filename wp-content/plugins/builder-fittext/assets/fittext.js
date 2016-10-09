/*!
* FitText.js 1.1
* @link https://github.com/petewarman/FitText.js/
*/
!function(a){a.fn.fitText=function(b){var c,d=a.extend({minFontSize:Number.NEGATIVE_INFINITY,maxFontSize:Number.POSITIVE_INFINITY,lineCount:1,scale:100},b);return this.each(function(){var b=a(this);b.css({"white-space":"nowrap",position:"absolute",width:"auto"}),c=parseFloat(b.width())/parseFloat(b.css("font-size")),b.css({position:"",width:"","white-space":""});var e=function(){b.css("font-size",Math.max(Math.min(d.scale/100*d.lineCount*b.width()/c-d.lineCount,parseFloat(d.maxFontSize)),parseFloat(d.minFontSize)))};e(),a(window).on("resize.fittext orientationchange.fittext",e)})}}(jQuery);

(function( $ ){
	function apply_fittext( el ) {
		el.find( 'span' )
			.fitText()
			.fitText(); // applying it twice fixes the issue of text breaking with some fonts.
		el.css( 'visibility', 'visible' );
	}

	function do_fittext() {
		$('.module.module-fittext').each(function(){
			var thiz = $(this);
			if( thiz.data( 'font-family' ) == 'default' ) {
				apply_fittext( thiz );
			} else {
				var _font = thiz.data( 'font-family' );
				WebFont.load({
					google: {
						families: [_font]
					},
					active : function(){
						apply_fittext( thiz );
					},
					inactive : function(){ // fail-safe: in case font fails to load, use the fallback font and apply the effect.
						apply_fittext( thiz );
					}
				});
			}
		});
	}

	$( 'body' ).on( 'builder_load_module_partial', do_fittext );
	$( 'body' ).on( 'builder_toggle_frontend', do_fittext );
	$( window ).bind( 'load', do_fittext );
})( jQuery );