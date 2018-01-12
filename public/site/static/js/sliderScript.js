$(function() {
    $('.jcarousel')
        .jcarousel({
            visible: 12
        })
        .jcarouselAutoscroll({
            interval: 3000,
            target: '+=1',
            autostart: true,
        })
    ;
});
$(function() {
    $('#slider-on-home').slidesjs({
        width: 940,
        height: 528,
        navigation: {
            effect: "fade"
        },
        pagination: {
            effect: "fade"
        },
        effect: {
            fade: {
                speed: 800
            }
        },
        play: {
            effect: "fade",
            // [string] Can be either "slide" or "fade".
            interval: 2500,
            // [number] Time spent on each slide in milliseconds.
            auto: true,
            restartDelay: 2500
            // [number] restart delay on inactive slideshow
        }

    });
});

$(function(){
    var topPos = $('.floating').offset().top; //topPos - это значение от верха блока до окна браузера
    $(window).scroll(function() {
        var top = $(document).scrollTop();
        if (top > topPos) $('.floating').addClass('fixed');
        else $('.floating').removeClass('fixed');
    });
});

$(function(){
    var topPos = $('header').offset().top; //topPos - это значение от верха блока до окна браузера
    $(window).scroll(function() {
        var top = $(document).scrollTop();
        if (top > topPos) $('header').addClass('height');
        else $('header').removeClass('height');
    });
});

$(function () {
    $('.single').pickmeup({
        flat	: true
    });
    $('.multiple').pickmeup({
        flat	: true,
        mode	: 'multiple'
    });
    $('.range').pickmeup({
        flat	: true,
        mode	: 'range'
    });
    var plus_5_days	= new Date;
    plus_5_days.addDays(5);
    $('.3-calendars').pickmeup({
        flat		: true,
        date		: [
            new Date,
            plus_5_days
        ],
        mode		: 'range',
        calendars	: 3
    });
});