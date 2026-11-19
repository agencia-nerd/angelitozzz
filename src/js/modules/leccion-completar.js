/*
 * "Marcar como completo" de la lección (template-parts/pages/leccion/cabecera.php). Sin JS el botón envía
 * un formulario a la acción angelitozzz_completar_leccion del plugin (marca o desmarca y vuelve). Con JS se
 * cancela ese envío y se llama a la ruta REST del plugin (POST marca, DELETE desmarca) con el nonce
 * wp_rest; con la respuesta se actualiza sin recargar el botón (aria-pressed), el check de la lección en
 * las dos copias del temario y su texto para lectores, y se anuncia el avance del curso (aria-live). Si
 * falla, vuelve al estado anterior y muestra el error (role="alert"). Los textos vienen traducidos en
 * data-* del botón.
 */

const formatear = (plantilla, ...valores) =>
    valores.reduce((texto, valor, i) => texto.replace(`%${i + 1}$s`, String(valor)), plantilla).replace('%%', '%');

export function initLeccionCompletar() {
    for (const boton of document.querySelectorAll('button[data-leccion-completar]')) {
        const raiz = boton.closest('[data-leccion-completar-raiz]') ?? boton.parentElement;
        const aviso = raiz.querySelector('[data-leccion-completar-aviso]');
        const error = raiz.querySelector('[data-leccion-completar-error]');
        const marcarCampo = raiz.querySelector('[data-leccion-completar-marcar]');
        const id = boton.dataset.leccionCompletar;
        const t = boton.dataset;

        const pintar = (completada) => {
            boton.setAttribute('aria-pressed', completada ? 'true' : 'false');
            if (marcarCampo) marcarCampo.value = completada ? '0' : '1';
            document.querySelectorAll(`[data-leccion-estado="${id}"]`).forEach((estado) => {
                estado.dataset.completada = completada ? 'true' : 'false';
            });
            document.querySelectorAll(`[data-leccion-estado-texto="${id}"]`).forEach((texto) => {
                texto.textContent = completada ? t.textoCompletada : t.textoPendiente;
            });
        };

        raiz.addEventListener('submit', async (evento) => {
            evento.preventDefault();
            if (boton.disabled) return;
            const antes = boton.getAttribute('aria-pressed') === 'true';
            const marcar = !antes;

            error.hidden = true;
            error.textContent = '';
            boton.disabled = true;
            pintar(marcar);

            try {
                const respuesta = await fetch(t.url, {
                    method: marcar ? 'POST' : 'DELETE',
                    credentials: 'same-origin',
                    headers: { 'X-WP-Nonce': t.nonce },
                });
                const datos = await respuesta.json().catch(() => ({}));
                if (!respuesta.ok) throw new Error(datos.message || t.error);

                pintar(Boolean(datos.completada));
                aviso.textContent = `${datos.completada ? t.avisoCompletada : t.avisoPendiente} ${formatear(t.avisoAvance, datos.porcentaje, datos.completadas, datos.total)}`;
            } catch (e) {
                pintar(antes);
                error.textContent = e.message || t.error;
                error.hidden = false;
            } finally {
                boton.disabled = false;
            }
        });
    }
}
