jQuery(window).load(function() {
  jQuery('.flexslider').flexslider({
  animation: "slide",
        start: function(slider){
          $('body').removeClass('loading');
        }
  });
});