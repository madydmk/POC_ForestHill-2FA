jQuery(document).ready(function($) {
    jQuery('.accordion-trigger').bind('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        if(jQuery(window).width() <= 768) {
            jQuery(this).parent().find('.green-block-txt').slideToggle('fast');
            jQuery(this).parent().toggleClass('is-expanded');
        }
    });

    jQuery(window).on('resize', function(event) {
        if(jQuery(window).width() >= 768) {
            jQuery(this).parent().removeClass('is-expanded');
        }
    });
});