// Slider de testimonios (template-parts/components/testimonios-slider.php). No avanza solo.
import Swiper from 'swiper';
import { opcionesBase } from './slider-base.js';

export function initTestimoniosSlider() {
    document.querySelectorAll('[data-testimonios-slider]').forEach((slider) => {
        const raiz = slider.closest('section') ?? slider.parentElement;
        const base = opcionesBase(raiz);

        new Swiper(slider, {
            ...base,
            slidesPerView: 1.1,
            spaceBetween: 16,
            watchSlidesProgress: true,
            // Como en el diseño: la primera tarjeta asoma a la izquierda.
            initialSlide: 1,
            pagination: {
                el: raiz.querySelector('[data-slider-puntos]'),
                clickable: true,
            },
            breakpoints: {
                768: { slidesPerView: 2, spaceBetween: 24 },
                1024: { slidesPerView: 3, spaceBetween: 24 },
            },
        });
    });
}
