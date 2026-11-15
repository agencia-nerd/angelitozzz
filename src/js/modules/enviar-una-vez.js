/*
 * Formularios que se envían una sola vez (canjear regalo y, después, los de acceso): al enviar,
 * desactiva el botón para que un doble clic no mande dos veces. Se activa con
 * <form data-enviar-una-vez>. Si el navegador bloquea el envío por un campo inválido, el evento
 * submit no ocurre y el botón sigue activo. Al volver con el botón "atrás" (bfcache) se reactiva.
 */

export function initEnviarUnaVez() {
    for (const form of document.querySelectorAll('form[data-enviar-una-vez]')) {
        const botones = () => form.querySelectorAll('button[type="submit"], input[type="submit"]');

        form.addEventListener('submit', () => {
            for (const boton of botones()) {
                boton.disabled = true;
                boton.setAttribute('aria-disabled', 'true');
            }
        });

        window.addEventListener('pageshow', (evento) => {
            if (!evento.persisted) return;
            for (const boton of botones()) {
                boton.disabled = false;
                boton.removeAttribute('aria-disabled');
            }
        });
    }
}
