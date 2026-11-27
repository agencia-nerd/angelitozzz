// Testimonios en cinta continua e infinita (template-parts/components/testimonios-slider.php).
// Clona las tarjetas hasta cubrir la ventana, anima la pista por CSS (input.css, .testimonios-cinta)
// y se pausa al pasar el mouse o con el botón. Con movimiento reducido queda la fila con scroll.
import { sinMovimiento } from './slider-base.js';

const VELOCIDAD = 45; // px por segundo

export function initTestimoniosSlider() {
    if (sinMovimiento()) {
        return;
    }

    document.querySelectorAll('[data-testimonios-cinta]').forEach((cinta) => {
        const pista = cinta.querySelector('[data-cinta-pista]');
        const originales = pista ? [...pista.children] : [];
        if (!originales.length) {
            return;
        }

        const raiz = cinta.closest('section') ?? cinta.parentElement;
        const boton = raiz.querySelector('[data-cinta-pausa]');
        let encima = false;
        let pausada = false;
        let ancho = 0;

        const actualizarPausa = () => cinta.toggleAttribute('data-pausado', encima || pausada);

        const armar = () => {
            pista.querySelectorAll('[data-cinta-clon]').forEach((clon) => clon.remove());

            const gap = parseFloat(getComputedStyle(pista).columnGap) || 0;
            const desplazamiento = pista.scrollWidth + gap;
            const copias = Math.ceil((window.innerWidth + desplazamiento) / desplazamiento);

            for (let i = 1; i < copias; i++) {
                originales.forEach((item) => {
                    const clon = item.cloneNode(true);
                    clon.setAttribute('data-cinta-clon', '');
                    clon.setAttribute('aria-hidden', 'true');
                    clon.inert = true;
                    pista.append(clon);
                });
            }

            cinta.style.setProperty('--cinta-desplazamiento', `${desplazamiento}px`);
            cinta.style.setProperty('--cinta-duracion', `${(desplazamiento / VELOCIDAD).toFixed(2)}s`);
        };

        cinta.setAttribute('data-cinta-activa', '');
        armar();
        ancho = cinta.clientWidth;

        new ResizeObserver(() => {
            if (cinta.clientWidth !== ancho) {
                ancho = cinta.clientWidth;
                armar();
            }
        }).observe(cinta);

        cinta.addEventListener('mouseenter', () => {
            encima = true;
            actualizarPausa();
        });
        cinta.addEventListener('mouseleave', () => {
            encima = false;
            actualizarPausa();
        });

        if (boton) {
            boton.hidden = false;
            boton.addEventListener('click', () => {
                pausada = !pausada;
                boton.setAttribute('aria-pressed', String(pausada));
                actualizarPausa();
            });
        }
    });
}
