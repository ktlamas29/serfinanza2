$(document).ready(function () {
    //Variables
    const btnMoreMenu = $('#show-submenu');
    const btnMoreMenuMobile = $('#show-submenu-mobile');
    const subMenu = $('#submenu-mobile');
    const subMenuDesktop = $('.submenu-menu-desk');
    const btnFaq = $('#accordion-faqs button');
    new WOW().init();
    //init slider mobile
    const widthScreen = $(document).width();
    console.log(widthScreen);
    if (widthScreen < 600) {
        $('#slideMecanica').slick({
            dots: true,
            infinite: false,
            slidesToShow: 1,
            slidesToScroll: 1,
            autoplay: true,
            arrows: false,
            autoplaySpeed: 2000,
            prevArrow: "<button type='button' class='slick-prev pull-left'><img src='/assets/icons/arrow-left.svg' ></button>",
            nextArrow: "<button type='button' class='slick-next pull-right'><img src='/assets/icons/arrow-right.svg' ></button>",
        });
    }
    $('#slideDiscountsProgress').slick({
        dots: true,
        infinite: false,
        slidesToShow: 3,
        slidesToScroll: 1,
        autoplay: true,
        arrows: true,
        autoplaySpeed: 2000,
        prevArrow: "<button type='button' class='slick-prev pull-left'><img src='/assets/icons/flechaIzq.svg' ></button>",
        nextArrow: "<button type='button' class='slick-next pull-right'><img src='/assets/icons/flechaDer.svg' ></button>",
        responsive: [
            {
                breakpoint: 1024,
                settings: {
                    slidesToShow: 3,
                    slidesToScroll: 3,
                    infinite: true,
                    dots: true
                }
            },
            {
                breakpoint: 600,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 2
                }
            },
            {
                breakpoint: 480,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1
                }
            }
            // You can unslick at a given breakpoint now by adding:
            // settings: "unslick"
            // instead of a settings object
        ]
    });

    btnFaq.on('click', function (e) {
        if (!$(this).hasClass('active')) {
            $('#accordion-faqs button').removeClass('active');
            $(this).addClass('active');
        } else {
            $(this).removeClass('active');
        }
    });

    btnMoreMenu.on('click', function (e) {
        console.log('entro');
        event.preventDefault();
        event.stopPropagation();
        subMenuDesktop.toggleClass('active');
        btnMoreMenu.toggleClass('item-active');
    });
    btnMoreMenuMobile.click((event) => {
        console.log('entro');
        event.preventDefault();
        event.stopPropagation();
        subMenu.toggleClass('show-menu-mobile');
        btnMoreMenuMobile.toggleClass('item-active');
    })

    $(window).click(() => {
        subMenu.removeClass('show-menu-mobile');
        btnMoreMenuMobile.removeClass('item-active');
    })

    $('#btn-download-bono').on('click', function (e) {

        window.dataLayer.push({
            event: 'descarga_bono',
            campaign: 'ScotiaBank 2',
            brand: $('.award.winner.download .name').text(),
            price: $('.price-bono').text(),
        });

    })
});