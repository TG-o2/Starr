(function ($) {
    "use strict";

    // Initiate the wowjs
    if (typeof WOW !== 'undefined') {
        try {
            new WOW().init();
        } catch (e) {
            console.log('WOW.js initialization error:', e);
        }
    }


    // Spinner
    var spinner = function () {
        setTimeout(function () {
            if ($('#spinner').length > 0) {
                $('#spinner').removeClass('show');
            }
        }, 1);
    };
    spinner();


    // Sticky Navbar
    $(window).scroll(function () {
        if ($(this).scrollTop() > 300) {
            $('.sticky-top').addClass('shadow-sm').css('top', '0px');
        } else {
            $('.sticky-top').removeClass('shadow-sm').css('top', '-100px');
        }
    });
    
    
    // Back to top button
    $(window).scroll(function () {
        if ($(this).scrollTop() > 300) {
            $('.back-to-top').fadeIn('slow');
        } else {
            $('.back-to-top').fadeOut('slow');
        }
    });
    $('.back-to-top').click(function () {
        var easingMethod = ($.easing && $.easing.easeInOutExpo) ? 'easeInOutExpo' : 'swing';
        $('html, body').animate({scrollTop: 0}, 1500, easingMethod);
        return false;
    });


    // Header carousel
    if ($.fn.owlCarousel) {
        $(".header-carousel").owlCarousel({
            autoplay: true,
            smartSpeed: 1500,
            items: 1,
            dots: true,
            loop: true,
            nav : true,
            navText : [
                '<i class="bi bi-chevron-left"></i>',
                '<i class="bi bi-chevron-right"></i>'
            ]
        });
    }


    // Testimonials carousel
    if ($.fn.owlCarousel) {
        $(".testimonial-carousel").owlCarousel({
            autoplay: true,
            smartSpeed: 1000,
            margin: 24,
            dots: false,
            loop: true,
            nav : true,
            navText : [
                '<i class="bi bi-arrow-left"></i>',
                '<i class="bi bi-arrow-right"></i>'
            ],
            responsive: {
                0:{
                    items:1
                },
                992:{
                    items:2
                }
            }
        });
    }
    
})(jQuery);

