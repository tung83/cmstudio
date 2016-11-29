// Contents of functions.js
$(function() { 
    if(typeof $.fn.layerSlider == "undefined") { lsShowNotice("layerslider_1","jquery"); } 
    else { 
        $("#layerslider_1").layerSlider({
            responsiveUnder: 940, 
            layersContainer: 940, 
            pauseOnHover: false, 
            forceLoopNum: false, 
            autoPlayVideos: false, 
            skinsPath: "/file/self/",
            skin: "fullwidthdark"
        })
    } 
    $( "#tabs" ).tabs();
       
    $("#search").on('submit',function(e){
        e.preventDefault();
        var val=$(this).find("#hint").val();
        var val=val.split(' ');
        var val=val.join('-');
        $( location ).attr("href","/tim-kiem/"+val);
    });
}); 
$(function(){
    $("body").append('<a href="#" class="scrollTo-top" style="display: inline;"><i class="fa fa-angle-double-up"></i></a>');
    var viewPortWidth = $(window).width();
    $(window).scroll(function(event) {
        event.preventDefault();
        if ($(this).scrollTop() > 180) {
            $('.scrollTo-top').fadeIn();
        } else {
            $('.scrollTo-top').fadeOut();
        }
    });    
    $('.scrollTo-top').click(function(event) {
        $('html, body').animate({scrollTop : 0 }, 600);
        event.preventDefault();
    }); 
    $(".test-popup-link").magnificPopup({
      type: "image",
      zoom: {
        enabled: true,
        duration: 300
      }
    });
    $('.popup-gallery').magnificPopup({
        delegate: 'a',
        type: 'image',
        tLoading: 'Loading image #%curr%...',
        mainClass: 'mfp-img-mobile',
        gallery: {
            enabled: true,
            navigateByImgClick: true,
            preload: [0,1] // Will preload 0 - before current, and 1 after the current image
        },
        zoom: {
            enabled: true,
            duration: 300
        },
        image: {
            verticalFit:true
        }
	});   
    $('.popup-youtube, .popup-vimeo, .popup-gmaps').magnificPopup({
      disableOn: 700,
      type: 'iframe',
      mainClass: 'mfp-fade',
      removalDelay: 160,
      preloader: false,
      zoom: {
            enabled: true,
            duration: 300
      },
      fixedContentPos: false
    });         
});
;(function($) {
  'use strict';
  var $body = $('html, body'),
  content = $('#main').smoothState({
    // Runs when a link has been activated
    onStart: {
      duration: 250, // Duration of our animation
      render: function (url, $container) {
        // toggleAnimationClass() is a public method
        // for restarting css animations with a class
        content.toggleAnimationClass('is-exiting');
        // Scroll user to the top
        $body.animate({
          scrollTop: 0
        });
      }
    }
  }).data('smoothState');
  //.data('smoothState') makes public methods available
})(jQuery);

function add_cart(id,qty){
    $.ajax({
        method: "POST",
        url: "/page/cart.php",
        data: { act : 'add' , product_id : id , product_qty : qty }
    }).done(function( msg ) {
        $("#cart-count").html(msg);
        alert( "Đã thêm sản phẩm vào giỏ hàng!" );
    });
}

