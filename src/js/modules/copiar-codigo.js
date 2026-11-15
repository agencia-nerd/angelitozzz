/*
 * "Copiar código" (gracias/regalo-listo): copia el código al portapapeles y lo anuncia.
 * Marcado: un <button data-copiar-codigo="<código>"> con un <span data-copiar-texto> dentro, y una
 * región [data-copiar-aviso] con aria-live="polite" en la misma tarjeta ([data-copiar-raiz]).
 * Sin permiso de portapapeles el código se puede seleccionar a mano (está en el HTML).
 */

const ESPERA = 2000;

export function initCopiarCodigo() {
    for (const boton of document.querySelectorAll('[data-copiar-codigo]')) {
        const raiz = boton.closest('[data-copiar-raiz]') ?? document;
        const texto = boton.querySelector('[data-copiar-texto]');
        const aviso = raiz.querySelector('[data-copiar-aviso]');
        const original = texto?.textContent ?? '';
        let temporizador = 0;

        boton.addEventListener('click', async () => {
            const codigo = boton.dataset.copiarCodigo ?? '';
            if (!codigo || !navigator.clipboard) return;

            try {
                await navigator.clipboard.writeText(codigo);
            } catch {
                return;
            }

            if (texto) texto.textContent = 'Código copiado';
            if (aviso) aviso.textContent = 'Código copiado';

            window.clearTimeout(temporizador);
            temporizador = window.setTimeout(() => {
                if (texto) texto.textContent = original;
                if (aviso) aviso.textContent = '';
            }, ESPERA);
        });
    }
}
