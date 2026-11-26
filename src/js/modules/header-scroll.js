// Header fijo (header.php): se oculta al bajar y aparece al subir. Sin JS queda siempre visible.
// No se oculta cerca del tope, con el menú móvil abierto ni con el foco dentro del header.
export function initHeaderScroll() {
    const header = document.querySelector('[data-header]');
    if (!header) return;

    const boton = header.querySelector('[data-menu-toggle]');
    const MARGEN = 8; // px de scroll hacia arriba para volver a mostrarlo (evita temblores del trackpad o del rebote de iOS).
    let ultimaY = window.scrollY;
    let pendiente = false;

    const ocultar = (oculto) => header.toggleAttribute('data-oculto', oculto);

    const revisar = () => {
        pendiente = false;
        const y = Math.max(window.scrollY, 0);
        const fijo = boton?.getAttribute('aria-expanded') === 'true' || header.querySelector(':focus-visible') !== null;

        if (fijo || y <= header.offsetHeight * 2) {
            ocultar(false);
        } else if (y > ultimaY) {
            ocultar(true);
        } else if (ultimaY - y > MARGEN) {
            ocultar(false);
        } else {
            return; // Subida mínima: se espera a que pase el margen sin mover la referencia.
        }
        ultimaY = y;
    };

    window.addEventListener('scroll', () => {
        if (!pendiente) {
            pendiente = true;
            requestAnimationFrame(revisar);
        }
    }, { passive: true });

    // Con Tab se muestra siempre (solo el foco de teclado: un clic con el mouse no lo deja fijo).
    header.addEventListener('focusin', () => {
        if (header.querySelector(':focus-visible')) ocultar(false);
    });
}
