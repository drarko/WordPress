/* jSticky Plugin
 * =============
 * Author: Andrew Henderson (@AndrewHenderson)
 * Date: 9/7/2012
 * Update: 02/14/2013
 * Website: http://github.com/andrewhenderson/jsticky/
 * Description: A jQuery plugin that keeps select DOM
 * element(s) in view while scrolling the page.
 */
!function($){$.fn.sticky=function(t){function e(){return"number"==typeof r.zIndex?!0:!1}function o(){return 0<$(r.stopper).length||"number"==typeof r.stopper?!0:!1}var i={topSpacing:0,zIndex:"",stopper:".sticky-stopper",responsivePointer:780},n,r=$.extend({},i,t),s=e(),p=o();return this.each(function(){function t(){var t=h.scrollTop();if(p&&"string"==typeof a)var e=$(a).offset().top-180,n=e-i-l;else if(p&&"number"==typeof a)var n=a;if(t>f){if(o.after(u).css({position:"fixed",top:l}),s&&o.css({zIndex:d}),p&&t>n){var r=n-t+l;o.css({top:r})}}else o.css({position:"static",top:null,left:null}),u.remove()}function e(){i=o.outerHeight(),c=o.outerWidth(),f=o.offset().top-l,u=$("<div></div>").width(c).height(i).addClass("sticky-placeholder"),h.width()>r.responsivePointer?h.bind("scroll",t):(h.unbind("scroll",t),o.removeAttr("style").nextAll(".sticky-placeholder").remove())}var o=$(this),i=o.outerHeight(),c=o.outerWidth(),l=r.topSpacing,d=r.zIndex,f=o.offset().top-l,u=$("<div></div>").width(c).height(i).addClass("sticky-placeholder"),a=r.stopper,h=$(window);h.bind("scroll",t),e(),h.resize(function(){clearTimeout(n),n=setTimeout(e,500)})})}}(jQuery);