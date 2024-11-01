jQuery(window).load(function() {
  jQuery('.flexslider').flexslider({
  animation: "slide",
  pauseOnHover: true,
  slideshowSpeed: 5000,
  smoothHeight   :false,
        start: function(slider) {
            jQuery('body').removeClass('loading');
            var start_count =  slider.currentSlide+1 ;
            var number_of_slides =  slider.count ;
            jQuery('.flex-control-nav').text(start_count+' / '+number_of_slides);
        },
        after: function(slider) {
            var start_count =  slider.currentSlide+1 ;
            var number_of_slides =  slider.count ;
            jQuery('.flex-control-nav').text(start_count+' / '+number_of_slides);
        }
  });
});
    
function init_gallery_popup(data)
{
    jQuery('#hdn_img_container'+data+' a:first').click() ;
}
    

Lightbox.prototype.build = function() {
var self = this;
jQuery('<div id="lightboxOverlay" class="lightboxOverlay"></div><div id="lightbox" class="lightbox"><div class="lb-dataContainer"><div class="lb-data"><div class="lb-details"><span class="lb-caption"></span><span class="lb-number"></span></div><div class="lb-closeContainer"><a class="lb-close"></a></div></div></div><div class="lb-outerContainer"><div class="lb-container"><img class="lb-image" src="data:image/gif;base64,R0lGODlhAQABAIAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw==" /><div class="lb-nav"><a class="lb-prev" href="" ></a><a class="lb-next" href="" ></a></div><div class="lb-loader"><a class="lb-cancel"></a></div></div></div></div>').appendTo(jQuery('body'));
}