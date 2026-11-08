/**
 * Pastillas de categorías del blog: en móvil la fila se desplaza en horizontal.
 * Al cargar y cada vez que wp-query cambia la vista sin recargar (evento wp-query:update), desplaza la fila
 * (sin mover la página) para que la pastilla activa quede visible. Si el foco estaba en una pastilla, pasa
 * a la nueva activa: la fila se reemplaza entera y el teclado no pierde su lugar.
 */
function centrarActiva({ enfocar = false } = {}) {
    document.querySelectorAll('[data-pastillas]').forEach((fila) => {
        const activa = fila.querySelector('[aria-current="page"]');
        if (!activa) return;

        if (enfocar) activa.focus({ preventScroll: true });
        if (fila.scrollWidth <= fila.clientWidth) return;

        const item = activa.closest('li') ?? activa;
        const centro = item.offsetLeft - (fila.clientWidth - item.offsetWidth) / 2;
        fila.scrollLeft = Math.max(0, centro);
    });
}

export function initPastillas() {
    centrarActiva();

    let focoEnPastillas = false;
    document.addEventListener('click', (event) => {
        focoEnPastillas = Boolean(event.target.closest?.('[data-pastillas] a'));
    });
    document.addEventListener('wp-query:update', () => {
        centrarActiva({ enfocar: focoEnPastillas });
        focoEnPastillas = false;
    });
}
