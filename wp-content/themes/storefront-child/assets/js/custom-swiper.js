document.addEventListener('DOMContentLoaded', function () {
    /*const swiper = new Swiper('.swiper-container', {
        loop: true,
        autoplay: {
            delay: 5000, // Adjust delay as needed
            disableOnInteraction: false,
        },
        pagination: {
            el: '.swiper-pagination',
            clickable: true,
        },
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
    });
*/

const swiper = new Swiper('.swiper-container', {
        loop: true,
        autoplay: {
            delay: 5000, // Adjust delay as needed
            disableOnInteraction: false,
        },
        pagination: {
            el: '.swiper-pagination',
            clickable: true,
        },
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
        on: {
            init: function () {
                const swiper = this;
                // Reverse autoplay direction
                setInterval(function () {
                    swiper.slidePrev();
                }, swiper.params.autoplay.delay);
            }
        }
    });
 
});
