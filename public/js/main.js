(function ($) {
    "use strict";

    // Back to top button: shown once the page has been scrolled.
    var $backToTop = $('.back-to-top');
    $(window).on('scroll', function () {
        $backToTop.toggleClass('is-visible', $(this).scrollTop() > 300);
    });
    $backToTop.on('click', function (event) {
        event.preventDefault();
        window.scrollTo(0, 0);
    });

    // Home carousel: changes slide only when the visitor asks for it.
    $('.header-carousel').owlCarousel({
        autoplay: false,
        loop: false,
        rewind: true,
        smartSpeed: 400,
        nav: false,
        dots: true,
        items: 1,
    });

})(jQuery);
