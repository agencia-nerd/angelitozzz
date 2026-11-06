// Carrusel de tarjetas de cursos (home/como-ayudarte). No avanza solo; sin flechas si todo cabe.
import Swiper from 'swiper';
import { opcionesBase } from './slider-base.js';

export function initCarruselTarjetas() {
    document.querySelectorAll('[data-carrusel-tarjetas]').forEach((raiz) => {
        const slider = raiz.querySelector('.swiper');
        if (!slider) return;

        new Swiper(slider, {
            ...opcionesBase(raiz),
            slidesPerView: 1.15,
            spaceBetween: 16,
            breakpoints: {
                768: { slidesPerView: 2, spaceBetween: 32 },
                1024: { slidesPerView: 3, spaceBetween: 32 },
                1280: { slidesPerView: 4, spaceBetween: 32 },
            },
        });
    });
}
