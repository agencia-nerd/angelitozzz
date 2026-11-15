/*
 * Botón ojo de los campos de contraseña (template-parts/components/campo-acceso.php): muestra u oculta
 * lo escrito. El botón sale oculto en el HTML y aparece solo con JS (sin JS no serviría de nada).
 * aria-pressed dice si la contraseña está visible; el nombre accesible es siempre "Mostrar contraseña".
 * Antes de enviar el formulario la contraseña vuelve a ocultarse, para que el navegador no la guarde
 * como texto.
 */

export function initVerContrasena() {
    for (const caja of document.querySelectorAll('[data-ver-contrasena]')) {
        const boton = caja.querySelector('[data-ver-contrasena-boton]');
        const campo = caja.querySelector('input');
        if (!boton || !campo) continue;

        const poner = (visible) => {
            campo.type = visible ? 'text' : 'password';
            boton.setAttribute('aria-pressed', visible ? 'true' : 'false');
        };

        boton.hidden = false;
        boton.addEventListener('click', () => poner(boton.getAttribute('aria-pressed') !== 'true'));
        campo.form?.addEventListener('submit', () => poner(false));
    }
}
