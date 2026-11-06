// Opciones comunes de los sliders del tema (Swiper): sin autoplay, con teclado y accesibles.
import { Navigation, Pagination, Keyboard, A11y } from 'swiper/modules';

export const modulos = [Navigation, Pagination, Keyboard, A11y];

export const sinMovimiento = () => window.matchMedia('(prefers-reduced-motion: reduce)').matches;

export function opcionesBase(raiz) {
    return {
        modules: modulos,
        speed: sinMovimiento() ? 0 : 400,
        watchOverflow: true,
        keyboard: { enabled: true, onlyInViewport: true },
        navigation: {
            prevEl: [...raiz.querySelectorAll('[data-slider-anterior]')],
            nextEl: [...raiz.querySelectorAll('[data-slider-siguiente]')],
            disabledClass: 'opacity-40',
            lockClass: 'invisible',
        },
        a11y: {
            prevSlideMessage: 'Anterior',
            nextSlideMessage: 'Siguiente',
            firstSlideMessage: 'Primera tarjeta',
            lastSlideMessage: 'Última tarjeta',
            paginationBulletMessage: 'Ir a la tarjeta {{index}}',
            slideLabelMessage: '{{index}} de {{slidesLength}}',
        },
    };
}
