jQuery(document).ready(function(){
    jQuery(document).on('click','footer .container .view-details',function(){
        jQuery('.package-container .column.right').css('display','block')
    });
    jQuery(document).on('click','.package-details .hide-package-detail',function(){
        jQuery('.package-container .column.right').hide();
    })
    jQuery('.hotel-image-block').slick({
        dots: true,
        infinite: true,
        speed: 500,
        arrows: true,
        fade: true,
        cssEase: 'linear'
    });

    jQuery('.hotel-image-popup-block').slick({
      dots: false,
      infinite: true,
      speed: 500,
      arrows: true,
      fade: true,
      cssEase: 'linear'
    });

    jQuery('.hotel-more-info a').on('click', function(e) {
      setTimeout(function() {
        jQuery('.hotel-image-popup-block').slick('setPosition');
      }, 100);
    });

    jQuery('.default-hotel-image-popup-block').slick({
      dots: false,
      infinite: true,
      speed: 500,
      arrows: true,
      fade: true,
      cssEase: 'linear'
    });
    jQuery('.default-hotel-more-info a').on('click', function(e) {
      setTimeout(function() {
        jQuery('.default-hotel-image-popup-block').slick('setPosition');
      }, 100);
    });

    // Initialize zoom slider
    $('.hotel-zoom-slider').slick({
      arrows: true,
      slidesToShow: 1,
      infinite: false
    });

      // Close zoom view
    $('.hotel-close-zoom').on('click', function() {
      $('.hotel-zoom-overlay').fadeOut();
    });

    // On image click, open zoom view
    $('.hotel-image-popup-block .hotel-zoomable-image').on('click', function() {
      const index = $(this).closest('.slick-slide').attr('data-slick-index');
      $('.hotel-zoom-overlay').fadeIn().css({display:'flex'});
      $('.hotel-zoom-slider').slick('slickGoTo', index);
    });


    // Initialize default zoom slider
    $('.default-zoom-slider').slick({
      arrows: true,
      slidesToShow: 1,
      infinite: false
    });
    $('.default-close-zoom').on('click', function() {
      $('.default-zoom-overlay').fadeOut();
    });
    // On image click, open zoom view
    $('.default-hotel-image-popup-block .default-zoomable-image').on('click', function() {
      console.log("YESSS")
      const index = $(this).closest('.slick-slide').attr('data-slick-index');
      $('.default-zoom-overlay').fadeIn().css({display:'flex'});
      $('.default-zoom-slider').slick('slickGoTo', index);
    });

    jQuery(document).on('click', '.email-quote .sub-heading-6', function() {
        jQuery(this).closest('.email-quote').find('form').slideToggle(400);
    });
})