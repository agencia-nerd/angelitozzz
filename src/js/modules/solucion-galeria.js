// Galería de una solución (template-parts/pages/solucion/galeria.php): una diapositiva a la vez, flechas
// debajo, no avanza sola. Al cambiar de diapositiva se detiene el video que estuviera sonando.
import Swiper from 'swiper';
import { opcionesBase } from './slider-base.js';
import { detenerVideos } from './video-portada.js';

export function initSolucionGaleria() {
    document.querySelectorAll('[data-solucion-galeria]').forEach((raiz) => {
        const slider = raiz.querySelector('.swiper');
        if (!slider || slider.querySelectorAll('.swiper-slide').length < 2) return;

        const base = opcionesBase(raiz);

        new Swiper(slider, {
            ...base,
            slidesPerView: 1,
            spaceBetween: 16,
            a11y: {
                ...base.a11y,
                firstSlideMessage: 'Primera diapositiva',
                lastSlideMessage: 'Última diapositiva',
            },
            on: {
                slideChange: () => detenerVideos(slider),
            },
        });
    });
}
