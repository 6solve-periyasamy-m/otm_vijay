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
        dots: true,
        infinite: true,
        speed: 500,
        arrows: true,
        fade: true,
        cssEase: 'linear'
    });

    jQuery(document).on('click', '.email-quote .sub-heading-6', function() {
        jQuery(this).closest('.email-quote').find('form').slideToggle(400);
    });
})