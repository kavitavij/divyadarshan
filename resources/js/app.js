import './bootstrap';
import '../css/app.css';

// ✅ Swiper styles and JS
import 'swiper/css';
import 'swiper/css/navigation';
import 'swiper/css/pagination';

import Swiper from 'swiper/bundle';

// ✅ Swiper initialization
document.addEventListener('DOMContentLoaded', function () {
    new Swiper('.mySwiper', {
        loop: true,
        spaceBetween: 30,
        centeredSlides: true,
        autoplay: {
            delay: 2500,
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
});
