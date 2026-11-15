// Testimonio destacado (template-parts/components/testimonio-destacado.php): uno a la vez, no avanza solo.
// Con un solo testimonio la plantilla no marca el slider y no hay nada que iniciar.
import Swiper from 'swiper';
import { opcionesBase } from './slider-base.js';

export function initTestimonioDestacado() {
    document.querySelectorAll('[data-testimonio-destacado-slider]').forEach((slider) => {
        const raiz = slider.closest('[data-testimonio-destacado]') ?? slider.parentElement;
        const base = opcionesBase(raiz);

        new Swiper(slider, {
            ...base,
            slidesPerView: 1,
            spaceBetween: 24,
            autoHeight: true,
            pagination: {
                el: raiz.querySelector('[data-slider-puntos]'),
                clickable: true,
            },
            a11y: {
                ...base.a11y,
                firstSlideMessage: 'Primer testimonio',
                lastSlideMessage: 'Último testimonio',
                paginationBulletMessage: 'Ir al testimonio {{index}}',
            },
        });
    });
}
