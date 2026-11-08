/**
 * Pastillas de categorías del blog: en móvil la fila se desplaza en horizontal.
 * Al cargar, desplaza la fila (sin mover la página) para que la pastilla activa quede visible.
 */
export function initPastillas() {
    document.querySelectorAll('[data-pastillas]').forEach((fila) => {
        const activa = fila.querySelector('[aria-current="page"]');
        if (!activa || fila.scrollWidth <= fila.clientWidth) return;

        const item = activa.closest('li') ?? activa;
        const centro = item.offsetLeft - (fila.clientWidth - item.offsetWidth) / 2;
        fila.scrollLeft = Math.max(0, centro);
    });
}
