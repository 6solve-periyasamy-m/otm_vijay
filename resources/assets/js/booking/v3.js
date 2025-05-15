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

    $('.hotel-image-popup-block').slick({
      dots: true,
      infinite: true,
      speed: 500,
      arrows: true,
      fade: true,
      cssEase: 'linear'
    });

    $('.hotel-more-info a').on('click', function(e) {
      setTimeout(function() {
        $('.hotel-image-popup-block').slick('setPosition');
      }, 100);
    });

    $('.default-hotel-image-popup-block').slick({
      dots: true,
      infinite: true,
      speed: 500,
      arrows: true,
      fade: true,
      cssEase: 'linear'
    });
    $('.default-hotel-more-info-popup a').on('click', function(e) {
      setTimeout(function() {
        $('.default-hotel-image-popup-block').slick('setPosition');
      }, 100);
    });
    jQuery(document).on('click', '.email-quote .sub-heading-6', function() {
        jQuery(this).closest('.email-quote').find('form').slideToggle(400);
    });
})