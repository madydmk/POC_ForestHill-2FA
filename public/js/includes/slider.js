jQuery(document).ready(function($) {
    $('.sliderHomeContainer').slick({
        infinite: true,
        autoplay: true,
        slidesToShow: 1,
        arrows: false,
        dots: true,
        slidesToScroll: 1
    });

    $('.sliderHighlightContainer').slick({
        infinite: false,
        autoplay: false,
        slidesToShow: 5,
        arrows: true,
        dots: false,
        slidesToScroll: 1,
        responsive: [
            {
                breakpoint: 1200,
                settings: {
                    slidesToShow: 4,
                    slidesToScroll: 1,
                    arrows: true
                }
            },
            {
                breakpoint: 980,
                settings: {
                    slidesToShow: 3,
                    slidesToScroll: 1,
                    arrows: true
                }
            },
            {
                breakpoint: 600,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1,
                    arrows: true
                }
            }
        ]
    });
});
