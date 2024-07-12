jQuery(document).ready(function($) {

    $('.panelItem:not(.panelLogin) ').parents('ul').find('> li').removeClass('is-expanded').find('.panel-body').slideUp('fast');

    $('.panel *:not(.panelLogin) .panel-title').click(function(e) {
        e.preventDefault();
        e.stopPropagation();
        if (!$(this).parent('li').hasClass('is-expanded')) {
            $(this).parents('ul').find('> li').removeClass('is-expanded').find('.panel-body').slideUp('fast');
            $(this).parent('li').addClass('is-expanded').find('.panel-body').slideDown('fast');
        } else {
            $(this).parent('li').removeClass('is-expanded').find('.panel-body').slideUp('fast');
        }



        // $(window).on('resize', function(event) {
        //     if ($(window).width() >= forestHill.consts.getValue('bpDesktop')) {
        //         $('.panel:not(.legal-mention-panel)').find('.panel-body').show();
        //     }
        // });

        $(window).resize();

    });
});