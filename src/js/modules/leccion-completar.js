/*
 * "Marcar como completo" de la lección (template-parts/pages/leccion/cabecera.php). Llama a la ruta REST
 * del plugin (POST marca, DELETE desmarca) con el nonce wp_rest y, con la respuesta, actualiza sin
 * recargar el botón (aria-pressed), el check de la lección en las dos copias del temario y su texto para
 * lectores, y anuncia el avance del curso (aria-live). Si falla, vuelve al estado anterior y muestra el
 * error (role="alert"). El botón sale oculto en el HTML: sin JS no aparece. Se activa con
 * [data-leccion-completar].
 */

export function initLeccionCompletar() {
    for (const boton of document.querySelectorAll('button[data-leccion-completar]')) {
        const raiz = boton.closest('[data-leccion-completar-raiz]') ?? boton.parentElement;
        const aviso = raiz.querySelector('[data-leccion-completar-aviso]');
        const error = raiz.querySelector('[data-leccion-completar-error]');
        const id = boton.dataset.leccionCompletar;

        const pintar = (completada) => {
            boton.setAttribute('aria-pressed', completada ? 'true' : 'false');
            document.querySelectorAll(`[data-leccion-estado="${id}"]`).forEach((estado) => {
                estado.dataset.completada = completada ? 'true' : 'false';
            });
            document.querySelectorAll(`[data-leccion-estado-texto="${id}"]`).forEach((texto) => {
                texto.textContent = completada ? '(completada)' : '(pendiente)';
            });
        };

        boton.hidden = false;

        boton.addEventListener('click', async () => {
            const antes = boton.getAttribute('aria-pressed') === 'true';
            const marcar = !antes;

            error.hidden = true;
            error.textContent = '';
            boton.disabled = true;
            pintar(marcar);

            try {
                const respuesta = await fetch(boton.dataset.url, {
                    method: marcar ? 'POST' : 'DELETE',
                    credentials: 'same-origin',
                    headers: { 'X-WP-Nonce': boton.dataset.nonce },
                });
                const datos = await respuesta.json().catch(() => ({}));
                if (!respuesta.ok) throw new Error(datos.message || 'No se pudo guardar el avance.');

                pintar(Boolean(datos.completada));
                aviso.textContent = `${datos.completada ? 'Lección completada.' : 'Lección marcada como pendiente.'} Avance del curso: ${datos.porcentaje}% (${datos.completadas} de ${datos.total} lecciones).`;
            } catch (e) {
                pintar(antes);
                error.textContent = e.message || 'No se pudo guardar el avance. Inténtalo de nuevo.';
                error.hidden = false;
            } finally {
                boton.disabled = false;
            }
        });
    }
}
