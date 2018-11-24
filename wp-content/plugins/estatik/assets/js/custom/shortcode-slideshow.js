(function($) {
    'use strict';

    $(function() {
        if (Estatik.shortcodes && Estatik.shortcodes.slideshow) {
            for (var i in Estatik.shortcodes.slideshow) {
                var $el = $('#es-slideshow__' + i + ' ul');
                var numSlides = $el.find('li').length;
                var item = Estatik.shortcodes.slideshow[i];

                var slidesToShow = parseInt(item.slides_to_show) || 1;

                slidesToShow = slidesToShow >= numSlides && item.slider_effect == 'vertical' ?
                    numSlides : slidesToShow;

                if ($el.length) {

                    var responsive = [];

                    if ( slidesToShow > 3 ) {
                        responsive.push( {
                            breakpoint: 1024,
                            settings: {
                                slidesToShow: 3,
                                slidesToScroll: 3,
                                infinite: true,
                                dots: true
                            }
                        } );
                    }

                    if ( slidesToShow > 2 ) {
                        responsive.push( {
                            breakpoint: 600,
                            settings: {
                                slidesToShow: 2,
                                slidesToScroll: 2
                            }
                        } );
                    }

                    responsive.push( {
                        breakpoint: 200,
                        settings: {
                            slidesToShow: 1,
                            slidesToScroll: 1
                        }
                    } );

                    var settings = {
                        margin: 20,
                        slide: 'li',
                        slidesToShow: slidesToShow,
                        arrows: 1 == item.show_arrows || false,
                        prevArrow: '<span class="es-slick-arrow es-slick-prev"></span>',
                        nextArrow: '<span class="es-slick-arrow es-slick-next"></span>',
                        responsive: responsive
                    };

                    if (!settings.arrows) {
                        settings.autoplay = true;
                    }

                    if (item.slider_effect === 'vertical') {
                        settings.vertical = true;
                        settings.autoplaySpeed = 5000;
                    }

                    $el.slick(settings);
                }
            }
        }

        $('.es-slide__bottom').each(function() {
            var $this = $(this);

            if ($this.find('.es-bottom-icon').length === 2) {
                $this.find('.es-bottom-icon:last-child').css({'text-align': 'right'});
            }
        });
    });
})(jQuery);
