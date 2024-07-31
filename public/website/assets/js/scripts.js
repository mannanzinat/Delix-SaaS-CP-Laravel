(function ($) {
    "use strict";
     /*********************************
     * Table of Context
     * *******************************/

    /*********************************
    /* Preloader Start
    *********************************/
     $(window).on('load', function () {
		$('#status').fadeOut()
		$('#preloader').delay(500).fadeOut('slow')
		$('body').delay(500).css({ overflow: 'visible' })
	})

     /*********************************
    /* Sticky Navbar
    *********************************/
    $(window).scroll(function () {
        var scrolling = $(this).scrollTop();
        var stikey = $(".header");

        if (scrolling >= 50) {
            $(stikey).addClass("nav-bg");
        } else {
            $(stikey).removeClass("nav-bg");
        }
    });

    /*********************************
    /*  Mobile Menu Flyout Menu
    *********************************/
    $(".header__toggle").on("click", function (event) {
        event.preventDefault();
        $(".flyoutMenu").toggleClass("active");
    });
    $(".closest__btn").on("click", function (event) {
        event.preventDefault();
        $(".flyoutMenu").toggleClass("active");
    });

    $(document).on("click", function (e) {
        if ($(e.target).closest(".flyout__flip").length === 0 && $(e.target).closest(".header__toggle").length === 0) {
            $(".flyoutMenu").removeClass("active");
        }
    });

    /*********************************
    /*  Mobile Menu Expand
    *********************************/
    $(".flyout-main__menu .has__dropdown .nav__link").click(function() {
        $(".sub__menu").slideUp(400);
        if (
          $(this)
            .parent()
            .hasClass("active")
        ) {
          $(".has__dropdown").removeClass("active");
          $(this)
            .parent()
            .removeClass("active");
        } else {
          $(".has__dropdown").removeClass("active");
          $(this)
            .next(".sub__menu")
            .slideDown(400);
          $(this)
            .parent()
            .addClass("active");
        }
      });


    /*********************************
    /*  Typed Js Here
    *********************************/

    if ($(".animation").length > 0) {
        $(".animation").typer({
            strings: [
                "A Smart Courier Solution",
                "A Smart Parcel Solution",
                "A Smart Courier Service",
            ],
            typeSpeed: 150,
            backspaceSpeed: 80,
            backspaceDelay: 800,
            repeatDelay: 1000,
            repeat:true,
            autoStart:true,
            startDelay: 100,
        });    
    }

    /*********************************
    /*  Pricing Slider Carousel
    *********************************/
    if ($(".pricing__slider").length > 0) {
        var swiper = new Swiper(".pricing__slider", {
            direction: "horizontal",
            loop: true,
            grabCursor: true,
            slidesPerView: 3,
            spaceBetween: 24,
            speed: 500,
            centeredSlides: false,
            freeMode: false,
            autoplay: {
                enabled: true,
            },
            navigation: {
                nextEl: ".project-swipe-next",
                prevEl: ".project-swipe-prev",
            },
            
            breakpoints: {
                300: {
                    slidesPerView: 1,
                },
                479: {
                    slidesPerView: 1.5,
                },
                575: {
                    slidesPerView: 2,
                    spaceBetween: 20,
                },
                767: {
                    slidesPerView: 2,
                    spaceBetween: 20,
                },
                992: {
                    slidesPerView: 3,
                    spaceBetween: 20,
                },
                1199: {
                    slidesPerView: 3,
                },
            },
        });
    }

    /*********************************
    /*   Select Start
    *********************************/
    if ($(".form__dropdown").length > 0) {
        $(".form__dropdown").select2();
    }

    
    /**********************************
    /*  AOS animation
     **********************************/
    AOS.init();

    /**********************************
     *  Pasword Show Hide Toggle
     **********************************/
    $('.toggle-password').click(function(){
        $(this).toggleClass("fa-eye fa-eye-slash");
        let toggleShow = $(this).parent().find("input");
        if($(this).hasClass('fa-eye-slash')){
            toggleShow.attr('type', 'password');
        } else {
            toggleShow.attr('type', 'text');
        }
    });

    /**********************************
     *  Back to Top JS 
     **********************************/
    $('body').append('<div id="toTop" class="back__icon"><i class="fa-solid fa-chevron-up"></i></div>');
    $(window).on('scroll', function () {
        if ($(this).scrollTop() != 0) {
            $('#toTop').addClass('active');
        } else {
            $('#toTop').removeClass('active');
        }
    });
    $('#toTop').on('click', function () {
        $("html, body").animate({ scrollTop: 0 }, 0);
        return false;
    });

    
})(jQuery);
