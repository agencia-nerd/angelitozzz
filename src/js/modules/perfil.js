/*
 * Cuenta · Mi perfil (template-parts/pages/cuenta/perfil.php), sobre [data-perfil]:
 * - "Contraseña actual" de Datos personales solo se ve si el correo cambia (sin JS se ve siempre).
 * - "Guardar contraseña" se activa cuando la nueva cumple las 4 reglas (las marca reglas-contrasena.js;
 *   el servidor las vuelve a validar).
 * - Al cambiar de pestaña se anota ?pestana= en la URL, para que el envío del formulario vuelva a ella.
 */

export function initPerfil() {
    const raiz = document.querySelector('[data-perfil]');
    if (!raiz) return;

    // Contraseña actual al cambiar el correo.
    const cajaCorreo = raiz.querySelector('[data-perfil-correo]');
    const correo = cajaCorreo?.querySelector('input[type="email"]');
    const cajaClave = raiz.querySelector('[data-perfil-clave-actual-caja]');
    if (correo && cajaClave) {
        const original = (cajaCorreo.dataset.perfilCorreo || '').trim().toLowerCase();
        const claveActual = cajaClave.querySelector('input');
        const revisar = () => {
            const cambia = correo.value.trim().toLowerCase() !== original;
            cajaClave.hidden = !cambia;
            if (claveActual) claveActual.required = cambia;
        };
        correo.addEventListener('input', revisar);
        revisar();
    }

    // Guardar contraseña solo con las 4 reglas cumplidas.
    const seguridad = raiz.querySelector('[data-perfil-seguridad]');
    const guardar = seguridad?.querySelector('[data-perfil-guardar-clave]');
    const nueva = seguridad?.querySelector('input[name="clave"]');
    const reglas = seguridad?.querySelector('[data-reglas-contrasena]');
    if (guardar && nueva && reglas) {
        const revisar = () => {
            // reglas-contrasena.js marca cada regla en su propio listener de "input": se lee después.
            requestAnimationFrame(() => {
                const pendientes = reglas.querySelectorAll('[data-regla][data-cumplida="false"]').length;
                guardar.disabled = pendientes > 0;
            });
        };
        nueva.addEventListener('input', revisar);
        revisar();
    }

    // La pestaña abierta queda en la URL (sin recargar).
    // Con clic o con las flechas (pestanas.js cambia aria-selected): se lee la pestaña elegida después.
    const anotar = (evento) => {
        if (!evento.target.closest('[role="tab"]')) return;
        requestAnimationFrame(() => {
            const tab = raiz.querySelector('[role="tab"][aria-selected="true"]');
            const id = (tab?.getAttribute('aria-controls') || '').replace(/-panel$/, '');
            if (!id) return;
            const url = new URL(location.href);
            url.searchParams.set('pestana', id);
            url.hash = '';
            history.replaceState(null, '', url);
        });
    };
    raiz.addEventListener('click', anotar);
    raiz.addEventListener('keyup', anotar);
}
