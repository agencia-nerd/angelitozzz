/*
 * Reglas de contraseña (template-parts/components/reglas-contrasena.php): marca cada regla al escribir
 * en el campo cuyo id está en [data-reglas-contrasena]. Las reglas de la lista las da el plugin
 * angelitozzz-cursos (angelitozzz_cursos_reglas_contrasena(), que las valida en el servidor): esto solo
 * ayuda a la usuaria mientras escribe. Las comprobaciones de abajo copian las del plugin; una regla
 * nueva sin comprobación aquí se muestra como pendiente y no se marca.
 * La lista tiene aria-live="polite": el lector anuncia el cambio de estado ("cumplida" / "pendiente").
 */

const reglas = {
    largo: (valor) => [...valor].length >= 8, // Cuenta caracteres como mb_strlen del plugin (un emoji es 1).
    minuscula: (valor) => /\p{Ll}/u.test(valor),
    numero: (valor) => /\d/.test(valor),
    mayuscula: (valor) => /\p{Lu}/u.test(valor),
};

function pintar(raiz, valor) {
    for (const item of raiz.querySelectorAll('[data-regla]')) {
        const comprobar = reglas[item.dataset.regla];
        if (!comprobar) continue;

        const cumplida = comprobar(valor);
        if (item.dataset.cumplida === String(cumplida)) continue; // Sin cambio: no se vuelve a anunciar.

        item.dataset.cumplida = String(cumplida);
        item.querySelector('[data-icono="cumplida"]')?.classList.toggle('hidden', !cumplida);
        item.querySelector('[data-icono="pendiente"]')?.classList.toggle('hidden', cumplida);

        const estado = item.querySelector('[data-estado]');
        if (estado) {
            estado.textContent = `(${cumplida ? estado.dataset.textoCumplida : estado.dataset.textoPendiente})`;
        }
    }
}

export function initReglasContrasena() {
    for (const raiz of document.querySelectorAll('[data-reglas-contrasena]')) {
        const campo = document.getElementById(raiz.dataset.reglasContrasena);
        if (!campo) continue;

        // El campo apunta a la lista para que el lector la lea al entrar.
        const lista = raiz.querySelector('ul[id]');
        if (lista) {
            const descritos = new Set((campo.getAttribute('aria-describedby') || '').split(/\s+/).filter(Boolean));
            descritos.add(lista.id);
            campo.setAttribute('aria-describedby', [...descritos].join(' '));
        }

        campo.addEventListener('input', () => pintar(raiz, campo.value));
        pintar(raiz, campo.value);
    }
}
