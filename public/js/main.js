(function ($) {
    "use strict";

    // Spinner: hide once the page (images included) has actually finished
    // loading, but never for less than MIN_VISIBLE_MS — on a fast
    // connection that keeps the custom loader from just flashing past
    // unseen, and on a slow one it hides the instant assets are ready
    // instead of padding the wait further.
    var MIN_VISIBLE_MS = 600;
    var spinnerShownAt = Date.now();
    var hideSpinner = function () {
        var elapsed = Date.now() - spinnerShownAt;
        var remaining = Math.max(0, MIN_VISIBLE_MS - elapsed);
        setTimeout(function () {
            $('#spinner').removeClass('show');
        }, remaining);
    };
    $(window).on('load', hideSpinner);
    // Fallback in case the load event is slow to fire (e.g. a lingering
    // third-party request) — never leave the loader up indefinitely.
    setTimeout(hideSpinner, 4000);
    
    
    // Initiate the wowjs
    new WOW().init();

    
    // Back to top button
    $(window).scroll(function () {
        if ($(this).scrollTop() > 300) {
            $('.back-to-top').fadeIn('slow');
        } else {
            $('.back-to-top').fadeOut('slow');
        }
    });


    // Sticky navbar: condensed/shadowed once the page has scrolled past it
    $(window).scroll(function () {
        $('.navbar').toggleClass('navbar-scrolled', $(this).scrollTop() > 40);
    });
    $('.back-to-top').click(function () {
        $('html, body').animate({scrollTop: 0}, 1500, 'easeInOutExpo');
        return false;
    });


    // Header carousel
    $(".header-carousel").owlCarousel({
        autoplay: true,
        smartSpeed: 1500,
        loop: true,
        nav: false,
        dots: true,
        items: 1,
    });
    
})(jQuery);

