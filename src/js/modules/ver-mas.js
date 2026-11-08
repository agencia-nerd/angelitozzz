/*
 * "Ver más" por lotes: cada clic muestra el siguiente grupo oculto ([data-ver-mas-grupo]) y lleva el
 * foco a su primera pregunta. Al mostrar el último grupo, el botón desaparece. Todo el contenido ya
 * está en el HTML del servidor; aquí solo se quita `hidden`.
 */

export function initVerMas() {
    for (const boton of document.querySelectorAll('[data-ver-mas]')) {
        const raiz = boton.closest('[data-ver-mas-root]');
        if (!raiz) continue;

        boton.addEventListener('click', () => {
            const grupo = raiz.querySelector('[data-ver-mas-grupo][hidden]');
            if (!grupo) {
                boton.hidden = true;
                return;
            }

            grupo.hidden = false;
            grupo.querySelector('summary')?.focus();

            const siguiente = raiz.querySelector('[data-ver-mas-grupo][hidden]');
            if (siguiente) {
                boton.setAttribute('aria-controls', siguiente.id);
            } else {
                boton.hidden = true;
            }
        });
    }
}
