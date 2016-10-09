;// Themify Theme Scripts - http://themify.me/

jQuery(document).ready(function($){
        
    // expand slider
    $('#header-slider .slides, #footer-slider .slides').css('height','auto');
    /////////////////////////////////////////////
    // Scroll to top 							
    /////////////////////////////////////////////
    $('.back-top a').click(function () {
        $('body,html').animate({scrollTop: 0}, 800);
        return false;
    });
});
