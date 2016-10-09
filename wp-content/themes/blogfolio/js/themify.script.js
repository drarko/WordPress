;// Themify Theme Scripts - http://themify.me/

jQuery(document).ready(function($){   

	/////////////////////////////////////////////
	// Large image slideshow					
	/////////////////////////////////////////////
	$("#slideshow span").each(function(index, element){$(element).attr("class", 'hide');});
    	$("#slideshow span").each(function(index, element){$(element).attr("id", 'img'+index);});
    	$("#showcasenav li a").each(function(index, element){$(element).attr("rel", 'img'+index);});
	
	 var mainImg ='img0';
	 var current = 'img0';
	
	 $('#img0').css('display', 'inline');
	 $('#img0').addClass('current');
	
	 $('#showcasenav li a').click(function(){
            mainImg = $(this).attr('rel');
            if(mainImg != current){
                    $('.current').fadeOut();
                    $('#'+mainImg).fadeIn('slow', function(){
                            $(this).addClass('current');
                            current = mainImg;
                    });
            }
            return false;
	 });


	/////////////////////////////////////////////
	// Scroll to top 							
	/////////////////////////////////////////////
	$('.back-top a').click(function () {
            $('body,html').animate({scrollTop: 0}, 800);
            return false;
	});

	if( $( 'body' ).hasClass( 'touch' ) && typeof jQuery.fn.themifyDropdown != 'function' ) {
		Themify.LoadAsync(themify_vars.url + '/js/themify.dropdown.js', function(){
			$( '#main-nav' ).themifyDropdown();
		});
	}
});