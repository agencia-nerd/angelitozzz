/*
 * "Copiar código" (gracias/regalo-listo): copia el código al portapapeles y lo anuncia.
 * Marcado: un <button data-copiar-codigo="<código>"> con un <span data-copiar-texto> dentro, y una
 * región [data-copiar-aviso] con aria-live="polite" en la misma tarjeta ([data-copiar-raiz]).
 * El aviso "Código copiado" viene traducido en data-copiado del botón.
 * Sin la API del portapapeles (sitio sin https, como el Local, o navegador antiguo) copia con el método
 * clásico (un campo oculto y execCommand). Si nada funciona, el código se puede seleccionar a mano.
 */

const ESPERA = 2000;

async function copiar(texto) {
    if (navigator.clipboard && window.isSecureContext) {
        try {
            await navigator.clipboard.writeText(texto);
            return true;
        } catch {
            // Sigue con el método clásico.
        }
    }
    const campo = document.createElement('textarea');
    campo.value = texto;
    campo.setAttribute('readonly', '');
    campo.style.position = 'fixed';
    campo.style.opacity = '0';
    document.body.append(campo);
    campo.select();
    let copiado = false;
    try {
        copiado = document.execCommand('copy');
    } catch {
        copiado = false;
    }
    campo.remove();
    return copiado;
}

export function initCopiarCodigo() {
    for (const boton of document.querySelectorAll('[data-copiar-codigo]')) {
        const raiz = boton.closest('[data-copiar-raiz]') ?? document;
        const texto = boton.querySelector('[data-copiar-texto]');
        const aviso = raiz.querySelector('[data-copiar-aviso]');
        const original = texto?.textContent ?? '';
        let temporizador = 0;

        boton.addEventListener('click', async () => {
            const codigo = boton.dataset.copiarCodigo ?? '';
            if (!codigo || !(await copiar(codigo))) return;

            const copiado = boton.dataset.copiado || 'Código copiado';
            if (texto) texto.textContent = copiado;
            if (aviso) aviso.textContent = copiado;

            window.clearTimeout(temporizador);
            temporizador = window.setTimeout(() => {
                if (texto) texto.textContent = original;
                if (aviso) aviso.textContent = '';
            }, ESPERA);
        });
    }
}
