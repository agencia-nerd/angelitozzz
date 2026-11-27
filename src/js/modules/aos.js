// AOS: animaciones al hacer scroll con data-aos="fade-up" (y data-aos-delay, etc.).
// Se carga solo en páginas con [data-aos]; su CSS viaja con este chunk, así que sin JS
// todo se ve normal. No lo pongas en el hero ni en un elemento que ya anime su transform
// (colgantes, cinta de testimonios, Swiper): va en un contenedor de afuera.
import AOS from 'aos';
import 'aos/dist/aos.css';

export function initAos() {
    AOS.init({
        once: false,
        duration: 600,
        easing: 'ease-out',
        offset: 80,
        // Con "reducir movimiento" AOS quita los data-aos y todo queda visible y quieto.
        disable: () => window.matchMedia('(prefers-reduced-motion: reduce)').matches,
    });
}
